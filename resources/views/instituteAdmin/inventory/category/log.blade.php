@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        --card-shadow: 0 20px 60px -15px rgba(99, 102, 241, 0.15);
        --transition-smooth: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-header {
        background: white;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        border: 1px solid rgba(99, 102, 241, 0.08);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }

    .page-header .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header .header-left .icon-wrapper {
        width: 52px;
        height: 52px;
        background: var(--primary-gradient);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.25);
    }

    .page-header .header-left .title-section h4 {
        font-weight: 700;
        margin: 0;
        color: #1e293b;
        letter-spacing: -0.02em;
    }

    .page-header .header-left .title-section .subtitle {
        color: #64748b;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.15rem;
    }

    .page-header .header-left .title-section .subtitle .badge-config {
        background: #f1f5f9;
        color: #475569;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .action-buttons .btn {
        border-radius: 12px;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-buttons .btn-back {
        background: #f1f5f9;
        color: #475569;
        border: none;
    }
    .action-buttons .btn-back:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .action-buttons .btn-edit {
        background: var(--primary-gradient);
        color: white;
        border: none;
    }
    .action-buttons .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35);
        color: white;
    }

    /* Stats Row */
    .stats-row {
        display: flex;
        justify-content: space-around;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
    }

    .stat-item {
        background: white;
        border-radius: 16px;
        padding: 0.9rem 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-item .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: white;
    }

    .stat-item .stat-icon.primary { background: var(--primary-gradient); }
    .stat-item .stat-icon.success { background: linear-gradient(135deg, #34d399, #10b981); }
    .stat-item .stat-icon.warning { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
    .stat-item .stat-icon.danger { background: linear-gradient(135deg, #f87171, #ef4444); }

    .stat-item .stat-content{
        display: flex;
        gap: 5px;
        justify-content: space-between;
    }

    .stat-item .stat-content .stat-number {
        font-weight: 700;
        font-size: 1rem;
        color: #0f172a;
    }

    .stat-item .stat-content .stat-label {
        font-size: 1rem;
        color: #94a3b8;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* Card */
    .logs-card {
        background: white;
        border-radius: 24px;
        border: none;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .logs-card .card-body {
        padding: 1.75rem 1.5rem;
    }

    /* Filter Bar */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .filter-bar .filter-group {
        display: flex;
        align-items: center;
        flex: 1 1 200px;
    }

    .filter-bar .filter-group .input-group-text {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px 0 0 12px;
        color: #94a3b8;
        font-size: 1.5rem;
    }

    .filter-bar .filter-group .form-select,
    .filter-bar .filter-group .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 0 12px 12px 0;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        background-color: white;
        transition: var(--transition-smooth);
    }

    .filter-bar .filter-group .form-select:focus,
    .filter-bar .filter-group .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }

    .filter-bar .total-badge {
        background: #f1f5f9;
        color: #25272b;
        font-weight: 600;
        padding: 0.4rem 1.2rem;
        border-radius: 50px;
        font-size: 0.8rem;
        white-space: nowrap;
    }

    /* Table */
    .table-responsive-custom {
        overflow-x: auto;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
    }

    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e9edf2;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .table-custom tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        background-color: white;
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .table-custom tbody tr {
        transition: var(--transition-smooth);
    }

    .table-custom tbody tr:hover {
        background-color: #fafbff;
        box-shadow: inset 0 0 0 2px rgba(99, 102, 241, 0.06);
    }

    .table-custom .avatar-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.8rem;
        color: white;
        flex-shrink: 0;
        background: var(--primary-gradient);
    }

    .table-custom .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .table-custom .user-info .user-details {
        line-height: 1.3;
    }
    .table-custom .user-info .user-details .name {
        font-weight: 600;
        color: #0f172a;
    }
    .table-custom .user-info .user-details .email {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .badge-custom {
        font-weight: 600;
        padding: 0.35rem 0.9rem;
        border-radius: 50px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .badge-custom.badge-create {
        background: #dcfce7;
        color: #15803d;
    }
    .badge-custom.badge-update {
        background: #fef9c3;
        color: #a16207;
    }
    .badge-custom.badge-delete {
        background: #fee2e2;
        color: #b91c1c;
    }

    .time-cell {
        font-size: 0.8rem;
        line-height: 1.4;
    }
    .time-cell .date-main {
        font-weight: 500;
        color: #1e293b;
    }
    .time-cell .time-sub {
        color: #94a3b8;
        font-size: 0.7rem;
    }
    .time-cell .time-ago {
        color: #94a3b8;
        font-size: 0.65rem;
        display: block;
        margin-top: 0.1rem;
    }

    .btn-view-changes {
        background: #f1f5f9;
        border: none;
        color: #475569;
        border-radius: 10px;
        padding: 0.3rem 0.9rem;
        font-size: 0.75rem;
        font-weight: 600;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-view-changes:hover {
        background: var(--primary-gradient);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.25);
    }

    .btn-view-changes i {
        font-size: 0.7rem;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }

    .pagination-wrapper .info-text {
        font-size: 0.85rem;
        color: #64748b;
    }

    .pagination-wrapper .pagination {
        margin: 0;
        gap: 0.2rem;
    }

    .pagination-wrapper .pagination .page-link {
        border: none;
        border-radius: 10px;
        padding: 0.45rem 0.9rem;
        color: #475569;
        font-weight: 500;
        font-size: 0.8rem;
        background: transparent;
        transition: var(--transition-smooth);
    }

    .pagination-wrapper .pagination .page-link:hover {
        background: #f1f5f9;
        color: #6366f1;
    }

    .pagination-wrapper .pagination .page-item.active .page-link {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .pagination-wrapper .pagination .page-item.disabled .page-link {
        color: #cbd5e1;
        background: transparent;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3.5rem 1.5rem;
    }

    .empty-state .empty-icon {
        font-size: 3.5rem;
        color: #e2e8f0;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #1e293b;
        font-weight: 600;
    }

    .empty-state p {
        color: #94a3b8;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Modal */
    .modal-modern .modal-content {
        border: none;
        border-radius: 24px;
        box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.3);
        overflow: hidden;
    }

    .modal-modern .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.5rem 2rem;
        background: #fafbff;
    }

    .modal-modern .modal-header .modal-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .modal-modern .modal-body {
        padding: 1.75rem 2rem 2rem;
    }

    .modal-modern .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 1rem 2rem;
        background: #fafbff;
    }

    .modal-modern .alert-summary {
        background: #f8fafc;
        border: 1px solid #e9edf2;
        border-radius: 14px;
        padding: 0.9rem 1.25rem;
        color: #475569;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .modal-modern .alert-summary i {
        color: #6366f1;
        font-size: 1.1rem;
    }

    .modal-modern .table-changes {
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
    }

    .modal-modern .table-changes thead th {
        background: #f8fafc;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9edf2;
    }

    .modal-modern .table-changes tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
    }

    .modal-modern .table-changes tbody tr:last-child td {
        border-bottom: none;
    }

    .modal-modern .table-changes .field-name {
        font-weight: 600;
        color: #1e293b;
    }

    .modal-modern .table-changes .old-value {
        color: #b91c1c;
        background: #fef2f2;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        display: inline-block;
        font-size: 0.8rem;
        text-decoration: line-through;
        text-decoration-color: #b91c1c;
    }

    .modal-modern .table-changes .new-value {
        color: #15803d;
        background: #f0fdf4;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        display: inline-block;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .modal-modern .table-changes .empty-value {
        color: #94a3b8;
        font-style: italic;
        font-size: 0.8rem;
    }

    /* Tax Composition Tag */
    .tax-tag {
        display: inline-block;
        background: #f1f5f9;
        padding: 0.15rem 0.6rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        color: #475569;
        margin: 0 0.15rem;
    }

    .tax-tag.cgst { background: #dbeafe; color: #1d4ed8; }
    .tax-tag.sgst { background: #fce7f3; color: #be185d; }
    .tax-tag.igst { background: #d1fae5; color: #065f46; }
    .tax-tag.cess { background: #fef3c7; color: #92400e; }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
            padding: 1.25rem;
        }

        .page-header .header-left {
            flex-wrap: wrap;
        }

        .action-buttons {
            justify-content: flex-start;
        }

        .filter-bar {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-bar .filter-group {
            flex: 1 1 auto;
        }

        .stats-row {
            flex-direction: column;
            gap: 0.75rem;
        }

        .stat-item {
            padding: 0.75rem 1rem;
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 1rem;
            align-items: center;
        }

        .modal-modern .modal-body {
            padding: 1.25rem;
        }

        .modal-modern .modal-header {
            padding: 1rem 1.25rem;
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.75rem 0.9rem;
        }

        .table-custom .user-info .user-details .email {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .page-header .header-left .icon-wrapper {
            width: 42px;
            height: 42px;
            font-size: 1.2rem;
        }

        .page-header .header-left .title-section h4 {
            font-size: 1.1rem;
        }

        .action-buttons .btn {
            font-size: 0.75rem;
            padding: 0.4rem 0.9rem;
        }

        .stat-item .stat-content .stat-number {
            font-size: 1rem;
        }
    }
</style>

<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="header-left">
            <div class="icon-wrapper">
                <i class="fas fa-history"></i>
            </div>
            <div class="title-section">
                <h4>{{ $category->category_name }}</h4>
                <div class="subtitle">
                    <span class="text-muted">•</span>
                    <code class="text-muted" style="background: #f1f5f9; padding: 0.1rem 0.6rem; border-radius: 6px; font-size: 0.75rem;">{{ $category->category_code }}</code>
                    @if($category->configuration)
                        <span class="badge-config">
                            <i class="fas fa-tag me-1"></i> {{ $category->configuration->configuration_name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('inventory.categories.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="{{ route('inventory.categories.edit', $category->id) }}" class="btn btn-edit d-none">
                <i class="fas fa-pen"></i> Edit Category
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="stats-row d-none">
        <div class="stat-item">
            <div class="stat-icon primary"><i class="fas fa-list"></i></div>
            <div class="stat-content">
                <div class="stat-number">{{ $logs->total() }}</div>
                <div class="stat-label">Total Logs</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon success"><i class="fas fa-plus"></i></div>
            <div class="stat-content">
                <div class="stat-number">{{ $logs->where('action', 'created')->count() }}</div>
                <div class="stat-label">Created</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon warning"><i class="fas fa-pen"></i></div>
            <div class="stat-content">
                <div class="stat-number">{{ $logs->where('action', 'updated')->count() }}</div>
                <div class="stat-label">Updated</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon danger"><i class="fas fa-trash"></i></div>
            <div class="stat-content">
                <div class="stat-number">{{ $logs->where('action', 'deleted')->count() }}</div>
                <div class="stat-label">Deleted</div>
            </div>
        </div>
    </div>

    {{-- Logs Card --}}
    <div class="logs-card">
        <div class="card-body">

            @if($logs->count())

                {{-- Filter Bar --}}
                <div class="filter-bar">
                    <div class="filter-group">
                        <span class="input-group-text"><i class="fas fa-sliders-h"></i></span>
                        <select id="actionFilter" class="form-select">
                            <option value="">All Actions</option>
                            <option value="create">📝 Created</option>
                            <option value="update">✏️ Updated</option>
                            <option value="delete">🗑️ Deleted</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchLogs" class="form-control" placeholder="Search by user or description...">
                    </div>

                    <span class="total-badge">
                        <i class="fas fa-file-alt me-1"></i> {{ $logs->total() }} entries
                    </span>
                </div>

                {{-- Table --}}
                <div class="table-responsive-custom">
                    <table class="table-custom" id="logsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th class="sortable">Date & Time</th>
                                <th class="sortable">User</th>
                                <th class="sortable">Action</th>
                                <th class="sortable">Description</th>
                                <th class="sortable">Changes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                            @php
                                // Decode new_data safely
                                $newData = is_array($log->new_data) ? $log->new_data : json_decode($log->new_data, true);
                                $oldData = is_array($log->old_data) ? $log->old_data : json_decode($log->old_data, true);
                                
                                // Get description from new_data
                                $description = $newData['description'] ?? ($log->remarks ?? $log->title ?? '-');
                                
                                // Format tax_composition if it exists
                                $taxDisplay = '';
                                if (isset($newData['tax_composition']) && is_array($newData['tax_composition'])) {
                                    $taxParts = [];
                                    foreach ($newData['tax_composition'] as $tax) {
                                        if (isset($tax['type']) && isset($tax['rate'])) {
                                            $taxParts[] = '<span class="tax-tag ' . strtolower($tax['type']) . '">' . strtoupper($tax['type']) . ' ' . $tax['rate'] . '%</span>';
                                        }
                                    }
                                    if (!empty($taxParts)) {
                                        $taxDisplay = ' <span style="font-size:0.75rem; color:#64748b;">|</span> ' . implode(' ', $taxParts);
                                    }
                                }
                            @endphp
                            <tr data-action="{{ $log->action }}" data-search="{{ $description }} {{ $log->user->name ?? '' }}">
                                <td><span class="fw-semibold text-muted" style="font-size: 0.85rem;">{{ $logs->firstItem() + $loop->index }}</span></td>
                                <td>
                                    <div class="time-cell">
                                        <div class="date-main">{{ $log->created_at->format('d M Y') }}</div>
                                        <div class="time-sub">{{ $log->created_at->format('h:i A') }}</div>
                                        <span class="time-ago">{{ $log->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <span class="avatar-circle" style="background: {{ $log->user ? 'var(--primary-gradient)' : '#94a3b8' }};">
                                            {{ $log->user ? strtoupper(substr($log->user->name, 0, 1)) : 'S' }}
                                        </span>
                                        <div class="user-details">
                                            <div class="name">{{ $log->user->name ?? 'System' }}</div>
                                            <div class="email">{{ $log->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($log->action == 'create')
                                        <span class="badge-custom badge-create"><i class="fas fa-plus-circle"></i> Created</span>
                                    @elseif($log->action == 'update')
                                        <span class="badge-custom badge-update"><i class="fas fa-edit"></i> Updated</span>
                                    @elseif($log->action == 'delete')
                                        <span class="badge-custom badge-delete"><i class="fas fa-trash-alt"></i> Deleted</span>
                                    @else
                                        <span class="badge-custom" style="background:#e0e7ff; color:#4338ca;">{{ ucfirst($log->action) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="color: #334155; font-size: 0.9rem;">
                                        {{ $description }}
                                        <!--{!! $taxDisplay !!}-->
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    @if($oldData || $newData)
                                        <button type="button" class="btn-view-changes viewChangesBtn"
                                            data-old='@json($oldData)'
                                            data-new='@json($newData)'
                                            data-action="{{ $log->action }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    @else
                                        <span class="text-muted" style="font-size: 0.75rem;">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pagination-wrapper">
                    <div class="info-text">
                        Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} entries
                    </div>
                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>

            @else
                {{-- Empty State --}}
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-history"></i></div>
                    <h5>No Logs Found</h5>
                    <p>No activities have been recorded for this category yet. Once changes are made, they will appear here.</p>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Changes Modal --}}
<div class="modal fade modal-modern" id="changesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-code-branch" style="color: #6366f1;"></i> Change Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert-summary">
                    <i class="fas fa-info-circle"></i>
                    <span id="changeSummaryText">Showing all changes made in this action</span>
                </div>

                <div class="table-changes mt-3">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                            </tr>
                        </thead>
                        <tbody id="changeTableBody">
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No changes to display</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 0.5rem 1.5rem; font-weight: 600;">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {

        // Filter by action
        $('#actionFilter').on('change', function() {
            filterLogs();
        });

        // Search logs
        $('#searchLogs').on('keyup', function() {
            filterLogs();
        });

        function filterLogs() {
            const action = $('#actionFilter').val();
            const search = $('#searchLogs').val().toLowerCase();

            $('#logsTable tbody tr').each(function() {
                const row = $(this);
                const rowAction = row.data('action') || '';
                const rowSearch = (row.data('search') || '').toLowerCase();

                let show = true;

                if (action && rowAction !== action) {
                    show = false;
                }

                if (search && !rowSearch.includes(search)) {
                    show = false;
                }

                row.toggle(show);
            });
        }

        // Helper function to format tax composition for display
        function formatTaxComposition(taxData) {
            if (!taxData || !Array.isArray(taxData)) return '-';
            return taxData.map(tax => {
                const type = (tax.type || '').toUpperCase();
                const rate = tax.rate || 0;
                return `${type} ${rate}%`;
            }).join(' + ');
        }

        // View changes button click
        $(document).on('click', '.viewChangesBtn', function() {
            const oldData = $(this).data('old') || {};
            const newData = $(this).data('new') || {};
            const action = $(this).data('action') || '';
            let html = '';

            // Update summary
            let summaryText = '';
            if (action === 'create') {
                summaryText = 'New category was created with the following details:';
            } else if (action === 'update') {
                summaryText = 'Category was updated. Changes made to the following fields:';
            } else if (action === 'delete') {
                summaryText = 'Category was deleted. The following data was removed:';
            } else {
                summaryText = 'Changes made to the category:';
            }
            $('#changeSummaryText').text(summaryText);

            const allKeys = [
                ...new Set([
                    ...Object.keys(oldData),
                    ...Object.keys(newData)
                ])
            ];

            // Human-readable field labels
            const fieldLabels = {
                'category_name': 'Category Name',
                'category_code': 'Category Code',
                'configuration_id': 'Configuration',
                'description': 'Description',
                'status': 'Status',
                'icon': 'Icon',
                'icon_color': 'Icon Color',
                'icon_image': 'Icon Image',
                'tax_composition': 'Tax Composition',
                'gst_rate': 'GST Rate',
                'tax_slab_id': 'Tax Slab',
                'hsn_code_id': 'HSN Code',
                'is_hsn_mandatory': 'HSN Mandatory',
                'is_gst_applicable': 'GST Applicable',
                'created_by': 'Created By',
                'updated_by': 'Updated By'
            };

            // Format tax composition for display
            function formatTaxValue(value) {
                if (!value) return '-';
                if (typeof value === 'string' && value.startsWith('{')) {
                    try {
                        const parsed = JSON.parse(value);
                        if (Array.isArray(parsed)) {
                            return parsed.map(t => `${(t.type || '').toUpperCase()} ${t.rate || 0}%`).join(' + ');
                        }
                    } catch(e) {}
                }
                if (Array.isArray(value)) {
                    return value.map(t => `${(t.type || '').toUpperCase()} ${t.rate || 0}%`).join(' + ');
                }
                if (typeof value === 'object') {
                    try {
                        return JSON.stringify(value);
                    } catch(e) {
                        return '-';
                    }
                }
                return value;
            }

            const sortedKeys = allKeys.sort();
            let hasChanges = false;

            sortedKeys.forEach(function(key) {
                // Skip internal/system fields
                if (['id', 'institute_id', 'created_at', 'updated_at', 'deleted_at', 'deleted_by'].includes(key)) {
                    return;
                }

                let oldValue = oldData[key] ?? '-';
                let newValue = newData[key] ?? '-';
                
                // Special handling for tax_composition
                if (key === 'tax_composition') {
                    oldValue = formatTaxValue(oldValue);
                    newValue = formatTaxValue(newValue);
                } else {
                    // Format boolean values
                    if (typeof oldValue === 'boolean') {
                        oldValue = oldValue ? '✅ Enabled' : '❌ Disabled';
                    }
                    if (typeof newValue === 'boolean') {
                        newValue = newValue ? '✅ Enabled' : '❌ Disabled';
                    }
                    // Format null/empty
                    if (oldValue === null || oldValue === 'null' || oldValue === '') {
                        oldValue = '<span class="empty-value">(Not set)</span>';
                    }
                    if (newValue === null || newValue === 'null' || newValue === '') {
                        newValue = '<span class="empty-value">(Not set)</span>';
                    }
                    // Format other objects/arrays
                    if (typeof oldValue === 'object' && oldValue !== null) {
                        oldValue = JSON.stringify(oldValue);
                    }
                    if (typeof newValue === 'object' && newValue !== null) {
                        newValue = JSON.stringify(newValue);
                    }
                }

                const isChanged = (oldData[key] ?? '-') !== (newData[key] ?? '-');

                // For update actions, only show changed fields
                if (action === 'update' && !isChanged) {
                    return;
                }

                hasChanges = true;
                const fieldName = fieldLabels[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                // Wrap with styling if changed (for update)
                let oldHtml = oldValue;
                let newHtml = newValue;
                if (isChanged && action === 'update') {
                    oldHtml = `<span class="old-value">${oldValue}</span>`;
                    newHtml = `<span class="new-value">${newValue}</span>`;
                }

                html += `
                    <tr>
                        <td class="field-name">${fieldName}</td>
                        <td>${oldHtml}</td>
                        <td>${newHtml}</td>
                    </tr>
                `;
            });

            if (!hasChanges) {
                html = `
                    <tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle me-1"></i> No significant changes detected
                        </td>
                    </tr>
                `;
            }

            $('#changeTableBody').html(html);
            $('#changesModal').modal('show');
        });

    });
</script>

@endsection