@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Management</title>
    <style>
        .header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card-header {
            background: #f8f9ff;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .card-header h2 {
            color: #333;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #444;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            background-color: white;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .classroom-list {
            margin-top: 2rem;
        }

        .classroom-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .classroom-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s;
            border: 1px solid #e0e0e0;
        }

        .classroom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .classroom-header {
            padding: 1rem 1.25rem;
            background: #f8f9ff;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .classroom-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #333;
        }

        .classroom-status {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .status-available {
            background: #d4edda;
            color: #155724;
        }

        .status-unavailable {
            background: #f8d7da;
            color: #721c24;
        }

        .status-inactive {
            background: #e2e3e5;
            color: #383d41;
        }

        .classroom-body {
            padding: 1.25rem;
        }

        .classroom-details {
            display: grid;
            gap: 0.75rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #f5f5f5;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        .facilities-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .facility-badge {
            background: #e9ecef;
            color: #495057;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .classroom-actions {
            padding: 1rem 1.25rem;
            background: #f8f9ff;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 0.75rem;
        }

        .classroom-actions .btn {
            flex: 1;
            padding: 0.5rem;
            font-size: 0.875rem;
            justify-content: center;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .search-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        .filter-select {
            min-width: 200px;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-content h3 {
            font-size: 2rem;
            margin: 0;
            color: #333;
        }

        .stat-content p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: white;
            width: 100%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 10px 10px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.2);
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            background: #f8f9ff;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .classroom-grid {
                grid-template-columns: 1fr;
            }
            
            .search-box {
                min-width: 100%;
            }
            
            .search-filter {
                flex-direction: column;
            }
            
            .filter-select {
                width: 100%;
            }
            
            .modal-content {
                max-width: 95%;
            }
            
            .modal-header {
                padding: 1rem;
            }
            
            .modal-body {
                padding: 1rem;
            }
            
            .modal-footer {
                padding: 1rem;
                flex-direction: column;
            }
            
            .modal-footer button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <h1><i class="fas fa-chalkboard-teacher"></i> Classroom Management System</h1>
            <p>Manage all classrooms, labs, and auditoriums for exam scheduling</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e3f2fd; color: #1976d2;">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <div class="stat-content">
                    <h3 id="totalClassrooms">0</h3>
                    <p>Total Classrooms</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5e9; color: #388e3c;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 id="availableClassrooms">0</h3>
                    <p>Available Now</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fff3e0; color: #f57c00;">
                    <i class="fas fa-flask"></i>
                </div>
                <div class="stat-content">
                    <h3 id="labCount">0</h3>
                    <p>Laboratories</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #f3e5f5; color: #7b1fa2;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 id="totalCapacity">0</h3>
                    <p>Total Capacity</p>
                </div>
            </div>
        </div>

        <!-- Add Classroom Card -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-plus-circle"></i> Add New Classroom</h2>
            </div>
            <div class="card-body">
                <form id="classroomForm">
                    <div class="form-row">
                        <!-- Basic Information -->
                        <div>
                            <div class="form-group">
                                <label for="name" class="form-label">Classroom Name *</label>
                                <input type="text" id="name" class="form-control" placeholder="e.g., Main Lecture Hall, Computer Lab A">
                            </div>
                            <div class="form-group">
                                <label for="room_number" class="form-label">Room Number *</label>
                                <input type="text" id="room_number" class="form-control" placeholder="e.g., 101, LAB-01, AUD-001">
                            </div>
                            <div class="form-group">
                                <label for="capacity" class="form-label">Capacity *</label>
                                <input type="number" id="capacity" class="form-control" min="1" max="500" value="40">
                            </div>
                            <div class="form-group">
                                <label for="room_type" class="form-label">Room Type *</label>
                                <select id="room_type" class="form-select">
                                    <option value="classroom">Classroom</option>
                                    <option value="lab">Laboratory</option>
                                    <option value="auditorium">Auditorium</option>
                                    <option value="seminar_hall">Seminar Hall</option>
                                    <option value="conference_room">Conference Room</option>
                                </select>
                            </div>
                        </div>

                        <!-- Location Details -->
                        <div>
                            <div class="form-group">
                                <label for="building" class="form-label">Building</label>
                                <input type="text" id="building" class="form-control" placeholder="e.g., Main Building, Science Block">
                            </div>
                            <div class="form-group">
                                <label for="floor" class="form-label">Floor</label>
                                <select id="floor" class="form-select">
                                    <option value="">Select Floor</option>
                                    <option value="ground">Ground Floor</option>
                                    <option value="1">1st Floor</option>
                                    <option value="2">2nd Floor</option>
                                    <option value="3">3rd Floor</option>
                                    <option value="4">4th Floor</option>
                                    <option value="5">5th Floor</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="facilities" class="form-label">Facilities (comma separated)</label>
                                <input type="text" id="facilities" class="form-control" placeholder="e.g., Whiteboard, Projector, Wi-Fi, CCTV">
                            </div>
                            
                            <!-- Amenities Checkboxes -->
                            <div class="form-group">
                                <label class="form-label">Amenities</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="has_projector" value="1">
                                        <label for="has_projector">Projector</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="has_ac" value="1">
                                        <label for="has_ac">Air Conditioning</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="has_smart_board" value="1">
                                        <label for="has_smart_board">Smart Board</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Checkboxes -->
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="is_available" checked value="1">
                                        <label for="is_available">Currently Available</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="is_active" checked value="1">
                                        <label for="is_active">Active (Visible in System)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="text-align: right; margin-top: 1.5rem;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Classroom
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="search-filter">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search classrooms by name, room number, or building...">
            </div>
            <select id="roomTypeFilter" class="form-select filter-select">
                <option value="">All Room Types</option>
                <option value="classroom">Classrooms</option>
                <option value="lab">Laboratories</option>
                <option value="auditorium">Auditoriums</option>
                <option value="seminar_hall">Seminar Halls</option>
            </select>
            <select id="availabilityFilter" class="form-select filter-select">
                <option value="">All Availability</option>
                <option value="available">Available Only</option>
                <option value="unavailable">Unavailable</option>
            </select>
            <select id="activeFilter" class="form-select filter-select">
                <option value="">All Status</option>
                <option value="active">Active Only</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <!-- Classroom List -->
        <div class="classroom-list">
            <h2 style="color: #333; margin-bottom: 1rem;">Classrooms List</h2>
            <div id="classroomsContainer">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Edit Classroom</h2>
                <button class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editForm"></form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" form="editForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Classroom
                </button>
            </div>
        </div>
    </div>

    <script>
        // Initialize classrooms array
        let classrooms = JSON.parse(localStorage.getItem('examClassrooms')) || [];
        let editingId = null;

        // Load classrooms on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadClassrooms();
            setupEventListeners();
            initializeModal();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Form submission
            document.getElementById('classroomForm').addEventListener('submit', function(e) {
                e.preventDefault();
                saveClassroom();
            });

            // Edit form submission
            document.addEventListener('submit', function(e) {
                if (e.target && e.target.id === 'editForm') {
                    e.preventDefault();
                    updateClassroom();
                }
            });

            // Search and filter
            document.getElementById('searchInput').addEventListener('input', loadClassrooms);
            document.getElementById('roomTypeFilter').addEventListener('change', loadClassrooms);
            document.getElementById('availabilityFilter').addEventListener('change', loadClassrooms);
            document.getElementById('activeFilter').addEventListener('change', loadClassrooms);
        }

        // Initialize modal
        function initializeModal() {
            const modal = document.getElementById('editModal');
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeEditModal();
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeEditModal();
                }
            });
        }

        // Load classrooms
        function loadClassrooms() {
            const container = document.getElementById('classroomsContainer');
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const roomTypeFilter = document.getElementById('roomTypeFilter').value;
            const availabilityFilter = document.getElementById('availabilityFilter').value;
            const activeFilter = document.getElementById('activeFilter').value;

            // Filter classrooms
            let filteredClassrooms = classrooms.filter(classroom => {
                const matchesSearch = !searchTerm || 
                    classroom.name.toLowerCase().includes(searchTerm) ||
                    classroom.room_number.toLowerCase().includes(searchTerm) ||
                    (classroom.building && classroom.building.toLowerCase().includes(searchTerm));
                
                const matchesType = !roomTypeFilter || classroom.room_type === roomTypeFilter;
                const matchesAvailability = !availabilityFilter || 
                    (availabilityFilter === 'available' && classroom.is_available) ||
                    (availabilityFilter === 'unavailable' && !classroom.is_available);
                const matchesActive = !activeFilter ||
                    (activeFilter === 'active' && classroom.is_active) ||
                    (activeFilter === 'inactive' && !classroom.is_active);

                return matchesSearch && matchesType && matchesAvailability && matchesActive;
            });

            // Update statistics
            updateStatistics(filteredClassrooms);

            if (filteredClassrooms.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-door-closed"></i>
                        <p>No classrooms found</p>
                        <small>${searchTerm || roomTypeFilter || availabilityFilter || activeFilter ? 'Try changing your filters' : 'Add your first classroom using the form above'}</small>
                    </div>
                `;
                return;
            }

            // Sort by name
            filteredClassrooms.sort((a, b) => a.name.localeCompare(b.name));

            // Generate classroom cards
            let html = '<div class="classroom-grid">';
            
            filteredClassrooms.forEach(classroom => {
                const statusClass = !classroom.is_active ? 'status-inactive' :
                    classroom.is_available ? 'status-available' : 'status-unavailable';
                const statusText = !classroom.is_active ? 'Inactive' :
                    classroom.is_available ? 'Available' : 'Unavailable';
                
                // Parse facilities
                const facilities = classroom.facilities ? 
                    classroom.facilities.split(',').map(f => f.trim()).filter(f => f) : [];
                
                html += `
                    <div class="classroom-card" id="classroom-${classroom.id}">
                        <div class="classroom-header">
                            <div class="classroom-name">${classroom.name}</div>
                            <div class="classroom-status ${statusClass}">${statusText}</div>
                        </div>
                        <div class="classroom-body">
                            <div class="classroom-details">
                                <div class="detail-item">
                                    <span class="detail-label">Room Number:</span>
                                    <span class="detail-value">${classroom.room_number}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Capacity:</span>
                                    <span class="detail-value">${classroom.capacity} seats</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Type:</span>
                                    <span class="detail-value">${formatRoomType(classroom.room_type)}</span>
                                </div>
                                ${classroom.building ? `
                                <div class="detail-item">
                                    <span class="detail-label">Building:</span>
                                    <span class="detail-value">${classroom.building}</span>
                                </div>
                                ` : ''}
                                ${classroom.floor ? `
                                <div class="detail-item">
                                    <span class="detail-label">Floor:</span>
                                    <span class="detail-value">${classroom.floor}${classroom.floor === 'ground' ? '' : classroom.floor.match(/^\d+$/) ? 'th Floor' : ''}</span>
                                </div>
                                ` : ''}
                            </div>
                            
                            ${facilities.length > 0 ? `
                            <div style="margin-top: 1rem;">
                                <strong style="color: #666; font-size: 0.9rem;">Facilities:</strong>
                                <div class="facilities-list">
                                    ${facilities.map(facility => `
                                        <span class="facility-badge">${facility}</span>
                                    `).join('')}
                                </div>
                            </div>
                            ` : ''}
                            
                            ${classroom.has_projector || classroom.has_ac || classroom.has_smart_board ? `
                            <div style="margin-top: 1rem;">
                                <strong style="color: #666; font-size: 0.9rem;">Amenities:</strong>
                                <div class="facilities-list">
                                    ${classroom.has_projector ? '<span class="facility-badge"><i class="fas fa-video"></i> Projector</span>' : ''}
                                    ${classroom.has_ac ? '<span class="facility-badge"><i class="fas fa-snowflake"></i> AC</span>' : ''}
                                    ${classroom.has_smart_board ? '<span class="facility-badge"><i class="fas fa-chalkboard"></i> Smart Board</span>' : ''}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                        <div class="classroom-actions">
                            <button class="btn btn-success" onclick="toggleAvailability(${classroom.id})">
                                <i class="fas fa-exchange-alt"></i> ${classroom.is_available ? 'Mark Busy' : 'Mark Available'}
                            </button>
                            <button class="btn btn-primary" onclick="openEditModal(${classroom.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger" onclick="deleteClassroom(${classroom.id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            container.innerHTML = html;
        }

        // Update statistics
        function updateStatistics(classrooms) {
            const total = classrooms.length;
            const available = classrooms.filter(c => c.is_available && c.is_active).length;
            const labs = classrooms.filter(c => c.room_type === 'lab' && c.is_active).length;
            const totalCapacity = classrooms.reduce((sum, c) => sum + (c.is_active ? parseInt(c.capacity) : 0), 0);

            document.getElementById('totalClassrooms').textContent = total;
            document.getElementById('availableClassrooms').textContent = available;
            document.getElementById('labCount').textContent = labs;
            document.getElementById('totalCapacity').textContent = totalCapacity.toLocaleString();
        }

        // Format room type for display
        function formatRoomType(type) {
            const types = {
                'classroom': 'Classroom',
                'lab': 'Laboratory',
                'auditorium': 'Auditorium',
                'seminar_hall': 'Seminar Hall',
                'conference_room': 'Conference Room'
            };
            return types[type] || type;
        }

        // Save new classroom
        function saveClassroom() {
            // Validate required fields
            const name = document.getElementById('name').value.trim();
            const roomNumber = document.getElementById('room_number').value.trim();
            const capacity = parseInt(document.getElementById('capacity').value);

            if (!name) {
                alert('Classroom name is required!');
                return;
            }
            if (!roomNumber) {
                alert('Room number is required!');
                return;
            }
            if (!capacity || capacity < 1) {
                alert('Please enter a valid capacity (minimum 1)');
                return;
            }

            // Check for duplicate room number
            const existing = classrooms.find(c => c.room_number.toLowerCase() === roomNumber.toLowerCase());
            if (existing) {
                alert(`Room number "${roomNumber}" already exists!`);
                return;
            }

            // Create classroom object
            const classroom = {
                id: Date.now(),
                name: name,
                room_number: roomNumber,
                capacity: capacity,
                building: document.getElementById('building').value.trim(),
                floor: document.getElementById('floor').value,
                room_type: document.getElementById('room_type').value,
                facilities: document.getElementById('facilities').value.trim(),
                has_projector: document.getElementById('has_projector').checked,
                has_ac: document.getElementById('has_ac').checked,
                has_smart_board: document.getElementById('has_smart_board').checked,
                is_available: document.getElementById('is_available').checked,
                is_active: document.getElementById('is_active').checked,
                created_at: new Date().toLocaleString(),
                updated_at: new Date().toLocaleString()
            };

            // Add to array and save to localStorage
            classrooms.push(classroom);
            localStorage.setItem('examClassrooms', JSON.stringify(classrooms));

            // Reset form and reload list
            resetForm();
            loadClassrooms();

            // Show success message
            alert(`Classroom "${name}" added successfully!`);
        }

        // Open edit modal
        function openEditModal(id) {
            const classroom = classrooms.find(c => c.id === id);
            if (!classroom) {
                alert('Classroom not found!');
                return;
            }

            editingId = id;

            // Populate edit form
            const editForm = document.getElementById('editForm');
            editForm.innerHTML = `
                <div class="form-row">
                    <div>
                        <div class="form-group">
                            <label for="edit_name" class="form-label">Classroom Name *</label>
                            <input type="text" class="form-control" id="edit_name" value="${classroom.name}" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_room_number" class="form-label">Room Number *</label>
                            <input type="text" class="form-control" id="edit_room_number" value="${classroom.room_number}" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_capacity" class="form-label">Capacity *</label>
                            <input type="number" class="form-control" id="edit_capacity" value="${classroom.capacity}" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_room_type" class="form-label">Room Type *</label>
                            <select class="form-select" id="edit_room_type">
                                <option value="classroom" ${classroom.room_type === 'classroom' ? 'selected' : ''}>Classroom</option>
                                <option value="lab" ${classroom.room_type === 'lab' ? 'selected' : ''}>Laboratory</option>
                                <option value="auditorium" ${classroom.room_type === 'auditorium' ? 'selected' : ''}>Auditorium</option>
                                <option value="seminar_hall" ${classroom.room_type === 'seminar_hall' ? 'selected' : ''}>Seminar Hall</option>
                                <option value="conference_room" ${classroom.room_type === 'conference_room' ? 'selected' : ''}>Conference Room</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="edit_building" class="form-label">Building</label>
                            <input type="text" class="form-control" id="edit_building" value="${classroom.building || ''}">
                        </div>
                        <div class="form-group">
                            <label for="edit_floor" class="form-label">Floor</label>
                            <select class="form-select" id="edit_floor">
                                <option value="">Select Floor</option>
                                <option value="ground" ${classroom.floor === 'ground' ? 'selected' : ''}>Ground Floor</option>
                                <option value="1" ${classroom.floor === '1' ? 'selected' : ''}>1st Floor</option>
                                <option value="2" ${classroom.floor === '2' ? 'selected' : ''}>2nd Floor</option>
                                <option value="3" ${classroom.floor === '3' ? 'selected' : ''}>3rd Floor</option>
                                <option value="4" ${classroom.floor === '4' ? 'selected' : ''}>4th Floor</option>
                                <option value="5" ${classroom.floor === '5' ? 'selected' : ''}>5th Floor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_facilities" class="form-label">Facilities</label>
                            <input type="text" class="form-control" id="edit_facilities" value="${classroom.facilities || ''}" placeholder="e.g., Whiteboard, Projector, Wi-Fi">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Amenities</label>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="edit_has_projector" ${classroom.has_projector ? 'checked' : ''}>
                                    <label for="edit_has_projector">Projector</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="edit_has_ac" ${classroom.has_ac ? 'checked' : ''}>
                                    <label for="edit_has_ac">Air Conditioning</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="edit_has_smart_board" ${classroom.has_smart_board ? 'checked' : ''}>
                                    <label for="edit_has_smart_board">Smart Board</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="edit_is_available" ${classroom.is_available ? 'checked' : ''}>
                                    <label for="edit_is_available">Currently Available</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="edit_is_active" ${classroom.is_active ? 'checked' : ''}>
                                    <label for="edit_is_active">Active (Visible in System)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Show modal
            document.getElementById('editModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        // Close edit modal
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            editingId = null;
        }

        // Update classroom
        function updateClassroom() {
            if (!editingId) {
                alert('No classroom selected for editing!');
                return;
            }

            const classroom = classrooms.find(c => c.id === editingId);
            if (!classroom) {
                alert('Classroom not found!');
                return;
            }

            // Validate required fields
            const name = document.getElementById('edit_name').value.trim();
            const roomNumber = document.getElementById('edit_room_number').value.trim();
            const capacity = parseInt(document.getElementById('edit_capacity').value);

            if (!name) {
                alert('Classroom name is required!');
                return;
            }
            if (!roomNumber) {
                alert('Room number is required!');
                return;
            }
            if (!capacity || capacity < 1) {
                alert('Please enter a valid capacity (minimum 1)');
                return;
            }

            // Check for duplicate room number (excluding current classroom)
            const existing = classrooms.find(c => 
                c.id !== editingId && 
                c.room_number.toLowerCase() === roomNumber.toLowerCase()
            );
            
            if (existing) {
                alert(`Room number "${roomNumber}" already exists for another classroom!`);
                return;
            }

            // Update classroom data
            classroom.name = name;
            classroom.room_number = roomNumber;
            classroom.capacity = capacity;
            classroom.building = document.getElementById('edit_building').value.trim();
            classroom.floor = document.getElementById('edit_floor').value;
            classroom.room_type = document.getElementById('edit_room_type').value;
            classroom.facilities = document.getElementById('edit_facilities').value.trim();
            classroom.has_projector = document.getElementById('edit_has_projector').checked;
            classroom.has_ac = document.getElementById('edit_has_ac').checked;
            classroom.has_smart_board = document.getElementById('edit_has_smart_board').checked;
            classroom.is_available = document.getElementById('edit_is_available').checked;
            classroom.is_active = document.getElementById('edit_is_active').checked;
            classroom.updated_at = new Date().toLocaleString();

            // Save to localStorage
            localStorage.setItem('examClassrooms', JSON.stringify(classrooms));

            // Close modal and reload
            closeEditModal();
            loadClassrooms();

            alert('Classroom updated successfully!');
        }

        // Toggle availability
        function toggleAvailability(id) {
            const classroom = classrooms.find(c => c.id === id);
            if (classroom) {
                classroom.is_available = !classroom.is_available;
                classroom.updated_at = new Date().toLocaleString();
                localStorage.setItem('examClassrooms', JSON.stringify(classrooms));
                loadClassrooms();
                
                const status = classroom.is_available ? 'available' : 'unavailable';
                alert(`Classroom marked as ${status}!`);
            }
        }

        // Delete classroom
        function deleteClassroom(id) {
            if (confirm('Are you sure you want to delete this classroom? This action cannot be undone.')) {
                classrooms = classrooms.filter(c => c.id !== id);
                localStorage.setItem('examClassrooms', JSON.stringify(classrooms));
                loadClassrooms();
                alert('Classroom deleted successfully!');
            }
        }

        // Reset form
        function resetForm() {
            document.getElementById('classroomForm').reset();
            document.getElementById('capacity').value = '40';
            document.getElementById('room_type').value = 'classroom';
            document.getElementById('is_available').checked = true;
            document.getElementById('is_active').checked = true;
        }
    </script>
</body>
</html>
@endsection