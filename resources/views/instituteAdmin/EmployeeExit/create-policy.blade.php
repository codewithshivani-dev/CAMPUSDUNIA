{{-- resources/views/instituteAdmin/EmployeeExit/create-policy.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Create Exit Policy')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
.exit-policy-page {
    --ep-ink: #101828;
    --ep-slate: #475467;
    --ep-muted: #98A2B3;
    --ep-primary: #2A5C8A;
    --ep-primary-dark: #1D4266;
    --ep-primary-soft: #EAF2F9;
    --ep-indigo: #4338CA;
    --ep-indigo-soft: #EEEDFC;
    --ep-amber: #B45309;
    --ep-amber-soft: #FEF3E2;
    --ep-teal: #0E8074;
    --ep-teal-soft: #E4F5F2;
    --ep-rose: #B42318;
    --ep-rose-soft: #FDEDEC;
    --ep-border: #E4E7EC;
    --ep-bg: #F7F9FC;
    --ep-surface: #FFFFFF;
    --ep-radius-lg: 14px;
    --ep-radius-md: 10px;
    --ep-shadow-sm: 0 1px 2px rgba(16, 24, 40, .04);
    --ep-shadow-md: 0 4px 12px rgba(16, 24, 40, .06);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    color: var(--ep-ink);
}

.exit-policy-page h3,
.exit-policy-page h5,
.exit-policy-page h6 {
    font-family: 'Inter', sans-serif;
    color: var(--ep-ink);
}

.exit-policy-page .page-shell {
    background: var(--ep-bg);
    margin: 0;
    padding: 1.25rem 0 3rem;
}

.exit-policy-page .ep-card {
    background: var(--ep-surface);
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius-lg);
    box-shadow: var(--ep-shadow-md);
    max-width: 1000px;
    margin: 0 auto;
    overflow: hidden;
}

.exit-policy-page .ep-card-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--ep-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.exit-policy-page .ep-card-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
}

.exit-policy-page .ep-card-header p {
    margin: .2rem 0 0;
    font-size: .875rem;
    color: var(--ep-slate);
}

.exit-policy-page .ep-back-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-size: .85rem;
    font-weight: 600;
    color: var(--ep-slate);
    border: 1px solid var(--ep-border);
    background: #fff;
    padding: .45rem .9rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all .15s ease;
}

.exit-policy-page .ep-back-btn:hover {
    background: var(--ep-bg);
    color: var(--ep-ink);
    text-decoration: none;
}

.exit-policy-page .ep-card-body {
    padding: 2rem;
}

.exit-policy-page .ep-card-footer {
    padding: 1.25rem 2rem;
    background: var(--ep-bg);
    border-top: 1px solid var(--ep-border);
    display: flex;
    gap: .75rem;
    flex-wrap: wrap;
}

.exit-policy-page .ep-section {
    margin-bottom: 2.5rem;
}

.exit-policy-page .ep-section:last-child {
    margin-bottom: 0;
}

.exit-policy-page .ep-section-eyebrow {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--ep-primary);
    margin-bottom: .25rem;
    display: block;
}

.exit-policy-page .ep-section-title {
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: .2rem;
}

.exit-policy-page .ep-section-desc {
    font-size: .85rem;
    color: var(--ep-slate);
    margin-bottom: 1.1rem;
}

.exit-policy-page label {
    font-size: .82rem;
    font-weight: 600;
    color: var(--ep-ink);
}

.exit-policy-page .form-control {
    border: 1.5px solid var(--ep-border);
    border-radius: 8px;
    font-size: .9rem;
    padding: .55rem .75rem;
    height: auto;
    color: var(--ep-ink);
}

.exit-policy-page .form-control:focus {
    border-color: var(--ep-primary);
    box-shadow: 0 0 0 3px var(--ep-primary-soft);
}

.exit-policy-page textarea.form-control {
    resize: vertical;
}

.exit-policy-page small.text-muted {
    color: var(--ep-muted) !important;
}

/* Policy code generator */
.exit-policy-page .code-input-group {
    display: flex;
    gap: .5rem;
}

.exit-policy-page .code-input-group .form-control {
    flex: 1 1 auto;
    min-width: 0;
}

.exit-policy-page .btn-generate-code {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: 0 .9rem;
    border: 1.5px solid var(--ep-primary);
    border-radius: 8px;
    background: var(--ep-primary-soft);
    color: var(--ep-primary-dark);
    font-size: .82rem;
    font-weight: 600;
    white-space: nowrap;
    transition: all .15s ease;
}

.exit-policy-page .btn-generate-code:hover {
    background: var(--ep-primary);
    color: #fff;
}

.exit-policy-page .btn-generate-code i {
    font-size: .78rem;
    transition: transform .3s ease;
}

.exit-policy-page .btn-generate-code.is-spinning i {
    transform: rotate(360deg);
}

/* Requirement cards */
.exit-policy-page .req-card {
    border: 1px solid var(--ep-border);
    border-left: 4px solid var(--ep-border);
    border-radius: var(--ep-radius-md);
    padding: 1.1rem 1.25rem;
    margin-bottom: .9rem;
    background: var(--ep-surface);
    transition: border-color .2s ease, box-shadow .2s ease;
}

.exit-policy-page .req-card:hover {
    box-shadow: var(--ep-shadow-sm);
}

.exit-policy-page .req-card.is-enabled {
    box-shadow: var(--ep-shadow-sm);
}

.exit-policy-page .req-card.accent-primary {
    border-left-color: var(--ep-primary);
}

.exit-policy-page .req-card.accent-primary .req-icon {
    background: var(--ep-primary-soft);
    color: var(--ep-primary);
}

.exit-policy-page .req-card.accent-amber {
    border-left-color: var(--ep-amber);
}

.exit-policy-page .req-card.accent-amber .req-icon {
    background: var(--ep-amber-soft);
    color: var(--ep-amber);
}

.exit-policy-page .req-card.accent-teal {
    border-left-color: var(--ep-teal);
}

.exit-policy-page .req-card.accent-teal .req-icon {
    background: var(--ep-teal-soft);
    color: var(--ep-teal);
}

.exit-policy-page .req-card.accent-indigo {
    border-left-color: var(--ep-indigo);
}

.exit-policy-page .req-card.accent-indigo .req-icon {
    background: var(--ep-indigo-soft);
    color: var(--ep-indigo);
}

.exit-policy-page .req-card.accent-rose {
    border-left-color: var(--ep-rose);
}

.exit-policy-page .req-card.accent-rose .req-icon {
    background: var(--ep-rose-soft);
    color: var(--ep-rose);
}

