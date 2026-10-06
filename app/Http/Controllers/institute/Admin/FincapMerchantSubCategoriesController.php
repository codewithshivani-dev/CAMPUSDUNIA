<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FincapMerchantSubCategories ;
use App\Models\FincapMerchant;
use App\Models\ProductDetails;
use App\Models\DepartmentCategory;
use App\Models\Departments;
use App\Models\ApplicantFeeDetails;

use App\Models\Allsubcategories ;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FincapMerchantSubCategoriesController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    public function AddFincapMerchantSubCategories(Request $request)
    {
       
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();
    
        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
    
        if ($request->form_type === 'new') {
            $validator = Validator::make($request->all(), [
                'department_category_id' => 'required',
                'department_id' => 'required',
                'new_course' => 'required|string|max:255',
                'finacp_merchant_sub_category_logo' => 'nullable|image|mimes:jpg,png,jpeg|max:5120',
                'new_branches' => 'nullable|array',
                'new_branches.*' => 'nullable|string|max:255',
            ]);
           
        } else {
            $validator = Validator::make($request->all(), [
                'department_category_id' => 'required',
                'department_id' => 'required',
                'finacp_merchant_sub_category_type' => 'required|array',
                'finacp_merchant_sub_category_type.*' => 'string',
                'existing_branches' => 'nullable|array',
                'existing_branches.*' => 'nullable|string|max:255',
            ]);
        }
    
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    
        $departmentID = $request->department_id;
        $departmentCategoryID = $request->department_category_id;
        
        // Verify department belongs to current institute/branch scope
        $department = $this->getCommonQuery(\App\Models\Departments::class)
            ->where('department_id', $departmentID)
            ->first();
       
        if (!$department) {
            return Redirect::back()->withErrors(['department_id' => 'Invalid department selected.']);
        }
    
        $merchantId = $context['institute_id']; // Use institute_id as merchantId
       
        // -------- ---------------
        // Case 1: Add New Course
        // -----------------------
        if ($request->form_type === 'new') {
            $newCourse = trim($request->new_course);
            
            // Check if course already exists in current scope
            $existingSubCategories = $this->getCommonQuery(FincapMerchantSubCategories::class)
                ->where('department_id', $departmentID)
                ->where('finacp_merchant_sub_category_type', $newCourse)
                ->pluck('finacp_merchant_sub_category_type')
                ->toArray();
            
            if (in_array($newCourse, $existingSubCategories)) {
                return Redirect::back()->withErrors(['new_course' => 'The course you entered already exists.']);
            }
    
            // ✅ Create new course record with institute/branch context
            $newSubCategoryData = [
                'fincap_program_category_id' => $request->fincap_program_category_id,
                'finacp_merchant_sub_category_id' => 'FMSC' . strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10)),
                'institute_id' => $merchantId, // Use fincap_merchant_id instead of institute_id
                'department_id' => $departmentID,
                'department_category_id' => $departmentCategoryID,
                'finacp_merchant_sub_category_type' => $newCourse,
                'finacp_merchant_sub_category_logo' => null,
            ];
           
            // Apply institute/branch context
            $newSubCategory = $this->createWithInstituteBranchContext($newSubCategoryData);
           
            if ($request->hasFile('finacp_merchant_sub_category_logo')) {
                $file = $request->file('finacp_merchant_sub_category_logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images/instituteadminImages', $filename, 'public');
                $newSubCategory['finacp_merchant_sub_category_logo'] = $path;
            }
           
            $course = FincapMerchantSubCategories::create($newSubCategory);
    
            // ✅ Store data in product_details table
            $branches = [];
            
            // If no branches are added, use course name as branch
            if (empty($request->new_branches) || (count($request->new_branches) === 1 && trim($request->new_branches[0]) === '')) {
                $branches = [$newCourse];
            } else {
                // Use provided branches
                foreach ($request->new_branches as $branchName) {
                    $branchName = trim($branchName);
                    if ($branchName !== '') {
                        $branches[] = $branchName;
                    }
                }
            }
    
            // Create entries in product_details table with institute/branch context
            foreach ($branches as $branch) {
                $productData = [
                    'product_id' => 'PRD' . strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10)),
                    'sub_type' => $branch, // This becomes the sub_type
                    'finacp_merchant_sub_category_id' => $course->finacp_merchant_sub_category_id,
                    'course_type' => $newCourse, // Store the main course type
                    'department_id' => $departmentID,
                    'department_category_id' => $departmentCategoryID,
                ];
    
                $productDataWithContext = $this->createWithInstituteBranchContext($productData);
                
                DB::table('product_details')->insert(array_merge($productDataWithContext, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
    
            $branchMessage = count($branches) > 1 ? 'with ' . count($branches) . ' branches' : 'with course as branch';
            $successMessage = $context['is_branch_admin'] 
                ? "Data Saved successfully"
                : "Data Saved successfully";
                
            return redirect()->route('course.basic.form')->with('success', $successMessage);
        }
    
        // -----------------------
        // Case 2: Existing Courses
        // -----------------------
        if ($request->form_type === 'existing' && $request->has('finacp_merchant_sub_category_type')) {
            $createdCourseIds = [];
            $totalBranchesCreated = 0;
            
            foreach ($request->finacp_merchant_sub_category_type as $courseType) {
                $courseType = trim($courseType);
                
                // Check if course already exists in current scope
                $existingCourse = $this->getCommonQuery(FincapMerchantSubCategories::class)
                    ->where('finacp_merchant_sub_category_type', $courseType)
                    ->where('department_id', $departmentID)
                    ->first();
    
                $courseId = null;
                
                if (!$existingCourse) {
                    // Create new course record with institute/branch context
                    $courseData = [
                        'fincap_program_category_id' => $request->fincap_program_category_id ?? null,
                        'finacp_merchant_sub_category_id' => 'FMSC' . strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10)),
                        'institute_id' => $merchantId, // Use fincap_merchant_id instead of institute_id
                        'department_id' => $departmentID,
                        'department_category_id' => $departmentCategoryID,
                        'finacp_merchant_sub_category_type' => $courseType,
                        'finacp_merchant_sub_category_logo' => null,
                    ];
    
                    $courseDataWithContext = $this->createWithInstituteBranchContext($courseData);
                    $newCourse = FincapMerchantSubCategories::create($courseDataWithContext);
                    $courseId = $newCourse->finacp_merchant_sub_category_id;
                } else {
                    $courseId = $existingCourse->finacp_merchant_sub_category_id;
                }
                
                $createdCourseIds[] = $courseId;
    
                // ✅ Store data in product_details table for each course
                $branches = [];
                
                // If no branches are added, use course name as branch
                if (empty($request->existing_branches) || (count($request->existing_branches) === 1 && trim($request->existing_branches[0]) === '')) {
                    $branches = [$courseType];
                } else {
                    // Use provided branches
                    foreach ($request->existing_branches as $branchName) {
                        $branchName = trim($branchName);
                        if ($branchName !== '') {
                            $branches[] = $branchName;
                        }
                    }
                }
    
                // Create entries in product_details table for this course
                foreach ($branches as $branch) {
                    // Check if this branch already exists for this course in current scope
                    $existingBranch = $this->getCommonQuery(\App\Models\ProductDetails::class)
                        ->where('finacp_merchant_sub_category_id', $courseId)
                        ->where('course_type', $branch)
                        ->first();
    
                    if (!$existingBranch) {
                        $productData = [
                            'product_id' => 'PRD' . strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10)),
                            'sub_type' => $branch, // This becomes the sub_type
                            'finacp_merchant_sub_category_id' => $courseId,
                            'course_type' => $courseType, // Store the main course type
                            'department_id' => $departmentID,
                            'department_category_id' => $departmentCategoryID,
                        ];
    
                        $productDataWithContext = $this->createWithInstituteBranchContext($productData);
                        
                        DB::table('product_details')->insert(array_merge($productDataWithContext, [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]));
                        $totalBranchesCreated++;
                    }
                }
            }
    
            $courseCount = count($request->finacp_merchant_sub_category_type);
            $branchMessage = $totalBranchesCreated > 0 ? "with {$totalBranchesCreated} branch entries" : "with courses as branches";
            
            $successMessage = $context['is_branch_admin'] 
                ? "{$courseCount} courses {$branchMessage} saved successfully for your branch!"
                : "{$courseCount} courses {$branchMessage} saved successfully for the institute!";
                
            return redirect()->route('course.basic.form')->with('success', $successMessage);
        }
    
        return redirect()->back()->with('error', 'No courses were selected.');
    }

    public function getallsubCategories(Request $request)
    {
        // Get institute/branch context using your trait
        $context = $this->getInstituteBranchContext();
        
        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $merchantId = $context['institute_id']; // Use institute_id as merchantId
        $programCategoryId = 2; 

        // -------------------------
        // 🔹 Global subcategories
        // -------------------------
        $global = DB::table('all_sub_categories')
            ->where('fincap_program_category_id', $programCategoryId)
            ->select(
                'finacp_merchant_sub_category_type',
                'finacp_merchant_sub_category_logo',
                DB::raw("'global' as source")
            )
            ->get();

        // -------------------------
        // 🔹 Merchant-specific subcategories (within current institute/branch scope)
        // -------------------------
        $merchantQuery = DB::table('fincap_merchant_sub_categories')
            ->where('institute_id', $merchantId) // Use fincap_merchant_id column
            ->where('fincap_program_category_id', $programCategoryId);

        // Apply branch filter if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $merchantQuery->where('branch_id', $context['branch_id']);
        } else {
            // Institute admin sees only institute-level data
            $merchantQuery->whereNull('branch_id');
        }

        $merchant = $merchantQuery->select(
                'finacp_merchant_sub_category_type',
                'finacp_merchant_sub_category_logo',
                DB::raw("'merchant' as source")
            )
            ->get();

        // -------------------------
        // 🔹 Merge and deduplicate
        // -------------------------
        $merged = $merchant->merge($global);

        $uniqueSubCategories = $merged
            ->unique('finacp_merchant_sub_category_type')
            ->values();

        // -------------------------
        // 🔹 Fetch departments with institute/branch scope
        // -------------------------
        $departmentsQuery = DB::table('departments')
            ->where('institute_id', $context['institute_id']);

        // Apply branch filter if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $departmentsQuery->where('branch_id', $context['branch_id']);
        } else {
            // Institute admin sees only institute-level departments
            $departmentsQuery->whereNull('branch_id');
        }

        $departments = $departmentsQuery->select('department_id', 'department', 'institute_id')
            ->orderBy('department')
            ->get();

        // -------------------------
        // 🔹 Get department categories for the form
        // -------------------------
        $departmentCategoriesQuery = DB::table('department_categories')
            ->where('institute_id', $context['institute_id']);

        // Apply branch filter if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $departmentCategoriesQuery->where('branch_id', $context['branch_id']);
        } else {
            // Institute admin sees only institute-level categories
            $departmentCategoriesQuery->whereNull('branch_id');
        }

        $departmentCategories = $departmentCategoriesQuery->select('department_category_id', 'category_name')
            ->orderBy('category_name')
            ->get();

        // -------------------------
        // 🔹 Return to view
        // -------------------------
        return view('instituteAdmin.CourseFiles.AddInstitutecourses', [
            'allsubCategories' => $uniqueSubCategories,
            'departments' => $departments,
            'departmentCategories' => $departmentCategories,
        ]);
    }

    public function ViewInstitutecourses()
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();
        
        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $merchantId = $context['institute_id'];
        
        // Get all courses with their departments and branches
        $courses = $this->getCommonQuery(FincapMerchantSubCategories::class)
            ->where('institute_id', $merchantId)
            ->with(['department', 'departmentCategory', 'productDetails'])
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($courses);
        // Group courses by department for better organization
        $coursesByDepartment = $courses->groupBy('department_id');
        
        // Get all departments for dropdown filtering
        $departments = $this->getCommonQuery(\App\Models\Departments::class)
            ->where('institute_id', $context['institute_id'])
            ->orderBy('department')
            ->get();
    
        // Get all department categories for dropdown filtering
        $departmentCategories = $this->getCommonQuery(\App\Models\DepartmentCategory::class)
            ->where('institute_id', $context['institute_id'])
            ->orderBy('category_name')
            ->get();
        
        return view('instituteAdmin.CourseFiles.viewInstitutecourses', compact(
            'courses',
            'coursesByDepartment',
            'departments',
            'departmentCategories',
            'context'
        ));
    }

    public function editInstitutecourses($finacp_merchant_sub_category_id)
    {
            $allsubCategories = Allsubcategories::where('fincap_program_category_id', 2)
                ->pluck('finacp_merchant_sub_category_type');
            $course_data = FincapMerchantSubCategories::where('finacp_merchant_sub_category_id', $finacp_merchant_sub_category_id)->first();
        
            return view('instituteAdmin.CourseFiles.EditInstitutecourses', compact('course_data', 'allsubCategories'));
    }
    
    public function updateInstitutecourses(Request $request, $finacp_merchant_sub_category_id)
    {
            $validator = validator($request->all(), [
                'finacp_merchant_sub_category_type' => 'required|array',
                'finacp_merchant_sub_category_type.*' => 'string',
                'finacp_merchant_sub_category_logo' => 'image|mimes:jpg,png,jpeg|max:5120',
            ]);
        
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

        
            $course_data = FincapMerchantSubCategories::where('finacp_merchant_sub_category_id', $finacp_merchant_sub_category_id)->first();
            $course_data->finacp_merchant_sub_category_type = implode(', ', $request->finacp_merchant_sub_category_type);
        
            if ($request->hasFile('finacp_merchant_sub_category_logo')) {
                $file = $request->file('finacp_merchant_sub_category_logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('images/instituteadminImages', $filename, 'public');
                $course_data->finacp_merchant_sub_category_logo = $path;
            }
        
            $course_data->save();
        
            return redirect()->route('institute-course.view', $finacp_merchant_sub_category_id)
                            ->with('success', 'Institute Courses updated successfully');
    }
    
    public function deleteInstitutecourse($finacp_merchant_sub_category_id)
    {
            $isLoanrunning = ApplicantFeeDetails::where('merchant_sub_category_id', $finacp_merchant_sub_category_id)->exists();
            if ($isLoanrunning) {
                return redirect()->back()->with('error', 'Cannot update status as loans are running for this course.');
            }
            $course = FincapMerchantSubCategories::where('finacp_merchant_sub_category_id', $finacp_merchant_sub_category_id)->first();
            if ($course) {
                $course->status = 'Inactive'; 
                $course->save();
                return redirect()->route('institute-course.view', $course->institute_id)->with('success', 'Record status updated to Inactive successfully.');
            }
            return redirect()->back()->with('error', 'Course not found.');
    }
    
    // ----------------------------
    // AJAX: Fetch course details
    // ----------------------------
    public function coursedetailsbehalfonID(Request $request)
    {
        $request->validate([
            'finacp_merchant_sub_category_id' => 'required',
        ]);

        // Load course along with its branches (productDetails)
        $course = FincapMerchantSubCategories::with('productDetails')
            ->where('finacp_merchant_sub_category_id', $request->finacp_merchant_sub_category_id)
            ->first();

        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Course not found']);
        }

        return response()->json(['success' => true, 'course' => $course]);
    }

    // ----------------------------
    // AJAX: Update course
    // ----------------------------
    public function editCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:fincap_merchant_sub_categories,finacp_merchant_sub_category_id',
            'sub_type' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    
        $course = FincapMerchantSubCategories::where(
            'finacp_merchant_sub_category_id',
            $request->id
        )->firstOrFail();
    
        /* ✅ ONLY UPDATE EXISTING COLUMN */
        $course->finacp_merchant_sub_category_type = $request->sub_type;
    
        $course->save();
    
        /* ===============================
           UPDATE BRANCHES
           =============================== */
        if ($request->has('branches')) {
            foreach ($request->branches as $branchData) {
                if (!empty($branchData['id'])) {
                    $branch = $course->productDetails()
                        ->where('id', $branchData['id'])
                        ->first();
    
                    if ($branch) {
                        $branch->sub_type = $branchData['sub_type'];
                        $branch->save();
                    }
                }
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Course and branches updated successfully!',
            'course' => $course->load('productDetails')
        ]);
    }

    // ----------------------------
    // AJAX: Delete course
    // ----------------------------
    public function deleteCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|string|exists:fincap_merchant_sub_categories,finacp_merchant_sub_category_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
        }

        $course = FincapMerchantSubCategories::where('finacp_merchant_sub_category_id', $request->course_id)->first();

        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Course not found.']);
        }

        // Optional: Check if course has linked records before deletion
        $linkedProducts = ProductDetails::where('finacp_merchant_sub_category_id', $course->finacp_merchant_sub_category_id)->exists();

        if ($linkedProducts) {
            return response()->json(['success' => false, 'message' => 'Cannot delete course, linked products exist.']);
        }

        $course->delete();

        return response()->json(['success' => true, 'message' => 'Course deleted successfully.']);
    }
}
    

   