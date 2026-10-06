<?php

namespace App\Http\Controllers\institute\Admin\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\StudentParentDetails;
use App\Models\EmployeeDetails;
use App\Models\FincapMerchant;
use App\Models\ApplicantDetail;
use App\Models\Departments;
use App\Models\ProductDetails;
use App\Models\EmployeeLeave;
use App\Models\StudentLeave;
use App\Models\StudentCourseFeeStructure;
use App\Models\StudentCustomFeestructure;
use App\Models\StudentHostelFeeStructure;
use App\Models\StudentMiscellaneousFeeStructure;
use App\Models\StudentRegistrationFeeStructure;
use App\Models\StudentTransportFeeStructure;
use App\Models\CourseFeeStructure;
use App\Models\HolidayEvent;
use App\Models\AuthorizedUser;
use App\Models\EmployeeAttendance;
use App\Models\Notice;
use App\Models\StudentAttendence;
use App\Models\Designations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AdminDashboardController extends Controller
{
    public function adminDashboardIndex()
    {
        $institute_id = auth()->user()->institute_id;

        // Get User Details
        $data['updated_at'] = now()->setTimezone('Asia/Kolkata');
        $data['user_details'] = AuthorizedUser::where('institute_id', $institute_id)->first();
        
        // Get Departments
        $data['total_departments'] = Departments::where('institute_id', $institute_id)->count();
        $data['active_departments'] = Departments::where('institute_id', $institute_id)
            ->where('status', 'active')->count();
        $data['inactive_departments'] = Departments::where('institute_id', $institute_id)
            ->where('status', 'inactive')->count();
        
        // Get Employees with type and gender breakdown
        $data['total_employees'] = EmployeeDetails::where('institute_id', $institute_id)->count();
        $data['active_employees'] = EmployeeDetails::where('institute_id', $institute_id)
            ->where('status', 'active')->count();
        $data['inactive_employees'] = EmployeeDetails::where('institute_id', $institute_id)
            ->where('status', 'inactive')->count();
        
        // Employee type classification
        $data['teaching_staff'] = EmployeeDetails::where('institute_id', $institute_id)
            ->where('assigned_role', 'teacher')
            ->orWhere('assigned_role', 'faculty')
            ->orWhere('assigned_role', 'professor')
            ->orWhere('assigned_role', 'lecturer')
            ->count();
            
        $data['non_teaching_staff'] = EmployeeDetails::where('institute_id', $institute_id)
            ->whereNotIn('assigned_role', ['teacher', 'faculty', 'professor', 'lecturer'])
            ->count();
        
        // Get Employee Gender Statistics
        $data['male_employees'] = EmployeeDetails::where('institute_id', $institute_id)
            ->where('gender', 'Male')->count();
        $data['female_employees'] = EmployeeDetails::where('institute_id', $institute_id)
            ->where('gender', 'Female')->count();
        $data['other_employees'] = EmployeeDetails::where('institute_id', $institute_id)
            ->whereNotIn('gender', ['Male', 'Female'])->count();
        
        // Calculate employee gender percentages
        $data['male_employee_percentage'] = $data['total_employees'] > 0 
            ? round(($data['male_employees'] / $data['total_employees']) * 100, 1) 
            : 0;
        $data['female_employee_percentage'] = $data['total_employees'] > 0 
            ? round(($data['female_employees'] / $data['total_employees']) * 100, 1) 
            : 0;
        $data['other_employee_percentage'] = $data['total_employees'] > 0 
            ? round(($data['other_employees'] / $data['total_employees']) * 100, 1) 
            : 0;
        
        // Get dynamic employee roles from designations
        $employee_roles = $this->getEmployeeRoles($institute_id);
        $data['employee_roles'] = $employee_roles['roles'];
        $data['employee_role_labels'] = $employee_roles['labels'];
        $data['employee_role_values'] = $employee_roles['values'];
        $data['role_ui_config'] = $this->getRoleUIConfig();
        
        // Calculate total from roles
        $data['total_employees'] = array_sum($employee_roles['values']);
        
        //Total Sections
        $unique = [];

        $records = CourseFeeStructure::where('institute_id', $institute_id)->get();
        
        foreach ($records as $record) {
            $sections = json_decode($record->sections, true);
        
            foreach ($sections as $section) {
                $key = $record->product_id . '_' . $section['id'];
                $unique[$key] = true;
            }
        }
        
        $data['total_sections'] = count($unique);
        
        // Get Students with gender breakdown
        $data['total_students'] = StudentParentDetails::where('institute_id', $institute_id)->count();
        $data['active_students'] = StudentParentDetails::where('institute_id', $institute_id)
            ->where('status', 'active')->count();
        $data['inactive_students'] = StudentParentDetails::where('institute_id', $institute_id)
            ->where('status', 'inactive')->count();
        
        // Get gender-wise student counts
        $data['male_students'] = StudentParentDetails::where('institute_id', $institute_id)
            ->where('gender', 'male')->count();
        $data['female_students'] = StudentParentDetails::where('institute_id', $institute_id)
            ->where('gender', 'female')->count();
        $data['other_gender_students'] = StudentParentDetails::where('institute_id', $institute_id)
            ->whereNotIn('gender', ['male', 'female'])->count();
        
        // Calculate percentages
        $data['male_percentage'] = $data['total_students'] > 0 
            ? round(($data['male_students'] / $data['total_students']) * 100, 1) 
            : 0;
        $data['female_percentage'] = $data['total_students'] > 0 
            ? round(($data['female_students'] / $data['total_students']) * 100, 1) 
            : 0;
        
        // Get Courses
        $data['total_courses'] = ProductDetails::where('institute_id', $institute_id)->count();
        $data['active_courses'] = ProductDetails::where('institute_id', $institute_id)
            ->where('status', 'active')->count();
        $data['inactive_courses'] = ProductDetails::where('institute_id', $institute_id)
            ->where('status', 'inactive')->count();
        
        // Get current month's growth
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;
        $currentMonthCourses = ProductDetails::where('institute_id', $institute_id)
            ->whereMonth('created_at', $currentMonth)->count();
        $lastMonthCourses = ProductDetails::where('institute_id', $institute_id)
            ->whereMonth('created_at', $lastMonth)->count();
        $data['course_growth'] = $lastMonthCourses > 0 
            ? round((($currentMonthCourses - $lastMonthCourses) / $lastMonthCourses) * 100, 1) 
            : ($currentMonthCourses > 0 ? 100 : 0);
        
        // Get Leave Requests
        $employeeLeaves = EmployeeLeave::where('institute_id', $institute_id)
        ->where('final_status', 'Pending')
        ->limit(5)
        ->get()
        ->map(function ($item) {
            $item->role = 'Employee';
            return $item;
        });
    
        $studentLeaves = StudentLeave::where('institute_id', $institute_id)
            ->where('status', 'Pending')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->role = 'Student';
                return $item;
            });
        
        $data['get_leave'] = $employeeLeaves->merge($studentLeaves)->sortByDesc('created_at')->take(5);
        
        // Fee Calculations
        $course_fee = StudentCourseFeeStructure::where('institute_id', $institute_id)->get();
        $registration_fee = StudentRegistrationFeeStructure::where('institute_id', $institute_id)->get();
        $transport_fee = StudentTransportFeeStructure::where('institute_id', $institute_id)->get();
        $hostel_fee = StudentHostelFeeStructure::where('institute_id', $institute_id)->get();
        $custom_fee = StudentCustomFeestructure::where('institute_id', $institute_id)->get();
        
        $data['total_course_fee'] = $course_fee->sum('course_fee');
        $data['total_registration_fee'] = $registration_fee->sum('registration_fee');
        $data['total_transport_fee'] = $transport_fee->sum('transport_fee');
        $data['total_hostel_fee'] = $hostel_fee->sum('hostel_fee');
        $data['total_custom_fee'] = $custom_fee->sum('custom_fee_value');
        $data['total_fee'] = $data['total_course_fee'] + $data['total_registration_fee'] + 
                            $data['total_transport_fee'] + $data['total_hostel_fee'] + $data['total_custom_fee'];
        
        $data['paid_course_fee'] = $course_fee->where('payment_status', 'paid')->sum('course_fee');
        $data['paid_registration_fee'] = $registration_fee->where('payment_status', 'paid')->sum('registration_fee');
        $data['paid_transport_fee'] = $transport_fee->where('payment_status', 'paid')->sum('transport_fee');
        $data['paid_hostel_fee'] = $hostel_fee->where('payment_status', 'paid')->sum('hostel_fee');
        $data['paid_custom_fee'] = $custom_fee->where('payment_status', 'paid')->sum('custom_fee_value');
        $data['paid_fee'] = $data['paid_course_fee'] + $data['paid_registration_fee'] + 
        $data['paid_transport_fee'] + $data['paid_hostel_fee'] + $data['paid_custom_fee'];
        $data['unpaid_fee'] = $data['total_fee'] - $data['paid_fee'];
        
        // Calculate collection rate
        $data['collection_rate'] = $data['total_fee'] > 0 
            ? round(($data['paid_fee'] / $data['total_fee']) * 100, 1) 
            : 0;
        
        // Calculate dues (current month and overdue)
        $currentMonthStart = now()->startOfMonth();
        $data['current_dues'] = StudentCourseFeeStructure::where('institute_id', $institute_id)
            ->where('payment_status', 'unpaid')
            ->whereDate('due_date', '>=', $currentMonthStart)
            ->sum('course_fee');
        
        $data['overdue_dues'] = StudentCourseFeeStructure::where('institute_id', $institute_id)
            ->where('payment_status', 'unpaid')
            ->whereDate('due_date', '<', $currentMonthStart)
            ->sum('course_fee');
        
        $data['total_dues'] = $data['current_dues'] + $data['overdue_dues'];
        
        // Late fees
        $data['late_course_fee'] = $course_fee->sum('late_fee_amount');
        $data['late_registration_fee'] = $registration_fee->sum('late_fee_amount');
        $data['late_transport_fee'] = $transport_fee->sum('late_fee_amount');
        $data['late_hostel_fee'] = $hostel_fee->sum('late_fee_amount');
        $data['late_custom_fee'] = $custom_fee->sum('late_fee_amount');
        $data['fine_fee'] = $data['late_course_fee'] + $data['late_registration_fee'] + 
        $data['late_transport_fee'] + $data['late_hostel_fee'] + $data['late_custom_fee'];
        
        // Events
        $data['all_events'] = HolidayEvent::where('institute_id', $institute_id)
            ->where('type', '!=' ,'holiday')
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();
            // dd($data);
            
        // Get current date
        $today = Carbon::now();
        
        // Determine financial year start & end
        if ($today->month >= 4) {
            // April to December → same year start
            $startOfFY = Carbon::create($today->year, 4, 1)->startOfDay();
            $endOfFY   = Carbon::create($today->year + 1, 3, 31)->endOfDay();
        } else {
            // Jan to March → previous year start
            $startOfFY = Carbon::create($today->year - 1, 4, 1)->startOfDay();
            $endOfFY   = Carbon::create($today->year, 3, 31)->endOfDay();
        }
        
        // Query
        $data['holidays'] = HolidayEvent::where('institute_id', $institute_id)
            ->where('type', 'holiday')
            ->where('status', 'active')
            ->whereBetween('start_date', [$startOfFY, $endOfFY])
            ->orderBy('start_date')
            // ->limit(5)
            ->get();

        // Attendance data
        $today = Carbon::today()->toDateString();
        $data['employee_present'] = EmployeeAttendance::where('institute_id', $institute_id)
            ->where('status', 'Present')
            ->whereDate('date', $today)
            ->count();
        
        $data['employee_absent'] = EmployeeAttendance::where('institute_id', $institute_id)
            ->where('status', 'Absent')
            ->whereDate('date', $today)
            ->count();
        
        $data['student_present'] = StudentAttendence::where('institute_id', $institute_id)
            ->where('status', 'Present')
            ->whereDate('date', $today)
            ->distinct('student_hash_id')
            ->count('student_hash_id');
        
        $data['student_absent'] = StudentAttendence::where('institute_id', $institute_id)
            ->where('status', 'Absent')
            ->whereDate('date', $today)
            ->distinct('student_hash_id')
            ->count('student_hash_id');
        
        // Last 7 days fee collection data
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $last7Days->push([
                'x' => Carbon::today()->subDays($i)->format('Y-m-d'),
                'y' => 0
            ]);
        }
        
        // Fetch fee data for different types
        $data['course_fee_graph'] = $this->getLast7DaysFeeData(StudentCourseFeeStructure::class, $institute_id, 'course_fee');
        $data['registration_fee_graph'] = $this->getLast7DaysFeeData(StudentRegistrationFeeStructure::class, $institute_id, 'registration_fee');
        $data['transport_fee_graph'] = $this->getLast7DaysFeeData(StudentTransportFeeStructure::class, $institute_id, 'transport_fee');
        $data['hostel_fee_graph'] = $this->getLast7DaysFeeData(StudentHostelFeeStructure::class, $institute_id, 'hostel_fee');
        $data['custom_fee_graph'] = $this->getLast7DaysFeeData(StudentCustomFeestructure::class, $institute_id, 'custom_fee_value');
        
        // Format the data for charts
        foreach (['course_fee_graph', 'registration_fee_graph', 'transport_fee_graph', 'hostel_fee_graph', 'custom_fee_graph'] as $graphKey) {
            $data[$graphKey] = $this->formatChartData($last7Days, $data[$graphKey]);
        }
        
        // Student registration by quarter
        $year = now()->year;
        $registrationPerQuarter = StudentParentDetails::where('institute_id', $institute_id)->whereYear('created_at', $year)
            ->select(
                DB::raw("QUARTER(created_at) as quarter"),
                DB::raw("COUNT(id) as total")
            )
            ->groupBy(DB::raw("QUARTER(created_at)"))
            ->orderBy(DB::raw("QUARTER(created_at)"))
            ->get();
        
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $values = [0, 0, 0, 0];
        
        foreach ($registrationPerQuarter as $row) {
            $index = ((int) $row->quarter) - 1;
            if (isset($values[$index])) {
                $values[$index] = (int) $row->total;
            }
        }
        
        $data['registration_quarter_labels'] = $quarters;
        $data['registration_quarter_values'] = $values;
        
        // Employee Gender Data for Chart
        $data['employee_gender_labels'] = ['Male', 'Female', 'Other'];
        $data['employee_gender_values'] = [
            $data['male_employees'],
            $data['female_employees'],
            $data['other_employees']
        ];
        
        // Notice Board
        $data['notice_board'] = Notice::where('institute_id', $institute_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('instituteAdmin.DashboardMainPagesFiles.admin', compact('data'));
    }

    /**
     * Get dynamic employee roles from database
     */
    private function getEmployeeRoles($institute_id)
    {
        // Get all designations with employee counts
        $designations = Designations::where('institute_id', $institute_id)
            ->where('status', 'active')
            ->withCount(['employees' => function($query) use ($institute_id) {
                $query->where('institute_id', $institute_id)
                      ->where('status', 'active');
            }])
            ->get();
        
        $roles = [];
        $labels = [];
        $values = [];
        
        foreach ($designations as $designation) {
            if ($designation->employees_count > 0) {
                $key = strtolower(str_replace(' ', '_', $designation->designations));
                $roles[$key] = [
                    'name' => $designation->designations,
                    'count' => $designation->employees_count,
                    'id' => $designation->id
                ];
                $labels[] = $designation->designations;
                $values[] = $designation->employees_count;
            }
        }
        
        // If no designations found, get roles from employee details
        if (empty($roles)) {
            $employeeRoles = EmployeeDetails::where('institute_id', $institute_id)
                ->where('status', 'active')
                ->groupBy('assigned_role')
                ->select('assigned_role', DB::raw('COUNT(*) as count'))
                ->get();
            
            foreach ($employeeRoles as $role) {
                if (!empty($role->assigned_role)) {
                    $key = strtolower(str_replace(' ', '_', $role->assigned_role));
                    $roles[$key] = [
                        'name' => ucfirst($role->assigned_role),
                        'count' => $role->count,
                        'id' => $key
                    ];
                    $labels[] = ucfirst($role->assigned_role);
                    $values[] = $role->count;
                }
            }
        }
        
        return [
            'roles' => $roles,
            'labels' => $labels,
            'values' => $values
        ];
    }
    
    /**
     * Get UI configuration for different roles
     */
    private function getRoleUIConfig()
    {
        return [
            'teacher' => ['icon' => 'ti ti-school', 'color' => 'text-info', 'class' => 'teacher-role', 'bg' => '#17a2b8'],
            'faculty' => ['icon' => 'ti ti-users', 'color' => 'text-info', 'class' => 'teacher-role', 'bg' => '#17a2b8'],
            'professor' => ['icon' => 'ti ti-user-star', 'color' => 'text-info', 'class' => 'teacher-role', 'bg' => '#17a2b8'],
            'lecturer' => ['icon' => 'ti ti-microphone', 'color' => 'text-info', 'class' => 'teacher-role', 'bg' => '#17a2b8'],
            'admin' => ['icon' => 'ti ti-shield', 'color' => 'text-success', 'class' => 'admin-role', 'bg' => '#28a745'],
            'principal' => ['icon' => 'ti ti-crown', 'color' => 'text-warning', 'class' => 'principal-role', 'bg' => '#ffc107'],
            'accountant' => ['icon' => 'ti ti-calculator', 'color' => 'text-danger', 'class' => 'accountant-role', 'bg' => '#dc3545'],
            'receptionist' => ['icon' => 'ti ti-phone', 'color' => 'text-primary', 'class' => 'receptionist-role', 'bg' => '#4a6cf7'],
            'librarian' => ['icon' => 'ti ti-book', 'color' => 'text-purple', 'class' => 'librarian-role', 'bg' => '#6f42c1'],
            'driver' => ['icon' => 'ti ti-steering-wheel', 'color' => 'text-orange', 'class' => 'driver-role', 'bg' => '#fd7e14'],
            'cleaner' => ['icon' => 'ti ti-broom', 'color' => 'text-teal', 'class' => 'cleaner-role', 'bg' => '#20c997'],
            'security' => ['icon' => 'ti ti-shield-check', 'color' => 'text-dark', 'class' => 'security-role', 'bg' => '#343a40'],
            'default' => ['icon' => 'ti ti-user', 'color' => 'text-secondary', 'class' => 'default-role', 'bg' => '#6c757d']
        ];
    }

    private function getLast7DaysFeeData($model, $institute_id, $feeColumn)
    {
        $sevenDaysAgo = now()->subDays(6)->format('Y-m-d');
        
        return $model::where('institute_id', $institute_id)
            ->whereDate('pay_date', '>=', $sevenDaysAgo)
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(pay_date) as date'),
                DB::raw('SUM(' . $feeColumn . ') as amount')
            )
            ->groupBy(DB::raw('DATE(pay_date)'))
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'amount' => (float) $item->amount
                ];
            });
    }

    private function formatChartData($baseDates, $dbData)
    {
        return $baseDates->map(function ($item) use ($dbData) {
            $match = $dbData->firstWhere('date', $item['x']);
            return [
                'x' => $item['x'],
                'y' => $match ? $match['amount'] : 0
            ];
        });
    }
}