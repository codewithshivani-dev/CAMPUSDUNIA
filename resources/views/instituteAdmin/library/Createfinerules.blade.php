{{-- resources/views/instituteAdmin/library/fine-rules/create.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>{{ isset($fineRule) ? 'Edit' : 'Create' }} Fine Rule - Library</title>
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
    --gray-50: #f9fafc;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-600: #6b7280;
    --gray-700: #374151;
    --gray-900: #111827;
    --border-radius: 20px;
    --border-radius-sm: 12px;
    --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    --hover-shadow: 0 15px 40px rgba(67, 97, 238, 0.12);
}

body {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.container-fluid {
    animation: fadeIn 0.5s ease;
}

/* Form Card - Enhanced */
.form-card {
    background: white;
    border: none;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--card-shadow);
    transition: all 0.3s ease;
}

.form-card:hover {
    box-shadow: var(--hover-shadow);
}

.card-header {
    background: var(--primary-gradient);
    padding: 1.75rem 2rem;
    border-bottom: none;
    position: relative;
    overflow: hidden;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.card-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 200px;
    height: 200px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}

.header-title {
    position: relative;
    z-index: 1;
}

.header-title h2 {
    font-size: 1.35rem;
    font-weight: 700;
    color: white;
    margin: 0 0 0.35rem 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-title h2 i {
    font-size: 1.5rem;
}

.header-title p {
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
    font-size: 0.85rem;
}

/* Back Button - Opposite Side */
.back-button-wrapper {
    position: relative;
    z-index: 1;
}

.back-link {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0.6rem 1.2rem;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 40px;
    transition: all 0.3s;
    font-weight: 500;
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.back-link:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: translateX(4px);
    border-color: rgba(255, 255, 255, 0.5);
}

.back-link i {
    font-size: 1rem;
}

.card-body {
    padding: 2rem;
}

/* Form Groups - Enhanced */
.form-group {
    margin-bottom: 1.75rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.form-label i {
    color: var(--primary-color);
    font-size: 1rem;
}

.required-star {
    color: #ef4444;
    margin-left: 2px;
}

.form-control {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.95rem;
    transition: all 0.3s;
    background: white;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: var(--accent-glow);
    outline: none;
    transform: translateY(-1px);
}

.form-control:hover {
    border-color: var(--primary-color);
}

.form-select {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    font-size: 0.95rem;
    background-color: white;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%234361ee' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    transition: all 0.3s;
    cursor: pointer;
}

.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: var(--accent-glow);
    outline: none;
}

.form-select:hover {
    border-color: var(--primary-color);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.75rem;
}

/* Helper Text - Enhanced */
.helper-text {
    font-size: 0.7rem;
    color: var(--gray-600);
    margin-top: 0.4rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.helper-text i {
    font-size: 0.7rem;
    color: var(--primary-color);
}

/* Error Messages */
.error-message {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.35rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.error-message i {
    font-size: 0.7rem;
}

/* Action Buttons - Enhanced */
.action-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--gray-200);
}

.btn {
    padding: 0.8rem 1.75rem;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    text-decoration: none;
    letter-spacing: 0.3px;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
}

.btn-outline {
    background: white;
    border: 2px solid var(--gray-200);
    color: var(--gray-700);
}

.btn-outline:hover {
    background: var(--gray-50);
    border-color: var(--primary-color);
    transform: translateY(-2px);
}

/* Checkbox Styling */
.checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
}

.checkbox-wrapper input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: var(--primary-color);
    border-radius: 6px;
    transition: all 0.3s;
}

.checkbox-wrapper input[type="checkbox"]:hover {
    transform: scale(1.05);
}

.checkbox-wrapper span {
    font-weight: 600;
    color: var(--gray-700);
    font-size: 0.9rem;
}

/* Info Box - Enhanced */
.info-box {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    border: none;
    border-radius: 16px;
    padding: 1.25rem;
    margin-bottom: 1.75rem;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    border-left: 4px solid var(--primary-color);
}

.info-box i {
    color: var(--primary-color);
    font-size: 1.25rem;
    margin-top: 2px;
}

.info-box-content {
    flex: 1;
}

.info-box-title {
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 6px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.info-box-text {
    font-size: 0.85rem;
    color: var(--gray-700);
    line-height: 1.4;
}

/* Readonly/Disabled Fields */
input:read-only,
input:disabled {
    background-color: var(--gray-50);
    cursor: not-allowed;
    opacity: 0.7;
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

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .card-header {
        padding: 1.25rem 1.5rem;
        flex-direction: column;
        text-align: center;
    }
    
    .header-title h2 {
        font-size: 1.2rem;
        justify-content: center;
    }
    
    .header-title p {
        text-align: center;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .action-buttons {
        flex-direction: column-reverse;
        gap: 0.75rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .back-link {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
}

/* Textarea specific */
textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* Focus visible outline */
.form-control:focus-visible,
.form-select:focus-visible {
    outline: none;
}
</style>

<div class="container-fluid">
    <div class="form-card">
        <div class="card-header">
            <div class="header-title">
                <h2>
                    <i class="bi {{ isset($fineRule) ? 'bi-pencil-square' : 'bi-plus-circle' }}"></i>
                    {{ isset($fineRule) ? 'Edit Fine Rule' : 'Create New Fine Rule' }}
                </h2>
                <p>
                    <i class="bi bi-info-circle"></i>
                    {{ isset($fineRule) ? 'Update the fine rule details' : 'Configure a new fine rule for the library' }}
                </p>
            </div>
            <div class="back-button-wrapper">
                <a href="{{ route('library.fine-rules.index') }}" class="back-link">
                    <i class="bi bi-arrow-left"></i> 
                    Back to Fine Rules
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(!isset($fineRule))
            <div class="info-box">
                <i class="bi bi-info-circle-fill"></i>
                <div class="info-box-content">
                    <div class="info-box-title">
                        <i class="bi bi-question-circle"></i> About Fine Rules
                    </div>
                    <div class="info-box-text">
                        Fine rules determine how much patrons are charged for overdue books, damaged items, or lost books.
                        You can set fixed amounts or percentages of the book cost.
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ isset($fineRule) ? route('library.fine-rules.update', $fineRule->id) : route('library.fine-rules.store') }}" method="POST">
                @csrf
                @if(isset($fineRule))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-tag-fill"></i>
                        Rule Name
                        <span class="required-star">*</span>
                    </label>
                    <input type="text" name="rule_name" class="form-control" 
                           value="{{ old('rule_name', $fineRule->rule_name ?? '') }}"
                           placeholder="e.g., Standard Overdue Fine, Damage Penalty"
                           required>
                    <div class="helper-text">
                        <i class="bi bi-info-circle"></i>
                        A descriptive name for this fine rule
                    </div>
                    @error('rule_name')
                        <div class="error-message">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                @if(!isset($fineRule))
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Fine Type
                            <span class="required-star">*</span>
                        </label>
                        <select name="fine_type" class="form-select" required>
                            <option value="">Select Fine Type</option>
                            <option value="overdue" {{ old('fine_type') == 'overdue' ? 'selected' : '' }}>📖 Overdue</option>
                            <option value="damaged" {{ old('fine_type') == 'damaged' ? 'selected' : '' }}>⚠️ Damaged</option>
                            <option value="lost" {{ old('fine_type') == 'lost' ? 'selected' : '' }}>❌ Lost</option>
                            <option value="misplaced" {{ old('fine_type') == 'misplaced' ? 'selected' : '' }}>🔍 Misplaced</option>
                            <option value="other" {{ old('fine_type') == 'other' ? 'selected' : '' }}>📌 Other</option>
                        </select>
                        @error('fine_type')
                            <div class="error-message">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-clock-fill"></i>
                            Frequency
                            <span class="required-star">*</span>
                        </label>
                        <select name="frequency" class="form-select" required>
                            <option value="">Select Frequency</option>
                            <option value="one_time" {{ old('frequency') == 'one_time' ? 'selected' : '' }}>🎯 One Time</option>
                            <option value="per_day" {{ old('frequency') == 'per_day' ? 'selected' : '' }}>📅 Per Day</option>
                        </select>
                        <div class="helper-text">
                            <i class="bi bi-info-circle"></i>
                            For overdue fines, use "Per Day"
                        </div>
                        @error('frequency')
                            <div class="error-message">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                @else
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-tag"></i>
                            Fine Type
                        </label>
                        <input type="text" class="form-control" value="{{ ucfirst($fineRule->fine_type) }}" readonly disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-clock"></i>
                            Frequency
                        </label>
                        <input type="text" class="form-control" value="{{ str_replace('_', ' ', ucfirst($fineRule->frequency)) }}" readonly disabled>
                    </div>
                </div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-currency-rupee"></i>
                            Amount
                            <span class="required-star">*</span>
                        </label>
                        <input type="number" name="amount" class="form-control" 
                               value="{{ old('amount', $fineRule->amount ?? '') }}"
                               min="0" step="0.01" placeholder="0.00"
                               required>
                        @error('amount')
                            <div class="error-message">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-percent"></i>
                            Amount Type
                            <span class="required-star">*</span>
                        </label>
                        <select name="amount_type" class="form-select" required>
                            <option value="fixed" {{ old('amount_type', $fineRule->amount_type ?? '') == 'fixed' ? 'selected' : '' }}>💰 Fixed Amount</option>
                            <option value="percentage_of_book_cost" {{ old('amount_type', $fineRule->amount_type ?? '') == 'percentage_of_book_cost' ? 'selected' : '' }}>
                                📊 Percentage of Book Cost
                            </option>
                        </select>
                        @error('amount_type')
                            <div class="error-message">
                                <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-calendar-heart-fill"></i>
                        Grace Period (Days)
                    </label>
                    <input type="number" name="grace_period_days" class="form-control" 
                           value="{{ old('grace_period_days', $fineRule->grace_period_days ?? 0) }}"
                           min="0" max="365" placeholder="0">
                    <div class="helper-text">
                        <i class="bi bi-info-circle"></i>
                        Number of days after due date before fines start accruing
                    </div>
                    @error('grace_period_days')
                        <div class="error-message">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-chat-text-fill"></i>
                        Description
                    </label>
                    <textarea name="description" class="form-control" 
                              rows="3" placeholder="Describe when this fine rule applies...">{{ old('description', $fineRule->description ?? '') }}</textarea>
                    @error('description')
                        <div class="error-message">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', $fineRule->is_active ?? true) ? 'checked' : '' }}>
                        <span>
                            <i class="bi bi-toggle-on"></i> Active Rule
                        </span>
                    </label>
                    <div class="helper-text">
                        <i class="bi bi-info-circle"></i>
                        Inactive rules won't be applied to new fines
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="{{ route('library.fine-rules.index') }}" class="btn btn-outline">
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi {{ isset($fineRule) ? 'bi-check-circle-fill' : 'bi-save-fill' }}"></i>
                        {{ isset($fineRule) ? 'Update Rule' : 'Save Rule' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection