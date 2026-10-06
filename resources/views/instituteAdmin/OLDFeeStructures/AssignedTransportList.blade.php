@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-pending { background-color: #fff3cd; color: #856404; }
    .status-paid { background-color: #d1e7dd; color: #0f5132; }
    .status-partially_paid { background-color: #cfe2ff; color: #084298; }
    .status-overdue { background-color: #f8d7da; color: #842029; }
    .status-cancelled { background-color: #e2e3e5; color: #383d41; }
    
    .type-badge {
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .type-student { background-color: #d0f0fd; color: #0c5460; }
    .type-employee { background-color: #d4edda; color: #155724; }
    
    .filter-card {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .summary-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    
    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .summary-icon-total { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .summary-icon-pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .summary-icon-paid { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .summary-icon-overdue { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
    
    .assignee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #6c757d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }
    
    .assignee-avatar.student { background: #0d6efd; }
    .assignee-avatar.employee { background: #198754; }
    
    .amount-cell {
        font-weight: 600;
        font-size: 14px;
    }
    
    .payment-progress {
        height: 6px;
        border-radius: 3px;
        background-color: #e9ecef;
        overflow: hidden;
    }
    
    .payment-progress-bar {
        height: 100%;
        border-radius: 3px;
    }
    
    .progress-paid { background-color: #198754; }
    .progress-pending { background-color: #ffc107; }
    
    .action-dropdown .btn {
        padding: 4px 12px;
        font-size: 12px;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }
    
    .no-data i {
        font-size: 60px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box .form-control {
        padding-left: 45px;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
</style>

<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><i class="fas fa-bus-alt me-2"></i>Assigned Transport Fees</h3>
            <p class="text-muted mb-0">View all assigned transport fees for students and employees</p>
        </div>
        <div>
            <a href="{{ route('admin.transport.assign-fee.form') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Assign New Fee
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="summary-icon summary-icon-total me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Total Assignments</h6>
                            <h4 class="card-title mb-0">{{ $assignedFees->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="summary-icon summary-icon-pending me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Pending Fees</h6>
                            <h4 class="card-title mb-0">
                                {{ $assignedFees->where('payment_status', 'pending')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="summary-icon summary-icon-paid me-3">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Paid Fees</h6>
                            <h4 class="card-title mb-0">
                                {{ $assignedFees->where('payment_status', 'paid')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="summary-icon summary-icon-overdue me-3">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle mb-2 text-muted">Overdue Fees</h6>
                            <h4 class="card-title mb-0">
                                {{ $assignedFees->where('payment_status', 'overdue')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-card">
        <h6 class="mb-3"><i class="fas fa-filter me-2"></i>Filter Assignments</h6>
        <form action="{{ route('admin.transport.assigned-list.filter') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Assignee Type</label>
                    <select name="assignee_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="student" {{ request('assignee_type') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="employee" {{ request('assignee_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partially_paid" {{ request('payment_status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                        <option value="overdue" {{ request('payment_status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Bus Number</label>
                    <select name="bus_number" class="form-control">
                        <option value="">All Buses</option>
                        @foreach($busNumbers as $busNumber)
                            <option value="{{ $busNumber }}" {{ request('bus_number') == $busNumber ? 'selected' : '' }}>
                                {{ $busNumber }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">Academic Year</label>
                    <select name="academic_year" class="form-control">
                        <option value="">All Years</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="form-control" placeholder="Search by name or ID..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-6 d-flex align-items-end">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('admin.transport.assigned-list') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Transport Fees Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($assignedFees->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Assignee</th>
                                <th>Type</th>
                                <th>Transport Details</th>
                                <th>Fee Duration</th>
                                <th>Installments</th>
                                <th>Total Fee</th>
                                <th>Payment Progress</th>
                                <th>Status</th>
                                <th>Assigned Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedFees as $fee)
                            <tr>
                                <!-- Assignee Column -->
                                <td>
                                <div class="d-flex align-items-center">
                                    <div class="assignee-avatar {{ $fee->assignee_type }}">
                                        {{ substr($fee->assignee_name, 0, 1) }}
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">{{ $fee->assignee_name }}</h6>
                                        <p class="mb-0 small text-muted">
                                            ID: {{ $fee->assignee_id }}
                                            @if($fee->assignee_type == 'employee' && isset($fee->position) && $fee->position)
                                                • {{ $fee->position }}
                                            @endif
                                            @if($fee->assignee_type == 'employee' && isset($fee->department) && $fee->department)
                                                <br><small>Dept: {{ $fee->department }}</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                                
                                <!-- Type Column -->
                                <td>
                                    <span class="type-badge type-{{ $fee->assignee_type }}">
                                        {{ $fee->type_display }}
                                    </span>
                                </td>
                                
                                <!-- Transport Details Column -->
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $fee->bus_number }}</div>
                                        <div class="small text-muted">{{ $fee->route_name }}</div>
                                        <div class="small text-muted">Stop: {{ $fee->transport_stop_name }}</div>
                                    </div>
                                </td>
                                
                                <!-- Fee Duration Column -->
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst(str_replace('_', ' ', $fee->fee_duration_type)) }}
                                    </span>
                                    @if($fee->academic_year_id)
                                        <div class="small text-muted mt-1">{{ $fee->academic_year_id }}</div>
                                    @endif
                                </td>
                                
                                <!-- Installments Column -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                        <span>{{ $fee->installment_count }} installments</span>
                                    </div>
                                </td>
                                
                                <!-- Total Fee Column -->
                                <td class="amount-cell">
                                    ₹{{ number_format($fee->transport_total_fee, 2) }}
                                    @if($fee->paid_amount > 0)
                                        <div class="small text-muted">
                                            Paid: ₹{{ number_format($fee->paid_amount, 2) }}
                                        </div>
                                    @endif
                                </td>
                                
                                <!-- Payment Progress Column -->
                                <td>
                                    @if($fee->transport_total_fee > 0)
                                        @php
                                            $paidPercentage = ($fee->paid_amount / $fee->transport_total_fee) * 100;
                                            $pendingPercentage = 100 - $paidPercentage;
                                        @endphp
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span>{{ number_format($paidPercentage, 0) }}% paid</span>
                                            <span>{{ number_format($pendingPercentage, 0) }}% pending</span>
                                        </div>
                                        <div class="payment-progress">
                                            <div class="payment-progress-bar progress-paid" style="width: {{ $paidPercentage }}%"></div>
                                            <div class="payment-progress-bar progress-pending" style="width: {{ $pendingPercentage }}%"></div>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                
                                <!-- Status Column -->
                                <td>
                                    @php
                                        $statusClass = 'status-' . $fee->payment_status;
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $fee->payment_status)) }}
                                    </span>
                                </td>
                                
                                <!-- Assigned Date Column -->
                                <td>
                                    {{ $fee->created_at->format('d M Y') }}
                                    <div class="small text-muted">
                                        {{ $fee->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                
                                <!-- Actions Column -->
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                data-bs-toggle="dropdown" data-bs-auto-close="true" 
                                                aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.transport.assigned-details', $fee->fee_reference_id) }}">
                                                    <i class="fas fa-eye me-2"></i>View Details
                                                </a>
                                            </li>
                                            @if($fee->payment_status == 'pending' || $fee->payment_status == 'partially_paid')
                                            <li>
                                                <a class="dropdown-item text-warning" href="#">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            @endif
                                            @if($fee->payment_status == 'pending')
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form id="cancelForm{{ $fee->fee_reference_id }}" 
                                                    action="{{ route('admin.transport.cancel-assigned', $fee->fee_reference_id) }}" 
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="button" class="dropdown-item text-danger cancel-btn" 
                                                            data-id="{{ $fee->fee_reference_id }}">
                                                        <i class="fas fa-times me-2"></i>Cancel Assignment
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $assignedFees->firstItem() }} to {{ $assignedFees->lastItem() }} of {{ $assignedFees->total() }} entries
                    </div>
                    <div>
                        {{ $assignedFees->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="no-data">
                    <i class="fas fa-bus-slash"></i>
                    <h4 class="mt-3">No Transport Fees Assigned</h4>
                    <p class="text-muted mb-4">No transport fees have been assigned yet.</p>
                    <a href="{{ route('admin.transport.assign-fee.form') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Assign Your First Transport Fee
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('select[name="assignee_type"], select[name="payment_status"], select[name="bus_number"], select[name="academic_year"]').on('change', function() {
        $(this).closest('form').submit();
    });
    
    // Search with delay
    let searchTimer;
    $('input[name="search"]').on('keyup', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            $(this).closest('form').submit();
        }, 500);
    });
});
</script>
@endsection