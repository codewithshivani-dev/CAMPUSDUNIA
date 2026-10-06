<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DepartmentCategory;
use App\Models\ProductDetails;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\StudentExamMarks;
use App\Models\ExamStructureOfflineExam;
use App\Models\GradeSystem;
use App\Models\ExamName;
use App\Models\InstituteBasicDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MultiReportCardController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Display the report card generator
     */
    public function index()
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get products (branches) for the institute
        $branches = ProductDetails::where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->get();

        // Get institute details
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
        
        // Determine board format based on institute affiliation
        $boardFormat = $this->determineBoardFormat($institute);
        
        // Get current session
        $currentYear = Carbon::now()->year;
        $nextYear = $currentYear + 1;
        $session = "{$currentYear}-{$nextYear}";
        
        // Generate dynamic academic years (3 previous, 5 upcoming)
        $academicYears = [];
        $startYear = $currentYear - 3;
        $endYear = $currentYear + 5;
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $academicYears[] = $year . '-' . ($year + 1);
        }

        return view('instituteAdmin.ReportCard.reportCardAllFormat', [
            'branches' => $branches,
            'institute' => $institute,
            'session' => $session,
            'academicYears' => $academicYears,
            'currentYear' => $currentYear,
            'context' => $context,
            'boardFormat' => $boardFormat
        ]);
    }

    /**
     * Determine board format based on institute affiliation
     */
    private function determineBoardFormat($institute)
    {

        if (!$institute || !$institute->affiliation) {
            return 'cbse'; // Default to CBSE
        }
        
        $affiliation = strtoupper($institute->affiliation);
        
        if (strpos($affiliation, 'CBSE') !== false) {
            return 'cbse';
        } elseif (strpos($affiliation, 'ICSE') !== false || strpos($affiliation, 'CISCE') !== false) {
            return 'icse';
        } elseif (strpos($affiliation, 'STATE') !== false || strpos($affiliation, 'BOARD') !== false) {
            return 'state';
        } elseif (strpos($affiliation, 'OPEN') !== false || strpos($affiliation, 'NIOS') !== false) {
            return 'open';
        }
        
        return 'cbse'; // Default fallback
    }

    /**
     * Get students based on selected branch
     */
    public function getStudents(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:product_details,product_id',
            'academic_year' => 'nullable|string'
        ]);

        $context = $this->getInstituteBranchContext();
        
        $students = StudentParentDetails::join('academic_transport_details as atd', 'atd.student_hash_id', '=', 'student_parent_details.student_hash_id')
            ->where('student_parent_details.institute_id', $context['institute_id'])
            ->where('atd.course_subtype_id', $request->branch_id)
            ->where('atd.institute_id', $context['institute_id'])
            ->when($request->academic_year, function($query) use ($request) {
                return $query->where('atd.academic_year', $request->academic_year);
            })
            ->select(
                'student_parent_details.student_hash_id',
                'student_parent_details.first_name',
                'student_parent_details.middle_name',
                'student_parent_details.last_name',
                'student_parent_details.father_first_name',
                'student_parent_details.father_middle_name',
                'student_parent_details.father_last_name',
                'student_parent_details.mother_first_name',
                'student_parent_details.mother_middle_name',
                'student_parent_details.mother_last_name',
                'student_parent_details.registration_number',
                'student_parent_details.dob',
                'student_parent_details.blood_group',
                'atd.section_id',
                'atd.course_type',
                'atd.course_subtype',
                'atd.academic_year',
            )
            ->get()
            ->map(function($student) {
                // Combine father name
                $fatherName = trim($student->father_first_name ?? '');
                if (!empty($student->father_middle_name)) {
                    $fatherName .= ' ' . trim($student->father_middle_name);
                }
                if (!empty($student->father_last_name)) {
                    $fatherName .= ' ' . trim($student->father_last_name);
                }
                
                // Combine mother name
                $motherName = trim($student->mother_first_name ?? '');
                if (!empty($student->mother_middle_name)) {
                    $motherName .= ' ' . trim($student->mother_middle_name);
                }
                if (!empty($student->mother_last_name)) {
                    $motherName .= ' ' . trim($student->mother_last_name);
                }
               
                return [
                    'student_hash_id' => $student->student_hash_id,
                    'full_name' => trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name),
                    'first_name' => $student->first_name,
                    'middle_name' => $student->middle_name,
                    'last_name' => $student->last_name,
                    'father_name' => $fatherName ?: '--',
                    'mother_name' => $motherName ?: '--',
                    'registration_number' => $student->registration_number,
                    'dob' => $student->dob,
                    'blood_group' => $student->blood_group,
                    'section_id' => $student->section_id,
                    'academic_year' => $student->academic_year
                ];
            });
    
        return response()->json([
            'success' => true,
            'students' => $students,
            'count' => $students->count()
        ]);
    }

    /**
     * Get exam names for a specific academic year and branch
     */
    public function getExamNames(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:product_details,product_id',
            'academic_year' => 'nullable|string'
        ]);
        
        $context = $this->getInstituteBranchContext();
        
        $examNames = ExamStructureOfflineExam::where('institute_id', $context['institute_id'])
            ->where('subtype_id', $request->branch_id)
            ->when($request->academic_year, function($query) use ($request) {
                return $query->where('academic_year', $request->academic_year);
            })
            ->with(['examNameDetail' => function($query) {
                $query->select('id', 'exam_name_id', 'name', 'academic_year', 'term');
            }])
            ->select('exam_name_id', 'academic_year')
            ->selectRaw('MAX(exam_date) as latest_exam_date')
            ->groupBy('exam_name_id', 'academic_year')
            ->orderBy('latest_exam_date', 'desc')
            ->get()
            ->map(function($exam) {
                return [
                    'exam_name_id' => $exam->exam_name_id,
                    'exam_name' => $exam->examNameDetail->name ?? 'N/A',
                    'academic_year' => $exam->academic_year,
                    'exam_date' => $exam->latest_exam_date,
                    'term' => $exam->examNameDetail->term ?? null
                ];
            });

        return response()->json([
            'success' => true,
            'exam_names' => $examNames
        ]);
    }

    public function getStudentExamMarks(Request $request)
    {
        $request->validate([
            'student_hash_id' => 'required|string',
            'exam_name_ids' => 'nullable|array',
            'exam_name_ids.*' => 'string',
            'academic_year' => 'nullable|string'
        ]);

        $context = $this->getInstituteBranchContext();
        
        // Get student details
        $student = StudentParentDetails::where('student_hash_id', $request->student_hash_id)
            ->where('institute_id', $context['institute_id'])
            ->with('address')
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        // Get academic details
        $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $request->student_hash_id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        // Get exam marks query with exam details
        $examMarksQuery = StudentExamMarks::with(['exam' => function($query) {
                $query->with(['subject', 'gradeSystem', 'examNameDetail']);
            }])
            ->where('student_hash_id', $request->student_hash_id)
            ->where('institute_id', $context['institute_id']);

        // Filter by multiple exam names if provided
        if ($request->has('exam_name_ids') && !empty($request->exam_name_ids)) {
            $examMarksQuery->whereHas('exam', function($query) use ($request) {
                $query->whereIn('exam_name_id', $request->exam_name_ids);
            });
        }

        // Filter by academic year if provided
        if ($request->academic_year && $request->academic_year !== '') {
            $examMarksQuery->whereHas('exam', function($query) use ($request) {
                $query->where('academic_year', $request->academic_year);
            });
        }

        $examMarks = $examMarksQuery->get();

        if ($examMarks->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No exam marks found for this student.'
            ], 404);
        }

        // Group marks by exam
        $examsGrouped = [];
        $allGradeSystems = [];
        $examNames = []; // To store unique exam names for the table header
        
        foreach ($examMarks as $mark) {
            $examId = $mark->exam_id;
            $examNameId = $mark->exam->exam_name_id;
            $examName = $mark->exam->examNameDetail->name ?? $mark->exam->exam_name ?? 'N/A';
            
            // Get section names for this exam
            $sectionNames = $this->getSectionNames(
                $mark->exam->section_id, 
                $mark->exam->subtype_id,
                $mark->exam->institute_id,
                $mark->exam->branch_id
            );
            
            // Store unique exam names for header
            if (!in_array($examNameId, array_keys($examNames))) {
                $examNames[$examNameId] = [
                    'id' => $examNameId,
                    'name' => $examName,
                    'date' => $mark->exam->exam_date ? Carbon::parse($mark->exam->exam_date)->format('d/m/Y') : null,
                    'sections' => $sectionNames,
                    'academic_year' => $mark->exam->academic_year
                ];
            }
            
            if (!isset($examsGrouped[$examId])) {
                // Get grade system details
                $gradeSystem = $mark->exam->gradeSystem;
                if ($gradeSystem && !in_array($gradeSystem->id, array_keys($allGradeSystems))) {
                    $allGradeSystems[$gradeSystem->id] = [
                        'id' => $gradeSystem->id,
                        'name' => $gradeSystem->name,
                        'description' => $gradeSystem->description,
                        'grade_ranges' => $gradeSystem->grade_ranges,
                        'is_default' => $gradeSystem->is_default
                    ];
                }
                
                $examsGrouped[$examId] = [
                    'exam_id' => $mark->exam_id,
                    'exam_name_id' => $examNameId,
                    'exam_name' => $examName,
                    'exam_date' => $mark->exam->exam_date ? Carbon::parse($mark->exam->exam_date)->format('d/m/Y') : null,
                    'academic_year' => $mark->exam->academic_year ?? null,
                    'sections' => $sectionNames,
                    'grade_system_id' => $mark->exam->grade_system_id,
                    'grade_system' => $gradeSystem ? [
                        'id' => $gradeSystem->id,
                        'name' => $gradeSystem->name,
                        'grade_ranges' => $gradeSystem->grade_ranges
                    ] : null,
                    'subjects' => []
                ];
            }
            
            // Calculate grade based on percentage using the grade system
            $percentage = $mark->total_marks > 0 ? round(($mark->obtained_marks / $mark->total_marks) * 100, 2) : 0;
            $calculatedGrade = $this->calculateGradeFromPercentage($percentage, $mark->exam->gradeSystem);
            
            // Organize by subject for easier display
            $subjectId = $mark->subject_id;
            if (!isset($examsGrouped[$examId]['subjects'][$subjectId])) {
                $examsGrouped[$examId]['subjects'][$subjectId] = [
                    'subject_id' => $subjectId,
                    'subject_name' => $mark->exam->subject->subject_name ?? 'N/A',
                    'subject_code' => $mark->exam->subject->subject_code ?? null,
                    'total_marks' => $mark->total_marks,
                    'obtained_marks' => $mark->obtained_marks,
                    'percentage' => $percentage,
                    'grade' => $calculatedGrade['grade'] ?? $mark->grade,
                    'grade_point' => $calculatedGrade['grade_point'] ?? $mark->grade_point,
                    'grade_details' => $calculatedGrade,
                    'passing_marks' => $mark->passing_marks,
                    'status' => $mark->obtained_marks >= $mark->passing_marks ? 'Pass' : 'Fail',
                    'remarks' => $mark->remarks,
                    'exam_name_id' => $examNameId,
                    'exam_name' => $examName
                ];
            }
        }

        // Reorganize data by subject with all exam marks
        $subjectsData = [];
        foreach ($examsGrouped as $examData) {
            foreach ($examData['subjects'] as $subjectData) {
                $subjectId = $subjectData['subject_id'];
                $examNameId = $subjectData['exam_name_id'];
                
                if (!isset($subjectsData[$subjectId])) {
                    $subjectsData[$subjectId] = [
                        'subject_id' => $subjectId,
                        'subject_name' => $subjectData['subject_name'],
                        'subject_code' => $subjectData['subject_code'],
                        'exam_marks' => []
                    ];
                }
                
                $subjectsData[$subjectId]['exam_marks'][$examNameId] = [
                    'total_marks' => $subjectData['total_marks'],
                    'obtained_marks' => $subjectData['obtained_marks'],
                    'percentage' => $subjectData['percentage'],
                    'grade' => $subjectData['grade'],
                    'grade_point' => $subjectData['grade_point'],
                    'status' => $subjectData['status'],
                    'remarks' => $subjectData['remarks']
                ];
            }
        }

        // Calculate exam-wise statistics
        $examStatistics = [];
        foreach ($examsGrouped as $examId => $examData) {
            $totalMarks = array_sum(array_column($examData['subjects'], 'total_marks'));
            $obtainedMarks = array_sum(array_column($examData['subjects'], 'obtained_marks'));
            $subjectCount = count($examData['subjects']);
            $passedSubjects = count(array_filter($examData['subjects'], function($subject) {
                return $subject['status'] === 'Pass';
            }));
            
            $examStatistics[$examId] = [
                'exam_name' => $examData['exam_name'],
                'total_marks' => $totalMarks,
                'obtained_marks' => $obtainedMarks,
                'percentage' => $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0,
                'subject_count' => $subjectCount,
                'passed_subjects' => $passedSubjects,
                'failed_subjects' => $subjectCount - $passedSubjects,
                'overall_status' => $passedSubjects === $subjectCount ? 'Pass' : 'Fail'
            ];
        }

        // Calculate overall statistics
        $allSubjects = [];
        foreach ($examsGrouped as $examData) {
            foreach ($examData['subjects'] as $subject) {
                $allSubjects[] = $subject;
            }
        }
        
        $totalMarksOverall = array_sum(array_column($allSubjects, 'total_marks'));
        $obtainedMarksOverall = array_sum(array_column($allSubjects, 'obtained_marks'));
        $passedSubjectsOverall = count(array_filter($allSubjects, function($subject) {
            return $subject['status'] === 'Pass';
        }));

        // Get available exam names for this student
        $availableExamNames = ExamStructureOfflineExam::whereIn('exam_id', function($query) use ($request) {
            $query->select('exam_id')
                ->from('student_exam_marks')
                ->where('student_hash_id', $request->student_hash_id);
        })
        ->with('examNameDetail')
        ->select('exam_name_id', 'academic_year', 'exam_date', 'section_id', 'subtype_id', 'branch_id')
        ->distinct('exam_name_id')
        ->orderBy('exam_date', 'desc')
        ->get()
        ->map(function($exam) {
            // Get section names for each available exam
            $sectionNames = $this->getSectionNames(
                $exam->section_id,
                $exam->subtype_id,
                $exam->institute_id,
                $exam->branch_id
            );
            
            return [
                'exam_name_id' => $exam->exam_name_id,
                'exam_name' => $exam->examNameDetail->name ?? 'N/A',
                'academic_year' => $exam->academic_year,
                'exam_date' => $exam->exam_date,
                'term' => $exam->examNameDetail->term ?? null,
                'sections' => $sectionNames
            ];
        });

        // Format student section name
        $studentSectionDisplay = '--';
        if ($academicDetails && $academicDetails->section_id) {
            $studentSectionDisplay = $this->getSectionNames(
                $academicDetails->section_id,
                $academicDetails->course_subtype_id,
                $context['institute_id'],
                $context['branch_id']
            );
        }

        // Format academic details with section display
        $academicDetailsArray = $academicDetails ? $academicDetails->toArray() : null;
        if ($academicDetailsArray) {
            $academicDetailsArray['section_display'] = $studentSectionDisplay;
            $academicDetailsArray['course_type_display'] = $academicDetails->course_type ?? '--';
        }

        // Combine father name
        $fatherName = trim($student->father_first_name ?? '');
        if (!empty($student->father_middle_name)) {
            $fatherName .= ' ' . trim($student->father_middle_name);
        }
        if (!empty($student->father_last_name)) {
            $fatherName .= ' ' . trim($student->father_last_name);
        }
        
        // Combine mother name
        $motherName = trim($student->mother_first_name ?? '');
        if (!empty($student->mother_middle_name)) {
            $motherName .= ' ' . trim($student->mother_middle_name);
        }
        if (!empty($student->mother_last_name)) {
            $motherName .= ' ' . trim($student->mother_last_name);
        }

        return response()->json([
            'success' => true,
            'student' => [
                'student_hash_id' => $student->student_hash_id,
                'full_name' => trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name),
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'blood_group' => $student->blood_group,
                'address' => $student->address->student_perm_address_line1,
                'father_name' => $fatherName ?: '--',
                'mother_name' => $motherName ?: '--',
                'dob' => $student->dob ? Carbon::parse($student->dob)->format('d/m/Y') : null,
                'registration_number' => $student->registration_number,
                'roll_number' => $student->roll_number ?? null
            ],
            'academic_details' => $academicDetailsArray,
            'exams' => array_values($examsGrouped),
            'subjects_data' => array_values($subjectsData),
            'exam_names' => array_values($examNames),
            'exam_statistics' => $examStatistics,
            'available_exam_names' => $availableExamNames,
            'grade_systems' => array_values($allGradeSystems),
            'statistics' => [
                'total_exams' => count($examsGrouped),
                'total_subjects' => count($allSubjects),
                'passed_subjects' => $passedSubjectsOverall,
                'failed_subjects' => count($allSubjects) - $passedSubjectsOverall,
                'pass_percentage' => count($allSubjects) > 0 ? round(($passedSubjectsOverall / count($allSubjects)) * 100, 2) : 0,
                'total_marks' => $totalMarksOverall,
                'obtained_marks' => $obtainedMarksOverall,
                'overall_percentage' => $totalMarksOverall > 0 ? round(($obtainedMarksOverall / $totalMarksOverall) * 100, 2) : 0
            ]
        ]);
    }
    /**
     * Get section names from section IDs
     */
    private function getSectionNames($sectionIds, $productId, $instituteId, $branchId = null)
    {
        if (empty($sectionIds)) {
            return 'All Sections';
        }

        // Convert to array if it's a string
        if (is_string($sectionIds)) {
            $sectionIds = array_map('trim', explode(',', $sectionIds));
        }

        // Get section data from course_fee_structures
        $sectionData = DB::table('course_fee_structures')
            ->where('product_id', $productId)
            ->where('institute_id', $instituteId)
            ->when($branchId, function ($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
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

        // Create mapping of section_id to section_name
        $map = [];
        foreach ($sections as $section) {
            $key = $section['section_id'] ?? $section['id'] ?? null;
            $value = $section['section_name'] ?? $section['name'] ?? null;
            
            if ($key && $value) {
                $map[$key] = $value;
            }
        }

        // Map section IDs to names
        return collect($sectionIds)
            ->map(fn($id) => $map[$id] ?? $id)
            ->implode(', ');
    }
     
    /**
     * Get display name for a single section ID
     */
    public function getSectionDisplayName($sectionId, $productId, $instituteId, $branchId = null)
    {
        if (empty($sectionId)) {
            return 'All Sections';
        }

        $sectionData = DB::table('course_fee_structures')
            ->where('product_id', $productId)
            ->where('institute_id', $instituteId)
            ->when($branchId, function ($q) use ($branchId) {
                return $q->where('branch_id', $branchId);
            }, function ($q) {
                return $q->whereNull('branch_id');
            })
            ->value('sections');

        if (!$sectionData) {
            return $sectionId;
        }

        $sections = json_decode($sectionData, true);
        if (!is_array($sections)) {
            return $sectionId;
        }

        foreach ($sections as $section) {
            $key = $section['section_id'] ?? $section['id'] ?? null;
            $value = $section['section_name'] ?? $section['name'] ?? null;
            
            if ($key == $sectionId && $value) {
                return $value;
            }
        }

        return $sectionId;
    }
    /**
     * Calculate grade from percentage based on grade system
     */
    private function calculateGradeFromPercentage($percentage, $gradeSystem)
    {
        if (!$gradeSystem || !$gradeSystem->grade_ranges) {
            return [
                'grade' => null,
                'grade_point' => null,
                'description' => null
            ];
        }
        
        $gradeRanges = $gradeSystem->grade_ranges;
        
        foreach ($gradeRanges as $range) {
            $min = floatval($range['min_percentage'] ?? 0);
            $max = floatval($range['max_percentage'] ?? 100);
            
            if ($percentage >= $min && $percentage <= $max) {
                return [
                    'grade' => $range['grade'] ?? null,
                    'grade_point' => $range['grade_point'] ?? null,
                    'description' => $range['description'] ?? null,
                    'min_percentage' => $min,
                    'max_percentage' => $max
                ];
            }
        }
        
        return [
            'grade' => null,
            'grade_point' => null,
            'description' => null
        ];
    }

    /**
     * Get grade systems for the institute
     */
    public function getGradeSystems(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        $gradeSystems = GradeSystem::where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->where('is_active', true)
            ->get();

        return response()->json([
            'success' => true,
            'grade_systems' => $gradeSystems
        ]);
    }

public function downloadPdf(Request $request)
{
    $data = $request->all();
    
    // Get the context to access institute data
    $context = $this->getInstituteBranchContext();
    
    // Get institute details for the PDF
    $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
    
    // Prepare the data structure that your PDF view expects
    $pdfData = [
        'institute' => $institute,
        'session' => $data['academic_details']['academic_year'] ?? date('Y'),
        'student' => (object) $data['student'], // Convert to object for arrow syntax
        'academic' => (object) $data['academic_details'], // Convert to object
        'examNames' => $data['exam_names'] ?? [],
        'subjects' => $this->prepareSubjectsForPdf($data['subjects_data'] ?? [], $data['exam_names'] ?? []),
        'statistics' => $data['statistics'] ?? [],
        'boardFormat' => $this->determineBoardFormat($institute)
    ];
    
    // Add calculated fields
    $pdfData['statistics']['result'] = ($pdfData['statistics']['passed_subjects'] ?? 0) == ($pdfData['statistics']['total_subjects'] ?? 0) 
        ? 'PASS' 
        : 'FAIL';
    
    $pdf = Pdf::loadView(
        'instituteAdmin.ReportCard.pdf.report-card-pdf',
        $pdfData
    )->setPaper('a4', 'portrait');

    return $pdf->download('Report_Card.pdf');
}

/**
 * Prepare subjects data with calculated totals
 */
private function prepareSubjectsForPdf($subjectsData, $examNames)
{
    $preparedSubjects = [];
    
    foreach ($subjectsData as $subject) {
        $subjectTotalObtained = 0;
        $subjectTotalMax = 0;
        
        // Calculate totals for this subject across all exams
        foreach ($examNames as $exam) {
            $examId = $exam['id'];
            if (isset($subject['exam_marks'][$examId])) {
                $marks = $subject['exam_marks'][$examId];
                $subjectTotalObtained += floatval($marks['obtained_marks'] ?? 0);
                $subjectTotalMax += floatval($marks['total_marks'] ?? 0);
            }
        }
        
        $preparedSubjects[] = [
            'subject_id' => $subject['subject_id'],
            'subject_name' => $subject['subject_name'],
            'exam_marks' => $subject['exam_marks'] ?? [],
            'total_obtained' => $subjectTotalObtained . ' / ' . $subjectTotalMax,
            'percentage' => $subjectTotalMax > 0 
                ? round(($subjectTotalObtained / $subjectTotalMax) * 100, 2) 
                : 0
        ];
    }
    
    return $preparedSubjects;
}


    
}