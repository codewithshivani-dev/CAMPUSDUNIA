@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
.attendance-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.section-info {
    background: #e8f4ff;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.section-badge {
    background-color: #6f42c1;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75em;
    display: inline-block;
    margin: 2px;
}

.header-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.student-row {
    transition: all 0.2s ease;
    border-left: 4px solid transparent;
}

.student-row:hover {
    background-color: #f8f9fa;
    border-left-color: #007bff;
}

.attendance-status {
    min-width: 200px;
}

.quick-actions {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.85em;
}

.status-present {
    background-color: #d4edda;
    color: #155724;
}

.status-absent {
    background-color: #f8d7da;
    color: #721c24;
}

.status-leave {
    background-color: #fff3cd;
    color: #856404;
}

.status-onduty {
    background-color: #cce5ff;
    color: #004085;
}

.status-pending {
    background-color: #e2e3e5;
    color: #383d41;
}

.section-name-cell {
    font-weight: 500;
}

.section-id-hint {
    font-size: 0.75em;
    color: #6c757d;
    margin-top: 2px;
}

/* Status Filter Cards */
.status-filter-card {
    cursor: pointer;
    transition: all 0.2s ease;
    border: 2px solid transparent;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-filter-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.status-filter-card.active {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

.status-filter-card.present {
    background-color: #d4edda;
    color: #155724;
}

.status-filter-card.absent {
    background-color: #f8d7da;
    color: #721c24;
}

.status-filter-card.leave {
    background-color: #fff3cd;
    color: #856404;
}

.status-filter-card.onduty {
    background-color: #cce5ff;
    color: #004085;
}

.status-filter-card.pending {
    background-color: #e2e3e5;
    color: #383d41;
}

.status-filter-card.all {
    background-color: #ffffff;
    color: #333;
    border: 1px solid #dee2e6;
}

.status-count {
    font-size: 2em;
    font-weight: bold;
    line-height: 1.2;
}

.status-label {
    font-size: 0.9em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-container {
    margin: 20px 0;
}

.student-row.filter-hidden {
    display: none;
}

/* Active filter indicator */
.active-filter-badge {
    background-color: #007bff;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 15px;
}
</style>

<div class="attendance-container">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>📝 Mark Attendance</h3>
            <p class="text-muted mb-0">Mark attendance for {{ $lecture->subject_name }}</p>
        </div>
        <div>
            <a href="{{ route('employee.attendance.index', ['date' => $attendanceDate]) }}"
                class="btn btn-outline-secondary">
                ← Back to Lectures
            </a>
        </div>
    </div>

    <!-- Lecture Information -->
    <div class="header-info">
        <div class="row">
            <div class="col-md-3">
                <strong>Date:</strong>
                <div>{{ \Carbon\Carbon::parse($attendanceDate)->format('l, F j, Y') }}</div>
            </div>
            <div class="col-md-3">
                <strong>Time:</strong>
                <div>{{ date('h:i A', strtotime($lecture->start_time)) }} -
                    {{ date('h:i A', strtotime($lecture->end_time)) }}</div>
            </div>
            <div class="col-md-3">
                <strong>Subject:</strong>
                <div>{{ $lecture->subject_name }}</div>
            </div>
            <div class="col-md-3">
                <strong>Class (Section)</strong>
                <div>{{ $lecture->course_type }} - {{ $lecture->sub_type }}
                    ({{ $sections->first()->section_name ?? $lecture->section_id }})
                </div>
            </div>
        </div>
    </div>

    <!-- Status Filter Cards -->
    @php
    // Calculate counts for each status
    $presentCount = $existingAttendance->where('status', 'Present')->count();
    $absentCount = $existingAttendance->where('status', 'Absent')->count();
    $leaveCount = $existingAttendance->where('status', 'Leave')->count();
    $onDutyCount = $existingAttendance->where('status', 'On Duty')->count();
    $totalMarked = $presentCount + $absentCount + $leaveCount + $onDutyCount;
    $pendingCount = count($students) - $totalMarked;
    @endphp

    <div class="filter-container">
        <div class="row g-3">
            <!-- All Students Card -->
            <div class="col-md-2">
                <div class="status-filter-card all" onclick="filterStudents('all')" id="filter-all">
                    <div class="status-count">{{ count($students) }}</div>
                    <div class="status-label">All Students</div>
                </div>
            </div>
            
            <!-- Present Card -->
            <div class="col-md-2">
                <div class="status-filter-card present" onclick="filterStudents('Present')" id="filter-present">
                    <div class="status-count">{{ $presentCount }}</div>
                    <div class="status-label">Present</div>
                </div>
            </div>
            
            <!-- Absent Card -->
            <div class="col-md-2">
                <div class="status-filter-card absent" onclick="filterStudents('Absent')" id="filter-absent">
                    <div class="status-count">{{ $absentCount }}</div>
                    <div class="status-label">Absent</div>
                </div>
            </div>
            
            <!-- Leave Card -->
            <div class="col-md-2">
                <div class="status-filter-card leave" onclick="filterStudents('Leave')" id="filter-leave">
                    <div class="status-count">{{ $leaveCount }}</div>
                    <div class="status-label">Leave</div>
                </div>
            </div>
            
            <!-- On Duty Card -->
            <div class="col-md-2">
                <div class="status-filter-card onduty" onclick="filterStudents('On Duty')" id="filter-onduty">
                    <div class="status-count">{{ $onDutyCount }}</div>
                    <div class="status-label">On Duty</div>
                </div>
            </div>
            
            <!-- Pending Card -->
            <div class="col-md-2">
                <div class="status-filter-card pending" onclick="filterStudents('Pending')" id="filter-pending">
                    <div class="status-count">{{ $pendingCount }}</div>
                    <div class="status-label">Pending</div>
                </div>
            </div>
        </div>
        
        <!-- Active Filter Indicator -->
        <div id="activeFilterIndicator" class="active-filter-badge" style="display: none; margin-top: 15px;">
            Showing: <span id="currentFilter">All Students</span> 
            <button class="btn btn-sm btn-light ms-2" onclick="clearFilter()">Clear Filter ✕</button>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>Mark All:</strong>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-success" onclick="markAll('Present')">
                    <i class="fas fa-check-circle"></i> All Present
                </button>
                <button type="button" class="btn btn-outline-danger" onclick="markAll('Absent')">
                    <i class="fas fa-times-circle"></i> All Absent
                </button>
                <button type="button" class="btn btn-outline-warning" onclick="markAll('Leave')">
                    <i class="fas fa-umbrella-beach"></i> All Leave
                </button>
                <button type="button" class="btn btn-outline-info" onclick="markAll('On Duty')">
                    <i class="fas fa-briefcase"></i> All On Duty
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="clearAll()">
                    <i class="fas fa-eraser"></i> Clear All
                </button>
            </div>
        </div>
    </div>

    <!-- Attendance Form -->
    <form id="attendanceForm" action="{{ route('ajax.employee.attendance.save') }}" method="POST">
        @csrf
        <input type="hidden" name="lecture_id" value="{{ $lecture->lecture_id }}">
        <input type="hidden" name="subject_id" value="{{ $lecture->subject_id }}">
        <input type="hidden" name="semester_id" value="{{ $lecture->semester_id }}">
        <input type="hidden" name="academic_year" value="{{ $lecture->academic_year }}">
        <input type="hidden" name="department_id" value="{{ $lecture->department_id }}">
        <input type="hidden" name="course_type" value="{{ $lecture->course_type }}">
        <input type="hidden" name="sub_type" value="{{ $lecture->sub_type }}">
        <input type="hidden" name="product_id" value="{{ $lecture->product_id }}">
        <input type="hidden" name="attendance_date" value="{{ $attendanceDate }}">
        <input type="hidden" name="start_time" value="{{ $lecture->start_time }}">
        <input type="hidden" name="end_time" value="{{ $lecture->end_time }}">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Reg. No.</th>
                        <th>Student Name</th>
                        <th>Section</th>
                        <th class="attendance-status">Attendance Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    @foreach($students as $index => $student)
                    @php
                    $existing = $existingAttendance[$student->student_hash_id] ?? null;
                    $status = $existing ? $existing->status : null;
                    $statusClass = $status ? strtolower(str_replace(' ', '-', $status)) : 'pending';

                    // Get proper section name for this student
                    $studentSectionName = $student->section_name ?? $student->section_id;

                    // If section_name is not set on student, try to find it from sections collection
                    if (!isset($student->section_name) || $student->section_name == $student->section_id) {
                    foreach($sections as $section) {
                    if($section->section_id == $student->section_id) {
                    $studentSectionName = $section->section_name;
                    break;
                    }
                    }
                    }
                    @endphp
                    <tr class="student-row {{ $statusClass }}" data-status="{{ $status ?: 'pending' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $student->registration_number }}</strong>
                        </td>
                        <td>{{ $student->full_name }}</td>
                        <td class="section-name-cell">
                            @if($student->section_id)
                            <div>
                                <span>
                                    <i></i> {{ $studentSectionName }}
                                </span>
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="attendance-status">
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="attendance[{{ $student->student_hash_id }}]"
                                    id="present{{ $student->student_hash_id }}" value="Present"
                                    {{ $status == 'Present' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="present{{ $student->student_hash_id }}">
                                    <i class="fas fa-check"></i> Present
                                </label>

                                <input type="radio" class="btn-check" name="attendance[{{ $student->student_hash_id }}]"
                                    id="absent{{ $student->student_hash_id }}" value="Absent"
                                    {{ $status == 'Absent' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger" for="absent{{ $student->student_hash_id }}">
                                    <i class="fas fa-times"></i> Absent
                                </label>

                                <input type="radio" class="btn-check" name="attendance[{{ $student->student_hash_id }}]"
                                    id="leave{{ $student->student_hash_id }}" value="Leave"
                                    {{ $status == 'Leave' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning" for="leave{{ $student->student_hash_id }}">
                                    <i class="fas fa-umbrella-beach"></i> Leave
                                </label>

                                <input type="radio" class="btn-check" name="attendance[{{ $student->student_hash_id }}]"
                                    id="onduty{{ $student->student_hash_id }}" value="On Duty"
                                    {{ $status == 'On Duty' ? 'checked' : '' }}>
                                <label class="btn btn-outline-info" for="onduty{{ $student->student_hash_id }}">
                                    <i class="fas fa-briefcase"></i> On Duty
                                </label>
                            </div>
                        </td>
                        <td>
                            <textarea name="remarks[{{ $student->student_hash_id }}]"
                                class="form-control form-control-sm" 
                                placeholder="Optional remarks"
                                rows="2"
                                style="min-width: 150px; resize: vertical;">{{ $existing ? $existing->remarks : '' }}</textarea>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div>
                <div class="d-flex gap-3">
                    <span class="status-badge status-present">
                        <i class="fas fa-check-circle"></i> Present
                    </span>
                    <span class="status-badge status-absent">
                        <i class="fas fa-times-circle"></i> Absent
                    </span>
                    <span class="status-badge status-leave">
                        <i class="fas fa-umbrella-beach"></i> Leave
                    </span>
                    <span class="status-badge status-onduty">
                        <i class="fas fa-briefcase"></i> On Duty
                    </span>
                    <span class="status-badge status-pending">
                        <i class="fas fa-hourglass-half"></i> Pending
                    </span>
                </div>

                @if($presentCount > 0 || $absentCount > 0 || $leaveCount > 0 || $onDutyCount > 0 || $pendingCount > 0)
                <div class="mt-2 text-muted">
                    <small>
                        Current status: 
                        <span class="text-success">{{ $presentCount }} Present</span>, 
                        <span class="text-danger">{{ $absentCount }} Absent</span>, 
                        <span class="text-warning">{{ $leaveCount }} Leave</span>,
                        <span class="text-info">{{ $onDutyCount }} On Duty</span>,
                        <span class="text-secondary">{{ $pendingCount }} Pending</span>
                    </small>
                </div>
                @endif
            </div>
            <div>
                <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                    <i class="fas fa-save"></i> Save Attendance
                </button>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // Count current attendance status
    updateAttendanceCount();

    // Update count when attendance changes
    $('input[type="radio"]').change(function() {
        updateAttendanceCount();
        updateStatusCards();
    });

    // Form submission
    $('#attendanceForm').submit(function(e) {
        const totalStudents = {{ count($students) }};
        const markedStudents = $('input[type="radio"]:checked').length;

        if (markedStudents !== totalStudents) {
            e.preventDefault();
            alert(
                `Please mark attendance for all ${totalStudents} students. Currently marked: ${markedStudents}`);
            return false;
        }

        // Show loading
        const $submitBtn = $(this).find('button[type="submit"]');
        $submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        return true;
    });

    // Initialize status cards
    updateStatusCards();
    
    // Remove active class from all filter cards initially
    $('.status-filter-card').removeClass('active');
    $('#filter-all').addClass('active');
});

function markAll(status) {
    $('input[type="radio"]').each(function() {
        if ($(this).val() === status) {
            $(this).prop('checked', true);
        }
    });
    updateAttendanceCount();
    updateStatusCards();
}

function clearAll() {
    $('input[type="radio"]').prop('checked', false);
    updateAttendanceCount();
    updateStatusCards();
}

function updateAttendanceCount() {
    const presentCount = $('input[value="Present"]:checked').length;
    const absentCount = $('input[value="Absent"]:checked').length;
    const leaveCount = $('input[value="Leave"]:checked').length;
    const onDutyCount = $('input[value="On Duty"]:checked').length;
    const totalCount = {{ count($students) }};
    const pendingCount = totalCount - (presentCount + absentCount + leaveCount + onDutyCount);

    console.log(`Present: ${presentCount}, Absent: ${absentCount}, Leave: ${leaveCount}, On Duty: ${onDutyCount}, Pending: ${pendingCount}, Total: ${totalCount}`);
    
    // Update submit button state
    if(presentCount + absentCount + leaveCount + onDutyCount === totalCount) {
        $('#submitBtn').removeClass('btn-secondary').addClass('btn-success');
    } else {
        $('#submitBtn').removeClass('btn-success').addClass('btn-secondary');
    }
}

function updateStatusCards() {
    // Update counts based on current selections
    const presentCount = $('input[value="Present"]:checked').length;
    const absentCount = $('input[value="Absent"]:checked').length;
    const leaveCount = $('input[value="Leave"]:checked').length;
    const onDutyCount = $('input[value="On Duty"]:checked').length;
    const totalCount = {{ count($students) }};
    const pendingCount = totalCount - (presentCount + absentCount + leaveCount + onDutyCount);
    
    // Update card counts
    $('#filter-present .status-count').text(presentCount);
    $('#filter-absent .status-count').text(absentCount);
    $('#filter-leave .status-count').text(leaveCount);
    $('#filter-onduty .status-count').text(onDutyCount);
    $('#filter-pending .status-count').text(pendingCount);
    $('#filter-all .status-count').text(totalCount);
}

function filterStudents(status) {
    // Remove active class from all filter cards
    $('.status-filter-card').removeClass('active');
    
    if (status === 'all') {
        // Show all rows
        $('.student-row').removeClass('filter-hidden');
        $('#filter-all').addClass('active');
        $('#currentFilter').text('All Students');
        $('#activeFilterIndicator').hide();
    } else {
        // Add active class to clicked card
        $(`#filter-${status.toLowerCase().replace(' ', '-')}`).addClass('active');
        
        // Hide all rows first
        $('.student-row').addClass('filter-hidden');
        
        // Show rows with matching status
        if (status === 'Pending') {
            $('.student-row[data-status="pending"]').removeClass('filter-hidden');
        } else {
            $(`.student-row[data-status="${status.toLowerCase()}"]`).removeClass('filter-hidden');
        }
        
        // Show active filter indicator
        $('#currentFilter').text(status);
        $('#activeFilterIndicator').show();
    }
}

function clearFilter() {
    filterStudents('all');
}

// Update student data-status when attendance changes
$(document).on('change', 'input[type="radio"]', function() {
    const row = $(this).closest('.student-row');
    const newStatus = $(this).val().toLowerCase();
    row.attr('data-status', newStatus);
    
    // Reapply current filter if active
    const currentFilter = $('#currentFilter').text();
    if (currentFilter !== 'All Students') {
        filterStudents(currentFilter);
    }
});
</script>
@endsection