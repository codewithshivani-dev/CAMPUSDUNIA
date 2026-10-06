<?php
namespace App\Http\Controllers\institute\Admin\PayrollPolicy;
use App\Http\Controllers\Controller;
use App\Models\PayrollPolicyOtherDeduction;
use Illuminate\Http\Request;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
class PayrollPolicyOtherDeductionController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    public function storeOtherDeductions(Request $request)
    {
        // Safe JSON decode
        $decoded = json_decode($request->getContent(), true);

        if (is_array($decoded)) {
            $request->merge($decoded);
        }

        \Log::info('Other Deduction Request:', $request->all());
        // ✅ Get institute & branch context
        $context = $this->getInstituteBranchContext();
        // ✅ Check institute access
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $validated = $request->validate([
            'payroll_policy_id' => 'required|string|max:255',
            // Default Deductions
            'insurance_selected' => 'required|boolean',
            'insurance_type' => 'nullable|required_if:insurance_selected,1|in:fixed,percentage',
            'loan_selected' => 'required|boolean',
            'loan_type' => 'nullable|required_if:loan_selected,1|in:fixed,percentage',
            'advance_selected' => 'required|boolean',
            'advance_type' => 'nullable|required_if:advance_selected,1|in:fixed,percentage',
            // Custom deductions array
            'custom_deductions' => 'nullable|array',
            'custom_deductions.*.name' => 'sometimes|required|string|max:100',
            'custom_deductions.*.selected' => 'sometimes|required|boolean',
            'custom_deductions.*.type' => 'sometimes|required|in:fixed,percentage',
            'custom_deductions.*.description' => 'nullable|string|max:255',
        ]);
        // ✅ Attach institute & branch
        $validated['institute_id'] = $context['institute_id'];
        $validated['branch_id'] = $context['is_branch_admin']
            ? $context['branch_id']
            : null;

        // ✅ Secure updateOrCreate
        $deduction = PayrollPolicyOtherDeduction::updateOrCreate(
            [
                'payroll_policy_id' => $validated['payroll_policy_id'],
                'institute_id' => $context['institute_id'] // IMPORTANT
            ],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Other deductions saved successfully.',
            'data' => $deduction
        ], 201);
    }

    // :small_blue_diamond: GET by payroll_policy_id
public function getOtherDeduction($payroll_policy_id)
{
    // ✅ Get institute context
    $context = $this->getInstituteBranchContext();

    if (!$context['institute_id']) {
        return response()->json([
            'success' => false,
            'message' => 'You are not associated with any institute.'
        ], 403);
    }

    $deduction = PayrollPolicyOtherDeduction::where('payroll_policy_id', $payroll_policy_id)
        ->where('institute_id', $context['institute_id']) // IMPORTANT
        ->first();

    if (!$deduction) {
        return response()->json([
            'success' => false,
            'message' => 'Other deductions policy not found.'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $deduction
    ], 200);
}

    // :small_blue_diamond: OPTIONAL: Get all other deduction policies
public function getAllOtherDeduction()
{
    // ✅ Get institute context
    $context = $this->getInstituteBranchContext();

    if (!$context['institute_id']) {
        return response()->json([
            'success' => false,
            'message' => 'You are not associated with any institute.'
        ], 403);
    }

    $deductions = PayrollPolicyOtherDeduction::where('institute_id', $context['institute_id'])
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $deductions
    ], 200);
}
}