@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .container {
        max-width: 1200px;
        margin: 30px auto;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .info-card {
        background: #f8faff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
    }
    .table th {
        background-color: #007bff;
        color: white;
    }
    .select-all-container {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .loading-spinner {
        text-align: center;
        padding: 20px;
    }
</style>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="container mt-4">
    <h3 class="fw-bold mb-3">📝 Mark Attendance</h3>

    <div class="info-card">
        <div class="row mb-3">
            <div class="col-md-3">
                <label><strong>Employee:</strong></label>
                <input type="text" class="form-control" value="{{ $employee->name ?? 'N/A' }}" readonly>
            </div>
            <div class="col-md-3">
                <label><strong>Course:</strong></label>
                <input type="text" class="form-control" value="{{ $lectureData['course_type'] ?? 'N/A' }}" readonly>
            </div>
            <div class="col-md-3">
                <label><strong>Sub Type:</strong></label>
                <input type="text" class="form-control" value="{{ $lectureData['sub_type'] ?? 'N/A' }}" readonly>
            </div>
            <div class="col-md-3">
                <label><strong>Academic Year:</strong></label>
                <input type="text" class="form-control" value="{{ $lectureData['academic_year'] ?? 'N/A' }}" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label><strong>Semester:</strong></label>
                <input type="text" class="form-control" value="{{ $lectureData['semester_id'] ?? 'N/A' }}" readonly>
            </div>
            <div class="col-md-3">
                <label><strong>Lecture Date:</strong></label>
                <input type="text" class="form-control" value="{{ $lectureData['attendance_date'] ?? 'N/A' }}" readonly>
            </div>
            <div class="col-md-3">
                <label><strong>Start Time:</strong></label>
                <input type="text" class="form-control" value="{{ $lectureData['start_time'] ?? 'N/A' }}" readonly>
            </div>
            <div class="col-md-3">
                <label><strong>End Time:</strong></label>
                <input type="text" class="form-control" value="{{ $lectureData['end_time'] ?? 'N/A' }}" readonly>
            </div>
        </div>
    </div>

    <!-- Add student loading section -->
    <div class="loading-spinner mb-4" id="studentsLoading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading students...</span>
        </div>
        <p class="mt-2">Loading students list...</p>
    </div>

    <form id="attendanceForm" action="{{ route('ajax.employee.attendance.save') }}" method="POST">
        @csrf
        <input type="hidden" name="lecture_id" value="{{ $lectureData['lecture_id'] }}">
        <input type="hidden" name="subject_id" value="{{ $lectureData['subject_id'] }}">
        <input type="hidden" name="semester_id" value="{{ $lectureData['semester_id'] }}">
        <input type="hidden" name="academic_year" value="{{ $lectureData['academic_year'] }}">
        <input type="hidden" name="course_type" value="{{ $lectureData['course_type'] }}">
        <input type="hidden" name="sub_type" value="{{ $lectureData['sub_type'] }}">
        <input type="hidden" name="product_id" value="{{ $lectureData['product_id'] }}">
        <input type="hidden" name="attendance_date" value="{{ $lectureData['attendance_date'] }}">
        <input type="hidden" name="start_time" value="{{ $lectureData['start_time'] }}">
        <input type="hidden" name="end_time" value="{{ $lectureData['end_time'] }}">

        <!-- Select All Options -->
        <div class="select-all-container mb-4" id="selectAllContainer" style="display: none;">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <strong>Mark All Students:</strong>
                </div>
                <div class="col-md-9">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-success" onclick="markAll('Present')">Mark All Present</button>
                        <button type="button" class="btn btn-outline-danger" onclick="markAll('Absent')">Mark All Absent</button>
                        <button type="button" class="btn btn-outline-warning" onclick="markAll('Leave')">Mark All Leave</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearAll()">Clear All</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive" id="studentsTableContainer" style="display: none;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Registration No.</th>
                        <th>Student Name</th>
                        <th>Attendance Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody id="studentsTableBody">
                    <!-- Students will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>

        <div class="mt-4" id="actionButtons" style="display: none;">
            <button type="submit" class="btn btn-success btn-lg px-5">
                <i class="fas fa-save"></i> Save Attendance
            </button>
            <a href="{{ route('employee.attendance.index') }}" class="btn btn-secondary btn-lg px-5">
                <i class="fas fa-arrow-left"></i> Back to Calendar
            </a>
        </div>
    </form>
</div>

<script>
// Wait for jQuery to be ready
$(document).ready(function() {
    const baseUrl = "{{ url('/ajax') }}";
    const productId = "{{ $lectureData['product_id'] }}";
    
    // Load students via AJAX using existing route
    function loadStudents() {
        if (!productId) {
            $('#studentsLoading').hide();
            $('#studentsTableContainer').html('<div class="alert alert-warning">No product ID specified</div>').show();
            return;
        }
        
        console.log('Loading students for product:', productId);
        
        $.ajax({
            url: `${baseUrl}/students-by-product`,
            method: 'GET',
            data: { product_id: productId },
            success: function(response) {
                console.log('Students response:', response);
                $('#studentsLoading').hide();
                
                if (response.success && response.students && response.students.length > 0) {
                    renderStudentsTable(response.students);
                } else {
                    $('#studentsTableContainer').html('<div class="alert alert-warning">No students found for this course/branch</div>').show();
                    $('#actionButtons').show();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading students:', error);
                $('#studentsLoading').hide();
                $('#studentsTableContainer').html('<div class="alert alert-danger">Error loading students. Please try again.</div>').show();
                $('#actionButtons').show();
            }
        });
    }
    
    function renderStudentsTable(students) {
        let html = '';
        students.forEach((student, index) => {
            // Create full name
            let fullName = student.full_name || 
                          `${student.first_name || ''} ${student.middle_name || ''} ${student.last_name || ''}`.trim();
            
            html += `
            <tr>
                <td>${index + 1}</td>
                <td>${student.registration_number || 'N/A'}</td>
                <td>${fullName}</td>
                <td class="attendance-options">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" 
                            name="attendance[${student.student_hash_id}]" 
                            value="Present" id="present${student.student_hash_id}">
                        <label class="form-check-label" for="present${student.student_hash_id}">
                            Present
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" 
                            name="attendance[${student.student_hash_id}]" 
                            value="Absent" id="absent${student.student_hash_id}">
                        <label class="form-check-label" for="absent${student.student_hash_id}">
                            Absent
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" 
                            name="attendance[${student.student_hash_id}]" 
                            value="Leave" id="leave${student.student_hash_id}">
                        <label class="form-check-label" for="leave${student.student_hash_id}">
                            Leave
                        </label>
                    </div>
                </td>
                <td>
                    <input type="text" name="remarks[${student.student_hash_id}]" 
                        class="form-control form-control-sm" 
                        placeholder="Remarks">
                </td>
            </tr>`;
        });
        
        $('#studentsTableBody').html(html);
        $('#selectAllContainer').show();
        $('#studentsTableContainer').show();
        $('#actionButtons').show();
    }
    
    // Load students on page load
    loadStudents();
});

function markAll(status) {
    // Mark all radio buttons for the given status
    $('input[type="radio"]').each(function() {
        if ($(this).val() === status) {
            $(this).prop('checked', true);
        }
    });
}

function clearAll() {
    // Clear all radio buttons
    $('input[type="radio"]').prop('checked', false);
}

// Validate form before submission
$(document).on('submit', '#attendanceForm', function(e) {
    const radios = $('input[type="radio"]');
    let allMarked = true;
    
    // Group radios by student
    const studentRadios = {};
    radios.each(function() {
        const name = $(this).attr('name');
        if (name && name.startsWith('attendance[')) {
            if (!studentRadios[name]) {
                studentRadios[name] = false;
            }
            if ($(this).is(':checked')) {
                studentRadios[name] = true;
            }
        }
    });
    
    // Check if all students have attendance marked
    for (const studentId in studentRadios) {
        if (!studentRadios[studentId]) {
            allMarked = false;
            break;
        }
    }
    
    if (!allMarked) {
        e.preventDefault();
        alert('Please mark attendance for all students before saving.');
        return false;
    }
    
    // Show loading indicator on submit
    $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
    
    return true;
});
</script>
@endsection