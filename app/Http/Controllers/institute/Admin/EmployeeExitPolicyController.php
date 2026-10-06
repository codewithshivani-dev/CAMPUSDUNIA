<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeExitPolicy;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\PolicyAssignment;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeExitPolicyController extends Controller
{
    use InstituteBranchAccess;

    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];
        
        $query = EmployeeExitPolicy::with(['assignments.employee'])
            ->where('institute_id', $instituteId);

        if ($context['is_branch_admin'] && $context['branch_id']) {
            $query->where('branch_id', $context['branch_id']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('policy_name', 'LIKE', "%{$search}%")
                    ->orWhere('policy_code', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('fnf_required')) {
            $query->where('fnf_required', $request->fnf_required === 'yes');
        }

        if ($request->filled('exit_type')) {
            $query->where('exit_type', $request->exit_type);
        }

        if ($request->filled('assignment')) {
            if ($request->assignment === 'unassigned') {
                $query->whereDoesntHave('assignments');
            } else {
                $assignmentTypes = match ($request->assignment) {
                    'all' => ['all', 'all_department', 'all_fallback'],
                    'department' => ['department', 'department_only'],
                    'individual' => ['individual', 'individual_only'],
                    default => [],
                };

                if ($assignmentTypes) {
                    $query->whereHas('assignments', function ($assignmentQuery) use ($assignmentTypes) {
                        $assignmentQuery->whereIn('assignment_type', $assignmentTypes);
                    });
                }
            }
        }

        $policies = $query->orderBy('created_at', 'desc')->paginate(15);

        $statistics = $this->getPolicyStatistics($instituteId);

        return view('instituteAdmin.EmployeeExit.policies', compact(
            'policies',
            'statistics',
            'context'
        ));
    }

    private function getPolicyStatistics($instituteId)
    {
        $policies = EmployeeExitPolicy::where('institute_id', $instituteId)->get();
        $assignments = PolicyAssignment::whereHas('policy', function($q) use ($instituteId) {
            $q->where('institute_id', $instituteId);
        })->get();

        return [
            'total' => $policies->count(),
            'active' => $policies->where('is_active', true)->count(),
            'inactive' => $policies->where('is_active', false)->count(),
            'assigned' => $assignments->groupBy('policy_id')->count(),
            'employees_covered' => $assignments->groupBy('employee_id')->count(),
        ];
    }

    public function create()
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];

        $departments = Departments::where('institute_id', $instituteId)
            ->withCount(['employees as employees_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('department')
            ->get();
        
        $employees = EmployeeDetails::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->select('employee_id', 'name', 'employee_code', 'department_id', 'employment_type', 'designation')
            ->orderBy('name')
            ->get();

        $employmentTypes = ['Full-time', 'Part-time', 'Contract-based', 'Probation-Period'];
        $exitTypes = ['Resignation', 'Termination', 'End of Contract', 'Mutual Agreement', 'Retirement', 'Other'];

        $unavailableDepartmentIds = $this->getUnavailableDepartmentIds($instituteId, []);
        
        $allEmployees = EmployeeDetails::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->select('employee_id', 'name', 'employee_code', 'designation', 'department_id')
            ->orderBy('name')
            ->get();

        return view('instituteAdmin.EmployeeExit.create-policy', compact(
            'departments',
            'employees',
            'employmentTypes',
            'exitTypes',
            'allEmployees',
            'unavailableDepartmentIds',
            'context'
        ));
    }

    private function getUnavailableDepartmentIds($instituteId, array $exitTypes, $excludePolicyId = null)
    {
        return PolicyAssignment::whereNotNull('department_id')
            ->whereIn('exit_type', $exitTypes ?: [''])
            ->whereHas('policy', function ($query) use ($instituteId, $excludePolicyId) {
                $query->where('institute_id', $instituteId)
                    ->where('is_active', true);

                if ($excludePolicyId) {
                    $query->where('id', '!=', $excludePolicyId);
                }
            })
            ->pluck('department_id')
            ->unique()
            ->values()
            ->all();
    }

    private function getDepartmentConflicts($instituteId, array $exitTypes, $excludePolicyId = null)
    {
        return PolicyAssignment::with(['department', 'policy'])
            ->whereNotNull('department_id')
            ->whereIn('exit_type', $exitTypes ?: [''])
            ->whereHas('policy', function ($query) use ($instituteId, $excludePolicyId) {
                $query->where('institute_id', $instituteId)
                    ->where('is_active', true);

                if ($excludePolicyId) {
                    $query->where('id', '!=', $excludePolicyId);
                }
            })
            ->get()
            ->groupBy('department_id')
            ->map(function ($assignments) {
                $department = $assignments->first()->department;

                return [
                    'department_id' => $department->department_id,
                    'department_name' => $department->department,
                    'policies' => $assignments->map(function ($assignment) {
                        return [
                            'exit_type' => $assignment->exit_type,
                            'policy_name' => $assignment->policy->policy_name,
                            'edit_url' => route('exit-policies.edit', $assignment->policy_id),
                        ];
                    })->unique('edit_url')->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    private function validateDepartmentAvailability($instituteId, array $exitTypes, array $departmentIds, $excludePolicyId = null)
    {
        $unavailable = $this->getUnavailableDepartmentIds($instituteId, $exitTypes, $excludePolicyId);
        $conflictingDepartmentIds = array_values(array_intersect($departmentIds, $unavailable));

        if (!$conflictingDepartmentIds) {
            return null;
        }

        $names = Departments::whereIn('department_id', $conflictingDepartmentIds)
            ->pluck('department', 'department_id');

        return collect($conflictingDepartmentIds)
            ->map(fn ($departmentId) => $names[$departmentId] ?? $departmentId)
            ->implode(', ');
    }

    /**
     * Check if any existing policy conflicts with the new policy
     * Now checks for specific exit type and assignment matches
     */
    private function checkExistingPolicyConflicts($instituteId, $exitTypes, $assignmentType, $departmentIds = [], $employeeIds = [])
    {
        $conflicts = [];

        // Check each exit type separately
        foreach ($exitTypes as $exitType) {
            // Check if a policy already exists for this exit type with the same assignments
            $existingPolicies = EmployeeExitPolicy::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->where('exit_type', $exitType)
                ->get();

            foreach ($existingPolicies as $policy) {
                // Get assignments for this policy
                $assignments = PolicyAssignment::where('policy_id', $policy->id)->get();
                
                if ($assignments->isEmpty()) {
                    continue;
                }

                // Check conflicts based on assignment type
                if ($assignmentType === 'all') {
                    // Check if any assignment exists for this exit type
                    $conflictingAssignments = $assignments->filter(function($assignment) use ($exitType) {
                        return $assignment->exit_type === $exitType;
                    });
                    
                    if ($conflictingAssignments->count() > 0) {
                        $conflicts[] = [
                            'policy_id' => $policy->id,
                            'policy_name' => $policy->policy_name,
                            'policy_code' => $policy->policy_code,
                            'exit_type' => $exitType,
                            'type' => 'all',
                            'affected_count' => $conflictingAssignments->count()
                        ];
                    }
                } elseif ($assignmentType === 'departments') {
                    // Check if any selected department has assignment for this exit type
                    $conflictingAssignments = $assignments->filter(function($assignment) use ($departmentIds, $exitType) {
                        return in_array($assignment->department_id, $departmentIds) && 
                               $assignment->exit_type === $exitType;
                    });
                    
                    if ($conflictingAssignments->count() > 0) {
                        $departmentNames = Departments::whereIn('department_id', $departmentIds)
                            ->pluck('department', 'department_id')
                            ->toArray();
                        
                        $affectedDepartments = [];
                        foreach ($conflictingAssignments as $assignment) {
                            if ($assignment->department_id && isset($departmentNames[$assignment->department_id])) {
                                $affectedDepartments[] = $departmentNames[$assignment->department_id];
                            }
                        }
                        
                        $conflicts[] = [
                            'policy_id' => $policy->id,
                            'policy_name' => $policy->policy_name,
                            'policy_code' => $policy->policy_code,
                            'exit_type' => $exitType,
                            'type' => 'department',
                            'affected_departments' => array_unique($affectedDepartments),
                            'affected_count' => $conflictingAssignments->count()
                        ];
                    }
                } elseif ($assignmentType === 'employees') {
                    // Check if any selected employee has assignment for this exit type
                    $conflictingAssignments = $assignments->filter(function($assignment) use ($employeeIds, $exitType) {
                        return in_array($assignment->employee_id, $employeeIds) && 
                               $assignment->exit_type === $exitType;
                    });
                    
                    if ($conflictingAssignments->count() > 0) {
                        $employeeNames = EmployeeDetails::whereIn('employee_id', $employeeIds)
                            ->pluck('name', 'employee_id')
                            ->toArray();
                        
                        $affectedEmployees = [];
                        foreach ($conflictingAssignments as $assignment) {
                            if ($assignment->employee_id && isset($employeeNames[$assignment->employee_id])) {
                                $affectedEmployees[] = $employeeNames[$assignment->employee_id];
                            }
                        }
                        
                        $conflicts[] = [
                            'policy_id' => $policy->id,
                            'policy_name' => $policy->policy_name,
                            'policy_code' => $policy->policy_code,
                            'exit_type' => $exitType,
                            'type' => 'employee',
                            'affected_employees' => array_unique($affectedEmployees),
                            'affected_count' => $conflictingAssignments->count()
                        ];
                    }
                }
            }
        }

        return $conflicts;
    }

    /**
     * Check for conflicts via AJAX
     */
    public function checkConflicts(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];

        $exitTypes = (array) $request->input('exit_type', []);
        $assignmentType = $request->input('assignment_type', 'all');
        $departmentIds = $request->input('department_ids', []);
        $employeeIds = $request->input('employee_ids', []);
        $excludePolicyId = $request->input('exclude_policy_id');

        $conflicts = $this->checkExistingPolicyConflicts(
            $instituteId,
            $exitTypes,
            $assignmentType,
            $departmentIds,
            $employeeIds
        );

        return response()->json([
            'has_conflicts' => count($conflicts) > 0,
            'conflicts' => $conflicts,
            'unavailable_department_ids' => $this->getUnavailableDepartmentIds($instituteId, $exitTypes, $excludePolicyId),
            'department_conflicts' => $this->getDepartmentConflicts($instituteId, $exitTypes, $excludePolicyId),
        ]);
    }

    /**
     * Handle override for existing policies - removes only conflicting policies
     */
    private function handleOverride($instituteId, $exitTypes, $assignmentType, $departmentIds = [], $employeeIds = [], $newPolicyIds = [])
    {
        // Check each exit type
        foreach ($exitTypes as $exitType) {
            // Find existing policy for this exit type
            $existingPolicy = EmployeeExitPolicy::where('institute_id', $instituteId)
                ->where('is_active', true)
                ->where('exit_type', $exitType)
                ->whereNotIn('id', $newPolicyIds)
                ->first();

            if (!$existingPolicy) {
                continue;
            }

            // Delete assignments based on assignment type
            if ($assignmentType === 'all') {
                PolicyAssignment::where('policy_id', $existingPolicy->id)
                    ->where('exit_type', $exitType)
                    ->delete();
            } elseif ($assignmentType === 'departments') {
                PolicyAssignment::where('policy_id', $existingPolicy->id)
                    ->whereIn('department_id', $departmentIds)
                    ->where('exit_type', $exitType)
                    ->delete();
            } elseif ($assignmentType === 'employees') {
                PolicyAssignment::where('policy_id', $existingPolicy->id)
                    ->whereIn('employee_id', $employeeIds)
                    ->where('exit_type', $exitType)
                    ->delete();
            }

            // If no assignments left, deactivate the policy
            $remainingAssignments = PolicyAssignment::where('policy_id', $existingPolicy->id)->count();
            if ($remainingAssignments === 0) {
                $existingPolicy->update(['is_active' => false]);
            }
        }
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'in' => 'Please select a valid :attribute.',
            'integer' => 'The :attribute must be a valid number.',
            'min' => 'The :attribute must be at least :min.',
            'max' => 'The :attribute must not exceed :max.',
            'array' => 'The :attribute must be an array.',
            'required_if' => 'The :attribute is required when :other is selected.',
            'exists' => 'The selected :attribute is invalid.',
            'unique' => 'The :attribute has already been taken.',
            'after_or_equal' => 'The effective date must be today or a future date.',
            'after' => 'The expiry date must be after the effective date.',
        ];

        $validator = Validator::make($request->all(), [
            'policy_name' => 'required|string|max:255',
            'policy_code' => 'required|string|max:50',
            'exit_type' => 'required|array|min:1',
            'exit_type.*' => 'in:Resignation,Termination,End of Contract,Mutual Agreement,Retirement,Other',
            'default_notice_period' => 'required|in:30,45,60,90,custom',
            'default_custom_days' => 'nullable|integer|min:1|max:365|required_if:default_notice_period,custom',
            'employment_notice_periods' => 'nullable|array',
            'employment_custom_days' => 'nullable|array',
            'exit_interview_required' => 'nullable|boolean',
            'interview_days' => 'nullable|integer|min:1|max:30',
            'fnf_required' => 'nullable|boolean',
            'fnf_processing_days' => 'nullable|required_if:fnf_required,1|in:7,15,30,45,60',
            'fnf_settlement_type' => 'nullable|in:standard,expedited',
            'fnf_items' => 'nullable|array',
            'kt_required' => 'nullable|boolean',
            'kt_days' => 'nullable|required_if:kt_required,1|in:3,5,7,10,15,30',
            'kt_requirements' => 'nullable|array',
            'additional_requirements' => 'nullable|array',
            'clearance_workflow' => 'required|in:sequential,parallel,hybrid',
            'clearance_days' => 'required|integer|min:1|max:90',
            'assignment_type' => 'required|in:all,departments,employees',
            'department_ids' => 'nullable|array|required_if:assignment_type,departments',
            'department_ids.*' => 'exists:departments,department_id',
            'employee_ids' => 'nullable|array|required_if:assignment_type,employees',
            'employee_ids.*' => 'exists:employee_details,employee_id',
            'effective_date' => 'required|date|after_or_equal:today',
            'expiry_date' => 'nullable|date|after:effective_date',
            'assignment_notes' => 'nullable|string',
            'description' => 'nullable|string|max:500',
            'terms_conditions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'override_confirmed' => 'nullable|boolean',
            'skip_conflicts' => 'nullable|boolean',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $context = $this->getInstituteBranchContext();
        $data = $validator->validated();

        $selectedDepartmentIds = $data['assignment_type'] === 'all'
            ? Departments::where('institute_id', $context['institute_id'])
                ->where('status', 'active')
                ->pluck('department_id')
                ->all()
            : ($data['department_ids'] ?? []);
        $departmentConflict = $data['assignment_type'] !== 'all'
            ? $this->validateDepartmentAvailability(
            $context['institute_id'],
            $data['exit_type'],
            $selectedDepartmentIds
            )
            : null;

        if ($departmentConflict) {
            return redirect()->back()
                ->withErrors(['department_ids' => "These departments already have a policy for one of the selected exit types: {$departmentConflict}."])
                ->withInput();
        }

        // Check for conflicts before creating
        $conflicts = $this->checkExistingPolicyConflicts(
            $context['institute_id'],
            $data['exit_type'],
            $data['assignment_type'],
            $data['department_ids'] ?? [],
            $data['employee_ids'] ?? []
        );

        // If there are conflicts and override is not confirmed, return conflicts
        if (count($conflicts) > 0
            && !($data['override_confirmed'] ?? false)
            && !($data['skip_conflicts'] ?? false)) {
            return response()->json([
                'has_conflicts' => true,
                'conflicts' => $conflicts,
                'message' => 'This policy conflicts with existing policies. Please confirm override.'
            ], 409);
        }

        DB::beginTransaction();

        // try {
            $defaultNoticeDays = $data['default_notice_period'] === 'custom' 
                ? (int) $data['default_custom_days'] 
                : (int) $data['default_notice_period'];
            
            $createdPolicyIds = [];
            $totalAssignedCount = 0;

            // Create ONE policy per exit type
            foreach ($data['exit_type'] as $exitType) {
                $policyData = [
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    'policy_name' => $data['policy_name'],
                    'policy_code' => $data['policy_code'] . '-' . substr($exitType, 0, 3),
                    'exit_type' => $exitType, // Single exit type per policy
                    'description' => $data['description'] ?? null,
                    'terms_conditions' => $data['terms_conditions'] ?? null,
                    'is_active' => $data['is_active'] ?? true,
                    'exit_interview_required' => $data['exit_interview_required'] ?? false,
                    'interview_days' => $data['interview_days'] ?? null,
                    'fnf_required' => $data['fnf_required'] ?? false,
                    'fnf_processing_days' => $data['fnf_processing_days'] ?? null,
                    'fnf_settlement_type' => $data['fnf_settlement_type'] ?? 'standard',
                    'fnf_items' => isset($data['fnf_items']) ? json_encode($data['fnf_items']) : null,
                    'kt_required' => $data['kt_required'] ?? false,
                    'kt_days' => $data['kt_days'] ?? null,
                    'kt_requirements' => isset($data['kt_requirements']) ? json_encode($data['kt_requirements']) : null,
                    'additional_requirements' => isset($data['additional_requirements']) ? json_encode($data['additional_requirements']) : null,
                    'clearance_workflow' => $data['clearance_workflow'],
                    'clearance_days' => (int) $data['clearance_days'],
                    'default_notice_period' => $defaultNoticeDays,
                    'employment_notice_periods' => isset($data['employment_notice_periods']) ? json_encode($data['employment_notice_periods']) : null,
                    'employment_custom_days' => isset($data['employment_custom_days']) ? json_encode($data['employment_custom_days']) : null,
                ];

                $policyData = array_filter($policyData, function($value) {
                    return !is_null($value);
                });

                // Create the policy
                $policy = EmployeeExitPolicy::create($policyData);
                $createdPolicyIds[] = $policy->id;

                // If override is confirmed, handle it
                if ($data['override_confirmed'] ?? false) {
                    $this->handleOverride(
                        $context['institute_id'],
                        [$exitType],
                        $data['assignment_type'],
                        $data['department_ids'] ?? [],
                        $data['employee_ids'] ?? [],
                        $createdPolicyIds
                    );
                }

                // Assign the policy - create assignment records
                $assignmentType = $data['assignment_type'];

                if ($assignmentType === 'all') {
                    $departments = Departments::where('institute_id', $policy->institute_id)
                        ->where('status', 'active')
                        ->get();

                    if ($data['skip_conflicts'] ?? false) {
                        $blockedDepartmentIds = $this->getUnavailableDepartmentIds(
                            $policy->institute_id,
                            [$exitType]
                        );
                        $departments = $departments->reject(function ($department) use ($blockedDepartmentIds) {
                            return in_array($department->department_id, $blockedDepartmentIds);
                        });
                    }

                    foreach ($departments as $department) {
                        PolicyAssignment::create([
                            'policy_id' => $policy->id,
                            'department_id' => $department->department_id,
                            'exit_type' => $exitType,
                            'assignment_type' => 'all_department',
                            'effective_date' => $data['effective_date'],
                            'expiry_date' => $data['expiry_date'] ?? null,
                            'notes' => $data['assignment_notes'] ?? null,
                            'assigned_by' => auth()->id(),
                        ]);
                    }
                    
                    $employeeCount = EmployeeDetails::where('institute_id', $policy->institute_id)
                        ->whereIn('department_id', $departments->pluck('department_id')->all())
                        ->where('status', 'active')
                        ->count();
                    $totalAssignedCount += $employeeCount;
                } elseif ($assignmentType === 'departments') {
                    $departmentIds = $data['department_ids'];

                    foreach ($departmentIds as $departmentId) {
                        PolicyAssignment::create([
                            'policy_id' => $policy->id,
                            'department_id' => $departmentId,
                            'exit_type' => $exitType,
                            'assignment_type' => 'department',
                            'effective_date' => $data['effective_date'],
                            'expiry_date' => $data['expiry_date'] ?? null,
                            'notes' => $data['assignment_notes'] ?? null,
                            'assigned_by' => auth()->id(),
                        ]);
                    }
                    
                    $employeeCount = EmployeeDetails::where('institute_id', $policy->institute_id)
                        ->whereIn('department_id', $departmentIds)
                        ->where('status', 'active')
                        ->count();
                    $totalAssignedCount += $employeeCount;
                } elseif ($assignmentType === 'employees') {
                    $employeeIds = $data['employee_ids'];

                    foreach ($employeeIds as $employeeId) {
                        PolicyAssignment::create([
                            'policy_id' => $policy->id,
                            'employee_id' => $employeeId,
                            'exit_type' => $exitType,
                            'assignment_type' => 'individual',
                            'effective_date' => $data['effective_date'],
                            'expiry_date' => $data['expiry_date'] ?? null,
                            'notes' => $data['assignment_notes'] ?? null,
                            'assigned_by' => auth()->id(),
                        ]);
                    }
                    $totalAssignedCount += count($employeeIds);
                }
            }

            DB::commit();

            $exitTypesList = implode(', ', $data['exit_type']);
            $overrideMsg = ($data['override_confirmed'] ?? false) ? ' and overridden existing policies' : '';
            return redirect()->route('exit-policies.index')
                ->with('success', "Policies created for exit types ({$exitTypesList}), assigned to {$totalAssignedCount} employee(s){$overrideMsg} successfully!");

        // } catch (\Exception $e) {
        //     DB::rollBack();
            
        //     \Log::error('Exit policy creation error: ' . $e->getMessage(), [
        //         'trace' => $e->getTraceAsString()
        //     ]);

        //     return redirect()->back()
        //         ->with('error', 'Failed to create exit policy: ' . $e->getMessage())
        //         ->withInput();
        // }
    }

    public function edit($id)
{
    $context = $this->getInstituteBranchContext();
    $instituteId = $context['institute_id'];

    $policy = EmployeeExitPolicy::where('id', $id)
        ->where('institute_id', $instituteId)
        ->firstOrFail();

    // Get assignments for this policy
    $assignments = PolicyAssignment::where('policy_id', $policy->id)->get();
    
    // Determine assignment type
    $assignmentType = 'all';
    $selectedDepartmentIds = [];
    $selectedEmployeeIds = [];
    $assignmentDepartments = [];
    $assignedEmployees = collect();
    
    if ($assignments->count() > 0) {
        $firstAssignment = $assignments->first();
        
        if ($firstAssignment->assignment_type === 'all_department' || $firstAssignment->assignment_type === 'all_fallback') {
            $assignmentType = 'all';
            
            // Get ALL employees of this institute
            $assignedEmployees = EmployeeDetails::where('institute_id', $instituteId)
                ->where('status', 'active')
                ->select('employee_id', 'name', 'employee_code', 'designation', 'department_id')
                ->orderBy('name')
                ->get();
                
        } elseif ($firstAssignment->assignment_type === 'department' || $firstAssignment->assignment_type === 'department_only') {
            $assignmentType = 'departments';
            $selectedDepartmentIds = $assignments->pluck('department_id')->unique()->filter()->toArray();
            
            // Get department names
            $assignmentDepartments = Departments::whereIn('department_id', $selectedDepartmentIds)
                ->where('institute_id', $instituteId)
                ->pluck('department', 'department_id')
                ->toArray();
            
            // Get all employees in these departments
            $assignedEmployees = EmployeeDetails::where('institute_id', $instituteId)
                ->whereIn('department_id', $selectedDepartmentIds)
                ->where('status', 'active')
                ->select('employee_id', 'name', 'employee_code', 'designation', 'department_id')
                ->orderBy('name')
                ->get();
                
        } elseif ($firstAssignment->assignment_type === 'individual' || $firstAssignment->assignment_type === 'individual_only') {
            $assignmentType = 'employees';
            $selectedEmployeeIds = $assignments->pluck('employee_id')->unique()->filter()->toArray();
            
            // Get these specific employees
            $assignedEmployees = EmployeeDetails::where('institute_id', $instituteId)
                ->whereIn('employee_id', $selectedEmployeeIds)
                ->where('status', 'active')
                ->select('employee_id', 'name', 'employee_code', 'designation', 'department_id')
                ->orderBy('name')
                ->get();
        }
    }

    $departments = Departments::where('institute_id', $instituteId)
        ->withCount(['employees as employees_count' => function ($query) {
            $query->where('status', 'active');
        }])
        ->orderBy('department')
        ->get();
    
    $employees = EmployeeDetails::where('institute_id', $instituteId)
        ->where('status', 'active')
        ->select('employee_id', 'name', 'employee_code', 'department_id', 'employment_type', 'designation')
        ->orderBy('name')
        ->get();

    $employmentTypes = ['Full-time', 'Part-time', 'Contract-based', 'Probation-Period'];
    $exitTypes = ['Resignation', 'Termination', 'End of Contract', 'Mutual Agreement', 'Retirement', 'Other'];

    $unavailableDepartmentIds = $this->getUnavailableDepartmentIds($instituteId, [$policy->exit_type], $policy->id);
    
    // Decode JSON fields
    $fnfItems = $this->decodeJson($policy->fnf_items);
    $ktRequirements = $this->decodeJson($policy->kt_requirements);
    $additionalRequirements = $this->decodeJson($policy->additional_requirements);
    $employmentNoticePeriods = $this->decodeJson($policy->employment_notice_periods);
    $employmentCustomDays = $this->decodeJson($policy->employment_custom_days);

    // Get assignment details
    $assignmentData = PolicyAssignment::where('policy_id', $policy->id)->first();
    
    return view('instituteAdmin.EmployeeExit.edit-policy', compact(
        'policy',
        'departments',
        'employees',
        'employmentTypes',
        'exitTypes',
        'fnfItems',
        'ktRequirements',
        'additionalRequirements',
        'employmentNoticePeriods',
        'employmentCustomDays',
        'assignmentType',
        'selectedDepartmentIds',
        'selectedEmployeeIds',
        'assignmentData',
        'assignmentDepartments',
        'assignedEmployees',
        'unavailableDepartmentIds',
        'context'
    ));
    }

    public function update(Request $request, $id)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];

        $policy = EmployeeExitPolicy::where('id', $id)
            ->where('institute_id', $instituteId)
            ->firstOrFail();

        $messages = [
            'required' => 'The :attribute field is required.',
            'in' => 'Please select a valid :attribute.',
            'integer' => 'The :attribute must be a valid number.',
            'min' => 'The :attribute must be at least :min.',
            'max' => 'The :attribute must not exceed :max.',
            'array' => 'The :attribute must be an array.',
            'required_if' => 'The :attribute is required when :other is selected.',
            'exists' => 'The selected :attribute is invalid.',
            'after' => 'The expiry date must be after the effective date.',
        ];

        $validator = Validator::make($request->all(), [
            'policy_name' => 'required|string|max:255',
            'policy_code' => 'required|string|max:50',
            'exit_type' => 'required|in:Resignation,Termination,End of Contract,Mutual Agreement,Retirement,Other',
            'default_notice_period' => 'required|in:30,45,60,90,custom',
            'default_custom_days' => 'nullable|integer|min:1|max:365|required_if:default_notice_period,custom',
            'employment_notice_periods' => 'nullable|array',
            'employment_custom_days' => 'nullable|array',
            'exit_interview_required' => 'nullable|boolean',
            'interview_days' => 'nullable|integer|min:1|max:30',
            'fnf_required' => 'nullable|boolean',
            'fnf_processing_days' => 'nullable|required_if:fnf_required,1|in:7,15,30,45,60',
            'fnf_settlement_type' => 'nullable|in:standard,expedited',
            'fnf_items' => 'nullable|array',
            'kt_required' => 'nullable|boolean',
            'kt_days' => 'nullable|required_if:kt_required,1|in:3,5,7,10,15,30',
            'kt_requirements' => 'nullable|array',
            'additional_requirements' => 'nullable|array',
            'clearance_workflow' => 'required|in:sequential,parallel,hybrid',
            'clearance_days' => 'required|integer|min:1|max:90',
            'assignment_type' => 'required|in:all,departments,employees',
            'department_ids' => 'nullable|array|required_if:assignment_type,departments',
            'department_ids.*' => 'exists:departments,department_id',
            'employee_ids' => 'nullable|array|required_if:assignment_type,employees',
            'employee_ids.*' => 'exists:employee_details,employee_id',
            'effective_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:effective_date',
            'assignment_notes' => 'nullable|string',
            'description' => 'nullable|string|max:500',
            'terms_conditions' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'override_confirmed' => 'nullable|boolean',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        $selectedDepartmentIds = $data['assignment_type'] === 'all'
            ? Departments::where('institute_id', $instituteId)
                ->where('status', 'active')
                ->pluck('department_id')
                ->all()
            : ($data['department_ids'] ?? []);
        $departmentConflict = $this->validateDepartmentAvailability(
            $instituteId,
            [$data['exit_type']],
            $selectedDepartmentIds,
            $policy->id
        );

        if ($departmentConflict) {
            return redirect()->back()
                ->withErrors(['department_ids' => "These departments already have a policy for this exit type: {$departmentConflict}."])
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update policy details
            $defaultNoticeDays = $data['default_notice_period'] === 'custom' 
                ? (int) $data['default_custom_days'] 
                : (int) $data['default_notice_period'];

            $updateData = [
                'policy_name' => $data['policy_name'],
                'policy_code' => $data['policy_code'],
                'exit_type' => $data['exit_type'],
                'description' => $data['description'] ?? null,
                'terms_conditions' => $data['terms_conditions'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'exit_interview_required' => $data['exit_interview_required'] ?? false,
                'interview_days' => $data['interview_days'] ?? null,
                'fnf_required' => $data['fnf_required'] ?? false,
                'fnf_processing_days' => $data['fnf_processing_days'] ?? null,
                'fnf_settlement_type' => $data['fnf_settlement_type'] ?? 'standard',
                'fnf_items' => isset($data['fnf_items']) ? json_encode($data['fnf_items']) : null,
                'kt_required' => $data['kt_required'] ?? false,
                'kt_days' => $data['kt_days'] ?? null,
                'kt_requirements' => isset($data['kt_requirements']) ? json_encode($data['kt_requirements']) : null,
                'additional_requirements' => isset($data['additional_requirements']) ? json_encode($data['additional_requirements']) : null,
                'clearance_workflow' => $data['clearance_workflow'],
                'clearance_days' => (int) $data['clearance_days'],
                'default_notice_period' => $defaultNoticeDays,
                'employment_notice_periods' => isset($data['employment_notice_periods']) ? json_encode($data['employment_notice_periods']) : null,
                'employment_custom_days' => isset($data['employment_custom_days']) ? json_encode($data['employment_custom_days']) : null,
            ];

            $policy->update($updateData);

            // Update assignments
            $assignmentType = $data['assignment_type'];
            
            // Delete existing assignments
            PolicyAssignment::where('policy_id', $policy->id)->delete();

            // Create new assignments
            if ($assignmentType === 'all') {
                $departments = Departments::where('institute_id', $instituteId)
                    ->where('status', 'active')
                    ->get();

                foreach ($departments as $department) {
                    PolicyAssignment::create([
                        'policy_id' => $policy->id,
                        'department_id' => $department->department_id,
                        'exit_type' => $data['exit_type'],
                        'assignment_type' => 'all_department',
                        'effective_date' => $data['effective_date'],
                        'expiry_date' => $data['expiry_date'] ?? null,
                        'notes' => $data['assignment_notes'] ?? null,
                        'assigned_by' => auth()->id(),
                    ]);
                }
            } elseif ($assignmentType === 'departments') {
                $departmentIds = $data['department_ids'] ?? [];

                foreach ($departmentIds as $departmentId) {
                    PolicyAssignment::create([
                        'policy_id' => $policy->id,
                        'department_id' => $departmentId,
                        'exit_type' => $data['exit_type'],
                        'assignment_type' => 'department',
                        'effective_date' => $data['effective_date'],
                        'expiry_date' => $data['expiry_date'] ?? null,
                        'notes' => $data['assignment_notes'] ?? null,
                        'assigned_by' => auth()->id(),
                    ]);
                }
            } elseif ($assignmentType === 'employees') {
                $employeeIds = $data['employee_ids'] ?? [];

                foreach ($employeeIds as $employeeId) {
                    PolicyAssignment::create([
                        'policy_id' => $policy->id,
                        'employee_id' => $employeeId,
                        'exit_type' => $data['exit_type'],
                        'assignment_type' => 'individual',
                        'effective_date' => $data['effective_date'],
                        'expiry_date' => $data['expiry_date'] ?? null,
                        'notes' => $data['assignment_notes'] ?? null,
                        'assigned_by' => auth()->id(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('exit-policies.index')
                ->with('success', 'Exit policy updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Exit policy update error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'policy_id' => $id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update exit policy: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
{
    $context = $this->getInstituteBranchContext();
    $instituteId = $context['institute_id'];

    $policy = EmployeeExitPolicy::where('id', $id)
        ->where('institute_id', $instituteId)
        ->firstOrFail();

    // Get all assignments for this policy
    $assignments = PolicyAssignment::where('policy_id', $policy->id)->get();

    // Determine assignment type
    $assignmentType = 'unassigned';
    $assignmentLabel = 'Unassigned';
    $assignedEmployees = collect();
    $assignmentDepartments = collect();
    $departmentEmployeeGroups = collect();
    $assignedEmployeeTotal = 0;
    $assignedCount = $assignments->count();

    if ($assignedCount > 0) {
        $firstAssignment = $assignments->first();

        if (in_array($firstAssignment->assignment_type, ['all', 'all_fallback', 'all_department'], true)) {
            $assignmentType = 'all';
            $assignmentLabel = 'All Departments';

            $assignmentDepartments = Departments::where('institute_id', $instituteId)
                ->where('status', 'active')
                ->orderBy('department')
                ->get();

        } elseif (in_array($firstAssignment->assignment_type, ['department', 'department_only'], true)) {
            $assignmentType = 'department';

            $departmentIds = $assignments->pluck('department_id')->unique()->filter()->toArray();

            $assignmentDepartments = Departments::whereIn('department_id', $departmentIds)
                ->where('institute_id', $instituteId)
                ->orderBy('department')
                ->get();

        } elseif (in_array($firstAssignment->assignment_type, ['individual', 'individual_only'], true)) {
            $assignmentType = 'individual';

            $employeeIds = $assignments->pluck('employee_id')->unique()->filter()->toArray();

            $assignedEmployees = EmployeeDetails::where('institute_id', $instituteId)
                ->whereIn('employee_id', $employeeIds)
                ->where('status', 'active')
                ->select('employee_id', 'name', 'employee_code', 'designation', 'department_id')
                ->orderBy('name')
                ->paginate(10);
        }

        // Build department employee groups for 'all' and 'department' assignment types
        if (in_array($assignmentType, ['all', 'department'], true)) {
            $departmentEmployeeGroups = $assignmentDepartments->map(function ($department) use ($instituteId) {
                $employees = EmployeeDetails::where('institute_id', $instituteId)
                    ->where('department_id', $department->department_id)
                    ->where('status', 'active')
                    ->select('employee_id', 'name', 'employee_code', 'designation', 'department_id')
                    ->orderBy('name')
                    ->get();

                return [
                    'department' => $department,
                    'employees' => $employees,
                    'employee_count' => $employees->count(),
                ];
            })->filter(function ($group) {
                return $group['employee_count'] > 0;
            })->values();

            $assignedEmployeeTotal = $departmentEmployeeGroups->sum('employee_count');
        } elseif ($assignedEmployees instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $assignedEmployeeTotal = $assignedEmployees->total();
        }
    }

    // Decode JSON fields ONCE — will be reused inside each department tab
    $employmentNoticePeriods = $this->decodeJson($policy->employment_notice_periods);
    $employmentCustomDays    = $this->decodeJson($policy->employment_custom_days);
    $fnfItems                = $this->decodeJson($policy->fnf_items);
    $ktRequirements          = $this->decodeJson($policy->kt_requirements);
    $additionalRequirements  = $this->decodeJson($policy->additional_requirements);

    return view('instituteAdmin.EmployeeExit.view-policy', compact(
        'policy',
        'assignmentType',
        'assignmentLabel',
        'assignedEmployees',
        'assignmentDepartments',
        'departmentEmployeeGroups',
        'assignedEmployeeTotal',
        'assignedCount',
        'employmentNoticePeriods',
        'employmentCustomDays',
        'fnfItems',
        'ktRequirements',
        'additionalRequirements',
        'context'
    ));
    }

    public function toggleStatus($id)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];

        $policy = EmployeeExitPolicy::where('id', $id)
            ->where('institute_id', $instituteId)
            ->firstOrFail();

        $policy->is_active = !$policy->is_active;
        $policy->save();

        return response()->json([
            'success' => true,
            'message' => 'Policy ' . ($policy->is_active ? 'activated' : 'deactivated') . ' successfully!',
            'is_active' => $policy->is_active
        ]);
    }

    public function destroy($id)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];

        $policy = EmployeeExitPolicy::where('id', $id)
            ->where('institute_id', $instituteId)
            ->firstOrFail();

        PolicyAssignment::where('policy_id', $policy->id)->delete();
        $policy->delete();

        return redirect()->route('exit-policies.index')
            ->with('success', 'Policy deleted successfully!');
    }

    /**
     * Get the applicable policy for an employee based on their exit type
     * This is the key method for retrieving the correct policy
     */
    public function getEmployeePolicy($employeeId, $exitType)
    {
        $employee = EmployeeDetails::with('department')->find($employeeId);
        
        if (!$employee) {
            return null;
        }
        
        // 1. Check for individual employee assignments for this exit type
        $individualAssignment = PolicyAssignment::where('employee_id', $employeeId)
            ->where('exit_type', $exitType)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with('policy')
            ->first();
        
        if ($individualAssignment) {
            return $individualAssignment->policy;
        }
        
        // 2. Check for department assignments for this exit type
        if ($employee->department_id) {
            $departmentAssignment = PolicyAssignment::where('department_id', $employee->department_id)
                ->where('exit_type', $exitType)
                ->where(function($query) {
                    $query->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', now());
                })
                ->with('policy')
                ->first();
            
            if ($departmentAssignment) {
                return $departmentAssignment->policy;
            }
        }
        
        // 3. Check for "All Departments" assignments for this exit type
        $allAssignment = PolicyAssignment::where('assignment_type', 'all_department')
            ->where('exit_type', $exitType)
            ->where(function($query) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            })
            ->with('policy')
            ->first();
        
        return $allAssignment ? $allAssignment->policy : null;
    }

    /**
     * Get notice period for employee with policy overrides
     */
    public function getEmployeeNoticePeriod($employeeId, $exitType)
    {
        $employee = EmployeeDetails::find($employeeId);
        $policy = $this->getEmployeePolicy($employeeId, $exitType);
        
        if (!$policy || !$employee) {
            return null;
        }
        
        // Check for employment type override
        $employmentNoticePeriods = json_decode($policy->employment_notice_periods, true) ?? [];
        $employmentCustomDays = json_decode($policy->employment_custom_days, true) ?? [];
        
        if ($employee->employment_type && isset($employmentNoticePeriods[$employee->employment_type])) {
            $period = $employmentNoticePeriods[$employee->employment_type];
            if ($period === 'custom' && isset($employmentCustomDays[$employee->employment_type])) {
                return (int) $employmentCustomDays[$employee->employment_type];
            }
            return (int) $period;
        }
        
        return $policy->default_notice_period;
    }

    private function decodeJson($data)
    {
        if (is_string($data)) {
            return json_decode($data, true);
        }
        return $data;
    }
}