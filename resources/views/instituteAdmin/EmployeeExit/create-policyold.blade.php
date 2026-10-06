{{-- resources/views/instituteAdmin/EmployeeExit/create-policy.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Create Exit Policy')

@section('content')
<style>
    .policy-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }
    .policy-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .policy-card.selected {
        border-color: #007bff;
        background-color: #f8f9ff;
    }
    .policy-card .card-body {
        padding: 1.5rem;
    }
    .policy-card .icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }
    
    /* Employment Type Notice Period Styling */
    .employment-notice-card {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        background: #fff;
    }
    .employment-notice-card:hover {
        border-color: #c0c8d4;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .employment-notice-card .employment-type-label {
        font-weight: 600;
        font-size: 1rem;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .employment-notice-card .employment-type-label .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
    .notice-period-options {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }
    .notice-period-options .btn-group-toggle {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .notice-period-btn {
        padding: 0.4rem 1rem;
        border: 2px solid #dee2e6;
        border-radius: 20px;
        background: #fff;
        color: #495057;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        font-weight: 500;
        min-width: 70px;
        text-align: center;
        user-select: none;
    }
    .notice-period-btn:hover {
        border-color: #007bff;
        background: #f8f9ff;
        transform: translateY(-1px);
    }
    .notice-period-btn.active {
        border-color: #007bff;
        background: #007bff;
        color: #fff;
        box-shadow: 0 2px 8px rgba(0,123,255,0.3);
    }
    .notice-period-btn.custom-btn {
        border-style: dashed;
    }
    .notice-period-btn.custom-btn.active {
        border-style: solid;
    }
    .notice-period-btn input[type="radio"] {
        display: none;
    }
    .custom-days-wrapper {
        display: none;
        margin-top: 0.75rem;
        padding: 0.75rem 1rem;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 3px solid #007bff;
    }
    .custom-days-wrapper.show {
        display: block;
    }
    .custom-days-wrapper .input-group {
        max-width: 200px;
    }
    .custom-days-wrapper .input-group-text {
        background: #fff;
        border-right: none;
    }
    .custom-days-wrapper .form-control {
        border-left: none;
    }
    
    /* Default notice period styling */
    .default-notice-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .default-notice-card .notice-period-options {
        margin-top: 0.5rem;
    }
    
    .employment-type-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e7f1ff;
        color: #007bff;
        font-size: 0.875rem;
    }
    
    .section-divider {
        border-top: 2px dashed #dee2e6;
        margin: 1.5rem 0;
    }

    .policy-card.border-danger {
        border-color: #dc3545 !important;
        background-color: #fff5f5 !important;
    }

    .policy-card .policy-radio {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 0.5rem 0;
    }
    .checkbox-group .custom-control {
        margin-right: 1rem;
    }
    .checkbox-group .custom-control-label {
        cursor: pointer;
    }
    
    .exit-requirement-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        background: #fff;
        transition: all 0.3s ease;
    }
    .exit-requirement-card:hover {
        border-color: #c0c8d4;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .exit-requirement-card .requirement-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .exit-requirement-card .requirement-header .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .exit-requirement-card .requirement-header .icon-circle.primary {
        background: #e7f1ff;
        color: #007bff;
    }
    .exit-requirement-card .requirement-header .icon-circle.success {
        background: #d4edda;
        color: #28a745;
    }
    .exit-requirement-card .requirement-header .icon-circle.warning {
        background: #fff3cd;
        color: #ffc107;
    }
    .exit-requirement-card .requirement-header .icon-circle.info {
        background: #d1ecf1;
        color: #17a2b8;
    }
    .exit-requirement-card .requirement-header .icon-circle.danger {
        background: #f8d7da;
        color: #dc3545;
    }

    .template-selector {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .template-selector:hover {
        border-color: #007bff;
        background: #f8f9ff;
    }
    .template-selector.active {
        border-color: #007bff;
        background: #f8f9ff;
    }
    .template-selector .template-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .template-selector .template-desc {
        font-size: 0.875rem;
        color: #6c757d;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Create New Exit Policy</h3>
                    <div class="card-tools">
                        <a href="{{ route('exit-policies.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Policies
                        </a>
                    </div>
                </div>
                <form action="{{ route('exit-policies.store') }}" method="POST" id="policyForm">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Policy Type Selection --}}
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h5>Select Policy Type <span class="text-danger">*</span></h5>
                                @error('policy_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="card policy-card {{ old('policy_type') == 'department_wise' ? 'selected' : '' }}" 
                                    id="card-department-wise" 
                                    data-policy-type="department_wise">
                                    <div class="card-body text-center">
                                        <div class="icon text-primary">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <h5>Department Wise</h5>
                                        <p class="text-muted small">Apply policy to all employees in a department</p>
                                        <input type="radio" name="policy_type" value="department_wise" 
                                            id="policy-department-wise" 
                                            class="policy-radio"
                                            {{ old('policy_type') == 'department_wise' ? 'checked' : '' }}>
                                        <label for="policy-department-wise" class="btn btn-outline-primary btn-sm mt-2">
                                            <i class="fas fa-check-circle"></i> Select
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card policy-card {{ old('policy_type') == 'employee_wise' ? 'selected' : '' }}" 
                                    id="card-employee-wise"
                                    data-policy-type="employee_wise">
                                    <div class="card-body text-center">
                                        <div class="icon text-success">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <h5>Employee Wise</h5>
                                        <p class="text-muted small">Apply policy to specific employees</p>
                                        <input type="radio" name="policy_type" value="employee_wise" 
                                            id="policy-employee-wise" 
                                            class="policy-radio"
                                            {{ old('policy_type') == 'employee_wise' ? 'checked' : '' }}>
                                        <label for="policy-employee-wise" class="btn btn-outline-success btn-sm mt-2">
                                            <i class="fas fa-check-circle"></i> Select
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Department Selection --}}
                        <div class="row" id="departmentSection" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department_id">Select Department <span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-control">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->department_id }}" 
                                                {{ old('department_id') == $department->department_id ? 'selected' : '' }}>
                                                {{ $department->department }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Employee Selection --}}
                        <div class="row" id="employeeSection" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">Select Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" id="employee_id" class="form-control">
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->employee_id }}" 
                                                {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                                {{ $employee->name }} ({{ $employee->employee_code }}) - {{ $employee->employment_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- Exit Requirements Configuration --}}
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Exit Requirements Configuration <span class="text-danger">*</span></h5>
                                <p class="text-muted small">Configure all exit requirements including Exit Interview, FNF, KT, etc.</p>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Note:</strong> Configure the exit requirements that employees must complete before their final settlement.
                                </div>

                                {{-- Exit Interview --}}
                                <div class="exit-requirement-card">
                                    <div class="requirement-header">
                                        <div class="icon-circle primary">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Exit Interview</h6>
                                            <small class="text-muted">Configure exit interview requirements</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="exit_interview_required" 
                                                        name="exit_interview_required" value="1" 
                                                        {{ old('exit_interview_required') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="exit_interview_required">Require Exit Interview</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="interviewer_group" style="{{ old('exit_interview_required') ? '' : 'display: none;' }}">
                                                <label for="interviewer_id">Assigned Interviewer <span class="text-danger">*</span></label>
                                                <select name="interviewer_id" id="interviewer_id" class="form-control">
                                                    <option value="">Select Interviewer</option>
                                                    @foreach($interviewers ?? [] as $interviewer)
                                                        <option value="{{ $interviewer->employee_id }}" 
                                                            {{ old('interviewer_id') == $interviewer->employee_id ? 'selected' : '' }}>
                                                            {{ $interviewer->name }} ({{ $interviewer->employee_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('interviewer_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="interview_questions_group" style="{{ old('exit_interview_required') ? '' : 'display: none;' }}">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Interview Questions Template</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="template-selector" data-template="standard">
                                                            <div class="template-title">Standard Questions</div>
                                                            <div class="template-desc">Pre-defined standard exit interview questions</div>
                                                            <div class="mt-2">
                                                                <span class="badge badge-info">10 Questions</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="template-selector" data-template="detailed">
                                                            <div class="template-title">Detailed Questions</div>
                                                            <div class="template-desc">Comprehensive questions covering all aspects</div>
                                                            <div class="mt-2">
                                                                <span class="badge badge-info">20 Questions</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="interview_template" id="interview_template" value="{{ old('interview_template', 'standard') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- FNF (Full and Final) --}}
                                <div class="exit-requirement-card">
                                    <div class="requirement-header">
                                        <div class="icon-circle warning">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">FNF (Full and Final Settlement)</h6>
                                            <small class="text-muted">Configure FNF settlement requirements</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="fnf_required" 
                                                        name="fnf_required" value="1" 
                                                        {{ old('fnf_required') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="fnf_required">Require FNF Settlement</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="fnf_days_group" style="{{ old('fnf_required') ? '' : 'display: none;' }}">
                                                <label for="fnf_processing_days">FNF Processing Days <span class="text-danger">*</span></label>
                                                <select name="fnf_processing_days" id="fnf_processing_days" class="form-control">
                                                    <option value="">Select Processing Days</option>
                                                    <option value="7" {{ old('fnf_processing_days') == 7 ? 'selected' : '' }}>7 Days</option>
                                                    <option value="15" {{ old('fnf_processing_days') == 15 ? 'selected' : '' }}>15 Days</option>
                                                    <option value="30" {{ old('fnf_processing_days') == 30 ? 'selected' : '' }}>30 Days</option>
                                                    <option value="45" {{ old('fnf_processing_days') == 45 ? 'selected' : '' }}>45 Days</option>
                                                    <option value="60" {{ old('fnf_processing_days') == 60 ? 'selected' : '' }}>60 Days</option>
                                                </select>
                                                @error('fnf_processing_days')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="fnf_items_group" style="{{ old('fnf_required') ? '' : 'display: none;' }}">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>FNF Items Checklist</label>
                                                <div class="checkbox-group">
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
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" 
                                                                id="fnf_item_{{ $key }}" 
                                                                name="fnf_items[]" value="{{ $key }}"
                                                                {{ in_array($key, $selectedFnfItems) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="fnf_item_{{ $key }}">
                                                                {{ $label }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- KT (Knowledge Transfer) --}}
                                <div class="exit-requirement-card">
                                    <div class="requirement-header">
                                        <div class="icon-circle success">
                                            <i class="fas fa-chalkboard-teacher"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">KT (Knowledge Transfer)</h6>
                                            <small class="text-muted">Configure knowledge transfer requirements</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="kt_required" 
                                                        name="kt_required" value="1" 
                                                        {{ old('kt_required') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="kt_required">Require Knowledge Transfer</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="kt_days_group" style="{{ old('kt_required') ? '' : 'display: none;' }}">
                                                <label for="kt_days">KT Duration (Days) <span class="text-danger">*</span></label>
                                                <select name="kt_days" id="kt_days" class="form-control">
                                                    <option value="">Select Duration</option>
                                                    <option value="3" {{ old('kt_days') == 3 ? 'selected' : '' }}>3 Days</option>
                                                    <option value="5" {{ old('kt_days') == 5 ? 'selected' : '' }}>5 Days</option>
                                                    <option value="7" {{ old('kt_days') == 7 ? 'selected' : '' }}>7 Days</option>
                                                    <option value="10" {{ old('kt_days') == 10 ? 'selected' : '' }}>10 Days</option>
                                                    <option value="15" {{ old('kt_days') == 15 ? 'selected' : '' }}>15 Days</option>
                                                    <option value="30" {{ old('kt_days') == 30 ? 'selected' : '' }}>30 Days</option>
                                                </select>
                                                @error('kt_days')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="kt_handover_group" style="{{ old('kt_required') ? '' : 'display: none;' }}">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>KT Handover Requirements</label>
                                                <div class="checkbox-group">
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
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" 
                                                                id="kt_requirement_{{ $key }}" 
                                                                name="kt_requirements[]" value="{{ $key }}"
                                                                {{ in_array($key, $selectedKtRequirements) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="kt_requirement_{{ $key }}">
                                                                {{ $label }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="kt_approver_group" style="{{ old('kt_required') ? '' : 'display: none;' }}">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="kt_approver">KT Approver <span class="text-danger">*</span></label>
                                                <select name="kt_approver" id="kt_approver" class="form-control">
                                                    <option value="">Select Approver</option>
                                                    @foreach($approvers ?? [] as $approver)
                                                        <option value="{{ $approver->employee_id }}" 
                                                            {{ old('kt_approver') == $approver->employee_id ? 'selected' : '' }}>
                                                            {{ $approver->name }} ({{ $approver->employee_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('kt_approver')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Additional Exit Requirements --}}
                                <div class="exit-requirement-card">
                                    <div class="requirement-header">
                                        <div class="icon-circle info">
                                            <i class="fas fa-tasks"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Additional Requirements</h6>
                                            <small class="text-muted">Configure additional exit requirements</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="checkbox-group">
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
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" 
                                                            id="additional_req_{{ $key }}" 
                                                            name="additional_requirements[]" value="{{ $key }}"
                                                            {{ in_array($key, $selectedAdditional) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="additional_req_{{ $key }}">
                                                            {{ $label }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Exit Clearance Workflow --}}
                                <div class="exit-requirement-card">
                                    <div class="requirement-header">
                                        <div class="icon-circle danger">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Exit Clearance Workflow</h6>
                                            <small class="text-muted">Configure exit clearance workflow</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="clearance_workflow">Clearance Workflow <span class="text-danger">*</span></label>
                                                <select name="clearance_workflow" id="clearance_workflow" class="form-control">
                                                    <option value="">Select Workflow</option>
                                                    <option value="sequential" {{ old('clearance_workflow') == 'sequential' ? 'selected' : '' }}>
                                                        Sequential (Step by Step)
                                                    </option>
                                                    <option value="parallel" {{ old('clearance_workflow') == 'parallel' ? 'selected' : '' }}>
                                                        Parallel (All Departments Simultaneously)
                                                    </option>
                                                    <option value="hybrid" {{ old('clearance_workflow') == 'hybrid' ? 'selected' : '' }}>
                                                        Hybrid (Mix of Sequential & Parallel)
                                                    </option>
                                                </select>
                                                @error('clearance_workflow')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="clearance_days">Clearance Processing Days <span class="text-danger">*</span></label>
                                                <input type="number" name="clearance_days" id="clearance_days" 
                                                    class="form-control" placeholder="Enter number of days"
                                                    min="1" max="90"
                                                    value="{{ old('clearance_days', 7) }}">
                                                @error('clearance_days')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- Notice Period Configuration --}}
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Notice Period Configuration <span class="text-danger">*</span></h5>
                                <p class="text-muted small">Configure notice periods for different employment types</p>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>How it works:</strong> Set a default notice period that applies to all employment types. 
                                    You can then customize notice periods for specific employment types.
                                </div>

                                {{-- Default Notice Period --}}
                                <div class="default-notice-card">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <h6 class="mb-0">Default Notice Period</h6>
                                        <span class="text-muted ms-2 small">(Applies to all employment types unless overridden)</span>
                                    </div>
                                    <div class="notice-period-options mt-2">
                                        <div class="btn-group-toggle">
                                            @foreach($noticePeriodOptions as $days)
                                                <label class="notice-period-btn {{ old('default_notice_period', 30) == $days ? 'active' : '' }}"
                                                       for="default_notice_period_{{ $days }}"
                                                       onclick="selectDefaultNoticePeriod('{{ $days }}')">
                                                    <input type="radio" id="default_notice_period_{{ $days }}" name="default_notice_period" value="{{ $days }}"
                                                        {{ old('default_notice_period', 30) == $days ? 'checked' : '' }}>
                                                    {{ $days }} Days
                                                </label>
                                            @endforeach
                                            <label class="notice-period-btn custom-btn {{ old('default_notice_period') == 'custom' ? 'active' : '' }}"
                                                   for="default_notice_period_custom"
                                                   onclick="selectDefaultNoticePeriod('custom')">
                                                <input type="radio" id="default_notice_period_custom" name="default_notice_period" value="custom"
                                                    {{ old('default_notice_period') == 'custom' ? 'checked' : '' }}>
                                                <i class="fas fa-pencil-alt"></i> Custom
                                            </label>
                                        </div>
                                    </div>
                                    <div class="custom-days-wrapper {{ old('default_notice_period') == 'custom' ? 'show' : '' }}" id="defaultCustomDays">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="number" name="default_custom_days" class="form-control" 
                                                   placeholder="Enter custom days" min="1" max="365"
                                                   value="{{ old('default_custom_days') }}">
                                            <span class="input-group-text">Days</span>
                                        </div>
                                        @error('default_custom_days')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @error('default_notice_period')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Employment Type Specific Notice Periods --}}
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">Employment Type Specific Notice Periods</h6>
                                        <span class="text-muted small">(Optional - Override default for specific types)</span>
                                    </div>
                                    
                                    <div id="employmentNoticePeriods">
                                        @foreach($employmentTypes as $type)
                                            <div class="employment-notice-card" id="notice-card-{{ Str::slug($type) }}">
                                                <div class="row align-items-center">
                                                    <div class="col-md-3">
                                                        <div class="employment-type-label">
                                                            <span class="employment-type-icon">
                                                                <i class="fas {{ 
                                                                    $type == 'Full-time' ? 'fa-clock' : 
                                                                    ($type == 'Part-time' ? 'fa-clock' : 
                                                                    ($type == 'Contract-based' ? 'fa-file-signature' : 
                                                                    ($type == 'Probation-Period' ? 'fa-user-graduate' : 'fa-users'))) 
                                                                }}"></i>
                                                            </span>
                                                            {{ $type }}
                                                            <span class="badge badge-secondary">Optional</span>
                                                        </div>
                                                        <input type="hidden" name="employment_types[]" value="{{ $type }}">
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="notice-period-options">
                                                            <div class="btn-group-toggle">
                                                                <label class="notice-period-btn" onclick="selectEmploymentNotice('{{ $type }}', 'default')">
                                                                    <input type="radio" name="employment_notice_periods[{{ $type }}]" value="" 
                                                                        {{ old('employment_notice_periods.'.$type) == '' ? 'checked' : '' }}>
                                                                    Use Default
                                                                </label>
                                                                @foreach($noticePeriodOptions as $days)
                                                                    <label class="notice-period-btn {{ old('employment_notice_periods.'.$type) == $days ? 'active' : '' }}" 
                                                                           onclick="selectEmploymentNotice('{{ $type }}', {{ $days }})">
                                                                        <input type="radio" name="employment_notice_periods[{{ $type }}]" value="{{ $days }}" 
                                                                            {{ old('employment_notice_periods.'.$type) == $days ? 'checked' : '' }}>
                                                                        {{ $days }} Days
                                                                    </label>
                                                                @endforeach
                                                                <label class="notice-period-btn custom-btn {{ old('employment_notice_periods.'.$type) == 'custom' ? 'active' : '' }}" 
                                                                       onclick="selectEmploymentNotice('{{ $type }}', 'custom')">
                                                                    <input type="radio" name="employment_notice_periods[{{ $type }}]" value="custom" 
                                                                        {{ old('employment_notice_periods.'.$type) == 'custom' ? 'checked' : '' }}>
                                                                    <i class="fas fa-pencil-alt"></i> Custom
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="custom-days-wrapper" id="custom-days-{{ Str::slug($type) }}">
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                                <input type="number" name="employment_custom_days[{{ $type }}]" class="form-control" 
                                                                       placeholder="Enter custom days" min="1" max="365"
                                                                       value="{{ old('employment_custom_days.'.$type) }}">
                                                                <span class="input-group-text">Days</span>
                                                            </div>
                                                            @error('employment_custom_days.'.$type)
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                        <small class="text-muted" id="notice-preview-{{ Str::slug($type) }}">
                                                            {{ old('employment_notice_periods.'.$type) ? 
                                                                (old('employment_notice_periods.'.$type) == 'custom' ? 
                                                                    old('employment_custom_days.'.$type).' days' : 
                                                                    old('employment_notice_periods.'.$type).' days') : 
                                                                'Default ('.old('default_notice_period', 30).' days)' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- Additional Options --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Policy Description</label>
                                    <textarea name="description" id="description" 
                                              class="form-control mt-2" rows="3" 
                                              placeholder="Brief description of this policy">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Terms & Conditions --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="terms_conditions">Terms & Conditions</label>
                                    <textarea name="terms_conditions" id="terms_conditions" 
                                              class="form-control mt-2" rows="3">{{ old('terms_conditions') }}</textarea>
                                    @error('terms_conditions')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Policy Status --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" 
                                            name="is_active" value="1" 
                                            {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Activate Policy Immediately</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Create Policy
                        </button>
                        <a href="{{ route('exit-policies.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Flag to prevent recursive calls
        var isProcessing = false;

        // Initialize policy type selection
        var initialType = $('input[name="policy_type"]:checked').val();
        if (initialType) {
            togglePolicySections(initialType);
            $('.policy-card').removeClass('selected');
            $('.policy-card[data-policy-type="' + initialType + '"]').addClass('selected');
        }

        // Initialize default custom days visibility
        var defaultNoticeVal = $('input[name="default_notice_period"]:checked').val();
        if (defaultNoticeVal === 'custom') {
            $('#defaultCustomDays').addClass('show');
            $('#defaultCustomDays').find('input').prop('required', true);
        }

        // Initialize employment type custom days visibility
        $('.employment-notice-card').each(function() {
            var card = $(this);
            var type = card.find('input[name="employment_types[]"]').val();
            var selectedVal = card.find('input[name="employment_notice_periods[' + type + ']"]:checked').val();
            if (selectedVal === 'custom') {
                $('#custom-days-' + slugify(type)).addClass('show');
                $('#custom-days-' + slugify(type)).find('input').prop('required', true);
            }
            updateNoticePreview(type);
        });

        // Handle policy card click
        $('.policy-card').off('click').on('click', function(e) {
            if ($(e.target).closest('label, .btn').length) {
                return;
            }
            
            if (isProcessing) return;
            
            var card = $(this);
            var policyType = card.data('policy-type');
            var radio = card.find('input[name="policy_type"]');
            
            if (radio.is(':checked')) {
                return;
            }
            
            isProcessing = true;
            
            try {
                $('input[name="policy_type"]').prop('checked', false);
                radio.prop('checked', true);
                selectPolicyTypeDirect(policyType);
                radio.trigger('change.selectPolicy');
            } finally {
                isProcessing = false;
            }
        });

        // Handle radio button change
        $('input[name="policy_type"]').off('change.selectPolicy').on('change.selectPolicy', function() {
            if (isProcessing) return;
            
            if ($(this).is(':checked')) {
                var type = $(this).val();
                isProcessing = true;
                
                try {
                    selectPolicyTypeDirect(type);
                } finally {
                    isProcessing = false;
                }
            }
        });

        // Handle default notice period change
        $('input[name="default_notice_period"]').off('change').on('change', function() {
            var value = $(this).val();
            $('.default-notice-card .notice-period-btn').removeClass('active');

            if (value === 'custom') {
                $('.default-notice-card .notice-period-btn.custom-btn').addClass('active');
                $('#defaultCustomDays').addClass('show');
                $('#defaultCustomDays').find('input').prop('required', true);
            } else {
                $('.default-notice-card .notice-period-btn input[value="' + value + '"]').closest('.notice-period-btn').addClass('active');
                $('#defaultCustomDays').removeClass('show');
                $('#defaultCustomDays').find('input').prop('required', false);
                $('#defaultCustomDays').find('input').val('');
            }
            // Update preview for all employment types using default
            $('.employment-notice-card').each(function() {
                var type = $(this).find('input[name="employment_types[]"]').val();
                var selectedVal = $(this).find('input[name="employment_notice_periods[' + type + ']"]:checked').val();
                if (!selectedVal || selectedVal === '') {
                    updateNoticePreview(type);
                }
            });
        });

        // Handle employment type notice period changes
        $(document).off('change', 'input[name^="employment_notice_periods"]')
            .on('change', 'input[name^="employment_notice_periods"]', function() {
                var card = $(this).closest('.employment-notice-card');
                var type = card.find('input[name="employment_types[]"]').val();
                var value = $(this).val();
                
                if (value === 'custom') {
                    $('#custom-days-' + slugify(type)).addClass('show');
                    $('#custom-days-' + slugify(type)).find('input').prop('required', true);
                } else {
                    $('#custom-days-' + slugify(type)).removeClass('show');
                    $('#custom-days-' + slugify(type)).find('input').prop('required', false);
                    if (value !== '') {
                        $('#custom-days-' + slugify(type)).find('input').val('');
                    }
                }
                
                // Update button states
                card.find('.notice-period-btn').removeClass('active');
                if (value === '') {
                    card.find('.notice-period-btn:first').addClass('active');
                } else if (value === 'custom') {
                    card.find('.notice-period-btn:last').addClass('active');
                } else {
                    card.find('.notice-period-btn input[value="' + value + '"]').closest('.notice-period-btn').addClass('active');
                }
                
                updateNoticePreview(type);
            });

        // Handle custom days input change
        $(document).off('input', '.custom-days-wrapper input[type="number"]')
            .on('input', '.custom-days-wrapper input[type="number"]', function() {
                var card = $(this).closest('.employment-notice-card');
                if (card.length) {
                    var type = card.find('input[name="employment_types[]"]').val();
                    if ($(this).val()) {
                        updateNoticePreview(type);
                    }
                }
            });

        // Exit Interview toggle
        $('#exit_interview_required').on('change', function() {
            if ($(this).is(':checked')) {
                $('#interviewer_group').show();
                $('#interview_questions_group').show();
                $('#interviewer_id').prop('required', true);
            } else {
                $('#interviewer_group').hide();
                $('#interview_questions_group').hide();
                $('#interviewer_id').prop('required', false);
            }
        });

        // FNF toggle
        $('#fnf_required').on('change', function() {
            if ($(this).is(':checked')) {
                $('#fnf_days_group').show();
                $('#fnf_items_group').show();
                $('#fnf_processing_days').prop('required', true);
            } else {
                $('#fnf_days_group').hide();
                $('#fnf_items_group').hide();
                $('#fnf_processing_days').prop('required', false);
            }
        });

        // KT toggle
        $('#kt_required').on('change', function() {
            if ($(this).is(':checked')) {
                $('#kt_days_group').show();
                $('#kt_handover_group').show();
                $('#kt_approver_group').show();
                $('#kt_days').prop('required', true);
                $('#kt_approver').prop('required', true);
            } else {
                $('#kt_days_group').hide();
                $('#kt_handover_group').hide();
                $('#kt_approver_group').hide();
                $('#kt_days').prop('required', false);
                $('#kt_approver').prop('required', false);
            }
        });

        // Template selector
        $('.template-selector').on('click', function() {
            $('.template-selector').removeClass('active');
            $(this).addClass('active');
            var template = $(this).data('template');
            $('#interview_template').val(template);
        });

        // Form validation
        $('#policyForm').off('submit').on('submit', function(e) {
            var policyType = $('input[name="policy_type"]:checked').val();
            if (!policyType) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select a policy type',
                    confirmButtonColor: '#dc3545'
                });
                $('.policy-card').addClass('border-danger');
                return false;
            }
            
            $('.policy-card').removeClass('border-danger');
            
            var isValid = true;
            var errorMessages = [];
            
            if (policyType === 'department_wise') {
                if (!$('#department_id').val()) {
                    errorMessages.push('Please select a department');
                    isValid = false;
                }
            } else if (policyType === 'employee_wise') {
                if (!$('#employee_id').val()) {
                    errorMessages.push('Please select an employee');
                    isValid = false;
                }
            }

            // Validate default notice period
            var defaultNotice = $('input[name="default_notice_period"]:checked').val();
            if (!defaultNotice) {
                errorMessages.push('Please select a default notice period');
                isValid = false;
            } else if (defaultNotice === 'custom') {
                var defaultCustom = $('#defaultCustomDays').find('input').val();
                if (!defaultCustom || defaultCustom < 1 || defaultCustom > 365) {
                    errorMessages.push('Please enter a valid default custom notice period (1-365 days)');
                    isValid = false;
                }
            }

            // Validate custom days for employment types
            $('.employment-notice-card').each(function() {
                var type = $(this).find('input[name="employment_types[]"]').val();
                var selectedVal = $(this).find('input[name="employment_notice_periods[' + type + ']"]:checked').val();
                
                if (selectedVal === 'custom') {
                    var customDays = $('#custom-days-' + slugify(type)).find('input').val();
                    if (!customDays || customDays < 1 || customDays > 365) {
                        errorMessages.push('Please enter valid custom days for ' + type + ' (1-365)');
                        isValid = false;
                    }
                }
            });

            // Validate exit interview
            if ($('#exit_interview_required').is(':checked')) {
                if (!$('#interviewer_id').val()) {
                    errorMessages.push('Please select an interviewer');
                    isValid = false;
                }
            }

            // Validate FNF
            if ($('#fnf_required').is(':checked')) {
                if (!$('#fnf_processing_days').val()) {
                    errorMessages.push('Please select FNF processing days');
                    isValid = false;
                }
            }

            // Validate KT
            if ($('#kt_required').is(':checked')) {
                if (!$('#kt_days').val()) {
                    errorMessages.push('Please select KT duration');
                    isValid = false;
                }
                if (!$('#kt_approver').val()) {
                    errorMessages.push('Please select KT approver');
                    isValid = false;
                }
            }

            // Validate clearance workflow
            if (!$('#clearance_workflow').val()) {
                errorMessages.push('Please select clearance workflow');
                isValid = false;
            }

            if (!$('#clearance_days').val()) {
                errorMessages.push('Please enter clearance processing days');
                isValid = false;
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

    function selectDefaultNoticePeriod(value) {
        var radio = $('input[name="default_notice_period"][value="' + value + '"]');
        if (radio.length) {
            radio.prop('checked', true).trigger('change');
        }
    }

    function selectPolicyTypeDirect(type) {
        $('.policy-card').removeClass('selected');
        $('.policy-card[data-policy-type="' + type + '"]').addClass('selected');
        $('.policy-card').removeClass('border-danger');
        $('input[name="policy_type"]').prop('checked', false);
        $('input[name="policy_type"][value="' + type + '"]').prop('checked', true);
        togglePolicySectionsDirect(type);
    }

    function togglePolicySectionsDirect(type) {
        if (type === 'department_wise') {
            $('#departmentSection').show();
            $('#employeeSection').hide();
            $('#department_id').prop('required', true);
            $('#employee_id').prop('required', false);
            $('#employee_id').val('');
        } else if (type === 'employee_wise') {
            $('#departmentSection').hide();
            $('#employeeSection').show();
            $('#employee_id').prop('required', true);
            $('#department_id').prop('required', false);
            $('#department_id').val('');
        }
    }

    function selectEmploymentNotice(type, value) {
        var card = $('#notice-card-' + slugify(type));
        card.find('.notice-period-btn').removeClass('active');
        
        if (value === 'default') {
            card.find('.notice-period-btn:first').addClass('active');
            card.find('input[name="employment_notice_periods[' + type + ']"]').prop('checked', false);
            card.find('input[name="employment_notice_periods[' + type + ']"]:first').prop('checked', true);
            $('#custom-days-' + slugify(type)).removeClass('show');
            $('#custom-days-' + slugify(type)).find('input').prop('required', false);
            $('#custom-days-' + slugify(type)).find('input').val('');
        } else {
            card.find('.notice-period-btn input[value="' + value + '"]').closest('.notice-period-btn').addClass('active');
            card.find('input[name="employment_notice_periods[' + type + ']"]').prop('checked', false);
            card.find('input[name="employment_notice_periods[' + type + ']"][value="' + value + '"]').prop('checked', true);
            
            if (value === 'custom') {
                $('#custom-days-' + slugify(type)).addClass('show');
                $('#custom-days-' + slugify(type)).find('input').prop('required', true);
            } else {
                $('#custom-days-' + slugify(type)).removeClass('show');
                $('#custom-days-' + slugify(type)).find('input').prop('required', false);
                $('#custom-days-' + slugify(type)).find('input').val('');
            }
        }
        
        updateNoticePreview(type);
    }

    function updateNoticePreview(type) {
        var card = $('#notice-card-' + slugify(type));
        var preview = $('#notice-preview-' + slugify(type));
        var defaultDays = $('input[name="default_notice_period"]:checked').val();
        var defaultDisplay = defaultDays === 'custom' ? 
            ($('#defaultCustomDays').find('input').val() || 'custom') + ' days' : 
            defaultDays + ' days';
        
        var selectedVal = card.find('input[name="employment_notice_periods[' + type + ']"]:checked').val();
        var displayText = '';
        
        if (!selectedVal || selectedVal === '') {
            displayText = 'Default (' + defaultDisplay + ')';
        } else if (selectedVal === 'custom') {
            var customDays = $('#custom-days-' + slugify(type)).find('input').val();
            displayText = customDays ? customDays + ' days' : 'Custom (pending)';
        } else {
            displayText = selectedVal + ' days';
        }
        
        preview.text(displayText);
    }

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }
</script>

@endsection