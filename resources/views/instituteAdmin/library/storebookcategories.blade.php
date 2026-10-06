@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 10px 30px;
        background: var(--primary-gradient);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-title i {
        font-size: 34px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .back-link {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(5px);
        transition: all 0.3s;
        text-decoration: none;
    }

    .back-link:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }

    /* Main Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        padding: 40px;
        position: relative;
        overflow: hidden;
        border: none;
        animation: fadeInUp 0.5s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--primary-gradient);
    }

    /* Form Header */
    .form-header {
        text-align: center;
        margin-bottom: 40px;
        position: relative;
    }

    .form-header-icon {
        width: 90px;
        height: 90px;
        background: var(--primary-gradient);
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: white;
        font-size: 2.5rem;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        position: relative;
        overflow: hidden;
    }

    .form-header-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .form-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .form-subtitle {
        color: #64748b;
        font-size: 1rem;
    }

    /* Alert */
    .alert {
        border: none;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 30px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert ul {
        margin-top: 10px;
        padding-left: 20px;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.8;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 30px;
        position: relative;
    }

    /* Form Labels */
    .form-label {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-label i {
        color: var(--primary-color);
        font-size: 16px;
        width: 20px;
    }

    .required::after {
        content: ' *';
        color: #ef4444;
        font-weight: 600;
    }

    /* Form Controls */
    .form-control-custom {
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 14px 18px;
        font-size: 1rem;
        transition: all 0.3s;
        background: #fff;
        width: 100%;
    }

    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
        background: #fff;
    }

    .form-control-custom:hover {
        border-color: var(--secondary-color);
    }

    .form-textarea {
        min-height: 140px;
        resize: vertical;
    }

    /* Character Count */
    .character-count {
        text-align: right;
        font-size: 12px;
        color: #94a3b8;
        margin-top: 6px;
        font-weight: 500;
    }

    .character-count.warning {
        color: #f59e0b;
    }

    .character-count.danger {
        color: #ef4444;
    }

    /* Error Message */
    .error-message {
        color: #ef4444;
        font-size: 13px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
        background: #fee2e2;
        padding: 6px 12px;
        border-radius: 8px;
        border-left: 3px solid #ef4444;
    }

    .error-message i {
        font-size: 14px;
    }

    /* Preview Card */
    .preview-card {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 25px;
        margin-top: 20px;
        transition: all 0.3s;
    }

    .preview-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
    }

    .preview-title {
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 10px;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px dashed var(--border-color);
        padding-bottom: 12px;
    }

    .preview-title i {
        font-size: 20px;
    }

    .preview-description {
        color: #475569;
        font-size: 14px;
        line-height: 1.6;
        padding: 5px 0;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: end;
        align-items: center;
    }

    .btn-back {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border: 2px solid var(--border-color);
        border-radius: 14px;
        padding: 12px 30px;
        font-weight: 600;
        color: #475569;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 15px;
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        color: #1e293b;
        text-decoration: none;
    }

    .btn-submit {
        background: var(--primary-gradient);
        border: none;
        border-radius: 14px;
        padding: 12px 40px;
        font-weight: 600;
        color: white;
        transition: all 0.3s;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-submit:hover::before {
        left: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.4);
    }

    .btn-submit:active {
        transform: translateY(-1px);
    }

    .btn-submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .form-container {
            margin: 20px auto;
        }

        .form-card {
            padding: 30px 20px;
        }
        
        .btn-back, .btn-submit {
            width: 100%;
            justify-content: center;
        }

        .form-header-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }

        .form-title {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 480px) {
        .form-card {
            padding: 25px 15px;
        }

        .form-title {
            font-size: 1.5rem;
        }

        .form-subtitle {
            font-size: 0.9rem;
        }
    }
</style>

<div class="form-container">
    <!-- Page Header -->
    <div class="page-header">
        <!--<h1 class="page-title">-->
        <!--    <i class="bi bi-bookmarks-fill"></i>-->
        <!--    Create New Category-->
        <!--</h1>-->
        <div>
            <h1 class="page-title">
                <i class="bi bi-journal-bookmark-fill"></i>
                {{ isset($category) ? 'Edit Category' : 'Create New Category' }}
            </h1>
            <p class="form-subtitle text-white">
                {{ isset($category) ? 'Update your book category information below' : 'Fill in the details below to add a new book category' }}
            </p>
        </div>
        <a href="{{ route('library.book.category') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Categories
        </a>
    </div>

    <!-- Main Form Card -->
    <div class="form-card">
        <div class="form-header d-none">
            <div class="form-header-icon">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>
            <h1 class="form-title">
                {{ isset($category) ? 'Edit Category' : 'Create New Category' }}
            </h1>
            <p class="form-subtitle">
                {{ isset($category) ? 'Update your book category information below' : 'Fill in the details below to add a new book category' }}
            </p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex">
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <i class="bi bi-tag-fill"></i>
                    <span class="required">Category Name</span>
                </label>
                <input type="text" 
                       name="name" 
                       class="form-control form-control-custom" 
                       value="{{ old('name', $category->name ?? '') }}" 
                       placeholder="e.g., Fiction, Science, History, Biography" 
                       required
                       maxlength="100"
                       id="categoryName">
                <div class="character-count">
                    <span id="nameCharCount">0</span>/100 characters
                </div>
                @error('name')
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-text-paragraph"></i>
                    Description
                </label>
                <textarea name="description" 
                          class="form-control form-control-custom form-textarea" 
                          placeholder="Enter a detailed description of this category (optional)"
                          rows="5"
                          maxlength="500"
                          id="categoryDescription">{{ old('description', $category->description ?? '') }}</textarea>
                <div class="character-count">
                    <span id="descCharCount">0</span>/500 characters
                </div>
                @error('description')
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Preview Section -->
            <div class="preview-card" id="previewSection" style="display: none;">
                <div class="preview-title">
                    <i class="bi bi-eye-fill"></i>
                    <span id="previewName">Category Name</span>
                </div>
                <div class="preview-description" id="previewDescription">Category description will appear here...</div>
            </div>

            <div class="form-actions">
                <a href="{{ route('library.book.category') }}" class="btn-back d-none">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <button type="submit" class="btn-submit">
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
    });
</script>

@endsection