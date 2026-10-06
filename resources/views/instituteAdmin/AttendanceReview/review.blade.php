@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<title>Review Attendance - {{ $employee->name }} - {{ Carbon\Carbon::create($year, $month)->format('F Y') }}</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
}

.page-header {
    background: var(--primary-gradient);
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
}

.page-header h1 {
    color: white;
    font-weight: 600;
    margin: 0;
    font-size: 22px;
}

.page-header h1 i {
    margin-right: 10px;
}

.employee-info-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    border: 2px solid #e2e8f0;
}

.employee-avatar {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 24px;
}

.leave-balance-card {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
}

.leave-balance-card h6 {
    color: #059669;
    font-weight: 600;
    margin-bottom: 12px;
}

.stats-summary {
    background: #f8fafc;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 20px;
}

.stat-box {
    text-align: center;
    padding: 12px;
    background: white;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
}

.stat-value.present { color: #059669; }
.stat-value.absent { color: #dc2626; }
.stat-value.leave { color: #d97706; }
.stat-label { font-size: 12px; color: #64748b; margin-top: 5px; }

.daily-attendance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 15px;
    max-height: 600px;
    overflow-y: auto;
    padding: 5px;
}

.day-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
    background: white;
    transition: all 0.2s;
}

.day-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.day-date {
    font-weight: 600;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e2e8f0;
}

.status-select {
    font-size: 13px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.bg-present { background: #d1fae5; color: #059669; }
.bg-absent { background: #fee2e2; color: #dc2626; }
.bg-leave { background: #fed7aa; color: #d97706; }
.bg-weekend { background: #f3f4f6; color: #6b7280; }

.approval-section {
    background: #fef3c7;
    border-radius: 8px;
    padding: 10px;
    margin-top: 10px;
    border-left: 3px solid #f59e0b;
}

.deduction-info {
    font-size: 11px;
    color: #6b7280;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px dashed #e2e8f0;
}

.btn-finalize {
    background: var(--success-gradient);
    color: white;
    padding: 12px 30px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-finalize:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
}

.btn-back {
    background: #64748b;
    color: white;
    padding: 12px 25px;
    border-radius: 10px;
    border: none;
    font-weight: 500;
}

.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loader-overlay.active { display: flex; }

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid #4361ee;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div class="container-fluid py-4">
    <div class="page-header">
        <h1>
            <i class="fas fa-edit"></i>
            Review Attendance - {{ $employee->name }}
        </h1>
        <button type="button" class="btn btn-light" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Back
        </button>
    </div>

    <div class="employee-info-card">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="employee-avatar">
                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                </div>
            </div>
            <div class="col">
                <h3 class="mb-1">{{ $employee->name }}</h3>
                <p class="text-muted mb-0">
                    <i class="fas fa-id-card"></i> {{ $employee->employee_code }} | 
                    <i class="fas fa-building"></i> {{ $employee->department_name ?? 'No Department' }} |
                    <i class="fas fa-calendar"></i> {{ Carbon\Carbon::create($year, $month)->format('F Y') }}
                </p>
                @if($attendanceData['shift_info'])
                <small class="text-muted">
                    <i class="fas fa-clock"></i> Shift: {{ $attendanceData['shift_info']['shift_name'] }} |
                    <i class="fas fa-calendar-week"></i> Weekly Off: {{ $attendanceData['shift_info']['weekly_offs'] }}
                </small>
                @endif
            </div>
        </div>
    </div>

    @if(isset($attendanceData['leave_summary']) && count($attendanceData['leave_summary']) > 0)
    <div class="leave-balance-card">
        <h6><i class="fas fa-chart-pie"></i> Leave Balance Summary (Session: {{ date('Y') }}-{{ date('Y')+1 }})</h6>
        <div class="row">
            @foreach($attendanceData['leave_summary'] as $leaveType => $data)
            <div class="col-md-3 col-sm-6 mb-2">
                <div class="stat-box">
                    <strong>{{ $leaveType }}</strong><br>
                    <small>Used: {{ $data['taken'] }} / {{ $data['quota'] }}</small>
                    <div class="progress mt-1" style="height: 5px;">
                        <div class="progress-bar bg-success" style="width: {{ $data['quota'] > 0 ? ($data['taken'] / $data['quota']) * 100 : 0 }}%"></div>
                    </div>
                    <small class="text-muted">Remaining: {{ $data['remaining'] }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="stats-summary">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-value present">{{ $attendanceData['present_days'] }}</div>
                    <div class="stat-label">Present Days</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-value absent">{{ $attendanceData['absent_days'] }}</div>
                    <div class="stat-label">Absent Days</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-value leave">{{ $attendanceData['leave_days'] }}</div>
                    <div class="stat-label">Leave Days</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-value">{{ $attendanceData['weekend_days'] }}</div>
                    <div class="stat-label">Week Off Days</div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-primary" style="width: {{ $attendanceData['attendance_percentage'] }}%"></div>
            </div>
            <div class="text-center mt-2">
                <strong>Attendance Rate: {{ $attendanceData['attendance_percentage'] }}%</strong>
                <small class="text-muted">(Working Days: {{ $attendanceData['working_days'] }})</small>
            </div>
        </div>
    </div>

    <form id="finalizeForm">
        <input type="hidden" name="employee_id" value="{{ $employee->employee_id }}">
        <input type="hidden" name="year" value="{{ $year }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="department_id" value="{{ $attendanceData['department_id'] }}">
        <input type="hidden" id="presentDays" name="present_days" value="{{ $attendanceData['present_days'] }}">
        <input type="hidden" id="absentDays" name="absent_days" value="{{ $attendanceData['absent_days'] }}">
        <input type="hidden" id="leaveDays" name="leave_days" value="{{ $attendanceData['leave_days'] }}">
        <input type="hidden" id="weekendDays" name="weekend_days" value="{{ $attendanceData['weekend_days'] }}">
        <input type="hidden" id="workingDays" name="working_days" value="{{ $attendanceData['working_days'] }}">
        <input type="hidden" id="shortAttendanceDays" name="short_attendance_days" value="0">
        <input type="hidden" id="unapprovedLeaveDays" name="unapproved_leave_days" value="0">

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-sticky-note"></i> Finalization Notes</label>
            <textarea class="form-control" name="finalize_notes" rows="2" placeholder="Add notes about this attendance finalization..."></textarea>
        </div>

        <h5 class="mb-3"><i class="fas fa-calendar-day"></i> Daily Attendance Details</h5>
        <div class="daily-attendance-grid" id="dailyAttendanceGrid">
            @php
            $leaveSummary = $attendanceData['leave_summary'] ?? [];
            @endphp
            
            @foreach($attendanceData['attendance_details'] as $date => $details)
            @php
            $formattedDate = \Carbon\Carbon::parse($date)->format('D, M d');
            $isWeekend = $details['status'] === 'weekend';
            $currentStatus = $details['status'];
            $currentLeaveType = $details['leave_type'] ?? '';
            $isShortLeave = ($currentStatus === 'present' && $currentLeaveType === 'Short Leave');
            $isHalfDay = ($currentStatus === 'present' && $currentLeaveType === 'Half Day');
            $isFullDayLeave = ($currentStatus === 'leave' && $currentLeaveType);
            $isApproved = $details['is_approved'] ?? false;
            $deductionPercentage = $details['deduction_percentage'] ?? 0;
            @endphp
            
            <div class="day-card" data-date="{{ $date }}">
                <div class="day-date">
                    <strong>{{ $formattedDate }}</strong>
                    @if($isWeekend)
                    <span class="badge bg-secondary float-end">Weekly Off</span>
                    @endif
                </div>
                
                @if(!$isWeekend)
                <select class="form-select form-select-sm status-select mb-2" data-date="{{ $date }}">
                    <option value="present" {{ (!$isShortLeave && !$isHalfDay && $currentStatus === 'present') ? 'selected' : '' }}>Present (Full Day)</option>
                    <option value="absent" {{ $currentStatus === 'absent' ? 'selected' : '' }}>Absent (No Check-in)</option>
                    
                    @if(isset($leaveSummary['Short Leave']) && $leaveSummary['Short Leave']['remaining'] > 0)
                    <option value="present_short_leave" {{ $isShortLeave ? 'selected' : '' }}>
                        Present + Short Leave ({{ $leaveSummary['Short Leave']['remaining'] }} left)
                    </option>
                    @endif
                    
                    @if(isset($leaveSummary['Half Day']) && $leaveSummary['Half Day']['remaining'] > 0)
                    <option value="present_half_day" {{ $isHalfDay ? 'selected' : '' }}>
                        Present + Half Day ({{ $leaveSummary['Half Day']['remaining'] }} left)
                    </option>
                    @endif
                    
                    @foreach(['Casual Leave', 'Sick Leave', 'Earned Leave', 'Unpaid Leave', 'Maternity Leave'] as $leaveType)
                    @if(isset($leaveSummary[$leaveType]) && $leaveSummary[$leaveType]['remaining'] > 0)
                    <option value="leave_{{ $leaveType }}" {{ ($isFullDayLeave && $currentLeaveType === $leaveType) ? 'selected' : '' }}>
                        {{ $leaveType }} ({{ $leaveSummary[$leaveType]['remaining'] }} left)
                    </option>
                    @endif
                    @endforeach
                </select>
                
                <div class="status-badge {{ $currentStatus === 'present' ? 'bg-present' : ($currentStatus === 'absent' ? 'bg-absent' : 'bg-leave') }}">
                    @if($isShortLeave)
                    Present + Short Leave
                    @elseif($isHalfDay)
                    Present + Half Day
                    @elseif($isFullDayLeave)
                    {{ $currentLeaveType }}
                    @else
                    {{ ucfirst($currentStatus) }}
                    @endif
                </div>
                
                @if($isShortLeave || $isHalfDay)
                <div class="approval-section">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input toggle-approval" 
                               data-date="{{ $date }}" data-leave-type="{{ $currentLeaveType }}"
                               {{ $isApproved ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold">
                            {{ $currentLeaveType }} - 
                            <span class="{{ $isApproved ? 'text-success' : 'text-danger' }} approval-status">
                                {{ $isApproved ? 'Approved' : 'Unapproved' }}
                            </span>
                        </label>
                    </div>
                    
                </div>
                @elseif($isFullDayLeave)
                
                @endif
                
                @if(!empty($details['check_logs']))
                <div class="mt-2">
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> 
                        @foreach($details['check_logs'] as $log)
                        {{ $log['type'] }}: {{ $log['time'] }} 
                        @endforeach
                        @if(isset($details['total_hours']))
                        | Total: {{ number_format($details['total_hours'], 2) }}h
                        @endif
                    </small>
                </div>
                @endif
                @else
                <div class="text-center text-muted py-3">
                    <i class="fas fa-calendar-week"></i> Weekly Off
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <div class="mt-4 d-flex justify-content-between">
            <button type="button" class="btn-back" onclick="window.history.back()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="btn-finalize" onclick="submitFinalize()">
                <i class="fas fa-lock"></i> Finalize Attendance
            </button>
        </div>
    </form>
</div>

<div id="loaderOverlay" class="loader-overlay">
    <div class="text-center bg-white p-4 rounded">
        <div class="spinner mx-auto"></div>
        <p class="mt-3 mb-0">Processing finalization...</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentAttendanceData = @json($attendanceData);
let currentEmployeeId = '{{ $employee->employee_id }}';
let currentYear = {{ $year }};
let currentMonth = {{ $month }};

function updateAttendanceStats() {
    let present = 0, absent = 0, leave = 0;
    const selects = document.querySelectorAll('.status-select');
    
    selects.forEach(select => {
        const value = select.value;
        if (value === 'present') present++;
        else if (value === 'absent') absent++;
        else if (value === 'present_short_leave' || value === 'present_half_day') present++;
        else if (value && value.startsWith('leave_')) leave++;
    });
    
    document.getElementById('presentDays').value = present;
    document.getElementById('absentDays').value = absent;
    document.getElementById('leaveDays').value = leave;
}

async function submitFinalize() {
    const selects = document.querySelectorAll('.status-select');
    const attendanceDetails = {};
    const leaveSummary = currentAttendanceData.leave_summary || {};
    
    selects.forEach(select => {
        const date = select.getAttribute('data-date');
        const selectedValue = select.value;
        const originalDetails = currentAttendanceData.attendance_details[date] || {};
        
        let status = '', leaveType = null, isApproved = false, deductionPercentage = 0, withinQuota = false, statusText = '';
        
        if (selectedValue === 'present') {
            status = 'present';
            statusText = 'Present';
            isApproved = true;
            deductionPercentage = 0;
            withinQuota = true;
        } 
        else if (selectedValue === 'absent') {
            status = 'absent';
            statusText = 'Absent';
            isApproved = false;
            deductionPercentage = 100;
            withinQuota = false;
        }
        else if (selectedValue === 'present_short_leave') {
            status = 'present';
            leaveType = 'Short Leave';
            const leaveData = leaveSummary['Short Leave'];
            if (leaveData && leaveData.remaining > 0) {
                statusText = 'Present + Short Leave (Approved)';
                isApproved = true;
                deductionPercentage = leaveData.approved_deduction_percentage || 0;
                withinQuota = true;
            } else {
                statusText = 'Present + Short Leave (Unapproved)';
                isApproved = false;
                deductionPercentage = leaveData?.unapproved_deduction_percentage || 100;
                withinQuota = false;
            }
        }
        else if (selectedValue === 'present_half_day') {
            status = 'present';
            leaveType = 'Half Day';
            const leaveData = leaveSummary['Half Day'];
            if (leaveData && leaveData.remaining > 0) {
                statusText = 'Present + Half Day (Approved)';
                isApproved = true;
                deductionPercentage = leaveData.approved_deduction_percentage || 0;
                withinQuota = true;
            } else {
                statusText = 'Present + Half Day (Unapproved)';
                isApproved = false;
                deductionPercentage = leaveData?.unapproved_deduction_percentage || 100;
                withinQuota = false;
            }
        }
        else if (selectedValue && selectedValue.startsWith('leave_')) {
            status = 'leave';
            leaveType = selectedValue.replace('leave_', '');
            const leaveData = leaveSummary[leaveType];
            if (leaveData && leaveData.remaining > 0) {
                statusText = `${leaveType} (Approved)`;
                isApproved = true;
                deductionPercentage = leaveData.approved_deduction_percentage || 0;
                withinQuota = true;
            } else {
                statusText = `${leaveType} (Unapproved)`;
                isApproved = false;
                deductionPercentage = leaveData?.unapproved_deduction_percentage || 100;
                withinQuota = false;
            }
        }
        
        // Get approval status from toggle if exists
        const toggleCheckbox = document.querySelector(`.toggle-approval[data-date="${date}"]`);
        if (toggleCheckbox) {
            isApproved = toggleCheckbox.checked;
            const leaveData = leaveSummary[leaveType || 'Short Leave'];
            deductionPercentage = isApproved 
                ? (leaveData?.approved_deduction_percentage || 0)
                : (leaveData?.unapproved_deduction_percentage || 100);
            statusText = `${leaveType} (${isApproved ? 'Approved' : 'Unapproved'})`;
            withinQuota = isApproved;
        }
        
        attendanceDetails[date] = {
            status: status,
            status_text: statusText,
            is_working_day: true,
            day_name: originalDetails.day_name || new Date(date).toLocaleDateString('en-US', { weekday: 'long' }),
            check_in: originalDetails.check_in || null,
            check_out: originalDetails.check_out || null,
            check_logs: originalDetails.check_logs || [],
            total_hours: originalDetails.total_hours || 0,
            required_hours: originalDetails.required_hours || 0,
            short_hours: originalDetails.short_hours || 0,
            leave_type: leaveType,
            is_approved: isApproved,
            is_auto_detected: originalDetails.is_auto_detected || false,
            within_quota: withinQuota,
            deduction_percentage: deductionPercentage
        };
    });
    
    // Preserve weekend days
    Object.entries(currentAttendanceData.attendance_details).forEach(([date, details]) => {
        if (details.status === 'weekend' && !attendanceDetails[date]) {
            attendanceDetails[date] = details;
        }
    });
    
    const presentDays = parseInt(document.getElementById('presentDays').value) || 0;
    const absentDays = parseInt(document.getElementById('absentDays').value) || 0;
    const leaveDays = parseInt(document.getElementById('leaveDays').value) || 0;
    const workingDays = parseInt(document.getElementById('workingDays').value) || 1;
    const weekendDays = parseInt(document.getElementById('weekendDays').value) || 0;
    const finalizeNotes = document.querySelector('textarea[name="finalize_notes"]')?.value || '';
    
    let shortAttendanceDays = 0;
    let unapprovedLeaveDays = 0;
    
    Object.values(attendanceDetails).forEach(detail => {
        if (detail.status === 'short_attendance') shortAttendanceDays++;
        if (detail.status === 'present' && !detail.is_approved && detail.leave_type) unapprovedLeaveDays++;
        if (detail.status === 'leave' && !detail.is_approved) unapprovedLeaveDays++;
    });
    
    document.getElementById('shortAttendanceDays').value = shortAttendanceDays;
    document.getElementById('unapprovedLeaveDays').value = unapprovedLeaveDays;
    
    Swal.fire({
        title: 'Confirm Finalization',
        html: `
            <div class="text-start">
                <p><strong>Are you sure you want to finalize this attendance?</strong></p>
                <div class="alert alert-info">
                    <strong>Summary for {{ $employee->name }}:</strong><br>
                    Present: ${presentDays} days<br>
                    Absent: ${absentDays} days<br>
                    Leave: ${leaveDays} days<br>
                    Working Days: ${workingDays} days<br>
                    <strong>Attendance Rate: ${((presentDays / workingDays) * 100).toFixed(2)}%</strong><br>
                    Unapproved Leave Days: ${unapprovedLeaveDays}
                </div>
                <p class="text-muted small">This action will lock the attendance and generate a detailed salary slip.</p>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Finalize',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('loaderOverlay').classList.add('active');
            
            const data = {
                employee_id: currentEmployeeId,
                year: currentYear,
                month: currentMonth,
                department_id: '{{ $attendanceData['department_id'] }}',
                present_days: presentDays,
                absent_days: absentDays,
                leave_days: leaveDays,
                weekend_days: weekendDays,
                working_days: workingDays,
                short_attendance_days: shortAttendanceDays,
                unapproved_leave_days: unapprovedLeaveDays,
                attendance_percentage: workingDays > 0 ? (presentDays / workingDays) * 100 : 0,
                finalize_notes: finalizeNotes,
                attendance_details: attendanceDetails
            };
            
            fetch('/attendance/review/finalize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loaderOverlay').classList.remove('active');
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Finalized!',
                        text: 'Attendance finalized and salary slip created successfully.',
                        confirmButtonColor: '#10b981'
                    }).then(() => {
                        window.location.href = '{{ route('attendance.review.index') }}';
                    });
                } else {
                    if (data.error_type === 'missing_salary_structure') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Salary Structure Missing!',
                            html: `
                                <p>${data.message}</p>
                                <button class="btn btn-primary mt-3" onclick="window.location.href = '{{ route('institute.payroll.structure') }}'">
                                    Create Salary Structure
                                </button>
                            `,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Error finalizing attendance',
                            confirmButtonColor: '#4361ee'
                        });
                    }
                }
            })
            .catch(error => {
                document.getElementById('loaderOverlay').classList.remove('active');
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Network error occurred. Please try again.',
                    confirmButtonColor: '#4361ee'
                });
            });
        }
    });
}

// Event listeners
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', updateAttendanceStats);
});

document.querySelectorAll('.toggle-approval').forEach(toggle => {
    toggle.addEventListener('change', function() {
        const date = this.dataset.date;
        const leaveType = this.dataset.leaveType;
        const isApproved = this.checked;
        const dayCard = document.querySelector(`.day-card[data-date="${date}"]`);
        const statusSpan = dayCard.querySelector('.approval-status');
        const deductionDiv = dayCard.querySelector('.deduction-info');
        const leaveSummary = currentAttendanceData.leave_summary || {};
        const leaveData = leaveSummary[leaveType];
        
        const deductionPercentage = isApproved 
            ? (leaveData?.approved_deduction_percentage || 0)
            : (leaveData?.unapproved_deduction_percentage || 100);
        
        if (statusSpan) {
            statusSpan.textContent = isApproved ? 'Approved' : 'Unapproved';
            statusSpan.className = isApproved ? 'text-success approval-status' : 'text-danger approval-status';
        }
        
        if (deductionDiv) {
            deductionDiv.innerHTML = `
                <i class="fas fa-percent"></i> Deduction: ${deductionPercentage}% of daily salary
                ${deductionPercentage > 0 ? '<span class="text-danger">(Will be deducted from salary)</span>' : '<span class="text-success">(No deduction - within quota)</span>'}
            `;
        }
        
        // Update select option text
        const select = dayCard.querySelector('.status-select');
        if (select) {
            const currentValue = select.value;
            if (currentValue === 'present_short_leave' || currentValue === 'present_half_day') {
                const option = select.querySelector(`option[value="${currentValue}"]`);
                if (option) {
                    option.text = `Present + ${leaveType} (${isApproved ? 'Approved' : 'Unapproved'} - ${deductionPercentage}% deduction)`;
                }
            }
        }
    });
});

updateAttendanceStats();
</script>
@endsection