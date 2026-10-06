<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmployeeDetails;
use App\Models\Assignment;
use App\Models\Departments;
use App\Models\AssignmentFile;
use App\Models\AssignmentStudents;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\SubjectsCoursewise;
use App\Models\ProductDetails;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateHierarchyEmployee extends Controller
{
    public function getEmployeeHierarchyList()
    {
        $departments = Department::with(['staff.role'])->get();

        $result = $departments->map(function ($dept) {
            return [
                'department' => $dept->name,
                'dean' => $dept->staff->firstWhere('role.name', 'Dean'),
                'principal' => $dept->staff->firstWhere('role.name', 'Principal'),
                'vice_principal' => $dept->staff->firstWhere('role.name', 'Vice Principal'),
                'hod' => $dept->staff->firstWhere('role.name', 'HOD'),
                'class_incharge' => $dept->staff->firstWhere('role.name', 'Class In-Charge'),
                'staff' => $dept->staff->where('role.name', 'Staff'),
            ];
        });

        return view('departments.hierarchy', compact('result'));
    }
}