@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-header {
        background: var(--primary-gradient);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h1 {
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.5rem;
    }

    .page-header h1 i {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.3rem;
    }

    .page-header p {
        opacity: 0.9;
        margin: 0;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        color: white;
    }

    .btn-export {
        background: white;
        color: #4361ee;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
        color: #4361ee;
    }

    .info-card {
        background: white;
        border-radius: 20px;
        border: 2px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
    }

    .info-card .card-body {
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .info-card .warehouse-info h5 {
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card .warehouse-info .code {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .info-card .warehouse-status {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .badge-status {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-active {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-default {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-logs-count {
        background: #e0e7ff;
        color: #4338ca;
        padding: 0.2rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .card-custom {
        background: white;
        border-radius: 20px;
        border: 2px solid var(--border-color);
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .card-custom .card-header {
        background: #f8fafc;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid var(--border-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .card-custom .card-header i {
        color: #4361ee;
        margin-right: 8px;
    }

    .card-custom .card-body {
        padding: 1.5rem;
    }

    .table-responsive-custom {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
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
        padding: 0.75rem 1rem;
        border-bottom: 2px solid var(--border-color);
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .table-custom tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        background-color: white;
    }

    .table-custom tbody tr:last-child td {
        border-bottom: none;
    }

    .table-custom tbody tr {
        transition: var(--transition);
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-custom .log-date {
        font-weight: 500;
        color: var(--text-dark);
    }

    .table-custom .log-date small {
        font-weight: 400;
        color: var(--text-muted);
    }

    .table-custom .user-name {
        font-weight: 500;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .table-custom .user-name .user-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e0e7ff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        font-weight: 700;
        color: #4338ca;
    }

    .action-badge {
        display: inline-block;
        padding: 0.25rem 0.7rem;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .action-badge.action-create {
        background: #d1fae5;
        color: #065f46;
    }

    .action-badge.action-update {
        background: #fef3c7;
        color: #92400e;
    }

    .action-badge.action-delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-badge.action-status-toggle {
        background: #e0e7ff;
        color: #4338ca;
    }

    .action-badge.action-other {
        background: #f1f5f9;
        color: #475569;
    }

    .log-details {
        font-size: 0.85rem;
        color: var(--text-dark);
    }

    .log-details .highlight {
        font-weight: 600;
        color: #4361ee;
    }

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
        color: var(--text-dark);
        font-weight: 600;
    }

    .empty-state p {
        color: var(--text-muted);
        max-width: 400px;
        margin: 0 auto;
    }

    .log-filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .log-filter-bar .filter-input {
        flex: 1;
        min-width: 200px;
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.85rem;
        background: white;
        transition: var(--transition);
    }

    .log-filter-bar .filter-input:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .log-filter-bar .filter-select {
        padding: 0.5rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.85rem;
        background: white;
        min-width: 150px;
        transition: var(--transition);
    }

    .log-filter-bar .filter-select:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .log-filter-bar .btn-reset {
        padding: 0.4rem 1.2rem;
        background: #f1f5f9;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
    }

    .log-filter-bar .btn-reset:hover {
        background: #e2e8f0;
        color: var(--text-dark);
    }

    .pagination-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 2px solid var(--border-color);
    }

    .pagination-custom .pagination-info {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .pagination-custom .pagination-info strong {
        color: var(--text-dark);
    }

    /* Custom Pagination Styles */
    .pagination-custom .pagination {
        display: flex;
        gap: 4px;
        list-style: none;
        padding: 0;
        margin: 0;
        align-items: center;
        flex-wrap: wrap;
    }

    .pagination-custom .pagination li {
        display: inline-block;
    }

    .pagination-custom .pagination li a,
    .pagination-custom .pagination li span {
        display: inline-block;
        padding: 0.5rem 0.85rem;
        border-radius: 8px;
        background: white;
        border: 2px solid var(--border-color);
        color: var(--text-dark);
        font-weight: 600;
        font-size: 0.85rem;
        transition: var(--transition);
        text-decoration: none;
        min-width: 40px;
        text-align: center;
    }

    .pagination-custom .pagination li a:hover {
        background: #f1f5f9;
        border-color: #4361ee;
        color: #4361ee;
        transform: translateY(-2px);
    }

    .pagination-custom .pagination li.active span {
        background: var(--primary-gradient);
        border-color: #4361ee;
        color: white;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .pagination-custom .pagination li.disabled span {
        opacity: 0.4;
        cursor: not-allowed;
        background: #f8fafc;
    }

    .pagination-custom .pagination li:first-child a,
    .pagination-custom .pagination li:last-child a,
    .pagination-custom .pagination li:first-child span,
    .pagination-custom .pagination li:last-child span {
        padding: 0.5rem 1.2rem;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .info-card .card-body {
            flex-direction: column;
            text-align: center;
        }

        .info-card .warehouse-status {
            justify-content: center;
        }

        .log-filter-bar {
            flex-direction: column;
        }

        .log-filter-bar .filter-input,
        .log-filter-bar .filter-select {
            min-width: 100%;
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }

        .pagination-custom {
            flex-direction: column;
            text-align: center;
        }

        .pagination-custom .pagination {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .card-custom .card-body {
            padding: 0.75rem;
        }

        .table-custom thead th,
        .table-custom tbody td {
            padding: 0.4rem 0.5rem;
            font-size: 0.65rem;
        }

        .action-badge {
            font-size: 0.55rem;
            padding: 0.15rem 0.5rem;
        }

        .pagination-custom .pagination li a,
        .pagination-custom .pagination li span {
            padding: 0.35rem 0.6rem;
            font-size: 0.75rem;
            min-width: 32px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-history"></i> Activity Logs</h1>
            <p>Track all activities for {{ $warehouse->warehouse_name }}</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('inventory.warehouses.view', $warehouse->id) }}" class="btn-export">
                <i class="fas fa-eye"></i> View Warehouse
            </a>
            <a href="{{ route('inventory.warehouses.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Warehouse Info Card -->
    <div class="info-card">
        <div class="card-body">
            <div class="warehouse-info">
                <h5>
                    <i class="fas fa-warehouse" style="color: #4361ee;"></i>
                    {{ $warehouse->warehouse_name }}
                    <span class="code">({{ $warehouse->warehouse_code }})</span>
                </h5>
                <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">
                    <i class="fas fa-map-marker-alt"></i> 
                    {{ $warehouse->city ? $warehouse->city . ', ' : '' }}
                    {{ $warehouse->state ? $warehouse->state : 'Location not set' }}
                </div>
            </div>
            <div class="warehouse-status">
                <span class="badge-status {{ $warehouse->status ? 'badge-active' : 'badge-inactive' }}">
                    <i class="fas fa-circle" style="font-size: 0.4rem;"></i>
                    {{ $warehouse->status ? 'Active' : 'Inactive' }}
                </span>
                @if($warehouse->is_default)
                    <span class="badge-default badge-status">
                        <i class="fas fa-check"></i> Default
                    </span>
                @endif
                <span class="badge-logs-count">
                    <i class="fas fa-file-alt"></i> {{ $logs->total() }} Logs
                </span>
            </div>
        </div>
    </div>

    <!-- Log Filter Bar -->
    <div class="log-filter-bar">
        <input type="text" id="searchLogs" class="filter-input" placeholder="🔍 Search logs by user or description...">
        <select id="filterAction" class="filter-select">
            <option value="">All Actions</option>
            <option value="CREATE">Created</option>
            <option value="UPDATE">Updated</option>
            <option value="DELETE">Deleted</option>
            <option value="status_toggle">Status Changed</option>
        </select>
        <select id="filterDate" class="filter-select">
            <option value="">All Time</option>
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
        </select>
        <button onclick="resetLogFilters()" class="btn-reset">
            <i class="fas fa-undo"></i> Reset
        </button>
    </div>

    <!-- Logs Table -->
    <div class="card-custom">
        <div class="card-header">
            <span>
                <i class="fas fa-list"></i> Activity Logs
                <span class="badge-logs-count ms-2">{{ $logs->total() }}</span>
            </span>
            <span style="font-size: 0.8rem; color: var(--text-muted);">
                <i class="fas fa-clock"></i> Latest activity: 
                @if($logs->isNotEmpty())
                    {{ $logs->first()->created_at->diffForHumans() }}
                @else
                    No activity yet
                @endif
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive-custom">
                <table class="table-custom" id="logsTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="min-width: 160px;">Date & Time</th>
                            <th style="min-width: 150px;">User</th>
                            <th style="min-width: 130px;">Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr data-action="{{ $log->action }}" data-date="{{ $log->created_at->format('Y-m-d') }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="log-date">
                                    {{ $log->created_at->format('d M Y, h:i A') }}
                                    <br>
                                    <small>{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="user-name">
                                    <span class="user-avatar">
                                        {{ $log->user ? substr($log->user->name, 0, 2) : 'SY' }}
                                    </span>
                                    {{ $log->user->name ?? 'System' }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $actionClass = 'action-other';
                                    $actionIcon = 'fa-circle';
                                    if($log->action == 'CREATE') {
                                        $actionClass = 'action-create';
                                        $actionIcon = 'fa-plus';
                                    } elseif($log->action == 'UPDATE') {
                                        $actionClass = 'action-update';
                                        $actionIcon = 'fa-pen';
                                    } elseif($log->action == 'DELETE') {
                                        $actionClass = 'action-delete';
                                        $actionIcon = 'fa-trash';
                                    } elseif($log->action == 'status_toggle') {
                                        $actionClass = 'action-status-toggle';
                                        $actionIcon = 'fa-exchange-alt';
                                    }
                                @endphp
                                <span class="action-badge {{ $actionClass }}">
                                    <i class="fas {{ $actionIcon }}"></i>
                                    @if($log->action == 'CREATE')
                                        Created
                                    @elseif($log->action == 'UPDATE')
                                        Updated
                                    @elseif($log->action == 'DELETE')
                                        Deleted
                                    @elseif($log->action == 'status_toggle')
                                        Status Changed
                                    @else
                                        {{ ucfirst($log->action) }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="log-details">
                                    {{ $log->remarks ?? $log->action . ' warehouse' }}
                                    @if($log->action == 'status_toggle')
                                        <span class="highlight">
                                            ({{ isset($log->old_data['status']) && $log->old_data['status'] ? 'Active' : 'Inactive' }} 
                                            → 
                                            {{ isset($log->new_data['status']) && $log->new_data['status'] ? 'Active' : 'Inactive' }})
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-history"></i></div>
                                    <h5>No Logs Found</h5>
                                    <p>There are no activity logs for this warehouse yet. Logs will appear when actions are performed.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ============================================ -->
            <!-- SINGLE PAGINATION SECTION - FIXED            -->
            <!-- ============================================ -->
            <div class="pagination-custom">
                <div class="pagination-info">
                    Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} entries
                </div>
                <div>
                    @if ($logs->hasPages())
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($logs->onFirstPage())
                                <li class="disabled"><span>« Previous</span></li>
                            @else
                                <li><a href="{{ $logs->previousPageUrl() }}">« Previous</a></li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                                @if ($page == $logs->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($logs->hasMorePages())
                                <li><a href="{{ $logs->nextPageUrl() }}">Next »</a></li>
                            @else
                                <li class="disabled"><span>Next »</span></li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
            <!-- ============================================ -->
            <!-- END PAGINATION SECTION                       -->
            <!-- ============================================ -->
        </div>
    </div>
</div>

<script>
    // ============================================
    // SEARCH & FILTER
    // ============================================

    document.getElementById('searchLogs').addEventListener('keyup', filterLogs);
    document.getElementById('filterAction').addEventListener('change', filterLogs);
    document.getElementById('filterDate').addEventListener('change', filterLogs);

    function filterLogs() {
        const search = document.getElementById('searchLogs').value.toLowerCase();
        const action = document.getElementById('filterAction').value;
        const date = document.getElementById('filterDate').value;

        const rows = document.querySelectorAll('#logsTable tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const user = row.querySelector('.user-name')?.textContent?.toLowerCase() || '';
            const description = row.querySelector('.log-details')?.textContent?.toLowerCase() || '';
            const rowAction = row.dataset.action || '';
            const rowDate = row.dataset.date || '';

            let show = true;

            // Search filter
            if (search && !user.includes(search) && !description.includes(search)) {
                show = false;
            }

            // Action filter
            if (action && rowAction !== action) {
                show = false;
            }

            // Date filter
            if (date) {
                const today = new Date();
                const logDate = new Date(rowDate);
                let matchDate = false;

                switch(date) {
                    case 'today':
                        matchDate = logDate.toDateString() === today.toDateString();
                        break;
                    case 'week':
                        const weekAgo = new Date(today);
                        weekAgo.setDate(today.getDate() - 7);
                        matchDate = logDate >= weekAgo;
                        break;
                    case 'month':
                        matchDate = logDate.getMonth() === today.getMonth() && logDate.getFullYear() === today.getFullYear();
                        break;
                    case 'year':
                        matchDate = logDate.getFullYear() === today.getFullYear();
                        break;
                    default:
                        matchDate = true;
                }

                if (!matchDate) {
                    show = false;
                }
            }

            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Update visible count if needed
        const countBadge = document.querySelector('.badge-logs-count');
        if (countBadge) {
            const total = document.querySelectorAll('#logsTable tbody tr').length;
            if (visibleCount !== total) {
                countBadge.textContent = visibleCount + ' shown';
            } else {
                countBadge.textContent = total + ' Logs';
            }
        }
    }

    function resetLogFilters() {
        document.getElementById('searchLogs').value = '';
        document.getElementById('filterAction').value = '';
        document.getElementById('filterDate').value = '';
        filterLogs();
    }

    // ============================================
    // INITIALIZE
    // ============================================

    document.addEventListener('DOMContentLoaded', function() {
        // Update the count badge if filtering is active
        const countBadge = document.querySelector('.badge-logs-count');
        if (countBadge) {
            const total = document.querySelectorAll('#logsTable tbody tr').length;
            countBadge.textContent = total + ' Logs';
        }
    });
</script>

@endsection