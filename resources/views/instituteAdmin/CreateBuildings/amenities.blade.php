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

        /* Title row with action buttons pushed to the right */
        .title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 5px;
            width: 100%;
        }

        .page-header h1 {
            font-weight: 700;
            margin: 0;
            font-size: 2rem;
            white-space: nowrap;
        }

        .title-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-left: auto;
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

        /* Category Cards */
        .category-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .category-card {
            background: white;
            border-radius: 14px;
            padding: 22px 18px;
            text-align: center;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .category-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(67,97,238,0.15);
            text-decoration: none;
            color: inherit;
        }

        .category-card .category-icon {
            font-size: 2.8rem;
            color: var(--primary-color);
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .category-card:hover .category-icon {
            transform: scale(1.1);
        }

        .category-card .category-name {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 1.05rem;
            margin-bottom: 6px;
        }

        .category-card .category-id {
            font-size: 0.75rem;
            color: #a0aec0;
            margin-bottom: 10px;
        }

        .category-card .category-count {
            font-size: 0.85rem;
            color: var(--text-muted);
            display: inline-block;
            background: #f7fafc;
            padding: 4px 16px;
            border-radius: 20px;
            margin-bottom: 12px;
        }

        .category-card .category-count .count-number {
            font-weight: 700;
            color: var(--primary-color);
        }

        .category-card .view-btn {
            display: inline-block;
            background: var(--primary-gradient);
            color: white;
            padding: 6px 20px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .category-card:hover .view-btn {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(67,97,238,0.3);
        }

        .category-card .view-btn i {
            margin-right: 5px;
        }

        /* Stats Row */
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

        /* Buttons */
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

        /* Header action buttons (on gradient) */
        .btn-header-light {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.35);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            font-size: 0.9rem;
        }

        .btn-header-light:hover {
            background: rgba(255,255,255,0.35);
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .btn-header-success {
            background: rgba(255,255,255,0.95);
            color: #059669;
            border: 2px solid rgba(255,255,255,0.95);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            font-size: 0.9rem;
        }

        .btn-header-success:hover {
            background: white;
            color: #059669;
            transform: translateY(-2px);
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
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

        .badge.bg-purple {
            background: var(--primary-color);
            color: white;
        }

        /* Selected Amenities Quick View Card */
        .selected-card {
            background: white;
            border-radius: 12px;
            border: 2px solid var(--success-gradient);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .selected-card .card-header {
            background: var(--success-gradient);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }

        .selected-card .card-header h5 {
            margin: 0;
            font-weight: 700;
        }

        .selected-item {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .selected-item:last-child {
            border-bottom: none;
        }

        .selected-item .item-name {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .selected-item .item-category {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .selected-item .item-remove {
            color: #dc3545;
            cursor: pointer;
            padding: 0 5px;
            transition: all 0.3s ease;
        }

        .selected-item .item-remove:hover {
            transform: scale(1.2);
        }

        .empty-selection {
            text-align: center;
            padding: 30px 20px;
            color: #a0aec0;
        }

        .empty-selection i {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        /* Toast */
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

        /* Responsive */
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
            .category-cards {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 15px;
            }
            .category-card {
                padding: 18px 14px;
            }
            .category-card .category-icon {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 768px) {
            .category-cards {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 12px;
            }
            .category-card {
                padding: 15px 10px;
            }
            .category-card .category-icon {
                font-size: 1.8rem;
                margin-bottom: 8px;
            }
            .category-card .category-name {
                font-size: 0.9rem;
            }
            .category-card .view-btn {
                font-size: 0.7rem;
                padding: 4px 14px;
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
            /* Stack on mobile: title on top, buttons below */
            .title-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .title-actions {
                margin-left: 0;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .category-cards {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            .category-card {
                padding: 12px 8px;
            }
            .category-card .category-icon {
                font-size: 1.5rem;
                margin-bottom: 5px;
            }
            .category-card .category-name {
                font-size: 0.8rem;
            }
            .category-card .category-count {
                font-size: 0.7rem;
                padding: 2px 10px;
                margin-bottom: 8px;
            }
            .category-card .view-btn {
                font-size: 0.65rem;
                padding: 3px 10px;
            }
            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            .stats-card .stats-number {
                font-size: 1.4rem;
            }
            .btn-header-light,
            .btn-header-success {
                font-size: 0.75rem;
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid main-container">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="page-header">
                <!-- Title Row with Action Buttons on the Right -->
                <div class="title-row">
                    <h1>
                        <i class="fas fa-concierge-bell me-3"></i>Amenities Management
                    </h1>
                    <div class="title-actions">
                        <a href="{{ route('institute.admin.asset-categories.index') }}" class="btn-header-light">
                            <i class="fas fa-arrow-left"></i>Back to Categories
                        </a>
                        <a href="{{ route('institute.admin.selected.amenities') }}" class="btn-header-success">
                            <i class="fas fa-check-circle"></i>
                            View Selected
                            <span class="badge bg-success text-white ms-1" id="headerSelectedCount">0</span>
                        </a>
                    </div>
                </div>

                <!-- Subtitle -->
                <p>Select amenities from available assets to include in your package</p>

                <!-- Stats -->
                <div class="header-stats">
                    <span class="stat-item">
                        <i class="fas fa-tags"></i>
                        <strong id="totalCategories">0</strong> Categories
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-cubes"></i>
                        <strong id="totalAmenities">0</strong> Total Assets
                    </span>
                    <span class="stat-item" style="background: rgba(255,255,255,0.25);">
                        <i class="fas fa-check-circle"></i>
                        <strong id="selectedCount">0</strong> Selected
                    </span>
                </div>
            </div>

            <!-- Stats -->
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

            <!-- Category Cards -->
            <h5 class="fw-bold mb-3" style="color: var(--text-dark);">
                <i class="fas fa-folder-open me-2" style="color: var(--primary-color);"></i>
                Categories
                <span class="badge bg-purple ms-2" id="categoryCountBadge">0</span>
            </h5>
            <div class="category-cards" id="categoryCards">
                <!-- Categories will be rendered here -->
            </div>

            <!-- Selected Amenities Quick View -->
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="selected-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Selected Amenities
                                <span class="badge bg-light text-dark ms-2" id="sidebarCount">0</span>
                            </h5>
                            <a href="{{ route('institute.admin.selected.amenities') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-eye me-1"></i>View All
                            </a>
                        </div>
                        <div class="card-body" id="selectedList">
                            <div class="empty-selection">
                                <i class="fas fa-hand-pointer"></i>
                                <p class="mb-0">No amenities selected yet.</p>
                                <p class="small text-muted">Click on a category above, then select and sync amenities.</p>
                            </div>
                        </div>
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
    var selectedAmenities = [];

    // ==========================================
    // LOAD AMENITIES
    // ==========================================
    function loadAmenities() {
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
            }
        });
    }

    function initializeAmenities() {
        updateStats();
        renderCategoryCards();
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
                        category_name: category.name,
                        category_asset_id: category.id
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
        $('#categoryCountBadge').text(categories);
        $('#headerSelectedCount').text(selected);
        
        $('#statCategories').text(categories);
        $('#statAssets').text(total);
        $('#statSelected').text(selected);
        $('#statPending').text(total - selected);
        $('#sidebarCount').text(selected);
    }

    // ==========================================
    // RENDER CATEGORY CARDS
    // ==========================================
    function renderCategoryCards() {
        var container = $('#categoryCards');
        var html = '';
        
        // Add "All Categories" card
        var totalAssets = getAllAmenities().length;
        html +=
            '<a href="{{ route("institute.admin.asset-categories.index") }}" class="category-card">' +
                '<div class="category-icon"><i class="fas fa-th-large"></i></div>' +
                '<div class="category-name">All Categories</div>' +
                '<div class="category-id">All</div>' +
                '<div class="category-count"><span class="count-number">' + totalAssets + '</span> Assets</div>' +
                '<span class="view-btn"><i class="fas fa-eye"></i> View All</span>' +
            '</a>';
        
        // Add individual category cards
        $.each(amenitiesData, function(index, cat) {
            var assetCount = cat.assets ? cat.assets.length : 0;
            html +=
                '<a href="{{ url("/institute/admin/category") }}/' + cat.id + '/amenities" class="category-card">' +
                    '<div class="category-icon"><i class="fas fa-folder"></i></div>' +
                    '<div class="category-name">' + cat.name + '</div>' +
                    '<div class="category-id">' + cat.category_id + '</div>' +
                    '<div class="category-count"><span class="count-number">' + assetCount + '</span> Assets</div>' +
                    '<span class="view-btn"><i class="fas fa-eye"></i> View Assets</span>' +
                '</a>';
        });
        
        container.html(html);
    }

    // ==========================================
    // AMENITY SELECTION (for quick view removal)
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
        
        renderSelectedAmenities();
        updateStats();
    };

    function renderSelectedAmenities() {
        var container = $('#selectedList');
        
        if (selectedAmenities.length === 0) {
            container.html(
                '<div class="empty-selection">' +
                    '<i class="fas fa-hand-pointer"></i>' +
                    '<p class="mb-0">No amenities selected yet.</p>' +
                    '<p class="small text-muted">Click on a category above, then select and sync amenities.</p>' +
                '</div>'
            );
            return;
        }
        
        // Show only first 5 selected amenities
        var displayItems = selectedAmenities.slice(0, 5);
        var html = '';
        $.each(displayItems, function(index, asset) {
            html +=
                '<div class="selected-item">' +
                    '<div>' +
                        '<div class="item-name">' + (asset.asset_name || 'Unnamed') + '</div>' +
                        '<div class="item-category"><i class="fas fa-folder me-1"></i>' + (asset.category_name || 'No Category') + '</div>' +
                    '</div>' +
                    '<button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); toggleAmenity(\'' + asset.asset_id + '\')" title="Remove">' +
                        '<i class="fas fa-times"></i>' +
                    '</button>' +
                '</div>';
        });
        
        if (selectedAmenities.length > 5) {
            html +=
                '<div class="text-center mt-2">' +
                    '<a href="{{ route("institute.admin.selected.amenities") }}" class="btn btn-sm btn-outline-purple">' +
                        'View all ' + selectedAmenities.length + ' amenities <i class="fas fa-arrow-right ms-1"></i>' +
                    '</a>' +
                '</div>';
        }
        
        container.html(html);
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
    // INIT
    // ==========================================
    loadAmenities();
});
</script>

</body>
</html>
@endsection