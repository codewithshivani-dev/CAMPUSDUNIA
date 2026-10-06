<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductDetails;
use App\Models\SubjectsCoursewise;
use App\Models\InstituteBasicDetails;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SubjectCoursewiseController extends Controller
{
    use \App\Traits\InstituteBranchAccess; 

    public function index()
    {
        $context = $this->getInstituteBranchContext();
        
        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $merchantId = auth()->user()->institute_id;
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->with(['stakeholders','stakeholderDocuments','authorizedUser','authorizedUserDocuments', 'documents','beneficiary'])
            ->first();

        // Get department categories for current institute/branch
        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->select('department_category_id', 'category_name')
            ->orderBy('category_name')
            ->get();
            
        // Get distinct departments
        $departments = DB::table('departments')
            ->select('department_id', 'department')
            ->orderBy('department')
            ->get();
            
        // Get distinct semesters
        $sem = SubjectsCoursewise::select('semester_id')
            ->distinct()
            ->orderBy('semester_id')
            ->get();
        
        // Get distinct Subject
        $subjectsList = SubjectsCoursewise::select('subject_name')
            ->distinct()
            ->orderBy('subject_name')
            ->get();

        // Get years
        $years = range(date('Y'), date('Y') + 5);

        // Get subjects with institute/branch context
        $subjects = SubjectsCoursewise::select(
                'subjects_coursewise.*',
                'product_details.course_type',
                'product_details.sub_type',
                'departments.department',
                'department_categories.category_name as category_name'
            )
            ->join('product_details', 'subjects_coursewise.course_detail_id', '=', 'product_details.product_id')
            ->leftJoin('departments', 'subjects_coursewise.department_id', '=', 'departments.department_id')
            ->leftJoin('department_categories', 'departments.department_category_id', '=', 'department_categories.department_category_id')
            ->where('subjects_coursewise.institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('subjects_coursewise.branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('subjects_coursewise.branch_id');
            })
            ->latest()
            ->get();

        // Load sub-subjects for each subject with proper context
        foreach ($subjects as $subject) {
            $subject->sub_subjects = \App\Models\SubSubject::where('subject_id', $subject->subject_id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->get();
                
        }

        return view('instituteAdmin.CourseFiles.SubjectCoursewise', compact('departments', 'fincapMerchants', 'categories', 'years', 'subjects', 'sem', 'subjectsList'));
    }

    public function getSemesters($productId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ]);
        }

        // Use getCommonQuery to ensure product belongs to current institute/branch
        $record = $this->getCommonQuery(ProductDetails::class)
            ->where('product_id', $productId)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found or you do not have access.',
                'semesters' => []
            ]);
        }

        // Decode semesters safely
        $semesters = [];

        if ($record->semesters) {
            $raw = $record->semesters;
            
            // Debug: Log the raw value
            \Log::info('Raw semesters value:', ['raw' => $raw]);
            
            // Try to decode JSON
            $decoded = json_decode($raw, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Case 1: If the array has only one element that's a string, it might be double-encoded
                if (count($decoded) === 1 && is_string($decoded[0])) {
                    \Log::info('Found single string element, trying to decode again:', ['element' => $decoded[0]]);
                    
                    $innerDecoded = json_decode($decoded[0], true);
                    
                    if (json_last_error() === JSON_ERROR_NONE && is_array($innerDecoded)) {
                        $semesters = $innerDecoded;
                    } else {
                        // Try to parse as string representation of array
                        $clean = str_replace(['[', ']', '"', "'"], '', $decoded[0]);
                        $semesters = array_map('trim', explode(',', $clean));
                    }
                } else {
                    // Case 2: It's already a proper array
                    $semesters = $decoded;
                }
            } else {
                // Case 3: It's not valid JSON, try to parse as string
                \Log::info('Not valid JSON, parsing as string:', ['raw' => $raw]);
                
                // Remove outer brackets and quotes
                $clean = trim($raw, "[]\"'");
                
                // Check if it contains inner JSON
                if (strpos($clean, '[') === 0) {
                    $innerClean = trim($clean, "[]\"'");
                    $semesters = array_map('trim', explode(',', $innerClean));
                } else {
                    $semesters = array_map('trim', explode(',', $clean));
                }
            }
        }

        // Final cleanup
        $semesters = array_filter($semesters, function($semester) {
            $clean = trim($semester, "\"'[] \t\n\r\0\x0B");
            return !empty($clean) && $clean !== 'null';
        });

        // Trim each semester value
        $semesters = array_map(function($semester) {
            return trim($semester, "\"'[] \t\n\r\0\x0B");
        }, $semesters);

        return response()->json([
            'success' => true,
            'semesters' => array_values($semesters),
            'debug' => [
                'raw' => $record->semesters,
                'processed_count' => count($semesters)
            ]
        ]);
    }

    public function store(Request $request)
    {
     
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        // Validation rules
        $request->validate([
            'department_id'   => 'required',
            'course_detail_id'=> 'required',
            'semester_option' => 'required|in:all,specific',
            'assigned_date'   => 'required|date',
            'remarks'         => 'nullable|string|max:255',
            'subjects'        => 'required|array|min:1',
            'subjects.*.name' => 'required|string|max:255',
            'subjects.*.sub_subjects' => 'nullable|array',
            'subjects.*.sub_subjects.*' => 'nullable|string|max:255',
        ]);

        // Validate specific semester if option is selected
        if ($request->semester_option === 'specific') {
            $request->validate([
                'semester_id' => 'required|string',
            ]);
        }

        // Verify department access
        $department = $this->getCommonQuery(Departments::class)
            ->where('department_id', $request->department_id)
            ->first();

        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid department selected or you do not have access.'
            ], 403);
        }

        // Verify product access
        $product = $this->getCommonQuery(ProductDetails::class)
            ->where('product_id', $request->course_detail_id)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid course selected or you do not have access.'
            ], 403);
        }

        // Verify semester if specific option is selected
        if ($request->semester_option === 'specific') {
            // Get available semesters for this product
            $availableSemesters = [];
            if ($product->semesters) {
                $raw = $product->semesters;
                $decoded = json_decode($raw, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // Handle double-encoded JSON
                    if (count($decoded) === 1 && is_string($decoded[0])) {
                        $innerDecoded = json_decode($decoded[0], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($innerDecoded)) {
                            $availableSemesters = $innerDecoded;
                        } else {
                            $clean = str_replace(['[', ']', '"', "'"], '', $decoded[0]);
                            $availableSemesters = array_map('trim', explode(',', $clean));
                        }
                    } else {
                        $availableSemesters = $decoded;
                    }
                } else {
                    $clean = trim($raw, "[]\"'");
                    if (strpos($clean, '[') === 0) {
                        $innerClean = trim($clean, "[]\"'");
                        $availableSemesters = array_map('trim', explode(',', $innerClean));
                    } else {
                        $availableSemesters = array_map('trim', explode(',', $clean));
                    }
                }
            }
            
            // Clean and validate
            $availableSemesters = array_map(function($sem) {
                return trim($sem, "\"'[] \t\n\r\0\x0B");
            }, $availableSemesters);
            
            $availableSemesters = array_filter($availableSemesters, function($sem) {
                return !empty($sem) && $sem !== 'null';
            });
            
            // Check if selected semester is valid
            if (!in_array($request->semester_id, $availableSemesters)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid semester/term selected. Available: ' . implode(', ', $availableSemesters)
                ], 422);
            }
        }

        $successCount = 0;
        $subSubjectCount = 0;
        
        // Determine semester for the subjects
        $semesterValue = $request->semester_option === 'all' ? 'all_semesters' : $request->semester_id;

        DB::beginTransaction();
        try {
            foreach ($request->subjects as $subjectData) {
                if (!empty(trim($subjectData['name']))) {
                    // Generate unique subject ID
                    $subjectPrefix = strtoupper(substr(preg_replace('/\s+/', '', $subjectData['name']), 0, 5));
                    do {
                        $randomDigits = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
                        $subjectId = $subjectPrefix . $randomDigits;
                    } while (SubjectsCoursewise::where('subject_id', $subjectId)->exists());

                    // Create main subject record
                    $subjectRecordData = $this->createWithInstituteBranchContext([
                        'subject_id'       => $subjectId,
                        'department_id'    => $request->department_id,
                        'course_detail_id' => $request->course_detail_id,
                        'subject_name'     => trim($subjectData['name']),
                        'semester_id'      => $semesterValue,
                        'assigned_date'    => $request->assigned_date,
                        'remarks'          => $request->remarks,
                        'status'           => 'Active',
                    ]);

                    $subjectRecord = SubjectsCoursewise::create($subjectRecordData);
                    $successCount++;

                    // Create sub-subjects if any
                    if (!empty($subjectData['sub_subjects'])) {
                        $subSubCounter = 1;
                        foreach ($subjectData['sub_subjects'] as $subSubjectName) {
                            if (!empty(trim($subSubjectName))) {
                                // Generate sub-subject ID based on parent subject ID
                                $subSubjectId = $subjectId . '-' . str_pad($subSubCounter, 3, '0', STR_PAD_LEFT);
                                
                                // Check if sub-subject ID already exists
                                while (\App\Models\SubSubject::where('sub_subject_id', $subSubjectId)->exists()) {
                                    $subSubCounter++;
                                    $subSubjectId = $subjectId . '-' . str_pad($subSubCounter, 3, '0', STR_PAD_LEFT);
                                }

                                // Create sub-subject record
                                $subSubjectData = $this->createWithInstituteBranchContext([
                                    'sub_subject_id' => $subSubjectId,
                                    'sub_subject_name' => trim($subSubjectName),
                                    'subject_id' => $subjectId,
                                    'status' => 'active',
                                ]);

                                \App\Models\SubSubject::create($subSubjectData);
                                $subSubjectCount++;
                                $subSubCounter++;
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$successCount} subject(s) and {$subSubjectCount} sub-subject(s) added successfully!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving subjects: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save subjects. Please try again.',
                'error' => env('APP_DEBUG') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($id)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $subject = SubjectsCoursewise::select(
                'subjects_coursewise.*',
                'product_details.course_type',
                'product_details.sub_type'
            )
            ->join('product_details', 'subjects_coursewise.course_detail_id', '=', 'product_details.product_id')
            ->where('subjects_coursewise.id', $id)
            ->where('subjects_coursewise.institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('subjects_coursewise.branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('subjects_coursewise.branch_id');
            })
            ->first();

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found or you do not have access.'
            ], 404);
        }

        // Get sub-subjects for this subject with proper context
        $subSubjects = \App\Models\SubSubject::where('subject_id', $subject->subject_id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $subject,
            'sub_subjects' => $subSubjects
        ]);
    }

    public function destroy($id)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $subject = SubjectsCoursewise::where('id', $id)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->first();

        if (!$subject) {
            return response()->json([
                'success' => false,
                'message' => 'Subject not found or you do not have access.'
            ], 404);
        }

        $subject->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subject deleted successfully!'
        ]);
    }

    public function getSubSubjects($subjectId)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
    
        $subSubjects = \App\Models\SubSubject::where('subject_id', $subjectId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->get();
    
        return response()->json([
            'success' => true,
            'data' => $subSubjects
        ]);
    }
    
    public function AddNewSubject()
    {
        $context = $this->getInstituteBranchContext();
        
        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $merchantId = auth()->user()->institute_id;
        
        // Get department categories for current institute/branch
        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->select('department_category_id', 'category_name')
            ->orderBy('category_name')
            ->get();
            
        return view('instituteAdmin.CourseFiles.AddNewSubject', compact('categories'));
    }
}