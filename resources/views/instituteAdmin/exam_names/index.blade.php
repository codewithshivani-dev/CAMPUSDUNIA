@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<style>
    .page-icon {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .table-row {
        transition: background-color 0.15s ease;
    }
    .table-row:hover {
        background-color: #f8fafc;
    }
    .term-badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    .term-mid_term { background: #dbeafe; color: #1e40af; }
    .term-final { background: #fce7f3; color: #9d174d; }
    .term-prelim { background: #fef3c7; color: #92400e; }
    .term-unit_test { background: #d1fae5; color: #065f46; }
    .term-other { background: #f1f5f9; color: #475569; }
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }
    .btn-outline-primary {
        border-color: #e2e8f0;
        color: #6366f1;
    }
    .btn-outline-primary:hover {
        background: #6366f1;
        border-color: #6366f1;
        color: #fff;
    }
    .btn-outline-danger {
        border-color: #e2e8f0;
        color: #ef4444;
    }
    .btn-outline-danger:hover {
        background: #ef4444;
        border-color: #ef4444;
        color: #fff;
    }
    .pagination .page-link {
        border: none;
        border-radius: 0.5rem !important;
        margin: 0 2px;
        color: #6366f1;
        font-size: 0.875rem;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: #fff;
    }
    .pagination .page-link:hover {
        background: #eef2ff;
    }
    .empty-state {
        padding: 2rem 0;
    }
    .table th {
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .table td {
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<div class="container-fluid py-4">
    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="page-icon bg-primary bg-opacity-10 rounded-3 p-3">
                <i class="fas fa-file-signature fa-2x"></i>
            </div>
            <div>
                <h2 class="mb-0 fw-bold">Exam Names</h2>
                <p class="text-muted mb-0">Manage exam names used when creating examinations and admit cards.</p>
            </div>
        </div>
        <a href="{{ route('institute.exam-names.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i>Add Exam Name
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 rounded-3">
                        <i class="fas fa-list fa-lg text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Exam Names</div>
                        <div class="fw-bold fs-4">{{ $examNames->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 rounded-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Active</div>
                        <div class="fw-bold fs-4">{{ $examNames->where('is_active', true)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 rounded-3">
                        <i class="fas fa-calendar-alt fa-lg text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Academic Years</div>
                        <div class="fw-bold fs-4">{{ $academicYears->count() ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 rounded-3">
                        <i class="fas fa-layer-group fa-lg text-info"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Current Page</div>
                        <div class="fw-bold fs-4">{{ $examNames->currentPage() }} / {{ $examNames->lastPage() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0 fw-semibold">
                <i class="fas fa-table me-2 text-primary"></i>All Exam Names
            </h5>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                {{ $examNames->total() }} {{ Str::plural('record', $examNames->total()) }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 fw-semibold text-muted small text-uppercase" style="min-width: 220px;">Name</th>
                        <th class="px-4 py-3 fw-semibold text-muted small text-uppercase">Academic Year</th>
                        <th class="px-4 py-3 fw-semibold text-muted small text-uppercase">Term</th>
                        <th class="px-4 py-3 fw-semibold text-muted small text-uppercase">Status</th>
                        <th class="px-4 py-3 fw-semibold text-muted small text-uppercase text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examNames as $examName)
                        <tr class="table-row">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary fw-bold">
                                        {{ strtoupper(substr($examName->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $examName->name }}</div>
                                        @if($examName->description)
                                            <div class="text-muted small text-truncate" style="max-width: 280px;">
                                                {{ $examName->description }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                                    <i class="fas fa-calendar-alt me-1 text-muted"></i>{{ $examName->academic_year }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge term-badge term-{{ $examName->term }} rounded-pill px-3 py-2">
                                    {{ ucwords(str_replace('_', ' ', $examName->term)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($examName->is_active)
                                    <span class="badge bg-success bg-opacity-10  rounded-pill px-3 py-2">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('institute.exam-names.edit', $examName->id) }}" 
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                       title="Edit">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form method="POST"  class="d-none"
                                          action="{{ route('institute.exam-names.destroy', $examName->id) }}" 
                                          class="d-inline" 
                                          onsubmit="return confirm('Delete this exam name? This action cannot be undone.');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                title="Delete">
                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3 d-block" style="opacity: 0.3;"></i>
                                    <h5 class="text-muted fw-semibold">No Exam Names Found</h5>
                                    <p class="text-muted small mb-3">Get started by creating your first exam name.</p>
                                    <a href="{{ route('institute.exam-names.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create Exam Name
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($examNames->hasPages())
            <div class="card-footer bg-white border-top py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $examNames->firstItem() }} to {{ $examNames->lastItem() }} of {{ $examNames->total() }} results
                    </div>
                    <div>
                        {{ $examNames->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
