<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamStructureOfflineExam;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\CourseFeeStructure;
use App\Models\User;
use App\Models\GradeSystem;
use App\Models\ExamName;
use App\Models\ExamInstruction;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CreateExamStructureController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    /**
     * Display exam structures.
     */
    public function index()
    {
        $exams = ExamStructureOfflineExam::with([
            'department',
            'course',
            'subject',
            'classroom',
            'instructions'
        ])
            ->orderBy('exam_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        return view(
            'admin.exams.index',
            compact('exams')
        );
    }

    /**
     * Show create exam structure page.
     */
    public function create()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'You are not associated with any institute.'
                );
        }

        $instituteId = $context['institute_id'];

        $branchId = $context['branch_id'] ?? null;

        $user = Auth::user();

        $categories = DepartmentCategory::where(
            'institute_id',
            $instituteId
        )->get();

        $gradeSystems = GradeSystem::where(
            'institute_id',
            $instituteId
        )->get();

        return view(
            'instituteAdmin.ExamStructure.examStructureOffline',
            compact(
                'categories',
                'gradeSystems'
            )
        );
    }

    /**
     * Store exam structure(s).
     *
     * This method also saves the dynamic exam instructions
     * entered while creating the examination.
     */
    public function store(Request $request)
    {
        $examsData = $request->input(
            'exams',
            []
        );

        $context = $this->getInstituteBranchContext();

        /*
        |--------------------------------------------------------------------------
        | Basic validation
        |--------------------------------------------------------------------------
        */

        if (
            empty($examsData) ||
            !is_array($examsData)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'No exam data provided.'
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | Exam Name
        |--------------------------------------------------------------------------
        */

        if (
            !isset($examsData[0]['exam_name_id']) ||
            empty($examsData[0]['exam_name_id'])
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Exam name ID is required.'
            ], 400);
        }

        $examName = ExamName::where(
            'exam_name_id',
            $examsData[0]['exam_name_id']
        )
            ->where(
                'institute_id',
                $context['institute_id']
            )
            ->first();

        if (!$examName) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid exam name selected.'
            ], 400);
        }

        $errors = [];

        $successCount = 0;

        $createdExams = [];

        /*
        |--------------------------------------------------------------------------
        | Create each subject examination
        |--------------------------------------------------------------------------
        */

        foreach (
            $examsData as $index => $examData
        ) {

            /*
            |--------------------------------------------------------------------------
            | Validate Exam
            |--------------------------------------------------------------------------
            */

            $validator = Validator::make(
                $examData,
                [
                    'institute_id' => 'required',

                    'branch_id' => 'required',

                    'department_category_id' => 'required',

                    'department_id' => 'required',

                    'course_id' => 'required',

                    'subtype_id' => 'required',

                    'subject_id' => 'required',

                    'semester_id' => 'nullable',

                    'section_id' => 'required',

                    'exam_name' => 'required',

                    'grade_system_id' => 'required',

                    'exam_date' => 'required|date',

                    'start_time' => 'required',

                    'reporting_time' =>
                        'required|date_format:H:i',

                    'end_time' => 'required',

                    'building_id' =>
                        'required|integer',

                    'block_id' =>
                        'required|integer',

                    'floor_id' =>
                        'required|integer',

                    'room_id' =>
                        'required|integer',

                    'classroom_id' =>
                        'required',

                    'total_marks' =>
                        'required|numeric|min:1',

                    'passing_marks' =>
                        'required|numeric|min:0',

                    'duration_minutes' =>
                        'required|numeric|min:15',

                    /*
                    |--------------------------------------------------------------------------
                    | Dynamic Instructions
                    |--------------------------------------------------------------------------
                    |
                    | Maximum 6 instructions.
                    | Each instruction can contain up to 2000 characters.
                    |
                    */

                    'exam_instructions' =>
                        'nullable|array|max:6',

                    'exam_instructions.*' =>
                        'nullable|string|max:2000',
                ]
            );

            if ($validator->fails()) {

                foreach (
                    $validator->errors()->all()
                    as $error
                ) {

                    $errors[] =
                        'Subject #' .
                        ($index + 1) .
                        ': ' .
                        $error;
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Validate Section IDs
            |--------------------------------------------------------------------------
            */

            $sectionIds = explode(
                ',',
                $examData['section_id']
            );

            $sectionIds = array_filter(
                array_map(
                    'trim',
                    $sectionIds
                )
            );

            if (empty($sectionIds)) {

                $errors[] =
                    'Subject #' .
                    ($index + 1) .
                    ': No valid sections selected';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Generate Unique Exam ID
            |--------------------------------------------------------------------------
            */

            $examID =
                'EXAM-' .
                time() .
                rand(10, 99) .
                '-' .
                ($index + 1);

            /*
            |--------------------------------------------------------------------------
            | Create Exam + Instructions
            |--------------------------------------------------------------------------
            */

            try {

                $exam = DB::transaction(
                    function () use (
                        $context,
                        $examData,
                        $examName,
                        $examID,
                        $index
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | Create Exam Structure
                        |--------------------------------------------------------------------------
                        */

                        $exam =
                            ExamStructureOfflineExam::create([

                                'institute_id' =>
                                    $context['institute_id']
                                    ??
                                    $examData['institute_id'],

                                'branch_id' =>
                                    $context['is_branch_admin']
                                    ? $context['branch_id']
                                    : null,

                                'department_category_id' =>
                                    $examData[
                                        'department_category_id'
                                    ],

                                'department_id' =>
                                    $examData[
                                        'department_id'
                                    ],

                                'course_id' =>
                                    $examData[
                                        'course_id'
                                    ],

                                'subtype_id' =>
                                    $examData[
                                        'subtype_id'
                                    ],

                                'subject_id' =>
                                    $examData[
                                        'subject_id'
                                    ],

                                'semester_id' =>
                                    $examData[
                                        'semester_id'
                                    ] ?? null,

                                'section_id' =>
                                    $examData[
                                        'section_id'
                                    ],

                                'exam_id' =>
                                    $examID,

                                'exam_name_id' =>
                                    $examName->exam_name_id,

                                'academic_year' =>
                                    $examName->academic_year,

                                'exam_name' =>
                                    $examData[
                                        'exam_name'
                                    ],

                                'grade_system_id' =>
                                    $examData[
                                        'grade_system_id'
                                    ],

                                'exam_date' =>
                                    $examData[
                                        'exam_date'
                                    ],

                                'start_time' =>
                                    $examData[
                                        'start_time'
                                    ],

                                'reporting_time' =>
                                    $examData[
                                        'reporting_time'
                                    ],

                                'end_time' =>
                                    $examData[
                                        'end_time'
                                    ],

                                'duration_minutes' =>
                                    $examData[
                                        'duration_minutes'
                                    ],

                                'building_id' =>
                                    $examData[
                                        'building_id'
                                    ],

                                'block_id' =>
                                    $examData[
                                        'block_id'
                                    ],

                                'floor_id' =>
                                    $examData[
                                        'floor_id'
                                    ],

                                'room_id' =>
                                    $examData[
                                        'room_id'
                                    ],

                                /*
                                |--------------------------------------------------------------------------
                                | IMPORTANT FIX
                                |--------------------------------------------------------------------------
                                |
                                | Previously classroom_id was being saved from
                                | room_id. Now the actual classroom_id is saved.
                                |
                                */

                                'classroom_id' =>
                                    $examData[
                                        'classroom_id'
                                    ],

                                'total_marks' =>
                                    $examData[
                                        'total_marks'
                                    ],

                                'passing_marks' =>
                                    $examData[
                                        'passing_marks'
                                    ],

                                'status' =>
                                    $examData[
                                        'status'
                                    ] ?? 'draft',

                                'is_published' =>
                                    $examData[
                                        'is_published'
                                    ] ?? false,
                            ]);

                        /*
                        |--------------------------------------------------------------------------
                        | Dynamic Exam Instructions
                        |--------------------------------------------------------------------------
                        */

                        $instructions =
                            collect(
                                $examData[
                                    'exam_instructions'
                                ] ?? []
                            )
                            ->map(
                                function (
                                    $instruction
                                ) {

                                    return trim(
                                        (string)
                                        $instruction
                                    );
                                }
                            )
                            ->filter(
                                function (
                                    $instruction
                                ) {

                                    return
                                        $instruction !== '';
                                }
                            )
                            ->values();

                        /*
                        |--------------------------------------------------------------------------
                        | Save Instructions
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $instructions->isNotEmpty()
                        ) {

                            $instructionRows =
                                $instructions->map(
                                    function (
                                        $instruction,
                                        $sortOrder
                                    ) {

                                        return [
                                            'instruction' =>
                                                $instruction,

                                            'sort_order' =>
                                                $sortOrder,
                                        ];
                                    }
                                )->all();

                            $exam
                                ->instructions()
                                ->createMany(
                                    $instructionRows
                                );
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Reload Instructions
                        |--------------------------------------------------------------------------
                        */

                        $exam->load([
                            'instructions'
                        ]);

                        /*
                        |--------------------------------------------------------------------------
                        | Debug Log
                        |--------------------------------------------------------------------------
                        |
                        | This allows us to confirm that instructions were
                        | actually saved against this exam.
                        |
                        */

                        Log::info(
                            'Exam structure created with dynamic instructions',
                            [
                                'exam_database_id' =>
                                    $exam->id,

                                'exam_id' =>
                                    $exam->exam_id,

                                'subject_id' =>
                                    $exam->subject_id,

                                'instructions_count' =>
                                    $exam
                                        ->instructions
                                        ->count(),

                                'instructions' =>
                                    $exam
                                        ->instructions
                                        ->pluck(
                                            'instruction'
                                        )
                                        ->values()
                                        ->toArray(),
                            ]
                        );

                        return $exam;
                    }
                );

                $successCount++;

                $createdExams[] = $exam;

            } catch (\Throwable $e) {

                Log::error(
                    'Failed to create exam structure',
                    [
                        'subject_index' =>
                            $index + 1,

                        'exam_data' =>
                            $examData,

                        'error' =>
                            $e->getMessage(),

                        'file' =>
                            $e->getFile(),

                        'line' =>
                            $e->getLine(),
                    ]
                );

                $errors[] =
                    'Subject #' .
                    ($index + 1) .
                    ': ' .
                    $e->getMessage();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Return Validation / Creation Errors
        |--------------------------------------------------------------------------
        */

        if (!empty($errors)) {

            return response()->json([
                'success' => false,

                'message' =>
                    implode(
                        "\n",
                        $errors
                    ),

                'created_count' =>
                    $successCount,

                'error_count' =>
                    count($errors),

                'exams' =>
                    $createdExams,
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Success Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'message' =>
                $successCount .
                ' exam(s) created successfully.',

            'created_count' =>
                $successCount,

            'exams' =>
                $createdExams,
        ], 201);
    }
}

