@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Exit Task Management')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --ink: #101828;
    --ink-soft: #5b6b7f;
    --ink-faint: #8a97a8;
    --surface: #ffffff;
    --bg: #f5f6f8;
    --line: #e5e8ec;
    --line-soft: #eef0f3;

    --accent: #0e7c6b;
    --accent-dark: #0b5e52;
    --accent-soft: #e4f5f1;

    --amber: #b54708;
    --amber-bg: #fff6e9;
    --amber-line: #fbdba7;

    --blue: #175cd3;
    --blue-bg: #eff6ff;
    --blue-line: #b8d8ff;

    --green: #067647;
    --green-bg: #ecfdf3;
    --green-line: #abefc6;

    --red: #b42318;
    --red-bg: #fef3f2;
    --red-line: #fda29b;

    --maroon: #9f1239;
    --maroon-bg: #fdf0f4;

    --radius: 14px;
    --radius-sm: 10px;
    --radius-xs: 8px;
    --shadow-sm: 0 1px 2px rgba(16, 24, 40, 0.05);
    --shadow-md: 0 4px 12px rgba(16, 24, 40, 0.06);
    --font-display: 'Lexend', 'Inter', sans-serif;
    --font-body: 'Inter', -apple-system, sans-serif;
}

.tm-wrap { font-family: var(--font-body); color: var(--ink); }
.tm-wrap * { box-sizing: border-box; }

/* ============ Page Header ============ */
.tm-page-header {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 22px 28px;
}
.tm-page-header .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}
.tm-page-header .header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}
.tm-page-header .header-icon {
    width: 46px;
    height: 46px;
    background: var(--accent-soft);
    color: var(--accent-dark);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    flex-shrink: 0;
}
.tm-page-header h4 {
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 1.2rem;
    color: var(--ink);
    margin: 0 0 2px;
    letter-spacing: -0.01em;
}
.tm-page-header .subtitle {
    color: var(--ink-soft);
    font-size: 0.85rem;
    margin: 0;
}
.tm-btn-refresh {
    background: var(--surface);
    border: 1px solid var(--line);
    color: var(--ink-soft);
    padding: 8px 16px;
    border-radius: var(--radius-xs);
    font-size: 0.82rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
}
.tm-btn-refresh:hover {
    border-color: var(--accent);
    color: var(--accent-dark);
    background: var(--accent-soft);
}

/* ============ Statistics Ledger ============ */
.tm-stats-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    background: var(--surface);
    border: 1px solid var(--line);
    border-top: none;
}
.tm-stat-card {
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-right: 1px solid var(--line-soft);
}
.tm-stat-card:last-child { border-right: none; }

.tm-stat-card .stat-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--radius-xs);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.tm-stat-card .stat-icon.total { background: var(--accent-soft); color: var(--accent-dark); }
.tm-stat-card .stat-icon.pending { background: var(--amber-bg); color: var(--amber); }
.tm-stat-card .stat-icon.in-progress { background: var(--blue-bg); color: var(--blue); }
.tm-stat-card .stat-icon.completed { background: var(--green-bg); color: var(--green); }
.tm-stat-card .stat-icon.overdue { background: var(--red-bg); color: var(--red); }

.tm-stat-card .stat-info .stat-number {
    font-family: var(--font-display);
    font-variant-numeric: tabular-nums;
    font-size: 21px;
    font-weight: 600;
    color: var(--ink);
    line-height: 1.2;
}
.tm-stat-card .stat-info .stat-label {
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--ink-faint);
    font-weight: 600;
    margin-top: 1px;
}

/* ============ Filters ============ */
.tm-filters {
    background: var(--bg);
    border: 1px solid var(--line);
    border-top: none;
    padding: 14px 24px;
}
.tm-filters .filter-group {
    flex: 1;
    min-width: 150px;
}
.tm-filters .filter-group label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--ink-faint);
    font-weight: 700;
    margin-bottom: 5px;
}
.tm-filters .filter-group select,
.tm-filters .filter-group input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--line);
    border-radius: var(--radius-xs);
    font-size: 0.84rem;
    color: var(--ink);
    background: var(--surface);
    transition: all 0.15s ease;
}
.tm-filters .filter-group select:focus,
.tm-filters .filter-group input:focus {
    border-color: var(--accent);
    outline: none;
    box-shadow: 0 0 0 3px rgba(14, 124, 107, 0.12);
}
.tm-filters .filter-group input::placeholder { color: var(--ink-faint); }

