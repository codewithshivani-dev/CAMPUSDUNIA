@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

@section('title', 'WFH Request Details')

@section('content')
<style>
    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    .status-approved {
        background: #d4edda;
        color: #155724;
    }
    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }
    .status-cancelled {
        background: #e2e3e5;
        color: #383d41;
    }
    
    .detail-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    .detail-card:hover {
        box-shadow: 0 4px 24px rgba(0,0,0,0.1);
    }
    
    .detail-label {
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .detail-value {
        font-size: 15px;
        font-weight: 500;
        color: #1a1a2e;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .info-item {
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f5;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f3f5;
    }
    
    .action-btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        width: 100%;
        border: none;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .action-btn:active {
        transform: translateY(0px);
    }
    
    .btn-approve {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    .btn-approve:hover {
        background: linear-gradient(135deg, #218838, #1aa179);
        color: white;
    }
    .btn-reject {
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        color: white;
    }
    .btn-reject:hover {
        background: linear-gradient(135deg, #c82333, #e55353);
        color: white;
    }
    .btn-back {
        background: #6c757d;
        color: white;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-back:hover {
        background: #5a6268;
        color: white;
        transform: translateX(-3px);
    }
    
    .timeline-item {
        padding: 12px 16px;
        border-left: 3px solid #e9ecef;
        margin-bottom: 12px;
        background: #f8f9fa;
        border-radius: 0 8px 8px 0;
        transition: all 0.2s ease;
    }
    .timeline-item:hover {
        background: #f1f3f5;
    }
    .timeline-item .time {
        font-size: 12px;
        color: #6c757d;
        font-weight: 500;
    }
    .timeline-item .event {
        font-size: 14px;
        font-weight: 500;
        color: #1a1a2e;
    }
    
    .overlap-alert {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px 18px;
        border-radius: 10px;
    }
    
    .info-card {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 16px;
        margin-top: 12px;
    }
    
    .badge-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .badge-dot.pending { background: #ffc107; }
    .badge-dot.approved { background: #28a745; }
    .badge-dot.rejected { background: #dc3545; }
    .badge-dot.cancelled { background: #6c757d; }
</style>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1" style="font-weight: 700; color: #1a1a2e;">
                        <i class="fas fa-home me-2" style="color: #4e73df;"></i>WFH Request Details
                    </h4>
                    <p class="text-muted mb-0" style="font-size: 14px;">
                        <i class="fas fa-calendar-alt me-1"></i> Request #{{ $request->request_id }}
                    </p>
                </div>
                <a href="{{ route('institute-admin.wfh-requests.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Request Details Card -->
            <div class="card detail-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start justify-content-between mb-4">
                        <h5 class="section-title mb-0">
                            <i class="fas fa-file-alt me-2" style="color: #4e73df;"></i>Request Information
                        </h5>
                        <span class="status-badge status-{{ $request->status }}">
                            <span class="badge-dot {{ $request->status }}"></span>
                            {{ ucfirst($request->status) }}
                        </span>
                    </div>

                    <div class="info-grid">
                        <div>
                            <div class="info-item">
                                <div class="detail-label">Request Date</div>
                                <div class="detail-value">
                                    <i class="far fa-calendar-alt me-2" style="color: #6c757d;"></i>
                                    {{ $request->created_at->format('d M, Y') }}
                                    <span style="font-size: 13px; color: #6c757d; font-weight: 400;">
                                        {{ $request->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="detail-label">Date Range</div>
                                <div class="detail-value">
                                    <i class="far fa-calendar me-2" style="color: #6c757d;"></i>
                                    {{ $request->formatted_date_range }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="info-item">
                                <div class="detail-label">Duration</div>
                                <div class="detail-value">
                                    <i class="far fa-clock me-2" style="color: #6c757d;"></i>
                                    {{ $request->duration }}
                                </div>
                            </div>
                            @if($request->start_time && $request->end_time)
                            <div class="info-item">
                                <div class="detail-label">Working Hours</div>
                                <div class="detail-value">
                                    <i class="fas fa-hourglass-half me-2" style="color: #6c757d;"></i>
                                    {{ $request->start_time }} — {{ $request->end_time }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="mt-3">
                        <div class="detail-label mb-2">Reason for Request</div>
                        <div class="detail-value" style="font-weight: 400; line-height: 1.6; background: #f8f9fa; padding: 14px 18px; border-radius: 10px;">
                            {{ $request->reason }}
                        </div>
                    </div>

                    @if($request->work_plan)
                    <div class="mt-3">
                        <div class="detail-label mb-2">Work Plan</div>
                        <div class="detail-value" style="font-weight: 400; line-height: 1.6; background: #f8f9fa; padding: 14px 18px; border-radius: 10px;">
                            {{ $request->work_plan }}
                        </div>
                    </div>
                    @endif

                    @if($request->emergency_contact)
                    <div class="mt-3">
                        <div class="detail-label mb-2">Emergency Contact</div>
                        <div class="detail-value" style="font-weight: 400; background: #f8f9fa; padding: 14px 18px; border-radius: 10px;">
                            <i class="fas fa-phone-alt me-2" style="color: #4e73df;"></i>
                            {{ $request->emergency_contact }}
                        </div>
                    </div>
                    @endif

                    @if($request->admin_remarks)
                    <div class="mt-3">
                        <div class="detail-label mb-2">Admin Remarks</div>
                        <div class="detail-value" style="font-weight: 400; background: #e7f3ff; padding: 14px 18px; border-radius: 10px; border-left: 4px solid #4e73df;">
                            <i class="fas fa-comment me-2" style="color: #4e73df;"></i>
                            {{ $request->admin_remarks }}
                            @if($request->processor)
                            <br><small style="color: #6c757d; font-size: 13px;">
                                Processed by: {{ $request->processor->name }}
                            </small>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Employee Information Card -->
            <div class="card detail-card mt-4">
                <div class="card-body p-4">
                    <h5 class="section-title">
                        <i class="fas fa-user-circle me-2" style="color: #4e73df;"></i>Employee Information
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="detail-label">Full Name</div>
                                <div class="detail-value">
                                    <i class="fas fa-user me-2" style="color: #6c757d;"></i>
                                    {{ $request->employee->name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="detail-label">Employee Code</div>
                                <div class="detail-value">
                                    <i class="fas fa-id-badge me-2" style="color: #6c757d;"></i>
                                    {{ $request->employee->employee_code ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-none">
                            <div class="info-item">
                                <div class="detail-label">Department</div>
                                <div class="detail-value">
                                    <i class="fas fa-building me-2" style="color: #6c757d;"></i>
                                    {{ $request->employee->department ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="detail-label">Email</div>
                                <div class="detail-value" style="font-weight: 400;">
                                    <i class="fas fa-envelope me-2" style="color: #6c757d;"></i>
                                    {{ $request->employee->email ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Section (Optional) -->
            <div class="card detail-card mt-4">
                <div class="card-body p-4">
                    <h5 class="section-title">
                        <i class="fas fa-history me-2" style="color: #4e73df;"></i>Request Timeline
                    </h5>
                    <div class="timeline-item">
                        <div class="d-flex justify-content-between">
                            <span class="event">
                                <i class="fas fa-plus-circle me-2" style="color: #28a745;"></i>
                                Request Created
                            </span>
                            <span class="time">{{ $request->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    @if($request->status != 'pending')
                    <div class="timeline-item">
                        <div class="d-flex justify-content-between">
                            <span class="event">
                                <i class="fas fa-check-circle me-2" style="color: {{ $request->status == 'approved' ? '#28a745' : '#dc3545' }};"></i>
                                Request {{ ucfirst($request->status) }}
                            </span>
                            <span class="time">{{ $request->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Shift Information -->
            @if(isset($shiftInfo))
            <div class="card detail-card">
                <div class="card-body p-4">
                    <h5 class="section-title" style="font-size: 14px;">
                        <i class="fas fa-clock me-2" style="color: #4e73df;"></i>Shift Information
                    </h5>
                    <div class="info-card">
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #6c757d; font-weight: 500;">Shift Name</span>
                            <span style="font-weight: 600; color: #1a1a2e;">{{ $shiftInfo['shift_name'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #6c757d; font-weight: 500;">Start Time</span>
                            <span style="font-weight: 600; color: #1a1a2e;">{{ $shiftInfo['start_time'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color: #6c757d; font-weight: 500;">End Time</span>
                            <span style="font-weight: 600; color: #1a1a2e;">{{ $shiftInfo['end_time'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            @if($request->canApprove())
            <div class="card detail-card mt-4">
                <div class="card-body p-4">
                    <h5 class="section-title" style="font-size: 14px;">
                        <i class="fas fa-tasks me-2" style="color: #4e73df;"></i>Actions
                    </h5>
                    <button onclick="approveRequest()" class="action-btn btn-approve mb-3">
                        <i class="fas fa-check-circle me-2"></i>Approve 
                    </button>
                    <button onclick="rejectRequest()" class="action-btn btn-reject">
                        <i class="fas fa-times-circle me-2"></i>Reject 
                    </button>
                </div>
            </div>
            @endif

            <!-- Overlapping Requests Alert -->
            @if(isset($overlapping) && $overlapping)
            <div class="card detail-card mt-4 border-warning">
                <div class="card-body p-4">
                    <div class="overlap-alert">
                        <i class="fas fa-exclamation-triangle me-2" style="color: #856404; font-size: 18px;"></i>
                        <strong style="color: #856404;">Overlapping Request Detected</strong>
                        <p class="mb-0 mt-1" style="color: #856404; font-size: 14px;">
                            This employee has overlapping WFH requests for the same date range.
                            Please review before approving.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="card detail-card mt-4">
                <div class="card-body p-4">
                    <h5 class="section-title" style="font-size: 14px;">
                        <i class="fas fa-chart-simple me-2" style="color: #4e73df;"></i>Quick Stats
                    </h5>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span style="color: #6c757d;">Request ID</span>
                        <span style="font-weight: 600; color: #1a1a2e;">#{{ $request->request_id }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span style="color: #6c757d;">Status</span>
                        <span class="status-badge status-{{ $request->request_status }}" style="font-size: 12px; padding: 4px 12px;">
                            {{ ucfirst($request->request_status) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span style="color: #6c757d;">Duration</span>
                        <span style="font-weight: 600; color: #1a1a2e;">{{ $request->duration }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function approveRequest() {
    Swal.fire({
        title: 'Approve Request?',
        text: 'This WFH request will be approved and the employee will be notified.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Approve it',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
            url: '{{ route("institute-admin.wfh-requests.update-status", $request->id) }}',
            method: 'POST',
            data: {
                status: 'approved',
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Approved!',
                        text: response.message || 'WFH request approved successfully',
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response.message || 'Could not approve the request',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Error processing request',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });
}

function rejectRequest() {
    Swal.fire({
        title: 'Reject Request',
        input: 'textarea',
        inputLabel: 'Reason for Rejection',
        inputPlaceholder: 'Please provide a clear reason for rejection...',
        inputAttributes: {
            'aria-label': 'Type your reason here',
            rows: 4
        },
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Reject',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        inputValidator: (value) => {
           
        },
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
            url: '{{ route("institute-admin.wfh-requests.update-status", $request->id) }}',
            method: 'POST',
            data: {
                status: 'rejected',
                remarks: result.value.trim(),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Rejected!',
                        text: response.message || 'WFH request rejected successfully',
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response.message || 'Could not reject the request',
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Error processing request',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    });
}
</script>

@endsection
@endsection