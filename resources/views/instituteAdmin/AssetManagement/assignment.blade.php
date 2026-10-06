@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .am-hero-sm {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border: 1px solid #e6e6ff; border-radius: 22px;
        padding: 24px 28px; margin-bottom: 22px;
        color: white;
    }

    .am-hero-sm .am-eyebrow {
        font-size:.78rem; font-weight:700; letter-spacing:.08em;
        text-transform:uppercase;
    }

    .am-hero-sm h3 {
        font-weight:800; letter-spacing:-.02em; margin:4px 0 6px;
    }

    .am-note {
        border:1px dashed #c7d2fe; background:#f5f7ff;
        border-radius:16px; padding:16px 18px; margin-bottom:22px;
        display:flex; gap:14px; align-items:flex-start;
    }

    .am-note i {
        color:#4f46e5;
        font-size:1.05rem;
        margin-top:2px;
    }

    .am-note strong {
        color:#1e1b4b;
    }

    .am-note code {
        background:#e0e7ff;
        color:#3730a3;
        padding:2px 6px;
        border-radius:6px;
        font-size:.82rem;
    }

    .am-card {
        background:#fff;
        border:1px solid #eef0f5;
        border-radius:20px;
        overflow:hidden;
        box-shadow: 0 20px 45px -32px rgba(17,24,39,.3);
    }

    .am-card-head {
        padding:18px 22px;
        border-bottom:1px solid #eef0f5;
        background: linear-gradient(180deg,#fff,#fafbff);
    }

    .am-card-head h6 {
        margin:0;
        font-weight:700;
        color:#111827;
    }

    .am-card-body {
        padding:22px;
    }

    .am-field {
        margin-bottom: 4px;
    }

    .am-label {
        font-size:.8rem;
        font-weight:700;
        color:#374151;
        text-transform:uppercase;
        letter-spacing:.04em;
        margin-bottom:6px;
        display:flex;
        align-items:center;
        gap:6px;
    }

    .am-label .req {
        color:#ef4444;
    }

    /* ===== Native select styling ===== */
    .am-select, .am-textarea, .am-input {
        border-radius:12px !important;
        border:1px solid #e5e7eb !important;
        padding:11px 14px !important;
        font-size:.92rem;
        background-color:#fbfbfd !important;
        transition: all .2s ease;
        width:100%;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
    }
    .am-select {
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236b7280'><path d='M4.5 6l3.5 4 3.5-4z'/></svg>") !important;
        background-repeat: no-repeat !important;
        background-position: right 12px center !important;
        background-size: 16px !important;
        padding-right: 36px !important;
        cursor: pointer;
    }
    .am-select:focus, .am-textarea:focus, .am-input:focus {
        background-color:#fff !important;
        border-color:#a5b4fc !important;
        box-shadow: 0 0 0 4px rgba(165,180,252,.25) !important;
        outline:none;
    }
    .am-select:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    .am-hint {
        font-size:.78rem;
        color:#6b7280;
        margin-top:6px;
    }

    .am-btn {
        display:inline-flex; align-items:center; gap:8px;
        border-radius:12px; padding:11px 20px;
        font-weight:600; font-size:.92rem;
        border:1px solid transparent; transition: all .2s ease;
        text-decoration:none;
    }
    .am-btn-primary {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color:#fff; box-shadow: 0 10px 20px -10px rgba(79,70,229,.55);
    }
    .am-btn-primary:hover { transform: translateY(-1px); color:#fff; }
    .am-btn-primary:disabled { opacity:.65; cursor:not-allowed; transform:none; }
    .am-btn-ghost { background:#fff; color:#374151; border-color:#e5e7eb; }
    .am-btn-ghost:hover { background:#f9fafb; color:#4f46e5; border-color:#c7d2fe; }

    .am-divider { height:1px; background:#eef0f5; margin:22px 0; }

    .am-type-toggle { display:flex; gap:8px; }
    .am-type-toggle label {
        flex:1; cursor:pointer; border:1px solid #e5e7eb; border-radius:12px;
        padding:11px 14px; font-weight:600; font-size:.9rem; color:#4b5563;
        display:flex; align-items:center; gap:8px; justify-content:center;
        background:#fbfbfd; transition: all .18s ease;
        margin: 0;
    }
    .am-type-toggle input { display:none; }
    .am-type-toggle input:checked + label {
        background: linear-gradient(135deg,#eef2ff,#f5f3ff);
        border-color:#a5b4fc; color:#4338ca;
        box-shadow: 0 0 0 3px rgba(165,180,252,.2);
    }

    /* toast */
    .am-toast-wrap {
        position: fixed; top: 20px; right: 20px; z-index: 99999;
        display: flex; flex-direction: column; gap: 10px;
        pointer-events: none;
    }
    .am-toast {
        min-width: 240px; max-width: 380px;
        background: #fff; color: #111827;
        border-radius: 12px; padding: 12px 16px;
        box-shadow: 0 20px 45px -20px rgba(17,24,39,.45);
        border-left: 4px solid #4f46e5;
        font-size: .9rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        animation: amSlideIn .25s ease;
        pointer-events: auto;
    }
    .am-toast.success { border-left-color: #059669; }
    .am-toast.success i { color: #059669; }
    .am-toast.error   { border-left-color: #dc2626; }
    .am-toast.error i { color: #dc2626; }
    .am-toast i { font-size: 1.1rem; flex-shrink: 0; }
    @keyframes amSlideIn {
        from { transform: translateX(30px); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }
</style>

<div class="am-toast-wrap" id="amToastWrap"></div>

{{-- Hero --}}
<div class="am-hero-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="am-eyebrow">Asset Management</div>
            <h3>Assign Asset</h3>
        </div>
        <a href="{{ route('asset-management.dashboard') }}" class="am-btn am-btn-ghost"><i class="fa fa-gauge"></i> Dashboard</a>
    </div>
</div>

{{-- Info note --}}
<div class="am-note">
    <i class="fa-solid fa-circle-info"></i>
    <div>
        <strong>Allocation and Assignment are separate.</strong><br>
        <span class="text-muted" style="font-size:.88rem">Allocation = where the physical unit is located. Assignment = who/which department is responsible for it.</span>
    </div>
</div>

{{-- Form --}}
<div class="am-card">
    <div class="am-card-head"><h6><i class="fa-solid fa-user-check me-2 text-primary"></i>Assignment Details</h6></div>
    <div class="am-card-body">

        <div class="row g-4">
            {{-- ASSET --}}
            <div class="col-md-6 am-field">
                <label class="am-label">Asset <span class="req">*</span></label>
                <select id="assetSelect" class="am-select">
                    <option value="">— Select asset —</option>
                    @foreach($amenities as $a)
                        <option value="{{ $a->asset_id }}" @if(optional($selectedAsset)->asset_id == $a->asset_id) selected @endif>
                            {{ $a->name }} — {{ $a->asset_id }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PHYSICAL UNIT --}}
            <div class="col-md-6 am-field">
                <label class="am-label">Physical Unit <span class="req">*</span></label>
                <select id="unitSelect" class="am-select">
                    <option value="">— Select unit —</option>
                    @foreach($units as $u)
                        <option value="{{ $u->unit_id }}">
                            Unit {{ $u->unit_number }} — {{ $u->unit_id }} ({{ ucfirst($u->status) }})
                        </option>
                    @endforeach
                </select>
                <div class="am-hint"><i class="fa fa-circle-info me-1"></i>Select one physical unit at a time.</div>
            </div>

            {{-- TYPE TOGGLE --}}
            <div class="col-12">
                <label class="am-label">Assign To <span class="req">*</span></label>
                <div class="am-type-toggle">
                    <input type="radio" name="assign_type" id="typeDept" value="department" checked>
                    <label for="typeDept"><i class="fa fa-building"></i> Department</label>

                    <input type="radio" name="assign_type" id="typeEmp" value="employee">
                    <label for="typeEmp"><i class="fa fa-user"></i> Employee</label>
                </div>
            </div>

            {{-- DEPARTMENT --}}
            <div class="col-12" id="deptWrap">
                <label class="am-label">Department <span class="req">*</span></label>
                <select id="deptSelect" class="am-select">
                    <option value="">— Select department —</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->department_id }}">
                            {{ $d->department }} — {{ $d->department_id }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- EMPLOYEE --}}
            <div class="col-12" id="employeeWrap" style="display:none">
                <label class="am-label">Employee <span class="req">*</span></label>
                <select id="empSelect" class="am-select">
                    <option value="">— Select employee —</option>
                    @foreach($employees as $e)
                        <option value="{{ $e->employee_id }}">
                            {{ $e->name }} — {{ $e->employee_id }}@if(!empty($e->department)) · {{ $e->department }}@endif
                        </option>
                    @endforeach
                </select>
                <div class="am-hint"><i class="fa fa-circle-info me-1"></i>Hold Ctrl/Cmd to jump through options, or type to search.</div>
            </div>

            {{-- NOTES --}}
            <div class="col-12">
                <label class="am-label">Assignment Notes</label>
                <textarea id="notes" class="am-textarea" rows="3" placeholder="Optional assignment notes"></textarea>
            </div>
        </div>

        <div class="am-divider"></div>

        <div class="d-flex justify-content-end">
            <button id="assignBtn" class="am-btn am-btn-primary">
                <i class="fa-solid fa-user-check"></i> Save Assignment
            </button>
        </div>
    </div>
</div>

<script>
/* ============ Self-contained helpers (no jQuery, no Select2) ============ */
function toast(message, type = 'success') {
    const wrap = document.getElementById('amToastWrap');
    if (!wrap) { alert(message); return; }
    const icons = { success:'fa-circle-check', error:'fa-circle-exclamation', info:'fa-circle-info' };
    const el = document.createElement('div');
    el.className = 'am-toast ' + type;
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

/* ============ Native type-to-search on <select> ============
   When a user types a letter while the select is focused, the browser
   automatically jumps to the first option starting with that letter.
   For multi-letter matching we add a small filter helper below. */

function filterSelectByPrefix(selectEl, prefix) {
    if (!selectEl) return;
    const q = (prefix || '').toLowerCase();
    if (!q) return;
    const options = Array.from(selectEl.options);
    const match = options.find(o => o.text.toLowerCase().startsWith(q))
               || options.find(o => o.text.toLowerCase().includes(q));
    if (match) {
        selectEl.value = match.value;
        selectEl.dispatchEvent(new Event('change'));
    }
}

/* ============ Boot ============ */
document.addEventListener('DOMContentLoaded', function () {

    const assetSelect = document.getElementById('assetSelect');
    const unitSelect  = document.getElementById('unitSelect');
    const deptSelect  = document.getElementById('deptSelect');
    const empSelect   = document.getElementById('empSelect');
    const notes       = document.getElementById('notes');
    const assignBtn   = document.getElementById('assignBtn');

    /* ===== Asset change → reload with ?asset_id ===== */
    assetSelect.addEventListener('change', function () {
        const v = this.value;
        if (v) {
            location.href = '{{ url('/institute/admin/asset-management/assignment') }}?asset_id=' + encodeURIComponent(v);
        }
    });

    /* ===== Toggle Department / Employee ===== */
    document.querySelectorAll('input[name="assign_type"]').forEach(r => {
        r.addEventListener('change', () => {
            const employeeMode = document.getElementById('typeEmp').checked;
            document.getElementById('deptWrap').style.display     = employeeMode ? 'none' : '';
            document.getElementById('employeeWrap').style.display = employeeMode ? '' : 'none';
        });
    });

    /* ===== Optional: type-to-search enhancement for long lists =====
       Pressing any printable key while the dropdown is closed will
       match against full option text (not just first letter).       */
    function attachSearch(el) {
        if (!el) return;
        let buffer = '';
        let timer = null;
        el.addEventListener('keydown', function (e) {
            if (e.key.length === 1) {
                buffer += e.key.toLowerCase();
                clearTimeout(timer);
                timer = setTimeout(() => { buffer = ''; }, 1200);
                // Let the browser handle arrow keys / enter / tab
                if (e.key === ' ' || e.key === 'Enter') return;
            }
        });
    }
    attachSearch(assetSelect);
    attachSearch(unitSelect);
    attachSearch(deptSelect);
    attachSearch(empSelect);

    /* ===== Submit ===== */
    assignBtn.addEventListener('click', async () => {
        const unitId    = unitSelect.value;
        const type      = document.getElementById('typeEmp').checked ? 'employee' : 'department';
        const assigneeId = type === 'department' ? deptSelect.value : empSelect.value;

        if (!unitId)      return toast('Select a physical unit', 'error');
        if (!assigneeId)  return toast('Select an assignee', 'error');

        const originalHTML = assignBtn.innerHTML;
        assignBtn.disabled = true;
        assignBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Saving…';

        try {
            const d = await api('{{ route('asset-management.assignment.store') }}', {
                method: 'POST',
                body: JSON.stringify({
                    unit_ids: [unitId],
                    assign_to_type: type,
                    assign_to_ids: assigneeId,
                    assign_notes: notes.value,
                }),
            });
            toast(d.message || 'Assignment saved');
            setTimeout(() => location.reload(), 700);
        } catch (e) {
            toast(e.message || 'Assignment failed', 'error');
            assignBtn.disabled = false;
            assignBtn.innerHTML = originalHTML;
        }
    });

});
</script>
@endsection