@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white text-center py-3 rounded-top-4">
            <h4 class="mb-0">📤 Upload Bulk Students Data</h4>
        </div>

        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('student.ulpoad.data') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label>Department</label>
                    <select name="department_id" id="department_id" class="form-control" required>
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}">{{ $dept->department }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label>Course Type</label>
                    <select name="course_type" id="course_type" class="form-control" required>
                        <option value="">-- Select Course Type --</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label>Sub Type</label>
                    <select name="course_detail_id" id="sub_type" class="form-control" required>
                        <option value="">-- Select Sub Type --</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="csv_file" class="form-label fw-semibold">Select File:</label>
                    <input type="file" name="csv_file" id="csv_file" class="form-control form-control-lg" accept=".csv,.txt" required>
                </div>
                <div class="form-text text-muted mt-2">
                    <strong>Note:</strong> Download the CSV file first, then enter the information in the designated fields. and upload the file that was downloaded once all the data has been added.<a href="" type="button">Download CSV File</a>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-upload"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(session('data'))
        <div class="card mt-4 shadow-sm border-0 rounded-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Preview CSV Data</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    @foreach (session('data') as $row)
                        <tr>
                            @foreach ($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#department_id').change(function(){
    let deptId = $(this).val();
    $('#course_type').html('<option value="">-- Select Course Type --</option>');
    $('#sub_type').html('<option value="">-- Select Sub Type --</option>');
    if(deptId){
        // :two: Load course types
        $.get('/get-course-types-by-department/' + deptId, function(data){
            $.each(data, function(_, c){
                $('#course_type').append('<option value="'+c.course_type+'">'+c.course_type+'</option>');
            });
        });
    }
});
// :white_check_mark: When Course Type Changes
$('#course_type').change(function(){
    let courseType = $(this).val();
    let deptId = $('#department_id').val();
    if(courseType && deptId){
        $.post('/get-subtypesforassignment', {
            department_id: deptId,
            course_type: courseType,
            _token: '{{ csrf_token() }}'
        }, function(data){
            $('#sub_type').html('<option value="">-- Select Sub Type --</option>');
            $.each(data, function(_, s){
                $('#sub_type').append('<option value="'+s.product_id+'">'+s.sub_type+'</option>');
            });
        });
    }
});
</script>
@endsection