.filter-actions { display: flex; gap: 8px; }
.btn-filter-apply {
    background: var(--accent);
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.84rem;
    cursor: pointer;
    transition: all 0.15s ease;
}
.btn-filter-apply:hover { background: var(--accent-dark); }
.btn-filter-reset {
    background: var(--surface);
    color: var(--ink-soft);
    border: 1px solid var(--line);
    padding: 8px 16px;
    border-radius: var(--radius-xs);
    font-weight: 600;
    font-size: 0.84rem;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
}
.btn-filter-reset:hover { background: var(--line-soft); color: var(--ink); }

/* ============ Tasks List ============ */
.tm-tasks-wrap {
    background: var(--bg);
    border: 1px solid var(--line);
    border-top: none;
    border-radius: 0 0 var(--radius) var(--radius);
    padding: 20px;
}
.tm-tasks-grid {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.tm-task-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-left: 4px solid var(--accent);
    border-radius: var(--radius-sm);
    padding: 18px 20px;
    box-shadow: var(--shadow-sm);
    transition: box-shadow 0.15s ease;
}
.tm-task-card:hover { box-shadow: var(--shadow-md); }
.tm-task-card[data-task-type="kt"] { border-left-color: var(--accent); }
.tm-task-card[data-task-type="exit_interview"] { border-left-color: var(--amber); }
.tm-task-card[data-task-type="asset_clearance"] { border-left-color: var(--blue); }
.tm-task-card[data-task-type="fnf"] { border-left-color: var(--maroon); }

.tm-task-card .task-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 8px;
}
.tm-task-card .task-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 600;
    border: 1px solid transparent;
}
.tm-task-card .task-type-badge.kt { background: var(--accent-soft); color: var(--accent-dark); }
.tm-task-card .task-type-badge.exit_interview { background: var(--amber-bg); color: var(--amber); }
.tm-task-card .task-type-badge.asset_clearance { background: var(--blue-bg); color: var(--blue); }
.tm-task-card .task-type-badge.fnf { background: var(--maroon-bg); color: var(--maroon); }

.tm-task-card .task-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    border: 1px solid;
}
.tm-task-card .task-status-badge.pending { background: var(--amber-bg); color: var(--amber); border-color: var(--amber-line); }
.tm-task-card .task-status-badge.in-progress { background: var(--blue-bg); color: var(--blue); border-color: var(--blue-line); }
.tm-task-card .task-status-badge.completed { background: var(--green-bg); color: var(--green); border-color: var(--green-line); }
.tm-task-card .task-status-badge.overdue { background: var(--red-bg); color: var(--red); border-color: var(--red-line); }

.tm-task-card .task-body {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 24px;
}
@media (max-width: 900px) {
    .tm-task-card .task-body { grid-template-columns: 1fr; }
}

.tm-task-card .task-meta { display: flex; flex-direction: column; gap: 10px; }

.tm-task-card .task-employee {
    display: flex;
    align-items: center;
    gap: 10px;
}
.tm-task-card .task-employee .avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: var(--ink);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}
.tm-task-card .task-employee .employee-name {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.92rem;
}
.tm-task-card .task-employee .employee-code {
    font-size: 0.72rem;
    color: var(--ink-faint);
}

.tm-task-card .task-assignee {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    color: var(--ink-soft);
    padding: 7px 10px;
    background: var(--bg);
    border-radius: var(--radius-xs);
}
.tm-task-card .task-assignee i { color: var(--accent); width: 14px; }

.tm-task-card .task-deadline {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--ink-soft);
}
.tm-task-card .task-deadline.overdue { color: var(--red); font-weight: 600; }
.tm-task-card .task-deadline i { width: 14px; }

.tm-task-card .policy-details {
    padding: 9px 12px;
    background: var(--bg);
    border-radius: var(--radius-xs);
    border-left: 3px solid var(--accent);
}
.tm-task-card .policy-details .policy-title {
    font-size: 0.66rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--ink-faint);
    margin-bottom: 3px;
}
.tm-task-card .policy-details .policy-name {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.86rem;
}

