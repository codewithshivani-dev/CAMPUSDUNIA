{{-- resources/views/instituteAdmin/Shifts/create.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
}

.form-card {
    background: white;
    border-radius: 16px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.form-card-header {
    background: var(--primary-gradient);
    color: white;
    padding: 16px 24px;
    border-radius: 16px 16px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
}

.form-card-body {
    padding: 24px;
}

.form-label {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.form-control,
.form-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 14px;
    transition: all 0.3s;
    background: white;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    outline: none;
}

.form-control:hover,
.form-select:hover {
    border-color: var(--secondary-color);
}

.flexibility-card {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 12px;
    padding: 16px 20px;
    border-left: 4px solid var(--primary-color);
    transition: all 0.3s;
    margin-top: 16px;
}

.flexibility-card:hover {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.form-check-label {
    font-weight: 500;
    color: #334155;
    font-size: 15px;
}

.btn-filter-primary {
    background: var(--primary-gradient);
    color: white;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    border: none;
    transition: all 0.3s;
}

.btn-filter-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
    color: white;
}

.btn-filter-secondary {
    background: #f1f5f9;
    color: #475569;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 500;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}

.btn-filter-secondary:hover {
    background: #e2e8f0;
    color: #475569;
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
}

.page-title i {
    font-size: 32px;
    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
}

.time-display {
    background: #f8fafc;
    padding: 10px 16px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-size: 14px;
    color: #475569;
}

.time-display strong {
    color: var(--primary-color);
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #ccc;
    border-top-color: #4361ee;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

#pageLoader {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.7);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.weekday-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.weekday-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.weekday-checkbox label {
    font-size: 12px;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    margin: 0;
}

.required-star {
    color: #dc2626;
    margin-left: 2px;
}

.help-text {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 4px;
}

.form-group {
    margin-bottom: 16px;
}

.flexibility-section {
    border-top: 2px dashed #e2e8f0;
    padding-top: 20px;
    margin-top: 20px;
}

.flexibility-section-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.flexibility-section-title i {
    color: var(--primary-color);
}

@media (max-width: 768px) {
    .page-title {
        font-size: 20px;
    }

    .form-card-body {
        padding: 16px;
    }
}
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-plus-circle"></i>
            Create New Shift
        </h1>
        <div>
            <a href="{{ route('manage.shifts.index') }}" class="btn-filter btn-filter-secondary"
                style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Create Shift Form -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock-history"></i>
                Shift Details
            </div>
        </div>
        <div class="form-card-body">
            <form id="shiftForm" method="POST">
                @csrf

                <!-- Basic Information -->
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">Shift Name <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="shift_name"
                            placeholder="e.g., Morning Shift, Evening Shift" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="form-label">Priority <span class="required-star">*</span></label>
                        <select class="form-select" name="priority" required>
                            <option value="high">High</option>
                            <option value="medium" selected>Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">Start Date <span class="required-star">*</span></label>
                        <input type="date" class="form-control" name="start_date" min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date" placeholder="Optional">
                        <div class="help-text">Leave empty for ongoing shift</div>
                    </div>
                </div>

                <!-- Shift Timing -->
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">Start Time <span class="required-star">*</span></label>
                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="form-label">End Time <span class="required-star">*</span></label>
                        <input type="time" class="form-control" id="end_time" name="end_time" required>
                    </div>
                </div>

                <!-- Time Display -->
                <div class="row">
                    <div class="col-12">
                        <div class="time-display mb-3" id="timeDisplay">
                            <i class="bi bi-info-circle me-2"></i>
                            <span>Shift timing will be displayed here in 12-hour format</span>
                        </div>
                    </div>
                </div>

                <!-- Working Hours -->
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="form-label">Working Hours <span class="required-star">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="working_hours" name="working_hours" step="0.1"
                                min="0" max="24" placeholder="Hours" required readonly>
                            <span class="input-group-text">hrs</span>
                        </div>
                        <div class="help-text">Auto-calculated from start/end time</div>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label">Half Day Hours</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="half_day_hours" name="half_day_hours"
                                step="0.1" min="0" max="24" value="4.0">
                            <span class="input-group-text">hrs</span>
                        </div>
                        <div class="help-text">Default: 4 hours</div>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="form-label">Short Leave Hours</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="short_leave_hours" name="short_leave_hours"
                                step="0.1" min="0" max="24" value="2.0">
                            <span class="input-group-text">hrs</span>
                        </div>
                        <div class="help-text">Default: 2 hours</div>
                    </div>
                </div>

                <!-- Break Time -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-cup-hot me-2"></i>Break Time</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label small">Start Time</label>
                                        <input type="time" class="form-control" id="break_start"
                                            name="break_start_time">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">End Time</label>
                                        <input type="time" class="form-control" id="break_end" name="break_end_time">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small">Duration</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="break_minutes"
                                                name="break_minutes" value="0" min="0" max="180" readonly>
                                            <span class="input-group-text">min</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex h-100 align-items-end">
                                            <small class="text-muted">Auto-calculated from break times</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grace Period -->
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="form-label">Grace Period</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="grace_minutes" value="15" min="0" max="60">
                            <span class="input-group-text">min</span>
                        </div>
                        <div class="help-text">Late arrival allowance</div>
                    </div>
                </div>

                <!-- Weekly Off Days -->
                <div class="row">
                    <div class="col-12 form-group">
                        <label class="form-label">Weekly Off Days</label>
                        <div class="card border">
                            <div class="card-body py-2">
                                <div class="row g-2">
                                    @php
                                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                                    'Saturday'];
                                    @endphp
                                    @foreach($days as $day)
                                    <div class="col-3 col-md-1">
                                        <div class="weekday-checkbox">
                                            <input type="checkbox" name="weekly_off_days[]" value="{{ $day }}"
                                                id="off_{{ strtolower($day) }}">
                                            <label for="off_{{ strtolower($day) }}">{{ substr($day, 0, 3) }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="help-text">Select days that are weekly off for this shift</div>
                    </div>
                </div>
                <!-- ==================== SIMPLE FLEXIBILITY TOGGLE ==================== -->
                <div class="flexibility-section">
                    <div class="flexibility-section-title">
                        <i class="bi bi-sliders2"></i>
                        Timing Flexibility
                    </div>

                    <div class="flexibility-card">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexibleWorkingHours"
                                name="flexible_working_hours" value="1">
                            <label class="form-check-label" for="flexibleWorkingHours">
                                <strong>Enable Time Flexibility</strong>
                            </label>
                        </div>
                        <div class="help-text" style="padding-left: 20px; margin-top: 4px;">
                            <i class="bi bi-info-circle me-1"></i>
                           When enabled, employees can check in or check out within the allowed flexible timing for this shift.
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('manage.shifts.index') }}" class="btn-filter btn-filter-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="reset" class="btn-filter btn-filter-secondary" onclick="resetTimeFields()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                            </button>
                            <button type="submit" class="btn-filter btn-filter-primary" id="submitBtn">
                                <i class="bi bi-plus-circle me-1"></i>Create Shift
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---- ELEMENTS ----
    const form = document.getElementById('shiftForm');
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const workingHours = document.getElementById('working_hours');
    const timeDisplay = document.getElementById('timeDisplay');
    const breakStart = document.getElementById('break_start');
    const breakEnd = document.getElementById('break_end');
    const breakMinutes = document.getElementById('break_minutes');
    const halfDayHours = document.getElementById('half_day_hours');
    const shortLeaveHours = document.getElementById('short_leave_hours');

    // ---- HELPER FUNCTIONS ----
    function formatTimeTo12Hour(time24) {
        if (!time24) return '';
        let [hours, minutes] = time24.split(':');
        hours = parseInt(hours);
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        return `${hours.toString().padStart(2, '0')}:${minutes} ${ampm}`;
    }

    function calculateTimeDifference(start, end) {
        if (!start || !end) return 0;
        const startDate = new Date(`1970-01-01T${start}`);
        const endDate = new Date(`1970-01-01T${end}`);
        let diff = (endDate - startDate) / (1000 * 60 * 60);
        if (diff < 0) diff += 24;
        return Math.round(diff * 10) / 10;
    }

    function updateWorkingHours() {
        const start = startTime.value;
        const end = endTime.value;
        if (start && end) {
            const hours = calculateTimeDifference(start, end);
            workingHours.value = hours;
            updateTimeDisplay();
        }
    }

    function updateTimeDisplay() {
        const start = startTime.value;
        const end = endTime.value;
        const hours = workingHours.value;

        if (start && end) {
            const start12 = formatTimeTo12Hour(start);
            const end12 = formatTimeTo12Hour(end);
            timeDisplay.innerHTML = `
                <i class="bi bi-clock me-2"></i>
                <strong>Shift Timing:</strong> ${start12} to ${end12} 
                <span class="badge bg-primary ms-2">${hours} hours</span>
            `;
        } else {
            timeDisplay.innerHTML = `
                <i class="bi bi-info-circle me-2"></i>
                <span>Shift timing will be displayed here in 12-hour format</span>
            `;
        }
    }

    function calculateBreakDuration() {
        if (breakStart.value && breakEnd.value) {
            const start = new Date('1970-01-01T' + breakStart.value);
            const end = new Date('1970-01-01T' + breakEnd.value);
            let diff = (end - start) / (1000 * 60);
            if (diff < 0) diff += 24 * 60;
            breakMinutes.value = Math.round(diff);
        } else {
            breakMinutes.value = 0;
        }
    }

    window.resetTimeFields = function() {
        workingHours.value = '';
        timeDisplay.innerHTML = `
            <i class="bi bi-info-circle me-2"></i>
            <span>Shift timing will be displayed here in 12-hour format</span>
        `;
        breakMinutes.value = 0;
        halfDayHours.value = '4.0';
        shortLeaveHours.value = '2.0';
    };

    // ---- EVENT LISTENERS ----
    startTime.addEventListener('change', updateWorkingHours);
    startTime.addEventListener('input', updateTimeDisplay);
    endTime.addEventListener('change', updateWorkingHours);
    endTime.addEventListener('input', updateTimeDisplay);
    breakStart.addEventListener('change', calculateBreakDuration);
    breakEnd.addEventListener('change', calculateBreakDuration);

    // ---- FORM SUBMISSION ----
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';
        submitBtn.disabled = true;

        const formData = new FormData(this);

        // Handle checkbox properly
        if (formData.has('flexible_working_hours')) {
            formData.set('flexible_working_hours', '1');
        } else {
            formData.append('flexible_working_hours', '0');
        }

        fetch('{{ route("manage.shifts.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.errors ? Object.values(data.errors).flat()
                            .join(', ') : 'Network error');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showAlert('Shift created successfully!', 'success');
                    setTimeout(() => {
                        window.location.href = '{{ route("manage.shifts.index") }}';
                    }, 1000);
                } else {
                    showAlert(data.message || 'Error creating shift', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(error.message || 'Network error occurred. Please try again.', 'danger');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
    });

    // ---- ALERT FUNCTION ----
    function showAlert(message, type) {
        const existingAlerts = document.querySelectorAll('.alert-dismissible');
        existingAlerts.forEach(alert => alert.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.cssText = `
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            max-width: 450px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease;
        `;

        const icons = {
            success: 'check-circle-fill',
            danger: 'exclamation-circle-fill',
            warning: 'exclamation-triangle-fill',
            info: 'info-circle-fill'
        };

        alertDiv.innerHTML = `
            <i class="bi bi-${icons[type] || 'info-circle-fill'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alertDiv);

        setTimeout(() => {
            if (alertDiv.parentNode) alertDiv.remove();
        }, 5000);
    }

    // ---- INITIALIZE ----
    updateWorkingHours();
    calculateBreakDuration();
});
</script>

<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }

    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>

@endsection