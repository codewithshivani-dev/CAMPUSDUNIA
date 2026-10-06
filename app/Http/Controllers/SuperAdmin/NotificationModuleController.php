<?php
// app/Http/Controllers/SuperAdmin/NotificationModuleController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstituteBasicDetails;
use App\Models\NotificationModule;
use App\Models\InstituteNotificationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationModuleController extends Controller
{
    /**
     * Display module assignment page
     */
    public function index()
    {
        // Get all institutes
        $institutes = InstituteBasicDetails::orderBy('name')->get();
       
        // Get all modules with their assignments
        $modules = NotificationModule::with('institute')
            ->orderBy('category')
            ->orderBy('module_display_name')
            ->get();
        
        // Group modules by category for display
        $modulesByCategory = $modules->groupBy('category');
        
        return view('superadmin.notification-modules.index', compact('institutes', 'modulesByCategory'));
    }
    
    /**
     * Show assign modules form
     */
    public function assignForm()
    {
        $institutes = InstituteBasicDetails::orderBy('name')->get();
        
        // Predefined modules list (simple array)
        $modules = [
            ['name' => 'employee_on_board', 'display_name' => 'Employee On Board', 'category' => 'Employee Management', 'description' => 'Send notification when a new employee is added', 'mandatory' => 0],
            ['name' => 'employee_password_change', 'display_name' => 'Employee Password Change', 'category' => 'Employee Management', 'description' => 'Notify employee when admin changes their password', 'mandatory' => 1],
            ['name' => 'shift_assignment', 'display_name' => 'Shift Assignment', 'category' => 'Employee Management', 'description' => 'Send notification when a new shift is assigned', 'mandatory' => 0],
            ['name' => 'leave_quota_assigned', 'display_name' => 'Leave Quota Assigned', 'category' => 'Employee Management', 'description' => 'Send notification when leave quota is assigned', 'mandatory' => 0],
            ['name' => 'leave_approved', 'display_name' => 'Leave Approved', 'category' => 'Employee Management', 'description' => 'Send notification when leave request is approved',
                'mandatory' => 0],
            ['name' => 'leave_rejected', 'display_name' => 'Leave Rejected', 'category' => 'Employee Management', 'description' => 'Send notification when leave request is rejected',
                'mandatory' => 0],
            ['name' => 'student_on_board', 'display_name' => 'Student On Board', 'category' => 'Student Management', 'description' => 'Send notification when a new student is enrolled', 'mandatory' => 0],
            ['name' => 'student_password_change', 'display_name' => 'Student Password Change', 'category' => 'Student Management', 'description' => 'Notify student when admin changes their password', 'mandatory' => 1],
            ['name' => 'subject_lecture_assignment', 'display_name' => 'Subject & Lecture Assignment', 'category' => 'Academic', 'description' => 'Send notification when subject or lecture is assigned', 'mandatory' => 0],
            ['name' => 'attendance_finalized', 'display_name' => 'Attendance Finalized', 'category' => 'Payroll Management', 'description' => 'Send notification when monthly attendance is finalized', 'mandatory' => 0],
            ['name' => 'salary_finalized', 'display_name' => 'Salary Finalized', 'category' => 'Payroll Management', 'description' => 'Send notification when salary is finalized',
                'mandatory' => 0],
            ['name' => 'payroll_executed', 'display_name' => 'Payroll Executed', 'category' => 'Payroll Management', 'description' => 'Send notification when payroll is executed',
                'mandatory' => 0],
            ['name' => 'lecture_reassignment', 'display_name' => 'Lecture Reassignment', 'category' => 'Academic', 'description' => 'Send notification to employees when a lecture is reassigned', 'mandatory' => 0],
            ['name' => 'student_suspension', 'display_name' => 'Student Suspension', 'category' => 'Student Management', 'description' => 'Send notification when a student is suspended', 'mandatory' => 0],
            ['name' => 'student_unsuspension', 'display_name' => 'Student Unsuspension', 'category' => 'Student Management', 'description' => 'Send notification when a student is unsuspended', 'mandatory' => 0],
            ['name' => 'student_exit', 'display_name' => 'Student Exit', 'category' => 'Student Management', 'description' => 'A student has been marked as exited by the admin', 
                'mandatory' => 1],
            ['name' => 'employee_probation_end', 'display_name' => 'Employee Probation End', 'category' => 'Employee Management', 'description' => 'Notify employee when their probation period ends', 'mandatory' => 1],
            ['name' => 'employee_suspension', 'display_name' => 'Employee Suspension', 'category' => 'Employee Management', 'description' => 'Send notification when a  employee is suspended', 'mandatory' => 0],
            ['name' => 'employee_unsuspension', 'display_name' => 'Employee Unsuspension', 'category' => 'Employee Management', 'description' => 'Send notification when a employee is unsuspended', 'mandatory' => 0],
            ['name' => 'employee_exit', 'display_name' => 'Employee Exit', 'category' => 'Employee Management', 'description' => 'A Employee has been marked as exited by the admin',
                'mandatory' => 1],
                
            ['name' => 'employee_exit_initiated', 'display_name' => 'Employee Exit Initiated', 'category' => 'Employee Exit', 'description' => 'Send notification when an exit process is initiated', 'mandatory' => 1],
            ['name' => 'employee_exit_approved', 'display_name' => 'Employee Exit Approved', 'category' => 'Employee Exit', 'description' => 'Send notification when an exit request is approved', 'mandatory' => 1],
            ['name' => 'employee_exit_rejected', 'display_name' => 'Employee Exit Rejected', 'category' => 'Employee Exit', 'description' => 'Send notification when an exit request is rejected', 'mandatory' => 1],
            ['name' => 'employee_exit_completed', 'display_name' => 'Employee Exit Completed', 'category' => 'Employee Exit', 'description' => 'Send notification when an exit process is completed', 'mandatory' => 1],
            ['name' => 'employee_exit_cancelled', 'display_name' => 'Employee Exit Cancelled', 'category' => 'Employee Exit', 'description' => 'Send notification when an exit process is cancelled', 'mandatory' => 1],
            ['name' => 'exit_approval_request', 'display_name' => 'Exit Approval Request', 'category' => 'Employee Exit', 'description' => 'Send notification to approver for exit request approval', 'mandatory' => 1],
        ];
        
        return view('superadmin.notification-modules.assign', compact('institutes', 'modules'));
    }
    
    /**
     * Store module assignments with update logic
     */
    public function storeAssignments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute_ids' => 'required|array|min:1',
            'institute_ids.*' => 'exists:institutes,fincap_merchant_id',
            'modules' => 'required|array|min:1',
            'modules.*.name' => 'required|string',
            'modules.*.display_name' => 'required|string',
            'modules.*.category' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $createdCount = 0;
        $updatedCount = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($request->institute_ids as $instituteId) {
                foreach ($request->modules as $module) {
                    // Check if module already exists for this institute
                    $existingModule = NotificationModule::where('institute_id', $instituteId)
                        ->where('module_name', $module['name'])
                        ->first();
                    
                    if ($existingModule) {
                        // ✅ UPDATE existing module
                        $existingModule->update([
                            'module_display_name' => $module['display_name'],
                            'description' => $module['description'] ?? null,
                            'category' => $module['category'],
                            'is_mandatory' => isset($module['mandatory']) ? (int) filter_var($module['mandatory'], FILTER_VALIDATE_BOOLEAN) : 0,
                            'is_active' => true,
                            'default_channels' => ['email'],
                            'updated_at' => now(),
                        ]);
                        $updatedCount++;
                    } else {
                        // ✅ CREATE new module
                        NotificationModule::create([
                            'institute_id' => $instituteId,
                            'module_name' => $module['name'],
                            'module_display_name' => $module['display_name'],
                            'description' => $module['description'] ?? null,
                            'category' => $module['category'],
                            'is_mandatory' => isset($module['mandatory']) ? (int) filter_var($module['mandatory'], FILTER_VALIDATE_BOOLEAN) : 0,
                            'is_active' => true,
                            'default_channels' => ['email'],
                            'sort_order' => 0,
                        ]);
                        $createdCount++;
                    }
                }
            }
            
            DB::commit();
            
            $message = [];
            if ($createdCount > 0) $message[] = "{$createdCount} new module(s) created";
            if ($updatedCount > 0) $message[] = "{$updatedCount} existing module(s) updated";
            $message[] = "for selected institutes";
            
            return response()->json([
                'success' => true,
                'message' => implode(', ', $message)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk sync modules for an institute (remove unchecked, add/update checked)
     */
    public function syncModules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute_id' => 'required|exists:institutes,fincap_merchant_id',
            'modules' => 'required|array',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $instituteId = $request->institute_id;
        $selectedModules = collect($request->modules);
        $selectedModuleNames = $selectedModules->pluck('name')->toArray();
        
        DB::beginTransaction();
        
        try {
            // Delete modules that are not in the selected list
            $deletedCount = NotificationModule::where('institute_id', $instituteId)
                ->whereNotIn('module_name', $selectedModuleNames)
                ->delete();
            
            // Add or update selected modules
            $createdCount = 0;
            $updatedCount = 0;
            
            foreach ($selectedModules as $module) {
                $existing = NotificationModule::where('institute_id', $instituteId)
                    ->where('module_name', $module['name'])
                    ->first();
                
                if ($existing) {
                    $existing->update([
                        'module_display_name' => $module['display_name'],
                        'description' => $module['description'] ?? null,
                        'category' => $module['category'],
                        'is_mandatory' => $module['mandatory'] ?? 0,
                        'updated_at' => now(),
                    ]);
                    $updatedCount++;
                } else {
                    NotificationModule::create([
                        'institute_id' => $instituteId,
                        'module_name' => $module['name'],
                        'module_display_name' => $module['display_name'],
                        'description' => $module['description'] ?? null,
                        'category' => $module['category'],
                        'is_mandatory' => $module['mandatory'] ?? 0,
                        'is_active' => true,
                        'default_channels' => ['email'],
                        'sort_order' => 0,
                    ]);
                    $createdCount++;
                }
            }
            
            DB::commit();
            
            $message = [];
            if ($createdCount > 0) $message[] = "{$createdCount} module(s) added";
            if ($updatedCount > 0) $message[] = "{$updatedCount} module(s) updated";
            if ($deletedCount > 0) $message[] = "{$deletedCount} module(s) removed";
            
            return response()->json([
                'success' => true,
                'message' => implode(', ', $message)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get module list for an institute (for viewing)
     */
    public function getInstituteModules($instituteId)
    {
        $modules = NotificationModule::where('institute_id', $instituteId)
            ->orderBy('category')
            ->orderBy('module_display_name')
            ->get();
        
        return response()->json([
            'success' => true,
            'modules' => $modules
        ]);
    }
    
    /**
     * Delete module from institute
     */
    public function deleteModule($id)
    {
        try {
            $module = NotificationModule::findOrFail($id);
            
            // Also delete related institute notification settings
            InstituteNotificationSetting::where('institute_id', $module->institute_id)
                ->where('module_name', $module->module_name)
                ->delete();
            
            $module->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Module deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Toggle module status
     */
    public function toggleStatus($id)
    {
        try {
            $module = NotificationModule::findOrFail($id);
            $module->is_active = !$module->is_active;
            $module->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'is_active' => $module->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}