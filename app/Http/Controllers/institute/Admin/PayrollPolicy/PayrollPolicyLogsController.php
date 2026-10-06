<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayrollPolicyLog;
use App\Traits\InstituteBranchAccess;
use App\Models\Departments;
use App\Models\EmployeeDetails;

class PayrollPolicyLogsController extends Controller
{
    use InstituteBranchAccess;

public function index(Request $request)
{
    $context = $this->getInstituteBranchContext();
    
    if (!$context['institute_id']) {
        return redirect()->back()->with('error', 'You are not associated with any institute.');
    }

    $query = PayrollPolicyLog::where('institute_id', $context['institute_id'])
        ->with(['department', 'employee']) // Make sure these relationships exist
        ->orderBy('updated_at', 'desc');

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('payroll_policy_id', 'like', "%{$search}%")
              ->orWhereHas('employee', function($eq) use ($search) {
                  $eq->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('department', function($dq) use ($search) {
                  $dq->where('department', 'like', "%{$search}%");
              });
        });
    }

    // Action filter
    if ($request->filled('action')) {
        $query->where('action', $request->action);
    }

    // Type filter
    if ($request->filled('type')) {
        $query->where('change_type', $request->type);
    }

    $logs = $query->paginate(20);

    return view('instituteAdmin.Payroll.PolicyLogs', compact('logs'));
}
}
