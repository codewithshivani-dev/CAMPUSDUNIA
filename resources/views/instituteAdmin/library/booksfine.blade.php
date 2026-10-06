@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout') 
@section('content')

<div class="container-fluid">
    <h2 class="mb-4">📚 Library Books Fine</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Fine ID</th>
                    <th>Book Issue ID</th>
                    <th>Book Title</th>
                    <th>Issued To</th>
                    <th>Days Overdue</th>
                    <th>Amount (₹)</th>
                    <th>Paid Status</th>
                    <th>Issued Date</th>
                    <th>Return Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fines as $fine)
                    <tr>
                        <td>{{ $fine->id }}</td>
                        <td>{{ $fine->bookIssue->book_issue_id }}</td>
                        <td>{{ $fine->bookIssue->libraryBook->title ?? 'N/A' }}</td>
                        <td>{{ $fine->bookIssue->issueable->name ?? 'N/A' }}</td>
                        <td>{{ $fine->days_overdue }}</td>
                        <td>{{ $fine->amount }}</td>
                        <td class="text-capitalize">{{ $fine->paid_status }}</td>
                        <td>{{ $fine->bookIssue->issue_date ?? '-' }}</td>
                        <td>{{ $fine->bookIssue->return_date ?? '-' }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">Edit</a>
                            <form action="#" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
