<?php

namespace App\Http\Controllers\institute\Admin\AdminController;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\FincapMerchantSubCategories;
use App\Models\InstituteBasicDetails;
use App\Models\AuthorizedUserDocument;
use App\Models\FincapMerchant;
use App\Models\AuthorizedUser;
use Illuminate\Support\Facades\Auth;
use App\models\Allsubcategories;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\EmployeeDetails;
use App\Models\NotificationModule;
use App\Models\StudentParentDetails;
use App\Models\InstituteNotificationSetting;
use App\Models\User;
use Carbon\Carbon;

class AdminSettingsController extends Controller
{
    public function adminSettingsController()
    {
        $merchantId = auth()->user()->institute_id;
        $userId = auth()->id();
      
        $fincapMerchants = InstituteBasicDetails::where('fincap_merchant_id', $merchantId)
            ->with(['stakeholders','stakeholderDocuments','authorizedUser','authorizedUserDocuments', 'documents','beneficiary'])
            ->first();
            
        $authorizedUserDocuments = DB::table('authorized_user_documents as aud')
            ->leftjoin('authorized_users as au', 'aud.authorized_user_id', '=', 'au.id')
            ->where('aud.id', $userId)
            ->select('aud.*', 'au.id')
            ->first();
      
        $authorizedUser = AuthorizedUser::where('institute_id', $merchantId)->orWhere('user_id', $userId)
            ->first();

        // Generate CAPTCHA for password change
        $captchaText = $this->generateCaptcha();
        session(['admin_captcha_text' => $captchaText]);

        return view('instituteAdmin.DashboardFiles.Setting', compact('fincapMerchants','authorizedUser','authorizedUserDocuments', 'captchaText'));
    }

    /**
     * Generate random CAPTCHA text
     */
    private function generateCaptcha($length = 6)
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $captcha = '';
        for ($i = 0; $i < $length; $i++) {
            $captcha .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $captcha;
    }

    /**
     * Refresh CAPTCHA
     */
    public function refreshCaptcha()
    {
        $captchaText = $this->generateCaptcha();
        session(['admin_captcha_text' => $captchaText]);
        
        return response()->json([
            'success' => true,
            'captcha_text' => $captchaText
        ]);
    }

    /**
     * Search for employee by employee code, name, or email
     * Includes department and designation from employee details
     */
    public function searchEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string|min:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $search = $request->search;
        $instituteId = auth()->user()->institute_id;
        $branchId = auth()->user()->branch_id;

        // Build query with joins to get department and designation names
        $employeesQuery = EmployeeDetails::where('employee_details.institute_id', $instituteId)
            ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.designation_id')
            ->where(function($query) use ($search) {
                $query->where('employee_details.employee_code', 'LIKE', "%{$search}%")
                    ->orWhere('employee_details.name', 'LIKE', "%{$search}%")
                    ->orWhere('employee_details.email', 'LIKE', "%{$search}%")
                    ->orWhere('employee_details.mobile_number', 'LIKE', "%{$search}%");
            });

        // Apply branch filter if branch admin
        if ($branchId) {
            $employeesQuery->where('employee_details.branch_id', $branchId);
        }

        $employees = $employeesQuery->select(
                'employee_details.*',
                'departments.department as department_name',
                'designations.designations as designation_name'
            )
            ->limit(10)
            ->get();

        if ($employees->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No employees found matching your search.'
            ]);
        }

        $results = $employees->map(function($employee) {
            return [
                'id' => $employee->employee_id,
                'employee_id' => $employee->employee_id,
                'employee_code' => $employee->employee_code,
                'name' => $employee->name,
                'email' => $employee->email,
                'phone' => $employee->mobile_number,
                'department_id' => $employee->department_id,
                'department' => $employee->department_name ?? $employee->department ?? 'N/A',
                'designation_id' => $employee->designation_id,
                'designation' => $employee->designation_name ?? $employee->designation ?? 'N/A',
                'gender' => $employee->gender ?? 'N/A',
                'dob' => $employee->dob ? Carbon::parse($employee->dob)->format('d-m-Y') : 'N/A',
                'employment_type' => $employee->employment_type ?? 'N/A',
                'doj' => $employee->doj ? Carbon::parse($employee->doj)->format('d-m-Y') : 'N/A',
                'user_id' => $employee->user_id,
                'display' => $employee->employee_code . ' - ' . $employee->name
            ];
        });

        return response()->json([
            'success' => true,
            'employees' => $results
        ]);
    }

    /**
     * Search for student by registration number, name, or email
     * Includes academic details, department, section names
     */
     public function searchStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string|min:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $search = $request->search;
        $instituteId = auth()->user()->institute_id;
        $branchId = auth()->user()->branch_id;

        // Get students with academic transport details
        $students = StudentParentDetails::where('institute_id', $instituteId)
            ->where(function($query) use ($search) {
                $query->where('registration_number', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$search}%");
            })
            ->with(['academicTransportDetails', 'user'])
            ->limit(10)
            ->get();

        if ($students->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No students found matching your search.'
            ]);
        }

        // Get all course IDs from academic details
        $courseIds = $students->filter(function($student) {
            return $student->academicTransportDetails && $student->academicTransportDetails->course_subtype_id;
        })->map(function($student) {
            return $student->academicTransportDetails->course_subtype_id;
        })->unique()->values();

        // Get section names from course_fee_structures
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

        $results = $students->map(function($student) use ($sectionDataMap) {
            $academic = $student->academicTransportDetails;
            
            // Get section name if academic details exist
            $sectionName = null;
            if ($academic && $academic->course_subtype_id && $academic->section_id) {
                $lookupKey = $academic->course_subtype_id . '_' . $academic->section_id;
                $sectionName = $sectionDataMap[$lookupKey] ?? null;
            }

            // Get department name
            $departmentName = null;
            if ($academic && $academic->department) {
                $departmentName = $academic->department;
            }

            return [
                'id' => $student->student_hash_id,
                'registration_no' => $student->registration_number,
                'name' => $student->first_name . ' ' . $student->last_name,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'phone' => $student->mobile,
                'class' => $academic->course_type ?? 'N/A',
                'course_name' => $academic->course_subtype ?? 'N/A',
                'section_id' => $academic->section_id ?? null,
                'section_name' => $sectionName ?? 'N/A',
                'department_name' => $departmentName ?? 'N/A',
                'batch' => $academic->batch ?? 'N/A',
                'academic_year' => $academic->academic_year ?? 'N/A',
                'mode_of_course' => $academic->mode_of_course ?? 'N/A',
                'user_id' => $student->user_id,
                'display' => $student->registration_number . ' - ' . $student->first_name . ' ' . $student->last_name
            ];
        });

        return response()->json([
            'success' => true,
            'students' => $results
        ]);
    }

    /**
     * Change admin's own password
     */
    public function changeAdminPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
            'captcha' => 'required|string'
        ], [
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'captcha.required' => 'CAPTCHA code is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify CAPTCHA
        if (strtolower($request->captcha) !== strtolower(session('admin_captcha_text'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid CAPTCHA code. Please try again.'
            ], 422);
        }

        $user = Auth::user();

        try {
            DB::beginTransaction();

            $user->password = Hash::make($request->new_password);
            $user->save();

            // Log the password change
            DB::table('password_change_logs')->insert([
                'user_id' => $user->id,
                'user_type' => 'admin',
                'changed_by' => $user->id,
                'changed_at' => now()
            ]);

            // Send notification email
            $this->sendPasswordChangeNotification($user->email, $user->name, 'Admin', $request->new_password);

            // Clear CAPTCHA session
            session()->forget('admin_captcha_text');
            
            // Generate new CAPTCHA
            $newCaptcha = $this->generateCaptcha();
            session(['admin_captcha_text' => $newCaptcha]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Your password has been updated successfully!',
                'new_captcha' => $newCaptcha
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Admin password update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password. Please try again.'
            ], 500);
        }
    }

    /**
     * Change employee password (by admin)
     */
    public function changeEmployeePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
            'captcha' => 'required|string'
        ], [
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'captcha.required' => 'CAPTCHA code is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify CAPTCHA
        if (strtolower($request->captcha) !== strtolower(session('admin_captcha_text'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid CAPTCHA code. Please try again.'
            ], 422);
        }

        // Get employee with manual join to users table
        $employee = DB::table('employee_details')
            ->leftJoin('users', 'employee_details.user_id', '=', 'users.id')
            ->where('employee_details.employee_id', $request->employee_id)
            ->select(
                'employee_details.*',
                'users.id as user_table_id',
                'users.email as user_email',
                'users.password as current_password'
            )
            ->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        // Check if employee has a user account
        if (!$employee->user_table_id) {
            return response()->json([
                'success' => false,
                'message' => 'Employee does not have a user account. Please create user account first.'
            ], 404);
        }

        // try {
            DB::beginTransaction();

            // Update password directly in users table using manual update
            DB::table('users')
                ->where('id', $employee->user_table_id)
                ->update([
                    'password' => Hash::make($request->new_password),
                    'updated_at' => now()
                ]);

            // Log the password change
            DB::table('password_change_logs')->insert([
                'institute_id' => auth()->user()->institute_id,
                'user_id' => $employee->user_table_id,
                'user_type' => 'employee',
                'employee_id' => $employee->employee_id,
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->name,
                'changed_by' => auth()->id(),
                'changed_by_name' => auth()->user()->name,
                'changed_at' => now(),
                'created_at' => now()
            ]);

            // Send notification email to employee
            $employeeEmail = $employee->email ?? $employee->user_email;
            $employeeName = $employee->name;
            
            if ($employeeEmail) {
                $this->sendPasswordChangeNotification($employeeEmail, $employeeName, 'Employee', $request->new_password);
            }

            // Clear CAPTCHA session
            session()->forget('admin_captcha_text');
            
            // Generate new CAPTCHA
            $newCaptcha = $this->generateCaptcha();
            session(['admin_captcha_text' => $newCaptcha]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee password has been updated successfully! An email with the new password has been sent to the employee.',
                'new_captcha' => $newCaptcha
            ]);
            
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     \Log::error('Employee password update error: ' . $e->getMessage(), [
        //         'employee_id' => $request->employee_id,
        //         'user_table_id' => $employee->user_table_id ?? null,
        //         'error' => $e->getMessage()
        //     ]);
            
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to update employee password. Please try again. Error: ' . $e->getMessage()
        //     ], 500);
        // }
    }

   /**
     * Change student password (by admin)
     */
    public function changeStudentPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
            'captcha' => 'required|string'
        ], [
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'captcha.required' => 'CAPTCHA code is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify CAPTCHA
        if (strtolower($request->captcha) !== strtolower(session('admin_captcha_text'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid CAPTCHA code. Please try again.'
            ], 422);
        }

        // Get student with manual join to users table
        $student = DB::table('student_parent_details')
            ->leftJoin('users', 'student_parent_details.user_id', '=', 'users.id')
            ->where('student_parent_details.student_hash_id', $request->student_id)
            ->select(
                'student_parent_details.*',
                'users.id as user_table_id',
                'users.email as user_email',
                'users.password as current_password'
            )
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found.'
            ], 404);
        }

        // Check if student has a user account
        if (!$student->user_table_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student does not have a user account. Please create user account first.'
            ], 404);
        }

        // try {
            DB::beginTransaction();

            // Update password directly in users table using manual update
            DB::table('users')
                ->where('id', $student->user_table_id)
                ->update([
                    'password' => Hash::make($request->new_password),
                    'updated_at' => now()
                ]);

            // Log the password change
            DB::table('password_change_logs')->insert([
                'institute_id' => auth()->user()->institute_id,
                'user_id' => $student->user_table_id,
                'user_type' => 'student',
                'student_id' => $student->student_hash_id,
                'student_registration_no' => $student->registration_number,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'changed_by' => auth()->id(),
                'changed_by_name' => auth()->user()->name,
                'changed_at' => now(),
                'created_at' => now()
            ]);

            // Send notification email to student
            $studentEmail = $student->email ?? $student->user_email;
            $studentName = $student->first_name . ' ' . $student->last_name;
            
            if ($studentEmail) {
                $this->sendPasswordChangeNotification($studentEmail, $studentName, 'Student', $request->new_password);
            }

            // Clear CAPTCHA session
            session()->forget('admin_captcha_text');
            
            // Generate new CAPTCHA
            $newCaptcha = $this->generateCaptcha();
            session(['admin_captcha_text' => $newCaptcha]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student password has been updated successfully! An email with the new password has been sent to the student.',
                'new_captcha' => $newCaptcha
            ]);
            
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     \Log::error('Student password update error: ' . $e->getMessage(), [
        //         'student_id' => $request->student_id,
        //         'user_table_id' => $student->user_table_id ?? null,
        //         'error' => $e->getMessage()
        //     ]);
            
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to update student password. Please try again. Error: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Send password change notification email
     */
    private function sendPasswordChangeNotification($email, $name, $userType, $password = null)
    {
        try {
            $data = [
                'name' => $name,
                'userType' => $userType,
                'title' => 'Password Changed Notification',
                'changed_at' => Carbon::now('Asia/Kolkata')->format('d-m-Y H:i:s'),
                'changed_by' => auth()->user()->name ?? 'Administrator',
                'password' => $password
            ];

            Mail::send('emails.password-changed-notification', $data, function ($message) use ($email, $data) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($email)
                    ->subject($data['title']);
            });

            \Log::info('Password change notification sent to: ' . $email);
        } catch (\Exception $e) {
            \Log::error('Failed to send password change notification: ' . $e->getMessage());
        }
    }

    public function uploadAuthorizedPhoto(Request $request)
    {
        $request->validate([
            'authorized_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);
    
        $userId = auth()->id();
        
        $merchantId = auth()->user()->institute_id;
    
        // ✅ CORRECT MODEL & TABLE
        $document = AuthorizedUserDocument::where('id', $userId)
            ->orWhere('institute_id', $merchantId)
            ->first();

        if ($document->authorized_photo &&
            Storage::disk('public')->exists($document->authorized_photo)) {
            Storage::disk('public')->delete($document->authorized_photo);
        }
    
        // Store new image
        $path = $request->file('authorized_photo')
            ->store('authorized_users', 'public');
    
        // ✅ SAVE TO authorized_user_documents TABLE
        $document->authorized_photo = $path;
        $document->save();

        return response()->json([
            'success' => true,
            'image_url' => asset('storage/' . $path)
        ]);
    }

    public function deleteAuthorizedPhoto()
    {
        $authorizedUser = AuthorizedUser::where('user_id', auth()->id())->first();

        if ($authorizedUser && $authorizedUser->authorized_photo) {
            Storage::disk('public')->delete($authorizedUser->authorized_photo);
            $authorizedUser->authorized_photo = null;
            $authorizedUser->save();
        }

        return response()->json(['success' => true]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->save();
        
        return response()->json(['success' => true, 'message' => 'Profile updated']);
    }

    /**
     * Get notification settings for the institute
     * Now fetches dynamically from database with proper institute filtering
     */
    public function getNotificationSettings()
    {
        $instituteId = auth()->user()->institute_id;
        
        // Get ALL active modules for this institute with their current settings
        $allModules = NotificationModule::where('is_active', true)
            ->where('institute_id', $instituteId)  // CRITICAL: Filter by institute_id
            ->get();
        
        // Get existing settings for this institute
        $existingSettings = InstituteNotificationSetting::where('institute_id', $instituteId)
            ->get()
            ->keyBy('module_name');
        
        // Prepare modules with their current settings
        $modulesByCategory = [];
        foreach ($allModules as $module) {
            $settings = $existingSettings->get($module->module_name);
            
            $modulesByCategory[$module->category][] = [
                'module_name' => $module->module_name,
                'module_display_name' => $module->module_display_name,
                'description' => $module->description,
                'is_mandatory' => (bool) $module->is_mandatory,
                'email_enabled' => $settings ? (bool) $settings->email_enabled : ($module->is_mandatory ? true : false),
                'whatsapp_enabled' => $settings ? (bool) $settings->whatsapp_enabled : false,
                'sms_enabled' => $settings ? (bool) $settings->sms_enabled : false,
            ];
        }
        
        // Get categories for UI organization
        $categories = NotificationModule::where('is_active', true)
            ->where('institute_id', $instituteId)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
        
        return response()->json([
            'success' => true,
            'modules' => $modulesByCategory,
            'categories' => $categories
        ]);
    }


    /**
     * Save notification settings
     */
    public function saveNotificationSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.module_name' => 'required|string',
            'settings.*.email_enabled' => 'boolean',
            'settings.*.whatsapp_enabled' => 'boolean',
            'settings.*.sms_enabled' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $instituteId = auth()->user()->institute_id;
        
        try {
            DB::beginTransaction();
            
            foreach ($request->settings as $setting) {
                // Get module and verify it belongs to this institute
                $module = NotificationModule::where('module_name', $setting['module_name'])
                    ->where('institute_id', $instituteId)
                    ->first();
                
                if (!$module) {
                    throw new \Exception("Module '{$setting['module_name']}' not found for this institute");
                }
                
                // For mandatory modules, email must be enabled
                if ($module->is_mandatory) {
                    $setting['email_enabled'] = true;
                }
                
                InstituteNotificationSetting::updateOrCreate(
                    [
                        'institute_id' => $instituteId,
                        'module_name' => $setting['module_name']
                    ],
                    [
                        'email_enabled' => $setting['email_enabled'] ?? false,
                        'whatsapp_enabled' => $setting['whatsapp_enabled'] ?? false,
                        'sms_enabled' => $setting['sms_enabled'] ?? false,
                    ]
                );
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification settings saved successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Save notification settings error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save notification settings: ' . $e->getMessage()
            ], 500);
        }
    }

}