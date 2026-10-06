<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;

class HomeworkController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    /**
     * Show Homework Create Page
     */
    public function create()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        $user = Auth::user();

        $employee = User::join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->where('users.id', $user->id)
            ->select('employee_details.*')
            ->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $categories = DepartmentCategory::where('institute_id', $employee->institute_id)
            ->where('department_category_id', $employee->department_category_id)
            ->when($employee->branch_id, function ($query) use ($employee) {
                $query->where('branch_id', $employee->branch_id);
            })
            ->get();

        $departments = Departments::where('institute_id', $employee->institute_id)
        ->where('department_category_id', $employee->department_category_id)
        ->where('department_id', $employee->department_id)
        ->when($employee->branch_id, fn ($q) =>
            $q->where('branch_id', $employee->branch_id)
        )
        ->first();
    
        return view(
            'instituteAdmin.SchoolHomework.HomeWork',
            compact('categories', 'departments', 'employee')
        );
    }

    public function employee()
    {
        return $this->hasOne(EmployeeDetails::class, 'user_id');
    }

}
