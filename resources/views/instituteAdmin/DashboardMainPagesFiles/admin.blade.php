@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.css">

    <style>
        :root {
            --primary: #4a6cf7;
            --success: #28a745;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --dark: #343a40;
            --light: #f8f9fa;
            --pink: #e83e8c;
            --purple: #6f42c1;
            --orange: #fd7e14;
            --teal: #20c997;
            --indigo: #6610f2;
        }
        
        .content {
            padding: 20px;
        }
        
        /* Dashboard Welcome Card */
        .card.bg-dark {
            background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%) !important;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }
        
        /* Dashboard Stats Boxes */
        .dashboard-box {
            border: none;
            border-radius: 12px;
            padding: 25px 20px;
            text-align: center;
            transition: transform 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .dashboard-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .dashboard-box i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: block;
        }
        
        .stat-title {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .stat-sub {
            font-size: 13px;
        }
        
        .stat-trend {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
            display: inline-block;
        }
        
        .trend-up {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .trend-down {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .trend-neutral {
            background: rgba(108, 117, 125, 0.1);
            color: var(--dark);
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 20px;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-title {
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        /* Quick Links */
        .quick-link-card {
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .quick-link-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }
        
        /* Charts */
        .chart-container {
            position: relative;
            width: 100%;
        }
        
        /* Event Items */
        .event-item {
            border-left: 4px solid;
            padding-left: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .event-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }
        
        .event-badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        /* Progress Bars */
        .progress {
            height: 8px;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }
            
            .dashboard-box {
                margin-bottom: 15px;
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
        }

        .avatar img{
            width:40px;
        }

        a{
            text-decoration: none;
        }

        a:hover{
            text-decoration: none;
            color: inherit;
        }
        
        /* Custom scrollbar */
        .custom-scroll {
            max-height: 400px;
            overflow-y: auto;
            border-bottom: 2px solid #e2e2e2;
            padding: 10px;
        }
        
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }
        
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        /* Status badges */
        .badge-published {
            background: #28a745;
        }
        
        .badge-draft {
            background: #6c757d;
        }
        
        .badge-pending {
            background: #ffc107;
            color: #212529;
        }
        
        /* Gender colors */
        .male-color {
            color: #17a2b8;
        }
        
        .female-color {
            color: #e83e8c;
        }
        .other-color {
            color: #ffc107;
        }
        
        /* Enhanced Tab System */
        .chart-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-bottom: 20px;
        }
        
        .chart-tab {
            padding: 10px 20px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .chart-tab:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        .chart-tab.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(74, 108, 247, 0.2);
        }
        
        .chart-tab .tab-icon {
            font-size: 16px;
        }
        
        .chart-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }
        
        .chart-content.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Employee Summary Cards */
        .employee-summary-card {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        
        .employee-summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .employee-summary-card.male {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .employee-summary-card.female {
            background: linear-gradient(135deg, #e83e8c 0%, #d63384 100%);
            color: white;
        }
        
        .employee-summary-card.other {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #ffffff;
        }
        
        /* Dynamic Role Cards */
        .teacher-role {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .admin-role {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
        }
        
        .principal-role {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }
        
        .accountant-role {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .receptionist-role {
            background: linear-gradient(135deg, #4a6cf7 0%, #3a5ce5 100%);
            color: white;
        }
        
        .librarian-role {
            background: linear-gradient(135deg, #6f42c1 0%, #5e34b1 100%);
            color: white;
        }
        
        .driver-role {
            background: linear-gradient(135deg, #fd7e14 0%, #e96b00 100%);
            color: white;
        }
        
        .cleaner-role {
            background: linear-gradient(135deg, #20c997 0%, #1ba87e 100%);
            color: white;
        }
        
        .security-role {
            background: linear-gradient(135deg, #343a40 0%, #23272b 100%);
            color: white;
        }
        
        .default-role {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
        }
        
        /* Enhanced Chart Cards */
        .chart-card {
            border: none;
            border-radius: 12px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .chart-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .chart-card .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid var(--primary);
        }
        
        /* Responsive adjustments for tabs */
        @media (max-width: 768px) {
            .chart-tabs {
                flex-direction: column;
            }
            
            .chart-tab {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Statistics Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-item .stat-icon {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .stat-item .stat-number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-item .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Employee Role Breakdown */
        .role-breakdown {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .role-item {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
        }
        
        .role-item .role-count {
            margin-left: 8px;
            font-weight: 600;
            color: var(--primary);
        }
        /* Responsive styles for fee distribution chart */
        .fee-distribution-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .chart-wrapper {
            flex: 1;
            min-width: 0; /* Prevents flex overflow */
        }

        .legend-wrapper {
            flex: 0 0 auto;
            min-width: 200px;
        }

        @media (max-width: 992px) {
            .fee-distribution-container {
                flex-direction: column;
            }
            
            .chart-wrapper {
                width: 100%;
            }
            
            .legend-wrapper {
                width: 100%;
                margin-top: 1rem;
            }
        }

        @media (max-width: 768px) {
            .legend-items {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .legend-items {
                grid-template-columns: 1fr;
            }
        }

        /* Card responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
            
            .legend-item {
                font-size: 0.875rem;
            }
            
            .legend-amount {
                font-size: 0.875rem;
                font-weight: 600;
            }
        }

        /* Loading indicator */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .filter-group {
            display: flex;
            gap: 10px;
        }
        
        .filter-group select {
            min-width: 120px;
        }
        .apexcharts-toolbar {
            right: 10px !important;
            left: auto !important;
        }
        
        .apexcharts-menu-icon{
            display: none;
        }
        
        .clickable-dashboard-card{
            cursor:pointer;
            transition: all 0.3s ease;
        }
        
        .clickable-dashboard-card:hover{
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
    </style>
</head>
<body>
    <div class="content">
        <!-- Dashboard Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card bg-dark">
                    <div class="card-body">
                        <div class="d-flex align-items-xl-center justify-content-xl-between flex-xl-row flex-column">
                            <div class="mb-3 mb-xl-0">
                                <div class="d-flex align-items-center flex-wrap mb-2">
                                    <h1 class="text-white me-2">
                                        Welcome, {{ Auth::user()->name }}
                                    </h1>
                                    <a href="/institute/admin/view-details" class="avatar avatar-sm img-rounded bg-gray-800">
                                        <i class="ti ti-edit text-white"></i>
                                    </a>
                                </div>
                                <p class="text-white mb-0">
                                    <i class="ti ti-calendar me-1"></i>
                                    Today is {{ date('l, F j, Y') }}
                                </p>
                            </div>
                            <div class="text-white text-xl-end">
                                <p class="mb-1">
                                    <a href="/admin/dashboard" style="color:#fff; text-decoration:none;"><i class="ti ti-refresh me-1"></i> Refresh</a>
                                </p>
                                <small class="opacity-75">
                                    Last updated: {{ isset($data['updated_at']) ? date('d M Y, h:i A', strtotime($data['updated_at'])) : '-' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Statistics -->
        <div class="row my-4">
            <!-- Departments -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card dashboard-box clickable-dashboard-card"
                     onclick="window.location.href='https://test.cerebroxtek.com/departments/view-all'">
                    <i class="fas fa-layer-group" style="color:#007bff;"></i>
                    <div class="stat-title">Departments</div>
                    <div class="stat-value">{{ $data['total_departments'] ?? '0' }}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">Active: {{ $data['active_departments'] ?? 0 }}</small>
                        <small class="text-danger">Inactive: {{ $data['inactive_departments'] ?? 0 }}</small>
                    </div>
                </div>
            </div>
            
            <!-- Courses -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card dashboard-box clickable-dashboard-card"
                     onclick="window.location.href='https://test.cerebroxtek.com/institute/admin/view-courses'">
                    <i class="fas fa-book-open" style="color:#ffc107;"></i>
                    <div class="stat-title">Classes</div>
                    <div class="stat-value">{{ $data['total_courses'] ?? '0' }}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">Courses: {{ $data['total_courses'] ?? 0 }}</small>
                        <small class="text-danger">Sections: {{ $data['total_sections'] ?? 0 }}</small>
                    </div>
                </div>
            </div>
            
            <!-- Employees -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card dashboard-box clickable-dashboard-card"
                     onclick="window.location.href='https://test.cerebroxtek.com/institute/admin/addemployees'">
                    <i class="fas fa-users-gear" style="color:#dc3545;"></i>
                    <div class="stat-title">Employees</div>
                    <div class="stat-value">{{ $data['total_employees'] ?? '0' }}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-success">Teaching: {{ $data['teaching_staff'] ?? 0 }}</small>
                        <small class="text-info">Non-teaching: {{ $data['non_teaching_staff'] ?? 0 }}</small>
                    </div>
                </div>
            </div>
            
            <!-- Fee Collection -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card dashboard-box clickable-dashboard-card"
                     onclick="window.location.href='https://test.cerebroxtek.com/admin/fee-analytics'">
                    <i class="fas fa-indian-rupee-sign" style="color:#fd7e14;"></i>
                    <div class="stat-title">Fee Collected</div>
                    <div class="stat-value">₹{{ number_format($data['paid_fee'] ?? 0) }}</div>
                    <div class="stat-sub">
                        <span class="trend-up stat-trend">
                            Collection: {{ $data['collection_rate'] ?? 0 }}%
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Pending Dues -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card dashboard-box clickable-dashboard-card"
                     onclick="window.location.href='https://test.cerebroxtek.com/admin/fee-analytics'">
                    <i class="fas fa-file-invoice-dollar" style="color:#795548;"></i>
                    <div class="stat-title">Pending Dues</div>
                    <div class="stat-value">
                        ₹{{ number_format($data['total_dues'] ?? 0) }}
                    </div>
                    <div class="progress-label">
                        <small>
                            Current: ₹{{ number_format($data['current_dues'] ?? 0) }}
                        </small>
                        <small>
                            Overdue: ₹{{ number_format($data['overdue_dues'] ?? 0) }}
                        </small>
                    </div>
                </div>
            </div>
            
            <!-- Leave Requests -->
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card dashboard-box clickable-dashboard-card"
                     onclick="window.location.href='https://test.cerebroxtek.com/leaves/approvals'">
                    <i class="fas fa-calendar-check" style="color:#e83e8c;"></i>
                    <div class="stat-title">Leave Requests</div>
                    <div class="stat-value">{{ $data['total_leaves'] ?? '0' }}</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-warning">
                            Pending: {{ $data['pending_leaves'] ?? 0 }}
                        </small>
                        <small class="text-success">
                            Today: {{ $data['today_leaves'] ?? 0 }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="row">
            <!-- Left Column: Charts -->
            <div class="col-lg-8">
                <!-- Fee Distribution Chart -->
                <div class="card chart-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Fee Distribution by Type</h4>
                    </div>

                    <div class="card-body">
                        <div class="fee-distribution-container d-flex flex-wrap">

                            <!-- Chart -->
                            <div class="chart-wrapper flex-grow-1">
                                <div id="fee-distribution-chart" style="height: 250px; width: 100%;"></div>
                            </div>

                            <!-- Dynamic Legend -->
                            <div class="legend-wrapper ms-3">
                                <div class="d-flex flex-column justify-content-center h-100">

                                    <div id="custom-legend"></div>

                                    <!-- Total (Mobile Only) -->
                                    <div class="mt-3 pt-2 border-top d-md-none">
                                        <div class="d-flex justify-content-between">
                                            <strong>Total Fee:</strong>
                                            <strong class="text-primary">
                                                ₹{{ number_format(
                                                    ($data['total_course_fee'] ?? 0) + 
                                                    ($data['total_registration_fee'] ?? 0) + 
                                                    ($data['total_transport_fee'] ?? 0) + 
                                                    ($data['total_hostel_fee'] ?? 0) + 
                                                    ($data['total_custom_fee'] ?? 0)
                                                ) }}
                                            </strong>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- DYNAMIC Fee Collection Analytics -->
                <div class="card mb-4 chart-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h4 class="card-title mb-0">Fee Collection Analytics</h4>
                            <div class="filter-group">
                                <select id="reportType" class="form-select form-select-sm">
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                                <select id="feeType" class="form-select form-select-sm">
                                    <option value="all">All Fees</option>
                                    <option value="course">Course Fee</option>
                                    <option value="registration">Registration Fee</option>
                                    <option value="transport">Transport Fee</option>
                                    <option value="hostel">Hostel Fee</option>
                                    <option value="custom">Custom Fee</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Summary Cards -->
                        <div class="row text-center mb-4 g-3">
                            <div class="col-sm-4">
                                <div class="bg-light rounded-3 p-3">
                                    <small class="text-muted d-block">Total Collection</small>
                                    <h5 id="totalCollection" class="mb-0 text-primary fw-bold">₹0</h5>
                                </div>
                            </div>
                            <div class="col-sm-4 d-none">
                                <div class="bg-light rounded-3 p-3">
                                    <small class="text-muted d-block">Paid Students</small>
                                    <h5 id="totalPaid" class="mb-0 text-success fw-bold">0</h5>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="bg-light rounded-3 p-3">
                                    <small class="text-muted d-block">Pending Amount</small>
                                    <h5 id="totalPending" class="mb-0 text-danger fw-bold">₹0</h5>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="bg-light rounded-3 p-3">
                                    <small class="text-muted d-block">Overdue</small>
                                    <h5 id="totalPending" class="mb-0 text-danger fw-bold">₹0</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Loading Indicator -->
                        <div id="chartLoading" class="text-center py-4 d-none">
                            <div class="loading-spinner"></div>
                            <span class="ms-2 text-muted">Loading analytics...</span>
                        </div>

                        <!-- Chart Container -->
                        <div id="feeAnalyticsChart" style="height: 250px; width: 100%;"></div>
                        
                        <!-- No Data Message -->
                        <div id="noDataMessage" class="text-center py-5 d-none">
                            <i class="ti ti-chart-bar-off fs-48 text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">No fee collection data available for the selected filters.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Employee Analytics with Enhanced Tab System -->
                <div class="card mb-4 chart-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Employee Analytics</h4>
                    </div>
                    <div class="card-body">
                        <!-- Tab Navigation -->
                        <div class="chart-tabs mb-4">
                            <div class="chart-tab active" data-tab="gender-distribution">
                                <i class="ti ti-gender-male-female tab-icon"></i>
                                Gender Distribution
                            </div>
                            <div class="chart-tab" data-tab="role-distribution">
                                <i class="ti ti-briefcase tab-icon"></i>
                                Role Distribution
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="chart-content active" id="gender-distribution">
                            <div class="row">
                                <div class="col-md-8">
                                    <div id="employee-gender-chart" style="height: 300px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column justify-content-center h-100">
                                        <h5 class="mb-3">Gender Breakdown</h5>
                                        <div class="employee-summary-card male mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="ti ti-gender-male fs-24"></i>
                                                    <h6 class="mb-0 mt-2">Male</h6>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="mb-0">{{ $data['male_employees'] ?? 0 }}</h3>
                                                    <small>{{ $data['male_employee_percentage'] ?? 0 }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="employee-summary-card female mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="ti ti-gender-female fs-24"></i>
                                                    <h6 class="mb-0 mt-2">Female</h6>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="mb-0">{{ $data['female_employees'] ?? 0 }}</h3>
                                                    <small>{{ $data['female_employee_percentage'] ?? 0 }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="employee-summary-card other">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="ti ti-users fs-24"></i>
                                                    <h6 class="mb-0 mt-2">Other</h6>
                                                </div>
                                                <div class="text-end">
                                                    <h3 class="mb-0">{{ $data['other_employees'] ?? 0 }}</h3>
                                                    <small>{{ $data['other_employee_percentage'] ?? 0 }}%</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="chart-content" id="role-distribution">
                            <div class="row">
                                <div class="col-md-8">
                                    <div id="employee-role-chart" style="height: 300px;"></div>
                                </div>

                                <div class="col-md-4">
                                    <div class="d-flex flex-column justify-content-center h-100">
                                        <h5 class="mb-3">Role Breakdown</h5>
                                        @if(!empty($data['employee_roles']))
                                            @foreach($data['employee_roles'] as $roleKey => $role)
                                                @php
                                                    $roleConfig = $data['role_ui_config'][$roleKey] ?? $data['role_ui_config']['default'];
                                                    $percentage = $data['total_employees'] > 0 ? round(($role['count'] / $data['total_employees']) * 100, 1) : 0;
                                                @endphp
                                                <div class="employee-summary-card {{ $roleConfig['class'] }} mb-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="ti {{ $roleConfig['icon'] }} fs-24"></i>
                                                            <h6 class="mb-0 mt-2">{{ $role['name'] }}</h6>
                                                        </div>
                                                        <div class="text-end">
                                                            <h3 class="mb-0">{{ $role['count'] }}</h3>
                                                            <small>{{ $percentage }}%</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-3">
                                                <i class="ti ti-users fs-48 text-muted mb-2"></i>
                                                <p class="text-muted">No employee roles found</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 chart-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Employee Onboarding Trend</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div id="employee-registrations-chart" style="height: 300px;"></div>
                    </div>
                </div>
                
                <!-- Student Gender Ratio Chart -->
                <div class="card mb-4 chart-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Student Gender Distribution</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div id="gender-chart" style="height: 300px;"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex flex-column justify-content-center h-100">
                                    <div class="mb-4">
                                        <h5 class="mb-1">Total Students</h5>
                                        <h3 class="text-primary">{{ $data['total_students'] ?? 0 }}</h3>
                                        <small class="text-muted">Active: {{ $data['active_students'] ?? 0 }}</small>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="badge bg-info me-2" style="width: 20px; height: 20px;"></div>
                                            <span>Boys</span>
                                            <span class="ms-auto">{{ $data['male_students'] ?? 0 }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="badge bg-pink me-2" style="width: 20px; height: 20px;"></div>
                                            <span>Girls</span>
                                            <span class="ms-auto">{{ $data['female_students'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4 chart-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Student Onboarding Trend</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div id="recent-registrations-chart" style="height: 250px;"></div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Events & Quick Actions -->
            <div class="col-lg-4">
                <!-- Upcoming Events -->
                <div class="card mb-4 chart-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Upcoming Events</h4>
                        <a href="/institute/admin/google/calendar" class="btn btn-sm btn-primary">
                            <!--<i class="ti ti-calendar-plus me-1"></i>-->
                            <i class="fa-regular fa-eye"></i>
                            View
                        </a>
                    </div>
                    <div class="card-body custom-scroll">
                        @if(count($data['all_events']) > 0)
                            @foreach($data['all_events'] as $event)
                            <div class="event-item mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0">{{ $event->title }}</h6>
                                    <span class="badge {{ $event->type == 'Holiday' ? 'bg-danger' : 'bg-primary' }} event-badge">
                                        {{ $event->type }}
                                    </span>
                                </div>
                                <p class="text-muted mb-1">
                                    <i class="ti ti-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                </p>
                                <small class="text-muted">
                                    <i class="ti ti-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($event->end_date)->format('h:i A') }}
                                </small>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-calendar-off fs-48 text-muted mb-3"></i>
                                <p class="text-muted">No upcoming events</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Holidays -->
                <div class="card mb-4 chart-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Holidays</h4>
                        <a href="/institute/admin/google/calendar" class="btn btn-sm btn-primary">
                            <i class="fa-regular fa-eye"></i>
                            <!--<i class="ti ti-calendar-plus me-1"></i>-->
                            View
                        </a>
                    </div>
                    <div class="card-body custom-scroll">
                        @if(count($data['holidays']) > 0)
                            @foreach($data['holidays'] as $event)
                            <div class="event-item mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0">{{ $event->title }}</h6>
                                    <span class="badge {{ strtolower($event->type) == 'Holiday' ? 'bg-success' : 'bg-primary' }} event-badge">
                                        {{ ucfirst($event->type) }}
                                    </span>
                                </div>
                                <p class="text-muted mb-1">
                                    <i class="ti ti-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                </p>
                                <small class="text-muted d-none">
                                    <i class="ti ti-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($event->end_date)->format('h:i A') }}
                                </small>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-calendar-off fs-48 text-muted mb-3"></i>
                                <p class="text-muted">No upcoming holidays</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Today's Attendance -->
                <div class="card chart-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Today's Attendance</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <h5 class="text-success mb-1">{{ $data['student_present'] ?? 0 }}</h5>
                                    <small class="text-muted">Students Present</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="text-danger mb-1">{{ $data['student_absent'] ?? 0 }}</h5>
                                <small class="text-muted">Students Absent</small>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h5 class="text-success mb-1">{{ $data['employee_present'] ?? 0 }}</h5>
                                    <small class="text-muted">Staff Present</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="text-danger mb-1">{{ $data['employee_absent'] ?? 0 }}</h5>
                                <small class="text-muted">Staff Absent</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="/institute/admin/monthly-attendance" class="btn btn-outline-primary w-100">
                                <i class="ti ti-calendar-share me-1"></i>View Detailed Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Employee Leave requests -->
                <div class="card mb-4 chart-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Leave Requests</h4>
                        <a href="{{ route('leaves.approvals') }}" class="btn btn-sm btn-primary">
                            <i class="fa-regular fa-eye"></i>
                            View All
                        </a>
                    </div>

                    <div class="card-body custom-scroll">
                        @if($data['get_leave']->count() > 0)
                            @foreach($data['get_leave'] as $leave)
                                <div class="leave-item mb-3 border-bottom pb-2">
                                    <div class="d-flex justify-content-between align-items-start mb-1 gap-2">
                                        <h6 class="mb-0">
                                            @if(isset($leave->employee))
                                                {{ $leave->employee->name }}
                                                <span class="badge bg-info" style="font-size: 0.65em !important;">Employee</span>
                                            @elseif(isset($leave->student))
                                                {{ $leave->student->name }}
                                                <span class="badge bg-secondary" style="font-size: 0.65em !important;">Student</span>
                                            @else
                                                N/A
                                            @endif
                                        </h6>
                                        
                                        <div style="text-align: end; display: block;">
                                            <span class="badge 
                                                {{ $leave->final_status == 'Approved' ? 'bg-success' : 
                                                   ($leave->final_status == 'Rejected' ? 'bg-danger' : 'bg-warning') }}" style="font-size: 10px;">
                                                {{ $leave->final_status ?? $leave->status }}
                                            </span>
                                            <span class="badge bg-info">
                                                {{ $leave->leave_type ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <p class="d-flex justify-content-between text-muted mb-1">
                                        <small class="text-muted">
                                            {{ $leave->reason ?? 'No reason provided' }}
                                        </small>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($leave->created_at)->format('d M Y') }}
                                        </small>
                                    </p>
                                </div>
                            @endforeach
                            @else
                            <div class="text-center py-4">
                                <i class="ti ti-calendar-off fs-48 text-muted mb-3"></i>
                                <p class="text-muted">No Leave requests</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card mb-4 chart-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="/institute/admin/monthly-attendance" class="card quick-link-card border text-center p-3">
                                    <i class="ti ti-calendar-share fs-32 mb-2 text-primary"></i>
                                    <h6 class="mb-0">Attendance</h6>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/fee-structure/view" class="card quick-link-card border text-center p-3">
                                    <i class="ti ti-license fs-32 mb-2 text-success"></i>
                                    <h6 class="mb-0">Fees</h6>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/syllabus/view" class="card quick-link-card border text-center p-3">
                                    <i class="ti ti-hexagonal-prism fs-32 mb-2 text-warning"></i>
                                    <h6 class="mb-0">Syllabus</h6>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/admin/assignments" class="card quick-link-card border text-center p-3">
                                    <i class="ti ti-report-money fs-32 mb-2 text-danger"></i>
                                    <h6 class="mb-0">Home Work</h6>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/institute/admin/shift-schedule" class="card quick-link-card border text-center p-3">
                                    <i class="ti ti-sphere fs-32 mb-2 text-info"></i>
                                    <h6 class="mb-0">Shifts</h6>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="/institute/admin/gallery" class="card quick-link-card border text-center p-3">
                                    <i class="ti ti-photo fs-32 mb-2 text-purple"></i>
                                    <h6 class="mb-0">Gallery</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Trends -->
                <div class="card mb-4 chart-card">
                    <div class="card chart-card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Registration Trends</h4>
                        </div>
                        <div class="p-3 pb-0">
                            <h6 class="card-title mb-0">Leads</h6>
                        </div>
                        <div class="card-body pt-0">
                            <div id="lead-chart" style="height: 250px;"></div>
                        </div>

                        <div class="p-3 pb-0">
                            <h6 class="card-title mb-0">Visitors</h6>
                        </div>
                        <div class="card-body pt-0">
                            <div id="visitor-chart" style="height: 250px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row: Notices -->
        <div class="row">
            <!-- Notice Board -->
            <div class="col-lg-12">
                <div class="card chart-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Recent Notices</h4>
                        <a href="{{ url('/notice-board/view') }}" class="btn btn-sm btn-primary">
                            <i class="fa-regular fa-eye"></i>
                            View All 
                            <!--<i class="ti ti-chevron-right ms-1"></i>-->
                        </a>
                    </div>
                    <div class="card-body custom-scroll" style="max-height: 400px;">
                        @if(count($data['notice_board']) > 0)
                            @foreach($data['notice_board'] as $notice)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="mb-0">{{ $notice->title }}</h6>
                                    <span class="badge 
                                        {{ $notice->status === 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ ucfirst($notice->status) }}
                                    </span>
                                </div>
                                <p class="text-muted mb-2">
                                    {!! Str::limit(strip_tags($notice->content), 150) !!}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="ti ti-calendar me-1"></i>
                                        {{ $notice->created_at->format('d M Y') }}
                                    </small>
                                    <a href="javascript:void(0)" 
                                       class="btn btn-sm btn-outline-primary view-notice"
                                       data-notice='@json($notice)'>
                                        View Details
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="ti ti-bell-off fs-48 text-muted mb-3"></i>
                                <p class="text-muted">No notices available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notice Detail Modal -->
    <div class="modal fade" id="noticeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noticeTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="badge bg-light text-dark me-2" id="noticeDate"></span>
                        <span class="badge bg-light text-dark" id="noticeStatus"></span>
                    </div>
                    <div id="noticeContent"></div>
                    <div id="noticeAttachment" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        // Global chart instances
        let genderChart, employeeGenderChart, employeeRoleChart, feeDistributionChart;
        let recentRegChart, employeeRegChart, leadRegChart, visitorRegChart;
        let feeAnalyticsChart = null;
        
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // console.log('DOM loaded, initializing charts...');
            
            // Initialize static charts
            initGenderChart();
            initEmployeeGenderChart();
            initEmployeeRoleChart();
            initFeeDistributionChart();
            initRecentRegistrationsChart();
            initEmployeeRegistrationsChart();
            initLeadChart();
            initVisitorChart();
            
            // Initialize dynamic fee analytics
            setTimeout(() => {
                loadDashboardAnalytics();
            }, 100);
            
            // Tab switching functionality
            $('.chart-tab').click(function() {
                const tabId = $(this).data('tab');
                
                $('.chart-tab').removeClass('active');
                $('.chart-content').removeClass('active');
                
                $(this).addClass('active');
                $('#' + tabId).addClass('active');
                
                setTimeout(() => {
                    if (tabId === 'gender-distribution' && employeeGenderChart) {
                        employeeGenderChart.resize();
                    } else if (tabId === 'role-distribution' && employeeRoleChart) {
                        employeeRoleChart.resize();
                    }
                }, 300);
            });
            
            // Notice view handler
            $('.view-notice').click(function() {
                const notice = $(this).data('notice');
                $('#noticeTitle').text(notice.title);
                $('#noticeDate').text(new Date(notice.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }));
                $('#noticeStatus').text(notice.status.charAt(0).toUpperCase() + notice.status.slice(1));
                $('#noticeContent').html(notice.content);
                
                if (notice.attachment) {
                    $('#noticeAttachment').html(`
                        <strong>Attachment:</strong><br>
                        <a href="${notice.attachment}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                            <i class="ti ti-download me-1"></i>Download Attachment
                        </a>
                    `);
                } else {
                    $('#noticeAttachment').html('');
                }
                
                new bootstrap.Modal(document.getElementById('noticeModal')).show();
            });
            
            // Filter change handlers
            $('#reportType, #feeType').on('change', function() {
                console.log('Filter changed:', $('#reportType').val(), $('#feeType').val());
                loadFilteredAnalytics();
            });
        });

        // Fee Distribution Chart
        function initFeeDistributionChart() {
            const el = document.querySelector("#fee-distribution-chart");
            if (!el) {
                console.error("❌ #fee-distribution-chart not found");
                return;
            }

            const series = [
                Number({{ $data['total_course_fee'] ?? 0 }}),
                Number({{ $data['total_registration_fee'] ?? 0 }}),
                Number({{ $data['total_transport_fee'] ?? 0 }}),
                Number({{ $data['total_hostel_fee'] ?? 0 }}),
                Number({{ $data['total_custom_fee'] ?? 0 }})
            ];

            const labels = ['Course Fee', 'Registration', 'Transport', 'Hostel', 'Custom'];
            const colors = ['#4a6cf7', '#28a745', '#ffc107', '#dc3545', '#17a2b8'];

            const options = {
                series: series,
                chart: {
                    type: 'pie',
                    height: 250,
                    width: '100%',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true
                        }
                    }
                },
                labels: labels,
                colors: colors,
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '13px',
                        fontWeight: 500,
                        colors: ["#000000"]
                    },
                    formatter: function(val, opts) {
                        return opts.w.config.series[opts.seriesIndex].toLocaleString('en-IN');
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return '₹' + new Intl.NumberFormat('en-IN').format(value);
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            height: 200
                        }
                    }
                }]
            };

            if (feeDistributionChart) {
                feeDistributionChart.destroy();
            }

            feeDistributionChart = new ApexCharts(el, options);
            feeDistributionChart.render();
            generateCustomLegend(series, labels, colors);
        }

        // Dynamic Legend Generator
        function generateCustomLegend(series, labels, colors) {
            const legendContainer = document.getElementById('custom-legend');
            if (!legendContainer) return;

            legendContainer.innerHTML = '';

            const total = series.reduce((a, b) => a + b, 0);

            series.forEach((value, index) => {
                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                const item = document.createElement('div');
                item.className = 'd-flex align-items-center mb-2 legend-item';
                item.style.cursor = 'pointer';
                
                item.innerHTML = `
                    <div style="width:12px; height:12px; background:${colors[index]}; border-radius:2px; margin-right:8px;"></div>
                    <span class="flex-grow-1" style="font-size: 13px;">${labels[index]}</span>
                    <span class="ms-auto" style="font-size: 13px;">
                        <strong>₹${new Intl.NumberFormat('en-IN').format(value)}</strong>
                        <small class="text-muted">(${percentage}%)</small>
                    </span>
                `;
                
                item.onclick = () => {
                    if (feeDistributionChart) {
                        feeDistributionChart.toggleDataPointSelection(index);
                    }
                };
                
                legendContainer.appendChild(item);
            });

            // Add total at the bottom
            const totalDiv = document.createElement('div');
            totalDiv.className = 'mt-3 pt-2 border-top d-none d-md-block';
            totalDiv.innerHTML = `
                <div class="d-flex justify-content-between">
                    <strong>Total Fee:</strong>
                    <strong class="text-primary">₹${new Intl.NumberFormat('en-IN').format(total)}</strong>
                </div>
            `;
            legendContainer.appendChild(totalDiv);
        }

        // DYNAMIC Fee Collection Analytics Function
        function loadDashboardAnalytics() {
            // console.log('loadDashboardAnalytics called');
            
            const reportType = $('#reportType').val();
            const feeType = $('#feeType').val();
            
            // console.log('Report Type:', reportType, 'Fee Type:', feeType);
            
            // If not "all fees", load filtered data
            if (feeType !== 'all') {
                loadFilteredAnalytics();
                return;
            }
            
            const labels = [
                'Course Fee',
                'Registration Fee',
                'Transport Fee',
                'Hostel Fee',
                'Custom Fee'
            ];

            const values = [
                Number({{ $data['total_course_fee'] ?? 0 }}),
                Number({{ $data['total_registration_fee'] ?? 0 }}),
                Number({{ $data['total_transport_fee'] ?? 0 }}),
                Number({{ $data['total_hostel_fee'] ?? 0 }}),
                Number({{ $data['total_custom_fee'] ?? 0 }})
            ];

            // Individual colors for each bar (matching Fee Distribution chart)
            const barColors = ['#4a6cf7', '#28a745', '#ffc107', '#dc3545', '#17a2b8'];

            const total = values.reduce((a, b) => a + b, 0);
            const pending = Number({{ $data['total_dues'] ?? 0 }});
            const overdue = Number({{ $data['overdue_dues'] ?? 0 }});

            // Update summary
            $('#totalCollection').text('₹' + total.toLocaleString('en-IN'));
            $('.col-sm-4 .text-danger.fw-bold').first().text('₹' + pending.toLocaleString('en-IN'));
            $('.col-sm-4 .text-danger.fw-bold').last().text('₹' + overdue.toLocaleString('en-IN'));

            // Get chart container
            const chartContainer = document.querySelector("#feeAnalyticsChart");
            if (!chartContainer) {
                console.error("❌ #feeAnalyticsChart container not found!");
                return;
            }

            // Destroy old chart if exists
            if (feeAnalyticsChart) {
                feeAnalyticsChart.destroy();
                feeAnalyticsChart = null;
            }

            // Check if there's data
            if (total === 0) {
                $('#noDataMessage').removeClass('d-none');
                chartContainer.innerHTML = '<div class="text-center py-5 text-muted">No data available</div>';
                return;
            } else {
                $('#noDataMessage').addClass('d-none');
            }

            // Clear container
            chartContainer.innerHTML = '';

            // ApexCharts configuration with individual bar colors
            const options = {
                series: [{
                    name: 'Fee Collection',
                    data: values
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    width: '100%',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    },
                    background: 'transparent'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        borderRadiusApplication: 'end',
                        columnWidth: '70%',
                        distributed: true,
                        dataLabels: {
                            enabled: false,
                        }
                    }
                },
                colors: barColors,
                dataLabels: {
                    enabled: true,
                    offsetY: 5,
                    style: {
                        fontSize: '13px',
                        fontWeight: 700,
                        colors: ["#000000"]
                    },
                    formatter: function(val) {
                        return '₹' + val.toLocaleString('en-IN');
                    }
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    },
                    padding: {
                        top: 20,
                        right: 20,
                        bottom: 10,
                        left: 10
                    }
                },
                xaxis: {
                    categories: labels,
                    labels: {
                        show: true,
                        rotate: -15,
                        style: {
                            fontSize: '12px'
                        }
                    },
                    axisTicks: {
                        show: true
                    },
                    axisBorder: {
                        show: true
                    }
                },
                yaxis: {
                    title: {
                        text: 'Amount (₹)',
                        style: {
                            fontSize: '13px',
                            fontWeight: 500
                        }
                    },
                    labels: {
                        show: true,
                        formatter: function(val) {
                            return '₹' + val.toLocaleString('en-IN');
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return '₹' + val.toLocaleString('en-IN');
                        }
                    },
                    theme: 'dark'
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    fontSize: '13px',
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 6
                    }
                },
                title: {
                    text: getFeeTypeLabel(feeType),
                    align: 'left',
                    style: {
                        fontSize: '14px',
                        fontWeight: 600
                    }
                }
            };

            // Create the chart
            try {
                feeAnalyticsChart = new ApexCharts(chartContainer, options);
                feeAnalyticsChart.render();
                // console.log('Chart rendered successfully with individual bar colors');
            } catch (error) {
                console.error('Error rendering chart:', error);
            }
        }

        // Function to load filtered analytics
        function loadFilteredAnalytics() {
            const reportType = $('#reportType').val();
            const feeType = $('#feeType').val();
            
            console.log('loadFilteredAnalytics called:', reportType, feeType);
            
            // If "All Fees" is selected, use the main data
            if (feeType === 'all') {
                loadDashboardAnalytics();
                return;
            }
            
            // Show loading indicator
            $('#chartLoading').removeClass('d-none');
            $('#noDataMessage').addClass('d-none');
            
            // For demo purposes, generate sample data based on filters
            // Replace this with your actual AJAX call
            setTimeout(() => {
                const sampleData = generateSampleData(reportType, feeType);
                updateChartWithFilteredData(sampleData, reportType, feeType);
                $('#chartLoading').addClass('d-none');
            }, 500);
            
            // Uncomment this for actual AJAX call
            /*
            $.ajax({
                url: '/get-fee-analytics',
                method: 'GET',
                data: {
                    report_type: reportType,
                    fee_type: feeType
                },
                success: function(response) {
                    updateChartWithFilteredData(response, reportType, feeType);
                },
                error: function(xhr) {
                    console.error('Error loading analytics:', xhr);
                    showErrorMessage();
                },
                complete: function() {
                    $('#chartLoading').addClass('d-none');
                }
            });
            */
        }
        
        // Generate sample data for demo
        function generateSampleData(reportType, feeType) {
            let labels = [];
            let values = [];
            
            if (reportType === 'weekly') {
                labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                values = [12000, 15000, 18000, 22000, 25000, 8000, 5000];
            } else if (reportType === 'monthly') {
                labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                values = [45000, 52000, 48000, 60000, 75000, 82000, 78000, 85000, 72000, 68000, 55000, 62000];
            } else {
                labels = ['2022', '2023', '2024', '2025'];
                values = [450000, 580000, 720000, 850000];
            }
            
            return {
                labels: labels,
                values: values,
                total_collection: values.reduce((a, b) => a + b, 0),
                total_pending: 125000,
                total_overdue: 45000
            };
        }

        // Update chart with filtered data
        function updateChartWithFilteredData(data, reportType, feeType) {
            console.log('updateChartWithFilteredData called', data);
            
            const labels = data.labels || getTimeLabels(data, reportType);
            const values = data.values || getDataValues(data, feeType);
            
            const chartContainer = document.querySelector("#feeAnalyticsChart");
            if (!chartContainer) return;
            
            // Clear container
            chartContainer.innerHTML = '';
            
            // Destroy old chart if exists
            if (feeAnalyticsChart) {
                feeAnalyticsChart.destroy();
                feeAnalyticsChart = null;
            }
            
            // Define color mapping for different fee types
            const feeTypeColors = {
                'course': '#4a6cf7',      // Indigo
                'registration': '#28a745', // Green
                'transport': '#ffc107',    // Yellow
                'hostel': '#dc3545',       // Red
                'custom': '#17a2b8',       // Blue
                'all': '#4a6cf7'           // Default blue for all fees
            };
            
            // Get the color for the selected fee type
            const mainColor = feeTypeColors[feeType] || '#4a6cf7';
            
            // Determine chart type based on report type
            // Use bar chart for weekly/monthly, line chart for yearly trends
            let chartType = 'bar';
            let isDistributed = false;
            
            if (feeType === 'all') {
                chartType = 'bar';
                isDistributed = true;
            } else {
                // For individual fee types, use line chart with area fill for better visualization
                chartType = 'area';
            }
            
            const options = {
                series: [{
                    name: getFeeTypeLabel(feeType),
                    data: values
                }],
                chart: {
                    type: chartType,
                    height: 250,
                    width: '100%',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                // For bar charts with distributed bars
                ...(chartType === 'bar' && isDistributed && {
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            borderRadiusApplication: 'end',
                            columnWidth: '60%',
                            distributed: true,
                            dataLabels: {
                                enabled: false,
                            }
                        }
                    }
                }),
                // For regular bar charts
                ...(chartType === 'bar' && !isDistributed && {
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            borderRadiusApplication: 'end',
                            columnWidth: '60%',
                            distributed: false,
                            dataLabels: {
                                enabled: false,
                            }
                        }
                    }
                }),
                // For area/line charts
                ...(chartType === 'area' && {
                    stroke: {
                        curve: 'smooth',
                        width: 3,
                        colors: [mainColor]
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.3,
                            stops: [0, 90, 100],
                            colorStops: [
                                {
                                    offset: 0,
                                    color: mainColor,
                                    opacity: 0.8
                                },
                                {
                                    offset: 100,
                                    color: mainColor,
                                    opacity: 0.1
                                }
                            ]
                        }
                    },
                    markers: {
                        size: 4,
                        colors: [mainColor],
                        strokeColors: '#fff',
                        strokeWidth: 2,
                        hover: {
                            size: 6
                        }
                    }
                }),
                colors: isDistributed ? ['#4a6cf7', '#28a745', '#ffc107', '#dc3545', '#17a2b8'] : [mainColor],
                dataLabels: {
                    enabled: false,
                    offsetY: chartType === 'bar' ? -20 : -10,
                    style: {
                        fontSize: '11px',
                        colors: ["#333"]
                    },
                    formatter: function(val) {
                        return '₹' + val.toLocaleString('en-IN');
                    },
                    background: {
                        enabled: true,
                        borderRadius: 4,
                        borderWidth: 0,
                        opacity: 0.8,
                        padding: 4
                    }
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    },
                    padding: {
                        top: 20,
                        right: 20,
                        bottom: 10,
                        left: 10
                    }
                },
                xaxis: {
                    categories: labels,
                    title: {
                        text: getXAxisLabel(reportType),
                        style: {
                            fontSize: '13px',
                            fontWeight: 500
                        }
                    },
                    labels: {
                        show: true,
                        rotate: -15,
                        style: {
                            fontSize: '11px'
                        }
                    },
                    axisBorder: {
                        show: true,
                        color: '#e7e7e7'
                    },
                    axisTicks: {
                        show: true,
                        color: '#e7e7e7'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Amount (₹)',
                        style: {
                            fontSize: '13px',
                            fontWeight: 500
                        }
                    },
                    labels: {
                        show: true,
                        formatter: function(val) {
                            return '₹' + val.toLocaleString('en-IN');
                        }
                    }
                },
                tooltip: {
                    shared: false,
                    intersect: true,

                    x: {
                        formatter: function(value, opts) {
                            return opts.w.globals.categoryLabels[opts.dataPointIndex];
                        }
                    },

                    y: {
                        formatter: function(val) {
                            return '₹' + val.toLocaleString('en-IN');
                        },
                        title: {
                            formatter: function(seriesName) {
                                return seriesName + ': ';
                            }
                        }
                    },

                    theme: 'dark',
                    marker: {
                        show: true
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    fontSize: '12px',
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 5,
                        fillColors: [mainColor]
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 5
                    }
                },
                title: {
                    text: getFeeTypeLabel(feeType),
                    align: 'left',
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#2c3e50'
                    }
                }
            };
            
            try {
                feeAnalyticsChart = new ApexCharts(chartContainer, options);
                feeAnalyticsChart.render();
                console.log('Filtered chart rendered successfully with color:', mainColor);
            } catch (error) {
                console.error('Error rendering filtered chart:', error);
            }
            
            // Update summary stats
            updateSummaryStats(data);
            
            // Show/hide no data message
            const total = values.reduce((a, b) => a + b, 0);
            if (total === 0) {
                $('#noDataMessage').removeClass('d-none');
            } else {
                $('#noDataMessage').addClass('d-none');
            }
        }

        // Helper functions
        function getFeeTypeLabel(feeType) {
            const labels = {
                'all': 'All Fees Collection',
                'course': 'Course Fee Collection',
                'registration': 'Registration Fee Collection',
                'transport': 'Transport Fee Collection',
                'hostel': 'Hostel Fee Collection',
                'custom': 'Custom Fee Collection'
            };
            return labels[feeType] || 'Fee Collection';
        }

        function getXAxisLabel(reportType) {
            const labels = {
                'weekly': 'Days',
                'monthly': 'Months',
                'yearly': 'Years'
            };
            return labels[reportType] || 'Period';
        }

        function getTimeLabels(data, reportType) {
            if (data && data.labels) return data.labels;
            if (reportType === 'weekly') {
                return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            } else if (reportType === 'monthly') {
                return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            }
            return ['2022', '2023', '2024', '2025'];
        }

        function getDataValues(data, feeType) {
            if (data && data.values) return data.values;
            return [15000, 25000, 35000, 20000, 30000];
        }

        function updateSummaryStats(data) {
            if (data) {
                $('#totalCollection').text('₹' + (data.total_collection || 0).toLocaleString('en-IN'));
                $('.col-sm-4 .text-danger.fw-bold').first().text('₹' + (data.total_pending || 0).toLocaleString('en-IN'));
                $('.col-sm-4 .text-danger.fw-bold').last().text('₹' + (data.total_overdue || 0).toLocaleString('en-IN'));
            }
        }

        function showErrorMessage() {
            $('#noDataMessage').removeClass('d-none');
            $('#noDataMessage p').text('Error loading analytics data. Please try again.');
            setTimeout(() => {
                $('#noDataMessage p').text('No fee collection data available for the selected filters.');
            }, 3000);
        }

        // Other chart initialization functions (keep your existing ones)
        function initGenderChart() {
            const options = {
                series: [{{ $data['male_students'] ?? 0 }}, {{ $data['female_students'] ?? 0 }}],
                chart: { type: 'donut', height: 300, toolbar: { show: true, tools: { download: true } } },
                colors: ['#17a2b8', '#e83e8c'],
                labels: ['Male Students', 'Female Students'],
                plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total Students', formatter: () => {{ $data['total_students'] ?? 0 }} } } } } },
                legend: { position: 'bottom' }
            };
            genderChart = new ApexCharts(document.querySelector("#gender-chart"), options);
            genderChart.render();
        }

        function initEmployeeGenderChart() {
            const options = {
                series: @json($data['employee_gender_values']),
                chart: { type: 'donut', height: 300, toolbar: { show: true, tools: { download: true } } },
                colors: ['#17a2b8', '#e83e8c', '#ffc107'],
                labels: @json($data['employee_gender_labels']),
                plotOptions: { pie: { donut: { size: '60%', labels: { show: true, total: { show: true, label: 'Total Employees', formatter: () => {{ $data['total_employees'] ?? 0 }} } } } } },
                legend: { position: 'bottom' }
            };
            employeeGenderChart = new ApexCharts(document.querySelector("#employee-gender-chart"), options);
            employeeGenderChart.render();
        }

        function initEmployeeRoleChart() {
            const options = {
                series: @json($data['employee_role_values']),
                chart: { type: 'pie', height: 300, toolbar: { show: true, tools: { download: true } } },
                labels: @json($data['employee_role_labels']),
                legend: { position: 'bottom' }
            };
            employeeRoleChart = new ApexCharts(document.querySelector("#employee-role-chart"), options);
            employeeRoleChart.render();
        }

        function initRecentRegistrationsChart() {
            const options = {
                series: [{ name: 'Registrations', data: @json($data['registration_quarter_values']) }],
                chart: { type: 'bar', height: 250, toolbar: { show: true, tools: { download: true } } },
                colors: ['#6f42c1'],
                plotOptions: { bar: { borderRadius: 8, columnWidth: '60%' } },
                dataLabels: { enabled: true, offsetY: -20 },
                xaxis: { categories: @json($data['registration_quarter_labels']) }
            };
            recentRegChart = new ApexCharts(document.querySelector("#recent-registrations-chart"), options);
            recentRegChart.render();
        }

        function initEmployeeRegistrationsChart() {
            const options = {
                series: [{ name: 'Registrations', data: @json($data['registration_quarter_values']) }],
                chart: { type: 'bar', height: 300, toolbar: { show: false } },
                colors: ['#28a745'],
                plotOptions: { bar: { borderRadius: 8, columnWidth: '60%' } },
                dataLabels: { enabled: true, offsetY: -20 },
                xaxis: { categories: @json($data['registration_quarter_labels']) }
            };
            employeeRegChart = new ApexCharts(document.querySelector("#employee-registrations-chart"), options);
            employeeRegChart.render();
        }

        function initLeadChart() {
            const options = {
                series: [{ name: 'Leads', data: @json($data['registration_quarter_values']) }],
                chart: { type: 'bar', height: 250, toolbar: { show: false } },
                colors: ['#ffc107'],
                plotOptions: { bar: { borderRadius: 8, columnWidth: '60%' } },
                dataLabels: { enabled: true, offsetY: -20 },
                xaxis: { categories: @json($data['registration_quarter_labels']) }
            };
            leadRegChart = new ApexCharts(document.querySelector("#lead-chart"), options);
            leadRegChart.render();
        }

        function initVisitorChart() {
            const options = {
                series: [{ name: 'Visitors', data: @json($data['registration_quarter_values']) }],
                chart: { type: 'bar', height: 250, toolbar: { show: false } },
                colors: ['#17a2b8'],
                plotOptions: { bar: { borderRadius: 8, columnWidth: '60%' } },
                dataLabels: { enabled: true, offsetY: -20 },
                xaxis: { categories: @json($data['registration_quarter_labels']) }
            };
            visitorRegChart = new ApexCharts(document.querySelector("#visitor-chart"), options);
            visitorRegChart.render();
        }

        // Resize handler
        window.addEventListener('resize', function() {
            const charts = [feeDistributionChart, feeAnalyticsChart, genderChart, employeeGenderChart, 
                            employeeRoleChart, recentRegChart, employeeRegChart, leadRegChart, visitorRegChart];
            charts.forEach(chart => {
                if (chart && typeof chart.resize === 'function') {
                    chart.resize();
                }
            });
        });
    </script>
</body>
</html>
@endsection