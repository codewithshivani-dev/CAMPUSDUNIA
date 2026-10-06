@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
/* Lecture Info Flex Row */
.lecture-info {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 500;
    background: #f8f9fa;
}

.lecture-info div {
    flex: 1;
    text-align: center;
    padding: 6px 10px;
}

.card {
    border-radius: 12px;
    border: 1px solid #e0e0e0;
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
}

.card-body table th {
    background: #f8f9fa;
    font-weight: 600;
    padding: 8px 12px;
}

.card-body table td {
    padding: 8px 12px;
}

/* Attendance Table */
.table-bordered th,
.table-bordered td {
    vertical-align: middle;
    text-align: center;
}

.table thead {
    background: #f1f5f9;
    font-weight: 600;
}

.table tbody tr:hover {
    background: #f9f9f9;
}

/* Attendance Radio Buttons */
td label {
    margin-right: 15px;
    font-weight: 500;
    cursor: pointer;
}

td input[type="radio"] {
    margin-right: 5px;
}

/* Remarks Textarea */
textarea.form-control {
    min-height: 35px;
    resize: vertical;
    font-size: 14px;
}

/* Save Button */
.btn-success {
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 600;
}

</style>
<div class="container">
    <h4 class="mb-3">Mark Attendance</h4>


    {{-- Show Lecture Details --}}
    <div class="lecture-info d-flex justify-content-between align-items-center mb-4 p-3 shadow-sm bg-white rounded">
    <div><strong>Date:</strong> {{ $lectureData['attendance_date'] ?? '' }}</div>
    <div><strong>Employee:</strong> {{ $employee->name }}</div>
    <div><strong>Employee:</strong> {{ $lectureData['course_type'] ?? '' }} <b>({{ $lectureData['sub_type'] ?? '' }})</b></div>
    <div><strong>Start Time:</strong> {{ $lectureData['start_time'] ?? '' }}</div>
    <div><strong>End Time:</strong> {{ $lectureData['end_time'] ?? '' }}</div>
</div>

   <form method="POST" action="{{ route('attendance.store') }}">
    @csrf

    {{-- Hidden Lecture Data --}}
    @foreach($lectureData as $key => $val)
        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
    @endforeach

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Reg. No.</th>
                <th>Student Name</th>
                <th>Attendance</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->registration_number }}</td>
                <td>{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</td>
                <td>
                    <label><input type="radio" name="attendance[{{ $student->student_hash_id }}]" value="Present" required> Present</label>
                    <label><input type="radio" name="attendance[{{ $student->student_hash_id }}]" value="Absent"> Absent</label>
                    <label><input type="radio" name="attendance[{{ $student->student_hash_id }}]" value="Leave"> Leave</label>
                    <label><input type="radio" name="attendance[{{ $student->student_hash_id }}]" value="Pending"> Pending</label>
                </td>
                <td>
                    <textarea name="remarks[{{ $student->student_hash_id }}]" placeholder="Remarks (optional)"></textarea>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Save Attendance</button>
</form>

</div>
@endsection