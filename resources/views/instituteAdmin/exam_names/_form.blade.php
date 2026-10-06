@csrf
@if(isset($examName))
    @method('PUT')
@endif
<style>
    .form-control-lg, .form-select-lg {
        border-radius: 0.5rem;
        border: 1.5px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    .form-control-lg:focus, .form-select-lg:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .form-control-lg.is-invalid, .form-select-lg.is-invalid {
        border-color: #ef4444;
    }
    .form-label {
        color: #334155;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .form-text {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 0.35rem;
    }
    .form-check-input {
        width: 3em;
        height: 1.5em;
        margin-top: 0.25em;
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: #6366f1;
        border-color: #6366f1;
    }
    .form-switch-lg .form-check-input {
        width: 3.5em;
        height: 1.75em;
    }
    .btn-lg {
        border-radius: 0.5rem;
        font-size: 0.95rem;
        padding: 0.65rem 1.5rem;
    }
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }
</style>


<div class="row g-4">
    {{-- Exam Name --}}
    <div class="col-12">
        <div class="form-group">
            <label for="name" class="form-label fw-semibold">
                <i class="fas fa-file-alt me-2 "></i>Exam Name
                <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                   value="{{ old('name', $examName->name ?? '') }}" 
                   placeholder="e.g., Final Examination 2025"
                   required 
                   maxlength="255">
            @error('name')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
            <div class="form-text">Enter a descriptive name for the exam.</div>
        </div>
    </div>

    {{-- Academic Year --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="academic_year" class="form-label fw-semibold">
                <i class="fas fa-calendar-alt me-2"></i>Academic Year
                <span class="text-danger">*</span>
            </label>
            <input type="text" 
                   id="academic_year" 
                   name="academic_year" 
                   class="form-control form-control-lg @error('academic_year') is-invalid @enderror" 
                   value="{{ old('academic_year', $academicYear ?? $examName->academic_year ?? '') }}" 
                   placeholder="e.g., 2024-2025"
                   maxlength="20" 
                   required>
            @error('academic_year')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
            <div class="form-text">Format: YYYY-YYYY (e.g., 2024-2025)</div>
        </div>
    </div>

    {{-- Term --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="term" class="form-label fw-semibold">
                <i class="fas fa-layer-group me-2"></i>Term
                <span class="text-danger">*</span>
            </label>
            <select id="term" 
                    name="term" 
                    class="form-select form-select-lg @error('term') is-invalid @enderror" 
                    required>
                <option value="" disabled>Select a term...</option>
                @foreach(['mid_term' => 'Mid Term', 'final' => 'Final', 'prelim' => 'Prelim', 'unit_test' => 'Unit Test', 'other' => 'Other'] as $value => $label)
                    <option value="{{ $value }}" {{ old('term', $examName->term ?? '') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('term')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>
    </div>

    {{-- Description --}}
    <div class="col-12">
        <div class="form-group">
            <label for="description" class="form-label fw-semibold">
                <i class="fas fa-align-left me-2"></i>Description
                <span class="text-muted fw-normal">(Optional)</span>
            </label>
            <textarea id="description" 
                      name="description" 
                      class="form-control @error('description') is-invalid @enderror" 
                      rows="3"
                      placeholder="Add any additional notes or details about this exam...">{{ old('description', $examName->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>
    </div>

    {{-- Status Toggle --}}
    <div class="col-12">
        <div class="form-group">
            <div class="form-check form-switch form-switch-lg">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" 
                       id="is_active" 
                       name="is_active" 
                       value="1" 
                       class="form-check-input" 
                       role="switch"
                       {{ old('is_active', $examName->is_active ?? true) ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label fw-semibold">
                    <i class="fas fa-check-circle me-2"></i>Active Status
                </label>
            </div>
            <div class="form-text">Inactive exam names won't appear in dropdowns when creating exams.</div>
        </div>
    </div>
</div>

<hr class="my-4">

{{-- Action Buttons --}}
<div class="d-flex flex-wrap justify-content-end gap-2">
    <a href="{{ route('institute.exam-names.index') }}" class="btn btn-light btn-lg px-4">
        <i class="fas fa-times me-2"></i>Cancel
    </a>
    <button type="submit" class="btn btn-primary btn-lg px-4">
        <i class="fas fa-save me-2"></i>{{ isset($examName) ? 'Update Exam Name' : 'Create Exam Name' }}
    </button>
</div>

