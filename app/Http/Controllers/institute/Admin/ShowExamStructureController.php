<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\ExamStructureOfflineExam;
use App\Models\ExamInstruction;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\Course;
use App\Models\Subject;
use App\Models\CourseFeeStructure;
use App\Models\User;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamOfflineExport;

class ShowExamStructureController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    
    // In GoogleCalendarController.php (or create a new ExamManagementController)
public function examManagement()
{
    $context = $this->getInstituteBranchContext();
    if (!$context['institute_id']) {
        return redirect()->back()->with('error', 'You are not associated with any institute.');
    }
    
    $institute_id = $context['institute_id'];
    $branch_id = $context['is_branch_admin'] ? $context['branch_id'] : null;
    
    // Get department categories for filters
    $categories = DepartmentCategory::whereHas('departments', function ($query) use ($institute_id, $branch_id) {
    $query->where('institute_id', $institute_id)
          ->when($branch_id, function ($q) use ($branch_id) {
              $q->where('branch_id', $branch_id);
          }, function ($q) {
              // 👇 When branch_id is NULL
              $q->whereNull('branch_id');
          });
    })
    ->with(['departments' => function ($query) use ($institute_id, $branch_id) {
        $query->where('institute_id', $institute_id)
            ->when($branch_id, function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            }, function ($q) {
                $q->whereNull('branch_id');
            });
    }])
    ->get();


    return view('instituteAdmin.ExamStructure.viewexammanagement', compact('categories'));
}

