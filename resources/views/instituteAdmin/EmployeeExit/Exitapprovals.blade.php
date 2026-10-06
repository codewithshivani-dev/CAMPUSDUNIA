@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Exit Approvals')

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
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow: 0 1px 3px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1);
}

.approval-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 4px;
}

.approval-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 28px 32px;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.approval-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.approval-header .header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    z-index: 1;
    position: relative;
}

.approval-header .header-icon {
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

.approval-header h4 {
    font-weight: 700;
    font-size: 1.35rem;
    margin: 0;
    letter-spacing: -0.3px;
}

.approval-header .subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 0.9rem;
    margin: 0;
}

/* FILTERS */
.filters-section {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    padding: 16px 28px;
}

.filters-section .filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: flex-end;
}

.filters-section .filter-group {
    flex: 1;
    min-width: 150px;
}

.filters-section .filter-group label {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    margin-bottom: 4px;
}

.filters-section .filter-group select,
.filters-section .filter-group input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xs);
    font-size: 0.85rem;
    color: var(--gray-700);
    background: #fff;
    transition: all 0.3s ease;
}

.filters-section .filter-group select:focus,
.filters-section .filter-group input:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.filters-section .filter-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-filter-apply {
    background: var(--primary);
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-filter-apply:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-filter-reset {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
    padding: 8px 16px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-filter-reset:hover {
    background: var(--gray-200);
}

/* TABS */
.tabs-modern {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    padding: 0 28px;
}

.tabs-modern .nav-tabs {
    border-bottom: 2px solid var(--gray-200);
    gap: 4px;
}

.tabs-modern .nav-tabs .nav-link {
    border: none;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--gray-500);
    border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    transition: all 0.3s ease;
    position: relative;
}

.tabs-modern .nav-tabs .nav-link:hover {
    color: var(--primary);
    background: var(--gray-50);
}

.tabs-modern .nav-tabs .nav-link.active {
    color: var(--primary);
    background: transparent;
}

.tabs-modern .nav-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary);
    border-radius: 2px;
}

.tabs-modern .nav-tabs .nav-link .badge-custom {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: 6px;
}

.badge-pending-count {
    background: var(--warning-bg);
    color: #92400e;
}

.badge-approved-count {
    background: var(--success-bg);
    color: #166534;
}

.badge-rejected-count {
    background: var(--danger-bg);
    color: #991b1b;
}

/* TABLE */
.table-wrapper {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    border-radius: 0 0 var(--radius) var(--radius);
    padding: 20px 28px;
}

.approval-table {
    width: 100%;
    border-collapse: collapse;
}

.approval-table thead th {
    padding: 12px 14px;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    font-weight: 700;
    border-bottom: 2px solid var(--gray-200);
    text-align: left;
}

.approval-table tbody td {
    padding: 14px;
    font-size: 0.9rem;
    color: var(--gray-700);
    border-bottom: 1px solid var(--gray-100);
    vertical-align: middle;
}

.approval-table tbody tr:hover {
    background: var(--gray-50);
}

.approval-table tbody tr:last-child td {
    border-bottom: none;
}

/* STATUS BADGES */
.badge-status-approval {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-status-approval.pending {
    background: var(--warning-bg);
    color: #92400e;
}

.badge-status-approval.approved {
    background: var(--success-bg);
    color: #166534;
}

.badge-status-approval.rejected {
    background: var(--danger-bg);
    color: #991b1b;
}

/* ACTION BUTTONS */
.action-buttons {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.btn-action-approve {
    background: var(--success);
    border: none;
    color: #fff;
    padding: 6px 14px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-action-approve:hover {
    background: #16a34a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.btn-action-reject {
    background: var(--danger);
    border: none;
    color: #fff;
    padding: 6px 14px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-action-reject:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-action-assign {
    background: var(--primary);
    border: none;
    color: #fff;
    padding: 6px 14px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-action-assign:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-action-assign a {
    color: #fff;
    text-decoration: none;
}

.btn-action-processed {
    background: var(--gray-200);
    color: var(--gray-500);
    padding: 6px 14px;
    border-radius: var(--radius-xs);
    font-size: 0.8rem;
    font-weight: 600;
    border: none;
    cursor: default;
}

/* Task Status Badge */
.task-status-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 0.65rem;
    font-weight: 600;
}

.task-status-badge.pending {
    background: var(--warning-bg);
    color: #92400e;
}

.task-status-badge.in-progress {
    background: var(--primary-bg);
    color: var(--primary-dark);
}

.task-status-badge.completed {
    background: var(--success-bg);
    color: #166534;
}

/* EMPTY STATE */
.empty-state-approval {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-approval .icon {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}

.empty-state-approval h5 {
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: 8px;
}

.empty-state-approval p {
    color: var(--gray-500);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .approval-header {
        padding: 20px;
    }
    .filters-section {
        padding: 16px;
    }
    .filters-section .filter-row {
        flex-direction: column;
    }
    .filters-section .filter-group {
        min-width: 100%;
    }
    .tabs-modern {
        padding: 0 16px;
    }
    .tabs-modern .nav-tabs .nav-link {
        padding: 10px 14px;
        font-size: 0.8rem;
    }
    .table-wrapper {
        padding: 16px;
        overflow-x: auto;
    }
    .approval-table {
        min-width: 900px;
    }
}

@media (max-width: 576px) {
    .approval-header .header-left {
        flex-wrap: wrap;
    }
    .approval-header .header-icon {
        width: 44px;
        height: 44px;
        font-size: 20px;
    }
    .approval-header h4 {
        font-size: 1.1rem;
    }
    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
    .btn-action-approve,
    .btn-action-reject,
    .btn-action-assign {
        width: 100%;
        justify-content: center;
    }
}

/* ANIMATIONS */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-up { animation: fadeUp 0.4s ease forwards; }
.delay-1 { animation-delay: 0.05s; }
.delay-2 { animation-delay: 0.1s; }
.delay-3 { animation-delay: 0.15s; }
</style>

<div class="approval-wrapper">
    
    {{-- HEADER --}}
    <div class="approval-header fade-up">
        <div class="header-left">
            <div class="header-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div>
                <h4>Exit Requests</h4>
                <p class="subtitle">Review and manage employee exit requests with task assignments</p>
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="filters-section fade-up delay-1">
        <form method="GET" action="{{ route('exit.approvals') }}" id="filterForm">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="search"><i class="fas fa-search"></i> Search</label>
                    <input type="text" id="search" name="search" 
                           placeholder="Employee name or code..." 
                           value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label for="department_id"><i class="fas fa-building"></i> Department</label>
                    <select id="department_id" name="department_id">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->department_id }}" 
                                    {{ request('department_id') == $department->department_id ? 'selected' : '' }}>
                                {{ $department->department }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label for="exit_reason"><i class="fas fa-tag"></i> Exit Reason</label>
                    <select id="exit_reason" name="exit_reason">
                        <option value="">All Reasons</option>
                        <option value="resignation" {{ request('exit_reason') == 'resignation' ? 'selected' : '' }}>Resignation</option>
                        <option value="termination" {{ request('exit_reason') == 'termination' ? 'selected' : '' }}>Termination</option>
                        <option value="end_of_contract" {{ request('exit_reason') == 'end_of_contract' ? 'selected' : '' }}>End of Contract</option>
                        <option value="mutual_agreement" {{ request('exit_reason') == 'mutual_agreement' ? 'selected' : '' }}>Mutual Agreement</option>
                        <option value="retirement" {{ request('exit_reason') == 'retirement' ? 'selected' : '' }}>Retirement</option>
                        <option value="other" {{ request('exit_reason') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="filter-group" style="min-width: 120px; flex: 0.5;">
                    <label for="date_from"><i class="fas fa-calendar-alt"></i> From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group" style="min-width: 120px; flex: 0.5;">
                    <label for="date_to"><i class="fas fa-calendar-alt"></i> To</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter-apply">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                    <a href="{{ route('exit.approvals', ['tab' => $activeTab]) }}" class="btn-filter-reset">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </div>
            <input type="hidden" name="tab" value="{{ $activeTab }}">
        </form>
    </div>

    {{-- TABS --}}
    <div class="tabs-modern fade-up delay-1">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'pending' ? 'active' : '' }}" 
                   href="{{ route('exit.approvals', array_merge(['tab' => 'pending'], request()->except('tab'))) }}">
                    <i class="fas fa-clock me-1"></i> Pending
                    <span class="badge-custom badge-pending-count">{{ $pending->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'approved' ? 'active' : '' }}" 
                   href="{{ route('exit.approvals', array_merge(['tab' => 'approved'], request()->except('tab'))) }}">
                    <i class="fas fa-check-circle me-1"></i> Approved
                    <span class="badge-custom badge-approved-count">{{ $approved->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'rejected' ? 'active' : '' }}" 
                   href="{{ route('exit.approvals', array_merge(['tab' => 'rejected'], request()->except('tab'))) }}">
                    <i class="fas fa-times-circle me-1"></i> Rejected
                    <span class="badge-custom badge-rejected-count">{{ $rejected->count() }}</span>
                </a>
            </li>
        </ul>
    </div>

    {{-- TABLE --}}
    <div class="table-wrapper fade-up delay-2">
        @php
            $approvals = [];
            if ($activeTab == 'pending') $approvals = $pending;
            elseif ($activeTab == 'approved') $approvals = $approved;
            elseif ($activeTab == 'rejected') $approvals = $rejected;
        @endphp

        @if($approvals->isEmpty())
            <div class="empty-state-approval">
                <div class="icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h5>No {{ $activeTab }} Approvals</h5>
                <p class="text-muted">
                    @if($activeTab == 'pending')
                        There are no pending exit requests waiting for your approval.
                    @elseif($activeTab == 'approved')
                        No exit requests have been approved yet.
                    @else
                        No exit requests have been rejected.
                    @endif
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="approval-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Reason</th>
                            <th>Resignation Date</th>
                            <th>Document</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvals as $approval)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:var(--gray-800);">{{ $approval->employee_name }}</div>
                                <div style="font-size:0.75rem;color:var(--gray-400);">{{ $approval->employee_code }}</div>
                            </td>
                            <td>{{ $approval->department_name ?? 'N/A' }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $approval->exit_reason)) }}</td>
                            <td>
                                @if($approval->resignation_date)
                                    <div style="font-weight:500;color:var(--gray-700);">
                                        {{ \Carbon\Carbon::parse($approval->resignation_date)->format('d M Y') }}
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($approval->exit_document)
                                    <a href="{{ asset('/image/' .  $approval->exit_document) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf"></i> View
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size:0.75rem;">No document</span>
                                @endif
                            </td>
                            <td>
                                @if($approval->status == 'Pending')
                                    <span class="badge-status-approval pending">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @elseif($approval->status == 'Approved')
                                    <span class="badge-status-approval approved">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </span>
                                @else
                                    <span class="badge-status-approval rejected">
                                        <i class="fas fa-times-circle"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($approval->status == 'Pending')
                                    <div class="action-buttons">
                                        <button onclick="processApproval({{ $approval->id }}, 'approve')" 
                                                class="btn-action-approve">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button onclick="processApproval({{ $approval->id }}, 'reject')" 
                                                class="btn-action-reject">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </div>
                                @else
                                    <a href="{{ route('exit.assign.tasks', ['exitId' => $approval->exit_id]) }}" 
                                       class="btn-action-assign" style="display:inline-flex;">
                                        <i class="fas fa-tasks"></i> Assign Tasks
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if(method_exists($approvals, 'links'))
                <div class="mt-3">
                    {{ $approvals->appends(request()->except('page'))->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

{{-- Hidden Form for AJAX --}}
<form id="approvalForm" action="" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="action" id="actionInput">
    <input type="hidden" name="comments" id="commentsInput">
</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ============================================
// APPROVAL FUNCTIONS
// ============================================
function processApproval(id, action) {
    const actionText = action === 'approve' ? 'APPROVE' : 'REJECT';
    const isApprove = action === 'approve';

    Swal.fire({
        title: `${actionText} Exit Request?`,
        html: `
            <div class="text-left">
                <p>Are you sure you want to <strong>${actionText}</strong> this exit request?</p>
                <div class="alert alert-${isApprove ? 'info' : 'warning'} mt-2">
                    <i class="fas fa-${isApprove ? 'info-circle' : 'exclamation-triangle'} me-1"></i>
                    ${isApprove
                        ? 'This will approve the exit request. You can assign tasks after approval.'
                        : 'This will permanently reject the exit request.'}
                </div>
                <div class="form-group mt-3">
                    <label for="comments" class="form-label">Comments <span class="text-muted">(Optional)</span>:</label>
                    <textarea id="comments" class="form-control" rows="3"
                              placeholder="Add your comments..."></textarea>
                </div>
            </div>
        `,
        icon: isApprove ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonColor: isApprove ? '#22c55e' : '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: `Yes, ${actionText}`,
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        preConfirm: () => {
            const comments = document.getElementById('comments').value;
            return { comments };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const comments = result.value?.comments || '';

            Swal.fire({
                title: `🔴 Final Confirmation Required`,
                html: `
                    <div style="padding:10px 0;">
                        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:12px;">
                            <p style="color:#991b1b;font-weight:600;margin-bottom:4px;">
                                <i class="fas fa-exclamation-triangle" style="margin-right:8px;"></i>
                                You are about to <strong>${actionText}</strong> this exit request!
                            </p>
                            <p style="color:#7f1d1d;font-size:0.9rem;margin:0;">
                                ${isApprove
                                    ? 'The exit request will be approved. You can then assign tasks for KT, Interview, etc.'
                                    : 'This action is irreversible. The exit request will be permanently rejected.'}
                            </p>
                        </div>
                        <p style="color:#64748b;font-size:0.9rem;">
                            Please type <strong style="color:#dc2626;">"${actionText}"</strong> to confirm:
                        </p>
                        <input type="text" id="confirmInput" class="form-control"
                               placeholder="Type ${actionText}"
                               style="text-align:center;font-weight:600;border:2px solid #e2e8f0;border-radius:8px;padding:10px;margin-top:8px;">
                    </div>
                `,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: isApprove ? '#22c55e' : '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: `Yes, ${actionText}`,
                cancelButtonText: 'Go Back',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const input = Swal.getPopup().querySelector('#confirmInput');
                    if (!input || input.value !== actionText) {
                        Swal.showValidationMessage(`Please type "${actionText}" to proceed`);
                        return false;
                    }
                    return true;
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((finalResult) => {
                if (finalResult.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: `${actionText === 'APPROVE' ? 'Approving' : 'Rejecting'} exit request...`,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    if (!csrfToken) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'CSRF token not found. Please refresh the page.',
                            confirmButtonColor: '#ef4444'
                        });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    formData.append('action', action);
                    formData.append('comments', comments);

                    const url = `/institute/admin/exit-approval/${id}`;

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
                            Swal.fire({
                                icon: 'success',
                                title: isApprove ? '✅ Approved!' : '✅ Rejected!',
                                html: data.message || `${actionText} request processed successfully.`,
                                timer: 3000,
                                timerProgressBar: true,
                                confirmButtonColor: isApprove ? '#22c55e' : '#ef4444',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                if (isApprove && data.exit_id) {
                                    window.location.href = `/institute/admin/exit-assign-tasks/${data.exit_id}`;
                                } else {
                                    location.reload();
                                }
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
                            text: error.message || 'An unexpected error occurred. Please try again.',
                            confirmButtonColor: '#ef4444'
                        });
                    });
                }
            });
        }
    });
}
</script>

@endsection