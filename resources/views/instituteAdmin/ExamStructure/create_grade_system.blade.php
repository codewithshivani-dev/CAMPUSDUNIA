@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
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
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --dark: #1f2937;
        --gray: #6b7280;
        --light-gray: #f9fafb;
        --border: #e5e7eb;
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px 30px;
        background: var(--primary-gradient);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        font-size: 32px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .back-btn {
        padding: 12px 24px;
        background: var(--primary-gradient);
        border: none;
        color: #fff !important ;
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .back-btn:hover {
        transform: translateY(-3px);
        color: #fff !important;
        text-decoration: none;
    }

    /* Cards */
    .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 18px 24px;
        background: var(--primary-gradient);
        border-bottom: none;
        color: white;
    }

    .card-header h5 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h5 i {
        font-size: 1.4rem;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .card-header .badge {
        background: white;
        color: var(--primary-color);
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 24px;
    }

    /* Form Controls */
    .form-label {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
        margin-bottom: 8px;
        letter-spacing: 0.3px;
    }

    .form-control, .form-select {
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

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .form-control:hover, .form-select:hover {
        border-color: var(--secondary-color);
    }

    textarea.form-control {
        height: auto;
        padding: 10px 14px;
        resize: vertical;
    }

    /* Checkbox */
    .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 4px;
        border: 2px solid #cbd5e1;
        transition: all 0.2s;
        margin-right: 8px;
    }

    .form-check-input:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }

    .form-check-label {
        font-weight: 500;
        color: var(--dark);
        cursor: pointer;
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--gray);
        margin-top: 4px;
    }

    /* Grade Range Card */
    .grade-range-card {
        border: 2px solid var(--border);
        border-radius: 12px;
        margin-bottom: 1rem;
        padding: 1.25rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .grade-range-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-gradient);
    }

    .grade-range-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(67, 97, 238, 0.15);
        border-color: var(--primary-color);
    }

    .grade-range-card .remove-range {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: none;
        color: var(--danger-color);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .grade-range-card .remove-range:hover {
        background: var(--danger-gradient);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    /* Add Range Button */
    .add-range-btn {
        border: 2px dashed var(--primary-color);
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        color: var(--primary-color);
        padding: 1.25rem;
        border-radius: 12px;
        transition: all 0.3s;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }

    .add-range-btn:hover {
        background: var(--primary-light);
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(67, 97, 238, 0.15);
        color: var(--primary-color);
    }

    .add-range-btn i {
        font-size: 1.2rem;
    }

    /* Validation Error */
    .validation-error {
        color: var(--danger-color);
        font-size: 0.8rem;
        margin-top: 4px;
        display: none;
        background: rgba(239, 68, 68, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        border-left: 3px solid var(--danger-color);
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
        background-color: rgba(239, 68, 68, 0.05);
    }

    /* Preview Grade */
    .preview-grade {
        display: inline-block;
        background: var(--primary-gradient);
        color: white;
        padding: 6px 14px;
        border-radius: 30px;
        margin-right: 8px;
        margin-bottom: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
        transition: all 0.3s;
    }

    .preview-grade:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
    }

    .empty-preview {
        color: var(--gray);
        font-style: italic;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 10px;
        text-align: center;
        border: 2px dashed var(--border);
    }

    /* Preview Sidebar */
    .sticky-top {
        top: 20px;
    }

    #preview-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-top: 4px;
    }

    #preview-description {
        color: var(--gray);
        font-size: 0.9rem;
        margin-top: 4px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 8px;
    }

    .status-default {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .status-active {
        background: var(--success-gradient);
        color: white;
    }

    .status-inactive {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
    }

    /* Alert Info */
    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        color: #1e40af;
        border-radius: 12px;
        padding: 16px;
    }

    .alert-info i {
        color: var(--primary-color);
    }

    /* Buttons */
    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        text-decoration: none;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-outline-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid var(--border);
    }

    .btn-outline-secondary:hover {
        background: var(--primary-gradient);
        color: white !important;
        border-color: transparent;
    }

    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
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
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .grade-range-card .row {
            flex-direction: column;
        }

        .grade-range-card [class*="col-"] {
            margin-bottom: 10px;
        }

        .grade-range-card .remove-range {
            width: 100%;
        }
    }
    .sticky-top{
        z-index: 1 !important;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-plus-circle-fill"></i>
            Create Grade System
        </h1>
        <a href="{{ route('grade-systems.index') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
            Back to List
        </a>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-md-9">
            <form id="gradeSystemForm" method="POST" action="{{ route('grade-systems.store') }}">
                @csrf
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>
                            <i class="bi bi-info-circle-fill"></i>
                            Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Grade System Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                                       value="{{ old('name') }}" placeholder="e.g., Standard Grading, University Grading" required>
                                <div class="validation-error" id="name-error"></div>
                                @error('name')
                                    <div class="validation-error" style="display: block;">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="1" placeholder="Optional description for this grade system">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_default" name="is_default" 
                                           value="1" {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">
                                        Set as Default Grade System
                                    </label>
                                    <div class="form-text">
                                        If checked, this will become the default grading system for all exams
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                    <div class="form-text">
                                        Active grade systems can be used for exams
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grade Ranges Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-bar-chart-steps"></i>
                            Grade Ranges
                        </h5>
                        <span class="badge" id="range-count">0 ranges</span>
                    </div>
                    <div class="card-body">
                        <div id="grade-ranges-container">
                            <!-- Grade ranges will be added here dynamically -->
                        </div>
                        
                        <!-- Add Range Button -->
                        <div class="text-center mt-4">
                            <button type="button" id="add-range-btn" class="add-range-btn">
                                <i class="bi bi-plus-circle-fill"></i>Add Grade Range
                            </button>
                        </div>
                        
                        <!-- Validation for ranges -->
                        <div id="ranges-error" class="validation-error mt-3"></div>
                        <input type="hidden" name="grade_ranges" id="grade_ranges_input" value="{{ old('grade_ranges', '[]') }}">
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('grade-systems.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>Save Grade System
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Sidebar -->
        <div class="col-md-3">
            <div class="card sticky-top">
                <div class="card-header">
                    <h5>
                        <i class="bi bi-eye-fill"></i>
                        Live Preview
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-3" style="color: var(--primary-color);">Grade System Preview</h6>
                    
                    <div class="mb-3">
                        <small class="text-muted">Name:</small>
                        <div id="preview-name" class="fw-bold" style="color: var(--primary-color);">Not set</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Description:</small>
                        <div id="preview-description" class="text-muted">No description</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Status:</small>
                        <div class="mt-2">
                            <span id="preview-default" class="status-badge">Not Default</span>
                            <span id="preview-active" class="status-badge">Active</span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="fw-bold mb-3" style="color: var(--primary-color);">Grade Ranges Preview</h6>
                    <div id="preview-ranges" class="empty-preview">
                        No grade ranges added yet
                    </div>
                    
                    <div class="alert-info mt-4" role="alert">
                        <i class="bi bi-lightbulb-fill me-2"></i>
                        <small>
                            <strong>Tip:</strong> Ensure your grade ranges cover 0-100% without gaps or overlaps for accurate grading.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grade Range Template (Hidden) -->
<template id="grade-range-template">
    <div class="grade-range-card" data-index="{index}">
        <div class="row align-items-center">
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-medium">Min %</label>
                <input type="number" class="form-control min-percentage" min="0" max="100" step="0.1" placeholder="0.0" required>
                <div class="validation-error"></div>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-medium">Max %</label>
                <input type="number" class="form-control max-percentage" min="0" max="100" step="0.1" placeholder="100.0" required>
                <div class="validation-error"></div>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-medium">Grade</label>
                <input type="text" class="form-control grade" maxlength="10" placeholder="A+" required>
                <div class="validation-error"></div>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small fw-medium">Grade Point</label>
                <input type="number" class="form-control grade-point" min="0" max="10" step="0.1" placeholder="4.0" required>
                <div class="validation-error"></div>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small fw-medium">Description</label>
                <input type="text" class="form-control range-description" placeholder="Excellent">
                <div class="validation-error"></div>
            </div>
            <div class="col-md-1 mb-2 text-center">
                <label class="form-label small d-block">&nbsp;</label>
                <button type="button" class="btn remove-range" title="Remove this range">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rangeCounter = 0;
    const ranges = [];
    
    // DOM Elements
    const rangesContainer = document.getElementById('grade-ranges-container');
    const addRangeBtn = document.getElementById('add-range-btn');
    const rangeCount = document.getElementById('range-count');
    const previewRanges = document.getElementById('preview-ranges');
    const gradeRangesInput = document.getElementById('grade_ranges_input');
    const rangeTemplate = document.getElementById('grade-range-template').innerHTML;
    
    // Preview elements
    const previewName = document.getElementById('preview-name');
    const previewDescription = document.getElementById('preview-description');
    const previewDefault = document.getElementById('preview-default');
    const previewActive = document.getElementById('preview-active');
    
    // Form elements
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const defaultCheckbox = document.getElementById('is_default');
    const activeCheckbox = document.getElementById('is_active');
    
    // Initialize with default range if empty
    if (ranges.length === 0) {
        addGradeRange(90, 100, 'A+', 4.0, 'Excellent');
        addGradeRange(80, 89.9, 'A', 3.7, 'Very Good');
        addGradeRange(70, 79.9, 'B+', 3.3, 'Good');
        addGradeRange(60, 69.9, 'B', 3.0, 'Above Average');
        addGradeRange(50, 59.9, 'C+', 2.7, 'Average');
        addGradeRange(40, 49.9, 'C', 2.3, 'Below Average');
        addGradeRange(0, 39.9, 'F', 0.0, 'Fail');
    }
    
    // Add Range Button Click
    addRangeBtn.addEventListener('click', function() {
        addGradeRange();
    });
    
    // Real-time Preview Updates
    nameInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    defaultCheckbox.addEventListener('change', updatePreview);
    activeCheckbox.addEventListener('change', updatePreview);
    
    // Form Submission
    document.getElementById('gradeSystemForm').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });
    
    // Function to add a grade range
    function addGradeRange(min = '', max = '', grade = '', gradePoint = '', description = '') {
        const index = rangeCounter++;
        const rangeHtml = rangeTemplate.replace(/{index}/g, index);
        
        const rangeElement = document.createElement('div');
        rangeElement.innerHTML = rangeHtml;
        rangesContainer.appendChild(rangeElement.firstElementChild);
        
        // Set values if provided
        const rangeDiv = rangesContainer.lastElementChild;
        if (min !== '') rangeDiv.querySelector('.min-percentage').value = min;
        if (max !== '') rangeDiv.querySelector('.max-percentage').value = max;
        if (grade !== '') rangeDiv.querySelector('.grade').value = grade;
        if (gradePoint !== '') rangeDiv.querySelector('.grade-point').value = gradePoint;
        if (description !== '') rangeDiv.querySelector('.range-description').value = description;
        
        // Add event listeners
        const inputs = rangeDiv.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', updateRangesArray);
            input.addEventListener('blur', updatePreview);
        });
        
        // Remove button
        rangeDiv.querySelector('.remove-range').addEventListener('click', function() {
            if (confirm('Remove this grade range?')) {
                rangeDiv.remove();
                updateRangesArray();
                updatePreview();
                updateRangeCount();
            }
        });
        
        updateRangesArray();
        updatePreview();
        updateRangeCount();
    }
    
    // Update ranges array from DOM
    function updateRangesArray() {
        ranges.length = 0;
        const rangeCards = document.querySelectorAll('.grade-range-card');
        
        rangeCards.forEach(card => {
            const min = parseFloat(card.querySelector('.min-percentage').value) || 0;
            const max = parseFloat(card.querySelector('.max-percentage').value) || 0;
            const grade = card.querySelector('.grade').value.trim();
            const gradePoint = parseFloat(card.querySelector('.grade-point').value) || 0;
            const description = card.querySelector('.range-description').value.trim();
            
            if (grade) {
                ranges.push({
                    min_percentage: min,
                    max_percentage: max,
                    grade: grade,
                    grade_point: gradePoint,
                    description: description || null
                });
            }
        });
        
        // Sort ranges by max percentage (descending)
        ranges.sort((a, b) => b.max_percentage - a.max_percentage);
        
        // Update hidden input
        gradeRangesInput.value = JSON.stringify(ranges);
    }
    
    // Update preview
    function updatePreview() {
        // Update basic info
        previewName.textContent = nameInput.value || 'Not set';
        previewDescription.textContent = descriptionInput.value || 'No description';
        
        // Update status badges
        previewDefault.textContent = defaultCheckbox.checked ? 'Default' : 'Not Default';
        previewDefault.className = defaultCheckbox.checked ? 'status-badge status-default' : 'status-badge status-inactive';
        
        previewActive.textContent = activeCheckbox.checked ? 'Active' : 'Inactive';
        previewActive.className = activeCheckbox.checked ? 'status-badge status-active' : 'status-badge status-inactive';
        
        // Update ranges preview
        if (ranges.length > 0) {
            previewRanges.innerHTML = '';
            ranges.forEach(range => {
                const gradeDiv = document.createElement('div');
                gradeDiv.className = 'preview-grade';
                gradeDiv.textContent = `${range.grade}: ${range.min_percentage}-${range.max_percentage}%`;
                gradeDiv.title = range.description || range.grade;
                previewRanges.appendChild(gradeDiv);
            });
        } else {
            previewRanges.innerHTML = '<div class="empty-preview">No grade ranges added yet</div>';
        }
        
        updateRangeCount();
    }
    
    // Update range count
    function updateRangeCount() {
        rangeCount.textContent = `${ranges.length} range${ranges.length !== 1 ? 's' : ''}`;
    }
    
    // Validate form
    function validateForm() {
        let isValid = true;
        const rangesError = document.getElementById('ranges-error');
        
        // Validate name
        if (!nameInput.value.trim()) {
            showError(nameInput, 'Grade system name is required');
            isValid = false;
        } else {
            clearError(nameInput);
        }
        
        // Validate each range input individually
        const rangeCards = document.querySelectorAll('.grade-range-card');
        let hasRangeErrors = false;
        
        rangeCards.forEach(card => {
            const minInput = card.querySelector('.min-percentage');
            const maxInput = card.querySelector('.max-percentage');
            const gradeInput = card.querySelector('.grade');
            
            // Check required fields
            if (!minInput.value.trim()) {
                showError(minInput, 'Min % is required');
                isValid = false;
                hasRangeErrors = true;
            } else {
                clearError(minInput);
            }
            
            if (!maxInput.value.trim()) {
                showError(maxInput, 'Max % is required');
                isValid = false;
                hasRangeErrors = true;
            } else {
                clearError(maxInput);
            }
            
            if (!gradeInput.value.trim()) {
                showError(gradeInput, 'Grade is required');
                isValid = false;
                hasRangeErrors = true;
            } else {
                clearError(gradeInput);
            }
        });
        
        // Check if at least one range exists
        if (ranges.length === 0) {
            rangesError.textContent = 'At least one grade range is required';
            rangesError.style.display = 'block';
            isValid = false;
        } else if (!hasRangeErrors) {
            rangesError.style.display = 'none';
        }
        
        // If validation failed, scroll to first error
        if (!isValid) {
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
        
        console.log('Validation result:', isValid); // Debug log
        return isValid;
    }
    
    // Utility functions
    function showError(element, message) {
        // If it's an input element
        if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
            element.classList.add('is-invalid');
            let errorDiv = element.nextElementSibling;
            
            // Find the error div (might not be immediate sibling)
            if (!errorDiv || !errorDiv.classList.contains('validation-error')) {
                errorDiv = element.parentElement.querySelector('.validation-error');
            }
            
            if (errorDiv) {
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
            }
        } 
        // If it's the ranges error div
        else if (element.id === 'ranges-error') {
            element.textContent = message;
            element.style.display = 'block';
        }
    }

    function clearError(element) {
        if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
            element.classList.remove('is-invalid');
            let errorDiv = element.nextElementSibling;
            
            if (!errorDiv || !errorDiv.classList.contains('validation-error')) {
                errorDiv = element.parentElement.querySelector('.validation-error');
            }
            
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
        } else if (element.id === 'ranges-error') {
            element.style.display = 'none';
        }
    }
    
    // Initialize preview
    updatePreview();
});
</script>
@endsection