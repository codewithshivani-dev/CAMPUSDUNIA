@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Amenities Management</title>
    
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
            padding: 25px 30px;
            max-width: 1600px;
            margin: 0 auto;
        }

        .page-header {
            background: var(--primary-gradient);
            padding: 30px 35px;
            border-radius: 16px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(67,97,238,0.3);
        }

        .page-header h1 {
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 2rem;
        }

        .page-header p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 1rem;
        }

        .header-stats {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .header-stats .stat-item {
            background: rgba(255,255,255,0.15);
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
        }

        .header-stats .stat-item i {
            margin-right: 8px;
        }

        .header-stats .stat-item strong {
            font-size: 1.1rem;
        }

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

        .category-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .category-tab {
            padding: 10px 22px;
            border-radius: 30px;
            background: white;
            border: 2px solid var(--border-color);
            color: #4a5568;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .category-tab:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67,97,238,0.15);
        }

        .category-tab.active {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 15px rgba(67,97,238,0.3);
        }

        .category-tab .badge {
            background: rgba(0,0,0,0.08);
            color: inherit;
            font-size: 0.75rem;
            padding: 2px 10px;
            border-radius: 20px;
        }

        .category-tab.active .badge {
            background: rgba(255,255,255,0.2);
            color: white;
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
            cursor: pointer;
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

        .selected-amenities-sidebar {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            position: sticky;
            top: 20px;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
        }

        .selected-amenities-sidebar .sidebar-header {
            font-weight: 700;
            color: var(--text-dark);
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .selected-amenities-sidebar .selected-item {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .selected-amenities-sidebar .selected-item .item-name {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .selected-amenities-sidebar .selected-item .item-remove {
            color: #e53e3e;
            cursor: pointer;
            padding: 0 8px;
            transition: all 0.3s ease;
        }

        .selected-amenities-sidebar .selected-item .item-remove:hover {
            transform: scale(1.2);
        }

        .selected-amenities-sidebar .empty-selection {
            text-align: center;
            padding: 40px 20px;
            color: #a0aec0;
        }

        .selected-amenities-sidebar .empty-selection i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .selected-amenities-sidebar .btn-submit {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .selected-amenities-sidebar .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(67,97,238,0.4);
            color: white;
        }

        .selected-amenities-sidebar .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
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

        .toast-container {
            z-index: 9999;
        }

        .loading-spinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.85);
            z-index: 9998;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner .spinner-border {
            width: 3.5rem;
            height: 3.5rem;
            color: var(--primary-color);
        }

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

        .back-btn {
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            transform: translateX(-5px);
        }

        .badge.bg-purple {
            background: var(--primary-color);
            color: white;
        }

        @media (max-width: 992px) {
            .main-container {
                padding: 15px;
            }
            .page-header {
                padding: 20px;
            }
            .page-header h1 {
                font-size: 1.5rem;
            }
            .header-stats {
                gap: 15px;
            }
            .header-stats .stat-item {
                font-size: 0.8rem;
                padding: 6px 15px;
            }
            .amenities-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .filter-section .row > div {
                margin-bottom: 10px;
            }
            .category-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 10px;
                gap: 8px;
            }
            .category-tab {
                white-space: nowrap;
                font-size: 0.8rem;
                padding: 8px 16px;
            }
            .amenities-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            .selected-amenities-sidebar {
                position: relative;
                top: 0;
                margin-top: 20px;
                max-height: 400px;
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
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
        }

        .selected-amenities-sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .selected-amenities-sidebar::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 10px;
        }

        .selected-amenities-sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="loading-spinner" id="loadingSpinner">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container-fluid main-container">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('institute.admin.asset-categories.index') }}" class="btn btn-outline-purple back-btn">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>

            <div class="page-header">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div>
                        <h1>
                            <i class="fas fa-concierge-bell me-3"></i>Amenities Selection
                        </h1>
                        <p>Browse and select amenities from available assets to include in your package</p>
                        <div class="header-stats">
                            <span class="stat-item">
                                <i class="fas fa-tags"></i>
                                <strong id="totalCategories">0</strong> Categories
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-boxes"></i>
                                <strong id="totalAmenities">0</strong> Total Amenities
                            </span>
                            <span class="stat-item" style="background: rgba(255,255,255,0.25);">
                                <i class="fas fa-check-circle"></i>
                                <strong id="selectedCount">0</strong> Selected
                            </span>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <button class="btn btn-light" onclick="window.location.reload();">
                            <i class="fas fa-sync-alt me-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>

            <div class="stats-row">
                <div class="stats-card purple">
                    <div class="stats-icon"><i class="fas fa-folder"></i></div>
                    <div class="stats-number" id="statCategories">0</div>
                    <div class="stats-label">Categories</div>
                </div>
                <div class="stats-card green">
                    <div class="stats-icon"><i class="fas fa-cube"></i></div>
                    <div class="stats-number" id="statAssets">0</div>
                    <div class="stats-label">Total Assets</div>
                </div>
                <div class="stats-card orange">
                    <div class="stats-icon"><i class="fas fa-check-double"></i></div>
                    <div class="stats-number" id="statSelected">0</div>
                    <div class="stats-label">Selected</div>
                </div>
                <div class="stats-card red">
                    <div class="stats-icon"><i class="fas fa-clock"></i></div>
                    <div class="stats-number" id="statPending">0</div>
                    <div class="stats-label">Pending Selection</div>
                </div>
            </div>

            <div class="filter-section">
                <div class="row align-items-end g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-1">
                            <i class="fas fa-search me-1"></i>Search
                        </label>
                        <input type="text" class="form-control" id="searchAmenity" 
                               placeholder="Search by name, ID, or category...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-1">
                            <i class="fas fa-filter me-1"></i>Filter by Category
                        </label>
                        <select class="form-select" id="filterCategory">
                            <option value="all">All Categories</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-1">
                            <i class="fas fa-sort me-1"></i>Sort By
                        </label>
                        <select class="form-select" id="sortAmenities">
                            <option value="name">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="category">Category</option>
                            <option value="id">Asset ID</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-purple w-100" id="applyFilters">
                            <i class="fas fa-sliders-h me-2"></i>Apply
                        </button>
                    </div>
                </div>
            </div>

            <div class="category-tabs" id="categoryTabs">
                <a href="javascript:void(0)" class="category-tab active" data-category="all">
                    <i class="fas fa-th-large"></i> All Categories
                    <span class="badge" id="totalBadge">0</span>
                </a>
                <!-- Category tabs will be dynamically added here -->
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="amenities-grid" id="amenitiesGrid">
                        <!-- Amenities will be rendered here -->
                    </div>
                    
                    <div class="text-center mt-4">
                        <button class="btn btn-outline-purple" id="loadMoreBtn" style="display:none;">
                            <i class="fas fa-plus-circle me-2"></i>Load More
                        </button>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="selected-amenities-sidebar" id="selectedSidebar">
                        <div class="sidebar-header">
                            <span>
                                <i class="fas fa-shopping-cart me-2" style="color: var(--primary-color);"></i>
                                Selected Amenities
                            </span>
                            <span class="badge bg-purple text-white" id="sidebarCount">0</span>
                        </div>
                        <div id="selectedList">
                            <div class="empty-selection">
                                <i class="fas fa-hand-pointer"></i>
                                <p class="mb-0">No amenities selected yet.</p>
                                <p class="small text-muted">Click on an amenity card to select it.</p>
                            </div>
                        </div>
                        <button class="btn-submit" id="submitAmenities" disabled>
                            <i class="fas fa-check-circle me-2"></i>Confirm Selection
                            <span id="submitCount" class="ms-1">(0)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Amenity Detail Modal --}}
<div class="modal fade" id="amenityDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    <span id="detailModalTitle">Amenity Details</span>
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
                    <i class="fas fa-plus-circle me-2"></i>Select This Amenity
                </button>
            </div>
        </div>
    </div>
</div>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    var amenitiesData = [];
    var selectedAmenities = [];
    var currentCategory = 'all';
    var currentPage = 1;
    var itemsPerPage = 12;
    var filteredData = [];

    // ==========================================
    // LOAD AMENITIES
    // ==========================================
    function loadAmenities() {
        $('#loadingSpinner').fadeIn();
        
        $.ajax({
            url: '{{ route("institute.admin.amenities.data") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    amenitiesData = response.data;
                    
                    // Restore selected amenities from database
                    if (response.selected_amenities && response.selected_amenities.length > 0) {
                        var allAmenities = getAllAmenities();
                        selectedAmenities = allAmenities.filter(function(a) {
                            return response.selected_amenities.includes(a.asset_id);
                        });
                    } else {
                        selectedAmenities = [];
                    }
                    
                    // Update stats with the response data
                    if (response.total_categories !== undefined) {
                        $('#totalCategories').text(response.total_categories);
                    }
                    if (response.total_amenities !== undefined) {
                        $('#totalAmenities').text(response.total_amenities);
                    }
                    if (response.total_selected !== undefined) {
                        $('#selectedCount').text(response.total_selected);
                    }
                    
                    initializeAmenities();
                    renderSelectedAmenities();
                } else {
                    showToast('error', response.message || 'Failed to load amenities data.');
                }
            },
            error: function(xhr) {
                showToast('error', 'Unable to fetch amenities. Please refresh the page.');
                console.error('Error fetching amenities:', xhr);
            },
            complete: function() {
                $('#loadingSpinner').fadeOut();
            }
        });
    }

    function initializeAmenities() {
        updateStats();
        renderCategoryTabs();
        populateCategoryFilter();
        applyFiltersAndRender();
    }

    function getAllAmenities() {
        var all = [];
        $.each(amenitiesData, function(index, category) {
            if (category.assets) {
                $.each(category.assets, function(i, asset) {
                    all.push({
                        id: asset.id || i,
                        asset_id: asset.asset_id,
                        asset_name: asset.asset_name,
                        specifications: asset.specifications || {},
                        created_at: asset.created_at,
                        updated_at: asset.updated_at,
                        category_id: category.category_id,
                        category_name: category.name
                    });
                });
            }
        });
        return all;
    }

    function updateStats() {
        var allAmenities = getAllAmenities();
        var total = allAmenities.length;
        var selected = selectedAmenities.length;
        var categories = amenitiesData.length;
        
        $('#totalCategories').text(categories);
        $('#totalAmenities').text(total);
        $('#selectedCount').text(selected);
        $('#totalBadge').text(total);
        
        $('#statCategories').text(categories);
        $('#statAssets').text(total);
        $('#statSelected').text(selected);
        $('#statPending').text(total - selected);
        $('#sidebarCount').text(selected);
        $('#submitCount').text('(' + selected + ')');
        
        $('#submitAmenities').prop('disabled', selected === 0);
    }

    function populateCategoryFilter() {
        var filter = $('#filterCategory');
        $.each(amenitiesData, function(index, cat) {
            filter.append('<option value="' + cat.category_id + '">' + cat.name + '</option>');
        });
    }

    // ==========================================
    // RENDER CATEGORY TABS (WITH NAVIGATION)
    // ==========================================
    function renderCategoryTabs() {
        var container = $('#categoryTabs');
        // Keep the "All" tab
        container.find('.category-tab:not([data-category="all"])').remove();
        
        $.each(amenitiesData, function(index, cat) {
            var assetCount = cat.assets ? cat.assets.length : 0;
            // Create a link that navigates to the category assets page
            container.append(
                '<a href="' + '{{ url("/institute/admin/asset-categories") }}/' + cat.id + '/assets" class="category-tab" data-category="' + cat.category_id + '">' +
                    '<i class="fas fa-folder"></i> ' + cat.name +
                    '<span class="badge">' + assetCount + '</span>' +
                '</a>'
            );
        });
        
        // Also make the "All" tab go to the categories page
        $('.category-tab[data-category="all"]').attr('href', '{{ route("institute.admin.asset-categories.index") }}');
    }

    function applyFiltersAndRender() {
        var searchTerm = $('#searchAmenity').val().toLowerCase().trim();
        var categoryFilter = $('#filterCategory').val();
        var sortBy = $('#sortAmenities').val();
        
        var allAmenities = getAllAmenities();
        
        if (currentCategory !== 'all') {
            allAmenities = allAmenities.filter(function(a) {
                return a.category_id === currentCategory;
            });
        }
        
        if (categoryFilter !== 'all') {
            allAmenities = allAmenities.filter(function(a) {
                return a.category_id === categoryFilter;
            });
        }
        
        if (searchTerm) {
            allAmenities = allAmenities.filter(function(a) {
                return (a.asset_name || '').toLowerCase().includes(searchTerm) ||
                       (a.asset_id || '').toLowerCase().includes(searchTerm) ||
                       (a.category_name || '').toLowerCase().includes(searchTerm);
            });
        }
        
        switch(sortBy) {
            case 'name':
                allAmenities.sort(function(a, b) {
                    return (a.asset_name || '').localeCompare(b.asset_name || '');
                });
                break;
            case 'name_desc':
                allAmenities.sort(function(a, b) {
                    return (b.asset_name || '').localeCompare(a.asset_name || '');
                });
                break;
            case 'category':
                allAmenities.sort(function(a, b) {
                    return (a.category_name || '').localeCompare(b.category_name || '');
                });
                break;
            case 'id':
                allAmenities.sort(function(a, b) {
                    return (a.asset_id || '').localeCompare(b.asset_id || '');
                });
                break;
        }
        
        filteredData = allAmenities;
        renderAmenities(allAmenities);
        updateStats();
    }

    function renderAmenities(amenities) {
        var grid = $('#amenitiesGrid');
        var start = 0;
        var end = currentPage * itemsPerPage;
        var pageItems = amenities.slice(start, end);
        
        if (pageItems.length === 0) {
            grid.html(
                '<div class="col-12 text-center py-5" style="grid-column: 1 / -1;">' +
                    '<i class="fas fa-search" style="font-size: 3rem; color: #a0aec0; margin-bottom: 20px;"></i>' +
                    '<h5 class="fw-bold text-muted">No amenities found</h5>' +
                    '<p class="text-muted">Try adjusting your search or filter criteria</p>' +
                '</div>'
            );
            $('#loadMoreBtn').hide();
            return;
        }
        
        var html = '';
        $.each(pageItems, function(index, asset) {
            var isSelected = selectedAmenities.some(function(s) {
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
                    '<div class="amenity-check">' + (isSelected ? '<i class="fas fa-check"></i>' : '') + '</div>' +
                    '<div class="amenity-icon"><i class="fas fa-cube"></i></div>' +
                    '<div class="amenity-name">' + (asset.asset_name || 'Unnamed Asset') + '</div>' +
                    '<div class="amenity-id">' + (asset.asset_id || 'N/A') + ' • ' + (asset.category_name || 'No Category') + '</div>' +
                    '<div class="amenity-specs">' + specHtml + '</div>' +
                    '<div class="amenity-actions">' +
                        '<button class="btn btn-sm btn-outline-purple view-detail-btn" onclick="event.stopPropagation(); viewAmenityDetail(\'' + asset.asset_id + '\')">' +
                            '<i class="fas fa-eye me-1"></i>View' +
                        '</button>' +
                        '<button class="btn btn-sm ' + (isSelected ? 'btn-danger' : 'btn-purple') + ' select-btn" onclick="event.stopPropagation(); toggleAmenity(\'' + asset.asset_id + '\')">' +
                            '<i class="fas ' + (isSelected ? 'fa-times' : 'fa-plus') + ' me-1"></i>' +
                            (isSelected ? 'Remove' : 'Select') +
                        '</button>' +
                    '</div>' +
                '</div>';
        });
        
        grid.html(html);
        
        if (end < amenities.length) {
            $('#loadMoreBtn').show();
        } else {
            $('#loadMoreBtn').hide();
        }
    }

    // ==========================================
    // AMENITY SELECTION
    // ==========================================
    window.toggleAmenity = function(assetId) {
        var allAmenities = getAllAmenities();
        var asset = null;
        $.each(allAmenities, function(i, a) {
            if (a.asset_id === assetId) {
                asset = a;
                return false;
            }
        });
        if (!asset) return;
        
        var index = -1;
        $.each(selectedAmenities, function(i, s) {
            if (s.asset_id === assetId) {
                index = i;
                return false;
            }
        });
        
        if (index > -1) {
            selectedAmenities.splice(index, 1);
            showToast('info', 'Removed "' + asset.asset_name + '" from selection');
        } else {
            selectedAmenities.push(asset);
            showToast('success', 'Added "' + asset.asset_name + '" to selection');
        }
        
        applyFiltersAndRender();
        renderSelectedAmenities();
    };

    function renderSelectedAmenities() {
        var container = $('#selectedList');
        var sidebar = $('#selectedSidebar');
        
        if (selectedAmenities.length === 0) {
            container.html(
                '<div class="empty-selection">' +
                    '<i class="fas fa-hand-pointer"></i>' +
                    '<p class="mb-0">No amenities selected yet.</p>' +
                    '<p class="small text-muted">Click on an amenity card to select it.</p>' +
                '</div>'
            );
            sidebar.find('.btn-submit').prop('disabled', true);
            return;
        }
        
        var html = '';
        $.each(selectedAmenities, function(index, asset) {
            html +=
                '<div class="selected-item">' +
                    '<div>' +
                        '<div class="item-name">' + (asset.asset_name || 'Unnamed') + '</div>' +
                        '<small class="text-muted">' + (asset.asset_id || '') + ' • ' + (asset.category_name || '') + '</small>' +
                    '</div>' +
                    '<span class="item-remove" onclick="toggleAmenity(\'' + asset.asset_id + '\')" title="Remove">' +
                        '<i class="fas fa-times-circle"></i>' +
                    '</span>' +
                '</div>';
        });
        
        container.html(html);
        sidebar.find('.btn-submit').prop('disabled', false);
        updateStats();
    }

    // ==========================================
    // VIEW AMENITY DETAIL
    // ==========================================
    window.viewAmenityDetail = function(assetId) {
        var allAmenities = getAllAmenities();
        var asset = null;
        $.each(allAmenities, function(i, a) {
            if (a.asset_id === assetId) {
                asset = a;
                return false;
            }
        });
        if (!asset) return;
        
        var specs = asset.specifications || {};
        var isSelected = selectedAmenities.some(function(s) {
            return s.asset_id === assetId;
        });
        
        $('#detailModalTitle').text(asset.asset_name || 'Amenity Details');
        
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
                        '<p class="fw-bold mb-0">' + (asset.category_name || 'N/A') + ' (' + (asset.category_id || '') + ')</p>' +
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
            .html('<i class="fas ' + (isSelected ? 'fa-times' : 'fa-plus-circle') + ' me-2"></i>' + (isSelected ? 'Remove from Selection' : 'Select This Amenity'))
            .off('click')
            .on('click', function() {
                toggleAmenity(assetId);
                $('#amenityDetailModal').modal('hide');
            });
        
        $('#amenityDetailModal').modal('show');
    };

    // ==========================================
    // EVENT HANDLERS
    // ==========================================
    $('#loadMoreBtn').on('click', function() {
        currentPage++;
        renderAmenities(filteredData);
    });

    $('#applyFilters').on('click', function() {
        currentPage = 1;
        applyFiltersAndRender();
    });

    var searchTimeout;
    $('#searchAmenity').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            currentPage = 1;
            applyFiltersAndRender();
        }, 300);
    });

    $('#filterCategory, #sortAmenities').on('change', function() {
        currentPage = 1;
        applyFiltersAndRender();
    });

    $('#submitAmenities').on('click', function() {
        if (selectedAmenities.length === 0) {
            showToast('warning', 'Please select at least one amenity.');
            return;
        }
        
        $('#loadingSpinner').fadeIn();
        
        var assetIds = selectedAmenities.map(function(a) { return a.asset_id; });
        
        $.ajax({
            url: '{{ route("institute.admin.amenities.save") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                amenities: assetIds
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', response.message || 'Amenities saved successfully!');
                } else {
                    showToast('error', response.message || 'Failed to save amenities.');
                }
            },
            error: function(xhr) {
                showToast('error', xhr.responseJSON?.message || 'An error occurred while saving.');
            },
            complete: function() {
                $('#loadingSpinner').fadeOut();
            }
        });
    });

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

    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            $('#searchAmenity').focus();
        }
        if (e.key === 'Escape') {
            $('#searchAmenity').val('');
            applyFiltersAndRender();
        }
    });

    // ==========================================
    // INIT
    // ==========================================
    loadAmenities();
});
</script>

</body>
</html>
@endsection