@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<style>
/* Clean & Minimal Styles */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

/* Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #e9ecef;
}

.page-header h1 {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
    color: #2c3e50;
}

.page-header h1 i {
    margin-right: 10px;
    color: #667eea;
}

/* Filter Bar */
.filter-bar {
    background: #fff;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    border: 1px solid #e9ecef;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.filter-controls {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-controls select {
    padding: 8px 16px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    font-size: 14px;
    background: #fff;
    cursor: pointer;
}

.filter-controls select:focus {
    outline: none;
    border-color: #667eea;
}

.filter-controls button {
    padding: 8px 20px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-controls button:hover {
    background: #5a67d8;
}

.btn-reset {
    background: #6c757d !important;
}

.btn-reset:hover {
    background: #5a6268 !important;
}

.finalized-badge {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.finalized-badge i {
    margin-right: 6px;
}

.finalized-badge.pending {
    background: #fff3e0;
    color: #e65100;
}

/* Stats Cards - Clean & Minimal */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.stat-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-color: #667eea;
}

.stat-card.active {
    border-color: #667eea;
    background: #f8f9ff;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.2;
}

.stat-label {
    font-size: 13px;
    color: #6c757d;
    margin-top: 6px;
}

/* Calendar */
.calendar-container {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.calendar-header div {
    padding: 12px;
    text-align: center;
    font-weight: 600;
    font-size: 13px;
    color: #495057;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.calendar-day {
    min-height: 100px;
    padding: 10px;
    border-right: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
    background: #fff;
}

.calendar-day:hover {
    background: #f8f9fa;
}

.calendar-day.today {
    background: #f0fdf4;
}

.day-number {
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    display: inline-block;
    margin-bottom: 8px;
}

/* Status Indicators - Minimal */
.status-present { background: #e8f5e9; }
.status-absent { background: #ffebee; }
.status-leave { background: #fff3e0; }
.status-weekend { background: #f5f5f5; }

.attendance-info {
    font-size: 11px;
    margin-top: 6px;
}

.check-time {
    color: #2e7d32;
    font-weight: 500;
}

.check-time.out {
    color: #1565c0;
}

.total-hours {
    color: #6c757d;
    font-size: 10px;
    margin-top: 2px;
}

.leave-badge {
    display: inline-block;
    background: #ffecb3;
    color: #e65100;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    margin-top: 4px;
}

.late-badge {
    color: #e65100;
    font-size: 10px;
    margin-top: 2px;
}

.quota-warning {
    color: #e65100;
    font-size: 9px;
    margin-top: 2px;
}

/* Modal */
.modal-content {
    border-radius: 12px;
    border: none;
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    background: #fff;
    padding: 16px 20px;
}

.modal-header .modal-title {
    font-weight: 600;
    color: #2c3e50;
}

.modal-body {
    padding: 20px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    color: #6c757d;
    font-size: 13px;
}

.detail-value {
    font-weight: 500;
    color: #2c3e50;
    font-size: 13px;
}

.status-badge-modal {
    display: inline-block;
    padding: 4px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.status-present-badge { background: #e8f5e9; color: #2e7d32; }
.status-absent-badge { background: #ffebee; color: #c62828; }
.status-leave-badge { background: #fff3e0; color: #e65100; }
.status-weekend-badge { background: #f5f5f5; color: #616161; }

/* Print */
@media print {
    .filter-bar, .stats-grid, .btn, .modal, .no-print {
        display: none !important;
    }
    .calendar-container {
        border: none;
    }
    .calendar-day {
        break-inside: avoid;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .calendar-day {
        min-height: 70px;
        padding: 6px;
    }
    .day-number {
        font-size: 12px;
    }
    .attendance-info {
        font-size: 9px;
    }
    .stat-number {
        font-size: 22px;
    }
}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-calendar-alt"></i>
            Attendance
        </h1>
        <div>
            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Filter Bar - Auto Submit with Reset Button -->
    <div class="filter-bar">
        <div class="filter-controls">
            <select name="month" id="monthSelect">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                    </option>
                @endfor
            </select>

            <select name="year" id="yearSelect">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <button type="button" class="btn-reset" onclick="resetFilters()">
                <i class="fas fa-undo-alt"></i> Reset
            </button>
        </div>

        @if(isset($isFinalized) && $isFinalized)
        <div class="finalized-badge">
            <i class="fas fa-check-circle"></i> Finalized on {{ $finalizedDate ?? 'N/A' }}
        </div>
        @else
        <div class="finalized-badge pending">
            <i class="fas fa-clock"></i> Pending Finalization
        </div>
        @endif
    </div>

    <!-- Stats Cards - Click to Filter -->
    <div class="stats-grid" id="statsGrid">
        <div class="stat-card" data-status="present">
            <div class="stat-number">{{ $totalPresent ?? 0 }}</div>
            <div class="stat-label">Present</div>
        </div>
        <div class="stat-card" data-status="absent">
            <div class="stat-number">{{ $totalAbsent ?? 0 }}</div>
            <div class="stat-label">Absent</div>
        </div>
        <div class="stat-card" data-status="leave">
            <div class="stat-number">{{ $totalLeave ?? 0 }}</div>
            <div class="stat-label">Leave</div>
        </div>
        <div class="stat-card" data-status="weekend">
            <div class="stat-number">{{ $totalWeekend ?? 0 }}</div>
            <div class="stat-label">Week Off</div>
        </div>
        @if(isset($totalShortAttendance) && $totalShortAttendance > 0)
        <div class="stat-card" data-status="short_attendance">
            <div class="stat-number">{{ $totalShortAttendance }}</div>
            <div class="stat-label">Short Attendance</div>
        </div>
        @endif
        <div class="stat-card" data-status="all">
            <div class="stat-number">{{ ($totalPresent ?? 0) + ($totalAbsent ?? 0) + ($totalLeave ?? 0) + ($totalWeekend ?? 0) }}</div>
            <div class="stat-label">Total Days</div>
        </div>
    </div>

    <!-- Calendar -->
    @php
    use Carbon\Carbon;
    $startOfMonth = Carbon::createFromDate($year, $month, 1);
    $firstDayOfWeek = $startOfMonth->dayOfWeek;
    $daysInMonth = $startOfMonth->daysInMonth;
    $today = Carbon::today();
    
    // Helper function to convert decimal hours to hours and minutes
    function formatHours($hours) {
        if (!$hours) return null;
        $hrs = floor($hours);
        $mins = round(($hours - $hrs) * 60);
        if ($mins == 60) {
            $hrs++;
            $mins = 0;
        }
        if ($hrs > 0 && $mins > 0) {
            return $hrs . 'h ' . $mins . 'm';
        } elseif ($hrs > 0) {
            return $hrs . 'h';
        } else {
            return $mins . 'm';
        }
    }
    @endphp

    <div class="calendar-container">
        <div class="calendar-header">
            <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
        </div>
        <div class="calendar-grid" id="calendarGrid">
            @for($i = 0; $i < $firstDayOfWeek; $i++)
                <div class="calendar-day" style="background: #fafbfc;"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateObj = Carbon::createFromDate($year, $month, $day);
                    $dateString = $dateObj->toDateString();
                    $attendance = $detailedAttendance[$dateString] ?? null;
                    $isToday = $dateString == $today->toDateString();
                    $status = $attendance['status'] ?? 'absent';
                    
                    $statusClass = match($status) {
                        'present' => 'status-present',
                        'absent' => 'status-absent',
                        'leave' => 'status-leave',
                        'weekend' => 'status-weekend',
                        default => ''
                    };
                    
                    $formattedHours = isset($attendance['total_hours']) ? formatHours($attendance['total_hours']) : null;
                @endphp

                <div class="calendar-day {{ $statusClass }} {{ $isToday ? 'today' : '' }}" 
                     data-date="{{ $dateString }}"
                     data-status="{{ $status }}">
                    
                    <div class="day-number">{{ $day }}</div>

                    @if($status == 'present' && isset($attendance['check_in']))
                    <div class="attendance-info">
                        @if(isset($attendance['check_in']))
                        <div class="check-time">
                            <i class="fas fa-sign-in-alt" style="font-size: 8px;"></i> {{ $attendance['check_in'] }}
                        </div>
                        @endif
                        @if(isset($attendance['check_out']))
                        <div class="check-time out">
                            <i class="fas fa-sign-out-alt" style="font-size: 8px;"></i> {{ $attendance['check_out'] }}
                        </div>
                        @endif
                        @if($formattedHours)
                        <div class="total-hours">
                            <i class="far fa-clock"></i> {{ $formattedHours }}
                        </div>
                        @endif
                        @if(isset($attendance['leave_type']))
                        <div class="leave-badge">{{ $attendance['leave_type'] }}</div>
                        @endif
                        @if(isset($attendance['late_minutes']) && $attendance['late_minutes'] > 0)
                        <div class="late-badge">Late {{ $attendance['late_minutes'] }} min</div>
                        @endif
                        @if(isset($attendance['within_quota']) && !$attendance['within_quota'])
                        <!-- <div class="quota-warning ">⚠️ Exceeds quota</div> -->
                        @endif
                    </div>
                    @elseif($status == 'leave')
                    <div class="attendance-info">
                        <div class="leave-badge">
                            <i class="fas fa-calendar-week"></i> {{ $attendance['leave_type'] ?? 'Leave' }}
                        </div>
                    </div>
                    @elseif($status == 'absent')
                    <div class="attendance-info">
                        <div class="total-hours" style="color: #c62828;">
                            <i class="fas fa-times-circle"></i> Absent
                        </div>
                    </div>
                    @elseif($status == 'weekend')
                    <div class="attendance-info">
                        <div class="total-hours">
                            <i class="fas fa-calendar-alt"></i> Week Off
                        </div>
                    </div>
                    @endif
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

// AUTO FILTER - Automatically submit when month/year changes
document.getElementById('monthSelect')?.addEventListener('change', function() {
    filterAttendance();
});

document.getElementById('yearSelect')?.addEventListener('change', function() {
    filterAttendance();
});

function filterAttendance() {
    const month = document.getElementById('monthSelect').value;
    const year = document.getElementById('yearSelect').value;
    window.location.href = `{{ route('employee.myAttendance') }}?month=${month}&year=${year}`;
}

function resetFilters() {
    // Reset to current month and year
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth() + 1;
    const currentYear = currentDate.getFullYear();
    
    document.getElementById('monthSelect').value = currentMonth;
    document.getElementById('yearSelect').value = currentYear;
    
    filterAttendance();
}

// AUTO FILTER - Click on stat cards to highlight matching days
let activeFilter = 'all';

document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('click', function() {
        const status = this.dataset.status;
        
        // Update active state
        document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        
        activeFilter = status;
        
        // Filter calendar days
        const days = document.querySelectorAll('.calendar-day');
        let found = false;
        
        days.forEach(day => {
            const dayStatus = day.dataset.status;
            
            if (status === 'all') {
                day.style.opacity = '1';
                day.style.backgroundColor = '';
            } else if (dayStatus === status) {
                day.style.opacity = '1';
                day.style.backgroundColor = '#f8f9ff';
                if (!found) {
                    day.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    found = true;
                }
            } else if (dayStatus && dayStatus !== 'weekend') {
                day.style.opacity = '0.4';
            } else {
                day.style.opacity = '0.4';
            }
        });
        
        // Reset after 3 seconds if not all
        if (status !== 'all') {
            setTimeout(() => {
                if (activeFilter === status) {
                    days.forEach(day => {
                        day.style.opacity = '1';
                        day.style.backgroundColor = '';
                    });
                }
            }, 3000);
        }
    });
});

// Modal functionality
let modalInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    modalInstance = new bootstrap.Modal(document.getElementById('attendanceModal'));
    
    // Add click handlers to all calendar days
    document.querySelectorAll('.calendar-day').forEach(day => {
        if (day.dataset.date) {
            day.addEventListener('click', function(e) {
                const date = this.dataset.date;
                const attendance = @json($detailedAttendance);
                showAttendanceDetails(date, attendance[date] || null);
            });
        }
    });
});

function showAttendanceDetails(date, attendance) {
    const dateObj = new Date(date);
    const formattedDate = dateObj.toLocaleDateString('en-US', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
    
    let statusBadgeClass = 'status-absent-badge';
    let statusText = 'Absent';
    
    if (attendance) {
        switch(attendance.status) {
            case 'present': 
                statusBadgeClass = 'status-present-badge';
                // FIXED: Properly check if leave_type exists and concatenate string
                if (attendance.leave_type) {
                    statusText = attendance.status_text || 'Present + ' + attendance.leave_type;
                } else {
                    statusText = 'Present';
                }
                break;
            case 'absent': 
                statusBadgeClass = 'status-absent-badge'; 
                statusText = 'Absent'; 
                break;
            case 'leave': 
                statusBadgeClass = 'status-leave-badge'; 
                statusText = attendance.leave_type ?? 'Leave'; 
                break;
            case 'weekend': 
                statusBadgeClass = 'status-weekend-badge'; 
                statusText = 'Weekly Off'; 
                break;
            case 'short_attendance':
                statusBadgeClass = 'status-leave-badge';
                statusText = 'Short Attendance';
                break;
        }
    }
    
    let detailsHtml = `
        <div class="text-center mb-4">
            <div class="status-badge-modal ${statusBadgeClass}">${statusText}</div>
        </div>
        <div class="detail-row">
            <span class="detail-label">Day</span>
            <span class="detail-value">${dateObj.toLocaleDateString('en-US', { weekday: 'long' })}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date</span>
            <span class="detail-value">${formattedDate}</span>
        </div>
    `;
    
    if (attendance && attendance.status !== 'weekend') {
        if (attendance.check_in) {
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Check In</span>
                    <span class="detail-value">${attendance.check_in}</span>
                </div>
            `;
        }
        if (attendance.check_out) {
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Check Out</span>
                    <span class="detail-value">${attendance.check_out}</span>
                </div>
            `;
        }
        if (attendance.total_hours) {
            const formattedTotal = formatHoursForDisplay(attendance.total_hours);
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Total Hours</span>
                    <span class="detail-value">${formattedTotal}</span>
                </div>
            `;
        }
        if (attendance.required_hours) {
            const formattedRequired = formatHoursForDisplay(attendance.required_hours);
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Required Hours</span>
                    <span class="detail-value">${formattedRequired}</span>
                </div>
            `;
        }
        
        // Show short by hours if applicable
        if (attendance.short_hours && attendance.short_hours > 0) {
            const shortByFormatted = formatHoursForDisplay(attendance.short_hours);
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Short By</span>
                    <span class="detail-value text-warning">${shortByFormatted}</span>
                </div>
            `;
        }
        
        if (attendance.leave_type) {
            const quotaStatus = attendance.within_quota ? 'Within Quota ✓' : 'Exceeds Quota ⚠️';
            const quotaClass = attendance.within_quota ? 'text-success' : 'text-danger';
            
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Leave Type</span>
                    <span class="detail-value">${attendance.leave_type}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Quota Status</span>
                    <span class="detail-value ${quotaClass}">${quotaStatus}</span>
                </div>
            `;
            
            if (!attendance.within_quota && attendance.days_to_deduct) {
                detailsHtml += `
                    <div class="detail-row">
                        <span class="detail-label">Salary Impact</span>
                        <span class="detail-value text-danger">${attendance.days_to_deduct} day(s) will be deducted</span>
                    </div>
                `;
            }
            
            // Show approval status
            if (attendance.is_approved !== undefined) {
                const approvalStatus = attendance.is_approved ? 'Approved ✓' : 'Unapproved ⚠️';
                const approvalClass = attendance.is_approved ? 'text-success' : 'text-danger';
                detailsHtml += `
                    <div class="detail-row">
                        <span class="detail-label">Approval Status</span>
                        <span class="detail-value ${approvalClass}">${approvalStatus}</span>
                    </div>
                `;
            }
        }
        
        if (attendance.late_minutes && attendance.late_minutes > 0) {
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Late By</span>
                    <span class="detail-value text-warning">${attendance.late_minutes} minutes</span>
                </div>
            `;
        }
        
        if (attendance.grace_status && attendance.grace_status !== 'No check-in record') {
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Grace Period</span>
                    <span class="detail-value">${attendance.grace_status}</span>
                </div>
            `;
        }
        
        // Show deduction percentage if applicable
        if (attendance.deduction_percentage && attendance.deduction_percentage > 0) {
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Deduction</span>
                    <span class="detail-value text-danger">${attendance.deduction_percentage}% of daily salary</span>
                </div>
            `;
        }
    }
    
    if (!attendance || Object.keys(attendance).length === 0) {
        detailsHtml += `<div class="text-center text-muted py-3">No attendance record found for this day</div>`;
    }
    
    document.getElementById('modalBody').innerHTML = `
        <div class="text-center mb-3">
            <h6 class="text-muted">${formattedDate}</h6>
        </div>
        ${detailsHtml}
    `;
    
    modalInstance.show();
}

// Helper function to format hours
function formatHoursForDisplay(hours) {
    if (!hours) return null;
    var hrs = Math.floor(hours);
    var mins = Math.round((hours - hrs) * 60);
    if (mins == 60) {
        hrs++;
        mins = 0;
    }
    if (hrs > 0 && mins > 0) {
        return hrs + ' hours ' + mins + ' minutes';
    } else if (hrs > 0) {
        return hrs + ' hours';
    } else {
        return mins + ' minutes';
    }
}
</script>

@endsection