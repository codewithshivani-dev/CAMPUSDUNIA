@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .cr-hero {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border: 1px solid #e6e6ff; border-radius: 22px;
        padding: 24px 28px; margin-bottom: 22px;
        color: white !important;
    }

    .cr-hero .cr-eyebrow {
        font-size:.78rem;
        font-weight:700;
        letter-spacing:.08em;
        text-transform:uppercase;
    }

    .cr-hero h3 {
        font-weight:800;
        letter-spacing:-.02em;
        margin:4px 0 6px;
    }

    .cr-card {
        background:#fff;
        border:1px solid #eef0f5;
        border-radius:20px;
        overflow:hidden;
        box-shadow: 0 20px 45px -32px rgba(17,24,39,.3);
        margin-bottom:22px;
    }

    .cr-card-head {
        padding:16px 22px; border-bottom:1px solid #eef0f5;
        background: linear-gradient(180deg,#fff,#fafbff);
        display:flex; align-items:center; justify-content:space-between;
    }
    .cr-card-head h6 { margin:0; font-weight:700; color:#111827; }
    .cr-card-body { padding:22px; }

    .cr-label {
        font-size:.8rem; font-weight:700; color:#374151;
        text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px;
        display:block;
    }
    .cr-label .req { color:#ef4444; }
    .cr-input, .cr-select {
        border-radius:12px !important; border:1px solid #e5e7eb !important;
        padding:11px 14px !important; font-size:.92rem;
        background:#fbfbfd !important; transition: all .2s ease; width:100%;
    }
    .cr-input:focus, .cr-select:focus {
        background:#fff !important; border-color:#a5b4fc !important;
        box-shadow: 0 0 0 4px rgba(165,180,252,.25) !important; outline:none;
    }
    .cr-input.is-invalid, .cr-select.is-invalid {
        border-color:#dc2626 !important;
        background:#fef2f2 !important;
    }

    .cr-btn {
        display:inline-flex; align-items:center; gap:8px;
        border-radius:12px; padding:11px 20px; font-weight:600; font-size:.92rem;
        border:1px solid transparent; transition: all .2s ease; text-decoration:none;
    }
    .cr-btn-primary {
        background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff;
        box-shadow: 0 10px 20px -10px rgba(79,70,229,.55);
    }
    .cr-btn-primary:hover:not(:disabled) { transform: translateY(-1px); color:#fff; }
    .cr-btn-primary:disabled { opacity: .65; cursor: not-allowed; transform: none; }
    .cr-btn-ghost { background:#fff; color:#374151; border-color:#e5e7eb; }
    .cr-btn-ghost:hover { background:#f9fafb; color:#4f46e5; border-color:#c7d2fe; }

    .cr-spec-row {
        background:#fafbff; border:1px solid #eef0f5; border-radius:14px;
        padding:12px; margin-bottom:10px; transition: all .18s ease;
    }
    .cr-spec-row:hover { border-color:#c7d2fe; }
    .cr-btn-icon {
        width:40px; height:40px; display:grid; place-items:center;
        border-radius:10px; border:1px solid #fecaca; background:#fff;
        color:#dc2626; transition: all .18s ease; cursor:pointer;
    }
    .cr-btn-icon:hover { background:#dc2626; color:#fff; border-color:#dc2626; }
    .cr-add-spec {
        display:inline-flex; align-items:center; gap:6px;
        padding:7px 12px; border-radius:10px; font-size:.82rem; font-weight:600;
        border:1px solid #c7d2fe; background:#eef2ff; color:#4338ca;
        transition: all .18s ease; cursor:pointer;
    }
    .cr-add-spec:hover { background:#4f46e5; color:#fff; border-color:transparent; }

    .cr-inline-error {
        color:#dc2626; font-size:.8rem; margin-top:4px; display:none;
    }
    .cr-inline-error.show { display:block; }

    /* ===== self-contained toast ===== */
    .cr-toast-wrap {
        position: fixed; top: 20px; right: 20px; z-index: 99999;
        display: flex; flex-direction: column; gap: 10px;
        pointer-events: none;
    }
    .cr-toast {
        min-width: 240px; max-width: 380px;
        background: #fff; color: #111827;
        border-radius: 12px; padding: 12px 16px;
        box-shadow: 0 20px 45px -20px rgba(17,24,39,.45);
        border-left: 4px solid #4f46e5;
        font-size: .9rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        animation: crSlideIn .25s ease;
        pointer-events: auto;
    }
    .cr-toast.success { border-left-color: #059669; }
    .cr-toast.success i { color: #059669; }
    .cr-toast.error   { border-left-color: #dc2626; }
    .cr-toast.error i { color: #dc2626; }
    .cr-toast.info    { border-left-color: #4f46e5; }
    .cr-toast.info i  { color: #4f46e5; }
    .cr-toast i { font-size: 1.1rem; flex-shrink: 0; }
    @keyframes crSlideIn {
        from { transform: translateX(30px); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }
</style>

{{-- Toast container --}}
<div class="cr-toast-wrap" id="crToastWrap"></div>

<div class="cr-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-none cr-eyebrow">Asset Master</div>
            <h3>Create Asset</h3>
            <div class="" style="font-size:.94rem">Create the master asset and optionally create its initial units.</div>
        </div>
        <a href="{{ route('asset-management.assets.index') }}" class="cr-btn cr-btn-ghost"><i class="fa fa-arrow-left"></i> Back</a>
    </div>
</div>

<form id="assetForm" novalidate>
    {{-- Basic Info --}}
    <div class="cr-card">
        <div class="cr-card-head"><h6><i class="fa-solid fa-circle-info me-2 text-primary"></i>Basic Information</h6></div>
        <div class="cr-card-body row g-3">
            <div class="col-md-6">
                <label class="cr-label">Category <span class="req">*</span></label>
                <select name="category_id" class="cr-select" required>
                    <option value="">Select category</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->category_id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <div class="cr-inline-error" data-field="category_id"></div>
            </div>
            <div class="col-md-6">
                <label class="cr-label">Asset Name <span class="req">*</span></label>
                <input name="asset_name" class="cr-input" required placeholder="e.g. Laptop Computer">
                <div class="cr-inline-error" data-field="asset_name"></div>
            </div>
            <div class="col-md-6">
                <label class="cr-label">Initial Unit Count</label>
                <input type="number" min="0" placeholder="0" name="unit_count" class="cr-input">
                <div class="cr-inline-error" data-field="unit_count"></div>
            </div>
        </div>
    </div>

    {{-- Specifications --}}
    <div class="cr-card">
        <div class="cr-card-head">
            <h6><i class="fa-solid fa-sliders me-2 text-primary"></i>Asset Specifications</h6>
            <button type="button" class="cr-add-spec" onclick="addSpec()"><i class="fa fa-plus"></i> Add Field</button>
        </div>
        <div class="cr-card-body">
            <div id="specs">
                <div class="cr-spec-row row g-2 align-items-center">
                    <div class="col-md-5"><input class="cr-input sk" placeholder="Specification name"></div>
                    <div class="col-md-6"><input class="cr-input sv" placeholder="Value / default"></div>
                    <div class="col-md-1 text-end"><button type="button" class="cr-btn-icon" onclick="this.closest('.cr-spec-row').remove()"><i class="fa fa-trash"></i></button></div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="cr-btn cr-btn-primary" id="submitBtn">
            <i class="fa fa-save"></i> <span>Create Asset</span>
        </button>
    </div>
</form>

<script>
/* ============================================================
   Self-contained helpers — work regardless of layout
   ============================================================ */

// --- Toast ---
function toast(message, type = 'success') {
    const wrap = document.getElementById('crToastWrap');
    if (!wrap) { alert(message); return; }
    const icons = {
        success: 'fa-circle-check',
        error:   'fa-circle-exclamation',
        info:    'fa-circle-info',
    };
    const el = document.createElement('div');
    el.className = 'cr-toast ' + type;
    el.innerHTML = '<i class="fa-solid ' + (icons[type] || icons.info) + '"></i><span>' + message + '</span>';
    wrap.appendChild(el);
    setTimeout(() => {
        el.style.transition = 'opacity .25s ease, transform .25s ease';
        el.style.opacity = '0';
        el.style.transform = 'translateX(30px)';
        setTimeout(() => el.remove(), 260);
    }, 3400);
}

// --- API wrapper (prefers field-level validation errors) ---
async function api(url, options = {}) {
    const opts = {
        method: options.method || 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                            || '{{ csrf_token() }}',
            ...(options.headers || {}),
        },
        credentials: 'same-origin',
    };
    if (opts.method !== 'GET' && options.body !== undefined) {
        opts.body = options.body;
    }

    const res = await fetch(url, opts);
    let data = {};
    try { data = await res.json(); } catch (_) {}

    if (!res.ok) {
        let msg = null;
        if (data?.errors && typeof data.errors === 'object') {
            msg = Object.values(data.errors).flat().join(' ');
        }
        if (!msg) msg = data?.message || ('Request failed (' + res.status + ')');
        const err = new Error(msg);
        err.errors = data?.errors || null;
        err.status = res.status;
        throw err;
    }
    return data;
}

/* ============================================================
   Spec row helper
   ============================================================ */
function addSpec() {
    document.getElementById('specs').insertAdjacentHTML('beforeend', `
        <div class="cr-spec-row row g-2 align-items-center">
            <div class="col-md-5"><input class="cr-input sk" placeholder="Specification name"></div>
            <div class="col-md-6"><input class="cr-input sv" placeholder="Value / default"></div>
            <div class="col-md-1 text-end"><button type="button" class="cr-btn-icon" onclick="this.closest('.cr-spec-row').remove()"><i class="fa fa-trash"></i></button></div>
        </div>`);
}

/* ============================================================
   Inline error management
   ============================================================ */
function clearErrors() {
    document.querySelectorAll('.cr-inline-error').forEach(el => {
        el.textContent = '';
        el.classList.remove('show');
    });
    document.querySelectorAll('.cr-input.is-invalid, .cr-select.is-invalid')
        .forEach(el => el.classList.remove('is-invalid'));
}

function showErrors(errors) {
    if (!errors) return;
    Object.entries(errors).forEach(([field, msgs]) => {
        const input = document.querySelector(`[name="${field}"]`);
        const errEl = document.querySelector(`.cr-inline-error[data-field="${field}"]`);
        if (input) input.classList.add('is-invalid');
        if (errEl) {
            errEl.textContent = Array.isArray(msgs) ? msgs.join(' ') : msgs;
            errEl.classList.add('show');
        }
    });
}

/* ============================================================
   Submit handler
   ============================================================ */
document.getElementById('assetForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    const btn = document.getElementById('submitBtn');
    const btnLabel = btn.querySelector('span');
    const originalText = btnLabel.textContent;

    // Collect specs
    const specs = {};
    for (const r of document.querySelectorAll('.cr-spec-row')) {
        const k = r.querySelector('.sk').value.trim();
        const v = r.querySelector('.sv').value;
        if (k) specs[k] = v || null;
    }

    const f = new FormData(e.target);
    const body = {
        category_id: f.get('category_id'),
        asset_name:  f.get('asset_name'),
        unit_count:  Number(f.get('unit_count') || 0),
        specifications: specs,
    };

    // Client-side quick validation
    if (!body.category_id) {
        document.querySelector('[name="category_id"]').classList.add('is-invalid');
        document.querySelector('.cr-inline-error[data-field="category_id"]').textContent = 'Please select a category.';
        document.querySelector('.cr-inline-error[data-field="category_id"]').classList.add('show');
        toast('Please select a category', 'error');
        return;
    }
    if (!body.asset_name) {
        document.querySelector('[name="asset_name"]').classList.add('is-invalid');
        document.querySelector('.cr-inline-error[data-field="asset_name"]').textContent = 'Asset name is required.';
        document.querySelector('.cr-inline-error[data-field="asset_name"]').classList.add('show');
        toast('Asset name is required', 'error');
        return;
    }

    try {
        btn.disabled = true;
        btnLabel.textContent = 'Creating...';

        const d = await api('{{ route('asset-management.assets.store') }}', {
            method: 'POST',
            body: JSON.stringify(body),
        });

        toast(d.message || 'Asset created successfully');

        // Redirect after a short pause so the user sees the toast
        setTimeout(() => {
            location.href = '{{ url('/institute/admin/asset-management/assets') }}/' + d.asset_id;
        }, 700);
    } catch (x) {
        if (x.errors) showErrors(x.errors);
        toast(x.message || 'Unable to create asset', 'error');
        btn.disabled = false;
        btnLabel.textContent = originalText;
    }
});
</script>
@endsection