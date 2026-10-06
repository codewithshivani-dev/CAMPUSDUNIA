@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<title> Salary Slips</title>

<style>
.page-header {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    padding: 20px;
    border-radius: 10px;
    color: white;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

.filter-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    background: white;
}

.filter-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
}

.filter-actions {
    display: flex;
    gap: 10px;
    align-items: flex-end;
}

.btn-primary {
    background: #4361ee;
    color: white;
    padding: 8px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 8px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-secondary:hover {
    background: #5a6268;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.summary-card {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.2s;
}

.summary-card:hover {
    transform: translateY(-3px);
}

.summary-card h3 {
    margin: 0;
    font-size: 28px;
    color: #4361ee;
}

.summary-card p {
    margin: 5px 0 0;
    color: #666;
    font-size: 13px;
}

.slip-table {
    width: 100%;
    border-collapse: collapse;
}

.slip-table th,
.slip-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.slip-table th {
    background: #f8f9fa;
    font-weight: 600;
    position: sticky;
    top: 0;
}

.status-paid {
    background: #d1fae5;
    color: #059669;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    display: inline-block;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    display: inline-block;
}

.btn-view {
    background: #4361ee;
    color: white;
    padding: 4px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-view:hover {
    background: #3a0ca3;
    color: white;
}

/* Loader */
.page-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.page-loader.active {
    display: flex;
}

.loader-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #4361ee;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    background: white;
    padding: 10px;
    border-radius: 50%;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .summary-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .slip-table {
        font-size: 12px;
    }
    
    .slip-table th,
    .slip-table td {
        padding: 8px;
    }
}
</style>

<!-- Page Loader -->
<div id="pageLoader" class="page-loader">
    <div class="loader-spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-file-invoice-dollar"></i>  Salary Slips</h1>
        <div>
            <button class="btn btn-light" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button class="btn btn-light" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Filter Section - Auto filter on change -->
    <div class="filter-section">
        <form method="GET" action="{{ route('final-salary-slips.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label><i class="fas fa-calendar-alt"></i> Year</label>
                    <select name="year" class="filter-input" onchange="applyFilter()">
                        @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-calendar-month"></i> Month</label>
                    <select name="month" class="filter-input" onchange="applyFilter()">
                        @foreach($months as $m => $mName)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $mName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-building"></i> Department</label>
                    <select name="department_id" class="filter-input" onchange="applyFilter()">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}" {{ $departmentId == $dept->department_id ? 'selected' : '' }}>
                            {{ $dept->department }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-credit-card"></i> Payment Status</label>
                    <select name="payment_status" class="filter-input" onchange="applyFilter()">
                        <option value="">All</option>
                        <option value="pending" {{ $paymentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $paymentStatus == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <a href="{{ route('final-salary-slips.index') }}" class="btn-secondary">
                        <i class="fas fa-undo-alt"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>{{ $summary['total_slips'] }}</h3>
            <p>Total Slips</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['total_amount'], 0) }}</h3>
            <p>Total Amount</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['paid_count'] }}</h3>
            <p>Paid</p>
        </div>
        <div class="summary-card">
            <h3>₹{{ number_format($summary['paid_amount'], 0) }}</h3>
            <p>Paid Amount</p>
        </div>
    </div>

    <!-- Salary Slips Table -->
    <div class="card">
        <div class="card-header">
            <h5>Salary Slips for {{ Carbon\Carbon::create($year, $month)->format('F Y') }}</h5>
        </div>
        <div class="card-body">
            @if($salarySlips->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                <h4>No salary slips found</h4>
                <!-- <p>Run the command to generate salary slips:</p>
                <code>php artisan payroll:generate-final-slips --year={{ $year }} --month={{ $month }}</code> -->
            </div>
            @else
            <div class="table-responsive">
                <table class="slip-table">
                    <thead>
                        <tr>
                            <th>Slip ID</th>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Basic Salary</th>
                            <th>Gross Salary</th>
                            <th>Deductions</th>
                            <th>Net Payable</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salarySlips as $slip)
                        <tr>
                            <td>{{ $slip->slip_id }}</td>
                            <td>{{ $slip->employee_code ?? $slip->employee_id }}</td>
                            <td>{{ $slip->employee_name }}</td>
                            <td>₹{{ number_format($slip->basic_salary, 2) }}</td>
                            <td>₹{{ number_format($slip->gross_salary, 2) }}</td>
                            <td class="text-danger">-₹{{ number_format($slip->total_deductions, 2) }}</td>
                            <td class="text-success fw-bold">₹{{ number_format($slip->final_payable, 2) }}</td>
                            <td>
                                <span class="status-{{ $slip->payment_status }}">
                                    {{ ucfirst($slip->payment_status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('final-salary-slips.show', $slip->slip_id) }}" class="btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #f8f9fa; font-weight: bold;">
                            <td colspan="6" class="text-end">Total:</td>
                            <td class="text-success">₹{{ number_format($salarySlips->sum('final_payable'), 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function showLoader() {
        document.getElementById('pageLoader').classList.add('active');
    }
    
    function hideLoader() {
        document.getElementById('pageLoader').classList.remove('active');
    }
    
    function applyFilter() {
        showLoader();
        document.getElementById('filterForm').submit();
    }
    
    // Auto-hide loader when page loads
    window.addEventListener('load', function() {
        hideLoader();
    });
    
    // Also handle when page is loaded from back/forward cache
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoader();
        }
    });
</script>

@endsection