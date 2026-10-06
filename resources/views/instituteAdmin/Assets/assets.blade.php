@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Asset Categories & Assets Management</title>
    
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
            border: 1px solid var(--border-color);
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
            transition: all 0.3s ease;
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
            transition: all 0.3s ease;
        }

        .btn-outline-purple:hover {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-outline-danger {
            border: 2px solid #dc3545;
            color: #dc3545;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: var(--danger-gradient);
            color: white;
            border-color: transparent;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #cbd5e1;
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

        .btn-sm-custom {
            padding: 5px 12px;
            font-size: 0.8rem;
            border-radius: 8px;
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

        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67,97,238,0.15);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220,53,69,0.15);
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .toast-container {
            z-index: 9999;
        }

        .toast {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .toast-header {
            border-bottom: 1px solid var(--border-color);
        }

        /* ============================================ */
        /* GRID VIEW STYLES */
        /* ============================================ */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .category-card {
            background: white;
            border-radius: 12px;
            padding: 20px 15px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .category-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(67,97,238,0.15);
        }

        .category-card.active {
            border-color: var(--primary-color);
            background: #f0f3ff;
        }

        .category-card .category-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .category-card .category-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .category-card .category-id {
            font-size: 0.8rem;
            color: #a0aec0;
            margin-bottom: 8px;
        }

        .category-card .asset-count {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .asset-count-badge {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 0.8rem;
            display: inline-block;
        }

        .category-card .action-buttons {
            margin-top: 12px;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .category-card .action-buttons .btn {
            padding: 4px 12px;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .category-click-hint {
            position: absolute;
            bottom: 0px;
            right: 12px;
            font-size: 0.7rem;
            color: #a0aec0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .category-card:hover .category-click-hint {
            opacity: 1;
        }

        /* ============================================ */
        /* LIST VIEW STYLES */
        /* ============================================ */
        .category-list {
            display: none;
        }

        .category-list.show {
            display: block;
        }

        .category-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background: white;
            border-radius: 10px;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .category-list-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(67,97,238,0.1);
        }

        .category-list-item .list-item-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .category-list-item .list-item-left .list-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(67,97,238,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .category-list-item .list-item-left .list-info .list-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1rem;
        }

        .category-list-item .list-item-left .list-info .list-id {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .category-list-item .list-item-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .category-list-item .list-item-right .list-asset-count {
            background: var(--primary-gradient);
            color: white;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .category-list-item .list-item-right .list-actions {
            display: flex;
            gap: 8px;
        }

        .category-list-item .list-item-right .list-actions .btn {
            padding: 4px 10px;
            font-size: 0.75rem;
            border-radius: 8px;
        }

        /* ============================================ */
        /* VIEW TOGGLE BUTTONS */
        /* ============================================ */
        .view-toggle {
            display: flex;
            gap: 5px;
            background: white;
            padding: 4px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .view-toggle .btn-view {
            padding: 6px 14px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .view-toggle .btn-view:hover {
            color: var(--primary-color);
        }

        .view-toggle .btn-view.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 12px rgba(67,97,238,0.3);
        }

        .view-toggle .btn-view i {
            margin-right: 5px;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-success {
            color: #10b981 !important;
        }

        .text-warning {
            color: #f59e0b !important;
        }

        .text-danger {
            color: #ef4444 !important;
        }

        .bg-purple {
            background: var(--primary-gradient);
            color: white;
        }

        /* ============================================ */
        /* RESPONSIVE */
        /* ============================================ */
        @media (max-width: 768px) {
            .main-container {
                padding: 15px;
            }

            .category-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 15px;
            }

            .category-card .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .category-card .action-buttons .btn {
                font-size: 0.7rem;
                padding: 3px 8px;
            }

            .category-list-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding: 12px 15px;
            }

            .category-list-item .list-item-right {
                width: 100%;
                justify-content: space-between;
            }

            .view-toggle .btn-view {
                padding: 4px 10px;
                font-size: 0.75rem;
            }

            .view-toggle .btn-view span {
                display: none;
            }

            .view-toggle .btn-view i {
                margin-right: 0;
            }
        }

        @media (max-width: 480px) {
            .category-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .category-card {
                padding: 15px 10px;
            }

            .category-card .category-icon {
                font-size: 1.8rem;
            }

            .category-card .category-name {
                font-size: 0.85rem;
            }

            .category-list-item .list-item-left {
                gap: 10px;
            }

            .category-list-item .list-item-left .list-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }

            .category-list-item .list-item-left .list-info .list-name {
                font-size: 0.85rem;
            }
        }

        /* Search Input Styles */
        .search-input-wrapper {
            position: relative;
        }

        .search-input-wrapper .form-control {
            padding-left: 40px;
        }

        .search-input-wrapper .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }
    </style>
</head>
<body>

<div class="container-fluid main-container">
    <div class="row">
        <div class="col-12">
            {{-- Page Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1" style="color: var(--text-dark);">
                        <i class="fas fa-tags me-2" style="color: var(--primary-color);"></i>
                        Asset Categories & Assets
                    </h2>
                    <p class="text-muted mb-0">Manage your asset categories and view associated assets</p>
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    {{-- View Toggle --}}
                    <div class="view-toggle" id="viewToggle">
                        <button class="btn-view active" data-view="grid" title="Grid View">
                            <i class="fas fa-th"></i>
                            <span>Grid</span>
                        </button>
                        <button class="btn-view" data-view="list" title="List View">
                            <i class="fas fa-list"></i>
                            <span>List</span>
                        </button>
                    </div>
                    <button type="button" class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus-circle me-2"></i>Add New Category
                    </button>
                </div>
            </div>

            {{-- Quick Stats Section --}}
            <div class="row mb-4">
                <div class="col-md-3 col-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Categories</h6>
                            <h3 class="fw-bold" style="color: var(--primary-color);">{{ $categories->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Total Assets</h6>
                            <h3 class="fw-bold" style="color: #10b981;">{{ $categories->sum('assets_count') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Categories with Assets</h6>
                            <h3 class="fw-bold" style="color: #f59e0b;">{{ $categories->filter(function($cat) { return $cat->assets_count > 0; })->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Empty Categories</h6>
                            <h3 class="fw-bold" style="color: #ef4444;">{{ $categories->filter(function($cat) { return $cat->assets_count == 0; })->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Categories Section --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-folder me-2"></i>
                        Categories
                        <span class="badge bg-light text-dark ms-2">{{ $categories->count() }}</span>
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <div class="search-input-wrapper">
                            <span class="search-icon"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control form-control-sm" id="categorySearch" placeholder="Search categories..." style="width: 200px; padding-left: 35px;">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Grid View --}}
                    <div class="category-grid" id="categoryGrid">
                        @foreach($categories as $category)
                            <div class="category-card" 
                                 data-category-id="{{ $category->id }}"
                                 data-category-name="{{ strtolower($category->name) }}"
                                 onclick="window.location.href='{{ route('institute.admin.asset-categories.assets', $category->id) }}'">
                                <div class="category-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <div class="category-name">{{ $category->name }}</div>
                                <div class="category-id">{{ $category->category_id }}</div>
                                <div class="asset-count">
                                    <span class="asset-count-badge">
                                        <i class="fas fa-boxes me-1"></i>
                                        {{ $category->assets_count ?? 0 }} Assets
                                    </span>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-purple edit-category-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal{{ $category->id }}"
                                            onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-category-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $category->id }}"
                                            onclick="event.stopPropagation();">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="category-click-hint">
                                    <i class="fas fa-arrow-right"></i> Click to view
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- List View --}}
                    <div class="category-list" id="categoryList">
                        @foreach($categories as $category)
                            <div class="category-list-item" 
                                 data-category-id="{{ $category->id }}"
                                 data-category-name="{{ strtolower($category->name) }}"
                                 onclick="window.location.href='{{ route('institute.admin.asset-categories.assets', $category->id) }}'">
                                <div class="list-item-left">
                                    <div class="list-icon">
                                        <i class="fas fa-folder"></i>
                                    </div>
                                    <div class="list-info">
                                        <div class="list-name">{{ $category->name }}</div>
                                        <div class="list-id">
                                            <span class="badge-id">{{ $category->category_id }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-item-right">
                                    <span class="list-asset-count">
                                        <i class="fas fa-boxes me-1"></i>
                                        {{ $category->assets_count ?? 0 }} Assets
                                    </span>
                                    <div class="list-actions">
                                        <button class="btn btn-sm btn-outline-purple edit-category-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $category->id }}"
                                                onclick="event.stopPropagation();">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-category-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $category->id }}"
                                                onclick="event.stopPropagation();">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($categories->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open" style="font-size: 4rem; color: #a0aec0;"></i>
                            <h5 class="mt-3 text-muted">No categories found</h5>
                            <p class="text-muted">Click "Add New Category" to create your first category.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Category Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Create New Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="create_category_id" class="form-label">Category ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_category_id" maxlength="12" placeholder="e.g., CAT-001">
                    <small class="text-muted">Maximum 12 characters, must be unique</small>
                    <div class="invalid-feedback create-id-error"></div>
                </div>
                <div class="mb-0">
                    <label for="create_name" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="create_name" maxlength="255" placeholder="e.g., Office Equipment">
                    <small class="text-muted">Maximum 255 characters, must be unique</small>
                    <div class="invalid-feedback create-name-error"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-purple" id="createCategory">
                    <i class="fas fa-plus-circle me-2"></i>Create Category
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modals --}}
@foreach($categories as $category)
<div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label for="edit_category_id_{{ $category->id }}" class="form-label">Category ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control edit-category-id" id="edit_category_id_{{ $category->id }}" value="{{ $category->category_id }}" maxlength="12">
                    <small class="text-muted">Maximum 12 characters, unique identifier</small>
                    <div class="invalid-feedback edit-id-error"></div>
                </div>
                <div class="mb-0">
                    <label for="edit_name_{{ $category->id }}" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control edit-category-name" id="edit_name_{{ $category->id }}" value="{{ $category->name }}" maxlength="255">
                    <small class="text-muted">Maximum 255 characters, must be unique</small>
                    <div class="invalid-feedback edit-name-error"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-purple update-category" data-id="{{ $category->id }}">
                    <i class="fas fa-save me-2"></i>Update Category
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modals --}}
<div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger-gradient);">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="fas fa-trash-alt" style="font-size: 4rem; color: #ef4444;"></i>
                </div>
                <h5 class="fw-bold" style="color: var(--text-dark);">Delete Category</h5>
                <p class="text-muted mb-1">Are you sure you want to delete the category:</p>
                <p class="fw-bold" style="color: #dc3545;">"{{ $category->name }}" ({{ $category->category_id }})</p>
                @if($category->assets_count > 0)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Warning:</strong> This category has <strong>{{ $category->assets_count }}</strong> asset(s) associated with it. It cannot be deleted until all assets are removed.
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        This action cannot be undone.
                    </div>
                @endif
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                @if($category->assets_count == 0)
                    <button type="button" class="btn btn-danger delete-category" data-id="{{ $category->id }}">
                        <i class="fas fa-trash me-2"></i>Yes, Delete
                    </button>
                @else
                    <button type="button" class="btn btn-secondary" disabled>
                        <i class="fas fa-lock me-2"></i>Cannot Delete (Has Assets)
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- Toast Container --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-circle me-2" style="color: #10b981;"></i>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">Operation completed successfully.</div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Base URL for AJAX calls
    const baseUrl = '{{ url("/institute/admin/asset-categories") }}';

    // ==========================================
    // VIEW TOGGLE
    // ==========================================
    let currentView = 'grid';

    $('.btn-view').on('click', function() {
        const view = $(this).data('view');
        if (view === currentView) return;
        
        $('.btn-view').removeClass('active');
        $(this).addClass('active');
        
        if (view === 'grid') {
            $('#categoryGrid').show();
            $('#categoryList').removeClass('show').hide();
            currentView = 'grid';
        } else {
            $('#categoryGrid').hide();
            $('#categoryList').addClass('show').show();
            currentView = 'list';
        }
    });

    // ==========================================
    // SEARCH CATEGORIES
    // ==========================================
    $('#categorySearch').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase().trim();
        
        // Grid View Search
        $('#categoryGrid .category-card').each(function() {
            const name = $(this).data('category-name') || '';
            const matches = name.includes(searchTerm);
            $(this).toggle(matches);
        });
        
        // List View Search
        $('#categoryList .category-list-item').each(function() {
            const name = $(this).data('category-name') || '';
            const matches = name.includes(searchTerm);
            $(this).toggle(matches);
        });
        
        // Show/hide empty message
        const gridVisible = $('#categoryGrid .category-card:visible').length;
        const listVisible = $('#categoryList .category-list-item:visible').length;
        
        if (gridVisible === 0 && currentView === 'grid') {
            if ($('#gridEmptyMessage').length === 0) {
                $('#categoryGrid').append(
                    '<div class="text-center py-5" id="gridEmptyMessage" style="grid-column: 1 / -1;">' +
                        '<i class="fas fa-search" style="font-size: 3rem; color: #a0aec0;"></i>' +
                        '<h5 class="mt-3 text-muted">No categories found matching "' + searchTerm + '"</h5>' +
                        '<p class="text-muted">Try adjusting your search terms.</p>' +
                    '</div>'
                );
            } else {
                $('#gridEmptyMessage').show().html(
                    '<i class="fas fa-search" style="font-size: 3rem; color: #a0aec0;"></i>' +
                    '<h5 class="mt-3 text-muted">No categories found matching "' + searchTerm + '"</h5>' +
                    '<p class="text-muted">Try adjusting your search terms.</p>'
                );
            }
        } else {
            $('#gridEmptyMessage').hide();
        }
        
        if (listVisible === 0 && currentView === 'list') {
            if ($('#listEmptyMessage').length === 0) {
                $('#categoryList').append(
                    '<div class="text-center py-5" id="listEmptyMessage">' +
                        '<i class="fas fa-search" style="font-size: 3rem; color: #a0aec0;"></i>' +
                        '<h5 class="mt-3 text-muted">No categories found matching "' + searchTerm + '"</h5>' +
                        '<p class="text-muted">Try adjusting your search terms.</p>' +
                    '</div>'
                );
            } else {
                $('#listEmptyMessage').show().html(
                    '<i class="fas fa-search" style="font-size: 3rem; color: #a0aec0;"></i>' +
                    '<h5 class="mt-3 text-muted">No categories found matching "' + searchTerm + '"</h5>' +
                    '<p class="text-muted">Try adjusting your search terms.</p>'
                );
            }
        } else {
            $('#listEmptyMessage').hide();
        }
    });

    // ==========================================
    // CREATE CATEGORY
    // ==========================================
    $('#createCategory').on('click', function() {
        const categoryId = $('#create_category_id').val();
        const name = $('#create_name').val();
        
        $('.create-id-error, .create-name-error').hide();
        $('#create_category_id, #create_name').removeClass('is-invalid');

        $.ajax({
            url: baseUrl,
            type: 'POST',
            data: {
                category_id: categoryId,
                name: name,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $('#createCategory').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Creating...');
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.category_id) {
                        $('#create_category_id').addClass('is-invalid');
                        $('.create-id-error').text(errors.category_id[0]).show();
                    }
                    if (errors.name) {
                        $('#create_name').addClass('is-invalid');
                        $('.create-name-error').text(errors.name[0]).show();
                    }
                } else {
                    showToast('error', xhr.responseJSON?.message || 'Unable to create category');
                }
            },
            complete: function() {
                $('#createCategory').prop('disabled', false).html('<i class="fas fa-plus-circle me-2"></i>Create Category');
            }
        });
    });

    // ==========================================
    // UPDATE CATEGORY
    // ==========================================
    $('.update-category').on('click', function() {
        const id = $(this).data('id');
        const categoryId = $(`#edit_category_id_${id}`).val();
        const name = $(`#edit_name_${id}`).val();
        
        $(`#edit_category_id_${id}, #edit_name_${id}`).removeClass('is-invalid');
        $(`.edit-id-error, .edit-name-error`).hide();

        $.ajax({
            url: `${baseUrl}/${id}`,
            type: 'PUT',
            data: {
                category_id: categoryId,
                name: name,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.category_id) {
                        $(`#edit_category_id_${id}`).addClass('is-invalid');
                        $(`.edit-id-error`).text(errors.category_id[0]).show();
                    }
                    if (errors.name) {
                        $(`#edit_name_${id}`).addClass('is-invalid');
                        $(`.edit-name-error`).text(errors.name[0]).show();
                    }
                } else if (xhr.status === 404) {
                    showToast('error', 'Category not found');
                } else {
                    showToast('error', xhr.responseJSON?.message || 'Unable to update category');
                }
            },
            complete: function() {
                $(this).prop('disabled', false).html('<i class="fas fa-save me-2"></i>Update Category');
            }
        });
    });

    // ==========================================
    // DELETE CATEGORY
    // ==========================================
    $('.delete-category').on('click', function() {
        const id = $(this).data('id');

        $.ajax({
            url: `${baseUrl}/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Deleting...');
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 404) {
                    showToast('error', 'Category not found');
                } else {
                    showToast('error', xhr.responseJSON?.message || 'Unable to delete category. It may be in use by assets.');
                }
            },
            complete: function() {
                $(this).prop('disabled', false).html('<i class="fas fa-trash me-2"></i>Yes, Delete');
            }
        });
    });

    // ==========================================
    // TOAST FUNCTION
    // ==========================================
    function showToast(type, message) {
        var toast = $('#liveToast');
        var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        var color = type === 'success' ? '#10b981' : '#ef4444';
        
        toast.find('.toast-header i').attr('class', 'fas ' + icon + ' me-2').css('color', color);
        toast.find('.toast-body').text(message);
        
        var bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 5000
        });
        bsToast.show();
    }

    // ==========================================
    // ENTER KEY HANDLERS
    // ==========================================
    $('#create_category_id, #create_name').on('keypress', function(e) {
        if (e.which === 13) {
            $('#createCategory').click();
        }
    });

    $(document).on('keypress', '.edit-category-id, .edit-category-name', function(e) {
        if (e.which === 13) {
            const modal = $(this).closest('.modal');
            const updateBtn = modal.find('.update-category');
            if (updateBtn.length) {
                updateBtn.click();
            }
        }
    });

    // ==========================================
    // KEYBOARD SHORTCUTS
    // ==========================================
    $(document).on('keydown', function(e) {
        // Ctrl+F to focus search
        if (e.ctrlKey && (e.key === 'f' || e.key === 'F')) {
            e.preventDefault();
            $('#categorySearch').focus();
        }
        // Escape to clear search
        if (e.key === 'Escape') {
            $('#categorySearch').val('');
            $('#categorySearch').trigger('keyup');
        }
        // Alt+1 for Grid View
        if (e.altKey && e.key === '1') {
            e.preventDefault();
            $('.btn-view[data-view="grid"]').click();
        }
        // Alt+2 for List View
        if (e.altKey && e.key === '2') {
            e.preventDefault();
            $('.btn-view[data-view="list"]').click();
        }
    });
});
</script>
</body>
</html>
@endsection