@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

@php
use Carbon\Carbon;
@endphp

<title>Salary Slips | Salary Archive</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root {
    --primary: #4361ee;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --secondary: #64748b;
    --info: #0ea5e9;
}

/* Header */
.page-header {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: white;
    padding: 30px 35px;
    border-radius: 20px;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
}

.page-header h2 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
}

.page-header p {
    margin: 0;
    opacity: 0.8;
    font-size: 14px;
}

/* Filter Bar */
.filter-bar {
    background: white;
    border-radius: 16px;
    padding: 20px 25px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-group label {
    font-weight: 600;
    font-size: 14px;
    color: #475569;
}

.filter-select {
    padding: 10px 20px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    min-width: 160px;
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary);
}

.btn-filter {
    padding: 10px 24px;
    border-radius: 12px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary-custom {
    background: var(--primary);
    color: white;
}

.btn-primary-custom:hover {
    background: #3a0ca3;
    transform: translateY(-2px);
}

.btn-reset {
    background: #64748b;
    color: white;
}

.btn-reset:hover {
    background: #475569;
}

/* Summary Cards */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.summary-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.summary-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.summary-icon.total {
    background: #eef2ff;
    color: var(--primary);
}

.summary-icon.earned {
    background: #d1fae5;
    color: var(--success);
}

.summary-icon.months {
    background: #fed7aa;
    color: var(--warning);
}

.summary-info h3 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: #1e293b;
}

.summary-info p {
    margin: 0;
    font-size: 13px;
    color: #64748b;
}

/* Timeline View */
.timeline-container {
    background: white;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    margin-bottom: 30px;
}

.timeline-header {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 18px 25px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.timeline-header h4 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
}

.timeline-header h4 i {
    color: var(--primary);
    margin-right: 10px;
}

