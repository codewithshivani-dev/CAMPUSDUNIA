{{-- resources/views/instituteAdmin/StudentExit/ExitRequestStatus.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .page-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        /*height: 300px;*/
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 20%;
        width: 200px;
        /*height: 200px;*/
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .page-header h1 {
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }
    .page-header .subtitle {
        color: rgba(255,255,255,0.9);
        font-size: 14px;
        margin-top: 5px;
        position: relative;
        z-index: 1;
    }
    
    .status-timeline {
        position: relative;
        padding-left: 30px;
    }
    .status-timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: #e2e8f0;
        border-radius: 3px;
    }
    .timeline-item {
        position: relative;
        padding: 0 0 25px 25px;
        border-left: none;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-item .timeline-icon {
        position: absolute;
        left: -22px;
        top: 0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        z-index: 2;
    }
    .timeline-item .timeline-icon.pending {
        background: #fef3c7;
        color: #92400e;
    }
    .timeline-item .timeline-icon.approved {
        background: #dcfce7;
        color: #166534;
    }
    .timeline-item .timeline-icon.rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    .timeline-item .timeline-icon.cancelled {
        background: #f1f5f9;
        color: #475569;
    }
    .timeline-item .timeline-icon.inactive {
        background: #e2e8f0;
        color: #64748b;
    }
    .timeline-item .timeline-content {
        background: white;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }
    .timeline-item .timeline-content .title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .timeline-item .timeline-content .meta {
        font-size: 13px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    .timeline-item .timeline-content .meta .badge {
        font-size: 11px;
        padding: 3px 10px;
    }
    .timeline-item .timeline-content .description {
        color: #475569;
        font-size: 14px;
        margin-top: 6px;
        padding-top: 6px;
        border-top: 1px solid #f1f5f9;
    }
    
    .status-badge {
        padding: 6px 18px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
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
    
    .request-summary {
        background: white;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 25px;
    }
    .request-summary .summary-item {
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .request-summary .summary-item:last-child {
        border-bottom: none;
    }
    .request-summary .summary-item .label {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 500;
    }
    .request-summary .summary-item .value {
        font-size: 15px;
        color: #1e293b;
        font-weight: 500;
    }
    
    .info-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px 20px;
        border-left: 4px solid #f59e0b;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    .empty-state .icon {
        font-size: 64px;
        color: #e2e8f0;
        margin-bottom: 20px;
    }
    .empty-state h4 {
        color: #1e293b;
        margin-bottom: 10px;
    }
    .empty-state p {
        color: #94a3b8;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div>
                    <h1>
                        <i class="bi bi-clock-history"></i>
                        Exit Request Status
                    </h1>
                    <div class="subtitle">
                        <i class="bi bi-info-circle me-1"></i>
                        Track your exit request progress and history
                    </div>
                </div>
                @if(!$isExited && !$pendingRequest)
                    <a href="{{ route('student.exit.request.form') }}" class="d-none btn btn-light" style="position: relative; z-index: 1; border-radius: 12px;">
                        <i class="bi bi-plus-circle me-2"></i> New Request
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($isExited)
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>You have already been exited from the institute.</strong> 
            Your account is now inactive. Please contact the administration for any queries.
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            <i class="bi bi-info-circle me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            @if($pendingRequest)
                <!-- Pending Request Summary -->
                <div class="request-summary">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="mb-0">
                            <i class="bi bi-clock me-2"></i>
                            Pending Request
                        </h5>
                        <span class="status-badge pending">
                            <i class="bi bi-clock"></i> Pending Review
                        </span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="summary-item">
                                <div class="label">Request ID</div>
                                <div class="value">
                                    <code style="background: #f1f5f9; padding: 2px 10px; border-radius: 6px; font-size: 14px;">
                                        {{ $pendingRequest->request_id }}
                                    </code>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-item">
                                <div class="label">Exit Type</div>
                                <div class="value">{{ $pendingRequest->exit_type_label }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="summary-item">
                                <div class="label">Requested Date</div>
                                <div class="value">{{ $pendingRequest->requested_exit_date ? $pendingRequest->requested_exit_date->format('d-m-Y') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="summary-item">
                                <div class="label">Submitted On</div>
                                <div class="value">{{ $pendingRequest->created_at->format('d-m-Y H:i:s') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="summary-item">
                                <div class="label">Waiting Period</div>
                                <div class="value">
                                    <span class="text-warning">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $pendingRequest->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="summary-item">
                        <div class="label">Exit Reason</div>
                        <div class="value" style="font-weight: 400; font-size: 14px; white-space: pre-wrap;">
                            {{ $pendingRequest->exit_reason }}
                        </div>
                    </div>
                    
                    @if($pendingRequest->additional_notes)
                        <div class="summary-item">
                            <div class="label">Additional Notes</div>
                            <div class="value" style="font-weight: 400; font-size: 14px; white-space: pre-wrap;">
                                {{ $pendingRequest->additional_notes }}
                            </div>
                        </div>
                    @endif

                    <div class="info-box mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Status:</strong> Your request is being reviewed by the admin. 
                        You will be notified via email once a decision is made.
                        @if($pendingRequest->notify_parent)
                            <br><i class="bi bi-person me-1"></i>
                            <small>Your parent/guardian will also be notified.</small>
                        @endif
                    </div>
                    
                    <div class="mt-3">
                        <button class="btn btn-danger" onclick="cancelRequest('{{ $pendingRequest->id }}')">
                            <i class="bi bi-x-circle me-2"></i> Cancel Request
                        </button>
                    </div>
                </div>

                <!-- Pending Request Timeline -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Request Timeline</h5>
                    </div>
                    <div class="card-body">
                        <div class="status-timeline">
                            @php
                                $timelineItems = [
                                    [
                                        'icon' => 'bi bi-envelope',
                                        'class' => 'pending',
                                        'title' => 'Request Submitted',
                                        'time' => $pendingRequest->created_at->format('d-m-Y H:i:s'),
                                        'meta' => 'You submitted the exit request',
                                        'description' => 'Your request has been sent to the admin for review.'
                                    ],
                                    [
                                        'icon' => 'bi bi-clock',
                                        'class' => 'pending',
                                        'title' => 'Awaiting Admin Review',
                                        'time' => 'In Progress',
                                        'meta' => 'Admin has been notified',
                                        'description' => 'The admin will review your request and make a decision shortly.'
                                    ]
                                ];
                            @endphp
                            
                            @foreach($timelineItems as $item)
                                <div class="timeline-item">
                                    <div class="timeline-icon {{ $item['class'] }}">
                                        <i class="{{ $item['icon'] }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="title">{{ $item['title'] }}</div>
                                        <div class="meta">
                                            <span><i class="bi bi-calendar me-1"></i>{{ $item['time'] }}</span>
                                            <span class="badge bg-secondary">{{ $item['meta'] }}</span>
                                        </div>
                                        <div class="description">
                                            <i class="bi bi-info-circle me-1" style="color: #94a3b8;"></i>
                                            {{ $item['description'] }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            @elseif(!$isExited)
                <!-- No Active Request -->
                <div class="card">
                    <div class="card-body">
                        <div class="empty-state">
                            <div class="icon">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h4>No Active Exit Requests</h4>
                            <p>You haven't submitted any exit request yet.</p>
                            @if($canSelectCourseCompletion ?? false)
                                <div class="alert alert-info mt-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Course Completed!</strong> You are eligible for course completion exit.
                                </div>
                            @endif
                            <a href="{{ route('student.exit.request.form') }}" class="btn btn-warning mt-3" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border: none; padding: 10px 30px; border-radius: 12px;">
                                <i class="bi bi-plus-circle me-2"></i> Submit Exit Request
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Request History -->
            @if($exitRequests->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-list-ul me-2"></i> 
                            Request History
                            <span class="badge bg-secondary ms-2">{{ $exitRequests->count() }} Total</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead style="background: #f8fafc;">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th class="sortable">Request ID</th>
                                        <th class="sortable">Type</th>
                                        <th class="sortable">Date</th>
                                        <th class="sortable">Status</th>
                                        <th class="sortable">Processed By</th>
                                        <th class="sortable">Submitted</th>
                                        <th class="sortable">Processed At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exitRequests as $request)
                                        @php
                                            $statusIcons = [
                                                'pending' => '⏳',
                                                'approved' => '✅',
                                                'rejected' => '❌',
                                                'cancelled' => '🚫'
                                            ];
                                            $statusBadges = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'cancelled' => 'secondary'
                                            ];
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <code style="background: #f1f5f9; padding: 2px 8px; border-radius: 4px; font-size: 12px;">
                                                    {{ $request->request_id }}
                                                </code>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $request->exit_type_label }}
                                                </span>
                                            </td>
                                            <td>{{ $request->requested_exit_date ? $request->requested_exit_date->format('d-m-Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="status-badge {{ $request->status }}" style="font-size: 12px; padding: 4px 12px;">
                                                    {{ $statusIcons[$request->status] ?? '' }}
                                                    {{ $request->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($request->status == 'approved' || $request->status == 'rejected')
                                                    <div>
                                                        <strong>{{ $request->processedBy->name ?? 'System' }}</strong>
                                                        @if($request->processedBy)
                                                            <br>
                                                            <small class="text-muted">{{ $request->processedBy->email ?? '' }}</small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
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
                                                @if($request->processed_at)
                                                    <div style="font-size: 13px;">
                                                        <div>{{ \Carbon\Carbon::parse($request->processed_at)->format('d-m-Y') }}</div>
                                                        <div style="font-size: 11px; color: #94a3b8;">
                                                            <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($request->processed_at)->format('H:i:s') }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Show admin notes for rejected/approved requests -->
                @php
                    $lastProcessed = $exitRequests->whereIn('status', ['approved', 'rejected'])->first();
                @endphp
                @if($lastProcessed && $lastProcessed->admin_notes)
                    <div class="card mt-3">
                        <div class="card-header" style="background: #fef3c7;">
                            <h5 class="mb-0">
                                <i class="bi bi-chat me-2"></i>
                                Admin Notes
                            </h5>
                        </div>
                        <div class="card-body">
                            <div style="white-space: pre-wrap; background: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid #f59e0b;">
                                {{ $lastProcessed->admin_notes }}
                            </div>
                            @if($lastProcessed->processedBy)
                                <div class="mt-2 text-muted" style="font-size: 13px;">
                                    <i class="bi bi-person me-1"></i>
                                    By: {{ $lastProcessed->processedBy->name ?? 'System' }}
                                    @if($lastProcessed->processed_at)
                                        <span class="ms-3">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($lastProcessed->processed_at)->format('d-m-Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function cancelRequest(requestId) {
    Swal.fire({
        title: 'Cancel Exit Request?',
        text: 'Are you sure you want to cancel this exit request? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Cancel Request',
        cancelButtonText: 'No, Keep It'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we cancel your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/student/exit-request/${requestId}/cancel`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled!',
                            text: response.message,
                            confirmButtonColor: '#d97706',
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
                    let errorMessage = 'An error occurred while cancelling the request.';
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