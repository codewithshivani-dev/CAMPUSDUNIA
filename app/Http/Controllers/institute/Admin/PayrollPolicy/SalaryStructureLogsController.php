<?php

namespace App\Http\Controllers\institute\Admin\PayrollPolicy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalaryStructureLog;
use App\Traits\InstituteBranchAccess;
use App\Models\Departments;
use App\Models\EmployeeDetails;
use App\Models\EmployeeSalaryStructure;

class SalaryStructureLogsController extends Controller
{
    use InstituteBranchAccess;

public function index(Request $request)
{
    $context = $this->getInstituteBranchContext();
    
    if (!$context['institute_id']) {
        return redirect()->back()->with('error', 'You are not associated with any institute.');
    }

    $query = SalaryStructureLog::where('institute_id', $context['institute_id'])
        ->with([
            'department', 
            'employee', 
            'currentStructure.allowances', 
            'currentStructure.deductions', 
            'currentStructure.preview'
        ])
        ->orderBy('updated_at', 'desc');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('salary_structure_id', 'like', "%{$search}%")
              ->orWhereHas('employee', function($eq) use ($search) {
                  $eq->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('department', function($dq) use ($search) {
                  $dq->where('department', 'like', "%{$search}%");
              });
        });
    }

    if ($request->filled('action')) {
        $query->where('action', $request->action);
    }

    $logs = $query->paginate(20);

    return view('instituteAdmin.Payroll.SalaryStructureLogs', compact('logs'));
}
}
