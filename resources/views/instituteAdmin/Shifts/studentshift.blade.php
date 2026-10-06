@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --success-color: #10b981;
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
}


/* Page Header */
.page-header {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 25px 30px;
    margin-bottom: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    color:#fff;
}

.page-header h2 {
    font-size: 1.4rem;
    font-weight: 700;
    color:#fff;
    margin-bottom: 4px;
}

.page-header h2 i {
    background: #fff;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    color:#fff;
}

.page-header p {
    color:#fff;
    font-size: 0.9rem;
    margin: 0;
}

.btn-outline-primary {
    background: var(--primary-gradient);
    color: #fff;
    border-radius: 30px;
    padding: 10px 22px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    border:0px;
}

.btn-outline-primary:hover {
    
    transform: translateY(-2px);
}

/* Shift Card */
.shift-card {
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    margin-bottom: 20px;
    border: 2px solid rgba(67, 97, 238, 0.1);
    overflow: hidden;
}

.shift-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(67, 97, 238, 0.15);
}

.card-header {
    padding: 18px 25px;
    border: none;
}

.card-header.bg-success {
    background: var(--success-gradient) !important;
}

.card-header.bg-info {
    background: var(--info-gradient) !important;
}

.card-header h5 {
    font-weight: 700;
    font-size: 1.1rem;
}

.card-body {
    padding: 25px;
}

/* Shift Timings */
.shift-timings {
    font-size: 1.3rem;
    font-weight: 800;
}

.shift-timings.text-success {
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.shift-timings.text-danger {
    background: var(--danger-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Alert Styles */
.alert {
    border-radius: 12px;
    border: none;
    padding: 15px 20px;
}

.alert-light {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1px solid rgba(67, 97, 238, 0.1);
    color: #475569;
}

.alert-secondary {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border: 1px solid rgba(100, 116, 139, 0.2);
    color: #475569;
}

.alert-info {
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    border-left: 4px solid #3b82f6;
    color: #075985;
}

/* Badge Styles */
.badge {
    padding: 6px 14px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.badge-priority {
    font-size: 0.8rem;
    color:#fff !important;
}

.badge.bg-light {
    background: rgba(255,255,255,0.2) !important;
    backdrop-filter: blur(10px);
}

.badge.bg-success {
    background: var(--success-gradient) !important;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #64748b, #475569) !important;
}

.badge.bg-info {
    background: var(--info-gradient) !important;
}

.assignment-badge-individual {
    background: var(--success-gradient) !important;
    color: white;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

.assignment-badge-department {
    background: var(--info-gradient) !important;
    color: white;
    box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
}

/* P-3 bg-light rounded */
.p-3.bg-light {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0) !important;
    border-radius: 15px !important;
    border: 1px solid rgba(67, 97, 238, 0.1);
}

/* Icon Circles */
.bg-info.text-white.rounded-circle,
.bg-success.text-white.rounded-circle,
.bg-warning.text-dark.rounded-circle {
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.bg-info.text-white.rounded-circle {
    background: var(--info-gradient) !important;
}

.bg-success.text-white.rounded-circle {
    background: var(--success-gradient) !important;
}

.bg-warning.text-dark.rounded-circle {
    background: var(--warning-gradient) !important;
}

/* Holiday Card */
.holiday-card {
    background: var(--primary-gradient) !important;
    color: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.25);
    border: none;
    position: relative;
    overflow: hidden;
}

.holiday-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: holidayPulse 4s ease-in-out infinite;
}

@keyframes holidayPulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.3; }
}

.holiday-card .card-body {
    position: relative;
    z-index: 1;
}

.holiday-card .display-1 {
    font-size: 4rem;
    opacity: 0.9;
}

/* Conflict Alert */
.conflict-alert {
    border-left: 5px solid #f59e0b;
}

/* Shift Overlap Warning */
.shift-overlap-warning {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(245, 158, 11, 0.3);
}

.shift-overlap-warning .card-header {
    background: var(--warning-gradient) !important;
    color: white;
    border: none;
    padding: 15px 25px;
}

.shift-overlap-warning .card-header h6 {
    font-weight: 700;
}

/* Timeline */
.timeline-item {
    position: relative;
    padding-left: 35px;
    margin-bottom: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: 0;
    top: 8px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.15);
}

