@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Transport Routes</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        padding: 24px 32px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        border: 1px solid #e8ecf1;
    }
    
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-title i {
        color: #fff;
        font-size: 1.6rem;
    }

    .btn-add {
        padding: 12px 24px;
        background: var(--primary-gradient);
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        text-decoration: none;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        color: white;
        text-decoration: none;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .stat-card {
        background: white;
        border: 1px solid #e8ecf1;
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.3s ease;
        box-shadow: var(--card-shadow);
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--hover-shadow);
        border-color: var(--primary-color);
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .stat-icon.total {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #2563eb;
    }
    
    .stat-icon.active {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        color: #059669;
    }
    
    .stat-icon.inactive {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #dc2626;
    }
    
    .stat-icon.buses {
        background: linear-gradient(135deg, #fefce8, #fef08a);
        color: #ca8a04;
    }
    
    .stat-content h3 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }
    
    .stat-content p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    /* Filter container */
    .filter-container {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        border: 1px solid #e8ecf1;
        box-shadow: var(--card-shadow);
    }

    .filter-form {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        flex-wrap: wrap;
    }

    .filter-grid {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-group .bi {
        position: absolute;
        left: 14px;
        top: 38px;
        color: #94a3b8;
        z-index: 1;
        font-size: 1rem;
    }
    
    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border: 2px solid #e8ecf1;
        border-radius: 12px;
        font-size: 0.9rem;
        background: #f8fafc;
        transition: all 0.3s ease;
        height: 48px;
        box-sizing: border-box;
        color: #1e293b;
    }
    
    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        background: white;
    }
    
    .filter-group input:hover,
    .filter-group select:hover {
        border-color: #cbd5e1;
        background: white;
    }
    
    .filter-group input::placeholder {
        color: #94a3b8;
    }
    
    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: flex-end;
    }
    
    .btn-filter {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        height: 48px;
        box-sizing: border-box;
        text-decoration: none;
        white-space: nowrap;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }
    
    .btn-filter-primary {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }
    
    .btn-filter-primary:hover {
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        color: white;
    }
    
    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border-color: #e8ecf1;
    }
    
    .btn-filter-secondary:hover {
        background: #e2e8f0;
        color: #475569;
    }

    /* Table Container */
    .table-container {
        background: white;
        border-radius: 16px;
        /*overflow: hidden;*/
        box-shadow: var(--card-shadow);
        border: 1px solid #e8ecf1;
        margin-bottom: 24px;
    }

    /* ERP Table */
    .erp-table {
        width: 100%;
        background: #fff;
        border-collapse: collapse;
    }

    .erp-table thead {
        background: var(--primary-gradient);
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .erp-table th {
        padding: 16px 18px;
        font-weight: 600;
        color: white;
        text-align: left;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s;
        position: relative;
        border-bottom: none;
    }
    
    .erp-table th.sortable {
        padding-right: 35px;
    }
    
    .sort-icons {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    
    .sort-icon {
        color: rgba(255, 255, 255, 0.5);
        font-size: 11px;
        line-height: 1;
        transition: color 0.2s;
    }
    
    .sort-icon.active {
        color: #ffffff;
    }
    
    .erp-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.9rem;
        vertical-align: middle;
    }
    
    .erp-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .erp-table tbody tr:hover {
        background-color: #f8f7ff;
        transform: translateX(2px);
    }
    
    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Route Name */
    .route-name {
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .route-name i {
        color: var(--primary-color);
    }

    /* Route Badge */
    .route-badge {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #1e40af;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #93c5fd;
    }

    /* Buses Badge */
    .buses-badge {
        background: linear-gradient(135deg, #fefce8, #fef08a);
        color: #854d0e;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #fde047;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .status-active {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #64748b;
        border: 1px solid #cbd5e1;
    }

    /* Date Display */
    .date-display {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
    }

    .date-display i {
        color: var(--primary-color);
    }

    .date-time {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    
    .action-btn {
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        letter-spacing: 0.3px;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .action-btn-view {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #1e40af;
        border-color: #93c5fd;
    }
    
    .action-btn-edit {
        background: linear-gradient(135deg, #fefce8, #fef08a);
        color: #854d0e;
        border-color: #fde047;
    }
    
    .action-btn-delete {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        color: #991b1b;
        border-color: #fca5a5;
    }
    
    .action-btn-view:hover {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
    }
    
    .action-btn-edit:hover {
        background: linear-gradient(135deg, #fef08a, #fde047);
        color: #854d0e;
    }
    
    .action-btn-delete:hover {
        background: linear-gradient(135deg, #fecaca, #fca5a5);
        color: #991b1b;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: 1px solid #e8ecf1;
    }
    
    .empty-state-icon {
        font-size: 64px;
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 20px;
    }
    
    .empty-state h4 {
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        color: #64748b;
        margin-bottom: 24px;
    }

    /* Pagination Container */
    .pagination-container {
        background: white;
        padding: 20px 24px;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        border: 1px solid #e8ecf1;
    }
    
    .pagination-info {
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Loading Spinner */
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Animations */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .filter-container,
    .stat-card,
    .table-container {
        animation: slideUp 0.3s ease;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .filter-form {
            flex-direction: column;
            gap: 16px;
        }
        
        .filter-grid {
            width: 100%;
        }
        
        .filter-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
            padding: 20px;
        }
        
        .page-title {
            justify-content: center;
        }
        
        .btn-add {
            justify-content: center;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .filter-actions {
            flex-wrap: wrap;
        }
        
        .btn-filter {
            flex: 1;
            justify-content: center;
        }
        
        .action-group {
            flex-direction: column;
            gap: 6px;
        }
        
        .action-btn {
            width: 100%;
            justify-content: center;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .erp-table th,
        .erp-table td {
            padding: 12px 14px;
            font-size: 0.85rem;
        }
    }
    
    .table-responsive{
        overflow-x: hidden;
    }
    
    .erp-table thead .sticky-main,
    .erp-table tbody .sticky-main{
        left: 28px;
    }
</style>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-signpost-split"></i>
            Transport Routes
        </h1>
        <a href="{{ route('admin.transport.routes.create') }}" class="btn-add">
            <i class="bi bi-plus-circle"></i>
            Add New Route
        </a>
    </div>

    <!-- Stats Cards -->
    @php
        $totalRoutes = $routes->total();
        $activeRoutes = $routes->where('is_active', true)->count();
        $inactiveRoutes = $totalRoutes - $activeRoutes;
        $totalBusesAssigned = $routes->sum('buses_count');
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="bi bi-signpost-split"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalRoutes }}</h3>
                <p>Total Routes</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $activeRoutes }}</h3>
                <p>Active Routes</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon inactive">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $inactiveRoutes }}</h3>
                <p>Inactive Routes</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon buses">
                <i class="bi bi-bus-front"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalBusesAssigned }}</h3>
                <p>Buses Assigned</p>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="filter-container">
        <form method="GET" action="{{ route('admin.transport.routes.index') }}" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label>Route Name</label>
                    <i class="bi bi-search"></i>
                    <input type="text" name="route_name" placeholder="Search route name..." value="{{ request('route_name') }}">
                </div>
                <div class="filter-group">
                    <label>Reference ID</label>
                    <i class="bi bi-qr-code"></i>
                    <input type="text" name="route_reference_id" placeholder="Search reference ID..." value="{{ request('route_reference_id') }}">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <i class="bi bi-toggle-on"></i>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel"></i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.transport.routes.index') }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if($routes->count() > 0)
    <div class="table-container">
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th class="sticky-checkbox" width="50">#</th>
                        <th class="sticky-main sortable">Route Name</th>
                        <th class="sortable">Description</th>
                        <th class="sortable">Reference ID</th>
                        <th class="sortable">Buses</th>
                        <th class="sortable">Status</th>
                        <th class="sortable">Created</th>
                        <th class="text-center" width="180">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($routes as $index => $route)
                    <tr>
                        <td class="sticky-checkbox">
                            <span class="fw-semibold">{{ $routes->firstItem() + $index }}</span>
                        </td>
                        <td class="sticky-main">
                            <div class="route-name">
                                <i class="bi bi-signpost"></i>
                                {{ $route->route_name }}
                            </div>
                        </td>
                        <td>
                            @if($route->description)
                                <span class="text-muted" style="max-width: 200px; display: inline-block;">
                                    {{ Str::limit($route->description, 60) }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">No description</span>
                            @endif
                        </td>
                        <td>
                            <span class="route-badge">
                                <i class="bi bi-qr-code"></i> {{ $route->route_reference_id }}
                            </span>
                        </td>
                        <td>
                            <span class="buses-badge">
                                <i class="bi bi-bus-front"></i> {{ $route->buses_count }} Bus(es)
                            </span>
                        </td>
                        <td>
                            <span class="status-badge {{ $route->is_active ? 'status-active' : 'status-inactive' }}">
                                <i class="bi {{ $route->is_active ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                                {{ $route->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="date-display">
                                <i class="bi bi-calendar3"></i>
                                <div>
                                    <div>{{ $route->created_at->format('d M Y') }}</div>
                                    <div class="date-time">{{ $route->created_at->format('h:i A') }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('admin.transport.routes.show', $route->id) }}" 
                                   class="action-btn action-btn-view"
                                   title="View Route Details">
                                    <i class="bi bi-eye"></i>
                                    <span>View</span>
                                </a>
                                <a href="{{ route('admin.transport.routes.edit', $route->id) }}" 
                                   class="action-btn action-btn-edit"
                                   title="Edit Route">
                                    <i class="bi bi-pencil"></i>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('admin.transport.routes.destroy', $route->id) }}" 
                                      method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" 
                                            title="Delete Route">
                                        <i class="bi bi-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    </div>
    
    <!-- Pagination -->
    @if($routes->hasPages())
    <div class="pagination-container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagination-info">
                Showing {{ $routes->firstItem() ?? 0 }} to {{ $routes->lastItem() ?? 0 }} of {{ $routes->total() }} routes
            </div>
            <div>
                {{ $routes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="bi bi-signpost-split"></i>
        </div>
        <h4>No Routes Found</h4>
        <p>
            @if(count(array_filter(request()->all())) > 0)
            No routes match your current filters. Try adjusting your search criteria.
            @else
            No routes have been created yet. Get started by adding your first route.
            @endif
        </p>
        <a href="{{ route('admin.transport.routes.create') }}" class="btn-add" style="display: inline-flex;">
            <i class="bi bi-plus-circle"></i> Add New Route
        </a>
    </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Handle filter form submission with loading state
$('.filter-form').on('submit', function(e) {
    const submitBtn = $(this).find('.btn-filter-primary');
    const originalHTML = submitBtn.html();
    submitBtn.html('<span class="loading-spinner"></span> Applying...');
    submitBtn.prop('disabled', true);
    
    // Allow form to submit normally
    return true;
});

// Add confirmation for delete with bus count check
$('.delete-form').on('submit', function(e) {
    const busesCount = $(this).closest('tr').find('.buses-badge').text().trim();
    const busNumber = parseInt(busesCount.match(/\d+/)?.[0] || 0);
    
    if (busNumber > 0) {
        if (!confirm('Warning: This route has ' + busNumber + ' bus(es) assigned. Deleting it may affect those buses. Are you sure you want to proceed?')) {
            e.preventDefault();
        }
    } else {
        if (!confirm('Are you sure you want to delete this route? This action cannot be undone.')) {
            e.preventDefault();
        }
    }
});
</script>
@endsection