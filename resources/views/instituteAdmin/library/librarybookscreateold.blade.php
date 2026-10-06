@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    :root {
        --primary: #456bd9;
        --primary-light: #e8edff;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark: #1f2937;
        --gray: #6b7280;
        --light-gray: #f9fafb;
        --border: #e5e7eb;
    }

    

    .form-header {
        background: white;
        padding: 20px;
        border-radius: 10px 10px 0 0;
        border-bottom: 1px solid var(--border);
        margin-bottom: 0;
    }

    .form-header h1 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card {
        background: white;
        border-radius: 0 0 10px 10px;
        border: 1px solid var(--border);
        overflow: hidden;
    }

    .form-body {
        padding: 20px;
    }


    .form-group {
        margin-bottom: 20;
    }

    .form-label {
        display: block;
        margin-bottom: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--dark);
    }

    .required-star {
        color: var(--danger);
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        height: 40px;
        padding: 0 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.9rem;
        background: white;
        color: var(--dark);
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(69, 107, 217, 0.1);
    }

    .form-control[type="date"] {
        padding-right: 8px;
    }

    .form-control[type="number"] {
        padding-right: 8px;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 35px;
    }

    .form-actions {
        padding-top: 20px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-submit {
        background: var(--primary);
        color: white;
    }

    .btn-submit:hover {
        background: #3a5bc4;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(69, 107, 217, 0.2);
    }

    .btn-cancel {
        background: var(--light-gray);
        color: var(--gray);
        border: 1px solid var(--border);
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        color: var(--dark);
    }

    /* Validation error styling */
    .error-message {
        font-size: 0.8rem;
        color: var(--danger);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .error-message i {
        font-size: 0.7rem;
    }

    .form-control.error {
        border-color: var(--danger);
    }

    .form-control.error:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    /* Help text */
    .help-text {
        font-size: 0.8rem;
        color: var(--gray);
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .book-form-container {
            padding: 0 10px;
            margin: 10px auto;
        }
        
        .form-header {
            padding: 15px;
        }
        
        .form-body {
            padding: 15px;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .form-header h1 {
            font-size: 1.25rem;
        }
    }
</style>

<div class="book-form-container">
    <div class="form-header">
        <h1><i class="bi bi-plus-circle"></i> Add New Book</h1>
    </div>
    
    <div class="form-card">
        <div class="form-body">
            <form method="POST" action="{{ route('library.store.books') }}" id="bookForm">
                @csrf
                
                <div class="form-grid">
                    <!-- Book Category -->
                    <div class="form-group">
                        <label class="form-label">
                            Book Category
                            <span class="required-star">*</span>
                        </label>
                        <select name="book_categories_id" id="book_category" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($bookcategories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ old('book_categories_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('book_categories_id')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Book Title -->
                    <div class="form-group">
                        <label class="form-label">
                            Book Title
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title"
                               class="form-control {{ $errors->has('title') ? 'error' : '' }}" 
                               value="{{ old('title') }}"
                               placeholder="Enter book title"
                               required>
                        @error('title')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div class="form-group">
                        <label class="form-label">
                            Subject
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" 
                               name="subject" 
                               id="subject"
                               class="form-control {{ $errors->has('subject') ? 'error' : '' }}" 
                               value="{{ old('subject') }}"
                               placeholder="Enter subject"
                               required>
                        @error('subject')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Writer Name -->
                    <div class="form-group">
                        <label class="form-label">Writer Name</label>
                        <input type="text" 
                               name="writer_name" 
                               id="writer_name"
                               class="form-control {{ $errors->has('writer_name') ? 'error' : '' }}" 
                               value="{{ old('writer_name') }}"
                               placeholder="Enter writer's name">
                        @error('writer_name')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Class -->
                    <div class="form-group">
                        <label class="form-label">Class</label>
                        <input type="text" 
                               name="class" 
                               id="class"
                               class="form-control {{ $errors->has('class') ? 'error' : '' }}" 
                               value="{{ old('class') }}"
                               placeholder="e.g., 10th, 12th">
                        @error('class')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Publishing Date -->
                    <div class="form-group">
                        <label class="form-label">Publishing Date</label>
                        <input type="date" 
                               name="publishing_date" 
                               id="publishing_date"
                               class="form-control {{ $errors->has('publishing_date') ? 'error' : '' }}" 
                               value="{{ old('publishing_date') }}">
                        <div class="help-text">Date when the book was published</div>
                        @error('publishing_date')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Uploaded Date -->
                    <div class="form-group">
                        <label class="form-label">Uploaded Date</label>
                        <input type="date" 
                               name="uploaded_date" 
                               id="uploaded_date"
                               class="form-control {{ $errors->has('uploaded_date') ? 'error' : '' }}" 
                               value="{{ old('uploaded_date', date('Y-m-d')) }}">
                        <div class="help-text">Date when book is added to library</div>
                        @error('uploaded_date')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Total Copies -->
                    <div class="form-group">
                        <label class="form-label">
                            Total Copies
                            <span class="required-star">*</span>
                        </label>
                        <input type="number" 
                               name="total_copies" 
                               id="total_copies"
                               class="form-control {{ $errors->has('total_copies') ? 'error' : '' }}" 
                               value="{{ old('total_copies', 1) }}"
                               min="1"
                               max="1000"
                               required>
                        <div class="help-text">Number of copies available</div>
                        @error('total_copies')
                            <div class="error-message">
                                <i class="bi bi-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ url()->previous() }}" class="btn btn-cancel">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="bi bi-check-circle"></i> Save Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookForm');
    const titleInput = document.getElementById('title');
    const subjectInput = document.getElementById('subject');
    const totalCopiesInput = document.getElementById('total_copies');
    const publishingDateInput = document.getElementById('publishing_date');
    const uploadedDateInput = document.getElementById('uploaded_date');
    
    // Set default uploaded date to today
    if (!uploadedDateInput.value) {
        const today = new Date().toISOString().split('T')[0];
        uploadedDateInput.value = today;
    }
    
    // Validate form on submit
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validate required fields
        const requiredFields = [titleInput, subjectInput, totalCopiesInput];
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        // Validate total copies
        if (totalCopiesInput.value < 1 || totalCopiesInput.value > 1000) {
            totalCopiesInput.classList.add('error');
            isValid = false;
        }
        
        // Validate dates (optional)
        if (publishingDateInput.value) {
            const publishDate = new Date(publishingDateInput.value);
            if (publishDate > new Date()) {
                publishingDateInput.classList.add('error');
                alert('Publishing date cannot be in the future');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill all required fields correctly');
            return false;
        }
        
        // Add loading state to submit button
        const submitBtn = form.querySelector('.btn-submit');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
        submitBtn.disabled = true;
        
        // Re-enable after 3 seconds if form doesn't submit
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
    
    // Real-time validation for total copies
    totalCopiesInput.addEventListener('input', function() {
        if (this.value < 1 || this.value > 1000) {
            this.classList.add('error');
        } else {
            this.classList.remove('error');
        }
    });
    
    // Real-time validation for required fields
    [titleInput, subjectInput].forEach(field => {
        field.addEventListener('input', function() {
            if (!this.value.trim()) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });
    });
});
</script>

@endsection