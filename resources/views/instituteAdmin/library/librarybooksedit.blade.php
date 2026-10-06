@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container-fluid">
    <h1>Edit Book</h1>

    <form method="POST" action="{{ route('library_books.update', $libraryBook->id) }}">
        @csrf
        @method('PUT')

        <label>Title:</label>
        <input type="text" name="title" value="{{ $libraryBook->title }}" required class="form-control"><br>

        <label>Subject:</label>
        <input type="text" name="subject" value="{{ $libraryBook->subject }}" required class="form-control"><br>

        <label>Writer Name:</label>
        <input type="text" name="writer_name" value="{{ $libraryBook->writer_name }}" class="form-control"><br>

        <label>Class:</label>
        <input type="text" name="class" value="{{ $libraryBook->class }}" class="form-control"><br>

        <label>Publishing Date:</label>
        <input type="date" name="publishing_date" value="{{ $libraryBook->publishing_date }}" class="form-control"><br>

        <label>Uploaded Date:</label>
        <input type="date" name="uploaded_date" value="{{ $libraryBook->uploaded_date }}" class="form-control"><br>

        <label>Total Copies:</label>
        <input type="number" name="total_copies" value="{{ $libraryBook->total_copies }}" min="1" class="form-control"><br>

        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
