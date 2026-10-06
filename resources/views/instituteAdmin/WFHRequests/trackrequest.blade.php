@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'My WFH Requests')

@section('content')
<style>
    /* Modern Stats Cards */
    .stats-card-modern {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .stats-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    
    .stats-card-modern.primary::before { background: linear-gradient(90deg, #667eea, #764ba2); }
    .stats-card-modern.warning::before { background: linear-gradient(90deg, #f093fb, #f5576c); }
    .stats-card-modern.success::before { background: linear-gradient(90deg, #4facfe, #00f2fe); }
    .stats-card-modern.danger::before { background: linear-gradient(90deg, #fa709a, #fee140); }
    .stats-card-modern.info::before { background: linear-gradient(90deg, #a18cd1, #fbc2eb); }
    .stats-card-modern.secondary::before { background: linear-gradient(90deg, #fccb90, #d57eeb); }
    
    .stats-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .stats-card-modern .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        margin-bottom: 10px;
    }
    
    .stats-card-modern .stats-icon.primary { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stats-card-modern .stats-icon.warning { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .stats-card-modern .stats-icon.success { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .stats-card-modern .stats-icon.danger { background: linear-gradient(135deg, #fa709a, #fee140); }
    .stats-card-modern .stats-icon.info { background: linear-gradient(135deg, #a18cd1, #fbc2eb); }
    .stats-card-modern .stats-icon.secondary { background: linear-gradient(135deg, #fccb90, #d57eeb); }
    
    .stats-card-modern .stats-number {
        font-size: 28px;
        font-weight: 700;
        color: #2d3436;
        line-height: 1.2;
    }
    
    .stats-card-modern .stats-label {
        font-size: 13px;
        color: #636e72;
        font-weight: 500;
        margin-top: 2px;
    }
    
    /* Modern Table */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    
    .table-modern thead th {
        background: #f8f9fa;
        border: none;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #636e72;
        border-bottom: 2px solid #e9ecef;
    }
    
    .table-modern tbody tr {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
    }
    
    .table-modern tbody tr:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transform: scale(1.01);
    }
    
    .table-modern tbody td {
        border: none;
        padding: 16px 20px;
        vertical-align: middle;
        background: transparent;
    }
    
    .table-modern tbody tr td:first-child {
        border-radius: 10px 0 0 10px;
    }
    
    .table-modern tbody tr td:last-child {
        border-radius: 0 10px 10px 0;
    }
    
    /* Status Badges */
    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .badge-status.pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .badge-status.approved {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-status.rejected {
        background: #f8d7da;
        color: #721c24;
    }
    
    .badge-status.completed {
        background: #cce5ff;
        color: #004085;
    }
    
    .badge-status.cancelled {
        background: #e2e3e5;
        color: #383d41;
    }
    
    /* Upcoming Card */
    .upcoming-card-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px 30px;
        color: white;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
    }
    
    .upcoming-card-modern::after {
        content: '🏠';
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 60px;
        opacity: 0.1;
    }
    
    /* Filter Section */
    .filter-section-modern {
        background: white;
        padding: 20px 25px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    
    .filter-section-modern .form-control {
        border-radius: 10px;
        border: 1px solid #e9ecef;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    
    .filter-section-modern .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-new-request {
        background: linear-gradient(135deg, #00b894, #00cec9);
        border: none;
        color: white;
        padding: 10px 25px;
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-new-request:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0, 206, 201, 0.4);
        color: white;
    }
    
    .btn-action {
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
    }
    
    .btn-action-view {
        background: #e3f2fd;
        color: #1976d2;
        border: none;
    }
    
    .btn-action-view:hover {
        background: #1976d2;
        color: white;
    }
    
    .btn-action-cancel {
        background: #fce4ec;
        color: #c62828;
        border: none;
    }
    
    .btn-action-cancel:hover {
        background: #c62828;
        color: white;
    }
    
    .empty-state-modern {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state-modern .empty-icon {
        font-size: 80px;
        color: #dfe6e9;
        margin-bottom: 20px;
    }
    
    .empty-state-modern h5 {
        color: #2d3436;
        font-weight: 600;
    }
    
    .empty-state-modern p {
        color: #636e72;
    }
    
    .request-id-badge {
        font-weight: 600;
        color: #2d3436;
        font-size: 14px;
    }
    
    .date-range-text {
        font-size: 13px;
        color: #636e72;
    }
    
    @media (max-width: 768px) {
        .stats-card-modern {
            margin-bottom: 15px;
        }
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h4 class="mb-2">
                    <i class="fas fa-home text-primary mr-2"></i> Track Work From Home 
                </h4>
                <a href="{{ route('employee.wfh-requests.create') }}" class="btn btn-new-request">
                    <i class="fas fa-plus mr-2"></i> New Request
                </a>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-2 col-6">
            <div class="stats-card-modern primary">
                <div class="stats-icon primary">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stats-number">{{ $stats['total'] }}</div>
                <div class="stats-label">Total Requests</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stats-card-modern warning">
                <div class="stats-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number">{{ $stats['pending'] }}</div>
                <div class="stats-label">Pending</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stats-card-modern success">
                <div class="stats-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-number">{{ $stats['approved'] }}</div>
                <div class="stats-label">Approved</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stats-card-modern danger">
                <div class="stats-icon danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stats-number">{{ $stats['rejected'] }}</div>
                <div class="stats-label">Rejected</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stats-card-modern info">
                <div class="stats-icon info">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div class="stats-number">{{ $stats['completed'] }}</div>
                <div class="stats-label">Completed</div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stats-card-modern secondary">
                <div class="stats-icon secondary">
                    <i class="fas fa-home"></i>
                </div>
                <div class="stats-number">{{ $stats['active_today'] }}</div>
                <div class="stats-label"> Today</div>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Requests -->
    @if($upcoming->isNotEmpty())
    <div class="upcoming-card-modern">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div style="font-size: 12px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fas fa-calendar-check mr-2"></i> Upcoming WFH
                </div>
                @foreach($upcoming as $request)
                    <div style="font-size: 18px; font-weight: 600; margin-top: 5px;">
                        {{ $request->formatted_date_range }}
                    </div>
                    <div style="font-size: 14px; opacity: 0.9; margin-top: 3px;">
                        <i class="fas fa-clock mr-1"></i>
                        Starts in {{ \Carbon\Carbon::parse($request->start_date)->diffForHumans() }}
                    </div>
                    @break
                @endforeach
            </div>
            <div class="col-md-4 text-md-right">
                @if($upcoming->count() > 1)
                    <small>+{{ $upcoming->count() - 1 }} more upcoming</small>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    <!-- Filter Section -->
    <div class="filter-section-modern">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3">
                <label class="text-muted small font-weight-bold">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ ($filters['status'] ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ ($filters['status'] ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="text-muted small font-weight-bold">From Date</label>
                <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <label class="text-muted small font-weight-bold">To Date</label>
                <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-filter btn-block">
                    <i class="fas fa-filter mr-2"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>
    
    <!-- Requests Table -->
    @if($requests->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Date Range</th>
                        <th>Duration</th>
                        <th>Working Hours</th>
                        <th>Status</th>
                        <th>Requested On</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>
                                <span class="request-id-badge">
                                    <i class="fas fa-hashtag text-muted mr-1"></i>
                                    {{ $request->request_id }}
                                </span>
                            </td>
                            <td>
                                <div class="date-range-text">
                                    <i class="far fa-calendar-alt text-muted mr-1"></i>
                                    {{ \Carbon\Carbon::parse($request->start_date)->format('d M, Y') }}
                                    <span class="mx-1">→</span>
                                    {{ \Carbon\Carbon::parse($request->end_date)->format('d M, Y') }}
                                </div>
                            </td>
                            <td>
                                <span>
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $request->duration }}
                                </span>
                            </td>
                            <td>
                                @if($request->start_time && $request->end_time)
                                    <span class="text-muted small">
                                        <i class="fas fa-clock text-muted mr-1"></i>
                                        {{ date('h:i A', strtotime($request->start_time)) }} - 
                                        {{ date('h:i A', strtotime($request->end_time)) }}
                                    </span>
                                @else
                                    <span class="text-muted small">Not specified</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-status {{ $request->request_status }}">
                                    <i class="fas 
                                        @if($request->request_status == 'pending') fa-clock
                                        @elseif($request->request_status == 'approved') fa-check-circle
                                        @elseif($request->request_status == 'rejected') fa-times-circle
                                        @elseif($request->request_status == 'completed') fa-flag-checkered
                                        @elseif($request->request_status == 'cancelled') fa-ban
                                        @endif">
                                    </i>
                                    {{ ucfirst($request->request_status) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    {{ $request->created_at->format('d M, Y H:i') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('employee.wfh-requests.show', $request->id) }}" 
                                   class="btn btn-action btn-action-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($request->isPending())
                                    <button onclick="cancelRequest({{ $request->id }})" 
                                            class="btn btn-action btn-action-cancel ml-1">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            {{ $requests->links() }}
        </div>
    @else
        <div class="empty-state-modern">
            <div class="empty-icon">
                <i class="fas fa-inbox"></i>
            </div>
            <h5>No WFH Requests Found</h5>
            <p>You haven't submitted any work from home requests yet.</p>
            <a href="{{ route('employee.wfh-requests.create') }}" class="btn btn-new-request mt-2">
                <i class="fas fa-plus mr-2"></i> New Request
            </a>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function cancelRequest(id) {
    Swal.fire({
        title: 'Cancel request?',
        text: 'This action will cancel the selected WFH request.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (!result.isConfirmed) return;

        let url = "{{ route('employee.wfh-requests.cancel', ':id') }}";
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Cancelled',
                    text: response.message || 'Request cancelled successfully',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: xhr.responseJSON?.message || 'Error cancelling request'
                });
            }
        });
    });
}
</script>
@endsection