<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveDeduction;
use App\Models\LeaveType;
use App\Models\EmployeeLeave;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LeaveManagementController extends Controller
{
    use InstituteBranchAccess;
    
    /**
     * Display leave types (static defaults + custom from DB)
     */
    public function leaveTypes()
    {
        $context = $this->getInstituteBranchContext();
        
        // Static default leave types
        $defaultLeaveTypes = [
            ['leave_type' => 'Sick Leave', 'is_custom' => false, 'is_active' => true],
            ['leave_type' => 'Casual Leave', 'is_custom' => false, 'is_active' => true],
            ['leave_type' => 'Earned Leave', 'is_custom' => false, 'is_active' => true],
            ['leave_type' => 'Unpaid Leave', 'is_custom' => false, 'is_active' => true],
            ['leave_type' => 'Maternity Leave', 'is_custom' => false, 'is_active' => true],
            ['leave_type' => 'Half Day Leave', 'is_custom' => false, 'is_active' => true],
            ['leave_type' => 'Short Day Leave', 'is_custom' => false, 'is_active' => true],
        ];
        
        // Get custom leave types from database
        $customLeaveTypes = [];
        if (Schema::hasTable('leave_types')) {
            $customLeaveTypes = LeaveType::forInstitute($context['institute_id'], $context['branch_id'])
                ->where('is_custom', true)
                ->get()
                ->toArray();
        }
        
        // Merge defaults with custom
        $leaveTypes = array_merge($defaultLeaveTypes, $customLeaveTypes);
        
        return view('instituteAdmin.LeavePolicy.leaveTypes', compact('leaveTypes'));
    }
    
    /**
     * Store custom leave type (ONLY in leave_types table, NOT in leave_deductions)
     */
    public function storeLeaveType(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        $request->validate([
            'leave_type' => 'required|string|max:100',
            'is_active' => 'boolean'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create leave type only (NO deduction entry)
            $leaveType = LeaveType::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'],
                'leave_type' => $request->leave_type,
                'is_custom' => true,
                'is_active' => $request->is_active ?? true,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Custom leave type added successfully! You can now configure its deductions in the Deduction Configuration page.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete custom leave type (also delete its deduction entries if exist)
     */
    public function destroyLeaveType($id)
    {
        $context = $this->getInstituteBranchContext();
        
        $leaveType = LeaveType::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('id', $id)
            ->where('is_custom', true)
            ->first();
        
        if (!$leaveType) {
            return response()->json([
                'success' => false,
                'message' => 'Custom leave type not found or cannot be deleted.'
            ], 404);
        }
        
        try {
            // Delete associated deduction entries if exist
            LeaveDeduction::where('leave_type_id', $id)->delete();
            // Delete leave type
            $leaveType->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Custom leave type deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display attendance deductions page
     * NO automatic database creation - just display existing data + defaults
     */
    public function attendanceDeductions()
    {
        $context = $this->getInstituteBranchContext();
        
        // Get Absent deduction if exists (DO NOT CREATE)
        $absentDeduction = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('leave_type', 'Absent')
            ->first();
        
        // If Absent doesn't exist in DB, create a temporary array for display
        if (!$absentDeduction) {
            $absentDeduction = [
                'id' => null,
                'leave_type' => 'Absent',
                'approved_deduction_percentage' => 100,
                'unapproved_deduction_percentage' => 100,
                'is_active' => true,
            ];
        } else {
            $absentDeduction = $absentDeduction->toArray();
        }
        
        // Define default leave types with their deduction values (for display only)
        $defaultLeaveTypes = [
            ['leave_type' => 'Sick Leave', 'is_custom' => false, 'approved' => 0, 'unapproved' => 100, 'is_active' => true],
            ['leave_type' => 'Casual Leave', 'is_custom' => false, 'approved' => 0, 'unapproved' => 100, 'is_active' => true],
            ['leave_type' => 'Earned Leave', 'is_custom' => false, 'approved' => 0, 'unapproved' => 100, 'is_active' => true],
            ['leave_type' => 'Unpaid Leave', 'is_custom' => false, 'approved' => 100, 'unapproved' => 100, 'is_active' => true],
            ['leave_type' => 'Maternity Leave', 'is_custom' => false, 'approved' => 0, 'unapproved' => 100, 'is_active' => true],
            ['leave_type' => 'Half Day Leave', 'is_custom' => false, 'approved' => 50, 'unapproved' => 100, 'is_active' => true],
            ['leave_type' => 'Short Day Leave', 'is_custom' => false, 'approved' => 25, 'unapproved' => 50, 'is_active' => true],
        ];
        
        // Get custom leave types from database
        $customLeaveTypes = LeaveType::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('is_custom', true)
            ->get();
        
        // Get existing leave deductions from database (excluding Absent)
        $existingLeaveDeductions = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('leave_type', '!=', 'Absent')
            ->get()
            ->keyBy('leave_type');
        
        $leaveDeductions = [];
        
        // Process default leave types - ONLY DISPLAY, NO DATABASE CREATION
        foreach ($defaultLeaveTypes as $default) {
            $leaveTypeName = $default['leave_type'];
            
            if (isset($existingLeaveDeductions[$leaveTypeName])) {
                // Use existing deduction record from database
                $leaveDeductions[] = $existingLeaveDeductions[$leaveTypeName]->toArray();
            } else {
                // Display default values (temporary, not saved to DB)
                $leaveDeductions[] = [
                    'id' => null,
                    'leave_type' => $leaveTypeName,
                    'is_custom' => false,
                    'approved_deduction_percentage' => $default['approved'],
                    'unapproved_deduction_percentage' => $default['unapproved'],
                    'is_active' => $default['is_active'],
                ];
            }
        }
        
        // Process custom leave types - ONLY DISPLAY, NO DATABASE CREATION
        foreach ($customLeaveTypes as $customType) {
            $leaveTypeName = $customType->leave_type;
            
            if (isset($existingLeaveDeductions[$leaveTypeName])) {
                // Use existing deduction record
                $leaveDeductions[] = $existingLeaveDeductions[$leaveTypeName]->toArray();
            } else {
                // Display default values (temporary, not saved to DB)
                $leaveDeductions[] = [
                    'id' => null,
                    'leave_type' => $leaveTypeName,
                    'leave_type_id' => $customType->id,
                    'is_custom' => true,
                    'approved_deduction_percentage' => 0,
                    'unapproved_deduction_percentage' => 100,
                    'is_active' => $customType->is_active,
                ];
            }
        }
        
        return view('instituteAdmin.LeavePolicy.attendanceDeductions', compact('leaveDeductions', 'absentDeduction'));
    }
    
    /**
     * Bulk update attendance deductions - ONLY SAVES WHEN BUTTON IS CLICKED
     */
    public function bulkUpdateDeductions(Request $request)
    {
        if (!$request->ajax() || $request->method() !== 'POST') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request method.'
            ], 405);
        }
        
        $context = $this->getInstituteBranchContext();
        
        $updated = 0;
        $created = 0;
        $failed = 0;
        
        try {
            DB::beginTransaction();
            
            // Update or create absent deduction if provided
            if ($request->has('absent_percentage') && $request->absent_percentage !== null) {
                $absentDeduction = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
                    ->where('leave_type', 'Absent')
                    ->first();
                
                if ($absentDeduction) {
                    // Update existing
                    $absentDeduction->update([
                        'approved_deduction_percentage' => $request->absent_percentage,
                        'unapproved_deduction_percentage' => $request->absent_percentage,
                        'updated_by' => auth()->id(),
                    ]);
                    $updated++;
                } else {
                    // Create new
                    LeaveDeduction::create([
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'],
                        'leave_type' => 'Absent',
                        'is_custom' => false,
                        'approved_deduction_percentage' => $request->absent_percentage,
                        'unapproved_deduction_percentage' => $request->absent_percentage,
                        'is_active' => true,
                        'created_by' => auth()->id(),
                    ]);
                    $created++;
                }
            }
            
            // Update or create leave deductions
            if ($request->has('updates') && is_array($request->updates)) {
                foreach ($request->updates as $update) {
                    // Skip if leave_type is missing
                    if (!isset($update['leave_type']) || empty($update['leave_type'])) {
                        $failed++;
                        continue;
                    }
                    
                    $leaveTypeName = $update['leave_type'];
                    
                    // Check if deduction already exists in database
                    $existingDeduction = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
                        ->where('leave_type', $leaveTypeName)
                        ->first();
                    
                    if ($existingDeduction) {
                        // Update existing record
                        $existingDeduction->update([
                            'approved_deduction_percentage' => $update['approved_deduction_percentage'],
                            'unapproved_deduction_percentage' => $update['unapproved_deduction_percentage'],
                            'updated_by' => auth()->id(),
                        ]);
                        $updated++;
                    } else {
                        // Create new record
                        // For custom leave types, get the leave_type_id
                        $leaveType = LeaveType::forInstitute($context['institute_id'], $context['branch_id'])
                            ->where('leave_type', $leaveTypeName)
                            ->first();
                        
                        LeaveDeduction::create([
                            'institute_id' => $context['institute_id'],
                            'branch_id' => $context['branch_id'],
                            'leave_type' => $leaveTypeName,
                            'leave_type_id' => $leaveType ? $leaveType->id : null,
                            'is_custom' => $update['is_custom'] ?? false,
                            'approved_deduction_percentage' => $update['approved_deduction_percentage'],
                            'unapproved_deduction_percentage' => $update['unapproved_deduction_percentage'],
                            'is_active' => $update['is_active'] ?? true,
                            'created_by' => auth()->id(),
                        ]);
                        $created++;
                    }
                }
            }
            
            DB::commit();
            
            $total = $updated + $created;
            $message = "Successfully saved {$total} deduction rule(s)! (Updated: {$updated}, Created: {$created})";
            
            return response()->json([
                'success' => $failed === 0,
                'updated' => $updated,
                'created' => $created,
                'failed' => $failed,
                'message' => $failed === 0 ? $message : "Saved {$total} rules, {$failed} failed."
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save: ' . $e->getMessage()
            ], 500);
        }
    }
}