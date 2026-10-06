{{-- resources/views/instituteAdmin/StudentExit/AdminExitRequests.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .stat-card {
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .stat-card .stat-icon {
        position: absolute;
        right: 15px;
        top: 15px;
        font-size: 32px;
        opacity: 0.2;
    }
    .stat-card .stat-number {
        font-size: 32px;
        font-weight: 700;
        margin: 5px 0;
    }
    .stat-card .stat-label {
        font-size: 14px;
        opacity: 0.9;
        margin-bottom: 0;
    }
    .stat-card .stat-trend {
        font-size: 13px;
        margin-top: 8px;
        display: inline-block;
        padding: 2px 12px;
        border-radius: 20px;
        background: rgba(255,255,255,0.2);
    }
    
    .stat-card.primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
    }
    .stat-card.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    .stat-card.success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    .stat-card.danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    .stat-card.secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }
    
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .table-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .status-badge {
        padding: 6px 16px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }
    .status-badge.approved {
        background: #dcfce7;
        color: #166534;
    }
    .status-badge.rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    .status-badge.cancelled {
        background: #f1f5f9;
        color: #475569;
    }
    
    .exit-type-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .exit-type-badge.completion {
        background: #dbeafe;
        color: #1e40af;
    }
    .exit-type-badge.mid-session {
        background: #fef3c7;
        color: #92400e;
    }
    .exit-type-badge.cancellation {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .request-id {
        font-family: monospace;
        font-size: 13px;
        color: #6366f1;
        font-weight: 600;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .action-btn:hover {
        transform: scale(1.1);
    }
    .action-btn.approve {
        background: #dcfce7;
        color: #166534;
    }
    .action-btn.approve:hover {
        background: #10b981;
        color: white;
    }
    .action-btn.reject {
        background: #fee2e2;
        color: #991b1b;
    }
    .action-btn.reject:hover {
        background: #ef4444;
        color: white;
    }
    .action-btn.view {
        background: #dbeafe;
        color: #1e40af;
    }
    .action-btn.view:hover {
        background: #6366f1;
        color: white;
    }
    
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state .empty-icon {
        font-size: 64px;
        color: #e2e8f0;
        margin-bottom: 20px;
    }
    .empty-state h5 {
        color: #475569;
        margin-bottom: 10px;
    }
    .empty-state p {
        color: #94a3b8;
    }
    
    .table > :not(caption) > * > * {
        padding: 12px 15px;
        vertical-align: middle;
    }
    .col-md-2 {
        flex: 0 0 auto;
        width: 19.666667%;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-header" style="background: linear-gradient(135deg, #6366f1, #4f46e5); padding: 25px 35px; border-radius: 20px; margin-bottom: 30px; position: relative; overflow: hidden;">
                <div style="position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -30%; left: 20%; width: 200px; height: 200px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                    <div>
                        <h1 style="color: white; margin: 0; display: flex; align-items: center; gap: 14px; font-size: 28px;">
                            <i class="bi bi-door-open" style="font-size: 32px;"></i>
                            Exit Requests
                            <span class="badge bg-warning text-dark ms-2" style="font-size: 14px; padding: 6px 16px;">
                                <i class="bi bi-clock me-1"></i>
                                {{ $statistics['pending'] ?? 0 }} Pending
                            </span>
                        </h1>
                        <div class="mt-2" style="color: rgba(255,255,255,0.85); font-size: 14px;">
                            <i class="bi bi-info-circle me-1"></i>
                            Review and process student exit requests from this dashboard
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-light" onclick="window.location.reload()" style="border-radius: 12px; padding: 8px 20px;">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4" style="justify-content: space-evenly;">
        <div class="col-md-2 col-6">
            <div class="stat-card primary">
                <i class="bi bi-files stat-icon"></i>
                <div class="stat-label">Total Requests</div>
                <div class="stat-number">{{ $statistics['total'] ?? 0 }}</div>
                <div class="stat-trend">
                    <i class="bi bi-arrow-up"></i> All time
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-card warning">
                <i class="bi bi-clock-history stat-icon"></i>
                <div class="stat-label">Pending</div>
                <div class="stat-number">{{ $statistics['pending'] ?? 0 }}</div>
                <div class="stat-trend">
                    <i class="bi bi-clock"></i> Awaiting review
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-card success">
                <i class="bi bi-check-circle stat-icon"></i>
                <div class="stat-label">Approved</div>
                <div class="stat-number">{{ $statistics['approved'] ?? 0 }}</div>
                <div class="stat-trend">
                    <i class="bi bi-check"></i> Processed
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-card danger">
                <i class="bi bi-x-circle stat-icon"></i>
                <div class="stat-label">Rejected</div>
                <div class="stat-number">{{ $statistics['rejected'] ?? 0 }}</div>
                <div class="stat-trend">
                    <i class="bi bi-x"></i> Declined
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="stat-card secondary">
                <i class="bi bi-ban stat-icon"></i>
                <div class="stat-label">Cancelled</div>
                <div class="stat-number">{{ $statistics['cancelled'] ?? 0 }}</div>
                <div class="stat-trend">
                    <i class="bi bi-arrow-right"></i> By student
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-search me-1"></i> Search
                </label>
                <input type="text" name="search" class="form-control" placeholder="Student name, reg no, request ID..." value="{{ request('search') }}" style="border-radius: 10px;">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">
                    <i class="bi bi-filter me-1"></i> Status
                </label>
                <select name="status" class="form-select" style="border-radius: 10px;">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>✅ Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>🚫 Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">
                    <i class="bi bi-tag me-1"></i> Exit Type
                </label>
                <select name="exit_type" class="form-select" style="border-radius: 10px;">
                    <option value="">All Types</option>
                    <option value="course_completion" {{ request('exit_type') == 'course_completion' ? 'selected' : '' }}>🎓 Course Completion</option>
                    <option value="mid_session" {{ request('exit_type') == 'mid_session' ? 'selected' : '' }}>⏸️ Mid-Session</option>
                    <option value="cancellation" {{ request('exit_type') == 'cancellation' ? 'selected' : '' }}>❌ Cancellation</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">
                    <i class="bi bi-calendar me-1"></i> From
                </label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" style="border-radius: 10px;">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">
                    <i class="bi bi-calendar me-1"></i> To
                </label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" style="border-radius: 10px;">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100" style="border-radius: 10px; padding: 10px;">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Exit Requests Table -->
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>
                Exit Requests List
                <span class="badge bg-secondary ms-2">{{ $exitRequests->total() ?? 0 }} Total</span>
            </h5>
            <div>
                <span class="text-muted" style="font-size: 13px;">
                    <i class="bi bi-clock me-1"></i>
                    Last updated: {{ now()->format('d-m-Y H:i:s') }}
                </span>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead style="background: #f8fafc; border-radius: 12px;">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="min-width: 180px;">Request ID</th>
                        <th style="min-width: 180px;">Student</th>
                        <th style="min-width: 120px;">Registration</th>
                        <th style="min-width: 140px;">Exit Type</th>
                        <th style="min-width: 120px;">Requested Date</th>
                        <th style="min-width: 100px;">Status</th>
                        <th style="min-width: 150px;">Submitted</th>
                        <th style="min-width: 140px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exitRequests as $request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="request-id">
                                    <i class="bi bi-hash"></i>
                                    {{ $request->request_id ?? 'REQ-' . str_pad($request->id, 6, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-2" style="width: 36px; height: 36px; border-radius: 50%; background: #e0e7ff; display: flex; align-items: center; justify-content: center; color: #4f46e5; font-weight: 600; font-size: 14px;">
                                        {{ strtoupper(substr($request->student->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($request->student->last_name ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b;">
                                            {{ $request->student->first_name ?? '' }} {{ $request->student->last_name ?? '' }}
                                        </div>
                                        <div style="font-size: 12px; color: #94a3b8;">
                                            <i class="bi bi-envelope me-1"></i>{{ $request->student->email ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 13px; color: #475569;">
                                    {{ $request->student->registration_number ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $typeClasses = [
                                        'course_completion' => 'completion',
                                        'mid_session' => 'mid-session',
                                        'cancellation' => 'cancellation'
                                    ];
                                    $typeIcons = [
                                        'course_completion' => '🎓',
                                        'mid_session' => '⏸️',
                                        'cancellation' => '❌'
                                    ];
                                @endphp
                                <span class="exit-type-badge {{ $typeClasses[$request->exit_type] ?? '' }}">
                                    {{ $typeIcons[$request->exit_type] ?? '' }}
                                    {{ ucwords(str_replace('_', ' ', $request->exit_type)) }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 14px;">
                                    {{ $request->requested_exit_date ? \Carbon\Carbon::parse($request->requested_exit_date)->format('d-m-Y') : 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusIcons = [
                                        'pending' => '⏳',
                                        'approved' => '✅',
                                        'rejected' => '❌',
                                        'cancelled' => '🚫'
                                    ];
                                @endphp
                                <span class="status-badge {{ $request->status }}">
                                    {{ $statusIcons[$request->status] ?? '' }}
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px;">
                                    <div>{{ $request->created_at->format('d-m-Y') }}</div>
                                    <div style="font-size: 11px; color: #94a3b8;">
                                        <i class="bi bi-clock me-1"></i>{{ $request->created_at->format('H:i:s') }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.exit.requests.show', $request->id) }}" 
                                       class="action-btn view" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($request->status == 'pending')
                                        <button class="action-btn approve" onclick="approveRequest({{ $request->id }})" title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button class="action-btn reject" onclick="rejectRequest({{ $request->id }})" title="Reject">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <h5>No Exit Requests Found</h5>
                                    <p>There are no exit requests matching your filters or no requests have been submitted yet.</p>
                                    <button class="btn btn-primary mt-2" onclick="window.location.href='{{ route('admin.exit.requests.index') }}'">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Clear Filters
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <span class="text-muted" style="font-size: 14px;">
                    Showing {{ $exitRequests->firstItem() ?? 0 }} to {{ $exitRequests->lastItem() ?? 0 }} of {{ $exitRequests->total() ?? 0 }} entries
                </span>
            </div>
            <div>
                {{ $exitRequests->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function approveRequest(requestId) {
    Swal.fire({
        title: 'Approve Exit Request?',
        html: `
            <p>This will immediately exit the student from the system.</p>
            <div class="form-group text-left" style="margin-top: 15px;">
                <label for="admin_notes" style="font-weight: 600;">Admin Notes (Optional)</label>
                <textarea id="admin_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..." style="border-radius: 8px;"></textarea>
            </div>
            <div class="form-group text-left mt-2">
                <label for="exit_date" style="font-weight: 600;">Exit Date</label>
                <input type="date" id="exit_date" class="form-control" value="{{ date('Y-m-d') }}" style="border-radius: 8px;">
            </div>
            <div class="form-check text-left mt-2">
                <input type="checkbox" id="clear_dues" class="form-check-input" checked>
                <label for="clear_dues" class="form-check-label">Mark dues as cleared</label>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const adminNotes = document.getElementById('admin_notes').value;
            const exitDate = document.getElementById('exit_date').value;
            const clearDuesEl = document.getElementById('clear_dues');
            const clearDues = clearDuesEl ? clearDuesEl.checked : false;
            
            if (!exitDate) {
                Swal.showValidationMessage('Please select an exit date');
                return false;
            }
            
            return { 
                admin_notes: adminNotes, 
                exit_date: exitDate, 
                clear_dues: clearDues ? 1 : 0,
                generate_no_due: 0
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const data = result.value;
            
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process the request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/admin/exit-requests/${requestId}/approve`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    admin_notes: data.admin_notes,
                    exit_date: data.exit_date,
                    clear_dues: data.clear_dues,
                    generate_no_due: data.generate_no_due
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: response.message,
                            confirmButtonColor: '#22c55e',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        }
    });
}

function rejectRequest(requestId) {
    Swal.fire({
        title: 'Reject Exit Request?',
        html: `
            <p>Please provide a reason for rejection:</p>
            <div class="form-group text-left">
                <textarea id="rejection_reason" class="form-control" rows="4" placeholder="Enter rejection reason (minimum 10 characters)..." style="border-radius: 8px;"></textarea>
            </div>
            <div class="text-left mt-2">
                <small class="text-muted">This reason will be sent to the student.</small>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const rejectionReason = document.getElementById('rejection_reason').value;
            if (!rejectionReason || rejectionReason.length < 10) {
                Swal.showValidationMessage('Please provide a detailed rejection reason (minimum 10 characters)');
                return false;
            }
            return { rejection_reason: rejectionReason };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/admin/exit-requests/${requestId}/reject`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rejection_reason: result.value.rejection_reason
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Rejected!',
                            text: response.message,
                            confirmButtonColor: '#ef4444',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            });
        }
    });
}
</script>
@endsection