@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px 30px;
        background: var(--primary-gradient);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-title i {
        font-size: 32px;
    }

    /* Main Card */
    .main-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        background: white;
    }

    .card-header {
        background: var(--primary-gradient) !important;
        border-bottom: none;
        padding: 20px 30px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    .card-header h4 {
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h4 i {
        font-size: 32px;
    }

    .card-body {
        padding: 30px;
        background: white;
    }

    /* Form Elements */
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-label i {
        color: var(--primary-color);
        margin-right: 5px;
    }

    .text-danger {
        color: #ef4444 !important;
        font-size: 12px;
        margin-left: 4px;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s;
        background: white;
        height: auto;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-control:hover {
        border-color: var(--secondary-color);
    }

    /* Buttons */
    .btn {
        border-radius: 12px;
        font-weight: 600;
        padding: 12px 30px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-success::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-success:hover::before {
        left: 100%;
    }

    /* Alert Messages */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 1px solid #86efac;
        color: #166534;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Info Text */
    .text-danger.small {
        font-size: 13px;
        margin: 10px 0 0;
        padding-right: 20px;
        text-align: right;
    }

    .text-danger.small strong {
        color: var(--danger-color);
    }

    /* Error Message */
    .text-danger.small:not(.alert) {
        margin-top: 5px;
        font-size: 12px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .card-body {
            padding: 20px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Department row styling (for future use) */
    .department-row {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        transition: all 0.3s;
    }

    .department-row:hover {
        border-color: var(--primary-color);
        transform: translateX(5px);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
    }

    .remove-department {
        color: #ef4444;
        cursor: pointer;
        font-size: 1.2rem;
        background: transparent;
        border: none;
        padding: 5px 10px;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .remove-department:hover {
        color: white;
        background: var(--danger-gradient);
        transform: scale(1.1);
    }

    .category-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border-left: 4px solid var(--primary-color);
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    #departmentsContainer {
        max-height: 600px;
        overflow-y: auto;
        padding: 10px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: linear-gradient(135deg, #fafafa, #f8fafc);
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #64748b;
    }

    .empty-state i {
        font-size: 48px;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 15px;
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <!--<div class="page-header">-->
    <!--    <h1 class="page-title">-->
    <!--        <i class="bi bi-folder-fill"></i>-->
    <!--        Add Department Category-->
    <!--    </h1>-->
    <!--</div>-->

    <!-- Main Card -->
    <div class="main-card">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="bi bi-folder-fill"></i>
                Add Department Category
            </h4>
            <!-- Required Fields Note -->
            <div class="text-danger small">
                <i class="bi bi-info-circle-fill me-1"></i>
                Fields marked with <strong>(*)</strong> are mandatory.
            </div>
        </div>

        <div class="card-body">

            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form id="categoryForm" action="{{ route('department-categories.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_name" class="form-label">
                                <i class="bi bi-tag-fill"></i> Category Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="category_name" name="category_name"
                                placeholder="e.g. Studies, Sports, Accounts, Admin, Transport" required>
                            @error('category_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_description" class="form-label">
                                <i class="bi bi-pencil-fill"></i> Category Description (Optional)
                            </label>
                            <input type="text" class="form-control" id="category_description" name="description"
                                placeholder="Brief description of the category...">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="additional_notes" class="form-label">
                                <i class="bi bi-sticky-fill"></i> Additional Notes (Optional)
                            </label>
                            <textarea class="form-control" id="additional_notes" name="additional_notes" 
                                      rows="3" placeholder="Any additional information about this category..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Category Information Box -->
                <div class="category-info mt-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill fs-4 me-3" style="color: var(--primary-color);"></i>
                        <div>
                            <h6 class="fw-bold mb-1">About Categories</h6>
                            <p class="small text-muted mb-0">
                                Categories help organize departments into logical groups. For example, you can create categories like "Academic", "Administrative", "Transport", etc., and then add related departments under them.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success px-5">
                        <i class="bi bi-plus-circle-fill me-2"></i> Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection