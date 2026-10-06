<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\ProductDetails;
use App\Models\FincapMerchantSubCategories;
use App\Models\Departments;
use App\Models\InstituteBasicDetails;
use App\Models\EmployeeDetails;
use App\Models\Designations;
use App\Models\Letter;
use App\Models\DepartmentCategory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\EmployeeProbationLog;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use App\Notifications\ActivityNotification;
use App\Notifications\EmployeeOnboardedNotification;
use App\Traits\SendsInstituteNotifications;
use App\Models\User;
use App\Models\Lead;
use App\Exports\EmployeesExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Exception;

class EmployeeDetailsController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    use SendsInstituteNotifications;

    private function generateUniqueEmployeeCode(): string
    {
        do {
            $employeeCode = 'EMP-' . now()->year . '-' . random_int(1000, 9999);
        } while (EmployeeDetails::where('employee_code', $employeeCode)->exists());

        return $employeeCode;
    }
    public function AddemployeeDetails(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->with(['stakeholders', 'stakeholderDocuments', 'authorizedUser', 'authorizedUserDocuments', 'documents', 'beneficiary'])
            ->first();
        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->withCount('departments')
            ->orderBy('category_name')
            ->get();
        $departments = $this->getCommonQuery(Departments::class)
            ->with('category')
            ->orderBy('department')
            ->get();
        $designations = $this->getCommonQuery(Designations::class)
            ->with('departmentCategory')
            ->where('status', 'active')
            ->orderBy('designations')
            ->get();

        return view('instituteAdmin.EmployeeFiles.AddEmployeeDetails', [
            'categories' => $categories,
            'departments' => $departments,
            'designations' => $designations,
            'fincapMerchants' => $fincapMerchants,
            'user_type' => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
        ]);
    }

    public function storeemployeedetails(Request $request)
    {
        $request->merge(['employee_code' => $this->generateUniqueEmployeeCode()]);

        // Custom validation messages
        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'Please enter a valid email address.',
            'unique' => 'This :attribute is already registered.',
            'max' => 'The :attribute must not exceed :max characters.',
            'min' => 'The :attribute must be at least :min characters.',
            'date' => 'Please enter a valid date.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'regex' => 'Please enter a valid :attribute.',
            'aadhaar_number.regex' => 'Aadhaar number must be 12 digits.',
            'pan_number.regex' => 'PAN number must be 10 characters (e.g., ABCDE1234F).',
            'mobile_number.regex' => 'Please enter a valid 10-digit mobile number.',
            'emergency_contact_number.regex' => 'Please enter a valid 10-digit emergency contact number.',
            'pincode.regex' => 'Please enter a valid 6-digit pincode.',
            'account_number.regex' => 'Please enter a valid bank account number.',
            'ifsc_code.regex' => 'Please enter a valid IFSC code.',
            'documents.*.name.required' => 'Document name is required.',
            'documents.*.file.required' => 'Document file is required.',
            'documents.*.file.mimes' => 'Document must be a file of type: jpg, jpeg, png, pdf.',
            'documents.*.file.max' => 'Document file size must not exceed 2MB.',
            'marital_status.required' => 'Please select marital status.',
            'marital_status.in' => 'Please select a valid marital status.',
            'number_of_dependents.integer' => 'Number of dependents must be a number.',
            'number_of_dependents.min' => 'Number of dependents cannot be negative.',
            'number_of_dependents.max' => 'Number of dependents cannot exceed 20.',
        ];

        // Base validation rules
        $validationRules = [
            // Basic Details
            'employee_code' => 'required|string|max:50|unique:employee_details,employee_code',
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'mobile_number' => 'required|string|regex:/^[6-9]\d{9}$/|max:10',
            'email' => 'nullable|email|max:255|unique:employee_details,email',

            // Department & Designation
            'department_category_id' => 'required|exists:department_categories,department_category_id',
            'department_id' => 'required|exists:departments,department_id',
            'designation_id' => 'required|exists:designations,designation_id',
            'designation' => 'required|string|max:255',

            // Personal Details
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date|before:today',
            'blood_group' => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'height_unit' => 'required|in:cm,ft_in',
            'height_cm' => 'nullable|numeric|min:0',
            'height_feet' => 'nullable|numeric|min:0',
            'height_inches' => 'nullable|numeric|min:0|max:11.9',
            'weight_unit' => 'required|in:kg,lbs',
            'weight_input' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'health_condition' => 'nullable|string|max:255',
            'allergies' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'nationality' => 'required|string|max:100',
            'religion' => 'required|string|max:100',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'number_of_dependents' => 'nullable|integer|min:0|max:20',
            'spouse_name' => 'nullable|string|max:255',

            // Address
            'addressline1' => 'required|string|max:255',
            'addressline2' => 'nullable|string|max:255',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pincode' => 'required|string|regex:/^\d{6}$/',

            // GPS Capture
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'timestamp' => 'nullable|string|max:255',

            // Professional
            'employment_type' => 'required|in:Full-time,Part-time,Contract-based,Probation-Period',
            'probation_days' => 'nullable|integer|min:1|max:365|required_if:employment_type,Probation-Period',
            'salary_type' => 'required|in:Monthly,Hourly',
            'doj' => 'required|date|after_or_equal:2010-01-01',
            'previous_pf_number' => 'nullable|string|max:20',
            'esi_number' => 'nullable|string|regex:/^\d{17}$/',
            'previous_employer_name' => 'nullable|string|max:255',
            'previous_exit_date' => 'nullable|date|before:today',


            // Emergency Contact
            'emergency_contact_number' => 'required|string|regex:/^[6-9]\d{9}$/',
            'contact_person_name' => 'required|string|max:255',
            'relation_with_contact' => 'required|string|max:100',

            // Reference
            'reference_name' => 'nullable|string|max:255',
            'reference_contact_number' => 'nullable|string|regex:/^[6-9]\d{9}$/',

            // Legal Documents
            'pan_number' => 'nullable|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|unique:employee_details,pan_number',
            'is_address_same' => 'required|in:yes,no',
            'address_proof_type' => 'required_if:is_address_same,no|nullable|string|max:100',
            'address_proof_number' => 'nullable|string|max:50',

            // Bank Details
            'bank_name' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|regex:/^\d{9,18}$/',
            'ifsc_code' => 'nullable',

            // File Uploads
            'profile_photo' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'upload_signature' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'pan_card' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'driving_license' => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',
            'passport_photo' => 'nullable|mimes:jpg,jpeg,png|max:1024',
            'address_proof_file' => 'required_if:is_address_same,no|nullable|mimes:jpg,jpeg,png,pdf|max:2048',

            // Dynamic Additional Documents
            'documents' => 'nullable|array',
            'documents.*.name' => 'required_with:documents|string|max:255',
            'documents.*.number' => 'nullable|string|max:100',
            'documents.*.file' => 'required_with:documents.*.name|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        $validated = $request->validate($validationRules, $messages);



        $validated['height'] = null;
        if ($request->height_unit === 'ft_in' && ($request->filled('height_feet') || $request->filled('height_inches'))) {
            $validated['height'] = ((float) $request->height_feet * 30.48)
                + ((float) $request->height_inches * 2.54);
        } elseif ($request->filled('height_cm')) {
            $validated['height'] = (float) $request->height_cm;
        }

        $validated['weight'] = null;
        if ($request->filled('weight_input')) {
            $validated['weight'] = $request->weight_unit === 'lbs'
                ? (float) $request->weight_input * 0.45359237
                : (float) $request->weight_input;
        }

        // Conditional validation for address proof
        if ($request->is_address_same == 'no') {
            $request->validate([
                'address_proof_file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
                'address_proof_number' => 'required|string|max:50',
            ], $messages);
        }

        DB::beginTransaction();
        // try {
        // ✅ Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // ✅ Check if user has institute access
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // ✅ Get designation details to get the associated role
        $designation = Designations::where('designation_id', $validated['designation_id'])->first();
        if (!$designation) {
            return redirect()->back()->with('error', 'Selected designation not found.');
        }

        // ✅ Get the role from designation's roles column
        $designationRole = $designation->roles;
        $assignedRole = !empty($designationRole) ? $designationRole : 'employee';
        $validated['assigned_role'] = $assignedRole;

        // ✅ Generate Employee ID
        $validated['employee_id'] = 'EMP' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        // ✅ Handle Fixed File Uploads
        $fixedFiles = ['profile_photo', 'upload_signature', 'aadhaar_card', 'pan_card', 'driving_license', 'passport_photo', 'address_proof_file'];
        foreach ($fixedFiles as $file) {
            if ($request->hasFile($file)) {
                $validated[$file] = $request->file($file)->store('employee_documents', 'public');
            }
        }

        // ✅ Handle Dynamic Additional Documents
        $additionalDocuments = [];
        if ($request->has('documents')) {
            foreach ($request->file('documents') as $index => $documentData) {
                if (isset($documentData['file']) && $documentData['file']->isValid()) {
                    $fileName = $documentData['file']->store('employee_additional_documents', 'public');

                    $additionalDocuments[] = [
                        'name' => $request->input("documents.{$index}.name"),
                        'number' => $request->input("documents.{$index}.number"),
                        'file_path' => $fileName,
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
        }

        // Add additional_documents to validated data as JSON
        if (!empty($additionalDocuments)) {
            $validated['additional_documents'] = $additionalDocuments;
        }
        unset($validated['documents']);

        // ✅ Use createWithInstituteBranchContext properly
        $employeeData = array_merge(
            $this->createWithInstituteBranchContext([]),
            $validated
        );

        // ✅ Save into DB with institute/branch context
        $employee = EmployeeDetails::create($employeeData);

        // ✅ Create User Credentials with designated role
        $user = null;
        if (!empty($validated['email'])) {
            // Check if email notification is enabled for employee_on_board module
            $emailEnabled = $this->isNotificationEnabled(
                $context['institute_id'],
                'employee_on_board',
                'email'
            );

            $whatsappEnabled = $this->isNotificationEnabled(
                $context['institute_id'],
                'employee_on_board',
                'whatsapp'
            );

            $smsEnabled = $this->isNotificationEnabled(
                $context['institute_id'],
                'employee_on_board',
                'sms'
            );

            // Create user account with notification preferences
            $user = $this->createEmployeeUserAccount(
                $employee,
                $assignedRole,
                $emailEnabled,
                $whatsappEnabled,
                $smsEnabled
            );
        }

        // ✅ Send notifications to institute admins (this already uses your trait)
        $this->notifyInstituteAdmins(
            $context['institute_id'],
            new EmployeeOnboardedNotification($employee, 'added')
        );

        DB::commit();

        // ✅ Show appropriate success message
        $docCount = count($additionalDocuments);
        $docMessage = $docCount > 0 ? " with {$docCount} additional document(s)" : "";

        $notificationStatus = [];
        if (!empty($validated['email'])) {
            if ($emailEnabled) {
                $notificationStatus[] = "Welcome email sent to employee";
            } else {
                $notificationStatus[] = "Email notifications are disabled for employee onboarding";
            }
        }

        $statusMessage = !empty($notificationStatus) ? " " . implode(". ", $notificationStatus) : "";

        $message = $context['is_branch_admin']
            ? "Employee added successfully for your branch! Employee ID: {$validated['employee_id']} with role: {$assignedRole}{$docMessage}{$statusMessage}"
            : "Employee added successfully for the institute! Employee ID: {$validated['employee_id']} with role: {$assignedRole}{$docMessage}{$statusMessage}";

        return redirect()
            ->route('employees.index')
            ->with('success', $message);

        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     \Log::error('Employee creation error: ' . $e->getMessage());

        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', 'Failed to add employee: ' . $e->getMessage());
        // }
    }

    private function createEmployeeUserAccount(EmployeeDetails $employee, $role, $sendEmail = true, $sendWhatsapp = false, $sendSms = false)
    {
        $tempPassword = '12345678';
        $userData = [
            'name' => $employee->name,
            'email' => isset($employee->email) ? $employee->email : null,
            'phone' => $employee->mobile_number,
            'password' => Hash::make($tempPassword),
            'email_verified_at' => now(),
        ];
        $userData = $this->createWithInstituteBranchContext($userData);
        $user = User::create($userData);

        try {
            $user->assignRole($role);
        } catch (\Exception $e) {
            \Log::warning("Role '{$role}' not found for user {$user->id}, assigning 'employee' as fallback");
            $user->assignRole('employee'); // Fallback role
        }

        $employee->update(['user_id' => $user->id]);

        // ✅ Get additional details for email template
        $departmentName = 'N/A';
        if ($employee->department_id) {
            $department = Departments::where('department_id', $employee->department_id)->first();
            $departmentName = $department ? $department->department : 'N/A';
        }

        $designationName = $employee->designation ?? $role;
        $dateOfJoining = $employee->doj ? Carbon::parse($employee->doj)->format('d-m-Y') : 'N/A';

        // ✅ Send notifications based on settings
        if ($sendEmail && !empty($employee->email)) {
            try {
                // Send welcome email to employee with all details
                Mail::send('emails.employee-welcome', [
                    'employee' => $employee,
                    'password' => $tempPassword,
                    'role' => $role,
                    'departmentName' => $departmentName,
                    'designationName' => $designationName,
                    'dateOfJoining' => $dateOfJoining,
                    'reportingManager' => $this->getReportingManager($employee->department_id),
                    'workLocation' => $employee->city ?? $employee->state ?? 'N/A'
                ], function ($message) use ($employee) {
                    $message->to($employee->email)
                        ->subject('Welcome - Your Employee Account Details');
                });

                \Log::info('Welcome email sent to employee: ' . $employee->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email: ' . $e->getMessage());
            }
        }

        // ✅ Send WhatsApp notification if enabled
        if ($sendWhatsapp && !empty($employee->mobile_number)) {
            try {
                // Format WhatsApp message
                $whatsappMessage = "Welcome {$employee->name}!\n\n";
                $whatsappMessage .= "Your account has been created:\n";
                $whatsappMessage .= "Employee Code: {$employee->employee_code}\n";
                $whatsappMessage .= "Department: {$departmentName}\n";
                $whatsappMessage .= "Designation: {$designationName}\n";
                $whatsappMessage .= "DOJ: {$dateOfJoining}\n\n";
                $whatsappMessage .= "Email: {$employee->email}\n";
                $whatsappMessage .= "Password: {$tempPassword}\n\n";
                $whatsappMessage .= "Please login and change your password.\n";
                $whatsappMessage .= "Login URL: " . url('/login');

                // Integrate with your WhatsApp API
                // Example: $this->sendWhatsAppMessage($employee->mobile_number, $whatsappMessage);
                \Log::info('WhatsApp notification would be sent to: ' . $employee->mobile_number);
            } catch (\Exception $e) {
                \Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            }
        }

        // ✅ Send SMS notification if enabled
        if ($sendSms && !empty($employee->mobile_number)) {
            try {
                // Format SMS message (shorter version)
                $smsMessage = "Welcome {$employee->name}! Your account is ready. ";
                $smsMessage .= "Email: {$employee->email}, Password: {$tempPassword}. ";
                $smsMessage .= "Login: " . url('/login') . " Please change password after login.";

                // Integrate with your SMS provider
                // Example: $this->sendSms($employee->mobile_number, $smsMessage);
                \Log::info('SMS notification would be sent to: ' . $employee->mobile_number);
            } catch (\Exception $e) {
                \Log::error('Failed to send SMS notification: ' . $e->getMessage());
            }
        }

        return $user;
    }

    /**
     * Get reporting manager for a department
     */
    private function getReportingManager($departmentId)
    {
        try {
            // You can customize this logic based on your requirements
            // For example, get the HOD or manager of the department
            $manager = EmployeeDetails::where('department_id', $departmentId)
                ->where('designation', 'like', '%Manager%')
                ->orWhere('designation', 'like', '%HOD%')
                ->orWhere('designation', 'like', '%Head%')
                ->first();

            return $manager ? $manager->name : 'To be assigned';
        } catch (\Exception $e) {
            return 'To be assigned';
        }
    }


    // public function getemployee(Request $request)
    // {
    //     $merchantId = auth()->user()->institute_id;
    //     $context = $this->getInstituteBranchContext();
    //     $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
    //         ->where('status', '!=', 'inactive')
    //         ->with(['stakeholders','stakeholderDocuments','authorizedUser','authorizedUserDocuments', 'documents','beneficiary'])
    //         ->first();

    //     $categories = $this->getCommonQuery(DepartmentCategory::class)
    //         ->withCount('departments')
    //         ->orderBy('category_name')
    //         ->get();

    //     $departments = $this->getCommonQuery(Departments::class)
    //         ->with('category')
    //         ->orderBy('department')
    //         ->get();

    //     $designations = $this->getCommonQuery(Designations::class)
    //         ->with('departmentCategory')
    //         ->where('status', 'active')
    //         ->orderBy('designations')
    //         ->get();

    //     // Load dropdown data
    //     $employeeCodes = EmployeeDetails::where('institute_id', $merchantId)->select('employee_code')->distinct()->get();
    //     $employeeNames = EmployeeDetails::where('institute_id', $merchantId)->select('name')->distinct()->get();

    //     // Build employee query with join to departments
    //     $employeesQuery = EmployeeDetails::select(
    //         'employee_details.*',
    //         'departments.department as department_name'
    //     )->where('employee_details.institute_id', $merchantId)
    //     ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id');

    //     $selectedDeptName = null;
    //     if ($request->filled('department_id')) {
    //         $dept = Departments::find($request->department_id);
    //         $selectedDeptName = $dept ? $dept->department : null;
    //     }

    //     // Apply filters
    //     if ($request->filled('department_id')) {
    //         $employeesQuery->where('employee_details.department_id', $request->department_id);
    //     }

    //     if ($request->filled('designation')) {
    //         $employeesQuery->where('employee_details.designation', $request->designation);
    //     }

    //     if ($request->filled('email')) {
    //         $employeesQuery->where('employee_details.email', $request->email);
    //     }

    //     if ($request->filled('employee_code')) {
    //         $employeesQuery->where('employee_details.employee_code', 'LIKE', '%' . $request->employee_code . '%');
    //     }

    //     if ($request->filled('name')) {
    //         $employeesQuery->where('employee_details.name', 'LIKE', '%' . $request->name . '%');
    //     }

    //     // Add filter for probation status
    //     if ($request->filled('employment_filter')) {

    //         switch ($request->employment_filter) {

    //             case 'full_time':
    //                 $employeesQuery->where(
    //                     'employee_details.employment_type',
    //                     'Full-Time'
    //                 );
    //                 break;

    //             case 'part_time':
    //                 $employeesQuery->where(
    //                     'employee_details.employment_type',
    //                     'Part-Time'
    //                 );
    //                 break;

    //             case 'contract':
    //                 $employeesQuery->where(
    //                     'employee_details.employment_type',
    //                     'Contract-Based'
    //                 );
    //                 break;

    //             case 'probation_active':
    //                 $employeesQuery->where(
    //                     'employee_details.employment_type',
    //                     'Probation-Period'
    //                 )
    //                 ->whereRaw(
    //                     'DATE_ADD(doj, INTERVAL probation_days DAY) > CURDATE()'
    //                 );
    //                 break;

    //             case 'probation_today':
    //                 $employeesQuery->where(
    //                     'employee_details.employment_type',
    //                     'Probation-Period'
    //                 )
    //                 ->whereRaw(
    //                     'DATE_ADD(doj, INTERVAL probation_days DAY) = CURDATE()'
    //                 );
    //                 break;

    //             case 'probation_overdue':
    //                 $employeesQuery->where(
    //                     'employee_details.employment_type',
    //                     'Probation-Period'
    //                 )
    //                 ->whereRaw(
    //                     'DATE_ADD(doj, INTERVAL probation_days DAY) < CURDATE()'
    //                 );
    //                 break;
    //         }
    //     }

    //     $employees = $employeesQuery->latest('employee_details.created_at')->paginate(15);

    //     // Calculate probation data for each employee
    //     foreach ($employees as $employee) {
    //         $employee->probation_status = $employee->getProbationStatusAttribute();
    //         $employee->probation_days_delta = $employee->getProbationDaysDeltaAttribute();
    //         $employee->probation_end_date = $employee->getProbationEndDateAttribute();
    //     }

    //     return view('instituteAdmin.EmployeeFiles.AddFaculity', [
    //         'categories'   => $categories,
    //         'departments'  => $departments,
    //         'designations' => $designations,
    //         'employees'    => $employees,
    //         'employeeCodes'  => $employeeCodes,
    //         'employeeNames'  => $employeeNames,
    //         'fincapMerchants' => $fincapMerchants,
    //         'selectedDeptName' => $selectedDeptName,
    //         'user_type'    => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
    //     ]);
    // }

    public function getemployee(Request $request)
    {
        // Add this debug line temporarily to see what's being passed
        // dd($request->all(), $request->filled('status'));

        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->with(['stakeholders', 'stakeholderDocuments', 'authorizedUser', 'authorizedUserDocuments', 'documents', 'beneficiary'])
            ->first();

        $categories = $this->getCommonQuery(DepartmentCategory::class)
            ->withCount('departments')
            ->orderBy('category_name')
            ->get();

        $departments = $this->getCommonQuery(Departments::class)
            ->with('category')
            ->orderBy('department')
            ->get();

        $designations = $this->getCommonQuery(Designations::class)
            ->with('departmentCategory')
            ->where('status', 'active')
            ->orderBy('designations')
            ->get();

        // Load dropdown data
        $employeeCodes = EmployeeDetails::where('institute_id', $merchantId)->select('employee_code')->distinct()->get();
        $employeeNames = EmployeeDetails::where('institute_id', $merchantId)->select('name')->distinct()->get();

        // Build employee query with join to departments
        $employeesQuery = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name'
        )->where('employee_details.institute_id', $merchantId)
            ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id');

        $selectedDeptName = null;
        if ($request->filled('department_id')) {
            $dept = Departments::find($request->department_id);
            $selectedDeptName = $dept ? $dept->department : null;
        }

        // Apply filters
        if ($request->filled('department_id')) {
            $employeesQuery->where('employee_details.department_id', $request->department_id);
        }

        if ($request->filled('designation')) {
            $employeesQuery->where('employee_details.designation', $request->designation);
        }

        if ($request->filled('email')) {
            $employeesQuery->where('employee_details.email', $request->email);
        }

        if ($request->filled('employee_code')) {
            $employeesQuery->where('employee_details.employee_code', 'LIKE', '%' . $request->employee_code . '%');
        }

        if ($request->filled('name')) {
            $employeesQuery->where('employee_details.name', 'LIKE', '%' . $request->name . '%');
        }

        // ✅ FIXED: Status filter - SIMPLIFIED
        $status = $request->input('status');

        if ($status && $status !== '') {
            // Status is explicitly selected
            if ($status === 'active') {
                $employeesQuery->where('employee_details.status', 'active');
            } elseif ($status === 'inactive') {
                $employeesQuery->where('employee_details.status', 'inactive');
            } elseif ($status === 'exited') {
                $employeesQuery->where('employee_details.status', 'exited');
            }
            // If 'all' is selected, don't apply any status filter
        } else {
            // Default: Only show active employees when no status is selected
            $employeesQuery->where('employee_details.status', 'active')->orwhere('employee_details.status', 'inactive');
        }

        // Add filter for probation status
        if ($request->filled('employment_filter')) {
            switch ($request->employment_filter) {
                case 'full_time':
                    $employeesQuery->where('employee_details.employment_type', 'Full-Time');
                    break;
                case 'part_time':
                    $employeesQuery->where('employee_details.employment_type', 'Part-Time');
                    break;
                case 'contract':
                    $employeesQuery->where('employee_details.employment_type', 'Contract-Based');
                    break;
                case 'probation_active':
                    $employeesQuery->where('employee_details.employment_type', 'Probation-Period')
                        ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) > CURDATE()');
                    break;
                case 'probation_today':
                    $employeesQuery->where('employee_details.employment_type', 'Probation-Period')
                        ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) = CURDATE()');
                    break;
                case 'probation_overdue':
                    $employeesQuery->where('employee_details.employment_type', 'Probation-Period')
                        ->whereRaw('DATE_ADD(doj, INTERVAL probation_days DAY) < CURDATE()');
                    break;
            }
        }

        // Eager load activeExit relationship
        $employees = $employeesQuery->with([
            'activeExit' => function ($query) {
                $query->whereIn('exit_status', ['pending_approval', 'notice_period', 'exited', 'approved']);
            }
        ])->latest('employee_details.created_at')->paginate(15);

        // Calculate probation data for each employee
        foreach ($employees as $employee) {
            $employee->probation_status = $employee->getProbationStatusAttribute();
            $employee->probation_days_delta = $employee->getProbationDaysDeltaAttribute();
            $employee->probation_end_date = $employee->getProbationEndDateAttribute();
            $employee->exit_status_display = $this->getExitStatusDisplay($employee);
        }

        return view('instituteAdmin.EmployeeFiles.AddFaculity', [
            'categories' => $categories,
            'departments' => $departments,
            'designations' => $designations,
            'employees' => $employees,
            'employeeCodes' => $employeeCodes,
            'employeeNames' => $employeeNames,
            'fincapMerchants' => $fincapMerchants,
            'selectedDeptName' => $selectedDeptName,
            'user_type' => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
        ]);
    }

    /**
     * Get exit status display for employee
     */
    private function getExitStatusDisplay($employee)
    {
        $exit = $employee->activeExit;

        if (!$exit) {
            return [
                'has_exit' => false,
                'status' => null,
                'label' => 'No Exit',
                'color' => 'secondary',
                'is_active' => false,
                'is_exited' => false,
                'is_pending' => false,
                'is_notice_period' => false,
                'notice_end_date' => null,
                'actual_exit_date' => null,  // ✅ Add this
                'is_overdue' => false,
                'exit_id' => null
            ];
        }

        return [
            'has_exit' => true,
            'status' => $exit->exit_status,
            'label' => $exit->status_label,
            'color' => $exit->status_color,
            'is_active' => in_array($exit->exit_status, ['pending_approval', 'notice_period']),
            'is_exited' => $exit->exit_status === 'exited',
            'is_pending' => $exit->exit_status === 'pending_approval',
            'is_notice_period' => $exit->exit_status === 'notice_period',
            'notice_end_date' => $exit->notice_end_date,
            'actual_exit_date' => $exit->actual_exit_date,  // ✅ Add this
            'is_overdue' => $exit->is_overdue,
            'exit_id' => $exit->id
        ];
    }

    /**
     * Get Employee Details By Id
     */
    public function getEmployeeDetailsbyID($id)
    {
        $employee = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name',
            'designations.designations as designation_name'
        )
            ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.designation_id')
            ->where(function ($q) use ($id) {
                $q->where('employee_details.id', $id)
                    ->orWhere('employee_details.employee_id', $id);
            })
            ->firstOrFail();

        $empCode = $employee->employee_id;
        $empDbId = $employee->id;
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;

        // 1. Salary Structure & Slips
        $salaryStructure = \App\Models\EmployeeSalaryStructure::where('institute_id', $instituteId)
            ->where(function ($q) use ($empCode, $empDbId) {
                if ($empCode)
                    $q->where('employee_id', $empCode);
                if ($empDbId)
                    $q->orWhere('employee_id', $empDbId);
            })
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->with(['allowances', 'deductions', 'bonuses', 'overtime'])
            ->where('is_active', 1)
            ->latest()
            ->first();

        if (!$salaryStructure) {
            $salaryStructure = \App\Models\EmployeeSalaryStructure::where('institute_id', $instituteId)
                ->where(function ($q) use ($empCode, $empDbId) {
                    if ($empCode)
                        $q->where('employee_id', $empCode);
                    if ($empDbId)
                        $q->orWhere('employee_id', $empDbId);
                })
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->with(['allowances', 'deductions', 'bonuses', 'overtime'])
                ->latest()
                ->first();
        }

        $salaryDetails = [
            'basic_salary' => 0,
            'hra' => 0,
            'da' => 0,
            'special_allowance' => 0,
            'other_allowances' => 0,
            'pf_deduction' => 0,
            'pt_deduction' => 0,
            'tds_deduction' => 0,
            'monthly_fixed' => 0,
            'net_salary' => 0,
            'total_deductions' => 0,
        ];

        if ($salaryStructure) {
            $allowances = $salaryStructure->allowances;
            $deductions = $salaryStructure->deductions;
            $basicSalary = (float) ($salaryStructure->basic_salary_monthly
                ?? $salaryStructure->basic_salary_amount
                ?? ((float) ($salaryStructure->basic_salary_annual ?? 0) / 12));

            $monthlyAllowance = function ($name) use ($allowances, $basicSalary) {
                if (!$allowances) {
                    return 0;
                }

                $monthly = $allowances->getAttribute($name . '_value_monthly');
                if ($monthly !== null && $monthly !== '') {
                    return (float) $monthly;
                }

                return (float) ($allowances->getAttribute($name . '_value') ?? 0);
            };

            $hra = $monthlyAllowance('hra');
            $da = $monthlyAllowance('da');
            $special = $monthlyAllowance('special');
            $otherAllowanceNames = ['conveyance', 'medical', 'lta', 'education'];
            $otherAllowances = 0;
            foreach ($otherAllowanceNames as $allowanceName) {
                $otherAllowances += $monthlyAllowance($allowanceName);
            }

            if ($allowances && is_array($allowances->custom_allowances)) {
                foreach ($allowances->custom_allowances as $customAllowance) {
                    $monthly = $customAllowance['value_monthly'] ?? null;
                    $otherAllowances += $monthly !== null
                        ? (float) $monthly
                        : (float) ($customAllowance['value'] ?? 0);
                }
            }

            $deductionValue = function ($name) use ($deductions) {
                if (!$deductions) {
                    return 0;
                }
                $monthly = $deductions->getAttribute($name . '_value_monthly');
                return (float) ($monthly !== null && $monthly !== ''
                    ? $monthly
                    : ($deductions->getAttribute($name . '_value') ?? 0));
            };

            $monthlyFixed = (float) ($salaryStructure->monthly_fixed ?? 0);
            if ($monthlyFixed <= 0) {
                $monthlyFixed = $basicSalary + $hra + $da + $special + $otherAllowances;
            }

            $pf = (float) ($employee->pf_deduction ?? 0);
            $pt = $deductionValue('pt');
            $tds = $deductionValue('tds');
            $netSalary = $monthlyFixed - $pf - $pt - $tds;

            $salaryDetails = [
                'basic_salary' => $basicSalary,
                'hra' => $hra,
                'da' => $da,
                'special_allowance' => $special,
                'other_allowances' => $otherAllowances,
                'pf_deduction' => $pf,
                'pt_deduction' => $pt,
                'tds_deduction' => $tds,
                'monthly_fixed' => $monthlyFixed,
                'net_salary' => $netSalary,
                'earnings_breakdown' => [],
                'standard_deductions' => [],
                'attendance_deductions' => [],
                'other_deductions' => [],
            ];
        }

        $structureSalaryDetails = $salaryDetails;

        $finalSalarySlips = \App\Models\FinalSalarySlip::where('institute_id', $instituteId)
            ->where(function ($q) use ($empCode, $empDbId) {
                $q->where('employee_id', $empCode)
                    ->orWhere('employee_id', $empDbId);
            })
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $latestFinalSalarySlip = $finalSalarySlips->first();
        if ($latestFinalSalarySlip) {
            $earnings = is_array($latestFinalSalarySlip->earnings_breakdown)
                ? $latestFinalSalarySlip->earnings_breakdown
                : [];
            $deductions = is_array($latestFinalSalarySlip->standard_deductions)
                ? $latestFinalSalarySlip->standard_deductions
                : [];

            $breakdownValue = function (array $data, array $keys) {
                foreach ($keys as $key) {
                    if (array_key_exists($key, $data) && is_numeric($data[$key])) {
                        return (float) $data[$key];
                    }
                }
                return 0;
            };

            $basicSalary = (float) ($latestFinalSalarySlip->basic_salary ?? 0);
            $grossSalary = (float) ($latestFinalSalarySlip->gross_salary ?? 0);
            $hra = $breakdownValue($earnings, ['hra', 'HRA', 'HRA Allowance']);
            $da = $breakdownValue($earnings, ['da', 'DA', 'Dearness Allowance']);
            $special = $breakdownValue($earnings, ['special_allowance', 'special', 'Special Allowance']);
            $otherAllowances = max(0, $grossSalary - $basicSalary - $hra - $da - $special);
            $totalDeductions = (float) ($latestFinalSalarySlip->total_deductions
                ?? array_sum(array_map('floatval', $deductions)));

            $salaryDetails = [
                'basic_salary' => $basicSalary,
                'hra' => $hra,
                'da' => $da,
                'special_allowance' => $special,
                'other_allowances' => $otherAllowances,
                'pf_deduction' => $breakdownValue($deductions, ['pf', 'PF', 'pf_deduction']),
                'pt_deduction' => $breakdownValue($deductions, ['pt', 'PT', 'professional_tax']),
                'tds_deduction' => $breakdownValue($deductions, ['tds', 'TDS', 'tds_deduction']),
                'monthly_fixed' => $grossSalary,
                'net_salary' => (float) ($latestFinalSalarySlip->final_payable
                    ?? $latestFinalSalarySlip->net_salary
                    ?? ($grossSalary - $totalDeductions)),
                'total_deductions' => $totalDeductions,
                'earnings_breakdown' => $earnings,
                'standard_deductions' => $deductions,
                'attendance_deductions' => is_array($latestFinalSalarySlip->attendance_deductions)
                    ? $latestFinalSalarySlip->attendance_deductions
                    : [],
                'other_deductions' => is_array($latestFinalSalarySlip->other_deductions)
                    ? $latestFinalSalarySlip->other_deductions
                    : [],
            ];
        }

        $slipsByMonthYear = [];
        foreach ($finalSalarySlips as $slip) {
            $mPadded = str_pad($slip->month, 2, '0', STR_PAD_LEFT);
            $key = $slip->year . '-' . $mPadded;
            $slipsByMonthYear[$key] = $slip;
        }

        // 2. Leaves & Balances
        $leaveHistory = \App\Models\EmployeeLeave::where(function ($q) use ($empCode, $empDbId) {
            if ($empCode)
                $q->where('employee_id', $empCode);
            if ($empDbId)
                $q->orWhere('employee_id', $empDbId);
        })
            ->with('approvals')
            ->latest()
            ->get();

        $leaveBalance = \App\Models\EmployeeLeaveBalance::where(function ($q) use ($empCode, $empDbId) {
            if ($empCode)
                $q->where('employee_id', $empCode);
            if ($empDbId)
                $q->orWhere('employee_id', $empDbId);
        })
            ->first();

        // 3. Shifts
        $employeeShifts = \App\Models\EmployeeShift::where(function ($q) use ($empCode, $empDbId) {
            if ($empCode)
                $q->where('employee_id', $empCode);
            if ($empDbId)
                $q->orWhere('employee_id', $empDbId);
        })
            ->with('shift')
            ->latest()
            ->get();

        $effectiveShift = null;
        try {
            $effectiveShift = $employee->resolveEffectiveShift();
        } catch (\Throwable $e) {
            $effectiveShift = null;
        }

        if (!$effectiveShift && $employee->shift_id) {
            $shiftObj = \App\Models\Shifts::find($employee->shift_id);
            if ($shiftObj) {
                $effectiveShift = ['type' => 'individual', 'shift' => $shiftObj];
            }
        }

        // 4. Timetable / Lectures - Get ALL events (lectures + duties)
        $today = Carbon::today();
        $todayDate = $today->format('Y-m-d');

        $allEvents = collect();

        // 4a. Get lectures directly assigned to this employee
        $directLectures = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->leftJoin('subjects_coursewise as s', 'ase.subject_id', '=', 's.subject_id')
            ->leftJoin('sub_subjects as ss', function ($join) {
                $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                    ->on('ase.subject_id', '=', 'ss.subject_id');
            })
            ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->leftJoin('employee_details as reassigned_to_emp', 'esl.reassigned_to_employee_id', '=', 'reassigned_to_emp.employee_id')
            ->leftJoin('employee_details as reassigned_from_emp', 'esl.reassigned_from_employee_id', '=', 'reassigned_from_emp.employee_id')
            ->select(
                'esl.*',
                'ase.emp_assign_subject_id as ase_emp_assign_subject_id',
                'ase.subject_display_name',
                'ase.subject_type as ase_subject_type',
                'ase.semester_id',
                'ase.assigned_date',
                'ase.remarks as assignment_remarks',
                'ase.status as assignment_status',
                'ase.department_id as ase_department_id',
                'ase.section_id as ase_section_id',
                'ase.course_detail_id',
                'ase.branch_id as ase_branch_id',
                'ase.employee_id as assigned_employee_id',
                's.subject_name as main_subject_name',
                'ss.sub_subject_name',
                'd.department as department_name',
                'pd.course_type',
                'pd.sub_type as branch_name',
                'reassigned_to_emp.name as reassigned_to_employee_name',
                'reassigned_from_emp.name as reassigned_from_employee_name',
                DB::raw('TIME(esl.start_time) as start_time_only'),
                DB::raw('TIME(esl.end_time) as end_time_only'),
                DB::raw('TIMEDIFF(esl.end_time, esl.start_time) as duration'),
                DB::raw("'lecture' as event_type")
            )
            ->where(function ($q) use ($empCode, $empDbId) {
                $q->where('ase.employee_id', $empCode)
                    ->orWhere('ase.employee_id', $empDbId);
            })
            ->where('ase.institute_id', $instituteId)
            ->where('ase.status', 'Active')
            ->where('esl.status', 'active')
            ->where('esl.is_cancelled', 0)
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('ase.branch_id', $branchId);
            })
            ->get();

        $allEvents = $allEvents->merge($directLectures);

        // 4b. Get lectures reassigned TO this employee
        $reassignedToMe = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->leftJoin('subjects_coursewise as s', 'ase.subject_id', '=', 's.subject_id')
            ->leftJoin('sub_subjects as ss', function ($join) {
                $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                    ->on('ase.subject_id', '=', 'ss.subject_id');
            })
            ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->leftJoin('employee_details as reassigned_from_emp', 'esl.reassigned_from_employee_id', '=', 'reassigned_from_emp.employee_id')
            ->select(
                'esl.*',
                'ase.emp_assign_subject_id as ase_emp_assign_subject_id',
                'ase.subject_display_name',
                'ase.subject_type as ase_subject_type',
                'ase.semester_id',
                'ase.assigned_date',
                'ase.remarks as assignment_remarks',
                'ase.status as assignment_status',
                'ase.department_id as ase_department_id',
                'ase.section_id as ase_section_id',
                'ase.course_detail_id',
                'ase.branch_id as ase_branch_id',
                'ase.employee_id as assigned_employee_id',
                's.subject_name as main_subject_name',
                'ss.sub_subject_name',
                'd.department as department_name',
                'pd.course_type',
                'pd.sub_type as branch_name',
                'reassigned_from_emp.name as reassigned_from_employee_name',
                DB::raw('TIME(esl.start_time) as start_time_only'),
                DB::raw('TIME(esl.end_time) as end_time_only'),
                DB::raw('TIMEDIFF(esl.end_time, esl.start_time) as duration'),
                DB::raw("'lecture' as event_type")
            )
            ->where(function ($q) use ($empCode, $empDbId) {
                $q->where('esl.reassigned_to_employee_id', $empCode)
                    ->orWhere('esl.reassigned_to_employee_id', $empDbId);
            })
            ->where('esl.status', 'active')
            ->where('esl.is_cancelled', 0)
            ->where('esl.frequency', 'one_time')
            ->where(function ($query) use ($todayDate) {
                $query->whereNull('esl.valid_to')
                    ->orWhere('esl.valid_to', '>=', $todayDate);
            })
            ->where('ase.institute_id', $instituteId)
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('ase.branch_id', $branchId);
            })
            ->get();

        // Filter out duplicates - if a lecture is already in allEvents, don't add it again
        $existingIds = $allEvents->pluck('id')->toArray();
        $filteredReassigned = $reassignedToMe->filter(function ($lecture) use ($existingIds) {
            return !in_array($lecture->id, $existingIds);
        });

        $allEvents = $allEvents->merge($filteredReassigned);

        // 4c. Process lectures - Keep as single entries, don't expand
        $processedLectures = collect();
        $seenLectureIds = [];

        foreach ($allEvents as $lecture) {
            if (empty($lecture->valid_from)) {
                continue;
            }

            $lectureKey = $lecture->id . '_' . ($lecture->emp_assign_subject_id ?? $lecture->ase_emp_assign_subject_id ?? '');
            if (isset($seenLectureIds[$lectureKey])) {
                continue;
            }
            $seenLectureIds[$lectureKey] = true;

            $start = Carbon::parse($lecture->valid_from);
            $end = $lecture->valid_to ? Carbon::parse($lecture->valid_to) : null;

            $maxEnd = Carbon::today()->addDays(90);
            if ($end && $end->gt($maxEnd)) {
                $end = $maxEnd;
            }

            $override = \App\Models\LectureModeOverride::where('lecture_id', $lecture->id)
                ->where('override_date', $start->format('Y-m-d'))
                ->where('institute_id', $instituteId)
                ->where('is_active', true)
                ->first();

            $lectureCopy = clone $lecture;
            $lectureCopy->has_override = false;
            $lectureCopy->lecture_mode = $lecture->lecture_mode ?? 'offline';
            $lectureCopy->original_frequency = $lecture->frequency;
            $lectureCopy->valid_from = $start->format('Y-m-d');
            $lectureCopy->valid_to = $end ? $end->format('Y-m-d') : null;

            $lectureCopy->emp_assign_subject_id = $lecture->emp_assign_subject_id ?? $lecture->ase_emp_assign_subject_id ?? null;
            $lectureCopy->department_id = $lecture->department_id ?? $lecture->ase_department_id ?? null;
            $lectureCopy->section_id = $lecture->section_id ?? $lecture->ase_section_id ?? null;
            $lectureCopy->branch_id = $lecture->branch_id ?? $lecture->ase_branch_id ?? null;
            $lectureCopy->subject_type = $lecture->subject_type ?? $lecture->ase_subject_type ?? 'main_subject';

            if ($override) {
                $lectureCopy->lecture_mode = $override->lecture_mode;
                $lectureCopy->meeting_link = $override->meeting_link;
                $lectureCopy->meeting_password = $override->meeting_password;
                $lectureCopy->meeting_id = $override->meeting_id;
                $lectureCopy->meeting_instructions = $override->meeting_instructions;
                $lectureCopy->has_override = true;
                $lectureCopy->override_id = $override->id;
                $lectureCopy->override_reason = $override->remarks;

                if ($override->location_override) {
                    $lectureCopy->location = $override->location_override;
                }
                if ($override->room_override) {
                    $lectureCopy->room = $override->room_override;
                }
            }

            $processedLectures->push($lectureCopy);
        }

        $allEvents = $processedLectures;

        // 4d. Get duties assigned to this employee
        $priorityColors = [
            'low' => 'duty-low',
            'medium' => 'duty-medium',
            'high' => 'duty-high',
            'urgent' => 'duty-urgent'
        ];

        $dutyRecords = \App\Models\AssignDuties::with(['employee', 'dutyType'])
            ->where('institute_id', $instituteId)
            ->where(function ($q) use ($empCode, $empDbId) {
                if ($empCode)
                    $q->where('employee_id', $empCode);
                if ($empDbId)
                    $q->orWhere('employee_id', $empDbId);
            })
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'completed')
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->get();

        $dutyEvents = collect();
        $seenDutyIds = [];

        foreach ($dutyRecords as $duty) {
            $dutyKey = $duty->id . '_' . ($duty->date ?? $duty->from_date ?? '');
            if (isset($seenDutyIds[$dutyKey])) {
                continue;
            }
            $seenDutyIds[$dutyKey] = true;

            $departmentName = null;
            if ($duty->department_id) {
                $department = DB::table('departments')->where('department_id', $duty->department_id)->first();
                $departmentName = $department->department ?? null;
            }

            // Parse days_of_week properly - handle double-encoded JSON
            $daysOfWeek = [];
            if ($duty->days_of_week) {
                if (is_string($duty->days_of_week)) {
                    // Try to decode once
                    $decoded = json_decode($duty->days_of_week, true);
                    if (is_array($decoded)) {
                        $daysOfWeek = $decoded;
                    } elseif (is_string($decoded)) {
                        // Try to decode again (for double-encoded JSON)
                        $decodedAgain = json_decode($decoded, true);
                        if (is_array($decodedAgain)) {
                            $daysOfWeek = $decodedAgain;
                        }
                    }
                } elseif (is_array($duty->days_of_week)) {
                    $daysOfWeek = $duty->days_of_week;
                }
            }

            $startTime = date('H:i:s', strtotime($duty->start_time));
            $endTime = date('H:i:s', strtotime($duty->end_time));

            // For one-time duties
            if ($duty->frequency === 'once' && $duty->date) {
                $dutyDate = Carbon::parse($duty->date);

                $dutyEvents->push((object) [
                    'id' => $duty->id,
                    'event_type' => 'duty',
                    'subject_display_name' => $duty->title ?? $duty->duty_type ?? 'Duty',
                    'subject_type' => 'duty',
                    'main_subject_name' => $duty->duty_type,
                    'sub_subject_name' => null,
                    'department_name' => $departmentName,
                    'department_id' => $duty->department_id,
                    'course_type' => null,
                    'branch_name' => null,
                    'section_name' => null,
                    'semester_id' => null,
                    'frequency' => $duty->frequency,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'valid_from' => $dutyDate->format('Y-m-d'),
                    'valid_to' => $dutyDate->format('Y-m-d'),
                    'days_of_week' => $daysOfWeek,
                    'day_of_month' => $duty->day_of_month,
                    'location' => $duty->location,
                    'venue' => $duty->venue,
                    'duration' => null,
                    'status' => $duty->status,
                    'remarks' => $duty->description,
                    'instructions' => $duty->instructions,
                    'is_reassigned' => false,
                    'priority' => $duty->priority,
                    'duty_type' => $duty->duty_type,
                    'employee_duty_id' => $duty->employee_duty_id,
                    'employee_name' => $duty->employee->name ?? null,
                    'designation' => $duty->employee->designation ?? null,
                    'lecture_mode' => 'offline',
                    'has_override' => false,
                    'meeting_link' => null,
                    'meeting_password' => null,
                    'meeting_id' => null,
                    'meeting_instructions' => null,
                    'color_class' => $priorityColors[$duty->priority] ?? 'duty-medium'
                ]);
            }
            // For recurring duties - just add as single entry, don't expand
            elseif (in_array($duty->frequency, ['daily', 'weekly', 'monthly']) && $duty->from_date && $duty->to_date) {
                $fromDate = Carbon::parse($duty->from_date);
                $toDate = Carbon::parse($duty->to_date);

                $dutyEvents->push((object) [
                    'id' => $duty->id,
                    'event_type' => 'duty',
                    'subject_display_name' => $duty->title ?? $duty->duty_type ?? 'Duty',
                    'subject_type' => 'duty',
                    'main_subject_name' => $duty->duty_type,
                    'sub_subject_name' => null,
                    'department_name' => $departmentName,
                    'department_id' => $duty->department_id,
                    'course_type' => null,
                    'branch_name' => null,
                    'section_name' => null,
                    'semester_id' => null,
                    'frequency' => $duty->frequency,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'valid_from' => $fromDate->format('Y-m-d'),
                    'valid_to' => $toDate->format('Y-m-d'),
                    'days_of_week' => $daysOfWeek,
                    'day_of_month' => $duty->day_of_month,
                    'location' => $duty->location,
                    'venue' => $duty->venue,
                    'duration' => null,
                    'status' => $duty->status,
                    'remarks' => $duty->description,
                    'instructions' => $duty->instructions,
                    'is_reassigned' => false,
                    'priority' => $duty->priority,
                    'duty_type' => $duty->duty_type,
                    'employee_duty_id' => $duty->employee_duty_id,
                    'employee_name' => $duty->employee->name ?? null,
                    'designation' => $duty->employee->designation ?? null,
                    'lecture_mode' => 'offline',
                    'has_override' => false,
                    'meeting_link' => null,
                    'meeting_password' => null,
                    'meeting_id' => null,
                    'meeting_instructions' => null,
                    'color_class' => $priorityColors[$duty->priority] ?? 'duty-medium'
                ]);
            }
        }

        // Merge all events
        $allEvents = $allEvents->merge($dutyEvents);

        // Format all events for timetable
        $timetableEvents = collect();
        $employeeIdentifier = $empCode ?: $empDbId;

        foreach ($allEvents as $event) {
            if ($event->event_type === 'lecture' && isset($event->is_cancelled) && $event->is_cancelled == 1) {
                continue;
            }

            if ($event->event_type === 'lecture' && $event->frequency == 'one_time' && isset($event->is_reassigned) && $event->is_reassigned == 1) {
                if ($event->valid_to < $todayDate) {
                    continue;
                }
            }

            $daysOfWeek = [];
            if (isset($event->days_of_week)) {
                if (is_string($event->days_of_week)) {
                    try {
                        $decoded = json_decode($event->days_of_week, true);
                        if (is_array($decoded)) {
                            $daysOfWeek = $decoded;
                        } elseif (is_string($decoded)) {
                            $decodedAgain = json_decode($decoded, true);
                            if (is_array($decodedAgain)) {
                                $daysOfWeek = $decodedAgain;
                            }
                        }
                    } catch (\Exception $e) {
                        $daysOfWeek = [];
                    }
                } elseif (is_array($event->days_of_week)) {
                    $daysOfWeek = $event->days_of_week;
                }
            }

            $reassignmentInfo = null;
            if ($event->event_type === 'lecture') {
                $isReassignedToMe = (isset($event->reassigned_to_employee_id) && $event->reassigned_to_employee_id == $employeeIdentifier);
                $isReassignedFromMe = (isset($event->reassigned_from_employee_id) && $event->reassigned_from_employee_id == $employeeIdentifier);
                $isReassignmentLecture = ($event->frequency == 'one_time' && isset($event->is_reassigned) && $event->is_reassigned == 1);

                if ($isReassignmentLecture && $isReassignedToMe) {
                    $reassignmentInfo = [
                        'is_reassigned_to_me' => true,
                        'is_reassigned_from_me' => false,
                        'reassigned_from_employee_name' => $event->reassigned_from_employee_name ?? 'Unknown',
                        'reassigned_at' => $event->reassigned_at ?? null,
                        'reassignment_reason' => $event->reassignment_reason ?? null,
                        'reassignment_date' => $event->reassignment_date ?? $event->valid_from,
                        'original_frequency' => 'regular'
                    ];
                } elseif (isset($event->reassignment_date) && $event->reassignment_date == $todayDate && isset($event->is_reassigned) && $event->is_reassigned == 1) {
                    $reassignmentInfo = [
                        'is_reassigned_to_me' => false,
                        'is_reassigned_from_me' => true,
                        'reassigned_to_employee_name' => $event->reassigned_to_employee_name ?? 'Unknown',
                        'reassigned_at' => $event->reassigned_at ?? null,
                        'reassignment_reason' => $event->reassignment_reason ?? null,
                        'reassignment_date' => $event->reassignment_date,
                        'original_frequency' => $event->frequency
                    ];
                }
            }

            $startTime = $event->start_time ?? $event->start_time_only ?? null;
            $endTime = $event->end_time ?? $event->end_time_only ?? null;

            if ($startTime && (strpos($startTime, ' ') !== false || strpos($startTime, 'T') !== false)) {
                $startTime = date('H:i:s', strtotime($startTime));
            }
            if ($endTime && (strpos($endTime, ' ') !== false || strpos($endTime, 'T') !== false)) {
                $endTime = date('H:i:s', strtotime($endTime));
            }

            $labels = [
                'one_time' => 'One Time',
                'once' => 'One Time',
                'daily' => 'Daily',
                'weekly' => 'Weekly',
                'monthly' => 'Monthly'
            ];
            $frequencyLabel = $labels[$event->frequency] ?? ucfirst($event->frequency);

            $timetableEvents->push((object) [
                'id' => $event->id,
                'event_type' => $event->event_type ?? 'lecture',
                'emp_assign_subject_id' => $event->emp_assign_subject_id ?? $event->ase_emp_assign_subject_id ?? null,
                'subject_display_name' => $event->subject_display_name,
                'subject_type' => $event->subject_type ?? $event->ase_subject_type ?? 'main_subject',
                'subject_type_label' => ($event->subject_type ?? $event->ase_subject_type ?? 'main_subject') === 'sub_subject' ? 'Sub-Subject' : (($event->subject_type ?? $event->ase_subject_type ?? 'main_subject') === 'duty' ? 'Duty' : 'Main Subject'),
                'main_subject_name' => $event->main_subject_name,
                'sub_subject_name' => $event->sub_subject_name,
                'department_name' => $event->department_name,
                'department_id' => $event->department_id ?? $event->ase_department_id ?? null,
                'course_type' => $event->course_type,
                'branch_name' => $event->branch_name,
                'semester_id' => $event->semester_id,
                'frequency' => $event->frequency,
                'frequency_label' => $frequencyLabel,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'valid_from' => $event->valid_from,
                'valid_to' => $event->valid_to,
                'days_of_week' => $daysOfWeek,
                'day_of_month' => $event->day_of_month,
                'location' => $event->location,
                'venue' => $event->venue ?? null,
                'duration' => $event->duration,
                'status' => $event->status,
                'remarks' => $event->remarks,
                'is_reassigned' => isset($event->is_reassigned) ? ($event->is_reassigned == 1) : false,
                'is_reassignment_lecture' => isset($event->is_reassigned) && $event->is_reassigned == 1,
                'reassignment_info' => $reassignmentInfo,
                'lecture_mode' => $event->lecture_mode ?? 'offline',
                'has_override' => $event->has_override ?? false,
                'override_id' => $event->override_id ?? null,
                'override_reason' => $event->override_reason ?? null,
                'meeting_link' => $event->meeting_link ?? null,
                'meeting_password' => $event->meeting_password ?? null,
                'meeting_id' => $event->meeting_id ?? null,
                'meeting_instructions' => $event->meeting_instructions ?? null,
                'priority' => $event->priority ?? null,
                'duty_type' => $event->duty_type ?? null,
                'employee_duty_id' => $event->employee_duty_id ?? null,
                'instructions' => $event->instructions ?? null,
                'employee_name' => $event->employee_name ?? null,
                'designation' => $event->designation ?? null,
                'color_class' => $event->color_class ?? ($event->event_type === 'duty' ? 'duty-medium' : 'subject-default')
            ]);
        }

        // Final deduplication
        $timetableEvents = $timetableEvents->unique(function ($item) {
            return $item->id . '_' . $item->event_type . '_' . ($item->start_time ?? '');
        })->values();

        $timetableEvents = $timetableEvents->sortBy([
            ['valid_from', 'asc'],
            ['start_time', 'asc']
        ])->values();

        // 5. Employee Files / Additional Documents
        $employeeFiles = collect([]);
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('employee_files', 'employee_id')) {
                $employeeFiles = \App\Models\EmployeeFile::where(function ($q) use ($empCode, $empDbId) {
                    if ($empCode)
                        $q->where('employee_id', $empCode);
                    if ($empDbId)
                        $q->orWhere('employee_id', $empDbId);
                })->get();
            }
        } catch (\Throwable $e) {
            $employeeFiles = collect([]);
        }

        // 6. Reimbursements & Assigned Reimbursement Policies
        $reimbursements = \App\Models\ReimbursementClaim::where('institute_id', $instituteId)
            ->where(function ($q) use ($empCode, $empDbId) {
                $q->where('employee_id', $empCode)
                    ->orWhere('employee_id', $empDbId);
            })
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->latest()
            ->get();

        $reimbursementPolicies = collect([]);
        try {
            $reimbursementPolicies = \App\Models\ReimbursementPolicyAssignment::where('institute_id', $instituteId)
                ->where(function ($q) use ($empCode, $empDbId, $employee) {
                    $q->where(function ($direct) use ($empCode, $empDbId) {
                        $direct->where('employee_id', $empCode)
                            ->orWhere('employee_id', $empDbId);
                    });

                    if ($employee->department_id) {
                        $q->orWhere('department_id', $employee->department_id);
                    }

                    if ($employee->designation_id) {
                        $q->orWhere('designation_id', $employee->designation_id);
                    }
                })
                ->when($branchId, function ($query) use ($branchId) {
                    return $query->where(function ($branchQuery) use ($branchId) {
                        $branchQuery->where('branch_id', $branchId)
                            ->orWhereNull('branch_id');
                    });
                })
                ->latest()
                ->get()
                ->unique('id')
                ->values();
        } catch (\Throwable $e) {
            $reimbursementPolicies = collect([]);
        }

        $reimbursementClaimGroups = $reimbursements
            ->groupBy(function ($claim) {
                return $claim->master_request_id
                    ?: $claim->reimbursement_request_id
                    ?: 'claim-' . $claim->id;
            })
            ->map(function ($claims, $requestKey) {
                $representative = $claims->first();
                $statuses = $claims->pluck('status')->filter()->map(function ($status) {
                    return strtolower($status);
                })->unique()->values();

                $groupStatus = $statuses->contains('pending')
                    ? 'pending'
                    : ($statuses->contains('rejected')
                        ? 'rejected'
                        : ($statuses->contains('approved') ? 'approved' : ($statuses->first() ?? 'pending')));

                return [
                    'request_key' => $requestKey,
                    'master_request_id' => $representative->master_request_id,
                    'representative' => $representative,
                    'claims' => $claims->values(),
                    'claim_count' => $claims->count(),
                    'total_claim_amount' => $claims->sum('claim_amount'),
                    'total_approved_amount' => $claims->sum(function ($claim) {
                        return $claim->approved_amount ?? $claim->calculated_amount ?? 0;
                    }),
                    'status' => $groupStatus,
                ];
            })
            ->values();

        // 7. Attendance
        $attendanceByDate = [];
        try {
            $attendanceRecords = \App\Models\EmployeeAttendance::where(function ($q) use ($empCode, $empDbId) {
                if ($empCode)
                    $q->where('employee_id', $empCode);
                if ($empDbId)
                    $q->orWhere('employee_id', $empDbId);
            })
                ->get();

            foreach ($attendanceRecords as $att) {
                if (!empty($att->date)) {
                    $dateKey = Carbon::parse($att->date)->format('Y-m-d');
                    $attendanceByDate[$dateKey] = strtolower($att->status ?? 'present');
                }
            }
        } catch (\Throwable $e) {
            $attendanceByDate = [];
        }

        // 8. ID Card Data & Settings
        $institute = \App\Models\InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
        $idCardSettings = null;
        try {
            $idCardSettings = \App\Models\IDCardSetting::where('institute_id', $instituteId)->first();
        } catch (\Throwable $e) {
            $idCardSettings = null;
        }

        $idCardData = [
            'institute_name' => $institute->institute_name ?? 'Institute Name',
            'institute_logo' => $institute->logo ?? null,
            'institute_address' => $institute->address ?? '',
        ];

        $generatedEmployeeCard = \App\Models\GeneratedIdCard::where('employee_id', $empCode)
            ->where('is_active', true)
            ->latest('generated_at')
            ->first();

        // 9. Assigned Payroll Policy
        $payrollPolicy = null;
        try {
            if ($salaryStructure && !empty($salaryStructure->payroll_policy_id)) {
                $payrollPolicy = \App\Models\ProvidentFundPolicy::where('payroll_policy_id', $salaryStructure->payroll_policy_id)->first();
            }
            if (!$payrollPolicy) {
                $payrollPolicy = \App\Models\ProvidentFundPolicy::where(function ($q) use ($empCode, $empDbId) {
                    if ($empCode)
                        $q->where('employee_id', $empCode);
                    if ($empDbId)
                        $q->orWhere('employee_id', $empDbId);
                })
                    ->latest()
                    ->first();
            }
            if (!$payrollPolicy && $employee->department_id) {
                $payrollPolicy = \App\Models\ProvidentFundPolicy::where('department_id', $employee->department_id)
                    ->latest()
                    ->first();
            }
        } catch (\Throwable $e) {
            $payrollPolicy = null;
        }

        $payslips = $finalSalarySlips;

        // ===== ADD LETTER TEMPLATES FOR OFFICIAL DOCUMENTS TAB (FROM OLD FUNCTION) =====
        $letterTemplates = DB::table('letter_templates')
            ->select('*')
            ->orderBy('id')
            ->get()
            ->map(function ($template) {
                // Parse style_labels if it's JSON
                $styleLabels = [];
                if (!empty($template->style_labels)) {
                    $styleLabels = json_decode($template->style_labels, true) ?? [];
                }

                // Map the template data to match view expectations
                return (object) [
                    'template_key' => $template->key ?? $template->template_key ?? '',
                    'label' => $template->title ?? $template->label ?? 'Document',
                    'icon' => $this->getIconForTemplate($template->key ?? ''),
                    'color' => $this->getColorForTemplate($template->key ?? ''),
                    'style_labels' => $styleLabels,
                    'style_1' => $template->style_1 ?? null,
                    'style_2' => $template->style_2 ?? null,
                    'style_3' => $template->style_3 ?? null,
                    'key' => $template->key ?? '',
                    'title' => $template->title ?? '',
                    'letter_id' => $template->id ?? null,
                    'official_documenttype_id' => $template->official_documenttype_id ?? null,
                ];
            });

        // Also fetch letters for the employee (for official documents)
        $letters = \App\Models\Letter::where('employee_id', $employee->id)
            ->orderByDesc('created_at')
            ->get();

        // ===== END LETTER TEMPLATES =====
        return view('instituteAdmin.EmployeeFiles.ViewEmployee', compact(
            'employee',
            'salaryStructure',
            'structureSalaryDetails',
            'salaryDetails',
            'payrollPolicy',
            'finalSalarySlips',
            'payslips',
            'slipsByMonthYear',
            'leaveHistory',
            'leaveBalance',
            'employeeShifts',
            'effectiveShift',
            'employeeFiles',
            'reimbursements',
            'reimbursementClaimGroups',
            'reimbursementPolicies',
            'attendanceByDate',
            'idCardData',
            'idCardSettings',
            'generatedEmployeeCard',
            'timetableEvents',
            'letterTemplates',
            'letters'
        ));
    }

    public function getSubTypes($courseType)
    {
        $subTypes = ProductDetails::where('course_type', $courseType)
            ->select('sub_type')
            ->distinct()
            ->get();

        return response()->json($subTypes);
    }

    // VIEW EMPLOYEE
    public function show($id)
    {
        return EmployeeDetails::where(
            'id',
            $id
        )->first();
    }

    public function editemployee($id)
    {
        $employee = EmployeeDetails::where('employee_id', $id)->first();
        // Get all necessary data for the form
        $categories = DepartmentCategory::all();
        $departments = Departments::all();
        $designations = Designations::all();

        return view('instituteAdmin.EmployeeFiles.EditEmployeeDetails', compact('employee', 'categories', 'departments', 'designations'));
    }

    public function updateemployee(Request $request, $id)
    {
        $employee = EmployeeDetails::where('employee_id', $id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        // Check if this is a partial update
        if ($request->has('is_partial_update') && $request->input('is_partial_update') == 'true') {
            return response()->json(['success' => false, 'message' => 'Use partial update endpoint']);
        }

        // Get all request data except files & tokens
        $data = $request->except([
            '_token',
            '_method',
            'aadhaar_card',
            'pan_card',
            'address_proof_file',
            'documents'
        ]);

        // Store old employment type before update
        $oldEmploymentType = $employee->employment_type;
        $oldProbationDays = $employee->probation_days;
        $oldDoj = $employee->doj;
        $oldDepartment = $employee->department_name ?? $employee->department_id;
        $oldDesignation = $employee->designation;

        /*
        |--------------------------------------------------------------------------
        | File uploads
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('aadhaar_card')) {
            $file = $request->file('aadhaar_card');
            $fileName = time() . '_aadhaar_' . $file->getClientOriginalName();
            $data['aadhaar_card'] = $file->storeAs('employee_documents', $fileName, 'public');
        }

        if ($request->hasFile('pan_card')) {
            $file = $request->file('pan_card');
            $fileName = time() . '_pan_' . $file->getClientOriginalName();
            $data['pan_card'] = $file->storeAs('employee_documents', $fileName, 'public');
        }

        if ($request->hasFile('address_proof_file')) {
            $file = $request->file('address_proof_file');
            $fileName = time() . '_address_proof_' . $file->getClientOriginalName();
            $data['address_proof_file'] = $file->storeAs('employee_documents', $fileName, 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Additional documents (dynamic)
        |--------------------------------------------------------------------------
        */

        $additionalDocuments = [];

        if ($request->has('documents')) {
            foreach ($request->documents as $index => $document) {

                if (empty($document['name'])) {
                    continue;
                }

                $docData = [
                    'name' => $document['name'] ?? null,
                    'number' => $document['number'] ?? null,
                ];

                if ($request->hasFile("documents.$index.file")) {
                    $file = $request->file("documents.$index.file");
                    $fileName = time() . '_doc_' . $index . '_' . $file->getClientOriginalName();
                    $docData['file'] = $file->storeAs('employee_documents', $fileName, 'public');
                } elseif (!empty($document['existing_file'])) {
                    $docData['file'] = $document['existing_file'];
                }

                $additionalDocuments[] = $docData;
            }
        }

        $data['additional_documents'] = !empty($additionalDocuments)
            ? json_encode($additionalDocuments)
            : null;

        /*
        |--------------------------------------------------------------------------
        | Update employee
        |--------------------------------------------------------------------------
        */

        $employee->update($data);

        // Check if employment type was changed
        $newEmploymentType = $employee->employment_type;
        if ($oldEmploymentType !== $newEmploymentType) {
            // Get institute context for notification check
            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];

            // Check if notifications are enabled for employee_probation_end module
            $emailEnabled = $this->isNotificationEnabled($instituteId, 'employee_probation_end', 'email');
            $whatsappEnabled = $this->isNotificationEnabled($instituteId, 'employee_probation_end', 'whatsapp');
            $smsEnabled = $this->isNotificationEnabled($instituteId, 'employee_probation_end', 'sms');

            // Log the change with notification settings
            $this->logEmploymentTypeChange(
                $employee,
                $oldEmploymentType,
                $newEmploymentType,
                $oldProbationDays,
                $oldDoj,
                $oldDepartment,
                $oldDesignation,
                $emailEnabled,
                $whatsappEnabled,
                $smsEnabled
            );
        }

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully');
    }
    /**
     * Log employment type changes to the probation logs table.
     */
    private function logEmploymentTypeChange($employee, $oldType, $newType, $oldProbationDays = null, $oldDoj = null, $oldDepartment = null, $oldDesignation = null, $sendEmail = true, $sendWhatsapp = false, $sendSms = false)
    {
        try {
            $currentUser = auth()->user();

            // Calculate probation end date if it was a probation period
            $probationEndDate = null;
            if ($oldType === 'Probation-Period' && $oldDoj && $oldProbationDays) {
                $probationEndDate = Carbon::parse($oldDoj)->addDays($oldProbationDays);
            }

            // Check if this is a promotion from Probation to Full-time
            $isPromotion = ($oldType === 'Probation-Period' && $newType === 'Full-time');

            $logData = [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->name,
                'doj' => $oldDoj ?? $employee->doj,
                'probation_days' => $oldProbationDays ?? $employee->probation_days,
                'probation_start_date' => $oldDoj ? Carbon::parse($oldDoj) : null,
                'probation_end_date' => $probationEndDate,
                'employment_type_before' => $oldType,
                'employment_type_after' => $newType,
                'promotion_date' => $isPromotion ? Carbon::now() : null,
                'promoted_by' => $currentUser ? $currentUser->name : 'System',
                'promoted_by_user_id' => $currentUser ? $currentUser->id : null,
                'promotion_type' => 'edit',
                'department_before' => $oldDepartment,
                'designation_before' => $oldDesignation,
                'additional_data' => [
                    'changed_from' => 'employee_edit',
                    'changed_at' => Carbon::now()->toDateTimeString(),
                    'old_probation_days' => $oldProbationDays,
                    'new_probation_days' => $employee->probation_days,
                    'old_doj' => $oldDoj,
                    'new_doj' => $employee->doj,
                    'is_promotion' => $isPromotion
                ]
            ];

            EmployeeProbationLog::create($logData);

            // If promoted to Full-time, update promotion_date and send notification
            if ($isPromotion) {
                $employee->promotion_date = Carbon::now();
                $employee->save();

                // Send notification to employee
                if (!empty($employee->email)) {
                    $this->sendProbationCompletionNotification(
                        $employee,
                        $probationEndDate,
                        $sendEmail,
                        $sendWhatsapp,
                        $sendSms
                    );
                }

                // Send notification to institute admins
                $context = $this->getInstituteBranchContext();
                $this->notifyInstituteAdmins(
                    $context['institute_id'],
                    new EmployeeOnboardedNotification($employee, 'promoted_from_edit')
                );
            }

            \Log::info('Employment type change logged for employee: ' . $employee->employee_code, [
                'old_type' => $oldType,
                'new_type' => $newType,
                'is_promotion' => $isPromotion
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to log employment type change: ' . $e->getMessage());
        }
    }


    /**
     * Get institute name by ID.
     *
     * @param int $instituteId
     * @return string
     */
    private function getInstituteName($instituteId)
    {
        try {
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $instituteId)->first();
            return $institute ? ($institute->name ?? $institute->fincap_merchant_name ?? 'Our Institute') : 'Our Institute';
        } catch (\Exception $e) {
            return 'Our Institute';
        }
    }

    /**
     * Send probation completion notification to employee.
     */
    private function sendProbationCompletionNotification($employee, $probationEndDate, $sendEmail = true, $sendWhatsapp = false, $sendSms = false)
    {
        // Get additional details
        $departmentName = 'N/A';
        if ($employee->department_id) {
            $department = Departments::where('department_id', $employee->department_id)->first();
            $departmentName = $department ? $department->department : 'N/A';
        }

        $designationName = $employee->designation ?? 'N/A';
        $dateOfJoining = $employee->doj ? Carbon::parse($employee->doj)->format('d-m-Y') : 'N/A';
        $probationEndDateFormatted = $probationEndDate ? $probationEndDate->format('d-m-Y') : 'N/A';
        $promotionDate = Carbon::now()->format('d-m-Y');

        // Send Email Notification
        if ($sendEmail && !empty($employee->email)) {
            try {
                Mail::send('emails.employee-probation-completed', [
                    'employee' => $employee,
                    'departmentName' => $departmentName,
                    'designationName' => $designationName,
                    'dateOfJoining' => $dateOfJoining,
                    'probationEndDate' => $probationEndDateFormatted,
                    'promotionDate' => $promotionDate,
                    'companyName' => $this->getInstituteName($employee->institute_id)
                ], function ($message) use ($employee) {
                    $message->to($employee->email)
                        ->subject('Congratulations! Your Probation Period Has Ended');
                });

                \Log::info('Probation completion email sent to: ' . $employee->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send probation completion email: ' . $e->getMessage());
            }
        }

        // Send WhatsApp notification if enabled
        if ($sendWhatsapp && !empty($employee->mobile_number)) {
            try {
                $whatsappMessage = "🎉 Congratulations {$employee->name}!\n\n";
                $whatsappMessage .= "Your probation period has been completed successfully and you have been promoted to Full-Time employee.\n\n";
                $whatsappMessage .= "📅 Date of Joining: {$dateOfJoining}\n";
                $whatsappMessage .= "📅 Probation End Date: {$probationEndDateFormatted}\n";
                $whatsappMessage .= "📅 Promotion Date: {$promotionDate}\n";
                $whatsappMessage .= "🏢 Department: {$departmentName}\n";
                $whatsappMessage .= "💼 Designation: {$designationName}\n\n";
                $whatsappMessage .= "We appreciate your dedication and look forward to your continued growth with us! 🚀";

                \Log::info('WhatsApp notification would be sent to: ' . $employee->mobile_number);
            } catch (\Exception $e) {
                \Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            }
        }

        // Send SMS notification if enabled
        if ($sendSms && !empty($employee->mobile_number)) {
            try {
                $smsMessage = "🎉 Congrats {$employee->name}! Your probation has ended on {$probationEndDateFormatted}. You are now a Full-Time employee. - " . $this->getInstituteName($employee->institute_id);

                \Log::info('SMS notification would be sent to: ' . $employee->mobile_number);
            } catch (\Exception $e) {
                \Log::error('Failed to send SMS notification: ' . $e->getMessage());
            }
        }
    }

    public function updatePartial(Request $request, $id)
    {
        try {
            $employee = EmployeeDetails::where('employee_id', $id)->first();

            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found']);
            }

            // Determine which step is being saved
            $currentStep = $request->input('current_step', 1);
            $data = [];
            $updatedValues = [];

            // Initialize old values as null (will be set in step 2)
            $oldEmploymentType = null;
            $oldProbationDays = null;
            $oldDoj = null;
            $oldDepartment = null;
            $oldDesignation = null;

            // Step 1: Basic Details
            if ($currentStep == 1) {
                $fields = [
                    'department_category_id',
                    'department_id',
                    'name',
                    'designation_id',
                    'mobile_number',
                    'email',
                    'gender',
                    'dob',
                    'blood_group',
                    'nationality',
                    'religion',
                    'addressline1',
                    'addressline2',
                    'state',
                    'city',
                    'pincode',
                    'designation',
                    'assigned_role'
                ];

                foreach ($fields as $field) {
                    if ($request->has($field)) {
                        $data[$field] = $request->input($field);
                        if (in_array($field, ['mobile_number', 'email'])) {
                            $updatedValues[$field] = $request->input($field);
                        }
                    }
                }

                // Handle file uploads for step 1
                if ($request->hasFile('aadhaar_card')) {
                    $file = $request->file('aadhaar_card');
                    $fileName = time() . '_aadhaar_' . $file->getClientOriginalName();
                    $data['aadhaar_card'] = $file->storeAs('employee_documents', $fileName, 'public');
                }

                if ($request->hasFile('pan_card')) {
                    $file = $request->file('pan_card');
                    $fileName = time() . '_pan_' . $file->getClientOriginalName();
                    $data['pan_card'] = $file->storeAs('employee_documents', $fileName, 'public');
                }
            }

            // Step 2: Professional Details
            if ($currentStep == 2) {
                // Store old values before update for logging
                $oldEmploymentType = $employee->employment_type;
                $oldProbationDays = $employee->probation_days;
                $oldDoj = $employee->doj;
                $oldDepartment = $employee->department_name ?? $employee->department_id;
                $oldDesignation = $employee->designation;

                $fields = [
                    'employment_type',
                    'probation_days',
                    'salary_type',
                    'doj',
                    'previous_pf_number',
                    'esi_number',
                    'previous_employer_name',
                    'previous_exit_date'
                ];

                foreach ($fields as $field) {
                    if ($request->has($field)) {
                        $data[$field] = $request->input($field);
                    }
                }

                // Check if employment type is being changed to Full-time
                if (isset($data['employment_type']) && $oldEmploymentType === 'Probation-Period' && $data['employment_type'] === 'Full-time') {
                    // Set promotion date
                    $data['promotion_date'] = Carbon::now();
                }
            }

            // Step 3: Contact Details
            if ($currentStep == 3) {
                $fields = [
                    'emergency_contact_number',
                    'contact_person_name',
                    'relation_with_contact',
                    'reference_name',
                    'reference_contact_number'
                ];

                foreach ($fields as $field) {
                    if ($request->has($field)) {
                        $data[$field] = $request->input($field);
                    }
                }
            }

            // Step 4: Documents
            if ($currentStep == 4) {
                $fields = [
                    'aadhaar_number',
                    'pan_number',
                    'is_address_same',
                    'address_proof_type',
                    'address_proof_number'
                ];

                foreach ($fields as $field) {
                    if ($request->has($field)) {
                        $data[$field] = $request->input($field);
                    }
                }

                // Handle address proof file
                if ($request->hasFile('address_proof_file')) {
                    $file = $request->file('address_proof_file');
                    $fileName = time() . '_address_proof_' . $file->getClientOriginalName();
                    $data['address_proof_file'] = $file->storeAs('employee_documents', $fileName, 'public');
                }

                // Handle additional documents
                if ($request->has('documents')) {
                    $additionalDocuments = [];
                    $existingDocs = json_decode($employee->additional_documents ?? '[]', true) ?: [];

                    foreach ($request->documents as $index => $document) {
                        if (empty($document['name']))
                            continue;

                        $docData = [
                            'name' => $document['name'] ?? null,
                            'number' => $document['number'] ?? null,
                        ];

                        if ($request->hasFile("documents.$index.file")) {
                            $file = $request->file("documents.$index.file");
                            $fileName = time() . '_doc_' . $index . '_' . $file->getClientOriginalName();
                            $docData['file'] = $file->storeAs('employee_documents', $fileName, 'public');
                        } elseif (!empty($document['existing_file'])) {
                            $docData['file'] = $document['existing_file'];
                        }

                        $additionalDocuments[] = $docData;
                    }

                    $data['additional_documents'] = !empty($additionalDocuments)
                        ? json_encode($additionalDocuments)
                        : null;
                }
            }

            // Step 5: Bank Details
            if ($currentStep == 5) {
                $fields = ['bank_name', 'branch_name', 'account_number', 'ifsc_code'];

                foreach ($fields as $field) {
                    if ($request->has($field)) {
                        $data[$field] = $request->input($field);
                    }
                }
            }

            // Update the employee
            $employee->update($data);

            // Check if employment type was changed in step 2
            if ($currentStep == 2 && isset($data['employment_type'])) {
                $newEmploymentType = $employee->employment_type;
                if ($oldEmploymentType !== $newEmploymentType) {
                    // Get institute context for notification check
                    $context = $this->getInstituteBranchContext();
                    $instituteId = $context['institute_id'];

                    // Check if notifications are enabled for employee_probation_end module
                    $emailEnabled = $this->isNotificationEnabled($instituteId, 'employee_probation_end', 'email');
                    $whatsappEnabled = $this->isNotificationEnabled($instituteId, 'employee_probation_end', 'whatsapp');
                    $smsEnabled = $this->isNotificationEnabled($instituteId, 'employee_probation_end', 'sms');

                    $this->logEmploymentTypeChange(
                        $employee,
                        $oldEmploymentType,
                        $newEmploymentType,
                        $oldProbationDays,
                        $oldDoj,
                        $oldDepartment,
                        $oldDesignation,
                        $emailEnabled,
                        $whatsappEnabled,
                        $smsEnabled
                    );
                }
            }

            // Re-fetch employee to return updated values
            $employee->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Step saved successfully',
                'updated_values' => $updatedValues ?? []
            ]);

        } catch (\Exception $e) {
            \Log::error('Partial update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving step: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadEmployees(Request $request)
    {
        //  Validate only required fields
        $validated = $request->validate([
            'type' => 'required|in:excel,csv,pdf',
            'ids' => 'nullable|string',
        ]);

        //  Get institute context
        $context = $this->getInstituteBranchContext();

        //  Attach institute & branch
        $validated['institute_id'] = $context['institute_id'];
        $validated['branch_id'] = $context['is_branch_admin'] ? $context['branch_id'] : null;

        //  Base query (Multi-tenant safe)
        $query = EmployeeDetails::where('institute_id', $validated['institute_id']);

        //  Apply branch filter only if branch admin
        if ($validated['branch_id']) {
            $query->where('branch_id', $validated['branch_id']);
        }

        //  Apply ID filter only if provided
        if ($request->has('select_all')) {

            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->filled('designation')) {
                $query->where('designation', $request->designation);
            }

            if ($request->filled('email')) {
                $query->where('email', $request->email);
            }

            if ($request->filled('employee_code')) {
                $query->where('employee_code', 'LIKE', '%' . $request->employee_code . '%');
            }

            if ($request->filled('name')) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }

        } elseif (!empty($validated['ids'])) {
            $ids = array_filter(explode(',', $validated['ids']));
            $query->whereIn('id', $ids);
        }

        //  Fetch employees
        $employees = $query->get();

        if ($employees->isEmpty()) {
            return response()->json(['message' => 'No employees found'], 404);
        }

        //  Download switch
        switch ($validated['type']) {

            case 'excel':
                return Excel::download(
                    new EmployeesExport($employees),
                    'employees.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new EmployeesExport($employees),
                    'employees.csv'
                );

            case 'pdf':
                $pdf = Pdf::loadView('pdf.employee_export', compact('employees'));
                return $pdf->download('employees.pdf');

            default:
                return response()->json(['message' => 'Invalid download type'], 400);
        }
    }

    /**
     * Check if a specific notification channel is enabled for a module
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        // try {
        $setting = \App\Models\InstituteNotificationSetting::where('institute_id', $instituteId)
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
        //     } catch (\Exception $e) {
        //         \Log::error('Error checking notification status: ' . $e->getMessage());
        //         return $channel === 'email'; // Default to email only on error
        //     }
    }

    public function promoteToFullTime($id)
    {
        try {
            DB::beginTransaction();

            $employee = EmployeeDetails::where('id', $id)->first();

            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
            }

            if ($employee->employment_type !== 'Probation-Period') {
                return response()->json(['success' => false, 'message' => 'Employee is not on probation'], 400);
            }

            // Store old values for logging
            $oldEmploymentType = $employee->employment_type;
            $oldProbationDays = $employee->probation_days;
            $oldDoj = $employee->doj;
            $oldDepartment = $employee->department_name ?? $employee->department_id;
            $oldDesignation = $employee->designation;
            $probationEndDate = $employee->getProbationEndDateAttribute();

            // Get institute context for notification check
            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];

            // Check if notifications are enabled for employee_probation_end module
            $emailEnabled = $this->isNotificationEnabled($instituteId, 'employee_probation_end', 'email');
            $whatsappEnabled = $this->isNotificationEnabled($instituteId, 'employee_probation_end', 'whatsapp');
            $smsEnabled = $this->isNotificationEnabled($instituteId, 'employee_probation_end', 'sms');

            // Update employment type to full-time
            $employee->employment_type = 'Full-time';
            $employee->probation_days = null;
            $employee->promotion_date = Carbon::now();
            $employee->save();

            // Log the change
            $this->logEmploymentTypeChange(
                $employee,
                $oldEmploymentType,
                'Full-time',
                $oldProbationDays,
                $oldDoj,
                $oldDepartment,
                $oldDesignation,
                $emailEnabled,
                $whatsappEnabled,
                $smsEnabled
            );

            // Send notification to employee (already handled in logEmploymentTypeChange)
            // but if for some reason it didn't send, we'll send it here too
            if (!empty($employee->email)) {
                $this->sendProbationCompletionNotification(
                    $employee,
                    $probationEndDate,
                    $emailEnabled,
                    $whatsappEnabled,
                    $smsEnabled
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee promoted to full-time successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Promotion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to promote employee: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exit an employee (set status to inactive)
     */
    public function exitEmployee(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            // Find employee
            $employee = EmployeeDetails::where('id', $id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found or you do not have access.'
                ], 404);
            }

            // Branch restriction
            if (
                $context['is_branch_admin'] &&
                $context['branch_id'] &&
                $employee->branch_id != $context['branch_id']
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this employee.'
                ], 403);
            }

            // Already exited
            if (strtolower($employee->status) === 'inactive') {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee is already exited.'
                ], 400);
            }

            // Update employee
            $employee->update([
                'status' => 'inactive',
                'exit_date' => now(),
                'exit_reason' => $request->input('exit_reason')
            ]);

            // Update linked user
            if (!empty($employee->user_id)) {

                $user = \App\Models\User::find($employee->user_id);

                if ($user) {

                    $userUpdate = [
                        'status' => 'inactive',
                    ];

                    // Only if column exists in DB
                    if (\Schema::hasColumn('users', 'deactivated_at')) {
                        $userUpdate['deactivated_at'] = now();
                    }

                    $user->update($userUpdate);
                }
            }

            DB::commit();

            // Log success
            \Log::info('Employee exited successfully', [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->name,
                'exit_reason' => $request->input('exit_reason'),
                'exited_by' => auth()->id(),
            ]);

            // Send email AFTER commit
            try {
                $this->sendEmployeeExitNotification(
                    $employee,
                    $context,
                    $request->input('exit_reason')
                );
            } catch (\Exception $mailException) {

                \Log::error('Exit email failed', [
                    'employee_id' => $employee->id,
                    'error' => $mailException->getMessage()
                ]);

                // Don't fail the exit if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Employee has been exited successfully.',
                'employee_id' => $employee->id
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Exit employee failed', [
                'employee_id' => $id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send exit notification to employee
     */
    private function sendEmployeeExitNotification($employee, $context, $exitReason = null)
    {
        try {
            // Check if exit notification is enabled
            $emailEnabled = $this->isNotificationEnabled(
                $context['institute_id'],
                'employee_exit',
                'email'
            );

            if (!$emailEnabled || empty($employee->email)) {
                \Log::info("Exit email notifications disabled or no email for employee: " . $employee->id);
                return;
            }

            // Get institute details
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
            $instituteName = $institute ? $institute->name ?? 'Institute' : 'Institute';

            // Get department name
            $departmentName = 'N/A';
            if ($employee->department_id) {
                $department = Departments::where('department_id', $employee->department_id)->first();
                $departmentName = $department ? $department->department : 'N/A';
            }

            // Prepare email data
            $emailData = [
                'employeeName' => $employee->name,
                'instituteName' => $instituteName,
                'exitReason' => $exitReason ?? 'Not specified',
                'exitDate' => now()->format('d-m-Y H:i:s'),
                'employeeCode' => $employee->employee_code,
                'departmentName' => $departmentName,
                'designation' => $employee->designation ?? 'N/A',
            ];

            // Send email to employee
            Mail::send('emails.employee-exit', $emailData, function ($message) use ($employee, $instituteName) {
                $message->to($employee->email)
                    ->subject("Employee Exit Confirmation - {$instituteName} (#{$employee->employee_code})");
            });

            \Log::info("Employee exit email sent to: " . $employee->email);

        } catch (\Exception $e) {
            \Log::error("Failed to send employee exit notification: " . $e->getMessage());
        }
    }

    /**
     * Bulk exit multiple employees
     */
    public function bulkExitEmployees(Request $request)
    {
        try {
            $request->validate([
                'employee_ids' => 'required|array',
                'employee_ids.*' => 'exists:employee_details,id',
                'exit_reason' => 'nullable|string|max:500'
            ]);

            $context = $this->getInstituteBranchContext();
            $exitReason = $request->input('exit_reason', 'Bulk exit action');

            DB::beginTransaction();

            $employeeIds = $request->employee_ids;
            $exitedCount = 0;
            $exitedEmployees = [];

            foreach ($employeeIds as $id) {
                $employee = EmployeeDetails::where('id', $id)
                    ->where('institute_id', $context['institute_id'])
                    ->first();

                if (!$employee || $employee->status === 'inactive') {
                    continue;
                }

                // Apply branch restriction if branch admin
                if ($context['is_branch_admin'] && $context['branch_id']) {
                    if ($employee->branch_id != $context['branch_id']) {
                        continue;
                    }
                }

                // Update employee
                $employee->update([
                    'status' => 'inactive',
                    'exit_date' => now(),
                    'exit_reason' => $exitReason,
                ]);

                // Update user
                if ($employee->user_id) {
                    \App\Models\User::where('id', $employee->user_id)->update([
                        'status' => 'inactive',
                        'deactivated_at' => now(),
                    ]);
                }

                $exitedCount++;
                $exitedEmployees[] = $employee->id;

                // Send individual notifications (optional - could be batched)
                $this->sendEmployeeExitNotification($employee, $context, $exitReason);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully exited {$exitedCount} employee(s).",
                'exited_count' => $exitedCount,
                'exited_employee_ids' => $exitedEmployees
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Bulk exit employees error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error exiting employees: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display employees currently on notice period
     */
    public function noticePeriodEmployees(Request $request)
    {
        //  dd('uhg');
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();

        // Get institute details for header
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->with(['documents'])
            ->first();

        // Build query for employees with active notice period
        $employeesQuery = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name'
        )
            ->where('employee_details.institute_id', $merchantId)
            ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
            ->whereHas('activeExit', function ($query) {
                $query->where('exit_status', 'notice_period');
            });

        // Apply branch filter if branch admin
        if ($context['is_branch_admin'] && $context['branch_id']) {
            $employeesQuery->where('employee_details.branch_id', $context['branch_id']);
        }

        // Apply search filters
        if ($request->filled('name')) {
            $employeesQuery->where('employee_details.name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('employee_code')) {
            $employeesQuery->where('employee_details.employee_code', 'LIKE', '%' . $request->employee_code . '%');
        }

        if ($request->filled('department_id')) {
            $employeesQuery->where('employee_details.department_id', $request->department_id);
        }

        if ($request->filled('designation')) {
            $employeesQuery->where('employee_details.designation', 'LIKE', '%' . $request->designation . '%');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'employee_details.created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Whitelist sortable columns to prevent SQL injection
        $allowedSortColumns = [
            'employee_details.name',
            'employee_details.employee_code',
            'employee_details.designation',
            'employee_details.doj',
            'employee_details.created_at',
            'departments.department',
            'employee_exits.notice_end_date'
        ];

        if (in_array($sortBy, $allowedSortColumns)) {
            // Handle special case for notice_end_date
            if ($sortBy === 'employee_exits.notice_end_date') {
                $employeesQuery->orderBy('employee_exits.notice_end_date', $sortOrder);
            } else {
                $employeesQuery->orderBy($sortBy, $sortOrder);
            }
        } else {
            $employeesQuery->orderBy('employee_details.created_at', 'desc');
        }

        // Eager load exit data
        $employees = $employeesQuery->with([
            'activeExit' => function ($query) {
                $query->where('exit_status', 'notice_period');
            }
        ])->paginate(15);

        // Get dropdown data
        $employeeNames = EmployeeDetails::where('institute_id', $merchantId)
            ->select('name')
            ->distinct()
            ->get();

        $employeeCodes = EmployeeDetails::where('institute_id', $merchantId)
            ->select('employee_code')
            ->distinct()
            ->get();

        $departments = Departments::where('institute_id', $merchantId)
            ->orderBy('department')
            ->get();

        $designations = EmployeeDetails::where('institute_id', $merchantId)
            ->select('designation')
            ->distinct()
            ->whereNotNull('designation')
            ->get();

        // Calculate notice period summary stats
        $totalNotice = $employees->total();

        // Count employees whose notice period ends today
        $endingToday = EmployeeDetails::where('institute_id', $merchantId)
            ->whereHas('activeExit', function ($query) {
                $query->where('exit_status', 'notice_period')
                    ->whereDate('notice_end_date', Carbon::today());
            })
            ->count();

        // Count overdue notice periods
        $overdue = EmployeeDetails::where('institute_id', $merchantId)
            ->whereHas('activeExit', function ($query) {
                $query->where('exit_status', 'notice_period')
                    ->whereDate('notice_end_date', '<', Carbon::today());
            })
            ->count();

        return view('instituteAdmin.EmployeeFiles.NoticePeriodEmployees', [
            'employees' => $employees,
            'fincapMerchants' => $fincapMerchants,
            'employeeNames' => $employeeNames,
            'employeeCodes' => $employeeCodes,
            'departments' => $departments,
            'designations' => $designations,
            'totalNotice' => $totalNotice,
            'endingToday' => $endingToday,
            'overdue' => $overdue,
            'user_type' => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
        ]);
    }

    private function getIconForTemplate($key)
    {
        $iconMap = [
            'appointment' => 'bi-file-earmark-person',
            'appointment_letter' => 'bi-file-earmark-person',
            'offer' => 'bi-file-earmark-check',
            'termination' => 'bi-file-earmark-x',
            'recommendation' => 'bi-file-earmark-arrow-up',
            'complaint' => 'bi-file-earmark-excel',
            'warning' => 'bi-exclamation-triangle',
            'noc' => 'bi-file-earmark-check-fill',
            'salary' => 'bi-file-earmark-arrow-up-fill',
            'experience' => 'bi-award',
            'appraisal' => 'bi-graph-up-arrow',
        ];

        return $iconMap[$key] ?? 'bi-file-earmark-text';
    }

    private function getColorForTemplate($key)
    {
        $colorMap = [
            'appointment' => '#3b82f6',
            'appointment_letter' => '#3b82f6',
            'offer' => '#10b981',
            'termination' => '#ef4444',
            'recommendation' => '#8b5cf6',
            'complaint' => '#f59e0b',
            'warning' => '#f97316',
            'noc' => '#06b6d4',
            'salary' => '#8b5cf6',
            'experience' => '#f43f5e',
            'appraisal' => '#14b8a6',
        ];

        return $colorMap[$key] ?? '#6b7280';
    }



    /**
     * Show the external onboarding form (Self-initiated)
     */
    public function showExternalOnboardingForm()
    {
        $lead = null;
        $leadPrefill = [];

        if (session()->has('onboarding_lead_id')) {
            abort_unless(session('onboarding_otp_verified') === true, 403, 'OTP verification is required.');

            $lead = Lead::with('department')
                ->with('InterviewRegistration.interviewConfiguration.department')
                ->whereKey(session('onboarding_lead_id'))
                ->where('institute_id', session('onboarding_institute_id'))
                ->where('lead_type', 'Interview')
                ->firstOrFail();

            $leadPrefill = array_filter([
                'name' => $lead->name,
                'mobile_number' => preg_replace('/\D+/', '', (string) $lead->phone_no),
                'email' => $lead->email,
                'institute_id' => $lead->institute_id,
                'department_category_id' => $lead->department_category_id ?? null,
                'department_id' => $lead->department_id ?? null,
                'designation_id' => $lead->designation_id ?? null,
                'designation' => $lead->designation ?? null,
            ], static fn($value) => $value !== null && $value !== '');

            if (empty($leadPrefill['department_id']) && $lead->department) {
                $leadPrefill['department_id'] = $lead->department->department_id;
            }

            $interviewConfig = $lead->InterviewRegistration?->interviewConfiguration;
            $configuredDepartment = $interviewConfig?->department;
            $interviewRegistration = $lead->InterviewRegistration;
            $leadPrefill['department_id'] = $interviewConfig?->department_id ?? $configuredDepartment?->department_id ?? $lead->department_id;
            $leadPrefill['department_category_id'] = $configuredDepartment?->department_category_id ?? $lead->department_category_id;
            $leadPrefill['doj'] = $interviewConfig?->joining_date;
            $leadPrefill['dob'] = $interviewRegistration?->dob ?? $interviewRegistration?->date_of_birth ?? $lead->dob ?? $lead->date_of_birth ?? null;
            $leadPrefill['previous_employer_name'] = $interviewRegistration?->organization ?? $lead->current_organization ?? $lead->organization ?? null;
            $leadPrefill['designation'] = $interviewConfig?->form_title ?? $interviewRegistration?->applying_for_profile ?? $lead->designation ?? null;
            $leadPrefill['designation_id'] = null;

            \Log::info('Interview onboarding prefill loaded', [
                'lead_id' => $lead->id,
                'lead_identifier' => $lead->lead_id,
                'reference_id' => $lead->reference_id,
                'dob' => $leadPrefill['dob'],
                'current_organization' => $leadPrefill['previous_employer_name'],
                'interview_config_id' => $interviewConfig?->interview_config_id,
                'joining_date' => $leadPrefill['doj'],
            ]);

            foreach ([
                'gender',
                'dob',
                'blood_group',
                'nationality',
                'religion',
                'marital_status',
                'number_of_dependents',
                'spouse_name',
                'height_unit',
                'height_cm',
                'height_feet',
                'height_inches',
                'height',
                'weight_unit',
                'weight_input',
                'weight',
                'health_condition',
                'allergies',
                'medical_notes',
                'addressline1',
                'addressline2',
                'state',
                'city',
                'pincode',
                'emergency_contact_number',
                'contact_person_name',
                'relation_with_contact',
                'reference_name',
                'reference_contact_number',
                'previous_pf_number',
                'esi_number',
                'previous_employer_name',
                'previous_exit_date',
                'aadhaar_number',
                'pan_number',
                'is_address_same',
                'address_proof_type',
                'address_proof_number',
                'bank_name',
                'branch_name',
                'account_number',
                'ifsc_code',
                'employment_type',
                'salary_type',
            ] as $field) {
                if (isset($lead->{$field}) && $lead->{$field} !== '') {
                    $leadPrefill[$field] = $lead->{$field};
                }
            }
        }

        $instituteId = $lead?->institute_id ?? 'ABC1234';

        return view('superadmin.OnboardingForms.EmployeeOnboard', compact(
            'instituteId',
            'lead',
            'leadPrefill'
        ));
    }


    public function storeExternalOnboarding(Request $request)
    {
        \Log::info('External Onboarding Submission Started', [
            'data' => $request->except([
                'profile_photo',
                'upload_signature',
                'aadhaar_card',
                'pan_card',
                'driving_license',
                'passport_photo',
                'address_proof_file',
                'documents',
            ]),
            'files' => $request->allFiles(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Institute
        |--------------------------------------------------------------------------
        |
        | External onboarding is unauthenticated, therefore we cannot use:
        | auth()->user()->institute_id
        |
        | The institute ID should come from the onboarding form / URL.
        |
        */
        $instituteId = $request->input('institute_id');

        $lead = null;
        if (session()->has('onboarding_lead_id')) {
            if (session('onboarding_otp_verified') !== true) {
                return redirect()->route('admission.send.otp')->with('error', 'Please verify the OTP first.');
            }

            $lead = Lead::whereKey(session('onboarding_lead_id'))
                ->where('institute_id', session('onboarding_institute_id'))
                ->where('lead_type', 'Interview')
                ->first();

            if (!$lead) {
                \Log::warning('External onboarding lead context was not found', [
                    'lead_id' => session('onboarding_lead_id'),
                    'institute_id' => session('onboarding_institute_id'),
                ]);

                return redirect()->back()->withInput()->with('error', 'This onboarding link is no longer valid.');
            }

            $instituteId = $lead->institute_id;
        }

        if (empty($instituteId)) {
            $instituteId = 'ABC1234';
        }

        /*
        |--------------------------------------------------------------------------
        | Validation Messages
        |--------------------------------------------------------------------------
        */

        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'Please enter a valid email address.',
            'unique' => 'This :attribute is already registered.',
            'max' => 'The :attribute must not exceed :max characters.',
            'min' => 'The :attribute must be at least :min characters.',
            'date' => 'Please enter a valid date.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'regex' => 'Please enter a valid :attribute.',

            'mobile_number.regex' =>
                'Please enter a valid 10-digit mobile number.',

            'emergency_contact_number.regex' =>
                'Please enter a valid 10-digit emergency contact number.',

            'reference_contact_number.regex' =>
                'Please enter a valid 10-digit reference contact number.',

            'pincode.regex' =>
                'Please enter a valid 6-digit pincode.',

            'account_number.regex' =>
                'Please enter a valid bank account number.',

            'aadhaar_number.regex' =>
                'Aadhaar number must be 12 digits.',

            'pan_number.regex' =>
                'PAN number must be 10 characters (e.g., ABCDE1234F).',

            'documents.*.name.required' =>
                'Document name is required.',

            'documents.*.file.required' =>
                'Document file is required.',

            'documents.*.file.mimes' =>
                'Document must be a file of type: jpg, jpeg, png, pdf.',

            'documents.*.file.max' =>
                'Document file size must not exceed 2MB.',

            'marital_status.required' =>
                'Please select marital status.',

            'marital_status.in' =>
                'Please select a valid marital status.',

            'number_of_dependents.integer' =>
                'Number of dependents must be a number.',

            'number_of_dependents.min' =>
                'Number of dependents cannot be negative.',

            'number_of_dependents.max' =>
                'Number of dependents cannot exceed 20.',

            'profile_photo.required' =>
                'Profile photo is required.',
        ];

        /*
        |--------------------------------------------------------------------------
        | Validation Rules
        |--------------------------------------------------------------------------
        |
        | Department, designation, employment type, salary type and DOJ are
        | intentionally NOT validated because external onboarding does not
        | provide them. They will be stored as NULL.
        |
        */

        $validationRules = [

            /*
            |--------------------------------------------------------------------------
            | Basic Details
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
            ],

            'mobile_number' => [
                'required',
                'string',
                'regex:/^[6-9]\d{9}$/',
                'max:10',
                'unique:employee_details,mobile_number',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:employee_details,email',
            ],

            'department_category_id' => 'nullable|exists:department_categories,department_category_id',
            'department_id' => 'nullable|exists:departments,department_id',
            'designation_id' => 'nullable|exists:designations,designation_id',
            'designation' => 'nullable|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | Personal Details
            |--------------------------------------------------------------------------
            */

            'gender' => 'required|in:male,female,other',

            'dob' => [
                'required',
                'date',
                'before:today',
            ],

            'blood_group' =>
                'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',

            'height_unit' =>
                'nullable|in:cm,ft_in',

            'height_cm' =>
                'nullable|numeric|min:0',

            'height_feet' =>
                'nullable|numeric|min:0',

            'height_inches' =>
                'nullable|numeric|min:0|max:11.9',

            'weight_unit' =>
                'nullable|in:kg,lbs',

            'weight_input' =>
                'nullable|numeric|min:0',

            'height' =>
                'nullable|numeric|min:0',

            'weight' =>
                'nullable|numeric|min:0',

            'health_condition' =>
                'nullable|string|max:255',

            'allergies' =>
                'nullable|string',

            'medical_notes' =>
                'nullable|string',

            'nationality' =>
                'required|string|max:100',

            'religion' =>
                'required|string|max:100',

            'marital_status' =>
                'required|in:single,married,divorced,widowed',

            'number_of_dependents' =>
                'nullable|integer|min:0|max:20',

            'spouse_name' =>
                'nullable|string|max:255',

            'previous_pf_number' =>
                'nullable|string|max:20',

            'esi_number' =>
                'nullable|string|regex:/^\d{17}$/',

            'previous_employer_name' =>
                'nullable|string|max:255',

            'previous_exit_date' =>
                'nullable|date|before:today',

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'addressline1' =>
                'required|string|max:255',

            'addressline2' =>
                'nullable|string|max:255',

            'state' =>
                'required|string|max:100',

            'city' =>
                'required|string|max:100',

            'pincode' =>
                'required|string|regex:/^\d{6}$/',

            /*
            |--------------------------------------------------------------------------
            | GPS
            |--------------------------------------------------------------------------
            */

            'latitude' =>
                'nullable|numeric|between:-90,90',

            'longitude' =>
                'nullable|numeric|between:-180,180',

            'timestamp' =>
                'nullable|string|max:255',

            /*
            |--------------------------------------------------------------------------
            | Emergency Contact
            |--------------------------------------------------------------------------
            */

            'emergency_contact_number' =>
                'required|string|regex:/^[6-9]\d{9}$/',

            'contact_person_name' =>
                'required|string|max:255',

            'relation_with_contact' =>
                'required|string|max:100',

            /*
            |--------------------------------------------------------------------------
            | Reference
            |--------------------------------------------------------------------------
            */

            'reference_name' =>
                'nullable|string|max:255',

            'reference_contact_number' =>
                'nullable|string|regex:/^[6-9]\d{9}$/',

            /*
            |--------------------------------------------------------------------------
            | Legal Documents
            |--------------------------------------------------------------------------
            */

            'aadhaar_number' => [
                'nullable',
                'string',
                'regex:/^\d{12}$/',
                'unique:employee_details,aadhaar_number',
            ],

            'pan_number' => [
                'nullable',
                'string',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'unique:employee_details,pan_number',
            ],

            'is_address_same' =>
                'required|in:yes,no',

            'address_proof_type' =>
                'required_if:is_address_same,no|nullable|string|max:100',

            'address_proof_number' =>
                'required_if:is_address_same,no|nullable|string|max:50',

            /*
            |--------------------------------------------------------------------------
            | Bank Details
            |--------------------------------------------------------------------------
            */

            'bank_name' =>
                'nullable|string|max:255',

            'branch_name' =>
                'nullable|string|max:255',

            'account_number' =>
                'nullable|string|regex:/^\d{9,18}$/',

            'ifsc_code' =>
                'nullable|string|max:20',

            /*
            |--------------------------------------------------------------------------
            | File Uploads
            |--------------------------------------------------------------------------
            */

            'profile_photo' =>
                'required|mimes:jpg,jpeg,png,pdf|max:2048',

            'upload_signature' =>
                'nullable|mimes:jpg,jpeg,png,pdf|max:2048',

            'aadhaar_card' =>
                'nullable|mimes:jpg,jpeg,png,pdf|max:2048',

            'pan_card' =>
                'nullable|mimes:jpg,jpeg,png,pdf|max:2048',

            'driving_license' =>
                'nullable|mimes:jpg,jpeg,png,pdf|max:2048',

            'passport_photo' =>
                'nullable|mimes:jpg,jpeg,png|max:1024',

            'address_proof_file' =>
                'required_if:is_address_same,no|nullable|mimes:jpg,jpeg,png,pdf|max:2048',

            /*
            |--------------------------------------------------------------------------
            | Additional Documents
            |--------------------------------------------------------------------------
            */

            'documents' =>
                'nullable|array',

            'documents.*.name' =>
                'required_with:documents|string|max:255',

            'documents.*.number' =>
                'nullable|string|max:100',

            'documents.*.file' =>
                'required_with:documents.*.name|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            $validationRules,
            $messages
        );

        if ($lead) {
            $interviewConfig = $lead->InterviewRegistration?->interviewConfiguration;
            $configuredDepartment = $interviewConfig?->department;
            $interviewRegistration = $lead->InterviewRegistration;
            $validated['name'] = $lead->name;
            $validated['mobile_number'] = preg_replace('/\D+/', '', (string) $lead->phone_no);
            $validated['email'] = $lead->email;
            $validated['institute_id'] = $lead->institute_id;
            $validated['department_id'] = $interviewConfig?->department_id ?? $configuredDepartment?->department_id;
            $validated['department_category_id'] = $configuredDepartment?->department_category_id;
            $validated['doj'] = $interviewConfig?->joining_date;
            $validated['dob'] = $interviewRegistration?->dob ?: $validated['dob'];
            $validated['previous_employer_name'] = $interviewRegistration?->organization;
            $validated['designation_id'] = null;
            $validated['designation'] = $interviewConfig?->form_title;

            \Log::info('Interview configuration mapped to employee onboarding', [
                'lead_id' => $lead->id,
                'lead_identifier' => $lead->lead_id,
                'interview_config_id' => $interviewConfig?->interview_config_id,
                'department_id' => $validated['department_id'],
                'department_category_id' => $validated['department_category_id'],
                'doj' => $validated['doj'],
                'designation' => $validated['designation'],
                'designation_id' => null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | PAN Verification Check
        |--------------------------------------------------------------------------
        */

        $panVerificationCompulsory = 1;
        $panStatus = null;
        $panVerification = null;
        $panVerificationResponse = null;

        if (!empty($validated['pan_number'])) {

            $panVerification = $this->verifyPanWithThirdParty(
                strtoupper($validated['pan_number']),
                $validated['name'],
                $validated['dob']
            );

            // Exact decoded response returned by PAN API
            $panVerificationResponse = $panVerification['response'] ?? null;

            \Log::info('External Employee PAN API Response', [
                'employee_name' => $validated['name'],
                'pan_number' => $validated['pan_number'],
                'dob' => $validated['dob'],
                'success' => $panVerification['success'] ?? null,
                'message' => $panVerification['message'] ?? null,
                'api_response' => $panVerificationResponse,
            ]);

            if (!empty($panVerification['skipped'])) {

                $panVerificationCompulsory = 0;
                $panStatus = null;

            } elseif (!$panVerification['success']) {

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        $panVerification['message']
                    );

            } else {

                $panResponse = $panVerification['response'] ?? [];

                if (
                    !isset($panResponse['data']['respcode']) ||
                    $panResponse['data']['respcode'] != 200
                ) {

                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            'error',
                            'PAN verification failed. Please enter a valid PAN.'
                        );
                }

                if (($panResponse['data']['panname'] ?? 'N') !== 'Y') {

                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            'error',
                            'Name does not match the name mentioned on the PAN card.'
                        );
                }

                if (($panResponse['data']['dob'] ?? 'N') !== 'Y') {

                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            'error',
                            'Date of birth does not match the date mentioned on the PAN card.'
                        );
                }

                // PAN successfully verified
                $panStatus = 'verified';
            }

        } else {

            if ($panVerificationCompulsory == 1) {

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'PAN number is required because PAN verification is compulsory.'
                    );
            }
        }

        $validated['pan_card_status'] = $panStatus;


        /*
        |--------------------------------------------------------------------------
        | Height Conversion
        |--------------------------------------------------------------------------
        */

        $validated['height'] = null;

        if (
            $request->height_unit === 'ft_in' &&
            (
                $request->filled('height_feet') ||
                $request->filled('height_inches')
            )
        ) {
            $validated['height'] =
                ((float) $request->height_feet * 30.48)
                +
                ((float) $request->height_inches * 2.54);
        } elseif ($request->filled('height_cm')) {

            $validated['height'] =
                (float) $request->height_cm;
        }

        /*
        |--------------------------------------------------------------------------
        | Weight Conversion
        |--------------------------------------------------------------------------
        */

        $validated['weight'] = null;

        if ($request->filled('weight_input')) {

            $validated['weight'] =
                $request->weight_unit === 'lbs'
                ? (float) $request->weight_input * 0.45359237
                : (float) $request->weight_input;
        }

        /*
        |--------------------------------------------------------------------------
        | Address Proof Validation
        |--------------------------------------------------------------------------
        */

        if ($request->is_address_same === 'no') {

            $request->validate([
                'address_proof_file' =>
                    'required|mimes:jpg,jpeg,png,pdf|max:2048',

                'address_proof_number' =>
                    'required|string|max:50',
            ], $messages);
        }

        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Generate Employee Identifiers
            |--------------------------------------------------------------------------
            */

            $employeeId = 'EMP' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

            /*
            |--------------------------------------------------------------------------
            | Fields Which Must Be NULL For External Onboarding
            |--------------------------------------------------------------------------
            */

            $validated['institute_id'] = $instituteId;

            if (!$lead) {
                $validated['department_category_id'] = null;
                $validated['department_id'] = null;

                $validated['designation_id'] = null;
                $validated['designation'] = null;
            }

            // No employment information during external onboarding
            $validated['employment_type'] = null;
            $validated['salary_type'] = null;
            if (!$lead) {
                $validated['doj'] = null;
            }
            $validated['probation_days'] = null;

            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */

            $validated['assigned_role'] = 'employee';

            /*
            |--------------------------------------------------------------------------
            | Employee ID / Code
            |--------------------------------------------------------------------------
            */

            $validated['employee_id'] = $employeeId;
            $validated['employee_code'] = $validated['employee_code'] ?? $this->generateUniqueEmployeeCode();

            /*
            |--------------------------------------------------------------------------
            | Self Initiated
            |--------------------------------------------------------------------------
            |
            | 0 = Added through internal/admin onboarding
            | 1 = Added through external/self onboarding
            |
            */

            $validated['self_initiated'] = 1;

            /*
            |--------------------------------------------------------------------------
            | Fixed File Uploads
            |--------------------------------------------------------------------------
            */

            $fixedFiles = [
                'profile_photo',
                'upload_signature',
                'aadhaar_card',
                'pan_card',
                'driving_license',
                'passport_photo',
                'address_proof_file',
            ];

            foreach ($fixedFiles as $file) {

                if ($request->hasFile($file)) {

                    $validated[$file] =
                        $request
                            ->file($file)
                            ->store(
                                'employee_documents',
                                'public'
                            );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Additional Documents
            |--------------------------------------------------------------------------
            */

            $additionalDocuments = [];
            $documentInputs = $request->input('documents', []);
            $documentFiles = $request->file('documents', []);

            foreach ($documentFiles as $index => $documentData) {
                $documentFile = is_array($documentData)
                    ? ($documentData['file'] ?? null)
                    : null;

                if (!$documentFile || !$documentFile->isValid()) {
                    continue;
                }

                $fileName = $documentFile->store(
                    'employee_additional_documents',
                    'public'
                );

                $documentInput = $documentInputs[$index] ?? [];
                $documentName = trim((string) ($documentInput['name'] ?? ''));

                $additionalDocuments[] = [
                    'name' => $documentName !== ''
                        ? $documentName
                        : $documentFile->getClientOriginalName(),
                    'number' => $documentInput['number'] ?? null,
                    'file_path' => $fileName,
                    'uploaded_at' => now()->toDateTimeString(),
                ];
            }

            if (!empty($additionalDocuments)) {

                $validated['additional_documents'] =
                    $additionalDocuments;
            }

            unset($validated['documents']);

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Remove anything coming from the external request that should
            | not be stored in employee_details.
            |--------------------------------------------------------------------------
            */

            unset(
                $validated['instituteId'],
                // $validated['onboarding_token'],
                // $validated['onboarding_completed_at']
            );


            /*
            |--------------------------------------------------------------------------
            | Create Employee
            |--------------------------------------------------------------------------
            */

            \Log::info('Creating external employee', [
                'employee_id' => $validated['employee_id'],
                'employee_code' => $validated['employee_code'],
                'institute_id' => $validated['institute_id'],
                'self_initiated' => $validated['self_initiated'],
            ]);

            $employee = EmployeeDetails::create($validated);

            if (!$employee) {
                throw new \Exception(
                    'Employee record could not be created.'
                );
            }

            if ($lead) {
                $steps = json_decode($lead->steps, true) ?? [];
                $steps['onboarding'] = array_merge($steps['onboarding'] ?? [], [
                    'status' => 'completed',
                    'enabled' => true,
                    'required' => true,
                ]);
                $lead->update([
                    'lead_status' => 'converted',
                    'steps' => json_encode($steps),
                ]);

                if ($lead->interviewRegistration) {
                    $lead->interviewRegistration->update(['onboarding_status' => 'completed']);
                }

                \Log::info('Interview lead onboarding completed', [
                    'lead_id' => $lead->id,
                    'institute_id' => $lead->institute_id,
                    'employee_id' => $employee->employee_id,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Save Exact PAN API Response
            |--------------------------------------------------------------------------
            */

            $employee->forceFill([
                'pan_card_response' => $panVerificationResponse
                    ? json_encode(
                        $panVerificationResponse,
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                    )
                    : null,
            ])->save();

            \Log::info('External Employee PAN Response Saved', [
                'employee_id' => $employee->employee_id,
                'database_id' => $employee->id,
                'pan_card_status' => $employee->pan_card_status,
                'pan_card_response' => $panVerificationResponse,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create User Account
            |--------------------------------------------------------------------------
            |
            | Keep this only if external onboarding should automatically
            | create login credentials.
            |
            */

            if (!empty($validated['email'])) {

                $tempPassword = '12345678';

                $existingUser = User::where('email', $employee->email)
                    ->orWhere('phone', $employee->mobile_number)
                    ->first();

                if (!$existingUser) {
                    $user = User::create([
                        'name' => $employee->name,
                        'email' => $employee->email,
                        'phone' => $employee->mobile_number,
                        'password' => Hash::make($tempPassword),
                        'email_verified_at' => now(),
                        'institute_id' => $instituteId,
                    ]);

                    $user->assignRole('employee');

                    $employee->update([
                        'user_id' => $user->id,
                    ]);
                } else {
                    $user = $existingUser;
                    $employee->update([
                        'user_id' => $existingUser->id,
                    ]);
                    \Log::warning('External onboarding skipped duplicate user account', [
                        'employee_id' => $employee->employee_id,
                        'existing_user_id' => $existingUser->id,
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Welcome Email
                |--------------------------------------------------------------------------
                */

                try {

                    $institute =
                        InstituteBasicDetails::where(
                            'fincap_merchant_id',
                            $instituteId
                        )->first();

                    Mail::send(
                        'emails.employee-registration',
                        [
                            'employee' =>
                                $employee,

                            'password' =>
                                $tempPassword,

                            'role' =>
                                'employee',

                            'institute_name' =>
                                $institute->institute_name
                                ?? 'Organization',
                        ],
                        function ($message) use ($employee) {

                            $message
                                ->to($employee->email)
                                ->subject('Employee Registration Confirmation');
                        }
                    );

                } catch (\Exception $mailException) {

                    /*
                    |--------------------------------------------------------------------------
                    | Email failure should NOT rollback employee creation
                    |--------------------------------------------------------------------------
                    */

                    \Log::error(
                        'Employee registration email failed',
                        [
                            'employee_id' =>
                                $employee->employee_id,

                            'error' =>
                                $mailException->getMessage(),
                        ]
                    );
                }
            }

            \Log::info('Employee Onboarding registration completed', [
                'lead_id' => $lead?->id,
                'lead_identifier' => $lead?->lead_id,
                'reference_id' => $lead?->reference_id,
                'employee_id' => $employee->employee_id ?? $employee->id,
                'user_id' => $employee->user_id,
                'dob' => $employee->dob,
                'previous_employer' => $employee->previous_employer_name,
                'joining_date' => $employee->doj,
                'address_proof_number' => $employee->address_proof_number,
                'registration_email' => $employee->email,
            ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Clear External Onboarding Session
            |--------------------------------------------------------------------------
            */

            session()->forget([
                'external_mobile_number',
                'external_mobile_verified',
                'external_email',
                'external_email_verified',
                'onboarding_lead_id',
                'onboarding_institute_id',
                'onboarding_otp_verified',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('external.onboarding.success')
                ->with(
                    'success',
                    'Your onboarding details have been submitted successfully! ' .
                    'Your employee ID is ' .
                    $employee->employee_id
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error('========== EXTERNAL ONBOARDING FAILED ==========', [
                'message' => $e->getMessage(),
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'institute_id' => $instituteId,
                'request_data' => $request->except([
                    '_token',
                    'profile_photo',
                    'upload_signature',
                    'aadhaar_card',
                    'pan_card',
                    'driving_license',
                    'passport_photo',
                    'address_proof_file',
                    'documents',
                ]),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to complete onboarding. Please try again.');
        }
    }

    /**
     * Show success page after external onboarding
     */
    public function externalOnboardingSuccess()
    {
        return view('superadmin.OnboardingForms.OnboardingSuccess');
    }

    /**
     * Send Mobile OTP for external employee onboarding (AJAX)
     */
    public function sendExternalMobileOtp(Request $request)
    {
        $attr = $request->all();
        $validator = Validator::make($attr, [
            'mobile_number' => 'required|digits:10',
        ]);

        // Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid 10-digit mobile number.',
                'errors' => $validator->messages()
            ], 200);
        }

        try {
            $mobile = $attr['mobile_number'];

            // Initialize cURL to send OTP via MSG91
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=63aa9abe2b190d6d07363e4a&mobile=91$mobile&authkey=196733AcZiJ1EG25e84a365P1",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/JSON"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                \Log::error('MSG91 External Mobile OTP error: ' . $err);
                return response()->json([
                    'success' => false,
                    'message' => 'Kindly retry after some time',
                ], 200);
            } else {
                $response = json_decode($response, true);

                if (isset($response['type']) && $response['type'] == 'success') {
                    // Store mobile number in session for verification
                    session([
                        'external_mobile_number' => $mobile,
                        'external_mobile_verified' => false
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'OTP sent successfully to your mobile number.'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kindly retry after some time',
                    ], 200);
                }
            }
        } catch (Exception $e) {
            \Log::error('External mobile OTP send error: ' . $e->getMessage());
            return response()->json([
                "success" => false,
                "errorCode" => "880",
                "description" => "Note : Initiate Status check after Some time"
            ], 200);
        }
    }

    /**
     * Verify Mobile OTP for external employee onboarding (AJAX)
     */
    public function verifyExternalMobileOtp(Request $request)
    {
        $attr = $request->all();
        $validator = Validator::make($attr, [
            'otp' => 'required|digits:4',
            'mobile_number' => 'required|digits:10',
        ]);

        // Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter valid details.',
                'errors' => $validator->messages()
            ], 200);
        }

        try {
            $mobile = $attr['mobile_number'];
            $otp = $attr['otp'];

            // Verify OTP with MSG91
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.msg91.com/api/v5/otp/verify?otp=$otp&authkey=196733AcZiJ1EG25e84a365P1&mobile=91$mobile",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/JSON"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                \Log::error('MSG91 External Mobile OTP verification error: ' . $err);
                return response()->json([
                    'success' => false,
                    'message' => 'Kindly try after sometime'
                ], 200);
            } else {
                $response = json_decode($response, true);

                if (isset($response['type']) && $response['type'] == 'success') {
                    // Verify that the mobile number matches the session
                    $storedMobile = session('external_mobile_number');
                    if ($mobile !== $storedMobile) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Mobile number mismatch. Please request a new OTP.'
                        ], 200);
                    }

                    // Set mobile verification status in session
                    session([
                        'external_mobile_verified' => true
                    ]);

                    // Optional: Generate dynamic link if needed (like in student admission)
                    // $rawToken = Str::random(40);
                    // $hashedToken = hash('sha256', $rawToken);
                    // ... store in database if needed

                    return response()->json([
                        'success' => true,
                        'message' => 'Mobile number verified successfully!'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid OTP. Please try again.'
                    ], 200);
                }
            }
        } catch (Exception $e) {
            \Log::error('External mobile OTP verification error: ' . $e->getMessage());
            return response()->json([
                "success" => false,
                "errorCode" => "881",
                "description" => "Note : Initiate Status check after Some time"
            ], 200);
        }
    }

    /**
     * Send Email OTP for external employee onboarding (AJAX)
     */
    public function sendExternalEmailOtp(Request $request)
    {
        $email = $request->input('email');

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid email address.'
            ]);
        }

        try {
            $otp = rand(1000, 9999);
            $expiresAt = Carbon::now('Asia/Kolkata')->addMinutes(2);

            session([
                'external_email_otp' => $otp,
                'external_email_otp_expires' => $expiresAt,
                'external_email' => $email,
                'external_email_verified' => false
            ]);

            \Log::info("External Email OTP generated for {$email}: {$otp}");

            // Send Mail using existing template user/emailOtpTemplate
            try {
                Mail::send('user/emailOtpTemplate', ['otp' => $otp], function ($message) use ($email) {
                    $message->from(config('mail.from.address'), config('mail.from.name', 'Organization'))
                        ->to($email)
                        ->subject('Employee Onboarding - Email Verification OTP');
                });
            } catch (\Exception $mailEx) {
                \Log::warning('Email OTP sending via Mailer failed (logged OTP fallback): ' . $mailEx->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email address successfully.'
            ]);
        } catch (\Exception $e) {
            \Log::error('External email OTP send error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify Email OTP for external employee onboarding (AJAX)
     */
    public function verifyExternalEmailOtp(Request $request)
    {
        $otp = $request->input('otp');
        $email = $request->input('email');

        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Email address is required.']);
        }

        if (!$otp || strlen($otp) !== 4) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid 4-digit OTP.']);
        }

        try {
            $storedOtp = session('external_email_otp');
            $storedExpires = session('external_email_otp_expires');
            $storedEmail = session('external_email');

            if (!$storedOtp || $email !== $storedEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP session expired or email mismatch. Please request a new OTP.'
                ]);
            }

            if (Carbon::now('Asia/Kolkata')->gt($storedExpires)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired. Please click Resend OTP to get a new code.'
                ]);
            }

            if ($otp != $storedOtp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP entered. Please try again.'
                ]);
            }

            session(['external_email_verified' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Email address verified successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('External email OTP verification error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error verifying email OTP.'], 500);
        }
    }

    public function verifyPanWithThirdParty($panNumber, $fullName, $dob, $is_enabled = 1)
    {
        /*
        |--------------------------------------------------------------------------
        | PAN VERIFICATION CONFIG
        |--------------------------------------------------------------------------
        */
        $pan_verification_enabled = $is_enabled;

        // Static bearer token
        $token = 'RPAvp18BUwFLhXA0gndlmmubi57qnOxh2tYZnJTS';

        /*
        |--------------------------------------------------------------------------
        | Verification disabled
        |--------------------------------------------------------------------------
        */
        if ($pan_verification_enabled != 1) {
            return [
                'success' => true,
                'message' => 'PAN verification is disabled. Skipped.',
                'response' => null,
                'skipped' => true,
            ];
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | Format DOB
            |--------------------------------------------------------------------------
            */
            $formattedDob = null;

            if (!empty($dob)) {
                try {
                    $formattedDob = Carbon::createFromFormat(
                        'Y-m-d',
                        $dob
                    )->format('d/m/Y');
                } catch (\Throwable $e) {

                    \Log::error('PAN DOB formatting failed', [
                        'dob' => $dob,
                        'error' => $e->getMessage(),
                    ]);

                    return [
                        'success' => false,
                        'message' => 'Invalid date of birth format.',
                        'response' => null,
                        'skipped' => false,
                    ];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | API Request Parameters
            |--------------------------------------------------------------------------
            */
            $formParams = [
                'pan_number' => strtoupper(trim($panNumber)),
                'nameoncard' => trim($fullName),
                'father_name' => '',
                'dob' => $formattedDob,
            ];

            /*
            |--------------------------------------------------------------------------
            | API URL
            |--------------------------------------------------------------------------
            */
            $baseUrl = rtrim(env('PRODUCTION_API_URL'), '/');

            $apiUrl = $baseUrl . '/authentication/pan-verification';

            \Log::info('PAN Verification API Request', [
                'url' => $apiUrl,
                'pan_number' => $formParams['pan_number'],
                'nameoncard' => $formParams['nameoncard'],
                'dob' => $formParams['dob'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | cURL
            |--------------------------------------------------------------------------
            */
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 30,
                CURLOPT_TIMEOUT => 90,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',

                // Important: send as form-data / form encoded
                CURLOPT_POSTFIELDS => $formParams,

                CURLOPT_HTTPHEADER => array(
                    'Authorization:Bearer ' . $token,
                    'Client-Id:5ijgWIhHQaqY3EgKUIhdlPefY1b7V55e',
                    'Client-Secret:Ne2mxikWVV8XzA75'
                ),
            ]);

            $response = curl_exec($curl);

            $error = curl_error($curl);

            $httpCode = curl_getinfo(
                $curl,
                CURLINFO_HTTP_CODE
            );

            curl_close($curl);

            /*
            |--------------------------------------------------------------------------
            | cURL Error
            |--------------------------------------------------------------------------
            */
            if ($error) {

                \Log::error('PAN Verification cURL Error', [
                    'url' => $apiUrl,
                    'error' => $error,
                    'http_code' => $httpCode,
                ]);

                return [
                    'success' => false,
                    'message' => 'Unable to connect to PAN verification service.',
                    'response' => null,
                    'skipped' => false,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Log Raw Response
            |--------------------------------------------------------------------------
            */
            \Log::info('PAN Verification Raw API Response', [
                'http_code' => $httpCode,
                'response' => $response,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Empty Response
            |--------------------------------------------------------------------------
            */
            if (empty($response)) {

                \Log::error('PAN Verification Empty API Response', [
                    'http_code' => $httpCode,
                    'url' => $apiUrl,
                ]);

                return [
                    'success' => false,
                    'message' => 'Empty response received from PAN verification service.',
                    'response' => null,
                    'skipped' => false,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | JSON Decode
            |--------------------------------------------------------------------------
            */
            $responseData = json_decode($response, true);

            if (
                json_last_error() !== JSON_ERROR_NONE ||
                !is_array($responseData)
            ) {

                \Log::error('PAN Verification Invalid JSON Response', [
                    'http_code' => $httpCode,
                    'json_error' => json_last_error_msg(),
                    'raw_response' => $response,
                ]);

                return [
                    'success' => false,
                    'message' => 'Invalid response from PAN verification service.',
                    'response' => null,
                    'skipped' => false,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Return API Response
            |--------------------------------------------------------------------------
            */
            \Log::info('PAN Verification API Response', [
                'http_code' => $httpCode,
                'response' => $responseData,
            ]);

            return [
                'success' => true,
                'message' => 'PAN verification API executed successfully.',
                'response' => $responseData,
                'skipped' => false,
            ];

        } catch (\Throwable $e) {

            \Log::error('PAN Verification Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => 'PAN verification failed due to technical error.',
                'response' => null,
                'skipped' => false,
            ];
        }
    }

    public function verifyPan(Request $request)
    {
        try {

            $validated = $request->validate([
                'pan_number' => ['required', 'string', 'regex:/^[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}$/'],
                'name' => ['required', 'string'],
                'dob' => ['required', 'date'],
            ]);

            $panNumber = strtoupper(trim($validated['pan_number']));
            $name = trim($validated['name']);
            $dob = $validated['dob'];

            \Log::info('PAN verification requested', [
                'pan_number' => $panNumber,
                'name' => $name,
                'dob' => $dob,
            ]);

            // ✅ Call the single verification function
            $verification = $this->verifyPanWithThirdParty($panNumber, $name, $dob);

            // ✅ If verification was skipped (config = 0), just return success
            if (!empty($verification['skipped'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'PAN verification is disabled.',
                    'skipped' => true,
                ]);
            }

            // ❌ Technical failure (API down, network issue, etc.)
            if (!$verification['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $verification['message'],
                ]);
            }

            $response = $verification['response'];

            // ❌ API response code check
            if (!isset($response['data']['respcode']) || $response['data']['respcode'] != 200) {
                return response()->json([
                    'success' => false,
                    'message' => 'PAN verification failed. Please enter a valid PAN.',
                ]);
            }

            // ❌ Name mismatch
            if (($response['data']['panname'] ?? 'N') !== 'Y') {
                return response()->json([
                    'success' => false,
                    'message' => 'Name does not match the name mentioned on the PAN card.',
                ]);
            }

            // ❌ DOB mismatch
            if (($response['data']['dob'] ?? 'N') !== 'Y') {
                return response()->json([
                    'success' => false,
                    'message' => 'Date of birth does not match the date mentioned on the PAN card.',
                ]);
            }

            // ✅ All good
            return response()->json([
                'success' => true,
                'message' => 'PAN verified successfully.',
                'pan_number' => $panNumber,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
            ]);

        } catch (\Throwable $e) {
            \Log::error('PAN verification controller exception', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to verify PAN at the moment. Please try again.',
            ]);
        }
    }




}