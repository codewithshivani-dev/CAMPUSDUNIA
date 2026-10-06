{{-- resources/views/instituteAdmin/StudentExit/AdminExitRequestDetails.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .status-badge {
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
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
    
    .info-section {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .info-section .label {
        font-weight: 600;
        color: #64748b;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-section .value {
        font-size: 16px;
        color: #1e293b;
        margin-top: 4px;
    }
    
    .timeline-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .timeline-item:last-child {
        border-bottom: none;
    }
    
    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .timeline-icon.pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .timeline-icon.approved {
        background: #dcfce7;
        color: #166534;
    }
    
    .timeline-icon.rejected {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .timeline-content {
        flex: 1;
    }
    
    .timeline-content .title {
        font-weight: 600;
        color: #1e293b;
    }
    
    .timeline-content .time {
        font-size: 13px;
        color: #94a3b8;
    }
    
    .timeline-content .description {
        color: #475569;
        margin-top: 4px;
        font-size: 14px;
    }
    
    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header" style="background: linear-gradient(135deg, #6366f1, #4f46e5); padding: 20px 30px; border-radius: 16px; margin-bottom: 30px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 style="color: white; margin: 0; display: flex; align-items: center; gap: 12px;">
                            <i class="bi bi-file-earmark-text"></i>
                            Exit Request Details
                        </h1>
                        <div class="mt-2" style="color: rgba(255,255,255,0.9);">
                            <i class="bi bi-info-circle me-1"></i>
                            Request #{{ $exitRequest->request_id ?? $exitRequest->id }}
                        </div>
                    </div>
                    <a href="{{ route('admin.exit.requests.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="alert alert-{{ $exitRequest->status == 'pending' ? 'warning' : ($exitRequest->status == 'approved' ? 'success' : ($exitRequest->status == 'rejected' ? 'danger' : 'secondary')) }} mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-{{ $exitRequest->status == 'pending' ? 'clock' : ($exitRequest->status == 'approved' ? 'check-circle' : ($exitRequest->status == 'rejected' ? 'x-circle' : 'ban')) }} me-2" style="font-size: 24px;"></i>
            <div>
                <strong>Status: {{ ucfirst($exitRequest->status) }}</strong>
                @if($exitRequest->status == 'pending')
                    <span class="ms-2 text-muted">Waiting for your review</span>
                @elseif($exitRequest->status == 'approved')
                    <span class="ms-2 text-muted">Processed on {{ $exitRequest->processed_at ? \Carbon\Carbon::parse($exitRequest->processed_at)->format('d-m-Y H:i') : 'N/A' }}</span>
                @elseif($exitRequest->status == 'rejected')
                    <span class="ms-2 text-muted">Rejected on {{ $exitRequest->processed_at ? \Carbon\Carbon::parse($exitRequest->processed_at)->format('d-m-Y H:i') : 'N/A' }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Student Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i> Student Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <div class="label">Full Name</div>
                                <div class="value">
                                    <strong>{{ $student->first_name ?? '' }} {{ $student->middle_name ?? '' }} {{ $student->last_name ?? '' }}</strong>
                                </div>
                            </div>
                            <div class="info-section">
                                <div class="label">Registration Number</div>
                                <div class="value">{{ $student->registration_number ?? 'N/A' }}</div>
                            </div>
                            <div class="info-section">
                                <div class="label">Email</div>
                                <div class="value">{{ $student->email ?? 'N/A' }}</div>
                            </div>
                            <div class="info-section">
                                <div class="label">Mobile</div>
                                <div class="value">{{ $student->mobile ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <div class="label">Course</div>
                                <div class="value">{{ $academicDetails->course_type ?? 'N/A' }}</div>
                            </div>
                            <div class="info-section">
                                <div class="label">Course Subtype</div>
                                <div class="value">{{ $academicDetails->course_subtype ?? 'N/A' }}</div>
                            </div>
                            <div class="info-section">
                                <div class="label">Batch</div>
                                <div class="value">{{ $academicDetails->batch ?? 'N/A' }}</div>
                            </div>
                            <div class="info-section">
                                <div class="label">Academic Year</div>
                                <div class="value">{{ $academicDetails->academic_year ?? 'N/A' }}</div>
                            </div>
                            @if($courseEndDate)
                            <div class="info-section">
                                <div class="label">Course End Date</div>
                                <div class="value">
                                    {{ $courseEndDate->format('d-m-Y') }}
                                    @if($isCourseCompleted)
                                        <span class="badge bg-success ms-2">Completed</span>
                                    @else
                                        <span class="badge bg-warning ms-2">In Progress</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exit Request Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i> Exit Request Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <div class="label">Exit Type</div>
                                <div class="value">
                                    <span class="badge bg-info">
                                        {{ ucwords(str_replace('_', ' ', $exitRequest->exit_type)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-section">
                                <div class="label">Requested Exit Date</div>
                                <div class="value">{{ $exitRequest->requested_exit_date ? \Carbon\Carbon::parse($exitRequest->requested_exit_date)->format('d-m-Y') : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <div class="label">Submitted On</div>
                                <div class="value">{{ $exitRequest->created_at->format('d-m-Y H:i:s') }}</div>
                            </div>
                            <div class="info-section">
                                <div class="label">Student Confirmed</div>
                                <div class="value">
                                    @if($exitRequest->student_confirmed)
                                        <span class="badge bg-success">Yes</span>
                                        <small class="text-muted ms-2">on {{ $exitRequest->student_confirmed_at ? \Carbon\Carbon::parse($exitRequest->student_confirmed_at)->format('d-m-Y H:i') : 'N/A' }}</small>
                                    @else
                                        <span class="badge bg-danger">No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <div class="label">Exit Reason</div>
                        <div class="value" style="white-space: pre-wrap;">{{ $exitRequest->exit_reason }}</div>
                    </div>

                    @if($exitRequest->additional_notes)
                    <div class="info-section">
                        <div class="label">Additional Notes</div>
                        <div class="value" style="white-space: pre-wrap;">{{ $exitRequest->additional_notes }}</div>
                    </div>
                    @endif

                    @if($exitRequest->admin_notes)
                    <div class="info-section" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
                        <div class="label" style="color: #92400e;">Admin Notes</div>
                        <div class="value" style="color: #78350f; white-space: pre-wrap;">{{ $exitRequest->admin_notes }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Parent/Guardian Information -->
            @if($student)
            <div class="card mb-4 d-none">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-people me-2"></i> Parent/Guardian Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-section">
                                <div class="label">Guardian Type</div>
                                <div class="value">{{ ucfirst($student->guardian_type ?? 'N/A') }}</div>
                            </div>
                            <div class="info-section">
                                <div class="label">Guardian Name</div>
                                <div class="value">
                                    @if($student->guardian_type == 'father')
                                        {{ $student->father_first_name ?? '' }} {{ $student->father_last_name ?? '' }}
                                    @elseif($student->guardian_type == 'mother')
                                        {{ $student->mother_first_name ?? '' }} {{ $student->mother_last_name ?? '' }}
                                    @else
                                        {{ $student->guardian_first_name ?? '' }} {{ $student->guardian_last_name ?? '' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section">
                                <div class="label">Guardian Contact</div>
                                <div class="value">{{ $student->guardian_contact ?? 'N/A' }}</div>
                            </div>
                            <div class="info-section">
                                <div class="label">Notify Parent</div>
                                <div class="value">
                                    @if($exitRequest->notify_parent)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline-item">
                        <div class="timeline-icon pending">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="title">Request Submitted</div>
                            <div class="time">{{ $exitRequest->created_at->format('d-m-Y H:i:s') }}</div>
                            <div class="description">Student submitted exit request</div>
                        </div>
                    </div>

                    @if($exitRequest->student_confirmed)
                    <div class="timeline-item">
                        <div class="timeline-icon approved">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="title">Student Confirmation</div>
                            <div class="time">{{ $exitRequest->student_confirmed_at ? \Carbon\Carbon::parse($exitRequest->student_confirmed_at)->format('d-m-Y H:i:s') : 'N/A' }}</div>
                            <div class="description">Student confirmed the exit request</div>
                        </div>
                    </div>
                    @endif

                    @if($exitRequest->status == 'approved')
                    <div class="timeline-item">
                        <div class="timeline-icon approved">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="title">Request Approved</div>
                            <div class="time">{{ $exitRequest->processed_at ? \Carbon\Carbon::parse($exitRequest->processed_at)->format('d-m-Y H:i:s') : 'N/A' }}</div>
                            <div class="description">
                                Processed by: {{ $exitRequest->processedBy->name ?? 'System' }}
                                @if($exitRequest->admin_notes)
                                    <br><small class="text-muted">Notes: {{ $exitRequest->admin_notes }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($exitRequest->status == 'rejected')
                    <div class="timeline-item">
                        <div class="timeline-icon rejected">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="title">Request Rejected</div>
                            <div class="time">{{ $exitRequest->processed_at ? \Carbon\Carbon::parse($exitRequest->processed_at)->format('d-m-Y H:i:s') : 'N/A' }}</div>
                            <div class="description">
                                Processed by: {{ $exitRequest->processedBy->name ?? 'System' }}
                                @if($exitRequest->admin_notes)
                                    <br><strong>Reason:</strong> {{ $exitRequest->admin_notes }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($exitRequest->status == 'cancelled')
                    <div class="timeline-item">
                        <div class="timeline-icon cancelled">
                            <i class="bi bi-ban"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="title">Request Cancelled</div>
                            <div class="time">{{ $exitRequest->updated_at->format('d-m-Y H:i:s') }}</div>
                            <div class="description">Student cancelled the exit request</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions -->
            @if($exitRequest->status == 'pending')
            <div class="card mb-4" style="border: 2px solid #f59e0b;">
                <div class="card-header" style="background: #fef3c7;">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i> Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <button class="btn btn-success btn-lg" onclick="approveRequest({{ $exitRequest->id }})">
                            <i class="bi bi-check-circle me-2"></i> Approve 
                        </button>
                        <button class="btn btn-danger btn-lg" onclick="rejectRequest({{ $exitRequest->id }})">
                            <i class="bi bi-x-circle me-2"></i> Reject 
                        </button>
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">Review the request details carefully before taking action.</small>
                        </div>
                    </div>
                </div>
            </div>
            @elseif($exitRequest->status == 'approved')
            <div class="card mb-4" style="border: 2px solid #22c55e;">
                <div class="card-header" style="background: #dcfce7;">
                    <h5 class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Approved</h5>
                </div>
                <div class="card-body">
                    <p class="text-center text-success">
                        <i class="bi bi-check-circle" style="font-size: 48px;"></i>
                    </p>
                    <p class="text-center">This request has been approved and processed.</p>
                    <div class="text-center">
                        <small class="text-muted">Processed on {{ $exitRequest->processed_at ? \Carbon\Carbon::parse($exitRequest->processed_at)->format('d-m-Y H:i') : 'N/A' }}</small>
                    </div>
                </div>
            </div>
            @elseif($exitRequest->status == 'rejected')
            <div class="card mb-4" style="border: 2px solid #ef4444;">
                <div class="card-header" style="background: #fee2e2;">
                    <h5 class="mb-0"><i class="bi bi-x-circle-fill text-danger me-2"></i> Rejected</h5>
                </div>
                <div class="card-body">
                    <p class="text-center text-danger">
                        <i class="bi bi-x-circle" style="font-size: 48px;"></i>
                    </p>
                    <p class="text-center">This request has been rejected.</p>
                    @if($exitRequest->admin_notes)
                        <div class="alert alert-danger">
                            <strong>Reason:</strong><br>
                            {{ $exitRequest->admin_notes }}
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Request Metadata -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i> Request Metadata</h5>
                </div>
                <div class="card-body">
                    <div class="info-section" style="background: none; padding: 8px 0;">
                        <div class="label">Request ID</div>
                        <div class="value" style="font-size: 14px;">{{ $exitRequest->request_id ?? $exitRequest->id }}</div>
                    </div>
                    <div class="info-section" style="background: none; padding: 8px 0;">
                        <div class="label">IP Address</div>
                        <div class="value" style="font-size: 14px;">{{ $exitRequest->ip_address ?? 'N/A' }}</div>
                    </div>
                    <div class="info-section" style="background: none; padding: 8px 0;">
                        <div class="label">User Agent</div>
                        <div class="value" style="font-size: 12px; word-break: break-all;">{{ $exitRequest->user_agent ?? 'N/A' }}</div>
                    </div>
                    @if($exitRequest->metadata)
                    <div class="info-section" style="background: none; padding: 8px 0; display: none;">
                        <div class="label">Additional Data</div>
                        <div class="value" style="font-size: 12px;">
                            <pre style="background: #f8fafc; padding: 10px; border-radius: 4px; margin: 0; font-size: 11px; max-height: 150px; overflow-y: auto;">{{ json_encode($exitRequest->metadata, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
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
            <div class="form-group text-left">
                <label for="admin_notes">Admin Notes (Optional)</label>
                <textarea id="admin_notes" class="form-control" rows="3" placeholder="Add any notes about this approval..."></textarea>
            </div>
            <div class="form-group text-left mt-2">
                <label for="exit_date">Exit Date</label>
                <input type="date" id="exit_date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="form-check text-left mt-2">
                <input type="checkbox" id="clear_dues" class="form-check-input" checked>
                <label for="clear_dues" class="form-check-label">Mark dues as cleared</label>
            </div>
            <div class="form-check text-left">
                <input type="checkbox" id="generate_no_due" class="form-check-input" checked>
                <label for="generate_no_due" class="form-check-label">Generate No Due Certificate</label>
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
            const clearDues = document.getElementById('clear_dues').checked;
            const generateNoDue = document.getElementById('generate_no_due').checked;
            
            return { admin_notes: adminNotes, exit_date: exitDate, clear_dues: clearDues, generate_no_due: generateNoDue };
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
                    clear_dues: data.clear_dues ? 1 : 0,
                    generate_no_due: data.generate_no_due ? 1 : 0
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: response.message,
                            confirmButtonColor: '#22c55e'
                        }).then(() => {
                            window.location.href = response.redirect_url || '{{ route("admin.exit.requests.index") }}';
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
                <textarea id="rejection_reason" class="form-control" rows="4" placeholder="Enter rejection reason (minimum 10 characters)..."></textarea>
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
                            confirmButtonColor: '#ef4444'
                        }).then(() => {
                            window.location.href = response.redirect_url || '{{ route("admin.exit.requests.index") }}';
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