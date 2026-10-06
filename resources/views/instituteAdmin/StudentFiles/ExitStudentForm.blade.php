{{-- resources/views/instituteAdmin/StudentFiles/ExitStudentForm.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exit Student</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
}

.page-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 30px;
    background: var(--primary-gradient);
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    position: relative;
    overflow: hidden;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    position: relative;
    z-index: 1;
}

.page-title i {
    font-size: 32px;
    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
}

.exit-form-container {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.student-info-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    border-left: 4px solid var(--primary-color);
    margin-bottom: 25px;
}

.student-info-card .info-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 5px 0;
}

.student-info-card .info-item i {
    color: var(--primary-color);
    width: 20px;
}

.exit-type-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.exit-type-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.exit-type-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
}

.exit-type-card.selected {
    border-color: var(--primary-color);
    background: rgba(67, 97, 238, 0.05);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
}

.exit-type-card.selected::after {
    content: '✓';
    position: absolute;
    top: -10px;
    right: -10px;
    background: var(--primary-color);
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
}

.exit-type-card .icon {
    font-size: 36px;
    margin-bottom: 10px;
    display: block;
}

.exit-type-card .title {
    font-weight: 600;
    font-size: 16px;
    color: #1e293b;
}

.exit-type-card .description {
    font-size: 13px;
    color: #64748b;
    margin-top: 5px;
}

.exit-type-card .badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 8px;
}

