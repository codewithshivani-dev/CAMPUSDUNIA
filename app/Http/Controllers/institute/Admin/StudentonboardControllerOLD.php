<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\FincapMerchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentParentAddress;
use App\Models\StudentParentBankAccount;
use App\Models\StudentParentDetails;
use App\Models\StudentParentDocuments;
use App\Models\StudentAcademicTransportDetails;
use App\Models\DepartmentCategory;
use App\Models\TransportDetails;
use App\Models\InstituteBasicDetails;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentonboardController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    public function getAcademicDetails($productId)
    {
        try {
            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            // Check if user has institute access
            if (!$context['institute_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ]);
            }

            // Get basic product details with institute context
            $productQuery = DB::table('product_details')
                ->where('product_id', $productId);

            // Apply institute/branch context
            if ($context['is_branch_admin'] && $context['branch_id']) {
                $productQuery->where('branch_id', $context['branch_id']);
            } else {
                $productQuery->whereNull('branch_id');
            }

            $productDetails = $productQuery->first();

            if (!$productDetails) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or you do not have access to this product.'
                ]);
            }

            // Get course fee structures with additional details and institute context
            $courseQuery = DB::table('course_fee_structures')
                ->where('product_id', $productId)
                ->where('institute_id', $context['institute_id']);

            // Apply branch filter if branch admin
            if ($context['is_branch_admin'] && $context['branch_id']) {
                $courseQuery->where('branch_id', $context['branch_id']);
            } else {
                $courseQuery->whereNull('branch_id');
            }

            $courseDetails = $courseQuery->get();

            // Get the latest batch record
            $latestBatch = $courseDetails->sortByDesc('batch_id')->first();

            // Extract unique values for dropdowns
            $batches = $courseDetails->unique('batch_id')->map(function ($item) {
                return [
                    'batch_id' => $item->batch_id,
                    'batch_name' => $item->batch
                ];
            })->values();

            $academicYears = $courseDetails->unique('academic_year_id')->map(function ($item) {
                return [
                    'academic_year_id' => $item->academic_year_id,
                    'academic_year_name' => $item->academic_year
                ];
            })->values();

            $courseModes = $courseDetails->unique('mode_of_course')->pluck('mode_of_course');
            $modeTypes = $courseDetails->unique('mode_type')->pluck('mode_type');

            // Get semesters
            $semesters = [];
            if ($productDetails->semesters) {
                $semesters = json_decode($productDetails->semesters, true) ?? [];
            }
            $sections = [];
            if ($courseDetails->isNotEmpty()) {
                // Try to get sections from the first course record that has sections
                $courseWithSections = $courseDetails->firstWhere('sections', '!=', null);
                if ($courseWithSections && $courseWithSections->sections) {
                    $sections = json_decode($courseWithSections->sections, true) ?? [];
                }
            }
            return response()->json([
                'success' => true,
                'batches' => $batches,
                'academic_years' => $academicYears,
                'course_modes' => $courseModes,
                'mode_types' => $modeTypes,
                'semesters' => $semesters,
                'sections' => $sections,
                'product_details' => $productDetails,
                'course_details' => $courseDetails,
                'latest_batch' => $latestBatch ? [
                    'batch_id' => $latestBatch->batch_id,
                    'batch_name' => $latestBatch->batch,
                    'academic_year_id' => $latestBatch->academic_year_id,
                    'academic_year_name' => $latestBatch->academic_year
                ] : null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching academic details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTransportDetails()
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Get departments with institute/branch context
        $departmentQuery = DB::table('departments')
            ->select('department_id', 'department')
            ->where('institute_id', $context['institute_id']);

        // Apply branch filter if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $departmentQuery->where('branch_id', $context['branch_id']);
        } else {
            $departmentQuery->whereNull('branch_id');
        }

        $departments = $departmentQuery->orderBy('department')->get();

        // Get department categories (assuming you have a model or table for this)
        $departmentCategoriesQuery = DB::table('department_categories')
            ->select('department_category_id', 'category_name')
            ->where('institute_id', $context['institute_id']);

        // Apply branch filter if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $departmentCategoriesQuery->where('branch_id', $context['branch_id']);
        } else {
            $departmentCategoriesQuery->whereNull('branch_id');
        }

        $departmentCategories = $departmentCategoriesQuery->orderBy('category_name')->get();

        return view('instituteAdmin.StudentFiles.AddStudentsDetails1', array_merge(
            [
                'departments' => $departments,
                'departmentCategories' => $departmentCategories,
                'institute_context' => $context
            ]
        ));
    }

    private function generateStudentHashId()
    {
        // Generate 3 segments of 5 characters each
        $part1 = Str::upper(Str::random(5)); // e.g. "FMSCE"
        $part2 = Str::upper(Str::random(5)); // e.g. "DV375"
        $part3 = Str::upper(Str::random(5)); // e.g. "OWVB"

        return $part1 . $part2 . $part3; // Combine to make "FMSCEDV375OWVB"
    }

    // StudentController.php
    public function handleStudentFormSubmission(Request $request)
    {
        DB::beginTransaction();

        try {
            $step = $request->input('form_step');
            $studentHashId = null; // Initialize variable
            $result = []; // Initialize result array

            switch ($step) {
                case 1:
                    $result = $this->saveForm1Data($request);
                    $studentHashId = $result['hash_id'];
                    $request->session()->put('current_student_hash_id', $studentHashId);
                    break;

                case 2:
                case 3:
                case 4:
                case 5:
                    $studentHashId = $this->getCurrentStudentHashId($request);
                    if (!$studentHashId) {
                        throw new \Exception("Student reference missing");
                    }

                    $method = 'saveForm' . $step . 'Data';
                    $this->{$method}($request, $studentHashId);
                    break;

                default:
                    throw new \Exception("Invalid form step");
            }

            DB::commit();

            // Build response with guardian type for step 1
            $response = [
                'success' => true,
                'student_id' => $result['id'] ?? null,  // Only for step 1
                'student_hash_id' => $studentHashId,
                'message' => "Form $step data saved successfully"
            ];

            // Add guardian type only for step 1
            if ($step == 1 && isset($result['guardian_type'])) {
                $response['guardian_type'] = $result['guardian_type'];
            }

            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getCurrentStudentHashId($request)
    {
        if ($request->form_step == 1)
            return null;

        if ($request->has('student_hash_id')) {
            return $request->student_hash_id;
        }

        if ($request->session()->has('current_student_hash_id')) {
            return $request->session()->get('current_student_hash_id');
        }

        throw new \Exception("Student reference missing");
    }

    private function saveForm1Data($request)
    {
        $studentHashId = $this->generateStudentHashId();

        // Create full name from first, middle, last names
        $fullName = trim($request->first_name . ' ' .
            ($request->middle_name ? $request->middle_name . ' ' : '') .
            $request->last_name);


        // $tempPassword = \Illuminate\Support\Str::random(10);
        $tempPassword = '12345678';

        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Check if user has institute access
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }

        // Create user account for student WITH institute/branch context
        $user = \App\Models\User::create([
            'name' => $fullName,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($tempPassword),
            'email_verified_at' => now(), // Auto-verify for students

            // Add institute/branch context to user
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,

            // You might also want to set these if your users table has them
            // 'created_by' => auth()->id(),
            // 'updated_by' => auth()->id(),
        ]);

        // Assign student role
        $user->assignRole('student');

        $data = [
            'student_hash_id' => $studentHashId,
            'user_id' => $user->id, // Link to user account

            // Institute/Branch context
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,

            'registration_number' => $request->registration_number,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'nationality' => $request->nationality,
            'religion' => $request->religion,
            'blood_group' => $request->blood_group,
            'category' => $request->blood_group,

            //Father Details
            'father_first_name' => $request->father_first_name,
            'father_middle_name' => $request->father_middle_name,
            'father_last_name' => $request->father_last_name,
            'father_dob' => $request->father_dob,
            'father_email' => $request->father_email,
            'father_phone' => $request->father_phone,
            'father_occupation' => $request->father_occupation,
            'father_income' => $request->father_income,
            'father_blood_group' => $request->father_blood_group,
            'father_nationality' => $request->father_nationality,
            'father_religion' => $request->father_religion,
            'father_category' => $request->father_category,

            //Mother Details
            'mother_first_name' => $request->mother_first_name,
            'mother_middle_name' => $request->mother_middle_name,
            'mother_last_name' => $request->mother_last_name,
            'mother_dob' => $request->mother_dob,
            'mother_email' => $request->mother_email,
            'mother_phone' => $request->mother_phone,
            'mother_occupation' => $request->mother_occupation,
            'mother_income' => $request->mother_income,
            'mother_blood_group' => $request->mother_blood_group,
            'mother_nationality' => $request->mother_nationality,
            'mother_religion' => $request->mother_religion,
            'mother_category' => $request->mother_category,

            // Parent fields
            'guardian_type' => $request->guardian_type,
            'guardian_relation' => $request->guardian_relation,
            'guardian_first_name' => $request->guardian_first_name,
            'guardian_middle_name' => $request->guardian_middle_name,
            'guardian_last_name' => $request->guardian_last_name,
            'guardian_gender' => $request->guardian_gender,
            'guardian_dob' => $request->guardian_dob,
            'guardian_email' => $request->guardian_email,
            'guardian_phone' => $request->guardian_phone,
            'alternate_phone_number' => $request->alternate_phone_number,
            'guardian_occupation' => $request->guardian_occupation,
            'guardian_income' => $request->guardian_income,
            'guardian_blood_group' => $request->guardian_blood_group,
            'guardian_nationality' => $request->guardian_nationality,
            'guardian_religion' => $request->guardian_religion,
            'guardian_category' => $request->guardian_category,
        ];

        $student = StudentParentDetails::create($data);

        // Store hash ID and context in session for subsequent forms
        $request->session()->put('current_student_hash_id', $studentHashId);
        $request->session()->put('current_institute_id', $context['institute_id']);
        $request->session()->put('current_branch_id', $context['is_branch_admin'] ? $context['branch_id'] : null);

        return [
            'id' => $student->id,
            'hash_id' => $studentHashId,
            'user_id' => $user->id,
            'guardian_type' => $request->guardian_type,
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            'email' => $request->email,
            'password' => $tempPassword, // Return temp password for reference
        ];
    }

    private function saveForm2Data($request, $studentHashId)
    {
        StudentParentAddress::updateOrCreate(
            ['student_hash_id' => $studentHashId],
            [
                // Student addresses
                'student_perm_address_line1' => $request->student_perm_address_line1,
                'student_perm_address_line2' => $request->student_perm_address_line2,
                'student_perm_city' => $request->student_perm_city,
                'student_perm_state' => $request->student_perm_state,
                'student_perm_pincode' => $request->student_perm_pincode,
                'student_comm_address_line1' => $request->student_comm_address_line1,
                'student_comm_address_line2' => $request->student_comm_address_line2,
                'student_comm_city' => $request->student_comm_city,
                'student_comm_state' => $request->student_comm_state,
                'student_comm_pincode' => $request->student_comm_pincode,
                // Parent addresses
                'parent_perm_address_line1' => $request->parent_perm_address_line1,
                'parent_perm_address_line2' => $request->parent_perm_address_line2,
                'parent_perm_city' => $request->parent_perm_city,
                'parent_perm_state' => $request->parent_perm_state,
                'parent_perm_pincode' => $request->parent_perm_pincode,
                'parent_comm_address_line1' => $request->parent_comm_address_line1,
                'parent_comm_address_line2' => $request->parent_comm_address_line2,
                'parent_comm_city' => $request->parent_comm_city,
                'parent_comm_state' => $request->parent_comm_state,
                'parent_comm_pincode' => $request->parent_comm_pincode,

                //guardian addresses
                'guardian_perm_address_line1' => $request->guardian_perm_address_line1,
                'guardian_perm_address_line2' => $request->guardian_perm_address_line2,
                'guardian_perm_city' => $request->guardian_perm_city,
                'guardian_perm_state' => $request->guardian_perm_state,
                'guardian_perm_pincode' => $request->guardian_perm_pincode,
                'guardian_comm_address_line1' => $request->guardian_comm_address_line1,
                'guardian_comm_address_line2' => $request->guardian_comm_address_line2,
                'guardian_comm_city' => $request->guardian_comm_city,
                'guardian_comm_state' => $request->guardian_comm_state,
                'guardian_comm_pincode' => $request->guardian_comm_pincode,

            ]
        );

    }

    private function saveForm3Data($request, $studentHashId)
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Check if user has institute access
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }

        // Verify the product belongs to current institute/branch scope
        $productQuery = DB::table('product_details')
            ->where('product_id', $request->course_subtype_id);

        // Apply institute/branch context
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $productQuery->where('branch_id', $context['branch_id']);
        } else {
            $productQuery->whereNull('branch_id');
        }

        $product = $productQuery->first();

        if (!$product) {
            throw new \Exception('Invalid course selected or you do not have access to this branch.');
        }

        // Verify department belongs to current institute/branch scope
        $departmentQuery = DB::table('departments')
            ->where('department_id', $request->department_id);

        if ($context['is_branch_admin'] && $context['branch_id']) {
            $departmentQuery->where('branch_id', $context['branch_id']);
        } else {
            $departmentQuery->where('institute_id', $context['institute_id'])
                ->whereNull('branch_id');
        }

        $department = $departmentQuery->first();

        if (!$department) {
            throw new \Exception('Invalid department selected or you do not have access to this department.');
        }

        // Verify department category belongs to current institute/branch scope
        $departmentCategoryQuery = DB::table('department_categories')
            ->where('department_category_id', $request->department_category_id);

        if ($context['is_branch_admin'] && $context['branch_id']) {
            $departmentCategoryQuery->where('branch_id', $context['branch_id']);
        } else {
            $departmentCategoryQuery->where('institute_id', $context['institute_id'])
                ->whereNull('branch_id');
        }

        $departmentCategory = $departmentCategoryQuery->first();

        if (!$departmentCategory) {
            throw new \Exception('Invalid department category selected or you do not have access to this category.');
        }

        \Log::debug('Saving Form 3 Data:', [
            'mode_of_course' => $request->mode_of_course,
            'mode_type' => $request->mode_type,
            'student_hash_id' => $studentHashId,
            'all_request_data' => $request->all()
        ]);

        // Let's explicitly check and sanitize the values
        $modeOfCourse = $request->mode_of_course;
        $modeType = $request->mode_type;

        // Convert to lowercase or handle nulls
        $modeOfCourse = $modeOfCourse ? strtolower(trim($modeOfCourse)) : null;
        $modeType = $modeType ? strtolower(trim($modeType)) : null;

        // Log the processed values
        \Log::debug('Processed values:', [
            'mode_of_course' => $modeOfCourse,
            'mode_type' => $modeType
        ]);

        // Save only academic details (no transport)
        StudentAcademicTransportDetails::updateOrCreate(
            ['student_hash_id' => $studentHashId],
            [
                // Institute/branch context
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,

                // Department and Category
                'department_id' => $request->department_id,
                'department_category_id' => $request->department_category_id,
                'department' => $request->department,

                // Course Type - both ID and name
                'course_type_id' => $request->course_type_id,
                'course_type' => $request->course_type,

                // Course Subtype - both ID and name
                'course_subtype_id' => $request->course_subtype_id,
                'course_subtype' => $request->course_subtype,

                'session_id' => $request->session_id,
                'semester_id' => $request->semester_id,
                'section_id' => $request->section_id,

                // Batch fields
                'batch_id' => $request->batch_id,
                'batch' => $request->batch_name,

                // Academic Year fields
                'academic_year_id' => $request->academic_year_id,
                'academic_year' => $request->academic_year_name,

                // Use processed values
                'mode_of_course' => $modeOfCourse,
                'mode_type' => $modeType,

            ]
        );
    }

    private function saveForm4Data($request, $studentHashId)
    {
        // dd($request->all());
        $data = [
            'student_hash_id' => $studentHashId,
            'student_aadhaar_number' => $request->student_aadhaar_number,
            'student_pan_number' => $request->student_pan_number,
            'parent_aadhaar_number' => $request->parent_aadhaar_number,
            'parent_pan_number' => $request->parent_pan_number,
            'guardian_aadhaar_number' => $request->guardian_aadhaar_number,
            'guardian_pan_number' => $request->guardian_pan_number,
        ];

        // Handle file uploads
        $fileFields = [
            'student_aadhaar_file' => 'student_aadhaar_file',
            'student_pan_file' => 'student_pan_file',
            'student_photo' => 'student_photo',
            'student_id_card' => 'student_id_card',
            'student_address_proof' => 'student_address_proof',
            'student_bonafide' => 'student_bonafide',
            'parent_aadhaar_file' => 'parent_aadhaar_file',
            'parent_pan_file' => 'parent_pan_file',
            'parent_income_proof' => 'parent_income_proof',
            'parent_photo' => 'parent_photo',
            'parent_address_proof' => 'parent_address_proof',
            'guardian_aadhaar_file' => 'guardian_aadhaar_file',
            'guardian_pan_file' => 'guardian_pan_file',
            'guardian_photo' => 'guardian_photo',
            'guardian_address_proof' => 'guardian_address_proof',
        ];

        foreach ($fileFields as $field => $dbField) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('student_documents', 'public');
                $data[$dbField] = $path;
            }
        }

        // Handle academic documents
        $academicFiles = [
            'marksheet_10' => 'marksheet_10',
            'marksheet_12' => 'marksheet_12',
            'bachelor_marksheet' => 'bachelor_marksheet',
        ];

        foreach ($academicFiles as $field => $dbField) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('academic_documents', 'public');
                $data[$dbField] = $path;
            }
        }

        // Handle other documents
        $otherDocs = [];
        for ($i = 1; $i <= 3; $i++) {
            if (
                $request->hasFile("student_other_doc_file_$i") &&
                $request->input("student_other_doc_label_$i")
            ) {
                $path = $request->file("student_other_doc_file_$i")->store('other_documents', 'public');
                $otherDocs[] = [
                    'label' => $request->input("student_other_doc_label_$i"),
                    'path' => $path
                ];
            }
        }

        if (!empty($otherDocs)) {
            $data['other_documents'] = json_encode($otherDocs);
        }

        StudentParentDocuments::updateOrCreate(
            ['student_hash_id' => $studentHashId],
            $data
        );
    }

    private function saveForm5Data($request, $studentHashId)
    {
        $data = [
            'student_hash_id' => $studentHashId,
            // Parent Bank Details
            'benificiary_name' => $request->benificiary_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'account_type' => $request->account_type,
        ];

        // Handle parent cancelled cheque file upload
        if ($request->hasFile('upload_cancelled_cheque')) {
            $path = $request->file('upload_cancelled_cheque')->store('bank_documents/parent', 'public');
            $data['upload_cancelled_cheque'] = $path;
        }

        // Handle student bank details if checkbox is checked
        if ($request->has('add_student_bank') && $request->add_student_bank == '1') {
            $data = array_merge($data, [
                // Student Bank Details
                'student_benificiary_name' => $request->student_benificiary_name,
                'student_bank_account_number' => $request->student_bank_account_number,
                'student_bank_name' => $request->student_bank_name,
                'student_ifsc_code' => $request->student_ifsc_code,
                'student_account_type' => $request->student_account_type,
            ]);

            // Handle student cancelled cheque file upload
            if ($request->hasFile('student_upload_cancelled_cheque')) {
                $path = $request->file('student_upload_cancelled_cheque')->store('bank_documents/student', 'public');
                $data['student_upload_cancelled_cheque'] = $path;
            }
        } else {
            // Clear student bank details if checkbox is not checked
            $data = array_merge($data, [
                'student_benificiary_name' => null,
                'student_bank_account_number' => null,
                'student_bank_name' => null,
                'student_ifsc_code' => null,
                'student_account_type' => null,
                'student_upload_cancelled_cheque' => null,
            ]);
        }

        StudentParentBankAccount::updateOrCreate(
            ['student_hash_id' => $studentHashId],
            $data
        );
    }

    public function allStudentsData(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->with([
                'stakeholders',
                'stakeholderDocuments',
                'authorizedUser',
                'authorizedUserDocuments',
                'documents',
                'beneficiary'
            ])
            ->first();
        
        // ---------------------
        // BASE QUERY
        // ---------------------
        $query = StudentParentDetails::where('institute_id', $merchantId)
            ->with('academicTransportDetails')
            ->select(
                'student_hash_id',
                'registration_number',
                'first_name',
                'last_name',
                'dob',
                'gender',
                'mobile',
                'email'
            );
        
        // ---------------------
        // FILTERS
        // ---------------------
        if ($request->filled('registration_number')) {
            $query->where('registration_number', 'LIKE', "%{$request->registration_number}%");
        }
        
        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'LIKE', "%{$request->name}%")
                ->orWhere('last_name', 'LIKE', "%{$request->name}%");
            });
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        if ($request->filled('course_type')) {
            $query->whereHas('academicTransportDetails', function ($q) use ($request) {
                $q->where('course_type', $request->course_type);
            });
        }

        if ($request->filled('section_id')) {
            $query->whereHas('academicTransportDetails', function ($q) use ($request) {
                $q->where('section_id', 'LIKE', "%{$request->section_id}%");
            });
        }
        
        if ($request->filled('dob')) {
            $query->whereDate('dob', $request->dob);
        }
        
        // ---------------------
        // PAGINATION
        // ---------------------
        $students = $query->paginate(15);
        
        // =========================================================
        // SECTION NAME RESOLUTION
        // =========================================================
        
        // Collect all course ids
        $courseIds = collect();

        foreach ($students as $student) {
            $academic = $student->academicTransportDetails;
            if ($academic && $academic->course_subtype_id) {
                $courseIds->push($academic->course_subtype_id);
            }
        }
        
        $courseIds = $courseIds->unique()->values();

        $sectionDataMap = [];
        
        if ($courseIds->isNotEmpty()) {
        
            $courseFeeStructures = DB::table('course_fee_structures as cfs')
                ->whereIn('cfs.product_id', $courseIds)
                ->where('cfs.institute_id', $merchantId)
                ->select('product_id', 'sections')
                ->get();
        
            foreach ($courseFeeStructures as $courseFee) {
        
                $sections = json_decode($courseFee->sections, true);
        
                if (is_array($sections)) {
                    foreach ($sections as $section) {
        
                        if (isset($section['id'], $section['name'])) {
                            $key = $courseFee->product_id . '_' . $section['id'];
                            $sectionDataMap[$key] = $section['name'];
                        }
                    }
                }
            }
        }
        
        // Attach section names
        foreach ($students as $student) {
            $academic = $student->academicTransportDetails;
            if (!$academic) {
                continue;
            }
            $lookupKey = $academic->course_subtype_id . '_' . $academic->section_id;
            $academic->section_name =
                $sectionDataMap[$lookupKey] ?? null;
        }
            
        // ---------------------
        // DATALIST VALUES
        // ---------------------
        $regNumbers = StudentParentDetails::select('registration_number')
            ->where('institute_id', $merchantId)
            ->distinct()->get();
        
        $names = StudentParentDetails::select('first_name')
            ->where('institute_id', $merchantId)
            ->distinct()->get();
        
        $genders = StudentParentDetails::select('gender')
            ->where('institute_id', $merchantId)
            ->distinct()->get();

        $courses = StudentAcademicTransportDetails::where('institute_id', $merchantId)
            ->select('course_type')
            ->distinct()
            ->pluck('course_type');
        
        $dobs = StudentParentDetails::select('dob')
            ->where('institute_id', $merchantId)
            ->distinct()->get();
        
        return view('instituteAdmin.StudentFiles.ViewStudentDetails', compact(
            'students',
            'fincapMerchants',
            'regNumbers',
            'names',
            'genders',
            'courses',
            'dobs'
        ));
    }

    // Detail view - all related data
    public function getStudentDetails($hash_id)
    {
        $student = StudentParentDetails::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
        $address = StudentParentAddress::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
        $bank = StudentParentBankAccount::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
        $documents = StudentParentDocuments::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
        $extra = StudentAcademicTransportDetails::where('student_hash_id', $hash_id)->first() ?? new \stdClass();

        return view('instituteAdmin.StudentFiles.StudentViewPage', compact('student', 'address', 'bank', 'documents', 'extra'));
    }

    public function editstudent($id)
    {
        $student = StudentParentDetails::where('student_hash_id', $id)->first() ?? new \stdClass();
        $address = StudentParentAddress::where('student_hash_id', $id)->first() ?? new \stdClass();
        $bank = StudentParentBankAccount::where('student_hash_id', $id)->first() ?? new \stdClass();
        $documents = StudentParentDocuments::where('student_hash_id', $id)->first() ?? new \stdClass();
        $extra = StudentAcademicTransportDetails::where('student_hash_id', $id)->first() ?? new \stdClass();
        $departmentCategories = DepartmentCategory::all();
        return view('instituteAdmin.StudentFiles.EditStudentDetails', compact('student', 'address', 'bank', 'documents', 'extra', 'departmentCategories'));
    }

    public function updatestudent(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            // 1️⃣ Student + Parent Basic Details
            StudentParentDetails::updateOrCreate(
                ['student_hash_id' => $id],
                $request->only([
                    'student_hash_id',
                    'user_id',
                    'institute_id',
                    'branch_id',
                    'registration_number',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'dob',
                    'gender',
                    'mobile',
                    'email',
                    'nationality',
                    'religion',
                    'category',
                    'blood_group',

                    //Father Details
                    'father_first_name',
                    'father_middle_name',
                    'father_last_name',
                    'father_dob',
                    'father_email',
                    'father_phone',
                    'father_occupation',
                    'father_income',
                    'father_blood_group',
                    'father_nationality',
                    'father_religion',
                    'father_category',

                    //Mother Details
                    'mother_first_name',
                    'mother_middle_name',
                    'mother_last_name',
                    'mother_dob',
                    'mother_email',
                    'mother_phone',
                    'mother_occupation',
                    'mother_income',
                    'mother_blood_group',
                    'mother_nationality',
                    'mother_religion',
                    'mother_category',

                    // Parent fields
                    'guardian_type',
                    'guardian_relation',
                    'guardian_first_name',
                    'guardian_middle_name',
                    'guardian_last_name',
                    'guardian_gender',
                    'guardian_dob',
                    'guardian_email',
                    'guardian_phone',
                    'alternate_phone_number',
                    'guardian_occupation',
                    'guardian_income',
                    'guardian_nationality',
                    'guardian_religion',
                    'guardian_category',
                ])
            );

            // 2️⃣ Address
            StudentParentAddress::updateOrCreate(
                ['student_hash_id' => $id],
                $request->only([
                    // Student addresses
                    'student_perm_address_line1',
                    'student_perm_address_line2',
                    'student_perm_city',
                    'student_perm_state',
                    'student_perm_pincode',
                    'student_comm_address_line1',
                    'student_comm_address_line2',
                    'student_comm_city',
                    'student_comm_state',
                    'student_comm_pincode',
                    // Parent addresses
                    'parent_perm_address_line1',
                    'parent_perm_address_line2',
                    'parent_perm_city',
                    'parent_perm_state',
                    'parent_perm_pincode',
                    'parent_comm_address_line1',
                    'parent_comm_address_line2',
                    'parent_comm_city',
                    'parent_comm_state',
                    'parent_comm_pincode',

                    //guardian addresses
                    'guardian_perm_address_line1',
                    'guardian_perm_address_line2',
                    'guardian_perm_city',
                    'guardian_perm_state',
                    'guardian_perm_pincode',
                    'guardian_comm_address_line1',
                    'guardian_comm_address_line2',
                    'guardian_comm_city',
                    'guardian_comm_state',
                    'guardian_comm_pincode',
                ])
            );

            // 3️⃣ Bank
            StudentParentBankAccount::updateOrCreate(
                ['student_hash_id' => $id],
                $request->only([
                    // Parent Bank Details
                    'benificiary_name',
                    'bank_account_number',
                    'bank_name',
                    'ifsc_code',
                    'account_type',
                    // Student Bank Details
                    'student_benificiary_name',
                    'student_bank_account_number',
                    'student_bank_name',
                    'student_ifsc_code',
                    'student_account_type',
                ])
            );

            StudentAcademicTransportDetails::updateOrCreate(
                ['student_hash_id' => $id],
                $request->only([
                    // Department and Category
                    'department_id',
                    'department_category_id',
                    'department',

                    // Course Type - both ID and name
                    'course_type_id',
                    'course_type',

                    // Course Subtype - both ID and name
                    'course_subtype_id',
                    'course_subtype',
                    'session_id',
                    'semester_id',
                    'section_id',

                    // Batch fields
                    'batch_id',
                    'batch',

                    // Academic Year fields
                    'academic_year_id',
                    'academic_year',

                    // Use processed values
                    'mode_of_course',
                    'mode_type',
                ])
            );

            $documents = StudentParentDocuments::updateOrCreate(
                ['student_hash_id' => $id,],
                $request->only([
                    'student_aadhaar_number',
                    'student_pan_number',
                    'parent_aadhaar_number',
                    'parent_pan_number',
                    'guardian_aadhaar_number',
                    'guardian_pan_number',
                ])
            );

            if ($request->hasFile('student_aadhaar_file')) {
                $documents->student_aadhaar_file = $request->file('student_aadhaar_file')->store('documents');
            }

            $documents->save();
        });

        return redirect()->back()->with('success', 'Student details updated successfully');
    }
 
    public function downloadStudentsData(Request $request)
    {
        // ✅ Validate
        $validated = $request->validate([
            'type' => 'required|in:excel,csv,pdf',
            'ids' => 'nullable|string',
            'select_all' => 'nullable|boolean',
            'filters' => 'nullable|string',
        ]);
    
        // ✅ Get institute & branch context
        $context = $this->getInstituteBranchContext();
    
        $instituteId = $context['institute_id'];
        $branchId = $context['is_branch_admin'] ? $context['branch_id'] : null;
    
        // ✅ Base Query
        
        $query = StudentParentDetails::where('institute_id', $instituteId)->with('academicTransportDetails');
    // dd($query);
        // Apply branch restriction
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
    
        // If select_all is checked, apply the same filters as the current view
        if ($request->has('select_all') && $request->select_all == 1) {
            // Apply the same filters that were used in the index page
            if ($request->has('filters') && $filters = json_decode($request->filters, true)) {
                // Apply registration number filter
                if (!empty($filters['registration_number'])) {
                    $query->where('registration_number', 'LIKE', '%' . $filters['registration_number'] . '%');
                }
                
                // Apply name filter
                if (!empty($filters['name'])) {
                    $query->where(function($q) use ($filters) {
                        $q->where('first_name', 'LIKE', '%' . $filters['name'] . '%')
                          ->orWhere('last_name', 'LIKE', '%' . $filters['name'] . '%');
                    });
                }
                
                // Apply gender filter
                if (!empty($filters['gender'])) {
                    $query->where('gender', $filters['gender']);
                }
                
                // Add other filters as needed based on your filter form
                if (!empty($filters['mobile'])) {
                    $query->where('mobile', 'LIKE', '%' . $filters['mobile'] . '%');
                }
                
                if (!empty($filters['course_type'])) {
                    $query->whereHas('academicTransportDetails', function ($q) use ($filters) {
                        $q->where('course_type', $filters['course_type']);
                    });
                }
            }
        } 
        // If specific IDs are provided
        elseif ($request->filled('ids')) {
            $ids = array_filter(explode(',', $validated['ids']));
            $query->whereIn('student_hash_id', $ids);
        }
        // If neither select_all nor ids provided
        else {
            return back()->with('error', 'No students selected for export');
        }
    
        // Load relations
        $students = $query->with([
            'address',
            'bankAccount',
            'academicTransportDetails',
            'documents'
        ])->get();
    
        if ($students->isEmpty()) {
            return back()->with('error', 'No students found for export');
        }
    
        $fileName = 'students_' . now()->format('Y_m_d_His');
    
        // ✅ Download Switch
        switch ($validated['type']) {
            case 'excel':
                return Excel::download(
                    new StudentsExport($students),
                    $fileName . '.xlsx'
                );
    
            case 'csv':
                return Excel::download(
                    new StudentsExport($students),
                    $fileName . '.csv'
                );
    
            case 'pdf':
                $pdf = Pdf::loadView('pdf.student_full_export', compact('students'));
                return $pdf->download($fileName . '.pdf');
                
            default:
                return back()->with('error', 'Invalid download type');
        }
    }

    public function count(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        $query = StudentParentDetails::where('institute_id', $context['institute_id']);

        if ($context['is_branch_admin']) {
            $query->where('branch_id', $context['branch_id']);
        }

        $filters = $request->all();

        if (!empty($filters['registration_number'])) {
            $query->where('registration_number','LIKE','%'.$filters['registration_number'].'%');
        }

        if (!empty($filters['name'])) {
            $query->where(function($q) use ($filters){
                $q->where('first_name','LIKE','%'.$filters['name'].'%')
                ->orWhere('last_name','LIKE','%'.$filters['name'].'%');
            });
        }

        if (!empty($filters['gender'])) {
            $query->where('gender',$filters['gender']);
        }

        return response()->json([
            'count' => $query->count()
        ]);
    }
}


// 'select_all' => 'nullable|boolean',


// // Apply branch restriction
// if ($branchId) {
//     $query->where('branch_id', $branchId);
// }

// // Apply ID filter only if NOT select all
// if (empty($validated['select_all']) && !empty($validated['ids'])) {
//     $ids = array_filter(explode(',', $validated['ids']));
//     $query->whereIn('student_hash_id', $ids);
// }

// // Load relations
// $students = $query->with([
//     'address',
//     'bankAccount',
//     'academicTransportDetails',
//     'documents'
// ])->get();