.tm-task-card .policy-due-date {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 9px 12px;
    background: var(--accent-soft);
    border-radius: var(--radius-xs);
    border-left: 3px solid var(--accent);
}
.tm-task-card .policy-due-date.overdue {
    background: var(--red-bg);
    border-left-color: var(--red);
}
.tm-task-card .policy-due-date i {
    color: var(--accent-dark);
    margin-top: 2px;
    flex-shrink: 0;
}
.tm-task-card .policy-due-date.overdue i { color: var(--red); }
.tm-task-card .policy-due-date .due-title {
    font-size: 0.66rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--accent-dark);
    margin-bottom: 2px;
}
.tm-task-card .policy-due-date.overdue .due-title { color: var(--red); }
.tm-task-card .policy-due-date .due-value {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.86rem;
}
.tm-task-card .policy-due-date .due-rule {
    font-size: 0.74rem;
    color: var(--ink-soft);
}

.tm-task-card .task-instructions {
    font-size: 0.8rem;
    color: var(--ink-soft);
    padding: 9px 12px;
    background: var(--bg);
    border-radius: var(--radius-xs);
    line-height: 1.5;
}

/* Progress */
.tm-task-card .progress-section { margin-top: auto; }
.tm-task-card .progress-section .progress {
    height: 6px;
    background: var(--line-soft);
    border-radius: 4px;
    overflow: hidden;
}
.tm-task-card .progress-section .progress-bar {
    height: 100%;
    border-radius: 4px;
    transition: width 0.5s ease;
    background: var(--accent);
}
.tm-task-card .progress-section .progress-label {
    display: flex;
    justify-content: space-between;
    font-size: 0.68rem;
    color: var(--ink-faint);
    margin-top: 5px;
    font-weight: 500;
}

/* Requirements Checklist */
.tm-task-card .task-content-right { display: flex; flex-direction: column; gap: 12px; }

.tm-task-card .requirements-checklist {
    border: 1px solid var(--line);
    border-radius: var(--radius-xs);
    overflow: hidden;
}
.tm-task-card .requirements-checklist .checklist-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 14px;
    background: var(--bg);
    border-bottom: 1px solid var(--line);
    font-size: 0.74rem;
    font-weight: 700;
    color: var(--ink-soft);
}
.tm-task-card .requirements-checklist .checklist-header .progress-text {
    font-weight: 600;
    color: var(--accent-dark);
    background: var(--accent-soft);
    padding: 2px 9px;
    border-radius: 10px;
    font-size: 0.7rem;
}
.tm-task-card .requirements-checklist .checklist-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    border-bottom: 1px solid var(--line-soft);
    transition: background 0.15s ease;
    cursor: pointer;
}
.tm-task-card .requirements-checklist .checklist-item:last-child { border-bottom: none; }
.tm-task-card .requirements-checklist .checklist-item:hover { background: var(--bg); }
.tm-task-card .requirements-checklist .checklist-item .req-checkbox {
    width: 17px;
    height: 17px;
    flex-shrink: 0;
    cursor: pointer;
    accent-color: var(--accent);
}
.tm-task-card .requirements-checklist .checklist-item .req-label {
    flex: 1;
    font-size: 0.84rem;
    color: var(--ink);
}
.tm-task-card .requirements-checklist .checklist-item .req-label.completed {
    text-decoration: line-through;
    color: var(--ink-faint);
}
.tm-task-card .requirements-checklist .checklist-item .req-status {
    font-size: 0.64rem;
    font-weight: 600;
    padding: 2px 9px;
    border-radius: 12px;
}
.tm-task-card .requirements-checklist .checklist-item .req-status.pending { background: var(--amber-bg); color: var(--amber); }
.tm-task-card .requirements-checklist .checklist-item .req-status.in-progress,
.tm-task-card .requirements-checklist .checklist-item .req-status.in_progress { background: var(--blue-bg); color: var(--blue); }
.tm-task-card .requirements-checklist .checklist-item .req-status.completed { background: var(--green-bg); color: var(--green); }

