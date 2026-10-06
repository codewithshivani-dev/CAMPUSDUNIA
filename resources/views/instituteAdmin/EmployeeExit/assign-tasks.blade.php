@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Assign Exit Tasks')

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
    --radius-xs: 6px;
    --shadow: 0 1px 3px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
}

.task-assignment-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 4px;
}

.page-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 28px 32px;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.page-header .header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    z-index: 1;
    position: relative;
}

.page-header .header-icon {
    width: 52px;
    height: 52px;
    background: rgba(255,255,255,0.15);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    backdrop-filter: blur(4px);
}

.page-header h4 {
    font-weight: 700;
    font-size: 1.35rem;
    margin: 0;
    letter-spacing: -0.3px;
}

.page-header .subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 0.9rem;
    margin: 0;
}

/* Employee Info Card */
.employee-info-card {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    padding: 20px 28px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    align-items: center;
}

.employee-info-card .info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: var(--gray-700);
}

.employee-info-card .info-item .label {
    font-weight: 600;
    color: var(--gray-500);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.employee-info-card .info-item .value {
    font-weight: 500;
}

.employee-info-card .badge-status {
    margin-left: auto;
}

/* Task Cards */
.task-cards-section {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    border-radius: 0 0 var(--radius) var(--radius);
    padding: 24px 28px;
}

.task-card {
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-sm);
    padding: 20px 24px;
    margin-bottom: 16px;
    transition: all 0.3s ease;
    background: #fff;
}

.task-card:hover {
    border-color: var(--primary-light);
    box-shadow: var(--shadow-md);
}

.task-card:last-child {
    margin-bottom: 0;
}

.task-card .task-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 12px;
}

.task-card .task-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.task-card .task-title .task-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-xs);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
    flex-shrink: 0;
}

