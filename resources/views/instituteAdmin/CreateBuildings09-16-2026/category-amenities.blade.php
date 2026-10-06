@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $category->name }} - Amenities</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            background: #f0f2f5;
        }

        .main-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 25px 30px;
        }

        .category-header {
            background: var(--primary-gradient);
            padding: 30px 35px;
            border-radius: 16px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(67,97,238,0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .category-header .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .category-header .header-left .icon {
            font-size: 3rem;
            background: rgba(255,255,255,0.15);
            padding: 15px;
            border-radius: 14px;
        }

        .category-header .header-left .info h1 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.8rem;
        }

        .category-header .header-left .info p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        .category-header .header-right .badge-count {
            background: rgba(255,255,255,0.2);
            padding: 10px 24px;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stats-card {
            background: white;
            padding: 18px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            text-align: center;
        }

        .stats-card .stats-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .stats-card .stats-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .stats-card .stats-icon {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .stats-card.purple .stats-icon { color: var(--primary-color); }
        .stats-card.green .stats-icon { color: #10b981; }
        .stats-card.orange .stats-icon { color: #f59e0b; }
        .stats-card.red .stats-icon { color: #ef4444; }

        .filter-section {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            margin-bottom: 25px;
        }

        .filter-section .form-control,
        .filter-section .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 10px 15px;
        }

        .filter-section .form-control:focus,
        .filter-section .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67,97,238,0.15);
        }

        .btn-purple {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67,97,238,0.4);
            color: white;
        }

        .btn-outline-purple {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-purple:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16,185,129,0.4);
            color: white;
        }

        .btn-outline-primary {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 5px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        .btn-outline-danger {
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
            padding: 5px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
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
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
            transform: translateY(-2px);
        }

        .view-toggle {
            display: flex;
            gap: 5px;
            background: white;
            padding: 4px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .view-toggle .btn-view {
            padding: 6px 16px;
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
            margin-right: 6px;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .amenity-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .amenity-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(67,97,238,0.12);
        }

        .amenity-card.selected {
            border-color: var(--primary-color);
            background: #f0f3ff;
        }

        .amenity-card .amenity-check {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
        }

        .amenity-card.selected .amenity-check {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .amenity-card .amenity-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .amenity-card .amenity-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1.05rem;
            margin-bottom: 4px;
        }

        .amenity-card .amenity-id {
            font-size: 0.8rem;
            color: #a0aec0;
            margin-bottom: 8px;
        }

        .amenity-card .amenity-specs {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .amenity-card .amenity-specs .spec-item {
            display: inline-block;
            background: #f7fafc;
            padding: 2px 12px;
            border-radius: 15px;
            margin: 2px 4px 2px 0;
            font-size: 0.75rem;
        }

        .amenity-card .amenity-actions {
            margin-top: 15px;
            display: flex;
            gap: 8px;
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
            flex-wrap: wrap;
        }

        .amenity-card .amenity-actions .btn {
            padding: 5px 14px;
            font-size: 0.8rem;
            border-radius: 8px;
            flex: 1;
        }

        .amenity-card .amenity-actions .edit-specs-btn {
            text-decoration: none;
            text-align: center;
        }

        .amenities-list {
            display: none;
        }

        .amenities-list.show {
            display: block;
        }

        .amenity-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background: white;
            border-radius: 10px;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .amenity-list-item:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(67,97,238,0.1);
        }

        .amenity-list-item.selected {
            border-color: var(--primary-color);
            background: #f0f3ff;
        }

        .amenity-list-item .list-item-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            min-width: 0;
        }

        .amenity-list-item .list-item-left .list-check {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .amenity-list-item.selected .list-check {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .amenity-list-item .list-item-left .list-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(67,97,238,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .amenity-list-item .list-item-left .list-info {
            flex: 1;
            min-width: 0;
        }

        .amenity-list-item .list-item-left .list-info .list-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1rem;
        }

        .amenity-list-item .list-item-left .list-info .list-id {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .amenity-list-item .list-item-left .list-info .list-specs {
            margin-top: 4px;
        }

        .amenity-list-item .list-item-left .list-info .list-specs .spec-item {
            display: inline-block;
            background: #f7fafc;
            padding: 1px 10px;
            border-radius: 12px;
            margin: 2px 4px 2px 0;
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .amenity-list-item .list-item-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .amenity-list-item .list-item-right .list-actions {
            display: flex;
            gap: 6px;
        }

        .amenity-list-item .list-item-right .list-actions .btn {
            padding: 4px 10px;
            font-size: 0.75rem;
            border-radius: 8px;
        }

        .amenity-list-item .list-item-right .list-actions .edit-specs-btn {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .toast-container {
            z-index: 9999;
        }

        /* Bottom Navigation Bar */
        .bottom-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid var(--border-color);
            flex-wrap: wrap;
            gap: 15px;
        }

        .bottom-nav .left-section {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .bottom-nav .right-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-btn {
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateX(-5px);
        }

        /* Sync Modal */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal-header {
            border-radius: 16px 16px 0 0;
            padding: 20px 25px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 30px 25px;
            text-align: center;
        }

        .modal-footer {
            padding: 15px 25px;
            border-top: 1px solid var(--border-color);
            justify-content: center;
        }

        .modal-icon {
            font-size: 4rem;
            margin-bottom: 15px;
        }

        .modal-icon.success { color: #10b981; }
        .modal-icon.error { color: #ef4444; }
        .modal-icon.warning { color: #f59e0b; }

        /* Responsive */
        @media (max-width: 992px) {
            .main-container {
                padding: 15px;
            }
            .category-header {
                padding: 20px;
            }
            .category-header .header-left .icon {
                font-size: 2rem;
                padding: 12px;
            }
            .category-header .header-left .info h1 {
                font-size: 1.3rem;
            }
            .amenities-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }
            .view-toggle .btn-view {
                padding: 4px 12px;
                font-size: 0.75rem;
            }
            .view-toggle .btn-view span {
                display: none;
            }
            .view-toggle .btn-view i {
                margin-right: 0;
            }
            .bottom-nav {
                flex-direction: column;
                align-items: stretch;
            }
            .bottom-nav .left-section,
            .bottom-nav .right-section {
                justify-content: center;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 768px) {
            .filter-section .row > div {
                margin-bottom: 10px;
            }
            .amenities-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
            .category-header {
                flex-direction: column;
                text-align: center;
            }
            .category-header .header-left {
                flex-direction: column;
                text-align: center;
            }
            .amenity-list-item {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 12px 15px;
            }
            .amenity-list-item .list-item-right {
                justify-content: space-between;
            }
        }

        @media (max-width: 480px) {
            .amenities-grid {
                grid-template-columns: 1fr;
            }
            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            .stats-card .stats-number {
                font-size: 1.4rem;
            }
            .amenity-list-item .list-item-left {
                flex-wrap: wrap;
            }
            .amenity-list-item .list-item-left .list-info {
                flex: 1 1 100%;
            }
            .bottom-nav .left-section {
                flex-direction: column;
                width: 100%;
            }
            .bottom-nav .left-section .btn {
                width: 100%;
                justify-content: center;
            }
            .bottom-nav .right-section {
                width: 100%;
            }
            .bottom-nav .right-section .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid main-container">
    <div class="row">
        <div class="col-12">
            <!-- Category Header -->
            <div class="category-header">
                <div class="header-left">
                    <div class="icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="info">
                        <h1>{{ $category->name }}</h1>
                        <p><i class="fas fa-tag me-2"></i>Category ID: {{ $category->category_id }}</p>
                    </div>
                </div>
                <div class="header-right">
                    <span class="badge-count">
                        <i class="fas fa-cubes me-2"></i>{{ $assets->count() }} Assets
                    </span>
                </div>
            </div>

            <!-- Stats -->
            <div class="stats-row">
                <div class="stats-card purple">
                    <div class="stats-icon"><i class="fas fa-cube"></i></div>
                    <div class="stats-number">{{ $assets->count() }}</div>
                    <div class="stats-label">Total Assets</div>
                </div>
                <div class="stats-card green">
                    <div class="stats-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stats-number" id="selectedCount">0</div>
                    <div class="stats-label">Selected</div>
                </div>
                <div class="stats-card orange">
                    <div class="stats-icon"><i class="fas fa-clock"></i></div>
                    <div class="stats-number" id="pendingCount">0</div>
                    <div class="stats-label">Pending Selection</div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row align-items-end g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold mb-1">
                            <i class="fas fa-search me-1"></i>Search Assets
                        </label>
                        <input type="text" class="form-control" id="searchAsset" 
                               placeholder="Search by name or ID...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-1">
                            <i class="fas fa-sort me-1"></i>Sort By
                        </label>
                        <select class="form-select" id="sortAssets">
                            <option value="name">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="id">Asset ID</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-1">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button class="btn btn-purple w-100" id="applyFilters">
                                <i class="fas fa-sliders-h me-2"></i>Apply
                            </button>
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assets Grid & List -->
            <div class="row">
                <div class="col-12">
                    <!-- Grid View -->
                    <div class="amenities-grid" id="amenitiesGrid">
                        <!-- Assets will be rendered here -->
                    </div>
                    
                    <!-- List View -->
                    <div class="amenities-list" id="amenitiesList">
                        <!-- Assets will be rendered here -->
                    </div>
                    
                    <div class="text-center mt-4">
                        <button class="btn btn-outline-purple" id="loadMoreBtn" style="display:none;">
                            <i class="fas fa-plus-circle me-2"></i>Load More
                        </button>
                    </div>

                    <!-- Bottom Navigation Bar -->
                    <div class="bottom-nav">
                        <div class="left-section">
                            <!-- Back Button 1 -->
                            <a href="{{ route('institute.admin.amenities.index') }}" class="btn btn-outline-purple back-btn">
                                <i class="fas fa-arrow-left me-2"></i>Back to Amenities
                            </a>
                            <!-- Back Button 2 -->
                            <!-- <a href="{{ url()->previous() }}" class="btn btn-secondary back-btn">
                                <i class="fas fa-undo me-2"></i>Back
                            </a> -->
                        </div>
                        <div class="right-section">
                            <!-- Sync Button -->
                            <button class="btn btn-success btn-lg" id="syncAmenitiesBtn">
                                <i class="fas fa-sync-alt me-2"></i>Sync Selected to Amenities
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Asset Detail Modal -->
<div class="modal fade" id="amenityDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    <span id="detailModalTitle">Asset Details</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-purple" id="detailSelectBtn">
                    <i class="fas fa-plus-circle me-2"></i>Select This Asset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sync Result Modal -->
<div class="modal fade" id="syncResultModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="syncModalHeader">
                <h5 class="modal-title" id="syncModalTitle">
                    <i class="fas fa-sync-alt me-2"></i>Sync Result
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="syncModalBody">
                    <!-- Dynamic content -->
                </div>
            </div>
            <div class="modal-footer" id="syncModalFooter">
                <!-- Dynamic buttons -->
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    var assets = @json($assets);
    var selectedAmenityIds = @json($selectedAmenityIds);
    var categoryName = @json($category->name);

    var currentView = 'grid';
    var currentPage = 1;
    var itemsPerPage = 12;
    var filteredAssets = [];
    var selectedAssets = [];

    // Initialize selected assets from database
    selectedAssets = assets.filter(function(asset) {
        return selectedAmenityIds.includes(asset.asset_id);
    });

    // ==========================================
    // VIEW TOGGLE
    // ==========================================
    $('.btn-view').on('click', function() {
        var view = $(this).data('view');
        if (view === currentView) return;
        
        $('.btn-view').removeClass('active');
        $(this).addClass('active');
        currentView = view;
        
        if (view === 'grid') {
            $('#amenitiesGrid').show();
            $('#amenitiesList').removeClass('show').hide();
        } else {
            $('#amenitiesGrid').hide();
            $('#amenitiesList').addClass('show').show();
        }
        
        renderAssets(filteredAssets);
    });

    // ==========================================
    // TOGGLE ASSET SELECTION
    // ==========================================
    window.toggleAmenity = function(assetId) {
        var asset = assets.find(function(a) { return a.asset_id === assetId; });
        if (!asset) return;
        
        var index = selectedAssets.findIndex(function(s) { return s.asset_id === assetId; });
        
        if (index > -1) {
            selectedAssets.splice(index, 1);
            showToast('info', 'Removed "' + asset.asset_name + '" from selection');
        } else {
            selectedAssets.push(asset);
            showToast('success', 'Added "' + asset.asset_name + '" to selection');
        }
        
        updateStats();
        renderAssets(filteredAssets);
    };

    // ==========================================
    // UPDATE STATS
    // ==========================================
    function updateStats() {
        var total = assets.length;
        var selected = selectedAssets.length;
        var pending = total - selected;
        
        $('#selectedCount').text(selected);
        $('#pendingCount').text(pending);
    }

    // ==========================================
    // APPLY FILTERS
    // ==========================================
    function applyFilters() {
        var searchTerm = $('#searchAsset').val().toLowerCase().trim();
        var sortBy = $('#sortAssets').val();
        
        var filtered = assets.slice();
        
        if (searchTerm) {
            filtered = filtered.filter(function(a) {
                return (a.asset_name || '').toLowerCase().includes(searchTerm) ||
                       (a.asset_id || '').toLowerCase().includes(searchTerm);
            });
        }
        
        switch(sortBy) {
            case 'name':
                filtered.sort(function(a, b) {
                    return (a.asset_name || '').localeCompare(b.asset_name || '');
                });
                break;
            case 'name_desc':
                filtered.sort(function(a, b) {
                    return (b.asset_name || '').localeCompare(a.asset_name || '');
                });
                break;
            case 'id':
                filtered.sort(function(a, b) {
                    return (a.asset_id || '').localeCompare(b.asset_id || '');
                });
                break;
        }
        
        filteredAssets = filtered;
        currentPage = 1;
        renderAssets(filtered);
    }

    // ==========================================
    // RENDER ASSETS
    // ==========================================
    function renderAssets(assetsList) {
        var start = 0;
        var end = currentPage * itemsPerPage;
        var pageItems = assetsList.slice(start, end);
        
        renderGridView(pageItems);
        renderListView(pageItems);
        
        if (end < assetsList.length) {
            $('#loadMoreBtn').show();
        } else {
            $('#loadMoreBtn').hide();
        }
    }

    function renderGridView(pageItems) {
        var grid = $('#amenitiesGrid');
        
        if (pageItems.length === 0) {
            grid.html(
                '<div class="col-12 text-center py-5" style="grid-column: 1 / -1;">' +
                    '<i class="fas fa-search" style="font-size: 3rem; color: #a0aec0; margin-bottom: 20px;"></i>' +
                    '<h5 class="fw-bold text-muted">No assets found</h5>' +
                    '<p class="text-muted">Try adjusting your search criteria</p>' +
                '</div>'
            );
            return;
        }
        
        var html = '';
        $.each(pageItems, function(index, asset) {
            var isSelected = selectedAssets.some(function(s) {
                return s.asset_id === asset.asset_id;
            });
            
            var specs = asset.specifications || {};
            var specKeys = Object.keys(specs).filter(function(k) {
                return specs[k] && specs[k] !== null && specs[k] !== 'null';
            });
            
            var specHtml = '';
            var displaySpecs = specKeys.slice(0, 3);
            $.each(displaySpecs, function(i, key) {
                specHtml += '<span class="spec-item">' + key.replace(/_/g, ' ') + ': ' + specs[key] + '</span>';
            });
            if (specKeys.length > 3) {
                specHtml += '<span class="spec-item">+' + (specKeys.length - 3) + ' more</span>';
            }
            
            html +=
                '<div class="amenity-card ' + (isSelected ? 'selected' : '') + '" data-asset-id="' + asset.asset_id + '">' +
                    '<div class="amenity-check" onclick="event.stopPropagation(); toggleAmenity(\'' + asset.asset_id + '\')">' + 
                        (isSelected ? '<i class="fas fa-check" style="color:white;font-size:12px;"></i>' : '') + 
                    '</div>' +
                    '<div class="amenity-icon"><i class="fas fa-cube"></i></div>' +
                    '<div class="amenity-name">' + (asset.asset_name || 'Unnamed Asset') + '</div>' +
                    '<div class="amenity-id">' + (asset.asset_id || 'N/A') + '</div>' +
                    '<div class="amenity-specs">' + specHtml + '</div>' +
                    '<div class="amenity-actions">' +
                        '<button class="btn btn-sm btn-outline-purple view-detail-btn" onclick="event.stopPropagation(); viewAmenityDetail(\'' + asset.asset_id + '\')">' +
                            '<i class="fas fa-eye me-1"></i>View' +
                        '</button>' +
                        '<a href="{{ url("/institute/admin/specifications") }}/' + asset.asset_id + '" class="d-none btn btn-sm btn-outline-primary edit-specs-btn" onclick="event.stopPropagation();">' +
                            '<i class="fas fa-cog me-1"></i>Specs' +
                        '</a>' +
                        '<button class="btn btn-sm ' + (isSelected ? 'btn-danger' : 'btn-purple') + ' select-btn" onclick="event.stopPropagation(); toggleAmenity(\'' + asset.asset_id + '\')">' +
                            '<i class="fas ' + (isSelected ? 'fa-times' : 'fa-plus') + ' me-1"></i>' +
                            (isSelected ? 'Remove' : 'Select') +
                        '</button>' +
                    '</div>' +
                '</div>';
        });
        
        grid.html(html);
    }

    function renderListView(pageItems) {
        var list = $('#amenitiesList');
        
        if (pageItems.length === 0) {
            list.html(
                '<div class="text-center py-5">' +
                    '<i class="fas fa-search" style="font-size: 3rem; color: #a0aec0; margin-bottom: 20px;"></i>' +
                    '<h5 class="fw-bold text-muted">No assets found</h5>' +
                    '<p class="text-muted">Try adjusting your search criteria</p>' +
                '</div>'
            );
            return;
        }
        
        var html = '';
        $.each(pageItems, function(index, asset) {
            var isSelected = selectedAssets.some(function(s) {
                return s.asset_id === asset.asset_id;
            });
            
            var specs = asset.specifications || {};
            var specKeys = Object.keys(specs).filter(function(k) {
                return specs[k] && specs[k] !== null && specs[k] !== 'null';
            });
            
            var specHtml = '';
            var displaySpecs = specKeys.slice(0, 2);
            $.each(displaySpecs, function(i, key) {
                specHtml += '<span class="spec-item">' + key.replace(/_/g, ' ') + ': ' + specs[key] + '</span>';
            });
            if (specKeys.length > 2) {
                specHtml += '<span class="spec-item">+' + (specKeys.length - 2) + ' more</span>';
            }
            
            html +=
                '<div class="amenity-list-item ' + (isSelected ? 'selected' : '') + '" data-asset-id="' + asset.asset_id + '">' +
                    '<div class="list-item-left">' +
                        '<div class="list-check" onclick="event.stopPropagation(); toggleAmenity(\'' + asset.asset_id + '\')">' + 
                            (isSelected ? '<i class="fas fa-check" style="color:white;font-size:10px;"></i>' : '') + 
                        '</div>' +
                        '<div class="list-icon"><i class="fas fa-cube"></i></div>' +
                        '<div class="list-info">' +
                            '<div class="list-name">' + (asset.asset_name || 'Unnamed Asset') + '</div>' +
                            '<div class="list-id">' + (asset.asset_id || 'N/A') + '</div>' +
                            '<div class="list-specs">' + specHtml + '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="list-item-right">' +
                        '<div class="list-actions">' +
                            '<button class="btn btn-sm btn-outline-purple view-detail-btn" onclick="event.stopPropagation(); viewAmenityDetail(\'' + asset.asset_id + '\')" title="View Details">' +
                                '<i class="fas fa-eye"></i>' +
                            '</button>' +
                            '<a href="{{ url("/institute/admin/specifications") }}/' + asset.asset_id + '" class="btn btn-sm btn-outline-primary edit-specs-btn" onclick="event.stopPropagation();" title="Edit Specs">' +
                                '<i class="fas fa-cog"></i>' +
                            '</a>' +
                            '<button class="btn btn-sm ' + (isSelected ? 'btn-danger' : 'btn-purple') + ' select-btn" onclick="event.stopPropagation(); toggleAmenity(\'' + asset.asset_id + '\')">' +
                                '<i class="fas ' + (isSelected ? 'fa-times' : 'fa-plus') + '"></i>' +
                            '</button>' +
                        '</div>' +
                    '</div>' +
                '</div>';
        });
        
        list.html(html);
    }

    // ==========================================
    // VIEW ASSET DETAIL
    // ==========================================
    window.viewAmenityDetail = function(assetId) {
        var asset = assets.find(function(a) { return a.asset_id === assetId; });
        if (!asset) return;
        
        var specs = asset.specifications || {};
        var isSelected = selectedAssets.some(function(s) {
            return s.asset_id === assetId;
        });
        
        $('#detailModalTitle').text(asset.asset_name || 'Asset Details');
        
        var specHtml = '';
        var specKeys = Object.keys(specs).filter(function(k) {
            return specs[k] && specs[k] !== null && specs[k] !== 'null';
        });
        $.each(specKeys, function(i, key) {
            specHtml +=
                '<tr>' +
                    '<td class="fw-semibold text-muted" style="width: 40%;">' + key.replace(/_/g, ' ').toUpperCase() + '</td>' +
                    '<td>' + specs[key] + '</td>' +
                '</tr>';
        });
        
        $('#detailModalBody').html(
            '<div class="row mb-3">' +
                '<div class="col-md-6">' +
                    '<div class="p-3 bg-light rounded">' +
                        '<small class="text-muted">Asset ID</small>' +
                        '<p class="fw-bold mb-0">' + (asset.asset_id || 'N/A') + '</p>' +
                    '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                    '<div class="p-3 bg-light rounded">' +
                        '<small class="text-muted">Category</small>' +
                        '<p class="fw-bold mb-0">' + categoryName + '</p>' +
                    '</div>' +
                '</div>' +
            '</div>' +
            '<h6 class="fw-bold mt-3"><i class="fas fa-list-ul me-2" style="color: var(--primary-color);"></i>Specifications</h6>' +
            (specHtml ? 
                '<div class="table-responsive"><table class="table table-sm table-bordered"><tbody>' + specHtml + '</tbody></table></div>' :
                '<p class="text-muted">No specifications available for this asset.</p>'
            )
        );
        
        $('#detailSelectBtn')
            .html('<i class="fas ' + (isSelected ? 'fa-times' : 'fa-plus-circle') + ' me-2"></i>' + (isSelected ? 'Remove from Selection' : 'Select This Asset'))
            .off('click')
            .on('click', function() {
                toggleAmenity(assetId);
                $('#amenityDetailModal').modal('hide');
            });
        
        $('#amenityDetailModal').modal('show');
    };

    // ==========================================
    // SYNC TO AMENITIES
    // ==========================================
    $('#syncAmenitiesBtn').on('click', function() {
        if (selectedAssets.length === 0) {
            showSyncResult('warning', 'No Assets Selected', 'Please select at least one asset to sync.', false);
            return;
        }
        
        var assetIds = selectedAssets.map(function(a) { return a.asset_id; });
        
        $('#syncAmenitiesBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Syncing...');
        
        $.ajax({
            url: '{{ route("institute.admin.amenities.sync") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                asset_ids: assetIds
            },
            success: function(response) {
                if (response.success) {
                    var message = response.message || 'Amenities synced successfully!';
                    var addedCount = response.added_count || 0;
                    var skippedCount = response.skipped_count || 0;
                    var totalActive = response.total_active || 0;
                    
                    var details = '';
                    if (addedCount > 0) {
                        details += '<p><i class="fas fa-check-circle text-success"></i> ' + addedCount + ' new amenity(ies) added.</p>';
                    }
                    if (skippedCount > 0) {
                        details += '<p><i class="fas fa-info-circle text-warning"></i> ' + skippedCount + ' asset(s) already existed.</p>';
                    }
                    details += '<p><i class="fas fa-cubes text-primary"></i> Total amenities: ' + totalActive + '</p>';
                    
                    showSyncResult('success', 'Sync Completed Successfully!', message + '<br><br>' + details, true);
                    
                    // Update counts
                    if (response.total_active !== undefined) {
                        $('#selectedCount').text(selectedAssets.length);
                        $('#pendingCount').text(assets.length - selectedAssets.length);
                    }
                } else {
                    showSyncResult('error', 'Sync Failed', response.message || 'Failed to sync assets.', false);
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON?.message || 'An error occurred while syncing.';
                showSyncResult('error', 'Sync Failed', message, false);
            },
            complete: function() {
                $('#syncAmenitiesBtn').prop('disabled', false).html('<i class="fas fa-sync-alt me-2"></i>Sync Selected to Amenities');
            }
        });
    });

    // ==========================================
    // SHOW SYNC RESULT MODAL
    // ==========================================
    function showSyncResult(type, title, message, redirect) {
        var iconClass = type === 'success' ? 'success' : (type === 'warning' ? 'warning' : 'error');
        var icon = type === 'success' ? 'fa-check-circle' : (type === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle');
        var headerColor = type === 'success' ? 'var(--success-gradient)' : (type === 'warning' ? 'var(--warning-gradient)' : 'var(--danger-gradient)');
        var btnClass = type === 'success' ? 'btn-success' : (type === 'warning' ? 'btn-warning' : 'btn-danger');
        var btnText = redirect ? 'Go to Amenities' : 'Close';
        var redirectUrl = '{{ route("institute.admin.amenities.index") }}';
        
        $('#syncModalHeader').css('background', headerColor);
        $('#syncModalHeader .modal-title').html('<i class="fas ' + icon + ' me-2"></i>' + title);
        
        $('#syncModalBody').html(
            '<div class="modal-icon ' + iconClass + '">' +
                '<i class="fas ' + icon + '"></i>' +
            '</div>' +
            '<h5 class="fw-bold">' + title + '</h5>' +
            '<p class="text-muted">' + message + '</p>'
        );
        
        var footerHtml = '';
        if (redirect) {
            footerHtml +=
                '<a href="' + redirectUrl + '" class="btn ' + btnClass + '">' +
                    '<i class="fas fa-arrow-right me-2"></i>' + btnText +
                '</a>' +
                '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">' +
                    '<i class="fas fa-times me-2"></i>Stay Here' +
                '</button>';
        } else {
            footerHtml +=
                '<button type="button" class="btn ' + btnClass + '" data-bs-dismiss="modal">' +
                    '<i class="fas fa-times me-2"></i>' + btnText +
                '</button>';
        }
        $('#syncModalFooter').html(footerHtml);
        
        $('#syncResultModal').modal('show');
    }

    // ==========================================
    // TOAST NOTIFICATION
    // ==========================================
    function showToast(type, message) {
        var toast = $('#liveToast');
        var icon = type === 'success' ? 'fa-check-circle' : 
                   type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';
        var color = type === 'success' ? '#10b981' : 
                    type === 'warning' ? '#f59e0b' : '#ef4444';
        
        toast.find('.toast-header i')
            .attr('class', 'fas ' + icon + ' me-2')
            .css('color', color);
        toast.find('.toast-body').text(message);
        
        var bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 4000
        });
        bsToast.show();
    }

    // ==========================================
    // EVENT HANDLERS
    // ==========================================
    $('#loadMoreBtn').on('click', function() {
        currentPage++;
        renderAssets(filteredAssets);
    });

    $('#applyFilters').on('click', function() {
        applyFilters();
    });

    var searchTimeout;
    $('#searchAsset').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            applyFilters();
        }, 300);
    });

    $('#sortAssets').on('change', function() {
        applyFilters();
    });

    // ==========================================
    // KEYBOARD SHORTCUTS
    // ==========================================
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            $('#searchAsset').focus();
        }
        if (e.key === 'Escape') {
            $('#searchAsset').val('');
            applyFilters();
        }
        if (e.altKey && e.key === '1') {
            e.preventDefault();
            $('.btn-view[data-view="grid"]').click();
        }
        if (e.altKey && e.key === '2') {
            e.preventDefault();
            $('.btn-view[data-view="list"]').click();
        }
    });

    // ==========================================
    // INIT
    // ==========================================
    updateStats();
    applyFilters();
});
</script>

</body>
</html>
@endsection