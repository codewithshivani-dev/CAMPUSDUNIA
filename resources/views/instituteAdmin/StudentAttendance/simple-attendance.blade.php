@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

/* Header Card */
.header-card {
    background: var(--primary-gradient);
    color: white;
    padding: 25px 30px;
    border-radius: 20px;
    margin-bottom: 25px;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
    position: relative;
    overflow: hidden;
}

.header-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: headerPulse 4s ease-in-out infinite;
}

@keyframes headerPulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.3; }
}

.header-card h3 {
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.header-card p {
    font-size: 1rem;
    opacity: 0.95;
}

.header-card .btn-outline-light {
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 30px;
    padding: 10px 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.header-card .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.header-card .btn-light {
    background: white;
    border: none;
    color: var(--primary-color);
    border-radius: 30px;
    padding: 10px 15px;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

.header-card .btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

/* Date Navigation */
.date-navigation {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 20px;
    margin-bottom: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(67, 97, 238, 0.1);
}

.date-navigation .col a {
    text-decoration: none;
}

.date-navigation .p-2 {
    border-radius: 15px;
    padding: 12px 8px !important;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.date-navigation .bg-light {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0) !important;
    color: #475569;
}

.date-navigation .bg-light:hover {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff) !important;
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

.date-navigation .bg-primary {
    background: var(--primary-gradient) !important;
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
}

.date-navigation .bg-info {
    background: var(--info-gradient) !important;
    box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
}

.date-navigation .small {
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.date-navigation .fw-bold {
    font-size: 1.3rem;
    margin: 5px 0;
}

/* Summary Card */
.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.98);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.12);
}

.card-body {
    padding: 25px 20px;
}

.display-6 {
    font-size: 2.2rem;
    font-weight: 800;
    margin-bottom: 5px;
}

.text-primary {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-success {
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-warning {
    background: var(--warning-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-muted {
    color: #64748b !important;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Lecture Card */
.lecture-card {
    border: 2px solid rgba(67, 97, 238, 0.1);
    border-radius: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    background: white;
    overflow: hidden;
}

.lecture-card:hover {
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.15);
    transform: translateY(-3px);
    border-color: var(--primary-color);
}

.lecture-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    padding: 18px 20px;
    border-bottom: 2px solid rgba(67, 97, 238, 0.1);
}

.lecture-body {
    padding: 20px;
    background: linear-gradient(135deg, #ffffff, #f8fafc);
}

/* Time Badge */
.time-badge {
    background: var(--primary-gradient);
    color: white;
    padding: 8px 18px;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    display: inline-block;
}

/* Badges */
.badge-marked {
    background: var(--success-gradient);
    color: white;
    padding: 6px 15px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

.badge-pending {
    background: var(--warning-gradient);
    color: white;
    padding: 6px 15px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #64748b, #475569) !important;
    padding: 6px 15px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Lecture Content */
.lecture-body h6 {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 8px;
}

.lecture-body .text-muted.small {
    color: #64748b !important;
    font-size: 0.8rem;
    text-transform: none;
    letter-spacing: normal;
}

.lecture-body .me-3 {
    margin-right: 1.5rem !important;
    display: inline-block;
    margin-bottom: 5px;
}

.lecture-body i {
    color: var(--primary-color);
    margin-right: 4px;
}

/* Buttons */
.btn {
    padding: 10px 20px;
    font-weight: 600;
    font-size: 0.85rem;
    border-radius: 40px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
}

.btn-outline-success {
    background: transparent;
    border: 2px solid var(--success-color);
    color: var(--success-color);
}

.btn-outline-success:hover {
    background: var(--success-gradient);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.8rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 40px;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 20px;
    border: 2px dashed rgba(67, 97, 238, 0.3);
}

.empty-state i {
    font-size: 5rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 25px;
    opacity: 0.6;
}

.empty-state h4 {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 10px;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 1.2rem 1.5rem;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    border-top: 1px solid rgba(67, 97, 238, 0.1);
    padding: 1.2rem 1.5rem;
}

/* Form Controls */
.form-control {
    border-radius: 12px;
    border: 2px solid rgba(67, 97, 238, 0.2);
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

/* Border Bottom */
.border-bottom {
    border-bottom: 2px solid rgba(67, 97, 238, 0.1) !important;
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

/* Responsive Design */
@media (max-width: 768px) {
    .header-card {
        padding: 20px;
    }
    
    .header-card h3 {
        font-size: 1.3rem;
    }
    
    .header-card .btn-group {
        margin-top: 15px;
        display: flex;
        justify-content: center;
    }
    
    .date-navigation .p-2 {
        padding: 8px 4px !important;
    }
    
    .date-navigation .fw-bold {
        font-size: 1rem;
    }
    
    .display-6 {
        font-size: 1.8rem;
    }
    
    .lecture-body .me-3 {
        margin-right: 1rem !important;
        display: block;
    }
    
    .lecture-header {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        padding: 8px 16px;
        font-size: 0.8rem;
    }
}

/* Animation */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.lecture-card {
    animation: slideDown 0.4s ease;
}

/* Fw-bold override */
.fw-bold {
    font-weight: 700 !important;
}

/* Icons in text */
.text-muted i,
.lecture-body i {
    color: var(--primary-color);
    margin-right: 5px;
}
</style>

<div class="container-fluid">
    <div class="attendance-container">
        <!-- Header Section -->
        <div class="header-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-1">
                        <i class="fas fa-book-open me-2"></i>Today's Lectures
                    </h3>
                    <p class="mb-0">
                        <i class="fas fa-user-graduate me-2"></i>
                        Welcome, {{ $employee->name }} - {{ $department->department ?? 'Employee' }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        <a href="{{ route('employee.attendance.index', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
                            class="btn btn-outline-light" style="font-size:10px;">
                            <i class="fas fa-chevron-left me-1"></i> Previous
                        </a>
                        <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#datePickerModal" style="font-size:10px;">
                            <i class="far fa-calendar-alt me-2"></i>
                            {{ $date->format('D, M d, Y') }}
                        </button>
                        <a href="{{ route('employee.attendance.index', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
                            class="btn btn-outline-light" style="font-size:10px;">
                             Next&nbsp; <i class="fas fa-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Date Navigation -->
        <div class="date-navigation">
            <div class="row text-center">
                @for($i = -2; $i <= 2; $i++) @php $navDate=$date->copy()->addDays($i);
                    $isToday = $navDate->isToday();
                    $isSelected = $navDate->format('Y-m-d') == $selectedDate;
                    @endphp
                    <div class="col">
                        <a href="{{ route('employee.attendance.index', ['date' => $navDate->format('Y-m-d')]) }}"
                            class="text-decoration-none">
                            <div
                                class="p-2 rounded {{ $isSelected ? 'bg-primary text-white' : ($isToday ? 'bg-info text-white' : 'bg-light') }}">
                                <div class="small">{{ $navDate->format('D') }}</div>
                                <div class="fw-bold">{{ $navDate->format('d') }}</div>
                                <div class="small">{{ $navDate->format('M') }}</div>
                            </div>
                        </a>
                    </div>
                    @endfor
            </div>
        </div>
        
         <!-- Summary -->
        @if($lectures->count() > 0)
        <div class="card my-4">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="display-6 fw-bold text-primary">{{ $lectures->count() }}</div>
                        <div class="text-muted">
                            <i class="fas fa-book me-1"></i>Total Lectures
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="display-6 fw-bold text-success">
                            {{ $lectures->where('attendance_marked', true)->count() }}
                        </div>
                        <div class="text-muted">
                            <i class="fas fa-check-circle me-1"></i>Attendance Marked
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="display-6 fw-bold text-warning">
                            {{ $lectures->where('attendance_marked', false)->count() }}
                        </div>
                        <div class="text-muted">
                            <i class="fas fa-clock me-1"></i>Pending Attendance
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- Lectures List -->
        @if($lectures->count() > 0)
        @foreach($lectures->groupBy('time_slot') as $timeSlot => $slotLectures)
        <div class="lecture-card">
            <div class="lecture-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="time-badge">
                            <i class="far fa-clock me-2"></i>{{ $timeSlot }}
                        </span>
                        <span class="ms-3 fw-bold">
                            <i class="fas fa-layer-group me-1"></i>
                            {{ $slotLectures->count() }}
                            Lecture{{ $slotLectures->count() > 1 ? 's' : '' }}
                        </span>
                    </div>
                    <div>
                        @if($slotLectures->where('attendance_marked', true)->count() == $slotLectures->count())
                        <span class="badge-marked">
                            <i class="fas fa-check-circle me-1"></i>All Marked
                        </span>
                        @elseif($slotLectures->where('attendance_marked', true)->count() > 0)
                        <span class="badge-pending">
                            <i class="fas fa-adjust me-1"></i>Partially Marked
                        </span>
                        @else
                        <span class="badge bg-secondary">
                            <i class="fas fa-hourglass-start me-1"></i>Pending
                        </span>
                        @endif
                    </div>
                </div>
            </div>
    
            <div class="lecture-body">
                <!-- CORRECT: Use $slotLectures for this specific time slot -->
                @foreach($slotLectures as $lecture)
                <div class="row align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="col-md-8">
                        <h6 class="mb-1">
                            <i class="fas fa-graduation-cap me-2"></i>{{ $lecture->subject_name }}
                        </h6>
                        <div class="text-muted small">
                            <span class="me-3">
                                <i class="fas fa-bookmark me-1"></i>{{ $lecture->course_type }} - {{ $lecture->sub_type }}
                            </span>
                            <span class="me-3">
                                <i class="fas fa-calendar-alt me-1"></i>Sem: {{ $lecture->semester_id }}
                            </span>
                            <span class="me-3">
                                @if($lecture->section_id === 'all')
                                <i class="fas fa-users me-1"></i>All Sections
                                @else
                                @php
                                // Get section name from the lecture object if available
                                $sectionName = $lecture->section_name ?? $lecture->section_id;
                                @endphp
                                <i class="fas fa-user-friends me-1"></i>Section: {{ $sectionName }}
                                @endif
                            </span>
                            <span>
                                <i class="fas fa-calendar me-1"></i>{{ $lecture->academic_year }}
                            </span>
                        </div>
                    </div>
    
                    <div class="col-md-4 text-end">
                        @if($lecture->attendance_marked)
                        <a href="{{ route('employee.attendance.students', ['lectureId' => $lecture->lecture_id, 'date' => $selectedDate]) }}"
                            class="btn btn-outline-success btn-sm">
                            <i class="fas fa-eye me-1"></i>View Attendance
                        </a>
                        @else
                        <a href="{{ route('employee.attendance.students', ['lectureId' => $lecture->lecture_id, 'date' => $selectedDate]) }}"
                            class="btn btn-primary btn-sm text-white">
                            <i class="fas fa-check-circle me-1" style="color:white!important;"></i>Mark Attendance
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h4>No Lectures Scheduled</h4>
            <p class="text-muted">You don't have any lectures scheduled for {{ $date->format('l, F j, Y') }}</p>
            <div class="mt-3">
                <a href="{{ route('employee.attendance.index', ['date' => Carbon\Carbon::today()->format('Y-m-d')]) }}"
                    class="btn btn-primary">
                    <i class="fas fa-calendar-day me-2"></i>Go to Today
                </a>
            </div>
        </div>
        @endif
   </div>
</div>

<!-- Date Picker Modal -->
<div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="datePickerModalLabel">
                    <i class="far fa-calendar-alt me-2"></i>Select Date
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="date" id="datePicker" class="form-control" value="{{ $selectedDate }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="goToSelectedDate()">
                    <i class="fas fa-check me-1"></i>Go to Date
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle date picker
    $('#datePicker').change(function() {
        const selectedDate = $(this).val();
        $('#datePickerModal').modal('hide');
        window.location.href = "{{ route('employee.attendance.index') }}?date=" + selectedDate;
    });

    // Auto-refresh page every 5 minutes to check for new lectures
    setInterval(function() {
        // Only refresh if not on attendance marking page
        if (!window.location.pathname.includes('/students/')) {
            window.location.reload();
        }
    }, 300000); // 5 minutes
});

function goToSelectedDate() {
    const selectedDate = $('#datePicker').val();
    window.location.href = "{{ route('employee.attendance.index') }}?date=" + selectedDate;
}

// Keyboard shortcuts
$(document).keydown(function(e) {
    // Left arrow for previous day
    if (e.keyCode === 37) {
        e.preventDefault();
        const prevDate = "{{ $date->copy()->subDay()->format('Y-m-d') }}";
        window.location.href = "{{ route('employee.attendance.index') }}?date=" + prevDate;
    }
    // Right arrow for next day
    if (e.keyCode === 39) {
        e.preventDefault();
        const nextDate = "{{ $date->copy()->addDay()->format('Y-m-d') }}";
        window.location.href = "{{ route('employee.attendance.index') }}?date=" + nextDate;
    }
    // T for today
    if (e.keyCode === 84 && e.ctrlKey) {
        e.preventDefault();
        window.location.href = "{{ route('employee.attendance.index') }}";
    }
});
</script>
@endsection