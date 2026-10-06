@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Submit Resignation')

@section('content')
<style>
:root {
    --primary: #4f46e5;
    --primary-light: #818cf8;
    --primary-dark: #3730a3;
    --primary-bg: #eef2ff;
    --success: #22c55e;
    --success-bg: #dcfce7;
    --danger: #ef4444;
    --danger-bg: #fee2e2;
    --warning: #f59e0b;
    --warning-bg: #fef3c7;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    --radius: 16px;
    --radius-sm: 10px;
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.resignation-wrapper {
    max-width: 820px;
    margin: 0 auto;
    padding: 0 4px;
}

/* ===== HEADER ===== */
.resignation-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 28px 32px;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.resignation-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 250px;
    height: 250px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.resignation-header .header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    z-index: 1;
    position: relative;
}

.resignation-header .header-icon {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.15);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #fff;
    backdrop-filter: blur(4px);
}

.resignation-header h4 {
    font-weight: 700;
    font-size: 1.3rem;
    margin: 0;
    letter-spacing: -0.3px;
}

.resignation-header .subtitle {
    color: rgba(255,255,255,0.85);
    font-size: 0.85rem;
    margin: 0;
}

/* ===== FORM BODY ===== */
.resignation-body {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    padding: 32px;
    border-radius: 0 0 var(--radius) var(--radius);
    box-shadow: var(--shadow-sm);
}

/* ===== POLICY SECTION ===== */
.policy-section {
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    padding: 18px 22px;
    margin-bottom: 24px;
    border: 1px solid var(--gray-200);
}

.policy-section .section-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.policy-section .section-label i {
    color: var(--primary);
}

.policy-section .policy-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.policy-section .policy-item {
    background: #fff;
    border-radius: 6px;
    padding: 8px 12px;
    text-align: center;
    border: 1px solid var(--gray-200);
}

.policy-section .policy-item .label {
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: var(--gray-500);
    font-weight: 600;
}

.policy-section .policy-item .value {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-top: 1px;
}

.policy-section .policy-item .value .badge {
    font-size: 0.55rem;
    padding: 2px 8px;
}

.policy-section .requirements-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 16px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid var(--gray-200);
}

.policy-section .requirements-row .req-tag {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: var(--gray-600);
}

.policy-section .requirements-row .req-tag i {
    color: var(--primary);
    font-size: 0.65rem;
    width: 14px;
}

.policy-section .requirements-row .req-tag .badge {
    font-size: 0.5rem;
    padding: 1px 6px;
}

.policy-section .items-row {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 6px;
}

.policy-section .items-row .badge {
    font-size: 0.55rem;
    padding: 2px 8px;
    font-weight: 500;
}

/* ===== FORM GROUPS ===== */
.form-group-custom {
    margin-bottom: 20px;
}

.form-group-custom .form-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--gray-700);
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-group-custom .form-label .required {
    color: var(--danger);
}

.form-group-custom .form-label .optional {
    color: var(--gray-400);
    font-weight: 400;
    font-size: 0.75rem;
}

.form-group-custom .form-control {
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-sm);
    padding: 10px 14px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    background: #fff;
    width: 100%;
}

.form-group-custom .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    outline: none;
}

.form-group-custom .form-control.is-invalid {
    border-color: var(--danger);
}

.form-group-custom .form-control.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.form-group-custom .form-text {
    font-size: 0.75rem;
    color: var(--gray-500);
    margin-top: 4px;
}

.form-group-custom .form-text i {
    margin-right: 4px;
}