.task-card .task-title .task-icon.kt { background: #4f46e5; }
.task-card .task-title .task-icon.interview { background: #f59e0b; }
.task-card .task-title .task-icon.asset { background: #06b6d4; }
.task-card .task-title .task-icon.fnf { background: #ef4444; }

.task-card .task-title h5 {
    margin: 0;
    font-weight: 600;
    color: var(--gray-800);
}

.task-card .task-title .task-code {
    font-size: 0.7rem;
    color: var(--gray-400);
    font-weight: 500;
}

.task-card .task-status-badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.task-card .task-status-badge.pending {
    background: var(--warning-bg);
    color: #92400e;
}

.task-card .task-status-badge.in-progress {
    background: var(--primary-bg);
    color: var(--primary-dark);
}

.task-card .task-status-badge.completed {
    background: var(--success-bg);
    color: #166534;
}

.task-card .task-status-badge.not-assigned {
    background: var(--gray-100);
    color: var(--gray-500);
}

.task-card .task-body {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--gray-100);
}

.task-card .task-body .assignee-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.task-card .task-body .assignee-info .avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--primary-bg);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.task-card .task-body .assignee-info .assignee-details {
    flex: 1;
}

.task-card .task-body .assignee-info .assignee-details .name {
    font-weight: 600;
    color: var(--gray-800);
}

.task-card .task-body .assignee-info .assignee-details .role {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.task-card .task-body .assignee-info .assignee-details .deadline {
    font-size: 0.75rem;
    color: var(--gray-400);
}

.task-card .task-body .instructions {
    margin-top: 8px;
    padding: 8px 12px;
    background: var(--gray-50);
    border-radius: var(--radius-xs);
    font-size: 0.85rem;
    color: var(--gray-600);
}

.task-card .task-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 12px;
}

.btn-task-assign {
    background: var(--primary);
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-task-assign:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-task-assign.assigned {
    background: var(--gray-200);
    color: var(--gray-500);
    cursor: default;
}

.btn-task-assign.assigned:hover {
    transform: none;
    box-shadow: none;
}

.btn-task-update {
    background: var(--warning);
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-task-update:hover {
    background: #d97706;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-back {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
    padding: 8px 20px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-back:hover {
    background: var(--gray-200);
    text-decoration: none;
    color: var(--gray-700);
}

.btn-save-all {
    background: var(--success);
    color: #fff;
    border: none;
    padding: 10px 32px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-save-all:hover {
    background: #16a34a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

/* Assign Modal */
.modal-content {
    border: none;
    border-radius: var(--radius);
}

.modal-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff;
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 20px 24px;
}

.modal-header .btn-close {
    color: #fff;
    opacity: 0.8;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--gray-200);
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 20px;
    }
    .employee-info-card {
        padding: 16px;
        flex-direction: column;
        align-items: flex-start;
    }
    .employee-info-card .badge-status {
        margin-left: 0;
    }
    .task-cards-section {
        padding: 16px;
    }
    .task-card {
        padding: 16px;
    }
    .task-card .task-header {
        flex-direction: column;
    }
    .task-card .task-actions {
        flex-direction: column;
        width: 100%;
    }
    .task-card .task-actions .btn-task-assign,
    .task-card .task-actions .btn-task-update {
        width: 100%;
        justify-content: center;
    }
}

/* Animations */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-up { animation: fadeUp 0.4s ease forwards; }
.delay-1 { animation-delay: 0.05s; }
.delay-2 { animation-delay: 0.1s; }
.delay-3 { animation-delay: 0.15s; }
</style>

<div class="task-assignment-wrapper">
    
    {{-- HEADER --}}
    <div class="page-header fade-up">
        <div class="header-left">
            <div class="header-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div>
                <h4>Assign Exit Tasks</h4>
                <p class="subtitle">Assign and manage tasks for the exit process</p>
            </div>
        </div>
    </div>

    {{-- EMPLOYEE INFO --}}
    <div class="employee-info-card fade-up delay-1">
        <div class="info-item">
            <span class="label"><i class="fas fa-user"></i> Employee</span>
            <span class="value">{{ $employee->name ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="label"><i class="fas fa-id-badge"></i> Code</span>
            <span class="value">{{ $employee->employee_code ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="label"><i class="fas fa-building"></i> Department</span>
            <span class="value">{{ $employee->department->department ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="label"><i class="fas fa-briefcase"></i> Designation</span>
            <span class="value">{{ $employee->designation ?? 'N/A' }}</span>
        </div>
        <div class="info-item">
            <span class="label"><i class="fas fa-calendar-alt"></i> Exit Date</span>
            <span class="value">{{ $exit->proposed_last_working_date ? \Carbon\Carbon::parse($exit->proposed_last_working_date)->format('d M Y') : 'N/A' }}</span>
        </div>
        <div class="badge-status">
            <span class="badge-status-approval {{ strtolower($exit->exit_status) }}">
                {{ ucfirst($exit->exit_status) }}
            </span>
        </div>
    </div>

    {{-- TASK CARDS --}}
    <div class="task-cards-section fade-up delay-2">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="mb-0" style="font-weight:700;color:var(--gray-800);">
                <i class="fas fa-list-check text-primary me-2"></i> Exit Tasks
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('exit.approvals') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button onclick="saveAllTasks()" class="btn-save-all">
                    <i class="fas fa-save"></i> Save All
                </button>
            </div>
        </div>

        <form id="taskAssignmentForm" action="{{ route('exit.task.assign') }}" method="POST">
            @csrf
            <input type="hidden" name="exit_id" value="{{ $exit->id }}">

            {{-- Task 1: Knowledge Transfer --}}
            <div class="task-card" id="task-kt">
                <div class="task-header">
                    <div class="task-title">
                        <div class="task-icon kt">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <h5>Knowledge Transfer <span class="task-code">(KT)</span></h5>
                            <small class="text-muted">Document and transfer critical knowledge</small>
                        </div>
                    </div>
                    <div>
                        @php
                            $ktTask = $tasks->where('task_type', 'kt')->first();
                            $ktStatus = $ktTask ? $ktTask->status : 'not-assigned';
                        @endphp
                        <span class="task-status-badge {{ $ktStatus }}">
                            {{ $ktStatus == 'not-assigned' ? 'Not Assigned' : ucfirst($ktStatus) }}
                        </span>
                    </div>
                </div>
                <div class="task-body">
                    <div class="assignee-info">
                        <div class="avatar">
                            {{ $ktTask && $ktTask->assigned_to_name ? substr($ktTask->assigned_to_name, 0, 1) : '?' }}
                        </div>
                        <div class="assignee-details">
                            <div class="name">
                                {{ $ktTask ? $ktTask->assigned_to_name ?? 'Not Assigned' : 'Not Assigned' }}
                            </div>
                            <div class="role">
                                {{ $ktTask ? $ktTask->assigned_to_role ?? 'N/A' : '' }}
                                @if($ktTask && $ktTask->deadline)
                                    <span class="deadline">
                                        <i class="fas fa-calendar-alt ms-2"></i> 
                                        {{ \Carbon\Carbon::parse($ktTask->deadline)->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="margin-left:auto;">
                            <button type="button" class="btn-task-{{ $ktTask && $ktTask->assigned_to_name ? 'update' : 'assign' }}" 
                                    onclick="openAssignModal('kt', {{ $ktTask ? $ktTask->id : 'null' }})">
                                <i class="fas fa-{{ $ktTask && $ktTask->assigned_to_name ? 'pencil-alt' : 'user-plus' }}"></i>
                                {{ $ktTask && $ktTask->assigned_to_name ? 'Reassign' : 'Assign' }}
                            </button>
                        </div>
                    </div>
                    @if($ktTask && $ktTask->instructions)
                        <div class="instructions">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            {{ $ktTask->instructions }}
                        </div>
                    @endif
                    <input type="hidden" name="tasks[kt][task_id]" value="{{ $ktTask ? $ktTask->id : '' }}">
                    <input type="hidden" name="tasks[kt][assigned_to]" id="kt_assigned_to" value="{{ $ktTask ? $ktTask->assigned_to_user_id : '' }}">
                    <input type="hidden" name="tasks[kt][instructions]" id="kt_instructions" value="{{ $ktTask ? $ktTask->instructions : '' }}">
                    <input type="hidden" name="tasks[kt][deadline]" id="kt_deadline" value="{{ $ktTask ? $ktTask->deadline : '' }}">
                    <input type="hidden" name="tasks[kt][status]" id="kt_status" value="{{ $ktTask ? $ktTask->status : 'pending' }}">
                </div>
            </div>

            {{-- Task 2: Exit Interview --}}
            <div class="task-card" id="task-exit_interview">
                <div class="task-header">
                    <div class="task-title">
                        <div class="task-icon interview">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div>
                            <h5>Exit Interview</h5>
                            <small class="text-muted">Conduct and document exit interview</small>
                        </div>
                    </div>
                    <div>
                        @php
                            $interviewTask = $tasks->where('task_type', 'exit_interview')->first();
                            $interviewStatus = $interviewTask ? $interviewTask->status : 'not-assigned';
                        @endphp
                        <span class="task-status-badge {{ $interviewStatus }}">
                            {{ $interviewStatus == 'not-assigned' ? 'Not Assigned' : ucfirst($interviewStatus) }}
                        </span>
                    </div>
                </div>
                <div class="task-body">
                    <div class="assignee-info">
                        <div class="avatar">
                            {{ $interviewTask && $interviewTask->assigned_to_name ? substr($interviewTask->assigned_to_name, 0, 1) : '?' }}
                        </div>
                        <div class="assignee-details">
                            <div class="name">
                                {{ $interviewTask ? $interviewTask->assigned_to_name ?? 'Not Assigned' : 'Not Assigned' }}
                            </div>
                            <div class="role">
                                {{ $interviewTask ? $interviewTask->assigned_to_role ?? 'N/A' : '' }}
                                @if($interviewTask && $interviewTask->deadline)
                                    <span class="deadline">
                                        <i class="fas fa-calendar-alt ms-2"></i> 
                                        {{ \Carbon\Carbon::parse($interviewTask->deadline)->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="margin-left:auto;">
                            <button type="button" class="btn-task-{{ $interviewTask && $interviewTask->assigned_to_name ? 'update' : 'assign' }}" 
                                    onclick="openAssignModal('exit_interview', {{ $interviewTask ? $interviewTask->id : 'null' }})">
                                <i class="fas fa-{{ $interviewTask && $interviewTask->assigned_to_name ? 'pencil-alt' : 'user-plus' }}"></i>
                                {{ $interviewTask && $interviewTask->assigned_to_name ? 'Reassign' : 'Assign' }}
                            </button>
                        </div>
                    </div>
                    @if($interviewTask && $interviewTask->instructions)
                        <div class="instructions">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            {{ $interviewTask->instructions }}
                        </div>
                    @endif
                    <input type="hidden" name="tasks[exit_interview][task_id]" value="{{ $interviewTask ? $interviewTask->id : '' }}">
                    <input type="hidden" name="tasks[exit_interview][assigned_to]" id="interview_assigned_to" value="{{ $interviewTask ? $interviewTask->assigned_to_user_id : '' }}">
                    <input type="hidden" name="tasks[exit_interview][instructions]" id="interview_instructions" value="{{ $interviewTask ? $interviewTask->instructions : '' }}">
                    <input type="hidden" name="tasks[exit_interview][deadline]" id="interview_deadline" value="{{ $interviewTask ? $interviewTask->deadline : '' }}">
                    <input type="hidden" name="tasks[exit_interview][status]" id="interview_status" value="{{ $interviewTask ? $interviewTask->status : 'pending' }}">
                </div>
            </div>

            {{-- Task 3: Asset Clearance --}}
            <div class="task-card" id="task-asset_clearance">
                <div class="task-header">
                    <div class="task-title">
                        <div class="task-icon asset">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div>
                            <h5>Asset Clearance</h5>
                            <small class="text-muted">Verify and collect company assets</small>
                        </div>
                    </div>
                    <div>
                        @php
                            $assetTask = $tasks->where('task_type', 'asset_clearance')->first();
                            $assetStatus = $assetTask ? $assetTask->status : 'not-assigned';
                        @endphp
                        <span class="task-status-badge {{ $assetStatus }}">
                            {{ $assetStatus == 'not-assigned' ? 'Not Assigned' : ucfirst($assetStatus) }}
                        </span>
                    </div>
                </div>
                <div class="task-body">
                    <div class="assignee-info">
                        <div class="avatar">
                            {{ $assetTask && $assetTask->assigned_to_name ? substr($assetTask->assigned_to_name, 0, 1) : '?' }}
                        </div>
                        <div class="assignee-details">
                            <div class="name">
                                {{ $assetTask ? $assetTask->assigned_to_name ?? 'Not Assigned' : 'Not Assigned' }}
                            </div>
                            <div class="role">
                                {{ $assetTask ? $assetTask->assigned_to_role ?? 'N/A' : '' }}
                                @if($assetTask && $assetTask->deadline)
                                    <span class="deadline">
                                        <i class="fas fa-calendar-alt ms-2"></i> 
                                        {{ \Carbon\Carbon::parse($assetTask->deadline)->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="margin-left:auto;">
                            <button type="button" class="btn-task-{{ $assetTask && $assetTask->assigned_to_name ? 'update' : 'assign' }}" 
                                    onclick="openAssignModal('asset_clearance', {{ $assetTask ? $assetTask->id : 'null' }})">
                                <i class="fas fa-{{ $assetTask && $assetTask->assigned_to_name ? 'pencil-alt' : 'user-plus' }}"></i>
                                {{ $assetTask && $assetTask->assigned_to_name ? 'Reassign' : 'Assign' }}
                            </button>
                        </div>
                    </div>
                    @if($assetTask && $assetTask->instructions)
                        <div class="instructions">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            {{ $assetTask->instructions }}
                        </div>
                    @endif
                    <input type="hidden" name="tasks[asset_clearance][task_id]" value="{{ $assetTask ? $assetTask->id : '' }}">
                    <input type="hidden" name="tasks[asset_clearance][assigned_to]" id="asset_assigned_to" value="{{ $assetTask ? $assetTask->assigned_to_user_id : '' }}">
                    <input type="hidden" name="tasks[asset_clearance][instructions]" id="asset_instructions" value="{{ $assetTask ? $assetTask->instructions : '' }}">
                    <input type="hidden" name="tasks[asset_clearance][deadline]" id="asset_deadline" value="{{ $assetTask ? $assetTask->deadline : '' }}">
                    <input type="hidden" name="tasks[asset_clearance][status]" id="asset_status" value="{{ $assetTask ? $assetTask->status : 'pending' }}">
                </div>
            </div>

            {{-- Task 4: FNF Settlement --}}
            <div class="task-card" id="task-fnf">
                <div class="task-header">
                    <div class="task-title">
                        <div class="task-icon fnf">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <h5>FNF Settlement</h5>
                            <small class="text-muted">Process Full & Final settlement</small>
                        </div>
                    </div>
                    <div>
                        @php
                            $fnfTask = $tasks->where('task_type', 'fnf')->first();
                            $fnfStatus = $fnfTask ? $fnfTask->status : 'not-assigned';
                        @endphp
                        <span class="task-status-badge {{ $fnfStatus }}">
                            {{ $fnfStatus == 'not-assigned' ? 'Not Assigned' : ucfirst($fnfStatus) }}
                        </span>
                    </div>
                </div>
                <div class="task-body">
                    <div class="assignee-info">
                        <div class="avatar">
                            {{ $fnfTask && $fnfTask->assigned_to_name ? substr($fnfTask->assigned_to_name, 0, 1) : '?' }}
                        </div>
                        <div class="assignee-details">
                            <div class="name">
                                {{ $fnfTask ? $fnfTask->assigned_to_name ?? 'Not Assigned' : 'Not Assigned' }}
                            </div>
                            <div class="role">
                                {{ $fnfTask ? $fnfTask->assigned_to_role ?? 'N/A' : '' }}
                                @if($fnfTask && $fnfTask->deadline)
                                    <span class="deadline">
                                        <i class="fas fa-calendar-alt ms-2"></i> 
                                        {{ \Carbon\Carbon::parse($fnfTask->deadline)->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div style="margin-left:auto;">
                            <button type="button" class="btn-task-{{ $fnfTask && $fnfTask->assigned_to_name ? 'update' : 'assign' }}" 
                                    onclick="openAssignModal('fnf', {{ $fnfTask ? $fnfTask->id : 'null' }})">
                                <i class="fas fa-{{ $fnfTask && $fnfTask->assigned_to_name ? 'pencil-alt' : 'user-plus' }}"></i>
                                {{ $fnfTask && $fnfTask->assigned_to_name ? 'Reassign' : 'Assign' }}
                            </button>
                        </div>
                    </div>
                    @if($fnfTask && $fnfTask->instructions)
                        <div class="instructions">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            {{ $fnfTask->instructions }}
                        </div>
                    @endif
                    <input type="hidden" name="tasks[fnf][task_id]" value="{{ $fnfTask ? $fnfTask->id : '' }}">
                    <input type="hidden" name="tasks[fnf][assigned_to]" id="fnf_assigned_to" value="{{ $fnfTask ? $fnfTask->assigned_to_user_id : '' }}">
                    <input type="hidden" name="tasks[fnf][instructions]" id="fnf_instructions" value="{{ $fnfTask ? $fnfTask->instructions : '' }}">
                    <input type="hidden" name="tasks[fnf][deadline]" id="fnf_deadline" value="{{ $fnfTask ? $fnfTask->deadline : '' }}">
                    <input type="hidden" name="tasks[fnf][status]" id="fnf_status" value="{{ $fnfTask ? $fnfTask->status : 'pending' }}">
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Assign Modal --}}
<div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i> 
                    Assign <span id="modalTaskLabel">Task</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalTaskType" value="">
                <input type="hidden" id="modalTaskId" value="">

                <div class="mb-3">
                    <label class="form-label fw-bold">Select Person</label>
                    <div class="mb-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="employeeSource" id="sourceDepartment" value="department" checked>
                            <label class="form-check-label" for="sourceDepartment">Same Department</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="employeeSource" id="sourceHR" value="hr">
                            <label class="form-check-label" for="sourceHR">HR/Admin</label>
                        </div>
                    </div>
                    <div id="employeeSelectContainer">
                        <select class="form-select" id="assignPersonSelect">
                            <option value="">Select a person...</option>
                        </select>
                    </div>
                    <div id="loadingEmployees" class="text-center p-3" style="display:none;">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span class="ms-2">Loading employees...</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Instructions <span class="text-muted">(Optional)</span></label>
                    <textarea class="form-control" id="modalInstructions" rows="3" 
                              placeholder="Provide specific instructions for this task..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Deadline <span class="text-muted">(Optional)</span></label>
                    <input type="date" class="form-control" id="modalDeadline">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select class="form-select" id="modalStatus">
                        <option value="pending">Pending</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="submitAssignment()">
                    <i class="fas fa-check me-1"></i> Assign
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentTaskType = null;
let currentTaskId = null;
let currentExitId = {{ $exit->id }};

// ============================================
// ASSIGN MODAL FUNCTIONS
// ============================================
function openAssignModal(taskType, taskId) {
    currentTaskType = taskType;
    currentTaskId = taskId;

    const taskLabels = {
        'kt': 'Knowledge Transfer (KT)',
        'exit_interview': 'Exit Interview',
        'asset_clearance': 'Asset Clearance',
        'fnf': 'FNF Settlement'
    };

    document.getElementById('modalTaskLabel').textContent = taskLabels[taskType] || taskType;
    document.getElementById('modalTaskType').value = taskType;
    document.getElementById('modalTaskId').value = taskId || '';
    document.getElementById('modalInstructions').value = '';
    document.getElementById('modalDeadline').value = '';
    document.getElementById('modalStatus').value = 'pending';

    // Load existing data
    if (taskId) {
        const taskKey = taskType.replace('-', '_');
        const assignedToEl = document.getElementById(taskKey + '_assigned_to');
        const instructionsEl = document.getElementById(taskKey + '_instructions');
        const deadlineEl = document.getElementById(taskKey + '_deadline');
        const statusEl = document.getElementById(taskKey + '_status');

        if (assignedToEl && assignedToEl.value) {
            // We'll pre-select the person if we can find them in the list
        }
        if (instructionsEl) {
            document.getElementById('modalInstructions').value = instructionsEl.value || '';
        }
        if (deadlineEl) {
            document.getElementById('modalDeadline').value = deadlineEl.value || '';
        }
        if (statusEl) {
            document.getElementById('modalStatus').value = statusEl.value || 'pending';
        }
    }

    // Load employees
    loadEmployees();

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('assignModal'));
    modal.show();
}

function loadEmployees() {
    const source = document.querySelector('input[name="employeeSource"]:checked').value;
    const url = `/institute/admin/exit-assignable-employees?exit_id=${currentExitId}&source=${source}`;

    document.getElementById('loadingEmployees').style.display = 'block';
    document.getElementById('employeeSelectContainer').style.display = 'none';

    fetch(url, {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loadingEmployees').style.display = 'none';
        document.getElementById('employeeSelectContainer').style.display = 'block';

        if (data.success && data.employees) {
            const select = document.getElementById('assignPersonSelect');
            select.innerHTML = '<option value="">Select a person...</option>';

            // Group employees by type
            const hrEmployees = data.employees.filter(e => e.type === 'hr');
            const deptEmployees = data.employees.filter(e => e.type === 'department');

            if (hrEmployees.length > 0) {
                const optgroup = document.createElement('optgroup');
                optgroup.label = '👔 HR/Admin';
                hrEmployees.forEach(emp => {
                    const option = document.createElement('option');
                    option.value = emp.id;
                    option.textContent = emp.label;
                    optgroup.appendChild(option);
                });
                select.appendChild(optgroup);
            }

            if (deptEmployees.length > 0) {
                const optgroup = document.createElement('optgroup');
                optgroup.label = `🏢 Same Department (${data.department_name || 'N/A'})`;
                deptEmployees.forEach(emp => {
                    const option = document.createElement('option');
                    option.value = emp.id;
                    option.textContent = emp.label;
                    optgroup.appendChild(option);
                });
                select.appendChild(optgroup);
            }

            // Pre-select existing assignee
            if (currentTaskId) {
                const taskKey = currentTaskType.replace('-', '_');
                const assignedToEl = document.getElementById(taskKey + '_assigned_to');
                if (assignedToEl && assignedToEl.value) {
                    select.value = assignedToEl.value;
                }
            }
        } else {
            document.getElementById('assignPersonSelect').innerHTML = '<option value="">No employees available</option>';
        }
    })
    .catch(error => {
        document.getElementById('loadingEmployees').style.display = 'none';
        document.getElementById('employeeSelectContainer').style.display = 'block';
        document.getElementById('assignPersonSelect').innerHTML = '<option value="">Error loading employees</option>';
        console.error('Error loading employees:', error);
    });
}

// Radio button change
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="employeeSource"]');
    radios.forEach(radio => {
        radio.addEventListener('change', loadEmployees);
    });
});

function submitAssignment() {
    const personId = document.getElementById('assignPersonSelect').value;
    const instructions = document.getElementById('modalInstructions').value;
    const deadline = document.getElementById('modalDeadline').value;
    const status = document.getElementById('modalStatus').value;
    const taskType = document.getElementById('modalTaskType').value;
    const taskId = document.getElementById('modalTaskId').value;

    if (!personId) {
        Swal.fire({
            icon: 'warning',
            title: 'Please Select a Person',
            text: 'You must select someone to assign this task to.',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    Swal.fire({
        title: taskId ? 'Update Assignment?' : 'Assign Task?',
        text: taskId ? 'Are you sure you want to update this assignment?' : 'Are you sure you want to assign this task?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: taskId ? 'Yes, Update' : 'Yes, Assign',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: taskId ? 'Updating...' : 'Assigning...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('exit_id', currentExitId);
            formData.append('task_type', taskType);
            formData.append('assigned_to', personId);
            formData.append('instructions', instructions);
            formData.append('deadline', deadline);
            formData.append('status', status);

            if (taskId) {
                formData.append('task_id', taskId);
            }

            const url = taskId ? '/institute/admin/exit-task-update' : '/institute/admin/exit-task-assign';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();

                    Swal.fire({
                        icon: 'success',
                        title: taskId ? '✅ Updated!' : '✅ Assigned!',
                        html: data.message,
                        timer: 3000,
                        timerProgressBar: true,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Refresh the page to show updated data
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Failed to process request.',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An unexpected error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            });
        }
    });
}

function saveAllTasks() {
    // Submit the form to save all task assignments
    const form = document.getElementById('taskAssignmentForm');
    const formData = new FormData(form);

    Swal.fire({
        title: 'Save All Tasks?',
        text: 'Are you sure you want to save all task assignments?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Save All',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Saving...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch('/institute/admin/exit-tasks-save-all', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Saved!',
                        html: data.message,
                        timer: 3000,
                        timerProgressBar: true,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'Failed to save tasks.',
                        confirmButtonColor: '#ef4444'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An unexpected error occurred. Please try again.',
                    confirmButtonColor: '#ef4444'
                });
            });
        }
    });
}
</script>

@endsection