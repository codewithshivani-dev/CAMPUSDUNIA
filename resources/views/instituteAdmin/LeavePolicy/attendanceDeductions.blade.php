{{-- resources/views/instituteAdmin/LeavePolicy/attendanceDeductions.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    }
    
    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.25);
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    }
    
    .page-title {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }
    
    .page-title i {
        font-size: 2rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }
    
    /* Info Note */
    .info-note {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border-left: 5px solid var(--success-color);
        padding: 18px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 3px 10px rgba(16, 185, 129, 0.1);
    }
    
    .info-note i {
        margin-right: 8px;
    }
    
    .info-note small {
        font-size: 0.85rem;
        line-height: 1.6;
    }
    
    .info-note strong {
        color: var(--primary-color);
    }
    
    /* Section Card */
    .section-card {
        background: white;
        border-radius: 20px;
        margin-bottom: 30px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 2px solid rgba(67, 97, 238, 0.1);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.12);
    }
    
    .section-header {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        padding: 18px 25px;
        border-bottom: 2px solid rgba(67, 97, 238, 0.1);
    }
    
    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--primary-color);
    }
    
    .section-title i {
        font-size: 1.4rem;
    }
    
    .attendance-icon {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .leave-icon {
        background: var(--warning-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Table Card */
    .table-card {
        background: white;
        overflow: hidden;
    }
    
    .table-responsive {
        overflow-x: auto;
        overflow-y: hidden;
    }
    
    /* Hide horizontal scrollbar */
    .table-responsive::-webkit-scrollbar {
        width: 6px;
        height: 0;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--secondary-color);
    }
    
    .table-responsive::-webkit-scrollbar-horizontal {
        display: none;
    }
    
    /* Table Styles */
    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table thead{
        background: var(--primary-gradient);
    }
    
    .table thead th {
        color: white;
        border: none;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 18px;
    }
    
    .table tbody tr {
        border-bottom: 1px solid rgba(67, 97, 238, 0.08);
        transition: background 0.3s ease;
    }
    
    /*.table tbody tr:last-child {*/
    /*    border-bottom: none;*/
    /*}*/
    .table tbody td {
        padding: 14px 18px;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    /* Attendance Row */
    .attendance-row:hover {
        background: linear-gradient(135deg, #fef9c3, #fef3c7) !important;
    }
    
    /* Leave Row */
    .leave-row:hover {
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(58, 12, 163, 0.05)) !important;
    }
    
    /* Form Controls */
    .deduction-input {
        width: 100px;
        display: inline-block;
        text-align: center;
        border-radius: 10px;
        border: 2px solid rgba(67, 97, 238, 0.2);
        padding: 8px 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .deduction-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        outline: none;
    }
    
    .deduction-input:read-only {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb) !important;
        cursor: not-allowed;
        opacity: 0.7;
    }
    
    .input-group {
        display: inline-flex;
        align-items: center;
    }
    
    .input-group-text {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border: 2px solid rgba(67, 97, 238, 0.2);
        border-left: none;
        font-weight: 600;
        color: var(--primary-color);
        padding: 8px 12px;
        border-radius: 0 10px 10px 0;
        font-size: 0.85rem;
    }
    
    /* Badge Styles */
    .custom-badge {
        background: var(--warning-gradient);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 10px;
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
    }
    
    .default-badge {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 10px;
        box-shadow: 0 3px 10px rgba(100, 116, 139, 0.3);
    }
    
    /* Status Badges */
    .status-badge {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    
    .status-active {
        background: var(--success-gradient);
        color: white;
        box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
    }
    
    .status-inactive {
        background: var(--danger-gradient);
        color: white;
        box-shadow: 0 3px 10px rgba(239, 68, 68, 0.3);
    }
    
    /* Text Styles */
    .text-muted {
        color: #64748b !important;
        font-size: 0.8rem;
    }
    
    .text-muted i {
        color: var(--primary-color);
        margin-right: 5px;
    }
    
    .always-active-badge {
        background: var(--info-gradient);
        color: white;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
    }
    
    .status-text-active {
        color: var(--success-color);
        font-weight: 700;
        font-size: 0.75rem;
    }
    
    .status-text-inactive {
        color: #ef4444;
        font-weight: 700;
        font-size: 0.75rem;
    }
    
    /* Divider Text */
    .divider-text {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 3px;
        font-weight: 500;
    }
    
    /* Strong Text */
    .table tbody td strong {
        color: var(--primary-color);
    }
    
    /* Action Buttons */
    .action-buttons {
        position: relative;
        background: white;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
        text-align: center;
        z-index: 100;
        border: 2px solid rgba(67, 97, 238, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .save-all-btn {
        padding: 14px 35px;
        font-size: 1rem;
        font-weight: 700;
        background: var(--primary-gradient);
        border: none;
        border-radius: 40px;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .save-all-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: left 0.3s ease;
    }
    
    .save-all-btn:hover::before {
        left: 100%;
    }
    
    .save-all-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }
    
    .save-all-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
        vertical-align: middle;
        margin-right: 8px;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 8px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 8px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: var(--secondary-color);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header {
            padding: 20px;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .section-card {
            border-radius: 15px;
        }
        
        .section-header {
            padding: 15px 20px;
        }
        
        .table thead {
            display: none;
        }
        
        .table tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 2px solid rgba(67, 97, 238, 0.1);
            border-radius: 15px;
            padding: 15px;
        }
        
        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 5px;
            text-align: right;
        }
        
        .table tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            color: var(--primary-color);
            margin-right: 10px;
            text-transform: uppercase;
            font-size: 0.8rem;
        }
        
        .save-all-btn {
            width: 100%;
            padding: 12px 25px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-calculator-fill"></i>
            Deduction Configuration
            
        </h1>
    </div>

    <!-- Info Note -->
    <div class="info-note">
        <div>
            <small class="d-block mb-1">
                <i class="bi bi-info-circle-fill"></i>
                <strong>Configure deduction percentages for attendance and leave types.</strong> These percentages determine how much of the daily salary is deducted.
            </small>
            <small class="d-block mt-2">
                <i class="bi bi-check-circle-fill text-success me-1"></i> 
                <strong>Approved Deduction:</strong> Percentage deducted when leave is approved by management.
            </small>
            <small class="d-block">
                <i class="bi bi-x-circle-fill text-danger me-1"></i> 
                <strong>Unapproved Deduction:</strong> Percentage deducted when leave is not approved / unauthorized absence.
            </small>
            <small class="d-block mt-1 text-muted">
                <i class="bi bi-lightbulb"></i> 
                <strong>Example:</strong> If Approved Deduction is 0%, employee gets full salary for approved leave. If 100%, no salary for that day.
            </small>
        </div>
    </div>

    <!-- 1. Attendance Deductions Section -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-person-check-fill attendance-icon"></i>
                <span>Attendance Deductions</span>
                <!-- <span class="divider-text">| Present & Absent Settings</span> -->
            </div>
        </div>
        <div class="table-card">
            <div class="table-responsive">
                <table class="table">
                    <thead style="background:var(--primary-gradient);">
                        <tr>
                            <th class="sortable"><i class="bi bi-tag me-1"></i>Attendance Type</th>
                            <th class="sortable"><i class="bi bi-percent me-1"></i>Deduction Percentage</th>
                            <th class="sortable"><i class="bi bi-info-circle me-1"></i>Description</th>

                        </tr>
                    </thead>
                    <tbody>

                        <!-- Present Row -->
                        <tr class="attendance-row">
                            
                            <td>
                                <strong>
                                    <i class="bi bi-check-circle-fill text-success me-2"></i>Present
                                </strong>
                                <span class="default-badge">Fixed</span>
                            </td>
                            <td>
                                <div class="input-group" style="width: 150px;">
                                    <input type="number" class="form-control deduction-input" value="0" readonly
                                        disabled style="background-color: #f3f4f6;">
                                    <span class="input-group-text">%</span>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-check-circle-fill text-success"></i> No deduction - Full salary
                                    credited
                                </small>
                            </td>

                        </tr>

                        <!-- Absent Row (Configurable) -->
                        <tr class="attendance-row">
                            <td>
                                <strong>
                                    <i class="bi bi-x-circle-fill text-danger me-2"></i>Absent
                                </strong>
                                <span class="custom-badge">Configurable</span>
                            </td>
                            <td>
                                <div class="input-group" style="width: 150px;">
                                    <input type="number" class="form-control deduction-input absent-input"
                                        value="{{ $absentDeduction['approved_deduction_percentage'] }}" step="0.5"
                                        min="0" max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                                    Deduction applied when employee is absent without approval
                                </small>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. Leave Types Deductions Section -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-title">
                <i class="bi bi-calendar-heart-fill leave-icon"></i>
                <span>Leave Type Deductions</span>
                <!-- <span class="divider-text">| Configure per leave type</span> -->
            </div>
        </div>
        <div class="table-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="sortable"><i class="bi bi-tag me-1"></i>Leave Type</th>
                            <th class="sortable"><i class="bi bi-check-circle me-1"></i>Approved Deduction (%) <br/> <small>(of Per-Day Salary)</small></th>
                            <th class="sortable"><i class="bi bi-x-circle me-1"></i>Unapproved Deduction (%) <br/> <small>(of Per-Day Salary)</small></th>
                            <th class="sortable"><i class="bi bi-gear me-1"></i>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaveDeductions as $deduction)
                        <tr class="leave-row" data-id="{{ $deduction['id'] ?? '' }}" data-type="leave">
                            <td>
                                <strong>
                                    <i class="bi bi-bookmark-fill me-2" style="color: var(--primary-color);"></i>
                                    {{ $deduction['leave_type'] }}
                                </strong>
                            </td>
                            @php
                                $leaveTypeName = strtolower($deduction['leave_type'] ?? '');
                                $isUnpaidLeave = strpos($leaveTypeName, 'unpaid') !== false;
                                $approvedReadonly = !(isset($deduction['is_custom']) && $deduction['is_custom']) && !$isUnpaidLeave;
                            @endphp
                            <td>
                                <div class="input-group" style="width: 140px;">
                                    <input type="number" class="form-control deduction-input approved-input"
                                        data-id="{{ $deduction['id'] ?? '' }}"
                                        value="{{ $deduction['approved_deduction_percentage'] }}" step="0.5" min="0"
                                        max="100" @if($approvedReadonly) readonly @endif>
                                    <span class="input-group-text">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group" style="width: 140px;">
                                    <input type="number" class="form-control deduction-input unapproved-input"
                                        data-id="{{ $deduction['id'] ?? '' }}"
                                        value="{{ $deduction['unapproved_deduction_percentage'] }}" step="0.5" min="0"
                                        max="100">
                                    <span class="input-group-text">%</span>
                                </div>
                            </td>

                            <td>
                                @if(isset($deduction['is_custom']) && $deduction['is_custom'])
                                <span class="custom-badge">
                                    <i class="bi bi-pencil-square me-1"></i> Custom
                                </span>
                                @else
                                <span class="default-badge">
                                    <i class="bi bi-shield-check me-1"></i> Default
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Save All Button -->
    <div class="action-buttons">
        <button type="button" id="saveAllBtn" class="btn btn-primary save-all-btn">
            <i class="bi bi-save-all me-2"></i> Save All Changes
        </button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    let csrfToken = '{{ csrf_token() }}';
    let isSaving = false;
    $(document).ready(function() {
    
        // Save All Changes
        $('#saveAllBtn').on('click', function(e) {
            e.preventDefault();
    
            if (isSaving) {
                Swal.fire({
                    icon: 'info',
                    title: 'Please Wait',
                    text: 'Save operation is already in progress...',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }
    
            let updates = [];
            let absentPercent = $('.absent-input').val();
    
            // Collect leave deduction updates
            $('tbody tr[data-type="leave"]').each(function() {
                let id = $(this).data('id');
                let leaveType = $(this).find('td:first strong').text();
                let approvedPercent = $(this).find('.approved-input').val();
                let unapprovedPercent = $(this).find('.unapproved-input').val();
                let isCustom = $(this).find('.custom-badge').length > 0 ? 1 : 0;
                
                if (id) {
                    // Existing record
                    updates.push({
                        id: id,
                        leave_type: leaveType,
                        approved_deduction_percentage: approvedPercent,
                        unapproved_deduction_percentage: unapprovedPercent,
                        is_custom: isCustom
                    });
                } else {
                    // New record (default leave type not yet in DB)
                    updates.push({
                        id: null,
                        leave_type: leaveType,
                        approved_deduction_percentage: approvedPercent,
                        unapproved_deduction_percentage: unapprovedPercent,
                        is_active: 1,
                        is_custom: isCustom
                    });
                }
            });
    
            if (updates.length === 0 && !absentPercent) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Changes',
                    text: 'No deduction rules found to save!',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }
    
            let totalItems = updates.length;
            let message = `You are about to update ${totalItems} leave deduction rule(s).`;
            if (absentPercent) {
                message += `\nAbsent deduction will be updated to ${absentPercent}%.`;
            }
    
            // Confirm before saving
            Swal.fire({
                title: 'Save All Changes?',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save Changes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    let saveBtn = $('#saveAllBtn');
                    let originalHtml = saveBtn.html();
                    saveBtn.html('<span class="loading-spinner"></span> Saving...').prop('disabled',
                        true);
                    isSaving = true;
    
                    // Send bulk save
                    $.ajax({
                        url: '{{ route("attendance.deductions.bulk-update") }}',
                        method: 'POST',
                        data: {
                            updates: updates,
                            absent_percentage: absentPercent,
                            _token: csrfToken
                        },
                        success: function(response) {
                            saveBtn.html(originalHtml).prop('disabled', false);
                            isSaving = false;
    
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Saved Successfully!',
                                    text: response.message ||
                                        'All deduction rules have been saved.',
                                    confirmButtonColor: '#4361ee',
                                    timer: 2000,
                                    showConfirmButton: true
                                }).then(() => {
                                    // Reload to show updated data
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Save Failed',
                                    text: response.message ||
                                        'Some updates failed to save. Please try again.',
                                    confirmButtonColor: '#4361ee'
                                });
                            }
                        },
                        error: function(xhr) {
                            saveBtn.html(originalHtml).prop('disabled', false);
                            isSaving = false;
    
                            let errorMsg = 'Failed to save changes. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
    
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMsg,
                                confirmButtonColor: '#4361ee'
                            });
                        }
                    });
                }
            });
        });
    
    });
</script>
@endsection