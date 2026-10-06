@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $category->name }} - Assets</title>
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --info-gradient: linear-gradient(135deg, #0ea5e9, #0284c7);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 20px 25px;
        }

        .card-header h5 {
            margin: 0;
        }

        .btn-purple {
            background: var(--primary-gradient);
            color: white;
            border: none;
        }

        .btn-purple:hover {
            color: white;
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67,97,238,0.4);
        }

        .btn-outline-purple {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-purple:hover {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
            border: none;
        }

        .btn-success:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16,185,129,0.4);
            color: white;
        }

        .btn-danger {
            background: var(--danger-gradient);
            color: white;
            border: none;
        }

        .btn-danger:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239,68,68,0.4);
            color: white;
        }

        .btn-info {
            background: var(--info-gradient);
            color: white;
            border: none;
        }

        .btn-info:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(14,165,233,0.4);
            color: white;
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
            border: none;
        }

        .btn-warning:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245,158,11,0.4);
            color: white;
        }

        .table thead {
            background: #f8fafc;
        }

        .table thead th {
            color: var(--text-dark);
            font-weight: 700;
            border-bottom: 2px solid var(--border-color);
        }

        .badge-id {
            background: #e9ecef;
            color: var(--text-dark);
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .badge-category {
            background: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .category-info {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(67,97,238,0.3);
        }

        .category-info .category-icon {
            font-size: 2.5rem;
            margin-right: 15px;
        }

        .category-info .category-details h3 {
            margin: 0;
            font-weight: 700;
        }

        .category-info .category-details p {
            margin: 0;
            opacity: 0.9;
        }

        .asset-count-badge {
            background: rgba(255,255,255,0.2);
            color: white;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
            border: 1px solid #86efac;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #fca5a5;
        }

        .status-maintenance {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #fcd34d;
        }

        .status-pending {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .status-available {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .back-btn {
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .back-btn:hover {
            transform: translateX(-5px);
        }

        .action-buttons .btn {
            margin: 0 3px;
            border-radius: 8px;
            padding: 6px 12px;
        }

        .action-buttons .btn i {
            font-size: 0.85rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67,97,238,0.15);
            outline: none;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 5px 8px;
        }

        .dataTables_wrapper .dataTables_info {
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            padding: 6px 14px !important;
            margin: 0 2px !important;
            border: 1px solid var(--border-color) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary-gradient) !important;
            color: white !important;
            border-color: var(--primary-color) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--primary-gradient) !important;
            color: white !important;
            border-color: var(--primary-color) !important;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 20px 25px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 15px 25px;
            border-top: 1px solid var(--border-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                padding: 15px;
            }
            .category-info {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .category-info .d-flex {
                flex-direction: column;
                text-align: center;
            }
            .category-info .category-icon {
                margin-right: 0;
                margin-bottom: 10px;
            }
            .action-buttons .btn {
                padding: 4px 8px;
                font-size: 0.7rem;
            }
            .action-buttons .btn i {
                font-size: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            .table-responsive {
                font-size: 0.8rem;
            }
            .badge-id, .status-badge {
                font-size: 0.7rem;
                padding: 3px 10px;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid main-container">
    <div class="row">
        <div class="col-12">
            {{-- Back Button --}}
            <a href="{{ route('institute.admin.asset-categories.index') }}" class="btn btn-outline-purple back-btn">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>

            {{-- Category Information --}}
            <div class="category-info d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="category-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="category-details">
                        <h3>{{ $category->name }}</h3>
                        <p><i class="fas fa-tag me-2"></i>Category ID: {{ $category->category_id }}</p>
                    </div>
                </div>
                <div>
                    <span class="asset-count-badge">
                        <i class="fas fa-boxes me-2"></i>{{ $assets->count() }} Assets
                    </span>
                </div>
            </div>

            {{-- Assets Table --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Asset List
                    </h5>
                    <button class="btn btn-light btn-sm" onclick="window.location.reload();">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                </div>
                <div class="card-body">
                    @if($assets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover" id="assetsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="sortable">Asset ID</th>
                                        <th class="sortable">Asset Name</th>
                                        <th class="sortable">Serial Number</th>
                                        <th class="sortable">Model</th>
                                        <th class="sortable">Status</th>
                                        <th class="sortable">Purchase Date</th>
                                        <th class="sortable">Added Date</th>
                                        <th class="sortable">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assets as $index => $asset)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <span class="badge-id">{{ $asset->asset_id ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold" style="color: var(--text-dark);">{{ $asset->asset_name }}</span>
                                            </td>
                                            <td>{{ $asset->serial_number ?? 'N/A' }}</td>
                                            <td>{{ $asset->model ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $statusClass = 'status-active';
                                                    $statusText = 'Active';
                                                    if(isset($asset->status)) {
                                                        $statusLower = strtolower($asset->status);
                                                        if($statusLower === 'inactive' || $statusLower === 'in-active') {
                                                            $statusClass = 'status-inactive';
                                                            $statusText = 'Inactive';
                                                        } elseif($statusLower === 'maintenance') {
                                                            $statusClass = 'status-maintenance';
                                                            $statusText = 'Maintenance';
                                                        } elseif($statusLower === 'pending') {
                                                            $statusClass = 'status-pending';
                                                            $statusText = 'Pending';
                                                        } elseif($statusLower === 'available') {
                                                            $statusClass = 'status-available';
                                                            $statusText = 'Available';
                                                        }
                                                    }
                                                @endphp
                                                <span class="status-badge {{ $statusClass }}">
                                                    {{ ucfirst($asset->status ?? 'Active') }}
                                                </span>
                                            </td>
                                            <td>{{ $asset->purchase_date ? date('d M Y', strtotime($asset->purchase_date)) : 'N/A' }}</td>
                                            <td>{{ $asset->created_at ? date('d M Y', strtotime($asset->created_at)) : 'N/A' }}</td>
                                            <td class="text-center action-buttons">
                                                <button class="btn btn-sm btn-info text-white" title="View Asset" data-bs-toggle="modal" data-bs-target="#viewAssetModal{{ $asset->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning text-white" title="Edit Asset" data-bs-toggle="modal" data-bs-target="#editAssetModal{{ $asset->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger text-white" title="Delete Asset" data-bs-toggle="modal" data-bs-target="#deleteAssetModal{{ $asset->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h4 class="fw-bold">No Assets Found</h4>
                            <p class="text-muted">This category currently has no assets assigned to it.</p>
                            <button class="btn btn-purple mt-3">
                                <i class="fas fa-plus-circle me-2"></i>Add New Asset
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#assetsTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            search: "Search assets:",
            lengthMenu: "Show _MENU_ assets per page",
            info: "Showing _START_ to _END_ of _TOTAL_ assets",
            infoEmpty: "No assets available",
            infoFiltered: "(filtered from _MAX_ total assets)"
        },
        columnDefs: [
            { orderable: false, targets: [8] } // Disable sorting on Actions column
        ]
    });
});
</script>

</body>
</html>
@endsection