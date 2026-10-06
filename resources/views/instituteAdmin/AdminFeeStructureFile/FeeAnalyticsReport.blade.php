@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Fee Analytics & Collection Report</title>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary: #4361ee;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #3b82f6;
    --dark: #1f2937;
    --border: #e5e7eb;
    --gray-100: #f3f4f6;
    --gray-500: #6b7280;
}

/* Header Section */
.dashboard-header {
    background: linear-gradient(135deg, var(--primary) 0%, #3a56d4 100%);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
}

.header-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.header-subtitle {
    font-size: 1rem;
    opacity: 0.9;
}

/* Stats Cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    /*display: flex;*/
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
}

.stat-icon-primary {
    background: linear-gradient(135deg, #e6eeff, #d4e2ff);
    color: var(--primary);
}

.stat-icon-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: var(--success);
}

.stat-icon-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: var(--danger);
}

.stat-icon-warning {
    background: linear-gradient(135deg, #fed7aa, #fde68a);
    color: var(--warning);
}

.stat-icon-info {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: var(--info);
}

.stat-content {
    margin-top: 12px;
    flex: 1;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--dark);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--gray-500);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-change {
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.change-positive {
    color: var(--success);
}

.change-negative {
    color: var(--danger);
}

/* Chart Containers */
.chart-container {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.chart-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chart-title i {
    color: var(--primary);
}

.chart-wrapper {
    position: relative;
    height: 300px;
}

/* Filter Section */
.filter-section {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.filter-group {
    margin-bottom: 0;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--dark);
    margin-bottom: 0.5rem;
    display: block;
}

.filter-select,
.filter-input {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 0.875rem;
    background: white;
    transition: all 0.2s;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-buttons {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
}

.btn-filter {
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-filter-primary {
    background: var(--primary);
    color: white;
}

.btn-filter-primary:hover {
    background: #2a4ad4;
    transform: translateY(-1px);
}

.btn-filter-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    text-decoration: none;
}

.btn-filter-secondary:hover {
    background: #e2e8f0;
    text-decoration: none;
}

/* Data Table */
.data-table-container {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.data-table-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.table-custom {
    width: 100%;
}

/*.table-custom thead th {*/
/*    background: linear-gradient(135deg, #f8fafc, #f1f5f9);*/
/*    color: var(--dark);*/
/*    font-weight: 600;*/
/*    font-size: 0.85rem;*/
/*    text-transform: uppercase;*/
/*    padding: 1rem;*/
/*    border-bottom: 2px solid var(--border);*/
/*}*/

/*.table-custom tbody td {*/
/*    padding: 0.875rem 1rem;*/
/*    vertical-align: middle;*/
/*    border-bottom: 1px solid var(--border);*/
/*}*/

/*.table-custom tbody tr:hover {*/
/*    background-color: var(--gray-100);*/
/*}*/

.amount-positive {
    color: var(--success);
    font-weight: 600;
}

.amount-negative {
    color: var(--danger);
    font-weight: 600;
}

/* Fee Type Badges */
.fee-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
}

.badge-course {
    background: #e6eeff;
    color: var(--primary);
}

.badge-registration {
    background: #fef3c7;
    color: #d97706;
}

.badge-transport {
    background: #d1fae5;
    color: #059669;
}

.badge-hostel {
    background: #ede9fe;
    color: #7c3aed;
}

.badge-misc {
    background: #fee2e2;
    color: #dc2626;
}

/* Export Buttons */
.export-buttons {
    display: flex;
    gap: 0.75rem;
}

.btn-export {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    border: 1px solid var(--border);
    background: white;
    transition: all 0.2s;
}

.btn-export:hover {
    background: var(--gray-100);
    transform: translateY(-1px);
}

/* Loader */
#pageLoader {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(5px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #ccc;
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-icon {
        width: 44px;
        height: 44px;
        font-size: 1.25rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
    
    .chart-wrapper {
        height: 250px;
    }
}

        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }

        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {  
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
        .table-responsive{
            overflow-x: hidden;
        }
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="header-title">
                    <i class="bi bi-graph-up me-2"></i>Fee Analytics & Collection Report
                </h1>
                <p class="header-subtitle">
                    Comprehensive analysis of all fee collections across courses, registration, transport, hostel, and miscellaneous fees
                </p>
                <div class="mt-2">
                    <span class="badge bg-light text-dark me-2">
                        <i class="bi bi-calendar3"></i> Last updated: {{ now()->format('d M Y, h:i A') }}
                    </span>
                </div>
            </div>
            <div class="export-buttons">
                <button class="btn-export" onclick="exportToExcel()">
                    <i class="bi bi-file-excel me-1 text-success"></i> Export Excel
                </button>
                <button class="btn-export" onclick="window.print()">
                    <i class="bi bi-printer me-1 text-primary"></i> Print
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <i class="bi bi-currency-rupee"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">₹{{ number_format($totalCollection, 2) }}</div>
                <div class="stat-label">Total Collection</div>
                <div class="stat-change">
                    <span class="change-positive">
                        <i class="bi bi-arrow-up"></i> {{ number_format($collectionGrowth, 1) }}%
                    </span> from last month
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalPaidCount) }}</div>
                <div class="stat-label">Successful Payments</div>
                <div class="stat-change">Collection rate: {{ number_format($collectionRate, 1) }}%</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-icon-danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">₹{{ number_format($totalPending, 2) }}</div>
                <div class="stat-label">Pending Collection</div>
                <div class="stat-change">{{ number_format($pendingCount) }} pending payments</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalOverdueCount) }}</div>
                <div class="stat-label">Overdue Payments</div>
                <div class="stat-change">₹{{ number_format($totalOverdueAmount, 2) }} overdue</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon stat-icon-info">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($totalStudents) }}</div>
                <div class="stat-label">Active Students</div>
                <div class="stat-change">{{ number_format($avgFeePerStudent, 2) }} avg per student</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <form method="GET" action="{{ route('admin.fee.analytics') }}" id="filterForm">
        <div class="filter-section">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Report Type</label>
                    <select name="report_type" class="filter-select" onchange="this.form.submit()">
                        <option value="daily" {{ request('report_type', 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ request('report_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ request('report_type') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Fee Type</label>
                    <select name="fee_type" class="filter-select" onchange="this.form.submit()">
                        <option value="all" {{ request('fee_type', 'all') == 'all' ? 'selected' : '' }}>All Fees</option>
                        <option value="course" {{ request('fee_type') == 'course' ? 'selected' : '' }}>Course Fee</option>
                        <option value="registration" {{ request('fee_type') == 'registration' ? 'selected' : '' }}>Registration Fee</option>
                        <option value="transport" {{ request('fee_type') == 'transport' ? 'selected' : '' }}>Transport Fee</option>
                        <option value="hostel" {{ request('fee_type') == 'hostel' ? 'selected' : '' }}>Hostel Fee</option>
                        <option value="miscellaneous" {{ request('fee_type') == 'miscellaneous' ? 'selected' : '' }}>Miscellaneous Fee</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Department</label>
                    <select name="department_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->department_id }}" {{ request('department_id') == $dept->department_id ? 'selected' : '' }}>
                                {{ $dept->department }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Year</label>
                    <select name="year" class="filter-select" onchange="this.form.submit()">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ request('year', date('Y')) == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @if(request('report_type') == 'monthly')
                <div class="filter-group">
                    <label class="filter-label">Month</label>
                    <select name="month" class="filter-select" onchange="this.form.submit()">
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ request('month', date('n')) == $month ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            
            <div class="filter-buttons">
                <a href="{{ route('admin.fee.analytics') }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i> Reset Filters
                </a>
            </div>
        </div>
    </form>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="bi bi-bar-chart-steps"></i> Collection Trend
                    <span class="ms-auto text-muted small">({{ ucfirst(request('report_type', 'daily')) }} view)</span>
                </div>
                <div class="chart-wrapper">
                    <canvas id="collectionChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="bi bi-pie-chart"></i> Collection by Fee Type
                </div>
                <div class="chart-wrapper">
                    <canvas id="feeTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="bi bi-building"></i> Collection by Department
                </div>
                <div class="chart-wrapper">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="bi bi-cash-stack"></i> Payment Status Distribution
                </div>
                <div class="chart-wrapper">
                    <canvas id="paymentStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Data Table -->
    <div class="data-table-container">
        <div class="data-table-title">
            <div>
                <i class="bi bi-table me-2"></i> Detailed Collection Report
                <span class="badge bg-secondary ms-2">{{ count($collectionData) }} records</span>
            </div>
        </div>
        <div>
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table id="collectionTable" class="erp-table table-custom table table-hover">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable">Period</th>
                            <th class="sortable">Course Fee</th>
                            <th class="sortable">Registration Fee</th>
                            <th class="sortable">Transport Fee</th>
                            <th class="sortable">Hostel Fee</th>
                            <th class="sortable">Miscellaneous Fee</th>
                            <th class="sortable">Total Collection</th>
                            <th class="sortable">Transactions</th>
                            <th class="sortable">Collection Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collectionData as $data)
                        <tr>
                            <td class="sticky-main-2 fw-semibold">{{ $data['period'] }}</td>
                            <td class="amount-positive">₹{{ number_format($data['course_fee'], 2) }}</td>
                            <td class="amount-positive">₹{{ number_format($data['registration_fee'], 2) }}</td>
                            <td class="amount-positive">₹{{ number_format($data['transport_fee'], 2) }}</td>
                            <td class="amount-positive">₹{{ number_format($data['hostel_fee'], 2) }}</td>
                            <td class="amount-positive">₹{{ number_format($data['miscellaneous_fee'], 2) }}</td>
                            <td class="fw-bold text-primary">₹{{ number_format($data['total'], 2) }}</td>
                            <td>{{ number_format($data['transaction_count']) }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: {{ $data['collection_rate'] }}%"></div>
                                    </div>
                                    <span class="small">{{ number_format($data['collection_rate'], 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No data available for the selected filters
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($collectionData) > 0)
                    <tfoot class="table-light">
                        <tr class="fw-bold">
                            <td>TOTAL</td>
                            <td>₹{{ number_format($totals['course_fee'], 2) }}</td>
                            <td>₹{{ number_format($totals['registration_fee'], 2) }}</td>
                            <td>₹{{ number_format($totals['transport_fee'], 2) }}</td>
                            <td>₹{{ number_format($totals['hostel_fee'], 2) }}</td>
                            <td>₹{{ number_format($totals['miscellaneous_fee'], 2) }}</td>
                            <td class="text-primary">₹{{ number_format($totals['total'], 2) }}</td>
                            <td>{{ number_format($totals['transaction_count']) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            
            <!-- Floating Horizontal Scrollbar -->
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
        </div>
    </div>
</div>

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
// Chart.js Global Configuration
Chart.defaults.font.family = "'Segoe UI', 'Roboto', sans-serif";
Chart.defaults.font.size = 11;

// Chart Data from PHP
const chartLabels = @json($chartLabels);
const chartValues = @json($chartValues);
const feeTypeLabels = @json($feeTypeLabels);
const feeTypeValues = @json($feeTypeValues);
const departmentLabels = @json($departmentLabels);
const departmentValues = @json($departmentValues);
const paymentStatusLabels = @json($paymentStatusLabels);
const paymentStatusValues = @json($paymentStatusValues);

let collectionChart, feeTypeChart, departmentChart, paymentStatusChart;

// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Collection Trend Chart
    const ctx1 = document.getElementById('collectionChart').getContext('2d');
    collectionChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Collection Amount (₹)',
                data: chartValues,
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4361ee',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₹' + context.raw.toLocaleString('en-IN');
                        }
                    }
                },
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString('en-IN');
                        }
                    }
                }
            }
        }
    });
    
    // Fee Type Distribution Chart
    const ctx2 = document.getElementById('feeTypeChart').getContext('2d');
    feeTypeChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: feeTypeLabels,
            datasets: [{
                data: feeTypeValues,
                backgroundColor: ['#4361ee', '#f59e0b', '#10b981', '#8b5cf6', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ₹${value.toLocaleString('en-IN')} (${percentage}%)`;
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
    
    // Department-wise Collection Chart
    const ctx3 = document.getElementById('departmentChart').getContext('2d');
    departmentChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: departmentLabels,
            datasets: [{
                label: 'Collection Amount (₹)',
                data: departmentValues,
                backgroundColor: 'rgba(67, 97, 238, 0.7)',
                borderRadius: 8,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₹' + context.raw.toLocaleString('en-IN');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString('en-IN');
                        }
                    }
                }
            }
        }
    });
    
    // Payment Status Distribution Chart
    const ctx4 = document.getElementById('paymentStatusChart').getContext('2d');
    paymentStatusChart = new Chart(ctx4, {
        type: 'pie',
        data: {
            labels: paymentStatusLabels,
            datasets: [{
                data: paymentStatusValues,
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${value.toLocaleString('en-IN')} (${percentage}%)`;
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});

// Show loader on filter submit
document.querySelectorAll('.filter-select').forEach(select => {
    select.addEventListener('change', function() {
        showLoader();
    });
});

function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

// Export to Excel
function exportToExcel() {
    const table = document.getElementById('collectionTable');
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(table, { sheet: "Collection Report" });
    
    // Add summary data as additional sheet
    const summaryData = [
        ['Report Summary'],
        ['Generated on', new Date().toLocaleString()],
        ['Report Type', '{{ ucfirst(request('report_type', 'daily')) }}'],
        ['Fee Type', '{{ request('fee_type', 'all') }}'],
        ['Year', '{{ request('year', date('Y')) }}'],
        [''],
        ['Total Collection', '₹{{ number_format($totalCollection, 2) }}'],
        ['Total Paid Count', '{{ number_format($totalPaidCount) }}'],
        ['Total Pending', '₹{{ number_format($totalPending, 2) }}'],
        ['Overdue Count', '{{ number_format($totalOverdueCount) }}'],
        ['Collection Rate', '{{ number_format($collectionRate, 1) }}%']
    ];
    
    const wsSummary = XLSX.utils.aoa_to_sheet(summaryData);
    XLSX.utils.book_append_sheet(wb, wsSummary, "Summary");
    XLSX.utils.book_append_sheet(wb, ws, "Collection Details");
    
    const fileName = `Fee_Collection_Report_{{ date('Y-m-d') }}.xlsx`;
    XLSX.writeFile(wb, fileName);
}

// Print function - Enhanced version
function printReport() {
    // Get the report content
    const reportContent = document.querySelector('.container-fluid').cloneNode(true);
    
    // Remove filter section and buttons from the cloned content
    const filterSection = reportContent.querySelector('.filter-section');
    const exportButtons = reportContent.querySelector('.export-buttons');
    
    if (filterSection) filterSection.remove();
    if (exportButtons) exportButtons.remove();
    
    // Create a print-friendly HTML structure
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Fee Analytics Report - {{ now()->format('d M Y') }}</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
            <style>
                body {
                    padding: 20px;
                    font-family: Arial, sans-serif;
                }
                .dashboard-header {
                    background: #4361ee;
                    color: white;
                    padding: 20px;
                    border-radius: 10px;
                    margin-bottom: 20px;
                }
                .stats-container {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 15px;
                    margin-bottom: 20px;
                }
                .stat-card {
                    border: 1px solid #ddd;
                    padding: 15px;
                    border-radius: 10px;
                    background: white;
                }
                .chart-container {
                    border: 1px solid #ddd;
                    padding: 15px;
                    border-radius: 10px;
                    margin-bottom: 20px;
                    page-break-inside: avoid;
                }
                .data-table-container {
                    border: 1px solid #ddd;
                    padding: 15px;
                    border-radius: 10px;
                    margin-top: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f5f5f5;
                }
                .amount-positive {
                    color: #10b981;
                }
                @media print {
                    body {
                        padding: 0;
                    }
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="no-print" style="text-align: center; margin-bottom: 20px;">
                <button onclick="window.print()" class="btn btn-primary">Print</button>
                <button onclick="window.close()" class="btn btn-secondary">Close</button>
            </div>
            ${reportContent.outerHTML}
            <div class="no-print" style="text-align: center; margin-top: 20px;">
                <p>Report generated on: {{ now()->format('d M Y, h:i A') }}</p>
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
}

// Initialize DataTables after jQuery is loaded
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#collectionTable').DataTable({
            pageLength: 15,
            ordering: true,
            searching: true,
            responsive: true,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
            }
        });
    }
});

// Auto-refresh data every 5 minutes (optional)
let refreshInterval = setInterval(function() {
    if (!document.hidden) {
        location.reload();
    }
}, 300000);

// Clear interval on page unload to prevent memory leaks
window.addEventListener('beforeunload', function() {
    clearInterval(refreshInterval);
});
</script>
@endsection