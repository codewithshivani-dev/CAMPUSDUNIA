@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .al-hero {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border: 1px solid #e6e6ff; border-radius: 22px;
        padding: 24px 28px; margin-bottom: 22px;
        color: white;
    }
    .al-hero .al-eyebrow {
        font-size:.78rem; font-weight:700; letter-spacing:.08em;
        text-transform:uppercase;
    }
    .al-hero h3 { font-weight:800; letter-spacing:-.02em; margin:4px 0 6px; }

    .al-card {
        background:#fff;
        border:1px solid #eef0f5;
        border-radius:20px;
        overflow:hidden;
        box-shadow: 0 20px 45px -32px rgba(17,24,39,.3);
    }
    .al-card-head {
        padding:16px 22px;
        border-bottom:1px solid #eef0f5;
        background: linear-gradient(180deg,#fff,#fafbff);
        display:flex;
        align-items:center;
        justify-content:space-between;
    }
    .al-card-head h6 { margin:0; font-weight:700; color:#111827; }
    .al-card-body { padding:22px; }

    .al-label {
        font-size:.8rem; font-weight:700; color:#374151;
        text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px; display:block;
    }
    .al-label .req { color:#ef4444; }
    .al-label .opt { color:#9ca3af; font-weight:600; text-transform:none; letter-spacing:0; font-size:.72rem; }

    .al-input, .al-select, .al-textarea {
        border-radius:12px !important;
        border:1px solid #e5e7eb !important;
        padding:11px 14px !important;
        font-size:.92rem;
        background:#fbfbfd !important;
        transition: all .2s ease;
        width:100%;
        appearance:none;
        -webkit-appearance:none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236b7280'><path d='M4.5 6l3.5 4 3.5-4z'/></svg>") !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 16px !important;
        padding-right: 36px !important;
    }
    .al-textarea { background-image:none !important; padding-right:14px !important; }
    .al-input:focus, .al-select:focus, .al-textarea:focus {
        background-color:#fff !important;
        border-color:#a5b4fc !important;
        box-shadow: 0 0 0 4px rgba(165,180,252,.25) !important;
        outline:none;
    }
    .al-select:disabled {
        background:#f3f4f6 !important;
        color:#9ca3af;
        cursor:not-allowed;
        opacity:.75;
    }
    .al-hint { font-size:.78rem; color:#6b7280; margin-top:6px; }

    .al-step {
        display:flex; gap:14px; align-items:flex-start;
        padding:16px 18px; border-radius:16px;
        background:#fafbff; border:1px solid #eef0f5; margin-bottom:16px;
    }
    .al-step-no {
        width:30px; height:30px; border-radius:50%;
        display:grid; place-items:center;
        background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff;
        font-size:.8rem; font-weight:700; flex-shrink:0;
    }
    .al-step-title { font-weight:700; color:#111827; font-size:.92rem; margin-bottom:2px; }
    .al-step-sub { font-size:.8rem; color:#6b7280; }

    .al-btn {
        display:inline-flex; align-items:center; gap:8px;
        border-radius:12px; padding:11px 22px; font-weight:600; font-size:.92rem;
        border:1px solid transparent; transition: all .2s ease; text-decoration:none;
    }
    .al-btn-primary {
        background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff;
        box-shadow: 0 10px 20px -10px rgba(79,70,229,.55);
    }
    .al-btn-primary:hover { transform: translateY(-1px); color:#fff; }
    .al-btn-primary:disabled { opacity:.6; cursor:not-allowed; transform:none; }

    .al-divider { height:1px; background:#eef0f5; margin:22px 0; }

    .al-toast-wrap {
        position: fixed; top: 20px; right: 20px; z-index: 99999;
        display: flex; flex-direction: column; gap: 10px;
        pointer-events: none;
    }
    .al-toast {
        min-width: 240px; max-width: 380px;
        background: #fff; color: #111827;
        border-radius: 12px; padding: 12px 16px;
        box-shadow: 0 20px 45px -20px rgba(17,24,39,.45);
        border-left: 4px solid #4f46e5;
        font-size: .9rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        animation: alSlideIn .25s ease;
        pointer-events: auto;
    }
    .al-toast.success { border-left-color: #059669; }
    .al-toast.success i { color: #059669; }
    .al-toast.error   { border-left-color: #dc2626; }
    .al-toast.error i { color: #dc2626; }
    .al-toast i { font-size: 1.1rem; flex-shrink: 0; }
    @keyframes alSlideIn {
        from { transform: translateX(30px); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }
</style>

<div class="al-toast-wrap" id="alToastWrap"></div>

<div class="al-hero">
    <div class="al-eyebrow">Asset Management</div>
    <h3>Asset Allocation</h3>
    <div style="font-size:.94rem">Pick a location — anywhere in the hierarchy. You can stop at any level.</div>
</div>

<div class="al-card">
    <div class="al-card-head">
        <h6><i class="fa-solid fa-location-dot me-2 text-primary"></i>Allocation Details</h6>
    </div>
    <div class="al-card-body">

        {{-- Step 1 --}}
        <div class="al-step">
            <div class="al-step-no">1</div>
            <div class="flex-fill">
                <div class="al-step-title">Select Asset & Units</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="al-label">Asset <span class="req">*</span></label>
                        <select id="asset" class="al-select">
                            <option value="">Select Asset</option>
                            @foreach($amenities as $a)
                                <option value="{{ $a->asset_id }}" @if(optional($selectedAsset)->asset_id == $a->asset_id) selected @endif>
                                    {{ $a->name }} ({{ $a->asset_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="al-label">Units <span class="req">*</span></label>
                        <select id="units" class="al-select" @if(!$units->count()) disabled @endif>
                            @if($units->count())
                                @foreach($units as $u)
                                    <option value="{{ $u->unit_id }}">
                                        {{ $u->unit_number }} — {{ $u->unit_id }} ({{ $u->status }})
                                    </option>
                                @endforeach
                            @else
                                <option value="">No available units for this asset</option>
                            @endif
                        </select>
                        <div class="al-hint">
                            <i class="fa fa-circle-info me-1"></i>
                            @if($selectedAsset && !$units->count())
                                All units of this asset are already allocated or assigned.
                            @else
                                Select one physical unit at a time.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="al-step">
            <div class="al-step-no">2</div>
            <div class="flex-fill">
                <div class="al-step-title">Choose Location</div>
                <div class="al-step-sub mb-3">
                    You can allocate at Building, Block, Floor or Room — just pick the deepest level you want.
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="al-label">Building <span class="req">*</span></label>
                        <select id="building" class="al-select">
                            <option value="">Select building</option>
                            @foreach($buildings as $b)
                                <option value="{{ $b->id }}" @if($autoBuildingId == $b->id) selected @endif>
                                    {{ $b->name }}{{ $b->code ? ' (' . $b->code . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="al-label">Block <span class="opt">(optional)</span></label>
                        <select id="block" class="al-select" disabled>
                            <option value="">Select block</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="al-label">Floor <span class="opt">(optional)</span></label>
                        <select id="floor" class="al-select" disabled>
                            <option value="">Select floor</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="al-label">Room <span class="opt">(optional)</span></label>
                        <select id="room" class="al-select" disabled>
                            <option value="">Select room</option>
                        </select>
                    </div>
                </div>

                <div id="allocationPreview" class="mt-3 d-none">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3"
                         style="background:#eef2ff;color:#4338ca;font-size:.84rem;font-weight:600;">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Will allocate at: <strong id="previewLevel">Building</strong></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 3 --}}
        <div class="al-step">
            <div class="al-step-no">3</div>
            <div class="flex-fill">
                <div class="al-step-title">Notes (optional)</div>
                <div class="al-step-sub mb-3">Any additional context for this allocation.</div>
                <textarea id="notes" class="al-textarea" rows="3" placeholder="Optional notes"></textarea>
            </div>
        </div>

        <div class="al-divider"></div>

        <div class="d-flex justify-content-end">
            <button id="allocateBtn" class="al-btn al-btn-primary"><i class="fa fa-location-dot"></i> Allocate Unit</button>
        </div>
    </div>
</div>

<script>
/* ============ helpers ============ */
function toast(message, type = 'success') {
    const wrap = document.getElementById('alToastWrap');
    if (!wrap) { alert(message); return; }
    const icons = { success:'fa-circle-check', error:'fa-circle-exclamation', info:'fa-circle-info' };
    const el = document.createElement('div');
    el.className = 'al-toast ' + type;
    el.innerHTML = '<i class="fa-solid ' + (icons[type] || icons.info) + '"></i><span>' + message + '</span>';
    wrap.appendChild(el);
    setTimeout(() => {
        el.style.transition = 'opacity .25s ease, transform .25s ease';
        el.style.opacity = '0';
        el.style.transform = 'translateX(30px)';
        setTimeout(() => el.remove(), 260);
    }, 3400);
}

async function api(url, options = {}) {
    const opts = {
        method: options.method || 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
            ...(options.headers || {}),
        },
        credentials: 'same-origin',
    };
    if (opts.method !== 'GET' && options.body !== undefined) opts.body = options.body;
    const res = await fetch(url, opts);
    let data = {};
    try { data = await res.json(); } catch (_) {}
    if (!res.ok) {
        let msg = null;
        if (data?.errors && typeof data.errors === 'object') msg = Object.values(data.errors).flat().join(' ');
        if (!msg) msg = data?.message || ('Request failed (' + res.status + ')');
        const err = new Error(msg); err.errors = data?.errors || null; err.status = res.status;
        throw err;
    }
    return data;
}

/* ============ Location hierarchy ============ */
const LOC_URL = '{{ route('asset-management.allocation.locations') }}';
const els = {
    building: document.getElementById('building'),
    block:    document.getElementById('block'),
    floor:    document.getElementById('floor'),
    room:     document.getElementById('room'),
};

/**
 * Load child options into the given select.
 * - Auto-selects when exactly one child exists (and cascades further).
 * - Disables the select if no children or if parent is empty.
 */
async function loadChildren(
    type,
    parentId,
    selectEl,
    placeholder,
    autoCascade = true
) {
    selectEl.disabled = true;
    selectEl.innerHTML = `<option value="">Loading…</option>`;

    if (!parentId) {
        selectEl.innerHTML = `<option value="">${placeholder}</option>`;
        return;
    }

    try {

        const r = await fetch(
            `${LOC_URL}?type=${encodeURIComponent(type)}&parent_id=${encodeURIComponent(parentId)}`,
            {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            }
        );

        const responseText = await r.text();

        console.log('Location API response:', {
            type: type,
            parentId: parentId,
            status: r.status,
            response: responseText
        });

        let d;

        try {
            d = JSON.parse(responseText);
        } catch (jsonError) {
            throw new Error(
                `Invalid server response. HTTP ${r.status}`
            );
        }

        if (!r.ok) {
            throw new Error(
                d.message || `Request failed with HTTP ${r.status}`
            );
        }

        if (d.success === false) {
            throw new Error(
                d.message || `Unable to load ${type}`
            );
        }

        const rows = Array.isArray(d.data)
            ? d.data
            : [];

        selectEl.innerHTML =
            `<option value="">${placeholder}</option>`;

        rows.forEach(x => {

            let text;

            if (type === 'floor') {
                text = 'Floor ' + x.floor_number;

            } else if (type === 'room') {
                text = 'Room ' + x.room_number;

            } else {
                text = x.name;
            }

            const option = document.createElement('option');

            option.value = x.id;
            option.textContent = text;

            selectEl.appendChild(option);
        });

        if (rows.length === 0) {

            selectEl.innerHTML =
                `<option value="">No ${type}s available</option>`;

            selectEl.disabled = true;

            console.warn(
                `No ${type} found for parent ID: ${parentId}`
            );

            return;
        }

        selectEl.disabled = false;

        // Auto-select if exactly one child exists
        if (autoCascade && rows.length === 1) {

            selectEl.value = rows[0].id;

            selectEl.dispatchEvent(
                new Event('change')
            );
        }

    } catch (e) {

        console.error(
            `Location API error for ${type}:`,
            e
        );

        selectEl.innerHTML = `
            <option value="">
                Unable to load ${type}
            </option>
        `;

        selectEl.disabled = true;

        toast(
            e.message || `Unable to load ${type}`,
            'error'
        );
    }
}

/* ============ Cascade handlers ============ */
els.building.addEventListener('change', async () => {

els.block.innerHTML =
    '<option value="">Select block</option>';

els.floor.innerHTML =
    '<option value="">Select floor</option>';

els.room.innerHTML =
    '<option value="">Select room</option>';

els.block.disabled = true;
els.floor.disabled = true;
els.room.disabled = true;

if (!els.building.value) {
    updatePreview();
    return;
}

await loadChildren(
    'block',
    els.building.value,
    els.block,
    'Select block'
);

updatePreview();
});


els.block.addEventListener('change', async () => {

els.floor.innerHTML =
    '<option value="">Select floor</option>';

els.room.innerHTML =
    '<option value="">Select room</option>';

els.floor.disabled = true;
els.room.disabled = true;

if (!els.block.value) {
    updatePreview();
    return;
}

await loadChildren(
    'floor',
    els.block.value,
    els.floor,
    'Select floor'
);

updatePreview();
});


els.floor.addEventListener('change', async () => {

els.room.innerHTML =
    '<option value="">Select room</option>';

els.room.disabled = true;

if (!els.floor.value) {
    updatePreview();
    return;
}

await loadChildren(
    'room',
    els.floor.value,
    els.room,
    'Select room'
);

updatePreview();
});


els.room.addEventListener(
'change',
updatePreview
);

/* ============ Preview: which level will be used ============ */
function currentLevel() {
    if (els.room.value)     return 'room';
    if (els.floor.value)    return 'floor';
    if (els.block.value)    return 'block';
    if (els.building.value) return 'building';
    return null;
}

function updatePreview() {
    const lvl = currentLevel();
    const box = document.getElementById('allocationPreview');
    const txt = document.getElementById('previewLevel');
    if (!lvl) { box.classList.add('d-none'); return; }
    box.classList.remove('d-none');
    txt.textContent = lvl.charAt(0).toUpperCase() + lvl.slice(1);
}

/* ============ Asset change → reload ============ */
document.getElementById('asset').addEventListener('change', function () {
    const v = this.value;
    if (v) {
        location.href = '{{ url('/institute/admin/asset-management/allocation') }}/' + encodeURIComponent(v);
    }
});

/* ============ Allocate ============ */
document.getElementById('allocateBtn').addEventListener('click', async () => {
    const level = currentLevel();
    const unitValue = document.getElementById('units').value;
    const unitIds = unitValue ? [unitValue] : [];

    const payload = {
        unit_ids: unitIds,
        assigned_to_type: level,
        building_id: els.building.value || null,
        block_id: els.block.value || null,
        floor_id: els.floor.value || null,
        room_id: els.room.value || null,
        notes: document.getElementById('notes').value,
    };

    if (!unitIds.length)   return toast('Select a physical unit', 'error');
    if (!payload.building_id) return toast('Select a building', 'error');
    if (!level)            return toast('Choose a location', 'error');

    // Sanity: if room is set, floor & block must be set
    if (els.room.value && !els.floor.value) return toast('Select a floor before a room', 'error');
    if (els.floor.value && !els.block.value) return toast('Select a block before a floor', 'error');

    const btn = document.getElementById('allocateBtn');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Allocating…';

    try {
        const d = await api('{{ route('asset-management.allocation.store') }}', {
            method: 'POST',
            body: JSON.stringify(payload),
        });
        toast(d.message || 'Unit allocated');
        setTimeout(() => location.reload(), 700);
    } catch (e) {
        toast(e.message || 'Allocation failed', 'error');
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
});

/* ============ Init ============ */
(function init() {
    // Auto-cascade: if a building is already selected (either by user or server), load its children.
    if (els.building.value) {
        els.building.dispatchEvent(new Event('change'));
    }
    updatePreview();
})();
</script>
@endsection