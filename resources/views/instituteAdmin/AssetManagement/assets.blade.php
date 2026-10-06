@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .am-page {
        --am-primary: #4361ee;
        --am-primary-2: #3a0ca3;
        --am-ink: #111827;
        --am-muted: #6b7280;
        --am-line: #eef0f5;
        --am-surface: #ffffff;
    }

    .am-page * {
        box-sizing: border-box;
    }

    .am-hero {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border: 1px solid #e6e6ff;
        border-radius: 22px;
        padding: 26px 28px;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
        color: white;
    }
    .am-hero::after {
        content: "";
        position: absolute;
        right: -60px; top: -60px;
        width: 220px; height: 220px;
        background: radial-gradient(closest-side, rgba(124,58,237,.18), transparent 70%);
        border-radius: 50%;
    }

    .am-hero h3 {
        font-weight: 800;
        letter-spacing: -.02em;
    }

    .am-eyebrow {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: .78rem; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: var(--am-primary);
        background: #fff; border: 1px solid #e6e6ff;
        padding: 6px 12px; border-radius: 999px; margin-bottom: 10px;
    }

    .am-btn {
        display: inline-flex; align-items: center; gap: 8px;
        border-radius: 12px; padding: 10px 18px;
        font-weight: 600; font-size: .92rem;
        border: 1px solid transparent; transition: all .2s ease;
        text-decoration: none;
    }

    .am-btn-primary {
        background: linear-gradient(135deg, var(--am-primary), var(--am-primary-2));
        color: #fff;
        box-shadow: 0 10px 20px -10px rgba(79,70,229,.55);
    }

    .am-btn-primary:hover {
        transform: translateY(-1px);
        color:#fff;
        box-shadow: 0 14px 24px -10px rgba(79,70,229,.7);
    }

    .am-btn-ghost {
        background: #fff;
        color: #374151;
        border-color: #e5e7eb;
    }

    .am-btn-ghost:hover {
        background: #f9fafb;
        color: var(--am-primary);
        border-color: #c7d2fe;
    }

    .am-filter {
        background: var(--am-surface);
        border: 1px solid var(--am-line);
        border-radius: 18px;
        padding: 18px;
        box-shadow: 0 10px 30px -22px rgba(17,24,39,.25);
        margin-bottom: 22px;
    }

    .am-input, .am-select {
        border-radius: 12px !important;
        border: 1px solid #e5e7eb !important;
        padding: 11px 14px !important;
        font-size: .92rem;
        background-color: #fbfbfd !important;
        transition: all .2s ease;
    }

    .am-input:focus, .am-select:focus {
        background-color: #fff !important;
        border-color: #a5b4fc !important;
        box-shadow: 0 0 0 4px rgba(165,180,252,.25) !important;
    }

    .am-card {
        background: var(--am-surface);
        border: 1px solid var(--am-line);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 45px -30px rgba(17,24,39,.35);
    }

    .am-card-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--am-line);
        display: flex; align-items: center; justify-content: space-between;
        background: linear-gradient(180deg, #ffffff, #fafbff);
    }

    .am-card-head h6 {
        margin: 0;
        font-weight: 700;
        color: var(--am-ink);
        letter-spacing: -.01em;
    }

    .am-chip {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .75rem; font-weight: 600;
        padding: 4px 10px; border-radius: 999px;
        background: #eef2ff; color: #4338ca;
    }

    .am-table {
        margin: 0;
    }

    .am-table thead th {
        font-size: .75rem; text-transform: uppercase; letter-spacing: .06em;
        color: #6b7280; font-weight: 700;
        background: #fafbff; border-bottom: 1px solid var(--am-line);
        padding: 14px 18px; white-space: nowrap;
    }

    .am-table tbody td {
        padding: 16px 18px; vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        font-size: .92rem; color: #374151;
    }

    .am-table tbody tr { transition: background .15s ease; }
    .am-table tbody tr:hover { background: #fafbff; }
    .am-table tbody tr:last-child td { border-bottom: 0; }

    .am-id {
        display: inline-flex; align-items: center;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: .78rem; font-weight: 600;
        padding: 4px 10px; border-radius: 8px;
        background: #eef2ff; color: #4338ca;
        border: 1px solid #e0e7ff;
    }

    .am-name {
        font-weight: 600;
        color: var(--am-ink);
    }

    .am-cat {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .82rem; color: #4b5563;
        background: #f3f4f6; padding: 4px 10px; border-radius: 8px;
    }

    .am-count {
        display: inline-flex; align-items: center; gap: 6px;
        font-weight: 700; color: var(--am-ink);
    }

    .am-count small {
        color: #9ca3af;
        font-weight: 500;
    }

    .am-avail {
        color: #059669;
        font-weight: 700;
    }

    .am-icon-btn {
        width: max-content;
        padding: 8px;
        place-items: center;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff; color: #4b5563;
        transition: all .18s ease; text-decoration: none;
    }

    .am-icon-btn:hover {
        color: var(--am-primary);
        border-color: #c7d2fe;
        background: #eef2ff;
        transform: translateY(-1px);
    }

    .am-icon-btn.primary:hover {
        color: #fff;
        background: linear-gradient(135deg, var(--am-primary), var(--am-primary-2));
        border-color: transparent;
    }

    .am-empty {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .am-empty i {
        font-size: 2.5rem;
        color: #c7d2fe;
        margin-bottom: 12px;
        display: block;
    }

    /* ===== pagination (bootstrap-4 markup) ===== */
    .am-pagination-footer {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 16px 22px;
        border-top: 1px solid var(--am-line);
        background: linear-gradient(180deg, #ffffff, #fafbff);
    }

    .am-page .pagination {
        margin: 0;
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        list-style: none;
        padding: 0;
    }

    .am-page .pagination .page-item {
        display: inline-block;
    }

    .am-page .pagination .page-link {
        border-radius: 10px !important;
        margin: 0;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        font-size: .85rem;
        font-weight: 600;
        padding: 8px 13px;
        min-width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        background: #fff;
        transition: all .18s ease;
        line-height: 1;
        box-shadow: none;
    }

    .am-page .pagination .page-link:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #4338ca;
    }

    .am-page .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 6px 14px -6px rgba(67,97,238,.6);
    }

    .am-page .pagination .page-item.disabled .page-link {
        background: #f9fafb;
        color: #9ca3af;
        border-color: #eef0f5;
        cursor: not-allowed;
    }

    /* Constrain any stray SVG icons inside pagination */
    .am-page .pagination svg {
        width: 16px !important;
        height: 16px !important;
        max-width: 16px !important;
        max-height: 16px !important;
        display: inline-block !important;
        vertical-align: middle;
        fill: currentColor;
    }
</style>

<div class="am-page">

    {{-- Hero --}}
    <div class="am-hero">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 position-relative" style="z-index:1">
            <div>
                <span class="d-none am-eyebrow"><i class="fa-solid fa-boxes-stacked"></i> Asset Master</span>
                <h3 class="mb-1">Assets</h3>
                <div class="" style="font-size:.94rem">View all asset masters and their institute unit counts.</div>
            </div>
            <a href="{{ route('asset-management.assets.create') }}" class="am-btn am-btn-primary">
                <i class="fa fa-plus"></i> Create Asset
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <form class="am-filter row g-2 align-items-end">
        <div class="col-md-6">
            <label class="form-label small fw-semibold text-muted mb-1">Search</label>
            <div class="position-relative">
                <i class="fa fa-search position-absolute" style="left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:.85rem;"></i>
                <input name="search" value="{{ e($search) }}" class="form-control am-input" style="padding-left:38px !important" placeholder="Search asset name or ID">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold text-muted mb-1">Category</label>
            <select name="category_id" class="form-select am-select">
                <option value="">All Categories</option>
                @foreach($categories as $c)
                    <option value="{{ $c->category_id }}" @selected($categoryId == $c->category_id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="am-btn am-btn-primary flex-fill justify-content-center"><i class="fa fa-filter"></i> Filter</button>
            <a href="{{ route('asset-management.assets.index') }}" class="am-btn am-btn-ghost">Reset</a>
        </div>
    </form>

    {{-- List --}}
    <div class="am-card">
        <div class="am-card-head">
            <h6><i class="fa-solid fa-list me-2 text-primary"></i>Asset List</h6>
            <span class="am-chip"><i class="fa fa-database"></i> {{ $assets->total() }} total</span>
        </div>

        <div class="table-responsive">
            <table class="table am-table align-middle">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th class="sortable">Asset ID</th>
                        <th class="sortable">Asset</th>
                        <th class="sortable">Category</th>
                        <th class="sortable">Units</th>
                        <th class="sortable">Available</th>
                        <th class="sortable d-none">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($assets as $i => $asset)
                    @php
                        $am = $amenityMap->get($asset->asset_id);
                        $total = $am ? $am->units()->count() : 0;
                        $available = $am ? $am->units()->where('status', 'available')->count() : 0;
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $assets->firstItem() + $i }}</td>
                        <td><span class="am-id">{{ $asset->asset_id }}</span></td>
                        <td class="am-name">{{ $asset->asset_name }}</td>
                        <td>
                            <span class="am-cat"><i class="fa fa-folder-open" style="font-size:.75rem; color:#9ca3af"></i>{{ $asset->category->name ?? $asset->category_id }}</span>
                        </td>
                        <td class="d-flex gap-2">
                            <span class="am-count"><i class="fa fa-cubes text-primary" style="font-size:.8rem"></i> {{ $total }}</span>
                            @if($am)
                                <a class="am-icon-btn primary" href="{{ route('asset-management.units.index', $asset->asset_id) }}" title="Manage Units">
                                    <i class="fa fa-cubes  me-1"></i>
                                    Manage Units
                                </a>
                            @endif
                        </td>
                        <td><span class="am-count am-avail"><i class="fa fa-circle-check" style="font-size:.8rem"></i> {{ $available }}</span></td>
                        <td>
                            <div class="d-flex gap-2 d-none">
                                <a class="am-icon-btn" href="{{ route('asset-management.assets.show', $asset->asset_id) }}" title="View"><i class="fa fa-eye me-1"></i> View</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="am-empty">
                                <i class="fa-regular fa-folder-open"></i>
                                <div class="fw-semibold text-dark mb-1">No assets found</div>
                                <div class="small">Try adjusting filters or create a new asset.</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($assets->hasPages())
            <div class="am-pagination-footer">
                <div class="small text-muted">
                    <i class="fa-regular fa-file-lines me-1"></i>
                    Showing <strong>{{ $assets->firstItem() }}</strong>–<strong>{{ $assets->lastItem() }}</strong>
                    of <strong>{{ $assets->total() }}</strong>
                </div>
                <div>
                    {{ $assets->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
</div>

@endsection