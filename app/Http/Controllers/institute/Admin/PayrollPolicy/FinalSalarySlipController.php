<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinalSalarySlip;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use Carbon\Carbon;

class FinalSalarySlipController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);
        $departmentId = $request->get('department_id');
        $paymentStatus = $request->get('payment_status');
        
        $query = FinalSalarySlip::where('year', $year)->where('month', $month);
        
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
        
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::create()->month($i)->format('F');
        }
        
        $years = range(Carbon::now()->subYears(2)->year, Carbon::now()->year);
        
        $summary = [
            'total_slips' => $salarySlips->count(),
            'total_amount' => $salarySlips->sum('final_payable'),
            'paid_count' => $salarySlips->where('payment_status', 'paid')->count(),
            'pending_count' => $salarySlips->where('payment_status', 'pending')->count(),
            'paid_amount' => $salarySlips->where('payment_status', 'paid')->sum('final_payable'),
        ];
        
        return view('instituteAdmin.Payroll.FinalSalarySlipList', compact(
            'salarySlips', 'departments', 'months', 'years',
            'year', 'month', 'departmentId', 'paymentStatus', 'summary'
        ));
    }
    
    public function show($slipId)
    {
        $salarySlip = FinalSalarySlip::where('slip_id', $slipId)
            ->with(['employee', 'employee.department', 'institute'])
            ->first();
       
        if (!$salarySlip) {
            return redirect()->back()->with('error', 'Salary slip not found');
        }
        
        return view('instituteAdmin.Payroll.FinalSalarySlipView', compact('salarySlip'));
    }
    
    public function markAsPaid(Request $request, $slipId)
    {
        $request->validate([
            'payment_reference' => 'nullable|string',
            'payment_mode' => 'required|in:bank,cheque,cash',
            'bank_transaction_id' => 'nullable|string'
        ]);
        
        $salarySlip = FinalSalarySlip::where('slip_id', $slipId)->first();
        
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
            'bank_transaction_id' => $request->bank_transaction_id,
            'notes' => 'Payment processed by ' . auth()->user()->name
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Salary marked as paid successfully'
        ]);
    }
}