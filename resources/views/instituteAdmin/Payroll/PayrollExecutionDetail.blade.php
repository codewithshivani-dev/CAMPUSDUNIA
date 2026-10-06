@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<title>Payroll Execution Details</title>

<style>
    .execution-detail-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .status-badge {
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .status-completed { background: #d1fae5; color: #059669; }
    .status-partial { background: #fed7aa; color: #c2410c; }
    .status-failed { background: #fee2e2; color: #dc2626; }
    .status-processing { background: #dbeafe; color: #2563eb; }
    
    .slip-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #3b82f6;
        transition: all 0.3s;
    }
    
    .slip-card:hover {
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .info-row {
        padding: 8px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .info-label {
        font-weight: 600;
        color: #475569;
        width: 180px;
        display: inline-block;
    }
    
    .summary-stats {
        display: flex;
        gap: 20px;
        margin-top: 15px;
    }
    
    .stat-box {
        flex: 1;
        background: #f8fafc;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: 700;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 0.85rem;
        margin-top: 5px;
    }
    
    .bg-success-light { background: #f0fdf4; }
    .bg-danger-light { background: #fef2f2; }
    .bg-info-light { background: #eff6ff; }
    
    .text-success-dark { color: #059669; }
    .text-danger-dark { color: #dc2626; }
    .text-info-dark { color: #2563eb; }
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-file-alt text-primary"></i> 
            Payroll Execution Details
        </h2>
        <div>
            <a href="{{ route('execution.history') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to History
            </a>
            <a href="{{ route('payroll.viewPage') }}" class="btn btn-primary ms-2">
                <i class="fas fa-play-circle"></i> New Execution
            </a>
        </div>
    </div>
    
    <!-- Execution Summary -->
    <div class="execution-detail-card">
        <h4 class="mb-3">
            <i class="fas fa-info-circle text-primary"></i> 
            Execution Summary
        </h4>
        <div class="row">
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Execution ID:</span>
                    <code>{{ $execution->execution_id }}</code>
                </div>
                <div class="info-row">
                    <span class="info-label">Execution Type:</span>
                    <span class="badge bg-secondary">{{ ucfirst($execution->execution_type) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Period:</span>
                    {{ \Carbon\Carbon::create($execution->year, $execution->month, 1)->format('F Y') }}
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="status-badge status-{{ $execution->status }}">
                        {{ ucfirst($execution->status) }}
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-row">
                    <span class="info-label">Planned Date:</span>
                    {{ \Carbon\Carbon::parse($execution->planned_execution_date)->format('d M Y h:i A') }}
                </div>
                <div class="info-row">
                    <span class="info-label">Actual Date:</span>
                    {{ \Carbon\Carbon::parse($execution->actual_execution_date)->format('d M Y h:i A') }}
                </div>
                <div class="info-row">
                    <span class="info-label">Executed By:</span>
                    {{ $execution->executedBy->name ?? $execution->executedBy->email ?? 'System' }}
                </div>
                @if($execution->department)
                <div class="info-row">
                    <span class="info-label">Department:</span>
                    {{ $execution->department->department ?? 'N/A' }}
                </div>
                @endif
                @if($execution->employee)
                <div class="info-row">
                    <span class="info-label">Employee:</span>
                    {{ $execution->employee->name ?? 'N/A' }} 
                    ({{ $execution->employee->employee_code ?? 'N/A' }})
                </div>
                @endif
            </div>
        </div>
        
        @if($execution->notes)
        <div class="mt-3">
            <strong>Notes:</strong>
            <p class="text-muted mt-1">{{ $execution->notes }}</p>
        </div>
        @endif
    </div>
    
    <!-- Execution Statistics -->
    @if($execution->execution_summary)
    <div class="execution-detail-card">
        <h4 class="mb-3">
            <i class="fas fa-chart-bar text-success"></i> 
            Execution Statistics
        </h4>
        <div class="summary-stats">
            <div class="stat-box bg-info-light">
                <div class="stat-number text-info-dark">
                    {{ $execution->execution_summary['total_employees'] ?? 0 }}
                </div>
                <div class="stat-label">Total Employees</div>
            </div>
            <div class="stat-box bg-success-light">
                <div class="stat-number text-success-dark">
                    {{ $execution->execution_summary['successful'] ?? 0 }}
                </div>
                <div class="stat-label">Successful</div>
            </div>
            <div class="stat-box bg-danger-light">
                <div class="stat-number text-danger-dark">
                    {{ $execution->execution_summary['failed'] ?? 0 }}
                </div>
                <div class="stat-label">Failed</div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Generated Salary Slips -->
    <div class="execution-detail-card">
        <h4 class="mb-3">
            <i class="fas fa-file-invoice-dollar text-success"></i> 
            Generated Salary Slips 
            <span class="badge bg-primary">{{ $slips->count() }}</span>
        </h4>
        
        @if($slips->count() > 0)
            @foreach($slips as $slip)
            <div class="slip-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">
                                    <i class="fas fa-user-circle text-primary"></i> 
                                    <strong>{{ $slip->employee_name }}</strong>
                                    <small class="text-muted">({{ $slip->employee_code }})</small>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-hashtag"></i> Slip ID: {{ $slip->slip_id }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> 
                                    Generated: {{ \Carbon\Carbon::parse($slip->generated_at)->format('d M Y h:i A') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <div class="mb-2">
                                    <div><strong>Basic Salary:</strong> ₹{{ number_format($slip->basic_salary ?? 0, 2) }}</div>
                                    <div><strong>Gross Salary:</strong> ₹{{ number_format($slip->gross_salary ?? 0, 2) }}</div>
                                </div>
                                <div class="mb-2">
                                    <div><strong>Total Deductions:</strong> ₹{{ number_format($slip->total_deductions ?? 0, 2) }}</div>
                                    <div><strong>Net Salary:</strong> <span class="text-success fw-bold">₹{{ number_format($slip->net_salary ?? 0, 2) }}</span></div>
                                </div>
                                <div>
                                    <strong>Final Payable:</strong> 
                                    <span class="text-primary fw-bold">₹{{ number_format($slip->final_payable ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-end">
                    <button class="btn btn-sm btn-outline-primary" onclick="viewSlip('{{ $slip->slip_id }}', '{{ $slip->employee_id }}', '{{ $slip->year }}', '{{ $slip->month }}')">
                        <i class="fas fa-eye"></i> View Full Slip
                    </button>
                    <!-- <button class="btn btn-sm btn-outline-secondary" onclick="downloadSlip('{{ $slip->slip_id }}')">
                        <i class="fas fa-download"></i> Download
                    </button> -->
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-file-invoice fa-3x mb-3"></i>
                <p>No salary slips were generated in this execution.</p>
                @if($execution->status == 'failed')
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        The execution failed. Please check the system logs for more details.
                    </div>
                @elseif($execution->status == 'partial')
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        Some employees failed to process. Check the failed employees list below.
                    </div>
                @endif
            </div>
        @endif
    </div>
    
    <!-- Failed Employees (if any) -->
    @if($execution->failed_employees && count($execution->failed_employees) > 0)
    <div class="execution-detail-card">
        <h4 class="mb-3 text-danger">
            <i class="fas fa-exclamation-triangle"></i> 
            Failed Employees ({{ count($execution->failed_employees) }})
        </h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Employee ID/Code</th>
                        <th>Reason for Failure</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($execution->failed_employees as $index => $failed)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $failed['employee_name'] ?? 'Unknown' }}</strong>
                        </td>
                        <td>{{ $failed['employee_id'] ?? $failed['employee_code'] ?? 'N/A' }}</td>
                        <td class="text-danger">
                            <i class="fas fa-times-circle"></i> 
                            {{ $failed['reason'] ?? 'Unknown error occurred' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
    <!-- Action Buttons -->
    <div class="mt-4 text-center">
        @if($execution->status == 'partial' || $execution->status == 'failed')
            <button class="btn btn-warning" onclick="retryFailed()">
                <i class="fas fa-redo-alt"></i> Retry Failed Executions
            </button>
        @endif
        <button class="btn btn-info" onclick="exportReport()">
            <i class="fas fa-download"></i> Export Report
        </button>
        <button class="btn btn-secondary" onclick="window.print()">
            <i class="fas fa-print"></i> Print Details
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function viewSlip(slipId, employeeId, year, month) {
    // Redirect to the salary slip view route
    window.location.href = "{{ route('final-salary-slips.index') }}?slip_id=" + slipId + "&employee_id=" + employeeId + "&year=" + year + "&month=" + month;
}

function downloadSlip(slipId) {
    Swal.fire({
        title: 'Download Salary Slip',
        text: 'Download functionality will be implemented soon.',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function retryFailed() {
    Swal.fire({
        title: 'Retry Failed Executions',
        text: 'This will attempt to execute payroll for failed employees only.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Retry',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#f59e0b'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Retrying failed executions',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Add your retry logic here
            setTimeout(() => {
                Swal.fire({
                    title: 'Success!',
                    text: 'Retry process completed.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            }, 2000);
        }
    });
}

function exportReport() {
    Swal.fire({
        title: 'Export Report',
        text: 'Choose export format',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonText: 'Excel',
        denyButtonText: 'PDF',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Exporting to Excel...', 'Please wait', 'info');
            // Add Excel export logic
        } else if (result.isDenied) {
            Swal.fire('Exporting to PDF...', 'Please wait', 'info');
            // Add PDF export logic
        }
    });
}

// Show any flash messages
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session('success') }}',
    confirmButtonColor: '#10b981',
    timer: 3000
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: '{{ session('error') }}',
    confirmButtonColor: '#dc2626'
});
@endif
</script>
@endsection