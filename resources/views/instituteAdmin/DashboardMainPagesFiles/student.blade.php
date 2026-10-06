@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Flatpickr for datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary: #5d87ff;
            --success: #2bbe3b;
            --warning: #ffae1f;
            --danger: #fa896b;
            --info: #539bff;
            --dark: #2a3547;
            --light: #f6f9fc;
            --gray: #5a6a85;
            --light-gray: #eef2f7;
        }

        .content {
            padding: 20px;
        }

        .page-title {
            color: #2a3547;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item a {
            color: #5d87ff;
            text-decoration: none;
        }

        .card-custom {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(31, 45, 65, 0.15);
            background: white;
            padding: 20px;
            height: 100%;
        }

        .card-custom h6 {
            color: #5a6a85;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .wallet-logo {
            height: 40px;
            margin-bottom: 10px;
        }

        .wallet-balance {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2bbe3b;
        }

        .smart-card {
            background: linear-gradient(135deg, #5d87ff 0%, #539bff 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            height: 100%;
            box-shadow: 0 5px 15px rgba(93, 135, 255, 0.3);
        }

        .smart-card h6 {
            color: white;
            opacity: 0.9;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .loan-card {
            background: linear-gradient(135deg, #2a3547 0%, #3a4457 100%);
            color: white;
        }

        .loan-card h6,
        .loan-card p {
            color: white;
        }

        .bg-dark {
            background: linear-gradient(135deg, #2a3547 0%, #3a4457 100%) !important;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-xxl {
            width: 80px;
            height: 80px;
        }

        .badge {
            font-weight: 500;
            /*padding: 5px 10px;*/
            /*font-size: 12px;*/
        }

        .bg-transparent-primary {
            background-color: rgba(93, 135, 255, 0.1);
            color: #5d87ff;
        }

        .badge-soft-success {
            background-color: rgba(19, 222, 185, 0.1);
            color: #2bbe3b;
        }

        .badge-soft-warning {
            background-color: rgba(255, 174, 31, 0.1);
            color: #ffae1f;
        }

        .bg-success {}

        .badge-soft-danger {
            background-color: rgba(250, 137, 107, 0.1);
            color: #fa896b;
        }

        .bg-success-transparent {
            background-color: rgba(19, 222, 185, 0.1);
        }

        .bg-info-transparent {
            background-color: rgba(83, 155, 255, 0.1);
        }

        .bg-danger-transparent {
            background-color: rgba(250, 137, 107, 0.1);
        }

        .bg-skyblue-transparent {
            background-color: rgba(135, 206, 235, 0.1);
        }

        .bg-warning-transparent {
            background-color: rgba(255, 174, 31, 0.1);
        }

        .bg-primary-transparent {
            background-color: rgba(93, 135, 255, 0.1);
        }

        .class-datepick {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 5px 10px;
        }

        .class-datepick input {
            width: 120px;
            text-align: center;
            font-weight: 500;
            border: none;
            background: transparent;
        }

        .animate-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            border-bottom: 3px solid;
        }

        .animate-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .border-success {
            border-color: #2bbe3b !important;
        }

        .border-primary {
            border-color: #5d87ff !important;
        }

        .border-warning {
            border-color: #ffae1f !important;
        }

        .circle-progress {
            width: 60px;
            height: 60px;
            position: relative;
        }

        .circle-progress .progress-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 14px;
            font-weight: 600;
        }

        .progress-left {
            left: 0;
        }

        .progress-right {
            right: 0;
        }

        .progress-bar {
            width: 100%;
            height: 100%;
            background: none;
            border-width: 6px;
            border-style: solid;
            position: absolute;
            top: 0;
            border-radius: 50%;
        }

        .student-card-bg {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
            border-radius: 10px;
            z-index: 0;
        }

        .student-card-bg div {
            position: absolute;
            opacity: 0.1;
        }

        .card-header {
            border-bottom: 1px solid #eef2f7;
            background: white;
            padding: 1rem 1.5rem;
        }

        .card-title {
            font-weight: 600;
            margin: 0;
            font-size: 1.1rem;
        }

        .dropdown-toggle {
            text-decoration: none;
            color: #5d87ff;
            font-weight: 500;
            cursor: pointer;
            font-size: 14px;
        }

        .dropdown-menu {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
            font-size: 14px;
        }

        .todo-strike-content h6 {
            text-decoration: line-through;
            color: #a0aec0;
        }

        .form-check-input:checked {
            background-color: #2bbe3b;
            border-color: #2bbe3b;
        }

        .text-gray-1 {
            color: #a0aec0;
        }

        .text-gray-2 {
            color: #718096;
        }

        .bg-light-300 {
            background-color: #f8fafc;
        }

        .bg-skyblue {
            background-color: #87ceeb;
        }

        .badge-soft-skyblue {
            background-color: rgba(135, 206, 235, 0.1);
            color: #5bc0de;
        }

        /* ===== Credit Limit, Wallet, Smart Card, Loan Card Section ===== */

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            padding: 20px;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Smart Card Styling */
        .smart-card {
            background: #000;
            color: #fff;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            height: 180px;
        }

        .smart-card p {
            margin: 0;
        }

        /* Wallet Styling */
        .wallet-logo {
            height: 24px;
            margin-right: 8px;
        }

        .wallet-info {
            font-size: 14px;
        }

        .wallet-balance {
            font-size: 16px;
            font-weight: 600;
            color: #007bff;
        }

        /* Loan Card Styling */
        .loan-card h6 {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .loan-card p {
            margin-bottom: 2px;
            font-size: 14px;
        }

        .amount {
            font-weight: 700;
            color: #000;
            font-size: 18px;
        }

        /* Responsive */
        @media (max-width: 991px) {

            .card-custom,
            .smart-card {
                height: auto;
                margin-bottom: 15px;
            }
        }

        #creditGauge {
            /* height: 120px !important;
            width: 100% !important; */
            max-height: 130px;
        }

        /* #attendance_chart {
            height: 100px !important;
            width: 100% !important;
        } */

        #performance_chart {
            height: 300px !important;
            width: 100% !important;
        }

        #exam-result-chart{
            height: 300px !important;
            width: 100% !important;
        }

        /* Chart containers */
        .attendance-chart-container {
            height: 100px;
        }

        .performance-chart-container {
            height: 300px;
        }

        .exam-chart-container {
            height: 300px;
        }

        /* Wallet logo placeholder */
        .wallet-logo-placeholder {
            width: 150px;
            height: 40px;
            background: linear-gradient(90deg, #5d87ff, #539bff);
            border-radius: 5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        /* Faculty slider */
        .slide-nav button {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #5d87ff;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Homework list */
        .homework-item {
            padding: 15px 0;
            border-bottom: 1px solid #eef2f7;
        }

        .homework-item:last-child {
            border-bottom: none;
        }

        /* Fees list */
        .fees-item {
            padding: 12px 0;
            border-bottom: 1px solid #eef2f7;
        }

        .fees-item:last-child {
            border-bottom: none;
        }

        /* Notice list */
        .notice-item {
            margin-bottom: 20px;
        }

        .notice-item:last-child {
            margin-bottom: 0;
        }

        /* Syllabus progress */
        .syllabus-item {
            margin-bottom: 15px;
        }

        .syllabus-item:last-child {
            margin-bottom: 0;
        }

        /* Todo list */
        .todo-item {
            padding: 12px 0;
            border-bottom: 1px solid #eef2f7;
        }

        .todo-item:last-child {
            border-bottom: none;
        }

        /* Leave items */
        .leave-item {
            margin-bottom: 15px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 10px;
        }

        .leave-item:last-child {
            margin-bottom: 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }

            .card-custom,
            .smart-card {
                padding: 15px;
            }

            #performance_chart {
                height: 200px !important;
            }
        }

        /* Credit gauge specific */
        .credit-gauge-container {
            position: relative;
            height: 150px;
            margin: 10px 0;
        }

        .credit-gauge-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .credit-gauge-text h4 {
            font-weight: 700;
            color: #2a3547;
            margin: 0;
        }

        .credit-gauge-text small {
            color: #5a6a85;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        .slider-wrapper {
            overflow: hidden;
        }

        #facultySlider {
            display: flex;
            flex-wrap: nowrap;
            transition: transform 0.45s ease-in-out;
        }

        .faculty-item {
            flex: 0 0 0 25%;
        }

        .class-lectures{
            /* max-height: 551px; */
            height: 470px;
            overflow-y: scroll;
        }

        .class-schedules, .class-homeworks, .student-leaves, .student-fees, .student-notice-board, .student-syllabus{
            max-height: 400px;
            overflow-y: scroll;
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Page Header -->
        <!--<div class="d-md-flex d-block align-items-center justify-content-between mb-3">-->
        <!--    <div class="my-auto mb-2">-->
        <!--        <h3 class="page-title mb-1">Student Dashboard</h3>-->
        <!--        <nav>-->
        <!--            <ol class="breadcrumb mb-0">-->
        <!--                <li class="breadcrumb-item">-->
        <!--                    <a href="index.html">Dashboard</a>-->
        <!--                </li>-->
        <!--                <li class="breadcrumb-item active" aria-current="page">-->
        <!--                    Student Dashboard-->
        <!--                </li>-->
        <!--            </ol>-->
        <!--        </nav>-->
        <!--    </div>-->
        <!--</div>-->
        <!-- /Page Header -->

        <!-- Top Cards -->
        <div class="row g-3 mb-4" style="display: none;">
            <!-- Credit Limit -->
            <div class="col-md-6">
                <div class="card card-custom text-center">
                    <h6>Credit Limit</h6>
                    <canvas id="creditGauge" class="credit-chart"></canvas>
                </div>
            </div>

            <!-- Wallet -->
            <div class="col-md-6">
                <div class="card card-custom">
                    <h5>
                        <img
                            src="/public/image/campusduniaLogo.png"
                            class="wallet-logo" />
                    </h5>
                    <h6 class="mb-2">CampusDunia Wallet</h6>
                    <div class="wallet-info mt-3">
                        <p>
                            Wallet ID :
                            <span class="text-primary fw-semibold">WLT123456789</span>
                        </p>
                        <p>
                            Wallet Balance :
                            <span class="wallet-balance">₹5000</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Smart Card -->
            <div class="col-md-6">
                <div class="smart-card">
                    <h6>Smart Card</h6>
                    <div>
                        <p style="letter-spacing: 2px">
                            4629 5289 0000 0228
                        </p>
                        <p>Valid Thru <strong>06/29</strong></p>
                        <p class="fw-bold">TARUN DHIMAN</p>
                    </div>
                </div>
            </div>

            <!-- Loan Card -->
            <div class="col-md-6">
                <div class="card card-custom loan-card">
                    <h6>Loan Card</h6>
                    <p class="fw-semibold mb-1">TARUN DHIMAN</p>
                    <p class="text-primary fw-bold mb-1">Personal Loan</p>
                    <p>XXXXXXXXXX1999</p>
                    <p class="text-secondary">@10%</p>
                    <div
                        class="d-flex justify-content-between align-items-center mt-2">
                        <span>Outstanding Amount</span>
                        <p class="amount">₹500000</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile -->
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-dark position-relative mb-3">
                    <div class="card-body position-relative d-flex justify-content-between">
                        <div class="d-flex align-items-center row-gap-3">
                            <div class="avatar avatar-xxl rounded flex-shrink-0 me-3">
                                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #5D87FF, #539BFF); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px;">
                                PS
                                </div>
                            </div>
                            <div class="d-block">
                                <span class="badge bg-transparent-primary text-primary mb-1">#{{$student->registration_number}}</span>

                                @if($student->suspend_status == 'suspended')
                                    <span class="badge bg-warning text-dark">
                                        Suspended
                                    </span>
                                @elseif(!empty($student->student_status))
                                    <span class="badge
                                        @if(strtolower($student->student_status) == 'active')
                                            bg-success
                                        @elseif(strtolower($student->student_status) == 'inactive')
                                            bg-danger
                                        @else
                                            bg-secondary
                                        @endif">
                                        {{ ucfirst($student->student_status) }}
                                    </span>
                                @endif
                                
                                <h3 class="text-truncate text-white mb-1">{{$student->first_name}} {{$student-> middle_name}} {{$student-> last_name}}</h3>
                                <div class="d-flex align-items-center flex-wrap row-gap-2 text-gray-2">
                                <span class="border-end me-2 pe-2">
                                Course : {{ $courseType ?? 'N/A'}}
                                </span>
                            <span>
                            {{ $courseSubtype ?? 'N/A' }}
                            </span>
                            </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between profile-footer flex-wrap row-gap-3 pt-3">
                            <div class="d-flex align-items-center">
                            </div>
                            <a href="{{ url('student/profile-settings') }}" class="btn btn-primary" style="position: relative; z-index: 10;"> Edit Profile </a>
                        </div>
                        <div class="student-card-bg">
                            <div style="top: 20px; right: 20px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);"></div>
                            <div style="bottom: 20px; right: 40px; width: 80px; height: 80px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);"></div>
                            <div style="top: 50%; left: 20px; width: 60px; height: 60px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);"></div>
                            <div style="bottom: 40px; left: 40px; width: 70px; height: 70px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile -->

        <!-- Class Faculties -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Class Faculties</h4>
                        <div class="slide-nav text-end d-flex">
                            <button class="btn btn-sm btn-primary me-2 faculty-prev"><i class="ti ti-chevron-left"></i></button>
                            <button class="btn btn-sm btn-primary faculty-next"><i class="ti ti-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="card-body slider-wrapper">
                        <div class="row g-3" id="facultySlider"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Class Faculties -->

        <div class="row">
            <div class="col-md-12 d-flex">
                <div class="row flex-fill">
                    <!-- Today's Class -->
                    <div class="col-md-6 d-flex">
                        <div class="flex-fill">
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h4 class="card-title">Today's Class</h4>

                                    <div class="d-inline-flex align-items-center class-datepick">
                                        <span class="icon" id="prevDay">
                                            <i class="ti ti-chevron-left me-2"></i>
                                        </span>

                                        <input type="text"
                                            class="form-control border-0"
                                            value="{{ \Carbon\Carbon::parse($date)->format('d M Y') ?? 'N/A'}} "
                                            readonly>

                                        <span class="icon" id="nextDay">
                                            <i class="ti ti-chevron-right"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body class-lectures">
                                    @forelse ($todayClasses as $class)
                                        <div class="card mb-3">
                                            <div class="d-flex align-items-center p-3">
                                                <div class="avatar avatar-lg flex-shrink-0 rounded me-3"
                                                    style="background: linear-gradient(135deg, #5d87ff, #539bff); color:white; display:flex; align-items:center; justify-content:center;">
                                                    <i class="ti ti-book"></i>
                                                </div>
    
                                                <div>
                                                    <h6 class="mb-1">{{ $class->subject_name }}</h6>
                                                    <span>
                                                        <i class="ti ti-clock me-2"></i>
                                                        {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    <div class="text-center text-muted p-3">
                                        No classes scheduled for this date
                                    </div>
                                    @endforelse

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Today's Class -->

                    <!-- Attendance -->
                    <div class="col-md-6 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h4 class="card-title">Attendance</h4>

                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle p-2" data-bs-toggle="dropdown">
                                        <span><i class="ti ti-calendar-due"></i></span>
                                        This Month
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="list-unstyled m-0">
                                            <li><a class="dropdown-item" href="#" data-period="week">This Week</a></li>
                                            <li><a class="dropdown-item" href="#" data-period="last-week">Last Week</a></li>
                                            <li><a class="dropdown-item" href="#" data-period="month">This Month</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="attendance-chart">
                                    <!-- Total Working Days -->
                                    <p class="mb-3">
                                        <i class="ti ti-calendar-heart text-primary me-2"></i>
                                        Total working days this month:
                                        <span class="fw-medium text-dark">{{ $totalDaysInMonth }} Days</span>
                                    </p>
                                    <!-- Status Summary -->
                                    <div class="border rounded p-2 mb-1">
                                        <div class="row">
                                            <div class="col text-center border-end">
                                                <p class="mb-1">Present</p>
                                                <h5>{{ $present }}</h5>
                                            </div>
                                            <div class="col text-center border-end">
                                                <p class="mb-1">Absent</p>
                                                <h5>{{ $absent }}</h5>
                                            </div>
                                            <div class="col text-center">
                                                <p class="mb-1">Leave</p>
                                                <h5>{{ $leave }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attendance Chart -->
                                    <div class="attendance-chart-container mb-3" style="height:215px">
                                        <canvas id="attendance_chart"></canvas>
                                    </div>

                                    <!-- Last 7 Days -->
                                    <div class="bg-light-300 rounded border p-3 mb-0">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-1">
                                            <h6 class="mb-2">Last 7 Days</h6>
                                            <p class="fs-12 mb-2">
                                                {{ now()->subDays(6)->format('d M Y') }} -
                                                {{ now()->format('d M Y') }}
                                            </p>
                                        </div>

                                        <div class="d-flex align-items-center rounded gap-1 flex-wrap">
                                            @foreach($last7Days as $day)
                                            @php
                                            $badgeClass = match($day['status']) {
                                            'Present' => 'bg-success text-white',
                                            'Absent' => 'bg-danger text-white',
                                            'Leave' => 'bg-warning text-dark',
                                            'Pending' => 'bg-white border text-muted',
                                            default => 'bg-white border text-muted'
                                            };
                                            @endphp

                                            <span class="badge badge-lg {{ $badgeClass }}"
                                                title="{{ $day['status'] }}">
                                                {{ strtoupper(\Carbon\Carbon::parse($day['date'])->format('D')[0]) }}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Attendance -->
                </div>
            </div>
        </div>

        <!-- /Quick Links -->
         <div class="row my-3">
            <!--<div class="col-md-12 d-flex">-->
                <div class="col-md-3">
                    <a href="{{ url('/fee-structure-for-students') }}" class="card border-0 border-bottom border-primary border-2 animate-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-md rounded bg-primary me-2">
                                    <i class="ti ti-report-money fs-16 text-white"></i>
                                </span>
                                <h6 class="mb-0">Pay Fees</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="/student/exam-schedule" class="card border-0 border-bottom border-success animate-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-md rounded bg-success me-2">
                                    <i class="ti ti-hexagonal-prism-plus fs-16 text-white"></i>
                                </span>
                                <h6 class="mb-0">Exam Result</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="/student/timetable" class="card border-0 border-bottom border-warning animate-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-md rounded bg-warning me-2">
                                    <i class="ti ti-calendar fs-16 text-white"></i>
                                </span>
                                <h6 class="mb-0">Timetable</h6>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="{{ url('/student/attendance') }}" class="card border-0 border-bottom border-dark border-2 animate-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-md rounded bg-dark me-2">
                                    <i class="ti ti-calendar-share fs-16 text-white"></i>
                                </span>
                                <h6 class="mb-0">Attendance</h6>
                            </div>
                        </div>
                    </a>
                </div>
            <!--</div>-->
        </div>
        <!-- Quick Links -->

        <!-- Schedules -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Schedules</h4>
                        <a href="#" class="link-primary fw-medium me-2 d-none" data-bs-toggle="modal" data-bs-target="#add_exam_schedule">
                            <i class="ti ti-square-plus me-1"></i>Add New
                        </a>
                    </div>
                    <div class="card-body pb-0 class-schedules">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="scheduleDate" value="16 May 2024" readonly>
                        </div>
                        <h5 class="mb-3">Exams</h5>
                        <div class="p-3 pb-0 mb-3 border rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-3">1st Quarterly</h5>
                                <span class="badge badge-soft-danger d-inline-flex align-items-center mb-3">
                                    <i class="ti ti-clock me-1"></i>19 Days More
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mb-3">
                                    <h6 class="mb-1">Mathematics</h6>
                                    <p class="mb-0"><i class="ti ti-clock me-1"></i>01:30 - 02:15 PM</p>
                                </div>
                                <div class="mb-3 text-end">
                                    <p class="mb-1"><i class="ti ti-calendar-bolt me-1"></i>06 May 2024</p>
                                    <p class="text-primary mb-0">Room No : 15</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 pb-0 mb-3 border rounded">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-3">2nd Quarterly</h5>
                                <span class="badge badge-soft-danger d-inline-flex align-items-center mb-3">
                                    <i class="ti ti-clock me-1"></i>20 Days More
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mb-3">
                                    <h6 class="mb-1">Physics</h6>
                                    <p class="mb-0"><i class="ti ti-clock me-1"></i>01:30 - 02:15 PM</p>
                                </div>
                                <div class="mb-3 text-end">
                                    <p class="mb-1"><i class="ti ti-calendar-bolt me-1"></i>07 May 2024</p>
                                    <p class="text-primary mb-0">Room No : 15</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Schedules -->

        <div class="row my-3">
            <!-- Performance -->
            <div class="col-md-12 mb-2 d-none mb-3">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Performance</h4>
                        <div class="dropdown">
                            <a href="#" class="bg-white dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ti ti-calendar me-2"></i>2024 - 2025
                            </a>
                            <ul class="dropdown-menu mt-2 p-3">
                                <li><a class="dropdown-item rounded-1" href="#" data-year="2024-2025">2024 - 2025</a></li>
                                <li><a class="dropdown-item rounded-1" href="#" data-year="2023-2024">2023 - 2024</a></li>
                                <li><a class="dropdown-item rounded-1" href="#" data-year="2022-2023">2022 - 2023</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="performance-chart-container">
                            <canvas id="performance_chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Performance -->

            <!-- Home Works -->
            <div class="col-md-12 d-flex">
                <div class="card flex-fill shadow-sm">
                    <!-- Header -->
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Assignments</h4>

                        <!-- Subject Filter -->
                        <div class="dropdown">
                            <a href="#"
                                class="btn btn-sm btn-outline-primary dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <i class="ti ti-book-2 me-1"></i>
                                <span id="selectedSubject">All Subjects</span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end mt-2 p-2">
                                <li>
                                    <a class="dropdown-item subject-filter active"
                                        href="#"
                                        data-subject="">
                                        All Subjects
                                    </a>
                                </li>

                                @foreach($subjects as $subject)
                                <li>
                                    <a class="dropdown-item subject-filter"
                                        href="#"
                                        data-subject="{{ strtolower($subject->subject_name) }}">
                                        {{ $subject->subject_name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body py-3 class-homeworks">
                        <div class="homework-list" id="homeworkList">
                            @forelse($assignments as $hw)
                            <div class="card homework-item mb-3 border-0 shadow-sm"
                                data-subject="{{ strtolower($hw->subject_name) }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-semibold mb-1">{{ $hw->title }}</h6>
                                            <p class="text-muted mb-2">
                                                {{ $hw->description }}
                                            </p>
                                        </div>

                                        <span class="badge bg-light text-dark">
                                            {{ $hw->subject_name }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            Due:
                                            <strong>
                                                {{ \Carbon\Carbon::parse($hw->due_date)->format('d M Y') }}
                                            </strong>
                                        </small>

                                        <span class="badge
                                                    {{ \Carbon\Carbon::parse($hw->due_date)->isPast()
                                                        ? 'bg-danger'
                                                        : 'bg-success' }}">
                                            {{ \Carbon\Carbon::parse($hw->due_date)->isPast()
                                                        ? 'Overdue'
                                                        : 'Upcoming' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-notes-off fs-2 d-block mb-2"></i>
                                No homework available
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Home Works -->
        </div>

        <div class="row my-3">
            <!-- Leave Status -->
            <div class="col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Leave Status</h4>
                        <div class="dropdown">
                            <a href="#" class="bg-white dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ti ti-calendar me-2"></i>This Month
                            </a>
                            <ul class="dropdown-menu mt-2 p-3">
                                <li><a class="dropdown-item rounded-1" href="#" data-period="month">This Month</a></li>
                                <li><a class="dropdown-item rounded-1" href="#" data-period="year">This Year</a></li>
                                <li><a class="dropdown-item rounded-1" href="#" data-period="week">Last Week</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body student-leaves">
                        <div class="leave-list">
                            @forelse ($studentLeaves as $leave)
                                @php
                                    // status badge
                                    $badgeClass = match(strtolower($leave->status)) {
                                        'approved' => 'bg-success',
                                        'pending'  => 'bg-skyblue',
                                        'rejected', 'declined' => 'bg-danger',
                                        default    => 'bg-secondary',
                                    };
    
                                    // icon & color (customize as needed)
                                    $icon = match(strtolower($leave->leave_type)) {
                                        'medical leave' => 'ti-medical-cross',
                                        default => 'ti-brand-socket-io',
                                    };
    
                                    $color = match(strtolower($leave->leave_type)) {
                                        'medical leave' => 'info',
                                        default => 'danger',
                                    };
                                @endphp
    
                                <div class="leave-item mb-3">
                                    <div class="d-sm-flex align-items-center justify-content-between">
    
                                        <div class="d-flex align-items-center mb-2 mb-sm-0">
                                            <div class="avatar avatar-lg bg-{{ $color }}-transparent flex-shrink-0 me-2">
                                                <i class="ti {{ $icon }}"></i>
                                            </div>
    
                                            <div>
                                                <h6 class="mb-1">{{ $leave->leave_type }}</h6>
                                                <p class="mb-0">
                                                    Date :
                                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
    
                                        <span class="badge {{ $badgeClass }} d-inline-flex align-items-center">
                                            <i class="ti ti-circle-filled fs-5 me-1"></i>
                                            {{ ucfirst($leave->status) }}
                                        </span>
    
                                    </div>
                                </div>
    
                            @empty
                                <div class="text-center text-muted py-3">
                                    <i class="ti ti-calendar-off fs-2 d-block mb-2"></i>
                                    No leave records available
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Leave Status -->

            <!-- Exam Result -->
            <div class="col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Exam Result</h4>
                        <div class="dropdown">
                            <a href="#" class="bg-white dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ti ti-calendar me-2"></i>1st Quarter
                            </a>
                            <ul class="dropdown-menu mt-2 p-3">
                                <li><a class="dropdown-item rounded-1" href="#" data-quarter="1">1st Quarter</a></li>
                                <li><a class="dropdown-item rounded-1" href="#" data-quarter="2">2nd Quarter</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- <div class="d-flex align-items-center flex-wrap mb-3">
                            <span class="badge badge-soft-success badge-md me-1 mb-2">Phy: 92</span>
                            <span class="badge badge-soft-warning badge-md me-1 mb-2">Che : 90</span>
                            <span class="badge badge-soft-danger badge-md mb-2">Eng : 80</span>
                            <span class="badge badge-soft-success badge-md me-1 mb-2">Mat : 100</span>
                        </div> -->
                        <div class="exam-chart-container">
                            <canvas id="exam-result-chart"> </canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Exam Result -->

            <!-- Fees Reminder -->
            <div class="col-md-12 mt-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Fees Reminder</h4>
                        <a href="javascript:void(0)" class="link-primary fw-medium disabled">
                            View All
                        </a>
                    </div>
                    <div class="card-body py-2 student-fees">
                        <div class="fees-list">
                            @forelse($studentFees as $fee)
                                @php
                                    $statusConfig = [
                                        'paid' => ['class' => 'bg-success', 'label' => 'Paid'],
                                        'partial' => ['class' => 'bg-warning text-dark', 'label' => 'Partial'],
                                        'pending' => ['class' => 'bg-danger', 'label' => 'Pending'],
                                        'unpaid' => ['class' => 'bg-secondary', 'label' => 'Unpaid'],
                                    ];

                                    $status = $statusConfig[$fee->payment_status] ?? [
                                        'class' => 'bg-light text-dark',
                                        'label' => ucfirst($fee->payment_status)
                                    ];
                                @endphp

                                <div class="d-flex align-items-center justify-content-between border-bottom py-2">
                                    <div>
                                        <h6 class="mb-0">{{ $fee->fee_type }}</h6>
                                        <small class="text-muted">
                                            {{ ucfirst(str_replace('_',' ', $fee->fee_duration)) }}
                                            • Due {{ \Carbon\Carbon::parse($fee->due_date)->format('d M Y') }}
                                        </small>
                                    </div>

                                    <div class="text-end">
                                        <h6 class="mb-1">₹{{ number_format($fee->fee, 2) }}</h6>
                                        <span class="badge {{ $status['class'] }}">
                                            {{ $status['label'] }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted mb-0">
                                    No fee records available 🎉
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Fees Reminder -->
        </div>

        <div class="row mt-3">
            <!-- Notice Board -->
            <div class="col-md-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Notice Board</h4>
                        <a href="{{ url('/notice-board/view') }}" class="fw-medium">View All</a>
                    </div>
                   <div class="card-body student-notice-board">
                        <div class="notice-list">
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

                                        <!-- Right Badges + Eye Icon -->
                                        <div class="d-flex align-items-center gap-3 mt-2 mt-sm-0">
                                            <!-- Status Badge -->
                                            <span class="badge d-none
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
            <!-- /Notice Board -->

            <!-- Syllabus -->
            <div class="col-md-6 d-flex">
                <div class="card flex-fill ">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Syllabus</h4>
                    </div>
                    <div class="card-body student-syllabus">
                        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                            <i class="ti ti-info-square-rounded me-2 fs-14"></i>
                            <div class="fs-14">
                                These Result are obtained from the syllabus completion on the respective Class
                            </div>
                        </div>
                        <div class="syllabus-progress">
                            <!-- Syllabus items loaded by JS -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Syllabus -->

            <!-- Todo -->
            <div class="col-md-6 d-none">
                <div class="card flex-fill">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Todo</h4>
                        <div class="dropdown">
                            <a href="#" class="bg-white dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ti ti-calendar me-2"></i>Today
                            </a>
                            <ul class="dropdown-menu mt-2 p-3">
                                <li><a class="dropdown-item rounded-1" href="#" data-period="today">Today</a></li>
                                <li><a class="dropdown-item rounded-1" href="#" data-period="month">This Month</a></li>
                                <li><a class="dropdown-item rounded-1" href="#" data-period="week">This Week</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="todo-list">
                            <!-- Todo items loaded by JS -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Todo -->
        </div>
    
    </div>

    <!-- Add Exam Schedule Modal -->
    <div class="modal fade" id="add_exam_schedule" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Exam Schedule</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="examScheduleForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Class</label>
                                            <input type="text" class="form-control" placeholder="Enter Class">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Section</label>
                                            <select class="form-select">
                                                <option>Select</option>
                                                <option>A</option>
                                                <option>B</option>
                                                <option>C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Exam Name</label>
                                            <select class="form-select">
                                                <option>Select</option>
                                                <option>Week Test</option>
                                                <option>Monthly Test</option>
                                                <option>Chapter Wise Test</option>
                                                <option>Unit Test</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Start Time</label>
                                            <select class="form-select">
                                                <option>Select</option>
                                                <option>09:30 AM</option>
                                                <option>10:30 AM</option>
                                                <option>11:00 AM</option>
                                                <option>12:30 PM</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">End Time</label>
                                            <select class="form-select">
                                                <option>Select</option>
                                                <option>10:45 AM</option>
                                                <option>11:00 AM</option>
                                                <option>11:30 AM</option>
                                                <option>12:00 PM</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Duration(min)</label>
                                            <select class="form-select">
                                                <option>Select</option>
                                                <option>3 hrs</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="examScheduleRows">
                            <div class="exam-schedule-row d-flex align-items-center flex-wrap column-gap-3 mb-3">
                                <div class="shedule-info flex-fill">
                                    <div class="mb-3">
                                        <label class="form-label">Exam Date</label>
                                        <select class="form-select">
                                            <option>Select</option>
                                            <option>13 May 2024</option>
                                            <option>14 May 2024</option>
                                            <option>15 May 2024</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="shedule-info flex-fill">
                                    <div class="mb-3">
                                        <label class="form-label">Subject</label>
                                        <select class="form-select">
                                            <option>Select</option>
                                            <option>English</option>
                                            <option>Spanish</option>
                                            <option>Physics</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="shedule-info flex-fill">
                                    <div class="mb-3">
                                        <label class="form-label">Room No</label>
                                        <select class="form-select">
                                            <option>Select</option>
                                            <option>101</option>
                                            <option>103</option>
                                            <option>104</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="shedule-info flex-fill">
                                    <div class="mb-3">
                                        <label class="form-label">Max Marks</label>
                                        <select class="form-select">
                                            <option>Select</option>
                                            <option>100</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="shedule-info flex-fill">
                                    <div class="d-flex align-items-end">
                                        <div class="mb-3 flex-fill">
                                            <label class="form-label">Min Marks</label>
                                            <select class="form-select">
                                                <option>Select</option>
                                                <option>35</option>
                                            </select>
                                        </div>
                                        <div class="mb-3 ms-2">
                                            <button type="button" class="btn btn-danger btn-sm delete-schedule" disabled>
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary add-new-schedule">
                                <i class="ti ti-square-rounded-plus-filled me-2"></i>Add New
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Exam Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Add Exam Schedule Modal -->


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

    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize datepickers
            flatpickr("#classDate", {
                dateFormat: "d M Y",
                defaultDate: "2024-05-16",
                onChange: function(selectedDates, dateStr) {
                    updateClassSchedule(dateStr);
                }
            });

            flatpickr("#scheduleDate", {
                dateFormat: "d M Y",
                defaultDate: "2024-05-16"
            });

            // Date navigation for class schedule
            document.getElementById('prevDay').addEventListener('click', function() {
                const dateInput = document.getElementById('classDate');
                const currentDate = new Date(dateInput.value || new Date());
                currentDate.setDate(currentDate.getDate() - 1);
                dateInput.value = formatDate(currentDate);
                updateClassSchedule(dateInput.value);
            });

            document.getElementById('nextDay').addEventListener('click', function() {
                const dateInput = document.getElementById('classDate');
                const currentDate = new Date(dateInput.value || new Date());
                currentDate.setDate(currentDate.getDate() + 1);
                dateInput.value = formatDate(currentDate);
                updateClassSchedule(dateInput.value);
            });

            function formatDate(date) {
                const options = {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                };
                return date.toLocaleDateString('en-US', options);
            }

            function updateClassSchedule(date) {
                console.log('Updating class schedule for:', date);
            }

            // ===== Gauge Chart for Credit Limit =====
            const ctxGauge = document
                .getElementById("creditGauge")
                .getContext("2d");
            new Chart(ctxGauge, {
                type: "doughnut",
                data: {
                    labels: ["Pending", "Used"],
                    datasets: [{
                        data: [60, 40],
                        backgroundColor: ["#ff5733", "#d3d3d3"],
                        borderWidth: 2,
                        cutout: "75%",
                    }, ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: "bottom"
                        }
                    },
                },
            });

            // Attendance Chart - Fixed height
            const attendanceCtx = document
                .getElementById('attendance_chart')
                .getContext('2d');

            // Attendance summary
            const present = 4;
            const halfday = 1;
            const absent = 1;
            const holiday = 1;

            new Chart(attendanceCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Half-Day', 'Absent', 'Holiday'],
                    datasets: [{
                        data: [present, halfday, absent, holiday],
                        backgroundColor: [
                            '#24be2d', // Present
                            '#0877e2', // Half-Day
                            '#e70742', // Absent
                            '#eef2f7' // Holiday
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%', // makes it hollow
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });

            // Performance Chart - Fixed height
            const performanceCtx = document.getElementById('performance_chart').getContext('2d');
            new Chart(performanceCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Performance',
                        data: [65, 70, 75, 80, 85, 82, 78, 85, 88, 90, 92, 95],
                        borderColor: '#5d87ff',
                        backgroundColor: 'rgba(93, 135, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#5d87ff',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 60,
                            max: 100,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });

            // Exam Result Chart - Fixed height
            const examResultCtx = document.getElementById('exam-result-chart').getContext('2d');

            new Chart(examResultCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Maths', 'Physics', 'Chemistry', 'English', 'Biology', 'Spanish'],
                    datasets: [{
                        data: [100, 92, 90, 80, 85, 75],
                        backgroundColor: [
                            '#0d6efd', // Maths
                            '#198754', // Physics
                            '#ffc107', // Chemistry
                            '#dc3545', // English
                            '#20c997', // Biology
                            '#6f42c1'  // Spanish
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',   // 🔥 Hollow
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 10
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.raw} Marks`;
                                }
                            }
                        }
                    }
                }
            });

            // Load dynamic data
            // function loadDynamicData() {
            //     // Faculty Data
            //     const slider = document.getElementById("facultySlider");
            //     slider.innerHTML = fullList.map(faculty => `
            //         <div class="faculty-item col-md-4">
            //             <div class="card bg-light-300 mb-0 h-100">
            //                 <div class="card-body">
            //                     <div class="d-flex align-items-center mb-3">
            //                         <div class="avatar avatar-lg rounded me-2"
            //                             style="background-color:${faculty.color}20;color:${faculty.color}">
            //                             <i class="ti ti-user fs-24"></i>
            //                         </div>
            //                         <div class="overflow-hidden">
            //                             <h6 class="mb-1 text-truncate">${faculty.name}</h6>
            //                             <p class="text-muted mb-0">${faculty.subject}</p>
            //                         </div>
            //                     </div>
            //                 </div>
            //             </div>
            //         </div>
            //     `).join("");
            // }

            // Modal functionality
            const addExamScheduleBtn = document.querySelector('.add-new-schedule');
            const examScheduleRows = document.getElementById('examScheduleRows');

            if (addExamScheduleBtn) {
                addExamScheduleBtn.addEventListener('click', function() {
                    const newRow = document.createElement('div');
                    newRow.className = 'exam-schedule-row d-flex align-items-center flex-wrap column-gap-3 mb-3';
                    newRow.innerHTML = `
                        <div class="shedule-info flex-fill">
                            <div class="mb-3">
                                <label class="form-label">Exam Date</label>
                                <select class="form-select">
                                    <option>Select</option>
                                    <option>13 May 2024</option>
                                    <option>14 May 2024</option>
                                    <option>15 May 2024</option>
                                </select>
                            </div>
                        </div>
                        <div class="shedule-info flex-fill">
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <select class="form-select">
                                    <option>Select</option>
                                    <option>English</option>
                                    <option>Spanish</option>
                                    <option>Physics</option>
                                </select>
                            </div>
                        </div>
                        <div class="shedule-info flex-fill">
                            <div class="mb-3">
                                <label class="form-label">Room No</label>
                                <select class="form-select">
                                    <option>Select</option>
                                    <option>101</option>
                                    <option>103</option>
                                    <option>104</option>
                                </select>
                            </div>
                        </div>
                        <div class="shedule-info flex-fill">
                            <div class="mb-3">
                                <label class="form-label">Max Marks</label>
                                <select class="form-select">
                                    <option>Select</option>
                                    <option>100</option>
                                </select>
                            </div>
                        </div>
                        <div class="shedule-info flex-fill">
                            <div class="d-flex align-items-end">
                                <div class="mb-3 flex-fill">
                                    <label class="form-label">Min Marks</label>
                                    <select class="form-select">
                                        <option>Select</option>
                                        <option>35</option>
                                    </select>
                                </div>
                                <div class="mb-3 ms-2">
                                    <button type="button" class="btn btn-danger btn-sm delete-schedule">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    examScheduleRows.appendChild(newRow);

                    // Enable delete button on new rows
                    newRow.querySelector('.delete-schedule').addEventListener('click', function() {
                        this.closest('.exam-schedule-row').remove();
                    });
                });
            }

            // Form submission
            const examScheduleForm = document.getElementById('examScheduleForm');
            if (examScheduleForm) {
                examScheduleForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Exam schedule added successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('add_exam_schedule')).hide();
                });
            }

            // Chat button functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.chat-teacher')) {
                    const teacherName = e.target.closest('.chat-teacher').getAttribute('data-name');
                    alert(`Opening chat with ${teacherName}`);
                }
            });

            // Dropdown functionality
            document.querySelectorAll('.dropdown-item[data-period]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const period = this.getAttribute('data-period');
                    const dropdownToggle = this.closest('.dropdown').querySelector('.dropdown-toggle');
                    dropdownToggle.innerHTML = `<i class="ti ti-calendar me-2"></i>${this.textContent}`;
                    console.log('Loading data for period:', period);
                });
            });

            document.querySelectorAll('.dropdown-item[data-year]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const year = this.getAttribute('data-year');
                    const dropdownToggle = this.closest('.dropdown').querySelector('.dropdown-toggle');
                    dropdownToggle.innerHTML = `<i class="ti ti-calendar me-2"></i>${this.textContent}`;
                    console.log('Loading data for year:', year);
                });
            });

            document.querySelectorAll('.dropdown-item[data-subject]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const subject = this.getAttribute('data-subject');
                    const dropdownToggle = this.closest('.dropdown').querySelector('.dropdown-toggle');
                    dropdownToggle.innerHTML = `<i class="ti ti-book-2 me-2"></i>${this.textContent}`;
                    console.log('Filtering homework by subject:', subject);
                });
            });

            document.querySelectorAll('.dropdown-item[data-quarter]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const quarter = this.getAttribute('data-quarter');
                    const dropdownToggle = this.closest('.dropdown').querySelector('.dropdown-toggle');
                    dropdownToggle.innerHTML = `<i class="ti ti-calendar me-2"></i>${this.textContent}`;
                    console.log('Loading exam results for quarter:', quarter);
                });
            });
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
</body>
</html>
@endsection