public function getExamsData(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json(['success' => false, 'message' => 'No institute found'], 401);
        }

        $institute_id = $context['institute_id'];
        $branch_id = $context['is_branch_admin'] ? $context['branch_id'] : null;

        // Build query
        $query = ExamStructureOfflineExam::with([
            'department',
            'course',
            'subject',
            'departmentCategory',
            'instructions',
        ])
            ->where('institute_id', $institute_id)
            ->when($branch_id, function ($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });

        // Apply filters
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('exam_name', 'like', "%{$search}%")
                    ->orWhere('exam_id', 'like', "%{$search}%")
                    ->orWhereHas('subject', function ($q) use ($search) {
                        $q->where('subject_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('department', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('exam_name')) {
            $query->where('exam_name', 'LIKE', '%' . $request->exam_name . '%');
        }

        if ($request->filled('subject')) {
            $query->whereHas('subject', function ($q) use ($request) {
                $q->where('subject_name', 'like', '%' . $request->subject . '%');
            });
        }

        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('department_category_id', $request->category_id);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->where('exam_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->where('exam_date', '<=', $request->date_to);
        }

        // Duration (minutes)
        if ($request->filled('duration')) {
            $query->where('duration_minutes', $request->duration);
        }

        // Classroom
        if ($request->filled('classroom')) {
            $query->where('classroom_id', 'like', '%' . $request->classroom . '%');
        }

        // Get total count
        $total = $query->count();

        $exams = $query->orderBy('exam_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($request->per_page ?? 20);

        // ✅ transform paginator items directly
        $exams->getCollection()->transform(function ($exam) {
            $exam->section_names = $this->getSectionNamesForExam($exam);
            return $exam;
        });
        $formattedExams = $exams->items();
        return response()->json([
            'success' => true,
            'exams' => $formattedExams,
            'total' => $total,
            'current_page' => $exams->currentPage(),
            'per_page' => $exams->perPage(),
            'last_page' => $exams->lastPage()
        ]);
    }

public function getExamDetails($id)
{
    $context = $this->getInstituteBranchContext();

    $exam = ExamStructureOfflineExam::with([
        'department',
        'course',
        'subject',
        'departmentCategory',
        'instructions',
    ])->findOrFail($id);

    $sectionIds = array_map('trim', explode(',', $exam->section_id));

  $sectionsJson = CourseFeeStructure::where('product_id', $exam->subtype_id)
    ->where('institute_id', $context['institute_id'])
    ->when($context['is_branch_admin'],
        fn ($q) => $q->where('branch_id', $context['branch_id']),
        fn ($q) => $q->whereNull('branch_id')
    )
    ->value('sections');


    $sectionNames = $this->resolveSectionNames($sectionsJson, $sectionIds);

    return response()->json([
        'success' => true,
        'exam' => array_merge($exam->toArray(), [
            'exam_date' => $exam->exam_date
            ? Carbon::parse($exam->exam_date)->format('Y-m-d')
            : null,
            'section_names' => $sectionNames, // ✅ VIEW
            'section_id'    => $exam->section_id, // ✅ EDIT
        ])
    ]);
}


public function updateExam(Request $request, $id)
{
    try {

        /*
        |--------------------------------------------------------------------------
        | 1. Get Exam
        |--------------------------------------------------------------------------
        */

        $exam = ExamStructureOfflineExam::with('instructions')
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | 2. Request Data
        |--------------------------------------------------------------------------
        */

        $requestData = $request->all();

        $isPublishedChanged = false;

        /*
        |--------------------------------------------------------------------------
        | 3. Convert is_published to boolean
        |--------------------------------------------------------------------------
        */

        if (array_key_exists('is_published', $requestData)) {

            $requestData['is_published'] = filter_var(
                $requestData['is_published'],
                FILTER_VALIDATE_BOOLEAN
            );

            $isPublishedChanged =
                $requestData['is_published'] != (bool) $exam->is_published;


            /*
            |--------------------------------------------------------------------------
            | If Published
            |--------------------------------------------------------------------------
            */

            if ($requestData['is_published'] === true) {

                $examDate = Carbon::parse(
                    $requestData['exam_date'] ?? $exam->exam_date
                );

                $today = Carbon::today();

                /*
                |--------------------------------------------------------------------------
                | Future Exam
                |--------------------------------------------------------------------------
                */

                if ($examDate->greaterThan($today)) {

                    $requestData['status'] = 'scheduled';

                }

                /*
                |--------------------------------------------------------------------------
                | Today's Exam
                |--------------------------------------------------------------------------
                */

                elseif ($examDate->equalTo($today)) {

                    $startTime = Carbon::parse(
                        $examDate->toDateString()
                        . ' '
                        . ($requestData['start_time'] ?? $exam->start_time)
                    );

                    $endTime = Carbon::parse(
                        $examDate->toDateString()
                        . ' '
                        . ($requestData['end_time'] ?? $exam->end_time)
                    );

                    $now = Carbon::now();

                    if ($now->between($startTime, $endTime)) {

                        $requestData['status'] = 'ongoing';

                    } elseif ($now->greaterThan($endTime)) {

                        $requestData['status'] = 'completed';

                    } else {

                        $requestData['status'] = 'scheduled';
                    }

                }

                /*
                |--------------------------------------------------------------------------
                | Past Exam
                |--------------------------------------------------------------------------
                */

                else {

                    $requestData['status'] = 'completed';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | If Unpublished
            |--------------------------------------------------------------------------
            */

            else {

                $requestData['status'] = 'draft';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make($requestData, [

            'exam_name' => 'required|string|max:255',

            'exam_date' => 'required|date',

            'start_time' => 'required',

            'end_time' => 'required',

            'duration_minutes' => 'required|numeric|min:15',

            'classroom_id' => 'required',

            'total_marks' => 'required|numeric|min:1',

            'passing_marks' => 'required|numeric|min:0',

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Instructions are now submitted as:
            |
            | exam_instructions[0][id]
            | exam_instructions[0][instruction]
            |--------------------------------------------------------------------------
            */

            'exam_instructions' => 'nullable|array|max:6',

            'exam_instructions.*.id' => 'nullable|integer',

            'exam_instructions.*.instruction' =>
                'nullable|string|max:2000',

            'status' =>
                'required|in:draft,scheduled,ongoing,completed,cancelled',

            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | 5. Update Exam + Instructions in Transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $exam,
            $requestData
        ) {

            /*
            |--------------------------------------------------------------------------
            | Update Main Exam
            |--------------------------------------------------------------------------
            */

            $exam->update(

                collect($requestData)
                    ->only([
                        'exam_name',
                        'exam_date',
                        'start_time',
                        'end_time',
                        'duration_minutes',
                        'classroom_id',
                        'total_marks',
                        'passing_marks',
                        'status',
                        'is_published',
                    ])
                    ->all()

            );


            /*
            |--------------------------------------------------------------------------
            | 6. Update Instructions
            |--------------------------------------------------------------------------
            */

            if (array_key_exists(
                'exam_instructions',
                $requestData
            )) {

                $submittedInstructions =
                    $requestData['exam_instructions'] ?? [];

                /*
                |--------------------------------------------------------------------------
                | Make sure it is an array
                |--------------------------------------------------------------------------
                */

                if (!is_array($submittedInstructions)) {

                    $submittedInstructions = [];
                }


                /*
                |--------------------------------------------------------------------------
                | Existing instruction IDs which should remain
                |--------------------------------------------------------------------------
                */

                $keptInstructionIds = [];


                /*
                |--------------------------------------------------------------------------
                | Process Each Instruction
                |--------------------------------------------------------------------------
                */

                foreach (
                    $submittedInstructions
                    as $sortOrder => $instructionData
                ) {

                    if (!is_array($instructionData)) {

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Instruction Text
                    |--------------------------------------------------------------------------
                    */

                    $instruction = trim(
                        (string) (
                            $instructionData['instruction']
                            ?? ''
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Ignore Empty Instruction
                    |--------------------------------------------------------------------------
                    */

                    if ($instruction === '') {

                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Existing Instruction ID
                    |--------------------------------------------------------------------------
                    */

                    $instructionId =
                        !empty($instructionData['id'])
                            ? (int) $instructionData['id']
                            : null;


                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE EXISTING INSTRUCTION
                    |--------------------------------------------------------------------------
                    */

                    if ($instructionId) {

                        /*
                        | Only find instruction belonging to THIS exam.
                        | This prevents updating another exam's instruction.
                        */

                        $existingInstruction =
                            $exam->instructions()
                                ->where(
                                    'id',
                                    $instructionId
                                )
                                ->first();


                        if ($existingInstruction) {

                            $existingInstruction->update([
                                'instruction' =>
                                    $instruction,

                                'sort_order' =>
                                    $sortOrder,
                            ]);


                            /*
                            |--------------------------------------------------------------------------
                            | Remember this ID
                            |--------------------------------------------------------------------------
                            */

                            $keptInstructionIds[] =
                                $existingInstruction->id;
                        }

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE NEW INSTRUCTION
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $newInstruction =
                            $exam->instructions()->create([
                                'instruction' =>
                                    $instruction,

                                'sort_order' =>
                                    $sortOrder,
                            ]);


                        /*
                        |--------------------------------------------------------------------------
                        | Remember newly created ID
                        |--------------------------------------------------------------------------
                        */

                        $keptInstructionIds[] =
                            $newInstruction->id;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | 7. Delete Instructions Removed From Edit Form
                |--------------------------------------------------------------------------
                */

                if (!empty($keptInstructionIds)) {

                    $exam->instructions()
                        ->whereNotIn(
                            'id',
                            $keptInstructionIds
                        )
                        ->delete();

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | If user removed ALL instructions
                    |--------------------------------------------------------------------------
                    */

                    $exam->instructions()->delete();
                }
            }
        });


        /*
        |--------------------------------------------------------------------------
        | 8. Reload Exam + Relationships
        |--------------------------------------------------------------------------
        */

        $exam->refresh();

        $exam->load([
            'department',
            'course',
            'subject',
            'departmentCategory',
            'instructions',
        ]);


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Never directly call count() on a possibly null relationship.
        |--------------------------------------------------------------------------
        */

        $instructions =
            $exam->getRelation('instructions')
            ?? collect();


        /*
        |--------------------------------------------------------------------------
        | 9. Logging
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Exam updated successfully with instructions',
            [

                'exam_database_id' =>
                    $exam->id,

                'exam_id' =>
                    $exam->exam_id,

                'instructions_count' =>
                    $instructions->count(),

                'instructions' =>
                    $instructions
                        ->pluck('instruction')
                        ->values()
                        ->toArray(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 10. Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'message' =>
                'Exam updated successfully',

            'exam' =>
                $exam,

            'published_changed' =>
                $isPublishedChanged,
        ]);


    } catch (\Exception $e) {

        /*
        |--------------------------------------------------------------------------
        | Log Error
        |--------------------------------------------------------------------------
        */

        Log::error(
            'Error updating exam',
            [

                'exam_id' =>
                    $id,

                'error' =>
                    $e->getMessage(),

                'file' =>
                    $e->getFile(),

                'line' =>
                    $e->getLine(),

                'trace' =>
                    $e->getTraceAsString(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Error Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => false,

            'message' =>
                'Failed to update exam: '
                . $e->getMessage(),

        ], 500);
    }
}


public function deleteExam($id)
{
    try {
        $exam = ExamStructureOfflineExam::findOrFail($id);
        $exam->delete();

        return response()->json([
            'success' => true,
            'message' => 'Exam deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error deleting exam: ' . $e->getMessage()
        ], 500);
    }
}

private function getSectionNamesForExam(ExamStructureOfflineExam $exam)
{
    if (!$exam->section_id) {
        return 'All Sections';
    }

    $sectionIds = array_map('trim', explode(',', $exam->section_id));

    $sectionData = DB::table('course_fee_structures')
        ->where('product_id', $exam->subtype_id) // ✅ SAME AS EMPLOYEE
        ->where('institute_id', $exam->institute_id)
        ->when($exam->branch_id, function ($q) use ($exam) {
            return $q->where('branch_id', $exam->branch_id);
        }, function ($q) {
            return $q->whereNull('branch_id');
        })
        ->value('sections');

    if (!$sectionData) {
        return implode(', ', $sectionIds);
    }

    $sections = json_decode($sectionData, true);
    if (!is_array($sections)) {
        return implode(', ', $sectionIds);
    }

    // ✅ Flexible mapping
    $map = [];
    foreach ($sections as $section) {
        $key = $section['section_id'] ?? $section['id'] ?? null;
        $value = $section['section_name'] ?? $section['name'] ?? null;
        
        if ($key && $value) {
            $map[$key] = $value;
           
        }
    }

    return collect($sectionIds)
        ->map(fn ($id) => $map[$id] ?? $id)
        ->implode(', ');
}


private function resolveSectionNames($sectionsJson, $sectionIds)
{
    if (!$sectionsJson) {
        return implode(', ', $sectionIds);
    }

    $sections = json_decode($sectionsJson, true);
    if (!is_array($sections)) {
        return implode(', ', $sectionIds);
    }

    $map = [];
    foreach ($sections as $section) {
        $key = $section['section_id'] ?? $section['id'] ?? null;
        $value = $section['section_name'] ?? $section['name'] ?? null;
        
        if ($key && $value) {
            $map[$key] = $value;
        }
    }

    return collect($sectionIds)
        ->map(fn ($id) => $map[$id] ?? $id)
        ->implode(', ');
}

private function resolveSectionNamesFromJson(?string $sectionData, array $sectionIds): string
{
    if (!$sectionData || empty($sectionIds)) {
        return implode(', ', $sectionIds);
    }

    $sections = json_decode($sectionData, true);
    if (!is_array($sections)) {
        return implode(', ', $sectionIds);
    }

    // ✅ Map: id => name
    $map = collect($sections)
        ->pluck('name', 'id')
        ->toArray();

    return collect($sectionIds)
        ->map(fn ($id) => $map[$id] ?? $id)
        ->implode(', ');
}

public function downloadOfflineExams(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|in:excel,csv,pdf',
    ]);

    $context = $this->getInstituteBranchContext();

    if (!$context['institute_id']) {
        return response()->json(['message' => 'Institute not found'], 403);
    }

    $institute_id = $context['institute_id'];
    $branch_id = $context['is_branch_admin'] ? $context['branch_id'] : null;

    /*
    |--------------------------------------------------------------------------
    | BASE QUERY (same as getExamsData)
    |--------------------------------------------------------------------------
    */
    $query = ExamStructureOfflineExam::with([
        'department',
        'course',
        'subject',
        'departmentCategory',
    ])
        ->where('institute_id', $institute_id)
        ->when($branch_id, function ($query) use ($branch_id) {
            return $query->where('branch_id', $branch_id);
        });

    /*
    |--------------------------------------------------------------------------
    | APPLY SAME FILTERS
    |--------------------------------------------------------------------------
    */

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('exam_name', 'like', "%{$search}%")
                ->orWhere('exam_id', 'like', "%{$search}%")
                ->orWhereHas('subject', function ($q) use ($search) {
                    $q->where('subject_name', 'like', "%{$search}%");
                })
                ->orWhereHas('department', function ($q) use ($search) {
                    $q->where('department', 'like', "%{$search}%");
                });
        });
    }

    if ($request->filled('exam_name')) {
        $query->where('exam_name', 'LIKE', '%' . $request->exam_name . '%');
    }

    if ($request->filled('subject')) {
        $query->whereHas('subject', function ($q) use ($request) {
            $q->where('subject_name', 'like', '%' . $request->subject . '%');
        });
    }

    if ($request->filled('department_id')) {
        $query->where('department_id', $request->department_id);
    }

    if ($request->filled('category_id')) {
        $query->where('department_category_id', $request->category_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('date_from')) {
        $query->where('exam_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->where('exam_date', '<=', $request->date_to);
    }

    if ($request->filled('duration')) {
        $query->where('duration_minutes', $request->duration);
    }

    if ($request->filled('classroom')) {
        $query->where('classroom_id', 'like', '%' . $request->classroom . '%');
    }

    /*
    |--------------------------------------------------------------------------
    | FETCH FULL DATA
    |--------------------------------------------------------------------------
    */
    $exams = $query->orderBy('exam_date', 'desc')
        ->orderBy('start_time', 'desc')
        ->get();

    if ($exams->isEmpty()) {
        return response()->json(['message' => 'No exams found'], 404);
    }

    // Add section names
    $exams->transform(function ($exam) {
        $exam->section_names = $this->getSectionNamesForExam($exam);
        return $exam;
    });

    /*
    |--------------------------------------------------------------------------
    | EXPORT SWITCH
    |--------------------------------------------------------------------------
    */
    switch ($validated['type']) {

        case 'excel':
            return Excel::download(
                new ExamOfflineExport($exams),
                'offline_exams.xlsx'
            );

        case 'csv':
            return Excel::download(
                new ExamOfflineExport($exams),
                'offline_exams.csv'
            );

        case 'pdf':
            $pdf = Pdf::loadView('pdf.offline_exams_export', [
                'exams' => $exams
            ]);
            return $pdf->download('offline_exams.pdf');

        default:
            return response()->json(['message' => 'Invalid type'], 400);
    }
}

}