.exit-policy-page .req-header {
    display: flex;
    align-items: center;
    gap: .85rem;
}

.exit-policy-page .req-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.exit-policy-page .req-header h6 {
    font-size: .92rem;
    font-weight: 700;
    margin: 0;
}

.exit-policy-page .req-header small {
    font-size: .78rem;
    color: var(--ep-slate);
}

.exit-policy-page .req-body {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px dashed var(--ep-border);
}

/* Switch */
.exit-policy-page .ep-switch {
    position: relative;
    width: 42px;
    height: 24px;
    flex-shrink: 0;
}

.exit-policy-page .ep-switch input {
    opacity: 0;
    width: 0;
    height: 0;
    position: absolute;
}

.exit-policy-page .ep-switch .track {
    position: absolute;
    inset: 0;
    background: #D0D5DD;
    border-radius: 999px;
    cursor: pointer;
    transition: background .2s ease;
}

.exit-policy-page .ep-switch .track::before {
    content: "";
    position: absolute;
    width: 18px;
    height: 18px;
    left: 3px;
    top: 3px;
    background: #fff;
    border-radius: 50%;
    transition: transform .2s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .2);
}

.exit-policy-page .ep-switch input:checked+.track {
    background: var(--ep-primary);
}

.exit-policy-page .ep-switch input:checked+.track::before {
    transform: translateX(18px);
}

.exit-policy-page .ep-switch input:focus-visible+.track {
    outline: 2px solid var(--ep-primary);
    outline-offset: 2px;
}

/* Chip checklist */
.exit-policy-page .chip-group {
    display: flex;
    flex-wrap: wrap;
    gap: .55rem;
}

.exit-policy-page .chip-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.exit-policy-page .chip-label {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .45rem .85rem;
    border: 1.5px solid var(--ep-border);
    border-radius: 999px;
    font-size: .8rem;
    font-weight: 500;
    color: var(--ep-slate);
    background: #fff;
    cursor: pointer;
    transition: all .15s ease;
    margin: 0;
    user-select: none;
}

.exit-policy-page .chip-label .chip-input {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    opacity: 0;
    cursor: pointer;
}

.exit-policy-page .chip-label .chip-check {
    display: none;
    font-size: .7rem;
}

.exit-policy-page .chip-label.is-checked {
    border-color: var(--ep-primary);
    background: var(--ep-primary-soft);
    color: var(--ep-primary-dark);
}

.exit-policy-page .chip-label.is-checked .chip-check {
    display: inline-block;
}

.exit-policy-page .chip-input:focus-visible~.chip-check,
.exit-policy-page .chip-label:focus-within {
    outline: 2px solid var(--ep-primary);
    outline-offset: 2px;
}

/* Notice period grid */
.exit-policy-page .notice-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: .8rem;
    margin-top: .25rem;
}

.exit-policy-page .notice-tile {
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius-md);
    padding: .85rem .9rem;
    background: var(--ep-bg);
    transition: border-color .15s ease;
}

.exit-policy-page .notice-tile:focus-within {
    border-color: var(--ep-primary);
}

.exit-policy-page .notice-tile .notice-tile-label {
    display: flex;
    align-items: center;
    gap: .45rem;
    font-size: .82rem;
    font-weight: 600;
    margin-bottom: .5rem;
    color: var(--ep-ink);
}

.exit-policy-page .notice-tile .notice-tile-label i {
    color: var(--ep-primary);
    font-size: .8rem;
}

.exit-policy-page .notice-tile select {
    font-size: .85rem;
}

.exit-policy-page .notice-tile .custom-days-input {
    margin-top: .5rem;
}

/* Assignment section */
.exit-policy-page .assignment-type-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

.exit-policy-page .assignment-type-card {
    flex: 1;
    min-width: 150px;
    border: 2px solid var(--ep-border);
    border-radius: var(--ep-radius-md);
    padding: 1rem 1.25rem;
    cursor: pointer;
    transition: all .2s ease;
    text-align: center;
    position: relative;
}

.exit-policy-page .assignment-type-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.exit-policy-page .assignment-type-card .type-label-wrapper {
    cursor: pointer;
    display: block;
    width: 100%;
    margin: 0;
}

.exit-policy-page .assignment-type-card:hover {
    border-color: var(--ep-primary);
    background: var(--ep-primary-soft);
}

.exit-policy-page .assignment-type-card.selected {
    border-color: var(--ep-primary);
    background: var(--ep-primary-soft);
    box-shadow: 0 0 0 3px rgba(42, 92, 138, 0.15);
}

.exit-policy-page .assignment-type-card .type-icon {
    font-size: 1.8rem;
    display: block;
    margin-bottom: .5rem;
}

.exit-policy-page .assignment-type-card .type-label {
    font-weight: 600;
    display: block;
    font-size: .9rem;
}

.exit-policy-page .assignment-type-card .type-desc {
    font-size: .75rem;
    color: var(--ep-slate);
}

/* Buttons */
.exit-policy-page .btn-ep-primary {
    background: var(--ep-primary);
    border-color: var(--ep-primary);
    color: #fff;
    font-weight: 600;
    font-size: .88rem;
    padding: .6rem 1.3rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    transition: background .15s ease;
    border: none;
}

.exit-policy-page .btn-ep-primary:hover {
    background: var(--ep-primary-dark);
    color: #fff;
}

.exit-policy-page .btn-ep-ghost {
    background: #fff;
    border: 1.5px solid var(--ep-border);
    color: var(--ep-slate);
    font-weight: 600;
    font-size: .88rem;
    padding: .6rem 1.3rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    text-decoration: none;
}

.exit-policy-page .btn-ep-ghost:hover {
    background: var(--ep-bg);
    color: var(--ep-ink);
    text-decoration: none;
}

.exit-policy-page .divider {
    border-top: 1px solid var(--ep-border);
    margin: 1.75rem 0;
}

/* Alert styling */
.exit-policy-page .alert-danger {
    background: var(--ep-rose-soft);
    border-color: var(--ep-rose);
    color: var(--ep-rose);
    border-radius: var(--ep-radius-md);
}

/* Multi-select */
.exit-policy-page .selection-list {
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
    overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius-md);
    padding: .5rem;
}

.exit-policy-page .selection-item {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .5rem .75rem;
    border-radius: 6px;
    transition: background .15s ease;
    cursor: pointer;
}

.exit-policy-page .selection-item:hover {
    background: var(--ep-bg);
}

