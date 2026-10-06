@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --pending-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --approved-gradient: linear-gradient(135deg, #10b981, #059669);
    --rejected-gradient: linear-gradient(135deg, #ef4444, #dc2626);
}

.page-header {
    background: var(--primary-gradient);
    border-radius: 16px;
    padding: 20px 25px;
    margin-bottom: 25px;
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.2);
}

.page-title {
    color: white;
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stats-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s;
    border: 1px solid #e2e8f0;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 24px;
}

.stats-icon.total {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    color: #4361ee;
}

.stats-icon.pending {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #f59e0b;
}

.stats-icon.approved {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #10b981;
}

.stats-icon.rejected {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #ef4444;
}

.stats-number {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 5px;
}

.stats-label {
    color: #64748b;
    font-size: 14px;
    font-weight: 500;
}

.filter-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid #e2e8f0;
}

.leave-card {
    background: white;
    border-radius: 16px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: all 0.3s;
}

.leave-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}

.leave-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.leave-body {
    padding: 20px;
}

.status-badge {
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-pending {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.status-approved {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.status-rejected {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
}

.approval-timeline {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
}

.approval-step {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    padding: 10px;
    background: #f8fafc;
    border-radius: 12px;
}

.approval-step-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.approval-step-icon.approved {
    background: #d1fae5;
    color: #10b981;
}

.approval-step-icon.pending {
    background: #fef3c7;
    color: #f59e0b;
}

.approval-step-icon.rejected {
    background: #fee2e2;
    color: #ef4444;
}

.approval-step-content {
    flex: 1;
}

.approval-step-name {
    font-weight: 600;
    font-size: 14px;
}

.approval-step-role {
    font-size: 12px;
    color: #64748b;
}

.approval-step-date {
    font-size: 11px;
    color: #94a3b8;
}

.document-link {
    color: #4361ee;
    text-decoration: none;
    font-size: 13px;
}

.document-link:hover {
    text-decoration: underline;
}

.pagination-wrapper {
    margin-top: 20px;
}

.btn-filter {
    background: var(--primary-gradient);
    border: none;
    border-radius: 10px;
    padding: 8px 20px;
    color: white;
    font-weight: 500;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
}

.btn-reset {
    background: #e2e8f0;
    border: none;
    border-radius: 10px;
    padding: 8px 20px;
    color: #475569;
    font-weight: 500;
}

.btn-reset:hover {
    background: #cbd5e1;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 64px;
    color: #cbd5e1;
    margin-bottom: 20px;
}

@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 15px;
    }

    .leave-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
.status-already-approved {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    color: #3730a3;
}

.approval-step-icon.already-approved {
    background: #e0e7ff;
    color: #4338ca;
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-calendar-check"></i>
            Leave Status
        </h1>

    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon total">
                    <i class="bi bi-calendar"></i>
                </div>
                <div class="stats-number">{{ $summary['total'] }}</div>
                <div class="stats-label">Total Leaves</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon pending">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stats-number">{{ $summary['pending'] }}</div>
                <div class="stats-label">Pending</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon approved">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number">{{ $summary['approved'] }}</div>
                <div class="stats-label">Approved</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon rejected">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stats-number">{{ $summary['rejected'] }}</div>
                <div class="stats-label">Rejected</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <form method="GET" action="{{ route('employee.leave.status') }}" id="filterForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved
                        </option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Leave Type</label>
                    <select name="leave_type" class="form-select">
                        <option value="">All Types</option>
                        @foreach($leaveTypes as $type)
                        <option value="{{ $type }}" {{ request('leave_type') == $type ? 'selected' : '' }}>{{ $type }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Year</label>
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('employee.leave.status') }}" class="btn btn-reset">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-filter">
                            <i class="bi bi-search me-1"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Leave Applications List -->
    @if($leaveApplications->count() > 0)
    <div class="leave-list">
        @foreach($leaveApplications as $leave)
        <div class="leave-card">
            <div class="leave-header">
                <div>
                    <strong class="fs-5">{{ $leave->leave_type }}</strong>
                    <span class="ms-2 text-muted small">
                        Applied on {{ \Carbon\Carbon::parse($leave->applied_date)->format('d M, Y') }}
                    </span>
                </div>
                <div>
                    @if($leave->final_status == 'Pending')
                    <span class="status-badge status-pending">
                        <i class="bi bi-clock"></i> Pending
                    </span>
                    @elseif($leave->final_status == 'Approved')
                    <span class="status-badge status-approved">
                        <i class="bi bi-check-circle"></i> Approved
                    </span>
                    @else
                    <span class="status-badge status-rejected">
                        <i class="bi bi-x-circle"></i> Rejected
                    </span>
                    @endif
                </div>
            </div>
            <div class="leave-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <small class="text-muted">Leave Period</small>
                            <p class="fw-semibold mb-0">
                                {{ \Carbon\Carbon::parse($leave->start_date)->format('d M, Y') }}
                                @if($leave->end_date && $leave->end_date != $leave->start_date)
                                - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M, Y') }}
                                @endif
                            </p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Total Days</small>
                            <p class="fw-semibold mb-0">{{ $leave->total_days }} day(s)</p>
                        </div>
                        @if($leave->reason)
                        <div class="mb-2">
                            <small class="text-muted">Reason</small>
                            <p class="mb-0">{{ $leave->reason }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($leave->leave_document)
                        <div class="mb-2">
                            <small class="text-muted">Supporting Document</small>
                            <div>
                                <a href="{{ route('image', ['path' => $leave->leave_document]) }}" target="_blank"
                                    class="document-link">
                                    <i class="bi bi-file-earmark-text me-1"></i> View Document
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($leave->final_status != 'Pending')
                        <div class="mb-2">
                            <small class="text-muted">Reviewed By</small>
                            <p class="fw-semibold mb-0">
                                @php
                                // Get the approval record that approved/rejected the leave
                                $finalApproval = $leave->approvals->where('status', $leave->final_status)->first();
                                @endphp
                                @if($finalApproval)
                                {{ $finalApproval->approver_name }}
                                @elseif($leave->approved_by)
                                {{ is_numeric($leave->approved_by) ? 'User ID: ' . $leave->approved_by : $leave->approved_by }}
                                @else
                                System
                                @endif
                            </p>
                        </div>
                        <div>
                            <small class="text-muted">Reviewed On</small>
                            <p class="mb-0">
                                {{ $leave->approved_date ? \Carbon\Carbon::parse($leave->approved_date)->format('d M, Y h:i A') : 'N/A' }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

       
                <!-- Approval Timeline -->
                @if($leave->approvals && $leave->approvals->count() > 0)
                <div class="approval-timeline">
                    <small class="text-muted fw-semibold">Approval Timeline</small>
                    <div class="mt-2">
                        @foreach($leave->approvals as $approval)
                        <div class="approval-step">
                            <div
                                class="approval-step-icon {{ $approval->display_status_class ?? strtolower($approval->status) }}">
                                @if($approval->status == 'Approved')
                                <i class="bi bi-check-lg"></i>
                                @elseif($approval->status == 'Rejected')
                                <i class="bi bi-x-lg"></i>
                                @elseif(isset($approval->display_status) && $approval->display_status == 'Already
                                Approved')
                                <i class="bi bi-check-circle"></i>
                                @else
                                <i class="bi bi-clock"></i>
                                @endif
                            </div>
                            <div class="approval-step-content">
                                <div class="approval-step-name">{{ $approval->approver_name }}</div>
                                <div class="approval-step-role">{{ $approval->approver_role }}</div>
                                @if($approval->approved_date)
                                <div class="approval-step-date">
                                    {{ \Carbon\Carbon::parse($approval->approved_date)->format('d M, Y h:i A') }}
                                </div>
                                @endif
                                @if($approval->comments)
                                <div class="small text-muted mt-1">Comment: {{ $approval->comments }}</div>
                                @endif
                            </div>
                            <div>
                                <span
                                    class="status-badge status-{{ $approval->display_status_class ?? strtolower($approval->status) }}"
                                    style="font-size: 10px; padding: 2px 8px;">
                                    {{ $approval->display_status ?? $approval->status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $leaveApplications->appends(request()->query())->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <h5 class="text-muted">No Leave Applications Found</h5>
        <p class="text-muted">You haven't applied for any leaves yet.</p>

    </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    // Auto-submit on filter change (except date inputs to avoid too many requests)
    $('.filter-card select').on('change', function() {
        $('#filterForm').submit();
    });

    // Add debounce for date inputs
    let debounceTimer;
    $('.filter-card input[type="date"]').on('change', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });
});
</script>
@endsection