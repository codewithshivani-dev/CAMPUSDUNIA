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
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\GradeSystem;
use Illuminate\Support\Facades\Log;

class CreateExamStructureController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    
    public function index()
    {
        $exams = ExamStructureOfflineExam::with(['department', 'course', 'subject', 'classroom'])
            ->orderBy('exam_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(20);
        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $instituteId = $context['institute_id'];
        $branchId = $context['branch_id'] ?? null;
        $user = Auth::user();
        $categories = DepartmentCategory::where('institute_id', $instituteId)->get();
         $gradeSystems =GradeSystem::where('institute_id', $instituteId)->get();
        return view('instituteAdmin.ExamStructure.examStructureOffline', compact('categories','gradeSystems'));
    }

    public function store(Request $request)
    {
       
            // Since you're sending exams as an array of objects
            $examsData = $request->input('exams', []);
            $context = $this->getInstituteBranchContext();
            
            if (empty($examsData) || !is_array($examsData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No exam data provided.'
                ], 400);
            }

            $errors = [];
            $successCount = 0;
            $createdExams = [];
            
            foreach ($examsData as $index => $examData) {
                $validator = Validator::make($examData, [
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
                    'end_time' => 'required',
                    'classroom_id' => 'required',
                    'total_marks' => 'required|numeric|min:1',
                    'passing_marks' => 'required|numeric|min:0',
                    'duration_minutes' => 'required|numeric|min:15',
                ]);
                
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $error) {
                        $errors[] = "Subject #" . ($index + 1) . ": " . $error;
                    }
                    continue;
                }

                // Convert comma separated section IDs to array
                $sectionIds = explode(',', $examData['section_id']);
                
                // Clean up section IDs
                $sectionIds = array_filter(array_map('trim', $sectionIds));
                
                if (empty($sectionIds)) {
                    $errors[] = "Subject #" . ($index + 1) . ": No valid sections selected";
                    continue;
                }
                
                $examID = 'EXAM-' . time() . rand(10, 99) . '-' . ($index + 1);

                
                    // Create exam
                    $exam = ExamStructureOfflineExam::create([
                        'institute_id' => $context['institute_id'] ?? $examData['institute_id'],
                        'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                        'department_category_id' => $examData['department_category_id'],
                        'department_id' => $examData['department_id'],
                        'course_id' => $examData['course_id'],
                        'subtype_id' => $examData['subtype_id'],
                        'subject_id' => $examData['subject_id'],
                        'semester_id' => $examData['semester_id'] ?? null,
                        'section_id' => $examData['section_id'], // Store as comma-separated string
                        'exam_id' => $examID,
                        'exam_name' => $examData['exam_name'],
                        'grade_system_id'=> $examData['grade_system_id'],
                        'exam_date' => $examData['exam_date'],
                        'start_time' => $examData['start_time'],
                        'end_time' => $examData['end_time'],
                        'duration_minutes' => $examData['duration_minutes'],
                        'classroom_id' => $examData['classroom_id'],
                        'total_marks' => $examData['total_marks'],
                        'passing_marks' => $examData['passing_marks'],
                        'status' => $examData['status'] ?? 'draft',
                        'is_published' => $examData['is_published'] ?? false,
                    ]);
                    
                    $successCount++;
                    $createdExams[] = $exam;
                    
            }

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => implode("\n", $errors),
                    'created_count' => $successCount,
                    'error_count' => count($errors)
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => $successCount . ' exam(s) created successfully.',
                'created_count' => $successCount,
                'exams' => $createdExams
            ], 201);
            
        
    }
}