.timeline-grid {
    padding: 25px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

/* Month Card */
.month-card {
    background: #f8fafc;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
    position: relative;
}

.month-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.month-card.generated {
    border-left: 4px solid var(--success);
}

.month-card.pending {
    border-left: 4px solid var(--warning);
    opacity: 0.7;
}

.month-header {
    background: white;
    padding: 16px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.month-name {
    font-weight: 700;
    font-size: 16px;
    color: #1e293b;
}

.month-badge {
    font-size: 11px;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
}

.badge-generated {
    background: #d1fae5;
    color: #059669;
}

.badge-pending {
    background: #fef3c7;
    color: #d97706;
}

.month-body {
    padding: 16px 20px;
}

.slip-detail {
    margin-bottom: 12px;
}

.slip-detail .label {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 4px;
}

.slip-detail .value {
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
}

.slip-detail .small-value {
    font-size: 13px;
    color: #475569;
}

.divider {
    height: 1px;
    background: #e2e8f0;
    margin: 12px 0;
}

.btn-view-slip {
    width: 100%;
    padding: 10px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 13px;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-view-slip:hover {
    background: #3a0ca3;
    transform: translateY(-2px);
}

.btn-view-slip:disabled {
    background: #cbd5e1;
    cursor: not-allowed;
    transform: none;
}

.btn-download {
    background: var(--success);
}

.btn-download:hover {
    background: #059669;
}

/* No Data */
.no-data {
    text-align: center;
    padding: 60px;
    background: #f8fafc;
    border-radius: 20px;
}

.no-data i {
    font-size: 64px;
    color: #94a3b8;
    margin-bottom: 20px;
}

.no-data h5 {
    font-size: 20px;
    color: #1e293b;
    margin-bottom: 10px;
}

/* Modal */
.salary-slip-modal {
    font-family: 'Segoe UI', Arial, sans-serif;
}

.salary-slip-header {
    text-align: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e2e8f0;
}

.salary-slip-header h3 {
    color: #1e293b;
    font-weight: 700;
    margin-bottom: 8px;
}

.salary-slip-company {
    color: #64748b;
    font-size: 13px;
}

.salary-slip-details {
    margin-bottom: 25px;
}

.salary-slip-section {
    margin-bottom: 20px;
}

.salary-slip-section-title {
    font-weight: 700;
    font-size: 14px;
    color: var(--primary);
    margin-bottom: 12px;
    padding-bottom: 6px;
    border-bottom: 1px solid #e2e8f0;
}

.salary-slip-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
}

.salary-slip-label {
    font-size: 13px;
    color: #64748b;
}

.salary-slip-value {
    font-weight: 600;
    color: #1e293b;
}

.salary-slip-total {
    background: #f0fdf4;
    padding: 12px;
    border-radius: 12px;
    margin-top: 15px;
}

.salary-slip-total .salary-slip-row {
    border-bottom: none;
    font-weight: 700;
}

.salary-slip-footer {
    text-align: center;
    margin-top: 25px;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
    font-size: 11px;
    color: #94a3b8;
}

@media (max-width: 768px) {
    .timeline-grid {
        grid-template-columns: 1fr;
    }
    
    .summary-grid {
        grid-template-columns: 1fr;
    }
    
    .page-header h2 {
        font-size: 22px;
    }
}

/* Print Styles */
@media print {
    .filter-bar, .btn, .no-print, .summary-card .btn-view-slip, .month-card .btn-view-slip {
        display: none !important;
    }
    
    .month-card {
        break-inside: avoid;
        page-break-inside: avoid;
        border: 1px solid #ddd;
    }
    
    .timeline-grid {
        display: block;
    }
    
    .month-card {
        margin-bottom: 20px;
    }
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-file-invoice-dollar me-3"></i>Salary Slips</h2>
            <p>View and download all your finalized salary slips for the academic year</p>
        </div>
        <div>
            <span class="badge bg-light text-dark px-3 py-2">
                <i class="fas fa-user"></i> {{ $employee->employee_code }}
            </span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> Academic Year</label>
            <select id="academicYear" class="filter-select">
                @foreach($availableYears as $year)
                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
                @endforeach
            </select>
            <button class="btn-filter btn-primary-custom" onclick="applyFilter()">
                <i class="fas fa-search"></i> View
            </button>
            <button class="btn-filter btn-reset" onclick="resetFilter()">
                <i class="fas fa-undo-alt"></i> Reset
            </button>
        </div>
        <div>
            <button class="btn-filter btn-primary-custom" onclick="printAllSlips()">
                <i class="fas fa-print"></i> Print Summary
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-icon total">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="summary-info">
                <h3>{{ $salarySlips->sum(function($slips) { return $slips->count(); }) }}</h3>
                <p>Total Salary Slips</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon earned">
                <i class="fas fa-rupee-sign"></i>
            </div>
            <div class="summary-info">
                <h3>₹{{ number_format($salarySlips->sum(function($slips) { 
                    return $slips->sum('final_payable'); 
                }), 2) }}</h3>
                <p>Total Earnings (YTD)</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon months">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="summary-info">
                <h3>{{ $salarySlips->sum(function($slips) { return $slips->count(); }) }}/12</h3>
                <p>Months Processed</p>
            </div>
        </div>
    </div>

    <!-- Timeline View - All Months -->
    <div class="timeline-container">
        <div class="timeline-header">
            <h4><i class="fas fa-timeline"></i> Salary Slip Timeline - {{ $selectedYear }}</h4>
            <small class="text-muted">{{ $salarySlips->sum(function($slips) { return $slips->count(); }) }} slips generated</small>
        </div>
        <div class="timeline-grid">
            @php
                // Generate all months for the academic year (April to March)
                $academicMonths = [];
                for ($m = 4; $m <= 12; $m++) {
                    $academicMonths[] = ['month' => $m, 'name' => Carbon::create()->month($m)->format('F'), 'is_generated' => false];
                }
                for ($m = 1; $m <= 3; $m++) {
                    $academicMonths[] = ['month' => $m, 'name' => Carbon::create()->month($m)->format('F'), 'is_generated' => false];
                }
                
                // Mark generated months
                $allSlips = collect();
                foreach ($salarySlips as $year => $slips) {
                    foreach ($slips as $slip) {
                        $allSlips->push($slip);
                    }
                }
                
                $generatedMonths = $allSlips->keyBy(function($slip) {
                    return $slip->month;
                });
            @endphp

            @foreach($academicMonths as $monthInfo)
                @php
                    $slip = $generatedMonths->get($monthInfo['month']);
                    $hasSlip = $slip ? true : false;
                @endphp
                <div class="month-card {{ $hasSlip ? 'generated' : 'pending' }}">
                    <div class="month-header">
                        <span class="month-name">{{ $monthInfo['name'] }}</span>
                        <span class="month-badge {{ $hasSlip ? 'badge-generated' : 'badge-pending' }}">
                            {{ $hasSlip ? 'Generated' : 'Pending' }}
                        </span>
                    </div>
                    <div class="month-body">
                        @if($hasSlip)
                            <div class="slip-detail">
                                <div class="label">Net Payable</div>
                                <div class="value">₹{{ number_format($slip->final_payable, 2) }}</div>
                            </div>
                            <div class="slip-detail">
                                <div class="label">Slip ID</div>
                                <div class="small-value">{{ $slip->slip_id }}</div>
                            </div>
                            @if($slip->generated_date)
                            <div class="slip-detail">
                                <div class="label">Generated On</div>
                                <div class="small-value">{{ Carbon::parse($slip->generated_date)->format('d M Y') }}</div>
                            </div>
                            @endif
                            <div class="divider"></div>
                            <button class="btn-view-slip" onclick="viewSalarySlip({{ $slip->year }}, {{ $slip->month }})">
                                <i class="fas fa-eye"></i> View Slip
                            </button>
                            <button class="btn-view-slip btn-download mt-2" onclick="downloadSalarySlip('{{ $slip->slip_id }}')">
                                <i class="fas fa-download"></i> Download PDF
                            </button>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-hourglass-half fa-2x text-muted mb-2 d-block"></i>
                                <span class="text-muted small">Not yet generated</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Year Details -->
    @if($salarySlips->count() > 0)
    <div class="timeline-container">
        <div class="timeline-header">
            <h4><i class="fas fa-chart-line"></i> Year-wise Summary</h4>
        </div>
        <div class="section-body p-4">
            @foreach($salarySlips as $year => $slips)
            <div class="mb-4">
                <h5 class="fw-bold mb-3" style="color: var(--primary);">
                    <i class="fas fa-calendar-alt"></i> Financial Year {{ $year }}-{{ $year+1 }}
                </h5>
                <div class="row">
                    @foreach($slips as $slip)
                    @php
                        $monthName = Carbon::create()->month($slip->month)->format('F');
                    @endphp
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="fw-bold">{{ $monthName }}</div>
                            <div class="text-success mt-1">₹{{ number_format($slip->final_payable, 2) }}</div>
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="viewSalarySlip({{ $slip->year }}, {{ $slip->month }})">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Salary Slip Modal -->
<div class="modal fade" id="salarySlipModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar me-2"></i>Salary Slip</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="salarySlipModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2">Loading salary slip...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printSalarySlip()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentSlipData = null;

function applyFilter() {
    const year = document.getElementById('academicYear').value;
    const url = new URL(window.location.href);
    url.searchParams.set('academic_year', year);
    window.location.href = url.toString();
}

function resetFilter() {
    window.location.href = window.location.href.split('?')[0];
}

function viewSalarySlip(year, month) {
    const modal = new bootstrap.Modal(document.getElementById('salarySlipModal'));
    const modalBody = document.getElementById('salarySlipModalBody');
    
    modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div><p class="mt-2">Loading salary slip...</p></div>';
    modal.show();
    
    fetch(`/employee/salary-slip?year=${year}&month=${month}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentSlipData = data.data;
                const slip = data.data.slip;
                const details = data.data.details;
                console.log('Salary Slip Details:', details);
                const isFinal = data.data.is_final || true;
                
                let earningsHtml = '';
                if (details.earnings_breakdown) {
                    Object.entries(details.earnings_breakdown).forEach(([key, value]) => {
                        if (value > 0) {
                            earningsHtml += `<div class="salary-slip-row"><span class="salary-slip-label">${key.replace(/_/g, ' ').toUpperCase()}</span><span class="salary-slip-value">₹${parseFloat(value).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span></div>`;
                        }
                    });
                }
                
                let deductionsHtml = '';
                if (details.standard_deductions_breakdown) {
                    Object.entries(details.standard_deductions_breakdown).forEach(([key, value]) => {
                        if (value > 0) {
                            let displayName = key.replace(/_/g, ' ').toUpperCase();
                            deductionsHtml += `<div class="salary-slip-row"><span class="salary-slip-label">${displayName}</span><span class="salary-slip-value">₹${parseFloat(value).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span></div>`;
                        }
                    });
                }
                
                let attendanceDeductionsHtml = '';
                if (details.attendance_deductions_breakdown) {
                    Object.entries(details.attendance_deductions_breakdown).forEach(([key, value]) => {
                        if (value > 0) {
                            let displayName = key.replace(/_/g, ' ').toUpperCase();
                            attendanceDeductionsHtml += `<div class="salary-slip-row"><span class="salary-slip-label">${displayName}</span><span class="salary-slip-value">₹${parseFloat(value).toLocaleString('en-IN', {minimumFractionDigits: 2})}</span></div>`;
                        }
                    });
                }
                
                const totalDeductions = (slip.gross_salary || 0) - (slip.final_payable || slip.payable_salary || 0);
                
                // ✅ FIXED: details.department is a string, not an object
                const departmentName = details.department || 'N/A';
                
                const html = `
                    <div class="salary-slip-modal">
                        <div class="salary-slip-header">
                            <h3>FINAL SALARY SLIP</h3>
                            <div class="salary-slip-company">For the month of ${new Date(slip.salary_month || `${year}-${month}-01`).toLocaleDateString('en-US', {month: 'long', year: 'numeric'})}</div>
                        </div>
                        
                        <div class="salary-slip-details">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div><strong>Employee Name:</strong> ${slip.employee_name || slip.name}</div>
                                    <div><strong>Employee ID:</strong> ${slip.employee_id}</div>
                                    <div><strong>Department:</strong> ${departmentName}</div>
                                </div>
                                <div class="col-md-6">
                                    <div><strong>Slip ID:</strong> ${slip.slip_id || slip.salaryslip_id}</div>
                                    <div><strong>Generated On:</strong> ${new Date(slip.generated_at || slip.created_at).toLocaleDateString()}</div>
                                    <div><strong>Status:</strong> <span class="badge bg-success">FINALIZED</span></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="salary-slip-section">
                                    <div class="salary-slip-section-title"><i class="fas fa-arrow-up text-success"></i> Earnings</div>
                                    <div class="salary-slip-section-body">
                                        ${earningsHtml || '<div class="text-muted">No earnings data</div>'}
                                        <div class="salary-slip-total">
                                            <div class="salary-slip-row">
                                                <span class="salary-slip-label"><strong>Gross Salary</strong></span>
                                                <span class="salary-slip-value"><strong>₹${parseFloat(slip.gross_salary || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="salary-slip-section">
                                    <div class="salary-slip-section-title"><i class="fas fa-arrow-down text-danger"></i> Deductions</div>
                                    <div class="salary-slip-section-body">
                                        ${deductionsHtml || ''}
                                        ${attendanceDeductionsHtml ? `<div><strong class="small">Attendance Deductions:</strong></div>${attendanceDeductionsHtml}` : ''}
                                        <div class="salary-slip-total">
                                            <div class="salary-slip-row">
                                                <span class="salary-slip-label"><strong>Total Deductions</strong></span>
                                                <span class="salary-slip-value"><strong>₹${parseFloat(totalDeductions).toLocaleString('en-IN', {minimumFractionDigits: 2})}</strong></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="salary-slip-total text-center mt-4" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
                            <div class="salary-slip-row justify-content-center">
                                <span class="salary-slip-label"><strong>NET PAYABLE</strong></span>
                                <span class="salary-slip-value"><strong>₹${parseFloat(slip.final_payable || slip.payable_salary || 0).toLocaleString('en-IN', {minimumFractionDigits: 2})}</strong></span>
                            </div>
                        </div>
                        
                        <div class="salary-slip-footer">
                            This is a computer generated salary slip. No signature required.
                        </div>
                    </div>
                `;
                modalBody.innerHTML = html;
            } else {
                modalBody.innerHTML = `<div class="alert alert-warning">${data.message || 'Salary slip not available'}</div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = `<div class="alert alert-danger">Error loading salary slip</div>`;
        });
}

function downloadSalarySlip(slipId) {
    window.open(`/employee/salary-slip/download?slip_id=${slipId}`, '_blank');
}

function printSalarySlip() {
    const printContent = document.querySelector('#salarySlipModalBody').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Salary Slip</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
            <style>
                body { padding: 20px; font-family: Arial, sans-serif; }
                .salary-slip-modal { max-width: 1000px; margin: 0 auto; }
                .salary-slip-header { text-align: center; margin-bottom: 20px; }
                .salary-slip-section-title { font-weight: bold; margin-top: 15px; margin-bottom: 10px; }
                .salary-slip-row { display: flex; justify-content: space-between; padding: 5px 0; }
                .salary-slip-total { background: #f0fdf4; padding: 10px; border-radius: 8px; margin-top: 10px; }
                @media print {
                    .btn, .modal-footer { display: none; }
                }
            </style>
        </head>
        <body>
            ${printContent}
            <script>window.print();<\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

function printAllSlips() {
    window.print();
}
</script>

@endsection