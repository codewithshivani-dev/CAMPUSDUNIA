@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .un-hero {
        background: linear-gradient(135deg, #eef2ff, #f5f3ff 60%, #fdf4ff);
        border: 1px solid #e6e6ff; border-radius: 22px;
        padding: 24px 28px; margin-bottom: 22px;
    }
    .un-hero .un-eyebrow {
        font-size:.78rem; font-weight:700; letter-spacing:.08em;
        text-transform:uppercase; color:#4f46e5;
    }
    .un-hero h3 { font-weight:800; letter-spacing:-.02em; color:#111827; margin:4px 0 6px; }
    .un-meta { display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-top:6px; }
    .un-chip {
        display:inline-flex; align-items:center; gap:6px;
        font-size:.76rem; font-weight:600; padding:4px 10px; border-radius:999px;
        background:#fff; color:#4338ca; border:1px solid #e0e7ff;
    }

    .un-btn {
        display:inline-flex; align-items:center; gap:8px;
        border-radius:12px; padding:10px 18px; font-weight:600; font-size:.9rem;
        border:1px solid transparent; transition: all .2s ease; text-decoration:none;
    }
    .un-btn-primary { background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff; box-shadow: 0 10px 20px -10px rgba(79,70,229,.55); }
    .un-btn-primary:hover { transform: translateY(-1px); color:#fff; }
    .un-btn-outline { background:#fff; color:#4f46e5; border-color:#c7d2fe; }
    .un-btn-outline:hover { background:#eef2ff; color:#4338ca; }
    .un-btn-ghost { background:#fff; color:#374151; border-color:#e5e7eb; }
    .un-btn-ghost:hover { background:#f9fafb; color:#4f46e5; border-color:#c7d2fe; }

    .un-card { background:#fff; border:1px solid #eef0f5; border-radius:20px; overflow:hidden; box-shadow: 0 20px 45px -32px rgba(17,24,39,.3); margin-bottom:22px; }
    .un-card-head {
        padding:16px 22px; border-bottom:1px solid #eef0f5;
        background: linear-gradient(180deg,#fff,#fafbff);
        display:flex; align-items:center; justify-content:space-between;
    }
    .un-card-head h6 { margin:0; font-weight:700; color:#111827; }
    .un-card-body { padding:22px; }

    .un-label {
        font-size:.8rem; font-weight:700; color:#374151;
        text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px; display:block;
    }
    .un-input, .un-select, .un-textarea {
        border-radius:12px !important; border:1px solid #e5e7eb !important;
        padding:11px 14px !important; font-size:.92rem;
        background:#fbfbfd !important; transition: all .2s ease; width:100%;
    }
    .un-input:focus, .un-select:focus, .un-textarea:focus {
        background:#fff !important; border-color:#a5b4fc !important;
        box-shadow: 0 0 0 4px rgba(165,180,252,.25) !important; outline:none;
    }

    .un-table { margin:0; }
    .un-table thead th {
        font-size:.72rem; text-transform:uppercase; letter-spacing:.06em;
        color:#6b7280; font-weight:700; background:#fafbff;
        padding:14px 18px; border-bottom:1px solid #eef0f5;
    }
    .un-table tbody td { padding:16px 18px; border-bottom:1px solid #f3f4f6; vertical-align:middle; font-size:.92rem; color:#374151; }
    .un-table tbody tr:hover { background:#fafbff; }
    .un-table tbody tr:last-child td { border-bottom:0; }
    .un-id {
        display:inline-flex; align-items:center;
        font-family: ui-monospace, monospace; font-size:.78rem; font-weight:600;
        padding:4px 10px; border-radius:8px;
        background:#eef2ff; color:#4338ca; border:1px solid #e0e7ff;
    }
    .un-name { font-weight:600; color:#111827; }
    .un-badge {
        display:inline-flex; align-items:center; gap:6px;
        font-size:.75rem; font-weight:600; padding:5px 11px; border-radius:999px;
    }
    .un-badge.ok { background:#ecfdf5; color:#047857; }
    .un-badge.warn { background:#fffbeb; color:#b45309; }
    .un-badge.info { background:#eef2ff; color:#4338ca; }
    .un-btn-sm {
        display:inline-flex; align-items:center; gap:6px;
        padding:7px 12px; border-radius:10px; font-size:.82rem; font-weight:600;
        border:1px solid #e5e7eb; background:#fff; color:#4b5563;
        transition: all .18s ease; cursor:pointer;
    }
    .un-btn-sm:hover { color:#4f46e5; border-color:#c7d2fe; background:#eef2ff; }
</style>

<div class="un-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="un-eyebrow">Physical Inventory</div>
            <h3>{{ $asset->asset_name }}</h3>
            <div class="un-meta">
                <span class="un-chip"><i class="fa fa-barcode"></i> {{ $asset->asset_id }}</span>
                <span class="un-chip"><i class="fa fa-cubes"></i> {{ $units->count() }} unit(s)</span>
            </div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('asset-management.allocation', $asset->asset_id) }}" class="un-btn un-btn-outline"><i class="fa fa-location-dot"></i> Allocate</a>
            <a href="{{ route('asset-management.assignment', ['asset_id' => $asset->asset_id]) }}" class="un-btn un-btn-primary"><i class="fa fa-user-check"></i> Assign</a>
            <a href="{{ route('asset-management.assets.show', $asset->asset_id) }}" class="un-btn un-btn-ghost">Back</a>
        </div>
    </div>
</div>

{{-- Add units --}}
<div class="un-card d-none">
    <div class="un-card-head">
        <h6><i class="fa-solid fa-plus me-2 text-primary"></i>Add Physical Units</h6>
    </div>
    <div class="un-card-body">
        <form id="unitsForm" class="row g-3">
            <div class="col-md-3">
                <label class="un-label">Mode</label>
                <select id="mode" class="un-select"><option value="multiple">Multiple</option><option value="single">Single</option></select>
            </div>
            <div class="col-md-3">
                <label class="un-label">Number of Units</label>
                <input id="count" type="number" min="1" value="1" class="un-input">
            </div>
            <div class="col-md-3">
                <label class="un-label">Specification Mode</label>
                <select id="specMode" class="un-select"><option value="same">Same for all</option><option value="different">Different per unit</option></select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="un-btn un-btn-primary w-100 justify-content-center"><i class="fa fa-plus"></i> Create Units</button>
            </div>
            <div class="col-12">
                <label class="un-label">Common Specifications (optional)</label>
                <textarea id="specs" class="un-textarea" rows="3" placeholder='JSON, e.g. {"serial_prefix":"PC"}'></textarea>
            </div>
        </form>
    </div>
</div>

{{-- Unit list --}}
<div class="un-card">
    <div class="un-card-head">
        <h6><i class="fa-solid fa-list me-2 text-primary"></i>Unit List</h6>
        <span class="un-chip"><i class="fa fa-cubes"></i> {{ $units->count() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="table un-table align-middle">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>Unit Number</th>
                    <th>Unit ID</th>
                    <th style="width:130px">Status</th>
                    <th style="width:120px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($units as $i => $unit)
                @php
                    $cls = $unit->status === 'maintenance' ? 'warn' : ($unit->status === 'available' ? 'ok' : 'info');
                    $ico = $unit->status === 'maintenance' ? 'fa-screwdriver-wrench' : ($unit->status === 'available' ? 'fa-circle-check' : 'fa-user-check');
                @endphp
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td class="un-name">{{ $unit->unit_number }}</td>
                    <td><span class="un-id">{{ $unit->unit_id }}</span></td>
                    <td><span class="un-badge {{ $cls }}"><i class="fa {{ $ico }}"></i> {{ ucfirst($unit->status) }}</span></td>
                    <td>
                        <button class="un-btn-sm" data-unit-id="{{ $unit->unit_id }}" data-status="{{ $unit->status }}" onclick="openUnitEdit(this)"><i class="fa fa-pen"></i> Edit</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">
                    <div class="text-center py-5 text-muted">
                        <i class="fa-regular fa-square-plus d-block mb-2" style="font-size:2.4rem;color:#c7d2fe"></i>
                        <div class="fw-semibold text-dark mb-1">No units found</div>
                        <div class="small">Create units using the form above.</div>
                    </div>
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="unitEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:0;border-radius:18px;overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg,#eef2ff,#f5f3ff);border-bottom:1px solid #e6e6ff;">
                <h5 class="modal-title fw-bold">Edit Unit Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="small text-muted mb-2">Unit</div>
                <div id="editUnitId" class="un-id mb-3"></div>
                <label class="un-label">Status</label>
                <select id="editUnitStatus" class="un-select">
                    <option value="available">Available</option>
                    <option value="assigned">Assigned</option>
                    <option value="maintenance">Under Maintenance</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="un-btn un-btn-primary" onclick="saveUnitEdit()"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    let editingUnitId = null;
    
    /*
    |--------------------------------------------------------------------------
    | Toast Notification
    |--------------------------------------------------------------------------
    */
    function toast(message, type = 'success') {
    
        // Use SweetAlert2 if it is already loaded
        if (typeof Swal !== 'undefined') {
    
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type === 'error' ? 'error' : 'success',
                title: message || 'Done',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
    
            return;
        }
    
        // Fallback if SweetAlert2 is not loaded
        const existing = document.getElementById('customToast');
    
        if (existing) {
            existing.remove();
        }
    
        const el = document.createElement('div');
    
        el.id = 'customToast';
    
        el.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            min-width: 280px;
            max-width: 420px;
            padding: 14px 18px;
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(0,0,0,.20);
            background: ${type === 'error' ? '#dc3545' : '#198754'};
        `;
    
        el.textContent = message || 'Done';
    
        document.body.appendChild(el);
    
        setTimeout(() => {
            el.remove();
        }, 3000);
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Open Unit Edit Modal
    |--------------------------------------------------------------------------
    */
    function openUnitEdit(button) {
    
        editingUnitId = button.dataset.unitId;
    
        document.getElementById('editUnitId').textContent = editingUnitId;
    
        document.getElementById('editUnitStatus').value =
            button.dataset.status;
    
        bootstrap.Modal
            .getOrCreateInstance(
                document.getElementById('unitEditModal')
            )
            .show();
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Save Unit Edit
    |--------------------------------------------------------------------------
    */
    async function saveUnitEdit() {
    
        const status =
            document.getElementById('editUnitStatus').value;
    
        if (!editingUnitId) {
            toast('Unit ID is missing', 'error');
            return;
        }
    
        try {
    
            const d = await api(
                '{{ url('/institute/admin/asset-management/units') }}/'
                + encodeURIComponent(editingUnitId),
                {
                    method: 'PUT',
                    body: JSON.stringify({
                        status: status
                    })
                }
            );
    
            toast(
                d.message || 'Unit updated successfully.'
            );
    
            setTimeout(() => {
                location.reload();
            }, 500);
    
        } catch (e) {
    
            console.error('saveUnitEdit error:', e);
    
            toast(
                e.message || 'Unable to update unit',
                'error'
            );
        }
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | Unit Creation Mode
    |--------------------------------------------------------------------------
    */
    document.getElementById('mode').addEventListener('change', () => {
    
        if (
            document.getElementById('mode').value === 'single'
        ) {
            document.getElementById('count').value = 1;
        }
    });
    
    
    /*
    |--------------------------------------------------------------------------
    | Create Units
    |--------------------------------------------------------------------------
    */
    document.getElementById('unitsForm').addEventListener(
        'submit',
        async e => {
    
            e.preventDefault();
    
            let count = Number(
                document.getElementById('count').value || 1
            );
    
            let specs = {};
    
            try {
    
                specs = JSON.parse(
                    document.getElementById('specs').value || '{}'
                );
    
            } catch (x) {
    
                toast(
                    'Specifications must be valid JSON',
                    'error'
                );
    
                return;
            }
    
            try {
    
                const d = await api(
                    '{{ route('asset-management.units.store', $asset->asset_id) }}',
                    {
                        method: 'POST',
                        body: JSON.stringify({
    
                            mode:
                                document.getElementById('mode').value,
    
                            count: count,
    
                            spec_mode:
                                document.getElementById('specMode').value,
    
                            specifications: specs
                        })
                    }
                );
    
                toast(
                    d.message || 'Units created successfully.'
                );
    
                setTimeout(() => {
                    location.reload();
                }, 500);
    
            } catch (x) {
    
                console.error('Create units error:', x);
    
                toast(
                    x.message || 'Unable to create units',
                    'error'
                );
            }
        }
    );
</script>
@endsection