@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Request Work From Home')

@section('content')
<style>
    .wfh-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .wfh-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .wfh-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 25px 30px;
        color: white;
    }
    
    .wfh-card-header h4 {
        margin: 0;
        font-weight: 600;
    }
    
    .wfh-card-header p {
        margin: 5px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }
    
    .wfh-card-body {
        padding: 30px;
        background: #f8f9fa;
    }
    
    .form-section {
        background: white;
        padding: 20px 25px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .form-section-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
    }
    
    .form-section-title i {
        margin-right: 10px;
        color: #667eea;
    }
    
    .form-label {
        font-weight: 500;
        color: #555;
        font-size: 14px;
    }
    
    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }
    
    .invalid-feedback {
        font-size: 13px;
        margin-top: 5px;
    }
    
    /* Multi-shift styles */
    .shifts-list {
        background: white;
        border-radius: 12px;
        padding: 5px 0;
        margin: 0;
        list-style: none;
    }
    
    .shift-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }
    
    .shift-item:last-child {
        border-bottom: none;
    }
    
    .shift-item:hover {
        background: #f8f9fa;
    }
    
    .shift-item .shift-name {
        font-weight: 500;
        color: #333;
    }
    
    .shift-item .shift-dates {
        font-size: 13px;
        color: #6c757d;
    }
    
    .shift-item .shift-time {
        font-size: 13px;
        color: #495057;
        background: #e9ecef;
        padding: 2px 10px;
        border-radius: 12px;
    }
    
    .shift-item .shift-badge {
        font-size: 11px;
        padding: 2px 10px;
        border-radius: 12px;
        font-weight: 500;
    }
    
    .shift-badge.active {
        background: #d4edda;
        color: #155724;
    }
    
    .shift-badge.weekly-off {
        background: #fff3cd;
        color: #856404;
    }
    
    .shift-badge.no-shift {
        background: #e2e3e5;
        color: #383d41;
    }
    
    .shift-info-box {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        padding: 15px 20px;
        border-radius: 10px;
        color: white;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .shift-info-box .shift-label {
        font-size: 12px;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .shift-info-box .shift-value {
        font-size: 18px;
        font-weight: 600;
        margin-top: 3px;
    }
    
    .shift-info-box .shift-timing {
        font-size: 14px;
        opacity: 0.95;
        margin-top: 5px;
    }
    
    .shift-info-box .shift-loader {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .employee-info-card {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        background: white;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid #667eea;
    }
    
    .employee-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        font-weight: 600;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .employee-info-text h6 {
        margin: 0;
        font-weight: 600;
        color: #333;
    }
    
    .employee-info-text p {
        margin: 2px 0 0;
        font-size: 13px;
        color: #888;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 35px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-cancel {
        background: #e9ecef;
        border: none;
        padding: 12px 35px;
        font-weight: 500;
        border-radius: 8px;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
        background: #dee2e6;
        color: #495057;
    }
    
    .date-range-picker {
        display: flex;
        gap: 15px;
    }
    
    .date-range-picker .form-group {
        flex: 1;
    }
    
    .badge-required {
        color: #dc3545;
        font-size: 16px;
        margin-left: 5px;
    }
    
    .form-hint {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }
    
    .emergency-contact-display {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .emergency-contact-display .label {
        font-weight: 500;
        color: #555;
    }
    
    .emergency-contact-display .value {
        color: #333;
        font-weight: 500;
    }
    
    .emergency-contact-display .badge-info {
        background: #e3f2fd;
        color: #1976d2;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 12px;
    }
    
    .no-shift-warning {
        background: #fff3cd;
        color: #856404;
        padding: 10px 15px;
        border-radius: 8px;
        border-left: 4px solid #ffc107;
    }
    
    .shift-loading {
        opacity: 0.6;
        pointer-events: none;
    }
    
    .weekly-off-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        margin-top: 5px;
    }
    
    .multiple-shifts-warning {
        background: #e3f2fd;
        border-left: 4px solid #1976d2;
        padding: 12px 16px;
        border-radius: 8px;
        color: #0d47a1;
        font-size: 14px;
        margin-top: 10px;
    }
    
    @media (max-width: 768px) {
        .date-range-picker {
            flex-direction: column;
            gap: 0;
        }
        
        .wfh-card-body {
            padding: 15px;
        }
        
        .form-section {
            padding: 15px;
        }
        
        .btn-submit, .btn-cancel {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .shift-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }
</style>

<div class="wfh-container">
    <div class="card wfh-card">
        <div class="wfh-card-header">
            <h4><i class="fas fa-home mr-2"></i> Request Work From Home</h4>
            <p>Submit your work from home request for approval</p>
        </div>
        
        <div class="wfh-card-body">
            <form action="{{ route('employee.wfh-requests.store') }}" method="POST" id="wfhForm">
                @csrf
                
                <!-- Employee Info -->
                <div class="employee-info-card">
                    <div class="employee-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'E', 0, 1)) }}
                    </div>
                    <div class="employee-info-text">
                        <h6>{{ Auth::user()->name ?? 'Employee' }}</h6>
                        <p>
                            <i class="fas fa-id-badge mr-1"></i> 
                            {{ Auth::user()->employeeDetails->employee_code ?? 'N/A' }}
                            @if(isset(Auth::user()->employeeDetails->department))
                                <span class="mx-2">|</span>
                                <i class="fas fa-building mr-1"></i>
                                {{ Auth::user()->employeeDetails->department->department_name ?? 'N/A' }}
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Date Selection Section -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-calendar-alt"></i>
                        Select Date Range
                        <span class="badge-required">*</span>
                    </div>
                    
                    <div class="date-range-picker">
                        <div class="form-group">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="startDate" 
                                   class="form-control @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('start_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div class="form-hint">
                                <i class="fas fa-info-circle"></i> Select the first day you want to work from home
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="endDate" 
                                   class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('end_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <div class="form-hint">
                                <i class="fas fa-info-circle"></i> Select the last day you want to work from home
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-2">
                        <small class="text-muted" id="durationHint">
                            <i class="fas fa-info-circle"></i>
                            Duration will be calculated automatically based on selected dates
                        </small>
                    </div>
                </div>

                <!-- Shift Information - Dynamic Multi-Shift Display -->
                <div id="shiftInfoContainer">
                    <div class="shift-info-box" id="shiftInfoBox">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="shift-label">
                                    <i class="fas fa-clock mr-1"></i> Shifts for Selected Date Range
                                </div>
                                <div id="shiftsListContainer">
                                    <div class="shift-value" id="shiftLoading">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Loading shifts...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Reason Section -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-pen"></i>
                        Request Details
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Reason for WFH Request</label>
                        <textarea name="reason" 
                                  class="form-control @error('reason') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="Please provide a brief reason for your work from home request (Optional)">{{ old('reason') }}</textarea>
                        @error('reason')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i> Optional - Provide context for your request
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Work Plan</label>
                        <textarea name="work_plan" 
                                  class="form-control" 
                                  rows="3" 
                                  placeholder="What work will you complete during this period? (Optional)">{{ old('work_plan') }}</textarea>
                        <div class="form-hint">
                            <i class="fas fa-info-circle"></i> Optional - Help your manager understand your work plan
                        </div>
                    </div>
                </div>
                
                <!-- Emergency Contact Section -->
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-phone-alt"></i>
                        Emergency Contact
                    </div>
                    
                    <div class="emergency-contact-display">
                        @if($employeeDetails && $employeeDetails->emergency_contact_number)
                            <span class="label d-none">
                                <i class="fas fa-phone"></i> Number:
                            </span>
                            <span class="value">
                                {{ $employeeDetails->emergency_contact_number }}
                            </span>
                            <span class="badge-info">
                                <i class="fas fa-check-circle"></i> Available
                            </span>
                        @else
                            <span class="badge-info">
                                <i class="fas fa-exclamation-triangle"></i> Emergency contact not provided
                            </span>
                        @endif
                    </div>
                    
                    <div class="form-hint mt-2">
                        <i class="fas fa-info-circle"></i>
                        Your emergency contact details are retrieved from your profile. 
                        <a href="{{ route('employee.profile') }}" class="text-primary">Update here</a>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
                    <div>
                        <button type="submit" class="btn btn-submit text-white" id="submitBtn">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Request
                        </button>
                        <a href="{{ route('employee.dashboard') }}" class="btn btn-cancel">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                    <div class="mt-2 mt-sm-0">
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> Request will be sent for approval
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const shiftsListContainer = document.getElementById('shiftsListContainer');
    const durationHint = document.getElementById('durationHint');
    const shiftInfoBox = document.getElementById('shiftInfoBox');
    
    let isFetching = false;
    let debounceTimer = null;
    
    /**
     * Format time to 12-hour format
     */
    function formatTime(time) {
        if (!time) return 'N/A';
        try {
            const date = new Date('2000-01-01 ' + time);
            return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        } catch (e) {
            return time;
        }
    }
    
    /**
     * Get status badge for shift
     */
    function getShiftBadge(shift) {
        if (shift.is_weekly_off) {
            return '<span class="shift-badge weekly-off"><i class="fas fa-calendar-times mr-1"></i> Weekly Off</span>';
        } else if (shift.shift_name === 'No Shift Assigned' || shift.shift_name === 'No Shift') {
            return '<span class="shift-badge no-shift"><i class="fas fa-exclamation-circle mr-1"></i> No Shift</span>';
        } else {
            return '<span class="shift-badge active"><i class="fas fa-check-circle mr-1"></i> Active</span>';
        }
    }
    
    /**
     * Render shifts list
     */
    function renderShifts(shifts, duration) {
        if (!shifts || shifts.length === 0) {
            return `
                <div class="no-shift-warning mt-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>No shift assigned for the selected date range.</strong>
                    <span class="d-block mt-1">Please contact your administrator to assign a shift.</span>
                </div>
            `;
        }
        
        let html = `
            <div class="mt-2">
                <div class="multiple-shifts-warning" style="${shifts.length > 1 ? '' : 'display: none;'}">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Multiple shifts detected:</strong> Your selected date range includes ${shifts.length} different shift(s).
                </div>
                <ul class="shifts-list">
        `;
        
        shifts.forEach((shift) => {
            const startDate = shift.start_date ? new Date(shift.start_date).toLocaleDateString('en-US', { 
                month: 'short', day: 'numeric', year: 'numeric' 
            }) : 'N/A';
            
            const endDate = shift.end_date ? new Date(shift.end_date).toLocaleDateString('en-US', { 
                month: 'short', day: 'numeric', year: 'numeric' 
            }) : 'N/A';
            
            const daysText = shift.days > 1 ? `${shift.days} days` : `${shift.days} day`;
            const shiftName = shift.shift_name || 'No Shift Assigned';
            const startTime = formatTime(shift.start_time);
            const endTime = formatTime(shift.end_time);
            
            html += `
                <li class="shift-item">
                    <div>
                        <span class="shift-name">${shiftName}</span>
                        ${getShiftBadge(shift)}
                    </div>
                    <div>
                        <span class="shift-dates">
                            <i class="far fa-calendar-alt mr-1"></i>
                            ${startDate} - ${endDate}
                            <span class="text-muted ml-1">(${daysText})</span>
                        </span>
                        ${shift.start_time && shift.end_time ? `
                            <span class="shift-time ml-2">
                                <i class="far fa-clock mr-1"></i>
                                ${startTime} - ${endTime}
                            </span>
                        ` : ''}
                    </div>
                </li>
            `;
        });
        
        html += `
                </ul>
                <div class="mt-2 text-muted small">
                    <i class="fas fa-info-circle mr-1"></i>
                    Total duration: ${duration || 'N/A'}
                </div>
            </div>
        `;
        
        return html;
    }
    
    /**
     * Fetch shifts for the selected date range
     */
    function fetchShiftsForDateRange(startDate, endDate) {
        if (!startDate || !endDate) return;
        
        // Clear any pending debounce
        if (debounceTimer) {
            clearTimeout(debounceTimer);
        }
        
        // Debounce the request
        debounceTimer = setTimeout(function() {
            // Show loading state
            isFetching = true;
            shiftsListContainer.innerHTML = `
                <div class="shift-value" id="shiftLoading">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Loading shifts...
                </div>
            `;
            
            // Make AJAX request
            $.ajax({
                url: '{{ route("employee.wfh-requests.get-shifts") }}',
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    isFetching = false;
                    if (response.success) {
                        const data = response.data;
                        const shifts = data.shifts || [];
                        
                        // Update duration hint
                        if (data.duration) {
                            durationHint.innerHTML = `<i class="fas fa-info-circle"></i> Duration: ${data.duration} (includes both start and end dates)`;
                        }
                        
                        // Render shifts
                        if (shifts.length > 0) {
                            shiftsListContainer.innerHTML = renderShifts(shifts, data.duration);
                            
                            // Change box color if multiple shifts
                            if (shifts.length > 1) {
                                shiftInfoBox.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                            } else {
                                shiftInfoBox.style.background = 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)';
                            }
                        } else {
                            shiftsListContainer.innerHTML = renderShifts([], data.duration);
                        }
                    } else {
                        shiftsListContainer.innerHTML = `
                            <div class="no-shift-warning mt-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>Error loading shifts:</strong> ${response.message || 'Please try again'}
                            </div>
                        `;
                    }
                },
                error: function(xhr) {
                    isFetching = false;
                    shiftsListContainer.innerHTML = `
                        <div class="no-shift-warning mt-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Error loading shifts:</strong> Please refresh the page and try again
                        </div>
                    `;
                }
            });
        }, 500); // 500ms debounce
    }
    
    /**
     * Update duration info in real-time
     */
    function updateDurationInfo() {
        const start = startDateInput.value;
        const end = endDateInput.value;
        
        if (start && end) {
            const startDate = new Date(start);
            const endDate = new Date(end);
            
            if (endDate >= startDate) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                durationHint.innerHTML = `<i class="fas fa-info-circle"></i> Duration: ${diffDays} day${diffDays > 1 ? 's' : ''} (includes both start and end dates)`;
                
                // Fetch shifts for the selected date range
                fetchShiftsForDateRange(start, end);
            } else {
                durationHint.innerHTML = `<i class="fas fa-exclamation-triangle text-warning"></i> End date must be after or equal to start date`;
            }
        }
    }
    
    // Event listeners - using 'input' event for real-time updates
    startDateInput.addEventListener('input', function() {
        const startVal = this.value;
        const endVal = endDateInput.value;
        
        // Update min attribute of end date
        if (startVal) {
            endDateInput.min = startVal;
            
            // If end date is before start date, update end date to start date
            if (endVal && endVal < startVal) {
                endDateInput.value = startVal;
            }
        }
        
        // Update duration info
        updateDurationInfo();
    });
    
    endDateInput.addEventListener('input', function() {
        const startVal = startDateInput.value;
        const endVal = this.value;
        
        // Validate end date is after start date
        if (startVal && endVal && endVal < startVal) {
            this.setCustomValidity('End date must be after or equal to start date');
            this.classList.add('is-invalid');
            durationHint.innerHTML = `<i class="fas fa-exclamation-triangle text-warning"></i> End date must be after or equal to start date`;
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
            updateDurationInfo();
        }
    });
    
    // Also listen to 'change' event for final validation
    startDateInput.addEventListener('change', function() {
        updateDurationInfo();
    });
    
    endDateInput.addEventListener('change', function() {
        const startVal = startDateInput.value;
        const endVal = this.value;
        
        if (startVal && endVal && endVal < startVal) {
            this.setCustomValidity('End date must be after or equal to start date');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });
    
    // Initial update
    setTimeout(function() {
        if (startDateInput.value && endDateInput.value) {
            updateDurationInfo();
        }
    }, 500);
    
    // Prevent form submission if shift is loading
    document.getElementById('wfhForm').addEventListener('submit', function(e) {
        if (isFetching) {
            e.preventDefault();
            if (typeof toastr !== 'undefined') {
                toastr.warning('Please wait, shift information is loading...');
            } else {
                alert('Please wait, shift information is loading...');
            }
        }
        
        // Validate dates
        const startVal = startDateInput.value;
        const endVal = endDateInput.value;
        
        if (startVal && endVal && endVal < startVal) {
            e.preventDefault();
            if (typeof toastr !== 'undefined') {
                toastr.error('End date must be after or equal to start date');
            } else {
                alert('End date must be after or equal to start date');
            }
        }
    });
});
</script>

@endsection
