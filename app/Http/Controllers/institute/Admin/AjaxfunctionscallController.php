<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Illuminate\Http\Request;
use App\Models\DepartmentCategory;
use App\Models\Departments;
use App\Models\AddBlock;
use App\Models\AddFloor;
use App\Models\AddRooms;
use App\Models\EmployeeDetails;
use App\Models\ProductDetails;
use App\Models\SubjectsCoursewise;
use App\Models\StudentParentDetails;
use Illuminate\Support\Facades\DB;
use App\Models\CourseFeeStructure;
use Illuminate\Support\Facades\Auth;

class AjaxfunctionscallController extends Controller
{
     use InstituteBranchAccess, DepartmentRelationships;

    // In your AjaxFunctionsController or similar
    public function getDepartmentsByCategory(Request $request)
    {
        $departments = Departments::with(['shift'])
            ->where('department_category_id', $request->category_id)
            ->where('institute_id', auth()->user()->institute_id)
            ->get()
            ->map(function($dept) {
                return [
                    'department_id' => $dept->department_id,
                    'department' => $dept->department,
                    'employees_count' => $dept->employees_count ?? 0,
                    'shift_id' => $dept->shift_id,
                    'shift_name' => $dept->shift->shift_name ?? null
                ];
            });

        return response()->json([
            'success' => true,
            'departments' => $departments
        ]);
    }

    protected function isCategoryInScopeByCustomId($departmentCategoryId)
    {
        return $this->getCommonQuery(DepartmentCategory::class)
            ->where('department_category_id', $departmentCategoryId)
            ->exists();
    }

    protected function getDepartmentsByCategoryCustom($departmentCategoryId)
    {
        return $this->getCommonQuery(Departments::class)
            ->where('department_category_id', $departmentCategoryId)
            ->orderBy('department')
            ->get();
    }

