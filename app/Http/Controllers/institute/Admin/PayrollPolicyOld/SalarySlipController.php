<?php
namespace App\Http\Controllers\institute\Admin\PayrollPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalarySlip;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
class SalarySlipController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    public function storeSalarySlip(Request $request)
    {
        // :white_check_mark: Institute
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        $validated = $request->validate([
            'employee_id'   => 'required|string',
            'name'          => 'required|string',
            'salary_month'  => 'required|string',
            'basic_salary'  => 'required|numeric',
            'gross_salary'  => 'required|numeric',
            'net_salary'    => 'required|numeric',
        ]);
        $salarySlipId = 'SLIP-' . date('Ym') . '-' . rand(1000, 9999);
        $data = [
            'salaryslip_id'  => $salarySlipId,
            'employee_id'    => $validated['employee_id'],
            'name'           => $validated['name'],
            'salary_month'   => $validated['salary_month'],
            'basic_salary'   => $validated['basic_salary'],
            'gross_salary'   => $validated['gross_salary'],
            'net_salary'     => $validated['net_salary'],
            'status'         => 'generated',
            'generated_date' => now()->toDateString(),
            // :white_check_mark: ADD institute
            'institute_id' => $context['institute_id'],
            // JSON column
            'salary_details_json' => [
                'salaryslip_id' => $salarySlipId,
                'employee_id'   => $validated['employee_id'],
                'name'          => $validated['name'],
                'salary_month'  => $validated['salary_month'],
                'basic_salary'  => $validated['basic_salary'],
                'gross_salary'  => $validated['gross_salary'],
                'net_salary'    => $validated['net_salary'],
                'status'        => 'generated',
                'generated_date'=> now()->toDateString(),
            ],
        ];
        $salarySlip = SalarySlip::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Salary slip created successfully',
            'data'    => $salarySlip
        ], 201);
    }
    public function getAllSalarySlips()
    {
        // :white_check_mark: Institute context ONLY
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        $salarySlips = SalarySlip::where(
            'institute_id',
            $context['institute_id']
        )->orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'message' => 'All salary slips fetched successfully',
            'data'    => $salarySlips
        ], 200);
    }
    public function getSalarySlipByEmployeeId($employee_id)
    {
        // :white_check_mark: Institute context ONLY
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }
        $salarySlips = SalarySlip::where('employee_id', $employee_id)
            ->where('institute_id', $context['institute_id'])
            ->orderBy('salary_month', 'desc')
            ->get();
        if ($salarySlips->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No salary slips found for this employee',
                'data'    => []
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Salary slips fetched successfully',
            'data'    => $salarySlips
        ], 200);
    }
public function updateSalarySlip(Request $request, $id)
{
    // :white_check_mark: Get institute context
    $context = $this->getInstituteBranchContext();
    if (!$context['institute_id']) {
        return response()->json([
            'success' => false,
            'message' => 'You are not associated with any institute.'
        ], 403);
    }
    // :white_check_mark: Ensure the salary slip belongs to this institute
    $salarySlip = SalarySlip::where('id', $id)
        ->where('institute_id', $context['institute_id'])
        ->first();
    if (!$salarySlip) {
        return response()->json([
            'success' => false,
            'message' => 'Salary slip not found or does not belong to your institute.'
        ], 404);
    }
    $validated = $request->validate([
        'employee_id'   => 'sometimes|required|string',
        'name'          => 'sometimes|required|string',
        'salary_month'  => 'sometimes|required|string',
        'basic_salary'  => 'sometimes|required|numeric',
        'gross_salary'  => 'sometimes|required|numeric',
        'net_salary'    => 'sometimes|required|numeric',
        'status'        => 'sometimes|required|string',
    ]);
    // Normal update
    $salarySlip->update($validated);
    // JSON sync
    $salarySlip->salary_details_json = array_merge(
        $salarySlip->salary_details_json ?? [],
        $validated
    );
    $salarySlip->save();
    return response()->json([
        'success' => true,
        'message' => 'Salary slip updated successfully',
        'data'    => $salarySlip
    ], 200);
}
}