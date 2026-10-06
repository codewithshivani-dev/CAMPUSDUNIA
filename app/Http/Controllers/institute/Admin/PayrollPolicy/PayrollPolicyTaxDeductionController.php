<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use App\Models\PayrollPolicyTaxDeduction;
use Illuminate\Http\Request;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;

class PayrollPolicyTaxDeductionController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    public function storeTaxDeductions(Request $request)
    {
        // :white_check_mark: Safe JSON decode (same as other deduction)
        $decoded = json_decode($request->getContent(), true);
        if (is_array($decoded)) {
            $request->merge($decoded);
        }
        \Log::info("TAX REQUEST", $request->all());
        // :white_check_mark: Get institute & branch context
        $context = $this->getInstituteBranchContext();
        // :white_check_mark: Check institute access
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        // :white_check_mark: Validation (allows nullable fields)
            $validated = $request->validate([
                'payroll_policy_id' => 'required|string|max:255',
                // Professional Tax
                'pt_selected' => 'required|boolean',
                'pt_type' => 'nullable|required_if:pt_selected,true|in:percentage,fixed,slabs',
                'pt_slabs' => 'nullable',
                // LST Tax
                'lst_selected' => 'required|boolean',
                'lst_type' => 'nullable|required_if:lst_selected,true|in:percentage,fixed,slabs',
                'lst_slabs' => 'nullable',
                // TDS
                'tds_selected' => 'required|boolean',
                'tds_slabs' => 'nullable',
            ]);
        // :white_check_mark: Attach institute & branch
        $validated['institute_id'] = $context['institute_id'];
        $validated['branch_id'] = $context['is_branch_admin']
            ? $context['branch_id']
            : null;
        // :white_check_mark: Secure update or create (same logic preserved)
        $record = PayrollPolicyTaxDeduction::updateOrCreate(
            [
                'payroll_policy_id' => $validated['payroll_policy_id'],
                'institute_id' => $context['institute_id']
            ],
            $validated
        );
        return response()->json([
            'success' => true,
            'message' => 'Saved successfully',
            'data' => $record
        ], 201);
    }

    // :small_blue_diamond: GET BY PAYROLL POLICY ID
    public function getTaxDeduction($payroll_policy_id)
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $record = PayrollPolicyTaxDeduction::where('payroll_policy_id', $payroll_policy_id)
            ->where('institute_id', $context['institute_id']) // IMPORTANT
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Tax deduction policy not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $record
        ], 200);
    }
    public function getAllTaxDeduction()
    {
        // ✅ Get institute context
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $records = PayrollPolicyTaxDeduction::where('institute_id', $context['institute_id'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $records
        ], 200);
    }


}