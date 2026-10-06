<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveDeduction;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveDeductionController extends Controller
{
    use InstituteBranchAccess;
    
    public function index()
    {
        $context = $this->getInstituteBranchContext();
        
        // Define default leave types (for display, not auto-save)
        $defaultLeaveTypes = [
            ['type' => 'Absent', 'approved' => 100, 'unapproved' => 100, 'custom' => false],
            ['type' => 'Sick Leave', 'approved' => 0, 'unapproved' => 100, 'custom' => false],
            ['type' => 'Casual Leave', 'approved' => 0, 'unapproved' => 100, 'custom' => false],
            ['type' => 'Earned Leave', 'approved' => 0, 'unapproved' => 100, 'custom' => false],
            ['type' => 'Unpaid Leave', 'approved' => 100, 'unapproved' => 100, 'custom' => false],
            ['type' => 'Maternity Leave', 'approved' => 0, 'unapproved' => 100, 'custom' => false],
            ['type' => 'Half Day', 'approved' => 0.5, 'unapproved' => 0.5, 'custom' => false],
            ['type' => 'Short Leave', 'approved' => 0.25, 'unapproved' => 0.25, 'custom' => false],
        ];
        
        // Get existing saved deductions
        $existingDeductions = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->orderByRaw("CASE WHEN is_custom = 1 THEN 1 ELSE 0 END")
            ->orderBy('leave_type')
            ->get();
        
        // Create a map of existing deductions by leave_type
        $existingMap = $existingDeductions->keyBy('leave_type');
        
        // Merge defaults with existing - show defaults even if not saved yet
        $allLeaveTypes = [];
        
        // Add defaults first
        foreach ($defaultLeaveTypes as $default) {
            $isSaved = $existingMap->has($default['type']);
            $allLeaveTypes[] = [
                'leave_type' => $default['type'],
                'approved_deduction_percentage' => $isSaved ? $existingMap[$default['type']]->approved_deduction_percentage : $default['approved'],
                'unapproved_deduction_percentage' => $isSaved ? $existingMap[$default['type']]->unapproved_deduction_percentage : $default['unapproved'],
                'is_custom' => false,
                'is_active' => $isSaved ? $existingMap[$default['type']]->is_active : true,
                'is_saved' => $isSaved,
                'id' => $isSaved ? $existingMap[$default['type']]->id : null
            ];
        }
        
        // Add custom saved deductions
        foreach ($existingDeductions as $deduction) {
            if ($deduction->is_custom) {
                $allLeaveTypes[] = [
                    'leave_type' => $deduction->leave_type,
                    'approved_deduction_percentage' => $deduction->approved_deduction_percentage,
                    'unapproved_deduction_percentage' => $deduction->unapproved_deduction_percentage,
                    'is_custom' => true,
                    'is_active' => $deduction->is_active,
                    'is_saved' => true,
                    'id' => $deduction->id
                ];
            }
        }
        
        return view('instituteAdmin.LeavePolicy.leaveDeductions', compact('allLeaveTypes'));
    }
    
    public function store(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        $request->validate([
            'leave_type' => 'required|string|max:100|unique:leave_deductions,leave_type,NULL,id,institute_id,' . $context['institute_id'],
            'approved_deduction_percentage' => 'required|numeric|min:0|max:100',
            'unapproved_deduction_percentage' => 'required|numeric|min:0|max:100',
        ]);
        
        try {
            DB::beginTransaction();
            
            $deduction = LeaveDeduction::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'],
                'leave_type' => $request->leave_type,
                'is_custom' => $request->boolean('is_custom'),
                'approved_deduction_percentage' => $request->approved_deduction_percentage,
                'unapproved_deduction_percentage' => $request->unapproved_deduction_percentage,
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Custom leave type added successfully!',
                'deduction' => $deduction  // Return the created deduction
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        $context = $this->getInstituteBranchContext();
        
        $request->validate([
            'approved_deduction_percentage' => 'required|numeric|min:0|max:100',
            'unapproved_deduction_percentage' => 'required|numeric|min:0|max:100',
        ]);
        
        $deduction = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('id', $id)
            ->first();
        
        if (!$deduction) {
            return response()->json([
                'success' => false,
                'message' => 'Deduction not found.'
            ], 404);
        }
        
        try {
            DB::beginTransaction();
            
            $deduction->update([
                'approved_deduction_percentage' => $request->approved_deduction_percentage,
                'unapproved_deduction_percentage' => $request->unapproved_deduction_percentage,
                'updated_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Deduction updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        $context = $this->getInstituteBranchContext();
        
        $deduction = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('id', $id)
            ->where('is_custom', true) // Only allow deleting custom types
            ->first();
        
        if (!$deduction) {
            return response()->json([
                'success' => false,
                'message' => 'Custom leave type not found or cannot be deleted.'
            ], 404);
        }
        
        try {
            $deduction->delete();
            
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
    
    public function toggleStatus($id)
    {
        $context = $this->getInstituteBranchContext();
        
        $deduction = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
            ->where('id', $id)
            ->first();
        
        if (!$deduction) {
            return response()->json([
                'success' => false,
                'message' => 'Deduction not found.'
            ], 404);
        }
        
        $deduction->is_active = !$deduction->is_active;
        $deduction->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!'
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        // Only process if it's an AJAX POST request
        if (!$request->ajax() || $request->method() !== 'POST') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request method.'
            ], 405);
        }
        
        $context = $this->getInstituteBranchContext();
        
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|exists:leave_deductions,id',
            'updates.*.approved_deduction_percentage' => 'required|numeric|min:0|max:100',
            'updates.*.unapproved_deduction_percentage' => 'required|numeric|min:0|max:100',
        ]);
        
        $updated = 0;
        $failed = 0;
        
        foreach ($request->updates as $update) {
            $deduction = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
                ->where('id', $update['id'])
                ->first();
            
            if ($deduction) {
                try {
                    $deduction->update([
                        'approved_deduction_percentage' => $update['approved_deduction_percentage'],
                        'unapproved_deduction_percentage' => $update['unapproved_deduction_percentage'],
                        'updated_by' => auth()->id(),
                    ]);
                    $updated++;
                } catch (\Exception $e) {
                    $failed++;
                }
            } else {
                $failed++;
            }
        }
        
        return response()->json([
            'success' => $failed === 0,
            'updated' => $updated,
            'failed' => $failed,
            'message' => $failed === 0 ? "All {$updated} deduction rules saved successfully!" : "Saved {$updated} rules, {$failed} failed."
        ]);
    }

    // Bulk save - handles both creating new and updating existing records
    public function bulkSave(Request $request)
    {
        if (!$request->ajax() || $request->method() !== 'POST') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request method.'
            ], 405);
        }
        
        $context = $this->getInstituteBranchContext();
        
        // Validate updates (existing records)
        if ($request->has('updates')) {
            $request->validate([
                'updates.*.id' => 'required|exists:leave_deductions,id',
                'updates.*.approved_deduction_percentage' => 'required|numeric|min:0|max:100',
                'updates.*.unapproved_deduction_percentage' => 'required|numeric|min:0|max:100',
            ]);
        }
        
        // Validate creates (new records)
        if ($request->has('creates')) {
            $request->validate([
                'creates.*.leave_type' => 'required|string|max:100',
                'creates.*.approved_deduction_percentage' => 'required|numeric|min:0|max:100',
                'creates.*.unapproved_deduction_percentage' => 'required|numeric|min:0|max:100',
            ]);
        }
        
        $created = 0;
        $updated = 0;
        $failed = 0;
        
        // Create new records
        if ($request->has('creates') && is_array($request->creates)) {
            foreach ($request->creates as $create) {
                try {
                    LeaveDeduction::create([
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['branch_id'],
                        'leave_type' => $create['leave_type'],
                        'is_custom' => isset($create['is_custom']) && $create['is_custom'] == 1,
                        'approved_deduction_percentage' => $create['approved_deduction_percentage'],
                        'unapproved_deduction_percentage' => $create['unapproved_deduction_percentage'],
                        'is_active' => isset($create['is_active']) ? $create['is_active'] : true,
                        'created_by' => auth()->id(),
                    ]);
                    $created++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }
        }
        
        // Update existing records
        if ($request->has('updates') && is_array($request->updates)) {
            foreach ($request->updates as $update) {
                $deduction = LeaveDeduction::forInstitute($context['institute_id'], $context['branch_id'])
                    ->where('id', $update['id'])
                    ->first();
                
                if ($deduction) {
                    try {
                        $deduction->update([
                            'approved_deduction_percentage' => $update['approved_deduction_percentage'],
                            'unapproved_deduction_percentage' => $update['unapproved_deduction_percentage'],
                            'is_active' => isset($update['is_active']) ? $update['is_active'] : true,
                            'updated_by' => auth()->id(),
                        ]);
                        $updated++;
                    } catch (\Exception $e) {
                        $failed++;
                    }
                } else {
                    $failed++;
                }
            }
        }
        
        $totalSaved = $created + $updated;
        
        return response()->json([
            'success' => $failed === 0,
            'created' => $created,
            'updated' => $updated,
            'failed' => $failed,
            'message' => $failed === 0 
                ? "Successfully created {$created} new rule(s) and updated {$updated} existing rule(s)!"
                : "Saved {$totalSaved} rules, {$failed} failed."
        ]);
    }

}