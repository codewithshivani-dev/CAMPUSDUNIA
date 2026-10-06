<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FincapMerchantSubCategories;
use App\Models\FincapMerchant;
use Illuminate\Support\Facades\Auth;
use App\Models\Allsubcategories;
use App\Models\ProductDetails;
use App\Models\InstituteBasicDetails;
use App\Models\CourseFeeStructure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use App\Exports\CoursesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\FeeStructureExport;
use App\Models\Departments;
use App\Models\DepartmentCategory;



class ProductDetailsController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    public function getCoursesname(Request $request)
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();
        
        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        // Get department categories with institute/branch scope
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
            
        return view('instituteAdmin.CourseFiles.AddCourseDetails', compact('departmentCategories'));
    }


    public function saveCourseBasicDetails(Request $request)
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();
        
        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $validator = Validator::make($request->all(), [
            'department_category_id' => 'required|exists:department_categories,department_category_id',
            'department_id' => 'required|exists:departments,department_id',
            'course_type' => 'required|string|max:255',
            'product_id' => 'required|string|max:255',
            'sub_type' => 'required|string|max:255',
            'mode_of_course' => 'required|in:Online,Offline,Hybrid',
            'mode_type' => 'required|in:part_time,full_time,hybrid',
            'course_duration' => 'required',
            'course_length' => 'required|integer|min:1',
            'semester_count' => 'required|integer|min:1|max:12',
            'semester_list' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verify the product belongs to current institute/branch scope
        $productQuery = DB::table('product_details')
            ->where('product_id', $request->product_id);

        // Apply institute/branch context
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $productQuery->where('branch_id', $context['branch_id']);
        } else {
            $productQuery->whereNull('branch_id');
        }

        $product = $productQuery->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Invalid branch selected or you do not have access to this branch.');
        }

        // Convert semesters string to JSON array
        $semestersArray = json_decode($request->semester_list, true);
        $semestersJson = json_encode($semestersArray);

        // Update the product_details record with basic information
        $updateData = [
            'mode_of_course' => $request->mode_of_course,
            'mode_type' => $request->mode_type,
            'course_duration' => $request->course_duration,
            'course_length' => $request->course_length,
            'semesters' => $semestersJson,
            'updated_at' => now(),
        ];
    
        try {
            $updated = DB::table('product_details')
                ->where('product_id', $request->product_id)
                ->update($updateData);

            if ($updated) {
                // Store in session for the next form
                session([
                    'product_id' => $request->product_id,
                    'sub_type' => $request->sub_type,
                    'department_category_id' => $request->department_category_id,
                    'department_id' => $request->department_id,
                    'department_name' => DB::table('departments')->where('department_id', $request->department_id)->value('department'),
                    'course_type' => $request->course_type,
                    'mode_of_course' => $request->mode_of_course,
                    'mode_type' => $request->mode_type,
                    'course_duration' => $request->course_duration,
                    'course_length' => $request->course_length,
                ]);

                $successMessage = $context['is_branch_admin'] 
                    ? 'Basic details saved successfully'
                    : 'Basic details saved successfully';

                return redirect()->route('course.fee.form')->with('success', $successMessage);
            }

            return redirect()->back()->with('error', 'Failed to save basic details.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage())->withInput();
        }
    }

    // Show standalone fee structure form
    public function showCourseFeeForm()
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();
        
        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get department categories with institute/branch scope
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

        return view('instituteAdmin.CourseFiles.AddCourseFeeStructure', compact('departmentCategories'));
    }

   // Get branch details for fee structure
    public function getBranchDetailsForFee(Request $request)
    {
        $branchId = $request->input('branch_id');

        if (!$branchId) {
            return response()->json(['status' => 'error', 'message' => 'Branch ID required']);
        }

        $branchDetails = DB::table('product_details')
            ->where('product_id', $branchId)
            ->first();

        if (!$branchDetails) {
            return response()->json(['status' => 'error', 'message' => 'Branch not found']);
        }

        // Get existing fee structures
        $existingFeeStructures = CourseFeeStructure::where('product_id', $branchId)
            ->orderBy('batch_year', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'branch' => $branchDetails,
            'existingFeeStructures' => $existingFeeStructures
        ]);
    }


    private function calculateBatches($branchDetails)
    {
        $batches = [];
        
        if (!$branchDetails->course_start_date || !$branchDetails->course_length || !$branchDetails->course_duration) {
            return $batches;
        }

        $startDate = Carbon::parse($branchDetails->course_start_date);
        $courseLength = $branchDetails->course_length;
        $durationType = $branchDetails->course_duration;

        // Calculate end date based on duration type
        $endDate = $this->calculateEndDate($startDate, $courseLength, $durationType);

        // Calculate total years for the course
        $totalYears = $this->calculateTotalYears($durationType, $courseLength);

        // Generate batches for each academic year
        for ($i = 0; $i < $totalYears; $i++) {
            $batchStart = $startDate->copy()->addYears($i);
            $batchEnd = $batchStart->copy()->addYears(1);
            
            // Adjust end date for the last batch if needed
            if ($i === $totalYears - 1 && $batchEnd > $endDate) {
                $batchEnd = $endDate;
            }

            $batchYear = $batchStart->year; // Each year gets its own batch year
            $academicYear = $batchStart->year . '-' . $batchEnd->year;
            $sessionRange = $batchStart->format('Y') . '–' . $batchEnd->format('Y');
            
            $batchName = 'Batch ' . $startDate->year . ' (' . $startDate->year . '-' . $endDate->year . ')';

            $batches[] = [
                'batch_name' => $batchName,
                'academic_year' => $academicYear,
                'batch_year' => $batchYear, // Individual year for each academic year
                'batch_start_date' => $batchStart->format('Y-m-d'),
                'batch_end_date' => $batchEnd->format('Y-m-d'),
                'session_range' => $sessionRange,
                'course_duration' => $branchDetails->course_duration,
                'course_length' => $branchDetails->course_length,
                'mode_of_course' => $branchDetails->mode_of_course,
                'mode_type' => $branchDetails->mode_type,
                'original_end_date' => $batchEnd->format('Y-m-d')
            ];
        }

        return $batches;
    }

    // Calculate total years based on duration type
    private function calculateTotalYears($durationType, $length)
    {
        switch (strtolower($durationType)) {
            case 'hourly':
                return ceil($length / (24 * 365)); // Approximate hours to years
            case 'weekly':
                return ceil(($length * 7) / 365); // Weeks to years
            case 'monthly':
                return ceil($length / 12); // Months to years
            case 'quarterly':
                return ceil($length / 4); // Quarters to years
            case 'half_yearly':
                return ceil($length / 2); // Half years to years
            case 'yearly':
                return $length; // Already in years
            default:
                return $length;
        }
    }
    
    // Calculate end date based on duration type
    private function calculateEndDate($startDate, $length, $durationType)
    {
        $endDate = $startDate->copy();

        switch (strtolower($durationType)) {
            case 'hourly':
                $endDate->addHours($length);
                break;
            case 'weekly':
                $endDate->addWeeks($length);
                break;
            case 'monthly':
                $endDate->addMonths($length);
                break;
            case 'quarterly':
                $endDate->addMonths($length * 3);
                break;
            case 'half_yearly':
                $endDate->addMonths($length * 6);
                break;
            case 'yearly':
                $endDate->addYears($length);
                break;
            default:
                $endDate->addYears($length);
                break;
        }

        return $endDate;
    }

    public function saveCourseFeeStructure(Request $request)
    {

        // try {
            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            // Check if user has institute access
            if (!$context['institute_id']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }

            $data = $request->all();

            if (!isset($data['submissions']) || !is_array($data['submissions'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid data format'
                ], 400);
            }

            $savedCount = 0;
            $batchId = null;
            $errors = [];

            foreach ($data['submissions'] as $index => $submission) {
                // Validate each submission
                $validator = Validator::make($submission, [
                    'product_id' => 'required',
                    'department_id' => 'required',
                    'course_type' => 'required',
                    'sub_type' => 'required',
                    'mode_type' => 'required',
                    'mode_of_course' => 'required',
                    'batch_id' => 'required',
                    'academic_year_id' => 'required',
                    'academic_year' => 'required',
                    'total_seats' => 'required|integer|min:1',
                    'available_seats' => 'integer|min:0',
                    'sections' => 'nullable|json',
                    'course_start_date' => 'required|date',
                    'course_end_date' => 'required|date|after:course_start_date',
                ]);

                if ($validator->fails()) {
                    $errors[] = "Submission {$index}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Verify the product belongs to current institute/branch scope
                $productQuery = DB::table('product_details')
                    ->where('product_id', $submission['product_id']);

                // Apply institute/branch context
                if ($context['is_branch_admin'] && $context['branch_id']) {
                    $productQuery->where('branch_id', $context['branch_id']);
                } else {
                    $productQuery->whereNull('branch_id');
                }

                $product = $productQuery->first();

                if (!$product) {
                    $errors[] = "Submission {$index}: Invalid product selected or you do not have access to this branch.";
                    continue;
                }

                // Verify department belongs to current institute/branch scope
                $departmentQuery = DB::table('departments')
                    ->where('department_id', $submission['department_id']);

                if ($context['is_branch_admin'] && $context['branch_id']) {
                    $departmentQuery->where('branch_id', $context['branch_id']);
                } else {
                    $departmentQuery->where('institute_id', $context['institute_id'])
                                ->whereNull('branch_id');
                }

                $department = $departmentQuery->first();

                if (!$department) {
                    $errors[] = "Submission {$index}: Invalid department selected or you do not have access to this department.";
                    continue;
                }

                // Validate seats allocation
                $totalSeats = $submission['total_seats'];
                $allocatedSeats = 0;

                // Additional validation for sections
                if (!empty($submission['sections'])) {
                    $sections = json_decode($submission['sections'], true);

                    // Validate section names are unique
                    $sectionNames = array_column($sections, 'name');
                    if (count($sectionNames) !== count(array_unique($sectionNames))) {
                        $errors[] = "Submission {$index}: Duplicate section names found";
                        continue;
                    }

                    // Validate no empty section names
                    $emptyNames = array_filter($sectionNames, function($name) {
                        return empty(trim($name));
                    });
                    if (count($emptyNames) > 0) {
                        $errors[] = "Submission {$index}: Empty section names found";
                        continue;
                    }

                    foreach ($sections as &$section) {
                        $allocatedSeats += $section['seats'] ?? 0;

                        // Keep existing keys and append new ones
                        $section['occupied_seats'] = 0;
                        $section['available_seats'] = $section['seats'] ?? 0;
                    }
                    unset($section);

                    // Save updated JSON
                    $submission['sections'] = json_encode($sections);

                    // Validate seats allocation
                    if ($allocatedSeats > $totalSeats) {
                        $errors[] = "Submission {$index}: Allocated seats ({$allocatedSeats}) exceed total seats ({$totalSeats})";
                        continue;
                    }
                }

                // Prepare data with institute/branch context
                $feeStructureData = [
                    'product_id' => $submission['product_id'],
                    'department_id' => $submission['department_id'],
                    'course_type' => $submission['course_type'],
                    'sub_type' => $submission['sub_type'],
                    'mode_of_course' => $submission['mode_of_course'],
                    'mode_type' => $submission['mode_type'],
                    'batch_id' => $submission['batch_id'],
                    'academic_year_id' => $submission['academic_year_id'],
                    'academic_year' => $submission['academic_year'],
                    'session_range' => $submission['session_range'] ?? null,
                    'course_start_date' => $submission['course_start_date'],
                    'course_end_date' => $submission['course_end_date'],
                    'total_seats' => $totalSeats,
                    'available_seats' => $submission['available_seats'] ?? $totalSeats,
                    'sections' => $submission['sections'] ?? null,
                    'batch' => $submission['batch'] ?? null,
                    'batch_year' => $submission['batch_year'] ?? null,
                    'batch_status' => $submission['batch_status'] ?? 'running',

                    // Fee categories
                    'course_fee' => $submission['course_fee'] ?? null,
                    // 'hostel_fee' => $submission['hostel_fee'] ?? null,
                    // 'transportation_fee' => $submission['transportation_fee'] ?? null,
                    'registration_fee' => $submission['registration_fee'] ?? null,
                    // 'miscellaneous_fee' => $submission['miscellaneous_fee'] ?? null,
                    // 'custom_fees' => $submission['custom_fees'] ?? null,
                    'total_fee' => $submission['total_fee'] ?? 0,

                    // Institute/branch context
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Save to database
                $courseFee = CourseFeeStructure::create($feeStructureData);
                if ($courseFee) {
                        $savedCount++;
                        $batchId = $submission['batch_id'];
                }

            }
            // Prepare response
            $response = [
                'status' => $savedCount > 0 ? 'success' : 'error',
                'message' => $savedCount > 0
                    ? "Successfully created {$savedCount} academic year records"
                    : 'Failed to save any records',
                'batch_id' => $batchId,
                'saved_count' => $savedCount,
            ];

            // Add errors to response if any
            if (!empty($errors)) {
                $response['errors'] = $errors;
                \Log::warning('Course fee structure validation errors:', $errors);
            }

            return response()->json($response);

        // } catch (\Exception $e) {
        //     \Log::error('Error saving course fee structure: ' . $e->getMessage());
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Failed to save fee structure: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    public function viewFeeStructure(Request $request)
    {
        // try {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])
            ->with([
                'stakeholders',
                'stakeholderDocuments',
                'authorizedUser',
                'authorizedUserDocuments',
                'documents',
                'beneficiary'
            ])
            ->first();

            $query = DB::table('course_fee_structures as cfs')
                ->join('product_details as pd', 'cfs.product_id', '=', 'pd.product_id')
                ->leftJoin('departments as d', 'cfs.department_id', '=', 'd.department_id')
                ->where(function ($q) use ($context) {
                    $q->where('cfs.institute_id', $context['institute_id'])
                    ->orWhere('pd.institute_id', $context['institute_id']);
                })
                ->select(
                    'cfs.*',
                    'pd.sub_type',
                    'pd.course_type',
                    'pd.mode_of_course',
                    'pd.mode_type',
                    'pd.course_duration',
                    'pd.course_length',
                    'cfs.course_start_date as branch_start_date',
                    'cfs.course_end_date as branch_end_date',
                    'd.department',
                    'd.department_id'
                );

        // =======================
        // APPLY FILTERS (ADDED)
        // =======================
        $filters = [
            'product_id'        => $request->get('product_id'),
            'batch_id'          => $request->get('batch_id'),
            'academic_year_id'  => $request->get('academic_year_id'),
            'batch_year'        => $request->get('batch_year'),
            'academic_year'     => $request->get('academic_year'),
            'department_id'     => $request->get('department_id'),
            'course_type'       => $request->get('course_type'),

            // ✅ Newly added filters
            'mode_of_course'    => $request->get('mode_of_course'),
            'mode_type'         => $request->get('mode_type'),
            'course_duration'   => $request->get('course_duration'),
        ];

        foreach ($filters as $key => $value) {
            if (!empty($value)) {

                // Filters from course_fee_structures table
                if (in_array($key, [
                    'product_id',
                    'batch_id',
                    'academic_year_id',
                    'batch_year',
                    'academic_year',
                    'department_id'
                ])) {
                    $query->where('cfs.' . $key, $value);
                }

                // Filters from product_details table
                elseif (in_array($key, [
                    'course_type',
                    'mode_of_course',
                    'mode_type',
                    'course_duration'
                ])) {
                    $query->where('pd.' . $key, $value);
                }
            }
        }
        // =======================
        // END FILTER SECTION
        // =======================

        $filterData = [
            'products' => DB::table('product_details')
                ->where('institute_id', $context['institute_id'])
                ->pluck('course_type', 'product_id'),

            'batches' => DB::table('course_fee_structures')
                ->where('institute_id', $context['institute_id'])
                ->distinct()
                ->pluck('batch_id', 'batch'),

            'batch_years' => DB::table('course_fee_structures')
                ->where('institute_id', $context['institute_id'])
                ->distinct()
                ->orderBy('batch_year', 'desc')
                ->pluck('batch_year'),

            'academic_years' => DB::table('course_fee_structures')
                ->where('institute_id', $context['institute_id'])
                ->distinct()
                ->orderBy('academic_year', 'desc')
                ->pluck('academic_year'),

            'departments' => DB::table('departments')
                ->where('institute_id', $context['institute_id'])
                ->pluck('department', 'department_id'),

            'course_types' => DB::table('product_details')
                ->where('institute_id', $context['institute_id'])
                ->distinct()
                ->pluck('course_type')
        ];

        $feeStructures = $query
            ->orderBy('cfs.batch_year', 'desc')
            ->orderBy('cfs.academic_year', 'desc')
            ->orderBy('cfs.created_at', 'desc')
            ->paginate(20)
            ->appends($request->query());

            // Process fee data with proper JSON decoding for double-encoded data
                $feeStructures->each(function ($item) {
                    $feeTypes = ['course_fee', 'registration_fee'];
                    foreach ($feeTypes as $feeType) {
                        if ($item->$feeType && $item->$feeType !== 'null' && $item->$feeType !== '') {
                            try {
                                $decoded = json_decode($item->$feeType, true);
                                // If first decode returns a string, it might be double-encoded
                                if (is_string($decoded)) {
                                    $decoded = json_decode($decoded, true);
                                }
                                // Check if final result is an array
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $item->$feeType = $decoded;
                                } else {
                                    $item->$feeType = null;
                                }
                            } catch (\Exception $e) {
                                $item->$feeType = null;
                            }
                        } else {
                            $item->$feeType = null;
                        }
                    }
                    // Process sections data
                    if ($item->sections && $item->sections !== 'null' && $item->sections !== '') {
                        try {
                            $decodedSections = json_decode($item->sections, true);
                            // If first decode returns a string, it might be double-encoded
                            if (is_string($decodedSections)) {
                                $decodedSections = json_decode($decodedSections, true);
                            }
                            // Check if final result is an array
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedSections)) {
                                $item->sections = $decodedSections;
                                // Calculate allocated seats from sections
                                $allocatedSeats = 0;
                                foreach ($decodedSections as $section) {
                                    $allocatedSeats += $section['seats'] ?? 0;
                                }
                                $item->allocated_seats = $allocatedSeats;
                            } else {
                                $item->sections = [];
                                $item->allocated_seats = 0;
                            }
                        } catch (\Exception $e) {
                            $item->sections = [];
                            $item->allocated_seats = 0;
                        }
                    } else {
                        $item->sections = [];
                        $item->allocated_seats = 0;
                    }
                    // Ensure total_fee is numeric
                    if (!is_numeric($item->total_fee)) {
                        $item->total_fee = 0;
                    }
                    // Ensure seats fields are numeric
                    if (!is_numeric($item->total_seats)) {
                        $item->total_seats = 0;
                    }
                    if (!is_numeric($item->available_seats)) {
                        $item->available_seats = 0;
                    }
                });

        return view(
            'instituteAdmin.CourseFiles.viewCourseFeeStructure',
            compact('feeStructures', 'filters', 'filterData')
        );

    }

    public function getcoursedetails(Request $request)
    {
        $context = $this->getInstituteBranchContext();
    
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
    
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])
            ->with([
                'stakeholders',
                'stakeholderDocuments',
                'authorizedUser',
                'authorizedUserDocuments',
                'documents',
                'beneficiary'
            ])->first();
    
        // ✅ Departments
        $departments = Departments::where('institute_id', $context['institute_id'])
            ->orderBy('department')
            ->get();
    
        // ✅ Department Categories
        $departmentCategories = DepartmentCategory::where('institute_id', $context['institute_id'])
            ->orderBy('category_name')
            ->get();
    
        // ✅ Courses Query
        $coursesQuery = ProductDetails::with([
                'department',
                'course.departmentCategory',
                'course.productDetails'
            ])
            ->where('institute_id', $context['institute_id'])
            ->whereNotNull('course_duration')
            ->where('course_duration', '!=', '')
            ->whereNotNull('course_length')
            ->where('course_length', '!=', '');
    
        // Filters
        if ($request->filled('category_id')) {
            $coursesQuery->whereHas('course.departmentCategory', function ($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }
    
        if ($request->filled('department_id')) {
            $coursesQuery->where('department_id', $request->department_id);
        }
    
        if ($request->filled('duration')) {
            $coursesQuery->where('course_duration', 'LIKE', '%' . $request->duration . '%');
        }
    
        if ($request->filled('course_type')) {
            $coursesQuery->where('course_type', 'LIKE', "%{$request->course_type}%");
        }
    
        if ($request->filled('course_duration')) {
            $coursesQuery->where('course_duration', 'LIKE', "%{$request->course_duration}%");
        }
    
        if ($request->filled('sub_type')) {
            $coursesQuery->where('sub_type', 'LIKE', "%{$request->sub_type}%");
        }
    
        if ($request->filled('product_id')) {
            $coursesQuery->where('product_id', $request->product_id);
        }
    
        $courses = $coursesQuery
            ->orderByDesc('id')
            ->paginate(15)
            ->appends($request->query());
    
        /*
        |--------------------------------------------------------------------------
        | SECTION DATA
        |--------------------------------------------------------------------------
        */
    
        $productIdsOnPage = $courses->pluck('product_id');
    
        // Get sections from course_fee_structures
        $sectionsByProduct = CourseFeeStructure::whereIn('product_id', $productIdsOnPage)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function ($q) use ($context) {
                $q->where('branch_id', $context['branch_id']);
            })
            ->select('product_id', 'sections')
            ->get()
            ->groupBy('product_id');
    
        // Get student count grouped by product + section
        $studentCounts = DB::table('academic_transport_details')
            ->whereIn('course_subtype_id', $productIdsOnPage)
            ->where('institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function ($q) use ($context) {
                $q->where('academic_transport_details.branch_id', $context['branch_id']);
            })
            ->select('course_subtype_id', 'section_id', DB::raw('COUNT(*) as total_students'))
            ->groupBy('course_subtype_id', 'section_id')
            ->get()
            ->groupBy('course_subtype_id');
    
        // Get student details for each section
        $studentDetails = DB::table('academic_transport_details')
            ->whereIn('course_subtype_id', $productIdsOnPage)
            ->where('academic_transport_details.institute_id', $context['institute_id'])
            ->when($context['is_branch_admin'] && $context['branch_id'], function ($q) use ($context) {
                $q->where('academic_transport_details.branch_id', $context['branch_id']);
            })
            ->join('student_parent_details', 'academic_transport_details.student_hash_id', '=', 'student_parent_details.student_hash_id')
            ->select(
                'academic_transport_details.course_subtype_id',
                'academic_transport_details.section_id',
                'student_parent_details.id as student_id',
                'student_parent_details.first_name',
                'student_parent_details.last_name',
                'student_parent_details.registration_number'
            )
            ->orderBy('student_parent_details.first_name')
            ->get()
            ->groupBy(['course_subtype_id', 'section_id']);
    
        /*
        |--------------------------------------------------------------------------
        | Attach Section + Student Count to Each Course (with SAFE array access)
        |--------------------------------------------------------------------------
        */
    
        $courses->getCollection()->transform(function ($course) use ($sectionsByProduct, $studentCounts, $studentDetails) {
    
            $sections = collect();
            $totalCourseStudents = 0;
    
            if (isset($sectionsByProduct[$course->product_id])) {
    
                foreach ($sectionsByProduct[$course->product_id] as $feeStructure) {
    
                    $decodedSections = json_decode($feeStructure->sections, true);
    
                    if (!$decodedSections || !is_array($decodedSections)) {
                        continue;
                    }
    
                    foreach ($decodedSections as &$sec) {
                        
                        // Ensure section is an array
                        if (!is_array($sec)) {
                            $sec = [];
                        }
    
                        // SAFELY get section ID with null coalescing operator
                        $sectionId = $sec['section_id'] ?? $sec['id'] ?? $sec['code'] ?? null;
                        
                        // If still no ID, generate a temporary one
                        if (!$sectionId) {
                            $sectionId = 'temp_' . uniqid();
                        }
    
                        $count = 0;
                        $students = collect();
    
                        // Safely get student count
                        if (isset($studentCounts[$course->product_id])) {
                            $matched = $studentCounts[$course->product_id]
                                ->where('section_id', $sectionId)
                                ->first();
                            $count = $matched->total_students ?? 0;
                        }
    
                        // Safely get student details
                        if (isset($studentDetails[$course->product_id][$sectionId])) {
                            $students = $studentDetails[$course->product_id][$sectionId];
                        }
    
                        // Safely set section data with defaults
                        $sec = array_merge([
                            'id' => $sectionId,
                            'name' => 'Unnamed Section',
                            'code' => '',
                            'seats' => 0,
                            'description' => '',
                            'student_count' => 0,
                            'available_seats' => 0,
                            'students' => collect()
                        ], $sec);
    
                        // Update dynamic values
                        $sec['student_count'] = $count;
                        $sec['available_seats'] = max(0, ($sec['seats'] ?? 0) - $count);
                        $sec['students'] = $students;
    
                        $totalCourseStudents += $count;
                    }
    
                    $sections = $sections->merge($decodedSections);
                }
            }
    
            $course->sections = $sections;
            $course->student_count = $totalCourseStudents;
            
            return $course;
        });
    
        /*
        |--------------------------------------------------------------------------
        | Filters Data
        |--------------------------------------------------------------------------
        */
    
        $courseTypes = ProductDetails::where('institute_id', $context['institute_id'])
            ->select('course_type')
            ->whereNotNull('course_type')
            ->where('course_type', '!=', '')
            ->distinct()
            ->orderBy('course_type')
            ->get();
    
        $courseDuration = ProductDetails::where('institute_id', $context['institute_id'])
            ->select('course_duration')
            ->distinct()
            ->get();
    
        $subTypes = ProductDetails::where('institute_id', $context['institute_id'])
            ->select('sub_type')
            ->distinct()
            ->get();
    
        $productIds = ProductDetails::where('institute_id', $context['institute_id'])
            ->select('product_id')
            ->distinct()
            ->get();
    
        $instituteDetails = DB::table('institutes')
            ->where('fincap_merchant_id', $context['institute_id'])
            ->first();
    
        return view('instituteAdmin.CourseFiles.ViewCourseDetails', compact(
            'courses',
            'departments',
            'departmentCategories',
            'instituteDetails',
            'courseDuration',
            'courseTypes',
            'subTypes',
            'productIds',
            'context',
            'fincapMerchants'
        ));
    }

    // JSON for slide panel
    public function coursedetailsbehalfonID($id)
    {
        // dd($id);
        $c = ProductDetails::findOrFail($id);

        // Decode JSON/string fields safely
        $sections   = $this->safeJson($c->sections, []);
        $semesters  = $this->safeJson($c->semesters, []);
        $courseFee  = $this->safeJson($c->course_fee, null);
        $regFee     = $this->safeJson($c->registration_fee, null);
        $hostelFee  = $this->safeJson($c->hostel_fee, null);
        $transport  = $this->safeJson($c->transportation_fee, null);
        $miscFee    = $this->safeJson($c->miscellaneous_fee, null);
        $userDef    = $this->safeJson($c->userdefined_column_fee, []);

        // Same response structure but for Blade
        $course = [
            'id'                => $c->id,
            'course_type'       => $c->course_type,
            'sub_type'          => $c->sub_type,
            'mode_of_course'    => $c->mode_of_course,
            'mode_type'         => $c->mode_type,
            'session'           => $c->session,
            'fee_academic_year' => $c->fee_academic_year,
            'course_duration'   => $c->course_duration,
            'course_length'     => $c->course_length,
            'start_date'        => $c->course_start_date,
            'end_date'          => $c->course_end_date,
            'status'            => $c->status,
            'product_id'        => $c->product_id,
            'fincap_partner_id' => $c->fincap_partner_id,
            'fincap_merchant_id'=> $c->fincap_merchant_id,
            'total_fee'         => $c->total_fee,
            'created_at'        => optional($c->created_at)->format('d M Y, h:i A'),
            'updated_at'        => optional($c->updated_at)->format('d M Y, h:i A'),
        ];

        $structure = [
            'sections'  => $sections,
            'semesters' => $semesters,
        ];

        $fees = [
            'course_fee'         => $courseFee,
            'registration_fee'   => $regFee,
            'hostel_fee'         => $hostelFee,
            'transportation_fee' => $transport,
            'miscellaneous_fee'  => $miscFee,
            'userdefined'        => $userDef,
        ];


        return view('instituteAdmin.CourseFiles.CourseDetailsViewPage', compact('c',
            'course',
            'structure',
            'fees'
        ));

    }


    // Helper: decode JSON (string/array) safely
    private function safeJson($value, $default = null)
    {
        if (is_array($value)) return $value;
        if (is_null($value) || $value === '') return $default;

        $decoded = json_decode($value, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $default;
    }

public function downloadCourses(Request $request)
{
    // dd($request->all());
    //  Validate
    $validated = $request->validate([
        'type' => 'required|in:excel,csv,pdf',
        'ids'  => 'nullable|string',
    ]);

    //  Institute context
    $context = $this->getInstituteBranchContext();

    //  Base query (same philosophy as employee)
        $query = ProductDetails::with([
                'department',
                'course.departmentCategory'
            ])
            ->where('institute_id', $context['institute_id'])
            ->whereNotNull('course_duration')
            ->where('course_duration', '!=', '')
            ->whereNotNull('course_length')
            ->where('course_length', '!=', '');

    // ================= SELECT ALL (FILTERED) =================
    if ($request->has('select_all')) {

        if ($request->filled('category_id')) {
            $query->whereHas('course.departmentCategory', function ($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }

        // if ($request->filled('department_id')) {
        //     $query->whereHas('department', function ($q) use ($request, $context) {
        //         $q->where('department_id', $request->department_id)
        //         ->where('institute_id', $context['institute_id']);
        //     });
        // }
        if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }
        if ($request->filled('course_type')) {
            $query->where('course_type', $request->course_type);
        }

        if ($request->filled('course_duration')) {
            $query->where('course_duration', $request->course_duration);
        }

        if ($request->filled('sub_type')) {
            $query->where('sub_type', $request->sub_type);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

    }
    
    // ================= EXPLICIT IDS =================
    elseif (!empty($validated['ids'])) {

        $ids = array_filter(explode(',', $validated['ids']));
        $query->whereIn('id', $ids);
    }

    //  Fetch final filtered result
    $courses = $query->get();

    if ($courses->isEmpty()) {
        return response()->json(['message' => 'No courses found'], 404);
    }

    //  Download
    switch ($validated['type']) {

        case 'excel':
            return Excel::download(
                new CoursesExport($courses),
                'courses.xlsx'
            );

        case 'csv':
            return Excel::download(
                new CoursesExport($courses),
                'courses.csv'
            );

        case 'pdf':
            $pdf = Pdf::loadView('pdf.course_export', compact('courses'));
            return $pdf->download('courses.pdf');

        default:
            return response()->json(['message' => 'Invalid download type'], 400);
    }
}

    public function downloadFeeStructure(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:excel,csv,pdf',
            'ids'        => 'nullable|string',
            'select_all' => 'nullable|boolean',
        ]);

        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $query = DB::table('course_fee_structures as cfs')
            ->join('product_details as pd', 'cfs.product_id', '=', 'pd.product_id')
            ->leftJoin('departments as d', 'cfs.department_id', '=', 'd.department_id')
            ->where(function ($q) use ($context) {
                $q->where('cfs.institute_id', $context['institute_id'])
                ->orWhere('pd.institute_id', $context['institute_id']);
            })
            ->select(
                'cfs.*',
                'pd.sub_type',
                'pd.course_type',
                'pd.mode_of_course',
                'pd.mode_type',
                'pd.course_duration',
                'd.department',
                'cfs.course_start_date as branch_start_date',
                'cfs.course_end_date as branch_end_date',
            );

        /*
        |--------------------------------------------------------------------------
        | SELECT ALL WITH FILTERS
        |--------------------------------------------------------------------------
        */
        if ($request->select_all == 1) {

            // Filters from course_fee_structures
            $cfsFilters = [
                'product_id',
                'batch_id',
                'academic_year_id',
                'batch_year',
                'academic_year',
                'department_id'
            ];

            foreach ($cfsFilters as $filter) {
                if ($request->filled($filter)) {
                    $query->where('cfs.' . $filter, $request->$filter);
                }
            }

            // Filters from product_details
            $pdFilters = [
                'course_type',
                'mode_of_course',
                'mode_type',
                'course_duration'
            ];

            foreach ($pdFilters as $filter) {
                if ($request->filled($filter)) {
                    $query->where('pd.' . $filter, $request->$filter);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SELECTED IDS
        |--------------------------------------------------------------------------
        */
        elseif ($request->filled('ids')) {

            $ids = array_filter(explode(',', $request->ids));

            // IMPORTANT FIX HERE
            $query->whereIn('cfs.id', $ids);
        }

        else {
            return response()->json([
                'status' => false,
                'message' => 'No records selected for export'
            ], 400);
        }

        $feeStructures = $query
            ->orderByDesc('cfs.batch_year')
            ->orderByDesc('cfs.academic_year')
            ->orderByDesc('cfs.created_at')
            ->get();

        if ($feeStructures->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No records found'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Process JSON Fields
        |--------------------------------------------------------------------------
        */
        $feeStructures->each(function ($item) {

            $item->allocated_seats = 0;

            if (!empty($item->sections)) {
                $sections = json_decode($item->sections, true);
                if (is_array($sections)) {
                    foreach ($sections as $section) {
                        $item->allocated_seats += $section['seats'] ?? 0;
                    }
                }
            }

            $item->total_fee       = is_numeric($item->total_fee) ? $item->total_fee : 0;
            $item->total_seats     = is_numeric($item->total_seats) ? $item->total_seats : 0;
            $item->available_seats = is_numeric($item->available_seats) ? $item->available_seats : 0;
        });

        $fileName = 'fee_structure_' . now()->format('Y_m_d_His');

        switch ($request->type) {

            case 'excel':
                return Excel::download(
                    new FeeStructureExport($feeStructures),
                    $fileName . '.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new FeeStructureExport($feeStructures),
                    $fileName . '.csv'
                );

            case 'pdf':
                $pdf = Pdf::loadView(
                    'pdf.fee_structure_export',
                    compact('feeStructures')
                );
                return $pdf->download($fileName . '.pdf');

            default:
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid export type'
                ], 400);
        }
    }


}