    /**
     * Get employees by department (AJAX)
     * URL: GET /ajax/employees-by-department?department_id=1
     */
     public function getEmployeesByDepartmentAjax(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id'
        ]);

        try {
            // Get the department using department_id
            $department = Departments::where('department_id', $request->department_id)
                ->where('institute_id', $this->getCurrentInstituteId())
                ->when($this->isBranchAdmin(), function($query) {
                    $query->where('branch_id', $this->getCurrentBranchId());
                }, function($query) {
                    $query->whereNull('branch_id');
                })
                ->first();

            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found in your scope'
                ], 404);
            }

            // Get employees - try without designation relation first
            $employees = EmployeeDetails::where('department_id', $department->department_id)
                ->where('department_category_id', $department->department_category_id)
                ->where('institute_id', $this->getCurrentInstituteId())
                ->when($this->isBranchAdmin(), function($query) {
                    $query->where('branch_id', $this->getCurrentBranchId());
                }, function($query) {
                    $query->whereNull('branch_id');
                })
                ->orderBy('name')
                ->get()
                ->map(function($employee) {
                    // Check if designation relationship exists, otherwise use the field
                    $designationName = 'N/A';
                    
                    // Try to get designation from relationship if it exists
                    if (method_exists($employee, 'designationRelation') && $employee->designationRelation) {
                        $designationName = $employee->designationRelation->designation_name ?? 'N/A';
                    } 
                    // Otherwise, use the designation field directly
                    elseif (isset($employee->designation)) {
                        $designationName = $employee->designation;
                    }
                    // Try other possible field names
                    elseif (isset($employee->designation_name)) {
                        $designationName = $employee->designation_name;
                    }

                    return [
                        'id' => $employee->id,
                        'employee_id' => $employee->employee_id,
                        'employee_code' => $employee->employee_code,
                        'name' => $employee->name,
                        'designation' => $designationName,
                        'email' => $employee->email,
                        'mobile_number' => $employee->mobile_number,
                    ];
                });

            return response()->json([
                'success' => true,
                'employees' => $employees,
                'count' => $employees->count(),
                'debug' => [
                    'department_id' => $department->department_id,
                    'department_category_id' => $department->department_category_id,
                    'department_name' => $department->department
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching employees by department: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employees: ' . $e->getMessage()
            ], 500);
        }
    }

     public function debugEmployeeDataStructure(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id'
        ]);

        try {
            $department = Departments::where('department_id', $request->department_id)->first();
            
            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found'
                ], 404);
            }

            // Check sample employee structure
            $sampleEmployee = EmployeeDetails::first();
            $columns = $sampleEmployee ? array_keys($sampleEmployee->getAttributes()) : [];
            
            // Check different query combinations
            $employeesWithDeptId = EmployeeDetails::where('department_id', $department->department_id)->get();
            $employeesWithBothIds = EmployeeDetails::where('department_id', $department->department_id)
                ->where('department_category_id', $department->department_category_id)
                ->get();

            // Check with scope
            $employeesWithScope = $this->getCommonQuery(EmployeeDetails::class)
                ->where('department_id', $department->department_id)
                ->where('department_category_id', $department->department_category_id)
                ->get();

            return response()->json([
                'success' => true,
                'debug_info' => [
                    'department' => [
                        'id' => $department->id,
                        'department_id' => $department->department_id,
                        'department_category_id' => $department->department_category_id,
                        'department_name' => $department->department
                    ],
                    'employee_table_columns' => $columns,
                    'query_results' => [
                        'employees_with_department_id_only' => [
                            'query' => "department_id = {$department->department_id}",
                            'count' => $employeesWithDeptId->count(),
                            'sample' => $employeesWithDeptId->take(2)->map(function($emp) {
                                return [
                                    'id' => $emp->id,
                                    'employee_id' => $emp->employee_id,
                                    'name' => $emp->name,
                                    'department_id' => $emp->department_id,
                                    'department_category_id' => $emp->department_category_id
                                ];
                            })
                        ],
                        'employees_with_both_ids' => [
                            'query' => "department_id = {$department->department_id} AND department_category_id = {$department->department_category_id}",
                            'count' => $employeesWithBothIds->count(),
                            'sample' => $employeesWithBothIds->take(2)->map(function($emp) {
                                return [
                                    'id' => $emp->id,
                                    'employee_id' => $emp->employee_id,
                                    'name' => $emp->name,
                                    'department_id' => $emp->department_id,
                                    'department_category_id' => $emp->department_category_id
                                ];
                            })
                        ],
                        'employees_with_scope' => [
                            'count' => $employeesWithScope->count(),
                            'sample' => $employeesWithScope->take(2)->map(function($emp) {
                                return [
                                    'id' => $emp->id,
                                    'employee_id' => $emp->employee_id,
                                    'name' => $emp->name,
                                    'institute_id' => $emp->institute_id,
                                    'branch_id' => $emp->branch_id
                                ];
                            })
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Debug failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentsByDepartmentAjax(Request $request)
    {
      
        $request->validate([
            'department_id' => 'required|exists:departments,department_id'
        ]);
     
        $department = Departments::where('department_id', $request->department_id)
                ->where('institute_id', $this->getCurrentInstituteId())
                ->when($this->isBranchAdmin(), function($query) {
                    $query->where('branch_id', $this->getCurrentBranchId());
                }, function($query) {
                    $query->whereNull('branch_id');
                })
                ->first();
   
            if (!$department) {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found'
                ], 404);
            }

            // Get students through academic transport details for this department's category
            $students = $this->getCommonQuery(\App\Models\StudentParentDetails::class)
                ->whereHas('academicTransportDetails', function($q) use ($department) {
                    $q->where('department_id', $department->department_id);
                })
                ->select('id','student_hash_id', 'first_name','middle_name','last_name','registration_number')
                ->get();

            return response()->json([
                'success' => true,
                'students' => $students,
                'count' => $students->count()
            ]);
    } 
    
    /**
     * Get course types by department (AJAX)
     * URL: GET /ajax/course-types-by-department?department_id=1
     */
    // AJAX Controller Method for getting courses by department
    public function getCourseTypesByDepartmentAjax(Request $request)
    {
        $user = Auth::user();
    
        if (!$user || !$user->institute_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not associated with any institute.'
            ]);
        }
    
        $departmentId = $request->input('department_id');
    
        if (!$departmentId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Department ID is required.'
            ]);
        }
    
        /*
        |--------------------------------------------------------------------------
        | Current Institute
        |--------------------------------------------------------------------------
        |
        | Main Institute:
        |   user.institute_id = main institute ID
        |
        | Branch Admin:
        |   user.institute_id = branch's own institute ID
        |   user.branch_id    = parent institute ID
        |
        */
    
        $instituteId = $user->institute_id;
    
        /*
        |--------------------------------------------------------------------------
        | Verify Department
        |--------------------------------------------------------------------------
        */
    
        $department = DB::table('departments')
            ->where('department_id', $departmentId)
            ->where('institute_id', $instituteId)
            ->first();
    
        if (!$department) {
            return response()->json([
                'status' => 'error',
                'message' => 'Department not found or access denied.'
            ]);
        }
    
        /*
        |--------------------------------------------------------------------------
        | Get Courses
        |--------------------------------------------------------------------------
        |
        | Courses belonging to the currently logged-in institute/branch.
        |
        */
    
        $courses = DB::table('fincap_merchant_sub_categories')
            ->where('institute_id', $instituteId)
            ->where('department_id', $departmentId)
            ->select(
                'finacp_merchant_sub_category_id',
                'finacp_merchant_sub_category_type'
            )
            ->orderBy('finacp_merchant_sub_category_type')
            ->get();
    
        return response()->json([
            'status' => 'success',
            'courses' => $courses
        ]);
    }
    
    // public function getCourseTypesByDepartmentAjax(Request $request)
    // {
    //     // Get institute/branch context
    //     $context = $this->getInstituteBranchContext();
    //     if (!$context['institute_id']) {
    //         return response()->json(['status' => 'error', 'message' => 'You are not associated with any institute.']);
    //     }

    //     $departmentId = $request->input('department_id');
    //     if (!$departmentId) {
    //         return response()->json(['status' => 'error', 'message' => 'Department ID is required']);
    //     }

    //     // Verify department belongs to current institute/branch scope
    //     $departmentQuery = DB::table('departments')
    //         ->where('department_id', $departmentId)
    //         ->where('institute_id', $context['institute_id']);

    //     if ($context['is_branch_admin'] && $context['branch_id']) {
    //         $departmentQuery->where('branch_id', $context['branch_id']);
    //     } else {
    //         $departmentQuery->whereNull('branch_id');
    //     }

    //     $department = $departmentQuery->first();
    //     if (!$department) {
    //         return response()->json(['status' => 'error', 'message' => 'Department not found or access denied.']);
    //     }

    //     // Get courses with institute/branch scope
    //     $coursesQuery = DB::table('fincap_merchant_sub_categories')
    //         ->where('institute_id', $context['institute_id'])
    //         ->where('department_id', $departmentId);

    //     if ($context['is_branch_admin'] && $context['branch_id']) {
    //         $coursesQuery->where('branch_id', $context['branch_id']);
    //     } else {
    //         $coursesQuery->whereNull('branch_id');
    //     }

    //     $courses = $coursesQuery->select('finacp_merchant_sub_category_id', 'finacp_merchant_sub_category_type')
    //         ->orderBy('finacp_merchant_sub_category_type')
    //         ->get();

    //     return response()->json([
    //         'status' => 'success', 
    //         'courses' => $courses
    //     ]);
    // }
    
     // AJAX Controller Method for getting branches by course
    // public function getBranchesByCourse(Request $request)
    // {
    //     try {       
    //       // Get institute/branch context
    //         $context = $this->getInstituteBranchContext();   
    //         if (!$context['institute_id']) {
    //             return response()->json(['status' => 'error', 'message' => 'You are not associated with any institute.']);
    //         }

    //         $departmentId = $request->input('department_id');
    //         $courseType = $request->input('course_type');

    //         if (!$departmentId || !$courseType) {
    //             return response()->json(['status' => 'error', 'message' => 'Department ID and Course Type required']);
    //         }

    //         // Verify department belongs to current institute/branch scope
    //         $departmentQuery = DB::table('departments')
    //             ->where('department_id', $departmentId)
    //             ->where('institute_id', $context['institute_id']);

    //         if ($context['is_branch_admin'] && $context['branch_id']) {
    //             $departmentQuery->where('branch_id', $context['branch_id']);
    //         } else {
    //             $departmentQuery->whereNull('branch_id');
    //         }

    //         $department = $departmentQuery->first();

    //         if (!$department) {
    //             return response()->json(['status' => 'error', 'message' => 'Department not found or access denied.']);
    //         }

    //         // Get subcategory with institute/branch scope
    //         $subCategoryQuery = DB::table('fincap_merchant_sub_categories')
    //             ->where('institute_id', $context['institute_id'])
    //             ->where('department_id', $departmentId)
    //             ->where('finacp_merchant_sub_category_type', $courseType);

    //         if ($context['is_branch_admin'] && $context['branch_id']) {
    //             $subCategoryQuery->where('branch_id', $context['branch_id']);
    //         } else {
    //             $subCategoryQuery->whereNull('branch_id');
    //         }

    //         $subCategory = $subCategoryQuery->first();
    //         if (!$subCategory) {
    //             $errorMessage = $context['is_branch_admin'] 
    //                 ? 'Course "'.$courseType.'" not found in your branch.' 
    //                 : 'Course "'.$courseType.'" not found in your institute.';
    //             return response()->json(['status' => 'error', 'message' => $errorMessage]);
    //         }

    //         // Get branches from product_details with institute/branch scope
    //         // FIXED THE TYPO: finacap_merchant_sub_category_id → finacp_merchant_sub_category_id
    //         $branchesQuery = DB::table('product_details')
    //             ->where('finacp_merchant_sub_category_id', $subCategory->finacp_merchant_sub_category_id) // FIXED: removed extra 'a'
    //             ->where('department_id', $departmentId);

    //         if ($context['is_branch_admin'] && $context['branch_id']) {
    //             $branchesQuery->where('branch_id', $context['branch_id']);
    //         } else {
    //             $branchesQuery->whereNull('branch_id');
    //         }

    //         $branches = $branchesQuery->select('product_id', 'sub_type', 'course_type')
    //             ->orderBy('sub_type')
    //             ->get();

    //         $successMessage = $context['is_branch_admin'] 
    //             ? 'Branches loaded for your branch.' 
    //             : 'Branches loaded for your institute.';

    //         return response()->json([
    //             'status' => 'success', 
    //             'branches' => $branches,
    //             'message' => $successMessage,
    //             'debug' => [
    //                 'institute_id' => $context['institute_id'],
    //                 'is_branch_admin' => $context['is_branch_admin'],
    //                 'branch_id' => $context['branch_id'],
    //                 'branches_count' => $branches->count(),
    //                 'department_found' => !!$department,
    //                 'course_found' => !!$subCategory,
    //                 'subcategory_id' => $subCategory->finacp_merchant_sub_category_id
    //             ]
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error', 
    //             'message' => 'Server error occurred. Check logs for details.'
    //         ]);
    //     }
    // }
    
    /**
     * Get branches by department + course type (AJAX)
     */
    public function getBranchesByCourse(Request $request)
    {
        try {
            $user = Auth::user();
    
            // Check authenticated user and institute
            if (!$user || !$user->institute_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not associated with any institute.'
                ]);
            }
    
            $instituteId = $user->institute_id;
            $departmentId = $request->input('department_id');
            $courseType = $request->input('course_type');
    
            if (!$departmentId || !$courseType) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Department ID and Course Type required.'
                ]);
            }
    
            /*
            |--------------------------------------------------------------------------
            | Verify Department
            |--------------------------------------------------------------------------
            */
            $department = DB::table('departments')
                ->where('department_id', $departmentId)
                ->where('institute_id', $instituteId)
                ->first();
    
            if (!$department) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Department not found or access denied.'
                ]);
            }
    
            /*
            |--------------------------------------------------------------------------
            | Get Course / Sub Category
            |--------------------------------------------------------------------------
            */
            $subCategory = DB::table('fincap_merchant_sub_categories')
                ->where('institute_id', $instituteId)
                ->where('department_id', $departmentId)
                ->where('finacp_merchant_sub_category_type', $courseType)
                ->first();
    
            if (!$subCategory) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course "' . $courseType . '" not found in your institute.'
                ]);
            }
    
            /*
            |--------------------------------------------------------------------------
            | Get Branches / Products
            |--------------------------------------------------------------------------
            */
            $branches = DB::table('product_details')
                ->where(
                    'finacp_merchant_sub_category_id',
                    $subCategory->finacp_merchant_sub_category_id
                )
                ->where('department_id', $departmentId)
                ->select(
                    'product_id',
                    'sub_type',
                    'course_type'
                )
                ->orderBy('sub_type')
                ->get();
    
            return response()->json([
                'status' => 'success',
                'branches' => $branches,
                'message' => 'Branches loaded successfully.',
                'debug' => [
                    'institute_id' => $instituteId,
                    'branches_count' => $branches->count(),
                    'department_found' => true,
                    'course_found' => true,
                    'subcategory_id' => $subCategory->finacp_merchant_sub_category_id
                ]
            ]);
    
        } catch (\Exception $e) {
    
            \Log::error('getBranchesByCourse error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'status' => 'error',
                'message' => 'Server error occurred. Check logs for details.'
            ]);
        }
    }
    public function getBranchesByCourseSchema(Request $request)
    {
        try {
          // Get institute/branch context
            $context = $this->getInstituteBranchContext();
            if (!$context['institute_id']) {
                return response()->json(['status' => 'error', 'message' => 'You are not associated with any institute.']);
            }

            $departmentId = $request->input('department_id');
            $courseType = $request->input('course_type');

            if (!$departmentId || !$courseType) {
                return response()->json(['status' => 'error', 'message' => 'Department ID and Course Type required']);
            }

            // Verify department belongs to current institute/branch scope
            $departmentQuery = DB::table('departments')
                ->where('department_id', $departmentId)
                ->where('institute_id', $context['institute_id']);

            if ($context['is_branch_admin'] && $context['branch_id']) {
                $departmentQuery->where('branch_id', $context['branch_id']);
            } else {
                $departmentQuery->whereNull('branch_id');
            }

            $department = $departmentQuery->first();

            if (!$department) {
                return response()->json(['status' => 'error', 'message' => 'Department not found or access denied.']);
            }

            // Get subcategory with institute/branch scope
            // The course_type parameter is actually the finacp_merchant_sub_category_id
            $subCategoryQuery = DB::table('fincap_merchant_sub_categories')
                ->where('institute_id', $context['institute_id'])
                ->where('department_id', $departmentId)
                ->where('finacp_merchant_sub_category_id', $courseType);

            if ($context['is_branch_admin'] && $context['branch_id']) {
                $subCategoryQuery->where('branch_id', $context['branch_id']);
            } else {
                $subCategoryQuery->whereNull('branch_id');
            }

            $subCategory = $subCategoryQuery->first();
            if (!$subCategory) {
                $errorMessage = $context['is_branch_admin']
                    ? 'Course "'.$courseType.'" not found in your branch.'
                    : 'Course "'.$courseType.'" not found in your institute.';
                return response()->json(['status' => 'error', 'message' => $errorMessage]);
            }

            // Get branches from product_details with institute/branch scope
            // FIXED THE TYPO: finacap_merchant_sub_category_id → finacp_merchant_sub_category_id
            $branchesQuery = DB::table('product_details')
                ->where('finacp_merchant_sub_category_id', $subCategory->finacp_merchant_sub_category_id) // FIXED: removed extra 'a'
                ->where('department_id', $departmentId);

            if ($context['is_branch_admin'] && $context['branch_id']) {
                $branchesQuery->where('branch_id', $context['branch_id']);
            } else {
                $branchesQuery->whereNull('branch_id');
            }

            $branches = $branchesQuery->select('product_id', 'sub_type', 'course_type')
                ->orderBy('sub_type')
                ->get();

            $successMessage = $context['is_branch_admin']
                ? 'Branches loaded for your branch.'
                : 'Branches loaded for your institute.';

            return response()->json([
                'status' => 'success',
                'branches' => $branches,
                'message' => $successMessage,
                'debug' => [
                    'institute_id' => $context['institute_id'],
                    'is_branch_admin' => $context['is_branch_admin'],
                    'branch_id' => $context['branch_id'],
                    'branches_count' => $branches->count(),
                    'department_found' => !!$department,
                    'course_found' => !!$subCategory,
                    'subcategory_id' => $subCategory->finacp_merchant_sub_category_id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server error occurred. Check logs for details.'
            ]);
        }
    }

    // Get subjects by course with semester info
    public function getSubjectsByCourse(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            if (!$context['institute_id']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not associated with any institute.'
                ]);
            }

            $courseDetailId = $request->input('course_detail_id');
            
            if (!$courseDetailId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course detail ID is required'
                ]);
            }

            // Get subjects for this course
            $subjects = SubjectsCoursewise::where('course_detail_id', $courseDetailId)
                ->where('institute_id', $context['institute_id'])
                ->select('subject_id', 'subject_name', 'semester_id')
                ->get();
                
            return response()->json([
                'status' => 'success',
                'subjects' => $subjects
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server error occurred.'
            ]);
        }
    }

    // Get subjects by course and semester
    public function getSubjectsByCourseSemester(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            if (!$context['institute_id']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not associated with any institute.'
                ]);
            }

            $courseDetailId = $request->input('course_detail_id');
            $semesterId = $request->input('semester_id');
            
            if (!$courseDetailId || !$semesterId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course detail ID and Semester ID are required'
                ]);
            }

            $query = SubjectsCoursewise::where('course_detail_id', $courseDetailId)
                ->where('institute_id', $context['institute_id']);
            
            if ($semesterId === 'all_semesters') {
                // Get subjects that are for all semesters
                $query->where('semester_id', 'all_semesters');
            } else {
                // Get subjects for specific semester OR all_semesters
                $query->where(function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                    ->orWhere('semester_id', 'all_semesters');
                });
            }
            
            $subjects = $query->select('subject_id', 'subject_name', 'semester_id')
                ->get();
                
            return response()->json([
                'status' => 'success',
                'subjects' => $subjects
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server error occurred.'
            ]);
        }
    }

     // In AjaxfunctionscallController.php
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
            
            // Try to decode JSON
            $decoded = json_decode($raw, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Case 1: If the array has only one element that's a string, it might be double-encoded
                if (count($decoded) === 1 && is_string($decoded[0])) {
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
        ]);
    }
    
    public function getSubjectCourseInfo(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $subjectId = $request->query('subject_id');
        $courseDetailId = $request->query('course_detail_id');

        if (!$subjectId || !$courseDetailId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subject and course are required.'
            ], 422);
        }

        $subject = SubjectsCoursewise::where('subject_id', $subjectId)
            ->where('course_detail_id', $courseDetailId)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$subject) {
            return response()->json([
                'status' => 'error',
                'message' => 'Subject not found for the selected course.'
            ], 404);
        }

        $courseDates = CourseFeeStructure::where('product_id', $courseDetailId)
            ->when(!empty($context['branch_id']), function ($q) use ($context) {
                return $q->where('branch_id', $context['branch_id']);
            })
            ->orderByDesc('id')
            ->first();

        if (!$courseDates) {
            $courseDates = CourseFeeStructure::where('course_type', optional(ProductDetails::where('product_id', $courseDetailId)->first())->course_type ?? null)
                ->where('sub_type', optional(ProductDetails::where('product_id', $courseDetailId)->first())->sub_type ?? null)
                ->when(!empty($context['branch_id']), function ($q) use ($context) {
                    return $q->where('branch_id', $context['branch_id']);
                })
                ->orderByDesc('id')
                ->first();
        }

        return response()->json([
            'status' => 'success',
            'subject_id' => $subject->subject_id,
            'semester_id' => $subject->semester_id ?? null,
            'course_start_date' => !empty($courseDates?->course_start_date)
                ? \Carbon\Carbon::parse($courseDates->course_start_date)->toDateString()
                : null,
            'course_end_date' => !empty($courseDates?->course_end_date)
                ? \Carbon\Carbon::parse($courseDates->course_end_date)->toDateString()
                : null,
        ]);
    }

    public function getAcademicYearsByProduct(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not associated with any institute.'
            ]);
        }

        $productId = $request->query('product_id');

        if (!$productId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product ID is required.'
            ]);
        }

        $query = CourseFeeStructure::where('product_id', $productId)
            ->when(!empty($context['branch_id']), function ($q) use ($context) {
                return $q->where('branch_id', $context['branch_id']);
            })
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        $academicYears = $query->values()->all();

        if (empty($academicYears)) {
            $product = DB::table('product_details')->where('product_id', $productId)->first();
            if ($product && $product->course_type && $product->sub_type) {
                $academicYears = CourseFeeStructure::where('course_type', $product->course_type)
                    ->where('sub_type', $product->sub_type)
                    ->when(!empty($context['branch_id']), function ($q) use ($context) {
                        return $q->where('branch_id', $context['branch_id']);
                    })
                    ->whereNotNull('academic_year')
                    ->where('academic_year', '!=', '')
                    ->distinct()
                    ->orderBy('academic_year', 'desc')
                    ->pluck('academic_year')
                    ->values()
                    ->all();
            }
        }

        return response()->json([
            'status' => 'success',
            'academic_years' => $academicYears,
        ]);
    }
    
    /**
     * Get all department relationships (AJAX)
     * URL: GET /ajax/department-full-data?department_id=1
     */
    public function getDepartmentFullData(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id'
        ]);

        // SECURITY CHECK: Verify department belongs to current institute/branch scope
        if (!$this->isDepartmentInScope($request->department_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found in your scope'
            ], 404);
        }

        try {
            $data = $this->getDepartmentRelationships($request->department_id);
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching department full data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch department data'
            ], 500);
        }
    }

   

    /**
     * Get category with full data (AJAX)
     * URL: GET /ajax/category-full-data/{categoryId}
     */
    public function getCategoryWithFullData($categoryId)
    {
        // SECURITY CHECK: Verify category belongs to current institute/branch scope
        if (!$this->isCategoryInScope($categoryId)) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found in your scope'
            ], 404);
        }

        try {
            $category = $this->getCategoryWithFullData($categoryId);
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching category full data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch category data'
            ], 500);
        }
    }

    /**
     * Search employees (AJAX)
     * URL: GET /ajax/search-employees?search_term=john&department_id=1
     */
    public function searchEmployees(Request $request)
    {
        $request->validate([
            'search_term' => 'required|string|min:2',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        // SECURITY CHECK: If department_id provided, verify it belongs to current scope
        if ($request->department_id && !$this->isDepartmentInScope($request->department_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found in your scope'
            ], 404);
        }

        try {
            $employees = $this->searchEmployees(
                $request->search_term, 
                $request->department_id
            );
            
            return response()->json([
                'success' => true,
                'employees' => $employees,
                'count' => $employees->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error searching employees: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search employees'
            ], 500);
        }
    }

    /**
     * Search departments (AJAX)
     * URL: GET /ajax/search-departments?search_term=hr
     */
    public function searchDepartments(Request $request)
    {
        $request->validate([
            'search_term' => 'required|string|min:2'
        ]);

        try {
            $departments = $this->getCommonQuery(Departments::class)
                ->with('category')
                ->where('department', 'like', '%' . $request->search_term . '%')
                ->orderBy('department')
                ->get();
            
            return response()->json([
                'success' => true,
                'departments' => $departments,
                'count' => $departments->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error searching departments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to search departments'
            ], 500);
        }
    }

    /**
     * Get department statistics (AJAX)
     * URL: GET /ajax/department-stats?department_id=1
     */
    public function getDepartmentStats(Request $request)
    {
        $request->validate([
            'department_id' => 'nullable|exists:departments,id'
        ]);

        // SECURITY CHECK: If department_id provided, verify it belongs to current scope
        if ($request->department_id && !$this->isDepartmentInScope($request->department_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found in your scope'
            ], 404);
        }

        try {
            $stats = $this->getDepartmentStatistics($request->department_id);
            
            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching department stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }

    /**
     * Verify department is in current scope (AJAX)
     * URL: GET /ajax/verify-department/1
     */
    public function verifyDepartmentInScope($departmentId)
    {
        try {
            $inScope = $this->isDepartmentInScope($departmentId);
            
            return response()->json([
                'success' => true,
                'in_scope' => $inScope,
                'department_id' => $departmentId
            ]);
        } catch (\Exception $e) {
            \Log::error('Error verifying department scope: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify department'
            ], 500);
        }
    }

    /**
     * Verify category is in current scope (AJAX)
     * URL: GET /ajax/verify-category/1
     */
    public function verifyCategoryInScope($categoryId)
    {
        try {
            $inScope = $this->isCategoryInScope($categoryId);
            
            return response()->json([
                'success' => true,
                'in_scope' => $inScope,
                'category_id' => $categoryId
            ]);
        } catch (\Exception $e) {
            \Log::error('Error verifying category scope: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify category'
            ], 500);
        }
    }

    /**
     * Test department scope (AJAX)
     * URL: GET /ajax/test-department-scope
     */
    public function testDepartmentScope()
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            // Test data retrieval
            $categories = $this->getCommonQuery(DepartmentCategory::class)->get();
            $departments = $this->getCommonQuery(Departments::class)->get();
            $employees = $this->getCommonQuery(EmployeeDetails::class)->get();
            $courseTypes = $this->getCommonQuery(CourseType::class)->get();
            
            return response()->json([
                'success' => true,
                'context' => $context,
                'counts' => [
                    'categories' => $categories->count(),
                    'departments' => $departments->count(),
                    'employees' => $employees->count(),
                    'course_types' => $courseTypes->count(),
                ],
                'scope_applied' => [
                    'institute_id' => $context['institute_id'],
                    'branch_id_applied' => $context['is_branch_admin'] ? $context['branch_id'] : 'null (institute_level_only)'
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error testing department scope: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to test scope'
            ], 500);
        }
    }

    public function getEmployeeFullData(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee_details,id'
        ]);

        try {
            // Get employee with common scope
            $employee = $this->getCommonQuery(EmployeeDetails::class)
                ->with(['department'])
                ->where('id', $request->employee_id)
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found in your scope'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'employee' => $employee
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching employee full data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employee data'
            ], 500);
        }
    }
    
    public function getStudentsByProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product_details,product_id'
        ]);
        // Get current context
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        // Get the product details to get course_type and sub_type
        $product = ProductDetails::where('product_id', $request->product_id)
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
                'message' => 'Course not found or you do not have access.'
            ], 404);
        }
        // Get students based on product (course_type and sub_type)
        $students = $this->getCommonQuery(\App\Models\StudentParentDetails::class)
            ->whereHas('academicTransportDetails', function($q) use ($product, $context) {
                $q->where('course_type_id', $product->finacp_merchant_sub_category_id)
                ->where('course_subtype_id', $product->product_id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                }, function($query) {
                    return $query->whereNull('branch_id');
                });
            })
            ->select(
                'student_hash_id',
                DB::raw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) as full_name"),
                'first_name',
                'middle_name',
                'last_name',
                'registration_number',
                'dob',
                'gender'
            )
            ->orderBy('first_name')
            ->get();
        return response()->json([
            'success' => true,
            'students' => $students,
            'count' => $students->count(),
            'course_type' => $product->course_type,
            'sub_type' => $product->sub_type
        ]);
    }
    
    public function getSectionsByBranch(Request $request)
    {
        $records = CourseFeeStructure::where('product_id', $request->branch_id)
            ->where('institute_id', auth()->user()->institute_id)
            ->whereNotNull('sections')
            ->get();
        $sections = collect();
        foreach ($records as $record) {
            $decodedSections = json_decode($record->sections, true);
            if (is_array($decodedSections)) {
                foreach ($decodedSections as $section) {
                    $sections->push([
                        'section_id'   => $section['id'] ?? null,
                        'section_name' => $section['name'] ?? null,
                        'seats'        => $section['seats'] ?? 0,
                    ]);
                }
            }
        }
        // Remove duplicate sections (important)
        $sections = $sections->unique('section_id')->values();
        return response()->json([
            'success'  => true,
            'sections' => $sections
        ]);
    }
    
    /**
     * Get floors by block (AJAX)
    */
    public function getFloorsByBlockAjax(Request $request)
    {
        $context = $this->getInstituteBranchContext();
    
        $request->validate([
            'block_id' => 'required'
        ]);
    
        try {
            // Verify block belongs to user's institute/branch
            $block = AddBlock::where('id', $request->block_id)
                ->where('institute_id', $context['institute_id'])
                ->when(
                    $this->isBranchAdmin(),
                    function ($query) use ($context) {
                        $query->where('branch_id', $context['branch_id']);
                    },
                    function ($query) {
                        $query->whereNull('branch_id');
                    }
                )
                ->first();
    
            if (!$block) {
                return response()->json([
                    'success' => false,
                    'message' => 'Block not found in your scope'
                ], 404);
            }
    
            // Get floors
            $floors = AddFloor::where('block_id', $block->id)
                ->where('institute_id', $context['institute_id'])
                ->when(
                    $this->isBranchAdmin(),
                    function ($query) use ($context) {
                        $query->where('branch_id', $context['branch_id']);
                    },
                    function ($query) {
                        $query->whereNull('branch_id');
                    }
                )
                ->orderBy('floor_number', 'asc')
                ->get()
                ->map(function ($floor) {
                    return [
                        'id' => $floor->id,
                        'floor_name' => $floor->floor_name,
                        'floor_number' => $floor->floor_number,
                        'description' => $floor->description,
                    ];
                });
    
            return response()->json([
                'success' => true,
                'floors' => $floors,
                'count' => $floors->count(),
                'debug' => [
                    'block_id' => $block->id,
                    'block_name' => $block->block_name,
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['branch_id']
                ]
            ]);
    
        } catch (\Exception $e) {
    
            \Log::error('Error fetching floors by block: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch floors: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get rooms by floor (AJAX)
    */
    public function getRoomsByFloorAjax(Request $request)
    {
        $context = $this->getInstituteBranchContext();
    
        $request->validate([
            'floor_id' => 'required',
            'block_id' => 'required'
        ]);
    
        try {
            // Verify floor belongs to user's institute/branch and block
            $floor = AddFloor::where('id', $request->floor_id)
                ->where('block_id', $request->block_id)
                ->where('institute_id', $context['institute_id'])
                ->when(
                    $this->isBranchAdmin(),
                    function ($query) use ($context) {
                        $query->where('branch_id', $context['branch_id']);
                    },
                    function ($query) {
                        $query->whereNull('branch_id');
                    }
                )
                ->first();
    
            if (!$floor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Floor not found in your scope'
                ], 404);
            }
    
            // Get rooms
            $rooms = AddRooms::where('floor_id', $floor->id)
                ->where('block_id', $request->block_id)
                ->where('institute_id', $context['institute_id'])
                ->when(
                    $this->isBranchAdmin(),
                    function ($query) use ($context) {
                        $query->where('branch_id', $context['branch_id']);
                    },
                    function ($query) {
                        $query->whereNull('branch_id');
                    }
                )
                ->orderBy('room_name', 'asc')
                ->get()
                ->map(function ($room) {
                    return [
                        'id' => $room->id,
                        'room_name' => $room->room_name,
                        'room_number' => $room->room_number,
                        'capacity' => $room->capacity,
                        'room_type' => $room->room_type,
                        'status' => $room->status,
                    ];
                });
    
            return response()->json([
                'success' => true,
                'rooms' => $rooms,
                'count' => $rooms->count(),
                'debug' => [
                    'floor_id' => $floor->id,
                    'floor_name' => $floor->floor_name,
                    'block_id' => $request->block_id,
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['branch_id']
                ]
            ]);
    
        } catch (\Exception $e) {
    
            \Log::error('Error fetching rooms by floor: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch rooms: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getDepartmentsByInstitute(Request $request)
    {
        $context = $this->getInstituteBranchContext();
    
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'No institute found'
            ]);
        }
    
        $instituteId = $request->institute_id;
    
        $departments = Departments::where('institute_id', $instituteId)
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->select('department_id', 'department')
            ->orderBy('department')
            ->get();
    
        return response()->json([
            'success' => true,
            'departments' => $departments
        ]);
    }
     
       /**
     * Get buildings (AJAX)
     */
    public function getBuildingsAjax(Request $request)
    {
        $context = $this->getInstituteBranchContext();
    
        try {
            $buildings = \App\Models\AddBuilding::where(
                    'institute_id',
                    $context['institute_id']
                )
                ->when(
                    $this->isBranchAdmin(),
                    function ($query) use ($context) {
                        $query->where('branch_id', $context['branch_id']);
                    },
                    function ($query) {
                        $query->whereNull('branch_id');
                    }
                )
                ->where('status', 'active')
                ->orderBy('name', 'asc')
                ->get()
                ->map(function ($building) {
                    return [
                        'id' => $building->id,
                        'building_name' => $building->name,
                        'code' => $building->code,
                        'description' => $building->description,
                        'total_blocks' => $building->total_blocks ?? 0,
                    ];
                });
    
            return response()->json([
                'success' => true,
                'buildings' => $buildings,
                'count' => $buildings->count(),
                'debug' => [
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['branch_id'],
                ],
            ]);
    
        } catch (\Exception $e) {
            \Log::error('Error fetching buildings: ' . $e->getMessage());
    
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch buildings: ' . $e->getMessage(),
            ], 500);
        }
    }
        
    /**
     * Get blocks by building (AJAX)
     */
    public function getBlocksByBuildingAjax(Request $request)
    {
        $context = $this->getInstituteBranchContext();
    
        $request->validate([
            'building_id' => 'required'
        ]);
    
        $building = \App\Models\AddBuilding::where('id', $request->building_id)
            ->where('institute_id', $context['institute_id'])
            ->when(
                $this->isBranchAdmin(),
                function ($query) use ($context) {
                    $query->where('branch_id', $context['branch_id']);
                },
                function ($query) {
                    $query->whereNull('branch_id');
                }
            )
            ->first();
    
        if (!$building) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found in your scope'
            ], 404);
        }
    
        // Get blocks for this building
        $blocks = AddBlock::where('building_id', $building->id)
            ->where('institute_id', $context['institute_id'])
            ->when(
                $this->isBranchAdmin(),
                function ($query) use ($context) {
                    $query->where('branch_id', $context['branch_id']);
                },
                function ($query) {
                    $query->whereNull('branch_id');
                }
            )
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($block) {
                return [
                    'id' => $block->id,
                    'block_name' => $block->name,
                    'code' => $block->code,
                    'description' => $block->description,
                    'total_floors' => $block->total_floors,
                    'total_rooms' => $block->total_rooms,
                ];
            });
    
        return response()->json([
            'success' => true,
            'blocks' => $blocks,
            'count' => $blocks->count(),
            'debug' => [
                'building_id' => $building->id,
                'building_name' => $building->name,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id']
            ]
        ]);
    }


}