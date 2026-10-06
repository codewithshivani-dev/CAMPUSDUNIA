@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<div class="container-fluid mt-4">
    <h2>Edit Category</h2>

    <form action="{{ route('library.category.update', $category->book_categories_id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ $category->description }}</textarea>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('library.book.category') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
