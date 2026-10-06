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
    .card {
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;
    }
    .alert-danger {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        color: #991b1b;
        border-radius: 0.75rem;
    }
</style>

<div class="container-fluid py-4">
    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="page-icon bg-primary bg-opacity-10 rounded-3 p-3">
                <i class="fas fa-plus-circle fa-2x"></i>
            </div>
            <div>
                <h2 class="mb-0 fw-bold">Create Exam Name</h2>
                <p class="text-muted mb-0">Add a new exam name to your institution</p>
            </div>
        </div>
        <a href="{{ route('institute.exam-names.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    {{-- Error Alert --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                <div>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="row justify-content-center">
        <div class="">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary bg-opacity-10 rounded-pill px-3 py-2">
                            <i class="fas fa-info-circle me-1"></i>Exam Details
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('institute.exam-names.store') }}">
                        @include('instituteAdmin.exam_names._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

