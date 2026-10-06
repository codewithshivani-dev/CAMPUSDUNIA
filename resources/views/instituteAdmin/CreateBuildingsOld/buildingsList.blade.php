@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Buildings - Building Infrastructure</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        .header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-content h1 {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-content h1 i {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 12px;
        }

        .header-content p {
            opacity: 0.9;
            font-size: 1rem;
            margin: 0;
        }

        .list-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            border: 2px solid var(--border-color);
        }

        .list-section h2 {
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .list-section h2 i {
            color: var(--primary-color);
        }

        .search-box {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 45px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f8fafc;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            background: white;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .stats-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .stat-item {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 12px 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-item i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .stat-item span {
            font-weight: 600;
            color: var(--text-dark);
        }

        .table-container {
            overflow-x: auto;
        }

        .buildings-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .buildings-table thead {
            background: var(--primary-gradient);
        }

        .buildings-table th {
            padding: 1rem 0.75rem;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .buildings-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s;
        }

        .buildings-table tbody tr:hover {
            background-color: #f8f7ff;
        }

        .buildings-table td {
            padding: 0.85rem 0.75rem;
            color: #334155;
            vertical-align: middle;
            font-size: 0.85rem;
        }

        .building-image {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            object-fit: cover;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 1.2rem;
            overflow: hidden;
            border: 2px solid var(--border-color);
        }

        .building-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .building-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .building-code {
            font-family: 'Courier New', monospace;
            background: rgba(67, 97, 238, 0.08);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            color: var(--primary-color);
            font-weight: 600;
            white-space: nowrap;
        }

        .building-status {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-active {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-inactive {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f1f5f9;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            color: #475569;
            white-space: nowrap;
        }

        .info-badge i {
            font-size: 0.7rem;
            color: var(--primary-color);
        }

        .action-buttons {
            display: flex;
            gap: 6px;
        }

        .action-btn {
            padding: 7px 14px;
            border: none;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            white-space: nowrap;
            border: 2px solid transparent;
        }

        .edit-btn {
            background: linear-gradient(135deg, #fefce8, #fef08a);
            color: #854d0e;
            border-color: #fde047;
        }
        .edit-btn:hover {
            background: linear-gradient(135deg, #fef08a, #fde047);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        .delete-btn {
            background: linear-gradient(135deg, #fef2f2, #fecaca);
            color: #991b1b;
            border-color: #fca5a5;
        }
        .delete-btn:hover {
            background: linear-gradient(135deg, #fecaca, #fca5a5);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .view-btn {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: #1e40af;
            border-color: #93c5fd;
        }
        .view-btn:hover {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--text-muted);
        }
        .empty-state i { font-size: 4rem; margin-bottom: 1rem; color: #cbd5e1; }
        .empty-state h3 { margin-bottom: 0.5rem; color: var(--text-dark); }
        .empty-state p { color: var(--text-muted); }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            justify-content: center;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4); }

        .btn-secondary {
            background: var(--primary-gradient)!important;
            color: #fff!important;
        }
        .btn-secondary:hover { transform: translateY(-2px); }

        .btn-danger {
            background: var(--danger-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }
        .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4); }

        /* Delete Confirmation Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .delete-modal-content {
            background: white;
            border-radius: 20px;
            padding: 0;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            animation: modalSlideUp 0.3s ease;
        }

        .modal-header {
            background: var(--danger-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        .modal-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.4);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 1.5rem 2rem;
        }

        .modal-body .warning-text {
            color: #dc2626;
            font-size: 0.9rem;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding: 0 2rem 1.5rem 2rem;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
            border: 2px solid var(--border-color);
        }

        .btn-cancel:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        @keyframes modalSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .toast {
            position: fixed; bottom: 20px; right: 20px;
            background: var(--success-gradient); color: white;
            padding: 16px 24px; border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: flex; align-items: center; gap: 10px; z-index: 1001;
            transform: translateY(100px); opacity: 0; transition: all 0.3s ease;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.error { background: var(--danger-gradient); }

        /* Loading */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .header { flex-direction: column; gap: 1rem; text-align: center; }
            .list-section { padding: 1.5rem; }
            .action-buttons { flex-direction: column; align-items: stretch; }
            .action-btn { justify-content: center; }
            .stats-bar { flex-direction: column; }
            .modal-content { width: 95%; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-list"></i> View Buildings</h1>
                <p>Manage and view all campus buildings</p>
            </div>
            <div>
                <a href="{{ route('buildings.page') }}" class="btn btn-secondary">
                    <i class="fas fa-plus"></i> Add Building
                </a>
            </div>
        </div>

        <div class="list-section">
            <h2><i class="fas fa-university"></i> Buildings List</h2>
            
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search-buildings" placeholder="Search buildings by name or code...">
            </div>

            <div class="stats-bar" id="stats-bar">
                <div class="stat-item">
                    <i class="fas fa-building"></i>
                    <span>Total: <strong id="total-count">0</strong></span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    <span>Active: <strong id="active-count">0</strong></span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-times-circle" style="color: #ef4444;"></i>
                    <span>Inactive: <strong id="inactive-count">0</strong></span>
                </div>
            </div>

            <div class="table-container">
                <table class="buildings-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Code</th>
                            <th>Building Name</th>
                            <th>Total Area</th>
                            <th>Gates</th>
                            <th>Additional Areas</th>
                            <th>Amenities</th>
                            <th>Status</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="buildings-list">
                        <!-- Rows will be inserted here -->
                    </tbody>
                </table>
                <div id="empty-state" class="empty-state" style="display: none;">
                    <i class="fas fa-university"></i>
                    <h3>No Buildings Added</h3>
                    <p>Start by adding your first building.</p>
                    <a href="{{ route('buildings.page') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Building
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal-overlay">
        <div class="delete-modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
                <button class="modal-close" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="building-to-delete-name"></strong>?</p>
                <p class="warning-text">
                    <i class="fas fa-exclamation-circle"></i> Warning: This action cannot be undone.
                </p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Building deleted successfully!</span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        let allBuildings = [];
        let buildingToDelete = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadBuildings();
            document.getElementById('search-buildings').addEventListener('input', function(e) {
                searchBuildings(e.target.value);
            });

            // Close modal on outside click
            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteModal();
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeleteModal();
                }
            });
        });

        async function loadBuildings() {
            const tbody = document.getElementById('buildings-list');
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" style="text-align:center;padding:3rem;">
                        <div class="loading-spinner" style="margin:0 auto 1rem;display:block;"></div>
                        <p style="color: var(--text-muted);">Loading buildings...</p>
                    </td>
                </tr>
            `;

            try {
                const response = await fetch(`${API_BASE_URL}/buildings`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    allBuildings = result.data.data || result.data || [];
                    displayBuildings(allBuildings);
                    updateStats(allBuildings);
                } else {
                    showToast('Failed to load buildings', 'error');
                }
            } catch (error) {
                console.error('Error loading buildings:', error);
                showToast('Failed to load buildings', 'error');
            }
        }

        function searchBuildings(searchTerm) {
            if (!searchTerm.trim()) {
                displayBuildings(allBuildings);
                updateStats(allBuildings);
                return;
            }
            
            const filtered = allBuildings.filter(b => 
                (b.name && b.name.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (b.code && b.code.toLowerCase().includes(searchTerm.toLowerCase()))
            );
            displayBuildings(filtered);
            updateStats(filtered);
        }

        function displayBuildings(buildings) {
            const tbody = document.getElementById('buildings-list');
            const emptyState = document.getElementById('empty-state');
            
            if (!buildings || buildings.length === 0) {
                tbody.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }
            
            emptyState.style.display = 'none';
            
            let html = '';
            buildings.forEach(building => {
                const hasPhoto = building.photo_url || building.photo;
                const editUrl = `/buildings/${building.id}/edit`;
                const viewUrl = `/view-building/${building.id}`;
                
                // Total area
                const areaValue = building.area_value || '';
                const areaUnit = building.area_unit || '';
                const totalArea = areaValue ? `${areaValue} ${formatUnit(areaUnit)}` : '—';
                
                // Gates count
                const gatesData = parseJsonSafe(building.gates);
                const gatesCount = Array.isArray(gatesData) ? gatesData.filter(g => g && (g.name || g.number)).length : 0;
                
                // Additional areas count
                const areasData = parseJsonSafe(building.additional_areas);
                const areasCount = Array.isArray(areasData) ? areasData.filter(a => a && a.name).length : 0;
                
                // Predefined amenities count
                const amenitiesData = parseJsonSafe(building.amenities);
                let enabledAmenitiesCount = 0;
                if (amenitiesData && typeof amenitiesData === 'object' && !Array.isArray(amenitiesData)) {
                    enabledAmenitiesCount = Object.values(amenitiesData).filter(a => {
                        if (!a) return false;
                        if (typeof a === 'object') return a.enabled === true || a.enabled === 'true' || a.enabled === 1 || a.enabled === '1';
                        return a === true || a === 'true' || a === 1 || a === '1';
                    }).length;
                }
                
                // Custom amenities count
                const customAmenitiesData = parseJsonSafe(building.custom_amenities);
                const customAmenitiesCount = Array.isArray(customAmenitiesData) ? customAmenitiesData.filter(a => a && a.name).length : 0;
                
                const totalAmenities = enabledAmenitiesCount + customAmenitiesCount;
                
                html += `
                    <tr>
                        <td>
                            <div class="building-image">
                                ${hasPhoto ? 
                                    `<img src="${hasPhoto}" alt="${escapeHtml(building.name)}" onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\\'fas fa-university\\'></i>';" />` : 
                                    `<i class="fas fa-university"></i>`
                                }
                            </div>
                        </td>
                        <td><span class="building-code">${escapeHtml(building.code)}</span></td>
                        <td>
                            <div class="building-name">${escapeHtml(building.name)}</div>
                            ${building.address ? `<div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 3px;">${escapeHtml(building.address.substring(0, 50))}${building.address.length > 50 ? '...' : ''}</div>` : ''}
                        </td>
                        <td>${areaValue ? `<span class="info-badge"><i class="fas fa-vector-square"></i> ${totalArea}</span>` : '<span style="font-size:0.75rem;color:var(--text-muted);">—</span>'}</td>
                        <td>${gatesCount > 0 ? `<span class="info-badge"><i class="fas fa-door-open"></i> ${gatesCount} Gate${gatesCount > 1 ? 's' : ''}</span>` : '<span style="font-size:0.75rem;color:var(--text-muted);">—</span>'}</td>
                        <td>${areasCount > 0 ? `<span class="info-badge"><i class="fas fa-map"></i> ${areasCount} Area${areasCount > 1 ? 's' : ''}</span>` : '<span style="font-size:0.75rem;color:var(--text-muted);">—</span>'}</td>
                        <td>${totalAmenities > 0 ? `<span class="info-badge"><i class="fas fa-concierge-bell"></i> ${totalAmenities}</span>` : '<span style="font-size:0.75rem;color:var(--text-muted);">—</span>'}</td>
                        <td>
                            <span class="building-status ${building.status === 'active' ? 'status-active' : 'status-inactive'}">
                                ${building.status === 'active' ? 'Active' : 'Inactive'}
                            </span>
                        </td>
                        <td>${building.year_established || '—'}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="${viewUrl}" class="action-btn view-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="${editUrl}" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button type="button" class="action-btn delete-btn" onclick="showDeleteModal('${building.id}', '${escapeHtml(building.name).replace(/'/g, "\\'")}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            
            tbody.innerHTML = html;
        }

        function updateStats(buildings) {
            const total = buildings ? buildings.length : 0;
            const active = buildings ? buildings.filter(b => b.status === 'active').length : 0;
            const inactive = total - active;
            
            document.getElementById('total-count').textContent = total;
            document.getElementById('active-count').textContent = active;
            document.getElementById('inactive-count').textContent = inactive;
        }

        function showDeleteModal(id, name) {
            buildingToDelete = id;
            document.getElementById('building-to-delete-name').textContent = name;
            document.getElementById('delete-modal').classList.add('active');
        }

        function closeDeleteModal() {
            buildingToDelete = null;
            document.getElementById('delete-modal').classList.remove('active');
        }

        async function confirmDelete() {
            if (!buildingToDelete) return;

            try {
                const response = await fetch(`${API_BASE_URL}/buildings/${buildingToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message || 'Building deleted successfully!');
                    closeDeleteModal();
                    loadBuildings();
                } else {
                    showToast(result.message || 'Failed to delete building', 'error');
                }
            } catch (error) {
                console.error('Error deleting building:', error);
                showToast('Failed to delete building', 'error');
            }
        }

        // ========== HELPER FUNCTIONS ==========

        function parseJsonSafe(str) {
            if (!str) return null;
            if (typeof str === 'object' && str !== null) return str;
            if (typeof str === 'string') {
                try {
                    return JSON.parse(str);
                } catch (e) {
                    return null;
                }
            }
            return null;
        }

        function formatUnit(unit) {
            if (!unit) return '';
            const units = {
                'sq_ft': 'Sq. Ft.', 'sq_m': 'Sq. M.', 'sq_yd': 'Sq. Yd.',
                'gaj': 'Gaj', 'marla': 'Marla', 'kanal': 'Kanal',
                'acre': 'Acre', 'hectare': 'Hectare', 'bigha': 'Bigha', 'biswa': 'Biswa'
            };
            return units[unit] || unit;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            if (!toast || !toastMessage) {
                alert(message);
                return;
            }
            
            toastMessage.textContent = message;
            toast.className = 'toast';
            if (type === 'error') {
                toast.classList.add('error');
                toast.querySelector('i').className = 'fas fa-exclamation-circle';
            } else {
                toast.classList.remove('error');
                toast.querySelector('i').className = 'fas fa-check-circle';
            }
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }
    </script>
</body>
</html>
@endsection