.exit-policy-page .selection-item input[type="checkbox"] {
    margin: 0;
    width: 16px;
    height: 16px;
    accent-color: var(--ep-primary);
    flex-shrink: 0;
}

.exit-policy-page .selection-item .item-info {
    flex: 1;
}

.exit-policy-page .selection-item .item-info .name {
    font-weight: 500;
    font-size: .85rem;
}

.exit-policy-page .selection-item .item-info .meta {
    font-size: .75rem;
    color: var(--ep-slate);
}

/* FNF Processing Days - Show/Hide */
.exit-policy-page .fnf-days-group {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    margin-top: .25rem;
}

.exit-policy-page .fnf-days-option {
    flex: 1;
    min-width: 120px;
}

.exit-policy-page .fnf-days-option input[type="radio"] {
    display: none;
}

.exit-policy-page .fnf-days-option label {
    display: block;
    padding: .6rem .75rem;
    border: 2px solid var(--ep-border);
    border-radius: 8px;
    text-align: center;
    cursor: pointer;
    transition: all .2s ease;
    font-weight: 500;
    font-size: .82rem;
    margin: 0;
}

.exit-policy-page .fnf-days-option label:hover {
    border-color: var(--ep-primary);
    background: var(--ep-primary-soft);
}

.exit-policy-page .fnf-days-option input[type="radio"]:checked + label {
    border-color: var(--ep-primary);
    background: var(--ep-primary-soft);
    color: var(--ep-primary-dark);
    box-shadow: 0 0 0 3px rgba(42, 92, 138, 0.15);
}

.exit-policy-page .fnf-days-option .days-badge {
    display: block;
    font-size: .7rem;
    color: var(--ep-slate);
    font-weight: 400;
}

.exit-policy-page .fnf-days-option input[type="radio"]:checked + label .days-badge {
    color: var(--ep-primary);
}

/* Conflict Notification */
.exit-policy-page .conflict-badge {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .25rem .75rem;
    border-radius: 999px;
    font-size: .75rem;
    font-weight: 600;
    background: #FEF3C7;
    color: #92400E;
}

.exit-policy-page .conflict-badge i {
    font-size: .7rem;
}

/* Exit type info */
.exit-type-info {
    background: var(--ep-primary-soft);
    border-radius: var(--ep-radius-md);
    padding: .75rem 1rem;
    margin-top: .5rem;
}

.exit-type-info .info-text {
    font-size: .8rem;
    color: var(--ep-slate);
}

.exit-type-info .info-text i {
    color: var(--ep-primary);
}

@media (max-width: 576px) {
    .exit-policy-page .ep-card-body {
        padding: 1.25rem;
    }

    .exit-policy-page .ep-card-header {
        padding: 1.1rem 1.25rem;
        flex-direction: column;
        align-items: flex-start;
        gap: .6rem;
    }

    .exit-policy-page .assignment-type-card {
        min-width: 120px;
    }

    .exit-policy-page .notice-grid {
        grid-template-columns: 1fr;
    }

    .exit-policy-page .fnf-days-option {
        min-width: 100px;
    }
}
</style>

