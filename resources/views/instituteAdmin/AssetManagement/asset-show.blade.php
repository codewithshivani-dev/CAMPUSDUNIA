@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .as-hero {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: #fff; border-radius: 22px;
        padding: 28px; position: relative; overflow: hidden;
        margin-bottom: 22px;
    }
    .as-hero::after {
        content:""; position:absolute; right:-80px; bottom:-80px;
        width:260px; height:260px; border-radius:50%;
        background: radial-gradient(closest-side, rgba(255,255,255,.18), transparent 70%);
    }
    .as-hero .as-eyebrow {
        font-size:.78rem; font-weight:700; letter-spacing:.08em;
        text-transform:uppercase; opacity:.75;
    }
    .as-hero h3 { font-weight:800; letter-spacing:-.02em; margin:6px 0 10px; }
    .as-hero .as-id {
        display:inline-flex; align-items:center; gap:6px;
        font-family: ui-monospace, monospace; font-size:.8rem;
        background: rgba(255,255,255,.14); color:#fff;
        padding:5px 12px; border-radius:999px; border:1px solid rgba(255,255,255,.18);
    }
    .as-hero .btn-light { border-radius:12px; font-weight:600; padding:10px 18px; }
    .as-hero .btn-outline-light { border-radius:12px; font-weight:600; padding:10px 18px; }

    .as-stat {
        background:#fff; border:1px solid #eef0f5; border-radius:18px;
        padding:20px 22px; height:100%;
        box-shadow: 0 20px 45px -32px rgba(17,24,39,.3);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .as-stat:hover { transform: translateY(-2px); box-shadow: 0 24px 50px -30px rgba(17,24,39,.4); }
    .as-stat .as-lbl { font-size:.78rem; color:#6b7280; font-weight:600; text-transform:uppercase; letter-spacing:.06em; }
    .as-stat .as-val { font-size:1.4rem; font-weight:800; color:#111827; margin-top:8px; letter-spacing:-.01em; }
    .as-stat .as-ico {
        width:42px; height:42px; border-radius:12px;
        display:grid; place-items:center;
        background: linear-gradient(135deg,#eef2ff,#f5f3ff); color:#4f46e5;
        margin-bottom:12px;
    }

    .as-card { background:#fff; border:1px solid #eef0f5; border-radius:20px; overflow:hidden; box-shadow: 0 20px 45px -32px rgba(17,24,39,.3); }
    .as-card-head { padding:18px 22px; border-bottom:1px solid #eef0f5; background: linear-gradient(180deg,#fff,#fafbff); }
    .as-card-head h6 { margin:0; font-weight:700; color:#111827; }
    .as-card-head .as-hint { font-size:.82rem; color:#6b7280; margin-top:4px; }

    .as-table { margin:0; }
    .as-table thead th {
        font-size:.72rem; text-transform:uppercase; letter-spacing:.06em;
        color:#6b7280; font-weight:700; background:#fafbff;
        padding:14px 18px; border-bottom:1px solid #eef0f5;
    }
    .as-table tbody td { padding:16px 18px; border-bottom:1px solid #f3f4f6; vertical-align:top; font-size:.9rem; color:#374151; }
    .as-table tbody tr:hover { background:#fafbff; }
    .as-table tbody tr:last-child td { border-bottom:0; }

    .as-unit-name { font-weight:600; color:#111827; }
    .as-unit-id { font-family: ui-monospace, monospace; font-size:.75rem; color:#6b7280; }
    .as-loc { font-size:.82rem; color:#6b7280; margin-top:2px; }
    .as-lbl-inline { font-size:.75rem; font-weight:700; color:#4f46e5; text-transform:uppercase; letter-spacing:.05em; }
    .as-badge {
        display:inline-flex; align-items:center; gap:6px;
        font-size:.75rem; font-weight:600; padding:5px 11px; border-radius:999px;
    }
    .as-badge.ok { background:#ecfdf5; color:#047857; }
    .as-badge.warn { background:#fffbeb; color:#b45309; }
    .as-badge.info { background:#eef2ff; color:#4338ca; }
    .as-btn-sm {
        display:inline-flex; align-items:center; gap:6px;
        padding:7px 12px; border-radius:10px; font-size:.82rem; font-weight:600;
        border:1px solid #e5e7eb; background:#fff; color:#4b5563;
        text-decoration:none; transition: all .18s ease;
    }
    .as-btn-sm:hover { color:#4f46e5; border-color:#c7d2fe; background:#eef2ff; }
    .as-muted { color:#9ca3af; font-style:italic; }
</style>

{{-- Hero --}}
<div class="as-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 position-relative" style="z-index:1">
        <div>
            <div class="as-eyebrow">Asset Details</div>
            <h3>{{ $asset->asset_name }}</h3>
            <span class="as-id"><i class="fa fa-barcode"></i> {{ $asset->asset_id }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('asset-management.units.index', $asset->asset_id) }}" class="btn btn-light"><i class="fa fa-cubes me-1"></i>Manage Units</a>
            <a href="{{ route('asset-management.assets.index') }}" class="btn btn-outline-light">Back</a>
        </div>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="as-stat">
            <div class="as-ico"><i class="fa fa-folder-open"></i></div>
            <div class="as-lbl">Category</div>
            <div class="as-val">{{ $asset->category->name ?? $asset->category_id }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="as-stat">
            <div class="as-ico"><i class="fa fa-barcode"></i></div>
            <div class="as-lbl">Institute Asset</div>
            <div class="as-val">{{ $amenity->amenity_id }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="as-stat">
            <div class="as-ico"><i class="fa fa-cubes"></i></div>
            <div class="as-lbl">Physical Units</div>
            <div class="as-val">{{ $units->count() }}</div>
        </div>
    </div>
</div>

{{-- Units --}}
<div class="as-card">
    <div class="as-card-head d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6><i class="fa fa-list me-2 text-primary"></i>Physical Units</h6>
            <div class="as-hint">Allocation and assignment are shown separately but come from the same <code>amenity_assignments</code> record.</div>
        </div>
        <span class="as-badge info"><i class="fa fa-cubes"></i> {{ $units->count() }} units</span>
    </div>

    <div class="table-responsive">
        <table class="table as-table align-middle">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th style="width:110px">Specs</th>
                    <th>Allocation</th>
                    <th>Assigned To</th>
                    <th style="width:130px">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($units as $unit)
                @php
                    $row = $active->get($unit->unit_id);
                
                    /*
                    |--------------------------------------------------------------------------
                    | Location
                    |--------------------------------------------------------------------------
                    */
                    $location = [];
                
                    if ($row?->building_id) {
                        $building = $buildings->get($row->building_id);
                
                        $location[] = 'Building: ' . ($building?->name ?? '#' . $row->building_id);
                    }
                
                    if ($row?->block_id) {
                        $block = $blocks->get($row->block_id);
                
                        $location[] = 'Block: ' . ($block?->name ?? '#' . $row->block_id);
                    }
                
                    if ($row?->floor_id) {
                        $floor = $floors->get($row->floor_id);
                
                        $location[] = 'Floor: ' . ($floor?->floor_number ?? '#' . $row->floor_id);
                    }
                
                    if ($row?->room_id) {
                        $room = $rooms->get($row->room_id);
                
                        $location[] = 'Room: ' . ($room?->room_number ?? '#' . $row->room_id);
                    }
                
                    /*
                    |--------------------------------------------------------------------------
                    | Normalize assign_to_ids
                    |
                    | Database may contain:
                    |   - single string: EMP001
                    |   - JSON string: ["EMP001","EMP002"]
                    |   - old array value
                    |--------------------------------------------------------------------------
                    */
                    $assignToIds = $row?->assign_to_ids ?? [];
                
                    if (is_string($assignToIds)) {
                        $assignToIds = trim($assignToIds);
                
                        if ($assignToIds === '') {
                            $assignToIds = [];
                        } else {
                            $decoded = json_decode($assignToIds, true);
                
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $assignToIds = $decoded;
                            } else {
                                // New VARCHAR format: one ID
                                $assignToIds = [$assignToIds];
                            }
                        }
                    }
                
                    if (!is_array($assignToIds)) {
                        $assignToIds = [];
                    }
                
                    $assignToIds = array_values(
                        array_filter(
                            $assignToIds,
                            fn ($id) => $id !== null && trim((string) $id) !== ''
                        )
                    );
                
                    /*
                    |--------------------------------------------------------------------------
                    | Resolve Assigned Names
                    |--------------------------------------------------------------------------
                    */
                    $assignedNames = [];
                
                    foreach ($assignToIds as $assignedId) {
                
                        $assignedId = (string) $assignedId;
                
                        if ($row?->assign_to_type === 'department') {
                
                            $department = $departmentMap->get($assignedId);
                
                            $assignedNames[] = $department?->department ?? $assignedId;
                
                        } elseif ($row?->assign_to_type === 'employee') {
                
                            $employee = $employeeMap->get($assignedId);
                
                            if ($employee) {
                                $assignedNames[] = $employee->name;
                            } else {
                                $assignedNames[] = $assignedId;
                            }
                
                        } elseif ($row?->assign_to_type === 'student') {
                
                            $student = $studentMap->get($assignedId);
                
                            if ($student) {
                
                                $studentName = trim(
                                    ($student->first_name ?? '') . ' ' .
                                    ($student->last_name ?? '')
                                );
                
                                if (!empty($student->registration_number)) {
                                    $studentName .= ' (' . $student->registration_number . ')';
                                }
                
                                $assignedNames[] = $studentName;
                
                            } else {
                                $assignedNames[] = $assignedId;
                            }
                        }
                    }
                @endphp
                <tr>
                    <td>
                        <div class="as-unit-name">Unit {{ $unit->unit_number }}</div>
                        <div class="as-unit-id">{{ $unit->unit_id }}</div>
                    </td>
                    <td>
                        <a href="{{ route('asset-management.units.index', $asset->asset_id) }}" class="as-btn-sm"><i class="fa fa-eye"></i> View</a>
                    </td>
                    <td>
                        @if($row?->status === 'active' && count($location))
                            <div class="as-lbl-inline">{{ $row->assigned_to_type }}</div>
                            <div class="as-loc">{{ implode(' · ', $location) }}</div>
                        @else
                            <span class="as-muted">Not allocated</span>
                        @endif
                    </td>
                    <td>
                        @if($row?->assign_status === 'assigned' && count($assignedNames))
                            <div class="as-lbl-inline">{{ $row->assign_to_type }}</div>
                            <div class="as-loc">{{ implode(', ', $assignedNames) }}</div>
                        @else
                            <span class="as-muted">Not assigned</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $cls = $unit->status === 'maintenance' ? 'warn' : ($unit->status === 'available' ? 'ok' : 'info');
                            $ico = $unit->status === 'maintenance' ? 'fa-screwdriver-wrench' : ($unit->status === 'available' ? 'fa-circle-check' : 'fa-user-check');
                        @endphp
                        <span class="as-badge {{ $cls }}"><i class="fa {{ $ico }}"></i> {{ ucfirst($unit->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="text-center py-5 text-muted">
                            <i class="fa-regular fa-square-plus d-block mb-2" style="font-size:2.4rem; color:#c7d2fe"></i>
                            <div class="fw-semibold text-dark mb-1">No physical units yet</div>
                            <div class="small">Create units from the Manage Units page.</div>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection