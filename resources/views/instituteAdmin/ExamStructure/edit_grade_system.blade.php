@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    .grade-range-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 1rem;
        padding: 1rem;
        background-color: #fafafa;
        transition: all 0.3s ease;
    }
    
    .grade-range-card:hover {
        background-color: #f5f5f5;
        border-color: #4361ee;
    }
    
    .grade-range-card .remove-range {
        color: #e63946;
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .grade-range-card .remove-range:hover {
        color: #d00000;
    }
    
    .add-range-btn {
        border: 2px dashed #dee2e6;
        background-color: #f8f9ff;
        color: #4361ee;
        padding: 1rem;
        border-radius: 8px;
        transition: all 0.3s;
    }
    
    .add-range-btn:hover {
        background-color: #eef2ff;
        border-color: #4361ee;
        transform: translateY(-2px);
    }
    
    .validation-error {
        color: #e63946;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
    }
    
    .form-control.is-invalid {
        border-color: #e63946;
    }
    
    .preview-grade {
        display: inline-block;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        margin-right: 8px;
        margin-bottom: 8px;
        font-size: 0.875rem;
    }
    
    .empty-preview {
        color: #6c757d;
        font-style: italic;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 6px;
        text-align: center;
    }
    
    .system-info {
        background-color: #f8f9ff;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .system-info .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-2" style="color: #343a40;">
                <i class="fas fa-edit text-primary me-2"></i>Edit Grade System
            </h2>
            <p class="text-muted mb-0">Update the grading system details and ranges</p>
        </div>
        <a href="{{ route('grade-systems.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <!-- System Info -->
    <div class="system-info">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="h5 mb-2">{{ $gradeSystem->name }}</h4>
                <p class="text-muted mb-0">{{ $gradeSystem->description ?: 'No description' }}</p>
            </div>
            <div class="col-md-4 text-md-end">
                @if($gradeSystem->is_default)
                    <span class="badge bg-primary me-2">Default</span>
                @endif
                @if($gradeSystem->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
                <div class="text-muted small mt-2">
                    Created: {{ $gradeSystem->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Form -->
        <div class="col-lg-8">
            <form id="gradeSystemForm" method="POST" action="{{ route('grade-systems.update', $gradeSystem->id) }}">
                @csrf
                @method('PUT')
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Grade System Name *</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $gradeSystem->name) }}" placeholder="e.g., Standard Grading, University Grading" required>
                                <div class="validation-error" id="name-error"></div>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="1" placeholder="Optional description for this grade system">{{ old('description', $gradeSystem->description) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_default" name="is_default" 
                                           value="1" {{ old('is_default', $gradeSystem->is_default) ? 'checked' : '' }}>
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
                                           value="1" {{ old('is_active', $gradeSystem->is_active) ? 'checked' : '' }}>
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
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Grade Ranges</h5>
                        <span class="badge bg-primary" id="range-count">
                            {{ is_array($gradeSystem->grade_ranges) ? count($gradeSystem->grade_ranges) : 0 }} ranges
                        </span>
                    </div>
                    <div class="card-body">
                        <div id="grade-ranges-container">
                            <!-- Grade ranges will be added here dynamically -->
                        </div>
                        
                        <!-- Add Range Button -->
                        <div class="text-center mt-4">
                            <button type="button" id="add-range-btn" class="add-range-btn btn w-100">
                                <i class="fas fa-plus-circle me-2"></i>Add Grade Range
                            </button>
                        </div>
                        
                        <!-- Validation for ranges -->
                        <div id="ranges-error" class="validation-error mt-3"></div>
                        <input type="hidden" name="grade_ranges" id="grade_ranges_input" 
                               value="{{ old('grade_ranges', json_encode($gradeSystem->grade_ranges ?? [])) }}">
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('grade-systems.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                    <div>
                        @if(!$gradeSystem->is_default)
                            <button type="button" class="btn btn-outline-danger me-2" 
                                    onclick="confirmDelete()">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Grade System
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Preview Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Live Preview</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Grade System Preview</h6>
                    
                    <div class="mb-3">
                        <small class="text-muted">Name:</small>
                        <div id="preview-name" class="fw-bold">{{ $gradeSystem->name }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Description:</small>
                        <div id="preview-description" class="text-muted">
                            {{ $gradeSystem->description ?: 'No description' }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Status:</small>
                        <div>
                            <span id="preview-default" class="badge {{ $gradeSystem->is_default ? 'bg-primary' : 'bg-secondary' }} me-2">
                                {{ $gradeSystem->is_default ? 'Default' : 'Not Default' }}
                            </span>
                            <span id="preview-active" class="badge {{ $gradeSystem->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $gradeSystem->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="mb-3">Grade Ranges Preview</h6>
                    <div id="preview-ranges">
                        @if($gradeSystem->grade_ranges && is_array($gradeSystem->grade_ranges))
                            @foreach($gradeSystem->grade_ranges as $range)
                                <div class="preview-grade" title="{{ $range['description'] ?? $range['grade'] }}">
                                    {{ $range['grade'] }}: {{ $range['min_percentage'] }}-{{ $range['max_percentage'] }}%
                                </div>
                            @endforeach
                        @else
                            <div class="empty-preview">No grade ranges defined</div>
                        @endif
                    </div>
                    
                    <div class="alert alert-info mt-4" role="alert">
                        <i class="fas fa-lightbulb me-2"></i>
                        <small>
                            <strong>Note:</strong> Changes to grade ranges will affect all exams using this system.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form -->
<form id="delete-form" action="{{ route('grade-systems.destroy', $gradeSystem->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Grade Range Template (Hidden) -->
<template id="grade-range-template">
    <div class="grade-range-card" data-index="{index}">
        <div class="row align-items-center">
            <div class="col-md-2 mb-2">
                <label class="form-label small">Min %</label>
                <input type="number" class="form-control min-percentage" min="0" max="100" step="0.1" placeholder="0.0" required>
                <div class="validation-error"></div>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small">Max %</label>
                <input type="number" class="form-control max-percentage" min="0" max="100" step="0.1" placeholder="100.0" required>
                <div class="validation-error"></div>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small">Grade</label>
                <input type="text" class="form-control grade" maxlength="10" placeholder="A+" required>
                <div class="validation-error"></div>
            </div>
            <div class="col-md-3 mb-2">
                <label class="form-label small">Grade Point</label>
                <input type="number" class="form-control grade-point" min="0" max="10" step="0.1" placeholder="4.0" required>
                <div class="validation-error"></div>
            </div>
            <div class="col-md-2 mb-2">
                <label class="form-label small">Description</label>
                                <input type="text" class="form-control range-description" placeholder="Excellent">
                <div class="validation-error"></div>
            </div>
            <div class="col-md-1 mb-2 text-center">
                <label class="form-label small d-block">&nbsp;</label>
                <button type="button" class="btn btn-sm remove-range" title="Remove this range">
                    <i class="fas fa-trash text-danger"></i>
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
    
    // Load existing grade ranges
    @if($gradeSystem->grade_ranges && is_array($gradeSystem->grade_ranges))
        @foreach($gradeSystem->grade_ranges as $range)
            addGradeRange(
                {{ $range['min_percentage'] ?? 0 }},
                {{ $range['max_percentage'] ?? 100 }},
                '{{ $range['grade'] ?? '' }}',
                {{ $range['grade_point'] ?? 0 }},
                '{{ $range['description'] ?? '' }}'
            );
        @endforeach
    @endif
    
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
    // Form Submission - Add debug logging
document.getElementById('gradeSystemForm').addEventListener('submit', function(e) {
    console.log('Form submit event triggered');
    console.log('Form data:', {
        name: nameInput.value,
        ranges: ranges,
        rangesInput: gradeRangesInput.value
    });
    
    if (!validateForm()) {
        console.log('Validation failed, preventing submission');
        e.preventDefault();
        
        // Show an alert for debugging
        setTimeout(() => {
            alert('Form validation failed. Please check the form for errors.');
        }, 100);
    } else {
        console.log('Validation passed, form will submit');
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
        previewDefault.className = defaultCheckbox.checked ? 'badge bg-primary me-2' : 'badge bg-secondary me-2';
        
        previewActive.textContent = activeCheckbox.checked ? 'Active' : 'Inactive';
        previewActive.className = activeCheckbox.checked ? 'badge bg-success' : 'badge bg-danger';
        
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
// Validate form - Minimal version
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
    
    // Delete confirmation
    window.confirmDelete = function() {
        if (confirm('Are you sure you want to delete this grade system? This action cannot be undone.')) {
            document.getElementById('delete-form').submit();
        }
    };
    
    // Initialize preview
    updatePreview();
});
</script>
@endsection