.form-group-custom .error-text {
    color: var(--danger);
    font-size: 0.8rem;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ===== FILE UPLOAD ===== */
.file-upload-wrapper {
    position: relative;
}

.file-upload-wrapper .file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-upload-wrapper .file-placeholder {
    border: 2px dashed var(--gray-300);
    border-radius: var(--radius-sm);
    padding: 24px;
    text-align: center;
    transition: all 0.2s ease;
    background: var(--gray-50);
}

.file-upload-wrapper .file-placeholder:hover {
    border-color: var(--primary);
    background: var(--primary-bg);
}

.file-upload-wrapper .file-placeholder .icon {
    font-size: 1.8rem;
    color: var(--gray-400);
    margin-bottom: 6px;
}

.file-upload-wrapper .file-placeholder .text {
    color: var(--gray-500);
    font-size: 0.85rem;
}

.file-upload-wrapper .file-placeholder .text strong {
    color: var(--primary);
}

.file-upload-wrapper .file-placeholder .hint {
    font-size: 0.7rem;
    color: var(--gray-400);
    margin-top: 4px;
}

.file-upload-wrapper .file-selected {
    display: none;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    background: var(--success-bg);
    border: 1px solid #bbf7d0;
    border-radius: var(--radius-sm);
}

.file-upload-wrapper .file-selected .file-icon {
    color: var(--success);
    font-size: 1.1rem;
}

.file-upload-wrapper .file-selected .file-name {
    flex: 1;
    font-weight: 500;
    color: var(--gray-700);
    font-size: 0.85rem;
}

.file-upload-wrapper .file-selected .file-remove {
    color: var(--danger);
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.2s ease;
    background: none;
    border: none;
}

.file-upload-wrapper .file-selected .file-remove:hover {
    transform: scale(1.2);
}

/* ===== NOTES ALERT ===== */
.notes-alert {
    background: var(--warning-bg);
    border: 1px solid #fde68a;
    border-radius: var(--radius-sm);
    padding: 14px 18px;
    margin-bottom: 20px;
}

.notes-alert .title {
    font-weight: 600;
    color: #92400e;
    font-size: 0.8rem;
    margin-bottom: 4px;
}

.notes-alert .title i {
    margin-right: 6px;
}

.notes-alert ul {
    margin: 0;
    padding-left: 18px;
    color: #78350f;
    font-size: 0.78rem;
}

.notes-alert ul li {
    margin-bottom: 2px;
}

/* ===== BUTTONS ===== */
.btn-custom {
    padding: 9px 24px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.85rem;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-custom:hover {
    transform: translateY(-2px);
}

.btn-custom:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn-primary-custom {
    background: var(--primary);
    color: #fff;
}

.btn-primary-custom:hover {
    background: var(--primary-dark);
    color: #fff;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.btn-secondary-custom {
    background: var(--gray-200);
    color: var(--gray-700);
}

.btn-secondary-custom:hover {
    background: var(--gray-300);
    color: var(--gray-800);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .resignation-header {
        padding: 20px;
    }
    .resignation-body {
        padding: 20px;
    }
    .policy-section .policy-grid {
        grid-template-columns: 1fr 1fr;
    }
    .form-actions {
        flex-direction: column-reverse;
        gap: 10px;
    }
    .form-actions .btn-custom {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .resignation-header .header-left {
        flex-wrap: wrap;
    }
    .resignation-header .header-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    .resignation-header h4 {
        font-size: 1.05rem;
    }
    .policy-section .policy-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="resignation-wrapper">
    
    {{-- HEADER --}}
    <div class="resignation-header">
        <div class="header-left">
            <div class="header-icon">
                <i class="fas fa-pen"></i>
            </div>
            <div>
                <h4>Submit Resignation</h4>
                <p class="subtitle">Fill in the details below to submit your resignation request</p>
            </div>
        </div>
    </div>

    {{-- FORM BODY --}}
    <div class="resignation-body">
        
        {{-- Policy Information --}}
        @if($exitPolicy)
        <div class="policy-section d-none">
            <div class="section-label">
                <i class="fas fa-file-contract"></i> Your Exit Policy
            </div>
            
            <div class="policy-grid d-none">
                <div class="policy-item">
                    <div class="label">Notice Period</div>
                    <div class="value">{{ $exitPolicy->notice_period_days ?? $exitPolicy->default_notice_period ?? 'N/A' }} Days</div>
                </div>
                <div class="policy-item d-none">
                    <div class="label">Exit Type</div>
                    <div class="value">
                        <span class="badge bg-primary">{{ $exitPolicy->exit_type ?? 'Resignation' }}</span>
                    </div>
                </div>
                <div class="policy-item d-none">
                    <div class="label">Policy Code</div>
                    <div class="value">
                        <span class="badge bg-secondary">{{ $exitPolicy->policy_code ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="policy-item d-none">
                    <div class="label">Clearance</div>
                    <div class="value">
                        <span class="badge bg-info">{{ ucfirst($exitPolicy->clearance_workflow ?? 'N/A') }}</span>
                        <small class="d-block text-muted" style="font-size:0.5rem;">{{ $exitPolicy->clearance_days ?? 'N/A' }} days</small>
                    </div>
                </div>
            </div>

            <div class="requirements-row">
                <span class="req-tag">
                    <i class="fas fa-comments"></i> Interview
                    <span class="badge {{ $exitPolicy->exit_interview_required ? 'bg-success' : 'bg-secondary' }}">
                        {{ $exitPolicy->exit_interview_required ? 'Yes' : 'No' }}
                    </span>
                </span>
                <span class="req-tag">
                    <i class="fas fa-file-invoice-dollar"></i> FNF
                    <span class="badge {{ $exitPolicy->fnf_required ? 'bg-success' : 'bg-secondary' }}">
                        {{ $exitPolicy->fnf_required ? 'Yes' : 'No' }}
                    </span>
                </span>
                <span class="req-tag">
                    <i class="fas fa-chalkboard-teacher"></i> KT
                    <span class="badge {{ $exitPolicy->kt_required ? 'bg-success' : 'bg-secondary' }}">
                        {{ $exitPolicy->kt_required ? 'Yes' : 'No' }}
                    </span>
                </span>
                @if($exitPolicy->exit_interview_required && $exitPolicy->interview_days)
                    <span class="req-tag text-muted" style="font-size:0.65rem;">
                        Interview: {{ $exitPolicy->interview_days }} days
                    </span>
                @endif
                @if($exitPolicy->fnf_required && $exitPolicy->fnf_processing_days)
                    <span class="req-tag text-muted" style="font-size:0.65rem;">
                        FNF: {{ $exitPolicy->fnf_processing_days }} days
                    </span>
                @endif
                @if($exitPolicy->kt_required && $exitPolicy->kt_days)
                    <span class="req-tag text-muted" style="font-size:0.65rem;">
                        KT: {{ $exitPolicy->kt_days }} days
                    </span>
                @endif
            </div>

            {{-- FNF Items --}}
            @if($exitPolicy->fnf_required && !empty($exitPolicy->fnf_items))
                <div class="items-row">
                    @php
                        $fnfLabels = [
                            'salary_settlement' => 'Salary',
                            'leave_encashment' => 'Leave',
                            'bonus_settlement' => 'Bonus',
                            'reimbursement' => 'Reimbursement',
                            'pf_settlement' => 'PF',
                            'esi_settlement' => 'ESI',
                            'gratuity' => 'Gratuity',
                            'other_dues' => 'Other'
                        ];
                    @endphp
                    @foreach($exitPolicy->fnf_items as $item)
                        <span class="badge bg-warning text-dark">
                            {{ $fnfLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                        </span>
                    @endforeach
                </div>
            @endif

            {{-- KT Requirements --}}
            @if($exitPolicy->kt_required && !empty($exitPolicy->kt_requirements))
                <div class="items-row">
                    @php
                        $ktLabels = [
                            'documentation' => 'Docs',
                            'project_handover' => 'Project',
                            'code_handover' => 'Code',
                            'client_handover' => 'Client',
                            'process_handover' => 'Process',
                            'training' => 'Training',
                            'knowledge_docs' => 'Knowledge'
                        ];
                    @endphp
                    @foreach($exitPolicy->kt_requirements as $item)
                        <span class="badge bg-success">
                            {{ $ktLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                        </span>
                    @endforeach
                </div>
            @endif

            {{-- Additional Requirements --}}
            @if(!empty($exitPolicy->additional_requirements))
                <div class="items-row">
                    @php
                        $additionalLabels = [
                            'asset_return' => 'Asset',
                            'access_revocation' => 'Access',
                            'id_card_return' => 'ID Card',
                            'visa_cancellation' => 'Visa',
                            'exit_reentry_visa' => 'Re-entry',
                            'medical_certificate' => 'Medical',
                            'police_clearance' => 'Police',
                            'housing_handover' => 'Housing'
                        ];
                    @endphp
                    @foreach($exitPolicy->additional_requirements as $item)
                        <span class="badge bg-secondary">
                            {{ $additionalLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
        @endif

        {{-- Process Info --}}
        <div class="notes-alert" style="background:var(--primary-bg);border-color:#c7d2fe;margin-bottom:20px;">
            <div class="title" style="color:var(--primary);">
                <i class="fas fa-clock"></i> Resignation Process
            </div>
            <ul style="color:var(--gray-700);">
                <li>Your resignation will be <strong>ON HOLD</strong> until approved by Admin/HR.</li>
                <li>Notice period will start only after approval.</li>
                <li>Last working day is calculated from the approval date.</li>
            </ul>
        </div>

        <form action="{{ route('employee.exit.resignation.submit') }}" method="POST" enctype="multipart/form-data" id="resignationForm">
            @csrf

            {{-- Exit Reason - Free Text --}}
            <div class="form-group-custom">
                <label for="exit_reason" class="form-label">
                    <i class="fas fa-question-circle"></i>
                    Reason for Resignation
                    <span class="required">*</span>
                </label>
                <textarea name="exit_reason" 
                          id="exit_reason" 
                          class="form-control @error('exit_reason') is-invalid @enderror" 
                          rows="3" 
                          placeholder="Please describe your reason for resignation..." 
                          required>{{ old('exit_reason') }}</textarea>
                @error('exit_reason')
                    <div class="error-text">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    <i class="fas fa-info-circle"></i>
                    Please provide a clear reason for your resignation.
                </div>
            </div>

            {{-- Additional Notes --}}
            <div class="form-group-custom">
                <label for="exit_notes" class="form-label">
                    <i class="fas fa-sticky-note"></i>
                    Additional Notes
                    <span class="optional">(Optional)</span>
                </label>
                <textarea name="exit_notes" 
                          id="exit_notes" 
                          class="form-control @error('exit_notes') is-invalid @enderror" 
                          rows="3" 
                          placeholder="Any additional details you'd like to share...">{{ old('exit_notes') }}</textarea>
                @error('exit_notes')
                    <div class="error-text">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Supporting Document --}}
            <div class="form-group-custom">
                <label for="exit_document" class="form-label">
                    <i class="fas fa-paperclip"></i>
                    Supporting Document
                    <span class="optional">(Optional)</span>
                </label>
                <div class="file-upload-wrapper">
                    <input type="file" 
                           name="exit_document" 
                           id="exit_document" 
                           class="file-input"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <div class="file-placeholder" id="filePlaceholder">
                        <div class="icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="text">
                            <strong>Click to upload</strong> or drag and drop
                        </div>
                        <div class="hint">
                            PDF, DOC, DOCX, JPG, PNG (Max: 2MB)
                        </div>
                    </div>
                    <div class="file-selected" id="fileSelected">
                        <div class="file-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="file-name" id="fileName">document.pdf</div>
                        <button type="button" class="file-remove" id="fileRemove" title="Remove file">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @error('exit_document')
                    <div class="error-text">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Important Notes --}}
            <div class="notes-alert">
                <div class="title">
                    <i class="fas fa-exclamation-triangle"></i>
                    Important Notes
                </div>
                <ul>
                    <li>Once submitted, this request cannot be edited or withdrawn online.</li>
                    <li>You can track the status on your dashboard.</li>
                </ul>
            </div>

            {{-- Form Actions --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;padding-top:12px;border-top:1px solid var(--gray-200);">
                <a href="{{ route('employee.exit.dashboard') }}" class="btn-custom btn-secondary-custom">
                    <i class="fas fa-arrow-left"></i>
                    Cancel
                </a>
                <button type="submit" class="btn-custom btn-primary-custom" id="submitBtn">
                    <i class="fas fa-paper-plane"></i>
                    Submit Resignation
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    
    // FILE UPLOAD HANDLER
    $('#exit_document').on('change', function() {
        var file = this.files[0];
        if (file) {
            $('#filePlaceholder').hide();
            $('#fileSelected').css('display', 'flex');
            $('#fileName').text(file.name);
        } else {
            $('#filePlaceholder').show();
            $('#fileSelected').hide();
        }
    });

    $('#fileRemove').on('click', function() {
        $('#exit_document').val('');
        $('#filePlaceholder').show();
        $('#fileSelected').hide();
    });

    // FORM SUBMISSION
    $('#resignationForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var exitReason = $('#exit_reason').val().trim();
        
        if (!exitReason) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please describe your reason for resignation.',
                confirmButtonColor: '#ef4444'
            });
            return false;
        }
        
        Swal.fire({
            title: '⚠️ Submit Resignation?',
            html: `
                <div style="text-align:left;padding:10px 0;">
                    <p style="font-weight:600;color:#1e293b;margin-bottom:8px;">
                        You are about to submit your resignation:
                    </p>
                    <div style="background:#f8fafc;padding:12px 16px;border-radius:8px;margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #e2e8f0;">
                            <span style="color:#64748b;">Reason:</span>
                            <span style="font-weight:600;color:#1e293b;max-width:250px;word-break:break-word;">${exitReason.substring(0, 100)}${exitReason.length > 100 ? '...' : ''}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:4px 0;">
                            <span style="color:#64748b;">Status:</span>
                            <span style="font-weight:600;color:#92400e;">⏳ ON HOLD (Pending Approval)</span>
                        </div>
                    </div>
                    <div style="background:#fef3c7;padding:10px 14px;border-radius:6px;margin-bottom:10px;">
                        <p style="color:#92400e;font-size:0.85rem;margin:0;">
                            <i class="fas fa-clock me-1"></i>
                            Your resignation will be <strong>ON HOLD</strong> until approved by Admin/HR.
                            Notice period will start after approval.
                        </p>
                    </div>
                    <p style="color:#64748b;font-size:0.85rem;margin-top:8px;">
                        <i class="fas fa-exclamation-triangle" style="color:#dc2626;"></i>
                        This action cannot be undone once submitted.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Submit Resignation',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '🔴 Final Confirmation',
                    html: `
                        <div style="padding:10px 0;">
                            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:12px;">
                                <p style="color:#991b1b;font-weight:600;margin-bottom:4px;">
                                    <i class="fas fa-exclamation-triangle" style="margin-right:8px;"></i>
                                    This action is irreversible!
                                </p>
                                <p style="color:#7f1d1d;font-size:0.9rem;margin:0;">
                                    Your resignation will be <strong>ON HOLD</strong> until Admin/HR approval.
                                </p>
                            </div>
                            <p style="color:#64748b;font-size:0.9rem;">
                                Type <strong style="color:#dc2626;">"CONFIRM"</strong> to proceed:
                            </p>
                            <input type="text" id="confirmInput" class="form-control" 
                                   placeholder="Type CONFIRM" 
                                   style="text-align:center;font-weight:600;border:2px solid #e2e8f0;border-radius:8px;padding:10px;margin-top:8px;">
                        </div>
                    `,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Finalize',
                    cancelButtonText: 'Go Back',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const input = Swal.getPopup().querySelector('#confirmInput');
                        if (!input || input.value !== 'CONFIRM') {
                            Swal.showValidationMessage('Please type "CONFIRM" to proceed');
                            return false;
                        }
                        return true;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((finalResult) => {
                    if (finalResult.isConfirmed) {
                        var btn = $('#submitBtn');
                        btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Submitting...');
                        btn.prop('disabled', true);
                        form[0].submit();
                    }
                });
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection