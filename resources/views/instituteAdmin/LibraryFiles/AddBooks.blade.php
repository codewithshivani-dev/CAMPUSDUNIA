@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container mt-4">
    <h3>Add New Book</h3>
    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('library.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Book Name</label>
                        <input type="text" name="book_name" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Writter Name</label>
                        <input type="text" name="writer_name" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Class</label>
                        <input type="text" name="class" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>ID No</label>
                        <input type="text" name="id_no" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Publishing Date</label>
                        <input type="date" name="publishing_date" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Uploade Date</label>
                        <input type="date" name="uploaded_date" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Copies</label>
                        <input type="number" name="copies" class="form-control" min="1" value="1">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Save Book</button>
                <a href="{{ route('library.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection
