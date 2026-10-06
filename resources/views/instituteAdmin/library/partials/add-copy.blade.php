@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Add New Copy - {{ $book->title }}</title>
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
    --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    --hover-shadow: 0 15px 40px rgba(67, 97, 238, 0.12);
}

.container-fluid {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
   
}

/* Compact Book Header - Enhanced */
.book-header {
    background: var(--primary-gradient);
    padding: 1.5rem 2rem;
    border-radius: 20px;
    color: white;
    margin-bottom: 1.5rem;
    box-shadow: var(--card-shadow);
    position: relative;
    overflow: hidden;
}


/* Breadcrumb Styling */
.breadcrumb-nav {
    background: transparent;
    margin-bottom: 0.75rem;
    position: relative;
    z-index: 1;
}

.breadcrumb-custom {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1.25rem;
    border-radius: 40px;
    display: inline-flex;
    font-size: 0.85rem;
}

.breadcrumb-custom .breadcrumb-item a {
    color: rgba(255,255,255,0.95);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
}

.breadcrumb-custom .breadcrumb-item a:hover {
    color: white;
    text-decoration: none;
    transform: translateX(2px);
}

.breadcrumb-custom .breadcrumb-item.active {
    color: white;
    font-weight: 600;
}

.breadcrumb-custom .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255,255,255,0.7);
    content: "›";
    font-size: 1.2rem;
}

/* Book Title Section - Enhanced */
.book-title-section {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-top: 0.5rem;
    position: relative;
    z-index: 1;
}

.book-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    backdrop-filter: blur(5px);
    transition: all 0.3s;
}

.book-icon:hover {
    transform: scale(1.05);
    background: rgba(255,255,255,0.3);
}

.book-details {
    flex: 1;
}

.book-details h1 {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
    letter-spacing: -0.5px;
}

.book-meta {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    opacity: 0.95;
    flex-wrap: wrap;
}

.book-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.15);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    backdrop-filter: blur(5px);
}

.book-meta i {
    font-size: 0.9rem;
}

/* Card Design - Enhanced */
.copy-card {
    background: white;
    border: none;
    border-radius: 24px;
    box-shadow: var(--card-shadow);
    transition: all 0.3s ease;
    overflow: hidden;
}

.copy-card:hover {
    box-shadow: var(--hover-shadow);
    transform: translateY(-2px);
}