.badge-success {
    background: #dcfce7;
    color: #166534;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

.course-completed-alert {
    background: #dcfce7;
    border: 1px solid #86efac;
    border-radius: 10px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.course-completed-alert i {
    color: #10b981;
    font-size: 24px;
}

.course-completed-alert .text {
    color: #166534;
    font-weight: 500;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    color: #334155;
    display: block;
    margin-bottom: 5px;
}

.form-group .required {
    color: #ef4444;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

.form-control:disabled {
    background: #f8fafc;
    cursor: not-allowed;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

.form-check {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.form-check input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: var(--primary-color);
}

.form-check label {
    font-weight: 500;
    color: #334155;
    cursor: pointer;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.btn {
    padding: 12px 28px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    font-size: 14px;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #e2e8f0;
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Loading Spinner */
.spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }

    .page-header {
        padding: 15px 20px;
    }

    .page-title {
        font-size: 20px;
    }

    .exit-type-cards {
        grid-template-columns: 1fr;
    }

    .exit-form-container {
        padding: 15px;
    }

    .action-buttons {
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        flex: 1;
        justify-content: center;
    }
}

/* Alert Messages */
.alert {
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-success {
    background: #dcfce7;
    border: 1px solid #86efac;
    color: #166534;
}

.alert-danger {
    background: #fee2e2;
    border: 1px solid #fca5a5;
    color: #991b1b;
}

.alert-warning {
    background: #fef3c7;
    border: 1px solid #fcd34d;
    color: #92400e;
}

.alert-info {
    background: #dbeafe;
    border: 1px solid #93c5fd;
    color: #1e40af;
}

/* Style for disabled date input */
#exit_date:disabled {
    background-color: #f1f5f9;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Info message for locked date */
#dateLockedMessage {
    animation: slideIn 0.3s ease;
    background: #e0f2fe;
    border: 1px solid #93c5fd;
    border-radius: 8px;
    padding: 10px 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #1e40af;
}

#dateLockedMessage i {
    color: #3b82f6;
    font-size: 18px;
}
</style>


<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-door-open-fill"></i>
            Exit Student
        </h1>
        <a href="{{ route('students.index') }}" class="btn btn-secondary" style="color: #475569 !important;">
            <i class="bi bi-arrow-left"></i> Back to Students
        </a>
    </div>

    <!-- Messages -->
    @if (session('error'))
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="exit-form-container">
        <!-- Student Information -->
        <div class="student-info-card">
            <h5 style="color: var(--primary-color); margin-bottom: 15px;">
                <i class="bi bi-person-badge"></i> Student Information
            </h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <i class="bi bi-person"></i>
                        <strong>Name:</strong> {{ $student->first_name }} {{ $student->middle_name }}
                        {{ $student->last_name }}
                    </div>
                    <div class="info-item">
                        <i class="bi bi-qr-code"></i>
                        <strong>Registration No:</strong> {{ $student->registration_number }}
                    </div>
                    <div class="info-item">
                        <i class="bi bi-envelope"></i>
                        <strong>Email:</strong> {{ $student->email }}
                    </div>
                    <div class="info-item">
                        <i class="bi bi-phone"></i>
                        <strong>Mobile:</strong> {{ $student->mobile }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <i class="bi bi-book"></i>
                        <strong>Course:</strong> {{ $academicDetails->course_type ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <i class="bi bi-grid-3x3"></i>
                        <strong>Course Subtype:</strong> {{ $academicDetails->course_subtype ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <i class="bi bi-layers"></i>
                        <strong>Batch:</strong> {{ $academicDetails->batch ?? 'N/A' }}
                    </div>
                    <div class="info-item">
                        <i class="bi bi-calendar"></i>
                        <strong>Academic Year:</strong> {{ $academicDetails->academic_year ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Completion Status -->
        @if($isCourseCompleted && $courseEndDate)
        <div class="course-completed-alert">
            <i class="bi bi-check-circle-fill"></i>
            <div>
                <span class="text">✅ This student has completed the course!</span>
                <div style="font-size: 13px; color: #065f46;">
                    Course completed on: {{ $courseEndDate->format('d-m-Y') }}
                </div>
            </div>
        </div>
        @elseif($courseEndDate)
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                <span style="font-weight: 500;">Course End Date: {{ $courseEndDate->format('d-m-Y') }}</span>
                <div style="font-size: 13px; color: #1e40af;">
                    {{ $courseEndDate->diffForHumans() }}
                </div>
            </div>
        </div>
        @endif

        <!-- Exit Type Selection -->
        <h5 style="color: #1e293b; margin-bottom: 15px;">
            <i class="bi bi-arrow-right-circle"></i> Select Exit Type
            <span class="text-danger">*</span>
        </h5>

        <div class="exit-type-cards">
            <!-- Course Completion Card -->
            @php
            $courseEndDateStr = $courseEndDate ? $courseEndDate->format('d-m-Y') : 'N/A';
            $isDisabled = !$isCourseCompleted;
            @endphp

            <div class="exit-type-card {{ $isCourseCompleted ? 'selected' : '' }}"
                onclick="window.selectExitType('course_completion')"
                data-type="course_completion"
                style="{{ !$isCourseCompleted ? 'opacity: 0.6; cursor: not-allowed;' : '' }}">
                <span class="icon">🎓</span>
                <div class="title">Course Completion</div>
                <div class="description">Student has successfully completed the course</div>
                @if($isCourseCompleted)
                <span class="badge badge-success"><i class="bi bi-check-circle"></i> Available</span>
                @elseif($courseEndDate)
                <span class="badge badge-warning"><i class="bi bi-clock"></i> Available on
                    {{ $courseEndDate->format('d-m-Y') }}</span>
                @else
                <span class="badge badge-warning"><i class="bi bi-clock"></i> End date not set</span>
                @endif
            </div>

            <!-- Mid Session -->
            <div class="exit-type-card" onclick="window.selectExitType('mid_session')" data-type="mid_session">
                <span class="icon">🔄</span>
                <div class="title">Mid-Session</div>
                <div class="description">Student is leaving in the middle of the session</div>
            </div>

            <!-- Cancellation -->
            <div class="exit-type-card" onclick="window.selectExitType('cancellation')" data-type="cancellation">
                <span class="icon">❌</span>
                <div class="title">Cancellation</div>
                <div class="description">Student is cancelling the admission</div>
            </div>
        </div>

        <!-- Exit Form -->
        <form id="exitForm" action="{{ route('institute.admin.student.exit.process', $student->student_hash_id) }}"
            method="POST">
            @csrf

            <input type="hidden" name="exit_type" id="exit_type"
                value="{{ $isCourseCompleted ? 'course_completion' : '' }}">

            <div class="form-group">
                <label for="exit_date">Exit Date <span class="required">*</span></label>
                <input type="date" name="exit_date" id="exit_date" class="form-control" required
                    value="{{ $isCourseCompleted && $courseEndDate ? $courseEndDate->format('Y-m-d') : now()->format('Y-m-d') }}"
                    max="{{ $isCourseCompleted && $courseEndDate ? $courseEndDate->format('Y-m-d') : now()->format('Y-m-d') }}">
                @if($isCourseCompleted && $courseEndDate)
                <div id="dateLockedMessage" class="alert alert-info mt-2" style="margin-bottom: 0;">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Course Completion date is fixed to course end date:
                        {{ $courseEndDate->format('d-m-Y') }}</span>
                </div>
                @endif
            </div>

            <!-- Exit Reason - ADDED BACK -->
            <div class="form-group">
                <label for="exit_reason">Exit Reason</label>
                <textarea name="exit_reason" id="exit_reason" class="form-control" 
                    placeholder="Please provide a reason for the student's exit (optional)"></textarea>
            </div>

            <!-- Double Confirmation Section -->
            <div
                style="background: #fef3c7; border-radius: 10px; padding: 20px; margin-top: 20px; border: 1px solid #fcd34d;">
                <h6 style="color: #92400e; margin-bottom: 15px;">
                    <i class="bi bi-exclamation-triangle-fill"></i> Confirmation Required
                </h6>

                <div class="form-check">
                    <input type="checkbox" name="confirm_exit" id="confirm_exit" value="1" required>
                    <label for="confirm_exit" style="font-weight: 600; color: #92400e;">
                        I confirm that I have verified all details and this student is being exited from the system.
                        <span class="text-danger">*</span>
                    </label>
                </div>

                <div class="form-check mt-2">
                    <input type="checkbox" name="confirm_data_verified" id="confirm_data_verified" value="1" required>
                    <label for="confirm_data_verified" style="font-weight: 600; color: #92400e;">
                        I confirm that all dues have been settled and all data has been verified.
                        <span class="text-danger">*</span>
                    </label>
                </div>

                @if($isCourseCompleted && $courseEndDate)
                <div class="alert alert-info mt-2" style="margin-bottom: 0;">
                    <i class="bi bi-info-circle"></i>
                    <strong>Course Completion Notice:</strong>
                    Course end date is <strong>{{ $courseEndDate->format('d-m-Y') }}</strong>.
                    The exit date has been automatically set to this date.
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="bi bi-arrow-left"></i> Cancel
                </button>
                <button type="submit" class="btn btn-danger" id="exitBtn">
                    <i class="bi bi-door-open"></i> Confirm Exit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Make functions globally accessible
window.selectExitType = function(type) {
    // Check if course completion is being selected but course is not completed
    @if(!$isCourseCompleted && $courseEndDate)
    if (type === 'course_completion') {
        var courseEndDateStr = @json($courseEndDate ? $courseEndDate->format('d-m-Y') : 'N/A');
        window.showCourseCompletionNotAvailable(courseEndDateStr);
        return;
    }
    @endif

    // Update selected card
    document.querySelectorAll('.exit-type-card').forEach(function(card) {
        card.classList.remove('selected');
    });
    var selectedCard = document.querySelector('.exit-type-card[data-type="' + type + '"]');
    if (selectedCard) {
        selectedCard.classList.add('selected');
    }

    // Update hidden input
    document.getElementById('exit_type').value = type;

    var exitDateInput = document.getElementById('exit_date');
    var today = new Date().toISOString().split('T')[0];

    // Update exit date based on type
    @if($isCourseCompleted && $courseEndDate)
    if (type === 'course_completion') {
        // For course completion: lock the date to course end date
        var courseEndDate = @json($courseEndDate->format('Y-m-d'));
        exitDateInput.value = courseEndDate;
        exitDateInput.max = courseEndDate;
        exitDateInput.min = courseEndDate;
        exitDateInput.disabled = true;
        exitDateInput.style.backgroundColor = '#f1f5f9';
        exitDateInput.style.cursor = 'not-allowed';

        // Show info message
        var message = @json('Course Completion date is fixed to course end date: ' . ($courseEndDate ? $courseEndDate->format('d-m-Y') : ''));
        window.showDateLockedMessage(message);
    } else {
        // For other types: allow date selection up to today
        exitDateInput.value = today;
        exitDateInput.max = today;
        exitDateInput.min = '';
        exitDateInput.disabled = false;
        exitDateInput.style.backgroundColor = '';
        exitDateInput.style.cursor = '';

        // Hide any info message
        window.hideDateLockedMessage();
    }
    @endif

    // Validate based on type
    window.validateExitType(type);
};

// Make helper functions globally accessible
window.showCourseCompletionNotAvailable = function(courseEndDate) {
    Swal.fire({
        icon: 'warning',
        title: 'Course Completion Not Available',
        html: '<p>Course completion is only available after the course end date.</p><p><strong>Course End Date:</strong> ' + courseEndDate + '</p><p><strong>Today:</strong> ' + new Date().toLocaleDateString('en-GB') + '</p><p class="text-muted">Please select <strong>Mid-Session</strong> or <strong>Cancellation</strong> instead.</p>',
        confirmButtonColor: '#f59e0b',
        confirmButtonText: 'OK'
    });
};

window.showDateLockedMessage = function(message) {
    // Remove existing message if any
    window.hideDateLockedMessage();

    // Create and show the message
    var messageDiv = document.createElement('div');
    messageDiv.id = 'dateLockedMessage';
    messageDiv.className = 'alert alert-info mt-2';
    messageDiv.style.marginBottom = '0';
    messageDiv.innerHTML = '<i class="bi bi-info-circle-fill"></i><span>' + message + '</span>';

    // Insert after the exit date input
    var dateInputGroup = document.getElementById('exit_date').closest('.form-group');
    if (dateInputGroup) {
        dateInputGroup.appendChild(messageDiv);
    }
};

window.hideDateLockedMessage = function() {
    var existingMessage = document.getElementById('dateLockedMessage');
    if (existingMessage) {
        existingMessage.remove();
    }
};

window.validateExitType = function(type) {
    // If mid-session or cancellation, show warning
    if (type === 'mid_session' || type === 'cancellation') {
        var isCourseCompleted = @json($isCourseCompleted);
        if (isCourseCompleted) {
            Swal.fire({
                icon: 'warning',
                title: 'Course Already Completed',
                text: 'This student has already completed the course. Consider using "Course Completion" instead.',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'I Understand'
            });
        }
    }
};

// Auto-select Course Completion if available
document.addEventListener('DOMContentLoaded', function() {
    @if($isCourseCompleted)
    window.selectExitType('course_completion');
    @endif

    // Set max date for exit date
    var exitDateInput = document.getElementById('exit_date');
    var today = new Date().toISOString().split('T')[0];

    @if($isCourseCompleted && $courseEndDate)
    exitDateInput.max = @json($courseEndDate->format('Y-m-d'));
    @else
    exitDateInput.max = today;
    @endif
});

// Main Form Submission
document.getElementById('exitForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Get form values
    var exitType = document.getElementById('exit_type').value;
    var exitDate = document.getElementById('exit_date').value;
    var exitReason = document.getElementById('exit_reason') ? document.getElementById('exit_reason').value : '';

    // 1. Validate Exit Type
    if (!exitType) {
        Swal.fire({
            icon: 'error',
            title: 'Exit Type Required',
            text: 'Please select an exit type before proceeding.',
            confirmButtonColor: '#4361ee',
            confirmButtonText: 'OK'
        });
        return;
    }

    // 2. Validate Exit Date
    if (!exitDate) {
        Swal.fire({
            icon: 'error',
            title: 'Exit Date Required',
            text: 'Please select an exit date.',
            confirmButtonColor: '#4361ee',
            confirmButtonText: 'OK'
        });
        return;
    }

    // 3. Check double confirmation checkboxes
    var confirmExit = document.getElementById('confirm_exit');
    var confirmDataVerified = document.getElementById('confirm_data_verified');

    if (!confirmExit || !confirmExit.checked) {
        Swal.fire({
            icon: 'warning',
            title: 'Confirmation Required',
            text: 'Please confirm that you have verified all details and this student is being exited from the system.',
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (!confirmDataVerified || !confirmDataVerified.checked) {
        Swal.fire({
            icon: 'warning',
            title: 'Confirmation Required',
            text: 'Please confirm that all dues have been settled and all data has been verified.',
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'OK'
        });
        return;
    }

    // 4. For course completion, auto-set the date to course end date
    @if($isCourseCompleted && $courseEndDate)
    if (exitType === 'course_completion') {
        // Override the exit date with course end date
        var courseEndDate = @json($courseEndDate->format('Y-m-d'));
        document.getElementById('exit_date').value = courseEndDate;

        // Show a message that the date is being set automatically
        Swal.fire({
            icon: 'info',
            title: 'Date Auto-Set',
            html: '<p>For Course Completion, the exit date has been automatically set to:</p><p style="font-size: 20px; font-weight: bold; color: #4361ee;">@json($courseEndDate->format('d-m-Y'))</p><p class="text-muted small">This date cannot be changed for course completion exits.</p>',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
        });

        // Submit after a small delay to show the message
        setTimeout(function() {
            window.submitExitForm();
        }, 1500);
        return;
    }
    @endif

    // 5. Show final confirmation for other exit types
    window.showExitConfirmation();
});

// Separate function for form submission
window.submitExitForm = function() {
    var btn = document.getElementById('exitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Processing...';
    btn.style.opacity = '0.7';
    btn.style.cursor = 'not-allowed';

    // Show processing message
    Swal.fire({
        title: 'Processing Exit...',
        text: 'Please wait while we process the student exit.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: function() {
            Swal.showLoading();
        }
    });

    setTimeout(function() {
        document.getElementById('exitForm').submit();
    }, 500);
};

// Function to show exit confirmation
window.showExitConfirmation = function() {
    var exitType = document.getElementById('exit_type').value;
    var exitDate = document.getElementById('exit_date').value;
    var exitReason = document.getElementById('exit_reason') ? document.getElementById('exit_reason').value : '';

    var exitTypeLabels = {
        'course_completion': '🎓 Course Completion',
        'mid_session': '🔄 Mid-Session',
        'cancellation': '❌ Cancellation'
    };

    var studentName = @json(trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')));
    var registrationNumber = @json($student->registration_number ?? '');
    var courseName = @json($academicDetails->course_subtype ?? 'N/A');
    var sectionName = @json($sectionDisplayName ?? $academicDetails->section_name ?? 'N/A');

    var exitTypeLabel = exitTypeLabels[exitType] || exitType;
    var formattedDate = new Date(exitDate).toLocaleDateString('en-GB');
    
    var htmlContent = '<div style="text-align: left; max-height: 400px; overflow-y: auto;">';
    htmlContent += '<div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 15px;">';
    htmlContent += '<p style="margin: 0;"><strong>Student:</strong> ' + studentName + '</p>';
    htmlContent += '<p style="margin: 5px 0;"><strong>Registration No:</strong> ' + registrationNumber + '</p>';
    htmlContent += '<p style="margin: 5px 0;"><strong>Course:</strong> ' + courseName + '</p>';
    htmlContent += '<p style="margin: 5px 0;"><strong>Section:</strong> ' + sectionName + '</p>';
    htmlContent += '</div>';
    htmlContent += '<div style="background: #e0f2fe; padding: 15px; border-radius: 8px; margin-bottom: 15px;">';
    htmlContent += '<p style="margin: 0;"><strong>Exit Type:</strong> ' + exitTypeLabel + '</p>';
    htmlContent += '<p style="margin: 5px 0;"><strong>Exit Date:</strong> ' + formattedDate + '</p>';
    if (exitReason) {
        htmlContent += '<p style="margin: 5px 0;"><strong>Reason:</strong> ' + exitReason + '</p>';
    }
    htmlContent += '</div>';

    @if($isCourseCompleted && $courseEndDate)
    htmlContent += '<div style="background: #f0fdf4; padding: 15px; border-radius: 8px; border: 2px solid #86efac; margin-bottom: 15px;">';
    htmlContent += '<p style="color: #166534; margin: 0;"><strong>📌 Course Completion Notice:</strong></p>';
    htmlContent += '<p style="color: #166534; margin: 5px 0 0 0; font-size: 13px;">Exit date will be set to course end date: <strong>@json($courseEndDate->format('d-m-Y'))</strong></p>';
    htmlContent += '</div>';
    @endif

    htmlContent += '<div style="background: #fee2e2; padding: 15px; border-radius: 8px; border: 2px solid #fca5a5;">';
    htmlContent += '<p style="color: #991b1b; margin: 0;"><strong>⚠️ This action cannot be undone!</strong></p>';
    htmlContent += '<p style="color: #991b1b; margin: 5px 0 0 0; font-size: 13px;">The student will be marked as exited and the seat will be released.</p>';
    htmlContent += '</div></div>';

    Swal.fire({
        title: '⚠️ Final Confirmation Required',
        html: htmlContent,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '✅ Yes, Exit Student',
        cancelButtonText: '❌ Cancel',
        width: 650,
        padding: '20px',
        allowOutsideClick: false,
        allowEscapeKey: true,
    }).then(function(result) {
        if (result.isConfirmed) {
            window.submitExitForm();
        }
    });
};
</script>
@endsection