/* Actions */
.tm-task-card .task-actions {
    display: flex;
    gap: 8px;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid var(--line-soft);
    flex-wrap: wrap;
    justify-content: flex-end;
}
.tm-task-card .task-actions .btn-action {
    padding: 7px 15px;
    border-radius: var(--radius-xs);
    font-size: 0.78rem;
    font-weight: 600;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.tm-task-card .task-actions .btn-view {
    background: var(--surface);
    color: var(--ink-soft);
    border-color: var(--line);
}
.tm-task-card .task-actions .btn-view:hover { border-color: var(--accent); color: var(--accent-dark); background: var(--accent-soft); }
.tm-task-card .task-actions .btn-complete {
    background: var(--accent);
    color: #fff;
}
.tm-task-card .task-actions .btn-complete:hover { background: var(--accent-dark); }

/* Empty state */
.tm-empty-state {
    text-align: center;
    padding: 70px 20px;
    background: var(--surface);
    border: 1px dashed var(--line);
    border-radius: var(--radius-sm);
}
.tm-empty-state .icon { font-size: 40px; color: var(--ink-faint); margin-bottom: 14px; }
.tm-empty-state h5 { font-family: var(--font-display); font-weight: 600; color: var(--ink); margin-bottom: 4px; }
.tm-empty-state p { color: var(--ink-faint); font-size: 0.88rem; }

/* ============ Modal ============ */
.modal-content { border: none; border-radius: var(--radius); overflow: hidden; }
.modal-header {
    background: var(--ink);
    color: #fff;
    padding: 18px 24px;
}
.modal-header .modal-title { font-family: var(--font-display); font-weight: 600; font-size: 1rem; }
.modal-header .btn-close { color: #fff; opacity: 0.8; filter: invert(1) grayscale(1); }
.modal-body { padding: 22px 24px; max-height: 70vh; overflow-y: auto; font-family: var(--font-body); }
.modal-footer { padding: 14px 24px; border-top: 1px solid var(--line); }

.task-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--line-soft);
    font-size: 0.85rem;
    gap: 12px;
}
.task-detail-row .label { color: var(--ink-faint); font-weight: 600; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; }
.task-detail-row .value { color: var(--ink); text-align: right; }

.modal-checklist .checklist-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-bottom: 1px solid var(--line-soft);
}
.modal-checklist .checklist-item:last-child { border-bottom: none; }
.modal-checklist .checklist-item .req-checkbox { width: 19px; height: 19px; accent-color: var(--accent); cursor: pointer; }
.modal-checklist .checklist-item .req-label { flex: 1; font-size: 0.88rem; color: var(--ink); }
.modal-checklist .checklist-item .req-label.completed { text-decoration: line-through; color: var(--ink-faint); }
.modal-checklist .checklist-item .req-notes { font-size: 0.74rem; color: var(--ink-faint); }
.modal-checklist .checklist-item .req-status {
    font-size: 0.64rem; font-weight: 600; padding: 2px 9px; border-radius: 12px;
}
.modal-checklist .checklist-item .req-status.pending { background: var(--amber-bg); color: var(--amber); }
.modal-checklist .checklist-item .req-status.in-progress { background: var(--blue-bg); color: var(--blue); }
.modal-checklist .checklist-item .req-status.completed { background: var(--green-bg); color: var(--green); }

/* Responsive */
@media (max-width: 768px) {
    .tm-page-header { padding: 18px 20px; }
    .tm-stats-row { grid-template-columns: 1fr 1fr; }
    .tm-stat-card { border-bottom: 1px solid var(--line-soft); }
    .tm-filters { padding: 14px 16px; flex-direction: column; align-items: stretch; }
    .tm-filters .filter-group { min-width: 100%; }
    .tm-tasks-wrap { padding: 14px; }
}
</style>

<div class="container-fluid tm-wrap">

    {{-- Header --}}
    <div class="tm-page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon"><i class="fas fa-tasks"></i></div>
                <div>
                    <h4>Exit Task Management</h4>
                    <p class="subtitle">Track and complete offboarding requirements for every departing employee</p>
                </div>
            </div>
            <button onclick="window.location.reload()" class="tm-btn-refresh">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="tm-stats-row">
        <div class="tm-stat-card">
            <div class="stat-icon total"><i class="fas fa-list"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $statistics['total'] ?? 0 }}</div>
                <div class="stat-label">Total Tasks</div>
            </div>
        </div>
        <div class="tm-stat-card">
            <div class="stat-icon pending"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $statistics['pending'] ?? 0 }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
        <div class="tm-stat-card">
            <div class="stat-icon in-progress"><i class="fas fa-spinner"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $statistics['in_progress'] ?? 0 }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
        <div class="tm-stat-card">
            <div class="stat-icon completed"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $statistics['completed'] ?? 0 }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        <div class="tm-stat-card">
            <div class="stat-icon overdue"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-info">
                <div class="stat-number">{{ $statistics['overdue'] ?? 0 }}</div>
                <div class="stat-label">Overdue</div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="tm-filters">
        <form method="GET" action="{{ route('exit.tasks.manage') }}" id="filterForm"
            style="width:100%;display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">
            <div class="filter-group">
                <label><i class="fas fa-search"></i> Search</label>
                <input type="text" name="search" placeholder="Employee name or code..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label><i class="fas fa-tag"></i> Task Type</label>
                <select name="task_type">
                    <option value="">All Types</option>
                    @foreach($taskTypes as $key => $type)
                    <option value="{{ $key }}" {{ request('task_type') == $key ? 'selected' : '' }}>
                        {{ $type['label'] }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-flag"></i> Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All (Except Completed)</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="filter-group">
                <label><i class="fas fa-user"></i> Assigned To</label>
                <select name="assigned_to">
                    <option value="">All Users</option>
                    @foreach($assignableUsers as $user)
                    <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter-apply"><i class="fas fa-filter"></i> Apply</button>
                <a href="{{ route('exit.tasks.manage') }}" class="btn-filter-reset"><i class="fas fa-undo"></i> Reset</a>
            </div>
        </form>
    </div>

    {{-- Tasks --}}
    <div class="tm-tasks-wrap">
        <div class="tm-tasks-grid">
            @forelse($processedTasks as $taskData)
            @php
                $task = $taskData['task'];
                $requirements = $taskData['requirements'];
                $policy = $taskData['policy_details'];
                $completedCount = $taskData['completed_count'];
                $totalCount = $taskData['total_count'];
                $percentage = $taskData['completion_percentage'];
                $isOverdue = $task->deadline && \Carbon\Carbon::parse($task->deadline)->isPast() && $task->status !== 'completed';
                $employee = $task->exit->employee ?? null;
                $assignedTo = $task->assignedTo;
                $taskType = $taskTypes[$task->task_type] ?? null;
                $statusClass = $isOverdue ? 'overdue' : $task->status;
                $policyDate = $taskData['policy_date'] ?? null;
            @endphp
            <div class="tm-task-card" data-task-id="{{ $task->id }}" data-task-type="{{ $task->task_type }}">
                {{-- Header --}}
                <div class="task-header">
                    <span class="task-type-badge {{ $task->task_type }}">
                        <i class="fas {{ $taskType['icon'] ?? 'fa-tag' }}"></i>
                        {{ $taskType['label'] ?? ucfirst($task->task_type) }}
                    </span>
                    <span class="task-status-badge {{ $statusClass }}">
                        @if($task->status === 'completed')
                            <i class="fas fa-check-circle"></i> Completed
                        @elseif($task->status === 'in-progress')
                            <i class="fas fa-spinner fa-spin"></i> In Progress
                        @elseif($isOverdue)
                            <i class="fas fa-exclamation-triangle"></i> Overdue
                        @else
                            <i class="fas fa-clock"></i> Pending
                        @endif
                    </span>
                </div>

                <div class="task-body">
                    {{-- Left: meta --}}
                    <div class="task-meta">
                        <div class="task-employee">
                            <div class="avatar">{{ $employee ? strtoupper(substr($employee->name, 0, 1)) : '?' }}</div>
                            <div>
                                <div class="employee-name">{{ $employee->name ?? 'N/A' }}</div>
                                <div class="employee-code">{{ $employee->employee_code ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="task-assignee">
                            <i class="fas fa-user-check"></i>
                            <span>
                                <strong>Assigned to:</strong> {{ $assignedTo ? $assignedTo->name : 'Not Assigned' }}
                                @if($task->assigned_to_role) <span class="text-muted">({{ $task->assigned_to_role }})</span> @endif
                            </span>
                        </div>

                        @if($task->deadline)
                        <div class="task-deadline {{ $isOverdue ? 'overdue' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Deadline: {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}
                                @if($isOverdue) (Overdue) @endif
                            </span>
                        </div>
                        @endif

                        @if($policy)
                        <div class="policy-details">
                            <div class="policy-title"><i class="fas fa-file-contract"></i> Policy</div>
                            <div class="policy-name">{{ $policy->policy_name ?? 'N/A' }}</div>
                        </div>
                        @endif

                        @if($policyDate)
                        <div class="policy-due-date {{ $policyDate['is_overdue'] ? 'overdue' : '' }}">
                            <i class="fas fa-calendar-check"></i>
                            <div>
                                <div class="due-title">{{ $policyDate['is_overdue'] ? 'Overdue Since (Policy)' : 'Due As Per Policy' }}</div>
                                <div class="due-value">{{ $policyDate['due_date'] }}</div>
                                <div class="due-rule">{{ $policyDate['label'] }} &middot; last working day {{ $policyDate['expected_exit_date'] }}</div>
                            </div>
                        </div>
                        @endif

                        @if($task->instructions)
                        <div class="task-instructions">
                            <i class="fas fa-info-circle" style="color:var(--accent);"></i>
                            {{ $task->instructions }}
                        </div>
                        @endif

                        @if($totalCount > 0)
                        <div class="progress-section">
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $percentage }}%;"></div>
                            </div>
                            <div class="progress-label">
                                <span>{{ $percentage }}% Complete</span>
                                <span>{{ $completedCount }} of {{ $totalCount }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Right: checklist + actions --}}
                    <div class="task-content-right">
                        @if($requirements->count() > 0)
                        <div class="requirements-checklist">
                            <div class="checklist-header">
                                <span><i class="fas fa-list-check"></i> Requirements Checklist</span>
                                <span class="progress-text">{{ $completedCount }}/{{ $totalCount }} done</span>
                            </div>
                            @foreach($requirements as $req)
                            <div class="checklist-item" data-requirement-id="{{ $req->id }}">
                                <input type="checkbox" class="req-checkbox"
                                       {{ $req->status === 'completed' ? 'checked' : '' }}
                                       data-requirement-id="{{ $req->id }}"
                                       onclick="toggleRequirement({{ $req->id }}, this.checked)">
                                <span class="req-label {{ $req->status === 'completed' ? 'completed' : '' }}">
                                    {{ $req->requirement_label }}
                                </span>
                                <span class="req-status {{ $req->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="task-actions">
                            <button class="btn-action btn-view" onclick="viewTask({{ $task->id }})">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            @if($task->status !== 'completed')
                            <button class="btn-action btn-complete" onclick="completeTask({{ $task->id }})">
                                <i class="fas fa-check"></i> Complete Task
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="tm-empty-state">
                <div class="icon"><i class="fas fa-inbox"></i></div>
                <h5>No tasks found</h5>
                <p>There are no exit tasks matching your filters.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $tasks->appends(request()->except('page'))->links() }}
    </div>
</div>

{{-- View Task Modal --}}
<div class="modal fade" id="viewTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i> Task Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewTaskContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading task details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ============================================
// TOGGLE REQUIREMENT
// ============================================
function toggleRequirement(requirementId, checked) {
    const status = checked ? 'completed' : 'pending';
    const checklistItem = document.querySelector(`.checklist-item[data-requirement-id="${requirementId}"]`);

    if (checklistItem) {
        const label = checklistItem.querySelector('.req-label');
        const statusBadge = checklistItem.querySelector('.req-status');

        // Update UI immediately
        if (checked) {
            label.classList.add('completed');
            statusBadge.textContent = 'Completed';
            statusBadge.className = 'req-status completed';
        } else {
            label.classList.remove('completed');
            statusBadge.textContent = 'Pending';
            statusBadge.className = 'req-status pending';
        }
    }

    // Send request to update requirement
    fetch('/institute/admin/exit-task/toggle-requirement', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            requirement_id: requirementId,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update task card progress
            updateTaskProgress(data);

            // If task is completed, show notification
            if (data.task_status === 'completed') {
                Swal.fire({
                    icon: 'success',
                    title: '🎉 All Requirements Completed!',
                    text: 'This task has been marked as completed.',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'Failed to update requirement.',
                confirmButtonColor: '#b42318'
            });
            // Revert the checkbox
            if (checklistItem) {
                const checkbox = checklistItem.querySelector('.req-checkbox');
                if (checkbox) checkbox.checked = !checked;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (checklistItem) {
            const checkbox = checklistItem.querySelector('.req-checkbox');
            if (checkbox) checkbox.checked = !checked;
        }
    });
}

// ============================================
// UPDATE TASK PROGRESS
// ============================================
function updateTaskProgress(data) {
    const card = document.querySelector(`.tm-task-card[data-task-id="${data.task_id}"]`);
    if (!card) return;

    // Update progress bar
    const progressBar = card.querySelector('.progress-bar');
    const progressLabel = card.querySelector('.progress-label');

    if (progressBar) {
        progressBar.style.width = data.completion_percentage + '%';
    }

    if (progressLabel) {
        const span = progressLabel.querySelector('span:last-child');
        if (span) {
            span.textContent = data.completed_count + ' of ' + data.total_count;
        }
        const firstSpan = progressLabel.querySelector('span:first-child');
        if (firstSpan) {
            firstSpan.textContent = data.completion_percentage + '% Complete';
        }
    }

    // Update header progress text
    const headerText = card.querySelector('.checklist-header .progress-text');
    if (headerText) {
        headerText.textContent = data.completed_count + '/' + data.total_count + ' done';
    }

    // Update task status badge
    const statusBadge = card.querySelector('.task-status-badge');
    if (statusBadge && data.task_status) {
        const statusLabels = {
            'pending': '<i class="fas fa-clock"></i> Pending',
            'in-progress': '<i class="fas fa-spinner fa-spin"></i> In Progress',
            'completed': '<i class="fas fa-check-circle"></i> Completed'
        };
        statusBadge.className = 'task-status-badge ' + data.task_status;
        statusBadge.innerHTML = statusLabels[data.task_status] || data.task_status;

        // Update complete button visibility
        const completeBtn = card.querySelector('.btn-complete');
        if (completeBtn && data.task_status === 'completed') {
            completeBtn.remove();
        }
    }
}

// ============================================
// VIEW TASK
// ============================================
function viewTask(taskId) {
    $('#viewTaskModal').modal('show');
    $('#viewTaskContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Loading task details...</p>
        </div>
    `);

    fetch(`/institute/admin/exit-task/${taskId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const task = data.task;
                const employee = data.employee;
                const assignedTo = data.assigned_to;
                const requirements = data.requirements || [];
                const policy = data.policy;
                const completedCount = data.completed_count || 0;
                const totalCount = data.total_count || 0;
                const percentage = data.completion_percentage || 0;
                const policyDate = data.policy_date;

                const taskLabels = {
                    'kt': 'Knowledge Transfer',
                    'exit_interview': 'Exit Interview',
                    'asset_clearance': 'Asset Clearance',
                    'fnf': 'FNF Settlement'
                };

                const statusLabels = {
                    'pending': 'Pending',
                    'in-progress': 'In Progress',
                    'completed': 'Completed'
                };

                let html = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="task-detail-row">
                                <span class="label">Task Type</span>
                                <span class="value"><strong>${taskLabels[task.task_type] || task.task_type}</strong></span>
                            </div>
                            <div class="task-detail-row">
                                <span class="label">Employee</span>
                                <span class="value">${employee ? employee.name + ' (' + employee.employee_code + ')' : 'N/A'}</span>
                            </div>
                            <div class="task-detail-row">
                                <span class="label">Department</span>
                                <span class="value">${employee && employee.department ? employee.department.department : 'N/A'}</span>
                            </div>
                            <div class="task-detail-row">
                                <span class="label">Assigned To</span>
                                <span class="value">${assignedTo ? assignedTo.name : 'Not Assigned'}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="task-detail-row">
                                <span class="label">Status</span>
                                <span class="value">
                                    <span class="task-status-badge ${task.status}">
                                        ${statusLabels[task.status] || task.status}
                                    </span>
                                </span>
                            </div>
                            <div class="task-detail-row">
                                <span class="label">Deadline</span>
                                <span class="value">${task.deadline ? new Date(task.deadline).toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }) : 'No deadline'}</span>
                            </div>
                            <div class="task-detail-row">
                                <span class="label">Progress</span>
                                <span class="value">
                                    <div class="progress" style="height:6px;width:100%;max-width:200px;display:inline-block;">
                                        <div class="progress-bar" style="width:${percentage}%;"></div>
                                    </div>
                                    <small class="text-muted ms-2">${completedCount}/${totalCount}</small>
                                </span>
                            </div>
                        </div>
                    </div>
                `;

                // Policy details
                if (policy) {
                    html += `
                        <div class="alert alert-light border">
                            <strong><i class="fas fa-file-contract me-1"></i> Policy:</strong>
                            ${policy.policy_name} (${policy.policy_code})
                            <span class="badge bg-light text-dark ms-2 d-none">Notice: ${policy.default_notice_period || 'N/A'} days</span>
                        </div>
                    `;
                }

                // Policy-based due date (e.g. "5 days before last working day")
                if (policyDate) {
                    const overdueClass = policyDate.is_overdue ? 'text-danger' : 'text-success';
                    html += `
                        <div class="alert ${policyDate.is_overdue ? 'alert-danger' : 'alert-light'} border mt-2">
                            <strong><i class="fas fa-calendar-check me-1"></i> ${policyDate.is_overdue ? 'Overdue Since (Policy)' : 'Due As Per Policy'}:</strong>
                            <span class="${overdueClass}">${policyDate.due_date}</span>
                            <div class="text-muted small mt-1">${policyDate.label} &middot; last working day ${policyDate.expected_exit_date}</div>
                        </div>
                    `;
                }

                // Requirements checklist
                if (requirements.length > 0) {
                    html += `
                        <h6 class="mt-3 mb-2"><i class="fas fa-list-check me-1"></i> Requirements Checklist</h6>
                        <div class="modal-checklist">
                            ${requirements.map(req => `
                                <div class="checklist-item">
                                    <input type="checkbox" class="req-checkbox"
                                           ${req.status === 'completed' ? 'checked' : ''}
                                           onchange="toggleRequirement(${req.id}, this.checked)">
                                    <span class="req-label ${req.status === 'completed' ? 'completed' : ''}">
                                        ${req.requirement_label}
                                    </span>
                                    <span class="req-status ${req.status}">
                                        ${req.status.charAt(0).toUpperCase() + req.status.slice(1)}
                                    </span>
                                    ${req.notes ? `<span class="req-notes"><i class="fas fa-sticky-note"></i> ${req.notes}</span>` : ''}
                                </div>
                            `).join('')}
                        </div>
                    `;
                }

                // Instructions
                if (task.instructions) {
                    html += `
                        <div class="mt-3">
                            <h6><i class="fas fa-info-circle me-1"></i> Instructions</h6>
                            <p class="text-muted">${task.instructions}</p>
                        </div>
                    `;
                }

                if (task.completion_notes) {
                    html += `
                        <div class="mt-3">
                            <h6><i class="fas fa-sticky-note me-1"></i> Completion Notes</h6>
                            <p class="text-muted">${task.completion_notes}</p>
                        </div>
                    `;
                }

                $('#viewTaskContent').html(html);
            } else {
                $('#viewTaskContent').html(`
                    <div class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-circle" style="font-size:32px;"></i>
                        <p class="mt-2">${data.message || 'Failed to load task details'}</p>
                    </div>
                `);
            }
        })
        .catch(error => {
            $('#viewTaskContent').html(`
                <div class="text-center py-4 text-danger">
                    <i class="fas fa-exclamation-circle" style="font-size:32px;"></i>
                    <p class="mt-2">Error loading task details. Please try again.</p>
                </div>
            `);
        });
}

// ============================================
// COMPLETE TASK
// ============================================
function completeTask(taskId) {
    // Check if all requirements are completed
    const card = document.querySelector(`.tm-task-card[data-task-id="${taskId}"]`);
    const reqCheckboxes = card ? card.querySelectorAll('.req-checkbox') : [];
    const allChecked = reqCheckboxes.length > 0 && Array.from(reqCheckboxes).every(cb => cb.checked);

    if (!allChecked && reqCheckboxes.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Requirements',
            text: 'Please complete all requirements before marking this task as completed.',
            confirmButtonColor: '#b54708'
        });
        return;
    }

    Swal.fire({
        title: 'Complete Task?',
        text: 'Are you sure you want to mark this task as completed?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0e7c6b',
        cancelButtonColor: '#5b6b7f',
        confirmButtonText: 'Yes, Complete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Completing...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch('/institute/admin/exit-task/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    task_id: taskId,
                    status: 'completed'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Task Completed!',
                        text: 'The task has been marked as completed.',
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
                        text: data.message || 'Failed to complete task.',
                        confirmButtonColor: '#b42318'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    confirmButtonColor: '#b42318'
                });
            });
        }
    });
}
</script>

@endsection