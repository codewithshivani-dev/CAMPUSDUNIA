@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Rooms</title>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
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
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .header-content h1 i { background: rgba(255,255,255,0.2); padding: 10px; border-radius: 12px; }
        .header-content p { opacity: 0.9; font-size: 1rem; }

        .back-btn {
            background: var(--primary-gradient)!important; color: white!important; padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600;
            cursor: pointer; transition: all 0.3s; display: flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .back-btn:hover {  background: var(--primary-gradient)!important; color: white!important; transform: translateY(-2px); }

        .list-card {
            background: white; border-radius: 20px; padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05); border: 2px solid var(--border-color);
        }

        .list-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;
        }
        .list-header h2 { color: var(--text-dark); display: flex; align-items: center; gap: 10px; font-weight: 700; }
        .list-header h2 i { color: var(--primary-color); }

        .items-count {
            background: var(--primary-gradient); color: white; padding: 6px 16px;
            border-radius: 30px; font-size: 0.875rem; font-weight: 600;
        }

        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            width: 100%;
            padding: 12px 16px 12px 45px;
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
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .rooms-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            min-width: 1200px;
        }

        .rooms-table thead {
            background: var(--primary-gradient);
            color: white;
        }

        .rooms-table thead th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }

        .rooms-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.2s;
        }

        .rooms-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .rooms-table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
        }

        .rooms-table tbody tr:last-child {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .status-active {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }

        .status-inactive {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .status-under_maintenance {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .type-classroom {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        .type-lab {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .type-office {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .type-conference {
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            color: #3730a3;
        }

        .type-library {
            background: linear-gradient(135deg, #fce7f3, #f9a8d4);
            color: #831843;
        }

        .type-other {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #475569;
        }

        .amenity-tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            margin: 1px 2px;
            white-space: nowrap;
            background: #f0f4ff;
            border: 1px solid rgba(67, 97, 238, 0.2);
            color: var(--primary-color);
        }

        .amenity-tag i {
            font-size: 0.6rem;
            margin-right: 2px;
        }

        .actions-cell {
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .action-btn {
            padding: 0.4rem 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .view-btn {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .view-btn:hover {
            background: var(--success-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        }

        .edit-btn {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(67, 97, 238, 0.2);
        }

        .edit-btn:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.3);
        }

        .delete-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .delete-btn:hover {
            background: var(--danger-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center; padding: 3rem; color: var(--text-muted);
        }
        .empty-state i { font-size: 4rem; color: var(--border-color); margin-bottom: 1rem; }
        .empty-state p { font-size: 1.1rem; margin-bottom: 0.5rem; }

        .btn {
            padding: 0.75rem 1.5rem; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .btn-primary { background: var(--primary-gradient); color: white; box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4); }
        .btn-danger { background: var(--danger-gradient); color: white; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); }
        .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4); }
        .btn-secondary { background: #f1f5f9; color: var(--text-dark); border: 2px solid var(--border-color); }
        .btn-secondary:hover { background: #e2e8f0; transform: translateY(-2px); }

        /* Delete Modal */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px);
            z-index: 1000; align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal-overlay.active { display: flex; }

        .modal-content {
            background: white; border-radius: 20px; padding: 0;
            max-width: 500px; width: 100%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            animation: modalSlideUp 0.3s ease;
        }

        @keyframes modalSlideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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
            margin: 0; display: flex; align-items: center; gap: 10px; font-size: 1.1rem;
        }

        .modal-close {
            background: rgba(255,255,255,0.2);
            border: none; color: white; width: 36px; height: 36px;
            border-radius: 50%; cursor: pointer; font-size: 1.2rem;
            display: flex; align-items: center; justify-content: center; transition: all 0.3s;
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

        .toast {
            position: fixed; bottom: 20px; right: 20px; background: var(--success-gradient);
            color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: flex; align-items: center; gap: 10px; z-index: 1001;
            transform: translateY(100px); opacity: 0; transition: all 0.3s ease;
        }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.error { background: var(--danger-gradient); }

        .loading-container { text-align: center; padding: 3rem; }
        .loading-spinner {
            width: 40px; height: 40px; border: 4px solid var(--border-color);
            border-top: 4px solid var(--primary-color); border-radius: 50%;
            animation: spin 1s linear infinite; margin: 0 auto 1rem;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        @media (max-width: 768px) {
            .header { flex-direction: column; gap: 1rem; text-align: center; }
            .list-card { padding: 1.5rem; }
            .rooms-table thead th,
            .rooms-table tbody td {
                padding: 8px 10px;
                font-size: 0.75rem;
            }
            .action-btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.65rem;
                gap: 3px;
            }
            .action-btn i {
                font-size: 0.7rem;
            }
            .rooms-table {
                min-width: 900px;
            }
        }

        @media (max-width: 480px) {
            .action-btn {
                padding: 0.2rem 0.4rem;
                font-size: 0.6rem;
                gap: 2px;
            }
            .action-btn i {
                font-size: 0.6rem;
            }
            .rooms-table thead th,
            .rooms-table tbody td {
                padding: 6px 8px;
                font-size: 0.7rem;
            }
            .rooms-table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-list"></i> List Rooms</h1>
                <p>Manage and view all building rooms</p>
            </div>
            <a href="{{ route('rooms.page') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Add Rooms
            </a>
        </div>

        <div class="list-card">
            <div class="list-header">
                <h2><i class="fas fa-table"></i> All Rooms</h2>
                <div class="items-count" id="roomsCount">0 items</div>
            </div>

            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchRooms" placeholder="Search rooms by room number, name, building, or block...">
            </div>
            
            <div class="table-container">
                <table class="rooms-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Room</th>
                            <th>Building</th>
                            <th>Block</th>
                            <th>Floor</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Capacity</th>
                            <th>Amenities</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="roomsTableBody">
                        <!-- Rows will be inserted here -->
                    </tbody>
                </table>
            </div>
            <div id="emptyState" style="display:none;">
                <div class="empty-state">
                    <i class="fas fa-door-closed"></i>
                    <p>No rooms added yet</p>
                    <small>Add your first room using the Add Room page</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="room-to-delete-name"></strong>?</p>
                <p class="warning-text">
                    <i class="fas fa-exclamation-circle"></i> Warning: This action cannot be undone.
                </p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Room deleted successfully!</span>
    </div>

    <script>
        let roomToDelete = null;
        let allRooms = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadRooms();

            document.getElementById('searchRooms').addEventListener('input', function(e) {
                searchRooms(e.target.value);
            });

            // Close delete modal on outside click
            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) closeDeleteModal();
            });

            // Close delete modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeDeleteModal();
            });
        });

        // ===== LOAD ROOMS =====
        async function loadRooms() {
            const tableBody = document.getElementById('roomsTableBody');
            const emptyState = document.getElementById('emptyState');
            
            tableBody.innerHTML = `
                <tr>
                    <td colspan="10" style="text-align:center;padding:3rem;">
                        <div class="loading-spinner" style="margin:0 auto 1rem;"></div>
                        <p style="color: var(--text-muted);">Loading rooms...</p>
                    </td>
                </tr>
            `;
            emptyState.style.display = 'none';

            try {
                const response = await fetch('/rooms', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                let rooms = null;
                if (Array.isArray(result)) {
                    rooms = result;
                } else if (result.success && Array.isArray(result.data)) {
                    rooms = result.data;
                } else if (result.success && result.data && Array.isArray(result.data.data)) {
                    rooms = result.data.data;
                } else if (result.success && typeof result.data === 'object' && !Array.isArray(result.data)) {
                    const dataObj = result.data;
                    if (Array.isArray(dataObj.rooms)) rooms = dataObj.rooms;
                    else if (Array.isArray(dataObj.data)) rooms = dataObj.data;
                    else rooms = Object.values(dataObj);
                } else if (typeof result === 'object' && !result.success) {
                    if (Array.isArray(Object.values(result)[0])) rooms = Object.values(result)[0];
                    else rooms = Object.values(result);
                }
                
                if (rooms && Array.isArray(rooms)) {
                    allRooms = rooms;
                    displayRooms(allRooms);
                } else {
                    console.error('Could not extract rooms array');
                    showError('Unexpected data format. Check console for details.');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                showError('Connection error: ' + error.message);
            }
        }

        async function searchRooms(searchTerm) {
            if (!searchTerm.trim()) {
                displayRooms(allRooms);
                return;
            }

            const filtered = allRooms.filter(room => {
                const roomNumber = String(room.room_number || '').toLowerCase();
                const roomName = String(room.room_name || '').toLowerCase();
                const blockName = String(room.block?.name || '').toLowerCase();
                const buildingName = String(room.building?.name || '').toLowerCase();
                const floorNumber = String(room.floor?.floor_number || '').toLowerCase();
                const search = searchTerm.toLowerCase();
                return roomNumber.includes(search) || 
                       roomName.includes(search) || 
                       blockName.includes(search) || 
                       buildingName.includes(search) ||
                       floorNumber.includes(search);
            });

            displayRooms(filtered);
        }

        function displayRooms(rooms) {
            const tableBody = document.getElementById('roomsTableBody');
            const emptyState = document.getElementById('emptyState');
            const roomsCount = document.getElementById('roomsCount');
            const editRouteBase = '/rooms/__ID__/edit';
            const viewRouteBase = '/view-room/__ID__';
            
            if (!rooms || rooms.length === 0) {
                tableBody.innerHTML = '';
                emptyState.style.display = 'block';
                roomsCount.textContent = '0 items';
                return;
            }
            
            emptyState.style.display = 'none';
            roomsCount.textContent = rooms.length + ' ' + (rooms.length === 1 ? 'item' : 'items');
            
            const html = rooms.map((room, index) => {
                const editUrl = editRouteBase.replace('__ID__', room.id);
                const viewUrl = viewRouteBase.replace('__ID__', room.id);
                
                // Get related data
                const block = room.block || {};
                const building = room.building || {};
                const floor = room.floor || {};
                
                // Type badge class
                const typeClasses = {
                    'classroom': 'type-classroom',
                    'lab': 'type-lab',
                    'office': 'type-office',
                    'conference': 'type-conference',
                    'library': 'type-library'
                };
                const typeClass = typeClasses[room.room_type] || 'type-other';
                
                let displayType = room.room_type || 'other';
                if (room.room_type === 'other' && room.custom_room_type) {
                    displayType = room.custom_room_type;
                }
                displayType = displayType.charAt(0).toUpperCase() + displayType.slice(1);
                
                // Status
                const statusClass = `status-${room.status || 'active'}`;
                const statusText = (room.status || 'active').replace('_', ' ').toUpperCase();
                
                // Generate amenity tags
                let amenityTags = '';
                const amenityIcons = {
                    'has_ac': 'fa-snowflake',
                    'has_washroom': 'fa-toilet',
                    'has_wifi': 'fa-wifi',
                    'has_projector': 'fa-projector',
                    'has_cctv': 'fa-video',
                    'has_whiteboard': 'fa-chalkboard',
                    'has_smart_board': 'fa-tv',
                    'has_fire_extinguisher': 'fa-fire-extinguisher',
                    'has_water_cooler': 'fa-tint'
                };
                const amenityShortNames = {
                    'has_ac': 'AC',
                    'has_washroom': 'Washroom',
                    'has_wifi': 'Wi-Fi',
                    'has_projector': 'Projector',
                    'has_cctv': 'CCTV',
                    'has_whiteboard': 'Whiteboard',
                    'has_smart_board': 'Smart Board',
                    'has_fire_extinguisher': 'Fire Ext.',
                    'has_water_cooler': 'Water Cooler'
                };
                
                let count = 0;
                Object.keys(amenityShortNames).forEach(key => {
                    const val = room[key];
                    if (val === true || val === 'yes' || val === 1 || val === 'true' || val === '1') {
                        const icon = amenityIcons[key] || 'fa-check';
                        const shortName = amenityShortNames[key] || key.replace('has_', '').replace(/_/g, ' ');
                        amenityTags += `<span class="amenity-tag"><i class="fas ${icon}"></i> ${shortName}</span>`;
                        count++;
                    }
                });
                
                if (count === 0) {
                    amenityTags = '<span style="color:var(--text-muted);font-size:0.7rem;">None</span>';
                }
                
                const capacity = room.capacity || room.max_capacity || 'N/A';
                
                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <strong>${escapeHtml(room.room_number)}</strong>
                            ${room.room_name ? `<br><small style="color:var(--text-muted);">${escapeHtml(room.room_name)}</small>` : ''}
                        </td>
                        <td><strong>${escapeHtml(building.name || 'N/A')}</strong></td>
                        <td>${escapeHtml(block.name || 'N/A')}</td>
                        <td>${escapeHtml(floor.floor_number || 'N/A')}</td>
                        <td><span class="type-badge ${typeClass}">${escapeHtml(displayType)}</span></td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>${capacity}</td>
                        <td>${amenityTags}</td>
                        <td>
                            <div class="actions-cell">
                                <a href="${viewUrl}" class="action-btn view-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="${editUrl}" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="action-btn delete-btn" onclick="deleteRoomPrompt(${room.id}, '${escapeHtml(room.room_number).replace(/'/g, "\\'")}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            tableBody.innerHTML = html;
        }

        function showError(message) {
            const tableBody = document.getElementById('roomsTableBody');
            const emptyState = document.getElementById('emptyState');
            emptyState.style.display = 'none';
            tableBody.innerHTML = `
                <tr>
                    <td colspan="10" style="text-align:center;padding:3rem;">
                        <i class="fas fa-exclamation-circle" style="font-size:3rem;color:#ef4444;display:block;margin-bottom:1rem;"></i>
                        <p style="font-size:1.1rem;color:var(--text-dark);">Error loading rooms</p>
                        <small style="color:var(--text-muted);">${escapeHtml(message)}</small>
                        <br><br>
                        <button class="btn btn-primary" onclick="loadRooms()">
                            <i class="fas fa-sync-alt"></i> Retry
                        </button>
                    </td>
                </tr>
            `;
        }

        // ===== DELETE FUNCTIONS =====
        function deleteRoomPrompt(id, name) {
            roomToDelete = id;
            document.getElementById('room-to-delete-name').textContent = name;
            document.getElementById('delete-modal').classList.add('active');
        }

        function closeDeleteModal() {
            roomToDelete = null;
            document.getElementById('delete-modal').classList.remove('active');
        }

        async function confirmDelete() {
            if (!roomToDelete) return;
            
            try {
                const response = await fetch('/rooms/' + roomToDelete, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message);
                    closeDeleteModal();
                    loadRooms();
                } else {
                    showToast(result.message || 'Failed to delete room', 'error');
                }
            } catch (error) {
                showToast('Failed to delete room', 'error');
            }
        }

        // ===== UTILITY FUNCTIONS =====
        function escapeHtml(text) {
            if (!text) return '';
            return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }

        function parseJsonSafe(data) {
            if (!data) return null;
            if (typeof data === 'object') return data;
            try { return JSON.parse(data); } catch (e) { return null; }
        }

        function showToast(message, type) {
            type = type || 'success';
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').textContent = message;
            toast.className = 'toast' + (type === 'error' ? ' error' : '');
            toast.querySelector('i').className = type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle';
            toast.classList.add('show');
            setTimeout(function() { toast.classList.remove('show'); }, 3000);
        }
    </script>
</body>
</html>
@endsection