@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --primary-light: rgba(67, 97, 238, 0.1);
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --danger-color: #ef4444;
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --dark: #1f2937;
        --gray: #6b7280;
        --light-gray: #f9fafb;
        --border: #e5e7eb;
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    body {
        background-color: #f8fafc;
    }

    /* Navigation Bar */
    .navigation-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        color: var(--primary-color);
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        border: 2px solid var(--primary-color);
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.1);
    }

    .back-button:hover {
        background: var(--primary-gradient);
        color: white !important;
        transform: translateX(-5px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        border-color: transparent;
    }

    .back-button i {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .back-button:hover i {
        transform: translateX(-3px);
        color: white;
    }

    .form-header {
        background: var(--primary-gradient);
        padding: 20px 30px;
        border-radius: 16px 16px 0 0;
        margin-bottom: 0;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.2);
    }

    .form-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-header h1 i {
        font-size: 32px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .form-card {
        background: white;
        border-radius: 0 0 16px 16px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .form-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dark);
        letter-spacing: 0.3px;
    }

    .required-star {
        color: var(--danger-color);
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        height: 42px;
        padding: 0 14px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        background: white;
        color: var(--dark);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .form-control:hover {
        border-color: var(--secondary-color);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 25px;
        border-radius: 12px;
        border-left: 4px solid var(--primary-color);
    }

    .book-copies-container {
        display: none;
        margin-top: 30px;
    }

    .book-copies-section {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 12px;
        padding: 25px;
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .book-copies-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-gradient);
    }

    .copies-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border);
    }

    .copies-header h3 {
        margin: 0;
        color: var(--primary-color);
        font-size: 1.2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .copies-header h3 i {
        color: var(--primary-color);
    }

    .copy-badge {
        background: var(--primary-gradient);
        color: white;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
    }

    .copy-item {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .copy-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(67, 97, 238, 0.15);
        border-color: var(--primary-color);
    }

    .copy-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border);
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 12px 15px;
        border-radius: 8px;
        margin-top: -10px;
    }

    .copy-title {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .copy-id {
        font-weight: 600;
        color: var(--secondary-color);
        background: white;
        padding: 4px 14px;
        border-radius: 30px;
        font-size: 0.9rem;
        border: 1px solid var(--primary-color);
        box-shadow: 0 2px 8px rgba(67, 97, 238, 0.1);
    }

    .copy-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .date-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px;
    }

    .date-grid select {
        width: 100%;
        height: 42px;
        padding: 0 10px;
        border: 2px solid var(--border);
        border-radius: 8px;
        font-size: 0.9rem;
        background: white;
        cursor: pointer;
    }

    .date-grid select:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .form-actions {
        padding-top: 25px;
        border-top: 2px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 10px;
    }

    .btn {
        padding: 12px 28px;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: white !important;
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .btn-submit {
        background: var(--primary-gradient);
        color: white;
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
        background: linear-gradient(135deg, #3a0ca3, #4361ee);
        color: white !important;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid var(--border);
    }

    .btn-cancel:hover {
        background: var(--danger-gradient);
        color: white !important;
        border-color: transparent;
    }

    .btn-generate {
        background: var(--success-gradient);
        color: white;
        padding: 12px 24px;
        font-size: 0.95rem;
        font-weight: 600;
        position: relative;
        overflow: hidden;
    }

    .btn-generate::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-generate:hover::before {
        left: 100%;
    }

    .btn-generate:hover {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white !important;
    }

    .error-message {
        font-size: 0.8rem;
        color: var(--danger-color);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
        background: rgba(239, 68, 68, 0.1);
        padding: 6px 12px;
        border-radius: 6px;
        border-left: 3px solid var(--danger-color);
    }

    .error-message i {
        font-size: 0.9rem;
        color: var(--danger-color);
    }

    .form-control.error {
        border-color: var(--danger-color);
        background-color: rgba(239, 68, 68, 0.05);
    }

    .help-text {
        font-size: 0.8rem;
        color: var(--gray);
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .help-text i {
        color: var(--primary-color);
        font-size: 0.9rem;
    }

    .title-id-display {
        font-size: 0.95rem;
        padding: 10px 14px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 8px;
        border: 2px solid var(--border);
        font-family: monospace;
        color: var(--primary-color);
        font-weight: 600;
    }

    /* Apply All Checkbox Styles */
    .apply-all-checkbox-container {
        display: none;
        background: linear-gradient(135deg, #e8edff, #dbe4ff);
        border-radius: 10px;
        padding: 18px 20px;
        margin: 20px 0;
        border: 1px solid var(--primary-color);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    .apply-all-checkbox {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
    }

    .apply-all-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--primary-color);
        border-radius: 4px;
    }

    .apply-all-checkbox label {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-color);
        cursor: pointer;
        user-select: none;
    }

    .apply-all-checkbox label:hover {
        color: var(--secondary-color);
    }

    .apply-all-description {
        margin-top: 8px;
        font-size: 0.85rem;
        color: var(--secondary-color);
        line-height: 1.4;
        padding-left: 32px;
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--success-gradient);
        color: white;
        padding: 14px 24px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 10000;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        animation: slideIn 0.3s ease;
        font-weight: 500;
        border-left: 4px solid white;
    }

    .toast-notification i {
        font-size: 1.2rem;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }

    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-top: 3px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .navigation-bar {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start;
        }

        .back-button {
            width: 100%;
            justify-content: center;
        }

        .form-body {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            padding: 15px;
        }

        .copy-grid {
            grid-template-columns: 1fr;
        }

        .date-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .copy-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .copy-header .copy-id {
            align-self: flex-start;
        }

        .copies-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .form-header h1 {
            font-size: 24px;
        }

        .form-header h1 i {
            font-size: 28px;
        }
    }

    /* Additional enhancements */
    .bi-arrow-right-short {
        color: var(--primary-color);
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%234361ee' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
    }

    select.form-control:hover {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%233a0ca3' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    }

    /* Placeholder styling */
    ::placeholder {
        color: #a0aec0;
        opacity: 0.8;
        font-size: 0.9rem;
    }

    /* Focus styles for all interactive elements */
    .btn:focus-visible,
    .form-control:focus-visible {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
    }

    /* Hover effects for all buttons - ensure text is white */
    .btn:hover i {
        color: white !important;
    }
</style>

<div class="container-fluid">
    <!-- Navigation Bar with Back Button -->
    

    <div class="book-form-container">
        <div class="form-header">
            <h1>
                <i class="bi bi-plus-circle-fill"></i>
                Add New Book
            </h1>
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

                        <!-- Subject (Optional) -->
                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <input type="text" 
                                   name="subject" 
                                   id="subject"
                                   class="form-control {{ $errors->has('subject') ? 'error' : '' }}" 
                                   value="{{ old('subject') }}"
                                   placeholder="Enter subject">
                            @error('subject')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Class (Optional) -->
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
                            <div class="help-text">
                                <i class="bi bi-info-circle"></i>
                                Enter number of copies, then click "Generate Copy Details"
                            </div>
                            @error('total_copies')
                                <div class="error-message">
                                    <i class="bi bi-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Generate Button -->
                        <div class="form-group" style="margin-top:29px;">
                            <button type="button" id="generateCopies" class="btn btn-generate">
                                <i class="bi bi-plus-circle"></i> Generate Copy Details
                            </button>
                        </div>

                        <!-- Generated Title ID (Hidden Input) -->
                        <input type="hidden" name="librarybook_id" id="librarybook_id" value="{{ 'T' . date('Y') . strtoupper(substr(uniqid(), -6)) }}">
                    </div>

                    <!-- Dynamic Copy Details Section -->
                    <div id="bookCopiesContainer" class="book-copies-container">
                        <div class="book-copies-section">
                            <div class="copies-header">
                                <h3>
                                    <i class="bi bi-files"></i>
                                    Book Copy Details
                                </h3>
                                <span class="copy-badge" id="copyCount">0 Copies</span>
                            </div>
                            
                            <!-- Apply All Checkbox (hidden initially, shown after first copy) -->
                            <div id="applyAllContainer" class="apply-all-checkbox-container">
                                <div class="apply-all-checkbox">
                                    <input type="checkbox" id="applyAllCheckbox">
                                    <label for="applyAllCheckbox">Apply details from Copy #1 to all other copies</label>
                                </div>
                                <div class="apply-all-description">
                                    <i class="bi bi-arrow-right-short"></i>
                                    When checked, any changes made to Copy #1 fields (except Copy ID) will automatically apply to all other copies.
                                </div>
                            </div>
                            
                            <div id="copiesList">
                                <!-- Copy details will be generated here -->
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('library.data') }}" class="btn btn-cancel">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-check-circle"></i> Save All Books
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('bookForm');
        const titleInput = document.getElementById('title');
        const titleIdInput = document.getElementById('librarybook_id');
        const totalCopiesInput = document.getElementById('total_copies');
        const generateBtn = document.getElementById('generateCopies');
        const copiesContainer = document.getElementById('bookCopiesContainer');
        const copiesList = document.getElementById('copiesList');
        const copyCount = document.getElementById('copyCount');
        const applyAllContainer = document.getElementById('applyAllContainer');
        const applyAllCheckbox = document.getElementById('applyAllCheckbox');
        
        // Track book IDs to ensure uniqueness
        let usedCopyIds = new Set();
        let copyCounter = 0;
        let isApplyingToAll = false;
        
        // Generate year options
        function generateYearOptions() {
            let options = '<option value="">Year</option>';
            const currentYear = new Date().getFullYear();
            for (let year = currentYear; year >= 1900; year--) {
                options += `<option value="${year}">${year}</option>`;
            }
            return options;
        }
        
        // Generate month options
        function generateMonthOptions() {
            const months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            let options = '<option value="">Month</option>';
            months.forEach((month, index) => {
                options += `<option value="${index + 1}">${month}</option>`;
            });
            return options;
        }
        
        // Generate day options
        function generateDayOptions() {
            let options = '<option value="">Day</option>';
            for (let day = 1; day <= 31; day++) {
                options += `<option value="${day}">${day}</option>`;
            }
            return options;
        }
        
        function generateUniqueBookId() {
            let bookId;
            let attempts = 0;
            const maxAttempts = 100;
            
            do {
                const year = new Date().getFullYear();
                const random = Math.floor(1000 + Math.random() * 9000);
                bookId = `C${year}${random}`;
                attempts++;
            } while (usedCopyIds.has(bookId) && attempts < maxAttempts);
            
            // If still duplicate, add timestamp
            if (usedCopyIds.has(bookId)) {
                const timestamp = Date.now().toString().substr(-4);
                bookId = `C${new Date().getFullYear()}${timestamp}`;
            }
            
            return bookId;
        }
        
        // Generate copy details
        generateBtn.addEventListener('click', function() {
            const copies = parseInt(totalCopiesInput.value);
            
            if (!copies || copies < 1 || copies > 1000) {
                alert('Please enter a valid number of copies (1-1000)');
                return;
            }
            
            // Check if basic info is filled
            if (!titleInput.value.trim()) {
                alert('Please enter book title first');
                titleInput.focus();
                return;
            }
            
            // Clear previous copies
            copiesList.innerHTML = '';
            copyCounter = 0;
            usedCopyIds.clear();
            
            // Generate new copies
            for (let i = 1; i <= copies; i++) {
                generateCopyForm(i);
            }
            
            // Show container and update count
            copiesContainer.style.display = 'block';
            copyCount.textContent = `${copies} ${copies === 1 ? 'Copy' : 'Copies'}`;
            
            // Show "Apply to All" checkbox if more than 1 copy
            if (copies > 1) {
                applyAllContainer.style.display = 'block';
            } else {
                applyAllContainer.style.display = 'none';
            }
            
            // Scroll to copies section
            copiesContainer.scrollIntoView({ behavior: 'smooth' });
        });
        
        function generateCopyForm(copyNumber) {
            copyCounter++;
            const bookId = generateUniqueBookId();
            usedCopyIds.add(bookId);
            
            const copyHTML = `
                <div class="copy-item" data-copy-index="${copyNumber}">
                    <div class="copy-header">
                        <span class="copy-title">
                            <i class="bi bi-file-text"></i>
                            Copy #${copyNumber}
                        </span>
                        <span class="copy-id">
                            <i class="bi bi-upc-scan"></i>
                            Copy ID: ${bookId}
                        </span>
                    </div>
                    
                    <div class="copy-grid">
                        <!-- Copy ID (Editable but must be unique) -->
                        <div class="form-group">
                            <label class="form-label">
                                Copy ID
                                <span class="required-star">*</span>
                            </label>
                            <input type="text" 
                                name="copies[${copyCounter}][copy_id]" 
                                value="${bookId}"
                                class="form-control copy-id-input" 
                                data-original-id="${bookId}"
                                data-copy-index="${copyCounter}"
                                placeholder="Enter copy ID"
                                required>
                            <div class="help-text">
                                <i class="bi bi-info-circle"></i>
                                Must be unique for each copy
                            </div>
                            <div class="error-message" id="copyIdError${copyCounter}" style="display: none;">
                                <i class="bi bi-exclamation-circle"></i>
                                This ID is already used
                            </div>
                        </div>
                        
                        <!-- Publishing Date (Year, Month, Day) -->
                        <div class="form-group">
                            <label class="form-label">
                                Publishing Date
                                <span class="required-star">*</span>
                            </label>
                            <div class="date-grid">
                                <select name="copies[${copyCounter}][publish_year]" 
                                    class="form-control publish-year-select" 
                                    data-field="publish_year" 
                                    data-copy-index="${copyNumber}"
                                    required>
                                    ${generateYearOptions()}
                                </select>
                                <select name="copies[${copyCounter}][publish_month]" 
                                    class="form-control publish-month-select"
                                    data-field="publish_month"
                                    data-copy-index="${copyNumber}">
                                    ${generateMonthOptions()}
                                </select>
                                <select name="copies[${copyCounter}][publish_day]" 
                                    class="form-control publish-day-select"
                                    data-field="publish_day"
                                    data-copy-index="${copyNumber}">
                                    ${generateDayOptions()}
                                </select>
                            </div>
                        </div>
                        
                        <!-- Pages -->
                        <div class="form-group">
                            <label class="form-label">Pages</label>
                            <input type="number" 
                                name="copies[${copyCounter}][pages]" 
                                class="form-control pages-input"
                                data-field="pages"
                                data-copy-index="${copyNumber}"
                                placeholder="Number of pages"
                                min="1">
                        </div>
                        
                        <!-- Author Name -->
                        <div class="form-group">
                            <label class="form-label">Author Name</label>
                            <input type="text" 
                                name="copies[${copyCounter}][writer_name]" 
                                class="form-control author-name-input"
                                data-field="writer_name"
                                data-copy-index="${copyNumber}"
                                placeholder="Enter author name">
                        </div>
                        
                        <!-- Cost -->
                        <div class="form-group">
                            <label class="form-label">Book Price (₹)</label>
                            <input type="number" 
                                name="copies[${copyCounter}][cost]" 
                                class="form-control cost-input"
                                data-field="cost"
                                data-copy-index="${copyNumber}"
                                placeholder="Enter cost"
                                min="0"
                                step="0.01">
                        </div>
                        
                        <!-- Rack -->
                        <div class="form-group">
                            <label class="form-label">Rack Number</label>
                            <input type="text" 
                                name="copies[${copyCounter}][rack]" 
                                class="form-control rack-input"
                                data-field="rack"
                                data-copy-index="${copyNumber}"
                                placeholder="e.g., R-01">
                        </div>
                        
                        <!-- Shelf -->
                        <div class="form-group">
                            <label class="form-label">Shelf Number</label>
                            <input type="text" 
                                name="copies[${copyCounter}][shelf]" 
                                class="form-control shelf-input"
                                data-field="shelf"
                                data-copy-index="${copyNumber}"
                                placeholder="e.g., S-01">
                        </div>
                        
                        <!-- Condition -->
                        <div class="form-group">
                            <label class="form-label">Condition</label>
                            <select name="copies[${copyCounter}][condition]" 
                                    class="form-control condition-select"
                                    data-field="condition"
                                    data-copy-index="${copyNumber}">
                                <option value="new">New</option>
                                <option value="good" selected>Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                                <option value="damage">Damage</option>
                            </select>
                        </div>
                        
                        <!-- Remarks -->
                        <div class="form-group">
                            <label class="form-label">Remarks</label>
                            <input type="text" 
                                name="copies[${copyCounter}][remarks]" 
                                class="form-control remarks-input"
                                data-field="remarks"
                                data-copy-index="${copyNumber}"
                                placeholder="Any special remarks">
                        </div>
                    </div>
                </div>
            `;
            
            copiesList.insertAdjacentHTML('beforeend', copyHTML);
        }
        
        // Apply All Checkbox functionality
        applyAllCheckbox.addEventListener('change', function() {
            isApplyingToAll = this.checked;
            
            if (isApplyingToAll) {
                // When checked, copy all values from first copy to others
                copyFirstCopyToAll();
                showToast('Applied values from Copy #1 to all other copies');
            }
        });
        
        // Function to copy values from first copy to all others
        function copyFirstCopyToAll() {
            const firstCopy = document.querySelector('.copy-item[data-copy-index="1"]');
            
            if (!firstCopy) {
                return;
            }
            
            // Get all fields from first copy
            const fields = [
                { class: 'publish-year-select', type: 'select' },
                { class: 'publish-month-select', type: 'select' },
                { class: 'publish-day-select', type: 'select' },
                { class: 'pages-input', type: 'input' },
                { class: 'author-name-input', type: 'input' },
                { class: 'cost-input', type: 'input' },
                { class: 'rack-input', type: 'input' },
                { class: 'shelf-input', type: 'input' },
                { class: 'condition-select', type: 'select' },
                { class: 'remarks-input', type: 'input' }
            ];
            
            fields.forEach(field => {
                const sourceElement = firstCopy.querySelector(`.${field.class}`);
                
                if (sourceElement) {
                    const value = sourceElement.value;
                    
                    // Apply to all other copies
                    const allCopyItems = document.querySelectorAll('.copy-item');
                    allCopyItems.forEach((copyItem, index) => {
                        if (index > 0) { // Skip first copy
                            const targetElement = copyItem.querySelector(`.${field.class}`);
                            
                            if (targetElement) {
                                targetElement.value = value;
                                // Trigger change event
                                targetElement.dispatchEvent(new Event('input', { bubbles: true }));
                                targetElement.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        }
                    });
                }
            });
        }
        
        // When first copy fields change, update others if "Apply to all" is checked
        copiesList.addEventListener('input', function(e) {
            if (isApplyingToAll) {
                const target = e.target;
                const parentCopy = target.closest('.copy-item');
                
                if (parentCopy && parentCopy.getAttribute('data-copy-index') === '1') {
                    const fieldClass = Array.from(target.classList).find(cls => 
                        cls.includes('-input') || cls.includes('-select')
                    );
                    
                    if (!fieldClass || fieldClass === 'copy-id-input') return;
                    
                    const value = target.value;
                    
                    // Apply to all other copies
                    const allCopyItems = document.querySelectorAll('.copy-item');
                    allCopyItems.forEach((copyItem, index) => {
                        if (index > 0) { // Skip first copy
                            const targetElement = copyItem.querySelector(`.${fieldClass}`);
                            if (targetElement) {
                                targetElement.value = value;
                                // Trigger change event
                                targetElement.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                        }
                    });
                }
            }
        });
        
        // Validate copy ID uniqueness on input
        copiesList.addEventListener('input', function(e) {
            if (e.target.classList.contains('copy-id-input')) {
                const input = e.target;
                const copyIndex = input.dataset.copyIndex;
                const errorElement = document.getElementById(`copyIdError${copyIndex}`);
                const currentValue = input.value.trim();
                
                // Clear previous error
                input.classList.remove('error');
                if (errorElement) errorElement.style.display = 'none';
                
                // Check if empty
                if (!currentValue) {
                    return;
                }
                
                // Check for duplicates
                const allCopyIds = Array.from(document.querySelectorAll('.copy-id-input'))
                    .map(el => el.value.trim())
                    .filter(val => val !== '');
                
                const duplicates = allCopyIds.filter((id, index) => 
                    allCopyIds.indexOf(id) !== index
                );
                
                // If current value is in duplicates array
                if (duplicates.includes(currentValue)) {
                    input.classList.add('error');
                    if (errorElement) {
                        errorElement.style.display = 'flex';
                        errorElement.innerHTML = `<i class="bi bi-exclamation-circle"></i> This ID is already used by another copy`;
                    }
                }
            }
        });
        
        // Form validation
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate basic info
            if (!titleInput.value.trim()) {
                alert('Please enter book title');
                return;
            }
            
            // Check if copies are generated
            if (copyCounter === 0) {
                alert('Please generate copy details first');
                return;
            }
            
            let isValid = true;
            const copyForms = document.querySelectorAll('.copy-item');
            const copyIdSet = new Set();
            
            // Validate all copy details
            copyForms.forEach((copyForm, index) => {
                // Check required fields
                const requiredInputs = copyForm.querySelectorAll('[required]');
                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('error');
                        isValid = false;
                    }
                });
                
                // Check copy ID uniqueness
                const copyIdInput = copyForm.querySelector('.copy-id-input');
                if (copyIdInput) {
                    const copyId = copyIdInput.value.trim();
                    if (!copyId) {
                        copyIdInput.classList.add('error');
                        isValid = false;
                    } else if (copyIdSet.has(copyId)) {
                        copyIdInput.classList.add('error');
                        const copyIndex = copyIdInput.dataset.copyIndex;
                        const errorElement = document.getElementById(`copyIdError${copyIndex}`);
                        if (errorElement) {
                            errorElement.style.display = 'flex';
                            errorElement.innerHTML = `<i class="bi bi-exclamation-circle"></i> This ID is already used by another copy`;
                        }
                        isValid = false;
                    } else {
                        copyIdSet.add(copyId);
                    }
                }
            });
            
            if (!isValid) {
                alert('Please fix the errors in the form:\n1. All required fields must be filled\n2. Copy IDs must be unique\n3. Publishing Date: Year is required');
                return;
            }
            
            // Add loading state
            const submitBtn = form.querySelector('.btn-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Saving...';
            submitBtn.disabled = true;
            
            // Submit form
            this.submit();
        });
        
        // Toast notification function
        function showToast(message) {
            // Remove existing toast
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create new toast
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = `
                <i class="bi bi-check-circle-fill"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(toast);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    });
</script>

@endsection