<div class="exit-policy-page">
    <div class="page-shell">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="ep-card">
                        <div class="ep-card-header">
                            <div>
                                <h3>Create Exit Policy</h3>
                                <p>Define exit type, notice periods, requirements, and assign to employees.</p>
                            </div>
                            <a href="{{ route('exit-policies.index') }}" class="ep-back-btn">
                                <i class="fas fa-arrow-left"></i> Back to Policies
                            </a>
                        </div>

                        <form action="{{ route('exit-policies.store') }}" method="POST" id="policyForm">
                            @csrf
                            <div class="ep-card-body">
                                @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                {{-- Step 1: Basic Information --}}
                                <div class="ep-section">
                                    <span class="ep-section-eyebrow">Step 1</span>
                                    <div class="ep-section-title">Basic information</div>
                                    <div class="ep-section-desc">Give this policy a name, code, and specify the exit
                                        type(s).</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="policy_name">Policy Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="policy_name" id="policy_name"
                                                    class="form-control" placeholder="e.g., Standard Exit Policy"
                                                    value="{{ old('policy_name') }}" required>
                                                @error('policy_name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="policy_code">Policy Code <span
                                                        class="text-danger">*</span></label>
                                                <div class="code-input-group">
                                                    <input type="text" name="policy_code" id="policy_code"
                                                        class="form-control" placeholder="e.g., EXIT-POL-001"
                                                        value="{{ old('policy_code') }}" required autocomplete="off">
                                                    <button type="button" class="btn-generate-code"
                                                        id="generateCodeBtn">
                                                        <i class="fas fa-sync-alt"></i> Generate
                                                    </button>
                                                </div>
                                                <small class="text-muted">Generate one automatically, or type your
                                                    own</small>
                                                @error('policy_code')
                                                <span class="text-danger d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Exit Type(s) <span class="text-danger">*</span></label>
                                                <div class="chip-group" id="exitTypeGroup">
                                                    @foreach($exitTypes as $type)
                                                    <label class="chip-label {{ in_array($type, old('exit_type', [])) ? 'is-checked' : '' }}">
                                                        <input type="checkbox" class="chip-input exit-type-checkbox" name="exit_type[]" value="{{ $type }}"
                                                            {{ in_array($type, old('exit_type', [])) ? 'checked' : '' }}>
                                                        <i class="fas fa-check chip-check"></i>{{ $type }}
                                                    </label>
                                                    @endforeach
                                                </div>
                                                <div class="exit-type-info">
                                                    <span class="info-text">
                                                        <i class="fas fa-info-circle"></i> 
                                                        <strong>Note:</strong> Each selected exit type will create a separate assignment row for the selected departments/employees. 
                                                        This ensures the correct policy is applied based on the employee's exit type.
                                                    </span>
                                                </div>
                                                @error('exit_type')
                                                <span class="text-danger d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                {{-- Step 2: Assignment Configuration --}}
                                <div class="ep-section">
                                    <span class="ep-section-eyebrow">Step 2</span>
                                    <div class="ep-section-title">Assign Policy</div>
                                    <div class="ep-section-desc">Choose who this policy applies to - all employees,
                                        specific departments, or individual employees.</div>

                                    {{-- Assignment Type Selection --}}
                                    <div class="assignment-type-group">
                                        <div class="assignment-type-card {{ old('assignment_type', 'all') == 'all' ? 'selected' : '' }}" data-value="all">
                                            <input type="radio" name="assignment_type" value="all" id="assignment_all" {{ old('assignment_type', 'all') == 'all' ? 'checked' : '' }}>
                                            <label for="assignment_all" class="type-label-wrapper">
                                                <span class="type-icon">🌐</span>
                                                <span class="type-label">All Departments</span>
                                                <span class="type-desc">Apply to all active departments</span>
                                            </label>
                                        </div>

                                        <div class="assignment-type-card {{ old('assignment_type') == 'departments' ? 'selected' : '' }}" data-value="departments">
                                            <input type="radio" name="assignment_type" value="departments" id="assignment_departments" {{ old('assignment_type') == 'departments' ? 'checked' : '' }}>
                                            <label for="assignment_departments" class="type-label-wrapper">
                                                <span class="type-icon">🏢</span>
                                                <span class="type-label">Department</span>
                                                <span class="type-desc">Select specific department</span>
                                            </label>
                                        </div>

                                        <div class="assignment-type-card {{ old('assignment_type') == 'employees' ? 'selected' : '' }}" data-value="employees">
                                            <input type="radio" name="assignment_type" value="employees" id="assignment_employees" {{ old('assignment_type') == 'employees' ? 'checked' : '' }}>
                                            <label for="assignment_employees" class="type-label-wrapper">
                                                <span class="type-icon">👤</span>
                                                <span class="type-label">Individual Employee</span>
                                                <span class="type-desc">Select specific employee</span>
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Department Selection --}}
                                    <div id="department_selection"
                                        style="{{ old('assignment_type') == 'departments' ? '' : 'display: none;' }}">
                                        <div class="form-group">
                                            <label>Select Department <span class="text-danger">*</span></label>
                                            <div class="selection-list">
                                                @foreach($departments as $department)
                                                <label class="selection-item department-option" data-department-id="{{ $department->department_id }}">
                                                    <input type="checkbox" class="department-checkbox" name="department_ids[]"
                                                        value="{{ $department->department_id }}"
                                                        {{ in_array($department->department_id, old('department_ids', [])) ? 'checked' : '' }}>
                                                    <span class="item-info">
                                                        <span class="name">{{ $department->department }}</span>
                                                        <span class="meta d-none">{{ $department->employees_count ?? 0 }}
                                                            employees</span>
                                                    </span>
                                                    <span class="department-availability-message"></span>
                                                </label>
                                                @endforeach
                                            </div>
                                            @error('department_ids')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <small id="departmentAvailabilityMessage" class="text-danger d-block mt-2"></small>
                                        </div>
                                    </div>

                                    {{-- Employee Selection --}}
                                    <div id="employee_selection"
                                        style="{{ old('assignment_type') == 'employees' ? '' : 'display: none;' }}">
                                        <div class="form-group">
                                            <label>Select Employees <span class="text-danger">*</span></label>
                                            <div class="selection-list">
                                                @foreach($employees as $employee)
                                                <label class="selection-item">
                                                    <input type="checkbox" class="employee-checkbox" name="employee_ids[]"
                                                        value="{{ $employee->employee_id }}"
                                                        {{ in_array($employee->employee_id, old('employee_ids', [])) ? 'checked' : '' }}>
                                                    <span class="item-info">
                                                        <span class="name">{{ $employee->name }}</span>
                                                        <span class="meta">{{ $employee->employee_code }} •
                                                            {{ $employee->employment_type ?? 'N/A' }}</span>
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                            @error('employee_ids')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Assignment Details --}}
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="effective_date">Effective Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" name="effective_date" id="effective_date"
                                                    class="form-control"
                                                    value="{{ old('effective_date', date('Y-m-d')) }}" required>
                                                @error('effective_date')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expiry_date">Expiry Date</label>
                                                <input type="date" name="expiry_date" id="expiry_date"
                                                    class="form-control" value="{{ old('expiry_date') }}">
                                                <small class="text-muted">Leave empty if policy doesn't expire</small>
                                                @error('expiry_date')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="assignment_notes">Assignment Notes</label>
                                        <textarea name="assignment_notes" id="assignment_notes" class="form-control"
                                            rows="2"
                                            placeholder="Additional notes about this policy assignment">{{ old('assignment_notes') }}</textarea>
                                    </div>
                                </div>

                                <div class="divider"></div>
                                
                                {{-- Step 3: Notice Period Configuration --}}
                                <div class="ep-section">
                                    <span class="ep-section-eyebrow">Step 3</span>
                                    <div class="ep-section-title">Notice period configuration <span
                                            class="text-danger">*</span></div>
                                    <div class="ep-section-desc">Set the default notice period for this exit type, and
                                        override it for specific employment types if needed.</div>

                                    {{-- Default Notice Period --}}
                                    <div class="req-card accent-primary">
                                        <div class="req-header">
                                            <div class="req-icon"><i class="fas fa-clock"></i></div>
                                            <div>
                                                <h6>Default notice period</h6>
                                                <small>Applies to all employment types unless overridden below</small>
                                            </div>
                                        </div>
                                        <div class="req-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="default_notice_period">Notice Period <span
                                                                class="text-danger">*</span></label>
                                                        <select name="default_notice_period" id="default_notice_period"
                                                            class="form-control">
                                                            <option value="30"
                                                                {{ old('default_notice_period', 30) == 30 ? 'selected' : '' }}>
                                                                30 Days</option>
                                                            <option value="45"
                                                                {{ old('default_notice_period') == 45 ? 'selected' : '' }}>
                                                                45 Days</option>
                                                            <option value="60"
                                                                {{ old('default_notice_period') == 60 ? 'selected' : '' }}>
                                                                60 Days</option>
                                                            <option value="90"
                                                                {{ old('default_notice_period') == 90 ? 'selected' : '' }}>
                                                                90 Days</option>
                                                            <option value="custom"
                                                                {{ old('default_notice_period') == 'custom' ? 'selected' : '' }}>
                                                                Custom</option>
                                                        </select>
                                                        @error('default_notice_period')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="default_custom_days_section"
                                                    style="display: none;">
                                                    <div class="form-group">
                                                        <label for="default_custom_days">Custom Days <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" name="default_custom_days"
                                                            id="default_custom_days" class="form-control"
                                                            placeholder="Enter custom days" min="1" max="365"
                                                            value="{{ old('default_custom_days') }}">
                                                        @error('default_custom_days')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Employment Type Specific Notice Periods --}}
                                    <div class="req-card accent-indigo">
                                        <div class="req-header">
                                            <div class="req-icon"><i class="fas fa-users"></i></div>
                                            <div>
                                                <h6>Employment type overrides</h6>
                                                <small>Optional — override the default for specific employment
                                                    types</small>
                                            </div>
                                        </div>
                                        <div class="req-body">
                                            <div class="notice-grid">
                                                @foreach($employmentTypes as $type)
                                                <div class="notice-tile">
                                                    <label class="notice-tile-label"
                                                        for="notice_period_{{ Str::slug($type) }}">
                                                        <i class="fas {{
                                                            $type == 'Full-time' ? 'fa-user-tie' :
                                                            ($type == 'Part-time' ? 'fa-user-clock' :
                                                            ($type == 'Contract-based' ? 'fa-file-contract' :
                                                            ($type == 'Probation-Period' ? 'fa-user-graduate' : 'fa-users')))
                                                        }}"></i>
                                                        {{ $type }}
                                                    </label>
                                                    <select name="employment_notice_periods[{{ $type }}]"
                                                        id="notice_period_{{ Str::slug($type) }}"
                                                        class="form-control form-control-sm employment-notice-select">
                                                        <option value="">Default</option>
                                                        <option value="30"
                                                            {{ old('employment_notice_periods.'.$type) == 30 ? 'selected' : '' }}>
                                                            30 Days</option>
                                                        <option value="45"
                                                            {{ old('employment_notice_periods.'.$type) == 45 ? 'selected' : '' }}>
                                                            45 Days</option>
                                                        <option value="60"
                                                            {{ old('employment_notice_periods.'.$type) == 60 ? 'selected' : '' }}>
                                                            60 Days</option>
                                                        <option value="90"
                                                            {{ old('employment_notice_periods.'.$type) == 90 ? 'selected' : '' }}>
                                                            90 Days</option>
                                                        <option value="custom"
                                                            {{ old('employment_notice_periods.'.$type) == 'custom' ? 'selected' : '' }}>
                                                            Custom</option>
                                                    </select>
                                                    <div class="custom-days-input" style="display: none;">
                                                        <input type="number" name="employment_custom_days[{{ $type }}]"
                                                            class="form-control form-control-sm"
                                                            placeholder="Custom days" min="1" max="365"
                                                            value="{{ old('employment_custom_days.'.$type) }}">
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                {{-- Step 4: Exit Requirements --}}
                                <div class="ep-section">
                                    <span class="ep-section-eyebrow">Step 4</span>
                                    <div class="ep-section-title">Exit Steps</div>
                                    <div class="ep-section-desc">Configure the steps that employees must complete
                                        during exit.</div>

                                         {{-- KT (Knowledge Transfer) --}}
                                    <div class="req-card accent-teal" id="kt_card">
                                        <div class="req-header">
                                            <div class="req-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                            <div class="flex-grow-1">
                                                <h6>KT (Knowledge Transfer)</h6>
                                                <small>Configure handover duration</small>
                                            </div>
                                            <label class="ep-switch mb-0">
                                                <input type="checkbox" id="kt_required" name="kt_required" value="1"
                                                    {{ old('kt_required') ? 'checked' : '' }}>
                                                <span class="track"></span>
                                            </label>
                                        </div>
                                        <div class="kt-config req-body"
                                            style="{{ old('kt_required') ? '' : 'display: none;' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="kt_days">KT Duration (Days) <span
                                                                class="text-danger">*</span></label>
                                                        <select name="kt_days" id="kt_days" class="form-control">
                                                            <option value="">Select Duration</option>
                                                            <option value="3"
                                                                {{ old('kt_days') == 3 ? 'selected' : '' }}>3 Days
                                                            </option>
                                                            <option value="5"
                                                                {{ old('kt_days') == 5 ? 'selected' : '' }}>5 Days
                                                            </option>
                                                            <option value="7"
                                                                {{ old('kt_days') == 7 ? 'selected' : '' }}>7 Days
                                                            </option>
                                                            <option value="10"
                                                                {{ old('kt_days') == 10 ? 'selected' : '' }}>10 Days
                                                            </option>
                                                            <option value="15"
                                                                {{ old('kt_days') == 15 ? 'selected' : '' }}>15 Days
                                                            </option>
                                                            <option value="30"
                                                                {{ old('kt_days') == 30 ? 'selected' : '' }}>30 Days
                                                            </option>
                                                        </select>
                                                        @error('kt_days')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label>KT Handover Requirements</label>
                                                <div class="chip-group">
                                                    @php
                                                    $ktRequirements = [
                                                    'documentation' => 'Documentation Handover',
                                                    'project_handover' => 'Project/Work Handover',
                                                    'code_handover' => 'Code/System Handover',
                                                    'client_handover' => 'Client/Stakeholder Handover',
                                                    'process_handover' => 'Process Handover',
                                                    'training' => 'Training & Support',
                                                    'knowledge_docs' => 'Knowledge Base Documentation'
                                                    ];
                                                    $selectedKtRequirements = old('kt_requirements', []);
                                                    @endphp
                                                    @foreach($ktRequirements as $key => $label)
                                                    <label
                                                        class="chip-label {{ in_array($key, $selectedKtRequirements) ? 'is-checked' : '' }}">
                                                        <input type="checkbox" class="chip-input"
                                                            name="kt_requirements[]" value="{{ $key }}"
                                                            {{ in_array($key, $selectedKtRequirements) ? 'checked' : '' }}>
                                                        <i class="fas fa-check chip-check"></i>{{ $label }}
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    {{-- Additional Requirements --}}
                                    <div class="req-card accent-primary">
                                        <div class="req-header">
                                            <div class="req-icon"><i class="fas fa-tasks"></i></div>
                                            <div>
                                                <h6>Assets & Clearances</h6>
                                                <small>Optional offboarding tasks to include</small>
                                            </div>
                                        </div>
                                        <div class="req-body">
                                            <div class="chip-group">
                                                @php
                                                $additionalRequirements = [
                                                'asset_return' => 'Asset Return (Laptop, Phone, etc.)',
                                                'access_revocation' => 'Access Revocation (Email, Systems, etc.)',
                                                'id_card_return' => 'ID Card Return',
                                                'visa_cancellation' => 'Visa/Iqama Cancellation (If Applicable)',
                                                'exit_reentry_visa' => 'Exit Re-entry Visa Processing',
                                                'medical_certificate' => 'Medical Certificate',
                                                'police_clearance' => 'Police Clearance Certificate',
                                                'housing_handover' => 'Housing/Accommodation Handover'
                                                ];
                                                $selectedAdditional = old('additional_requirements', []);
                                                @endphp
                                                @foreach($additionalRequirements as $key => $label)
                                                <label
                                                    class="chip-label {{ in_array($key, $selectedAdditional) ? 'is-checked' : '' }}">
                                                    <input type="checkbox" class="chip-input"
                                                        name="additional_requirements[]" value="{{ $key }}"
                                                        {{ in_array($key, $selectedAdditional) ? 'checked' : '' }}>
                                                    <i class="fas fa-check chip-check"></i>{{ $label }}
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                     {{-- Exit Interview --}}
                                    <div class="req-card accent-indigo" id="exit_interview_card">
                                        <div class="req-header">
                                            <div class="req-icon"><i class="fas fa-comments"></i></div>
                                            <div class="flex-grow-1">
                                                <h6>Exit interview</h6>
                                                <small>Enable exit interview requirement</small>
                                            </div>
                                            <label class="ep-switch mb-0">
                                                <input type="checkbox" id="exit_interview_required"
                                                    name="exit_interview_required" value="1"
                                                    {{ old('exit_interview_required') ? 'checked' : '' }}>
                                                <span class="track"></span>
                                            </label>
                                        </div>
                                        <div class="exit-interview-config req-body"
                                            style="{{ old('exit_interview_required') ? '' : 'display: none;' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="interview_days">Interview Timeline (Days)</label>
                                                        <input type="number" name="interview_days" id="interview_days"
                                                            class="form-control" placeholder="e.g., 5" min="1" max="30"
                                                            value="{{ old('interview_days', 5) }}">
                                                        <small class="text-muted">Days before exit to schedule the
                                                            interview</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- FNF (Full and Final) --}}
                                    <div class="req-card accent-amber" id="fnf_card">
                                        <div class="req-header">
                                            <div class="req-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                            <div class="flex-grow-1">
                                                <h6>FNF (Full &amp; Final Settlement)</h6>
                                                <small>Configure final settlement processing rules</small>
                                            </div>
                                            <label class="ep-switch mb-0">
                                                <input type="checkbox" id="fnf_required" name="fnf_required" value="1"
                                                    {{ old('fnf_required') ? 'checked' : '' }}>
                                                <span class="track"></span>
                                            </label>
                                        </div>
                                        <div class="fnf-config req-body"
                                            style="{{ old('fnf_required') ? '' : 'display: none;' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fnf_settlement_type">Settlement Type <span
                                                                class="text-danger">*</span></label>
                                                        <select name="fnf_settlement_type" id="fnf_settlement_type"
                                                            class="form-control">
                                                            <option value="standard"
                                                                {{ old('fnf_settlement_type') == 'standard' ? 'selected' : '' }}>
                                                                Standard Settlement</option>
                                                            <option value="expedited"
                                                                {{ old('fnf_settlement_type') == 'expedited' ? 'selected' : '' }}>
                                                                Expedited Settlement</option>
                                                        </select>
                                                        <small class="text-muted">Standard: 30-60 days | Expedited: 7-15
                                                            days</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fnf_processing_days">FNF Processing Days <span
                                                                class="text-danger">*</span></label>
                                                        <div class="fnf-days-group">
                                                            @php
                                                            $fnfDayOptions = [
                                                                'standard' => [
                                                                    '30' => '30 Days',
                                                                    '45' => '45 Days',
                                                                    '60' => '60 Days'
                                                                ],
                                                                'expedited' => [
                                                                    '7' => '7 Days',
                                                                    '15' => '15 Days'
                                                                ]
                                                            ];
                                                            $selectedType = old('fnf_settlement_type', 'standard');
                                                            $selectedDays = old('fnf_processing_days');
                                                            @endphp
                                                            @foreach($fnfDayOptions as $type => $options)
                                                                <div class="fnf-days-option fnf-type-{{ $type }}" 
                                                                    style="{{ $type == $selectedType ? '' : 'display: none;' }}">
                                                                    @foreach($options as $value => $label)
                                                                        <div>
                                                                            <input type="radio" 
                                                                                name="fnf_processing_days" 
                                                                                value="{{ $value }}"
                                                                                id="fnf_days_{{ $type }}_{{ $value }}"
                                                                                {{ $selectedDays == $value ? 'checked' : '' }}
                                                                                {{ $type == $selectedType && $loop->first && !$selectedDays ? 'checked' : '' }}>
                                                                            <label for="fnf_days_{{ $type }}_{{ $value }}">
                                                                                {{ $label }}
                                                                                <span class="days-badge">{{ $type == 'standard' ? 'Standard' : 'Expedited' }}</span>
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @error('fnf_processing_days')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label>FNF Items Checklist</label>
                                                <div class="chip-group">
                                                    @php
                                                    $fnfItems = [
                                                    'salary_settlement' => 'Salary Settlement',
                                                    'leave_encashment' => 'Leave Encashment',
                                                    'bonus_settlement' => 'Bonus/Incentive Settlement',
                                                    'reimbursement' => 'Reimbursement Claims',
                                                    'pf_settlement' => 'PF Settlement',
                                                    'esi_settlement' => 'ESI Settlement',
                                                    'gratuity' => 'Gratuity',
                                                    'other_dues' => 'Other Dues'
                                                    ];
                                                    $selectedFnfItems = old('fnf_items', []);
                                                    @endphp
                                                    @foreach($fnfItems as $key => $label)
                                                    <label
                                                        class="chip-label {{ in_array($key, $selectedFnfItems) ? 'is-checked' : '' }}">
                                                        <input type="checkbox" class="chip-input" name="fnf_items[]"
                                                            value="{{ $key }}"
                                                            {{ in_array($key, $selectedFnfItems) ? 'checked' : '' }}>
                                                        <i class="fas fa-check chip-check"></i>{{ $label }}
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                               

                                    {{-- Clearance Workflow --}}
                                    <div class="req-card accent-rose">
                                        <div class="req-header">
                                            <div class="req-icon"><i class="fas fa-check-double"></i></div>
                                            <div>
                                                <h6>Exit clearance workflow</h6>
                                                <small>How department clearances are routed</small>
                                            </div>
                                        </div>
                                        <div class="req-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="clearance_workflow">Clearance Workflow <span
                                                                class="text-danger">*</span></label>
                                                        <select name="clearance_workflow" id="clearance_workflow"
                                                            class="form-control">
                                                            <option value="">Select Workflow</option>
                                                            <option value="sequential"
                                                                {{ old('clearance_workflow') == 'sequential' ? 'selected' : '' }}>
                                                                Sequential (Step by Step)
                                                            </option>
                                                            <option value="hybrid"
                                                                {{ old('clearance_workflow') == 'hybrid' ? 'selected' : '' }}>
                                                                Hybrid (Mix of Sequential &amp; Parallel)
                                                            </option>
                                                        </select>
                                                        @error('clearance_workflow')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-0">
                                                        <label for="clearance_days">Clearance Processing Days <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" name="clearance_days" id="clearance_days"
                                                            class="form-control" placeholder="Enter number of days"
                                                            min="1" max="90" value="{{ old('clearance_days', 7) }}">
                                                        @error('clearance_days')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                {{-- Additional Options --}}
                                <div class="ep-section">
                                    <span class="ep-section-eyebrow">Additional Info</span>
                                    <div class="ep-section-title">Description &amp; terms</div>
                                    <div class="ep-section-desc">Optional context and terms shown when this policy is
                                        assigned.</div>
                                    <div class="form-group">
                                        <label for="description">Policy Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="3"
                                            placeholder="Brief description of this policy">{{ old('description') }}</textarea>
                                        @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="terms_conditions">Terms &amp; Conditions</label>
                                        <textarea name="terms_conditions" id="terms_conditions" class="form-control"
                                            rows="3">{{ old('terms_conditions') }}</textarea>
                                        @error('terms_conditions')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Policy Status --}}
                                <div class="req-card accent-teal mb-0 mt-3">
                                    <div class="req-header">
                                        <div class="req-icon"><i class="fas fa-bolt"></i></div>
                                        <div class="flex-grow-1">
                                            <h6>Activate immediately</h6>
                                            <small>Make this policy available for assignment right away</small>
                                        </div>
                                        <label class="ep-switch mb-0">
                                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                                {{ old('is_active', true) ? 'checked' : '' }}>
                                            <span class="track"></span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Hidden override confirmation field --}}
                                <input type="hidden" name="override_confirmed" id="override_confirmed" value="0">
                                <input type="hidden" name="skip_conflicts" id="skip_conflicts" value="0">
                            </div>

                            <div class="ep-card-footer">
                                <button type="submit" class="btn-ep-primary" id="submitBtn">
                                    <i class="fas fa-save"></i> Create & Assign Policy
                                </button>
                                <a href="{{ route('exit-policies.index') }}" class="btn-ep-ghost">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    function syncEnabledState($checkbox) {
        var $card = $checkbox.closest('.req-card');
        $card.toggleClass('is-enabled', $checkbox.is(':checked'));
    }

    // Chip checklist styling
    $('.chip-input').on('change', function() {
        $(this).closest('.chip-label').toggleClass('is-checked', this.checked);
    });

    // Policy code generator
    function generatePolicyCode() {
        var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var suffix = '';
        for (var i = 0; i < 6; i++) {
            suffix += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return 'EXIT-POL-' + suffix;
    }

    $('#generateCodeBtn').on('click', function() {
        var $btn = $(this);
        $('#policy_code').val(generatePolicyCode());
        $btn.addClass('is-spinning');
        setTimeout(function() {
            $btn.removeClass('is-spinning');
        }, 300);
    });

    // Assignment type toggle
    $('.assignment-type-card').on('click', function(e) {
        if ($(e.target).closest('.type-label-wrapper').length) return;
        
        var radio = $(this).find('input[type="radio"]');
        radio.prop('checked', true);
        $(this).addClass('selected').siblings('.assignment-type-card').removeClass('selected');
        radio.trigger('change');
    });

    $('.assignment-type-card .type-label-wrapper').on('click', function(e) {
        e.preventDefault();
        var $card = $(this).closest('.assignment-type-card');
        var radio = $card.find('input[type="radio"]');
        radio.prop('checked', true);
        $card.addClass('selected').siblings('.assignment-type-card').removeClass('selected');
        radio.trigger('change');
    });

    // Assignment type change handler
    $('input[name="assignment_type"]').on('change', function() {
        var value = $(this).val();
        $('.assignment-type-card').removeClass('selected');
        $(this).closest('.assignment-type-card').addClass('selected');

        $('#department_selection').hide();
        $('#employee_selection').hide();

        if (value === 'departments') {
            $('#department_selection').show();
        } else if (value === 'employees') {
            $('#employee_selection').show();
        }
    });

    // Trigger change on load for existing selection
    var checkedRadio = $('input[name="assignment_type"]:checked');
    if (checkedRadio.length > 0) {
        checkedRadio.trigger('change');
    }

    function refreshDepartmentAvailability() {
        var formData = new FormData($('#policyForm')[0]);
        $.ajax({
            url: "{{ route('exit-policies.check-conflicts') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                var unavailable = (response.unavailable_department_ids || []).map(String);
                var conflictDetails = {};
                (response.department_conflicts || []).forEach(function(conflict) {
                    conflictDetails[String(conflict.department_id)] = conflict;
                });
                var removed = [];

                $('.department-option').each(function() {
                    var departmentId = String($(this).data('department-id'));
                    var blocked = unavailable.indexOf(departmentId) !== -1;
                    $(this).toggleClass('text-muted', blocked);
                    $(this).find('input').prop('disabled', blocked);
                    var message = '';
                    if (blocked && conflictDetails[departmentId]) {
                        message = 'Already assigned: ' + conflictDetails[departmentId].policies.map(function(policy) {
                            return policy.exit_type + ' (<a href="' + policy.edit_url + '" target="_blank">Update policy</a>)';
                        }).join(', ');
                    }
                    $(this).find('.department-availability-message').html(message);
                    if (blocked && $(this).find('input').is(':checked')) {
                        removed.push($(this).find('.name').text().trim());
                        $(this).find('input').prop('checked', false);
                    }
                });

                $('#departmentAvailabilityMessage').text(removed.length
                    ? removed.join(', ') + ' is unavailable for the selected exit type.'
                    : '');
            }
        });
    }

    $('.exit-type-checkbox').on('change', refreshDepartmentAvailability);
    refreshDepartmentAvailability();

    // Exit Interview toggle
    $('#exit_interview_required').on('change', function() {
        syncEnabledState($(this));
        if ($(this).is(':checked')) {
            $('.exit-interview-config').show();
        } else {
            $('.exit-interview-config').hide();
        }
    });

    // FNF toggle
    $('#fnf_required').on('change', function() {
        syncEnabledState($(this));
        if ($(this).is(':checked')) {
            $('.fnf-config').show();
            var type = $('#fnf_settlement_type').val();
            $('.fnf-days-option').hide();
            $('.fnf-type-' + type).show();
            var firstRadio = $('.fnf-type-' + type + ' input[type="radio"]').first();
            if (!firstRadio.is(':checked')) {
                firstRadio.prop('checked', true);
            }
        } else {
            $('.fnf-config').hide();
        }
    });

    // FNF Settlement Type change
    $('#fnf_settlement_type').on('change', function() {
        var type = $(this).val();
        $('.fnf-days-option').hide();
        $('.fnf-type-' + type).show();
        var firstRadio = $('.fnf-type-' + type + ' input[type="radio"]').first();
        if (!firstRadio.is(':checked')) {
            firstRadio.prop('checked', true);
        }
    });

    // KT toggle
    $('#kt_required').on('change', function() {
        syncEnabledState($(this));
        if ($(this).is(':checked')) {
            $('.kt-config').show();
            $('#kt_days').prop('required', true);
        } else {
            $('.kt-config').hide();
            $('#kt_days').prop('required', false);
        }
    });

    $('#is_active').on('change', function() {
        syncEnabledState($(this));
    });

    // Default notice period change
    $('#default_notice_period').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#default_custom_days_section').show();
            $('#default_custom_days').prop('required', true);
        } else {
            $('#default_custom_days_section').hide();
            $('#default_custom_days').prop('required', false);
        }
    });

    // Employment type custom days toggle
    $('.employment-notice-select').on('change', function() {
        var customInput = $(this).closest('.notice-tile').find('.custom-days-input');
        if ($(this).val() === 'custom') {
            customInput.show();
            customInput.find('input').prop('required', true);
        } else {
            customInput.hide();
            customInput.find('input').prop('required', false);
        }
    });

    // Initialize custom days visibility on page load
    $('.employment-notice-select').each(function() {
        if ($(this).val() === 'custom') {
            $(this).closest('.notice-tile').find('.custom-days-input').show();
            $(this).closest('.notice-tile').find('.custom-days-input input').prop('required', true);
        }
    });

    // Initialize enabled-state styling for switches already checked on load
    $('#exit_interview_required, #fnf_required, #kt_required, #is_active').each(function() {
        syncEnabledState($(this));
    });

    // Initialize FNF days visibility on load
    if ($('#fnf_required').is(':checked')) {
        var initialType = $('#fnf_settlement_type').val();
        $('.fnf-days-option').hide();
        $('.fnf-type-' + initialType).show();
        var firstRadio = $('.fnf-type-' + initialType + ' input[type="radio"]').first();
        if (!firstRadio.is(':checked')) {
            firstRadio.prop('checked', true);
        }
    }

    // Auto-populate effective date
    if (!$('#effective_date').val()) {
        var today = new Date().toISOString().split('T')[0];
        $('#effective_date').val(today);
    }

    // Date validation - expiry date must be after effective date
    $('#expiry_date').on('change', function() {
        var effective = $('#effective_date').val();
        var expiry = $(this).val();
        if (effective && expiry && expiry <= effective) {
            $(this).val('');
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Date',
                text: 'Expiry date must be after the effective date.',
                confirmButtonColor: '#B45309'
            });
        }
    });

    // Function to show conflict dialog
    function showConflictDialog(conflicts, allowSkip, callback) {
        var conflictHtml = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> The following policies conflict with the new policy:</div>';
        conflictHtml += '<ul class="list-group mb-3" style="max-height: 300px; overflow-y: auto;">';
        
        conflicts.forEach(function(conflict) {
            var details = '';
            if (conflict.type === 'all') {
                details = 'Affects ' + conflict.affected_count + ' employees';
            } else if (conflict.type === 'department') {
                details = 'Affects departments: ' + (conflict.affected_departments || []).join(', ');
            } else if (conflict.type === 'employee') {
                details = 'Affects employees: ' + (conflict.affected_employees || []).join(', ');
            }
            
            conflictHtml += '<li class="list-group-item">';
            conflictHtml += '<strong>' + (conflict.policy_name || 'Unknown Policy') + '</strong> <span class="text-muted">(' + (conflict.policy_code || 'N/A') + ')</span>';
            if (conflict.matching_exit_types) {
                conflictHtml += '<br><small class="text-muted">Matching exit types: ' + conflict.matching_exit_types.join(', ') + '</small>';
            }
            if (details) {
                conflictHtml += '<br><small>' + details + '</small>';
            }
            conflictHtml += '</li>';
        });
        
        conflictHtml += '</ul>';
        conflictHtml += '<p><strong>Some assignments already exist for this exit type.</strong></p>';
        conflictHtml += '<p class="text-muted small">Override replaces the existing assignments. Skip keeps existing assignments and creates this policy only for departments without a matching policy.</p>';
        
        Swal.fire({
            title: '⚠️ Policy Conflict Detected!',
            html: conflictHtml,
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: allowSkip,
            confirmButtonColor: '#d33',
            denyButtonColor: '#0E8074',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Override It!',
            denyButtonText: 'Skip Assigned',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
            allowEscapeKey: false,
            width: 650,
            customClass: {
                popup: 'swal2-popup-custom'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                callback('override');
            } else if (result.isDenied) {
                callback('skip');
            }
        });
    }

    // Form submission with conflict checking
    $('#policyForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = new FormData(form[0]);
        
        // Show loading state
        Swal.fire({
            title: 'Checking for conflicts...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });
        
        // First, check for conflicts
        $.ajax({
            url: "{{ route('exit-policies.check-conflicts') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.close();
                if (response.has_conflicts) {
                    // Show conflict dialog
                    showConflictDialog(response.conflicts, form.find('input[name="assignment_type"]:checked').val() === 'all', function(action) {
                        $('#override_confirmed').val(action === 'override' ? 1 : 0);
                        $('#skip_conflicts').val(action === 'skip' ? 1 : 0);
                        form[0].submit();
                    });
                } else {
                    // No conflicts, submit normally
                    form[0].submit();
                }
            },
            error: function(xhr) {
                Swal.close();
                if (xhr.status === 409 && xhr.responseJSON && xhr.responseJSON.has_conflicts) {
                    // Show conflict dialog
                    showConflictDialog(xhr.responseJSON.conflicts, form.find('input[name="assignment_type"]:checked').val() === 'all', function(action) {
                        $('#override_confirmed').val(action === 'override' ? 1 : 0);
                        $('#skip_conflicts').val(action === 'skip' ? 1 : 0);
                        form[0].submit();
                    });
                } else if (xhr.status === 422) {
                    // Validation errors - let Laravel handle
                    form[0].submit();
                } else {
                    // Other error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'An error occurred while checking for conflicts.',
                        confirmButtonColor: '#dc3545'
                    });
                }
            }
        });
    });

    // Ensure override_confirmed is reset when form is reset or page reloads
    $(window).on('beforeunload', function() {
        $('#override_confirmed').val('0');
    });
});
</script>
@endsection