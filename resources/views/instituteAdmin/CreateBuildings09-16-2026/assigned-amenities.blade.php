@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<style>
    .assigned-page {
        width: 100%;
    }

    .assigned-header {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: #fff;
        padding: 24px 28px;
        border-radius: 16px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        box-shadow: 0 15px 35px rgba(67, 97, 238, .25);
    }

    .assigned-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #fff;
    }

    .assigned-header p {
        margin: 6px 0 0;
        color: rgba(255,255,255,.85);
    }

    .assigned-header .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 9px;
        text-decoration: none;
        color: #fff;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.3);
        transition: .2s;
    }

    .assigned-header .back-btn:hover {
        background: rgba(255,255,255,.25);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,.04);
        transition: .2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,.08);
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        font-size: 20px;
    }

    .stat-icon.blue { background: #dbeafe; color: #1d4ed8; }
    .stat-icon.green { background: #dcfce7; color: #15803d; }
    .stat-icon.yellow { background: #fef3c7; color: #a16207; }
    .stat-icon.purple { background: #ede9fe; color: #7e22ce; }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-label {
        color: #64748b;
        font-size: 13px;
        margin-top: 3px;
    }

    .filter-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 24px;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 14px;
        align-items: end;
    }

    .filter-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #fff;
        font-size: 14px;
        box-sizing: border-box;
        transition: .2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67,97,238,.1);
    }

    .btn {
        border: 0;
        border-radius: 8px;
        padding: 9px 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s;
        font-size: 13px;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    .btn-info {
        background: #0ea5e9;
        color: #fff;
    }

    .btn-info:hover {
        background: #0284c7;
        transform: translateY(-1px);
    }

    .btn-primary {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: #fff;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(67,97,238,.3);
    }

    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,.04);
    }

    .table-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .table-card-header h3 {
        margin: 0;
        color: #1e293b;
        font-size: 18px;
    }

    .table-card-header p {
        margin: 5px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .record-count {
        background: #eef2ff;
        color: #4361ee;
        padding: 7px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .assigned-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .assigned-table th {
        background: #f8fafc;
        color: #334155;
        padding: 13px 14px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .assigned-table td {
        padding: 13px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
        font-size: 13px;
        color: #334155;
    }

    .assigned-table tbody tr:hover {
        background: #f8fafc;
    }

    .assigned-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .serial-number {
        font-weight: 700;
        color: #94a3b8;
        font-size: 13px;
    }

    .amenity-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .amenity-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .amenity-info {
        display: flex;
        flex-direction: column;
    }

    .amenity-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 14px;
    }

    .amenity-category {
        color: #64748b;
        font-size: 11px;
        margin-top: 2px;
    }

    .code-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
    }

    .category-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 14px;
        font-size: 11px;
        font-weight: 700;
    }

    .category-badge.technology { background: #dbeafe; color: #1e40af; }
    .category-badge.security { background: #fef3c7; color: #92400e; }
    .category-badge.accessibility { background: #d1fae5; color: #065f46; }
    .category-badge.utilities { background: #fce7f3; color: #9d174d; }
    .category-badge.hygiene { background: #e0e7ff; color: #3730a3; }
    .category-badge.food { background: #fef2f2; color: #991b1b; }
    .category-badge.education { background: #ede9fe; color: #5b21b6; }
    .category-badge.recreation { background: #d1fae5; color: #065f46; }
    .category-badge.medical { background: #fce7f3; color: #9d174d; }
    .category-badge.services { background: #dbeafe; color: #1e40af; }
    .category-badge.accommodation { background: #fef3c7; color: #92400e; }
    .category-badge.general { background: #f1f5f9; color: #475569; }

    .location-cell .location-name {
        font-weight: 600;
        color: #1e293b;
    }

    .location-cell .assignment-type {
        color: #64748b;
        font-size: 11px;
        margin-top: 2px;
    }

    .unit-count {
        display: inline-block;
        background: #ecfdf5;
        color: #047857;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 15px;
        background: #dcfce7;
        color: #166534;
        font-size: 11px;
        font-weight: 700;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 6px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 55px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .empty-state h3 {
        margin: 0 0 7px;
        color: #1e293b;
    }

    .empty-state p {
        color: #64748b;
        margin: 0 0 18px;
    }

    .toast {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 10000;
        background: #059669;
        color: #fff;
        padding: 13px 18px;
        border-radius: 9px;
        box-shadow: 0 8px 25px rgba(0,0,0,.15);
        transform: translateY(100px);
        opacity: 0;
        transition: .3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast.error {
        background: #dc2626;
    }

    .toast.info {
        background: #0ea5e9;
    }

    @media(max-width: 1000px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-card {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 600px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .assigned-header h1 {
            font-size: 22px;
        }

        .assigned-header {
            padding: 16px 18px;
        }

        .table-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

@php
    $assignedAmenities = $assignedAmenities ?? [];

    if ($assignedAmenities instanceof \Illuminate\Support\Collection) {
        $assignedAmenities = $assignedAmenities->toArray();
    }

    $assignedAmenities = is_array($assignedAmenities)
        ? array_values($assignedAmenities)
        : [];

    $totalAssignments = count($assignedAmenities);

    $totalUnits = 0;
    $buildingIds = [];
    $blockIds = [];
    $floorIds = [];
    $roomIds = [];

    foreach ($assignedAmenities as $item) {
        if (is_object($item)) {
            $item = $item->toArray();
        }

        $units = $item['units'] ?? [];

        if ($units instanceof \Illuminate\Support\Collection) {
            $units = $units->toArray();
        }

        if (!is_array($units)) {
            $units = [];
        }

        if (empty($units) && !empty($item['unit'])) {
            $units = [$item['unit']];
        }

        $totalUnits += count($units);

        $assignment = $item['assignment'] ?? [];

        if (is_object($assignment)) {
            $assignment = $assignment->toArray();
        }

        $type = $assignment['assigned_to_type'] ?? '';
        $id = $assignment['assigned_to_id'] ?? '';

        if ($type === 'building' && $id !== '') {
            $buildingIds[$id] = true;
        }

        if ($type === 'block' && $id !== '') {
            $blockIds[$id] = true;
        }

        if ($type === 'floor' && $id !== '') {
            $floorIds[$id] = true;
        }

        if ($type === 'room' && $id !== '') {
            $roomIds[$id] = true;
        }
    }
@endphp

<div class="assigned-page">

    <div class="assigned-header">
        <div>
            <h1>
                <i class="fas fa-check-double"></i>
                Assigned Amenities
            </h1>
            <p>
                All amenities assigned to buildings, blocks, floors and rooms
            </p>
        </div>
        <a href="{{ route('institute.admin.amenities.management') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Amenities
        </a>
    </div>

    {{-- STATISTICS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-cubes"></i>
            </div>
            <div class="stat-number">{{ $totalUnits }}</div>
            <div class="stat-label">Total Assigned Units</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-number">{{ count($buildingIds) }}</div>
            <div class="stat-label">Buildings with Amenities</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon yellow">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-number">{{ count($blockIds) + count($floorIds) }}</div>
            <div class="stat-label">Blocks / Floors with Amenities</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-number">{{ count($roomIds) }}</div>
            <div class="stat-label">Rooms with Amenities</div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="filter-card">
        <div class="filter-group">
            <label><i class="fas fa-search"></i> Search</label>
            <input type="text" id="searchInput" class="form-control" placeholder="Search amenity, code, or location...">
        </div>

        <div class="filter-group">
            <label>Assignment Type</label>
            <select id="assignmentTypeFilter" class="form-control">
                <option value="">All Types</option>
                <option value="building">Building</option>
                <option value="block">Block</option>
                <option value="floor">Floor</option>
                <option value="room">Room</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Category</label>
            <select id="categoryFilter" class="form-control">
                <option value="">All Categories</option>
                <option value="technology">Technology</option>
                <option value="security">Security</option>
                <option value="accessibility">Accessibility</option>
                <option value="utilities">Utilities</option>
                <option value="hygiene">Hygiene</option>
                <option value="food">Food</option>
                <option value="education">Education</option>
                <option value="recreation">Recreation</option>
                <option value="medical">Medical</option>
                <option value="services">Services</option>
                <option value="accommodation">Accommodation</option>
            </select>
        </div>

        <button type="button" class="btn btn-secondary" onclick="resetFilters()">
            <i class="fas fa-undo"></i> Reset
        </button>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <div class="table-card-header">
            <div>
                <h3><i class="fas fa-list-check"></i> Assigned Amenities</h3>
                <p>Click View to see full details, specifications, and edit assignments</p>
            </div>
            <div class="record-count" id="recordCount">
                {{ $totalAssignments }} Assignment(s)
            </div>
        </div>

        <div class="table-responsive">
            @if($totalAssignments > 0)
                <table class="assigned-table" id="assignedAmenitiesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Amenity</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Assigned To</th>
                            <th>Units</th>
                            <th>Assigned Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedAmenities as $index => $item)
                            @php
                                if (is_object($item)) {
                                    $item = $item->toArray();
                                }

                                $amenity = $item['amenity'] ?? [];

                                if (is_object($amenity)) {
                                    $amenity = $amenity->toArray();
                                }

                                $assignment = $item['assignment'] ?? [];

                                if (is_object($assignment)) {
                                    $assignment = $assignment->toArray();
                                }

                                $units = $item['units'] ?? [];

                                if ($units instanceof \Illuminate\Support\Collection) {
                                    $units = $units->toArray();
                                }

                                if (!is_array($units)) {
                                    $units = [];
                                }

                                if (empty($units) && !empty($item['unit'])) {
                                    $units = [$item['unit']];
                                }

                                $amenityName = $amenity['name'] ?? $item['amenity_name'] ?? 'Unknown Amenity';
                                $amenityCode = $amenity['code'] ?? $amenity['amenity_id'] ?? $item['amenity_id'] ?? 'N/A';
                                $category = strtolower($amenity['category'] ?? $item['category'] ?? 'general');

                                $assignmentType = strtolower($assignment['assigned_to_type'] ?? '');
                                $assignmentTypeDisplay = $assignmentType ? ucfirst($assignmentType) : 'N/A';
                                $locationName = $item['location_name'] ?? $assignment['assigned_to_display'] ?? 'N/A';

                                $assignedDate = 'N/A';
                                if (!empty($assignment['assigned_at'])) {
                                    try {
                                        $assignedDate = \Carbon\Carbon::parse($assignment['assigned_at'])->format('d M Y h:i A');
                                    } catch (\Exception $e) {
                                        $assignedDate = $assignment['assigned_at'];
                                    }
                                }

                                $assignmentId = $assignment['assignment_id'] ?? $item['assignment_id'] ?? '';

                                $searchText = strtolower(
                                    $amenityName . ' ' .
                                    $amenityCode . ' ' .
                                    $category . ' ' .
                                    $locationName . ' ' .
                                    $assignmentTypeDisplay
                                );
                            @endphp

                            <tr class="assignment-row"
                                data-assignment-type="{{ $assignmentType }}"
                                data-category="{{ $category }}"
                                data-search="{{ $searchText }}">

                                <td class="serial-number">{{ $index + 1 }}</td>

                                <td>
                                    <div class="amenity-cell">
                                        <div class="amenity-icon">
                                            <i class="fas {{ $amenity['icon'] ?? 'fa-cube' }}"></i>
                                        </div>
                                        <div class="amenity-info">
                                            <span class="amenity-name">{{ $amenityName }}</span>
                                            <span class="amenity-category">{{ ucfirst($category) }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="code-badge">{{ $amenityCode }}</span>
                                </td>

                                <td>
                                    <span class="category-badge {{ $category }}">{{ ucfirst($category) }}</span>
                                </td>

                                <td class="location-cell">
                                    <div class="location-name">{{ $locationName }}</div>
                                    <div class="assignment-type">{{ $assignmentTypeDisplay }}</div>
                                </td>

                                <td>
                                    <span class="unit-count">{{ count($units) }} Unit(s)</span>
                                </td>

                                <td>{{ $assignedDate }}</td>

                                <td>
                                    <span class="status-badge">
                                        <i class="fas fa-check-circle"></i>
                                        {{ ucfirst($assignment['status'] ?? 'Assigned') }}
                                    </span>
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        @if($assignmentId)
                                            <a href="{{ route('institute.admin.amenities.assigned.view', $assignmentId) }}"
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        @elseif(!empty($units) && !empty($units[0]['unit_id']))
                                            <a href="{{ route('institute.admin.amenities.assigned.view.unit', $units[0]['unit_id']) }}"
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>No Assigned Amenities</h3>
                    <p>There are currently no active amenity assignments available for this institute.</p>
                    <a href="{{ route('institute.admin.amenities.management') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Manage Amenities
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="toast" class="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage"></span>
</div>

<script>
    const CSRF_TOKEN = @json(csrf_token());

    function applyFilters() {
        const search = document.getElementById('searchInput').value.toLowerCase().trim();
        const type = document.getElementById('assignmentTypeFilter').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value.toLowerCase();

        const rows = document.querySelectorAll('.assignment-row');
        let visibleCount = 0;

        rows.forEach(function(row) {
            const rowSearch = (row.dataset.search || '').toLowerCase();
            const rowType = (row.dataset.assignmentType || '').toLowerCase();
            const rowCategory = (row.dataset.category || '').toLowerCase();

            const visible = (!search || rowSearch.includes(search)) &&
                            (!type || rowType === type) &&
                            (!category || rowCategory === category);

            row.style.display = visible ? '' : 'none';

            if (visible) {
                visibleCount++;
            }
        });

        document.getElementById('recordCount').textContent = visibleCount + ' Assignment(s)';
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('assignmentTypeFilter').value = '';
        document.getElementById('categoryFilter').value = '';
        applyFilters();
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const messageElement = document.getElementById('toastMessage');

        messageElement.textContent = message;
        toast.className = 'toast';

        if (type === 'error') {
            toast.classList.add('error');
            toast.querySelector('i').className = 'fas fa-exclamation-circle';
        } else if (type === 'info') {
            toast.classList.add('info');
            toast.querySelector('i').className = 'fas fa-info-circle';
        } else {
            toast.querySelector('i').className = 'fas fa-check-circle';
        }

        toast.classList.add('show');

        clearTimeout(window.toastTimeout);
        window.toastTimeout = setTimeout(function() {
            toast.classList.remove('show');
        }, 3000);
    }

    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('assignmentTypeFilter').addEventListener('change', applyFilters);
    document.getElementById('categoryFilter').addEventListener('change', applyFilters);

    document.addEventListener('DOMContentLoaded', function() {
        applyFilters();
    });
</script>

@endsection