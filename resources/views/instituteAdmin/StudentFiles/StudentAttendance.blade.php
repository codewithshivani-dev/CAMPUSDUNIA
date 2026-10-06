@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .form-container {
        max-width: 700px;
        margin: 40px auto;
        padding: 30px;
        background: #f8faff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    label {
        font-weight: 600;
    }
  
</style>
<div class="container mt-5">
    <div class="form-container">
        <h2>Mark Attendance</h2>

        <form id="attendanceForm">
            @csrf

            <!-- Employee -->
            <div class="form-group">
                <label>Employee</label>
                <select name="employee_id" id="employee_id" class="form-control" required>
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" id="course_detail_id" name="course_detail_id">

            <!-- Course -->
            <div class="form-group">
                <label>Course</label>
                <input type="text" name="course_type" id="course_type" class="form-control" readonly>
            </div>

            <!-- Sub Type -->
            <div class="form-group">
                <label>Sub Type</label>
                <input type="text" name="sub_type" id="sub_type" class="form-control" readonly>
            </div>

             <!-- Semester -->
            <div class="form-group">
                <label>Semester</label>
                <select id="semester_id" name="semester_id" class="form-control" required>
                    <option value="">-- Select Semester --</option>
                </select>
            </div>

            <!-- Subject -->
            <div class="form-group">
                <label>Subject</label>
                <select id="subject_id" name="subject_id" class="form-control" required>
                    <option value="">-- Select Subject --</option>
                </select>
            </div>

            <!-- Academic Year -->
            <div class="form-group">
                <label>Academic Year</label>
                <input type="text" name="academic_year" id="academic_year" class="form-control" readonly>
            </div>

           <!-- Lecture Date -->
            <div class="form-group">
                <label>Lecture Date</label>
                <input type="date" name="attendance_date" class="form-control" required>
            </div>

            <!-- Start Time -->
            <div class="form-group">
                <label>Lecture Start Time</label>
                <input type="time" name="start_time" id="start_time" class="form-control" required>
            </div>

            <!-- End Time -->
            <div class="form-group">
                <label>Lecture End Time</label>
                <input type="time" name="end_time" id="end_time" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Proceed to Student List</button>
        </form>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (bundle includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Load assignments when employee is selected
$('#employee_id').change(function () {
    let empId = $(this).val();
    if (!empId) return;

    $.ajax({
        url: '/attendance/assignments/' + empId,
        method: 'GET',
        success: function (response) {
            if (response.length > 0) {
                let first = response[0];

                $('#course_type').val(first.course_type);
                $('#sub_type').val(first.sub_type);
                $('#semester_id').val(first.semester_id);
                $('#academic_year').val(first.academic_year);
                $('#course_detail_id').val(first.course_detail_id); // ✅ store it

                let subjectDropdown = $('#subject_id');
                subjectDropdown.empty().append('<option value="">-- Select Subject --</option>');

                let uniqueSubjects = new Set();
                response.forEach(function (item) {
                    if (!uniqueSubjects.has(item.subject_id)) {
                        uniqueSubjects.add(item.subject_id);
                        subjectDropdown.append(`<option value="${item.subject_id}">${item.subject_name}</option>`);
                    }
                });
            }
        }
    });
});

// When employee is selected → load semesters
$('#employee_id').change(function () {
    let empId = $(this).val();
    if (!empId) return;

    $.get('/attendance/semesters/' + empId, function (response) {
        let semesterDropdown = $('#semester_id');
        semesterDropdown.empty().append('<option value="">-- Select Semester --</option>');

        response.forEach(function (item) {
            semesterDropdown.append(
                `<option value="${item.semester_id}">${item.semester_id} (${item.academic_year})</option>`
            );
        });

        // reset subjects
        $('#subject_id').empty().append('<option value="">-- Select Subject --</option>');
    });
});

// When semester is selected → load subjects
$('#semester_id').change(function() {
    let employeeId     = $('#employee_id').val();
    let semesterId     = $(this).val();
    let courseDetailId = $('#course_detail_id').val(); // ✅ fixed

    if(employeeId && semesterId && courseDetailId){
        $.ajax({
            url: `/attendance/subjects/${employeeId}/${semesterId}/${courseDetailId}`,
            method: 'GET',
            success: function(subjects) {
                $('#subject_id').empty().append('<option value="">-- Select Subject --</option>');
                subjects.forEach(function(sub){
                    $('#subject_id').append(`<option value="${sub.subject_id}">${sub.subject_name}</option>`);
                });
            }
        });
    }
});


$('#subject_id').change(function() {
    let employeeId = $('#employee_id').val();
    let semesterId = $('#semester_id').val();
    let subjectId  = $(this).val();

    if(employeeId && semesterId && subjectId){
        $.ajax({
            url: `/attendance/lectures/${employeeId}/${semesterId}/${subjectId}`,
            method: 'GET',
            success: function(lectures) {
                if(lectures.length > 0){
                    // For now, just pick the first lecture schedule
                    let lec = lectures[0];
                    $('#start_time').val(lec.start_time);
                    $('#end_time').val(lec.end_time);
                    $('input[name="attendance_date"]').val(lec.valid_from); 
                } else {
                    alert("No lectures scheduled for this subject.");
                }
            }
        });
    }
});

// Save Lecture & Fetch Students

$('#attendanceForm').submit(function(e){
    e.preventDefault();

    let formData = $(this).serialize();
    window.location.href = "/attendance/students?" + formData;
});
 
</script>
@endsection