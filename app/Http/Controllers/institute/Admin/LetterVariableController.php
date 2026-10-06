<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeave;
use App\Models\HolidayEvent;
use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\EmployeeSalaryStructure;
use App\Models\EmployeeExit;
use App\Models\SalaryStructureLog;
use App\Models\SalaryStructureAllowances;
use App\Models\SalaryStructureOvertime;
use App\Models\SalaryStructureBonus;
use App\Models\SalaryStructureDeduction;
use App\Models\SalaryPreview;
use App\Models\Designations;
use App\Models\EmployeeProbationLog;
use App\Models\InstituteBasicDetails;
use App\Models\Syllabus;
use Illuminate\Support\Facades\Storage;

class LetterVariableController extends Controller
{
    /**
     * Get all read-only employee variables needed to generate an employee letter.
     */
    public function getEmployeeLetterVariables(string $employeeId)
    {
        $employee = EmployeeDetails::with(['institute', 'designationRelation', 'department'])
            ->where(function ($query) use ($employeeId) {
                $query->where('id', $employeeId)
                    ->orWhere('employee_id', $employeeId);
            })
            ->firstOrFail();

        $employeeReferenceId = $employee->employee_id ?? (string) $employee->id;
        $exit = EmployeeExit::where('employee_id', $employeeReferenceId)->latest('actual_exit_date')->latest('id')->first();
        $salary = EmployeeSalaryStructure::where('employee_id', $employeeReferenceId)
            ->where(fn ($query) => $query->where('is_active', true)->orWhere('status', 'active'))
            ->latest('updated_at')->latest('id')->first()
            ?? EmployeeSalaryStructure::where('employee_id', $employeeReferenceId)->latest('updated_at')->latest('id')->first();

        $salaryLog = SalaryStructureLog::where('employee_id', $employeeReferenceId)
            ->whereNotNull('previous_data')->latest('id')->first();
        $previousSalary = data_get($salaryLog?->previous_data, 'monthly_fixed')
            ?? data_get($salaryLog?->previous_data, 'monthly_salary');
        if ($previousSalary === null && data_get($salaryLog?->previous_data, 'total_ctc_annual') !== null) {
            $previousSalary = (float) data_get($salaryLog->previous_data, 'total_ctc_annual') / 12;
        }

        $currentSalary = (float) ($salary?->monthly_fixed ?? $salary?->monthly_salary ?? (($salary?->total_ctc_annual ?? 0) / 12));
        $previousSalary = $previousSalary !== null ? (float) $previousSalary : null;
        $incrementAmount = $previousSalary !== null ? max(0, $currentSalary - $previousSalary) : null;
        $incrementPercentage = $previousSalary > 0 && $incrementAmount !== null ? round(($incrementAmount / $previousSalary) * 100, 2) : null;

        $joiningDate = $employee->doj ? Carbon::parse($employee->doj) : null;
        $actualExitDate = $exit?->actual_exit_date ?? $exit?->notice_end_date;
        $endDate = $actualExitDate ? Carbon::parse($actualExitDate) : now();
        $experience = $joiningDate ? $joiningDate->diff($endDate) : null;
        $company = $employee->institute;
        $jobTitle = $employee->designation ?? $employee->designationRelation?->designations;

        return response()->json([
            'employee_id' => $employeeReferenceId,
            'employee_code' => $employee->employee_code,
            'employee_name' => $employee->name,
            'name' => $employee->name,
            'employee_email' => $employee->email,
            'employee_phone' => $employee->phone ?? $employee->mobile ?? $employee->contact_number,
            'job_title' => $jobTitle,
            'designation' => $jobTitle,
            'department' => $employee->department?->department,
            'employment_type' => $employee->employment_type,
            'date_of_joining' => $joiningDate?->format('d-m-Y'),
            'joining_date' => $joiningDate?->format('d-m-Y'),
            'doj' => $joiningDate?->toDateString(),
            'exit_date' => $actualExitDate ? Carbon::parse($actualExitDate)->format('d-m-Y') : null,
            'experience' => $experience ? sprintf('%d year(s), %d month(s), %d day(s)', $experience->y, $experience->m, $experience->d) : null,
            'experience_years' => $experience ? round($joiningDate->floatDiffInYears($endDate), 2) : null,
            'experience_as_of_date' => $endDate->format('d-m-Y'),
            'company_name' => $company?->name,
            'company' => $company?->name,
            'company_address' => $company ? trim(implode(', ', array_filter([$company->address_line_1, $company->address_line_2, $company->city, $company->state, $company->pincode]))) : null,
            'current_salary' => $currentSalary,
            'current_salary_formatted' => number_format($currentSalary, 2),
            'salary' => number_format($currentSalary, 2),
            'annual_ctc' => (float) ($salary?->total_ctc_annual ?? 0),
            'previous_salary' => $previousSalary,
            'increment' => $incrementAmount,
            'increment_formatted' => $incrementAmount !== null ? number_format($incrementAmount, 2) : null,
            'increment_percentage' => $incrementPercentage,
            'letter_date' => now()->format('d-m-Y'),
            'exit_status' => $exit?->exit_status,
        ]);
    }
    
}

