@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .ca-hero {
        background: linear-gradient(135deg, #eef2ff, #f5f3ff 60%, #fdf4ff);
        border: 1px solid #e6e6ff; border-radius: 22px;
        padding: 24px 28px; margin-bottom: 22px;
    }
    .ca-hero .ca-eyebrow {
        font-size:.78rem; font-weight:700; letter-spacing:.08em;
        text-transform:uppercase; color:#4f46e5;
    }
    .ca-hero h3 { font-weight:800; letter-spacing:-.02em; color:#111827; margin:4px 0 6px; }
    .ca-meta { display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-top:6px; }
    .ca-chip {
        display:inline-flex; align-items:center; gap:6px;
        font-size:.76rem; font-weight:600; padding:4px 10px; border-radius:999px;
        background:#fff; color:#4338ca; border:1px solid #e0e7ff;
    }
    .ca-btn {
        display:inline-flex; align-items:center; gap:8px;
        border-radius:12px; padding:10px 18px; font-weight:600; font-size:.9rem;
        border:1px solid #e5e7eb; background:#fff; color:#374151;
        text-decoration:none; transition: all .2s ease;
    }
    .ca-btn:hover { color:#4f46e5; border-color:#c7d2fe; background:#eef2ff; }

    .ca-card { background:#fff; border:1px solid #eef0f5; border-radius:20px; overflow:hidden; box-shadow: 0 20px 45px -32px rgba(17,24,39,.3); }
    .ca-card-head {
        padding:16px 22px; border-bottom:1px solid #eef0f5;
        background: linear-gradient(180deg,#fff,#fafbff);
        display:flex; align-items:center; justify-content:space-between;
    }
    .ca-card-head h6 { margin:0; font-weight:700; color:#111827; }

    .ca-table { margin:0; }
    .ca-table thead th {
        font-size:.72rem; text-transform:uppercase; letter-spacing:.06em;
        color:#6b7280; font-weight:700; background:#fafbff;
        padding:14px 18px; border-bottom:1px solid #eef0f5;
    }
    .ca-table tbody td { padding:16px 18px; border-bottom:1px solid #f3f4f6; vertical-align:middle; font-size:.92rem; color:#374151; }
    .ca-table tbody tr:hover { background:#fafbff; }
    .ca-table tbody tr:last-child td { border-bottom:0; }
    .ca-id {
        display:inline-flex; align-items:center;
        font-family: ui-monospace, monospace; font-size:.78rem; font-weight:600;
        padding:4px 10px; border-radius:8px;
        background:#eef2ff; color:#4338ca; border:1px solid #e0e7ff;
    }
    .ca-name { font-weight:600; color:#111827; }
    .ca-count {
        display:inline-flex; align-items:center; gap:6px;
        font-weight:700; color:#111827;
    }
    .ca-btn-sm {
        display:inline-flex; align-items:center; gap:6px;
        padding:7px 12px; border-radius:10px; font-size:.82rem; font-weight:600;
        border:1px solid #e5e7eb; background:#fff; color:#4b5563;
        text-decoration:none; transition: all .18s ease;
    }
    .ca-btn-sm:hover { color:#4f46e5; border-color:#c7d2fe; background:#eef2ff; }
</style>

<div class="ca-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="ca-eyebrow">Category</div>
            <h3>{{ $category->name }}</h3>
            <div class="ca-meta">
                <span class="ca-chip"><i class="fa fa-tag"></i> {{ $category->category_id }}</span>
                <span class="ca-chip"><i class="fa fa-box"></i> {{ $assets->count() }} asset masters</span>
            </div>
        </div>
        <a href="{{ route('asset-management.categories.index') }}" class="ca-btn"><i class="fa fa-arrow-left"></i> Categories</a>
    </div>
</div>

<div class="ca-card">
    <div class="ca-card-head">
        <h6><i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>Assets in this Category</h6>
    </div>
    <div class="table-responsive">
        <table class="table ca-table align-middle">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Asset Name</th>
                    <th>Institute Units</th>
                    <th style="width:130px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($assets as $a)
                @php $am = $amenities->get($a->asset_id); @endphp
                <tr>
                    <td><span class="ca-id">{{ $a->asset_id }}</span></td>
                    <td class="ca-name">{{ $a->asset_name }}</td>
                    <td><span class="ca-count"><i class="fa fa-cubes text-primary" style="font-size:.8rem"></i> {{ $am ? $am->units()->count() : 0 }}</span></td>
                    <td><a class="ca-btn-sm" href="{{ route('asset-management.assets.show', $a->asset_id) }}"><i class="fa fa-eye"></i> View</a></td>
                </tr>
            @empty
                <tr><td colspan="4">
                    <div class="text-center py-5 text-muted">
                        <i class="fa-regular fa-folder-open d-block mb-2" style="font-size:2.4rem;color:#c7d2fe"></i>
                        <div class="fw-semibold text-dark mb-1">No assets in this category</div>
                        <div class="small">Create assets under this category to see them here.</div>
                    </div>
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection