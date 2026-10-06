<?php

namespace App\Http\Controllers\institute\admin\Reimbursement;

use App\Http\Controllers\Controller;
use App\Models\Departments;
use App\Models\Designations;
use App\Models\DepartmentCategory;
use App\Models\ReimbursementPolicyAssignment;
use App\Models\ReimbursementTravelPolicy;
use App\Models\ReimbursementAccommodationPolicy;
use App\Models\ReimbursementFoodPolicy;
use App\Models\ReimbursementClaim;
use App\Models\ReimbursementOtherPolicies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReimbursementAssignmentController extends Controller
{
    public function indexView()
    {
        return view('instituteAdmin.Reimbursement.AssignReimbursementPolicy');
    }

    private function buildPolicyQuery(string $type, $instituteId, $branchId = null)
    {
        return match ($type) {
            'travel' => ReimbursementTravelPolicy::where('institute_id', $instituteId)
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId)),
            'accommodation' => ReimbursementAccommodationPolicy::where('institute_id', $instituteId)
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId)),
            'food' => ReimbursementFoodPolicy::where('institute_id', $instituteId)
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId)),
            default => ReimbursementOtherPolicies::where('institute_id', $instituteId)
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId)),
        };
    }

    private function resolvePolicy($policyId, $type = null, $instituteId = null, $branchId = null)
    {
        $candidates = array_values(array_unique(array_filter([
            $type,
            'travel',
            'accommodation',
            'food',
            'custom',
            'other',
            'mobile',
            'internet',
            'entertainment',
            'miscellaneous'
        ])));

        foreach ($candidates as $candidateType) {
            $policy = $this->buildPolicyQuery($candidateType, $instituteId, $branchId)
                ->where('reimbursement_policy_id', $policyId)
                ->first();

            if ($policy) {
                return $policy;
            }
        }

        return null;
    }

    private function decodePolicyData($policyData): array
    {
        if (is_array($policyData)) {
            return $policyData;
        }
        if (is_string($policyData)) {
            $decoded = json_decode($policyData, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    private function buildPolicyDataFromModel($policy): array
    {
        $category = $policy->policy_category ?? $policy->category ?? 'other';
        $common = array_filter([
            'submission_within' => $policy->submission_within ?? null,
            'allow_multi_bills' => $policy->allow_multi_bills ?? null,
            'max_bills' => $policy->max_bills ?? null,
            'allow_same_bill' => $policy->allow_same_bill ?? null,
            'settlement_timeline' => $policy->settlement_timeline ?? null,
            'settlement_mode' => $policy->settlement_mode ?? null,
            'auto_settlement' => $policy->auto_settlement ?? null,
            'allow_partial_settlement' => $policy->allow_partial_settlement ?? null,
            'frequency_type' => $policy->frequency_type ?? null,
            'frequency_value' => $policy->frequency_value ?? null,
            'submission_type' => $policy->submission_type ?? null,
            'required_fields' => $policy->required_fields ?? null,
        ], function ($value) {
            return $value !== null && $value !== '';
        });

        $sections = [];

        switch ($category) {
            case 'travel':
                $vehicles = array_filter([
                    'two_wheeler' => [
                        'min' => $policy->two_wheeler_min ?? null,
                        'max' => $policy->two_wheeler_max ?? null,
                        'rate_km' => $policy->two_wheeler_rate_km ?? null,
                        'bill_required' => $policy->two_wheeler_bill_required ?? null,
                        'photo_required' => $policy->two_wheeler_photo_required ?? null,
                    ],
                    'car' => [
                        'min' => $policy->car_min ?? null,
                        'max' => $policy->car_max ?? null,
                        'rate_km' => $policy->car_rate_km ?? null,
                        'bill_required' => $policy->car_bill_required ?? null,
                        'photo_required' => $policy->car_photo_required ?? null,
                    ],
                    'auto' => [
                        'min' => $policy->auto_min ?? null,
                        'max' => $policy->auto_max ?? null,
                        'rate_km' => $policy->auto_rate_km ?? null,
                        'bill_required' => $policy->auto_bill_required ?? null,
                        'photo_required' => $policy->auto_photo_required ?? null,
                    ],
                ], function ($value) {
                    return is_array($value) && array_filter($value, function ($item) {
                        return $item !== null && $item !== '';
                    });
                });

                if (!empty($vehicles)) {
                    $sections[] = ['vehicles' => $vehicles];
                }

                // Bus categories
                if ($policy->bus_categories) {
                    $busCat = is_string($policy->bus_categories) ? json_decode($policy->bus_categories, true) : $policy->bus_categories;
                    if (is_array($busCat)) {
                        $sections[] = ['bus_categories' => $busCat];
                    }
                }

                // Train categories
                if ($policy->train_categories) {
                    $trainCat = is_string($policy->train_categories) ? json_decode($policy->train_categories, true) : $policy->train_categories;
                    if (is_array($trainCat)) {
                        $sections[] = ['train_categories' => $trainCat];
                    }
                }

                // Flight categories
                if ($policy->flight_categories) {
                    $flightCat = is_string($policy->flight_categories) ? json_decode($policy->flight_categories, true) : $policy->flight_categories;
                    if (is_array($flightCat)) {
                        $sections[] = ['flight_categories' => $flightCat];
                    }
                }
                break;

            case 'accommodation':
                $rooms = array_filter([
                    'basic' => [
                        'min' => $policy->basic_min ?? null,
                        'max' => $policy->basic_max ?? null,
                        'includes_food' => $policy->basic_includes_food ?? null,
                        'bill_required' => $policy->basic_bill_required ?? null,
                        'photo_required' => $policy->basic_photo_required ?? null,
                    ],
                    'deluxe' => [
                        'min' => $policy->deluxe_min ?? null,
                        'max' => $policy->deluxe_max ?? null,
                        'includes_food' => $policy->deluxe_includes_food ?? null,
                        'bill_required' => $policy->deluxe_bill_required ?? null,
                        'photo_required' => $policy->deluxe_photo_required ?? null,
                    ],
                    'premium' => [
                        'min' => $policy->premium_min ?? null,
                        'max' => $policy->premium_max ?? null,
                        'includes_food' => $policy->premium_includes_food ?? null,
                        'bill_required' => $policy->premium_bill_required ?? null,
                        'photo_required' => $policy->premium_photo_required ?? null,
                    ],
                ], function ($value) {
                    return is_array($value) && array_filter($value, function ($item) {
                        return $item !== null && $item !== '';
                    });
                });

                if (!empty($rooms)) {
                    $sections[] = ['rooms' => $rooms];
                }
                break;

            case 'food':
                $meals = array_filter([
                    'breakfast' => ['min' => $policy->breakfast_min ?? null, 'max' => $policy->breakfast_max ?? null],
                    'lunch' => ['min' => $policy->lunch_min ?? null, 'max' => $policy->lunch_max ?? null],
                    'dinner' => ['min' => $policy->dinner_min ?? null, 'max' => $policy->dinner_max ?? null],
                    'two_meals' => ['min' => $policy->two_meals_min ?? null, 'max' => $policy->two_meals_max ?? null],
                    'three_meals' => ['min' => $policy->three_meals_min ?? null, 'max' => $policy->three_meals_max ?? null],
                    'bill_required' => $policy->individual_bill_required ?? null,
                    'photo_required' => $policy->individual_photo_required ?? null,
                ], function ($value) {
                    return $value !== null && $value !== '';
                });

                if (!empty($meals)) {
                    $sections[] = ['meals' => $meals];
                }
                break;

            default:
                $fixed = array_filter([
                    'min_amount' => $policy->min_amount ?? null,
                    'max_amount' => $policy->max_amount ?? null,
                    'remarks' => $policy->remarks ?? null,
                    'bill_required' => $policy->bill_required ?? null,
                    'photo_required' => $policy->photo_required ?? null,
                ], function ($value) {
                    return $value !== null && $value !== '';
                });

                if (!empty($fixed)) {
                    $sections[] = ['fixed' => $fixed];
                }
                break;
        }

        if (!empty($common)) {
            $sections[] = ['common' => $common];
        }

        return $sections;
    }

    private function resolvePolicyDataItems($policy): array
    {
        $stored = $this->decodePolicyData($policy->policy_data ?? null);
        if (!empty($stored)) {
            return $stored;
        }
        return $this->buildPolicyDataFromModel($policy);
    }

    private function extractRangesFromPolicy($policy, array $policyDataItems): array
    {
        $ranges = [];
        $sections = [];
        $sectionKeys = ['claim_rules', 'vehicles', 'rooms', 'meals', 'fixed', 'common', 'bus_categories', 'train_categories', 'flight_categories'];

        if (empty($policyDataItems)) {
            return $ranges;
        }

        foreach ($policyDataItems as $key => $value) {
            if (!is_array($value))
                continue;

            $hasSection = false;
            foreach ($sectionKeys as $sectionKey) {
                if (array_key_exists($sectionKey, $value)) {
                    $hasSection = true;
                    break;
                }
            }

            if ($hasSection) {
                $sections[] = $value;
                continue;
            }

            if (in_array($key, $sectionKeys, true) && is_array($value)) {
                $sections[] = [$key => $value];
            }
        }

        if (empty($sections) && !array_is_list($policyDataItems)) {
            $hasSection = false;
            foreach ($sectionKeys as $sectionKey) {
                if (array_key_exists($sectionKey, $policyDataItems)) {
                    $hasSection = true;
                    break;
                }
            }
            if ($hasSection) {
                $sections[] = $policyDataItems;
            }
        }

        foreach ($sections as $section) {
            // Custom claim rules
            if (isset($section['claim_rules']) && is_array($section['claim_rules'])) {
                foreach ($section['claim_rules'] as $rule) {
                    if (!is_array($rule))
                        continue;
                    $ruleFields = $rule['fields'] ?? [];
                    $ranges[] = [
                        'name' => $rule['name'] ?? $rule['claim_name'] ?? 'Custom Rule',
                        'min' => $rule['min'] ?? $ruleFields['min'] ?? 0,
                        'max' => $rule['max'] ?? $ruleFields['max'] ?? 0,
                        'type' => $rule['type'] ?? 'custom',
                        'bill_required' => $rule['bill_required'] ?? 'Yes',
                        'photo_required' => $rule['photo_required'] ?? 'No',
                        'fields' => $ruleFields,
                        'is_custom_rule' => true,
                    ];
                }
            }

            // Vehicles
            if (isset($section['vehicles']) && is_array($section['vehicles'])) {
                foreach ($section['vehicles'] as $key => $vehicle) {
                    if (!is_array($vehicle))
                        continue;
                    $ranges[] = [
                        'name' => ucfirst(str_replace('_', ' ', $key)),
                        'min' => $vehicle['min'] ?? 0,
                        'max' => $vehicle['max'] ?? 0,
                        'rate' => $vehicle['rate_km'] ?? 0,
                        'bill' => $vehicle['bill_required'] ?? 'Yes',
                        'photo' => $vehicle['photo_required'] ?? 'No',
                        'type' => 'vehicle',
                    ];
                }
            }

            // Bus categories
            if (isset($section['bus_categories']) && is_array($section['bus_categories'])) {
                $busNames = ['general' => 'General Bus', 'seater_ac' => 'Seater AC', 'seater_nonac' => 'Seater Non-AC', 'sleeper_ac' => 'Sleeper AC', 'sleeper_nonac' => 'Sleeper Non-AC'];
                foreach ($section['bus_categories'] as $key => $bus) {
                    if (!is_array($bus))
                        continue;
                    $ranges[] = [
                        'name' => $busNames[$key] ?? ucfirst($key),
                        'min' => $bus['min'] ?? 0,
                        'max' => $bus['max'] ?? 0,
                        'bill' => $bus['bill_required'] ?? 'Yes',
                        'photo' => $bus['photo_required'] ?? 'No',
                        'type' => 'bus',
                    ];
                }
            }

            // Train categories
            if (isset($section['train_categories']) && is_array($section['train_categories'])) {
                $trainNames = ['general' => 'General', 'sleeper' => 'Sleeper Class', 'ac3' => '3AC', 'ac2' => '2AC', 'ac1' => '1AC'];
                foreach ($section['train_categories'] as $key => $train) {
                    if (!is_array($train))
                        continue;
                    $ranges[] = [
                        'name' => $trainNames[$key] ?? ucfirst($key),
                        'min' => $train['min'] ?? 0,
                        'max' => $train['max'] ?? 0,
                        'bill' => $train['bill_required'] ?? 'Yes',
                        'photo' => $train['photo_required'] ?? 'No',
                        'type' => 'train',
                    ];
                }
            }

            // Flight categories
            if (isset($section['flight_categories']) && is_array($section['flight_categories'])) {
                $flightNames = ['economy' => 'Economy', 'business' => 'Business Class'];
                foreach ($section['flight_categories'] as $key => $flight) {
                    if (!is_array($flight))
                        continue;
                    $ranges[] = [
                        'name' => $flightNames[$key] ?? ucfirst($key),
                        'min' => $flight['min'] ?? 0,
                        'max' => $flight['max'] ?? 0,
                        'bill' => $flight['bill_required'] ?? 'Yes',
                        'photo' => $flight['photo_required'] ?? 'No',
                        'type' => 'flight',
                    ];
                }
            }

            // Rooms
            if (isset($section['rooms']) && is_array($section['rooms'])) {
                foreach ($section['rooms'] as $key => $room) {
                    if (!is_array($room))
                        continue;
                    $ranges[] = [
                        'name' => ucfirst($key),
                        'min' => $room['min'] ?? 0,
                        'max' => $room['max'] ?? 0,
                        'includes_food' => $room['includes_food'] ?? false,
                        'bill' => $room['bill_required'] ?? 'Yes',
                        'photo' => $room['photo_required'] ?? 'No',
                        'type' => 'room',
                    ];
                }
            }

            // Meals
            if (isset($section['meals']) && is_array($section['meals'])) {
                $mealKeys = ['breakfast', 'lunch', 'dinner', 'two_meals', 'three_meals'];
                foreach ($mealKeys as $key) {
                    if (!isset($section['meals'][$key]) || !is_array($section['meals'][$key]))
                        continue;
                    $meal = $section['meals'][$key];
                    $ranges[] = [
                        'name' => ucfirst(str_replace('_', ' ', $key)),
                        'min' => $meal['min'] ?? 0,
                        'max' => $meal['max'] ?? 0,
                        'type' => 'meal',
                    ];
                }
            }

            // Fixed
            if (isset($section['fixed']) && is_array($section['fixed'])) {
                $fixed = $section['fixed'];
                $ranges[] = [
                    'name' => 'Standard',
                    'min' => $fixed['min_amount'] ?? $fixed['min'] ?? 0,
                    'max' => $fixed['max_amount'] ?? $fixed['max'] ?? 0,
                    'bill' => $fixed['bill_required'] ?? 'Yes',
                    'photo' => $fixed['photo_required'] ?? 'No',
                    'type' => 'fixed',
                ];
            }
        }

        if (empty($ranges) && isset($policy->min_amount) && isset($policy->max_amount)) {
            $ranges[] = [
                'name' => 'Standard',
                'min' => $policy->min_amount,
                'max' => $policy->max_amount,
                'type' => 'direct',
            ];
        }

        return $ranges;
    }

    public function getDepartmentCategories(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;

        $categories = DepartmentCategory::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->select('department_category_id', 'category_name as name')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->department_category_id,
                    'name' => $category->name,
                ];
            });

        return response()->json($categories, 200);
    }

    public function getDepartments(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;

        $departments = Departments::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->select('department_id', 'department as name')
            ->get()
            ->map(function ($department) {
                return [
                    'id' => $department->department_id,
                    'name' => $department->name,
                ];
            });

        return response()->json($departments, 200);
    }

    public function getDesignationsByCategory(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;
        $categoryId = $request->input('department_category_id')
            ?? $request->input('department_category_id')
            ?? $request->input('departmentCategoryId');

        if (!$categoryId) {
            return response()->json([], 200);
        }

        $designations = Designations::where('institute_id', $instituteId)
            ->where('department_category_id', $categoryId)
            ->where('status', 'active')
            ->select('designation_id', 'designations as name')
            ->get()
            ->map(function ($designation) {
                return [
                    'id' => $designation->designation_id,
                    'name' => $designation->name,
                ];
            });

        return response()->json($designations, 200);
    }

    public function getAllDesignations(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;

        $designations = Designations::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->select('designation_id', 'designations as name')
            ->get()
            ->map(function ($designation) {
                return [
                    'id' => $designation->designation_id,
                    'name' => $designation->name,
                ];
            });

        return response()->json($designations, 200);
    }

    public function getEmployeesByDepartment(Request $request)
    {
        $instituteId = auth()->user()->institute_id ?? $request->institute_id;
        $departmentId = $request->input('department_id');

        if (!$departmentId) {
            return response()->json([], 200);
        }

        $employees = \App\Models\EmployeeDetails::where('institute_id', $instituteId)
            ->where('department_id', $departmentId)
            ->where('status', 'active')
            ->select('employee_id', 'name')
            ->get()
            ->map(function ($employee) {
                return [
                    'employee_id' => $employee->employee_id,
                    'name' => $employee->name,
                ];
            });

        return response()->json($employees, 200);
    }

    // public function getAssignmentsJSON()
    // {
    //     $assignments = ReimbursementPolicyAssignment::orderBy('created_at', 'desc')->get();

    //     $assignments->transform(function ($assignment) {
    //         $approvers = $assignment->approvers ?? [];
    //         if (is_string($approvers)) {
    //             $approvers = json_decode($approvers, true) ?? [];
    //         }

    //         $step1Approver = $assignment->step1_approver;
    //         $step2Approver = $assignment->step2_approver;

    //         if (empty($approvers) && ($step1Approver || $step2Approver)) {
    //             if ($step1Approver)
    //                 $approvers[] = $step1Approver;
    //             if ($step2Approver)
    //                 $approvers[] = $step2Approver;
    //         }

    //         $mappedApprovers = [];
    //         foreach ($approvers as $app) {
    //             if (is_string($app) && (strpos($app, 'DESG-') === 0 || preg_match('/^[A-Z0-9-]+$/', $app))) {
    //                 $designation = Designations::where('designation_id', $app)->first();
    //                 if ($designation) {
    //                     $mappedApprovers[] = $designation->designations ?? $designation->name ?? $app;
    //                 } else {
    //                     $mappedApprovers[] = $app;
    //                 }
    //             } else {
    //                 $mappedApprovers[] = $app;
    //             }
    //         }

    //         $assignment->approvers = $mappedApprovers;

    //         // Attach policy metadata
    //         try {
    //             $policy = $this->resolvePolicy(
    //                 $assignment->reimbursement_policy_id,
    //                 $assignment->policy_category,
    //                 $assignment->institute_id,
    //                 $assignment->branch_id
    //             );
    //             if ($policy) {
    //                 $assignment->financial_year = $policy->financial_year ?? null;
    //                 $assignment->calculation_type = $policy->calculation_type ?? null;
    //                 $assignment->effective_from = $policy->effective_from ?? null;
    //                 $assignment->effective_to = $policy->effective_to ?? null;
    //                 $assignment->allow_actual_amount = $policy->allow_actual_amount ?? 'No';
    //                 $assignment->policy_name = $policy->policy_name ?? $assignment->policy_name;
    //             }
    //         } catch (\Exception $e) {
    //             // Silently fail
    //         }

    //         return $assignment;
    //     });

    //     return response()->json($assignments, 200);
    // }


    public function getAssignmentsJSON()
    {
        $assignments = ReimbursementPolicyAssignment::orderBy('created_at', 'desc')->get();

        $assignments->transform(function ($assignment) {
            // Resolve step1 approver name
            $step1Name = $assignment->step1_approver;
            if ($step1Name && preg_match('/^DESG-[A-Z0-9]+$/', $step1Name)) {
                $designation = Designations::where('designation_id', $step1Name)->first();
                if ($designation) {
                    $step1Name = $designation->designations ?? $designation->name ?? $step1Name;
                }
            }

            // Resolve step2 approver name
            $step2Name = $assignment->step2_approver;
            if ($step2Name && preg_match('/^DESG-[A-Z0-9]+$/', $step2Name)) {
                $designation = Designations::where('designation_id', $step2Name)->first();
                if ($designation) {
                    $step2Name = $designation->designations ?? $designation->name ?? $step2Name;
                }
            }

            $assignment->step1_approver_name = $step1Name;
            $assignment->step2_approver_name = $step2Name;

            // Attach policy metadata
            try {
                $policy = $this->resolvePolicy(
                    $assignment->reimbursement_policy_id,
                    $assignment->policy_category,
                    $assignment->institute_id,
                    $assignment->branch_id
                );
                if ($policy) {
                    $assignment->financial_year = $policy->financial_year ?? null;
                    $assignment->calculation_type = $policy->calculation_type ?? null;
                    $assignment->effective_from = $policy->effective_from ?? null;
                    $assignment->effective_to = $policy->effective_to ?? null;
                    $assignment->allow_actual_amount = $policy->allow_actual_amount ?? 'No';
                    $assignment->policy_name = $policy->policy_name ?? $assignment->policy_name;
                }
            } catch (\Exception $e) {
                // Silently fail
            }

            return $assignment;
        });

        return response()->json($assignments, 200);
    }

    public function getPolicyDetails(Request $request)
    {
        try {
            $instituteId = auth()->user()->institute_id ?? $request->institute_id;
            $branchId = auth()->user()->branch_id ?? $request->branch_id ?? null;

            $policyType = $request->input('policy_type')
                ?? $request->input('policy_category')
                ?? $request->input('type');

            $policyId = $request->input('policy_id') ?? $request->input('reimbursement_policy_id');

            if (!$policyId) {
                return response()->json(['success' => false, 'message' => 'Policy ID is required'], 400);
            }

            $policy = $this->resolvePolicy($policyId, $policyType, $instituteId, $branchId);

            if (!$policy) {
                return response()->json(['success' => false, 'message' => 'Policy not found'], 404);
            }

            // ✅ ONLY allow active and upcoming policies for assignment
            // Block expired and inactive completely
            if (!in_array($policy->status, ['active', 'upcoming'])) {
                $statusMessages = [
                    'expired' => 'This policy has expired and is no longer available.',
                    'inactive' => 'This policy is currently inactive.'
                ];
                $message = $statusMessages[$policy->status] ?? 'This policy is not available for assignment.';

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'policy_status' => $policy->status
                ], 400);
            }


            $policyDataItems = $this->resolvePolicyDataItems($policy);
            $category = $policyType ?: ($policy->policy_category ?? 'other');
            $isFixedPolicy = in_array($category, ['mobile', 'internet', 'entertainment', 'miscellaneous']);
            $isReadonlyPolicy = in_array($category, ['travel', 'accommodation', 'food']);

            $reimbursementRequestId = $request->input('reimbursement_request_id');
            $ranges = [];
            $assignment = null;

            if ($reimbursementRequestId) {
                try {
                    $claim = ReimbursementClaim::find($reimbursementRequestId);
                    if ($claim) {
                        $assignment = ReimbursementPolicyAssignment::where('reimbursement_policy_id', $claim->reimbursement_policy_id)
                            ->where('institute_id', $instituteId)
                            ->where('branch_id', $branchId)
                            ->where('status', 'active')
                            ->where(function ($query) use ($claim) {
                                $query->where('designation_id', $claim->designation_id)
                                    ->orWhere('department_id', $claim->department_id)
                                    ->orWhere('department_id', 'all');
                            })->first();
                    }
                } catch (\Exception $e) {
                    // Claim not found
                }
            }

            if ($assignment && !empty($assignment->allowed_ranges)) {
                $ranges = $assignment->allowed_ranges;
                if (is_string($ranges)) {
                    $ranges = json_decode($ranges, true) ?: [];
                }
            } else {
                $ranges = $this->extractRangesFromPolicy($policy, $policyDataItems);
            }

            $responseData = [
                'id' => $policy->id,
                'reimbursement_policy_id' => $policy->reimbursement_policy_id ?? $policy->id,
                'policy_name' => $policy->policy_name,
                'description' => $policy->description ?? '',
                'policy_category' => $category,
                'status' => $policy->status ?? 'active',
                // NEW METADATA FIELDS
                'financial_year' => $policy->financial_year ?? null,
                'calculation_type' => $policy->calculation_type ?? null,
                'effective_from' => $policy->effective_from ?? null,
                'effective_to' => $policy->effective_to ?? null,
                'allow_actual_amount' => $policy->allow_actual_amount ?? 'No',
                'remarks_mandatory' => $policy->remarks_mandatory ?? false,
                'bill_mandatory' => $policy->bill_mandatory ?? false,
                'vendor_mandatory' => $policy->vendor_mandatory ?? false,
                'bill_required' => $policy->bill_required ?? 'Yes',
                'photo_required' => $policy->photo_required ?? 'No',
                // END NEW FIELDS
                'ranges' => $ranges,
                'policy_data' => $policyDataItems,
                'is_fixed_policy' => $isFixedPolicy,
                'is_readonly_policy' => $isReadonlyPolicy,
                'general_limit' => $isFixedPolicy ? [
                    'min' => $policy->min_amount ?? null,
                    'max' => $policy->max_amount ?? null,
                ] : null,
                'min_amount' => $policy->min_amount ?? null,
                'max_amount' => $policy->max_amount ?? null,
                'remarks' => $policy->remarks ?? null,
                'two_wheeler_min' => $policy->two_wheeler_min ?? null,
                'two_wheeler_max' => $policy->two_wheeler_max ?? null,
                'two_wheeler_rate_km' => $policy->two_wheeler_rate_km ?? null,
                'two_wheeler_bill_required' => $policy->two_wheeler_bill_required ?? null,
                'two_wheeler_photo_required' => $policy->two_wheeler_photo_required ?? null,
                'car_min' => $policy->car_min ?? null,
                'car_max' => $policy->car_max ?? null,
                'car_rate_km' => $policy->car_rate_km ?? null,
                'car_bill_required' => $policy->car_bill_required ?? null,
                'car_photo_required' => $policy->car_photo_required ?? null,
                'auto_min' => $policy->auto_min ?? null,
                'auto_max' => $policy->auto_max ?? null,
                'auto_rate_km' => $policy->auto_rate_km ?? null,
                'auto_bill_required' => $policy->auto_bill_required ?? null,
                'auto_photo_required' => $policy->auto_photo_required ?? null,
                'bus_categories' => $policy->bus_categories ?? null,
                'train_categories' => $policy->train_categories ?? null,
                'flight_categories' => $policy->flight_categories ?? null,
                'basic_min' => $policy->basic_min ?? null,
                'basic_max' => $policy->basic_max ?? null,
                'basic_includes_food' => $policy->basic_includes_food ?? null,
                'basic_bill_required' => $policy->basic_bill_required ?? null,
                'basic_photo_required' => $policy->basic_photo_required ?? null,
                'deluxe_min' => $policy->deluxe_min ?? null,
                'deluxe_max' => $policy->deluxe_max ?? null,
                'deluxe_includes_food' => $policy->deluxe_includes_food ?? null,
                'deluxe_bill_required' => $policy->deluxe_bill_required ?? null,
                'deluxe_photo_required' => $policy->deluxe_photo_required ?? null,
                'premium_min' => $policy->premium_min ?? null,
                'premium_max' => $policy->premium_max ?? null,
                'premium_includes_food' => $policy->premium_includes_food ?? null,
                'premium_bill_required' => $policy->premium_bill_required ?? null,
                'premium_photo_required' => $policy->premium_photo_required ?? null,
                'breakfast_min' => $policy->breakfast_min ?? null,
                'breakfast_max' => $policy->breakfast_max ?? null,
                'lunch_min' => $policy->lunch_min ?? null,
                'lunch_max' => $policy->lunch_max ?? null,
                'dinner_min' => $policy->dinner_min ?? null,
                'dinner_max' => $policy->dinner_max ?? null,
                'individual_bill_required' => $policy->individual_bill_required ?? null,
                'individual_photo_required' => $policy->individual_photo_required ?? null,
                'two_meals_min' => $policy->two_meals_min ?? null,
                'two_meals_max' => $policy->two_meals_max ?? null,
                'two_meals_bill_required' => $policy->two_meals_bill_required ?? null,
                'two_meals_photo_required' => $policy->two_meals_photo_required ?? null,
                'three_meals_min' => $policy->three_meals_min ?? null,
                'three_meals_max' => $policy->three_meals_max ?? null,
                'three_meals_bill_required' => $policy->three_meals_bill_required ?? null,
                'three_meals_photo_required' => $policy->three_meals_photo_required ?? null,
                'submission_within' => $policy->submission_within ?? null,
                'frequency_type' => $policy->frequency_type ?? null,
                'frequency_value' => $policy->frequency_value ?? null,
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData
            ]);

        } catch (\Exception $e) {
            \Log::error('Policy details error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching policy details: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     try {
    //         $instituteId = auth()->user()->institute_id ?? $request->institute_id;
    //         $branchId = auth()->user()->branch_id ?? $request->branch_id ?? null;
    //         $assignmentType = $request->input('assignment_type');
    //         $policyId = $request->input('reimbursement_policy_id');
    //         $policyType = $request->input('policy_type')
    //             ?? $request->input('policy_category')
    //             ?? $request->input('type')
    //             ?? 'other';

    //         $policy = $this->resolvePolicy($policyId, $policyType, $instituteId, $branchId);

    //         if (!$policy) {
    //             return response()->json(['message' => 'Policy not found'], 404);
    //         }

    //         $policyData = $this->resolvePolicyDataItems($policy);
    //         $allowedRanges = $request->input('allowed_ranges', []);

    //         if (is_string($allowedRanges)) {
    //             $allowedRanges = json_decode($allowedRanges, true) ?: [];
    //         }

    //         if (empty($allowedRanges)) {
    //             $allowedRanges = $this->extractRangesFromPolicy($policy, $policyData);
    //         }

    //         $approvalType = $request->input('approval_type', '1step');
    //         $approvers = $request->input('approvers', []);
    //         $notes = $request->input('notes');

    //         $createdRows = [];

    //         if ($assignmentType === 'department') {
    //             $departmentInput = $request->input('department_id');

    //             if ($departmentInput === 'all') {
    //                 $departments = Departments::where('institute_id', $instituteId)
    //                     ->where('status', 'active')
    //                     ->get();

    //                 foreach ($departments as $department) {
    //                     $assignment = $this->createAssignment([
    //                         'institute_id' => $instituteId,
    //                         'department_id' => $department->department_id,
    //                         'department_name' => $department->department,
    //                         'assignment_type' => 'department',
    //                         'reimbursement_policy_id' => $policyId,
    //                         'policy_name' => $policy->policy_name,
    //                         'policy_category' => $policyType,
    //                         'policy_details' => $policyData,
    //                         'allowed_ranges' => $allowedRanges,
    //                         'approval_type' => $approvalType,
    //                         'approvers' => $approvers,
    //                         'step1_approver' => $approvers[0] ?? null,
    //                         'step2_approver' => $approvers[1] ?? null,
    //                         'notes' => $notes,
    //                         'approved_by' => auth()->user()->name ?? 'System',
    //                         'effective_from' => now(),
    //                     ]);
    //                     $createdRows[] = $assignment;
    //                 }
    //             } else {
    //                 $department = Departments::where('department_id', $departmentInput)
    //                     ->where('institute_id', $instituteId)
    //                     ->first();

    //                 if ($department) {
    //                     $assignment = $this->createAssignment([
    //                         'institute_id' => $instituteId,
    //                         'department_id' => $department->department_id,
    //                         'department_name' => $department->department,
    //                         'assignment_type' => 'department',
    //                         'reimbursement_policy_id' => $policyId,
    //                         'policy_name' => $policy->policy_name,
    //                         'policy_category' => $policyType,
    //                         'policy_details' => $policyData,
    //                         'allowed_ranges' => $allowedRanges,
    //                         'approval_type' => $approvalType,
    //                         'approvers' => $approvers,
    //                         'step1_approver' => $approvers[0] ?? null,
    //                         'step2_approver' => $approvers[1] ?? null,
    //                         'notes' => $notes,
    //                         'approved_by' => auth()->user()->name ?? 'System',
    //                         'effective_from' => now(),
    //                     ]);
    //                     $createdRows[] = $assignment;
    //                 }
    //             }
    //         } elseif ($assignmentType === 'designation') {
    //             $categoryId = $request->input('department_category_id');
    //             $designationId = $request->input('designation_id');

    //             $designation = Designations::where('designation_id', $designationId)
    //                 ->where('institute_id', $instituteId)
    //                 ->first();

    //             if ($designation) {
    //                 $assignment = $this->createAssignment([
    //                     'institute_id' => $instituteId,
    //                     'department_category_id' => $categoryId,
    //                     'designation_id' => $designationId,
    //                     'designation_name' => $designation->designations,
    //                     'assignment_type' => 'designation',
    //                     'reimbursement_policy_id' => $policyId,
    //                     'policy_name' => $policy->policy_name,
    //                     'policy_category' => $policyType,
    //                     'policy_details' => $policyData,
    //                     'allowed_ranges' => $allowedRanges,
    //                     'approval_type' => $approvalType,
    //                     'approvers' => $approvers,
    //                     'step1_approver' => $approvers[0] ?? null,
    //                     'step2_approver' => $approvers[1] ?? null,
    //                     'notes' => $notes,
    //                     'approved_by' => auth()->user()->name ?? 'System',
    //                     'effective_from' => now(),
    //                 ]);
    //                 $createdRows[] = $assignment;
    //             }
    //         } elseif ($assignmentType === 'employee') {
    //             $departmentId = $request->input('department_id');
    //             $employeeId = $request->input('employee_id');

    //             $employee = \App\Models\EmployeeDetails::where('employee_id', $employeeId)
    //                 ->where('institute_id', $instituteId)
    //                 ->first();

    //             if ($employee) {
    //                 $department = Departments::where('department_id', $departmentId)
    //                     ->where('institute_id', $instituteId)
    //                     ->first();

    //                 $assignment = $this->createAssignment([
    //                     'institute_id' => $instituteId,
    //                     'department_id' => $departmentId,
    //                     'department_name' => $department ? $department->department : null,
    //                     'employee_id' => $employeeId,
    //                     'name' => $employee->name,
    //                     'assignment_type' => 'employee',
    //                     'reimbursement_policy_id' => $policyId,
    //                     'policy_name' => $policy->policy_name,
    //                     'policy_category' => $policyType,
    //                     'policy_details' => $policyData,
    //                     'allowed_ranges' => $allowedRanges,
    //                     'approval_type' => $approvalType,
    //                     'approvers' => $approvers,
    //                     'step1_approver' => $approvers[0] ?? null,
    //                     'step2_approver' => $approvers[1] ?? null,
    //                     'notes' => $notes,
    //                     'approved_by' => auth()->user()->name ?? 'System',
    //                     'effective_from' => now(),
    //                 ]);
    //                 $createdRows[] = $assignment;
    //             }
    //         }

    //         return response()->json([
    //             'message' => 'Policy mapped successfully!',
    //             'data' => $createdRows,
    //         ], 201);

    //     } catch (\Exception $e) {
    //         \Log::error('Error creating assignment: ' . $e->getMessage());
    //         return response()->json(['message' => $e->getMessage()], 400);
    //     }
    // }


    public function store(Request $request)
    {
        try {
            $instituteId = auth()->user()->institute_id ?? $request->institute_id;
            $branchId = auth()->user()->branch_id ?? $request->branch_id ?? null;
            $assignmentType = $request->input('assignment_type');
            $policyId = $request->input('reimbursement_policy_id');
            $policyType = $request->input('policy_type')
                ?? $request->input('policy_category')
                ?? $request->input('type')
                ?? 'other';

            $policy = $this->resolvePolicy($policyId, $policyType, $instituteId, $branchId);

            if (!$policy) {
                return response()->json(['message' => 'Policy not found'], 404);
            }

            // ✅ Only allow assignment of active and upcoming policies
            if (!in_array($policy->status, ['active', 'upcoming'])) {
                $messages = [
                    'expired' => 'Cannot assign an expired policy.',
                    'inactive' => 'Cannot assign an inactive policy.'
                ];
                $message = $messages[$policy->status] ?? 'This policy cannot be assigned.';
                return response()->json(['message' => $message], 400);
            }

            $policyData = $this->resolvePolicyDataItems($policy);
            $allowedRanges = $request->input('allowed_ranges', []);

            if (is_string($allowedRanges)) {
                $allowedRanges = json_decode($allowedRanges, true) ?: [];
            }

            if (empty($allowedRanges)) {
                $allowedRanges = $this->extractRangesFromPolicy($policy, $policyData);
            }

            $approvalType = $request->input('approval_type', '1step');
            $approvers = $request->input('approvers', []);
            $notes = $request->input('notes');

            // Resolve approver IDs to actual designation IDs
            $step1Approver = null;
            $step2Approver = null;

            if (!empty($approvers)) {
                // Step 1 approver (first in array)
                if (isset($approvers[0]) && !empty($approvers[0])) {
                    $step1Approver = $this->resolveApproverId($approvers[0], $instituteId);
                }
                // Step 2 approver (second in array)
                if (isset($approvers[1]) && !empty($approvers[1])) {
                    $step2Approver = $this->resolveApproverId($approvers[1], $instituteId);
                }
            }

            $createdRows = [];

            if ($assignmentType === 'department') {
                $departmentInput = $request->input('department_id');

                if ($departmentInput === 'all') {
                    $departments = Departments::where('institute_id', $instituteId)
                        ->where('status', 'active')
                        ->get();

                    foreach ($departments as $department) {
                        $assignment = $this->createAssignment([
                            'institute_id' => $instituteId,
                            'branch_id' => $branchId,
                            'department_id' => $department->department_id,
                            'department_name' => $department->department,
                            'assignment_type' => 'department',
                            'reimbursement_policy_id' => $policyId,
                            'policy_name' => $policy->policy_name,
                            'policy_category' => $policyType,
                            'policy_details' => $policyData,
                            'allowed_ranges' => $allowedRanges,
                            'approval_type' => $approvalType,
                            'approvers' => $approvers,
                            'step1_approver' => $step1Approver,
                            'step2_approver' => $step2Approver,
                            'notes' => $notes,
                            'approved_by' => auth()->user()->name ?? 'System',
                            'effective_from' => now(),
                        ]);
                        $createdRows[] = $assignment;
                    }
                } else {
                    $department = Departments::where('department_id', $departmentInput)
                        ->where('institute_id', $instituteId)
                        ->first();

                    if ($department) {
                        $assignment = $this->createAssignment([
                            'institute_id' => $instituteId,
                            'branch_id' => $branchId,
                            'department_id' => $department->department_id,
                            'department_name' => $department->department,
                            'assignment_type' => 'department',
                            'reimbursement_policy_id' => $policyId,
                            'policy_name' => $policy->policy_name,
                            'policy_category' => $policyType,
                            'policy_details' => $policyData,
                            'allowed_ranges' => $allowedRanges,
                            'approval_type' => $approvalType,
                            'approvers' => $approvers,
                            'step1_approver' => $step1Approver,
                            'step2_approver' => $step2Approver,
                            'notes' => $notes,
                            'approved_by' => auth()->user()->name ?? 'System',
                            'effective_from' => now(),
                        ]);
                        $createdRows[] = $assignment;
                    }
                }
            } elseif ($assignmentType === 'designation') {
                $categoryId = $request->input('department_category_id');
                $designationId = $request->input('designation_id');

                $designation = Designations::where('designation_id', $designationId)
                    ->where('institute_id', $instituteId)
                    ->first();

                if ($designation) {
                    $assignment = $this->createAssignment([
                        'institute_id' => $instituteId,
                        'branch_id' => $branchId,
                        'department_category_id' => $categoryId,
                        'designation_id' => $designationId,
                        'designation_name' => $designation->designations,
                        'assignment_type' => 'designation',
                        'reimbursement_policy_id' => $policyId,
                        'policy_name' => $policy->policy_name,
                        'policy_category' => $policyType,
                        'policy_details' => $policyData,
                        'allowed_ranges' => $allowedRanges,
                        'approval_type' => $approvalType,
                        'approvers' => $approvers,
                        'step1_approver' => $step1Approver,
                        'step2_approver' => $step2Approver,
                        'notes' => $notes,
                        'approved_by' => auth()->user()->name ?? 'System',
                        'effective_from' => now(),
                    ]);
                    $createdRows[] = $assignment;
                }
            } elseif ($assignmentType === 'employee') {
                $departmentId = $request->input('department_id');
                $employeeId = $request->input('employee_id');

                $employee = \App\Models\EmployeeDetails::where('employee_id', $employeeId)
                    ->where('institute_id', $instituteId)
                    ->first();

                if ($employee) {
                    $department = Departments::where('department_id', $departmentId)
                        ->where('institute_id', $instituteId)
                        ->first();

                    $assignment = $this->createAssignment([
                        'institute_id' => $instituteId,
                        'branch_id' => $branchId,
                        'department_id' => $departmentId,
                        'department_name' => $department ? $department->department : null,
                        'designation_id' => $employee->designation_id ?? null,
                        'employee_id' => $employeeId,
                        'name' => $employee->name,
                        'assignment_type' => 'employee',
                        'reimbursement_policy_id' => $policyId,
                        'policy_name' => $policy->policy_name,
                        'policy_category' => $policyType,
                        'policy_details' => $policyData,
                        'allowed_ranges' => $allowedRanges,
                        'approval_type' => $approvalType,
                        'approvers' => $approvers,
                        'step1_approver' => $step1Approver,
                        'step2_approver' => $step2Approver,
                        'notes' => $notes,
                        'approved_by' => auth()->user()->name ?? 'System',
                        'effective_from' => now(),
                    ]);
                    $createdRows[] = $assignment;
                }
            }

            return response()->json([
                'message' => 'Policy mapped successfully!',
                'data' => $createdRows,
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error creating assignment: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    private function resolveApproverId($approverValue, $instituteId)
    {
        if (empty($approverValue)) {
            return null;
        }

        // Check if it's already a valid designation_id (starts with DESG- or similar pattern)
        if (preg_match('/^DESG-[A-Z0-9]+$/', $approverValue)) {
            // Verify it exists
            $exists = Designations::where('designation_id', $approverValue)
                ->where('institute_id', $instituteId)
                ->exists();
            if ($exists) {
                return $approverValue;
            }
        }

        // Try to find by name
        $designation = Designations::where('institute_id', $instituteId)
            ->where(function ($q) use ($approverValue) {
                $q->where('designations', $approverValue)
                    ->orWhere('name', $approverValue)
                    ->orWhere('designation_id', $approverValue);
            })
            ->first();

        if ($designation) {
            return $designation->designation_id;
        }

        // If not found, return the original value
        return $approverValue;
    }

    private function createAssignment($data)
    {
        $assignment = new ReimbursementPolicyAssignment();
        $assignment->fill($data);
        $assignment->created_by = auth()->id();
        $assignment->status = 'active';
        $assignment->save();
        return $assignment;
    }


    public function destroy($id)
    {
        try {
            $assignment = ReimbursementPolicyAssignment::find($id);
            if (!$assignment) {
                return response()->json(['message' => 'Assignment not found'], 404);
            }
            $assignment->delete();
            return response()->json(['message' => 'Assignment removed successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}