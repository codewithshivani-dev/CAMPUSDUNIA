@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Route Details - {{ $route->route_name }}</title>
<style>
    /* ERP Table Styles */
    .erp-table {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }
    
    .erp-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .erp-table th {
        padding: 12px 16px;
        font-weight: 600;
        color: #475569;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .erp-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }
    
    .erp-table tbody tr {
        transition: background-color 0.2s, transform 0.2s;
    }
    
    .erp-table tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
        animation: fadeIn 0.5s ease;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-title i {
        color: #3b82f6;
    }
    
    /* Action Buttons */
    .action-btn {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        margin: 0 2px;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-decoration: none;
    }
    
    .action-btn-primary {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    
    .action-btn-primary:hover {
        background: #2563eb;
        color: white;
    }
    
    .action-btn-edit {
        background: #fef2c8;
        color: #92400e;
        border-color: #fde68a;
    }
    
    .action-btn-edit:hover {
        background: #fde68a;
    }
    
    .action-btn-view {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }
    
    .action-btn-view:hover {
        background: #bae6fd;
    }
    
    .action-btn-back {
        background: #f1f5f9;
        color: #475569;
        border-color: #e2e8f0;
    }
    
    .action-btn-back:hover {
        background: #e2e8f0;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
        transition: transform 0.2s;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .status-active {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .status-inactive {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    
    /* Info Cards */
    .info-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .info-card-title {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .info-card-title i {
        color: #3b82f6;
    }
    
    .info-card-value {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    
    .info-card-sub {
        color: #94a3b8;
        font-size: 13px;
        margin-top: 4px;
    }
    
    /* Stats Cards */
    .stats-cards {
        display: flex;
        gap: 20px;
        margin: 24px 0;
        flex-wrap: wrap;
    }
    
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px 20px;
        flex: 1;
        min-width: 200px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: #f0f9ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b82f6;
        font-size: 24px;
    }
    
    .stat-content h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        color: #1e293b;
    }
    
    .stat-content p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }
    
    /* Section Header */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 30px 0 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .section-title i {
        color: #3b82f6;
    }
    
    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-success {
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
    }
    
    .alert-info {
        background: #e0f2fe;
        border: 1px solid #bae6fd;
        color: #0369a1;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    
    .empty-state-icon {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .stats-cards {
            flex-direction: column;
        }
        
        .info-card {
            margin-bottom: 16px;
        }
        
        .erp-table th,
        .erp-table td {
            padding: 8px 12px;
            font-size: 13px;
        }
    }
</style>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-signpost-split"></i>
            Route Details: {{ $route->route_name }}
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.transport.routes.edit', $route->id) }}" class="action-btn action-btn-edit">
                <i class="bi bi-pencil"></i>
                Edit Route
            </a>
            <a href="{{ route('admin.transport.routes.index') }}" class="action-btn action-btn-back">
                <i class="bi bi-arrow-left"></i>
                Back to Routes
            </a>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <!-- Route Information Cards -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="info-card">
                <div class="info-card-title">
                    <i class="bi bi-signpost"></i>
                    Route Name
                </div>
                <div class="info-card-value">{{ $route->route_name }}</div>
                <div class="info-card-sub">Route identifier</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <div class="info-card-title">
                    <i class="bi bi-qr-code"></i>
                    Reference ID
                </div>
                <div class="info-card-value">
                    <span class="badge bg-light text-dark fs-6 px-3 py-2">
                        {{ $route->route_reference_id }}
                    </span>
                </div>
                <div class="info-card-sub">Unique route identifier</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <div class="info-card-title">
                    <i class="bi bi-toggle-on"></i>
                    Status
                </div>
                <div class="info-card-value">
                    <span class="status-badge {{ $route->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $route->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="info-card-sub">Current route status</div>
            </div>
        </div>
    </div>

    <!-- Description Section -->
    @if($route->description)
    <div class="row mt-4">
        <div class="col-12">
            <div class="info-card">
                <div class="info-card-title">
                    <i class="bi bi-info-circle"></i>
                    Description
                </div>
                <div class="info-card-value" style="font-size: 15px; font-weight: normal;">
                    {{ $route->description }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Timeline Information -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="info-card">
                <div class="info-card-title">
                    <i class="bi bi-calendar-plus"></i>
                    Created
                </div>
                <div class="info-card-value">{{ $route->created_at->format('d M Y') }}</div>
                <div class="info-card-sub">{{ $route->created_at->format('h:i A') }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-card">
                <div class="info-card-title">
                    <i class="bi bi-calendar-check"></i>
                    Last Updated
                </div>
                <div class="info-card-value">{{ $route->updated_at->format('d M Y') }}</div>
                <div class="info-card-sub">{{ $route->updated_at->format('h:i A') }}</div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    @php
        $totalBuses = $route->buses->count();
        $activeBuses = $route->buses->where('status', 'active')->count();
        $inactiveBuses = $totalBuses - $activeBuses;
        $totalCapacity = $route->buses->sum('sitting_capacity');
    @endphp

    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-bus-front"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalBuses }}</h3>
                <p>Total Buses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $activeBuses }}</h3>
                <p>Active Buses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $inactiveBuses }}</h3>
                <p>Inactive Buses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
                <h3>{{ $totalCapacity }}</h3>
                <p>Total Capacity</p>
            </div>
        </div>
    </div>

    <!-- Buses Section -->
    <div class="section-header">
        <h5 class="section-title">
            <i class="bi bi-bus-front"></i>
            Buses on this Route
        </h5>
        <a href="{{ route('admin.transport.add') }}?route_id={{ $route->id }}" class="action-btn action-btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add New Bus
        </a>
    </div>

    @if($route->buses->count() > 0)
    <div class="table-responsive">
        <table class="erp-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Bus Number</th>
                    <th>Vehicle Number</th>
                    <th>Driver Name</th>
                    <th>Driver Contact</th>
                    <th>Capacity</th>
                    <th>Route Type</th>
                    <th>Status</th>
                  
                </tr>
            </thead>
            <tbody>
                @foreach($route->buses as $index => $bus)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="fw-bold">{{ $bus->bus_number }}</div>
                        @if($bus->transport_reference_id)
                        <small class="text-muted">{{ $bus->transport_reference_id }}</small>
                        @endif
                    </td>
                    <td>{{ $bus->vehicle_number }}</td>
                    <td>
                        <div>{{ $bus->driver_name }}</div>
                        <small class="text-muted">
                            <i class="bi bi-telephone"></i> {{ $bus->driver_contact }}
                        </small>
                    </td>
                    <td>{{ $bus->driver_contact }}</td>
                    <td>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-people"></i> {{ $bus->sitting_capacity }} seats
                        </span>
                    </td>
                    <td>
                        @if($bus->route_type)
                            <span class="route-badge">
                                @if($bus->route_type == 'morning')
                                    <i class="bi bi-sun"></i> Morning
                                @elseif($bus->route_type == 'evening')
                                    <i class="bi bi-moon"></i> Evening
                                @elseif($bus->route_type == 'both')
                                    <i class="bi bi-arrow-left-right"></i> Both
                                @else
                                    {{ ucfirst($bus->route_type) }}
                                @endif
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $bus->status ? 'status-active' : 'status-inactive' }}">
                            {{ $bus->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="bi bi-bus-front"></i>
        </div>
        <h5>No Buses Assigned Yet</h5>
        <p class="text-muted mb-3">This route doesn't have any buses assigned yet.</p>
        <a href="{{ route('admin.transport.add') }}?route_id={{ $route->id }}" class="action-btn action-btn-primary">
            <i class="bi bi-plus-circle"></i>
            Add Your First Bus
        </a>
    </div>
    @endif
</div>

<!-- Bus Details Modal -->
<div class="modal fade" id="busDetailsModal" tabindex="-1" aria-labelledby="busDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="busDetailsModalLabel">
                    <i class="bi bi-bus-front"></i>
                    Bus Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="busDetailsModalContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading bus details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// View bus details function
function viewBusDetails(busId) {
    $('#busDetailsModalLabel').html('<i class="bi bi-bus-front"></i> Bus Details');
    $('#busDetailsModalContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading bus details...</p>
        </div>
    `);
    $('#busDetailsModal').modal('show');
    
    $.ajax({
        url: `/institute/admin/transport/${busId}/details`,
        method: 'GET',
        success: function(response) {
            // Format and display bus details
            let html = '<div class="container-fluid">';
            
            if (response.success) {
                let bus = response.bus || response;
                html += `
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr><th>Bus Number</th><td>${bus.bus_number || 'N/A'}</td></tr>
                                <tr><th>Vehicle Number</th><td>${bus.vehicle_number || 'N/A'}</td></tr>
                                <tr><th>Route Name</th><td>${bus.route_name || 'N/A'}</td></tr>
                                <tr><th>Driver Name</th><td>${bus.driver_name || 'N/A'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr><th>Driver Contact</th><td>${bus.driver_contact || 'N/A'}</td></tr>
                                <tr><th>Capacity</th><td>${bus.sitting_capacity || 'N/A'} seats</td></tr>
                                <tr><th>Route Type</th><td>${bus.route_type || 'N/A'}</td></tr>
                                <tr><th>Status</th><td>
                                    <span class="badge ${bus.status ? 'bg-success' : 'bg-secondary'}">
                                        ${bus.status ? 'Active' : 'Inactive'}
                                    </span>
                                </td></tr>
                            </table>
                        </div>
                    </div>
                `;
            } else {
                html += '<div class="alert alert-warning">Unable to load bus details</div>';
            }
            
            html += '</div>';
            $('#busDetailsModalContent').html(html);
        },
        error: function() {
            $('#busDetailsModalContent').html(`
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    Error loading bus details
                </div>
            `);
        }
    });
}
</script>

<!-- Add route badge style if not already defined -->
<style>
.route-badge {
    background: #e0f2fe;
    color: #0369a1;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
    display: inline-block;
    border: 1px solid #bae6fd;
}
</style>
@endsection