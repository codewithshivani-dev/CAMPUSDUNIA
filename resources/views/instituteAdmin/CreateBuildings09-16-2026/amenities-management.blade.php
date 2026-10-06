@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Amenities Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --success-gradient: linear-gradient(135deg, #10b981, #059669);
            --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --info-gradient: linear-gradient(135deg, #0ea5e9, #0284c7);
            --border-color: #e2e8f0;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --shadow: 0 8px 25px rgba(0,0,0,0.05);
        }
        * { box-sizing: border-box; }

        .header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 15px 35px rgba(67,97,238,0.3);
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
            margin: 5px 0 0 0;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
            border: 2px solid var(--border-color);
            max-width: 1400px;
            margin: 0 auto;
        }
        .form-card h2 {
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-card h2 i {
            color: var(--primary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.95rem;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
            background: #f8fafc;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67,97,238,0.1);
            background: white;
        }
        select.form-control {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234361ee' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            padding-right: 40px;
        }

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
        }
        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(67,97,238,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67,97,238,0.4);
        }
        .btn-secondary {
            background: #f1f5f9;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }
        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }
        .btn-success {
            background: var(--success-gradient);
            color: white;
        }
        .btn-success:hover {
            transform: translateY(-2px);
        }
        .btn-danger {
            background: var(--danger-gradient);
            color: white;
        }
        .btn-danger:hover {
            transform: translateY(-2px);
        }
        .btn-outline-primary {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
        }
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .amenity-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .amenity-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 8px 25px rgba(67,97,238,0.12);
            transform: translateY(-4px);
        }
        .amenity-card .amenity-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #e8edff, #f0f4ff);
            color: var(--primary-color);
        }
        .amenity-card .amenity-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        .amenity-card .amenity-count {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }
        .amenity-card .amenity-count strong {
            color: var(--primary-color);
        }
        .amenity-card .amenity-badge {
            display: inline-block;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        .amenity-card .amenity-badge.technology { background: #dbeafe; color: #1e40af; }
        .amenity-card .amenity-badge.security { background: #fef3c7; color: #92400e; }
        .amenity-card .amenity-badge.accessibility { background: #d1fae5; color: #065f46; }
        .amenity-card .amenity-badge.utilities { background: #fce7f3; color: #9d174d; }
        .amenity-card .amenity-badge.hygiene { background: #e0e7ff; color: #3730a3; }
        .amenity-card .amenity-badge.food { background: #fef2f2; color: #991b1b; }
        .amenity-card .amenity-badge.education { background: #ede9fe; color: #5b21b6; }
        .amenity-card .amenity-badge.recreation { background: #d1fae5; color: #065f46; }
        .amenity-card .amenity-badge.medical { background: #fce7f3; color: #9d174d; }
        .amenity-card .amenity-badge.services { background: #dbeafe; color: #1e40af; }
        .amenity-card .amenity-badge.accommodation { background: #fef3c7; color: #92400e; }
        .amenity-card .amenity-badge.general { background: #f1f5f9; color: #475569; }

        .amenity-card .btn-assign {
            width: 100%;
            justify-content: center;
            margin-top: auto;
        }

        .no-amenities {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-muted);
            background: #f8fafc;
            border-radius: 16px;
            border: 2px dashed var(--border-color);
        }
        .no-amenities i {
            font-size: 3rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }
        .no-amenities h3 {
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--success-gradient);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1001;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        .toast.error {
            background: var(--danger-gradient);
        }
        .toast.info {
            background: var(--info-gradient);
        }

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

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
        }
        .status-badge.available { background: #dcfce7; color: #166534; }
        .status-badge.assigned { background: #dbeafe; color: #1e40af; }
        .status-badge.maintenance { background: #fef3c7; color: #92400e; }
        .status-badge.retired { background: #fef2f2; color: #991b1b; }

        @media (max-width: 768px) {
            .header { flex-direction: column; gap: 1rem; text-align: center; }
            .form-card { padding: 1.5rem; }
            .amenities-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1><i class="fas fa-cogs"></i> Amenities Management</h1>
                <p>View and assign amenities to your campus buildings</p>
            </div>
            <div>
                <a href="{{ route('buildings.list') }}" class="btn btn-secondary" style="background:rgba(255,255,255,0.2);color:white;border:2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-university"></i> View Campuses
                </a>
                <a href="{{ route('institute.admin.campus.amenities') }}" class="btn btn-secondary" style="background:rgba(255,255,255,0.2);color:white;border:2px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-plus"></i> Add Amenities
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="form-card">
            <h2><i class="fas fa-list"></i> Available Amenities</h2>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
                <i class="fas fa-info-circle"></i> 
                Showing amenities that have at least one available unit. 
                Click <strong>Assign</strong> to manage units for a specific amenity.
            </p>

            <!-- Building Filter -->
            <div class="form-group">
                <label for="buildingFilter">Filter by Building</label>
                <select id="buildingFilter" class="form-control" onchange="loadAmenities()">
                    <option value="">All Buildings</option>
                    @foreach($buildings ?? [] as $building)
                        <option value="{{ $building->id }}" {{ ($selectedBuilding ?? null) && $selectedBuilding->id == $building->id ? 'selected' : '' }}>
                            {{ $building->name }} @if($building->code)({{ $building->code }})@endif
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Amenities Grid -->
            <div id="amenitiesContainer">
                @if(isset($amenities) && $amenities->count() > 0)
                    <div class="amenities-grid" id="amenitiesGrid">
                        @foreach($amenities as $amenity)
                            <div class="amenity-card" data-amenity-id="{{ $amenity->amenity_id }}">
                                <div class="amenity-icon">
                                    <i class="fas {{ $amenity->icon ?? 'fa-cube' }}"></i>
                                </div>
                                <div class="amenity-name">{{ $amenity->name }}</div>
                                <span class=" d-none amenity-badge {{ $amenity->category ?? 'general' }}">
                                    {{ ucfirst($amenity->category ?? 'General') }}
                                </span>
                                <div class="amenity-count">
                                    <strong>{{ $amenity->units_count ?? 0 }}</strong> unit(s) available
                                </div>
                                <a href="{{ route('institute.admin.amenities.assign', ['amenityId' => $amenity->amenity_id]) }}" 
                                   class="btn btn-primary btn-assign">
                                    <i class="fas fa-arrow-right"></i> Assign
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-amenities">
                        <i class="fas fa-cubes"></i>
                        <h3>No Amenities Available</h3>
                        <p>There are no amenities with available units.</p>
                        <p style="font-size: 0.85rem;">Go to <a href="{{ route('institute.admin.campus.amenities') }}" style="color: var(--primary-color); font-weight: 600;">Campus Infrastructure</a> to add amenities and create units.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage"></span>
    </div>

    <script>
        const API_BASE_URL = '{{ url('/') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const messageEl = document.getElementById('toastMessage');
            
            messageEl.textContent = message;
            toast.className = 'toast' + (type === 'error' ? ' error' : type === 'info' ? ' info' : '');
            toast.querySelector('i').className = type === 'error' ? 'fas fa-exclamation-circle' : 
                                                   type === 'info' ? 'fas fa-info-circle' : 'fas fa-check-circle';
            toast.classList.add('show');
            
            clearTimeout(window.toastTimeout);
            window.toastTimeout = setTimeout(() => toast.classList.remove('show'), 3000);
        }

        async function loadAmenities() {
            const buildingId = document.getElementById('buildingFilter').value;
            const container = document.getElementById('amenitiesContainer');
            
            // Show loading
            container.innerHTML = `
                <div style="text-align: center; padding: 3rem;">
                    <div class="loading-spinner" style="width: 40px; height: 40px; margin: 0 auto;"></div>
                    <p style="margin-top: 1rem; color: var(--text-muted);">Loading amenities...</p>
                </div>
            `;

            try {
                let url = API_BASE_URL + '/institute/admin/campus/amenities';
                if (buildingId) {
                    url += '/' + buildingId;
                }

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                const result = await response.json();

                if (result.success && result.data) {
                    const amenities = result.data;
                    
                    if (amenities.length === 0) {
                        container.innerHTML = `
                            <div class="no-amenities">
                                <i class="fas fa-cubes"></i>
                                <h3>No Amenities Available</h3>
                                <p>There are no amenities with available units in this building.</p>
                            </div>
                        `;
                        return;
                    }

                    let html = `<div class="amenities-grid" id="amenitiesGrid">`;
                    
                    amenities.forEach(amenity => {
                        const category = amenity.category || 'general';
                        const icon = getCategoryIcon(category);
                        const unitCount = amenity.units_count || 0;
                        
                        html += `
                            <div class="amenity-card" data-amenity-id="${amenity.amenity_id}">
                                <div class="amenity-icon">
                                    <i class="fas ${icon}"></i>
                                </div>
                                <div class="amenity-name">${escapeHtml(amenity.name)}</div>
                                <span class="amenity-badge ${category}">
                                    ${getCategoryDisplayName(category)}
                                </span>
                                <div class="amenity-count">
                                    <strong>${unitCount}</strong> unit(s) available
                                </div>
                                <a href="${API_BASE_URL}/institute/admin/amenities/${amenity.amenity_id}/assign" 
                                   class="btn btn-primary btn-assign">
                                    <i class="fas fa-arrow-right"></i> Assign
                                </a>
                            </div>
                        `;
                    });
                    
                    html += `</div>`;
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="no-amenities">
                            <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                            <h3>Error Loading Amenities</h3>
                            <p>${result.message || 'Unable to load amenities. Please try again.'}</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading amenities:', error);
                container.innerHTML = `
                    <div class="no-amenities">
                        <i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>
                        <h3>Error Loading Amenities</h3>
                        <p>${error.message || 'Network error. Please try again.'}</p>
                    </div>
                `;
                showToast('Error loading amenities', 'error');
            }
        }

        function getCategoryIcon(category) {
            const icons = {
                'technology': 'fa-microchip',
                'security': 'fa-shield-alt',
                'accessibility': 'fa-wheelchair',
                'utilities': 'fa-bolt',
                'hygiene': 'fa-hand-sparkles',
                'food': 'fa-utensils',
                'education': 'fa-graduation-cap',
                'recreation': 'fa-gamepad',
                'medical': 'fa-heartbeat',
                'services': 'fa-concierge-bell',
                'accommodation': 'fa-bed',
                'general': 'fa-cube'
            };
            return icons[category] || 'fa-cube';
        }

        function getCategoryDisplayName(category) {
            const names = {
                'technology': 'Technology & Connectivity',
                'security': 'Security & Safety',
                'accessibility': 'Accessibility',
                'utilities': 'Utilities & Power',
                'hygiene': 'Hygiene & Sanitation',
                'food': 'Food & Dining',
                'education': 'Education & Learning',
                'recreation': 'Recreation & Sports',
                'medical': 'Medical & Health',
                'services': 'Services & Amenities',
                'accommodation': 'Accommodation',
                'general': 'General'
            };
            return names[category] || 'General';
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initial load is already handled by server-side rendering
        });
    </script>
</body>
</html>
@endsection