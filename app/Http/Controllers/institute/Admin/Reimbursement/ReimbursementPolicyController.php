<?php

namespace App\Http\Controllers\institute\admin\Reimbursement;

use App\Http\Controllers\Controller;
use App\Models\ReimbursementTravelPolicy;
use App\Models\ReimbursementAccommodationPolicy;
use App\Models\ReimbursementFoodPolicy;
use App\Models\ReimbursementOtherPolicies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReimbursementPolicyController extends Controller
{
    /**
     * Generate a unique reimbursement policy ID
     * Format: RMP-YYYYMMDD-XXXXX (RMP = Reimbursement Policy)
     */
    private function generatePolicyId()
    {
        $date = Carbon::now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        return "RMP-{$date}-{$random}";
    }

    /**
     * Check if policy ID exists in any policy table
     */
    private function isPolicyIdUnique($policyId)
    {
        $existsInTravel = ReimbursementTravelPolicy::where('reimbursement_policy_id', $policyId)->exists();
        $existsInAccommodation = ReimbursementAccommodationPolicy::where('reimbursement_policy_id', $policyId)->exists();
        $existsInFood = ReimbursementFoodPolicy::where('reimbursement_policy_id', $policyId)->exists();
        $existsInOther = ReimbursementOtherPolicies::where('reimbursement_policy_id', $policyId)->exists();

        return !($existsInTravel || $existsInAccommodation || $existsInFood || $existsInOther);
    }

    /**
     * Get unique policy ID
     */
    private function getUniquePolicyId()
    {
        do {
            $policyId = $this->generatePolicyId();
        } while (!$this->isPolicyIdUnique($policyId));

        return $policyId;
    }

    /**
     * Display a listing of all reimbursement policies
     */
    /**
     * Display a listing of all reimbursement policies
     */
    public function index()
    {
        $instituteId = Auth::user()->institute_id;
        $branchId = Auth::user()->branch_id;

        $now = Carbon::now()->startOfDay();

        // Auto-expire and auto-activate policies before fetching
        try {
            $models = [
                ReimbursementTravelPolicy::class,
                ReimbursementAccommodationPolicy::class,
                ReimbursementFoodPolicy::class,
                ReimbursementOtherPolicies::class,
            ];

            foreach ($models as $model) {
                $table = (new $model)->getTable();

                // Expire policies whose effective_to has passed
                if (Schema::hasColumn($table, 'effective_to')) {
                    $model::where('institute_id', $instituteId)
                        ->where('branch_id', $branchId)
                        ->whereNotNull('effective_to')
                        ->where('effective_to', '<', $now)
                        ->whereNotIn('status', ['expired', 'inactive'])
                        ->update(['status' => 'expired']);
                }

                // Activate policies whose effective_from has arrived
                if (Schema::hasColumn($table, 'effective_from')) {
                    $model::where('institute_id', $instituteId)
                        ->where('branch_id', $branchId)
                        ->whereNotNull('effective_from')
                        ->where('effective_from', '<=', $now)
                        ->where('status', 'upcoming')
                        ->update(['status' => 'active']);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Policy status auto-update failed: ' . $e->getMessage());
        }

        $travelPolicies = ReimbursementTravelPolicy::where('institute_id', $instituteId)
            ->where('branch_id', $branchId)
            ->latest()
            ->get();

        $accommodationPolicies = ReimbursementAccommodationPolicy::where('institute_id', $instituteId)
            ->where('branch_id', $branchId)
            ->latest()
            ->get();

        $foodPolicies = ReimbursementFoodPolicy::where('institute_id', $instituteId)
            ->where('branch_id', $branchId)
            ->latest()
            ->get();

        $otherPolicies = ReimbursementOtherPolicies::where('institute_id', $instituteId)
            ->where('branch_id', $branchId)
            ->latest()
            ->get();

        // Merge all policies for the listing
        $allPolicies = collect()
            ->merge($travelPolicies)
            ->merge($accommodationPolicies)
            ->merge($foodPolicies)
            ->merge($otherPolicies)
            ->sortByDesc('created_at')
            ->values();

        // Return JSON for AJAX requests
        if (request()->expectsJson() || request()->ajax() || request()->wantsJson()) {
            return response()->json($allPolicies);
        }

        // Return view for direct page loads
        return view('instituteAdmin.Reimbursement.ReimbursementPolicies', compact('allPolicies'));
    }
    /**
     * Store a newly created reimbursement policy
     */
    /**
     * Store a newly created reimbursement policy
     */


    /**
     * Refresh and update the policy status based on effective dates
     * 
     * @param mixed $policy
     * @return mixed
     */
    private function refreshPolicyStatus($policy)
    {
        $now = Carbon::now()->startOfDay();
        $effectiveFrom = $policy->effective_from ? Carbon::parse($policy->effective_from)->startOfDay() : null;
        $effectiveTo = $policy->effective_to ? Carbon::parse($policy->effective_to)->startOfDay() : null;

        $newStatus = 'active'; // default

        if ($effectiveFrom && $effectiveFrom->gt($now)) {
            $newStatus = 'upcoming';
        } elseif ($effectiveTo && $effectiveTo->lt($now)) {
            $newStatus = 'expired';
        } elseif ($effectiveFrom && $effectiveFrom->lte($now)) {
            $newStatus = 'active';
        }

        // Only update if status has changed
        if ($policy->status !== $newStatus) {
            $policy->update(['status' => $newStatus]);
            $policy->refresh();
        }

        return $policy;
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $instituteId = Auth::user()->institute_id;
            $branchId = Auth::user()->branch_id;

            // Base validation rules
            $rules = [
                'policy_name' => 'required|string|max:255',
                'policy_category' => 'required|string|in:travel,accommodation,food,mobile,internet,entertainment,miscellaneous,custom',
                'description' => 'nullable|string|max:1000',
                'financial_year' => 'required|string|max:10',
                'calculation_type' => 'required|string|in:per_claim,per_day,per_month,per_quarter,per_year,lumpsum',
                'effective_from' => 'required|date',
                'effective_to' => 'nullable|date|after_or_equal:effective_from',
                'frequency_type' => 'required|string|in:unlimited,day,week,month,quarter,year',
                'frequency_value' => 'nullable|integer|min:1',
                'submission_within' => 'required|integer|min:1',
                'submission_type' => 'required|string|in:expense_date,bill_date',
                'settlement_timeline' => 'required|integer|min:1',
                'settlement_mode' => 'required|string|in:payroll,bank_transfer,cash,wallet',
                'auto_settlement' => 'required|string|in:Yes,No',
                'allow_partial_settlement' => 'required|string|in:Yes,No',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate unique policy ID
            $reimbursementPolicyId = $this->getUniquePolicyId();

            // Determine initial status based on effective dates (INLINE)
            $now = Carbon::now()->startOfDay();
            $effectiveFrom = Carbon::parse($request->effective_from)->startOfDay();
            $effectiveTo = $request->effective_to ? Carbon::parse($request->effective_to)->startOfDay() : null;

            if ($effectiveFrom->gt($now)) {
                $status = 'upcoming';
            } elseif ($effectiveTo && $effectiveTo->lt($now)) {
                $status = 'expired';
            } else {
                $status = 'active';
            }

            $category = $request->policy_category;
            $policy = null;

            switch ($category) {
                case 'travel':
                    $policy = $this->storeTravelPolicy($request, $instituteId, $branchId, $reimbursementPolicyId, $status);
                    break;

                case 'accommodation':
                    $policy = $this->storeAccommodationPolicy($request, $instituteId, $branchId, $reimbursementPolicyId, $status);
                    break;

                case 'food':
                    $policy = $this->storeFoodPolicy($request, $instituteId, $branchId, $reimbursementPolicyId, $status);
                    break;

                case 'mobile':
                case 'internet':
                case 'entertainment':
                case 'miscellaneous':
                case 'custom':
                    $policy = $this->storeOtherPolicy($request, $instituteId, $branchId, $reimbursementPolicyId, $status);
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reimbursement policy created successfully',
                'data' => $policy,
                'policy_id' => $reimbursementPolicyId
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create policy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store travel reimbursement policy
     */
    private function storeTravelPolicy(Request $request, $instituteId, $branchId, $policyId, $status)
    {
        // Validate travel-specific fields
        $validator = Validator::make($request->all(), [
            'two_wheeler_min' => 'nullable|numeric|min:0',
            'two_wheeler_max' => 'nullable|numeric|min:0',
            'two_wheeler_rate_km' => 'nullable|numeric|min:0',
            'two_wheeler_bill_required' => 'nullable|string|in:Yes,No',
            'two_wheeler_photo_required' => 'nullable|string|in:Yes,No',
            'car_min' => 'nullable|numeric|min:0',
            'car_max' => 'nullable|numeric|min:0',
            'car_rate_km' => 'nullable|numeric|min:0',
            'car_bill_required' => 'nullable|string|in:Yes,No',
            'car_photo_required' => 'nullable|string|in:Yes,No',
            'auto_min' => 'nullable|numeric|min:0',
            'auto_max' => 'nullable|numeric|min:0',
            'auto_rate_km' => 'nullable|numeric|min:0',
            'auto_bill_required' => 'nullable|string|in:Yes,No',
            'auto_photo_required' => 'nullable|string|in:Yes,No',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Travel policy validation failed: ' . implode(', ', $validator->errors()->all()));
        }

        return ReimbursementTravelPolicy::create([
            'reimbursement_policy_id' => $policyId,
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
            'policy_name' => $request->policy_name,
            'policy_category' => $request->policy_category,
            'description' => $request->description,
            'two_wheeler_min' => $request->two_wheeler_min,
            'two_wheeler_max' => $request->two_wheeler_max,
            'two_wheeler_rate_km' => $request->two_wheeler_rate_km,
            'two_wheeler_bill_required' => $request->two_wheeler_bill_required ?? 'Yes',
            'two_wheeler_photo_required' => $request->two_wheeler_photo_required ?? 'No',
            'car_min' => $request->car_min,
            'car_max' => $request->car_max,
            'car_rate_km' => $request->car_rate_km,
            'car_bill_required' => $request->car_bill_required ?? 'Yes',
            'car_photo_required' => $request->car_photo_required ?? 'No',
            'auto_min' => $request->auto_min,
            'auto_max' => $request->auto_max,
            'auto_rate_km' => $request->auto_rate_km,
            'auto_bill_required' => $request->auto_bill_required ?? 'Yes',
            'auto_photo_required' => $request->auto_photo_required ?? 'No',
            'bus_categories' => $request->has('bus_categories') ? json_encode($request->bus_categories) : null,
            'train_categories' => $request->has('train_categories') ? json_encode($request->train_categories) : null,
            'flight_categories' => $request->has('flight_categories') ? json_encode($request->flight_categories) : null,
            'frequency_type' => $request->frequency_type,
            'frequency_value' => $request->frequency_value,
            'submission_within' => $request->submission_within,
            'submission_type' => $request->submission_type,
            'financial_year' => $request->financial_year,
            'calculation_type' => $request->calculation_type,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
            'allow_actual_amount' => $request->allow_actual_amount ?? 'No',
            'allow_multi_bills' => $request->allow_multi_bills ?? 'Yes',
            'max_bills' => $request->max_bills ?? 5,
            'allow_same_bill' => $request->allow_same_bill ?? 'No',
            'remarks_mandatory' => $request->has('remarks_mandatory'),
            'bill_mandatory' => $request->has('bill_mandatory'),
            'vendor_mandatory' => $request->has('vendor_mandatory'),
            'settlement_timeline' => $request->settlement_timeline,
            'settlement_mode' => $request->settlement_mode,
            'auto_settlement' => $request->auto_settlement ?? 'No',
            'allow_partial_settlement' => $request->allow_partial_settlement ?? 'Yes',
            'status' => $status
        ]);
    }

    /**
     * Store accommodation reimbursement policy
     */
    private function storeAccommodationPolicy(Request $request, $instituteId, $branchId, $policyId, $status)
    {
        $validator = Validator::make($request->all(), [
            'basic_min' => 'nullable|numeric|min:0',
            'basic_max' => 'nullable|numeric|min:0',
            'basic_includes_food' => 'nullable|boolean',
            'basic_bill_required' => 'nullable|string|in:Yes,No',
            'basic_photo_required' => 'nullable|string|in:Yes,No',
            'deluxe_min' => 'nullable|numeric|min:0',
            'deluxe_max' => 'nullable|numeric|min:0',
            'deluxe_includes_food' => 'nullable|boolean',
            'deluxe_bill_required' => 'nullable|string|in:Yes,No',
            'deluxe_photo_required' => 'nullable|string|in:Yes,No',
            'premium_min' => 'nullable|numeric|min:0',
            'premium_max' => 'nullable|numeric|min:0',
            'premium_includes_food' => 'nullable|boolean',
            'premium_bill_required' => 'nullable|string|in:Yes,No',
            'premium_photo_required' => 'nullable|string|in:Yes,No',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Accommodation policy validation failed: ' . implode(', ', $validator->errors()->all()));
        }

        return ReimbursementAccommodationPolicy::create([
            'reimbursement_policy_id' => $policyId,
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
            'policy_name' => $request->policy_name,
            'policy_category' => $request->policy_category,
            'description' => $request->description,
            'basic_min' => $request->basic_min,
            'basic_max' => $request->basic_max,
            'basic_includes_food' => $request->has('basic_includes_food'),
            'basic_bill_required' => $request->basic_bill_required ?? 'Yes',
            'basic_photo_required' => $request->basic_photo_required ?? 'No',
            'deluxe_min' => $request->deluxe_min,
            'deluxe_max' => $request->deluxe_max,
            'deluxe_includes_food' => $request->has('deluxe_includes_food'),
            'deluxe_bill_required' => $request->deluxe_bill_required ?? 'Yes',
            'deluxe_photo_required' => $request->deluxe_photo_required ?? 'No',
            'premium_min' => $request->premium_min,
            'premium_max' => $request->premium_max,
            'premium_includes_food' => $request->has('premium_includes_food'),
            'premium_bill_required' => $request->premium_bill_required ?? 'Yes',
            'premium_photo_required' => $request->premium_photo_required ?? 'No',
            'frequency_type' => $request->frequency_type,
            'frequency_value' => $request->frequency_value,
            'submission_within' => $request->submission_within,
            'submission_type' => $request->submission_type,
            'financial_year' => $request->financial_year,
            'calculation_type' => $request->calculation_type,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
            'allow_actual_amount' => $request->allow_actual_amount ?? 'No',
            'allow_multi_bills' => $request->allow_multi_bills ?? 'Yes',
            'max_bills' => $request->max_bills ?? 5,
            'allow_same_bill' => $request->allow_same_bill ?? 'No',
            'remarks_mandatory' => $request->has('remarks_mandatory'),
            'bill_mandatory' => $request->has('bill_mandatory'),
            'vendor_mandatory' => $request->has('vendor_mandatory'),
            'settlement_timeline' => $request->settlement_timeline,
            'settlement_mode' => $request->settlement_mode,
            'auto_settlement' => $request->auto_settlement ?? 'No',
            'allow_partial_settlement' => $request->allow_partial_settlement ?? 'Yes',
            'status' => $status
        ]);
    }

    /**
     * Store food reimbursement policy
     */
    private function storeFoodPolicy(Request $request, $instituteId, $branchId, $policyId, $status)
    {
        $validator = Validator::make($request->all(), [
            'breakfast_min' => 'nullable|numeric|min:0',
            'breakfast_max' => 'nullable|numeric|min:0',
            'lunch_min' => 'nullable|numeric|min:0',
            'lunch_max' => 'nullable|numeric|min:0',
            'dinner_min' => 'nullable|numeric|min:0',
            'dinner_max' => 'nullable|numeric|min:0',
            'individual_bill_required' => 'nullable|string|in:Yes,No',
            'individual_photo_required' => 'nullable|string|in:Yes,No',
            'two_meals_min' => 'nullable|numeric|min:0',
            'two_meals_max' => 'nullable|numeric|min:0',
            'two_meals_bill_required' => 'nullable|string|in:Yes,No',
            'two_meals_photo_required' => 'nullable|string|in:Yes,No',
            'three_meals_min' => 'nullable|numeric|min:0',
            'three_meals_max' => 'nullable|numeric|min:0',
            'three_meals_bill_required' => 'nullable|string|in:Yes,No',
            'three_meals_photo_required' => 'nullable|string|in:Yes,No',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Food policy validation failed: ' . implode(', ', $validator->errors()->all()));
        }

        return ReimbursementFoodPolicy::create([
            'reimbursement_policy_id' => $policyId,
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
            'policy_name' => $request->policy_name,
            'policy_category' => $request->policy_category,
            'description' => $request->description,
            'breakfast_min' => $request->breakfast_min,
            'breakfast_max' => $request->breakfast_max,
            'lunch_min' => $request->lunch_min,
            'lunch_max' => $request->lunch_max,
            'dinner_min' => $request->dinner_min,
            'dinner_max' => $request->dinner_max,
            'individual_bill_required' => $request->individual_bill_required ?? 'Yes',
            'individual_photo_required' => $request->individual_photo_required ?? 'No',
            'two_meals_min' => $request->two_meals_min,
            'two_meals_max' => $request->two_meals_max,
            'two_meals_bill_required' => $request->two_meals_bill_required ?? 'Yes',
            'two_meals_photo_required' => $request->two_meals_photo_required ?? 'No',
            'three_meals_min' => $request->three_meals_min,
            'three_meals_max' => $request->three_meals_max,
            'three_meals_bill_required' => $request->three_meals_bill_required ?? 'Yes',
            'three_meals_photo_required' => $request->three_meals_photo_required ?? 'No',
            'frequency_type' => $request->frequency_type,
            'frequency_value' => $request->frequency_value,
            'submission_within' => $request->submission_within,
            'submission_type' => $request->submission_type,
            'financial_year' => $request->financial_year,
            'calculation_type' => $request->calculation_type,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
            'allow_actual_amount' => $request->allow_actual_amount ?? 'No',
            'allow_multi_bills' => $request->allow_multi_bills ?? 'Yes',
            'max_bills' => $request->max_bills ?? 5,
            'allow_same_bill' => $request->allow_same_bill ?? 'No',
            'remarks_mandatory' => $request->has('remarks_mandatory'),
            'bill_mandatory' => $request->has('bill_mandatory'),
            'vendor_mandatory' => $request->has('vendor_mandatory'),
            'settlement_timeline' => $request->settlement_timeline,
            'settlement_mode' => $request->settlement_mode,
            'auto_settlement' => $request->auto_settlement ?? 'No',
            'allow_partial_settlement' => $request->allow_partial_settlement ?? 'Yes',
            'status' => $status
        ]);
    }

    /**
     * Store other/custom reimbursement policy
     */
    private function storeOtherPolicy(Request $request, $instituteId, $branchId, $policyId, $status)
    {
        $data = [
            'reimbursement_policy_id' => $policyId,
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
            'policy_name' => $request->policy_name,
            'policy_category' => $request->policy_category,
            'description' => $request->description,
            'min_amount' => $request->min_amount ?? 0,
            'max_amount' => $request->max_amount ?? 0,
            'remarks' => $request->remarks,
            'bill_required' => $request->bill_required ?? 'Yes',
            'photo_required' => $request->photo_required ?? 'No',
            'frequency_type' => $request->frequency_type,
            'frequency_value' => $request->frequency_value,
            'submission_within' => $request->submission_within,
            'submission_type' => $request->submission_type,
            'financial_year' => $request->financial_year,
            'calculation_type' => $request->calculation_type,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
            'allow_actual_amount' => $request->allow_actual_amount ?? 'No',
            'allow_multi_bills' => $request->allow_multi_bills ?? 'Yes',
            'max_bills' => $request->max_bills ?? 5,
            'allow_same_bill' => $request->allow_same_bill ?? 'No',
            'remarks_mandatory' => $request->has('remarks_mandatory'),
            'bill_mandatory' => $request->has('bill_mandatory'),
            'vendor_mandatory' => $request->has('vendor_mandatory'),
            'settlement_timeline' => $request->settlement_timeline,
            'settlement_mode' => $request->settlement_mode,
            'auto_settlement' => $request->auto_settlement ?? 'No',
            'allow_partial_settlement' => $request->allow_partial_settlement ?? 'Yes',
            'status' => $status
        ];

        // For custom policies, store the claim rules in policy_data JSON field
        if ($request->policy_category === 'custom') {
            \Log::info('Custom policy data received:', [
                'has_custom_rules' => $request->has('custom_rules'),
                'custom_rules' => $request->custom_rules,
                'has_policy_data' => $request->has('policy_data'),
                'policy_data' => $request->policy_data,
            ]);

            $policyData = [];

            // Try custom_rules array first
            if ($request->has('custom_rules') && is_array($request->custom_rules)) {
                foreach ($request->custom_rules as $rule) {
                    if (!empty($rule['name'])) {
                        $policyData[] = [
                            'name' => $rule['name'],
                            'type' => $rule['type'] ?? 'actual',
                            'fields' => $rule['fields'] ?? [],
                            'bill_required' => $rule['bill_required'] ?? 'Yes',
                            'photo_required' => $rule['photo_required'] ?? 'No',
                        ];
                    }
                }
            }
            // Fallback: check for policy_data JSON string
            elseif ($request->has('policy_data') && !empty($request->policy_data)) {
                $decoded = json_decode($request->policy_data, true);
                if (is_array($decoded)) {
                    $policyData = array_values(array_filter($decoded, function ($rule) {
                        return !empty($rule['name']);
                    }));
                }
            }

            // Clean up null/empty values in fields
            $policyData = array_map(function ($rule) {
                if (isset($rule['fields']) && is_array($rule['fields'])) {
                    // Keep all fields, even null ones (they define the structure)
                    // But convert empty strings to null for consistency
                    $rule['fields'] = array_map(function ($value) {
                        return $value === '' ? null : $value;
                    }, $rule['fields']);
                }
                return $rule;
            }, $policyData);

            \Log::info('Final policy_data:', $policyData);
            $data['policy_data'] = $policyData;
        }

        return ReimbursementOtherPolicies::create($data);
    }

    /**
     * Display the specified policy with all details
     */
    public function view($id)
    {
        $instituteId = Auth::user()->institute_id;
        $branchId = Auth::user()->branch_id;

        $policy = $this->findPolicyById($id, $instituteId, $branchId);

        if (!$policy) {
            return redirect()->route('institute-admin.reimbursement-policies.index')
                ->with('error', 'Policy not found');
        }

        // Get policy details based on category
        $policyDetails = $this->getPolicyDetailedView($policy);

        return view('instituteAdmin.reimbursement.viewPolicyDetails', compact('policy', 'policyDetails'));
    }

    /**
     * Show the form for editing the specified policy
     */
    public function edit($id)
    {
        $instituteId = Auth::user()->institute_id;
        $branchId = Auth::user()->branch_id;

        $policy = $this->findPolicyById($id, $instituteId, $branchId);

        if (!$policy) {
            return redirect()->route('institute-admin.reimbursement-policies.index')
                ->with('error', 'Policy not found');
        }

        return view('instituteAdmin.reimbursement.editPolicy', compact('policy'));
    }

    /**
     * Update the specified policy
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $instituteId = Auth::user()->institute_id;
            $branchId = Auth::user()->branch_id;

            $policy = $this->findPolicyById($id, $instituteId, $branchId);

            if (!$policy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Policy not found'
                ], 404);
            }

            $category = $request->policy_category ?? $policy->policy_category;
            $updatedPolicy = null;

            switch ($category) {
                case 'travel':
                    $updatedPolicy = $this->updateTravelPolicy($request, $policy);
                    break;

                case 'accommodation':
                    $updatedPolicy = $this->updateAccommodationPolicy($request, $policy);
                    break;

                case 'food':
                    $updatedPolicy = $this->updateFoodPolicy($request, $policy);
                    break;

                case 'mobile':
                case 'internet':
                case 'entertainment':
                case 'miscellaneous':
                case 'custom':
                    $updatedPolicy = $this->updateOtherPolicy($request, $policy);
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Policy updated successfully',
                'data' => $updatedPolicy
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update policy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete the specified policy
     */
    public function destroy($id)
    {
        try {
            $instituteId = Auth::user()->institute_id;
            $branchId = Auth::user()->branch_id;

            $policy = $this->findPolicyById($id, $instituteId, $branchId);

            if (!$policy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Policy not found'
                ], 404);
            }

            $policy->delete();

            return response()->json([
                'success' => true,
                'message' => 'Policy deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete policy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find policy by ID across all policy types
     * Can search by database ID or reimbursement_policy_id
     */
    private function findPolicyById($id, $instituteId, $branchId)
    {
        // Try to find by primary key first
        $policy = ReimbursementTravelPolicy::where('id', $id)
            ->where('institute_id', $instituteId)
            ->first();

        if (!$policy) {
            $policy = ReimbursementAccommodationPolicy::where('id', $id)
                ->where('institute_id', $instituteId)
                ->first();
        }

        if (!$policy) {
            $policy = ReimbursementFoodPolicy::where('id', $id)
                ->where('institute_id', $instituteId)
                ->first();
        }

        if (!$policy) {
            $policy = ReimbursementOtherPolicies::where('id', $id)
                ->where('institute_id', $instituteId)
                ->first();
        }

        // If not found by primary key, try by reimbursement_policy_id
        if (!$policy) {
            $policy = ReimbursementTravelPolicy::where('reimbursement_policy_id', $id)
                ->where('institute_id', $instituteId)
                ->first();

            if (!$policy) {
                $policy = ReimbursementAccommodationPolicy::where('reimbursement_policy_id', $id)
                    ->where('institute_id', $instituteId)
                    ->first();
            }

            if (!$policy) {
                $policy = ReimbursementFoodPolicy::where('reimbursement_policy_id', $id)
                    ->where('institute_id', $instituteId)
                    ->first();
            }

            if (!$policy) {
                $policy = ReimbursementOtherPolicies::where('reimbursement_policy_id', $id)
                    ->where('institute_id', $instituteId)
                    ->first();
            }
        }

        // Refresh status when retrieving a single policy
        if ($policy) {
            $policy = $this->refreshPolicyStatus($policy);
        }

        return $policy;
    }

    /**
     * Get detailed view data for policy display
     */
    private function getPolicyDetailedView($policy)
    {
        $details = [
            'basic_info' => [
                'Policy ID' => $policy->reimbursement_policy_id,
                'Policy Name' => $policy->policy_name,
                'Category' => ucfirst($policy->policy_category),
                'Description' => $policy->description ?? 'N/A',
                'Financial Year' => $policy->financial_year ?? 'N/A',
                'Calculation Type' => $policy->calculation_type
                    ? str_replace('_', ' ', ucfirst(str_replace('per_', 'Per ', $policy->calculation_type)))
                    : 'N/A',
                'Effective From' => $policy->effective_from
                    ? Carbon::parse($policy->effective_from)->format('d M Y')
                    : 'N/A',
                'Effective To' => $policy->effective_to
                    ? Carbon::parse($policy->effective_to)->format('d M Y')
                    : 'Ongoing',
                'Status' => ucfirst($policy->status),
            ],
            'claim_rules' => [
                'Frequency Type' => ucfirst($policy->frequency_type),
                'Frequency Value' => $policy->frequency_value ?? 'Unlimited',
                'Submission Within' => $policy->submission_within . ' days',
                'Submission Type' => $policy->submission_type === 'expense_date' ? 'After Expense Date' : 'Of Bill Date',
                'Allow Multiple Bills' => $policy->allow_multi_bills,
                'Max Bills' => $policy->max_bills ?? 'N/A',
                'Allow Same Bill Number' => $policy->allow_same_bill,
            ],
            'mandatory_fields' => [
                'Expense Date' => 'Required',
                'Remarks' => $policy->remarks_mandatory ? 'Required' : 'Optional',
                'Bill Number' => $policy->bill_mandatory ? 'Required' : 'Optional',
                'Vendor Name' => $policy->vendor_mandatory ? 'Required' : 'Optional',
            ],
            'settlement_rules' => [
                'Settlement Timeline' => $policy->settlement_timeline . ' days',
                'Settlement Mode' => str_replace('_', ' ', ucfirst($policy->settlement_mode)),
                'Auto Settlement' => $policy->auto_settlement,
                'Allow Partial Settlement' => $policy->allow_partial_settlement,
            ]
        ];

        // Add category-specific details
        if ($policy->policy_category === 'travel') {
            $details['reimbursement_values'] = [
                'Two Wheeler' => [
                    'Range' => "₹{$policy->two_wheeler_min} - ₹{$policy->two_wheeler_max}",
                    'Rate/KM' => "₹{$policy->two_wheeler_rate_km}",
                    'Bill Required' => $policy->two_wheeler_bill_required,
                    'Photo Required' => $policy->two_wheeler_photo_required,
                ],
                'Car' => [
                    'Range' => "₹{$policy->car_min} - ₹{$policy->car_max}",
                    'Rate/KM' => "₹{$policy->car_rate_km}",
                    'Bill Required' => $policy->car_bill_required,
                    'Photo Required' => $policy->car_photo_required,
                ],
                'Auto' => [
                    'Range' => "₹{$policy->auto_min} - ₹{$policy->auto_max}",
                    'Rate/KM' => "₹{$policy->auto_rate_km}",
                    'Bill Required' => $policy->auto_bill_required,
                    'Photo Required' => $policy->auto_photo_required,
                ],
                'Bus' => [
                    'Range' => "₹{$policy->bus_min} - ₹{$policy->bus_max}",
                    'Bill Required' => $policy->bus_bill_required,
                    'Photo Required' => $policy->bus_photo_required,
                ],
                'Train' => [
                    'Range' => "₹{$policy->train_min} - ₹{$policy->train_max}",
                    'Bill Required' => $policy->train_bill_required,
                    'Photo Required' => $policy->train_photo_required,
                ],
                'Flight' => [
                    'Range' => "₹{$policy->flight_min} - ₹{$policy->flight_max}",
                    'Bill Required' => $policy->flight_bill_required,
                    'Photo Required' => $policy->flight_photo_required,
                ],
            ];
        } elseif ($policy->policy_category === 'accommodation') {
            $details['reimbursement_values'] = [
                '1-2 Star (Budget)' => [
                    'Range' => "₹{$policy->basic_min} - ₹{$policy->basic_max}",
                    'Includes Food' => $policy->basic_includes_food ? 'Yes' : 'No',
                    'Bill Required' => $policy->basic_bill_required,
                    'Photo Required' => $policy->basic_photo_required,
                ],
                '3-4 Star (Business)' => [
                    'Range' => "₹{$policy->deluxe_min} - ₹{$policy->deluxe_max}",
                    'Includes Food' => $policy->deluxe_includes_food ? 'Yes' : 'No',
                    'Bill Required' => $policy->deluxe_bill_required,
                    'Photo Required' => $policy->deluxe_photo_required,
                ],
                '5 Star (Premium)' => [
                    'Range' => "₹{$policy->premium_min} - ₹{$policy->premium_max}",
                    'Includes Food' => $policy->premium_includes_food ? 'Yes' : 'No',
                    'Bill Required' => $policy->premium_bill_required,
                    'Photo Required' => $policy->premium_photo_required,
                ],
            ];
        } elseif ($policy->policy_category === 'food') {
            $details['reimbursement_values'] = [
                'Individual Meals' => [
                    'Breakfast' => "₹{$policy->breakfast_min} - ₹{$policy->breakfast_max}",
                    'Lunch' => "₹{$policy->lunch_min} - ₹{$policy->lunch_max}",
                    'Dinner' => "₹{$policy->dinner_min} - ₹{$policy->dinner_max}",
                    'Bill Required' => $policy->individual_bill_required,
                    'Photo Required' => $policy->individual_photo_required,
                ],
                'Two Meals Combined' => [
                    'Range' => "₹{$policy->two_meals_min} - ₹{$policy->two_meals_max}",
                    'Bill Required' => $policy->two_meals_bill_required,
                    'Photo Required' => $policy->two_meals_photo_required,
                ],
                'Three Meals (Full Day)' => [
                    'Range' => "₹{$policy->three_meals_min} - ₹{$policy->three_meals_max}",
                    'Bill Required' => $policy->three_meals_bill_required,
                    'Photo Required' => $policy->three_meals_photo_required,
                ],
            ];
        } elseif (in_array($policy->policy_category, ['mobile', 'internet', 'entertainment', 'miscellaneous'])) {
            $details['reimbursement_values'] = [
                'General Limits' => [
                    'Range' => "₹{$policy->min_amount} - ₹{$policy->max_amount}",
                    'Bill Required' => $policy->bill_required,
                    'Photo Required' => $policy->photo_required,
                    'Remarks' => $policy->remarks ?? 'N/A',
                ]
            ];
        } elseif ($policy->policy_category === 'custom') {
            $details['reimbursement_values'] = $policy->policy_data ?? [];
        }

        return $details;
    }

    /**
     * Update travel policy
     */
    private function updateTravelPolicy(Request $request, $policy)
    {
        $updateData = [
            'policy_name' => $request->policy_name ?? $policy->policy_name,
            'description' => $request->description ?? $policy->description,
            'two_wheeler_min' => $request->two_wheeler_min ?? $policy->two_wheeler_min,
            'two_wheeler_max' => $request->two_wheeler_max ?? $policy->two_wheeler_max,
            'two_wheeler_rate_km' => $request->two_wheeler_rate_km ?? $policy->two_wheeler_rate_km,
            'two_wheeler_bill_required' => $request->two_wheeler_bill_required ?? $policy->two_wheeler_bill_required,
            'two_wheeler_photo_required' => $request->two_wheeler_photo_required ?? $policy->two_wheeler_photo_required,
            'car_min' => $request->car_min ?? $policy->car_min,
            'car_max' => $request->car_max ?? $policy->car_max,
            'car_rate_km' => $request->car_rate_km ?? $policy->car_rate_km,
            'car_bill_required' => $request->car_bill_required ?? $policy->car_bill_required,
            'car_photo_required' => $request->car_photo_required ?? $policy->car_photo_required,
            'auto_min' => $request->auto_min ?? $policy->auto_min,
            'auto_max' => $request->auto_max ?? $policy->auto_max,
            'auto_rate_km' => $request->auto_rate_km ?? $policy->auto_rate_km,
            'auto_bill_required' => $request->auto_bill_required ?? $policy->auto_bill_required,
            'auto_photo_required' => $request->auto_photo_required ?? $policy->auto_photo_required,
            'bus_min' => $request->bus_min ?? $policy->bus_min,
            'bus_max' => $request->bus_max ?? $policy->bus_max,
            'bus_bill_required' => $request->bus_bill_required ?? $policy->bus_bill_required,
            'bus_photo_required' => $request->bus_photo_required ?? $policy->bus_photo_required,
            'train_min' => $request->train_min ?? $policy->train_min,
            'train_max' => $request->train_max ?? $policy->train_max,
            'train_bill_required' => $request->train_bill_required ?? $policy->train_bill_required,
            'train_photo_required' => $request->train_photo_required ?? $policy->train_photo_required,
            'flight_min' => $request->flight_min ?? $policy->flight_min,
            'flight_max' => $request->flight_max ?? $policy->flight_max,
            'flight_bill_required' => $request->flight_bill_required ?? $policy->flight_bill_required,
            'flight_photo_required' => $request->flight_photo_required ?? $policy->flight_photo_required,
            'frequency_type' => $request->frequency_type ?? $policy->frequency_type,
            'frequency_value' => $request->frequency_value ?? $policy->frequency_value,
            'submission_within' => $request->submission_within ?? $policy->submission_within,
            'submission_type' => $request->submission_type ?? $policy->submission_type,
            'financial_year' => $request->financial_year ?? $policy->financial_year,
            'calculation_type' => $request->calculation_type ?? $policy->calculation_type,
            'effective_from' => $request->effective_from ?? $policy->effective_from,
            'effective_to' => $request->effective_to ?? $policy->effective_to,
            'allow_actual_amount' => $request->allow_actual_amount ?? $policy->allow_actual_amount,
            'allow_multi_bills' => $request->allow_multi_bills ?? $policy->allow_multi_bills,
            'max_bills' => $request->max_bills ?? $policy->max_bills,
            'allow_same_bill' => $request->allow_same_bill ?? $policy->allow_same_bill,
            'remarks_mandatory' => $request->has('remarks_mandatory'),
            'bill_mandatory' => $request->has('bill_mandatory'),
            'vendor_mandatory' => $request->has('vendor_mandatory'),
            'settlement_timeline' => $request->settlement_timeline ?? $policy->settlement_timeline,
            'settlement_mode' => $request->settlement_mode ?? $policy->settlement_mode,
            'auto_settlement' => $request->auto_settlement ?? $policy->auto_settlement,
            'allow_partial_settlement' => $request->allow_partial_settlement ?? $policy->allow_partial_settlement,
            'status' => $request->status ?? $policy->status,
        ];

        if ($request->has('bus_categories')) {
            $updateData['bus_categories'] = is_array($request->bus_categories) ? json_encode($request->bus_categories) : $request->bus_categories;
        }
        if ($request->has('train_categories')) {
            $updateData['train_categories'] = is_array($request->train_categories) ? json_encode($request->train_categories) : $request->train_categories;
        }
        if ($request->has('flight_categories')) {
            $updateData['flight_categories'] = is_array($request->flight_categories) ? json_encode($request->flight_categories) : $request->flight_categories;
        }

        $policy->update($updateData);

        // Refresh status based on new effective dates
        $this->refreshPolicyStatus($policy);

        return $policy;
    }

    /**
     * Update accommodation policy
     */
    private function updateAccommodationPolicy(Request $request, $policy)
    {
        $policy->update([
            'policy_name' => $request->policy_name ?? $policy->policy_name,
            'description' => $request->description ?? $policy->description,
            'basic_min' => $request->basic_min ?? $policy->basic_min,
            'basic_max' => $request->basic_max ?? $policy->basic_max,
            'basic_includes_food' => $request->has('basic_includes_food'),
            'basic_bill_required' => $request->basic_bill_required ?? $policy->basic_bill_required,
            'basic_photo_required' => $request->basic_photo_required ?? $policy->basic_photo_required,
            'deluxe_min' => $request->deluxe_min ?? $policy->deluxe_min,
            'deluxe_max' => $request->deluxe_max ?? $policy->deluxe_max,
            'deluxe_includes_food' => $request->has('deluxe_includes_food'),
            'deluxe_bill_required' => $request->deluxe_bill_required ?? $policy->deluxe_bill_required,
            'deluxe_photo_required' => $request->deluxe_photo_required ?? $policy->deluxe_photo_required,
            'premium_min' => $request->premium_min ?? $policy->premium_min,
            'premium_max' => $request->premium_max ?? $policy->premium_max,
            'premium_includes_food' => $request->has('premium_includes_food'),
            'premium_bill_required' => $request->premium_bill_required ?? $policy->premium_bill_required,
            'premium_photo_required' => $request->premium_photo_required ?? $policy->premium_photo_required,
            'frequency_type' => $request->frequency_type ?? $policy->frequency_type,
            'frequency_value' => $request->frequency_value ?? $policy->frequency_value,
            'submission_within' => $request->submission_within ?? $policy->submission_within,
            'submission_type' => $request->submission_type ?? $policy->submission_type,
            'financial_year' => $request->financial_year ?? $policy->financial_year,
            'calculation_type' => $request->calculation_type ?? $policy->calculation_type,
            'effective_from' => $request->effective_from ?? $policy->effective_from,
            'effective_to' => $request->effective_to ?? $policy->effective_to,
            'allow_actual_amount' => $request->allow_actual_amount ?? $policy->allow_actual_amount,
            'allow_multi_bills' => $request->allow_multi_bills ?? $policy->allow_multi_bills,
            'max_bills' => $request->max_bills ?? $policy->max_bills,
            'allow_same_bill' => $request->allow_same_bill ?? $policy->allow_same_bill,
            'remarks_mandatory' => $request->has('remarks_mandatory'),
            'bill_mandatory' => $request->has('bill_mandatory'),
            'vendor_mandatory' => $request->has('vendor_mandatory'),
            'settlement_timeline' => $request->settlement_timeline ?? $policy->settlement_timeline,
            'settlement_mode' => $request->settlement_mode ?? $policy->settlement_mode,
            'auto_settlement' => $request->auto_settlement ?? $policy->auto_settlement,
            'allow_partial_settlement' => $request->allow_partial_settlement ?? $policy->allow_partial_settlement,
            'status' => $request->status ?? $policy->status,
        ]);

        // Refresh status based on new effective dates
        $this->refreshPolicyStatus($policy);

        return $policy;
    }

    /**
     * Update food policy
     */
    private function updateFoodPolicy(Request $request, $policy)
    {
        $policy->update([
            'policy_name' => $request->policy_name ?? $policy->policy_name,
            'description' => $request->description ?? $policy->description,
            'breakfast_min' => $request->breakfast_min ?? $policy->breakfast_min,
            'breakfast_max' => $request->breakfast_max ?? $policy->breakfast_max,
            'lunch_min' => $request->lunch_min ?? $policy->lunch_min,
            'lunch_max' => $request->lunch_max ?? $policy->lunch_max,
            'dinner_min' => $request->dinner_min ?? $policy->dinner_min,
            'dinner_max' => $request->dinner_max ?? $policy->dinner_max,
            'individual_bill_required' => $request->individual_bill_required ?? $policy->individual_bill_required,
            'individual_photo_required' => $request->individual_photo_required ?? $policy->individual_photo_required,
            'two_meals_min' => $request->two_meals_min ?? $policy->two_meals_min,
            'two_meals_max' => $request->two_meals_max ?? $policy->two_meals_max,
            'two_meals_bill_required' => $request->two_meals_bill_required ?? $policy->two_meals_bill_required,
            'two_meals_photo_required' => $request->two_meals_photo_required ?? $policy->two_meals_photo_required,
            'three_meals_min' => $request->three_meals_min ?? $policy->three_meals_min,
            'three_meals_max' => $request->three_meals_max ?? $policy->three_meals_max,
            'three_meals_bill_required' => $request->three_meals_bill_required ?? $policy->three_meals_bill_required,
            'three_meals_photo_required' => $request->three_meals_photo_required ?? $policy->three_meals_photo_required,
            'frequency_type' => $request->frequency_type ?? $policy->frequency_type,
            'frequency_value' => $request->frequency_value ?? $policy->frequency_value,
            'submission_within' => $request->submission_within ?? $policy->submission_within,
            'submission_type' => $request->submission_type ?? $policy->submission_type,
            'financial_year' => $request->financial_year ?? $policy->financial_year,
            'calculation_type' => $request->calculation_type ?? $policy->calculation_type,
            'effective_from' => $request->effective_from ?? $policy->effective_from,
            'effective_to' => $request->effective_to ?? $policy->effective_to,
            'allow_actual_amount' => $request->allow_actual_amount ?? $policy->allow_actual_amount,
            'allow_multi_bills' => $request->allow_multi_bills ?? $policy->allow_multi_bills,
            'max_bills' => $request->max_bills ?? $policy->max_bills,
            'allow_same_bill' => $request->allow_same_bill ?? $policy->allow_same_bill,
            'remarks_mandatory' => $request->has('remarks_mandatory'),
            'bill_mandatory' => $request->has('bill_mandatory'),
            'vendor_mandatory' => $request->has('vendor_mandatory'),
            'settlement_timeline' => $request->settlement_timeline ?? $policy->settlement_timeline,
            'settlement_mode' => $request->settlement_mode ?? $policy->settlement_mode,
            'auto_settlement' => $request->auto_settlement ?? $policy->auto_settlement,
            'allow_partial_settlement' => $request->allow_partial_settlement ?? $policy->allow_partial_settlement,
            'status' => $request->status ?? $policy->status,
        ]);

        // Refresh status based on new effective dates
        $this->refreshPolicyStatus($policy);

        return $policy;
    }

    /**
     * Update other/custom policy
     */
    private function updateOtherPolicy(Request $request, $policy)
    {
        $updateData = [
            'policy_name' => $request->policy_name ?? $policy->policy_name,
            'description' => $request->description ?? $policy->description,
            'min_amount' => $request->min_amount ?? $policy->min_amount,
            'max_amount' => $request->max_amount ?? $policy->max_amount,
            'remarks' => $request->remarks ?? $policy->remarks,
            'bill_required' => $request->bill_required ?? $policy->bill_required,
            'photo_required' => $request->photo_required ?? $policy->photo_required,
            'frequency_type' => $request->frequency_type ?? $policy->frequency_type,
            'frequency_value' => $request->frequency_value ?? $policy->frequency_value,
            'submission_within' => $request->submission_within ?? $policy->submission_within,
            'submission_type' => $request->submission_type ?? $policy->submission_type,
            'financial_year' => $request->financial_year ?? $policy->financial_year,
            'calculation_type' => $request->calculation_type ?? $policy->calculation_type,
            'effective_from' => $request->effective_from ?? $policy->effective_from,
            'effective_to' => $request->effective_to ?? $policy->effective_to,
            'allow_actual_amount' => $request->allow_actual_amount ?? $policy->allow_actual_amount,
            'allow_multi_bills' => $request->allow_multi_bills ?? $policy->allow_multi_bills,
            'max_bills' => $request->max_bills ?? $policy->max_bills,
            'allow_same_bill' => $request->allow_same_bill ?? $policy->allow_same_bill,
            'remarks_mandatory' => $request->has('remarks_mandatory'),
            'bill_mandatory' => $request->has('bill_mandatory'),
            'vendor_mandatory' => $request->has('vendor_mandatory'),
            'settlement_timeline' => $request->settlement_timeline ?? $policy->settlement_timeline,
            'settlement_mode' => $request->settlement_mode ?? $policy->settlement_mode,
            'auto_settlement' => $request->auto_settlement ?? $policy->auto_settlement,
            'allow_partial_settlement' => $request->allow_partial_settlement ?? $policy->allow_partial_settlement,
            'status' => $request->status ?? $policy->status,
        ];

        // Handle custom policy rules
        if ($policy->policy_category === 'custom' || $request->policy_category === 'custom') {
            $policyData = [];
            if ($request->has('custom_rules') && is_array($request->custom_rules)) {
                foreach ($request->custom_rules as $rule) {
                    if (!empty($rule['name'])) {
                        $policyData[] = [
                            'name' => $rule['name'],
                            'type' => $rule['type'] ?? 'actual',
                            'fields' => $rule['fields'] ?? [],
                            'bill_required' => $rule['bill_required'] ?? 'Yes',
                            'photo_required' => $rule['photo_required'] ?? 'No',
                        ];
                    }
                }
            } elseif ($request->has('policy_data') && !empty($request->policy_data)) {
                $decoded = is_array($request->policy_data) ? $request->policy_data : json_decode($request->policy_data, true);
                if (is_array($decoded)) {
                    $policyData = array_values(array_filter($decoded, function ($rule) {
                        return !empty($rule['name']);
                    }));
                }
            }

            if (!empty($policyData)) {
                $updateData['policy_data'] = $policyData;
            }
        }

        $policy->update($updateData);

        // Refresh status based on new effective dates
        $this->refreshPolicyStatus($policy);

        return $policy;
    }

    /**
     * Toggle policy status (active/inactive)
     */
    public function toggleStatus($id)
    {
        try {
            $instituteId = Auth::user()->institute_id;
            $branchId = Auth::user()->branch_id;

            $policy = $this->findPolicyById($id, $instituteId, $branchId);

            if (!$policy) {
                return response()->json([
                    'success' => false,
                    'message' => 'Policy not found'
                ], 404);
            }

            $newStatus = $policy->status === 'active' ? 'inactive' : 'active';
            $policy->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => "Policy status changed to {$newStatus}",
                'status' => $newStatus,
                'policy_id' => $policy->reimbursement_policy_id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }
}