.copy-card .card-header {
    background: linear-gradient(135deg, #ffffff, #f8fafc);
    border-bottom: 2px solid #e2e8f0;
    padding: 1.25rem 1.75rem;
}

.copy-card .card-body {
    padding: 1.75rem;
}

/* Form Elements - Enhanced */
.form-group-modern {
    margin-bottom: 1.5rem;
    position: relative;
}

.form-label-modern {
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #1e293b;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.required-star {
    color: #ef4444;
    font-size: 1rem;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 14px;
    color: var(--primary-color);
    font-size: 1.1rem;
    z-index: 1;
    transition: all 0.3s;
}

.form-control-modern {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #ffffff;
}

.form-control-modern:focus {
    border-color: var(--primary-color);
    box-shadow: var(--accent-glow);
    outline: none;
    transform: translateY(-1px);
}

.form-control-modern:hover {
    border-color: var(--primary-color);
}

/* Date Grid - Enhanced */
.date-grid-modern {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 15px;
}

.date-select {
    padding: 0.75rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.date-select:focus {
    border-color: var(--primary-color);
    box-shadow: var(--accent-glow);
    outline: none;
}

.date-select:hover {
    border-color: var(--primary-color);
}

/* Action Buttons - Enhanced */
.btn-modern {
    padding: 0.7rem 1.75rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border: none;
    cursor: pointer;
    letter-spacing: 0.3px;
}

.btn-primary-modern {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
}

.btn-primary-modern:active {
    transform: translateY(0);
}

.btn-outline-modern {
    background: white;
    border: 2px solid #e2e8f0;
    color: #1e293b;
}

.btn-outline-modern:hover {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

/* Grid System */
.row-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1.5rem;
}

/* Helper Text - Enhanced */
.helper-text {
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.helper-text i {
    font-size: 0.7rem;
    color: var(--primary-color);
}

/* Error Messages */
.text-danger {
    font-size: 0.75rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Header Icon Box */
.header-icon-box {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.header-icon-box i {
    color: var(--primary-color);
    font-size: 1.3rem;
}

/* Responsive */
@media (max-width: 1024px) {
    .row-modern {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .book-header {
        padding: 1.25rem;
    }
    
    .row-modern {
        grid-template-columns: 1fr;
    }
    
    .date-grid-modern {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .book-title-section {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .book-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .book-details h1 {
        font-size: 1.3rem;
    }
    
    .book-meta {
        gap: 0.75rem;
    }
    
    .copy-card .card-header {
        padding: 1rem 1.25rem;
    }
    
    .copy-card .card-body {
        padding: 1.25rem;
    }
    
    .btn-modern {
        padding: 0.6rem 1.25rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease forwards;
}

.animate-slide-in {
    animation: slideInLeft 0.4s ease forwards;
}

/* Loading State */
.btn-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Select dropdown styling */
select.form-control-modern,
select.date-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%234361ee' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 2rem;
}

/* Textarea specific styling */
textarea.form-control-modern {
    resize: vertical;
    min-height: 80px;
}

/* Focus visible outline */
.form-control-modern:focus-visible,
.date-select:focus-visible {
    outline: none;
}
</style>

<div class="container-fluid">
    <!-- Compact Book Header -->
    <div class="book-header animate-fade-in">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item">
                    <a href="{{ route('library.data') }}">
                        <i class="bi bi-collection me-1"></i> Books
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('library.books.copies', $book->librarybook_id) }}">
                        <i class="bi bi-files me-1"></i> Copies
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <i class="bi bi-plus-circle me-1"></i> Add New Copy
                </li>
            </ol>
        </nav>
        
        <div class="book-title-section">
            <div class="book-icon">
                <i class="bi bi-book-fill"></i>
            </div>
            <div class="book-details">
                <h1>Add New Copy</h1>
                <div class="book-meta">
                    <span><i class="bi bi-book"></i> {{ $book->title }}</span>
                    <span><i class="bi bi-upc-scan"></i> ID: {{ $book->librarybook_id }}</span>
                    <span><i class="bi bi-person"></i> {{ $book->writer_name ?? 'Not specified' }}</span>
                    <span><i class="bi bi-tag"></i> {{ $book->category->name ?? 'General' }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="copy-card animate-fade-in" style="animation-delay: 0.1s;">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-box">
                            <i class="bi bi-file-earmark-plus"></i>
                        </div>
                        <div>
                            <h6 class="mb-0" style="font-weight: 700; color: #1e293b;">Copy Information</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Fill in the details for the new book copy</small>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('library.books.store-copy', $book->librarybook_id) }}" method="POST">
                        @csrf
                        
                        <div class="row-modern">
                            <!-- Copy ID -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-upc-scan"></i>
                                    <span>Copy ID</span>
                                    <span class="required-star">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="bi bi-upc-scan input-icon"></i>
                                    <input type="text" name="copy_id" class="form-control-modern" 
                                           value="{{ old('copy_id') }}"
                                           placeholder="e.g., CPY-001" required>
                                </div>
                                <div class="helper-text">
                                    <i class="bi bi-info-circle"></i>
                                    Must be unique for this book
                                </div>
                                @error('copy_id')
                                    <div class="text-danger small mt-1">
                                        <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Author -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-pencil-fill"></i>
                                    <span>Author</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="bi bi-pencil-fill input-icon"></i>
                                    <input type="text" name="writer_name" class="form-control-modern" 
                                           value="{{ old('writer_name', $book->writer_name) }}"
                                           placeholder="Enter author name">
                                </div>
                            </div>
                            
                            <!-- Cost -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-currency-rupee"></i>
                                    <span>Book Price</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="bi bi-currency-rupee input-icon"></i>
                                    <input type="number" name="cost" class="form-control-modern" 
                                           value="{{ old('cost') }}"
                                           min="0" step="0.01" placeholder="e.g., 450.00">
                                </div>
                            </div>
                            
                            <!-- Pages -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-file-text"></i>
                                    <span>Pages</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="bi bi-file-text input-icon"></i>
                                    <input type="number" name="pages" class="form-control-modern" 
                                           value="{{ old('pages') }}"
                                           min="1" placeholder="e.g., 250">
                                </div>
                            </div>
                            
                            <!-- Condition -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-clipboard-check"></i>
                                    <span>Condition</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="bi bi-clipboard-check input-icon"></i>
                                    <select name="condition" class="form-control-modern">
                                        <option value="">Select Condition</option>
                                        <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>🆕 New</option>
                                        <option value="good" {{ old('condition', 'good') == 'good' ? 'selected' : '' }}>✅ Good</option>
                                        <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>📖 Fair</option>
                                        <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>⚠️ Poor</option>
                                        <option value="damage" {{ old('condition') == 'damage' ? 'selected' : '' }}>🔧 Damaged</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Rack -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-grid-3x3-gap-fill"></i>
                                    <span>Rack Number</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="bi bi-grid-3x3-gap-fill input-icon"></i>
                                    <input type="text" name="rack" class="form-control-modern" 
                                           value="{{ old('rack') }}"
                                           placeholder="e.g., A-12">
                                </div>
                            </div>
                            
                            <!-- Shelf -->
                            <div class="form-group-modern">
                                <label class="form-label-modern">
                                    <i class="bi bi-layers-fill"></i>
                                    <span>Shelf Number</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="bi bi-layers-fill input-icon"></i>
                                    <input type="text" name="shelf" class="form-control-modern" 
                                           value="{{ old('shelf') }}"
                                           placeholder="e.g., S-3">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Publishing Date - Full Width -->
                        <div class="form-group-modern mt-3">
                            <label class="form-label-modern">
                                <i class="bi bi-calendar-event"></i>
                                <span>Publishing Date</span>
                                <span class="required-star">*</span>
                                <span style="font-weight: normal; font-size: 0.7rem; margin-left: 8px; color: #64748b; text-transform: none;">
                                    (Year required, Month/Day optional)
                                </span>
                            </label>
                            <div class="date-grid-modern">
                                <select name="publish_year" class="date-select" required>
                                    <option value="">📅 Select Year</option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 100;
                                    @endphp
                                    @for ($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}" {{ old('publish_year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                                
                                <select name="publish_month" class="date-select">
                                    <option value="">📆 Select Month</option>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <option value="{{ $month }}" {{ old('publish_month') == $month ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                                
                                <select name="publish_day" class="date-select">
                                    <option value="">📌 Select Day</option>
                                    @for ($day = 1; $day <= 31; $day++)
                                        <option value="{{ $day }}" {{ old('publish_day') == $day ? 'selected' : '' }}>
                                            {{ $day }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            @error('publish_year')
                                <div class="text-danger small mt-1">
                                    <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Remarks - Full Width -->
                        <div class="form-group-modern mt-3">
                            <label class="form-label-modern">
                                <i class="bi bi-chat-text"></i>
                                <span>Remarks</span>
                            </label>
                            <div class="input-wrapper">
                                <i class="bi bi-chat-text input-icon" style="top: 14px;"></i>
                                <textarea name="remarks" class="form-control-modern" 
                                          rows="3" style="padding-top: 0.8rem; padding-left: 2.5rem;"
                                          placeholder="Any additional notes about this copy...">{{ old('remarks') }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-2">
                            <a href="{{ route('library.books.copies', $book->librarybook_id) }}" 
                               class="btn-outline-modern btn-modern">
                                <i class="bi bi-arrow-left"></i>
                                Back to Copies
                            </a>
                            <button type="submit" class="btn-primary-modern btn-modern">
                                <i class="bi bi-plus-circle"></i>
                                Add Copy
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.querySelector('select[name="publish_year"]');
    const monthSelect = document.querySelector('select[name="publish_month"]');
    const daySelect = document.querySelector('select[name="publish_day"]');
    
    function updateDays() {
        const year = yearSelect.value;
        const month = monthSelect.value;
        
        if (!year || !month) {
            return;
        }
        
        const daysInMonth = new Date(year, month, 0).getDate();
        const currentSelectedDay = daySelect.value;
        
        daySelect.innerHTML = '<option value="">📌 Select Day</option>';
        
        for (let day = 1; day <= daysInMonth; day++) {
            const option = document.createElement('option');
            option.value = day;
            option.textContent = day;
            if (currentSelectedDay && currentSelectedDay == day) {
                option.selected = true;
            }
            daySelect.appendChild(option);
        }
    }
    
    yearSelect.addEventListener('change', updateDays);
    monthSelect.addEventListener('change', updateDays);
});
</script>
@endsection