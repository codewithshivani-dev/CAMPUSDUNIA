@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<div class="container mt-5">
    <h2 class="mb-4">Student Attendance Report</h2>

    <!-- Filters -->
    <form method="GET" action="{{ route('attendance.report') }}" class="form-row mb-4">
        <div class="form-group col-md-3">
            <label>From Date</label>
            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>
        <div class="form-group col-md-3">
            <label>To Date</label>
            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>
        <div class="form-group col-md-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">-- All --</option>
                <option value="Present" {{ request('status')=='Present'?'selected':'' }}>Present</option>
                <option value="Absent" {{ request('status')=='Absent'?'selected':'' }}>Absent</option>
                <option value="Leave" {{ request('status')=='Leave'?'selected':'' }}>Leave</option>
                <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
            </select>
        </div>
        <div class="form-group col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </div>
    </form>

    <!-- Students Summary Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Reg. No.</th>
                <th>Name</th>
                <th>Total Present</th>
                <th>Total Absent</th>
                <th>Total Leave</th>
                <th>Total Pending</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $stu)
            <tr>
                <td>{{ $stu->registration_number }}</td>
                <td>{{ $stu->first_name }} {{ $stu->last_name }}</td>
                <td>{{ $stu->present_count }}</td>
                <td>{{ $stu->absent_count }}</td>
                <td>{{ $stu->leave_count }}</td>
                <td>{{ $stu->pending_count }}</td>
                <td>
                    <button class="btn btn-info btn-sm student-link" data-id="{{ $stu->student_id }}">
                        <i class="fas fa-eye"></i> View
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">No Records Found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Slide-in Panel -->
<div id="slidePanel">
    <div class="slide-panel-header">
        <h5>Student Attendance Details</h5>
        <button type="button" id="closeSlidePanel">&times;</button>
    </div>
    <div class="slide-panel-body" id="student-details-body">
        <p>Select a student to see details...</p>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
/* Slide Panel CSS */
#slidePanel {
    position: fixed;
    top: 0;
    right: -500px; /* hidden initially */
    width: 500px;
    height: 100%;
    background: #fff;
    box-shadow: -3px 0 10px rgba(0,0,0,0.3);
    z-index: 1050;
    transition: right 0.3s ease;
    overflow-y: auto;
    border-left: 1px solid #ddd;
}

#slidePanel.open {
    right: 0;
}

.slide-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid #ddd;
    background: #f8f9fa;
}

.slide-panel-header h5 {
    margin: 0;
}

.slide-panel-header button {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
}

.slide-panel-body {
    padding: 15px;
}
</style>

<script>
$(document).ready(function(){

    // Open slide panel and load details
    $(document).on('click', '.student-link', function(){
        let studentId = $(this).data('id');
        $('#student-details-body').html('<p>Loading...</p>');
        $('#slidePanel').addClass('open');

        $.get("/attendance/student-details/"+studentId, function(data){
            if(data.success){
                let stu = data.student;
                let records = data.records;

                let html = `<h5>${stu.first_name} ${stu.last_name} (${stu.registration_number})</h5>`;
                html += `<table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Date</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Lecture Time</th>
                              </tr>
                            </thead><tbody>`;

                if(records.length > 0){
                    records.forEach(function(r){
                        html += `<tr>
                                   <td>${r.date}</td>
                                   <td>${r.subject_name}</td>
                                   <td>${r.status}</td>
                                   <td>${r.remarks ?? ''}</td>
                                   <td>${r.start_time} - ${r.end_time}</td>
                                 </tr>`;
                    });
                } else {
                    html += `<tr><td colspan="5">No attendance found.</td></tr>`;
                }
                html += `</tbody></table>`;

                $('#student-details-body').html(html);
            } else {
                $('#student-details-body').html('<p>No data available</p>');
            }
        });
    });

    // Close slide panel
    $('#closeSlidePanel').click(function(){
        $('#slidePanel').removeClass('open');
    });

});
</script>

@endsection
