<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkAssignment;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\StudentParentDetails;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Illuminate\Support\Str;


class HomeworkController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    /**
     * Show Homework Create Page
     */
    public function create()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $user = Auth::user();

        $employee = User::join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->where('users.id', $user->id)
            ->select('employee_details.*')
            ->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $categories = DepartmentCategory::where('institute_id', $employee->institute_id)
            ->where('department_category_id', $employee->department_category_id)
            ->when($employee->branch_id, function ($query) use ($employee) {
                $query->where('branch_id', $employee->branch_id);
            })
            ->get();

        $departments = Departments::where('institute_id', $employee->institute_id)
            ->where('department_category_id', $employee->department_category_id)
            ->where('department_id', $employee->department_id)
            ->when($employee->branch_id, fn ($q) =>
                $q->where('branch_id', $employee->branch_id)
            )
            ->first();

        return view(
            'instituteAdmin.SchoolHomework.HomeWork',
            compact('categories', 'departments', 'employee')
        );
    }

    /**
     * Store new homework
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // try {
            DB::beginTransaction();

            $user = Auth::user();
            $employee = EmployeeDetails::where('user_id', $user->id)->first();
            $homeworkid = 'HW-' . strtoupper(Str::random(8));

            // Validate required fields
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'instructions' => 'string',
                'due_date' => 'date',
                'course_id' => 'required',
                'branch_id' => 'required',
                'subject_id' => 'required',
                'section_id' => 'required',
            ]);
            
            $productId = $validated['branch_id'];
            
            $homework = Homework::create([
                'title' => $validated['title'],
                'homework_id' => $homeworkid,
                'instructions' => $validated['instructions'],
                'due_date' => $validated['due_date'],
                'institute_id' => $employee->institute_id,
                'department_id' => $employee->department_id,
                'course_id' => $validated['course_id'],
                'product_id' => $productId,
                'subject_id' => $validated['subject_id'],
                'section_id' => $validated['section_id'],
                'semester_id' => $request->semester_id,
                'created_by' => $user->id,
                'status' => 'created',
            ]);            

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Homework created successfully!',
                'homework' => $homework
            ]);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to create homework: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Assign homework to students
     */
    public function assign(Request $request)
    {
        // try {
            DB::beginTransaction();

            $validated = $request->validate([
                'homework_id' => 'required|exists:homeworks,id',
                'student_hash_ids' => 'required|array',
            ]);

            $homework = Homework::findOrFail($validated['homework_id']);
            
            // Create assignments for each student
            $assignments = [];
            foreach ($validated['student_hash_ids'] as $studentHashId) {
                $assignments[] = [
                    'institute_id'     => $homework->institute_id,
                    'homework_id'      => $homework->id,
                    'product_id'       => $homework->product_id,
                    'student_hash_id'  => $studentHashId,
                    'status'           => 'assigned',
                    'assigned_at'      => now(),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }

            // Bulk insert for performance
            HomeworkAssignment::insert($assignments);

            // Update homework status
            $homework->update([
                'status' => 'assigned',
                'assigned_to_count' => count($validated['student_hash_ids']),
                'assigned_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Homework assigned to " . count($validated['student_hash_ids']) . " students successfully!",
            ]);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to assign homework: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Get homework list
     */
    public function getHomeworks(Request $request)
    {
        // try {
            $user = Auth::user();
            $employee = EmployeeDetails::where('user_id', $user->id)->first();

            $query = Homework::query()
            ->leftJoin(
                'subjects_coursewise',
                'subjects_coursewise.subject_id',
                '=',
                'homeworks.subject_id'
            )
            ->leftJoin(
                'course_fee_structures',
                'course_fee_structures.product_id',
                '=',
                'homeworks.product_id'
            )
            ->where('homeworks.institute_id', $employee->institute_id)
            ->where('homeworks.department_id', $employee->department_id)
            ->select(
                'homeworks.*',
                'subjects_coursewise.subject_id',
                'subjects_coursewise.subject_name',
                'subjects_coursewise.semester_id',
                'course_fee_structures.course_type',
                'course_fee_structures.sub_type',
                'course_fee_structures.sections',
            );

            // Apply filters
            if ($request->filled('department_id')) {
                $query->where('homeworks.department_id', $request->department_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            // Get month-wise data
            if ($request->filled('month')) {
                $year = $request->filled('year') ? $request->year : date('Y');
                $query->whereYear('due_date', $year)
                      ->whereMonth('due_date', $request->month);
            }

            // Get week-wise data
            if ($request->filled('week_start') && $request->filled('week_end')) {
                $query->whereBetween('due_date', [$request->week_start, $request->week_end]);
            }

            $homeworks = $query->orderBy('due_date', 'desc')->get();
            // Format for frontend
            $formattedHomeworks = $homeworks->map(function($hw) {
                $sectionIds = json_decode($hw->section_id, true);

                // Ensure $sectionIds is always an array
                $sectionIds = is_array($sectionIds) ? $sectionIds : [];
                if (count($sectionIds) > 1) {
                    $sectionText = 'All Sections';
                } elseif (count($sectionIds) === 1) {
                    $sectionText = $hw->section_name ?? 'N/A';
                } else {
                    $sectionText = 'N/A';
                }

                return [
                    'id' => $hw->id,
                    'title' => $hw->title,
                    'class' => $hw->course_id,
                    'classText' => $hw->course_type ?? '',
                    'product_id' => $hw->product_id,
                    'branchText' => $hw->sub_type ?? '',
                    'subject' => $hw->subject_id,
                    'subjectText' => $hw->subject_name ?? '',
                    'section' => $hw->section_id,
                    'sectionText' => is_array($sectionIds)? 'All Sections' : $hw->section_name,
                    'dueDate' => $hw->due_date,
                    'instructions' => $hw->instructions,
                    'createdAt' => $hw->created_at->format('M d, Y h:i A'),
                    'status' => $hw->status,
                    'assignedTo' => $hw->assigned_to_count,
                    'assignedStudents' => $hw->assignments->pluck('student_hash_id')->toArray(),
                    'assignedAt' => $hw->assigned_at ? $hw->assigned_at->format('M d, Y h:i A') : null,
                ];
            });

            // Calculate statistics
            $stats = [
                'total' => $homeworks->count(),
                'assigned' => $homeworks->where('status', 'assigned')->count(),
                'created' => $homeworks->where('status', 'created')->count(),
                'total_students' => HomeworkAssignment::whereIn('homework_id', $homeworks->pluck('id'))->distinct('student_hash_id')->count(),
            ];

            return response()->json([
                'success' => true,
                'homeworks' => $formattedHomeworks,
                'statistics' => $stats
            ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to fetch homeworks: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Get homework details
     */
    public function show($id)
    {
        // try {
            $homework = Homework::with(['assignments.student', 'creator'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'homework' => $homework
            ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Homework not found: ' . $e->getMessage()
        //     ], 404);
        // }
    }

    /**
     * Update homework
     */
    public function update(Request $request, $id)
    {
        // try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'instructions' => 'required|string',
                'due_date' => 'required|date',
            ]);

            $homework = Homework::findOrFail($id);
            $homework->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Homework updated successfully!',
                'homework' => $homework
            ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to update homework: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Delete homework
     */
    public function destroy($id)
    {
        // try {
            $homework = Homework::findOrFail($id);
            
            // Delete assignments first
            HomeworkAssignment::where('homework_id', $id)->delete();
            
            // Delete homework
            $homework->delete();

            return response()->json([
                'success' => true,
                'message' => 'Homework deleted successfully!'
            ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to delete homework: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics()
    {
        // try {
            $user = Auth::user();
            $employee = EmployeeDetails::where('user_id', $user->id)->first();

            $total = Homework::where('institute_id', $employee->institute_id)->count();
            $assigned = Homework::where('institute_id', $employee->institute_id)
                ->where('status', 'assigned')
                ->count();
            $created = Homework::where('institute_id', $employee->institute_id)
                ->where('status', 'created')
                ->count();
                
            $total_students = HomeworkAssignment::whereHas('homework', function($q) use ($employee) {
                $q->where('institute_id', $employee->institute_id);
            })->distinct('student_hash_id')->count();

            return response()->json([
                'success' => true,
                'statistics' => [
                    'total' => $total,
                    'assigned' => $assigned,
                    'created' => $created,
                    'total_students' => $total_students
                ]
            ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to get statistics: ' . $e->getMessage()
        //     ], 500);
        // }
    }
}