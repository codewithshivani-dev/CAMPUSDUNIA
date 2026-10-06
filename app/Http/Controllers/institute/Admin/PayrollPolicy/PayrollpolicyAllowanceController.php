<?php
namespace App\Http\Controllers\institute\Admin\PayrollPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollPolicyAllowance;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
class PayrollpolicyAllowanceController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    public function storeAllowance(Request $request)
    {
        \Log::info('Allowance Request:', $request->all());
        // :white_check_mark: Get institute & branch context
        $context = $this->getInstituteBranchContext();
        // :white_check_mark: Check institute access
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        $validated = $request->validate([
            'payroll_policy_id' => 'required',
            // DEFAULT ALLOWANCES
            'hra_selected' => 'required|boolean',
            'hra_type' => 'nullable|required_if:hra_selected,1|in:percentage,fixed',
            'conveyance_selected' => 'required|boolean',
            'conveyance_type' => 'nullable|required_if:conveyance_selected,1|in:percentage,fixed',
            'medical_selected' => 'required|boolean',
            'medical_type' => 'nullable|required_if:medical_selected,1|in:percentage,fixed',
            'special_selected' => 'required|boolean',
            'special_type' => 'nullable|required_if:special_selected,1|in:percentage,fixed',
            'lta_selected' => 'required|boolean',
            'lta_type' => 'nullable|required_if:lta_selected,1|in:percentage,fixed',
            'education_selected' => 'required|boolean',
            'education_type' => 'nullable|required_if:education_selected,1|in:percentage,fixed',
            // CUSTOM ALLOWANCES
            'custom_allowances' => 'nullable|array',
            'custom_allowances.*.name' => 'sometimes|required|string|max:100',
            'custom_allowances.*.selected' => 'sometimes|required|boolean',
            'custom_allowances.*.type' => 'sometimes|required|in:percentage,fixed',
            'custom_allowances.*.description' => 'nullable|string|max:255',
        ]);
        // :white_check_mark: Attach institute & branch
        $validated['institute_id'] = $context['institute_id'];
        $validated['branch_id'] = $context['is_branch_admin']
            ? $context['branch_id']
            : null;
        // :white_check_mark: Save or update allowance
        $policy = PayrollPolicyAllowance::updateOrCreate(
            [
                'payroll_policy_id' => $validated['payroll_policy_id'],
                'institute_id' => $context['institute_id'], // IMPORTANT
            ],
            $validated
        );
        return response()->json([
            'success' => true,
            'message' => 'Payroll allowances saved successfully.',
            'data' => $policy
        ], 201);
    }
    // :small_blue_diamond: GET by payroll_policy_id
    public function getAllowance($payroll_policy_id)
    {
        // :white_check_mark: Get institute context
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        $policy = PayrollPolicyAllowance::where('payroll_policy_id', $payroll_policy_id)
            ->where('institute_id', $context['institute_id']) // IMPORTANT
            ->first();
        if (!$policy) {
            return response()->json([
                'success' => false,
                'message' => 'Payroll allowance policy not found.'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $policy
        ], 200);
    }
    // :small_blue_diamond: GET all
    public function getAllAllowances()
    {
        // :white_check_mark: Get institute context
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        $policies = PayrollPolicyAllowance::where('institute_id', $context['institute_id'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $policies
        ], 200);
    }
    

}