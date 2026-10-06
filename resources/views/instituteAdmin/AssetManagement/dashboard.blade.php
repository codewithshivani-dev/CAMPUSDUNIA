@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .db-hero {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color:#fff; border-radius:22px; padding:32px;
        position:relative; overflow:hidden; margin-bottom:24px;
    }
    .db-hero::before {
        content:""; position:absolute; right:-80px; top:-80px;
        width:280px; height:280px; border-radius:50%;
        background: radial-gradient(closest-side, rgba(255,255,255,.2), transparent 70%);
    }
    .db-hero::after {
        content:""; position:absolute; left:-60px; bottom:-80px;
        width:220px; height:220px; border-radius:50%;
        background: radial-gradient(closest-side, rgba(124,58,237,.35), transparent 70%);
    }
    .db-hero .db-eyebrow {
        font-size:.78rem; font-weight:700; letter-spacing:.08em;
        text-transform:uppercase; opacity:.75;
    }
    .db-hero h2 { font-weight:800; letter-spacing:-.02em; margin:6px 0 8px; }
    .db-hero .btn-light {
        border-radius:12px; font-weight:600; padding:10px 20px;
        border:0; color:#4f46e5;
    }

    .stat-tile {
        border-radius: 18px; padding: 22px 24px; color: #fff; height: 100%;
        position: relative; overflow: hidden;
        box-shadow: 0 18px 40px -22px rgba(67,97,238,.45);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .stat-tile:hover { transform: translateY(-3px); box-shadow: 0 22px 48px -20px rgba(67,97,238,.55); }
    .stat-tile::after {
        content:""; position:absolute; right:-40px; bottom:-40px;
        width:140px; height:140px; border-radius:50%;
        background: radial-gradient(closest-side, rgba(255,255,255,.18), transparent 70%);
    }
    .stat-tile .st-val { font-size: 1.9rem; font-weight: 800; line-height: 1.1; position: relative; z-index: 1; }
    .stat-tile .st-lbl { font-size: .8rem; font-weight: 600; opacity: .92; margin-top: 4px; position: relative; z-index: 1; }
    .stat-tile .st-ico {
        position: absolute; right: 18px; top: 50%; transform: translateY(-50%);
        font-size: 2.2rem; opacity: .28; z-index: 0;
    }
    .tile-1 { background: linear-gradient(135deg,#4361ee,#3a0ca3); }
    .tile-2 { background: linear-gradient(135deg,#4f46e5,#7c3aed); }
    .tile-3 { background: linear-gradient(135deg,#6366f1,#8b5cf6); }
    .tile-4 { background: linear-gradient(135deg,#3b82f6,#6366f1); }

    .db-card { background:#fff; border:1px solid #eef0f5; border-radius:20px; overflow:hidden; box-shadow: 0 20px 45px -34px rgba(17,24,39,.3); }
    .db-card-head {
        padding:16px 22px; border-bottom:1px solid #eef0f5;
        background: linear-gradient(180deg,#fff,#fafbff);
        display:flex; align-items:center; justify-content:space-between;
        flex-wrap: wrap; gap: 10px;
    }
    .db-card-head h6 { margin:0; font-weight:700; color:#111827; }
    .db-chip {
        font-size:.72rem; font-weight:600; padding:4px 10px; border-radius:999px;
        background:#eef2ff; color:#4338ca;
    }
    .db-table { margin:0; }
    .db-table thead th {
        font-size:.72rem; text-transform:uppercase; letter-spacing:.06em;
        color:#6b7280; font-weight:700; background:#fafbff;
        padding:12px 18px; border-bottom:1px solid #eef0f5;
    }
    .db-table tbody td { padding:14px 18px; border-bottom:1px solid #f3f4f6; font-size:.9rem; color:#374151; }
    .db-table tbody tr:hover { background:#fafbff; }
    .db-table tbody tr:last-child td { border-bottom:0; }
    .db-unit {
        font-family: ui-monospace, monospace; font-size:.78rem; font-weight:600;
        padding:4px 9px; border-radius:7px; background:#f3f4f6; color:#4b5563;
    }
    .db-level {
        display:inline-flex; align-items:center; gap:6px;
        font-size:.78rem; font-weight:600; padding:3px 9px; border-radius:999px;
        background:#eef2ff; color:#4338ca; text-transform:capitalize;
    }
    .db-date { color:#6b7280; font-size:.85rem; }
    .db-empty { text-align:center; padding:40px 20px; color:#9ca3af; }
    .db-empty i { font-size:2rem; color:#c7d2fe; display:block; margin-bottom:10px; }

    .badge-due {
        background:#fee2e2; color:#b91c1c; font-weight:700;
        font-size:.72rem; padding:4px 10px; border-radius:999px;
        display:inline-flex; align-items:center; gap:5px;
    }

    .asset-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px; padding: 12px 0 18px;
    }
    .asset-toolbar .toolbar-links { display: flex; flex-wrap: wrap; gap: 6px; }
    .asset-toolbar .toolbar-links a {
        font-size: .95rem; font-weight: 600; color: #4f46e5;
        padding: 7px 16px; border-radius: 10px; background: #fff;
        border: 1px solid #eef0f5; text-decoration: none;
        transition: all .2s ease;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .asset-toolbar .toolbar-links a:hover {
        background: #eef2ff; border-color: #c7d2fe;
        color: #3a0ca3; transform: translateY(-1px);
    }
    .asset-toolbar .toolbar-links a i { font-size: .78rem; opacity: .85; }

    .btn-create {
        background: linear-gradient(135deg,#4361ee,#3a0ca3);
        color: #fff; border: 0; font-weight: 600; font-size: .85rem;
        padding: 10px 22px; border-radius: 12px; text-decoration: none;
        box-shadow: 0 14px 30px -14px rgba(67,97,238,.7);
        transition: all .2s ease;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-create:hover { transform: translateY(-2px); color: #fff; }

    /* ===== universal per-section filter row ===== */
    .sec-filter {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff; border: 1px solid #eef0f5;
        border-radius: 10px; padding: 4px 6px; font-size: .78rem;
    }
    .sec-filter input,
    .sec-filter select {
        border: 0; outline: 0; background: transparent;
        font-size: .78rem; color: #374151; padding: 4px 6px; min-width: 90px;
    }
    .sec-filter input::placeholder { color:#9ca3af; }
    .sec-filter button {
        border: 0; background: #eef2ff; color: #4338ca;
        border-radius: 7px; padding: 4px 9px; font-size: .74rem;
        font-weight: 600; cursor: pointer;
    }
    .sec-filter button:hover { background:#e0e7ff; }
    .sec-filter .reset { background:#f3f4f6; color:#6b7280; }
    .sec-filter .reset:hover { background:#e5e7eb; }

    /* live "no match" row for filtered tables */
    .filter-empty { display:none; }
    .filter-empty.show { display: table-row; }

    /* ===== asset name + meta cell styling ===== */
    .entity-name {
        font-weight: 600;
        color: #111827;
        font-size: .88rem;
        line-height: 1.25;
    }
    .entity-meta {
        font-size: .74rem;
        color: #6b7280;
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .entity-meta i { font-size: .68rem; opacity: .8; }
    .unit-line { margin-top: 5px; display: block; }
</style>

{{-- ===== Toolbar ===== --}}
<div class="asset-toolbar">
    <div class="toolbar-links">
        <a href="{{ route('asset-management.dashboard') }}"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="{{ route('asset-management.categories.index') }}"><i class="fa-solid fa-folder"></i> Categories</a>
        <a href="{{ route('asset-management.assets.index') }}"><i class="fa-solid fa-box"></i> Assets</a>
        <a href="{{ route('asset-management.assignment') }}"><i class="fa-solid fa-user-check"></i> Assignments</a>
        <a href="{{ route('asset-management.allocation') }}"><i class="fa-solid fa-location-dot"></i> Allocations</a>
        <a href="{{ route('asset-management.assignment-history') }}" class="d-none"><i class="fa-solid fa-list-check"></i> Assignment History</a>
        <a href="{{ route('asset-management.allocation-history') }}" class="d-none"><i class="fa-solid fa-clock-rotate-left"></i> Allocation History</a>
        <a href="{{ route('asset-management.reports') }}" class="d-none"><i class="fa-solid fa-file-lines"></i> Reports</a>
    </div>
    <div>
        <a href="{{ route('asset-management.assets.create') }}" class="btn-create">
            <i class="fa-solid fa-plus"></i> Register Asset
        </a>
    </div>
</div>

{{-- ===== Stat tiles ===== --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile tile-1">
            <div class="st-val">{{ number_format($stats['assets'] ?? 0) }}</div>
            <div class="st-lbl">Total Assets</div>
            <i class="fa-solid fa-box st-ico"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile tile-2">
            <div class="st-val">{{ number_format($stats['units'] ?? 0) }}</div>
            <div class="st-lbl">Total Units</div>
            <i class="fa-solid fa-cubes st-ico"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile tile-3">
            <div class="st-val">{{ number_format($stats['allocated'] ?? 0) }}</div>
            <div class="st-lbl">Allocated Units</div>
            <i class="fa-solid fa-location-dot st-ico"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-tile tile-4">
            <div class="st-val">{{ number_format($stats['maintenance'] ?? 0) }}</div>
            <div class="st-lbl">Under Maintenance</div>
            <i class="fa-solid fa-screwdriver-wrench st-ico"></i>
        </div>
    </div>
</div>

{{-- ===== Recent Allocations + Recent Assignments ===== --}}
<div class="row g-4 mb-4">
    {{-- Recent Allocations --}}
    <div class="col-lg-6">
        <div class="db-card h-100">
            <div class="db-card-head">
                <h6><i class="fa-solid fa-location-dot me-2" style="color:#4f46e5;"></i>Recent Allocations</h6>
                <div class="d-flex align-items-center gap-2">
                    <form class="sec-filter" onsubmit="return false;">
                        <input type="text" id="allocSearch" placeholder="Search asset / unit / location" oninput="filterAlloc()">
                        <select id="allocType" onchange="filterAlloc()">
                            <option value="">All levels</option>
                            <option value="building">Building</option>
                            <option value="block">Block</option>
                            <option value="floor">Floor</option>
                            <option value="room">Room</option>
                        </select>
                    </form>
                    <span class="db-chip">{{ ($recentAllocations ?? collect())->count() }}</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table db-table mb-0" id="allocTable">
                    <thead><tr><th>Asset / Unit</th><th>Location</th><th>Date</th></tr></thead>
                    <tbody>
                    @forelse($recentAllocations ?? [] as $a)
                        @php
                            // Support multiple possible relation / column names gracefully
                            $assetName    = $a->asset->name
                                            ?? $a->asset_name
                                            ?? $a->unit->asset->name
                                            ?? null;
                            $locationName = $a->location_name
                                            ?? $a->assignedTo->name
                                            ?? null;
                            $locationType = $a->assigned_to_type ?? $a->location_type ?? null;
                        @endphp
                        <tr data-unit="{{ strtolower($a->unit_id) }}"
                            data-type="{{ strtolower($locationType ?? '') }}"
                            data-asset="{{ strtolower($assetName ?? '') }}"
                            data-location="{{ strtolower($locationName ?? '') }}">
                            <td>
                                @if($assetName)
                                    <div class="entity-name">{{ $assetName }}</div>
                                @endif
                                <span class="db-unit unit-line">{{ $a->unit_id }}</span>
                            </td>
                            <td>
                                <span class="db-level">{{ ucfirst($locationType ?? '—') }}</span>
                                @if($locationName)
                                    <div class="entity-meta">
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ $locationName }}
                                    </div>
                                @endif
                            </td>
                            <td class="db-date">
                                @php
                                    $allocationDate = $a->assigned_at ?? $a->created_at ?? null;
                                @endphp
                            
                                @if($allocationDate)
                                    {{ \Carbon\Carbon::parse($allocationDate)->format('d M Y') }}<br>
                                    {{ \Carbon\Carbon::parse($allocationDate)->format('h:i A') }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">
                            <div class="db-empty">
                                <i class="fa-regular fa-map"></i>
                                <div class="fw-semibold text-dark mb-1">No active allocations</div>
                                <div class="small">Allocate units to see them here.</div>
                            </div>
                        </td></tr>
                    @endforelse
                    <tr class="filter-empty" id="allocFilterEmpty"><td colspan="3" class="text-center text-muted py-3 small">No matching allocations</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Assignments --}}
    <div class="col-lg-6">
        <div class="db-card h-100">
            <div class="db-card-head">
                <h6><i class="fa-solid fa-user-check me-2" style="color:#4f46e5;"></i>Recent Assignments</h6>
                <div class="d-flex align-items-center gap-2">
                    <form class="sec-filter" onsubmit="return false;">
                        <input type="text" id="assignSearch" placeholder="Search asset / unit / person" oninput="filterAssign()">
                        <select id="assignType" onchange="filterAssign()">
                            <option value="">All types</option>
                            <option value="employee">Employee</option>
                            <option value="department">Department</option>
                            <option value="student">Student</option>
                        </select>
                    </form>
                    <span class="db-chip">{{ ($recentAssignments ?? collect())->count() }}</span>
                </div>
            </div>
            <div class="table-responsive" style="height: inherit;">
                <table class="table db-table mb-0" id="assignTable" style="height: inherit;">
                    <thead><tr><th>Asset / Unit</th><th>Assigned To</th><th>Date</th></tr></thead>
                    <tbody>
                    @forelse($recentAssignments ?? [] as $a)
                        @php
                            $assetName    = $a->asset->name
                                            ?? $a->asset_name
                                            ?? $a->unit->asset->name
                                            ?? null;
                            $assigneeName = $a->assignee_name
                                            ?? $a->assignTo->name
                                            ?? null;
                            $assigneeMeta = $a->assignee_meta ?? null;
                            $assigneeType = $a->assign_to_type ?? $a->assigned_to_type ?? null;
                        @endphp
                        <tr data-unit="{{ strtolower($a->unit_id) }}"
                            data-type="{{ strtolower($assigneeType ?? '') }}"
                            data-asset="{{ strtolower($assetName ?? '') }}"
                            data-assignee="{{ strtolower(trim(($assigneeName ?? '') . ' ' . ($assigneeMeta ?? ''))) }}">
                            <td>
                                @if($assetName)
                                    <div class="entity-name">{{ $assetName }}</div>
                                @endif
                                <span class="db-unit unit-line">{{ $a->unit_id }}</span>
                            </td>
                            <td>
                                <span class="db-level">{{ ucfirst($assigneeType ?? '—') }}</span>
                                @if($assigneeName)
                                    <div class="entity-meta">
                                        <i class="fa-solid fa-user"></i>
                                        {{ $assigneeName }}
                                    </div>
                                    @if(!empty($a->assignee_meta))
                                        <div class="entity-meta" style="margin-top:2px;">
                                            <i class="fa-solid fa-id-badge"></i>
                                            {{ $a->assignee_meta }}
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="db-date">
                                @php
                                    $assignmentDate = $a->assign_at ?? $a->created_at ?? null;
                                @endphp
                            
                                @if($assignmentDate)
                                    {{ \Carbon\Carbon::parse($assignmentDate)->format('d M Y') }}<br>
                                    {{ \Carbon\Carbon::parse($assignmentDate)->format('h:i A') }}
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">
                            <div class="db-empty">
                                <i class="fa-regular fa-user"></i>
                                <div class="fw-semibold text-dark mb-1">No active assignments</div>
                                <div class="small">Assign units to see them here.</div>
                            </div>
                        </td></tr>
                    @endforelse
                    <tr class="filter-empty" id="assignFilterEmpty"><td colspan="3" class="text-center text-muted py-3 small">No matching assignments</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ===== By Status + By Category + Warranty ===== --}}
<div class="row g-4 mb-4">
    <div class="col-xl-4">
        <div class="db-card h-100">
            <div class="db-card-head">
                <h6><i class="fa-solid fa-chart-pie me-2" style="color:#4f46e5;"></i>By Status</h6>
            </div>
            <div class="p-4">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="db-chip" style="background:#eef2ff;color:#4338ca;"><i class="fa-solid fa-circle me-1" style="font-size:.5rem;vertical-align:middle;"></i> Allocated {{ $stats['allocated'] ?? 0 }}</span>
                    <span class="db-chip" style="background:#ecfdf5;color:#047857;"><i class="fa-solid fa-circle me-1" style="font-size:.5rem;vertical-align:middle;"></i> Available {{ $stats['available'] ?? 0 }}</span>
                    <span class="db-chip" style="background:#fef3c7;color:#b45309;"><i class="fa-solid fa-circle me-1" style="font-size:.5rem;vertical-align:middle;"></i> Maintenance {{ $stats['maintenance'] ?? 0 }}</span>
                    <span class="db-chip" style="background:#e0e7ff;color:#3730a3;"><i class="fa-solid fa-circle me-1" style="font-size:.5rem;vertical-align:middle;"></i> Assigned {{ $stats['assigned'] ?? 0 }}</span>
                </div>
                <div class="small text-muted">Quick snapshot of the current asset lifecycle states.</div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="db-card h-100">
            <div class="db-card-head">
                <h6><i class="fa-solid fa-folder me-2" style="color:#4f46e5;"></i>By Category</h6>
                <form class="sec-filter" onsubmit="return false;">
                    <input type="text" id="catSearch" placeholder="Filter" oninput="filterCat()">
                </form>
            </div>
            <div class="table-responsive" style="max-height:230px;overflow-y:auto;">
                <table class="table db-table mb-0" id="catTable">
                    <thead><tr><th>Category</th><th class="text-end">Assets</th></tr></thead>
                    <tbody>
                    @forelse($assetsByCategory ?? [] as $c)
                        <tr data-cat="{{ strtolower($c->category_name) }}">
                            <td>{{ $c->category_name }}</td>
                            <td class="text-end fw-semibold">{{ $c->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2"><div class="db-empty py-4"><i class="fa-regular fa-folder-open"></i><div class="small">No categories yet</div></div></td></tr>
                    @endforelse
                    <tr class="filter-empty" id="catFilterEmpty"><td colspan="2" class="text-center text-muted py-3 small">No matching categories</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="db-card h-100">
            <div class="db-card-head">
                <h6><i class="fa-solid fa-shield-halved me-2" style="color:#4f46e5;"></i>Warranty Expiring</h6>
                <span class="db-chip">Next 30 days</span>
            </div>
            <div class="table-responsive" style="height: inherit;">
                <table class="table db-table mb-0" style="height: inherit;">
                    <thead><tr><th>Asset</th><th>Tag</th><th>Expires</th></tr></thead>
                    <tbody>
                    @forelse($warrantyExpiring ?? [] as $w)
                        <tr>
                            <td>{{ $w->asset_name }}</td>
                            <td><span class="db-unit">{{ $w->tag }}</span></td>
                            <td class="db-date">
                                {{ \Carbon\Carbon::parse($w->warranty_end)->format('d M Y') }}<br>
                                {{ \Carbon\Carbon::parse($w->warranty_end)->format('h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3"><div class="db-empty py-4"><i class="fa-regular fa-shield-halved"></i><div class="small">No warranties expiring soon</div></div></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ===== Maintenance Due ===== --}}
<div class="db-card mb-4">
    <div class="db-card-head">
        <h6><i class="fa-solid fa-triangle-exclamation me-2" style="color:#f59e0b;"></i>Maintenance Due &amp; Overdue</h6>
        <div class="d-flex align-items-center gap-2">
            <form class="sec-filter" onsubmit="return false;">
                <input type="text" id="maintSearch" placeholder="Search asset / task" oninput="filterMaint()">
                <select id="maintType" onchange="filterMaint()">
                    <option value="">All types</option>
                    <option value="servicing">Servicing</option>
                    <option value="inspection">Inspection</option>
                    <option value="calibration">Calibration</option>
                </select>
            </form>
            <span class="db-chip">{{ ($maintenanceDue ?? collect())->count() }}</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table db-table mb-0" id="maintTable">
            <thead><tr><th>Asset</th><th>Task</th><th>Type</th><th>Due</th></tr></thead>
            <tbody>
            @forelse($maintenanceDue ?? [] as $m)
                <tr data-asset="{{ strtolower($m->asset_name) }}" data-task="{{ strtolower($m->task) }}" data-type="{{ strtolower($m->type) }}">
                    <td>{{ $m->asset_name }}</td>
                    <td>{{ $m->task }}</td>
                    <td>{{ $m->type }}</td>
                    <td>
                        <span class="db-date me-2">
                            {{ \Carbon\Carbon::parse($m->due_date)->format('d M Y') }}<br>
                            {{ \Carbon\Carbon::parse($m->due_date)->format('h:i A') }}
                        </span>
                        <span class="badge-due"><i class="fa-solid fa-circle-exclamation"></i> Due</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">
                    <div class="db-empty">
                        <i class="fa-regular fa-circle-check"></i>
                        <div class="fw-semibold text-dark mb-1">No maintenance due</div>
                        <div class="small">All assets are up to date.</div>
                    </div>
                </td></tr>
            @endforelse
            <tr class="filter-empty" id="maintFilterEmpty"><td colspan="4" class="text-center text-muted py-3 small">No matching maintenance records</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ===== Recently Registered + Recent Activity ===== --}}
<div class="row g-4">
    <div class="col-lg-7">
        <div class="db-card h-100">
            <div class="db-card-head">
                <h6><i class="fa-solid fa-clock-rotate-left me-2" style="color:#4f46e5;"></i>Recently Registered</h6>
                <div class="d-flex align-items-center gap-2">
                    <form class="sec-filter" onsubmit="return false;">
                        <input type="text" id="recentSearch" placeholder="Search tag / asset" oninput="filterRecent()">
                        <select id="recentStatus" onchange="filterRecent()">
                            <option value="">All status</option>
                            <option value="in use">In Use</option>
                            <option value="available">Available</option>
                        </select>
                    </form>
                    <span class="db-chip">{{ ($recentlyRegistered ?? collect())->count() }}</span>
                </div>
            </div>
            <div class="table-responsive" style="height: inherit;">
                <table class="table db-table mb-0" id="recentTable" style="height: inherit;">
                    <thead><tr><th>Tag</th><th>Asset</th><th>Category</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($recentlyRegistered ?? [] as $r)
                        <tr data-tag="{{ strtolower($r->tag) }}" data-asset="{{ strtolower($r->asset_name) }}" data-status="{{ strtolower($r->status) }}">
                            <td><span class="db-unit">{{ $r->tag }}</span></td>
                            <td>{{ $r->asset_name }}</td>
                            <td>{{ $r->category_name }}</td>
                            <td>
                                @php
                                    $statusClass = match(strtolower($r->status)) {
                                        'in use'            => 'background:#eef2ff;color:#4338ca;',
                                        'available'         => 'background:#ecfdf5;color:#047857;',
                                        'under maintenance' => 'background:#fef3c7;color:#b45309;',
                                        'retired'           => 'background:#fee2e2;color:#b91c1c;',
                                        default             => 'background:#f3f4f6;color:#4b5563;',
                                    };
                                @endphp
                                <span class="db-level" style="{{ $statusClass }}">{{ $r->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="db-empty py-4"><i class="fa-regular fa-box-open"></i><div class="small">No assets registered yet</div></div></td></tr>
                    @endforelse
                    <tr class="filter-empty" id="recentFilterEmpty"><td colspan="4" class="text-center text-muted py-3 small">No matching assets</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="db-card h-100">
            <div class="db-card-head">
                <h6><i class="fa-solid fa-wave-square me-2" style="color:#4f46e5;"></i>Recent Activity</h6>
                <span class="db-chip">Live</span>
            </div>
            <div class="p-3" style="max-height:360px;overflow-y:auto;">
                @forelse($recentActivities ?? [] as $act)
                    <div class="d-flex align-items-start gap-3 py-2 border-bottom" style="border-color:#f3f4f6 !important;">
                        <div style="width:36px;height:36px;border-radius:10px;font-size:.85rem;flex-shrink:0;display:grid;place-items:center;background:linear-gradient(135deg,#eef2ff,#f5f3ff);color:#4f46e5;">
                            <i class="fa-solid {{ $act->icon ?? 'fa-circle-info' }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold" style="font-size:.88rem;color:#111827;">{{ $act->title }}</div>
                            <div class="text-muted" style="font-size:.78rem;">{{ $act->description }}</div>
                        </div>
                        <div class="text-muted flex-shrink-0" style="font-size:.72rem;">
                            @if(!empty($act->created_at))
                                {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="db-empty">
                        <i class="fa-regular fa-wave-square"></i>
                        <div class="fw-semibold text-dark mb-1">No recent activity</div>
                        <div class="small">Actions will appear here.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFilterEmpty(tableId, emptyRowId) {
        const rows = document.querySelectorAll('#' + tableId + ' tbody tr[data-unit], #' + tableId + ' tbody tr[data-cat], #' + tableId + ' tbody tr[data-asset], #' + tableId + ' tbody tr[data-tag]');
        const visible = Array.from(rows).filter(r => r.style.display !== 'none');
        document.getElementById(emptyRowId).classList.toggle('show', visible.length === 0 && rows.length > 0);
    }

    function filterAlloc() {
        const q = document.getElementById('allocSearch').value.toLowerCase().trim();
        const t = document.getElementById('allocType').value.toLowerCase();
        document.querySelectorAll('#allocTable tbody tr[data-unit]').forEach(tr => {
            const unit     = tr.dataset.unit || '';
            const asset    = tr.dataset.asset || '';
            const location = tr.dataset.location || '';
            const type     = tr.dataset.type || '';
            const matchesQ = !q || unit.includes(q) || asset.includes(q) || location.includes(q);
            tr.style.display = (matchesQ && (!t || type === t)) ? '' : 'none';
        });
        toggleFilterEmpty('allocTable', 'allocFilterEmpty');
    }

    function filterAssign() {
        const q = document.getElementById('assignSearch').value.toLowerCase().trim();
        const t = document.getElementById('assignType').value.toLowerCase();
        document.querySelectorAll('#assignTable tbody tr[data-unit]').forEach(tr => {
            const unit     = tr.dataset.unit || '';
            const asset    = tr.dataset.asset || '';
            const assignee = tr.dataset.assignee || '';
            const type     = tr.dataset.type || '';
            const matchesQ = !q || unit.includes(q) || asset.includes(q) || assignee.includes(q);
            tr.style.display = (matchesQ && (!t || type === t)) ? '' : 'none';
        });
        toggleFilterEmpty('assignTable', 'assignFilterEmpty');
    }

    function filterCat() {
        const q = document.getElementById('catSearch').value.toLowerCase().trim();
        document.querySelectorAll('#catTable tbody tr[data-cat]').forEach(tr => {
            tr.style.display = (!q || (tr.dataset.cat || '').includes(q)) ? '' : 'none';
        });
        toggleFilterEmpty('catTable', 'catFilterEmpty');
    }

    function filterMaint() {
        const q = document.getElementById('maintSearch').value.toLowerCase().trim();
        const t = document.getElementById('maintType').value.toLowerCase();
        document.querySelectorAll('#maintTable tbody tr[data-asset]').forEach(tr => {
            const asset = tr.dataset.asset || '';
            const task  = tr.dataset.task  || '';
            const type  = tr.dataset.type  || '';
            tr.style.display = ((!q || asset.includes(q) || task.includes(q)) && (!t || type === t)) ? '' : 'none';
        });
        toggleFilterEmpty('maintTable', 'maintFilterEmpty');
    }

    function filterRecent() {
        const q = document.getElementById('recentSearch').value.toLowerCase().trim();
        const s = document.getElementById('recentStatus').value.toLowerCase();
        document.querySelectorAll('#recentTable tbody tr[data-tag]').forEach(tr => {
            const tag    = tr.dataset.tag    || '';
            const asset  = tr.dataset.asset  || '';
            const status = tr.dataset.status || '';
            tr.style.display = ((!q || tag.includes(q) || asset.includes(q)) && (!s || status === s)) ? '' : 'none';
        });
        toggleFilterEmpty('recentTable', 'recentFilterEmpty');
    }
</script>

@endsection