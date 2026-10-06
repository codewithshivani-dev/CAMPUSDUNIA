@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
.error {
    color: red;
    font-size: 12px;
    margin-top: 4px;
}

.mainDiv {
    position: relative;
}

.childContent {
    position: absolute;
    color: #000;
    top: 65%;
    left: 7%;
}

.child1 {
    background: linear-gradient(90deg, #4B3F72, #F6C667);
    padding: 40px 20px;
    border-radius: 8px 8px 0 0;
    text-align: center;
    color: white;
}

.profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-top: -50px;
    border: 5px solid white;
}

.add-btn {
    margin: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    border: none;
    color: white;
    cursor: pointer;
    border-radius: 5px;
}

</style>
 <div class="mainDiv1">
        <div class="mainDiv" style="height: 270px;">
            <div class="child1" style="height: 200px;">
                <div class="childContent">
                    <img src="/images/allen-logo.webp" alt="Profile Image" class="profile-image">
                    <h3>Vignesh Ramesh</h3>
                </div>
            </div>
        </div>
    </div>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Library Books</h3>
        <a href="{{ route('library.create') }}" class="btn btn-success">+ Add New Book</a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Books Table --}}
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Book Name</th>
                    <th>Subject</th>
                    <th>Writer</th>
                    <th>Class</th>
                    <th>ID No</th>
                    <th>Publishing Date</th>
                    <th>Uploaded Date</th>
                    <th>Copies</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->subject }}</td>
                    <td>{{ $book->writer_name ?? '-' }}</td>
                    <td>{{ $book->class ?? '-' }}</td>
                    <td>{{ $book->id_no }}</td>
                    <td>{{ $book->publishing_date ? \Carbon\Carbon::parse($book->publishing_date)->format('d M, Y') : '-' }}</td>
                    <td>{{ $book->uploaded_date ? \Carbon\Carbon::parse($book->uploaded_date)->format('d M, Y') : '-' }}</td>
                    <td>{{ $book->copies }}</td>
                    <td>
                        <span class="badge {{ $book->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($book->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">No books found in library.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
