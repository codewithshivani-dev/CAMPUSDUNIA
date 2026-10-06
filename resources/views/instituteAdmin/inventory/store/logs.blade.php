{{-- resources/views/instituteAdmin/Inventory/store/logs.blade.php --}}

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

    .info-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 2px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .info-card .card-title {
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card .card-title i {
        color: #4361ee;
    }

    .log-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        transition: var(--transition);
        align-items: flex-start;
    }

    .log-item:hover {
        background: #f8fafc;
    }

    .log-item:last-child {
        border-bottom: none;
    }

    .log-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .log-icon.create {
        background: #d1fae5;
        color: #065f46;
    }

    .log-icon.update {
        background: #fef3c7;
        color: #92400e;
    }

    .log-icon.delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .log-icon.status_toggle {
        background: #dbeafe;
        color: #1e40af;
    }

    .log-content {
        flex: 1;
    }

    .log-content .log-action {
        font-weight: 600;
        color: var(--text-dark);
    }

    .log-content .log-remarks {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .log-content .log-meta {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .log-content .log-meta i {
        margin-right: 4px;
    }

    .log-details {
        margin-top: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f1f5f9;
        border-radius: 8px;
        font-size: 0.85rem;
        display: none;
    }

    .log-details.show {
        display: block;
    }

    .log-details pre {
        margin: 0;
        white-space: pre-wrap;
        word-break: break-all;
        font-size: 0.8rem;
    }

    .btn-toggle-details {
        background: none;
        border: none;
        color: #4361ee;
        font-size: 0.8rem;
        cursor: pointer;
        padding: 0;
        text-decoration: underline;
    }

    .btn-toggle-details:hover {
        color: #3a0ca3;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        color: var(--text-dark);
        font-weight: 600;
    }

    .empty-state p {
        color: var(--text-muted);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            text-align: center;
        }

        .log-item {
            flex-direction: column;
            align-items: stretch;
        }

        .log-content .log-meta {
            flex-wrap: wrap;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-history"></i>Store Activity Logs</h1>
            <p>{{ $store->store_name }} ({{ $store->store_code }})</p>
        </div>
        <div>
            <a href="{{ route('inventory.stores.view', $store->id) }}" class="btn-back">
                <i class="fas fa-eye"></i> View Store
            </a>
            <a href="{{ route('inventory.stores.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Store Info -->
    <div class="info-card">
        <div class="row">
            <div class="col-md-3">
                <strong>Store Name:</strong> {{ $store->store_name }}
            </div>
            <div class="col-md-3">
                <strong>Store Code:</strong> {{ $store->store_code }}
            </div>
            <div class="col-md-3">
                <strong>Warehouse:</strong> {{ $store->warehouse->warehouse_name ?? 'N/A' }}
            </div>
            <div class="col-md-3">
                <strong>Status:</strong>
                <span class="badge {{ $store->status ? 'bg-success' : 'bg-danger' }}">
                    {{ $store->status ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Logs -->
    <div class="info-card">
        <div class="card-title">
            <i class="fas fa-list"></i> Activity Logs
            <span class="badge bg-primary ms-2">{{ $logs->total() }}</span>
        </div>

        @if($logs->count() > 0)
            @foreach($logs as $log)
                <div class="log-item">
                    <div class="log-icon {{ $log->action }}">
                        @if($log->action == 'CREATE')
                            <i class="fas fa-plus"></i>
                        @elseif($log->action == 'UPDATE' || $log->action == 'status_toggle')
                            <i class="fas fa-pen"></i>
                        @elseif($log->action == 'DELETE')
                            <i class="fas fa-trash"></i>
                        @else
                            <i class="fas fa-circle"></i>
                        @endif
                    </div>
                    <div class="log-content">
                        <div class="log-action">
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                            <span class="badge bg-secondary ms-2">{{ ucfirst($log->module) }}</span>
                        </div>
                        @if($log->remarks)
                            <div class="log-remarks">{{ $log->remarks }}</div>
                        @endif
                        <div class="log-meta">
                            <span>
                                <i class="fas fa-user"></i>
                                {{ $log->user ? $log->user->name : 'System' }}
                            </span>
                            <span>
                                <i class="fas fa-clock"></i>
                                {{ $log->created_at ? $log->created_at->format('d M Y, h:i A') : 'N/A' }}
                            </span>
                            @if($log->old_data || $log->new_data)
                                <button class="btn-toggle-details" onclick="toggleDetails({{ $log->id }})">
                                    <i class="fas fa-chevron-down"></i> View Details
                                </button>
                            @endif
                        </div>
                        @if($log->old_data || $log->new_data)
                            <div class="log-details" id="logDetails{{ $log->id }}">
                                @if($log->old_data)
                                    <div class="mb-2">
                                        <strong>Old Data:</strong>
                                        <pre>{{ json_encode(json_decode($log->old_data), JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @endif
                                @if($log->new_data)
                                    <div>
                                        <strong>New Data:</strong>
                                        <pre>{{ json_encode(json_decode($log->new_data), JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} logs
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-history"></i>
                <h4>No Logs Found</h4>
                <p>No activity logs found for this store.</p>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleDetails(id) {
        const details = document.getElementById('logDetails' + id);
        const btn = details.previousElementSibling.querySelector('.btn-toggle-details');
        
        if (details.classList.contains('show')) {
            details.classList.remove('show');
            btn.innerHTML = '<i class="fas fa-chevron-down"></i> View Details';
        } else {
            details.classList.add('show');
            btn.innerHTML = '<i class="fas fa-chevron-up"></i> Hide Details';
        }
    }
</script>

@endsection