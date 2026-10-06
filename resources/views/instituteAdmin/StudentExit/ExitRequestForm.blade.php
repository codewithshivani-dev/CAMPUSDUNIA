@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
    .exit-card {
        cursor: pointer;
        border: 3px solid transparent;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .exit-card:hover:not(.disabled) {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .exit-card.selected {
        border-color: #f59e0b;
        background: #fffbeb;
        box-shadow: 0 5px 20px rgba(245, 158, 11, 0.2);
    }
    
    .exit-card .card-icon {
        font-size: 48px;
        margin-bottom: 15px;
        display: block;
    }
    
    .exit-card .card-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #1e293b;
    }
    
    .exit-card .card-desc {
        font-size: 14px;
        color: #64748b;
        line-height: 1.6;
    }
    
    .exit-card .check-mark {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 28px;
        height: 28px;
        background: #f59e0b;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
    }
    
    .exit-card.selected .check-mark {
        display: flex;
    }
    
    .exit-card .recommended-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #10b981;
        color: white;
        font-size: 11px;
        padding: 3px 12px;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .exit-card .card-emoji {
        font-size: 32px;
        display: block;
        margin-bottom: 12px;
    }
    
    .exit-card .feature-list {
        list-style: none;
        padding: 0;
        margin: 10px 0 0 0;
        text-align: left;
    }
    
    .exit-card .feature-list li {
        padding: 4px 0;
        font-size: 13px;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .exit-card .feature-list li i {
        color: #f59e0b;
        font-size: 14px;
    }
    
    .exit-card .feature-list li i.text-warning {
        color: #f59e0b;
    }
    
    .exit-card .feature-list li i.text-danger {
        color: #ef4444;
    }
    
    .exit-card .feature-list li i.text-success {
        color: #10b981;
    }
    
    .exit-card.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
        background: #f8fafc;
        position: relative;
    }
    
    .exit-card.disabled .card-title {
        color: #94a3b8;
    }
    
    .exit-card.disabled .card-desc {
        color: #94a3b8;
    }
    
    .exit-card.disabled .feature-list li {
        color: #94a3b8;
    }
    
    /* Remove the overlay that was blocking clicks */
    .exit-card .disabled-overlay {
        display: none;
    }
    
    /* Add a subtle disabled indicator */
    .exit-card.disabled::after {
        content: '🔒';
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        opacity: 0.7;
    }
    
    .page-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
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
        background: rgba(255,255,255,0.1);
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
    
    .course-status-badge {
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
    }
    
    .course-status-badge.completed {
        background: #dcfce7;
        color: #166534;
    }
    
    .course-status-badge.in-progress {
        background: #fef3c7;
        color: #92400e;
    }

    /* Modal Styles */
    .exit-disabled-modal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .exit-disabled-modal .modal-header {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-bottom: none;
        border-radius: 16px 16px 0 0;
        padding: 20px 24px;
    }

    .exit-disabled-modal .modal-header .modal-title {
        color: #78350f;
        font-weight: 700;
        font-size: 20px;
    }

    .exit-disabled-modal .modal-body {
        padding: 24px;
    }

    .exit-disabled-modal .modal-footer {
        border-top: none;
        padding: 16px 24px 24px;
    }

    .exit-disabled-modal .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        color: white;
        font-weight: 600;
        padding: 10px 30px;
        border-radius: 10px;
    }

    .exit-disabled-modal .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(245, 158, 11, 0.3);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <h1>
                    <i class="bi bi-door-open"></i>
                    Exit Request
                </h1>
                <div class="subtitle">
                    <i class="bi bi-info-circle me-1"></i>
                    Please select an exit type and provide details for your request
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <!-- Student Information Summary -->
                    <div class="alert alert-light border">
                        <h5><i class="bi bi-person me-2"></i> Student Information</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</p>
                                <p><strong>Registration:</strong> {{ $student->registration_number }}</p>
                                <p><strong>Email:</strong> {{ $student->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Course:</strong> {{ $academicDetails->course_type ?? 'N/A' }}</p>
                                <p><strong>Subtype:</strong> {{ $academicDetails->course_subtype ?? 'N/A' }}</p>
                                <p><strong>Batch:</strong> {{ $academicDetails->batch ?? 'N/A' }}</p>
                                <p><strong>Academic Year:</strong> {{ $academicDetails->academic_year ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($courseEndDate)
                            <div class="mt-2">
                                <strong>Course End Date:</strong> {{ $courseEndDate->format('d-m-Y') }}
                                @if($isCourseCompleted)
                                    <span class="course-status-badge completed ms-2">
                                        <i class="bi bi-check-circle me-1"></i> Course Completed
                                    </span>
                                @else
                                    <span class="course-status-badge in-progress ms-2">
                                        <i class="bi bi-clock me-1"></i> Course In Progress
                                    </span>
                                @endif
                            </div>
                            @if(!$isCourseCompleted)
                                <div class="mt-2 text-danger">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <small>Course completion is only available after the course end date ({{ $courseEndDate->format('d-m-Y') }}).</small>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Exit Request Form -->
                    <form id="exitRequestForm" method="POST" action="{{ route('student.exit.request.submit') }}">
                        @csrf
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Please read carefully:</strong> Submitting an exit request will initiate the process of exiting from the institute. This action requires admin approval and is not reversible once approved.
                        </div>

                        <!-- Exit Type Selection - Card Style -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">Select Exit Type <span class="text-danger">*</span></label>
                            <div class="row g-3 mt-2">
                                <!-- Course Completion Card -->
                                <div class="col-md-4">
                                    <div class="exit-card {{ $canSelectCourseCompletion && $isCourseCompleted ? 'selected' : '' }} {{ !$canSelectCourseCompletion ? 'disabled' : '' }}" 
                                         data-value="course_completion" 
                                         onclick="{{ $canSelectCourseCompletion ? 'selectExitType(this)' : 'return false;' }}">
                                        <div class="check-mark"><i class="bi bi-check-lg"></i></div>
                                        @if($isCourseCompleted)
                                            <div class="recommended-badge"><i class="bi bi-star-fill me-1"></i>Recommended</div>
                                        @endif
                                        <span class="card-emoji">🎓</span>
                                        <div class="card-title">Course Completion</div>
                                        <div class="card-desc">Exit after successfully completing your course</div>
                                        <ul class="feature-list">
                                            <li><i class="bi bi-check-circle text-success"></i> Eligible for completion certificate</li>
                                            <li><i class="bi bi-check-circle text-success"></i> Course completed successfully</li>
                                        </ul>
                                        @if(!$canSelectCourseCompletion)
                                            <div class="mt-2 text-warning" style="font-size: 12px;">
                                                <i class="bi bi-info-circle"></i>
                                                Course ends on {{ $courseEndDate ? $courseEndDate->format('d-m-Y') : 'N/A' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Mid-Session Card -->
                                <div class="col-md-4">
                                    <div class="exit-card {{ $isCourseCompleted ? 'disabled' : '' }}" 
                                         data-value="mid_session" 
                                         onclick="{{ $isCourseCompleted ? 'showDisabledModal(\'Mid-Session\')' : 'selectExitType(this)' }}">
                                        <div class="check-mark"><i class="bi bi-check-lg"></i></div>
                                        <span class="card-emoji">⏸️</span>
                                        <div class="card-title">Mid-Session</div>
                                        <div class="card-desc">Exit during the course before completion</div>
                                        <ul class="feature-list">
                                            <li><i class="bi bi-exclamation-triangle text-warning"></i> Partial course completion</li>
                                            <li><i class="bi bi-exclamation-triangle text-warning"></i> Requires admin review</li>
                                        </ul>
                                        @if($isCourseCompleted)
                                            <div class="mt-2 text-muted" style="font-size: 12px;">
                                                <i class="bi bi-lock"></i>
                                                Not available - Course already completed
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Cancellation Card -->
                                <div class="col-md-4">
                                    <div class="exit-card {{ $isCourseCompleted ? 'disabled' : '' }}" 
                                         data-value="cancellation" 
                                         onclick="{{ $isCourseCompleted ? 'showDisabledModal(\'Cancellation\')' : 'selectExitType(this)' }}">
                                        <div class="check-mark"><i class="bi bi-check-lg"></i></div>
                                        <span class="card-emoji">❌</span>
                                        <div class="card-title">Cancellation</div>
                                        <div class="card-desc">Cancel enrollment before course completion</div>
                                        <ul class="feature-list">
                                            <li><i class="bi bi-exclamation-triangle text-danger"></i> No academic credits earned</li>
                                            <li><i class="bi bi-exclamation-triangle text-danger"></i> Requires admin review</li>
                                        </ul>
                                        @if($isCourseCompleted)
                                            <div class="mt-2 text-muted" style="font-size: 12px;">
                                                <i class="bi bi-lock"></i>
                                                Not available - Course already completed
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="exit_type" id="selected_exit_type" value="{{ $canSelectCourseCompletion && $isCourseCompleted ? 'course_completion' : '' }}">
                            <small class="form-text text-muted mt-2">
                                <i class="bi bi-info-circle"></i>
                                Click on a card above to select your exit type
                                @if($isCourseCompleted)
                                    <br>
                                    <i class="bi bi-check-circle text-success"></i>
                                    <span class="text-success">Course completed! Only "Course Completion" option is available.</span>
                                @endif
                            </small>
                        </div>

                        <!-- Requested Exit Date -->
                        <div class="form-group mb-3">
                            <label for="requested_exit_date" class="form-label">Requested Exit Date</label>
                            <input type="date" name="requested_exit_date" id="requested_exit_date" 
                                   class="form-control" min="{{ date('Y-m-d') }}"
                                   value="{{ date('Y-m-d') }}">
                            <small class="form-text text-muted">If not specified, today's date will be used.</small>
                        </div>

                        <!-- Exit Reason -->
                        <div class="form-group mb-3">
                            <label for="exit_reason" class="form-label">Exit Reason <span class="text-danger">*</span></label>
                            <textarea name="exit_reason" id="exit_reason" class="form-control" rows="4" 
                                      placeholder="Please provide a detailed reason for your exit request..." required></textarea>
                            <small class="form-text text-muted">Minimum 10 characters. This will be reviewed by admin.</small>
                        </div>

                        <!-- Notify Parent -->
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="notify_parent" id="notify_parent" class="form-check-input" value="1">
                                <label for="notify_parent" class="form-check-label">
                                    <i class="bi bi-person"></i>
                                    Notify Parent/Guardian about this exit request
                                </label>
                            </div>
                        </div>

                        <!-- Confirmation -->
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="confirm_exit" id="confirm_exit" class="form-check-input" required>
                                <label for="confirm_exit" class="form-check-label">
                                    <i class="bi bi-check-circle"></i>
                                    I confirm that I want to initiate the exit process and understand that this requires admin approval.
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-3 mt-4">
                            <button type="submit" class="btn btn-warning btn-lg" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; min-width: 200px;">
                                <i class="bi bi-send me-2"></i> Submit
                            </button>
                            <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Disabled Exit Types -->
<div class="modal fade exit-disabled-modal" id="disabledExitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Option Not Available
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div style="font-size: 64px;">🎓</div>
                </div>
                <h5 class="text-center mb-3" id="disabledModalTitle">Mid-Session Exit</h5>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>This option is not available.</strong>
                </div>
                <p id="disabledModalMessage">
                    You have already completed your course. 
                    Please select the <strong>"Course Completion"</strong> option instead.
                </p>
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i class="bi bi-lightbulb me-1"></i>
                        <strong>Tip:</strong> Course Completion exit is the recommended option for students who have successfully completed their course.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i> Close
                </button>
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal" onclick="selectCourseCompletion()">
                    <i class="bi bi-check-circle me-2"></i> Select Course Completion
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let selectedExitType = '{{ $canSelectCourseCompletion && $isCourseCompleted ? "course_completion" : "" }}';
let isCourseCompleted = {{ $isCourseCompleted ? 'true' : 'false' }};

function selectExitType(element) {
    // Don't allow selection if disabled
    if (element.classList.contains('disabled')) {
        return false;
    }
    
    // Remove selected class from all cards
    document.querySelectorAll('.exit-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    element.classList.add('selected');
    
    // Update hidden input
    const value = element.getAttribute('data-value');
    document.getElementById('selected_exit_type').value = value;
    selectedExitType = value;
}

function showDisabledModal(exitType) {
    // Show the modal with the appropriate message
    const modal = new bootstrap.Modal(document.getElementById('disabledExitModal'));
    
    // Update modal content based on exit type
    const title = document.getElementById('disabledModalTitle');
    const message = document.getElementById('disabledModalMessage');
    
    if (exitType === 'Mid-Session') {
        title.textContent = 'Mid-Session Exit Not Available';
        message.innerHTML = `
            You have already completed your course. 
            The <strong>Mid-Session</strong> exit option is only available for students who are currently in the middle of their course.
            <br><br>
            Please select the <strong>"Course Completion"</strong> option instead.
        `;
    } else if (exitType === 'Cancellation') {
        title.textContent = 'Cancellation Not Available';
        message.innerHTML = `
            You have already completed your course. 
            The <strong>Cancellation</strong> exit option is only available for students who wish to cancel before course completion.
            <br><br>
            Please select the <strong>"Course Completion"</strong> option instead.
        `;
    }
    
    modal.show();
}

function selectCourseCompletion() {
    // Find and select the course completion card
    const courseCompletionCard = document.querySelector('.exit-card[data-value="course_completion"]');
    if (courseCompletionCard && !courseCompletionCard.classList.contains('disabled')) {
        selectExitType(courseCompletionCard);
    }
}

// Highlight recommended card on load if course is completed
document.addEventListener('DOMContentLoaded', function() {
    @if($canSelectCourseCompletion && $isCourseCompleted)
        const recommendedCard = document.querySelector('.exit-card[data-value="course_completion"]');
        if (recommendedCard && !recommendedCard.classList.contains('disabled')) {
            recommendedCard.classList.add('selected');
            document.getElementById('selected_exit_type').value = 'course_completion';
        }
    @endif

    // If course is completed, auto-select course completion
    @if($isCourseCompleted)
        const courseCompletionCard = document.querySelector('.exit-card[data-value="course_completion"]');
        if (courseCompletionCard && !courseCompletionCard.classList.contains('disabled')) {
            courseCompletionCard.classList.add('selected');
            document.getElementById('selected_exit_type').value = 'course_completion';
        }
    @endif
});

$(document).ready(function() {
    $('#exitRequestForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate exit type
        const exitType = document.getElementById('selected_exit_type').value;
        const exitReason = $('#exit_reason').val().trim();
        const confirmExit = $('#confirm_exit').is(':checked');
        
        if (!exitType) {
            Swal.fire({
                icon: 'error',
                title: 'Selection Required',
                text: 'Please select an exit type from the cards above.',
                confirmButtonColor: '#d97706'
            });
            return;
        }
        
        if (exitReason.length < 10) {
            Swal.fire({
                icon: 'error',
                title: 'Reason Too Short',
                text: 'Please provide a detailed exit reason (minimum 10 characters).',
                confirmButtonColor: '#d97706'
            });
            return;
        }
        
        if (!confirmExit) {
            Swal.fire({
                icon: 'error',
                title: 'Confirmation Required',
                text: 'Please confirm that you want to initiate the exit process.',
                confirmButtonColor: '#d97706'
            });
            return;
        }
        
        // Get exit type label for confirmation
        const exitTypeLabels = {
            'course_completion': 'Course Completion',
            'mid_session': 'Mid-Session',
            'cancellation': 'Cancellation'
        };
        const exitTypeLabel = exitTypeLabels[exitType] || exitType;
        
        // Confirm with user
        Swal.fire({
            title: 'Confirm Exit Request?',
            html: `
                <div style="text-align: left;">
                    <p>You are about to submit an <strong>${exitTypeLabel}</strong> exit request.</p>
                    <div class="alert alert-warning mt-2">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Important:</strong> This action requires admin approval and is not reversible once approved.
                    </div>
                    <p class="text-muted small mt-2">
                        <i class="bi bi-info-circle"></i>
                        You can cancel this request before it's approved by the admin.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d97706',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-send me-2"></i> Yes, Submit Request',
            cancelButtonText: '<i class="bi bi-x-circle me-2"></i> Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Submitting...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                const formData = new FormData(this);
                
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Submitted!',
                                html: `
                                    <p>${response.message}</p>
                                    <div class="alert alert-info mt-2">
                                        <i class="bi bi-clock me-2"></i>
                                        You will be notified once the admin reviews your request.
                                    </div>
                                    <p class="text-muted small">Request ID: #${response.data?.request_id || 'N/A'}</p>
                                `,
                                confirmButtonColor: '#d97706',
                                confirmButtonText: '<i class="bi bi-arrow-right me-2"></i> View Status'
                            }).then(() => {
                                window.location.href = response.redirect_url || '{{ route("student.exit.request.status") }}';
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Submission Failed',
                                text: response.message,
                                confirmButtonColor: '#d97706'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while submitting your request.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#d97706'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection