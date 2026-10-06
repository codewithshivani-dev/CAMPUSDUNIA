<?php
// app/Http/Controllers/institute/Admin/RollNumberController.php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\StudentRollNumber;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\CourseFeeStructure;
use App\Services\RollNumberService;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RollNumberController extends Controller
{
    use InstituteBranchAccess;

    protected $rollNumberService;

    public function __construct(RollNumberService $rollNumberService)
    {
        $this->rollNumberService = $rollNumberService;
    }

    /**
     * Display the roll number assignment page
     */
    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get all departments with branch context
        $departmentQuery = Departments::where('institute_id', $context['institute_id']);
        
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $departmentQuery->where('branch_id', $context['branch_id']);
        } else {
            $departmentQuery->whereNull('branch_id');
        }
        
        $departments = $departmentQuery->orderBy('department')->get();

        $courseTypes = collect();
        $subTypes = collect();
        $batches = collect();
        $academicYears = collect();

        if ($request->filled('department_id')) {
            $courseTypes = DB::table('product_details')
                ->where('institute_id', $context['institute_id'])
                ->where('department_id', $request->department_id)
                ->whereNotNull('course_type')
                ->where('course_type', '!=', '')
                ->select('course_type as id', 'course_type as name')
                ->distinct()
                ->orderBy('course_type')
                ->get();
        }

        if ($request->filled('department_id') && $request->filled('course_type')) {
            $subTypes = DB::table('product_details')
                ->where('institute_id', $context['institute_id'])
                ->where('department_id', $request->department_id)
                ->where('course_type', $request->course_type)
                ->whereNotNull('sub_type')
                ->where('sub_type', '!=', '')
                ->select('product_id as id', 'sub_type as name', 'course_type')
                ->orderBy('sub_type')
                ->get();
        }

        if ($request->filled('course_subtype_id')) {
            $batchQuery = CourseFeeStructure::where('institute_id', $context['institute_id'])
                ->where('product_id', $request->course_subtype_id)
                ->whereNotNull('batch')
                ->where('batch', '!=', '');

            if ($context['is_branch_admin'] && $context['branch_id']) {
                $batchQuery->where('branch_id', $context['branch_id']);
            }

            $batches = $batchQuery->select('batch_id', 'batch')
                ->distinct()
                ->orderBy('batch', 'desc')
                ->get();
        }

        if ($request->filled('batch_id')) {
            $academicYearQuery = CourseFeeStructure::where('institute_id', $context['institute_id'])
                ->where('batch_id', $request->batch_id)
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '');

            if ($context['is_branch_admin'] && $context['branch_id']) {
                $academicYearQuery->where('branch_id', $context['branch_id']);
            }

            $academicYears = $academicYearQuery->select('academic_year_id', 'academic_year')
                ->distinct()
                ->orderBy('academic_year', 'desc')
                ->get();
        }

        // Get sections for each course (will be loaded via AJAX)
        $sections = [];

        // Get selected filters from request
        $selectedFilters = [
            'department_id' => $request->department_id,
            'course_type' => $request->course_type,
            'course_subtype_id' => $request->course_subtype_id,
            'batch_id' => $request->batch_id,
            'academic_year_id' => $request->academic_year_id,
            'section_id' => $request->section_id,
        ];

        // Sorting parameters
        $sortBy = $request->get('sort_by', 'first_name');
        $sortOrder = $request->get('sort_order', 'asc');

        // Allowed sort columns (white list for security)
        $allowedSortColumns = [
            'registration_number',
            'first_name',
            'last_name',
            'course_subtype',
            'batch',
            'academic_year',
            'roll_number',
            'status',
        ];

        // Validate sort column
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'first_name';
        }

        // Validate sort order
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        // If all filters are selected, fetch students
        $students = collect();
        $statistics = [
            'total' => 0,
            'with_roll_number' => 0,
            'without_roll_number' => 0,
        ];

        if ($request->has('filter') && $request->filter == '1') {
            if ($selectedFilters['batch_id'] && $selectedFilters['academic_year_id'] && $selectedFilters['section_id']) {
                $students = $this->rollNumberService->getStudentsBySection(
                    $context['institute_id'],
                    $selectedFilters['batch_id'],
                    $selectedFilters['academic_year_id'],
                    $selectedFilters['section_id'],
                    $selectedFilters['course_subtype_id']
                );

                // Apply sorting to the collection
                $students = $this->sortStudents($students, $sortBy, $sortOrder);

                // Calculate statistics
                $statistics['total'] = $students->count();
                $statistics['with_roll_number'] = $students->filter(function ($student) {
                    return $student->rollNumber && $student->rollNumber->status === 'active';
                })->count();
                $statistics['without_roll_number'] = $statistics['total'] - $statistics['with_roll_number'];
            }
        }

        return view('instituteAdmin.RollNumber.AssignRollNumber', compact(
            'departments',
            'courseTypes',
            'subTypes',
            'batches',
            'academicYears',
            'sections',
            'selectedFilters',
            'students',
            'statistics',
            'context',
            'sortBy',
            'sortOrder'
        ));
    }

    /**
     * Sort students collection by specified column
     */
    private function sortStudents($students, $sortBy, $sortOrder)
{
    $descending = $sortOrder === 'desc';

    return $students->sort(function ($a, $b) use ($sortBy, $descending) {

        $valueA = $this->getSortableValue($a, $sortBy);
        $valueB = $this->getSortableValue($b, $sortBy);

        // Normalize values
        $valueA = trim((string)($valueA ?? ''));
        $valueB = trim((string)($valueB ?? ''));

        // Empty values always go to the end
        if ($valueA === '' && $valueB === '') {
            return 0;
        }

        if ($valueA === '') {
            return 1;
        }

        if ($valueB === '') {
            return -1;
        }

        // Numeric sorting
        if (is_numeric($valueA) && is_numeric($valueB)) {
            $comparison = $valueA <=> $valueB;
        } else {
            // Natural alphabetical sorting
            $comparison = strnatcasecmp($valueA, $valueB);
        }

        return $descending ? -$comparison : $comparison;
    })->values();
    }

    /**
     * Get sortable value from student for a given column
     */
    private function getSortableValue($student, $sortBy)
    {
        switch ($sortBy) {
            case 'registration_number':
                return $student->registration_number ?? '';
                
            case 'first_name':
                return $student->first_name ?? '';
                
            case 'last_name':
                return $student->last_name ?? '';
                
            case 'course_subtype':
                return $student->academicTransportDetails->course_subtype ?? '';
                
            case 'batch':
                return $student->academicTransportDetails->batch ?? '';
                
            case 'academic_year':
                return $student->academicTransportDetails->academic_year ?? '';
                
            case 'roll_number':
                return $student->rollNumber ? $student->rollNumber->roll_number : '';
                
            case 'status':
                return $student->rollNumber && $student->rollNumber->status === 'active' 
                    ? 'Assigned' 
                    : 'Pending';
                
            default:
                return $student->first_name ?? '';
        }
    }

    /**
     * Get courses for a department
     */
    public function getCourses(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $query = DB::table('product_details')
            ->where('institute_id', $context['institute_id'])
            ->whereNotNull('sub_type')
            ->where('sub_type', '!=', '');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('course_type')) {
            $query->where('course_type', $request->course_type);
        }

        if ($request->filled('course_subtype_id')) {
            $query->where('product_id', $request->course_subtype_id);
        }

        if ($request->filled('course_type')) {
            $courses = $query->select('product_id as id', 'sub_type as name', 'course_type')
                ->orderBy('sub_type')
                ->get();

            return response()->json([
                'success' => true,
                'courses' => $courses,
            ]);
        }

        $courseTypes = $query->select('course_type as name')
            ->distinct()
            ->orderBy('course_type')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->name,
                    'name' => $item->name,
                ];
            });

        return response()->json([
            'success' => true,
            'courses' => $courseTypes,
        ]);
    }

    public function getBatches(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $request->validate([
            'course_subtype_id' => 'required',
        ]);

        $batchQuery = CourseFeeStructure::where('institute_id', $context['institute_id'])
            ->where('product_id', $request->course_subtype_id)
            ->whereNotNull('batch')
            ->where('batch', '!=', '');

        if ($context['is_branch_admin'] && $context['branch_id']) {
            $batchQuery->where('branch_id', $context['branch_id']);
        }

        $batches = $batchQuery->select('batch_id', 'batch')
            ->distinct()
            ->orderBy('batch', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'batches' => $batches,
        ]);
    }

    public function getAcademicYears(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $request->validate([
            'batch_id' => 'required',
        ]);

        $yearQuery = CourseFeeStructure::where('institute_id', $context['institute_id'])
            ->where('batch_id', $request->batch_id)
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '');

        if ($request->filled('course_subtype_id')) {
            $yearQuery->where('product_id', $request->course_subtype_id);
        }

        if ($context['is_branch_admin'] && $context['branch_id']) {
            $yearQuery->where('branch_id', $context['branch_id']);
        }

        $academicYears = $yearQuery->select('academic_year_id', 'academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'academicYears' => $academicYears,
        ]);
    }

    /**
     * Get sections for a course
     */
    public function getSections(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $request->validate([
            'course_subtype_id' => 'required',
            'batch_id' => 'required',
        ]);

        $courseFeeStructure = CourseFeeStructure::where('product_id', $request->course_subtype_id)
            ->where('batch_id', $request->batch_id)
            ->where('institute_id', $context['institute_id'])
            ->when($request->filled('academic_year_id'), function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            })
            ->first();

        $sections = [];
        
        if ($courseFeeStructure && $courseFeeStructure->sections) {
            $decodedSections = json_decode($courseFeeStructure->sections, true);
            if (is_array($decodedSections)) {
                foreach ($decodedSections as $section) {
                    $sections[] = [
                        'id' => $section['id'] ?? $section['section_id'] ?? null,
                        'name' => $section['name'] ?? $section['section_name'] ?? 'Section',
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'sections' => $sections,
        ]);
    }

    /**
     * Get students for a section with sorting
     */
    public function getStudents(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $request->validate([
            'batch_id' => 'required',
            'academic_year_id' => 'required',
            'section_id' => 'required',
        ]);

        $sortBy = $request->get('sort_by', 'first_name');
        $sortOrder = $request->get('sort_order', 'asc');

        $students = $this->rollNumberService->getStudentsBySection(
            $context['institute_id'],
            $request->batch_id,
            $request->academic_year_id,
            $request->section_id,
            $request->course_subtype_id
        );

        // Apply sorting
        $students = $this->sortStudents($students, $sortBy, $sortOrder);

        $studentsData = $students->map(function ($student) {
            return [
                'student_hash_id' => $student->student_hash_id,
                'registration_number' => $student->registration_number,
                'name' => $student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name,
                'roll_number' => $student->rollNumber ? $student->rollNumber->roll_number : null,
                'has_roll_number' => $student->rollNumber && $student->rollNumber->status === 'active',
                'student' => $student,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'students' => $studentsData,
            'total' => $students->count(),
            'with_roll_number' => $studentsData->filter(fn($s) => $s['has_roll_number'])->count(),
            'without_roll_number' => $studentsData->filter(fn($s) => !$s['has_roll_number'])->count(),
        ]);
    }

    /**
     * Assign roll number to a single student
     */
    public function assignSingle(Request $request)
    {
        try {
            $request->validate([
                'student_hash_id' => 'required|exists:student_parent_details,student_hash_id',
                'roll_number' => 'nullable|string|max:50',
            ]);

            $result = $this->rollNumberService->assignRollNumber(
                $request->student_hash_id,
                $request->roll_number
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Roll number saved successfully',
                    'roll_number' => $result->roll_number,
                    'sequence' => $result->roll_number_sequence,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign roll number',
            ], 500);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Roll number assignment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk assign roll numbers to selected students
     */
    public function bulkAssign(Request $request)
    {
        try {
            $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'exists:student_parent_details,student_hash_id',
            ]);

            $assigned = 0;
            $failed = [];
            $skipped = [];

            foreach ($request->student_ids as $studentHashId) {
                // Check if student already has a roll number
                $existing = StudentRollNumber::where('student_hash_id', $studentHashId)
                    ->where('status', 'active')
                    ->first();

                if ($existing) {
                    $skipped[] = $studentHashId;
                    continue;
                }

                // Check if student has complete academic details
                $academic = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)->first();
                
                if (!$academic || !$academic->batch_id || !$academic->academic_year_id || !$academic->section_id) {
                    $failed[] = $studentHashId;
                    continue;
                }

                $result = $this->rollNumberService->assignRollNumber($studentHashId);
                
                if ($result) {
                    $assigned++;
                } else {
                    $failed[] = $studentHashId;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Roll numbers assigned: {$assigned}, Failed: " . count($failed) . ", Skipped: " . count($skipped),
                'assigned' => $assigned,
                'failed' => $failed,
                'skipped' => $skipped,
            ]);

        } catch (\Exception $e) {
            Log::error('Bulk roll number assignment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Assign roll numbers to all students in the current filter
     */
    public function assignAll(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();

            $request->validate([
                'batch_id' => 'required',
                'academic_year_id' => 'required',
                'section_id' => 'required',
            ]);

            $students = StudentParentDetails::where('institute_id', $context['institute_id'])
                ->whereHas('academicTransportDetails', function ($q) use ($request) {
                    $q->where('batch_id', $request->batch_id)
                      ->where('academic_year_id', $request->academic_year_id)
                      ->where('section_id', $request->section_id);
                    
                    if ($request->course_subtype_id) {
                        $q->where('course_subtype_id', $request->course_subtype_id);
                    }
                })
                ->whereDoesntHave('rollNumber', function ($q) {
                    $q->where('status', 'active');
                })
                ->with('academicTransportDetails')
                ->get();

            $assigned = 0;
            $failed = 0;

            foreach ($students as $student) {
                $result = $this->rollNumberService->assignRollNumber($student->student_hash_id);
                if ($result) {
                    $assigned++;
                } else {
                    $failed++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Roll numbers assigned: {$assigned}, Failed: {$failed}",
                'assigned' => $assigned,
                'failed' => $failed,
            ]);

        } catch (\Exception $e) {
            Log::error('Assign all roll numbers error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reassign roll numbers for a section
     */
    public function reassignSection(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();

            $request->validate([
                'batch_id' => 'required',
                'academic_year_id' => 'required',
                'section_id' => 'required',
            ]);

            $result = $this->rollNumberService->reassignRollNumbers(
                $context['institute_id'],
                $request->batch_id,
                $request->academic_year_id,
                $request->section_id,
                $request->course_subtype_id
            );

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Reassign roll numbers error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export students with roll numbers
     */
    public function export(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $students = $this->rollNumberService->getStudentsBySection(
            $context['institute_id'],
            $request->batch_id,
            $request->academic_year_id,
            $request->section_id,
            $request->course_subtype_id
        );

        // Apply sorting for export
        $sortBy = $request->get('sort_by', 'first_name');
        $sortOrder = $request->get('sort_order', 'asc');
        $students = $this->sortStudents($students, $sortBy, $sortOrder);

        $fileName = 'roll_numbers_' . date('Y-m-d_Hi') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'Registration Number',
                'Student Name',
                'Course',
                'Batch',
                'Academic Year',
                'Section',
                'Roll Number',
                'Status'
            ]);

            // Data
            foreach ($students as $student) {
                $academic = $student->academicTransportDetails;
                fputcsv($file, [
                    $student->registration_number ?? 'N/A',
                    $student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name,
                    $academic->course_subtype ?? 'N/A',
                    $academic->batch ?? 'N/A',
                    $academic->academic_year ?? 'N/A',
                    $this->getSectionName($academic->section_id, $academic->course_subtype_id, $student->institute_id),
                    $student->rollNumber ? $student->rollNumber->roll_number : 'Not Assigned',
                    $student->rollNumber && $student->rollNumber->status === 'active' ? 'Active' : 'Inactive',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getSectionName($sectionId, $courseId, $instituteId)
    {
        if (!$sectionId) return 'N/A';

        $courseFee = CourseFeeStructure::where('product_id', $courseId)
            ->where('institute_id', $instituteId)
            ->first();

        if ($courseFee && $courseFee->sections) {
            $sections = json_decode($courseFee->sections, true);
            if (is_array($sections)) {
                foreach ($sections as $section) {
                    $id = $section['id'] ?? $section['section_id'] ?? null;
                    if ($id == $sectionId) {
                        return $section['name'] ?? $section['section_name'] ?? 'Section';
                    }
                }
            }
        }

        return $sectionId;
    }
}