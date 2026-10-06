@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --primary: #456bd9;
        --primary-dark: #3652b3;
        --secondary: #6c757d;
        --success: #28a745;
        --warning: #ffc107;
        --danger: #dc3545;
        --light: #f8f9fa;
        --dark: #343a40;
    }

    .form-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 40px 0;
    }

    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 40px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(69, 107, 217, 0.1);
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #456bd9 0%, #3652b3 100%);
    }

    .form-header {
        text-align: center;
        margin-bottom: 40px;
        position: relative;
    }

    .form-header-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #456bd9 0%, #3652b3 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: white;
        font-size: 2rem;
        box-shadow: 0 8px 20px rgba(69, 107, 217, 0.3);
    }

    .form-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .form-subtitle {
        color: var(--secondary);
        font-size: 1rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label i {
        color: var(--primary);
        width: 20px;
    }

    .required::after {
        content: ' *';
        color: var(--danger);
    }

    .form-control-custom {
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #fff;
    }

    .form-control-custom:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(69, 107, 217, 0.1);
        background: #fff;
    }

    .form-control-custom:hover {
        border-color: #b0b0b0;
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-top: 25px;
        border-top: 1px solid #eee;
    }

    .btn-back {
        border-radius: 12px;
        padding: 12px 30px;
        font-weight: 600;
        border: 2px solid var(--secondary);
        color: var(--secondary);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        background: var(--secondary);
        color: white;
        transform: translateY(-2px);
    }

    .btn-submit {
        background: linear-gradient(135deg, #456bd9 0%, #3652b3 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 40px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(69, 107, 217, 0.2);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(69, 107, 217, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .error-message {
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .error-message i {
        font-size: 0.75rem;
    }

    .form-group {
        margin-bottom: 30px;
        position: relative;
    }

    .character-count {
        text-align: right;
        font-size: 0.8rem;
        color: var(--secondary);
        margin-top: 5px;
    }

    .character-count.warning {
        color: var(--warning);
    }

    .character-count.danger {
        color: var(--danger);
    }

    .preview-card {
        background: var(--light);
        border-radius: 12px;
        padding: 20px;
        margin-top: 10px;
        border-left: 4px solid var(--primary);
    }

    .preview-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .preview-description {
        color: var(--secondary);
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
        
        .form-card {
            padding: 30px 20px;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 15px;
        }
        
        .btn-back, .btn-submit {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <div class="form-header-icon">
                <i class="bi bi-journal-bookmark"></i>
            </div>
            <h1 class="form-title">
                {{ isset($category) ? 'Edit Category' : 'Create New Category' }}
            </h1>
            <p class="form-subtitle">
                {{ isset($category) ? 'Update your book category information' : 'Fill in the details to add a new book category' }}
            </p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 1.2rem;"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ isset($category) ? route('library.category.update', $category->book_categories_id) : route('library.category.store') }}" 
              method="POST" id="categoryForm">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-tag"></i>
                    <span class="required">Category Name</span>
                </label>
                <input type="text" 
                       name="name" 
                       class="form-control form-control-custom" 
                       value="{{ old('name', $category->name ?? '') }}" 
                       placeholder="Enter category name (e.g., Fiction, Science, History)" 
                       required
                       maxlength="100"
                       id="categoryName">
                <div class="character-count">
                    <span id="nameCharCount">0</span>/100 characters
                </div>
                @error('name')
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-text-left"></i>
                    Description
                </label>
                <textarea name="description" 
                          class="form-control form-control-custom form-textarea" 
                          placeholder="Enter category description (optional)"
                          rows="5"
                          maxlength="500"
                          id="categoryDescription">{{ old('description', $category->description ?? '') }}</textarea>
                <div class="character-count">
                    <span id="descCharCount">0</span>/500 characters
                </div>
                @error('description')
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Preview Section -->
            <div class="preview-card" id="previewSection" style="display: none;">
                <div class="preview-title" id="previewName">Category Name</div>
                <div class="preview-description" id="previewDescription">Category description will appear here...</div>
            </div>

            <div class="form-actions">
                <a href="{{ route('library.book.category') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Back to Categories
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="bi bi-check-lg"></i>
                    {{ isset($category) ? 'Update Category' : 'Create Category' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('categoryName');
        const descInput = document.getElementById('categoryDescription');
        const nameCharCount = document.getElementById('nameCharCount');
        const descCharCount = document.getElementById('descCharCount');
        const previewSection = document.getElementById('previewSection');
        const previewName = document.getElementById('previewName');
        const previewDescription = document.getElementById('previewDescription');

        // Update character counts
        function updateCharCount() {
            nameCharCount.textContent = nameInput.value.length;
            descCharCount.textContent = descInput.value.length;
            
            // Update preview
            if (nameInput.value.trim() || descInput.value.trim()) {
                previewSection.style.display = 'block';
                previewName.textContent = nameInput.value || 'Category Name';
                previewDescription.textContent = descInput.value || 'No description provided';
            } else {
                previewSection.style.display = 'none';
            }
            
            // Add warning class for near limit
            if (nameInput.value.length > 90) {
                nameCharCount.parentElement.classList.add('warning');
            } else {
                nameCharCount.parentElement.classList.remove('warning');
            }
            
            if (descInput.value.length > 450) {
                descCharCount.parentElement.classList.add('warning');
            } else {
                descCharCount.parentElement.classList.remove('warning');
            }
        }

        // Initialize counts
        updateCharCount();

        // Add event listeners
        nameInput.addEventListener('input', updateCharCount);
        descInput.addEventListener('input', updateCharCount);

        // Form validation
        const form = document.getElementById('categoryForm');
        form.addEventListener('submit', function(e) {
            if (nameInput.value.trim().length === 0) {
                e.preventDefault();
                alert('Please enter a category name');
                nameInput.focus();
                return false;
            }
            
            if (nameInput.value.trim().length > 100) {
                e.preventDefault();
                alert('Category name must not exceed 100 characters');
                nameInput.focus();
                return false;
            }
            
            if (descInput.value.length > 500) {
                e.preventDefault();
                alert('Description must not exceed 500 characters');
                descInput.focus();
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('.btn-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
            submitBtn.disabled = true;
            
            // Re-enable button after 3 seconds if form doesn't submit
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        // Add animation to form
        const formCard = document.querySelector('.form-card');
        formCard.style.opacity = '0';
        formCard.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            formCard.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            formCard.style.opacity = '1';
            formCard.style.transform = 'translateY(0)';
        }, 100);
    });
</script>

@endsection