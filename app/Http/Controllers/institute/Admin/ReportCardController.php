<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DepartmentCategory;
use App\Models\ProductDetails;
use App\Models\StudentParentDetails;
use Illuminate\Support\Facades\DB;
use App\Models\InstituteBasicDetails;
use Carbon\Carbon;
use App\Models\GradeSystem;

class ReportCardController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Display the hierarchical report card generator
     */
    public function index()
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get categories for the institute
        $categories = DepartmentCategory::where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->get();

        // Get institute details
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
        
        // Get current session
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;
        $session = "{$currentYear}-{$nextYear}";

        return view('instituteAdmin.ReportCard.hierarchical', [
            'categories' => $categories,
            'institute' => $institute,
            'session' => $session,
            'context' => $context
        ]);
    }

    /**
     * Display student marks and generate report
     */
    public function showStudentReport(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $request->validate([
            'student_hash_id' => 'required|string',
            'academic_year' => 'nullable|string'
        ]);

        // Store selection data in session for later use
        session([
            'report_student_id' => $request->student_hash_id,
            'report_academic_year' => $request->academic_year
        ]);
        
        $merchantId = auth()->user()->institute_id;
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->first();
            
        return view('instituteAdmin.ReportCard.studentReport', [
            'student_hash_id' => $request->student_hash_id,
            'academic_year' => $request->academic_year,
            'context' => $context,
            'fincapMerchants' => $fincapMerchants
        ]);
    }

    /**
     * Generate PDF report
     */
    public function generatePDF(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $request->validate([
            'student_hash_id' => 'required|string'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PDF report generated successfully.',
            'download_url' => '#',
            'student_id' => $request->student_hash_id
        ]);
    }

    // Get student exam marks with actual grades
    public function getStudentExamMarks(Request $request)
    {
        $request->validate([
            'student_hash_id' => 'required|string',
            'academic_year' => 'nullable|string'
        ]);

        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        // Get student details
        $student = StudentParentDetails::where('student_hash_id', $request->student_hash_id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        // Get academic details
        $academicDetails = \App\Models\StudentAcademicTransportDetails::where('student_hash_id', $request->student_hash_id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$academicDetails) {
            return response()->json([
                'success' => false,
                'message' => 'Academic details not found for this student.'
            ], 404);
        }

        // Get all exam marks for this student
        $examMarks = \App\Models\StudentExamMarks::with(['exam' => function($query) {
                $query->with(['subject', 'gradeSystem']);
            }])
            ->where('student_hash_id', $request->student_hash_id)
            ->where('institute_id', $context['institute_id'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get overall statistics
        $totalMarks = $examMarks->sum('total_marks');
        $obtainedMarks = $examMarks->sum('obtained_marks');
        $passedExams = $examMarks->where('obtained_marks', '>=', DB::raw('passing_marks'))->count();
        $failedExams = $examMarks->where('obtained_marks', '<', DB::raw('passing_marks'))->count();

        // Calculate overall grade from actual exam grades
        $overallGrade = $this->calculateOverallGrade($examMarks, $context);

        // Group by subject
        $subjectPerformance = [];
        foreach ($examMarks as $mark) {
            $subjectId = $mark->subject_id;
            $subjectName = $mark->exam->subject->subject_name ?? 'N/A';
            
            if (!isset($subjectPerformance[$subjectId])) {
                $subjectPerformance[$subjectId] = [
                    'subject_name' => $subjectName,
                    'exams' => [],
                    'total_marks' => 0,
                    'obtained_marks' => 0,
                    'exam_count' => 0
                ];
            }
            
            $subjectPerformance[$subjectId]['exams'][] = [
                'exam_name' => $mark->exam->exam_name ?? 'N/A',
                'exam_date' => $mark->exam->exam_date ?? null,
                'total_marks' => $mark->total_marks,
                'obtained_marks' => $mark->obtained_marks,
                'percentage' => $mark->total_marks > 0 ? round(($mark->obtained_marks / $mark->total_marks) * 100, 2) : 0,
                'grade' => $mark->grade, // Actual grade from backend
                'passing_marks' => $mark->passing_marks,
                'status' => $mark->obtained_marks >= $mark->passing_marks ? 'Pass' : 'Fail',
                'remarks' => $mark->remarks
            ];
                        
            $subjectPerformance[$subjectId]['total_marks'] += $mark->total_marks;
            $subjectPerformance[$subjectId]['obtained_marks'] += $mark->obtained_marks;
            $subjectPerformance[$subjectId]['exam_count']++;
        }

        // Calculate subject percentages and get subject grades
        foreach ($subjectPerformance as $subjectId => &$subject) {
            $subject['percentage'] = $subject['total_marks'] > 0 ? 
                round(($subject['obtained_marks'] / $subject['total_marks']) * 100, 2) : 0;
            
            // Get subject grade from actual exam grades (use most frequent grade)
            $subjectGrades = array_column($subject['exams'], 'grade');
            if (!empty($subjectGrades)) {
                $gradeCounts = array_count_values($subjectGrades);
                arsort($gradeCounts);
                $subject['grade'] = key($gradeCounts); // Most frequent grade
            }
        }

        return response()->json([
            'success' => true,
            'student' => $student,
            'academic_details' => $academicDetails,
            'exam_marks' => $examMarks,
            'subject_performance' => array_values($subjectPerformance),
            'statistics' => [
                'total_exams' => $examMarks->count(),
                'passed_exams' => $passedExams,
                'failed_exams' => $failedExams,
                'pass_percentage' => $examMarks->count() > 0 ? round(($passedExams / $examMarks->count()) * 100, 2) : 0,
                'total_marks' => $totalMarks,
                'obtained_marks' => $obtainedMarks,
                'overall_percentage' => $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0,
                'overall_grade' => $overallGrade, // Add overall grade
                'subjects_count' => count($subjectPerformance)
            ],
            'academic_year' => $student->academic_year ?? date('Y') . '-' . (date('Y') + 1)
        ]);
    }

    // Calculate overall grade from actual exam grades
    private function calculateOverallGrade($examMarks, $context)
    {
        if ($examMarks->isEmpty()) {
            return 'N/A';
        }

        // Get all grades from exams
        $grades = $examMarks->pluck('grade')->filter()->toArray();
        
        if (empty($grades)) {
            return 'N/A';
        }

        // Get the most frequent grade
        $gradeCounts = array_count_values($grades);
        arsort($gradeCounts);
        return key($gradeCounts);
    }

    // Get students by branch
    public function getStudentsByBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:product_details,product_id',
            'department_id' => 'required|exists:departments,department_id',
            'course_type' => 'required|string'
        ]);

        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        // Get the product/branch details
        $product = ProductDetails::where('product_id', $request->branch_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Branch not found or you do not have access.'
            ], 404);
        }

        // Get students for this branch
       $students = $this->getCommonQuery(\App\Models\StudentParentDetails::class)
    ->join('academic_transport_details as atd', 'atd.student_hash_id', '=', 'student_parent_details.student_hash_id')

    // 👇 Explicit table reference
    ->where('student_parent_details.institute_id', $context['institute_id'])
    ->where('atd.department_id', $request->department_id)
    ->where('atd.course_subtype_id', $product->product_id)
    ->where('atd.institute_id', $context['institute_id'])

    ->select(
        'student_parent_details.student_hash_id',
        'student_parent_details.first_name',
        'student_parent_details.middle_name',
        'student_parent_details.last_name',
        'student_parent_details.registration_number',
        'atd.section_id',
        'atd.course_type',
        'atd.course_subtype',
        'atd.academic_year'
    )
    ->get()
    ->map(function($student) {
        $student->avatar_initials = substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1);
        $student->full_name = trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name);
        return $student;
    });


        return response()->json([
            'success' => true,
            'students' => $students,
            'count' => $students->count(),
            'branch_name' => $product->sub_type ?? $product->course_type,
            'course_type' => $request->course_type
        ]);
    }
    public function getCommonQuery($model)
{
    $context = $this->getInstituteBranchContext();
    $table = (new $model)->getTable();

    $query = $model::query()
        ->where($table . '.institute_id', $context['institute_id']);

    if ($context['is_branch_admin'] && $context['branch_id']) {
        $query->where($table . '.branch_id', $context['branch_id']);
    } else {
        $query->whereNull($table . '.branch_id');
    }

    return $query;
}

}