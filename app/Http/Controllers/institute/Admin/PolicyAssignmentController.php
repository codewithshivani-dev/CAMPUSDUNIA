<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeavePolicy;
use App\Models\LeavePolicyAssignment;
use App\Models\Shifts;
use App\Models\Departments;
use App\Models\EmployeeDetails;
use App\Models\DepartmentCategory;
use App\Models\EmployeeShift;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PolicyAssignmentController extends Controller
{
    use InstituteBranchAccess;

    // Show policy assignment form
    public function assignPolicyForm()
    {
        $context = $this->getInstituteBranchContext();
        
        // Get available policies
        $policies = LeavePolicy::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orWhere(function($query) use ($context) {
                $query->where('institute_id', $context['institute_id'])
                    ->whereNull('branch_id')
                    ->where('is_default', true);
            })
            ->where('active', 1) // Using where clause for LeavePolicy
            ->orderBy('is_default', 'desc')
            ->get();
        
        // Get department categories
        $departmentCategories = DepartmentCategory::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('category_name')
            ->get();
        
        // Get shifts - USING THE SCOPE METHOD
        $shifts = Shifts::where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->active() // This is your scope method
            ->orderBy('priority')
            ->get();
        
        return view('instituteAdmin.LeavePolicy.assignleavepolicies', compact(
            'policies',
            'departmentCategories',
            'shifts',
            'context'
        ));
    }

    // Handle policy assignment
    public function assignPolicy(Request $request)
    {
        // dd($request->all());

        $context = $this->getInstituteBranchContext();
        
        // Validate request based on assignment type
        $validator = Validator::make($request->all(), [
            // 'policy_id' => 'required',
            // 'assignment_type' => 'required',
            // 'effective_from' => 'nullable|date',
            // 'effective_to' => 'nullable|date|after:effective_from',
            // 'is_default' => 'boolean',
            
            // // Shift specific
            // 'shift_ids' => 'required_if:assignment_type,shift|array',
            // 'shift_ids.*' => 'exists:shifts,id',
            
            // // Department specific
            // 'department_category_id' => 'required_if:assignment_type,department|exists:department_categories,department_category_id',
            // 'department_ids' => 'required_if:assignment_type,department|array',
            // 'department_ids.*' => 'exists:departments,department_id',
            // 'assign_to_type' => 'required_if:assignment_type,department|in:whole_department,selected_employees',
            // 'employee_ids' => 'required_if:assign_to_type,selected_employees|array',
            
            // // Employee specific (for direct employee assignment)
            // 'emp_category_id' => 'required_if:assignment_type,employee|exists:department_categories,department_category_id',
            // 'emp_department_id' => 'required_if:assignment_type,employee|exists:departments,department_id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // try {
        //     DB::beginTransaction();
            
            $policy = LeavePolicy::where('policy_id',$request->policy_id)->first();
           
            // Verify policy belongs to same institute/branch
            $this->verifyPolicyAccess($policy, $context);
            
            $totalAssigned = 0;
            
            switch ($request->assignment_type) {
                case 'shift':
                    $totalAssigned = $this->assignToShifts($policy, $request, $context);
                    break;
                    
                case 'department':
                    $totalAssigned = $this->assignToDepartments($policy, $request, $context);
                    break;
                    
                case 'employee':
                    $totalAssigned = $this->assignToEmployeesDirectly($policy, $request, $context);
                    break;
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 
                "Policy '{$policy->policy_name}' assigned to {$totalAssigned} {$request->assignment_type}(s) successfully!");
                
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     Log::error('Policy assignment error: ' . $e->getMessage());
        //     return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        // }
    }

    // Assign policy to shifts
    private function assignToShifts($policy, $request, $context)
    {
        
        $assignedCount = 0;
        
        foreach ($request->shift_ids as $shiftId) {
            $shift = Shifts::where('id', $shiftId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$shift) continue;
            
            // Deactivate any existing active assignment for this shift
            LeavePolicyAssignment::where('assignable_type', Shifts::class)
                ->where('assignable_id', $shiftId)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->where('active', true)
                ->update(['active' => false]);
            
            // Create new assignment
            $assignment = LeavePolicyAssignment::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'],
                'policy_id' => $policy->policy_id,
                'assignable_type' => Shifts::class,
                'assignable_id' => $shiftId,
                'assignment_type' => 'shift',
                'assignable_name' => $shift->shift_name,
                'is_default' => $request->is_default ?? false,
                'active' => true,
                'effective_from' => $request->effective_from ?: now(),
                'effective_to' => $request->effective_to,
            ]);
            
            // if ($assignment) {
            //     $assignedCount++;
                
            //     // If this is a default assignment, also assign to all employees in this shift
            //     if ($request->is_default) {
            //         $this->assignPolicyToShiftEmployees($policy, $shiftId, $context, $request);
            //     }
            // }
        }
        
        return $assignedCount;
    }

    // Assign policy to departments
    private function assignToDepartments($policy, $request, $context)
    {
        $assignedCount = 0;
        
        // Verify departments belong to selected category
        foreach ($request->department_ids as $departmentId) {
            $department = Departments::where('department_id', $departmentId)
                ->where('department_category_id', $request->department_category_id)
                ->where('institute_id', $context['institute_id'])
                ->first();
        
            if (!$department) {
                throw new \Exception("Department ID {$departmentId} does not belong to selected category");
            }
        }
        
        foreach ($request->department_ids as $departmentId) {
            $department = Departments::where('department_id',$departmentId)->first();
            
            if (!$department) continue;
            
            // Deactivate any existing active assignment for this department
            LeavePolicyAssignment::where('assignable_type', Departments::class)
                ->where('assignable_id', $departmentId)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->where('active', true)
                ->update(['active' => false]);
            
            // Create new assignment
            $assignment = LeavePolicyAssignment::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'],
                'policy_id' => $policy->policy_id,
                'assignable_type' => Departments::class,
                'assignable_id' => $departmentId,
                'assignment_type' => 'department',
                'assignable_name' => $department->department,
                'is_default' => $request->is_default ?? false,
                'active' => true,
                'effective_from' => $request->effective_from ?: now(),
                'effective_to' => $request->effective_to,
            ]);
            
            if ($assignment) {
                $assignedCount++;
                
                // Process based on assignment type
                if ($request->assign_to_type === 'selected_employees') {
                    // Only create individual records for selected employees
                    if (!empty($request->employee_ids)) {
                        $this->assignToSelectedEmployees($policy, $request->employee_ids, $context, $request);
                    }
                }
                // For "whole_department", we don't create individual records
                // The policy will be applied dynamically when checking employee's policy
            }
        }
        
        return $assignedCount;
    }

    // Assign policy directly to selected employees (from department assignment)
    private function assignToSelectedEmployees($policy, $employeeIds, $context, $request)
    {
        $assignedCount = 0;
        
        foreach ($employeeIds as $employeeId) {
            $employee = EmployeeDetails::where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$employee) continue;
            
            // Deactivate any existing active assignment for this employee
            LeavePolicyAssignment::where('assignable_type', EmployeeDetails::class)
                ->where('assignable_id', $employee->id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->where('active', true)
                ->update(['active' => false]);
            
            // Create new assignment
            $assignment = LeavePolicyAssignment::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'],
                'policy_id' => $policy->policy_id,
                'assignable_type' => EmployeeDetails::class,
                'assignable_id' => $employee->employee_id,
                'assignment_type' => 'employee',
                'assignable_name' => $employee->name,
                'is_default' => false,
                'active' => true,
                'effective_from' => $request->effective_from ?: now(),
                'effective_to' => $request->effective_to,
            ]);
            
            if ($assignment) {
                $assignedCount++;
            }
        }
        
        return $assignedCount;
    }

    // Assign policy directly to employees (from employee assignment section)
    private function assignToEmployeesDirectly($policy, $request, $context)
    {
        $assignedCount = 0;
        
        if (empty($request->employee_ids)) {
            throw new \Exception('Please select employees');
        }
        
        foreach ($request->employee_ids as $employeeId) {
            $employee = EmployeeDetails::where('employee_id', $employeeId)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$employee) continue;
            
            // Deactivate any existing active assignment for this employee
            LeavePolicyAssignment::where('assignable_type', EmployeeDetails::class)
                ->where('assignable_id', $employee->employee_id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->where('active', true)
                ->update(['active' => false]);
            
            // Create new assignment
            $assignment = LeavePolicyAssignment::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'],
                'policy_id' => $policy->policy_id,
                'assignable_type' => EmployeeDetails::class,
                'assignable_id' => $employee->employee_id,
                'assignment_type' => 'employee',
                'assignable_name' => $employee->name,
                'is_default' => false,
                'active' => true,
                'effective_from' => $request->effective_from ?: now(),
                'effective_to' => $request->effective_to,
            ]);
            
            if ($assignment) {
                $assignedCount++;
            }
        }
        
        return $assignedCount;
    }

    // Assign policy to all employees in a shift
    // private function assignPolicyToShiftEmployees($policy, $shiftId, $context, $request)
    // {
    //     // Get active employee shifts
    //     $employeeShifts = EmployeeShift::where('shift_id', $shiftId)
    //         ->where('institute_id', $context['institute_id'])
    //         ->when($context['branch_id'], function($query) use ($context) {
    //             return $query->where('branch_id', $context['branch_id']);
    //         })
    //         ->where('status', 'active')
    //         ->with('employee')
    //         ->get();
        
    //     foreach ($employeeShifts as $employeeShift) {
    //         if (!$employeeShift->employee) continue;
            
    //         // Skip if employee already has a direct assignment
    //         $existingAssignment = LeavePolicyAssignment::where('assignable_type', EmployeeDetails::class)
    //             ->where('assignable_id', $employeeShift->employee->employee_id)
    //             ->where('active', true)
    //             ->first();
            
    //         if (!$existingAssignment) {
    //             LeavePolicyAssignment::create([
    //                 'institute_id' => $context['institute_id'],
    //                 'branch_id' => $context['branch_id'],
    //                 'policy_id' => $policy->policy_id,
    //                 'assignable_type' => EmployeeDetails::class,
    //                 'assignable_id' => $employeeShift->employee->employee_id,
    //                 'assignment_type' => 'employee',
    //                 'assignable_name' => $employeeShift->employee->name,
    //                 'is_default' => false,
    //                 'active' => true,
    //                 'effective_from' => $request->effective_from ?: now(),
    //                 'effective_to' => $request->effective_to,
    //             ]);
    //         }
    //     }
    // }

    // Assign policy to all employees in a department
    // private function assignPolicyToDepartmentEmployees($policy, $departmentId, $context, $request)
    // {
    //     // Get active employees in department
    //     $employees = EmployeeDetails::where('department_id', $departmentId)
    //         ->where('institute_id', $context['institute_id'])
    //         ->when($context['branch_id'], function($query) use ($context) {
    //             return $query->where('branch_id', $context['branch_id']);
    //         })
    //         ->where('status', 'active')
    //         ->get();
        
    //     foreach ($employees as $employee) {
    //         // Skip if employee already has a direct assignment
    //         $existingAssignment = LeavePolicyAssignment::where('assignable_type', EmployeeDetails::class)
    //             ->where('assignable_id', $employee->id)
    //             ->where('active', true)
    //             ->first();
            
    //         if (!$existingAssignment) {
    //             LeavePolicyAssignment::create([
    //                 'institute_id' => $context['institute_id'],
    //                 'branch_id' => $context['branch_id'],
    //                 'policy_id' => $policy->policy_id,
    //                 'assignable_type' => EmployeeDetails::class,
    //                 'assignable_id' => $employee->employee_id,
    //                 'assignment_type' => 'employee',
    //                 'assignable_name' => $employee->name ,
    //                 'is_default' => false,
    //                 'active' => true,
    //                 'effective_from' => $request->effective_from ?: now(),
    //                 'effective_to' => $request->effective_to,
    //             ]);
    //         }
    //     }
    // }

    // Verify policy access
    private function verifyPolicyAccess($policy, $context)
    {
        if ($policy->institute_id != $context['institute_id']) {
            throw new \Exception('Policy does not belong to your institute');
        }
        
        if ($context['branch_id'] && $policy->branch_id && $policy->branch_id != $context['branch_id']) {
            throw new \Exception('Policy does not belong to your branch');
        }
    }

    // View assigned policies
    public function viewAssignments()
    {
        $context = $this->getInstituteBranchContext();
        
        $assignments = LeavePolicyAssignment::with(['policy', 'assignable'])
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('assignment_type')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('assignment_type');
        
        return view('instituteAdmin.LeavePolicy.assignleavepolicies', compact('assignments'));
    }

    // Deactivate assignment
    public function deactivateAssignment($id)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            $assignment = LeavePolicyAssignment::where('id', $id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->firstOrFail();
            
            $assignment->update(['active' => false]);
            
            return redirect()->back()->with('success', 'Assignment deactivated successfully!');
            
        } catch (\Exception $e) {
            Log::error('Deactivate assignment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}