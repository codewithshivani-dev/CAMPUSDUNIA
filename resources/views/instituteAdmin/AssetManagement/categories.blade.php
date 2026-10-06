@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
    .ct-hero {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border: 1px solid #e6e6ff; border-radius: 22px;
        padding: 24px 28px; margin-bottom: 22px;
        color: white;
    }

    .ct-hero .ct-eyebrow {
        font-size:.78rem;
        font-weight:700;
        letter-spacing:.08em;
        text-transform:uppercase;
    }

    .ct-hero h3 {
        font-weight:800;
        letter-spacing:-.02em;
        margin:4px 0 6px;
    }

    .ct-btn {
        display:inline-flex; align-items:center; gap:8px;
        border-radius:12px; padding:11px 20px; font-weight:600; font-size:.92rem;
        border:1px solid transparent; transition: all .2s ease; text-decoration:none;
    }
    .ct-btn-primary {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color:#fff; box-shadow: 0 10px 20px -10px rgba(79,70,229,.55);
    }
    .ct-btn-primary:hover { transform: translateY(-1px); color:#fff; }

    .ct-card { background:#fff; border:1px solid #eef0f5; border-radius:20px; overflow:hidden; box-shadow: 0 20px 45px -32px rgba(17,24,39,.3); }
    .ct-card-head {
        padding:18px 22px; border-bottom:1px solid #eef0f5;
        background: linear-gradient(180deg,#fff,#fafbff);
        display:flex; align-items:center; justify-content:space-between;
    }
    .ct-card-head h6 { margin:0; font-weight:700; color:#111827; }
    .ct-chip {
        display:inline-flex; align-items:center; gap:6px;
        font-size:.75rem; font-weight:600; padding:4px 10px; border-radius:999px;
        background:#eef2ff; color:#4338ca;
    }

    .ct-table { margin:0; }
    .ct-table thead th {
        font-size:.72rem; text-transform:uppercase; letter-spacing:.06em;
        color:#6b7280; font-weight:700; background:#fafbff;
        padding:14px 18px; border-bottom:1px solid #eef0f5;
    }
    .ct-table tbody td { padding:16px 18px; border-bottom:1px solid #f3f4f6; vertical-align:middle; font-size:.92rem; color:#374151; }
    .ct-table tbody tr:hover { background:#fafbff; }
    .ct-table tbody tr:last-child td { border-bottom:0; }
    .ct-id {
        display:inline-flex; align-items:center;
        font-family: ui-monospace, monospace; font-size:.78rem; font-weight:600;
        padding:4px 10px; border-radius:8px;
        background:#eef2ff; color:#4338ca; border:1px solid #e0e7ff;
    }
    .ct-name { font-weight:600; color:#111827; }
    .ct-count {
        display:inline-flex; align-items:center; gap:6px;
        font-weight:700; color:#111827;
    }
    .ct-icon-btn {
        width:34px; height:34px; display:inline-grid; place-items:center;
        border-radius:10px; border:1px solid #e5e7eb; background:#fff;
        color:#4b5563; transition: all .18s ease; text-decoration:none; cursor:pointer;
    }
    .ct-icon-btn:hover { transform: translateY(-1px); }
    .ct-icon-btn.sec:hover { color:#4f46e5; border-color:#c7d2fe; background:#eef2ff; }
    .ct-icon-btn.pri:hover { color:#fff; background: linear-gradient(135deg,#4f46e5,#7c3aed); border-color:transparent; }
    .ct-icon-btn.dng:hover { color:#fff; background:#dc2626; border-color:#dc2626; }

    .modal-content { border:0; border-radius:18px; overflow:hidden; }
    .modal-header { background: linear-gradient(135deg,#eef2ff,#f5f3ff); border-bottom:1px solid #e6e6ff; }
    .modal-title { font-weight:700; color:#111827; }
    .modal-footer { border-top:1px solid #eef0f5; }
    .ct-input { border-radius:12px !important; border:1px solid #e5e7eb !important; padding:11px 14px !important; background:#fbfbfd !important; }
    .ct-input:focus { background:#fff !important; border-color:#a5b4fc !important; box-shadow: 0 0 0 4px rgba(165,180,252,.25) !important; outline:none; }

    /* ===== self-contained toast ===== */
    .ct-toast-wrap {
        position: fixed; top: 20px; right: 20px; z-index: 99999;
        display: flex; flex-direction: column; gap: 10px;
        pointer-events: none;
    }
    .ct-toast {
        min-width: 240px; max-width: 360px;
        background: #fff; color: #111827;
        border-radius: 12px; padding: 12px 16px;
        box-shadow: 0 20px 45px -20px rgba(17,24,39,.45);
        border-left: 4px solid #4f46e5;
        font-size: .9rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        animation: ctSlideIn .25s ease;
        pointer-events: auto;
    }
    .ct-toast.success { border-left-color: #059669; }
    .ct-toast.success i { color: #059669; }
    .ct-toast.error   { border-left-color: #dc2626; }
    .ct-toast.error i { color: #dc2626; }
    .ct-toast.info    { border-left-color: #4f46e5; }
    .ct-toast.info i  { color: #4f46e5; }
    .ct-toast i { font-size: 1.1rem; flex-shrink: 0; }
    @keyframes ctSlideIn {
        from { transform: translateX(30px); opacity: 0; }
        to   { transform: translateX(0); opacity: 1; }
    }
</style>

{{-- Toast container --}}
<div class="ct-toast-wrap" id="ctToastWrap"></div>

{{-- Hero --}}
<div class="ct-hero">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="ct-eyebrow">Asset Master</div>
            <h3>Categories</h3>
            <div class="" style="font-size:.94rem">Manage asset categories. Edit is available from Actions.</div>
        </div>
        <button class="ct-btn ct-btn-primary" data-bs-toggle="modal" data-bs-target="#createCategory"><i class="fa fa-plus"></i> Create Category</button>
    </div>
</div>

{{-- List --}}
<div class="ct-card">
    <div class="ct-card-head">
        <h6><i class="fa-solid fa-list me-2 text-primary"></i>Category List</h6>
        <span class="ct-chip"><i class="fa fa-folder"></i> {{ $categories->count() }} categories</span>
    </div>

    <div class="table-responsive">
        <table class="table ct-table align-middle">
            <thead>
                <tr>
                    <th style="width:60px">#</th>
                    <th>Category ID</th>
                    <th>Name</th>
                    <th>Assets</th>
                    <th class="text-end" style="width:170px">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($categories as $i => $c)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td><span class="ct-id">{{ $c->category_id }}</span></td>
                    <td class="ct-name">{{ $c->name }}</td>
                    <td><span class="ct-count"><i class="fa fa-box text-primary" style="font-size:.8rem"></i> {{ $c->assets_count }}</span></td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-2">
                            <a class="ct-icon-btn sec" href="{{ route('asset-management.categories.assets', $c->id) }}" title="View"><i class="fa fa-eye"></i></a>
                            <button class="ct-icon-btn pri" data-bs-toggle="modal" data-bs-target="#edit{{ $c->id }}" title="Edit"><i class="fa fa-pen"></i></button>
                            <button class="ct-icon-btn dng" onclick="deleteCategory({{ $c->id }})" title="Delete"><i class="fa fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-5 text-muted">
                    <i class="fa-regular fa-folder-open d-block mb-2" style="font-size:2.4rem;color:#c7d2fe"></i>
                    <div class="fw-semibold text-dark mb-1">No categories found</div>
                    <div class="small">Create your first category to get started.</div>
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Edit Modals (outside the table to avoid DOM nesting issues) --}}
@foreach($categories as $c)
    <div class="modal fade" id="edit{{ $c->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label small fw-semibold">Category Name</label>
                    <input class="form-control ct-input" id="name{{ $c->id }}" value="{{ e($c->name) }}">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="ct-btn ct-btn-primary" onclick="editCategory({{ $c->id }})">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Create Modal --}}
<div class="modal fade" id="createCategory" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Category</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label small fw-semibold">Category Name</label>
                <input class="form-control ct-input" id="categoryName" placeholder="e.g. IT Equipment">
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="ct-btn ct-btn-primary" onclick="createCategory()">Create</button>
            </div>
        </div>
    </div>
</div>

<script>
/* ============================================================
   Self-contained helpers — work regardless of layout
   ============================================================ */

// --- Toast ---
function toast(message, type = 'success') {
    const wrap = document.getElementById('ctToastWrap');
    const icons = {
        success: 'fa-circle-check',
        error:   'fa-circle-exclamation',
        info:    'fa-circle-info',
    };
    const el = document.createElement('div');
    el.className = 'ct-toast ' + type;
    el.innerHTML = '<i class="fa-solid ' + (icons[type] || icons.info) + '"></i><span>' + message + '</span>';
    wrap.appendChild(el);
    setTimeout(() => {
        el.style.transition = 'opacity .25s ease, transform .25s ease';
        el.style.opacity = '0';
        el.style.transform = 'translateX(30px)';
        setTimeout(() => el.remove(), 260);
    }, 3200);
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

        // 1. Prefer specific field-level validation messages
        if (data?.errors && typeof data.errors === 'object') {
            msg = Object.values(data.errors).flat().join(' ');
        }

        // 2. Fall back to top-level message
        if (!msg) {
            msg = data?.message || ('Request failed (' + res.status + ')');
        }

        throw new Error(msg);
    }
    return data;
}

/* ============================================================
   Category actions
   ============================================================ */

async function createCategory() {
    try {
        const input = document.getElementById('categoryName');
        const n = input.value.trim();
        if (!n) { toast('Category name is required', 'error'); input.focus(); return; }
        const d = await api('{{ route('asset-management.categories.store') }}', {
            method: 'POST',
            body: JSON.stringify({ name: n }),
        });
        toast(d.message || 'Category created');
        setTimeout(() => location.reload(), 700);
    } catch (e) {
        toast(e.message || 'Unable to create category', 'error');
    }
}

async function editCategory(id) {
    try {
        const input = document.getElementById('name' + id);
        const n = input.value.trim();
        if (!n) { toast('Category name is required', 'error'); input.focus(); return; }
        const d = await api('{{ url('/institute/admin/asset-management/categories') }}/' + id, {
            method: 'PUT',
            body: JSON.stringify({ name: n }),
        });
        toast(d.message || 'Category updated');
        setTimeout(() => location.reload(), 700);
    } catch (e) {
        toast(e.message || 'Unable to update category', 'error');
    }
}

async function deleteCategory(id) {
    if (!confirm('Delete this category?')) return;
    try {
        const d = await api('{{ url('/institute/admin/asset-management/categories') }}/' + id, {
            method: 'DELETE',
        });
        toast(d.message || 'Category deleted');
        setTimeout(() => location.reload(), 700);
    } catch (e) {
        toast(e.message || 'Unable to delete category', 'error');
    }
}
</script>

@endsection