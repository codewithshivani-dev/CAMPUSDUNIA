{{-- resources/views/instituteAdmin/EmployeeExit/edit-policy.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Edit Exit Policy')

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

.exit-policy-page .form-control.readonly-field {
    background-color: #f1f4f9;
    cursor: not-allowed;
}

.exit-policy-page textarea.form-control {
    resize: vertical;
}

.exit-policy-page small.text-muted {
    color: var(--ep-muted) !important;
}

/* Read-only info box */
.exit-policy-page .info-box {
    background: var(--ep-primary-soft);
    border-left: 4px solid var(--ep-primary);
    padding: 1rem 1.25rem;
    border-radius: var(--ep-radius-md);
    margin-bottom: 1.5rem;
}

.exit-policy-page .info-box i {
    color: var(--ep-primary);
    margin-right: 0.5rem;
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

.exit-policy-page .chip-label {
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

.exit-policy-page .chip-label input[type="checkbox"] {
    display: none;
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

/* Assignment Read-Only Display */
.exit-policy-page .assignment-readonly {
    background: var(--ep-bg);
    border-radius: var(--ep-radius-md);
    padding: 1.25rem;
}

.exit-policy-page .assignment-readonly .assignment-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--ep-border);
}

.exit-policy-page .assignment-readonly .assignment-item:last-child {
    border-bottom: none;
}

.exit-policy-page .assignment-readonly .assignment-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.exit-policy-page .assignment-readonly .assignment-icon.all {
    background: #DCFCE7;
    color: #166534;
}

.exit-policy-page .assignment-readonly .assignment-icon.department {
    background: #FEF3C7;
    color: #92400E;
}

.exit-policy-page .assignment-readonly .assignment-icon.individual {
    background: #EEEDFC;
    color: #4338CA;
}

.exit-policy-page .assignment-readonly .assignment-details {
    flex: 1;
}

.exit-policy-page .assignment-readonly .assignment-details .label {
    font-weight: 600;
    font-size: 0.9rem;
}

.exit-policy-page .assignment-readonly .assignment-details .sub-label {
    font-size: 0.8rem;
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

/* FNF Processing Days */
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

/* Responsive */
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

    .exit-policy-page .notice-grid {
        grid-template-columns: 1fr;
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
                                <h3>Edit Exit Policy</h3>
                                <p>Update policy details, requirements, and notice period.</p>
                            </div>
                            <a href="{{ route('exit-policies.index') }}" class="ep-back-btn">
                                <i class="fas fa-arrow-left"></i> Back to Policies
                            </a>
                        </div>

                        <form action="{{ route('exit-policies.update', $policy->id) }}" method="POST" id="policyForm">
                            @csrf
                            @method('PUT')
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

                                {{-- Info Box --}}
                                <div class="info-box">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Note:</strong> Assignment details (who this policy applies to) cannot be changed. 
                                    To change assignments, please create a new policy.
                                </div>

                                {{-- Step 1: Basic Information --}}
                                <div class="ep-section">
                                    <span class="ep-section-eyebrow">Step 1</span>
                                    <div class="ep-section-title">Basic information</div>
                                    <div class="ep-section-desc">Update policy name and exit type.</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="policy_name">Policy Name <span class="text-danger">*</span></label>
                                                <input type="text" name="policy_name" id="policy_name"
                                                    class="form-control" placeholder="e.g., Standard Exit Policy"
                                                    value="{{ old('policy_name', $policy->policy_name) }}" required>
                                                @error('policy_name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="policy_code">Policy Code</label>
                                                <input type="text" name="policy_code" id="policy_code"
                                                    class="form-control readonly-field"
                                                    value="{{ $policy->policy_code }}" readonly disabled>
                                                <small class="text-muted">Policy code cannot be changed</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Exit Type <span class="text-danger">*</span></label>
                                                <div class="chip-group" id="exitTypeGroup">
                                                    @foreach($exitTypes as $type)
                                                    <label class="chip-label {{ $policy->exit_type == $type ? 'is-checked' : '' }}">
                                                        <input type="radio" class="chip-input" name="exit_type" value="{{ $type }}"
                                                            {{ $policy->exit_type == $type ? 'checked' : '' }}>
                                                        <i class="fas fa-check chip-check"></i>{{ $type }}
                                                    </label>
                                                    @endforeach
                                                </div>
                                                @error('exit_type')
                                                <span class="text-danger d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                {{-- Step 2: Assignment Configuration (READ ONLY) --}}
                                <div class="ep-section">
                                    <span class="ep-section-eyebrow">Step 2</span>
                                    <div class="ep-section-title">Assignment Configuration</div>
                                    <div class="ep-section-desc">This policy is currently assigned to:</div>

                                    <div class="assignment-readonly">
                                        @php
                                            $assignmentIcon = 'all';
                                            $assignmentLabel = 'All Departments';
                                            $assignmentIconClass = 'all';
                                            $assignmentDetails = 'Applied to all active departments';
                                            $assignedNames = [];
                                            
                                            if ($assignmentType == 'all') {
                                                $assignmentIcon = 'fa-globe';
                                                $assignmentLabel = 'All Departments';
                                                $assignmentIconClass = 'all';
                                                $assignmentDetails = 'Applied to all active departments (' . $assignedEmployees->count() . ' employees)';
                                            } elseif ($assignmentType == 'departments') {
                                                $assignmentIcon = 'fa-building';
                                                $assignmentLabel = 'Department Specific';
                                                $assignmentIconClass = 'department';
                                                $deptNames = array_values($assignmentDepartments);
                                                $assignedNames = $deptNames;
                                                $assignmentDetails = 'Applied to ' . count($deptNames) . ' department(s): ' . implode(', ', $deptNames) . ' (' . $assignedEmployees->count() . ' employees)';
                                            } elseif ($assignmentType == 'employees') {
                                                $assignmentIcon = 'fa-user';
                                                $assignmentLabel = 'Individual Employees';
                                                $assignmentIconClass = 'individual';
                                                $empNames = $assignedEmployees->pluck('name')->toArray();
                                                $assignedNames = $empNames;
                                                $displayNames = count($empNames) > 5 ? array_slice($empNames, 0, 5) : $empNames;
                                                $assignmentDetails = 'Applied to ' . count($empNames) . ' employee(s): ' . implode(', ', $displayNames) . (count($empNames) > 5 ? ' (+' . (count($empNames) - 5) . ' more)' : '');
                                            }
                                        @endphp

                                        <div class="assignment-item">
                                            <div class="assignment-icon {{ $assignmentIconClass }}">
                                                <i class="fas {{ $assignmentIcon }}"></i>
                                            </div>
                                            <div class="assignment-details">
                                                <div class="label">{{ $assignmentLabel }}</div>
                                                <div class="sub-label">{{ $assignmentDetails }}</div>
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden fields to preserve assignment data -->
                                        @if($assignmentType == 'all')
                                            <input type="hidden" name="assignment_type" value="all">
                                        @elseif($assignmentType == 'departments')
                                            <input type="hidden" name="assignment_type" value="departments">
                                            @foreach($selectedDepartmentIds as $deptId)
                                                <input type="hidden" name="department_ids[]" value="{{ $deptId }}">
                                            @endforeach
                                        @elseif($assignmentType == 'employees')
                                            <input type="hidden" name="assignment_type" value="employees">
                                            @foreach($selectedEmployeeIds as $empId)
                                                <input type="hidden" name="employee_ids[]" value="{{ $empId }}">
                                            @endforeach
                                        @else
                                            <input type="hidden" name="assignment_type" value="unassigned">
                                        @endif

                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-lock"></i> Assignment details are read-only. To change assignments, please create a new policy.
                                            </small>
                                        </div>
                                    </div>

                                    {{-- Assignment Details (Date fields - Editable) --}}
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="effective_date">Effective Date <span class="text-danger">*</span></label>
                                                <input type="date" name="effective_date" id="effective_date"
                                                    class="form-control"
                                                    value="{{ old('effective_date', $assignmentData ? $assignmentData->effective_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                                                @error('effective_date')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expiry_date">Expiry Date</label>
                                                <input type="date" name="expiry_date" id="expiry_date"
                                                    class="form-control" value="{{ old('expiry_date', $assignmentData && $assignmentData->expiry_date ? $assignmentData->expiry_date->format('Y-m-d') : '') }}">
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
                                            placeholder="Additional notes about this policy assignment">{{ old('assignment_notes', $assignmentData ? $assignmentData->notes : '') }}</textarea>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                {{-- Step 3: Notice Period Configuration --}}
                                <div class="ep-section">
                                    <span class="ep-section-eyebrow">Step 3</span>
                                    <div class="ep-section-title">Notice period configuration <span class="text-danger">*</span></div>
                                    <div class="ep-section-desc">Set the default notice period for this exit type, and override it for specific employment types if needed.</div>

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
                                                        <label for="default_notice_period">Notice Period <span class="text-danger">*</span></label>
                                                        <select name="default_notice_period" id="default_notice_period" class="form-control">
                                                            <option value="30" {{ old('default_notice_period', $policy->default_notice_period) == 30 ? 'selected' : '' }}>30 Days</option>
                                                            <option value="45" {{ old('default_notice_period', $policy->default_notice_period) == 45 ? 'selected' : '' }}>45 Days</option>
                                                            <option value="60" {{ old('default_notice_period', $policy->default_notice_period) == 60 ? 'selected' : '' }}>60 Days</option>
                                                            <option value="90" {{ old('default_notice_period', $policy->default_notice_period) == 90 ? 'selected' : '' }}>90 Days</option>
                                                            <option value="custom" {{ old('default_notice_period', $policy->default_notice_period) == 'custom' || (is_int($policy->default_notice_period) && !in_array($policy->default_notice_period, [30,45,60,90])) ? 'selected' : '' }}>Custom</option>
                                                        </select>
                                                        @error('default_notice_period')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6" id="default_custom_days_section"
                                                    style="display: {{ (old('default_notice_period', $policy->default_notice_period) == 'custom' || (is_int($policy->default_notice_period) && !in_array($policy->default_notice_period, [30,45,60,90]))) ? '' : 'none' }};">
                                                    <div class="form-group">
                                                        <label for="default_custom_days">Custom Days <span class="text-danger">*</span></label>
                                                        <input type="number" name="default_custom_days"
                                                            id="default_custom_days" class="form-control"
                                                            placeholder="Enter custom days" min="1" max="365"
                                                            value="{{ old('default_custom_days', (is_int($policy->default_notice_period) && !in_array($policy->default_notice_period, [30,45,60,90])) ? $policy->default_notice_period : '') }}">
                                                        @error('default_custom_days')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="req-card accent-indigo">
                                        <div class="req-header">
                                            <div class="req-icon"><i class="fas fa-users"></i></div>
                                            <div>
                                                <h6>Employment type overrides</h6>
                                                <small>Optional — override the default for specific employment types</small>
                                            </div>
                                        </div>
                                        <div class="req-body">
                                            <div class="notice-grid">
                                                @foreach($employmentTypes as $type)
                                                <div class="notice-tile">
                                                    <label class="notice-tile-label" for="notice_period_{{ Str::slug($type) }}">
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
                                                        <option value="30" {{ isset($employmentNoticePeriods[$type]) && $employmentNoticePeriods[$type] == 30 ? 'selected' : '' }}>30 Days</option>
                                                        <option value="45" {{ isset($employmentNoticePeriods[$type]) && $employmentNoticePeriods[$type] == 45 ? 'selected' : '' }}>45 Days</option>
                                                        <option value="60" {{ isset($employmentNoticePeriods[$type]) && $employmentNoticePeriods[$type] == 60 ? 'selected' : '' }}>60 Days</option>
                                                        <option value="90" {{ isset($employmentNoticePeriods[$type]) && $employmentNoticePeriods[$type] == 90 ? 'selected' : '' }}>90 Days</option>
                                                        <option value="custom" {{ isset($employmentNoticePeriods[$type]) && $employmentNoticePeriods[$type] == 'custom' ? 'selected' : '' }}>Custom</option>
                                                    </select>
                                                    <div class="custom-days-input" style="display: {{ isset($employmentNoticePeriods[$type]) && $employmentNoticePeriods[$type] == 'custom' ? '' : 'none' }};">
                                                        <input type="number" name="employment_custom_days[{{ $type }}]"
                                                            class="form-control form-control-sm"
                                                            placeholder="Custom days" min="1" max="365"
                                                            value="{{ old('employment_custom_days.'.$type, $employmentCustomDays[$type] ?? '') }}">
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
                                    <div class="ep-section-title">Exit requirements</div>
                                    <div class="ep-section-desc">Configure the requirements that employees must complete during exit.</div>

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
                                                    {{ old('exit_interview_required', $policy->exit_interview_required) ? 'checked' : '' }}>
                                                <span class="track"></span>
                                            </label>
                                        </div>
                                        <div class="exit-interview-config req-body"
                                            style="{{ old('exit_interview_required', $policy->exit_interview_required) ? '' : 'display: none;' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="interview_days">Interview Timeline (Days)</label>
                                                        <input type="number" name="interview_days" id="interview_days"
                                                            class="form-control" placeholder="e.g., 5" min="1" max="30"
                                                            value="{{ old('interview_days', $policy->interview_days ?? 5) }}">
                                                        <small class="text-muted">Days before exit to schedule the interview</small>
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
                                                    {{ old('fnf_required', $policy->fnf_required) ? 'checked' : '' }}>
                                                <span class="track"></span>
                                            </label>
                                        </div>
                                        <div class="fnf-config req-body"
                                            style="{{ old('fnf_required', $policy->fnf_required) ? '' : 'display: none;' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fnf_settlement_type">Settlement Type <span class="text-danger">*</span></label>
                                                        <select name="fnf_settlement_type" id="fnf_settlement_type" class="form-control">
                                                            <option value="standard" {{ old('fnf_settlement_type', $policy->fnf_settlement_type) == 'standard' ? 'selected' : '' }}>Standard Settlement</option>
                                                            <option value="expedited" {{ old('fnf_settlement_type', $policy->fnf_settlement_type) == 'expedited' ? 'selected' : '' }}>Expedited Settlement</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="fnf_processing_days">FNF Processing Days <span class="text-danger">*</span></label>
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
                                                            $selectedType = old('fnf_settlement_type', $policy->fnf_settlement_type ?? 'standard');
                                                            $selectedDays = old('fnf_processing_days', $policy->fnf_processing_days);
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
                                                    $fnfItemsList = [
                                                        'salary_settlement' => 'Salary Settlement',
                                                        'leave_encashment' => 'Leave Encashment',
                                                        'bonus_settlement' => 'Bonus/Incentive Settlement',
                                                        'reimbursement' => 'Reimbursement Claims',
                                                        'pf_settlement' => 'PF Settlement',
                                                        'esi_settlement' => 'ESI Settlement',
                                                        'gratuity' => 'Gratuity',
                                                        'other_dues' => 'Other Dues'
                                                    ];
                                                    @endphp
                                                    @foreach($fnfItemsList as $key => $label)
                                                    <label class="chip-label {{ in_array($key, $fnfItems ?? []) ? 'is-checked' : '' }}">
                                                        <input type="checkbox" class="chip-input" name="fnf_items[]" value="{{ $key }}"
                                                            {{ in_array($key, $fnfItems ?? []) ? 'checked' : '' }}>
                                                        <i class="fas fa-check chip-check"></i>{{ $label }}
                                                    </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
                                                    {{ old('kt_required', $policy->kt_required) ? 'checked' : '' }}>
                                                <span class="track"></span>
                                            </label>
                                        </div>
                                        <div class="kt-config req-body"
                                            style="{{ old('kt_required', $policy->kt_required) ? '' : 'display: none;' }}">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="kt_days">KT Duration (Days) <span class="text-danger">*</span></label>
                                                        <select name="kt_days" id="kt_days" class="form-control">
                                                            <option value="">Select Duration</option>
                                                            <option value="3" {{ old('kt_days', $policy->kt_days) == 3 ? 'selected' : '' }}>3 Days</option>
                                                            <option value="5" {{ old('kt_days', $policy->kt_days) == 5 ? 'selected' : '' }}>5 Days</option>
                                                            <option value="7" {{ old('kt_days', $policy->kt_days) == 7 ? 'selected' : '' }}>7 Days</option>
                                                            <option value="10" {{ old('kt_days', $policy->kt_days) == 10 ? 'selected' : '' }}>10 Days</option>
                                                            <option value="15" {{ old('kt_days', $policy->kt_days) == 15 ? 'selected' : '' }}>15 Days</option>
                                                            <option value="30" {{ old('kt_days', $policy->kt_days) == 30 ? 'selected' : '' }}>30 Days</option>
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
                                                    $ktRequirementsList = [
                                                        'documentation' => 'Documentation Handover',
                                                        'project_handover' => 'Project/Work Handover',
                                                        'code_handover' => 'Code/System Handover',
                                                        'client_handover' => 'Client/Stakeholder Handover',
                                                        'process_handover' => 'Process Handover',
                                                        'training' => 'Training & Support',
                                                        'knowledge_docs' => 'Knowledge Base Documentation'
                                                    ];
                                                    @endphp
                                                    @foreach($ktRequirementsList as $key => $label)
                                                    <label class="chip-label {{ in_array($key, $ktRequirements ?? []) ? 'is-checked' : '' }}">
                                                        <input type="checkbox" class="chip-input" name="kt_requirements[]" value="{{ $key }}"
                                                            {{ in_array($key, $ktRequirements ?? []) ? 'checked' : '' }}>
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
                                                <h6>Additional requirements</h6>
                                                <small>Optional offboarding tasks to include</small>
                                            </div>
                                        </div>
                                        <div class="req-body">
                                            <div class="chip-group">
                                                @php
                                                $additionalRequirementsList = [
                                                    'asset_return' => 'Asset Return (Laptop, Phone, etc.)',
                                                    'access_revocation' => 'Access Revocation (Email, Systems, etc.)',
                                                    'id_card_return' => 'ID Card Return',
                                                    'visa_cancellation' => 'Visa/Iqama Cancellation (If Applicable)',
                                                    'exit_reentry_visa' => 'Exit Re-entry Visa Processing',
                                                    'medical_certificate' => 'Medical Certificate',
                                                    'police_clearance' => 'Police Clearance Certificate',
                                                    'housing_handover' => 'Housing/Accommodation Handover'
                                                ];
                                                @endphp
                                                @foreach($additionalRequirementsList as $key => $label)
                                                <label class="chip-label {{ in_array($key, $additionalRequirements ?? []) ? 'is-checked' : '' }}">
                                                    <input type="checkbox" class="chip-input" name="additional_requirements[]" value="{{ $key }}"
                                                        {{ in_array($key, $additionalRequirements ?? []) ? 'checked' : '' }}>
                                                    <i class="fas fa-check chip-check"></i>{{ $label }}
                                                </label>
                                                @endforeach
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
                                                        <label for="clearance_workflow">Clearance Workflow <span class="text-danger">*</span></label>
                                                        <select name="clearance_workflow" id="clearance_workflow" class="form-control">
                                                            <option value="sequential" {{ old('clearance_workflow', $policy->clearance_workflow) == 'sequential' ? 'selected' : '' }}>Sequential (Step by Step)</option>
                                                            <option value="hybrid" {{ old('clearance_workflow', $policy->clearance_workflow) == 'hybrid' ? 'selected' : '' }}>Hybrid (Mix of Sequential &amp; Parallel)</option>
                                                        </select>
                                                        @error('clearance_workflow')
                                                        <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-0">
                                                        <label for="clearance_days">Clearance Processing Days <span class="text-danger">*</span></label>
                                                        <input type="number" name="clearance_days" id="clearance_days"
                                                            class="form-control" placeholder="Enter number of days"
                                                            min="1" max="90" value="{{ old('clearance_days', $policy->clearance_days ?? 7) }}">
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
                                    <div class="ep-section-desc">Optional context and terms shown when this policy is assigned.</div>
                                    <div class="form-group">
                                        <label for="description">Policy Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="3"
                                            placeholder="Brief description of this policy">{{ old('description', $policy->description) }}</textarea>
                                        @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="terms_conditions">Terms &amp; Conditions</label>
                                        <textarea name="terms_conditions" id="terms_conditions" class="form-control" rows="3">{{ old('terms_conditions', $policy->terms_conditions) }}</textarea>
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
                                                {{ old('is_active', $policy->is_active) ? 'checked' : '' }}>
                                            <span class="track"></span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Hidden override confirmation field --}}
                                <input type="hidden" name="override_confirmed" id="override_confirmed" value="0">
                            </div>

                            <div class="ep-card-footer">
                                <button type="submit" class="btn-ep-primary" id="submitBtn">
                                    <i class="fas fa-save"></i> Update Policy
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
    // Chip checklist styling for checkbox only (not radio)
    $('.chip-label').on('click', function(e) {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.length > 0) {
            checkbox.prop('checked', !checkbox.prop('checked'));
            $(this).toggleClass('is-checked', checkbox.prop('checked'));
        }
    });

    // Exit Interview toggle
    $('#exit_interview_required').on('change', function() {
        if ($(this).is(':checked')) {
            $('.exit-interview-config').show();
        } else {
            $('.exit-interview-config').hide();
        }
    });

    // FNF toggle
    $('#fnf_required').on('change', function() {
        if ($(this).is(':checked')) {
            $('.fnf-config').show();
            var type = $('#fnf_settlement_type').val();
            $('.fnf-days-option').hide();
            $('.fnf-type-' + type).show();
        } else {
            $('.fnf-config').hide();
        }
    });

    // FNF Settlement Type change
    $('#fnf_settlement_type').on('change', function() {
        var type = $(this).val();
        $('.fnf-days-option').hide();
        $('.fnf-type-' + type).show();
    });

    // KT toggle
    $('#kt_required').on('change', function() {
        if ($(this).is(':checked')) {
            $('.kt-config').show();
            $('#kt_days').prop('required', true);
        } else {
            $('.kt-config').hide();
            $('#kt_days').prop('required', false);
        }
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

    // Initialize default custom days visibility on page load
    if ($('#default_notice_period').val() === 'custom') {
        $('#default_custom_days_section').show();
        $('#default_custom_days').prop('required', true);
    }

    // Initialize FNF days visibility on load
    if ($('#fnf_required').is(':checked')) {
        var initialType = $('#fnf_settlement_type').val();
        $('.fnf-days-option').hide();
        $('.fnf-type-' + initialType).show();
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

    // Form validation
    $('#policyForm').on('submit', function(e) {
        var isValid = true;
        var errorMessages = [];
        
        var noticePeriod = $('#default_notice_period').val();
        if (noticePeriod === 'custom') {
            var customDays = $('#default_custom_days').val();
            if (!customDays || customDays < 1 || customDays > 365) {
                errorMessages.push('Please enter a valid custom days (1-365)');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errorMessages.join('<br>'),
                confirmButtonColor: '#dc3545'
            });
            return false;
        }
    });
});
</script>
@endsection