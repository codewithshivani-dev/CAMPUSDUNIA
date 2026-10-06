<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalarySlip;
use App\Models\SalaryReview;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use Carbon\Carbon;

class SalarySlipController extends Controller
{
    /**
     * List all salary slips
     */
    public function index(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        $departmentId = $request->get('department_id');
        $paymentStatus = $request->get('payment_status');
        
        $salaryMonth = Carbon::create($year, $month, 1)->format('Y-m');
        
        $query = SalarySlip::where('salary_month', $salaryMonth);
        
        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }
        
        if ($paymentStatus) {
            $query->where('payment_status', $paymentStatus);
        }
        
        $salarySlips = $query->get();
        
        $departments = Departments::all();
        
        return view('instituteAdmin.Payroll.SalarySlipList', compact(
            'salarySlips', 'departments', 'year', 'month', 'paymentStatus'
        ));
    }
    
    /**
     * View single salary slip
     */
    public function show($id)
    {
        $salarySlip = SalarySlip::where('salaryslip_id', $id)->first();
        
        if (!$salarySlip) {
            return redirect()->back()->with('error', 'Salary slip not found');
        }
        
        $details = json_decode($salarySlip->salary_details_json, true);
        
        return view('instituteAdmin.Payroll.SalarySlipView', compact('salarySlip', 'details'));
    }
    
    /**
     * Mark salary as paid
     */
    public function markAsPaid(Request $request, $id)
    {
        $request->validate([
            'payment_reference' => 'nullable|string',
            'payment_mode' => 'required|in:bank,cheque,cash'
        ]);
        
        $salarySlip = SalarySlip::where('salaryslip_id', $id)->first();
        
        if (!$salarySlip) {
            return response()->json(['success' => false, 'message' => 'Salary slip not found']);
        }
        
        if ($salarySlip->payment_status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Salary already marked as paid']);
        }
        
        $salarySlip->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
            'payment_reference' => $request->payment_reference,
            'payment_mode' => $request->payment_mode,
            'remarks' => 'Payment processed by ' . auth()->user()->name
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Salary marked as paid successfully',
            'data' => $salarySlip
        ]);
    }
    
    /**
     * Get payment status summary
     */
    public function getPaymentSummary(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        
        $salaryMonth = Carbon::create($year, $month, 1)->format('Y-m');
        
        $stats = [
            'total_slips' => SalarySlip::where('salary_month', $salaryMonth)->count(),
            'paid' => SalarySlip::where('salary_month', $salaryMonth)->where('payment_status', 'paid')->count(),
            'pending' => SalarySlip::where('salary_month', $salaryMonth)->where('payment_status', 'pending')->count(),
            'processing' => SalarySlip::where('salary_month', $salaryMonth)->where('payment_status', 'processing')->count(),
            'failed' => SalarySlip::where('salary_month', $salaryMonth)->where('payment_status', 'failed')->count(),
            'total_amount' => SalarySlip::where('salary_month', $salaryMonth)->sum('payable_salary'),
            'paid_amount' => SalarySlip::where('salary_month', $salaryMonth)->where('payment_status', 'paid')->sum('payable_salary'),
        ];
        
        return response()->json($stats);
    }
}