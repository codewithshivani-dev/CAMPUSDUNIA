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
use Illuminate\Support\Facades\Validator;
use App\Models\CourseFeeStructure;
use App\Models\StudentSibling;
use App\Http\Controllers\institute\Admin\StudentFeeController;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\StudentCourseFeeStructure;
use App\Models\StudentCustomFeestructure;
use App\Models\StudentHostelFeeStructure;
use App\Models\StudentMiscellaneousFeeStructure;
use App\Models\StudentRegistrationFeeStructure;
use App\Models\StudentTransportFeeStructure;
use App\Models\LessonPlan;
use App\Models\SubjectsCoursewise;
use Illuminate\Support\Facades\Mail;
use App\Models\InstituteNotificationSetting;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])
            ->with([
                'stakeholders',
                'stakeholderDocuments',
                'authorizedUser',
                'authorizedUserDocuments',
                'documents',
                'beneficiary'
            ])->first();

        $instituteType = $fincapMerchants->type ?? null;

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
                'institute_context' => $context,
                'instituteType' => $instituteType
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

    public function handleStudentFormSubmission(Request $request)
    {
        DB::beginTransaction();

        try {
            $step = $request->input('form_step');
            $studentHashId = null; // Initialize variable
            $result = []; // Initialize result array

            switch ($step) {
                case 1:
                    $request->validate([
                        'blood_group' => ['required', 'string'],
                        'height_unit' => ['required', 'in:cm,ft_in'],
                        'height_cm' => ['nullable', 'numeric', 'min:0'],
                        'height_feet' => ['nullable', 'numeric', 'min:0'],
                        'height_inches' => ['nullable', 'numeric', 'min:0', 'max:11.9'],
                        'weight_unit' => ['required', 'in:kg,lbs'],
                        'weight_input' => ['nullable', 'numeric', 'min:0'],
                    ]);
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
        // ============================================================
        // CHECK WHETHER THIS FORM 1 BELONGS TO AN EXISTING ONBOARDING
        // ============================================================

        $existingHashId = $request->input('student_hash_id');

        if (!$existingHashId) {
            $existingHashId = $request->session()->get('current_student_hash_id');
        }

        $existingStudent = null;

        if ($existingHashId) {
            $existingStudent = StudentParentDetails::where(
                'student_hash_id',
                $existingHashId
            )->first();
        }

        /*
         * If an existing student was found:
         * - DO NOT generate a new hash
         * - DO NOT create another User
         * - DO NOT create another StudentParentDetails
         *
         * Instead, update the existing records.
         */
        if ($existingStudent) {
            $studentHashId = $existingStudent->student_hash_id;

            $user = \App\Models\User::find($existingStudent->user_id);

            if (!$user) {
                throw new \Exception(
                    'Student user account could not be found.'
                );
            }
        } else {
            // ========================================================
            // FIRST SUBMISSION - CREATE NEW STUDENT
            // ========================================================

            $studentHashId = $this->generateStudentHashId();

            $tempPassword = '12345678';

            $context = $this->getInstituteBranchContext();

            if (!$context['institute_id']) {
                throw new \Exception(
                    'You are not associated with any institute.'
                );
            }

            $fullName = trim(
                $request->first_name . ' ' .
                ($request->middle_name
                    ? $request->middle_name . ' '
                    : '') .
                $request->last_name
            );

            $user = \App\Models\User::create([
                'name' => $fullName,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make(
                    $tempPassword
                ),
                'email_verified_at' => now(),
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin']
                    ? $context['branch_id']
                    : null,
            ]);

            $user->assignRole('student');
        }

        // ============================================================
        // GET INSTITUTE / BRANCH CONTEXT
        // ============================================================

        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            throw new \Exception(
                'You are not associated with any institute.'
            );
        }

        // ============================================================
        // FULL NAME
        // ============================================================

        $fullName = trim(
            $request->first_name . ' ' .
            ($request->middle_name
                ? $request->middle_name . ' '
                : '') .
            $request->last_name
        );

        // ============================================================
        // UPDATE USER
        // ============================================================

        $user->update([
            'name' => $fullName,
            'email' => $request->email,
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin']
                ? $context['branch_id']
                : null,
        ]);

        // ============================================================
        // PREPARE STUDENT DATA
        // ============================================================

        $selectedParent = $request->parent_selection ?? 'father';

        $height = null;
        if ($request->height_unit === 'ft_in' && ($request->filled('height_feet') || $request->filled('height_inches'))) {
            $height = ((float) $request->height_feet * 30.48) + ((float) $request->height_inches * 2.54);
        } elseif ($request->filled('height_cm')) {
            $height = (float) $request->height_cm;
        }

        $weight = null;
        if ($request->filled('weight_input')) {
            $weight = $request->weight_unit === 'lbs'
                ? (float) $request->weight_input * 0.45359237
                : (float) $request->weight_input;
        }

        $data = [
            'student_hash_id' => $studentHashId,
            'user_id' => $user->id,

            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin']
                ? $context['branch_id']
                : null,

            'registration_number' => $request->registration_number,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,

            'parent_selection' => $selectedParent,

            'gender' => $request->gender,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'nationality' => $request->nationality,
            'religion' => $request->religion,
            'blood_group' => $request->blood_group,
            'category' => $request->category,
            'height' => $height,
            'height_unit' => $request->height_unit,
            'weight' => $weight,
            'weight_unit' => $request->weight_unit,
            'health_condition' => $request->health_condition,
            'allergies' => $request->allergies,
            'medical_notes' => $request->medical_notes,

            'guardian_type' => $request->guardian_type,
            'guardian_relation' => $request->guardian_relation,
            'guardian_first_name' => $request->guardian_first_name,
            'guardian_middle_name' => $request->guardian_middle_name,
            'guardian_last_name' => $request->guardian_last_name,
            'guardian_gender' => $request->guardian_gender,
            'guardian_dob' => $request->guardian_dob,
            'guardian_email' => $request->guardian_email,
            'guardian_phone' => $request->guardian_phone,
            'guardian_number_of_dependents' =>
                $request->guardian_number_of_dependents,
            'alternate_phone_number' =>
                $request->alternate_phone_number,
            'guardian_occupation' => $request->guardian_occupation,
            'guardian_income' => $request->guardian_income,
            'guardian_blood_group' =>
                $request->guardian_blood_group,
            'guardian_nationality' =>
                $request->guardian_nationality,
            'guardian_religion' =>
                $request->guardian_religion,
            'guardian_category' =>
                $request->guardian_category,

            'student_status' => 'new',
        ];

        // ============================================================
        // FATHER
        // ============================================================

        if ($selectedParent === 'father') {

            $data = array_merge($data, [
                'father_first_name' => $request->father_first_name,
                'father_middle_name' => $request->father_middle_name,
                'father_last_name' => $request->father_last_name,
                'father_dob' => $request->father_dob,
                'father_email' => $request->father_email,
                'father_phone' => $request->father_phone,
                'father_occupation' => $request->father_occupation,
                'father_income' => $request->father_income,
                'father_blood_group' =>
                    $request->father_blood_group,
                'father_nationality' =>
                    $request->father_nationality,
                'father_religion' =>
                    $request->father_religion,
                'father_category' =>
                    $request->father_category,
                'father_marital_status' =>
                    $request->father_marital_status,
                'father_number_of_dependents' =>
                    $request->father_number_of_dependents,
                'father_spouse_name' =>
                    $request->father_spouse_name,

                'mother_first_name' =>
                    $request->father_married_mother_first_name,
                'mother_middle_name' =>
                    $request->father_married_mother_middle_name,
                'mother_last_name' =>
                    $request->father_married_mother_last_name,
                'mother_dob' =>
                    $request->father_married_mother_dob,
                'mother_email' =>
                    $request->father_married_mother_email,
                'mother_phone' =>
                    $request->father_married_mother_phone,
                'mother_occupation' =>
                    $request->father_married_mother_occupation,
                'mother_income' =>
                    $request->father_married_mother_income,
                'mother_blood_group' =>
                    $request->father_married_mother_blood_group,
            ]);

        }

        // ============================================================
        // MOTHER
        // ============================================================
        elseif ($selectedParent === 'mother') {

            $data = array_merge($data, [
                'mother_first_name' => $request->mother_first_name,
                'mother_middle_name' => $request->mother_middle_name,
                'mother_last_name' => $request->mother_last_name,
                'mother_dob' => $request->mother_dob,
                'mother_email' => $request->mother_email,
                'mother_phone' => $request->mother_phone,
                'mother_occupation' => $request->mother_occupation,
                'mother_income' => $request->mother_income,
                'mother_blood_group' =>
                    $request->mother_blood_group,
                'mother_nationality' =>
                    $request->mother_nationality,
                'mother_religion' =>
                    $request->mother_religion,
                'mother_category' =>
                    $request->mother_category,
                'mother_marital_status' =>
                    $request->mother_marital_status,
                'mother_number_of_dependents' =>
                    $request->mother_number_of_dependents,
                'mother_spouse_name' =>
                    $request->mother_spouse_name,

                'father_first_name' =>
                    $request->mother_married_father_first_name,
                'father_middle_name' =>
                    $request->mother_married_father_middle_name,
                'father_last_name' =>
                    $request->mother_married_father_last_name,
                'father_dob' =>
                    $request->mother_married_father_dob,
                'father_email' =>
                    $request->mother_married_father_email,
                'father_phone' =>
                    $request->mother_married_father_phone,
                'father_occupation' =>
                    $request->mother_married_father_occupation,
                'father_income' =>
                    $request->mother_married_father_income,
                'father_blood_group' =>
                    $request->mother_married_father_blood_group,
            ]);
        }

        // ============================================================
        // CREATE OR UPDATE STUDENT
        // ============================================================

        if ($existingStudent) {

            $existingStudent->update($data);

            $student = $existingStudent;

        } else {

            $student = StudentParentDetails::create($data);
        }
        // ============================================================
        // KEEP CURRENT STUDENT IN SESSION
        // ============================================================

        $request->session()->put(
            'current_student_hash_id',
            $studentHashId
        );

        $request->session()->put(
            'current_institute_id',
            $context['institute_id']
        );

        $request->session()->put(
            'current_branch_id',
            $context['is_branch_admin']
            ? $context['branch_id']
            : null
        );

        return [
            'id' => $student->id,
            'hash_id' => $studentHashId,
            'user_id' => $user->id,
            'guardian_type' => $request->guardian_type,
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin']
                ? $context['branch_id']
                : null,
            'email' => $request->email,
            'password' => '12345678',
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

        // ============== NEW: Update sibling record with academic details ==============
        $siblingRecord = StudentSibling::where('student_hash_id', $studentHashId)->first();

        if ($siblingRecord) {
            $siblingRecord->update([
                'course_subtype_id' => $request->course_subtype_id,
                'section_id' => $request->section_id
            ]);
        }
        // =============================================================================

        // ============== NEW: Assign fee structure after academic details are saved ==============
        $this->assignStudentFeeStructure($studentHashId, $context);
        // ========================================================================================
    }

    private function saveForm4Data($request, $studentHashId)
    {
        $guardianType = strtolower((string) StudentParentDetails::where('student_hash_id', $studentHashId)->value('guardian_type'));
        $selectedParent = in_array($guardianType, ['father', 'mother'], true)
            ? $guardianType
            : (StudentParentDetails::where('student_hash_id', $studentHashId)->value('parent_selection') ?? 'father');

        $data = [
            'student_hash_id' => $studentHashId,
            'student_aadhaar_number' => $request->student_aadhaar_number,
            'student_pan_number' => $request->student_pan_number,
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'timestamp' => $request->input('timestamp'),
        ];

        // Parent document numbers are optional and mirror the selected parent.
        $parentDocumentNumbers = [
            'aadhaar_number' => 'parent_aadhaar_number',
            'pan_number' => 'parent_pan_number',
        ];
        foreach ($parentDocumentNumbers as $suffix => $parentField) {
            $selectedField = $selectedParent . '_' . $suffix;
            $value = $request->input($selectedField) ?? $request->input($parentField);
            $data[$parentField] = $value;
            $data[$selectedField] = $value;
        }

        // Handle guardian documents (only if guardian_type is 'other')
        if ($guardianType === 'other') {
            $data['guardian_aadhaar_number'] = $request->guardian_aadhaar_number;
            $data['guardian_pan_number'] = $request->guardian_pan_number;
        } else {
            $data['guardian_aadhaar_number'] = null;
            $data['guardian_pan_number'] = null;
        }

        // Student documents are optional.
        $fileFields = [
            'student_aadhaar_file' => 'student_aadhaar_file',
            'student_pan_file' => 'student_pan_file',
            'student_photo' => 'student_photo',
            'student_id_card' => 'student_id_card',
            'student_address_proof' => 'student_address_proof',
            'student_bonafide' => 'student_bonafide',
        ];

        // Keep separately supplied Father/Mother documents, when available.
        foreach (['father', 'mother'] as $parentType) {
            foreach (['aadhaar_file', 'pan_file', 'income_proof', 'photo', 'address_proof'] as $suffix) {
                $fileFields[$parentType . '_' . $suffix] = $parentType . '_' . $suffix;
            }
        }

        foreach ($fileFields as $field => $dbField) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store('student_documents', 'public');
                $data[$dbField] = $path;
            }
        }

        // The selected Father/Mother document uses one stored path in both column sets.
        foreach (['aadhaar_file', 'pan_file', 'income_proof', 'photo', 'address_proof'] as $suffix) {
            $selectedField = $selectedParent . '_' . $suffix;
            $parentField = 'parent_' . $suffix;
            if ($request->hasFile($selectedField)) {
                $path = $data[$selectedField];
                $data[$parentField] = $path;
                $data[$selectedField] = $path;
            } elseif ($request->hasFile($parentField)) {
                $path = $request->file($parentField)->store('student_documents', 'public');
                $data[$parentField] = $path;
                $data[$selectedField] = $path;
            }
        }

        // Handle academic documents (always stored)
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

        // Handle other documents (always stored)
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

        // Handle guardian documents (only if guardian_type is 'other')
        if ($guardianType === 'other') {
            $guardianFiles = [
                'guardian_aadhaar_file' => 'guardian_aadhaar_file',
                'guardian_pan_file' => 'guardian_pan_file',
                'guardian_income_proof' => 'guardian_income_proof',
                'guardian_photo' => 'guardian_photo',
                'guardian_address_proof' => 'guardian_address_proof',
            ];

            foreach ($guardianFiles as $field => $dbField) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store('student_documents', 'public');
                    $data[$dbField] = $path;
                }
            }
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


    // public function allStudentsData(Request $request)
    // {
    //     $merchantId = auth()->user()->institute_id;
    //     $generatedCertificates = $this->getGeneratedCertificates($merchantId);
    //     $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
    //         ->with([
    //             'stakeholders',
    //             'stakeholderDocuments',
    //             'authorizedUser',
    //             'authorizedUserDocuments',
    //             'documents',
    //             'beneficiary'
    //         ])
    //         ->first();

    //     // ---------------------
    //     // BASE QUERY - ONLY ACTIVE STUDENTS (in both parent and academic transport)
    //     // ---------------------
    //     $query = StudentParentDetails::where('institute_id', $merchantId)
    //         ->where('status', 'active') // Parent status active
    //         ->whereHas('academicTransportDetails', function($q) {
    //             $q->where('status', 'active'); // Academic transport status active
    //         })
    //         ->with('academicTransportDetails')
    //         ->select(
    //             'student_hash_id',
    //             'registration_number',
    //             'first_name',
    //             'last_name',
    //             'dob',
    //             'gender',
    //             'mobile',
    //             'email',
    //             'status' 
    //         );

    //     // ---------------------
    //     // FILTERS
    //     // ---------------------
    //     if ($request->filled('registration_number')) {
    //         $query->where('registration_number', 'LIKE', "%{$request->registration_number}%");
    //     }

    //     if ($request->filled('name')) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('first_name', 'LIKE', "%{$request->name}%")
    //             ->orWhere('last_name', 'LIKE', "%{$request->name}%");
    //         });
    //     }

    //     if ($request->filled('gender')) {
    //         $query->where('gender', $request->gender);
    //     }

    //     if ($request->filled('course_type')) {
    //         $query->whereHas('academicTransportDetails', function ($q) use ($request) {
    //             $q->where('course_type', $request->course_type)
    //             ->where('status', 'active'); // Ensure we're checking active academic transport
    //         });
    //     }

    //     // Updated section filter logic - works with both section_id and section_name
    //     if ($request->filled('section_id') || $request->filled('section_name')) {
    //         $sectionId = $request->filled('section_id') ? $request->section_id : null;
    //         $sectionName = $request->filled('section_name') ? $request->section_name : null;

    //         $query->whereHas('academicTransportDetails', function ($q) use ($sectionId, $sectionName, $merchantId) {
    //             // Ensure we're only checking active academic transport
    //             $q->where('status', 'active');

    //             if ($sectionId) {
    //                 // Filter by exact section ID
    //                 $q->where('section_id', $sectionId);
    //             } elseif ($sectionName) {
    //                 // If only section name is provided, we need to find matching section IDs
    //                 // Get all courses and their sections
    //                 $courseFeeStructures = DB::table('course_fee_structures')
    //                     ->where('institute_id', $merchantId)
    //                     ->get();

    //                 $matchingSectionIds = [];

    //                 foreach ($courseFeeStructures as $courseFee) {
    //                     $sections = json_decode($courseFee->sections, true);
    //                     if (is_array($sections)) {
    //                         foreach ($sections as $section) {
    //                             if (isset($section['name']) && str_contains(strtolower($section['name']), strtolower($sectionName))) {
    //                                 $matchingSectionIds[] = $section['id'];
    //                             }
    //                         }
    //                     }
    //                 }

    //                 if (!empty($matchingSectionIds)) {
    //                     $q->whereIn('section_id', array_unique($matchingSectionIds));
    //                 } else {
    //                     // No matching sections found, return no results
    //                     $q->where('section_id', 'NOT_EXISTING_VALUE');
    //                 }
    //             }
    //         });
    //     }

    //     if ($request->filled('dob')) {
    //         $query->whereDate('dob', $request->dob);
    //     }

    //     if ($request->filled('batch')) {
    //         $query->whereHas('academicTransportDetails', function ($q) use ($request) {
    //             $q->where('batch', 'LIKE', "%{$request->batch}%")
    //             ->where('status', 'active');
    //         });
    //     }

    //     if ($request->filled('academic_year')) {
    //         $query->whereHas('academicTransportDetails', function ($q) use ($request) {
    //             $q->where('academic_year', 'LIKE', "%{$request->academic_year}%")
    //             ->where('status', 'active');
    //         });
    //     }

    //     // ---------------------
    //     // SORTING (if implemented)
    //     // ---------------------
    //     if ($request->filled('sort_by')) {
    //         $sortBy = $request->sort_by;
    //         $sortOrder = $request->sort_order ?? 'asc';

    //         // Whitelist allowed columns to prevent SQL injection
    //         $allowedSortColumns = ['first_name', 'last_name', 'dob', 'mobile', 'gender', 'registration_number', 'status'];

    //         if (in_array($sortBy, $allowedSortColumns)) {
    //             $query->orderBy($sortBy, $sortOrder);
    //         }
    //     }

    //     // ---------------------
    //     // PAGINATION
    //     // ---------------------
    //     $students = $query->paginate(15);

    //     // =========================================================
    //     // SECTION NAME RESOLUTION
    //     // =========================================================

    //     // Collect all course ids
    //     $courseIds = collect();

    //     foreach ($students as $student) {
    //         $academic = $student->academicTransportDetails;
    //         if ($academic && $academic->course_subtype_id) {
    //             $courseIds->push($academic->course_subtype_id);
    //         }
    //     }

    //     $courseIds = $courseIds->unique()->values();

    //     $sectionDataMap = [];

    //     if ($courseIds->isNotEmpty()) {
    //         $courseFeeStructures = DB::table('course_fee_structures as cfs')
    //             ->whereIn('cfs.product_id', $courseIds)
    //             ->where('cfs.institute_id', $merchantId)
    //             ->select('product_id', 'sections')
    //             ->get();

    //         foreach ($courseFeeStructures as $courseFee) {
    //             $sections = json_decode($courseFee->sections, true);

    //             if (is_array($sections)) {
    //                 foreach ($sections as $section) {
    //                     if (isset($section['id'], $section['name'])) {
    //                         $key = $courseFee->product_id . '_' . $section['id'];
    //                         $sectionDataMap[$key] = $section['name'];
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     // Attach section names
    //     foreach ($students as $student) {
    //         $academic = $student->academicTransportDetails;
    //         if (!$academic) {
    //             continue;
    //         }
    //         $lookupKey = $academic->course_subtype_id . '_' . $academic->section_id;
    //         $academic->section_name = $sectionDataMap[$lookupKey] ?? null;
    //     }

    //     // ---------------------
    //     // DATALIST VALUES - Also filter by active status
    //     // ---------------------
    //     $regNumbers = StudentParentDetails::select('registration_number')
    //         ->where('institute_id', $merchantId)
    //         ->where('status', 'active')
    //         ->whereHas('academicTransportDetails', function($q) {
    //             $q->where('status', 'active');
    //         })
    //         ->distinct()->get();

    //     $names = StudentParentDetails::select('first_name')
    //         ->where('institute_id', $merchantId)
    //         ->where('status', 'active')
    //         ->whereHas('academicTransportDetails', function($q) {
    //             $q->where('status', 'active');
    //         })
    //         ->distinct()->get();

    //     $genders = StudentParentDetails::select('gender')
    //         ->where('institute_id', $merchantId)
    //         ->where('status', 'active')
    //         ->whereHas('academicTransportDetails', function($q) {
    //             $q->where('status', 'active');
    //         })
    //         ->distinct()->get();

    //     $courses = StudentAcademicTransportDetails::where('institute_id', $merchantId)
    //         ->where('status', 'active') // Add this to filter active academic transport
    //         ->whereHas('student', function($q) use ($merchantId) {
    //             $q->where('institute_id', $merchantId)
    //             ->where('status', 'active');
    //         })
    //         ->select('course_type')
    //         ->distinct()
    //         ->pluck('course_type');

    //     // Get sections for each course type from course_fee_structures table
    //     $courseSections = [];

    //     foreach ($courses as $course) {
    //         $courseFeeStructure = DB::table('course_fee_structures')
    //             ->where('institute_id', $merchantId)
    //             ->where('sub_type', $course)
    //             ->first();

    //         if ($courseFeeStructure && $courseFeeStructure->sections) {
    //             $sections = json_decode($courseFeeStructure->sections, true);
    //             $courseSections[$course] = $sections;
    //         }
    //     }    

    //     $dobs = StudentParentDetails::select('dob')
    //         ->where('institute_id', $merchantId)
    //         ->where('status', 'active')
    //         ->whereHas('academicTransportDetails', function($q) {
    //             $q->where('status', 'active');
    //         })
    //         ->distinct()->get();

    //     // Get unique batches
    //     $batches = StudentAcademicTransportDetails::where('institute_id', $merchantId)
    //         ->where('status', 'active')
    //         ->whereHas('student', function($q) use ($merchantId) {
    //             $q->where('institute_id', $merchantId)
    //             ->where('status', 'active');
    //         })
    //         ->where('batch', '!=', null)
    //         ->distinct()
    //         ->pluck('batch')
    //         ->filter()
    //         ->values();

    //     // Get unique academic years
    //     $academicYears = StudentAcademicTransportDetails::where('institute_id', $merchantId)
    //         ->where('status', 'active')
    //         ->whereHas('student', function($q) use ($merchantId) {
    //             $q->where('institute_id', $merchantId)
    //             ->where('status', 'active');
    //         })
    //         ->where('academic_year', '!=', null)
    //         ->distinct()
    //         ->pluck('academic_year')
    //         ->filter()
    //         ->values();

    //     return view('instituteAdmin.StudentFiles.ViewStudentDetails', compact(
    //         'students',
    //         'fincapMerchants',
    //         'regNumbers',
    //         'names',
    //         'genders',
    //         'courses',
    //         'dobs',
    //         'courseSections',
    //         'generatedCertificates',
    //         'batches',
    //         'academicYears'
    //     ));
    // }

    public function getCurrentAcademicYear()
    {
        $today = Carbon::now();
        $startYear = $today->month >= 7 ? $today->year : $today->year - 1;

        return $startYear . '-' . ($startYear + 1);
    }

    public function getAcademicYearSortValue($academicYear)
    {
        $normalizedAcademicYear = trim((string) ($academicYear ?? ''));
        $currentAcademicYear = $this->getCurrentAcademicYear();

        return [
            $normalizedAcademicYear !== '' && $normalizedAcademicYear === $currentAcademicYear ? 0 : 1,
            $normalizedAcademicYear,
        ];
    }

    public function allStudentsData(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        $generatedCertificates = $this->getGeneratedCertificates($merchantId);
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
        // BASE QUERY - Show all students and filter from the UI
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
                'email',
                'student_status',
                'status',
                'suspend_status',
                'suspended_at',
                'suspension_reason'
            );

        // ---------------------
        // FILTERS
        // ---------------------
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

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

        // Updated section filter logic - works with both section_id and section_name
        if ($request->filled('section_id') || $request->filled('section_name')) {
            $sectionId = $request->filled('section_id') ? $request->section_id : null;
            $sectionName = $request->filled('section_name') ? $request->section_name : null;

            $query->whereHas('academicTransportDetails', function ($q) use ($sectionId, $sectionName, $merchantId) {
                if ($sectionId) {
                    // Filter by exact section ID
                    $q->where('section_id', $sectionId);
                } elseif ($sectionName) {
                    // If only section name is provided, we need to find matching section IDs
                    // Get all courses and their sections
                    $courseFeeStructures = DB::table('course_fee_structures')
                        ->where('institute_id', $merchantId)
                        ->get();

                    $matchingSectionIds = [];

                    foreach ($courseFeeStructures as $courseFee) {
                        $sections = json_decode($courseFee->sections, true);
                        if (is_array($sections)) {
                            foreach ($sections as $section) {
                                if (isset($section['name']) && str_contains(strtolower($section['name']), strtolower($sectionName))) {
                                    $matchingSectionIds[] = $section['id'];
                                }
                            }
                        }
                    }

                    if (!empty($matchingSectionIds)) {
                        $q->whereIn('section_id', array_unique($matchingSectionIds));
                    } else {
                        // No matching sections found, return no results
                        $q->where('section_id', 'NOT_EXISTING_VALUE');
                    }
                }
            });
        }

        if ($request->filled('dob')) {
            $query->whereDate('dob', $request->dob);
        }

        if ($request->filled('batch')) {
            $query->whereHas('academicTransportDetails', function ($q) use ($request) {
                $q->where('batch', 'LIKE', "%{$request->batch}%");
            });
        }

        if ($request->filled('academic_year')) {
            $query->whereHas('academicTransportDetails', function ($q) use ($request) {
                $q->where('academic_year', 'LIKE', "%{$request->academic_year}%");
            });
        }

        // ---------------------
        // SORTING (if implemented)
        // ---------------------
        if ($request->filled('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order ?? 'asc';

            // Whitelist allowed columns to prevent SQL injection
            $allowedSortColumns = ['first_name', 'last_name', 'dob', 'mobile', 'gender', 'registration_number', 'status'];

            if (in_array($sortBy, $allowedSortColumns)) {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        // ---------------------
        // SORTING AND PAGINATION
        // ---------------------
        $studentsCollection = $query->get();
        $preferredAcademicYear = $request->filled('academic_year')
            ? trim($request->academic_year)
            : $this->getCurrentAcademicYear();

        if ($request->filled('sort_by')) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->sort_order ?? 'asc';

            $studentsCollection = $studentsCollection->sortBy(function ($student) use ($sortBy) {
                $value = $student->{$sortBy} ?? null;
                if ($value instanceof \DateTimeInterface) {
                    return $value->format('Y-m-d');
                }

                return mb_strtolower((string) $value);
            }, SORT_NATURAL, $sortOrder === 'desc');
        } else {
            $studentsCollection = $studentsCollection->sortBy(function ($student) use ($preferredAcademicYear) {
                $academicYear = $student->academicTransportDetails?->academic_year;
                $sortValue = $this->getAcademicYearSortValue($academicYear);
                $priority = $sortValue[0];
                $yearValue = trim((string) ($academicYear ?? ''));

                return sprintf('%02d|%s|%s', $priority, $yearValue, $student->registration_number ?? '');
            });
        }

        $page = max(1, (int) $request->get('page', 1));
        $perPage = 15;
        $items = $studentsCollection->slice(($page - 1) * $perPage, $perPage)->values();

        $students = new LengthAwarePaginator($items, $studentsCollection->count(), $perPage, $page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

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
            $academic->section_name = $sectionDataMap[$lookupKey] ?? null;
        }

        // ---------------------
        // DATALIST VALUES - Show all records and let the UI filter them
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

        // Get sections for each course type from course_fee_structures table
        $courseSections = [];

        foreach ($courses as $course) {
            $courseFeeStructure = DB::table('course_fee_structures')
                ->where('institute_id', $merchantId)
                ->where('sub_type', $course)
                ->first();

            if ($courseFeeStructure && $courseFeeStructure->sections) {
                $sections = json_decode($courseFeeStructure->sections, true);
                $courseSections[$course] = $sections;
            }
        }

        $dobs = StudentParentDetails::select('dob')
            ->where('institute_id', $merchantId)
            ->distinct()->get();

        // Get unique batches
        $batches = StudentAcademicTransportDetails::where('institute_id', $merchantId)
            ->where('batch', '!=', null)
            ->distinct()
            ->pluck('batch')
            ->filter()
            ->values();

        // Get unique academic years
        $academicYears = StudentAcademicTransportDetails::where('institute_id', $merchantId)
            ->where('academic_year', '!=', null)
            ->distinct()
            ->pluck('academic_year')
            ->filter()
            ->values();

        return view('instituteAdmin.StudentFiles.ViewStudentDetails', compact(
            'students',
            'fincapMerchants',
            'regNumbers',
            'names',
            'genders',
            'courses',
            'dobs',
            'courseSections',
            'generatedCertificates',
            'batches',
            'academicYears'
        ));
    }

    // public function getStudentDetails($hash_id)
    // {
    //     $student = StudentParentDetails::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
    //     $address = StudentParentAddress::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
    //     $bank = StudentParentBankAccount::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
    //     $documents = StudentParentDocuments::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
    //     $extra = StudentAcademicTransportDetails::where('student_hash_id', $hash_id)->first() ?? new \stdClass();

    //     return view('instituteAdmin.StudentFiles.StudentViewPage', compact('student', 'address', 'bank', 'documents', 'extra'));
    // }


    //     public function getStudentDetails($hash_id)
    // {
    //     // Eager load fee relationships without broken relation queries
    //     $student = StudentParentDetails::with([
    //         'StudentCourseFeeStructure',
    //         'StudentCustomFeestructure',
    //         'StudentHostelFeeStructure',
    //         'StudentMiscellaneousFeeStructure',
    //         'StudentRegistrationFeeStructure',
    //         'StudentTransportFeeStructure',
    //     ])->where('student_hash_id', $hash_id)->first() ?? new \stdClass();

    //     $address = StudentParentAddress::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
    //     $bank = StudentParentBankAccount::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
    //     $documents = StudentParentDocuments::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
    //     $extra = StudentAcademicTransportDetails::where('student_hash_id', $hash_id)->first() ?? new \stdClass();

    //     // Fetch Institute details for branding & ID card
    //     $institute = null;
    //     if (isset($student->institute_id)) {
    //         $institute = InstituteBasicDetails::with('documents')
    //             ->where('fincap_merchant_id', $student->institute_id)
    //             ->first();
    //     }

    //     // Fetch Siblings (excluding the current student)
    //     $siblings = collect();
    //     $studentSiblingRecord = StudentSibling::where('student_hash_id', $hash_id)->first();
    //     if ($studentSiblingRecord && $studentSiblingRecord->sibling_pair_id) {
    //         $siblings = StudentSibling::where('sibling_pair_id', $studentSiblingRecord->sibling_pair_id)
    //             ->where('student_hash_id', '!=', $hash_id)
    //             ->with('studentDetails')
    //             ->get();
    //     }

    //     // Fetch fee structure collections
    //     $feeStructures = [
    //         'course' => $student->StudentCourseFeeStructure ?? collect(),
    //         'hostel' => $student->StudentHostelFeeStructure ?? collect(),
    //         'transport' => $student->StudentTransportFeeStructure ?? collect(),
    //         'registration' => $student->StudentRegistrationFeeStructure ?? collect(),
    //         'misc' => $student->StudentMiscellaneousFeeStructure ?? collect(),
    //         'custom' => $student->StudentCustomFeestructure ?? collect(),
    //     ];

    //     // Map fee types to their corresponding columns
    //     $feeTypeColumns = [
    //         'course' => [
    //             'amount' => 'course_fee',
    //             'paid' => 'course_pay_fee_amount',
    //             'total' => 'course_total_fee',
    //         ],
    //         'hostel' => [
    //             'amount' => 'hostel_fee',
    //             'paid' => 'hostel_pay_fee_amount',   // adjust if column name differs
    //             'total' => 'hostel_total_fee',
    //         ],
    //         'transport' => [
    //             'amount' => 'transport_fee',
    //             'paid' => 'transport_pay_fee_amount',
    //             'total' => 'transport_total_fee',
    //         ],
    //         'registration' => [
    //             'amount' => 'registration_fee',
    //             'paid' => 'registration_pay_fee_amount',
    //             'total' => 'registration_total_fee',
    //         ],
    //         'misc' => [
    //             'amount' => 'miscellaneous_fee',
    //             'paid' => 'miscellaneous_pay_fee_amount',
    //             'total' => 'miscellaneous_total_fee',
    //         ],
    //         'custom' => [
    //             'amount' => 'custom_fee_value',
    //             'paid' => 'custom_pay_fee_amount',
    //             'total' => 'custom_total_fee',
    //         ],
    //     ];

    //     $feeTypeLabels = [
    //         'course' => 'Course Fee',
    //         'hostel' => 'Hostel Fee',
    //         'transport' => 'Transport Fee',
    //         'registration' => 'Registration Fee',
    //         'misc' => 'Miscellaneous Fee',
    //         'custom' => 'Custom Fees',
    //     ];

    //     $totalAssigned = 0;
    //     $totalPaid = 0;
    //     $totalConcession = 0;
    //     $totalFine = 0;
    //     $feeGroups = [];

    //     foreach ($feeStructures as $type => $items) {
    //         if ($items->isEmpty()) {
    //             continue;
    //         }

    //         $colMapping = $feeTypeColumns[$type] ?? null;
    //         if (!$colMapping) {
    //             continue;
    //         }

    //         $groupTotal = 0;
    //         $groupPaid = 0;
    //         $groupConcession = 0;
    //         $groupFine = 0;
    //         $groupDue = 0;
    //         $unpaidCount = 0;

    //         foreach ($items as $item) {
    //             // Base amount from the mapped column (fallback chain)
    //             $baseAmount = floatval(
    //                 $item->{$colMapping['amount']}
    //                 ?? $item->custom_fee_value
    //                 ?? $item->course_fee
    //                 ?? $item->hostel_fee
    //                 ?? $item->transport_fee
    //                 ?? $item->registration_fee
    //                 ?? $item->miscellaneous_fee
    //                 ?? $item->amount
    //                 ?? 0
    //             );

    //             // Discount and late fee (actual amounts)
    //             $discount = floatval($item->discount_amount ?? 0);
    //             $fine = floatval($item->late_fee_amount ?? 0);

    //             // Total payable after adjustments:
    //             // Prefer final_payable_fee if set and > 0, else calculate
    //             $totalPayable = floatval($item->final_payable_fee ?? 0);
    //             if ($totalPayable <= 0) {
    //                 $totalPayable = max(0, $baseAmount - $discount + $fine);
    //             }

    //             // Paid amount from the mapped column
    //             $paidAmount = floatval($item->{$colMapping['paid']} ?? 0);

    //             // Fallback: if payment_status is 'paid' and paid amount is 0, assume fully paid
    //             $paymentStatus = strtolower(trim($item->payment_status ?? $item->status ?? 'unpaid'));
    //             if ($paymentStatus === 'paid') {
    //                 if ($paidAmount <= 0 && $totalPayable > 0) {
    //                     $paidAmount = $totalPayable;
    //                 } elseif ($paidAmount < $totalPayable) {
    //                     // Ensure no negative due for paid records
    //                     $paidAmount = $totalPayable;
    //                 }
    //             }

    //             // Compute due
    //             $due = max(0, $totalPayable - $paidAmount);

    //             // Determine display status
    //             if ($due <= 0) {
    //                 $statusLabel = 'Paid';
    //                 $statusClass = 'success';
    //             } elseif ($paidAmount > 0) {
    //                 $statusLabel = 'Partial';
    //                 $statusClass = 'warning';
    //             } else {
    //                 $statusLabel = 'Unpaid';
    //                 $statusClass = 'danger';
    //             }

    //             // Attach computed values to the item for Blade
    //             $item->computed_amount = $totalPayable;
    //             $item->computed_paid = $paidAmount;
    //             $item->computed_due = $due;
    //             $item->computed_status_label = $statusLabel;
    //             $item->computed_status_class = $statusClass;
    //             $item->computed_due_date = $item->due_date ?? 'N/A';

    //             // Aggregate
    //             $groupTotal += $totalPayable;
    //             $groupPaid += $paidAmount;
    //             $groupConcession += $discount;
    //             $groupFine += $fine;
    //             $groupDue += $due;
    //             if ($due > 0) {
    //                 $unpaidCount++;
    //             }

    //             $totalAssigned += $totalPayable;
    //             $totalPaid += $paidAmount;
    //             $totalConcession += $discount;
    //             $totalFine += $fine;
    //         }

    //         $feeGroups[$type] = [
    //             'label' => $feeTypeLabels[$type] ?? $type,
    //             'items' => $items,
    //             'total_amount' => $groupTotal,
    //             'total_paid' => $groupPaid,
    //             'total_concession' => $groupConcession,
    //             'total_fine' => $groupFine,
    //             'total_due' => $groupDue,
    //             'unpaid_count' => $unpaidCount,
    //         ];
    //     }

    //     $balance = max(0, $totalAssigned - $totalPaid);
    //     $pctComplete = $totalAssigned > 0 ? (int) round(($totalPaid / $totalAssigned) * 100) : 0;
    //     $deg = $totalAssigned > 0 ? (int) round(($totalPaid / $totalAssigned) * 360) : 0;
    //     $pct = $pctComplete;

    //     // Fetch Student Attendance (Lecture-wise aggregation)
    //     $attendanceRecords = DB::table('student_attendance')
    //         ->where('student_hash_id', $hash_id)
    //         ->get();

    //     $groupedByDate = [];
    //     foreach ($attendanceRecords as $att) {
    //         if (isset($att->date)) {
    //             $dKey = Carbon::parse($att->date)->format('Y-m-d');
    //             if (!isset($groupedByDate[$dKey])) {
    //                 $groupedByDate[$dKey] = [];
    //             }
    //             $groupedByDate[$dKey][] = strtolower($att->status ?? '');
    //         }
    //     }

    //     $attendanceByDate = [];
    //     $attPresentCount = 0;
    //     $attAbsentCount = 0;
    //     $attLateCount = 0;
    //     $attHalfDayCount = 0;

    //     foreach ($groupedByDate as $dKey => $statuses) {
    //         // Rule: If present in at least 1 lecture on that day, mark day as present
    //         if (in_array('present', $statuses) || in_array('late', $statuses) || in_array('half_day', $statuses) || in_array('halfday', $statuses)) {
    //             $attendanceByDate[$dKey] = 'present';
    //             $attPresentCount++;
    //         } else {
    //             $attendanceByDate[$dKey] = 'absent';
    //             $attAbsentCount++;
    //         }
    //     }

    //     $attTotalMarked = count($attendanceByDate);
    //     $attPct = $attTotalMarked > 0 ? (int) round(($attPresentCount / $attTotalMarked) * 100) : 0;
    //     $attDeg = (int) round(($attPct / 100) * 360);

    //     // Fetch Exam Marks
    //     $examMarks = collect();
    //     if (class_exists('\App\Models\StudentExamMarks')) {
    //         $examMarks = \App\Models\StudentExamMarks::with(['exam', 'subject'])
    //             ->where('student_hash_id', $hash_id)
    //             ->get();
    //     }

    //     // Fetch Upcoming Exams
    //     $upcomingExams = collect();
    //     if (class_exists('\App\Models\ExamStructureOfflineExam')) {
    //         try {
    //             $upcomingExams = \App\Models\ExamStructureOfflineExam::with(['subject', 'examNameDetail'])
    //                 ->where('exam_date', '>=', now()->toDateString())
    //                 ->orderBy('exam_date', 'asc')
    //                 ->take(6)
    //                 ->get();
    //         } catch (\Exception $e) {
    //             // ignore
    //         }
    //     }

    //     // Fetch Transport details (grouped by unique transport + stop combination)
    //     $transportRoutesList = collect();
    //     $transportStructures = $student->StudentTransportFeeStructure ?? collect();

    //     if ($transportStructures->isNotEmpty()) {
    //         // Group by both fee_type_id and transport_stop_id to differentiate assignments
    //         $grouped = $transportStructures->groupBy(function ($item) {
    //             $feeTypeId = $item->fee_type_id ?? 'unknown';
    //             $stopId = $item->transport_stop_id ?? 'unknown';
    //             return $feeTypeId . '|' . $stopId;
    //         });

    //         foreach ($grouped as $groupKey => $items) {
    //             $first = $items->first();
    //             $feeTypeId = $first->fee_type_id ?? null;
    //             $stopId = $first->transport_stop_id ?? null;
    //             $stopName = $first->transport_stop_name ?? 'N/A';
    //             $fare = floatval($first->transport_fee ?? 0);

    //             $routeName = null;
    //             $vehicleNumber = null;
    //             $driverName = null;
    //             $driverContact = null;
    //             $routeType = null;
    //             $busNumber = null;

    //             // Direct match: fee_type_id in student_transport_fee = transport_reference_id in transport_details
    //             if ($feeTypeId && $feeTypeId !== 'unknown' && class_exists('\App\Models\TransportDetails')) {
    //                 try {
    //                     $transportDetail = \App\Models\TransportDetails::where('transport_reference_id', $feeTypeId)
    //                         ->where('institute_id', $student->institute_id)
    //                         ->first();

    //                     if ($transportDetail) {
    //                         $routeName = $transportDetail->route_name ?? null;
    //                         $vehicleNumber = $transportDetail->vehicle_number ?? null;
    //                         $busNumber = $transportDetail->bus_number ?? null;
    //                         $driverName = $transportDetail->driver_name ?? null;
    //                         $driverContact = $transportDetail->driver_contact ?? null;
    //                         $routeType = $transportDetail->route_type ?? null;

    //                         // If stop_id matches a specific stop in the route, get the stop name from route
    //                         if ($stopId && $stopId !== 'route_default' && isset($transportDetail->stops)) {
    //                             $stops = json_decode($transportDetail->stops, true);
    //                             if (is_array($stops)) {
    //                                 foreach ($stops as $stop) {
    //                                     if (isset($stop['id']) && $stop['id'] === $stopId) {
    //                                         $stopName = $stop['name'] ?? $stopName;
    //                                         break;
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 } catch (\Exception $e) {
    //                     \Log::error('Transport details fetch error: ' . $e->getMessage());
    //                 }
    //             }

    //             // Handle route_default case
    //             if ($stopId === 'route_default' || $stopId === null) {
    //                 $stopName = 'Whole Route';
    //             }

    //             // Fallback to extra fields if still not found
    //             if (!$vehicleNumber && !$busNumber) {
    //                 $vehicleNumber = $extra->vehicle_number ?? null;
    //             }
    //             if (!$driverName) {
    //                 $driverName = $extra->driver_name ?? null;
    //             }
    //             if (!$routeName) {
    //                 $routeName = $extra->morning_route_name ?? 'N/A';
    //             }

    //             // Use bus_number as fallback for vehicle display
    //             $displayVehicle = $vehicleNumber ?: ($busNumber ?: 'N/A');
    //             $displayDriver = $driverName ?: 'N/A';
    //             $displayRoute = $routeName ?: 'N/A';
    //             $displayFare = $fare > 0 ? $fare : ($extra->transport_fare ?? null);

    //             $transportRoutesList->push([
    //                 'route' => $displayRoute,
    //                 'stop' => $stopName,
    //                 'vehicle' => $displayVehicle,
    //                 'driver' => $displayDriver,
    //                 'driver_contact' => $driverContact,
    //                 'fare' => $displayFare,
    //                 'route_type' => $routeType,
    //             ]);
    //         }
    //     }

    //     // If no transport structures found, try to get from extra fields
    //     if ($transportRoutesList->isEmpty()) {
    //         $transportRoutesList->push([
    //             'route' => $extra->morning_route_name ?? 'N/A',
    //             'stop' => $extra->morning_stop ?? 'N/A',
    //             'vehicle' => $extra->vehicle_number ?? 'N/A',
    //             'driver' => $extra->driver_name ?? 'N/A',
    //             'fare' => $extra->transport_fare ?? null,
    //         ]);
    //     }

    //     $transportData = $transportRoutesList->first();

    //     // Fetch Hostel details properly from HostelFee master & StudentHostelFeeStructure & extra
    //     $hostelData = [
    //         'hostel' => $extra->hostel_name ?? null,
    //         'room_no' => $extra->room_no ?? null,
    //         'room_type' => $extra->room_type ?? null,
    //         'cost' => $extra->hostel_cost ?? null,
    //     ];
    //     if (isset($student->StudentHostelFeeStructure) && $student->StudentHostelFeeStructure->isNotEmpty()) {
    //         $hStruct = $student->StudentHostelFeeStructure->first();
    //         if ($hStruct) {
    //             $refId = $hStruct->hostel_reference_id ?? $hStruct->fee_type_id ?? $hStruct->fee_type ?? null;
    //             if ($refId && class_exists('\App\Models\HostelFee')) {
    //                 try {
    //                     $hObj = \App\Models\HostelFee::where('hostel_fee_reference_id', $refId)
    //                         ->orWhere('id', $refId)
    //                         ->orWhere('hostel_name', $refId)
    //                         ->first();
    //                     if ($hObj) {
    //                         $hostelData['hostel'] = $hostelData['hostel'] ?? $hObj->hostel_name ?? null;
    //                         $hostelData['room_type'] = $hostelData['room_type'] ?? $hObj->room_type ?? null;
    //                         $hostelData['cost'] = $hostelData['cost'] ?? $hObj->monthly_fee ?? $hObj->total_fee ?? null;
    //                     }
    //                 } catch (\Exception $e) {
    //                     // ignore schema mismatches
    //                 }
    //             }
    //             $hostelData['cost'] = $hostelData['cost'] ?? $hStruct->hostel_fee ?? $hStruct->hostel_total_fee ?? null;
    //             $hostelData['room_no'] = $hostelData['room_no'] ?? $hStruct->room_no ?? null;
    //         }
    //     }

    //     // Fetch ID Card Settings & Data
    //     $instituteId = $student->institute_id ?? null;
    //     $idCardSettings = [];
    //     if ($instituteId && class_exists('\App\Models\IDCardSetting')) {
    //         $idCardSettings = \App\Models\IDCardSetting::where('institute_id', $instituteId)
    //             ->where('type', 'student_card')
    //             ->pluck('value', 'key')
    //             ->toArray();
    //     }

    //     $defaultCardSettings = [
    //         'card_title' => 'STUDENT IDENTIFICATION CARD',
    //         'signature_text' => "Principal's Signature",
    //         'signature_image' => null,
    //         'header_bg_color' => '#3a0ca3',
    //         'header_font_color' => '#ffffff',
    //         'footer_bg_color' => '#3a0ca3',
    //         'footer_font_color' => '#ffffff',
    //         'header_banner' => null
    //     ];
    //     $idCardSettings = array_merge($defaultCardSettings, $idCardSettings);

    //     $instituteLogo = null;
    //     $instituteAddress = 'N/A';
    //     if ($institute) {
    //         if (isset($institute->documents) && $institute->documents->count() > 0) {
    //             $instituteLogo = $institute->documents->pluck('logo_path')->first();
    //         }
    //         $instituteAddress = collect([
    //             $institute->address_line1 ?? null,
    //             $institute->address_line2 ?? null,
    //             $institute->city ?? null,
    //             $institute->state ?? null,
    //             $institute->pincode ?? null,
    //         ])->filter()->implode(', ');
    //     }

    //     $idCardData = [
    //         'institute_name' => $institute->name ?? 'Institute Name',
    //         'institute_logo' => $instituteLogo,
    //         'institute_address' => $instituteAddress ?: 'N/A',
    //         'full_name' => trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? '')),
    //         'registration_number' => $student->registration_number ?? 'N/A',
    //         'blood_group' => $student->blood_group ?? 'N/A',
    //         'father_name' => trim(($student->father_first_name ?? '') . ' ' . ($student->father_middle_name ?? '') . ' ' . ($student->father_last_name ?? '')),
    //         'mother_name' => trim(($student->mother_first_name ?? '') . ' ' . ($student->mother_middle_name ?? '') . ' ' . ($student->mother_last_name ?? '')),
    //         'course' => $extra->course_subtype ?? 'N/A',
    //         'department' => $extra->department ?? 'N/A',
    //         'academic_year' => $extra->academic_year ?? 'N/A',
    //         'section' => $extra->section_id ?? 'N/A',
    //         'roll_no' => $extra->roll_no ?? 'N/A',
    //         'dob' => $student->dob ?? 'N/A',
    //         'mobile' => $student->mobile ?? $student->father_phone ?? $student->mother_phone ?? 'N/A',
    //         'email' => $student->email ?? 'N/A',
    //         'photo' => $documents->student_photo ?? null,
    //         'address' => trim(($address->student_perm_address_line1 ?? '') . ' ' . ($address->student_perm_city ?? '') . ' ' . ($address->student_perm_state ?? '')),
    //         'settings' => $idCardSettings
    //     ];

    //     return view('instituteAdmin.StudentFiles.StudentViewPage', compact(
    //         'student',
    //         'address',
    //         'bank',
    //         'documents',
    //         'extra',
    //         'siblings',
    //         'institute',
    //         'feeStructures',
    //         'feeGroups',
    //         'totalAssigned',
    //         'totalPaid',
    //         'totalConcession',
    //         'totalFine',
    //         'balance',
    //         'pctComplete',
    //         'pct',
    //         'deg',
    //         'attPresentCount',
    //         'attAbsentCount',
    //         'attLateCount',
    //         'attHalfDayCount',
    //         'attTotalMarked',
    //         'attPct',
    //         'attDeg',
    //         'attendanceByDate',
    //         'examMarks',
    //         'upcomingExams',
    //         'transportData',
    //         'transportRoutesList',
    //         'hostelData',
    //         'idCardData',
    //         'idCardSettings'
    //     ));
    // }


    public function getStudentDetails($hash_id, Request $request)
    {
        // Eager load fee relationships without broken relation queries
        $student = StudentParentDetails::with([
            'StudentCourseFeeStructure',
            'StudentCustomFeestructure',
            'StudentHostelFeeStructure',
            'StudentMiscellaneousFeeStructure',
            'StudentRegistrationFeeStructure',
            'StudentTransportFeeStructure',
        ])->where('student_hash_id', $hash_id)->first() ?? new \stdClass();

        $address = StudentParentAddress::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
        $bank = StudentParentBankAccount::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
        $documents = StudentParentDocuments::where('student_hash_id', $hash_id)->first() ?? new \stdClass();
        $extra = StudentAcademicTransportDetails::where('student_hash_id', $hash_id)->first() ?? new \stdClass();

        // Fetch Institute details for branding & ID card
        $institute = null;
        if (isset($student->institute_id)) {
            $institute = InstituteBasicDetails::with('documents')
                ->where('fincap_merchant_id', $student->institute_id)
                ->first();
        }

        // Fetch Siblings (excluding the current student)
        $siblings = collect();
        $studentSiblingRecord = StudentSibling::where('student_hash_id', $hash_id)->first();
        if ($studentSiblingRecord && $studentSiblingRecord->sibling_pair_id) {
            $siblings = StudentSibling::where('sibling_pair_id', $studentSiblingRecord->sibling_pair_id)
                ->where('student_hash_id', '!=', $hash_id)
                ->with('studentDetails')
                ->get();
        }

        // Fetch fee structure collections
        $feeStructures = [
            'course' => $student->StudentCourseFeeStructure ?? collect(),
            'hostel' => $student->StudentHostelFeeStructure ?? collect(),
            'transport' => $student->StudentTransportFeeStructure ?? collect(),
            'registration' => $student->StudentRegistrationFeeStructure ?? collect(),
            'misc' => $student->StudentMiscellaneousFeeStructure ?? collect(),
            'custom' => $student->StudentCustomFeestructure ?? collect(),
        ];

        // Map fee types to their corresponding columns
        $feeTypeColumns = [
            'course' => [
                'amount' => 'course_fee',
                'paid' => 'course_pay_fee_amount',
                'total' => 'course_total_fee',
            ],
            'hostel' => [
                'amount' => 'hostel_fee',
                'paid' => 'hostel_pay_fee_amount',
                'total' => 'hostel_total_fee',
            ],
            'transport' => [
                'amount' => 'transport_fee',
                'paid' => 'transport_pay_fee_amount',
                'total' => 'transport_total_fee',
            ],
            'registration' => [
                'amount' => 'registration_fee',
                'paid' => 'registration_pay_fee_amount',
                'total' => 'registration_total_fee',
            ],
            'misc' => [
                'amount' => 'miscellaneous_fee',
                'paid' => 'miscellaneous_pay_fee_amount',
                'total' => 'miscellaneous_total_fee',
            ],
            'custom' => [
                'amount' => 'custom_fee_value',
                'paid' => 'custom_pay_fee_amount',
                'total' => 'custom_total_fee',
            ],
        ];

        $feeTypeLabels = [
            'course' => 'Course Fee',
            'hostel' => 'Hostel Fee',
            'transport' => 'Transport Fee',
            'registration' => 'Registration Fee',
            'misc' => 'Miscellaneous Fee',
            'custom' => 'Custom Fees',
        ];

        $totalAssigned = 0;
        $totalPaid = 0;
        $totalConcession = 0;
        $totalFine = 0;
        $feeGroups = [];

        foreach ($feeStructures as $type => $items) {
            if ($items->isEmpty()) {
                continue;
            }

            $colMapping = $feeTypeColumns[$type] ?? null;
            if (!$colMapping) {
                continue;
            }

            $groupTotal = 0;
            $groupPaid = 0;
            $groupConcession = 0;
            $groupFine = 0;
            $groupDue = 0;
            $unpaidCount = 0;

            foreach ($items as $item) {
                $baseAmount = floatval(
                    $item->{$colMapping['amount']}
                    ?? $item->custom_fee_value
                    ?? $item->course_fee
                    ?? $item->hostel_fee
                    ?? $item->transport_fee
                    ?? $item->registration_fee
                    ?? $item->miscellaneous_fee
                    ?? $item->amount
                    ?? 0
                );

                $discount = floatval($item->discount_amount ?? 0);
                $fine = floatval($item->late_fee_amount ?? 0);

                $totalPayable = floatval($item->final_payable_fee ?? 0);
                if ($totalPayable <= 0) {
                    $totalPayable = max(0, $baseAmount - $discount + $fine);
                }

                $paidAmount = floatval($item->{$colMapping['paid']} ?? 0);
                $paymentStatus = strtolower(trim($item->payment_status ?? $item->status ?? 'unpaid'));
                if ($paymentStatus === 'paid') {
                    if ($paidAmount <= 0 && $totalPayable > 0) {
                        $paidAmount = $totalPayable;
                    } elseif ($paidAmount < $totalPayable) {
                        $paidAmount = $totalPayable;
                    }
                }

                $due = max(0, $totalPayable - $paidAmount);

                if ($due <= 0) {
                    $statusLabel = 'Paid';
                    $statusClass = 'success';
                } elseif ($paidAmount > 0) {
                    $statusLabel = 'Partial';
                    $statusClass = 'warning';
                } else {
                    $statusLabel = 'Unpaid';
                    $statusClass = 'danger';
                }

                $item->computed_amount = $totalPayable;
                $item->computed_paid = $paidAmount;
                $item->computed_due = $due;
                $item->computed_status_label = $statusLabel;
                $item->computed_status_class = $statusClass;
                $item->computed_due_date = $item->due_date ?? 'N/A';

                $groupTotal += $totalPayable;
                $groupPaid += $paidAmount;
                $groupConcession += $discount;
                $groupFine += $fine;
                $groupDue += $due;
                if ($due > 0) {
                    $unpaidCount++;
                }

                $totalAssigned += $totalPayable;
                $totalPaid += $paidAmount;
                $totalConcession += $discount;
                $totalFine += $fine;
            }

            $feeGroups[$type] = [
                'label' => $feeTypeLabels[$type] ?? $type,
                'items' => $items,
                'total_amount' => $groupTotal,
                'total_paid' => $groupPaid,
                'total_concession' => $groupConcession,
                'total_fine' => $groupFine,
                'total_due' => $groupDue,
                'unpaid_count' => $unpaidCount,
            ];
        }

        $balance = max(0, $totalAssigned - $totalPaid);
        $pctComplete = $totalAssigned > 0 ? (int) round(($totalPaid / $totalAssigned) * 100) : 0;
        $deg = $totalAssigned > 0 ? (int) round(($totalPaid / $totalAssigned) * 360) : 0;
        $pct = $pctComplete;

        // ============================================================
        // ====== STUDENT PERFORMANCE DATA WITH FILTERS ======
        // ============================================================

        // Get filter parameters from request
        $perfMonth = (int) $request->query('perf_month', now()->month);
        $perfYear = (int) $request->query('perf_year', now()->year);
        $perfSubjectFilter = $request->query('perf_subject');

        // Validate month and year
        $perfMonth = max(1, min(12, (int) $perfMonth));
        $perfYear = max(2000, min(2099, (int) $perfYear));

        $performanceData = [];
        $performanceSubjects = collect();
        $performanceTotalPlans = 0;
        $performanceTotalTopics = 0;
        $performanceCoveredTopics = 0;
        $performanceTotalMaterials = 0;
        $performanceViewedMaterials = 0;
        $performanceTotalPresent = 0;
        $performanceTotalAttendance = 0;

        try {
            // Get student's course type
            $courseType = optional($student ? $student->academicTransportDetails : null)->course_type;

            if ($student && $courseType) {
                // Get student hash id
                $studentHashId = $student->student_hash_id ?? $hash_id;

                // Fetch lesson plans
                $plans = LessonPlan::with('topics')
                    ->where(function ($query) use ($courseType) {
                        $query->where('course_type', $courseType)->orWhereNull('course_type');
                    })
                    ->get();

                $performanceAssignments = DB::table('assign_subjects_to_employee')
                    ->where('department_id', $extra->department_id ?? null)
                    ->where('course_detail_id', $extra->course_subtype_id ?? null)
                    ->whereRaw('LOWER(status) = ?', ['active'])
                    ->select('subject_id', 'course_detail_id', 'employee_id')
                    ->get()
                    ->keyBy('subject_id');

                // Get topic IDs
                $topicIds = $plans->flatMap->topics->pluck('id')->all();

                // Fetch views for this student
                $views = DB::table('lesson_topic_media_views')
                    ->where('student_hash_id', $studentHashId)
                    ->whereIn('lesson_topic_id', $topicIds ?: [0])
                    ->get()
                    ->groupBy(function ($view) {
                        return $view->lesson_topic_id . ':' . $view->media_type . ':' . $view->media_url;
                    });

                // Fetch attendance
                $attendance = DB::table('student_attendance as sa')
                    ->join('assign_subjects_to_employee as ase', 'sa.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
                    ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
                    ->where('sa.student_hash_id', $studentHashId)
                    ->where('pd.course_type', $courseType)
                    ->select('ase.subject_id', 'sa.date', 'sa.status')
                    ->get()
                    ->groupBy('subject_id');

                // Fetch lectures
                $lectures = DB::table('employee_subject_lectures as esl')
                    ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
                    ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
                    ->where('pd.course_type', $courseType)
                    ->select('ase.subject_id', 'esl.*')
                    ->get()
                    ->groupBy('subject_id');

                // Build subjects list
                $performanceSubjects = $plans->groupBy('subject_id')->keys()->mapWithKeys(function ($subjectId) {
                    $subject = SubjectsCoursewise::where('subject_id', $subjectId)->first();
                    return [$subjectId => $subject->subject_name ?? 'General'];
                });

                // Build performance data with filters
                $performanceData = $plans->groupBy('subject_id')->map(function ($subjectPlans, $subjectId) use ($views, $attendance, $lectures, $performanceAssignments, $studentHashId, $extra, $perfYear, $perfMonth) {
                    // Get topics for this subject within the selected month/year
                    $topics = $subjectPlans->flatMap->topics->filter(function ($topic) use ($perfMonth, $perfYear) {
                        return $topic->scheduled_date
                            && Carbon::parse($topic->scheduled_date)->year === $perfYear
                            && Carbon::parse($topic->scheduled_date)->month === $perfMonth;
                    });

                    // Build materials list from filtered topics with proper key format
                    $materials = collect();
                    foreach ($topics as $topic) {
                        // Add video materials
                        $videoUrls = array_values(array_filter(array_map('trim', explode(',', (string) $topic->video_url))));
                        foreach ($videoUrls as $videoUrl) {
                            if ($videoUrl) {
                                $materials->push([
                                    'topic_id' => $topic->id,
                                    'type' => 'video',
                                    'url' => $videoUrl,
                                    'key' => $topic->id . ':video:' . $videoUrl
                                ]);
                            }
                        }

                        // Add file materials
                        $files = is_array($topic->files) ? $topic->files : [];
                        foreach ($files as $file) {
                            $url = is_array($file) ? ($file['url'] ?? $file['path'] ?? '') : $file;
                            if ($url) {
                                $materials->push([
                                    'topic_id' => $topic->id,
                                    'type' => 'file',
                                    'url' => $url,
                                    'key' => $topic->id . ':file:' . $url
                                ]);
                            }
                        }
                    }

                    // Count viewed materials using the key field
                    $viewedMaterials = $materials->filter(function ($material) use ($views) {
                        return $views->has($material['key']);
                    })->count();

                    // Get attendance for this subject
                    $subjectAttendance = $attendance->get($subjectId, collect());
                    $attendanceByDate = $subjectAttendance->keyBy('date');

                    // Get lectures for this subject
                    $subjectLectures = $lectures->get($subjectId, collect());

                    // Calculate scheduled dates for the month
                    $scheduledDates = collect();
                    $date = Carbon::create($perfYear, $perfMonth, 1);
                    while ($date->month === $perfMonth) {
                        if ($this->isLectureScheduled($date, $subjectLectures)) {
                            $scheduledDates->push($date->format('Y-m-d'));
                        }
                        $date->addDay();
                    }

                    // Count present attendance
                    $present = $scheduledDates->filter(function ($dateStr) use ($attendanceByDate) {
                        $record = $attendanceByDate->get($dateStr);
                        return $record && strtolower($record->status) === 'present';
                    })->count();

                    $attendanceTotal = $scheduledDates->count();
                    $subject = SubjectsCoursewise::where('subject_id', $subjectId)->first();
                    $assignment = $performanceAssignments->get($subjectId);

                    return [
                        'subject_id' => $subjectId,
                        'subject' => $subject->subject_name ?? 'General',
                        'view_url' => $assignment ? route('lesson-planner.studentDetail', [
                            'student_id' => $studentHashId,
                            'department' => $extra->department_id,
                            'course' => $assignment->course_detail_id . ':' . $subjectId,
                            'employee_id' => $assignment->employee_id,
                        ]) : null,
                        'plans' => $subjectPlans->count(),
                        'topics' => $topics->count(),
                        'covered_topics' => $topics->where('covered', true)->count(),
                        'materials' => $materials->count(),
                        'viewed_materials' => $materials->count() > 0 ? min($viewedMaterials, $materials->count()) : 0,
                        'present' => $present,
                        'attendance_total' => $attendanceTotal,
                    ];
                })->filter(function ($item) {
                    // Only include subjects with data
                    return $item['topics'] > 0 || $item['attendance_total'] > 0 || $item['materials'] > 0;
                })->when($perfSubjectFilter, function ($data) use ($perfSubjectFilter) {
                    return $data->filter(fn($item) => (string) $item['subject_id'] === (string) $perfSubjectFilter);
                })->values()->all();

                // Calculate totals
                $performanceTotalPlans = collect($performanceData)->sum('plans');
                $performanceTotalTopics = collect($performanceData)->sum('topics');
                $performanceCoveredTopics = collect($performanceData)->sum('covered_topics');
                $performanceTotalMaterials = collect($performanceData)->sum('materials');
                $performanceViewedMaterials = collect($performanceData)->sum('viewed_materials');
                $performanceTotalPresent = collect($performanceData)->sum('present');
                $performanceTotalAttendance = collect($performanceData)->sum('attendance_total');
            }
        } catch (\Exception $e) {
            // Log error but don't break the page
            \Log::error('Performance data fetch error: ' . $e->getMessage());
        }

        // ============================================================
        // ====== END PERFORMANCE DATA ======
        // ============================================================

        // Fetch Student Attendance (Lecture-wise aggregation)
        $attendanceRecords = DB::table('student_attendance')
            ->where('student_hash_id', $hash_id)
            ->get();

        $groupedByDate = [];
        foreach ($attendanceRecords as $att) {
            if (isset($att->date)) {
                $dKey = Carbon::parse($att->date)->format('Y-m-d');
                if (!isset($groupedByDate[$dKey])) {
                    $groupedByDate[$dKey] = [];
                }
                $groupedByDate[$dKey][] = strtolower($att->status ?? '');
            }
        }

        $attendanceByDate = [];
        $attPresentCount = 0;
        $attAbsentCount = 0;
        $attLateCount = 0;
        $attHalfDayCount = 0;

        foreach ($groupedByDate as $dKey => $statuses) {
            if (in_array('present', $statuses) || in_array('late', $statuses) || in_array('half_day', $statuses) || in_array('halfday', $statuses)) {
                $attendanceByDate[$dKey] = 'present';
                $attPresentCount++;
            } else {
                $attendanceByDate[$dKey] = 'absent';
                $attAbsentCount++;
            }
        }

        $attTotalMarked = count($attendanceByDate);
        $attPct = $attTotalMarked > 0 ? (int) round(($attPresentCount / $attTotalMarked) * 100) : 0;
        $attDeg = (int) round(($attPct / 100) * 360);

        // Fetch Exam Marks
        $examMarks = collect();
        if (class_exists('\App\Models\StudentExamMarks')) {
            $examMarks = \App\Models\StudentExamMarks::with(['exam', 'subject'])
                ->where('student_hash_id', $hash_id)
                ->get();
        }

        // Fetch Upcoming Exams
        $upcomingExams = collect();
        if (class_exists('\App\Models\ExamStructureOfflineExam')) {
            try {
                $upcomingExams = \App\Models\ExamStructureOfflineExam::with(['subject', 'examNameDetail'])
                    ->where('exam_date', '>=', now()->toDateString())
                    ->orderBy('exam_date', 'asc')
                    ->take(6)
                    ->get();
            } catch (\Exception $e) {
                // ignore
            }
        }

        // Fetch Transport details (grouped by unique transport + stop combination)
        $transportRoutesList = collect();
        $transportStructures = $student->StudentTransportFeeStructure ?? collect();

        if ($transportStructures->isNotEmpty()) {
            $grouped = $transportStructures->groupBy(function ($item) {
                $feeTypeId = $item->fee_type_id ?? 'unknown';
                $stopId = $item->transport_stop_id ?? 'unknown';
                return $feeTypeId . '|' . $stopId;
            });

            foreach ($grouped as $groupKey => $items) {
                $first = $items->first();
                $feeTypeId = $first->fee_type_id ?? null;
                $stopId = $first->transport_stop_id ?? null;
                $stopName = $first->transport_stop_name ?? 'N/A';
                $fare = floatval($first->transport_fee ?? 0);

                $routeName = null;
                $vehicleNumber = null;
                $driverName = null;
                $driverContact = null;
                $routeType = null;
                $busNumber = null;

                if ($feeTypeId && $feeTypeId !== 'unknown' && class_exists('\App\Models\TransportDetails')) {
                    try {
                        $transportDetail = \App\Models\TransportDetails::where('transport_reference_id', $feeTypeId)
                            ->where('institute_id', $student->institute_id)
                            ->first();

                        if ($transportDetail) {
                            $transportRoute = \App\Models\TransportRoute::where('route_reference_id', $transportDetail->route_id)
                                ->where('institute_id', $student->institute_id)
                                ->first();
                            $routeName = $transportRoute->route_name ?? null;
                            $vehicleNumber = $transportDetail->vehicle_number ?? null;
                            $busNumber = $transportDetail->bus_number ?? null;
                            $driverName = $transportDetail->driver_name ?? null;
                            $driverContact = $transportDetail->driver_contact ?? null;
                            $routeType = $transportDetail->route_type ?? null;

                            if ($stopId && $stopId !== 'route_default' && isset($transportDetail->stops)) {
                                $stops = $transportDetail->stops;
                                if (is_array($stops)) {
                                    foreach ($stops as $stop) {
                                        if (isset($stop['id']) && $stop['id'] === $stopId) {
                                            $stopName = $stop['name'] ?? $stopName;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('Transport details fetch error: ' . $e->getMessage());
                    }
                }

                if ($stopId === 'route_default' || $stopId === null) {
                    $stopName = 'Whole Route';
                }

                if (!$vehicleNumber && !$busNumber) {
                    $vehicleNumber = $extra->vehicle_number ?? null;
                }
                if (!$driverName) {
                    $driverName = $extra->driver_name ?? null;
                }
                if (!$routeName) {
                    $routeName = $extra->morning_route_name ?? 'N/A';
                }

                $displayVehicle = $vehicleNumber ?: ($busNumber ?: 'N/A');
                $displayDriver = $driverName ?: 'N/A';
                $displayRoute = $routeName ?: 'N/A';
                $displayFare = $fare > 0 ? $fare : ($extra->transport_fare ?? null);

                $transportRoutesList->push([
                    'route_name' => $displayRoute,
                    'stop' => $stopName,
                    'vehicle' => $displayVehicle,
                    'driver' => $displayDriver,
                    'driver_contact' => $driverContact,
                    'fare' => $displayFare,
                    'route_type' => $routeType,
                ]);
            }
        }

        if ($transportRoutesList->isEmpty()) {
            $transportRoutesList->push([
                'route_name' => $extra->morning_route_name ?? 'N/A',
                'stop' => $extra->morning_stop ?? 'N/A',
                'vehicle' => $extra->vehicle_number ?? 'N/A',
                'driver' => $extra->driver_name ?? 'N/A',
                'fare' => $extra->transport_fare ?? null,
            ]);
        }

        $transportData = $transportRoutesList->first();

        // Fetch Hostel details
        $hostelData = [
            'hostel' => $extra->hostel_name ?? null,
            'room_no' => $extra->room_no ?? null,
            'room_type' => $extra->room_type ?? null,
            'cost' => $extra->hostel_cost ?? null,
        ];
        if (isset($student->StudentHostelFeeStructure) && $student->StudentHostelFeeStructure->isNotEmpty()) {
            $hStruct = $student->StudentHostelFeeStructure->first();
            if ($hStruct) {
                $refId = $hStruct->hostel_reference_id ?? $hStruct->fee_type_id ?? $hStruct->fee_type ?? null;
                if ($refId && class_exists('\App\Models\HostelFee')) {
                    try {
                        $hObj = \App\Models\HostelFee::where('hostel_fee_reference_id', $refId)
                            ->orWhere('id', $refId)
                            ->orWhere('hostel_name', $refId)
                            ->first();
                        if ($hObj) {
                            $hostelData['hostel'] = $hostelData['hostel'] ?? $hObj->hostel_name ?? null;
                            $hostelData['room_type'] = $hostelData['room_type'] ?? $hObj->room_type ?? null;
                            $hostelData['cost'] = $hostelData['cost'] ?? $hObj->monthly_fee ?? $hObj->total_fee ?? null;
                        }
                    } catch (\Exception $e) {
                        // ignore schema mismatches
                    }
                }
                $hostelData['cost'] = $hostelData['cost'] ?? $hStruct->hostel_fee ?? $hStruct->hostel_total_fee ?? null;
                $hostelData['room_no'] = $hostelData['room_no'] ?? $hStruct->room_no ?? null;
            }
        }

        // Fetch ID Card Settings & Data
        $instituteId = $student->institute_id ?? null;
        $idCardSettings = [];
        if ($instituteId && class_exists('\App\Models\IDCardSetting')) {
            $idCardSettings = \App\Models\IDCardSetting::where('institute_id', $instituteId)
                ->where('type', 'student_card')
                ->pluck('value', 'key')
                ->toArray();
        }

        $defaultCardSettings = [
            'card_title' => 'STUDENT IDENTIFICATION CARD',
            'signature_text' => "Principal's Signature",
            'signature_image' => null,
            'header_bg_color' => '#3a0ca3',
            'header_font_color' => '#ffffff',
            'footer_bg_color' => '#3a0ca3',
            'footer_font_color' => '#ffffff',
            'header_banner' => null
        ];
        $idCardSettings = array_merge($defaultCardSettings, $idCardSettings);

        $instituteLogo = null;
        $instituteAddress = 'N/A';
        if ($institute) {
            if (isset($institute->documents) && $institute->documents->count() > 0) {
                $instituteLogo = $institute->documents->pluck('logo_path')->first();
            }
            $instituteAddress = collect([
                $institute->address_line1 ?? null,
                $institute->address_line2 ?? null,
                $institute->city ?? null,
                $institute->state ?? null,
                $institute->pincode ?? null,
            ])->filter()->implode(', ');
        }

        $idCardData = [
            'institute_name' => $institute->name ?? 'Institute Name',
            'institute_logo' => $instituteLogo,
            'institute_address' => $instituteAddress ?: 'N/A',
            'full_name' => trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? '')),
            'registration_number' => $student->registration_number ?? 'N/A',
            'blood_group' => $student->blood_group ?? 'N/A',
            'father_name' => trim(($student->father_first_name ?? '') . ' ' . ($student->father_middle_name ?? '') . ' ' . ($student->father_last_name ?? '')),
            'mother_name' => trim(($student->mother_first_name ?? '') . ' ' . ($student->mother_middle_name ?? '') . ' ' . ($student->mother_last_name ?? '')),
            'course' => $extra->course_subtype ?? 'N/A',
            'department' => $extra->department ?? 'N/A',
            'academic_year' => $extra->academic_year ?? 'N/A',
            'section' => $extra->section_id ?? 'N/A',
            'roll_no' => $extra->roll_no ?? 'N/A',
            'dob' => $student->dob ?? 'N/A',
            'mobile' => $student->mobile ?? $student->father_phone ?? $student->mother_phone ?? 'N/A',
            'email' => $student->email ?? 'N/A',
            'photo' => $documents->student_photo ?? null,
            'address' => trim(($address->student_perm_address_line1 ?? '') . ' ' . ($address->student_perm_city ?? '') . ' ' . ($address->student_perm_state ?? '')),
            'settings' => $idCardSettings
        ];

        return view('instituteAdmin.StudentFiles.StudentViewPage', compact(
            'student',
            'address',
            'bank',
            'documents',
            'extra',
            'siblings',
            'institute',
            'feeStructures',
            'feeGroups',
            'totalAssigned',
            'totalPaid',
            'totalConcession',
            'totalFine',
            'balance',
            'pctComplete',
            'pct',
            'deg',
            'attPresentCount',
            'attAbsentCount',
            'attLateCount',
            'attHalfDayCount',
            'attTotalMarked',
            'attPct',
            'attDeg',
            'attendanceByDate',
            'examMarks',
            'upcomingExams',
            'transportData',
            'transportRoutesList',
            'hostelData',
            'idCardData',
            'idCardSettings',
            // Performance data variables
            'performanceData',
            'performanceSubjects',
            'performanceTotalPlans',
            'performanceTotalTopics',
            'performanceCoveredTopics',
            'performanceTotalMaterials',
            'performanceViewedMaterials',
            'performanceTotalPresent',
            'performanceTotalAttendance',
            'perfMonth',
            'perfYear',
            'perfSubjectFilter'
        ));
    }


    public function editstudent($id)
    {
        $student = StudentParentDetails::where('student_hash_id', $id)->first() ?? new \stdClass();
        $address = StudentParentAddress::where('student_hash_id', $id)->first() ?? new \stdClass();
        $bank = StudentParentBankAccount::where('student_hash_id', $id)->first() ?? new \stdClass();
        $documents = StudentParentDocuments::where('student_hash_id', $id)->first() ?? new \stdClass();
        $extra = StudentAcademicTransportDetails::where('student_hash_id', $id)->first() ?? new \stdClass();
        $courseId = $extra->course_subtype_id ?? null;
        $departmentCategories = DepartmentCategory::all();
        return view('instituteAdmin.StudentFiles.EditStudentDetails', compact('student', 'address', 'bank', 'documents', 'extra', 'departmentCategories', 'courseId'));
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
                    $query->where(function ($q) use ($filters) {
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
                // Apply section filter when provided (section_id expected)
                if (!empty($filters['section_id'])) {
                    $query->whereHas('academicTransportDetails', function ($q) use ($filters) {
                        $q->where('section_id', $filters['section_id']);
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

        // =========================================================
        // SECTION NAME RESOLUTION FOR EXPORT
        // (attach human-readable section_name onto each academicTransportDetails)
        // =========================================================
        $courseIds = collect();
        foreach ($students as $student) {
            $academic = $student->academicTransportDetails;
            if ($academic && !empty($academic->course_subtype_id)) {
                $courseIds->push($academic->course_subtype_id);
            }
        }
        $courseIds = $courseIds->unique()->values();

        $sectionDataMap = [];
        if ($courseIds->isNotEmpty()) {
            $courseFeeStructures = DB::table('course_fee_structures as cfs')
                ->whereIn('cfs.product_id', $courseIds)
                ->where('cfs.institute_id', $instituteId)
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

        // Attach section name to academicTransportDetails
        foreach ($students as $student) {
            $academic = $student->academicTransportDetails;
            if (!$academic)
                continue;
            $lookupKey = ($academic->course_subtype_id ?? '') . '_' . ($academic->section_id ?? '');
            $academic->section_name = $sectionDataMap[$lookupKey] ?? null;
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
            $query->where('registration_number', 'LIKE', '%' . $filters['registration_number'] . '%');
        }

        if (!empty($filters['name'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('first_name', 'LIKE', '%' . $filters['name'] . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $filters['name'] . '%');
            });
        }

        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        return response()->json([
            'count' => $query->count()
        ]);
    }

    /**
     * Handle sibling assignments when saving form 1
     */
    private function handleSiblingAssignments($request, $mainStudentHashId, $context)
    {
        $validSiblings = [];
        $invalidSiblings = [];
        $existingFamilyId = null;
        $siblingsInOtherFamilies = [];

        // Filter only valid siblings (those with hash_id)
        foreach ($request->siblings as $index => $sibling) {
            if (!empty($sibling['student_hash_id'])) {
                $validSiblings[] = $sibling;
            } elseif (!empty($sibling['search_value'])) {
                // This sibling had a search value but no valid hash_id (invalid/not found)
                $invalidSiblings[] = $sibling['search_value'];
            }
        }

        // If there are invalid siblings, throw exception with details
        if (!empty($invalidSiblings)) {
            throw new \Exception('Some siblings could not be found: ' . implode(', ', $invalidSiblings) . '. Please verify the registration numbers or emails.');
        }

        // If no valid siblings, return early
        if (empty($validSiblings)) {
            return;
        }

        // Check if any of the siblings already belong to another family
        foreach ($validSiblings as $sibling) {
            $existingSibling = StudentSibling::where('student_hash_id', $sibling['student_hash_id'])->first();
            if ($existingSibling) {
                $siblingsInOtherFamilies[] = [
                    'registration_number' => $sibling['registration_number'] ?? $sibling['student_hash_id'],
                    'family_id' => $existingSibling->sibling_pair_id
                ];
            }
        }

        // If multiple siblings belong to different families, throw error
        if (count($siblingsInOtherFamilies) > 1) {
            $differentFamilies = collect($siblingsInOtherFamilies)->pluck('family_id')->unique();
            if ($differentFamilies->count() > 1) {
                throw new \Exception('The selected siblings belong to different families and cannot be added together.');
            }
        }

        // Determine which family ID to use
        if (!empty($siblingsInOtherFamilies)) {
            // Use the existing family ID from the first sibling that already has a family
            $existingFamilyId = $siblingsInOtherFamilies[0]['family_id'];

            // Check if main student already has a family (shouldn't, but just in case)
            $mainExisting = StudentSibling::where('student_hash_id', $mainStudentHashId)->first();
            if ($mainExisting && $mainExisting->sibling_pair_id != $existingFamilyId) {
                throw new \Exception('Main student already belongs to a different family.');
            }
        }

        // Generate a new family ID if no existing family found
        $familyPairId = $existingFamilyId ?? $this->generateUniqueSiblingId();

        // 1. Create or update record for the main student
        StudentSibling::updateOrCreate(
            ['student_hash_id' => $mainStudentHashId],
            [
                'student_registration_number' => $request->registration_number,
                'sibling_pair_id' => $familyPairId,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'course_subtype_id' => null, // Will be updated in form 3
                'section_id' => null, // Will be updated in form 3
                'type' => $existingFamilyId ? 'sibling' : 'main', // If joining existing family, this student is a sibling, not main
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 2. Create records for each valid sibling (only if they don't already exist)
        foreach ($validSiblings as $sibling) {
            $existingSibling = StudentSibling::where('student_hash_id', $sibling['student_hash_id'])->first();

            if (!$existingSibling) {
                // This sibling doesn't have a family yet, add them
                StudentSibling::create([
                    'student_hash_id' => $sibling['student_hash_id'],
                    'student_registration_number' => $sibling['registration_number'],
                    'sibling_pair_id' => $familyPairId,
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    'course_subtype_id' => $sibling['class_id'] ?? null,
                    'section_id' => $sibling['section_id'] ?? null,
                    'type' => 'sibling',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else if ($existingSibling->sibling_pair_id != $familyPairId) {
                // This sibling already has a different family - this shouldn't happen due to our earlier check
                // But just in case, log it

            }
        }

    }

    /**
     * Get the family ID for a student if they already belong to a family
     */

    private function getStudentFamilyId($studentHashId)
    {
        $siblingRecord = StudentSibling::where('student_hash_id', $studentHashId)->first();
        return $siblingRecord ? $siblingRecord->sibling_pair_id : null;
    }

    /**
     * Generate a unique random sibling pair ID
     */
    private function generateUniqueSiblingId()
    {
        do {
            // Generate a random ID with prefix 'SIB' and 10 random characters
            $siblingPairId = 'SIB' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

            // Check if this ID already exists in the database
            $exists = StudentSibling::where('sibling_pair_id', $siblingPairId)->exists();

        } while ($exists);

        return $siblingPairId;
    }

    /**
     * Check if student already belongs to any family
     */
    private function studentHasFamily($studentHashId)
    {
        return StudentSibling::where('student_hash_id', $studentHashId)->exists();
    }

    private function assignStudentFeeStructure($studentHashId, $context)
    {
        try {
            // Get student details with academic transport details
            $studentDetail = StudentParentDetails::with('academicTransportDetails')
                ->where('student_hash_id', $studentHashId)
                ->first();

            if (!$studentDetail || !$studentDetail->academicTransportDetails) {
                \Log::warning('Student academic details not found for fee assignment', [
                    'student_hash_id' => $studentHashId
                ]);
                return false;
            }

            $academicDetails = $studentDetail->academicTransportDetails;

            // Get course fee structure based on batch
            $courseFeeStructures = CourseFeeStructure::where('batch_id', $academicDetails->batch_id)
                ->get();

            if ($courseFeeStructures->isEmpty()) {
                \Log::warning('Course fee structure not found for batch', [
                    'batch_id' => $academicDetails->batch_id
                ]);
                return false;
            }

            // Extract all academic years
            $academicYears = $courseFeeStructures->pluck('academic_year_id')
                ->unique()
                ->values()
                ->toArray();

            $totalAcademicYear = count($academicYears);

            // Process Course Fee
            $this->processAndSaveFeeStructure(
                $courseFeeStructures,
                'course_fee',
                'StudentCourseFeeStructure',
                $studentDetail,
                $context,
                $academicYears,
                $totalAcademicYear
            );

            // Process Registration Fee - Separate handling
            $this->processRegistrationFee(
                $courseFeeStructures,
                $studentDetail,
                $context,
                $academicYears
            );

            \Log::info('Fee structure assigned successfully', [
                'student_hash_id' => $studentHashId
            ]);

            return true;

        } catch (\Exception $e) {
            \Log::error('Error assigning fee structure', [
                'student_hash_id' => $studentHashId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    private function processRegistrationFee($courseFeeStructures, $studentDetail, $context, $academicYears)
    {
        // Find the fee structure that has registration_fee
        $feeStructure = $courseFeeStructures->first(function ($structure) {
            return !empty($structure->registration_fee) &&
                $structure->registration_fee !== 'null' &&
                $structure->registration_fee !== '[]';
        });

        if (!$feeStructure || empty($feeStructure->registration_fee)) {
            return false;
        }

        // Decode the registration fee JSON
        $feeData = json_decode($feeStructure->registration_fee, true);

        // Handle double-encoded JSON
        if (is_string($feeData)) {
            $feeData = json_decode($feeData, true);
        }

        if (!is_array($feeData) || empty($feeData['payments'])) {
            return false;
        }

        $payments = $feeData['payments'];
        $lateFee = $feeData['late_fee'] ?? [];
        $duration = $feeData['duration'] ?? null;
        $partialPayment = $feeData['partial_payment'] ?? [];

        // Get product_id from fee structure if available
        $productId = $feeStructure->product_id ?? null;

        // Clear existing registration fee records for this student
        StudentRegistrationFeeStructure::where('student_hash_id', $studentDetail->student_hash_id)->delete();

        // Insert registration fee structure
        foreach ($payments as $index => $payment) {
            // For registration fee, typically assign to first academic year
            $academicYear = $academicYears[0] ?? null;

            $feeAmount = is_array($payment) ? ($payment['amount'] ?? $payment) : $payment;
            $dueDate = is_array($payment) ? ($payment['end_date'] ?? null) : null;

            $data = [
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'student_hash_id' => $studentDetail->student_hash_id,
                'fee_duration_type' => $duration,
                'batch_id' => $feeStructure->batch_id,
                'academic_year_id' => $academicYear,
                'registration_fee' => $feeAmount,
                'registration_total_fee' => $feeAmount,
                'due_date' => $dueDate,
                'late_fee_type' => $lateFee['type'] ?? null,
                'late_fee_value' => $lateFee['amount'] ?? 0,
            ];

            // Add product_id if available
            if ($productId) {
                $data['product_id'] = $productId;
            }

            StudentRegistrationFeeStructure::create($data);
        }

        return true;
    }

    private function processAndSaveFeeStructure($courseFeeStructures, $feeField, $modelClass, $studentDetail, $context, $academicYears, $totalAcademicYear, $optional = false)
    {
        // Skip registration fee here since it's handled separately
        if ($feeField === 'registration_fee') {
            return true;
        }

        // Define a mapping of fee fields to actual model classes
        $modelMapping = [
            'course_fee' => 'App\\Models\\StudentCourseFeeStructure',
            'hostel_fee' => 'App\\Models\\StudentHostelFeeStructure',
            'transportation_fee' => 'App\\Models\\StudentTransportFeeStructure',
            'miscellaneous_fee' => 'App\\Models\\StudentMiscellaneousFeeStructure',
            'custom_fees' => 'App\\Models\\StudentCustomFeestructure',
        ];

        // Get the actual model class from mapping
        $fullModelClass = $modelMapping[$feeField] ?? null;

        if (!$fullModelClass) {
            return false;
        }

        // Get the first record that has this fee type
        $feeStructure = $courseFeeStructures->first(function ($structure) use ($feeField) {
            return !empty($structure->$feeField) &&
                $structure->$feeField !== 'null' &&
                $structure->$feeField !== '[]';
        });

        if (!$feeStructure || empty($feeStructure->$feeField)) {
            if (!$optional) {
            }
            return false;
        }

        // Decode the fee structure
        $feeData = json_decode($feeStructure->$feeField, true);

        // Handle double-encoded JSON
        if (is_string($feeData)) {
            $feeData = json_decode($feeData, true);
        }

        if (!is_array($feeData) || empty($feeData['payments'])) {
            return false;
        }

        $payments = $feeData['payments'];
        $lateFee = $feeData['late_fee'] ?? [];
        $duration = $feeData['duration'] ?? null;
        $partialPayment = $feeData['partial_payment'] ?? [];

        // Get product_id from fee structure if available
        $productId = $feeStructure->product_id ?? null;

        $totalPayments = count($payments);
        $paymentsPerYear = $totalAcademicYear > 0 ? ceil($totalPayments / $totalAcademicYear) : $totalPayments;

        // Clear existing records for this student
        $fullModelClass::where('student_hash_id', $studentDetail->student_hash_id)->delete();

        // Determine the fee column name based on fee field
        $feeColumn = str_replace('_fee', '_fee', $feeField);
        if ($feeField === 'transportation_fee') {
            $feeColumn = 'transportation_fee';
        } elseif ($feeField === 'custom_fees') {
            $feeColumn = 'custom_fees';
        }

        // Insert new fee structure
        foreach ($payments as $index => $payment) {
            // Determine academic year dynamically
            $yearIndex = floor($index / $paymentsPerYear);
            $yearIndex = min($yearIndex, $totalAcademicYear - 1);
            $academicYear = $academicYears[$yearIndex] ?? null;

            $feeAmount = is_array($payment) ? ($payment['amount'] ?? $payment) : $payment;
            $dueDate = is_array($payment) ? ($payment['end_date'] ?? null) : null;

            $data = [
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'student_hash_id' => $studentDetail->student_hash_id,
                'fee_duration_type' => $duration,
                'batch_id' => $feeStructure->batch_id,
                'academic_year_id' => $academicYear,
                $feeColumn => $feeAmount,
                'due_date' => $dueDate,
                'late_fee_type' => $lateFee['type'] ?? null,
                'late_fee_value' => $lateFee['amount'] ?? 0,
                'partially_fee_type' => $partialPayment['type'] ?? null,
                'partially_fee_value' => $partialPayment['amount'] ?? 0,
            ];

            // Add product_id only for course fee and if it exists
            if ($feeField === 'course_fee' && $productId) {
                $data['product_id'] = $productId;
            }

            $fullModelClass::create($data);
        }

        return true;
    }

    public function showStudentFee($student_hash_id)
    {
        $studentDetail = StudentParentDetails::with([
            'StudentCourseFeeStructure',
            'StudentCustomFeestructure',
            'StudentHostelFeeStructure',
            'StudentMiscellaneousFeeStructure',
            'StudentRegistrationFeeStructure',
            'StudentTransportFeeStructure',
            'academicTransportDetails'
        ])
            ->where('student_hash_id', $student_hash_id)
            ->first();

        if (!$studentDetail) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        // Calculate total fee
        $totalAllFees = 0;
        $feeCategories = [
            'StudentCourseFeeStructure',
            'StudentHostelFeeStructure',
            'StudentTransportFeeStructure',
            'StudentRegistrationFeeStructure',
            'StudentMiscellaneousFeeStructure'
        ];
        foreach ($feeCategories as $category) {
            if ($studentDetail->$category) {
                foreach ($studentDetail->$category as $feeItem) {
                    if (isset($feeItem->course_fee))
                        $totalAllFees += floatval($feeItem->course_fee);
                    if (isset($feeItem->hostel_fee))
                        $totalAllFees += floatval($feeItem->hostel_fee);
                    if (isset($feeItem->transport_fee))
                        $totalAllFees += floatval($feeItem->transport_fee);
                    if (isset($feeItem->registration_fee))
                        $totalAllFees += floatval($feeItem->registration_fee);
                    if (isset($feeItem->miscellaneous_fee))
                        $totalAllFees += floatval($feeItem->miscellaneous_fee);
                }
            }
        }

        // Get section display name - FIXED: use course_subtype_id from academic details
        $academic = $studentDetail->academicTransportDetails;
        $sectionDisplayName = null;
        if ($academic && $academic->course_subtype_id && $academic->section_id) {
            $sectionDisplayName = $this->resolveSectionName(
                $academic->course_subtype_id, // product_id from academic details
                $studentDetail->institute_id,
                $academic->section_id,
                $studentDetail->branch_id // pass branch if needed
            );
        }

        $charges = null;

        return view('instituteAdmin.StudentFiles.AdminViewStudentFeeStructure', compact(
            'studentDetail',
            'totalAllFees',
            'charges',
            'sectionDisplayName'
        ));
    }

    private function resolveSectionName($productId, $instituteId, $sectionId, $branchId = null)
    {
        if (!$productId || !$sectionId) {
            return null;
        }

        $query = DB::table('course_fee_structures')
            ->where('product_id', $productId)
            ->where('institute_id', $instituteId);



        $sectionData = $query->value('sections');

        if (!$sectionData) {
            return null;
        }

        $sections = json_decode($sectionData, true);

        if (!is_array($sections)) {
            return null;
        }

        foreach ($sections as $section) {
            if (!isset($section['id'], $section['name'])) {
                continue;
            }

            // Normalize both values
            $dbSection = str_replace('section_', '', $section['id']);
            $studentSection = str_replace('section_', '', $sectionId);

            if ($dbSection == $studentSection) {
                return $section['name'];
            }
        }

        return null;
    }

    /**
     * Get sections for a specific course
     */
    public function getSections($productId, Request $request)
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

            // Get course fee structures for this product
            $query = DB::table('course_fee_structures')
                ->where('product_id', $productId)
                ->where('institute_id', $context['institute_id']);

            // Apply branch filter if branch admin
            if ($context['is_branch_admin'] && $context['branch_id']) {
                $query->where('branch_id', $context['branch_id']);
            } else {
                $query->whereNull('branch_id');
            }

            $courseFeeStructure = $query->first();

            if (!$courseFeeStructure || !$courseFeeStructure->sections) {
                return response()->json([
                    'success' => false,
                    'message' => 'No sections found for this course',
                    'sections' => []
                ]);
            }

            // Decode sections JSON
            $sections = json_decode($courseFeeStructure->sections, true);

            // Format sections for dropdown
            $formattedSections = [];
            if (is_array($sections)) {
                foreach ($sections as $section) {
                    if (is_array($section)) {
                        $formattedSections[] = [
                            'id' => $section['id'] ?? $section['value'] ?? $section,
                            'name' => $section['name'] ?? $section['label'] ?? $section
                        ];
                    } else {
                        // If sections are simple strings
                        $formattedSections[] = [
                            'id' => $section,
                            'name' => $section
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'sections' => $formattedSections
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching sections: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sections: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exit a student (set status to inactive)
     */
    // public function exitStudent(Request $request, $student_hash_id)
    // {
    //     try {
    //         // Get institute/branch context
    //         $context = $this->getInstituteBranchContext();

    //         // Find the student
    //         $student = StudentParentDetails::where('student_hash_id', $student_hash_id)
    //             ->where('institute_id', $context['institute_id'])
    //             ->first();

    //         if (!$student) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Student not found or you do not have access.'
    //             ], 404);
    //         }

    //         // Apply branch restriction if branch admin
    //         if ($context['is_branch_admin'] && $context['branch_id']) {
    //             if ($student->branch_id != $context['branch_id']) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'You do not have access to this student.'
    //                 ], 403);
    //             }
    //         }

    //         // Prepare data for update
    //         $updateData = [
    //             'student_status'=>'exit',
    //             'status' => 'inactive',
    //             'exit_date' => now(),
    //         ];

    //         // Add exit reason if provided (FIXED SYNTAX)
    //         if ($request->has('exit_reason') && !empty($request->exit_reason)) {
    //             $updateData['exit_reason'] = $request->exit_reason;
    //         }

    //         // Update student status
    //         $student->update($updateData);

    //         // Return success response
    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Student has been exited successfully.'
    //             ]);
    //         }

    //         return redirect()->back()->with('success', 'Student has been exited successfully.');

    //     } catch (\Exception $e) {
    //         \Log::error('Error exiting student: ' . $e->getMessage(), [
    //             'student_hash_id' => $student_hash_id,
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Error exiting student: ' . $e->getMessage()
    //             ], 500);
    //         }

    //         return redirect()->back()->with('error', 'Error exiting student: ' . $e->getMessage());
    //     }
    // }

    /**
     * Exit a student (set status to inactive)
     */
    public function exitStudent(Request $request, $student_hash_id)
    {
        try {  // <-- UNCOMMENT this try block
            DB::beginTransaction(); // Start transaction for data consistency

            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            // Find the student
            $student = StudentParentDetails::where('student_hash_id', $student_hash_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found or you do not have access.'
                ], 404);
            }

            // Apply branch restriction if branch admin
            if ($context['is_branch_admin'] && $context['branch_id']) {
                if ($student->branch_id != $context['branch_id']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to this student.'
                    ], 403);
                }
            }

            // Check if student is already exited
            if ($student->status === 'inactive' || $student->student_status === 'exit') {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is already exited.'
                ], 400);
            }

            // Prepare data for update
            $updateData = [
                'student_status' => 'exit',
                'status' => 'inactive',
                'exit_date' => now(),
            ];

            // Add exit reason if provided
            if ($request->has('exit_reason') && !empty($request->exit_reason)) {
                $updateData['exit_reason'] = $request->exit_reason;
            }

            // Clear any active suspension when exiting
            if ($student->suspend_status === 'suspended') {
                $updateData['suspend_status'] = 'active';
                $updateData['suspended_at'] = null;
                $updateData['suspension_reason'] = null;
            }

            // Update student status in student_parent_details
            $student->update($updateData);

            // ========== UPDATE USER TABLE STATUS ==========
            $user = \App\Models\User::where('id', $student->user_id)->first();

            if ($user) {
                $user->update([
                    'status' => 'inactive',
                    'deactivated_at' => now(),
                ]);
            }
            // =============================================

            DB::commit();

            // Log the exit action
            \Log::info('Student exited from institute', [
                'student_hash_id' => $student_hash_id,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'exit_reason' => $request->exit_reason ?? null,
                'exited_by' => auth()->id(),
                'exited_at' => now()
            ]);

            // ========== ADD THIS - SEND EMAIL NOTIFICATIONS ==========
            $this->sendExitNotification($student, $context, $request->exit_reason);
            // ========================================================

            // Return success response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student has been exited successfully.'
                ]);
            }

            return redirect()->back()->with('success', 'Student has been exited successfully.');

        } catch (\Exception $e) {  // <-- UNCOMMENT this catch block
            DB::rollBack();

            \Log::error('Error exiting student: ' . $e->getMessage(), [
                'student_hash_id' => $student_hash_id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error exiting student: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error exiting student: ' . $e->getMessage());
        }
    }

    /**
     * Send exit notification to student and parent
     */
    private function sendExitNotification($student, $context, $exitReason = null)
    {
        // try {
        // Check if exit notification is enabled
        $emailEnabled = $this->isNotificationEnabled(
            $context['institute_id'],
            'student_exit',
            'email'
        );

        if (!$emailEnabled) {
            \Log::info("Exit email notifications are disabled for institute: " . $context['institute_id']);
            return;
        }

        // Get institute details
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
        $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

        $studentName = trim($student->first_name . ' ' .
            ($student->middle_name ? $student->middle_name . ' ' : '') .
            $student->last_name);

        // Prepare email data
        $emailData = [
            'studentName' => $studentName,
            'instituteName' => $instituteName,
            'exitReason' => $exitReason,
            'exitDate' => now()->format('d-m-Y H:i:s'),
            'registrationNumber' => $student->registration_number,
        ];

        // Send email to student
        if (!empty($student->email)) {
            Mail::send('emails.student-exit', $emailData, function ($message) use ($student, $instituteName) {
                $message->to($student->email)
                    ->subject("Student Exit Confirmation - {$instituteName} (#{$student->registration_number})");
            });

            \Log::info("Student exit email sent to: " . $student->email);
        }

        // Send email to parent/guardian
        $parentEmail = $this->getParentEmail($student);

        if ($parentEmail) {
            $parentName = $this->getParentName($student);
            $emailData['parentName'] = $parentName;

            Mail::send('emails.parent-exit', $emailData, function ($message) use ($parentEmail, $instituteName, $student) {
                $message->to($parentEmail)
                    ->subject("Student Exit Confirmation - {$instituteName} ({$student->registration_number})");
            });

            \Log::info("Parent exit email sent to: " . $parentEmail);
        }

        // } catch (\Exception $e) {
        //     \Log::error("Failed to send exit notification: " . $e->getMessage());
        // }
    }

    /**
     * Get parent/guardian email
     */
    private function getParentEmail($student)
    {
        if ($student->guardian_type === 'father' && !empty($student->father_email)) {
            return $student->father_email;
        } elseif ($student->guardian_type === 'mother' && !empty($student->mother_email)) {
            return $student->mother_email;
        } elseif (!empty($student->guardian_email)) {
            return $student->guardian_email;
        }
        return null;
    }

    /**
     * Get parent/guardian name
     */
    private function getParentName($student)
    {
        if ($student->guardian_type === 'father' && !empty($student->father_first_name)) {
            return trim($student->father_first_name . ' ' .
                ($student->father_middle_name ? $student->father_middle_name . ' ' : '') .
                $student->father_last_name);
        } elseif ($student->guardian_type === 'mother' && !empty($student->mother_first_name)) {
            return trim($student->mother_first_name . ' ' .
                ($student->mother_middle_name ? $student->mother_middle_name . ' ' : '') .
                $student->mother_last_name);
        } elseif (!empty($student->guardian_first_name)) {
            return trim($student->guardian_first_name . ' ' .
                ($student->guardian_middle_name ? $student->guardian_middle_name . ' ' : '') .
                $student->guardian_last_name);
        }
        return 'Parent/Guardian';
    }

    // Add this method to your StudentonboardController
    private function getGeneratedCertificates($merchantId)
    {
        $certificates = DB::table('student_certificates')
            ->whereIn('certificate_type', ['course_completion', 'character', 'bonafide', 'tc', 'school_leaving', 'no_due'])
            ->select('registration_number', 'certificate_type')
            ->get();

        $certificateMap = [];
        foreach ($certificates as $cert) {
            $key = $cert->registration_number . '_' . $cert->certificate_type;
            $certificateMap[$key] = true;
        }

        return $certificateMap;
    }

    /**
     * Check if a specific notification channel is enabled for a module
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        try {
            $setting = InstituteNotificationSetting::where('institute_id', $instituteId)
                ->where('module_name', $moduleName)
                ->first();

            if (!$setting) {
                // If no setting found, use default (email enabled by default)
                return $channel === 'email';
            }

            switch ($channel) {
                case 'email':
                    return $setting->email_enabled;
                case 'whatsapp':
                    return $setting->whatsapp_enabled;
                case 'sms':
                    return $setting->sms_enabled;
                default:
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error('Error checking notification status: ' . $e->getMessage());
            return $channel === 'email';
        }
    }

    /**
     * Send student onboarding notification
     */
    private function sendStudentOnboardingNotification($studentData, $context)
    {
        // try {
        $student = StudentParentDetails::where('student_hash_id', $studentData['hash_id'])->first();

        if (!$student) {
            \Log::warning('Student not found for onboarding notification', [
                'student_hash_id' => $studentData['hash_id'] ?? null
            ]);
            return;
        }

        // Get academic details for additional info
        $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $studentData['hash_id'])->first();

        // Get institute name
        $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
        $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

        // Check if student notification is enabled
        $emailEnabled = $this->isNotificationEnabled(
            $context['institute_id'],
            'student_on_board',
            'email'
        );

        $whatsappEnabled = $this->isNotificationEnabled(
            $context['institute_id'],
            'student_on_board',
            'whatsapp'
        );

        $smsEnabled = $this->isNotificationEnabled(
            $context['institute_id'],
            'student_on_board',
            'sms'
        );

        // Prepare data for email
        $studentName = trim($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name);

        $emailData = [
            'studentName' => $studentName,
            'registrationNumber' => $student->registration_number,
            'email' => $student->email,
            'password' => $studentData['password'] ?? '12345678',
            'instituteName' => $instituteName,
            'courseName' => $academicDetails ? $academicDetails->course_subtype : null,
            'departmentName' => $academicDetails ? $academicDetails->department : null,
            'batchName' => $academicDetails ? $academicDetails->batch : null,
            'academicYear' => $academicDetails ? $academicDetails->academic_year : null,
            'enrollmentDate' => now()->format('d-m-Y'),
        ];

        // Send Email to Student
        if ($emailEnabled && !empty($student->email)) {
            try {
                Mail::send('emails.student-welcome', $emailData, function ($message) use ($student, $instituteName) {
                    $message->to($student->email)
                        ->subject('Welcome to ' . $instituteName . ' - Your Enrollment Confirmation');
                });

                \Log::info('Student onboarding email sent to: ' . $student->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send student onboarding email: ' . $e->getMessage());
            }
        }

        // Send WhatsApp to Student
        if ($whatsappEnabled && !empty($student->mobile)) {
            try {
                $whatsappMessage = "🎓 Welcome to {$instituteName}!\n\n";
                $whatsappMessage .= "Dear {$studentName},\n\n";
                $whatsappMessage .= "Your enrollment is confirmed!\n";
                $whatsappMessage .= "Registration No: {$student->registration_number}\n";
                $whatsappMessage .= "Email: {$student->email}\n";
                $whatsappMessage .= "Password: {$studentData['password']}\n\n";
                $whatsappMessage .= "Login: " . url('/login') . "\n\n";
                $whatsappMessage .= "Please login and change your password.";

            } catch (\Exception $e) {
                \Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            }
        }

        // Send SMS to Student
        if ($smsEnabled && !empty($student->mobile)) {
            try {
                $smsMessage = "Welcome to {$instituteName}! Your enrollment is confirmed. Registration No: {$student->registration_number}. Login: " . url('/login') . " Use email: {$student->email}";

                // Integrate with your SMS provider
                \Log::info('SMS notification would be sent to: ' . $student->mobile);
            } catch (\Exception $e) {
                \Log::error('Failed to send SMS notification: ' . $e->getMessage());
            }
        }

        // Also send notification to parent/guardian if enabled
        $this->sendParentOnboardingNotification($student, $context, $instituteName, $studentData['password'] ?? null);

        // } catch (\Exception $e) {
        //     \Log::error('Error sending student onboarding notification: ' . $e->getMessage());
        // }
    }

    /**
     * Send parent/guardian onboarding notification
     */
    private function sendParentOnboardingNotification($student, $context, $instituteName, $studentPassword = null)
    {
        // try {
        // Check if parent notification is enabled
        $parentEmailEnabled = $this->isNotificationEnabled(
            $context['institute_id'],
            'parent_on_board',
            'email'
        );

        // Determine parent/guardian email based on guardian type
        $parentEmail = null;
        $parentName = null;

        if ($student->guardian_type === 'father' && !empty($student->father_email)) {
            $parentEmail = $student->father_email;
            $parentName = trim($student->father_first_name . ' ' . ($student->father_middle_name ? $student->father_middle_name . ' ' : '') . $student->father_last_name);
        } elseif ($student->guardian_type === 'mother' && !empty($student->mother_email)) {
            $parentEmail = $student->mother_email;
            $parentName = trim($student->mother_first_name . ' ' . ($student->mother_middle_name ? $student->mother_middle_name . ' ' : '') . $student->mother_last_name);
        } elseif (!empty($student->guardian_email)) {
            $parentEmail = $student->guardian_email;
            $parentName = trim($student->guardian_first_name . ' ' . ($student->guardian_middle_name ? $student->guardian_middle_name . ' ' : '') . $student->guardian_last_name);
        }

        if ($parentEmailEnabled && !empty($parentEmail)) {
            $studentName = trim($student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name);

            $parentEmailData = [
                'parentName' => $parentName ?: 'Parent/Guardian',
                'studentName' => $studentName,
                'registrationNumber' => $student->registration_number,
                'instituteName' => $instituteName,
                'studentEmail' => $student->email,
                'studentPassword' => $studentPassword,
                'enrollmentDate' => now()->format('d-m-Y'),
            ];

            try {
                Mail::send('emails.parent-welcome', $parentEmailData, function ($message) use ($parentEmail, $instituteName, $studentName) {
                    $message->to($parentEmail)
                        ->subject('Your Child\'s Enrollment at ' . $instituteName);
                });

                \Log::info('Parent onboarding email sent to: ' . $parentEmail);
            } catch (\Exception $e) {
                \Log::error('Failed to send parent onboarding email: ' . $e->getMessage());
            }
        }

        // } catch (\Exception $e) {
        //     \Log::error('Error sending parent onboarding notification: ' . $e->getMessage());
        // }
    }

    /**
     * Check if a lecture is scheduled on a specific date
     *
     * @param Carbon $date
     * @param mixed $lectures
     * @return bool
     */
    private function isLectureScheduled(Carbon $date, $lectures): bool
    {
        if (!$lectures || (is_countable($lectures) && count($lectures) === 0)) {
            return false;
        }

        return collect($lectures)->contains(function ($lecture) use ($date) {
            $validFrom = Carbon::parse($lecture->valid_from);
            $validTo = $lecture->valid_to ? Carbon::parse($lecture->valid_to) : null;

            if ($date->lt($validFrom) || ($validTo && $date->gt($validTo))) {
                return false;
            }

            $weekdays = array_map('strtolower', array_map('strval', (array) json_decode($lecture->days_of_week ?: '[]', true)));
            $dayNames = [strtolower($date->format('D')), strtolower($date->format('l')), (string) $date->dayOfWeek];

            return match (strtolower($lecture->frequency)) {
                'one_time' => $date->isSameDay($validFrom),
                'daily' => true,
                'weekly' => (bool) array_intersect($dayNames, $weekdays),
                'monthly' => (int) $date->day === (int) $lecture->day_of_month,
                default => false,
            };
        });
    }
    
        /**
     * Public student onboarding entry point. This is intentionally separate
     * from the authenticated student onboarding flow above.
     */
    public function showExternalStudentOnboarding()
    {
        return view('superadmin.OnboardingForms.StudentOnboard', [
            'serviceInstitutedetails' => null,
            'departmentCategories' => collect(),
            'departments' => collect(),
            'instituteType' => null,
        ]);
    }

    /**
     * Save public student onboarding steps using the fixed external institute.
     */
    public function handleExternalStudentFormSubmission(Request $request)
    {
        $step = (int) $request->input('form_step');
        $instituteId = 'ABCD1234';

        DB::beginTransaction();

        try {
            if ($step === 1) {
                $request->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'nullable|string|max:255',
                    'dob' => 'required|date|before:today',
                    'gender' => 'required|string|max:30',
                    'mobile' => 'required|digits:10',
                    'email' => 'required|email|max:255',
                    'blood_group' => 'required|string|max:30',
                ]);

                $result = $this->saveExternalStudentForm1($request, $instituteId);
                $studentHashId = $result['hash_id'];
                $request->session()->put('external_student_hash_id', $studentHashId);
            } else {
                $studentHashId = $request->input('student_hash_id')
                    ?: $request->session()->get('external_student_hash_id');

                if (!$studentHashId) {
                    throw new \Exception('Student reference missing. Please restart onboarding.');
                }

                if ($step === 2) {
                    $this->saveForm2Data($request, $studentHashId);
                } elseif ($step === 3) {
                    // Academic data is assigned by the institute and remains NULL.
                } elseif ($step === 4) {
                    $this->saveForm4Data($request, $studentHashId);
                } elseif ($step === 5) {
                    $this->saveForm5Data($request, $studentHashId);
                } else {
                    throw new \Exception('Invalid form step.');
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'student_id' => $result['id'] ?? null,
                'student_hash_id' => $studentHashId,
                'message' => "Form {$step} data saved successfully",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    private function saveExternalStudentForm1(Request $request, string $instituteId): array
    {
        $studentHashId = $this->generateStudentHashId();
        $fullName = trim(implode(' ', array_filter([
            $request->input('first_name'),
            $request->input('middle_name'),
            $request->input('last_name'),
        ])));

        $user = User::where('email', $request->input('email'))
            ->orWhere('phone', $request->input('mobile'))
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $fullName,
                'email' => $request->input('email'),
                'phone' => $request->input('mobile'),
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'email_verified_at' => now(),
                'institute_id' => $instituteId,
            ]);
            $user->assignRole('student');
        }

        $height = null;
        if ($request->input('height_unit') === 'ft_in') {
            $height = ((float) $request->input('height_feet', 0) * 30.48)
                + ((float) $request->input('height_inches', 0) * 2.54);
        } elseif ($request->filled('height_cm')) {
            $height = (float) $request->input('height_cm');
        }

        $weight = null;
        if ($request->filled('weight_input')) {
            $weight = $request->input('weight_unit') === 'lbs'
                ? (float) $request->input('weight_input') * 0.45359237
                : (float) $request->input('weight_input');
        }

        $studentFields = [
            'first_name',
            'middle_name',
            'last_name',
            'dob',
            'gender',
            'mobile',
            'email',
            'nationality',
            'religion',
            'blood_group',
            'category',
            'health_condition',
            'allergies',
            'medical_notes',
            'guardian_type',
            'guardian_relation',
            'guardian_first_name',
            'guardian_middle_name',
            'guardian_last_name',
            'guardian_gender',
            'guardian_dob',
            'guardian_email',
            'guardian_phone',
            'guardian_number_of_dependents',
            'alternate_phone_number',
            'guardian_occupation',
            'guardian_income',
            'guardian_blood_group',
            'guardian_nationality',
            'guardian_religion',
            'guardian_category',
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
            'father_marital_status',
            'father_number_of_dependents',
            'father_spouse_name',
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
            'mother_marital_status',
            'mother_number_of_dependents',
            'mother_spouse_name',
        ];

        $data = $request->only($studentFields);
        $data['student_hash_id'] = $studentHashId;
        $data['user_id'] = $user->id;
        $data['institute_id'] = $instituteId;
        $data['branch_id'] = null;
        $data['registration_number'] = null;
        $data['student_status'] = 'new';
        $data['self_initiated'] = 1;
        $data['height'] = $height;
        $data['height_unit'] = $request->input('height_unit');
        $data['weight'] = $weight;
        $data['weight_unit'] = $request->input('weight_unit');
        $data['parent_selection'] = $request->input('parent_selection', 'father');

        $student = StudentParentDetails::create($data);

        return [
            'id' => $student->id,
            'hash_id' => $studentHashId,
        ];
    }

}