.timeline-item:after {
    content: '';
    position: absolute;
    left: 6px;
    top: 22px;
    width: 2px;
    height: calc(100% - 14px);
    background: rgba(67, 97, 238, 0.15);
    border-radius: 2px;
}

.timeline-item:last-child:after {
    display: none;
}

.timeline-item:before {
    background: var(--primary-gradient);
}

.timeline-item.department:before {
    background: var(--info-gradient);
}

.timeline-item.inactive:before {
    background: linear-gradient(135deg, #64748b, #475569);
}

.timeline-item .card {
    border: 2px solid rgba(67, 97, 238, 0.1);
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

/* Modal Styles */
.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 18px 24px;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-title {
    font-weight: 700;
    font-size: 1.1rem;
}

.modal-footer {
    border-top: 1px solid rgba(67, 97, 238, 0.1);
    padding: 15px 24px;
}

/* Button Styles */
.btn-secondary {
    background: linear-gradient(135deg, #64748b, #475569);
    border: none;
    border-radius: 30px;
    padding: 10px 20px;
    color: white;
    font-weight: 600;
}

/* Text Styles */
.text-muted {
    color: #94a3b8 !important;
}

strong {
    color: #334155;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-gradient);
    border-radius: 8px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .page-header .btn {
        width: 100%;
        text-align: center;
    }
    
    .shift-timings {
        font-size: 1.1rem;
    }
    
    .card-body {
        padding: 18px;
    }
}
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-clock me-2"></i>My Shift Schedule
            </h2>
            <p class="mb-0">View your current class shift assignment and details</p>
        </div>
        <button class="btn btn-outline-primary" onclick="loadShiftHistory()">
            <i class="bi bi-clock-history me-2"></i>View Shift History
        </button>
    </div>

    @if(isset($assignmentType) && $assignmentType === 'holiday')
        <!-- Holiday/No Shift Card -->
        <div class="card holiday-card shadow-lg mb-4">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-emoji-sunglasses display-1"></i>
                </div>
                <h3 class="card-title">🎉 No Classes Today!</h3>
                <p class="card-text">No shift/class is assigned to you for today.</p>
                <div class="mt-4">
                    <p class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        {{ $message ?? 'Enjoy your free time!' }}
                    </p>
                </div>
            </div>
        </div>
    @elseif(isset($shiftDetails))
        <!-- Current Shift Card -->
        <div class="card shift-card border-{{ $shiftDetails['assignment_type'] === 'individual' ? 'success' : 'info' }}">
            <div class="card-header bg-{{ $shiftDetails['assignment_type'] === 'individual' ? 'success' : 'info' }} text-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="bi bi-{{ $shiftDetails['assignment_type'] === 'individual' ? 'person-check' : 'building' }} me-2"></i>
                        {{ $shiftDetails['shift_name'] }}
                    </h5>
                    <small>
                        @if($shiftDetails['assignment_type'] === 'individual')
                            <i class="bi bi-person-fill me-1"></i> Personal Class Schedule
                        @else
                            <i class="bi bi-building me-1"></i> Department Class Schedule
                        @endif
                    </small>
                </div>
                <div>
                    <span class="badge bg-light text-dark badge-priority">
                        <i class="bi bi-flag me-1"></i>Priority: {{ ucfirst($shiftDetails['priority']) }}
                    </span>
                    <span class="badge {{ $shiftDetails['is_active'] ? 'bg-success' : 'bg-secondary' }} ms-2">
                        <i class="bi bi-circle-fill me-1"></i>
                        {{ $shiftDetails['is_active'] ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Shift Timings -->
                @if($shiftDetails['start_time'] && $shiftDetails['end_time'])
                <div class="row mb-4">
                    <div class="col-md-6 text-center">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">
                                <i class="bi bi-play-circle me-1"></i>CLASS START
                            </small>
                            <div class="shift-timings text-success">
                                {{ $shiftDetails['formatted_start_time'] ?? 
                                   (\Carbon\Carbon::parse($shiftDetails['start_time'])->format('h:i A') ?? 'N/A') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">
                                <i class="bi bi-stop-circle me-1"></i>CLASS END
                            </small>
                            <div class="shift-timings text-danger">
                                {{ $shiftDetails['formatted_end_time'] ?? 
                                   (\Carbon\Carbon::parse($shiftDetails['end_time'])->format('h:i A') ?? 'N/A') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Shift Duration -->
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <div class="alert alert-light">
                            <i class="bi bi-clock-history me-2"></i>
                            <strong>Class Duration:</strong> {{ $shiftDetails['duration'] }}
                            @if($shiftDetails['break_minutes'] > 0)
                                <span class="text-muted ms-2">
                                    (Includes {{ $shiftDetails['break_minutes'] }} min break)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Assignment Type -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                @if($shiftDetails['assignment_type'] === 'individual')
                                    <span class="badge assignment-badge-individual p-2">
                                        <i class="bi bi-person-fill me-1"></i>Individual Assignment
                                    </span>
                                @else
                                    <span class="badge assignment-badge-department p-2">
                                        <i class="bi bi-building me-1"></i>Department Assignment
                                    </span>
                                @endif
                            </div>
                            <div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    Assigned: {{ \Carbon\Carbon::parse($shiftDetails['assignment_date'])->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                <div class="row">
                    @if($shiftDetails['grace_minutes'] > 0)
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-alarm"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">GRACE PERIOD</small>
                                <strong>{{ $shiftDetails['grace_minutes'] }} minutes</strong>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(!empty($shiftDetails['weekly_off_days']))
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-calendar-week"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">WEEKLY OFF</small>
                                @if(!empty($shiftDetails['weekly_off_days']))
                                    <strong>{{ implode(', ', array_slice($shiftDetails['weekly_off_days'], 0, 3)) }}</strong>
                                    @if(count($shiftDetails['weekly_off_days']) > 3)
                                        <small class="text-muted d-block">
                                            +{{ count($shiftDetails['weekly_off_days']) - 3 }} more days
                                        </small>
                                    @endif
                                @else
                                    <strong>None</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="bi bi-flag"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">PRIORITY LEVEL</small>
                                <strong>
                                    @switch($shiftDetails['priority'])
                                        @case('high')
                                            High (Individual Schedule)
                                            @break
                                        @case('medium')
                                            Medium
                                            @break
                                        @case('low')
                                            Low (Department Default)
                                            @break
                                        @default
                                            {{ ucfirst($shiftDetails['priority']) }}
                                    @endswitch
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shift Dates -->
                @if($shiftDetails['start_date'] || $shiftDetails['end_date'])
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-secondary">
                            <i class="bi bi-calendar-range me-2"></i>
                            <strong>Schedule Period:</strong> 
                            @if($shiftDetails['start_date'])
                                {{ \Carbon\Carbon::parse($shiftDetails['start_date'])->format('M d, Y') }}
                            @else
                                Immediate
                            @endif
                            
                            @if($shiftDetails['end_date'])
                                to {{ \Carbon\Carbon::parse($shiftDetails['end_date'])->format('M d, Y') }}
                                
                                @php
                                    $daysLeft = \Carbon\Carbon::parse($shiftDetails['end_date'])->diffInDays(now());
                                @endphp
                                
                                @if($daysLeft > 0)
                                    <span class="badge bg-info ms-2">
                                        {{ $daysLeft }} days left
                                    </span>
                                @endif
                            @else
                                onwards
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Upcoming Shifts (if any overlapping) -->
        @if(isset($upcomingShifts) && count($upcomingShifts) > 0)
        <div class="card mt-4 shift-overlap-warning">
            <div class="card-header">
                <h6 class="mb-0 text-white">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Upcoming Schedule Changes
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    You have overlapping shifts scheduled. Higher priority shifts will take precedence.
                </p>
                @foreach($upcomingShifts as $upcomingShift)
                <div class="alert alert-light mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $upcomingShift['shift_name'] }}</strong>
                            <small class="text-muted ms-2">
                                ({{ $upcomingShift['assignment_type'] }})
                            </small>
                        </div>
                        <div>
                            <small class="text-muted">
                                Starts: {{ \Carbon\Carbon::parse($upcomingShift['start_date'])->format('M d') }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif

    <!-- Shift History Modal -->
    <div class="modal fade" id="shiftHistoryModal" tabindex="-1" aria-labelledby="shiftHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shiftHistoryModalLabel">
                        <i class="bi bi-clock-history me-2"></i>My Shift History
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="shiftHistoryContent">
                        <div class="text-center py-4">
                            <div class="spinner-border" style="color: var(--primary-color);" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading shift history...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadShiftHistory() {
    const modal = new bootstrap.Modal(document.getElementById('shiftHistoryModal'));
    const content = document.getElementById('shiftHistoryContent');
    
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border" style="color: var(--primary-color);" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading shift history...</p>
        </div>
    `;
    
    modal.show();
    
    // Fetch shift history from API
    fetch('/student/shift-history')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = `
                    <div class="mb-3">
                        <h6>Student: ${data.student.name}</h6>
                        <p class="text-muted mb-1">Registration No.: ${data.student.registration_number}</p>
                        <p class="text-muted mb-3">Department: ${data.student.department}</p>
                    </div>
                `;
                
                if (data.history && data.history.length > 0) {
                    html += '<div class="timeline">';
                    data.history.forEach(item => {
                        if (!item.shift) return;
                        
                        const typeClass = item.type;
                        const statusClass = item.status === 'active' ? '' : 'inactive';
                        const shift = item.shift;
                        
                        // Format time
                        const formatTime = (timeStr) => {
                            if (!timeStr) return 'N/A';
                            try {
                                return new Date('1970-01-01T' + timeStr + 'Z').toLocaleTimeString([], { 
                                    hour: '2-digit', 
                                    minute: '2-digit',
                                    hour12: true 
                                });
                            } catch (e) {
                                return timeStr;
                            }
                        };
                        //format Date
                        const anotherDate = new Date();
                        const formatter = new Intl.DateTimeFormat('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'short',
                            day: '2-digit'
                        });
                        const formatDate = formatter.format(anotherDate);
                        console.log(formatDate); 
                        
                        html += `
                            <div class="timeline-item ${typeClass} ${statusClass}">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-1">${shift.shift_name}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-${item.type === 'individual' ? 'person-check' : 'building'} me-1"></i>
                                                    ${item.type === 'individual' ? 'Personal Schedule' : 'Department Schedule'}
                                                </small>
                                            </div>
                                            <div>
                                                <span class="badge ${item.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                                                    ${item.status}
                                                </span>
                                                <span class="badge bg-light text-dark ms-1">
                                                    Priority: ${shift.priority}
                                                </span>
                                            </div>
                                        </div>
                                         <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                 
                                                 ${shift.start_date? 'Start Date: ' + new Date(shift.start_date).toLocaleDateString() : ''}
                                                ${shift.end_date ? ' | End Date: ' + new Date(shift.end_date).toLocaleDateString() : ''}
                                            </small>
                                            <br>
                                            
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                ${formatTime(shift.start_time)} - ${formatTime(shift.end_time)}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                ${item.assigned_at ? 'Assigned: ' + new Date(item.assigned_at).toLocaleDateString() : ''}
                                                ${item.ended_at ? ' | Ended: ' + new Date(item.ended_at).toLocaleDateString() : ''}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                } else {
                    html += '<div class="alert alert-info">No shift history found.</div>';
                }
                
                content.innerHTML = html;
            } else {
                content.innerHTML = '<div class="alert alert-danger">Error loading shift history.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = '<div class="alert alert-danger">Error loading shift history. Please try again.</div>';
        });
}

// Auto-refresh every 30 minutes
setInterval(() => {
    // Optional: Only reload if on current shift view
    if (!window.location.href.includes('history')) {
        window.location.reload();
    }
}, 30 * 60 * 1000);
</script>
@endsection