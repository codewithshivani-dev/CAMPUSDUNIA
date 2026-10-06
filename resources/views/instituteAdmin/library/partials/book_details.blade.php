<div class="book-details">
    <div class="row">
        <div class="col-md-6">
            <div class="detail-item mb-3">
                <strong>Book ID:</strong> {{ $book->librarybook_id }}
            </div>
            <div class="detail-item mb-3">
                <strong>Title:</strong> {{ $book->title }}
            </div>
            <div class="detail-item mb-3">
                <strong>Subject:</strong> {{ $book->subject }}
            </div>
            <div class="detail-item mb-3">
                <strong>Category:</strong> {{ $book->category->name ?? 'N/A' }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="detail-item mb-3">
                <strong>Class:</strong> {{ $book->class }}
            </div>
            <div class="detail-item mb-3">
                <strong>Total Copies:</strong> {{ $book->total_copies }}
            </div>
            <div class="detail-item mb-3">
                <strong>Uploaded Date:</strong> {{ $book->uploaded_date }}
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <h6>Copy Information:</h6>
        <ul class="list-group">
            <li class="list-group-item">
                <strong>Available Copies:</strong> {{ $book->available_copies ?? 0 }}
            </li>
            <li class="list-group-item">
                <strong>Issued Copies:</strong> {{ $book->total_copies - ($book->available_copies ?? 0) }}
            </li>
        </ul>
    </div>
</div>