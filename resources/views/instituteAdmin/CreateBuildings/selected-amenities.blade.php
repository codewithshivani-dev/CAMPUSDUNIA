@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Selected Amenities</title>
    
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

        .btn-light {
            background: white;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-light:hover {
            background: #f8fafc;
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

        /* Grid View */
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

        .amenity-card .amenity-category {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .amenity-card .amenity-count {
            display: inline-block;
            background: var(--primary-gradient);
            color: white;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 8px;
        }

        /* List View */
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

        .amenity-list-item .list-item-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            min-width: 0;
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

        .amenity-list-item .list-item-left .list-info .list-category {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .amenity-list-item .list-item-left .list-info .list-count {
            display: inline-block;
            background: var(--primary-gradient);
            color: white;
            padding: 1px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 4px;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        .empty-state h4 {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
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

        /* Hide grid when list is active */
        .view-grid .amenities-grid { display: grid; }
        .view-grid .amenities-list { display: none; }
        .view-list .amenities-grid { display: none; }
        .view-list .amenities-list { display: block; }

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
        }

        @media (max-width: 768px) {
            .filter-section .row > div {
                margin-bottom: 10px;
            }
            .amenities-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
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
        }
    </style>
</head>
<body>

<div class="loading-spinner" id="loadingSpinner">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container-fluid main-container view-grid" id="mainContainer">
    <div class="row">
        <div class="col-12">
            <!-- Back Button -->
            <a href="{{ route('institute.admin.amenities.index') }}" class="btn btn-outline-purple back-btn">
                <i class="fas fa-arrow-left me-2"></i>Back to Amenities
            </a>

            <!-- Page Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div>
                        <h1>
                            <i class="fas fa-check-circle me-3"></i>Selected Amenities
                        </h1>
                        <p>View all amenities selected from different categories</p>
                        <div class="header-stats">
                            <span class="stat-item">
                                <i class="fas fa-cubes"></i>
                                <strong id="totalAmenities">0</strong> Total Amenities
                            </span>
                            <span class="stat-item" style="background: rgba(255,255,255,0.25);">
                                <i class="fas fa-tags"></i>
                                <strong id="totalCategories">0</strong> Categories
                            </span>
                        </div>
                    </div>
                    <div class="mt-2 mt-md-0 d-flex align-items-center gap-3">
                        <button class="btn btn-light" onclick="window.location.reload();">
                            <i class="fas fa-sync-alt me-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="stats-row">
                <div class="stats-card purple">
                    <div class="stats-icon"><i class="fas fa-cube"></i></div>
                    <div class="stats-number" id="statTotal">0</div>
                    <div class="stats-label">Total Amenities</div>
                </div>
                <div class="stats-card green">
                    <div class="stats-icon"><i class="fas fa-tags"></i></div>
                    <div class="stats-number" id="statCategories">0</div>
                    <div class="stats-label">Categories</div>
                </div>
                <div class="stats-card orange">
                    <div class="stats-icon"><i class="fas fa-clock"></i></div>
                    <div class="stats-number" id="statLastAdded">0</div>
                    <div class="stats-label">Last Added</div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row align-items-end g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold mb-1">
                            <i class="fas fa-search me-1"></i>Search Amenities
                        </label>
                        <input type="text" class="form-control" id="searchAmenity" 
                               placeholder="Search by name or ID...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-1">
                            <i class="fas fa-filter me-1"></i>Filter by Category
                        </label>
                        <select class="form-select" id="filterCategory">
                            <option value="all">All Categories</option>
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

            <!-- Amenities Grid & List -->
            <div class="row">
                <div class="col-12">
                    <!-- Grid View -->
                    <div class="amenities-grid" id="amenitiesGrid">
                        <!-- Amenities will be rendered here -->
                    </div>
                    
                    <!-- List View -->
                    <div class="amenities-list" id="amenitiesList">
                        <!-- Amenities will be rendered here -->
                    </div>
                    
                    <div class="text-center mt-4">
                        <button class="btn btn-outline-purple" id="loadMoreBtn" style="display:none;">
                            <i class="fas fa-plus-circle me-2"></i>Load More
                        </button>
                    </div>

                    <!-- Empty State -->
                    <div class="empty-state" id="emptyState" style="display:none;">
                        <i class="fas fa-check-circle"></i>
                        <h4>No Amenities Selected Yet</h4>
                        <p class="text-muted">Go to the Amenities Management page and select amenities from different categories.</p>
                        <a href="{{ route('institute.admin.amenities.index') }}" class="btn btn-purple mt-3">
                            <i class="fas fa-arrow-left me-2"></i>Browse Categories
                        </a>
                    </div>
                </div>
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
    var amenitiesData = [];
    var categoriesData = [];
    var currentView = 'grid';
    var currentPage = 1;
    var itemsPerPage = 12;
    var filteredAmenities = [];

    // ==========================================
    // LOAD AMENITIES
    // ==========================================
    function loadAmenities() {
        $('#loadingSpinner').fadeIn();
        
        $.ajax({
            url: '{{ route("institute.admin.selected.amenities.data") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    amenitiesData = response.data || [];
                    categoriesData = response.categories || [];
                    populateCategoryFilter();
                    updateStats();
                    applyFiltersAndRender();
                    
                    if (amenitiesData.length === 0) {
                        $('#emptyState').show();
                        $('#amenitiesGrid').hide();
                        $('#amenitiesList').hide();
                    } else {
                        $('#emptyState').hide();
                        if (currentView === 'grid') {
                            $('#amenitiesGrid').show();
                            $('#amenitiesList').hide();
                        } else {
                            $('#amenitiesGrid').hide();
                            $('#amenitiesList').show();
                        }
                    }
                } else {
                    showToast('error', response.message || 'Failed to load amenities.');
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

    // ==========================================
    // POPULATE CATEGORY FILTER
    // ==========================================
    function populateCategoryFilter() {
        var filter = $('#filterCategory');
        filter.find('option:not([value="all"])').remove();
        $.each(categoriesData, function(index, cat) {
            filter.append('<option value="' + cat.category_id + '">' + cat.name + '</option>');
        });
    }

    // ==========================================
    // UPDATE STATS
    // ==========================================
    function updateStats() {
        var total = amenitiesData.length;
        var categories = new Set();
        $.each(amenitiesData, function(index, amenity) {
            if (amenity.category_id) {
                categories.add(amenity.category_id);
            }
        });
        
        $('#statTotal').text(total);
        $('#statCategories').text(categories.size);
        $('#totalAmenities').text(total);
        $('#totalCategories').text(categories.size);
        
        if (total > 0) {
            var lastAdded = amenitiesData[0]?.created_at;
            if (lastAdded) {
                var date = new Date(lastAdded);
                $('#statLastAdded').text(date.toLocaleDateString());
            } else {
                $('#statLastAdded').text('N/A');
            }
        } else {
            $('#statLastAdded').text('N/A');
        }
    }

    // ==========================================
    // VIEW TOGGLE
    // ==========================================
    $('.btn-view').on('click', function() {
        var view = $(this).data('view');
        if (view === currentView) return;
        
        $('.btn-view').removeClass('active');
        $(this).addClass('active');
        currentView = view;
        
        var container = $('#mainContainer');
        container.removeClass('view-grid view-list');
        container.addClass('view-' + view);
        
        if (view === 'grid') {
            $('#amenitiesGrid').show();
            $('#amenitiesList').hide();
        } else {
            $('#amenitiesGrid').hide();
            $('#amenitiesList').show();
        }
        
        renderAmenities(filteredAmenities);
    });

    // ==========================================
    // APPLY FILTERS & RENDER
    // ==========================================
    function applyFiltersAndRender() {
        var searchTerm = $('#searchAmenity').val().toLowerCase().trim();
        var categoryFilter = $('#filterCategory').val();
        
        var filtered = amenitiesData.slice();
        
        if (categoryFilter !== 'all') {
            filtered = filtered.filter(function(a) {
                return a.category_id === categoryFilter;
            });
        }
        
        if (searchTerm) {
            filtered = filtered.filter(function(a) {
                return (a.name || '').toLowerCase().includes(searchTerm) ||
                       (a.amenity_id || '').toLowerCase().includes(searchTerm) ||
                       (a.asset_id || '').toLowerCase().includes(searchTerm);
            });
        }
        
        filteredAmenities = filtered;
        currentPage = 1;
        renderAmenities(filtered);
    }

    // ==========================================
    // RENDER AMENITIES
    // ==========================================
    function renderAmenities(amenities) {
        var start = 0;
        var end = currentPage * itemsPerPage;
        var pageItems = amenities.slice(start, end);
        
        renderGridView(pageItems);
        renderListView(pageItems);
        
        if (end < amenities.length) {
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
                    '<h5 class="fw-bold text-muted">No amenities found</h5>' +
                    '<p class="text-muted">Try adjusting your search or filter criteria</p>' +
                '</div>'
            );
            return;
        }
        
        var html = '';
        $.each(pageItems, function(index, amenity) {
            var count = amenity.count || 1;
            var categoryName = amenity.category_name || 'Uncategorized';
            
            html +=
                '<div class="amenity-card" data-amenity-id="' + amenity.amenity_id + '">' +
                    '<div class="amenity-icon"><i class="fas fa-cube"></i></div>' +
                    '<div class="amenity-name">' + (amenity.name || 'Unnamed Amenity') + '</div>' +
                    '<div class="amenity-id">' + (amenity.amenity_id || 'N/A') + ' • ' + (amenity.asset_id || 'N/A') + '</div>' +
                    '<div class="amenity-category"><i class="fas fa-folder me-1"></i>' + categoryName + '</div>' +
                    '<div class="amenity-count"><i class="fas fa-hashtag me-1"></i>' + count + '</div>' +
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
                    '<h5 class="fw-bold text-muted">No amenities found</h5>' +
                    '<p class="text-muted">Try adjusting your search or filter criteria</p>' +
                '</div>'
            );
            return;
        }
        
        var html = '';
        $.each(pageItems, function(index, amenity) {
            var count = amenity.count || 1;
            var categoryName = amenity.category_name || 'Uncategorized';
            
            html +=
                '<div class="amenity-list-item" data-amenity-id="' + amenity.amenity_id + '">' +
                    '<div class="list-item-left">' +
                        '<div class="list-icon"><i class="fas fa-cube"></i></div>' +
                        '<div class="list-info">' +
                            '<div class="list-name">' + (amenity.name || 'Unnamed Amenity') + '</div>' +
                            '<div class="list-id">' + (amenity.amenity_id || 'N/A') + ' • ' + (amenity.asset_id || 'N/A') + '</div>' +
                            '<div class="list-category"><i class="fas fa-folder me-1"></i>' + categoryName + '</div>' +
                            '<span class="list-count"><i class="fas fa-hashtag me-1"></i>' + count + '</span>' +
                        '</div>' +
                    '</div>' +
                '</div>';
        });
        
        list.html(html);
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

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    // ==========================================
    // EVENT HANDLERS
    // ==========================================
    $('#loadMoreBtn').on('click', function() {
        currentPage++;
        renderAmenities(filteredAmenities);
    });

    $('#applyFilters').on('click', function() {
        applyFiltersAndRender();
    });

    var searchTimeout;
    $('#searchAmenity').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            applyFiltersAndRender();
        }, 300);
    });

    $('#filterCategory').on('change', function() {
        applyFiltersAndRender();
    });

    // ==========================================
    // KEYBOARD SHORTCUTS
    // ==========================================
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            $('#searchAmenity').focus();
        }
        if (e.key === 'Escape') {
            $('#searchAmenity').val('');
            applyFiltersAndRender();
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
    loadAmenities();
});
</script>

</body>
</html>
@endsection