@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .info-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .info-label {
        color: #6c757d;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: #495057;
    }
    
    .installment-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    
    .installment-card:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .installment-card.paid {
        border-left: 4px solid #198754;
    }
    
    .installment-card.pending {
        border-left: 4px solid #ffc107;
    }
    
    .installment-card.overdue {
        border-left: 4px solid #dc3545;
    }
    
    .installment-card.cancelled {
        border-left: 4px solid #6c757d;
    }
    
    .status-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 8px;
    }
    
    .status-paid { background-color: #198754; }
    .status-pending { background-color: #ffc107; }
    .status-overdue { background-color: #dc3545; }
    .status-cancelled { background-color: #6c757d; }
    
    .payment-summary-card {
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        padding: 20px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #dee2e6;
    }
    
    .summary-row.total {
        font-weight: bold;
        font-size: 18px;
        color: #198754;
        border-top: 2px solid #dee2e6;
        margin-top: 10px;
        padding-top: 15px;
    }
    
    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }
    
    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }
    
    .assignee-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
        color: #764ba2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: bold;
        margin-right: 20px;
    }
    
    .amount-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
    }
    
    .amount-paid { background: #d1e7dd; color: #0f5132; }
    .amount-pending { background: #fff3cd; color: #856404; }
    .amount-overdue { background: #f8d7da; color: #842029; }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -22px;
        top: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #0d6efd;
        border: 2px solid white;
    }
    
    .timeline-date {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 5px;
    }
</style>

<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="header-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-2"><i class="fas fa-bus-alt me-2"></i>Transport Fee Assignment Details</h1>
                <p class="mb-0 opacity-75">Reference ID: {{ $feeReferenceId }}</p>
            </div>
            <div>
                <a href="{{ route('admin.transport.assigned-list') }}" class="btn back-btn">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="assignee-avatar">
                {{ substr($assigneeDetails->first_name, 0, 1) }}{{ substr($assigneeDetails->last_name, 0, 1) }}
            </div>
            <div>
                <h2 class="h4 mb-1"> @if($assigneeType == 'student')
                    {{ $assigneeDetails->first_name }} {{ $assigneeDetails->last_name }}
                     @else
                      {{ $assigneeDetails->name }}
                      @endif
                </h2>
                <p class="mb-1">
                    <strong>
                        @if($assigneeType == 'student')
                            Student ID: {{ $assigneeDetails->registration_number }}
                        @else
                            Employee ID: {{ $assigneeDetails->employee_id }}
                            • Designation: {{ $assigneeDetails->designation }}
                        @endif
                    </strong>
                </p>
                <p class="mb-0 opacity-75">
                    @if($assigneeType == 'student')
                        <i class="fas fa-user-graduate me-1"></i>Student
                    @else
                        <i class="fas fa-briefcase me-1"></i>Employee
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Column: Details -->
        <div class="col-md-8">
            <!-- Transport Details -->
            <div class="info-card">
                <h5 class="mb-4"><i class="fas fa-bus me-2"></i>Transport Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Bus Number</div>
                        <div class="info-value">{{ $transport->bus_number ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Route Name</div>
                        <div class="info-value">{{ $transport->route_name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Route Type</div>
                        <div class="info-value">{{ ucfirst($transport->route_type ?? 'N/A') }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Assigned Stop</div>
                        <div class="info-value">{{ $installments[0]->transport_stop_name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Fee Duration</div>
                        <div class="info-value">
                            {{ ucfirst(str_replace('_', ' ', $installments[0]->fee_duration_type ?? 'N/A')) }}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Academic Year</div>
                        <div class="info-value">{{ $academicYear ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="info-card">
                <h5 class="mb-4"><i class="fas fa-address-card me-2"></i>Contact Information</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $assigneeDetails->email ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $assigneeDetails->phone ?? 'N/A' }}</div>
                    </div>
                    @if($assigneeType == 'student')
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Registration Number</div>
                        <div class="info-value">{{ $assigneeDetails->registration_number }}</div>
                    </div>
                    @else
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Employee ID</div>
                        <div class="info-value">{{ $assigneeDetails->employee_id }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Designation</div>
                        <div class="info-value">{{ $assigneeDetails->designation ?? 'N/A' }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Installments -->
            <div class="info-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Installments ({{ count($installments) }})</h5>
                    <span class="badge bg-primary">{{ $installments[0]->fee_duration_type ?? 'N/A' }}</span>
                </div>
                
                @foreach($installments as $installment)
                <div class="installment-card {{ $installment->payment_status }}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <span class="status-indicator status-{{ $installment->payment_status }}"></span>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $installment->installment_name ?? 'Installment ' . $installment->installment_number }}</h6>
                                        <small class="text-muted">#{{ $installment->installment_number }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Amount</div>
                                <div class="info-value fw-bold">₹{{ number_format($installment->transport_fee, 2) }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-label">Due Date</div>
                                <div class="info-value">
                                    {{ \Carbon\Carbon::parse($installment->due_date)->format('d M Y') }}
                                    @if($installment->payment_status == 'overdue')
                                        <span class="badge bg-danger ms-2">Overdue</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <span class="badge bg-{{ $installment->payment_status == 'paid' ? 'success' : ($installment->payment_status == 'overdue' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $installment->payment_status)) }}
                                </span>
                                @if($installment->pay_date)
                                    <div class="small text-muted mt-1">
                                        Paid: {{ \Carbon\Carbon::parse($installment->pay_date)->format('d M Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column: Summary & Actions -->
        <div class="col-md-4">
            <!-- Payment Summary -->
            <div class="payment-summary-card mb-4">
                <h5 class="mb-4"><i class="fas fa-chart-pie me-2"></i>Payment Summary</h5>
                
                <div class="summary-row">
                    <span>Total Fee:</span>
                    <span class="fw-bold">₹{{ number_format($installments->sum('transport_fee'), 2) }}</span>
                </div>
                
                <div class="summary-row">
                    <span>Paid Amount:</span>
                    <span class="fw-bold text-success">₹{{ number_format($totalPaid, 2) }}</span>
                </div>
                
                <div class="summary-row">
                    <span>Pending Amount:</span>
                    <span class="fw-bold text-warning">₹{{ number_format($totalPending, 2) }}</span>
                </div>
                
                @if($totalOverdue > 0)
                <div class="summary-row">
                    <span>Overdue Amount:</span>
                    <span class="fw-bold text-danger">₹{{ number_format($totalOverdue, 2) }}</span>
                </div>
                @endif
                
                @if($installments[0]->discount_amount > 0)
                <div class="summary-row">
                    <span>Discount:</span>
                    <span class="fw-bold text-info">
                        ₹{{ number_format($installments[0]->discount_amount, 2) }}
                        @if($installments[0]->discount_type == 'percentage')
                            ({{ $installments[0]->discount_value }}%)
                        @endif
                    </span>
                </div>
                @endif
                
                <div class="summary-row total">
                    <span>Net Total:</span>
                    <span>₹{{ number_format($installments->sum('transport_fee') - $installments[0]->discount_amount, 2) }}</span>
                </div>
                
                @if($installments[0]->discount_reason)
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Discount Reason: {{ $installments[0]->discount_reason }}
                    </small>
                </div>
                @endif
            </div>

            <!-- Payment Progress -->
            <div class="info-card mb-4">
                <h5 class="mb-4"><i class="fas fa-chart-line me-2"></i>Payment Progress</h5>
                
                @php
                    $totalAmount = $installments->sum('transport_fee');
                    $paidPercentage = $totalAmount > 0 ? ($totalPaid / $totalAmount) * 100 : 0;
                @endphp
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small">Payment Progress</span>
                        <span class="small fw-bold">{{ number_format($paidPercentage, 1) }}%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" style="width: {{ $paidPercentage }}%"></div>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-4">
                        <div class="amount-badge amount-paid">
                            ₹{{ number_format($totalPaid, 0) }}
                        </div>
                        <div class="small text-muted mt-1">Paid</div>
                    </div>
                    <div class="col-4">
                        <div class="amount-badge amount-pending">
                            ₹{{ number_format($totalPending, 0) }}
                        </div>
                        <div class="small text-muted mt-1">Pending</div>
                    </div>
                    @if($totalOverdue > 0)
                    <div class="col-4">
                        <div class="amount-badge amount-overdue">
                            ₹{{ number_format($totalOverdue, 0) }}
                        </div>
                        <div class="small text-muted mt-1">Overdue</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="info-card">
                <h5 class="mb-4"><i class="fas fa-cogs me-2"></i>Actions</h5>
                
                <div class="d-grid gap-2">
                    @if($totalPending > 0)
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
                            <i class="fas fa-money-bill-wave me-2"></i>Record Payment
                        </button>
                    @endif
                    
                    @if($installments[0]->payment_status == 'pending')
                        <a href="#" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Assignment
                        </a>
                        
                        <form action="{{ route('admin.transport.cancel-assigned', $feeReferenceId) }}" method="POST" class="d-grid">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure you want to cancel this transport fee assignment?')">
                                <i class="fas fa-times me-2"></i>Cancel Assignment
                            </button>
                        </form>
                    @endif
                    
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Details
                    </button>
                </div>
            </div>

            <!-- Assignment Timeline -->
            <div class="info-card">
                <h5 class="mb-4"><i class="fas fa-history me-2"></i>Timeline</h5>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-date">Assigned on</div>
                        <div class="fw-bold">{{ $installments[0]->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    
                    @foreach($installments as $installment)
                        @if($installment->payment_status == 'paid')
                        <div class="timeline-item">
                            <div class="timeline-date">Paid on {{ \Carbon\Carbon::parse($installment->pay_date)->format('d M Y') }}</div>
                            <div>Installment {{ $installment->installment_number }} paid</div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Payment recording feature will be implemented here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Record Payment</button>
            </div>
        </div>
    </div>
</div>
@endsection