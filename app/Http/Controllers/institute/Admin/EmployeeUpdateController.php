<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\EmployeeDetailsLog;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\Designations;
use App\Models\InstituteBasicDetails;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EmployeeUpdateController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Show edit employee form
     */
    public function edit($id)
    {
        // Get institute/branch context
        $context = $this->getInstituteBranchContext();
        
        // Get employee with proper access control
        $employee = EmployeeDetails::where('employee_id', $id)
            ->where('institute_id', $context['institute_id'])
            ->first();

        if (!$employee) {
            return redirect()->route('employees.index')
                ->with('error', 'Employee not found or you do not have access.');
        }

        // Branch admin access restriction
        if ($context['is_branch_admin'] && $context['branch_id']) {
            if ($employee->branch_id != $context['branch_id']) {
                return redirect()->route('employees.index')
                    ->with('error', 'You do not have access to this employee.');
            }
        }

        // Get all necessary data for the form
        $categories = DepartmentCategory::all();
        $departments = Departments::all();
        $designations = Designations::all();
        
        // Get change history for the employee
        $changeLogs = EmployeeDetailsLog::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $changeCount = EmployeeDetailsLog::where('employee_id', $employee->id)->count();

        return view('instituteAdmin.EmployeeFiles.EditEmployeeDetails', compact(
            'employee', 
            'categories', 
            'departments', 
            'designations',
            'changeLogs',
            'changeCount'
        ));
    }

    /**
     * Update employee details with full logging
     */
    public function update(Request $request, $id)
    {
        $employee = EmployeeDetails::where('employee_id', $id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        // Get institute/branch context
        $context = $this->getInstituteBranchContext();

        // Check if this is a partial update (step-by-step)
        if ($request->has('is_partial_update') && $request->input('is_partial_update') == 'true') {
            return $this->updatePartial($request, $id);
        }

        // Full update - get all request data except files & tokens
        $data = $request->except([
            '_token',
            '_method',
            'aadhaar_card',
            'pan_card',
            'address_proof_file',
            'documents',
            'profile_photo',
            'upload_signature',
            'driving_license',
            'passport_photo'
        ]);

        DB::beginTransaction();

        try {
            // Store old values before update for logging
            $oldValues = $employee->getAttributes();
            
            // Handle file uploads
            $this->handleFileUploads($request, $data);

            // Handle additional documents
            $this->handleAdditionalDocuments($request, $employee, $data);

            // Update the employee
            $employee->update($data);

            // Log all changes
            $this->logEmployeeChanges($employee, $oldValues, $data, $context);

            // Handle employment type change (probation to full-time)
            if (isset($data['employment_type']) && 
                $oldValues['employment_type'] === 'Probation-Period' && 
                $data['employment_type'] === 'Full-time') {
                $this->handleProbationCompletion($employee, $oldValues, $context);
            }

            DB::commit();

            return redirect()->route('employees.index')
                ->with('success', 'Employee updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Employee update error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
    }

    /**
     * Partial update for step-by-step form
     */
    public function updatePartial(Request $request, $id)
    {
        try {
            $employee = EmployeeDetails::where('employee_id', $id)->first();

            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found']);
            }

            $context = $this->getInstituteBranchContext();
            $currentStep = $request->input('current_step', 1);
            $data = [];
            $updatedValues = [];

            // Store old values before update
            $oldValues = $employee->getAttributes();

            // Process based on step
            switch ($currentStep) {
                case 1: // Basic Details
                    $data = $this->processStep1($request, $employee);
                    break;
                case 2: // Professional Details
                    $data = $this->processStep2($request, $employee);
                    break;
                case 3: // Contact Details
                    $data = $this->processStep3($request);
                    break;
                case 4: // Documents
                    $data = $this->processStep4($request, $employee);
                    break;
                case 5: // Bank Details
                    $data = $this->processStep5($request);
                    break;
            }

            if (empty($data)) {
                return response()->json([
                    'success' => true,
                    'message' => 'No changes detected'
                ]);
            }

            // Update employee
            $employee->update($data);

            // Log changes
            $this->logEmployeeChanges($employee, $oldValues, $data, $context, $currentStep);

            // Handle employment type change in step 2
            if ($currentStep == 2 && isset($data['employment_type'])) {
                $this->handleEmploymentTypeChange($employee, $oldValues, $context);
            }

            // Refresh employee to get updated values
            $employee->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Step saved successfully',
                'updated_values' => $updatedValues
            ]);

        } catch (\Exception $e) {
            \Log::error('Partial update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving step: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process Step 1: Basic Details
     */
    private function processStep1(Request $request, $employee)
    {
        $data = [];
        $fields = [
            'department_category_id', 'department_id', 'name', 'designation_id',
            'mobile_number', 'email', 'gender', 'dob', 'blood_group',
            'nationality', 'religion', 'addressline1', 'addressline2',
            'state', 'city', 'pincode', 'designation', 'assigned_role',
            'marital_status', 'number_of_dependents', 'spouse_name'
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        // Handle file uploads for step 1
        $fileFields = ['profile_photo', 'upload_signature', 'aadhaar_card', 'pan_card'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = time() . '_' . $field . '_' . $file->getClientOriginalName();
                $data[$field] = $file->storeAs('employee_documents', $fileName, 'public');
            }
        }

        return $data;
    }

    /**
     * Process Step 2: Professional Details
     */
    private function processStep2(Request $request, $employee)
    {
        $data = [];
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

        return $data;
    }

    /**
     * Process Step 3: Contact Details
     */
    private function processStep3(Request $request)
    {
        $data = [];
        $fields = [
            'emergency_contact_number', 'contact_person_name',
            'relation_with_contact', 'reference_name', 'reference_contact_number'
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        return $data;
    }

    /**
     * Process Step 4: Documents
     */
    private function processStep4(Request $request, $employee)
    {
        $data = [];
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
            $this->handleAdditionalDocuments($request, $employee, $data);
        }

        return $data;
    }

    /**
     * Process Step 5: Bank Details
     */
    private function processStep5(Request $request)
    {
        $data = [];
        $fields = ['bank_name', 'branch_name', 'account_number', 'ifsc_code'];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $data[$field] = $request->input($field);
            }
        }

        return $data;
    }

    /**
     * Handle file uploads for various fields
     */
    private function handleFileUploads(Request $request, &$data)
    {
        $fileFields = [
            'profile_photo', 'upload_signature', 'aadhaar_card', 
            'pan_card', 'driving_license', 'passport_photo', 
            'address_proof_file'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $fileName = time() . '_' . $field . '_' . $file->getClientOriginalName();
                $data[$field] = $file->storeAs('employee_documents', $fileName, 'public');
            }
        }
    }

    /**
     * Handle additional documents
     */
    private function handleAdditionalDocuments(Request $request, $employee, &$data)
    {
        $additionalDocuments = [];
        $existingDocs = json_decode($employee->additional_documents ?? '[]', true) ?: [];

        foreach ($request->documents as $index => $document) {
            if (empty($document['name'])) continue;

            $docData = [
                'name' => $document['name'] ?? null,
                'number' => $document['number'] ?? null,
            ];

            if ($request->hasFile("documents.{$index}.file")) {
                $file = $request->file("documents.{$index}.file");
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

    /**
     * Log employee changes with detailed tracking
     */
    private function logEmployeeChanges($employee, $oldValues, $newData, $context, $step = null)
    {
        try {
            $changes = [];
            $changeLogs = [];

            // Get the actual old values from the employee model before update
            $oldAttributes = $oldValues;
            
            // Get the new values
            $newAttributes = array_merge($oldAttributes, $newData);

            // Compare each field
            foreach ($newData as $field => $newValue) {
                $oldValue = $oldAttributes[$field] ?? null;

                // Skip fields that are the same or null values
                if ($oldValue == $newValue) {
                    continue;
                }

                // Handle file fields - don't compare file contents, just log they were updated
                if (in_array($field, ['profile_photo', 'upload_signature', 'aadhaar_card', 'pan_card', 
                                      'driving_license', 'passport_photo', 'address_proof_file'])) {
                    if ($newValue !== $oldValue) {
                        $changes[] = [
                            'field' => $field,
                            'old_value' => $oldValue ?? 'No file',
                            'new_value' => $newValue ?? 'New file uploaded',
                        ];
                        
                        $changeLogs[] = "Updated {$field}";
                    }
                    continue;
                }

                // Skip JSON fields that need special handling
                if ($field === 'additional_documents') {
                    if ($newValue !== $oldValue) {
                        $changes[] = [
                            'field' => $field,
                            'old_value' => $oldValue ? json_decode($oldValue, true) : [],
                            'new_value' => $newValue ? json_decode($newValue, true) : [],
                        ];
                        $changeLogs[] = "Updated additional documents";
                    }
                    continue;
                }

                // Skip sensitive fields
                if (in_array($field, ['password', 'remember_token'])) {
                    continue;
                }

                // Standard field change
                $changes[] = [
                    'field' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                ];

                // Create human-readable change log
                $fieldLabel = str_replace('_', ' ', ucwords($field));
                $changeLogs[] = "Changed {$fieldLabel} from '{$oldValue}' to '{$newValue}'";
            }

            // Only log if there are actual changes
            if (empty($changes)) {
                return;
            }

            // Create the log entry
            $log = EmployeeDetailsLog::create([
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->name,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'] ?? null,
                'changed_by' => auth()->id(),
                'changed_by_name' => auth()->user()->name ?? 'System',
                'changes' => json_encode($changes),
                'change_summary' => implode('; ', $changeLogs),
                'change_count' => count($changes),
                'step' => $step,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);

            \Log::info('Employee changes logged', [
                'employee_id' => $employee->id,
                'change_count' => count($changes),
                'log_id' => $log->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to log employee changes: ' . $e->getMessage());
        }
    }

    /**
     * Handle employment type change (probation to full-time)
     */
    private function handleEmploymentTypeChange($employee, $oldValues, $context)
    {
        if ($oldValues['employment_type'] === 'Probation-Period' && 
            $employee->employment_type === 'Full-time') {
            $this->handleProbationCompletion($employee, $oldValues, $context);
        }
    }

    /**
     * Handle probation completion and promotion
     */
    private function handleProbationCompletion($employee, $oldValues, $context)
    {
        try {
            // Calculate probation end date
            $probationEndDate = null;
            if (isset($oldValues['doj']) && isset($oldValues['probation_days'])) {
                $probationEndDate = Carbon::parse($oldValues['doj'])->addDays($oldValues['probation_days']);
            }

            // Log probation completion
            $this->logProbationCompletion($employee, $oldValues, $probationEndDate, $context);

            // Update promotion date
            $employee->promotion_date = Carbon::now();
            $employee->save();

            // Send notification if enabled
            $this->sendProbationCompletionNotification($employee, $probationEndDate, $context);

        } catch (\Exception $e) {
            \Log::error('Error handling probation completion: ' . $e->getMessage());
        }
    }

    /**
     * Log probation completion
     */
    private function logProbationCompletion($employee, $oldValues, $probationEndDate, $context)
    {
        try {
            $logData = [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
                'employee_name' => $employee->name,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'] ?? null,
                'doj' => $oldValues['doj'] ?? null,
                'probation_days' => $oldValues['probation_days'] ?? null,
                'probation_start_date' => $oldValues['doj'] ?? null,
                'probation_end_date' => $probationEndDate,
                'employment_type_before' => 'Probation-Period',
                'employment_type_after' => 'Full-time',
                'promotion_date' => Carbon::now(),
                'promoted_by' => auth()->user()->name ?? 'System',
                'promoted_by_user_id' => auth()->id(),
                'promotion_type' => 'update',
                'department_before' => $oldValues['department_id'] ?? null,
                'designation_before' => $oldValues['designation'] ?? null,
                'additional_data' => json_encode([
                    'changed_from' => 'employee_update',
                    'changed_at' => Carbon::now()->toDateTimeString(),
                    'old_probation_days' => $oldValues['probation_days'] ?? null,
                    'new_probation_days' => $employee->probation_days,
                    'old_doj' => $oldValues['doj'] ?? null,
                    'new_doj' => $employee->doj,
                    'is_promotion' => true
                ])
            ];

            \App\Models\EmployeeProbationLog::create($logData);

            \Log::info('Probation completion logged', ['employee_id' => $employee->id]);

        } catch (\Exception $e) {
            \Log::error('Failed to log probation completion: ' . $e->getMessage());
        }
    }

    /**
     * Send probation completion notification
     */
    private function sendProbationCompletionNotification($employee, $probationEndDate, $context)
    {
        try {
            // Check if notifications are enabled
            $emailEnabled = $this->isNotificationEnabled($context['institute_id'], 'employee_probation_end', 'email');
            
            if (!$emailEnabled || empty($employee->email)) {
                return;
            }

            // Prepare notification data
            $departmentName = 'N/A';
            if ($employee->department_id) {
                $department = Departments::where('department_id', $employee->department_id)->first();
                $departmentName = $department ? $department->department : 'N/A';
            }

            $institute = InstituteBasicDetails::where('fincap_merchant_id', $context['institute_id'])->first();
            $instituteName = $institute ? $institute->name : 'Institute';

            // Send email notification
            \Mail::send('emails.employee-probation-completed', [
                'employee' => $employee,
                'departmentName' => $departmentName,
                'designationName' => $employee->designation ?? 'N/A',
                'dateOfJoining' => $employee->doj ? Carbon::parse($employee->doj)->format('d-m-Y') : 'N/A',
                'probationEndDate' => $probationEndDate ? $probationEndDate->format('d-m-Y') : 'N/A',
                'promotionDate' => Carbon::now()->format('d-m-Y'),
                'companyName' => $instituteName
            ], function ($message) use ($employee) {
                $message->to($employee->email)
                        ->subject('Congratulations! Your Probation Period Has Ended');
            });

            \Log::info('Probation completion email sent', ['employee_id' => $employee->id]);

        } catch (\Exception $e) {
            \Log::error('Failed to send probation completion notification: ' . $e->getMessage());
        }
    }

    /**
     * Check if a specific notification channel is enabled
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        try {
            $setting = \App\Models\InstituteNotificationSetting::where('institute_id', $instituteId)
                ->where('module_name', $moduleName)
                ->first();
            
            if (!$setting) {
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
     * Get employee change history
     */
    public function getChangeHistory($id)
    {
        $employee = EmployeeDetails::where('employee_id', $id)->first();
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }

        $logs = EmployeeDetailsLog::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Get summary of changes for an employee
     */
    public function getChangeSummary($id)
    {
        $employee = EmployeeDetails::where('employee_id', $id)->first();
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }

        $summary = EmployeeDetailsLog::where('employee_id', $employee->id)
            ->select(
                DB::raw('COUNT(*) as total_changes'),
                DB::raw('SUM(change_count) as total_fields_changed'),
                DB::raw('MAX(created_at) as last_change_date'),
                DB::raw('COUNT(DISTINCT changed_by) as unique_changers')
            )
            ->first();

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }
}