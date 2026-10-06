@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        /* Custom Colors */
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --info: #0dcaf0;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
            --teal: #20c997;
            --skyblue: #17a2b8;
            --dark-bg: #1c2836;
            --bg-03: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        }
        
        /* Attendance badges */
        .badge-present {
            background-color: var(--success);
            color: #fff;
        }

        .badge-absent {
            background-color: var(--danger);
            color: #fff;
        }

        .badge-halfday {
            background-color: var(--warning);
            color: var(--dark);
        }

        .badge-late {
            background-color: var(--teal);
            color: #fff;
        }

        .badge-leave {
            background-color: var(--info);
            color: #fff;
        }

        .badge-norecord {
            background-color: var(--light);
            color: var(--secondary);
            border: 1px solid var(--secondary);
        }

        .page-title {
            font-weight: 600;
            color: var(--dark);
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--secondary);
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        /* Greeting Section */
        .bg-info.bg-03 {
            background: var(--bg-03) !important;
            border: none;
        }

        .bg-info.bg-03 h1,
        .bg-info.bg-03 p {
            color: white !important;
        }

        /* Teacher Profile Card */
        .bg-dark {
            background-color: var(--dark-bg) !important;
            border: none;
        }

        .avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            overflow: hidden;
        }

        .avatar-xxl {
            width: 80px;
            height: 80px;
        }

        .avatar-lg {
            width: 60px;
            height: 60px;
        }

        .avatar-md {
            width: 40px;
            height: 40px;
        }

        .avatar-rounded {
            border-radius: 8px !important;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .badge {
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .badge.bg-transparent-primary {
            background-color: rgba(13, 110, 253, 0.1) !important;
            color: var(--primary) !important;
        }

        /* Progress Bars */
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }

        .progress-bar {
            border-radius: 4px;
        }

        /* Today's Class Carousel */
        .task-slider .item {
            padding: 0 10px;
        }

        .bg-light-400 {
            background-color: #f8f9fa;
        }

        .border-3 {
            border-width: 3px !important;
        }

        /* Attendance Section */
        .bg-light-300 {
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .br-5 {
            border-radius: 5px;
        }

        /* Student Progress */
        .border.br-5 {
            border-radius: 5px;
        }

        /* Schedules Section */
        .event-scroll {
            max-height: 400px;
            overflow-y: auto;
        }

        .border-start {
            border-left-width: 3px !important;
        }

        .avatar-list-stacked .avatar {
            margin-left: -8px;
            border: 2px solid white;
        }

        .avatar-list-stacked .avatar:first-child {
            margin-left: 0;
        }

        /* Syllabus Carousel */
        .lesson .item {
            padding: 0 12px;
        }

        .transparent-badge {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary);
        }

        /* Student Marks Table */
        .custom-datatable-filter table {
            margin-bottom: 0;
        }

        .custom-datatable-filter thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #dee2e6;
        }

        /* Leave Status */
        .bg-light-500 {
            background-color: #f8f9fa;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header {
                padding: 1rem;
            }

            .avatar-xxl {
                width: 60px;
                height: 60px;
            }
        }

        .calendar-week {
            display: flex;
        }

        .calendar-day-cell {
            flex: 1;
            min-height: 40px;
            padding: 2px;
        }

        .calendar-day {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            width: 36px;
            margin: 0 auto;
            border-radius: 50%;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            position: relative;
        }

        .calendar-day:hover:not(.empty):not(.other-month) {
            background-color: #f0f8ff !important;
            border-color: #cce5ff;
            transform: translateY(-1px);
        }

        .calendar-day.empty {
            cursor: default;
            background-color: transparent !important;
        }

        .calendar-day.today {
            background-color: #0d6efd !important;
            color: white !important;
            border-color: #0d6efd;
            font-weight: 600;
        }

        .calendar-day.selected {
            background-color: #20c997 !important;
            color: white !important;
            border-color: #20c997;
            font-weight: 600;
        }

        .calendar-day.other-month {
            color: #adb5bd !important;
            opacity: 0.5;
        }

        .event-indicator {
            position: absolute;
            bottom: 3px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background-color: #dc3545;
        }

        /* Event scroll */
        .event-scroll {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .event-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .event-scroll::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        /* Avatar styling */
        .avatar-list-stacked {
            display: flex;
        }

        .avatar-list-stacked .avatar {
            margin-left: -8px;
            border: 2px solid white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
        }

        .avatar-list-stacked .avatar:first-child {
            margin-left: 0;
        }

        @media (max-width: 768px) {
            .calendar-day {
                height: 32px;
                width: 32px;
                font-size: 0.85rem;
            }

            .calendar-day-cell {
                min-height: 36px;
            }
        }

        @media (max-width: 576px) {
            .calendar-day {
                height: 28px;
                width: 28px;
                font-size: 0.8rem;
            }

            .calendar-day-cell {
                min-height: 32px;
                padding: 1px;
            }
        }

        .employee-leave, .employee-upcoming-events, .employee-syllabus, .student-marks, .employee-notices{
            max-height:500px;
            overflow-y: scroll;
        }
        
        a{
            text-decoration: none;
        }
    </style>
    <div class="container-fluid">
        <!-- Page Header -->
        <!--<div class="d-md-flex d-block align-items-center justify-content-between mb-3">-->
        <!--    <div class="my-auto mb-2">-->
        <!--        <h3 class="page-title mb-1">Teacher Dashboard</h3>-->
        <!--        <nav>-->
        <!--            <ol class="breadcrumb mb-0">-->
        <!--                <li class="breadcrumb-item">-->
        <!--                    <a href="#">Dashboard</a>-->
        <!--                </li>-->
        <!--                <li class="breadcrumb-item active" aria-current="page">-->
        <!--                    Teacher Dashboard-->
        <!--                </li>-->
        <!--            </ol>-->
        <!--        </nav>-->
        <!--    </div>-->
        <!--</div>-->
        <!-- /Page Header -->
        @php
            // Name
            $name = $employee->name ?? 'Employee';
            $status = $employee->status ?? '';

            // Gender-based title
            $gender = strtolower($employee->gender ?? '');

            if ($gender === 'male') {
                $title = 'Mr.';
            } elseif ($gender === 'female') {
                $title = 'Ms.';
            } else {
                $title = '';
            }

            // Greeting based on time
            $hour = now()->hour;

            if ($hour >= 5 && $hour < 12) {
                $greeting = 'Good Morning';
            } elseif ($hour >= 12 && $hour < 16) {
                $greeting = 'Good Afternoon';
            } elseif ($hour >= 16 && $hour < 20) {
                $greeting = 'Good Evening';
            } else {
                $greeting = 'Good Night';
            }
        @endphp

        <!-- Notice -->
        <div class="alert-message d-none">
            <div class="alert alert-success rounded-pill d-flex align-items-center justify-content-between border-success mb-4 p-1" role="alert">
                <div class="d-flex align-items-center">
                    <!-- <span class="me-1 avatar avatar-sm flex-shrink-0"><img src="/erpassets/img/profiles/avatar-27.jpg" alt="Img" class="img-fluid rounded-circle"></span> -->
                    <strong>Notice:</strong>
                    <p style="margin-block: 0;">
                        Staff meeting at <strong>9:00 AM</strong>. Don’t forget to attend!
                    </p>
                </div>
                <button type="button" class="btn-close pr-3 d-flex" data-bs-dismiss="alert" aria-label="Close">
                    <span></span>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex">
                <!--<div class="card flex-fill bg-info bg-03">-->
                <!--     <div class="card-body">-->
                <!--        <h1 class="text-white mb-1">{{ $greeting }} {{ $title }} {{ $name }}</h1>-->
                <!--        <p class="text-white mb-3">Have a Good day at work</p>-->
                <!--        <p class="text-light">-->
                <!--            Notice : There is a staff meeting at 9AM today, Dont forget to Attend!!!-->
                <!--        </p>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
        <!-- /Greeting Section -->

        <!-- Teacher-Profile -->
        <div class="row">
            <div class="col-md-8 d-flex">
                <div class="card bg-dark text-white shadow-lg border-0 rounded-2 position-relative flex-fill">
                    <div class="card-body p-4">
                        <!-- Greeting -->
                        <div class="mb-3">
                            <h2 class="fw-bold mb-1">
                                {{ $greeting }} {{ $title }} {{ $name }}
                            </h2>
                            <p class="text-light mb-0">Have a great day at work 🌟</p>
                        </div>

                        <!-- Notice -->
                        <!-- <div class="alert  bg-warning bg-opacity-10 border  rounded-3 mb-4">
                            <strong>Notice:</strong> Staff meeting at <strong>9:00 AM</strong>. Don’t forget to attend!
                        </div> -->

                        <!-- Profile Section -->
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $name = $employee->name ?? 'Teacher';
                                    $initials = strtoupper(substr($name, 0, 1));
                                    $colors = ['primary', 'secondary', 'success', 'danger', 'warning',
                                    'info'];
                                    $bgColor = $colors[crc32($employee->employee_code ?? $name) %
                                    count($colors)];
                                @endphp

                                <!-- Avatar -->
                                <div class="avatar avatar-xxl rounded flex-shrink-0 border border-2 border-white me-3 position-relative"
                                            style="width: 80px; height: 80px; overflow: hidden;">
                                    @if($employee->profile_photo)
                                        <img src="{{ route('image', $employee->profile_photo) }}" alt="{{ $name }}" class="w-100 h-100 object-fit-cover" 
                                            onerror="this.style.display='none';
                                            this.parentElement.classList.add('bg-{{ $bgColor }}', 'bg-opacity-25', 'd-flex', 'align-items-center', 'justify-content-center');
                                            this.parentElement.innerHTML = '<span class=\'fw-bold text-{{ $bgColor }}\' style=\'font-size: 2rem;\'>{{ $initials }}</span>';">
                                    @else
                                        <div
                                            class="w-100 h-100 bg-{{ $bgColor }} bg-opacity-25 d-flex align-items-center justify-content-center">
                                            <span class="fw-bold text-white"
                                                style="font-size: 2rem;">{{ $initials }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div>
                                    <span class="badge bg-primary bg-opacity-25 mb-2">
                                        #{{ $employee->employee_code }}
                                    </span>
                                        
                                    @if($employee->suspend_status == 'suspended')
                                        <span class="badge bg-warning text-dark">
                                            Suspended
                                        </span>
                                    @elseif(!empty($employee->status))
                                        <span class="badge
                                            @if(strtolower($employee->status) == 'active')
                                                bg-success
                                            @elseif(strtolower($employee->status) == 'inactive')
                                                bg-danger
                                            @else
                                                bg-secondary
                                            @endif">
                                            {{ ucfirst($employee->status) }}
                                        </span>
                                    @endif

                                    <!-- <h4 class="fw-semibold mb-1 text-truncate">
                                        {{ $employee->name }}
                                    </h4> -->

                                    <div class="d-flex align-items-center text-light small flex-wrap gap-2">
                                        <span style="font-size:14px;">{{ $employee->designation ?? 'Designation' }}</span>
                                        <span class="d-flex align-items-center" style="font-size:14px;">
                                            <i class="bi bi-circle-fill text-warning fs-7 me-1"></i>
                                            {{ $employee->department_name ?? 'Department' }}
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex align-items-center text-light small flex-wrap gap-2">
                                        <span class="small">{{ $employee->employment_type }}</span>
                                        @if($employee->employment_type == 'Probation-Period')
                                            <br>
                                            <span class="small text-warning">
                                                {{ $employee->probation_days }} Days
                                            </span>
                                        @endif       
                                    </div>
                                    
                                    @php
                                        $status = optional($todayAttendance)->status ?? 'Absent';
                                    @endphp
                                    <div class="mt-2">
                                        <span
                                            style="
                                                background-color: {{ $status === 'Present' ? 'var(--success)' : 'var(--danger)' }};
                                                color: #fff;
                                                padding: 2px 6px;
                                                border-radius: 6px;
                                            "
                                        >
                                            {{ $status }}
                                        </span>
                                    </div>
      
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="{{ url('employee/profile-settings') }}" class="btn btn-primary px-4 rounded-pill">
                                <i class="bi bi-pencil-square me-1"></i>
                                Edit Profile
                            </a>
                        </div>
                    </div>

                    <!-- Decorative Background -->
                    <!-- <div class="position-absolute bottom-0 end-0 opacity-25 pe-none">
                        <i class="bi bi-circle-fill text-primary fs-1 position-absolute"
                            style="bottom: 20px; right: 80px;"></i>
                        <i class="bi bi-circle-fill text-info fs-2 position-absolute"
                            style="bottom: 40px; right: 40px;"></i>
                        <i class="bi bi-circle-fill text-warning fs-3 position-absolute"
                            style="bottom: 60px; right: 100px;"></i>
                    </div> -->
                </div>
            </div>
            <div class="col-md-4 d-flex">
                <div class="card flex-fill">
                    <div class="card-body d-flex">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-sm-5">
                                <div id="plan_chart" class="mb-3 mb-sm-0 text-center text-sm-start"></div>
                            </div>
                            <div class="col-sm-7">
                                <div class="text-center text-sm-start">
                                    <h4 class="mb-3">Syllabus</h4>
                                    <p class="mb-2">
                                        <i class="bi bi-circle-fill text-success me-1"></i>Completed :
                                        <span class="fw-semibold">80%</span>
                                    </p>
                                    <p>
                                        <i class="bi bi-circle-fill text-danger me-1"></i>Pending :
                                        <span class="fw-semibold">20%</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Class -->
        <div class="row">
            <div class="col-md-12">
                <div class="card"> 
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center"> 
                    <h4 class="me-2">Today's Class</h4> 
                </div> 
                <div class="d-inline-flex align-items-center class-datepick"> 
                    <span class="icon"><i class="bi bi-chevron-left me-2"></i></span>
                    <input type="text"
                        class="form-control datetimepicker border-0"
                        value="{{ now()->format('d M Y') }}"
                        readonly>
                    <span class="icon"><i class="bi bi-chevron-right"></i></span>
                </div> 
            </div>
            <div class="card-body">
                <div class="d-flex overflow-auto pb-2">

                    @forelse($employeeLectures as $class)
                        <div class="me-3">
                            <div class="bg-light-400 rounded p-3" style="min-width: 180px;">
                                <span class="badge bg-danger mb-2 d-inline-flex align-items-center">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
                                    -
                                    {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                </span>

                                <p class="text-dark mb-0">
                                    {{ $class->section_name ?? 'N/A' }}
                                </p>

                                <small class="text-muted">
                                    {{ $class->subject_display_name }}
                                </small>

                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No classes scheduled for today.</p>
                    @endforelse


                </div>
            </div>
        </div>
            </div>
        </div>
        <!-- /Today's Class -->
            
        <div class="row">
            <!-- Attendance -->
            <div class="col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Attendance</h4>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle p-2 text-decoration-none" data-bs-toggle="dropdown">
                                <i class="bi bi-calendar-week me-1"></i>This Week
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">This Week</a>
                                <a class="dropdown-item" href="#">Last Week</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="bg-light-300 rounded border p-3 mb-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <h6 class="mb-2">Last 7 Days</h6>
                                <p class="mb-2">
                                    {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                @for ($i = 0; $i < 7; $i++)
                                    @php
                                        $date = \Carbon\Carbon::now()->subDays(6 - $i)->toDateString();
                                        $day  = \Carbon\Carbon::parse($date)->format('D')[0];

                                        $attendance = $attendanceData->get($date);
                                        $status = trim(strtolower($attendance?->status ?? ''));
                                    @endphp

                                    @if ($status === 'present')
                                        <span class="badge badge-present p-2" title="Present">{{ $day }}</span>

                                        @elseif ($status === 'absent')
                                        <span class="badge badge-absent p-2" title="Absent">{{ $day }}</span>

                                        @elseif ($status === 'half day')
                                        <span class="badge badge-halfday p-2" title="Half Day">{{ $day }}</span>

                                        @elseif ($status === 'late')
                                        <span class="badge badge-late p-2" title="Late">{{ $day }}</span>

                                        @elseif ($status === 'on leave')
                                        <span class="badge badge-leave p-2" title="On Leave">{{ $day }}</span>

                                        @else
                                        <span class="badge badge-norecord p-2" title="No Record">{{ $day }}</span>
                                    @endif
                                @endfor
                            </div>

                        </div>
                            <p class="mb-3">
                                <i class="bi bi-calendar-heart text-primary me-2"></i>No of total working days
                                <span class="fw-medium text-dark">
                                    {{ \Carbon\Carbon::now()->daysInMonth }} Days
                                </span>
                            </p>
                            @php
                                $present = $monthlyAttendance->where('status', 'Present')->count();
                                $absent  = $monthlyAttendance->where('status', 'Absent')->count();
                                $halfday = $monthlyAttendance->where('status', 'Half Day')->count();
                                $late    = $monthlyAttendance->where('status', 'Late')->count();
                                
                            @endphp

                            <div class="border rounded p-1">
                                <div class="row">
                                    <div class="col text-center border-end">
                                        <p class="mb-1">Present</p>
                                        <h5>{{ $present }}</h5>
                                    </div>
                                    <div class="col text-center border-end">
                                        <p class="mb-1">Absent</p>
                                        <h5>{{ $absent }}</h5>
                                    </div>
                                    <div class="col text-center border-end">
                                        <p class="mb-1">Halfday</p>
                                        <h5>{{ $halfday }}</h5>
                                    </div>
                                    <div class="col text-center">
                                        <p class="mb-1">Late</p>
                                        <h5>{{ $late }}</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="attendance-chart text-center p-4">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                    </div>
                </div>
            </div>
            <!-- /Attendance -->

            <!-- Leave Status -->
            <div class="col-md-6 d-flex">
                <div class="card w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Leave Status</h4>
                        <div class="dropdown">
                            <a href="#" class="bg-white dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                                <i class="bi bi-calendar me-2"></i>This Month
                            </a>
                            <div class="dropdown-menu mt-2 p-2">
                                <a class="dropdown-item" href="#">This Month</a>
                                <a class="dropdown-item" href="#">This Year</a>
                                <a class="dropdown-item" href="#">Last Week</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body employee-leave">
                        @forelse ($leaves as $leave)
                            @php
                                // Status color
                                $statusClass = match ($leave->final_status) {
                                    'Approved'  => 'success',
                                    'Rejected'  => 'danger',
                                    'Cancelled' => 'secondary',
                                    default     => 'warning', // Pending
                                };

                                // Leave type icon & color
                                [$icon, $typeColor] = match ($leave->leave_type) {
                                    'Sick Leave'       => ['bi-heart-pulse', 'info'],
                                    'Casual Leave'     => ['bi-sun', 'primary'],
                                    'Earned Leave'     => ['bi-star', 'success'],
                                    'Unpaid Leave'     => ['bi-exclamation-triangle', 'warning'],
                                    'Maternity Leave'  => ['bi-heart', 'danger'],
                                    default      => ['bi-file-earmark', 'secondary'],
                                };
                            @endphp

                            <div class="bg-light-300 d-sm-flex align-items-center justify-content-between p-3 mb-3">
                                <div class="d-flex align-items-center mb-2 mb-sm-0">
                                    <div class="avatar avatar-lg bg-{{ $typeColor }} bg-opacity-10 flex-shrink-0 me-2">
                                        <i class="bi {{ $icon }} text-white"></i>
                                    </div>

                                    <div>
                                        <h6 class="mb-1">{{ $leave->leave_type }}</h6>
                                        <p class="text-muted mb-0">
                                            Date :
                                            {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                            @if ($leave->end_date && $leave->end_date !== $leave->start_date)
                                                - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <span class="badge bg-{{ $statusClass }} bg-opacity-10">
                                    <i class="bi bi-circle fs-6 me-1"></i>
                                    {{ $leave->final_status }}
                                </span>
                            </div>
                            @empty
                                <p class="text-muted text-center mb-0">
                                    No leave records found.
                                </p>
                        @endforelse
                    </div>
                </div>
            </div>
            <!-- /Leave Status -->
        </div>

        <!-- Schedules -->
         <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="card flex-fill">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">Schedules</h4>
                            <a href="#" class="btn btn-sm btn-primary d-none align-items-center text-decoration-none"
                                data-bs-toggle="modal" data-bs-target="#add_event">
                                <i class="bi bi-plus-circle me-1"></i>Add New
                            </a>
                        </div>
                        <div class="card-body ">
                            <!-- Calendar Section -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-semibold" id="currentMonth"></h5>
                                    <div>
                                        <button class="btn btn-sm btn-outline-secondary" id="prevMonth">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" id="nextMonth">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
    
                                <!-- Day Headers -->
                                <div class="row g-0 text-center mb-1">
                                    <div class="col p-1"><small class="text-muted fw-medium">Sun</small></div>
                                    <div class="col p-1"><small class="text-muted fw-medium">Mon</small></div>
                                    <div class="col p-1"><small class="text-muted fw-medium">Tue</small></div>
                                    <div class="col p-1"><small class="text-muted fw-medium">Wed</small></div>
                                    <div class="col p-1"><small class="text-muted fw-medium">Thu</small></div>
                                    <div class="col p-1"><small class="text-muted fw-medium">Fri</small></div>
                                    <div class="col p-1"><small class="text-muted fw-medium">Sat</small></div>
                                </div>
    
                                <!-- Calendar Days Container - THIS IS THE KEY FIX -->
                                <div id="calendarContainer">
                                    <!-- Calendar will be generated by JavaScript in proper rows -->
                                </div>
                            </div>
    
                                <!-- Upcoming Events -->
                            <h4 class="mb-3 fw-semibold">Upcoming Events</h4>
    
                            <div class="event-scroll employee-upcoming-events">
                                @forelse ($events as $event)
    
                                    @php
                                        // Color (fallback safe)
                                        $color = $event->color ?? 'primary';
    
                                        // Icon based on type
                                        $icon = match ($event->type) {
                                            'holiday' => 'bi-calendar-event',
                                            'meeting' => 'bi-people',
                                            'event'   => 'bi-megaphone',
                                            default   => 'bi-calendar',
                                        };
                                    @endphp
                                    <div class="border-start border-{{ $color }} border-3 shadow-sm p-3 mb-3 bg-light">
                                        <div class="d-flex align-items-center mb-1 pb-1">
                                            <span class="avatar p-2 me-3 bg-{{ $color }} bg-opacity-10 flex-shrink-0 rounded-circle">
                                                <i class="bi {{ $icon }} text-{{ $color }} fs-5"></i>
                                            </span>
    
                                            <div class="flex-fill">
                                                <h6 class="mb-1 fw-semibold">{{ $event->title }}</h6>
                                                <p class="d-flex align-items-center text-muted mb-0">
                                                    <i class="bi bi-calendar me-2"></i>
                                                    {{ $event->start_date->format('d M Y') }}
                                                </p>
                                            </div>
                                            <div>
                                                <div class="text-end">
                                                    <p class="mb-0 text-muted">
                                                        @if(strtolower($event->type) !== 'holiday')
                                                            <i class="bi bi-clock me-2"></i>
                                                            {{ $event->start_date->format('h:i A') }}
                                                            @if($event->end_date)
                                                                - {{ $event->end_date->format('h:i A') }}
                                                            @endif
                                                        @else
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-calendar-check me-2"></i>
                                                                Full Day Holiday
                                                            </span>
                                                        @endif
                                                    </p>
                                                
                                                    {{-- Scope Badge --}}
                                                    <span class="d-none badge bg-primary text-{{ $color }}">
                                                        {{ ucfirst(str_replace('_', ' ', $event->scope)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
    
                                @empty
                                    <p class="text-muted text-center mb-0">
                                        No upcoming events found.
                                    </p>
                                @endforelse
                            </div>
    
                            <!-- /Schedules -->
                        </div>
                    </div>
                </div>
                <!-- Teacher-profile -->

                <!-- Syllabus -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-title">Syllabus / Lesson Plan</h4>
                            <a href="#" class="link-primary fw-medium text-decoration-none">View All</a>
                        </div>
                        <div class="d-flex overflow-auto pb-3">
                            @forelse($syllabuses as $syllabus)
                                <div class="me-3" style="min-width: 280px;">
                                    <div class="card h-100">
                                        <div class="card-body employee-syllabus">
                                            <!-- Course Detail ID -->
                                            <div class="bg-success bg-opacity-10 rounded p-2 fw-semibold mb-3 text-center text-success">
                                                <div>{{ $syllabus->course_type }}</div>
                                                <small class="text-muted">{{ $syllabus->subject_display_name }}</small>
                                            </div>

                                            <div class="border-bottom mb-3">
                                                <!-- Title -->
                                                <h5 class="mb-2">
                                                    {{ $syllabus->title }}
                                                </h5>

                                                <!-- Description -->
                                                <p class="text-muted small mb-3">
                                                    {{ Str::limit($syllabus->description, 80) }}
                                                </p>

                                                <div class="progress progress-xs mb-3">
                                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between">
                                                <!-- File path / open -->
                                                <a href="{{ $syllabus->file_url }}" target="_blank" class="fw-medium text-decoration-none">
                                                    <i class="bi bi-file-earmark-text me-1"></i>
                                                    View File
                                                </a>

                                                <!-- Download -->
                                                <a href="{{ $syllabus->file_url }}" target="_blank" class="link-primary text-decoration-none">
                                                    <i class="bi bi-download me-1"></i>Download
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted mx-auto">No syllabus uploaded yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <!-- /Syllabus -->
                
                <!-- Student Marks -->
                <div class="d-none">
                    <div class="col-md-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                                <h4 class="card-title">Student Marks</h4>
                                <div class="d-flex align-items-center">
                                    <div class="dropdown me-2">
                                        <a href="#" class="bg-white dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                                            <i class="bi bi-calendar me-2"></i>All Classes
                                        </a>
                                        <div class="dropdown-menu mt-2 p-2">
                                            <a class="dropdown-item" href="#">I</a>
                                            <a class="dropdown-item" href="#">II</a>
                                            <a class="dropdown-item" href="#">III</a>
                                            <a class="dropdown-item" href="#">IV</a>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <a href="#" class="bg-white dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                                            <i class="bi bi-calendar me-2"></i>All Sections
                                        </a>
                                        <div class="dropdown-menu mt-2 p-2">
                                            <a class="dropdown-item" href="#">A</a>
                                            <a class="dropdown-item" href="#">B</a>
                                            <a class="dropdown-item" href="#">C</a>
                                            <a class="dropdown-item" href="#">D</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-0 student-marks">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Section</th>
                                                <th>Marks %</th>
                                                <th>CGPA</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>35013</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-md me-2">
                                                            <img src="https://i.pravatar.cc/150?img=19" class="rounded-circle"
                                                                alt="student">
                                                        </div>
                                                        <div>
                                                            <p class="text-dark mb-0">Janet Williams</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>III</td>
                                                <td>A</td>
                                                <td>89%</td>
                                                <td>4.2</td>
                                                <td><span class="badge bg-success">Pass</span></td>
                                            </tr>
                                            <tr>
                                                <td>35012</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-md me-2">
                                                            <img src="https://i.pravatar.cc/150?img=20" class="rounded-circle"
                                                                alt="student">
                                                        </div>
                                                        <div>
                                                            <p class="text-dark mb-0">Joann Smith</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>IV</td>
                                                <td>B</td>
                                                <td>88%</td>
                                                <td>3.2</td>
                                                <td><span class="badge bg-success">Pass</span></td>
                                            </tr>
                                            <tr>
                                                <td>35011</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-md me-2">
                                                            <img src="https://i.pravatar.cc/150?img=21" class="rounded-circle"
                                                                alt="student">
                                                        </div>
                                                        <div>
                                                            <p class="text-dark mb-0">Kathleen Brown</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>II</td>
                                                <td>A</td>
                                                <td>69%</td>
                                                <td>4.5</td>
                                                <td><span class="badge bg-success">Pass</span></td>
                                            </tr>
                                            <tr>
                                                <td>35010</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-md me-2">
                                                            <img src="https://i.pravatar.cc/150?img=22" class="rounded-circle"
                                                                alt="student">
                                                        </div>
                                                        <div>
                                                            <p class="text-dark mb-0">Gifford Miller</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>I</td>
                                                <td>B</td>
                                                <td>21%</td>
                                                <td>4.5</td>
                                                <td><span class="badge bg-danger">Fail</span></td>
                                            </tr>
                                            <tr>
                                                <td>35009</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-md me-2">
                                                            <img src="https://i.pravatar.cc/150?img=23" class="rounded-circle"
                                                                alt="student">
                                                        </div>
                                                        <div>
                                                            <p class="text-dark mb-0">Lisa Johnson</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>II</td>
                                                <td>B</td>
                                                <td>31%</td>
                                                <td>3.9</td>
                                                <td><span class="badge bg-danger">Fail</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Student Marks -->
            </div>
        </div>
        
        <!-- Notice Board -->
        <div class="row">
            <div class="col-md-12 order-3 order-md-2 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Notice Board</h4>
                        <a href="{{ url('/notice-board/view') }}" class="fw-medium"
                            >View All</a
                        >
                    </div>
                    <div class="card-body employee-notices">
                        <div class="notice-widget">
                            @if(count($notice_board) > 0)
                                @foreach($notice_board as $notice)
                                <div class="d-sm-flex align-items-center justify-content-between mb-4 p-3 border rounded">
                                    
                                    <!-- Left Content -->
                                    <div class="d-flex align-items-center overflow-hidden me-2">
                                        <span class="bg-primary-transparent avatar avatar-md me-3 rounded-circle flex-shrink-0">
                                            <i class="fa-solid fa-book"></i>
                                        </span>
                                
                                        <div class="overflow-hidden">
                                            <h6 class="text-truncate mb-1 fw-semibold">
                                                {{ $notice->title }}
                                            </h6>
                                
                                            <p class="mb-0 text-muted fs-13">
                                                <i class="ti ti-calendar me-1"></i>
                                                {{ $notice->created_at->format('d M Y') }}
                                                <span class="mx-2">•</span>
                                                <i class="fa fa-clock me-1"></i>
                                                {{ $notice->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                
                                    <!-- Right Badges -->
                                    <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                                        <!-- Status Badge -->
                                        <span class="d-none badge 
                                            {{ $notice->status === 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ ucfirst($notice->status) }}
                                        </span>
                                        <!-- Eye Icon -->
                                        <a href="javascript:void(0)"
                                            class="btn btn-outline-primary btn-sm action-btn view-btn"
                                            data-title="{{ $notice->title }}"
                                            data-content="{!! htmlspecialchars($notice->content) !!}"
                                            data-date="{{ $notice->created_at->format('M d, Y h:i A') }}"
                                            data-recipient="{{ ucfirst($notice->recipient_type) }}"
                                            data-attachment="{{ $notice->attachment ? asset('storage/'.$notice->attachment) : '' }}"
                                        >
                                            <i class="fas fa-eye"></i> 
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                No active notices available
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Notice Board -->
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="add_event">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Event</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Event Title</label>
                            <input type="text" class="form-control" placeholder="Enter event title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Time</label>
                            <input type="time" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" rows="3" placeholder="Enter event description"></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Notice Modal -->
    <div id="viewNoticeModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000;">
        <div style="background:#fff; max-width:750px; margin:60px auto; border-radius:12px; overflow:hidden; box-shadow:0 12px 35px rgba(0,0,0,0.25);">
            <!-- Header -->
            <div style="padding:20px; background:#1e3c72; color:#fff;">
                <h3 id="modalTitle" style="margin:0;"></h3>
            </div>

            <!-- Body -->
            <div style="padding:20px;">
                <div id="modalMeta" style="font-size:13px; color:#777; margin-bottom:15px;"></div>
                <div id="modalContent" style="line-height:1.6;"></div>
                <div id="modalAttachment" style="margin-top:15px;"></div>
            </div>

            <!-- Footer -->
            <div style="padding:15px; text-align:right; border-top:1px solid #eee;">
                <button class="create-btn btn btn-outline-primary" onclick="closeNoticeModal()">Close</button>
            </div>
        </div>
    </div>
    <!-- /View Notice Modal -->

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* =====================
            Bootstrap Components
            ====================== */

            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap JS not loaded');
                return;
            }

            // Tooltips
            document
                .querySelectorAll('[data-bs-toggle="tooltip"]')
                .forEach(el => new bootstrap.Tooltip(el));

            // Popovers
            document
                .querySelectorAll('[data-bs-toggle="popover"]')
                .forEach(el => new bootstrap.Popover(el));

            // Dropdowns
            document
                .querySelectorAll('.dropdown-toggle')
                .forEach(el => new bootstrap.Dropdown(el));

            /* =====================
            ApexCharts
            ====================== */

            const planChartEl = document.querySelector('#plan_chart');

            if (planChartEl) {
                const completed = 80;
                const pending = 20;

                const planChart = new ApexCharts(planChartEl, {
                    series: [completed, pending],
                    chart: {
                        type: 'donut',
                        height: 180
                    },
                    labels: ['Completed', 'Pending'],
                    colors: ['#198754', '#dc3545'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                            }
                        }
                    },
                    legend: { show: false },
                    dataLabels: { enabled: false }
                });

                planChart.render();
            }

            /* =====================
            Attendance Chart
            ====================== */

            const attendanceCtx = document.getElementById('attendanceChart');

            if (attendanceCtx) {
                new Chart(attendanceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Present', 'Absent', 'Half Day', 'Late'],
                        datasets: [{
                            data: [
                                {{ $present }},
                                {{ $absent }},
                                {{ $halfday }},
                                {{ $late }}
                            ],
                            backgroundColor: [
                                '#198754', // Present
                                '#dc3545', // Absent
                                '#ffc107', // Half Day
                                '#0d6efd'  // Late
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            /* =====================
            Student Table Logic
            ====================== */

            const studentsData = [
                { id: '35013', name: 'Janet Williams', avatar: 'https://i.pravatar.cc/150?img=19', class: 'III', section: 'A', marks: '89%', cgpa: '4.2', status: 'Pass' },
                { id: '35012', name: 'Joann Smith', avatar: 'https://i.pravatar.cc/150?img=20', class: 'IV', section: 'B', marks: '88%', cgpa: '3.2', status: 'Pass' },
                { id: '35011', name: 'Kathleen Brown', avatar: 'https://i.pravatar.cc/150?img=21', class: 'II', section: 'A', marks: '69%', cgpa: '4.5', status: 'Pass' },
                { id: '35010', name: 'Gifford Miller', avatar: 'https://i.pravatar.cc/150?img=22', class: 'I', section: 'B', marks: '21%', cgpa: '4.5', status: 'Fail' }
            ];

            const tbody = document.querySelector('.table tbody');

            const renderStudents = (className = 'All') => {
                if (!tbody) return;

                tbody.innerHTML = '';

                const filtered = className === 'All'
                    ? studentsData
                    : studentsData.filter(s => s.class === className);

                filtered.forEach(s => {
                    tbody.insertAdjacentHTML('beforeend', `
                            <tr>
                                <td>${s.id}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="${s.avatar}" class="rounded-circle me-2" width="40">
                                        <span>${s.name}</span>
                                    </div>
                                </td>
                                <td>${s.class}</td>
                                <td>${s.section}</td>
                                <td>${s.marks}</td>
                                <td>${s.cgpa}</td>
                                <td>
                                    <span class="badge bg-${s.status === 'Pass' ? 'success' : 'danger'}">
                                        ${s.status}
                                    </span>
                                </td>
                            </tr>
                        `);
                });
            };

            // Dropdown filtering
            document.querySelectorAll('.dropdown-menu a').forEach(item => {
                item.addEventListener('click', e => {
                    e.preventDefault();
                    const className = item.textContent.trim();
                    renderStudents(className);
                });
            });

            renderStudents();
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Calendar functionality
            let currentDate = new Date(); // Start with today's date

            // Initialize calendar
            function initCalendar() {
                generateCalendar(currentDate);
                setupEventListeners();
            }

            // Generate calendar for specific month
            function generateCalendar(date) {
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"];

                // Update month display
                document.getElementById('currentMonth').textContent =
                    `${monthNames[date.getMonth()]} ${date.getFullYear()}`;

                // Get calendar data
                const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
                const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDay = firstDay.getDay(); // 0 = Sunday

                // Clear calendar container
                const container = document.getElementById('calendarContainer');
                container.innerHTML = '';

                let dayCounter = 1;
                let html = '';

                // Generate 6 rows (weeks) maximum
                for (let week = 0; week < 6; week++) {
                    html += '<div class="calendar-week">';

                    // 7 days per week
                    for (let dayOfWeek = 0; dayOfWeek < 7; dayOfWeek++) {
                        const dayIndex = week * 7 + dayOfWeek;

                        if (week === 0 && dayOfWeek < startingDay) {
                            // Empty cells before the first day of month
                            html += `
                                    <div class="calendar-day-cell">
                                        <div class="calendar-day empty"></div>
                                    </div>
                                `;
                        } else if (dayCounter > daysInMonth) {
                            // Empty cells after the last day of month
                            html += `
                                    <div class="calendar-day-cell">
                                        <div class="calendar-day empty"></div>
                                    </div>
                                `;
                        } else {
                            // Actual day of the month
                            const today = new Date();
                            const isToday = today.getDate() === dayCounter &&
                                today.getMonth() === date.getMonth() &&
                                today.getFullYear() === date.getFullYear();

                            const dayClass = isToday ? 'today' : '';
                            const dayDate = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(dayCounter).padStart(2, '0')}`;

                            html += `
                                    <div class="calendar-day-cell">
                                        <div class="calendar-day ${dayClass}" 
                                            data-day="${dayCounter}" 
                                            data-date="${dayDate}">
                                            ${dayCounter}
                                        </div>
                                    </div>
                                `;

                            dayCounter++;
                        }
                    }

                    html += '</div>';

                    // Stop if we've displayed all days
                    if (dayCounter > daysInMonth) {
                        break;
                    }
                }

                container.innerHTML = html;

                // Add click events to all calendar days
                document.querySelectorAll('.calendar-day:not(.empty)').forEach(day => {
                    day.addEventListener('click', function () {
                        selectDay(this);
                    });
                });
            }

            // Select a day
            function selectDay(element) {
                // Remove selected class from all days
                document.querySelectorAll('.calendar-day').forEach(day => {
                    day.classList.remove('selected');
                });

                // Add selected class to clicked day
                element.classList.add('selected');

                // Get selected date
                const selectedDate = element.dataset.date;
                console.log('Selected date:', selectedDate);

                // Optional: Show events for selected date
                // showEventsForDate(selectedDate);
            }

            // Setup event listeners for navigation
            function setupEventListeners() {
                const prevBtn = document.getElementById('prevMonth');
                const nextBtn = document.getElementById('nextMonth');

                if (prevBtn) {
                    prevBtn.addEventListener('click', function () {
                        // Go to previous month
                        currentDate.setMonth(currentDate.getMonth() - 1);
                        generateCalendar(currentDate);
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', function () {
                        // Go to next month
                        currentDate.setMonth(currentDate.getMonth() + 1);
                        generateCalendar(currentDate);
                    });
                }
            }

            // Sample events data (optional - for showing dots on days with events)
            const sampleEvents = [
                { date: '2024-05-16', title: 'Team Meeting', color: 'danger' },
                { date: '2024-05-20', title: 'Project Deadline', color: 'primary' },
                { date: '2024-05-25', title: 'Conference', color: 'success' }
            ];

            // Function to mark days with events (optional feature)
            function markDaysWithEvents(events) {
                events.forEach(event => {
                    const eventDate = new Date(event.date);
                    if (eventDate.getMonth() === currentDate.getMonth() &&
                        eventDate.getFullYear() === currentDate.getFullYear()) {

                        const dayElement = document.querySelector(`.calendar-day[data-date="${event.date}"]`);
                        if (dayElement) {
                            // Add a small indicator dot
                            const indicator = document.createElement('div');
                            indicator.className = 'event-indicator';
                            indicator.style.backgroundColor = getColorFromName(event.color);
                            dayElement.appendChild(indicator);
                        }
                    }
                });
            }

            function getColorFromName(colorName) {
                const colors = {
                    'primary': '#0d6efd',
                    'danger': '#dc3545',
                    'success': '#198754',
                    'warning': '#ffc107',
                    'info': '#0dcaf0'
                };
                return colors[colorName] || '#0d6efd';
            }

            // Initialize the calendar
            initCalendar();

            // Uncomment to show event indicators
            // setTimeout(() => markDaysWithEvents(sampleEvents), 100);
        });
          function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#4CAF50' : '#f44336'};
                color: white;
                padding: 12px 24px;
                border-radius: 6px;
                font-weight: 500;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function () {

                document.getElementById('modalTitle').innerText = this.dataset.title;

                document.getElementById('modalMeta').innerHTML = `
                    <b>Recipient:</b> ${this.dataset.recipient} <br>
                    <b>Date:</b> ${this.dataset.date}
                `;

                document.getElementById('modalContent').innerHTML = this.dataset.content;

                if (this.dataset.attachment) {
                    document.getElementById('modalAttachment').innerHTML = `
                        <a href="${this.dataset.attachment}" target="_blank">
                            <i class="fas fa-paperclip"></i> View Attachment
                        </a>
                    `;
                } else {
                    document.getElementById('modalAttachment').innerHTML = '';
                }

                document.getElementById('viewNoticeModal').style.display = 'block';
            });
        });

        function closeNoticeModal() {
            document.getElementById('viewNoticeModal').style.display = 'none';
        }
        
    </script>
    @endsection