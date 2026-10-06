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
use App\Models\DepartmentCategory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\EmployeeProbationLog;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use App\Notifications\ActivityNotification;
use App\Notifications\EmployeeOnboardedNotification;
use App\Traits\SendsInstituteNotifications;
use App\Models\User; 
use App\Exports\EmployeesExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class EmployeeDetailsController extends Controller
{
    use InstituteBranchAccess,DepartmentRelationships; 
    use SendsInstituteNotifications;
    public function AddemployeeDetails(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
        ->with(['stakeholders','stakeholderDocuments','authorizedUser','authorizedUserDocuments', 'documents','beneficiary'])
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
            'categories'   => $categories,
            'departments'  => $departments,
            'designations' => $designations,
            'fincapMerchants' => $fincapMerchants,
            'user_type'    => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
        ]);
    }

    public function storeemployeedetails(Request $request)
    {
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
            // 'aadhaar_number' => 'required|string|regex:/^\d{12}$/|unique:employee_details,aadhaar_number',
            // 'pan_number' => 'required|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|unique:employee_details,pan_number',
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
            // 'aadhaar_card' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            // 'pan_card' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
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
            $fixedFiles = ['profile_photo','upload_signature' ,'aadhaar_card', 'pan_card', 'driving_license', 'passport_photo', 'address_proof_file'];
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
        ->with(['stakeholders','stakeholderDocuments','authorizedUser','authorizedUserDocuments', 'documents','beneficiary'])
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
    $employees = $employeesQuery->with(['activeExit' => function($query) {
        $query->whereIn('exit_status', ['pending_approval', 'notice_period', 'exited', 'approved']);
    }])->latest('employee_details.created_at')->paginate(15);

    // Calculate probation data for each employee
    foreach ($employees as $employee) {
        $employee->probation_status = $employee->getProbationStatusAttribute();
        $employee->probation_days_delta = $employee->getProbationDaysDeltaAttribute();
        $employee->probation_end_date = $employee->getProbationEndDateAttribute();
        $employee->exit_status_display = $this->getExitStatusDisplay($employee);
    }

    return view('instituteAdmin.EmployeeFiles.AddFaculity', [
        'categories'   => $categories,
        'departments'  => $departments,
        'designations' => $designations,
        'employees'    => $employees,
        'employeeCodes'  => $employeeCodes,
        'employeeNames'  => $employeeNames,
        'fincapMerchants' => $fincapMerchants,
        'selectedDeptName' => $selectedDeptName,
        'user_type'    => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
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

    public function getEmployeeDetailsbyID($id)
    {
        $employee = EmployeeDetails::select(
            'employee_details.*',
            'departments.department as department_name'
        )
        ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
        ->where('employee_details.id', $id)
        ->firstOrFail();
        return view('instituteAdmin.EmployeeFiles.ViewEmployee', compact('employee') );
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
        return EmployeeDetails::where('id',
            $id)->first();
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
                    'name'   => $document['name'] ?? null,
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
                    'department_category_id', 'department_id', 'name', 'designation_id',
                    'mobile_number', 'email', 'gender', 'dob', 'blood_group',
                    'nationality', 'religion', 'addressline1', 'addressline2',
                    'state', 'city', 'pincode', 'designation', 'assigned_role'
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
                    'employment_type', 'probation_days', 'salary_type', 'doj',
                    'previous_pf_number', 'esi_number', 'previous_employer_name',
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
                    'emergency_contact_number', 'contact_person_name',
                    'relation_with_contact', 'reference_name', 'reference_contact_number'
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
                    'aadhaar_number', 'pan_number', 'is_address_same',
                    'address_proof_type', 'address_proof_number'
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
                        if (empty($document['name'])) continue;
                        
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
        $validated['branch_id'] = $context['is_branch_admin'] ? $context['branch_id']: null;

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

        }
        elseif (!empty($validated['ids'])) {
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
                'status'      => 'inactive',
                'exit_date'   => now(),
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
                'employee_id'   => $employee->id,
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->name,
                'exit_reason'   => $request->input('exit_reason'),
                'exited_by'     => auth()->id(),
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
        ->whereHas('activeExit', function($query) {
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
        $employees = $employeesQuery->with(['activeExit' => function($query) {
            $query->where('exit_status', 'notice_period');
        }])->paginate(15);

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
            ->whereHas('activeExit', function($query) {
                $query->where('exit_status', 'notice_period')
                    ->whereDate('notice_end_date', Carbon::today());
            })
            ->count();

        // Count overdue notice periods
        $overdue = EmployeeDetails::where('institute_id', $merchantId)
            ->whereHas('activeExit', function($query) {
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
    
    
}