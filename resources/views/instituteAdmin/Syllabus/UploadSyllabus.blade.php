@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1, #8b5cf6);
        --primary-color: #6366f1;
        --primary-dark: #4f46e5;
        --secondary-color: #8b5cf6;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --bg-light: #f8fafc;
        --border-color: #e2e8f0;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 8px 30px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.15);
        --shadow-xl: 0 25px 80px rgba(99, 102, 241, 0.2);
        --radius: 16px;
        --radius-sm: 10px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 28px 35px;
        background: var(--primary-gradient);
        border-radius: var(--radius);
        box-shadow: var(--shadow-xl);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        pointer-events: none;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -60%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
        pointer-events: none;
    }

    .page-title {
        font-size: 30px;
        font-weight: 800;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 14px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 1;
    }

    .page-title i {
        font-size: 34px;
        filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.2));
    }

    .page-title small {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        background: rgba(255, 255, 255, 0.15);
        padding: 4px 16px;
        border-radius: 30px;
        backdrop-filter: blur(10px);
    }

    .btn-view-all {
        padding: 12px 28px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #fff !important;
        border-radius: var(--radius-sm);
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: var(--transition);
        position: relative;
        z-index: 1;
    }

    .btn-view-all:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        text-decoration: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .main-card {
        border: none;
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        background: white;
    }

    .card-body {
        padding: 40px;
    }

    .card-body.bg-light {
        background: var(--bg-light) !important;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
    }

    .section-header i {
        font-size: 24px;
        color: var(--primary-color);
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        padding: 8px;
        border-radius: 10px;
    }

    .section-header h5 {
        margin: 0;
        font-weight: 700;
        color: var(--text-primary);
        font-size: 18px;
    }

    .section-header .badge {
        margin-left: auto;
        background: var(--primary-gradient);
        color: white;
        padding: 4px 14px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 12px;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
        font-size: 13px;
        letter-spacing: 0.2px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-label i {
        color: var(--primary-color);
        font-size: 15px;
    }

    .form-label .required {
        color: #ef4444;
        font-weight: 700;
    }

    .form-control,
    .form-select {
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        font-size: 14px;
        transition: var(--transition);
        background: white;
        height: auto;
        color: var(--text-primary);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .form-control:hover,
    .form-select:hover {
        border-color: var(--primary-color);
    }

    .form-control::placeholder {
        color: #94a3b8;
        font-size: 13px;
    }

    .shadow-sm {
        box-shadow: var(--shadow-sm) !important;
    }

    .alert {
        border: none;
        border-radius: var(--radius-sm);
        padding: 16px 24px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 14px;
        animation: slideIn 0.4s ease;
        font-weight: 500;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        border-left: 4px solid var(--success-color);
        color: #065f46;
    }

    .alert-danger {
        background: linear-gradient(135deg, #fef2f2, #fecaca);
        border-left: 4px solid #ef4444;
        color: #991b1b;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fffbeb, #fde68a);
        border-left: 4px solid #f59e0b;
        color: #92400e;
    }

    .topic-item {
        border: 2px solid var(--border-color) !important;
        border-radius: var(--radius) !important;
        overflow: hidden;
        transition: var(--transition);
        background: white;
        margin-bottom: 20px;
    }

    .topic-item:hover {
        border-color: var(--primary-color) !important;
        box-shadow: 0 8px 30px rgba(99, 102, 241, 0.12);
        transform: translateY(-2px);
    }

    .topic-item .topic-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 16px 24px;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .topic-item .topic-header h6 {
        margin: 0;
        color: var(--primary-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
    }

    .topic-item .topic-header h6 i {
        font-size: 20px;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .topic-item .topic-body {
        padding: 24px;
    }

    .topic-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-gradient);
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
        -webkit-text-fill-color: white;
    }

    .file-upload-wrapper {
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-sm);
        padding: 30px;
        text-align: center;
        background: linear-gradient(135deg, #fafbff, #f1f4f9);
        transition: var(--transition);
        cursor: pointer;
        position: relative;
    }

    .file-upload-wrapper:hover {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f0f2ff, #e8ecff);
        transform: scale(1.01);
    }

    .file-upload-wrapper .upload-icon {
        font-size: 48px;
        color: var(--primary-color);
        margin-bottom: 12px;
        display: block;
    }

    .file-upload-wrapper .upload-text {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 16px;
    }

    .file-upload-wrapper .upload-subtext {
        color: var(--text-secondary);
        font-size: 13px;
        margin-top: 5px;
    }

    .file-upload-wrapper input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-upload-wrapper .file-types {
        display: inline-flex;
        gap: 8px;
        margin-top: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .file-upload-wrapper .file-types span {
        background: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
    }

    .btn {
        border-radius: var(--radius-sm);
        font-weight: 600;
        padding: 10px 22px;
        transition: var(--transition);
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        position: relative;
        overflow: hidden;
    }

    .btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:active::after {
        width: 300px;
        height: 300px;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-primary:hover {
        box-shadow: 0 8px 30px rgba(99, 102, 241, 0.4);
        transform: translateY(-3px);
    }

    .btn-outline-primary {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
    }

    .btn-outline-danger {
        background: transparent;
        border: 2px solid #ef4444;
        color: #ef4444;
    }

    .btn-outline-danger:hover {
        background: var(--danger-gradient);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
    }

    .btn-sm {
        padding: 6px 14px;
        font-size: 12px;
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-success:hover {
        box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
        transform: translateY(-3px);
    }

    .btn-submit {
        padding: 14px 50px;
        font-size: 17px;
        font-weight: 700;
        background: var(--primary-gradient);
        color: white;
        border-radius: var(--radius-sm);
        box-shadow: 0 8px 30px rgba(99, 102, 241, 0.35);
        transition: var(--transition);
        letter-spacing: 0.5px;
    }

    .btn-submit:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 45px rgba(99, 102, 241, 0.45);
        color: white;
    }

    .btn-submit i {
        font-size: 20px;
    }

    .info-card {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border-radius: var(--radius-sm);
        padding: 12px 16px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: var(--text-primary);
        border-left: 4px solid var(--primary-color);
    }

    .info-card i {
        color: var(--primary-color);
        font-size: 18px;
    }

    .info-card-success {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        border-left-color: var(--success-color);
    }

    .info-card-success i {
        color: var(--success-color);
    }

    .info-card-warning {
        background: linear-gradient(135deg, #fffbeb, #fde68a);
        border-left-color: #f59e0b;
    }

    .info-card-warning i {
        color: #f59e0b;
    }

    /* Mode Selection Cards */
    .mode-selection {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .mode-card {
        flex: 1;
        min-width: 200px;
        padding: 20px 25px;
        border: 3px solid var(--border-color);
        border-radius: var(--radius);
        cursor: pointer;
        transition: var(--transition);
        background: white;
        text-align: center;
        position: relative;
    }

    .mode-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .mode-card.active {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f8faff, #f0f2ff);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15), var(--shadow-sm);
    }

    .mode-card .mode-icon {
        font-size: 36px;
        color: var(--primary-color);
        display: block;
        margin-bottom: 10px;
    }

    .mode-card .mode-title {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 16px;
        margin-bottom: 5px;
    }

    .mode-card .mode-desc {
        font-size: 13px;
        color: var(--text-secondary);
    }

    .mode-card .mode-check {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: var(--transition);
        font-size: 14px;
    }

    .mode-card.active .mode-check {
        background: var(--primary-gradient);
    }

    .month-selector-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        padding: 12px;
        background: var(--bg-light);
        border-radius: var(--radius-sm);
        border: 1px solid var(--border-color);
    }

    .month-selector-grid .form-check {
        padding-left: 28px;
        margin-bottom: 0;
    }

    .month-selector-grid .form-check-input {
        width: 18px;
        height: 18px;
        margin-top: 2px;
        cursor: pointer;
        border: 2px solid var(--border-color);
        transition: var(--transition);
    }

    .month-selector-grid .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .month-selector-grid .form-check-label {
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        cursor: pointer;
    }

    .month-topic-badge {
        display: inline-block;
        background: var(--primary-gradient);
        color: white;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .semester-badge {
        display: inline-block;
        background: var(--success-gradient);
        color: white;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* PDF Preview Section */
    .pdf-preview-section {
        border: 2px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 20px;
        background: linear-gradient(135deg, #fafbff, #f1f4f9);
        margin-top: 15px;
        display: none;
    }

    .pdf-preview-section .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border-color);
    }

    .pdf-preview-section .preview-header h6 {
        margin: 0;
        color: var(--primary-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pdf-preview-section .preview-content {
        max-height: 200px;
        overflow-y: auto;
        padding: 12px;
        background: white;
        border-radius: var(--radius-sm);
        font-size: 13px;
        color: var(--text-primary);
        white-space: pre-wrap;
        line-height: 1.6;
    }

    .pdf-preview-section .preview-content .no-text {
        color: var(--text-secondary);
        font-style: italic;
    }

    .pdf-preview-section .preview-stats {
        display: flex;
        gap: 20px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .pdf-preview-section .preview-stats .stat-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--text-secondary);
    }

    .pdf-preview-section .preview-stats .stat-item i {
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding: 20px 24px;
        }

        .page-title {
            font-size: 24px;
            flex-wrap: wrap;
        }

        .page-title small {
            font-size: 13px;
            padding: 2px 12px;
        }

        .btn-view-all {
            width: 100%;
            justify-content: center;
        }

        .card-body {
            padding: 20px;
        }

        .row > [class*="col-"] {
            margin-bottom: 15px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .btn-submit {
            width: 100%;
        }

        .topic-item .topic-body {
            padding: 16px;
        }

        .topic-item .topic-header {
            padding: 12px 16px;
        }

        .month-selector-grid {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }

        .file-upload-wrapper {
            padding: 20px;
        }

        .file-upload-wrapper .upload-icon {
            font-size: 36px;
        }

        .mode-selection {
            flex-direction: column;
        }

        .mode-card {
            min-width: auto;
        }

        .pdf-preview-section .preview-stats {
            flex-direction: column;
            gap: 5px;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 20px;
        }

        .page-title i {
            font-size: 26px;
        }

        .card-body {
            padding: 16px;
        }

        .section-header h5 {
            font-size: 16px;
        }
    }

    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: var(--bg-light);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--primary-dark);
    }
</style>

@php
    $user = Auth::user();
    $isEmployee = $user->hasRole('employee') || $user->hasRole('Teacher');
    $instituteType = $instituteType ?? '';
    $courseLabel = ($instituteType === 'School') ? 'Class' : 'Course';
    $employee = $employee ?? null;
@endphp

<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i> 
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i> 
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-cloud-arrow-up-fill"></i>
            Upload Syllabus
            @if($isEmployee && $employee)
                <small><i class="bi bi-person-circle me-1"></i> {{ $employee->name }}</small>
            @endif
        </h1>
        <a href="{{ route('instituteAdmin.syllabus.viewAll') }}" class="btn-view-all">
            <i class="bi bi-list-ul"></i> View All Syllabuses
        </a>
    </div>

    <div class="main-card">
        <div class="card-body bg-light">
            <form method="POST" action="{{ route('instituteAdmin.syllabus.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-building"></i> Category <span class="required">*</span>
                        </label>
                        <select id="category" name="category_id" class="form-control shadow-sm" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->department_category_id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-building-fill"></i> Department <span class="required">*</span>
                        </label>
                        <select id="department" name="department_id" class="form-control shadow-sm" required>
                            @if($isEmployee && $employeeDepartments->count() > 0)
                                @foreach($employeeDepartments as $dept)
                                    <option value="{{ $dept->department_id }}" selected>{{ $dept->department }}</option>
                                @endforeach
                            @else
                                <option value="">Select Department</option>
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-book"></i> {{ $courseLabel }} Type <span class="required">*</span>
                        </label>
                        <select id="courseType" name="course_type" class="form-control shadow-sm" required>
                            <option value="">Select {{ $courseLabel }} Type</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-bookmark"></i> {{ $courseLabel }} Sub Type <span class="required">*</span>
                        </label>
                        <select id="subType" name="course_detail_id" class="form-control shadow-sm" required>
                            <option value="">Select {{ $courseLabel }} Sub Type</option>
                        </select>
                        <div id="singleSubtypeMessage" class="info-card info-card-success mt-2" style="display: none;">
                            <i class="bi bi-check-circle-fill"></i> 
                            <span>Only one sub-type available, auto-selected</span>
                        </div>
                    </div>

                    <div class="col-md-6" id="academic_year_container" style="display: block !important;">
                        <label class="form-label">
                            <i class="bi bi-calendar-check"></i> Academic Year <span class="required">*</span>
                        </label>
                        <select name="academic_year" id="academic_year" class="form-control shadow-sm" disabled>
                            <option value="">Select Course Sub Type first</option>
                        </select>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-info-circle"></i> Academic Year will appear after selecting a Course Sub Type
                        </div>
                        <div id="academic_year_info" class="info-card info-card-success mt-2" style="display: none;">
                            <i class="bi bi-check-circle-fill"></i> 
                            <span id="academic_year_info_text"></span>
                        </div>
                    </div>

                    <div class="col-md-6" id="semester_container" style="display: none;">
                        <label class="form-label">
                            <i class="bi bi-calendar-range"></i> Semester/Term <span class="required">*</span>
                        </label>
                        <select name="semester_id" id="semester_id" class="form-control shadow-sm">
                            <option value="">-- Select Semester --</option>
                        </select>
                        <input type="hidden" name="term_type" value="semester">
                        <input type="hidden" name="term_value" id="term_value_hidden" value="">
                        <div id="semester_info" class="info-card mt-2" style="display: none;">
                            <i class="bi bi-info-circle-fill"></i> 
                            <span id="semester_info_text"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-journal-bookmark"></i> Subject <span class="required">*</span>
                        </label>
                        <select name="subject_id" id="subject" class="form-control shadow-sm" required>
                            <option value="">Select Subject</option>
                        </select>
                        <div id="subject_info" class="info-card mt-2" style="display: none;">
                            <i class="bi bi-info-circle-fill"></i>
                            <div>
                                <div class="fw-semibold">Subject Details</div>
                                <div class="small mt-1">
                                    <strong>Semester:</strong> <span id="subject_semester_value">-</span> &bull;
                                    <strong>Start:</strong> <span id="subject_start_date_value">-</span> &bull;
                                    <strong>End:</strong> <span id="subject_end_date_value">-</span>
                                </div>
                            </div>
                        </div>
                        <div id="subject_syllabus_status" class="info-card mt-2" style="display: none;"></div>
                        <div id="subject_locked_notice" class="alert alert-warning mt-2" style="display: none;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                <div class="fw-semibold">This syllabus is already configured for this subject.</div>
                                <a href="#" id="subject_edit_link" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="bi bi-pencil-square"></i> Add/Edit Syllabus
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="bi bi-pencil-square"></i> Syllabus Title <span class="required">*</span>
                        </label>
                        <input type="text" name="title" id="syllabus_title" class="form-control shadow-sm" 
                               placeholder="e.g., Mathematics Syllabus 2024" required>
                    </div>

                    <!-- Mode Selection -->
                    <div class="col-12">
                        <div class="section-header">
                            <i class="bi bi-layers"></i>
                            <h5>Syllabus Upload Mode</h5>
                            <span class="badge">Choose how to organize topics</span>
                        </div>

                        <div class="mode-selection">
                            <div class="mode-card active" data-mode="whole_semester" onclick="selectMode('whole_semester')">
                                <span class="mode-check"><i class="bi bi-check"></i></span>
                                <span class="mode-icon"><i class="bi bi-file-earmark-text"></i></span>
                                <div class="mode-title">Whole Semester</div>
                                <div class="mode-desc">Upload entire semester syllabus as one topic</div>
                            </div>
                            <div class="mode-card" data-mode="month_wise" onclick="selectMode('month_wise')">
                                <span class="mode-check"><i class="bi bi-check"></i></span>
                                <span class="mode-icon"><i class="bi bi-calendar-month"></i></span>
                                <div class="mode-title">Month Wise</div>
                                <div class="mode-desc">Split syllabus into monthly topics</div>
                            </div>
                        </div>
                    </div>

                    <!-- Month Selection (visible only in month-wise mode) -->
                    <div class="col-12" id="month_section" style="display: none;">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <label class="form-label">
                                    <i class="bi bi-calendar3"></i> Select Months for Topics <span class="required">*</span>
                                </label>
                                <p class="text-muted small">Select months and topics will be auto-created for each month</p>
                                <input type="hidden" name="selected_months" id="selected_months_hidden" value="">
                                <div id="month_selector_container" class="month-selector-grid"></div>
                                <button type="button" class="btn btn-success mt-3" onclick="generateMonthTopics()">
                                    <i class="bi bi-magic"></i> Generate Topics from Selected Months
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Topics Container -->
                    <div class="col-12">
                        <div class="section-header">
                            <i class="bi bi-list-ul"></i>
                            <h5>Topics</h5>
                            <span class="badge" id="topicCountBadge">1 Topic</span>
                        </div>

                        <div id="topicsContainer">
                            <!-- Topic 1 - Default for Whole Semester mode -->
                            <div class="topic-item" data-topic-index="0" id="topic_0">
                                <div class="topic-header">
                                    <h6>
                                        <i class="bi bi-journal-text"></i>
                                        <span class="topic-number">1</span> 
                                        <span id="defaultTopicLabel">Full Semester Syllabus</span>
                                        <span class="semester-badge">Whole Semester</span>
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTopic(this)" style="display: none;">
                                        <i class="bi bi-trash-fill"></i> Remove
                                    </button>
                                </div>
                                <div class="topic-body">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label"><i class="bi bi-pencil"></i> Topic Name <span class="required">*</span></label>
                                            <input type="text" name="topics[0][topic_name]" class="form-control" placeholder="e.g., Full Semester Syllabus" value="Full Semester Syllabus" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label"><i class="bi bi-chat-text"></i> Topic Description</label>
                                            <input type="text" name="topics[0][description]" class="form-control" placeholder="Short description (optional)">
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label class="form-label">
                                                <i class="bi bi-file-earmark-arrow-up"></i> Upload Syllabus File <span class="required">*</span>
                                            </label>
                                            <div class="file-upload-wrapper">
                                                <i class="bi bi-cloud-upload upload-icon"></i>
                                                <div class="upload-text">Drop your syllabus file here</div>
                                                <div class="upload-subtext">or click to browse</div>
                                                <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.png" required>
                                                <div class="file-types">
                                                    <span>PDF</span>
                                                    <span>DOC</span>
                                                    <span>DOCX</span>
                                                    <span>JPG</span>
                                                    <span>PNG</span>
                                                </div>
                                            </div>
                                            <div class="text-muted small mt-2">
                                                <i class="bi bi-info-circle"></i> Max file size: 5MB
                                            </div>
                                            
                                            <!-- PDF Preview Section -->
                                            <div class="pdf-preview-section" id="pdfPreviewSection_0">
                                                <div class="preview-header">
                                                    <h6><i class="bi bi-file-text"></i> Extracted Text Preview</h6>
                                                    <span class="badge bg-info word-count" id="wordCount_0">0 words</span>
                                                </div>
                                                <div class="preview-content pdf-extracted-text" id="pdfExtractedText_0">
                                                    <span class="no-text">No text extracted yet</span>
                                                </div>
                                                <div class="preview-stats">
                                                    <span class="stat-item"><i class="bi bi-file-earmark-pdf"></i> PDF detected</span>
                                                    <span class="stat-item"><i class="bi bi-book"></i> <span class="page-count" id="pageCount_0">0</span> pages</span>
                                                    <span class="stat-item"><i class="bi bi-arrow-left-right"></i> <span class="char-count" id="charCount_0">0</span> characters</span>
                                                </div>
                                            </div>
                                            <input type="hidden" class="parsed-preview-text" id="parsed_preview_text_0" name="topics[0][parsed_text]" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary" id="addTopicBtn" onclick="addTopic()">
                            <i class="bi bi-plus-circle-fill"></i> Add Another Topic
                        </button>
                    </div>

                    @if(!$isEmployee)
                        <input type="hidden" name="uploaded_by_admin" value="1">
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>{{ session('warning') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <input type="hidden" name="syllabus_for" id="syllabus_for_hidden" value="subject_level">
                    <input type="hidden" name="upload_mode" id="upload_mode_hidden" value="whole_semester">

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-cloud-arrow-up-fill"></i> Upload Syllabus
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PDF.js for client-side PDF text extraction -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
const instituteType = "{{ $instituteType }}";
const isEmployee = {{ $isEmployee ? 'true' : 'false' }};
const courseLabel = instituteType === 'School' ? 'Class' : 'Course';

let availableSemesters = [];
let topicIndex = 1;
let courseDateRange = null;
let currentMode = 'whole_semester';
let availableMonths = [];
let semesterStartDate = null;
let semesterEndDate = null;

const syllabusForm = document.querySelector('form');
let subjectLocked = false;
let lockedSubjectEditUrl = '';

function resetSubjectLockState() {
    const subjectStatus = document.getElementById('subject_syllabus_status');
    const lockNotice = document.getElementById('subject_locked_notice');
    const subjectEditLink = document.getElementById('subject_edit_link');
    const submitButton = document.querySelector('.btn-submit');
    const formControls = document.querySelectorAll('form input, form select, form textarea, form button');

    subjectLocked = false;
    lockedSubjectEditUrl = '';

    formControls.forEach(control => {
        if (control.type === 'hidden' || control.id === 'subject' || control.id === 'subject_syllabus_status' || control.id === 'subject_locked_notice' || control.id === 'subject_edit_link') {
            return;
        }

        if (control.type === 'submit') {
            control.disabled = false;
            return;
        }

        if (control.tagName === 'BUTTON' && control.classList.contains('btn-submit')) {
            control.disabled = false;
            return;
        }

        if (control.tagName === 'BUTTON') {
            control.disabled = false;
        } else {
            control.disabled = false;
        }
    });

    if (subjectStatus) {
        subjectStatus.style.display = 'none';
        subjectStatus.innerHTML = '';
    }

    if (lockNotice) {
        lockNotice.style.display = 'none';
    }

    if (subjectEditLink) {
        subjectEditLink.href = '#';
    }

    if (submitButton) {
        submitButton.disabled = false;
    }
}

function setSubjectLockState(isLocked, subjectId, message) {
    const subjectStatus = document.getElementById('subject_syllabus_status');
    const lockNotice = document.getElementById('subject_locked_notice');
    const subjectEditLink = document.getElementById('subject_edit_link');
    const submitButton = document.querySelector('.btn-submit');
    const formControls = document.querySelectorAll('form input, form select, form textarea, form button');
    const modeSelectionBlock = document.querySelector('.mode-selection')?.closest('.col-12');
    const monthSection = document.getElementById('month_section');
    const topicsSection = document.getElementById('topicsContainer')?.closest('.col-12');

    subjectLocked = isLocked;
    lockedSubjectEditUrl = isLocked && subjectId ? `{{ url('/syllabus/edit') }}/${subjectId}` : '';

    formControls.forEach(control => {
        if (control.type === 'hidden' || control.id === 'subject' || control.id === 'subject_syllabus_status' || control.id === 'subject_locked_notice' || control.id === 'subject_edit_link') {
            return;
        }

        if (control.type === 'submit' || (control.tagName === 'BUTTON' && control.classList.contains('btn-submit'))) {
            control.disabled = isLocked;
            return;
        }

        if (control.tagName === 'BUTTON') {
            control.disabled = isLocked;
        } else {
            control.disabled = isLocked;
        }
    });

    if (modeSelectionBlock) {
        modeSelectionBlock.style.display = isLocked ? 'none' : 'block';
    }
    if (monthSection) {
        monthSection.style.display = 'none';
    }
    if (topicsSection) {
        topicsSection.style.display = isLocked ? 'none' : 'block';
    }

    if (subjectStatus) {
        subjectStatus.style.display = isLocked ? 'none' : 'block';
        subjectStatus.className = `info-card mt-2 ${isLocked ? 'info-card-warning' : ''}`;
        if (!isLocked) {
            subjectStatus.innerHTML = `<i class="bi ${message ? 'bi-info-circle-fill' : 'bi-info-circle-fill'}"></i><div>${message || ''}</div>`;
        }
    }

    if (lockNotice) {
        lockNotice.style.display = isLocked ? 'block' : 'none';
        if (isLocked) {
            lockNotice.innerHTML = `
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    <div class="fw-semibold">This syllabus is already configured for this subject.</div>
                    <a href="${lockedSubjectEditUrl}" id="subject_edit_link" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-pencil-square"></i> Add/Edit Syllabus
                    </a>
                </div>
            `;
        }
    }

    if (subjectEditLink && isLocked && subjectId) {
        subjectEditLink.href = `{{ url('/syllabus/edit') }}/${subjectId}`;
    }

    if (submitButton) {
        submitButton.disabled = isLocked;
    }
}

if (syllabusForm) {
    syllabusForm.addEventListener('submit', function(event) {
        if (subjectLocked && lockedSubjectEditUrl) {
            event.preventDefault();
            window.location.href = lockedSubjectEditUrl;
            return;
        }

        syncSelectedMonthsHiddenField();
    });
}

// ============================================
// PDF TEXT EXTRACTION - CLIENT SIDE (ENHANCED)
// ============================================
function setupPdfPreview(fileInput) {
    if (!fileInput) return;
    
    // Remove any existing listener to avoid duplicates
    fileInput.removeEventListener('change', handlePdfChange);
    fileInput.addEventListener('change', handlePdfChange);
}

function handlePdfChange(e) {
    const file = e.target.files[0];
    const parentTopic = e.target.closest('.topic-item');
    if (!parentTopic) return;
    
    // Find the preview section within this topic
    const previewSection = parentTopic.querySelector('.pdf-preview-section');
    if (!previewSection) return;
    
    const extractedTextDiv = previewSection.querySelector('.pdf-extracted-text');
    const wordCountSpan = previewSection.querySelector('.word-count');
    const pageCountSpan = previewSection.querySelector('.page-count');
    const charCountSpan = previewSection.querySelector('.char-count');
    const parsedPreviewField = parentTopic.querySelector('.parsed-preview-text');
    
    if (file && file.type === 'application/pdf') {
        previewSection.style.display = 'block';
        if (extractedTextDiv) extractedTextDiv.innerHTML = '<span class="text-muted">Extracting text from PDF...</span>';
        if (wordCountSpan) wordCountSpan.textContent = 'Loading...';
        if (parsedPreviewField) parsedPreviewField.value = '';
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const typedarray = new Uint8Array(e.target.result);
            
            pdfjsLib.getDocument(typedarray).promise
                .then(function(pdf) {
                    let fullText = '';
                    const numPages = pdf.numPages;
                    if (pageCountSpan) pageCountSpan.textContent = numPages;
                    
                    const promises = [];
                    for (let i = 1; i <= numPages; i++) {
                        promises.push(pdf.getPage(i).then(function(page) {
                            return page.getTextContent().then(function(textContent) {
                                const pageText = textContent.items
                                    .map(item => item.str)
                                    .join(' ');
                                fullText += pageText + '\n';
                            });
                        }));
                    }
                    
                    Promise.all(promises).then(function() {
                        const trimmedText = fullText.trim();
                        const wordCount = trimmedText.length > 0 ? trimmedText.split(/\s+/).length : 0;
                        const charCount = trimmedText.length;
                        
                        if (wordCountSpan) wordCountSpan.textContent = wordCount + ' words';
                        if (charCountSpan) charCountSpan.textContent = charCount;
                        
                        if (trimmedText.length > 0) {
                            let displayText = trimmedText.substring(0, 1000);
                            if (trimmedText.length > 1000) {
                                displayText += '...';
                            }
                            if (extractedTextDiv) extractedTextDiv.innerHTML = displayText;
                            if (parsedPreviewField) parsedPreviewField.value = trimmedText;
                        } else {
                            if (extractedTextDiv) {
                                extractedTextDiv.innerHTML = '<span class="no-text">No text could be extracted from this PDF. It may be a scanned image or have no selectable text.</span>';
                            }
                            if (parsedPreviewField) parsedPreviewField.value = '';
                        }
                    });
                })
                .catch(function(error) {
                    if (extractedTextDiv) {
                        extractedTextDiv.innerHTML = '<span class="text-danger">Error reading PDF: ' + error.message + '</span>';
                    }
                    if (wordCountSpan) wordCountSpan.textContent = 'Error';
                    if (parsedPreviewField) parsedPreviewField.value = '';
                });
        };
        reader.readAsArrayBuffer(file);
    } else if (file) {
        previewSection.style.display = 'block';
        if (extractedTextDiv) {
            extractedTextDiv.innerHTML = '<span class="text-warning">Text extraction only available for PDF files.</span>';
        }
        if (wordCountSpan) wordCountSpan.textContent = 'N/A';
        if (pageCountSpan) pageCountSpan.textContent = 'N/A';
        if (charCountSpan) charCountSpan.textContent = 'N/A';
        if (parsedPreviewField) parsedPreviewField.value = '';
    } else {
        previewSection.style.display = 'none';
        if (parsedPreviewField) parsedPreviewField.value = '';
    }
}

// Setup PDF preview for all file inputs in topics
function setupAllPdfPreviews() {
    document.querySelectorAll('.topic-item input[type="file"]').forEach(function(input) {
        setupPdfPreview(input);
    });
}

// ============================================
// MODE SELECTION
// ============================================
function selectMode(mode) {
    currentMode = mode;
    document.getElementById('upload_mode_hidden').value = mode;
    
    // Update active state on cards
    document.querySelectorAll('.mode-card').forEach(card => {
        card.classList.toggle('active', card.dataset.mode === mode);
    });

    // Show/hide month section
    const monthSection = document.getElementById('month_section');
    const addTopicBtn = document.getElementById('addTopicBtn');
    const defaultTopicLabel = document.getElementById('defaultTopicLabel');
    const semesterBadge = document.querySelector('.semester-badge');
    
    if (mode === 'month_wise') {
        monthSection.style.display = 'block';
        document.querySelectorAll('.topic-item .topic-header .btn-outline-danger').forEach(btn => {
            btn.style.display = 'inline-flex';
        });
        addTopicBtn.style.display = 'none';
        if (defaultTopicLabel) defaultTopicLabel.textContent = 'Topic 1';
        if (semesterBadge) semesterBadge.style.display = 'none';
        resetToSingleTopicForMonthWise();
    } else {
        monthSection.style.display = 'none';
        document.querySelectorAll('.topic-item .topic-header .btn-outline-danger').forEach(btn => {
            btn.style.display = 'none';
        });
        addTopicBtn.style.display = 'inline-flex';
        resetToSingleTopic();
        if (defaultTopicLabel) defaultTopicLabel.textContent = 'Full Semester Syllabus';
        if (semesterBadge) semesterBadge.style.display = 'inline-block';
    }
    
    updateTopicCount();
    updateSyllabusTitle();
}

function resetToSingleTopic() {
    const container = document.getElementById('topicsContainer');
    const topics = container.querySelectorAll('.topic-item');
    
    topics.forEach((topic, index) => {
        if (index > 0) {
            topic.remove();
        }
    });
    
    const firstTopic = container.querySelector('.topic-item');
    if (firstTopic) {
        const nameInput = firstTopic.querySelector('input[name="topics[0][topic_name]"]');
        if (nameInput) nameInput.value = 'Full Semester Syllabus';
    }
    
    const firstTopicRemoveBtn = container.querySelector('.topic-item .topic-header .btn-outline-danger');
    if (firstTopicRemoveBtn) firstTopicRemoveBtn.style.display = 'none';
    
    const defaultLabel = document.getElementById('defaultTopicLabel');
    if (defaultLabel) defaultLabel.textContent = 'Full Semester Syllabus';
    const semesterBadge = document.querySelector('.semester-badge');
    if (semesterBadge) semesterBadge.style.display = 'inline-block';
    
    topicIndex = 1;
    updateTopicCount();
    setupAllPdfPreviews();
}

function resetToSingleTopicForMonthWise() {
    const container = document.getElementById('topicsContainer');
    const topics = container.querySelectorAll('.topic-item');
    
    topics.forEach((topic, index) => {
        if (index > 0) {
            topic.remove();
        }
    });
    
    const firstTopic = container.querySelector('.topic-item');
    if (firstTopic) {
        const nameInput = firstTopic.querySelector('input[name="topics[0][topic_name]"]');
        if (nameInput) nameInput.value = 'Select months and generate topics';
    }
    
    const firstTopicRemoveBtn = container.querySelector('.topic-item .topic-header .btn-outline-danger');
    if (firstTopicRemoveBtn) firstTopicRemoveBtn.style.display = 'inline-flex'; 
    
    const defaultLabel = document.getElementById('defaultTopicLabel');
    if (defaultLabel) defaultLabel.textContent = 'Topic 1';
    const semesterBadge = document.querySelector('.semester-badge');
    if (semesterBadge) semesterBadge.style.display = 'none';
    
    topicIndex = 1;
    updateTopicCount();
    setupAllPdfPreviews();
}

// ============================================
// TOPIC MANAGEMENT
// ============================================
function addTopic() {
    if (currentMode === 'month_wise') {
        alert('In Month Wise mode, topics are generated from selected months. Use the "Generate Topics" button.');
        return;
    }
    
    const container = document.getElementById('topicsContainer');   
    const div = document.createElement('div'); 
    div.className = 'topic-item';
    div.dataset.topicIndex = topicIndex;
    div.id = 'topic_' + topicIndex;
    div.innerHTML = `
        <div class="topic-header">
            <h6>
                <i class="bi bi-journal-text"></i>
                <span class="topic-number">${topicIndex + 1}</span>
                Topic ${topicIndex + 1}
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTopic(this)">
                <i class="bi bi-trash-fill"></i> Remove
            </button>
        </div>
        <div class="topic-body">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label"><i class="bi bi-pencil"></i> Topic Name <span class="required">*</span></label>
                    <input type="text" name="topics[${topicIndex}][topic_name]" class="form-control" placeholder="Enter topic name" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label"><i class="bi bi-chat-text"></i> Topic Description</label>
                    <input type="text" name="topics[${topicIndex}][description]" class="form-control" placeholder="Short description">
                </div>
                <div class="col-12 mt-3">
                    <label class="form-label">
                        <i class="bi bi-file-earmark-arrow-up"></i> Upload Syllabus File <span class="required">*</span>
                    </label>
                    <div class="file-upload-wrapper">
                        <i class="bi bi-cloud-upload upload-icon"></i>
                        <div class="upload-text">Drop your syllabus file here</div>
                        <div class="upload-subtext">or click to browse</div>
                        <input type="file" name="files[${topicIndex}]" accept=".pdf,.doc,.docx,.jpg,.png" required>
                        <div class="file-types">
                            <span>PDF</span>
                            <span>DOC</span>
                            <span>DOCX</span>
                            <span>JPG</span>
                            <span>PNG</span>
                        </div>
                    </div>
                    <div class="text-muted small mt-2">
                        <i class="bi bi-info-circle"></i> Max file size: 5MB
                    </div>
                    
                    <!-- PDF Preview Section for each topic -->
                    <div class="pdf-preview-section" id="pdfPreviewSection_${topicIndex}" style="display: none;">
                        <div class="preview-header">
                            <h6><i class="bi bi-file-text"></i> Extracted Text Preview</h6>
                            <span class="badge bg-info word-count" id="wordCount_${topicIndex}">0 words</span>
                        </div>
                        <div class="preview-content pdf-extracted-text" id="pdfExtractedText_${topicIndex}">
                            <span class="no-text">No text extracted yet</span>
                        </div>
                        <div class="preview-stats">
                            <span class="stat-item"><i class="bi bi-file-earmark-pdf"></i> PDF detected</span>
                            <span class="stat-item"><i class="bi bi-book"></i> <span class="page-count" id="pageCount_${topicIndex}">0</span> pages</span>
                            <span class="stat-item"><i class="bi bi-arrow-left-right"></i> <span class="char-count" id="charCount_${topicIndex}">0</span> characters</span>
                        </div>
                    </div>
                    <input type="hidden" class="parsed-preview-text" id="parsed_preview_text_${topicIndex}" name="topics[${topicIndex}][parsed_text]" value="">
                </div>
            </div>
        </div>
    `;
    container.appendChild(div);
    topicIndex++;
    updateTopicCount();
    
    // Setup PDF preview for the new file input
    const fileInput = div.querySelector('input[type="file"]');
    if (fileInput) {
        setupPdfPreview(fileInput);
    }
}

function removeTopic(button) {
    const topics = document.querySelectorAll('.topic-item');
    if (topics.length <= 1) {
        alert('You must have at least one topic.');
        return;
    }
    button.closest('.topic-item').remove();
    updateTopicCount();
    renumberTopics();
}

function renumberTopics() {
    const topics = document.querySelectorAll('.topic-item');
    topics.forEach((topic, index) => {
        const numberSpan = topic.querySelector('.topic-number');
        const headerText = topic.querySelector('.topic-header h6');
        if (numberSpan) numberSpan.textContent = index + 1;
        if (headerText) {
            const textNode = headerText.childNodes[2];
            if (textNode && !textNode.textContent.includes('Full Semester')) {
                textNode.textContent = ` Topic ${index + 1}`;
            }
        }
        const inputs = topic.querySelectorAll('input[name^="topics["]');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/topics\[\d+\]/, `topics[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
    topicIndex = topics.length;
    updateTopicCount();
}

function updateTopicCount() {
    const count = document.querySelectorAll('.topic-item').length;
    document.getElementById('topicCountBadge').textContent = `${count} Topic${count > 1 ? 's' : ''}`;
}

// ============================================
// MONTH SELECTOR
// ============================================
function populateMonthSelector(startDate, endDate) {
    const monthContainer = document.getElementById('month_selector_container');
    if (!monthContainer) return;

    semesterStartDate = startDate;
    semesterEndDate = endDate;

    const start = new Date(startDate);
    const end = new Date(endDate);

    monthContainer.innerHTML = '';
    availableMonths = [];

    if (isNaN(start.getTime()) || isNaN(end.getTime()) || start > end) {
        monthContainer.innerHTML = '<div class="text-muted small">No month range available for this subject.</div>';
        return;
    }

    const cursor = new Date(start.getFullYear(), start.getMonth(), 1);
    const finish = new Date(end.getFullYear(), end.getMonth(), 1);

    while (cursor <= finish) {
        const value = `${cursor.getFullYear()}-${String(cursor.getMonth() + 1).padStart(2, '0')}`;
        const label = cursor.toLocaleString('default', { month: 'long', year: 'numeric' });
        availableMonths.push({ value, label, month: cursor.getMonth(), year: cursor.getFullYear() });
        monthContainer.innerHTML += `
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="month_selector[]" value="${value}" id="month_${value}">
                <label class="form-check-label" for="month_${value}">${label}</label>
            </div>
        `;
        cursor.setMonth(cursor.getMonth() + 1);
    }

    monthContainer.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', syncSelectedMonthsHiddenField);
    });
    syncSelectedMonthsHiddenField();
}

function syncSelectedMonthsHiddenField() {
    const selectedMonths = Array.from(document.querySelectorAll('#month_selector_container input[type="checkbox"]:checked'))
        .map(input => input.value)
        .filter(Boolean);
    const hiddenField = document.getElementById('selected_months_hidden');
    if (hiddenField) {
        hiddenField.value = selectedMonths.join(',');
    }
}

// ============================================
// GENERATE MONTH TOPICS (UPDATED WITH PDF PREVIEW)
// ============================================
function generateMonthTopics() {
    const selectedCheckboxes = document.querySelectorAll('#month_selector_container input[type="checkbox"]:checked');
    const selectedMonths = Array.from(selectedCheckboxes).map(cb => cb.value);

    if (!selectedMonths.length) {
        alert('Please select at least one month first.');
        return;
    }

    if (currentMode === 'whole_semester') {
        selectMode('month_wise');
    }

    const container = document.getElementById('topicsContainer');
    container.innerHTML = '';

    const subjectSelect = document.getElementById('subject');
    const subjectName = subjectSelect.options[subjectSelect.selectedIndex]?.dataset?.name || 'Syllabus';

    selectedMonths.forEach((monthValue, index) => {
        const [year, month] = monthValue.split('-').map(Number);
        const monthName = new Date(year, month - 1, 1).toLocaleString('default', { month: 'long' });

        const div = document.createElement('div');
        div.className = 'topic-item';
        div.dataset.topicIndex = index;
        div.id = 'topic_' + index;
        div.innerHTML = `
            <div class="topic-header">
                <h6>
                    <i class="bi bi-calendar-event"></i>
                    <span class="topic-number">${index + 1}</span>
                    ${monthName} ${year}
                    <span class="month-topic-badge">${monthValue}</span>
                </h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTopic(this)">
                    <i class="bi bi-trash-fill"></i> Remove
                </button>
            </div>
            <div class="topic-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label"><i class="bi bi-pencil"></i> Topic Name <span class="required">*</span></label>
                        <input type="text" name="topics[${index}][topic_name]" class="form-control" placeholder="e.g., ${monthName} Syllabus" value="${subjectName} - ${monthName} ${year}" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label"><i class="bi bi-chat-text"></i> Topic Description</label>
                        <input type="text" name="topics[${index}][description]" class="form-control" placeholder="Topics covered in ${monthName}">
                    </div>
                    <div class="col-12 mt-3">
                        <label class="form-label">
                            <i class="bi bi-file-earmark-arrow-up"></i> Upload Syllabus File <span class="required">*</span>
                        </label>
                        <div class="file-upload-wrapper">
                            <i class="bi bi-cloud-upload upload-icon"></i>
                            <div class="upload-text">Drop your syllabus file here</div>
                            <div class="upload-subtext">or click to browse</div>
                            <input type="file" name="files[${index}]" accept=".pdf,.doc,.docx,.jpg,.png" required>
                            <div class="file-types">
                                <span>PDF</span>
                                <span>DOC</span>
                                <span>DOCX</span>
                                <span>JPG</span>
                                <span>PNG</span>
                            </div>
                        </div>
                        <div class="text-muted small mt-2">
                            <i class="bi bi-info-circle"></i> Max file size: 5MB
                        </div>
                        
                        <!-- PDF Preview Section for each month topic -->
                        <div class="pdf-preview-section" id="pdfPreviewSection_${index}" style="display: none;">
                            <div class="preview-header">
                                <h6><i class="bi bi-file-text"></i> Extracted Text Preview</h6>
                                <span class="badge bg-info word-count" id="wordCount_${index}">0 words</span>
                            </div>
                            <div class="preview-content pdf-extracted-text" id="pdfExtractedText_${index}">
                                <span class="no-text">No text extracted yet</span>
                            </div>
                            <div class="preview-stats">
                                <span class="stat-item"><i class="bi bi-file-earmark-pdf"></i> PDF detected</span>
                                <span class="stat-item"><i class="bi bi-book"></i> <span class="page-count" id="pageCount_${index}">0</span> pages</span>
                                <span class="stat-item"><i class="bi bi-arrow-left-right"></i> <span class="char-count" id="charCount_${index}">0</span> characters</span>
                            </div>
                        </div>
                        <input type="hidden" class="parsed-preview-text" id="parsed_preview_text_${index}" name="topics[${index}][parsed_text]" value="">
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
    });

    topicIndex = selectedMonths.length;
    updateTopicCount();
    
    // Setup PDF preview for all file inputs in the newly created topics
    setTimeout(function() {
        setupAllPdfPreviews();
    }, 100);
}

// ============================================
// EXISTING FUNCTIONS
// ============================================
function loadSubjectCourseInfo() {
    const subjectId = document.getElementById('subject').value;
    const courseDetailId = document.getElementById('subType').value;
    const infoBox = document.getElementById('subject_info');
    const semesterValue = document.getElementById('subject_semester_value');
    const startDateValue = document.getElementById('subject_start_date_value');
    const endDateValue = document.getElementById('subject_end_date_value');

    if (!subjectId || !courseDetailId) {
        if (infoBox) {
            infoBox.style.display = 'none';
        }
        return;
    }

    fetch(`/ajax/get-subject-course-info?subject_id=${encodeURIComponent(subjectId)}&course_detail_id=${encodeURIComponent(courseDetailId)}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const semesterSelect = document.getElementById('semester_id');
                if (data.semester_id && semesterSelect.value !== data.semester_id) {
                    semesterSelect.value = data.semester_id;
                }

                if (infoBox) {
                    infoBox.style.display = 'flex';
                    semesterValue.textContent = data.semester_id || 'N/A';
                    startDateValue.textContent = data.course_start_date || 'N/A';
                    endDateValue.textContent = data.course_end_date || 'N/A';
                }

                if (data.course_start_date && data.course_end_date) {
                    semesterStartDate = data.course_start_date;
                    semesterEndDate = data.course_end_date;
                    courseDateRange = {
                        startDate: data.course_start_date,
                        endDate: data.course_end_date
                    };
                    populateMonthSelector(data.course_start_date, data.course_end_date);
                }
            }
        })
        .catch(error => {
            console.error('Error loading subject course info:', error);
        });
}

document.getElementById('department').addEventListener('change', function() {
    const deptId = this.value;
    resetFormAfterDepartment();
    loadCourseTypes(deptId);
});

document.getElementById('subject').addEventListener('change', function() {
    const subjectId = this.value;
    const statusBox = document.getElementById('subject_syllabus_status');

    if (!subjectId) {
        resetSubjectLockState();
        if (statusBox) {
            statusBox.style.display = 'none';
            statusBox.innerHTML = '';
        }
        return;
    }

    statusBox.style.display = 'block';
    statusBox.innerHTML = '<i class="bi bi-hourglass-split"></i> Checking existing syllabus for this subject...';

    fetch(`/instituteAdmin/syllabus/get-subject-status/${encodeURIComponent(subjectId)}`)
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                const isWarning = data.whole_semester_uploaded || data.monthly_count > 0;
                if (isWarning) {
                    setSubjectLockState(true, subjectId, data.message);
                    return;
                }

                statusBox.className = 'info-card mt-2 info-card-info';
                statusBox.innerHTML = `<i class="bi bi-info-circle-fill"></i><div>${data.message}</div>`;
                resetSubjectLockState();
                return;
            }

            resetSubjectLockState();
            if (statusBox) {
                statusBox.style.display = 'none';
            }
        })
        .catch(() => {
            resetSubjectLockState();
            if (statusBox) {
                statusBox.style.display = 'none';
            }
        });
});

function resetFormAfterDepartment() {
    const subjectSelect = document.getElementById('subject');
    const subjectInfo = document.getElementById('subject_info');
    document.getElementById('subType').innerHTML = '<option value="">Select ' + courseLabel + ' Sub Type</option>';
    if (subjectSelect) subjectSelect.innerHTML = '<option value="">Select Subject</option>';
    if (subjectInfo) subjectInfo.style.display = 'none';
    document.getElementById('semester_container').style.display = 'none';
    document.getElementById('semester_id').innerHTML = '<option value="">-- Select Semester --</option>';
    const academicYearContainer = document.getElementById('academic_year_container');
    const academicYearSelect = document.getElementById('academic_year');
    academicYearContainer.style.display = 'block';
    academicYearSelect.innerHTML = '<option value="">Select Course Sub Type first</option>';
    academicYearSelect.disabled = true;
    academicYearSelect.required = false;
    document.getElementById('academic_year_info').style.display = 'none';
    document.getElementById('subject_info').style.display = 'none';
    document.getElementById('syllabus_title').value = '';
    availableSemesters = [];
}

function loadCourseTypes(deptId) {
    const courseTypeSelect = document.getElementById('courseType');
    
    if (!deptId) return;
    
    fetch(`/ajax/course-types-by-department?department_id=${deptId}`)
        .then(res => res.json())
        .then(data => {
            courseTypeSelect.innerHTML = '<option value="">Select ' + courseLabel + ' Type</option>';
            if (data.status === 'success' && data.courses) {
                data.courses.forEach(course => {
                    courseTypeSelect.innerHTML += `<option value="${course.finacp_merchant_sub_category_type}">${course.finacp_merchant_sub_category_type}</option>`;
                });
            } else if (data.courseTypes) {
                data.courseTypes.forEach(course => {
                    courseTypeSelect.innerHTML += `<option value="${course.course_type}">${course.course_type}</option>`;
                });
            } else {
                courseTypeSelect.innerHTML = '<option value="">No course types found</option>';
            }
        })
        .catch(error => {
            console.error('Error loading course types:', error);
            courseTypeSelect.innerHTML = '<option value="">Error loading course types</option>';
        });
}

document.getElementById('courseType').addEventListener('change', function() {
    const courseType = this.value;
    const departmentId = document.getElementById('department').value;
    const subtypeSelect = document.getElementById('subType');
    const messageDiv = document.getElementById('singleSubtypeMessage');
    
    subtypeSelect.innerHTML = '<option value="">Select ' + courseLabel + ' Sub Type</option>';
    messageDiv.style.display = 'none';
    resetFormAfterCourseType();
    
    if (courseType && departmentId) {
        fetch(`/ajax/get-branches-by-course?department_id=${departmentId}&course_type=${encodeURIComponent(courseType)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.branches) {
                    subtypeSelect.innerHTML = '<option value="">Select ' + courseLabel + ' Sub Type</option>';
                    
                    if (data.branches.length > 0) {
                        data.branches.forEach(item => {
                            subtypeSelect.innerHTML += `<option value="${item.product_id}">${item.sub_type}</option>`;
                        });
                        
                        if (data.branches.length === 1) {
                            const singleItem = data.branches[0];
                            subtypeSelect.value = singleItem.product_id;
                            messageDiv.style.display = 'flex';
                            messageDiv.querySelector('span').textContent = `Only one sub-type available: ${singleItem.sub_type}`;
                            
                            loadAcademicYears(singleItem.product_id);
                            checkSubjectDistribution(singleItem.product_id);
                        }
                    } else {
                        subtypeSelect.innerHTML = '<option value="">No sub types found</option>';
                    }
                } else {
                    console.error('API Error:', data.message);
                    subtypeSelect.innerHTML = `<option value="">Error: ${data.message || 'Failed to load sub types'}</option>`;
                }
            })
            .catch(error => {
                console.error('Error loading sub types:', error);
                subtypeSelect.innerHTML = '<option value="">Error loading sub types</option>';
            });
    }
});

function resetFormAfterCourseType() {
    const subjectSelect = document.getElementById('subject');
    const subjectInfo = document.getElementById('subject_info');
    if (subjectSelect) subjectSelect.innerHTML = '<option value="">Select Subject</option>';
    if (subjectInfo) subjectInfo.style.display = 'none';
    document.getElementById('semester_container').style.display = 'none';
    document.getElementById('semester_id').innerHTML = '<option value="">-- Select Semester --</option>';
    const academicYearContainer = document.getElementById('academic_year_container');
    const academicYearSelect = document.getElementById('academic_year');
    academicYearContainer.style.display = 'block';
    academicYearSelect.innerHTML = '<option value="">Select Course Sub Type first</option>';
    academicYearSelect.disabled = true;
    academicYearSelect.required = false;
    document.getElementById('academic_year_info').style.display = 'none';
    document.getElementById('subject_info').style.display = 'none';
    document.getElementById('syllabus_title').value = '';
    availableSemesters = [];
}

document.getElementById('subType').addEventListener('change', function() {
    const productId = this.value;
    if (productId) {
        loadAcademicYears(productId);
        checkSubjectDistribution(productId);
        selectMode('whole_semester');
    } else {
        resetFormAfterCourseType();
    }
});

function checkSubjectDistribution(productId) {
    loadAvailableSemesters(productId)
        .then(() => {
            return fetch(`/instituteAdmin/syllabus/get-subjects/${productId}`)
                .then(res => res.json())
                .then(data => {
                    const subjects = Array.isArray(data) ? data : (data?.subjects || []);
                    if (Array.isArray(subjects) && subjects.length > 0) {
                        const transformedData = subjects.map(item => ({
                            subject_id: item.subject_id,
                            subject_name: item.subject_name,
                            semester_id: item.semester_id || 'all_semesters'
                        }));
                        analyzeSubjectDistribution(transformedData);
                    } else {
                        const subjectInfo = document.getElementById('subject_info');
                        const subjectSelect = document.getElementById('subject');
                        if (subjectInfo) subjectInfo.style.display = 'block';
                        if (subjectSelect) subjectSelect.innerHTML = '<option value="">No subjects available</option>';
                    }
                });
        })
        .catch(error => {
            console.error('Error loading semesters:', error);
            loadSubjectsDirectly(productId);
        });
}

function loadAvailableSemesters(productId) {
    return fetch(`/ajax/get-semesters/${productId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.semesters && data.semesters.length > 0) {
                availableSemesters = data.semesters;
            } else {
                availableSemesters = [];
            }
        })
        .catch(error => {
            console.error('Error loading semesters:', error);
            availableSemesters = [];
            throw error;
        });
}

function loadAcademicYears(productId) {
    const yearContainer = document.getElementById('academic_year_container');
    const yearSelect = document.getElementById('academic_year');
    const yearInfo = document.getElementById('academic_year_info');
    const yearInfoText = document.getElementById('academic_year_info_text');

    yearSelect.disabled = true;
    yearSelect.required = false;
    yearSelect.innerHTML = '<option value="">Loading academic years...</option>';
    yearInfo.style.display = 'none';

    return fetch(`/ajax/get-academic-years-by-product?product_id=${encodeURIComponent(productId)}`)
        .then(res => res.json())
        .then(data => {
            yearContainer.style.display = 'block';
            if (data.status === 'success' && Array.isArray(data.academic_years) && data.academic_years.length > 0) {
                yearSelect.innerHTML = '<option value="">Select Academic Year</option>';
                data.academic_years.forEach(year => {
                    yearSelect.innerHTML += `<option value="${year}">${year}</option>`;
                });

                yearSelect.disabled = false;
                yearSelect.required = true;
                yearInfo.style.display = 'none';

                if (data.academic_years.length === 1) {
                    yearSelect.value = data.academic_years[0];
                    yearInfo.style.display = 'flex';
                    yearInfoText.textContent = `Academic year automatically selected: ${data.academic_years[0]}`;
                }
            } else {
                yearSelect.innerHTML = '<option value="">No academic years found</option>';
                yearSelect.disabled = true;
                yearSelect.required = false;
                yearInfo.style.display = 'flex';
                yearInfoText.textContent = 'No academic years found for this course/sub-type.';
            }
        })
        .catch(error => {
            console.error('Error loading academic years:', error);
            yearContainer.style.display = 'block';
            yearSelect.innerHTML = '<option value="">Error loading academic years</option>';
            yearSelect.disabled = true;
            yearSelect.required = false;
            yearInfo.style.display = 'flex';
            yearInfoText.textContent = 'Error loading academic years.';
        });
}

function analyzeSubjectDistribution(subjects) {
    const semesterContainer = document.getElementById('semester_container');
    const semesterInfoText = document.getElementById('semester_info_text');

    const allAll = subjects.every(s => s.semester_id === 'all_semesters');
    const allSpecific = subjects.every(s => s.semester_id && s.semester_id !== 'all_semesters');
    const mixed = !allAll && !allSpecific;

    if (allAll) {
        semesterContainer.style.display = 'none';
        semesterInfoText.textContent = 'Subjects are available for all semesters';
        document.getElementById('syllabus_for_hidden').value = 'class_level';
        populateSubjects(subjects);
    } else if (allSpecific || mixed) {
        semesterContainer.style.display = 'block';
        semesterInfoText.textContent = 'Please select a semester';
        document.getElementById('syllabus_for_hidden').value = 'subject_level';
        populateSemesterSelect();
        document.getElementById('subject').innerHTML = '<option value="">Select semester first</option>';
    }
}

function loadSubjectsDirectly(productId) {
    fetch(`/instituteAdmin/syllabus/get-subjects/${productId}`)
        .then(res => res.json())
        .then(data => {
            const subjectSelect = document.getElementById('subject');
            if (subjectSelect) {
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            }
            const subjects = Array.isArray(data) ? data : (data?.subjects || []);
            
            if (Array.isArray(subjects) && subjects.length > 0) {
                const transformedData = subjects.map(item => ({
                    subject_id: item.subject_id,
                    subject_name: item.subject_name,
                    semester_id: item.semester_id || 'all_semesters'
                }));
                populateSubjects(transformedData);
                document.getElementById('semester_container').style.display = 'none';
                document.getElementById('semester_info').style.display = 'none';
                if (instituteType === 'School') {
                    document.getElementById('syllabus_for_hidden').value = 'class_level';
                }
            } else if (subjectSelect) {
                subjectSelect.innerHTML = '<option value="">No subjects found</option>';
            }
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
            const subjectSelect = document.getElementById('subject');
            if (subjectSelect) {
                subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
            }
        });
}

function populateSemesterSelect() {
    const semesterSelect = document.getElementById('semester_id');
    semesterSelect.innerHTML = '<option value="">-- Select Semester --</option>';
    
    if (availableSemesters.length > 0) {
        availableSemesters.forEach(semester => {
            semesterSelect.innerHTML += `<option value="${semester}">${semester}</option>`;
        });
    } else {
        semesterSelect.innerHTML = '<option value="">No semesters defined for this course</option>';
    }
}

document.getElementById('semester_id').addEventListener('change', function () {
    const semesterId = this.value;
    const productId = document.getElementById('subType').value;
    const termValueHidden = document.getElementById('term_value_hidden');

    if (termValueHidden) {
        termValueHidden.value = semesterId;
    }

    if (!semesterId || !productId) return;

    fetch(`/ajax/get-subjects-by-course-semester?course_detail_id=${productId}&semester_id=${semesterId}`)
        .then(res => res.json())
        .then(data => {
            populateSubjects(data.subjects || []);
        });
});

function populateSubjects(subjects) {
    const subjectSelect = document.getElementById('subject');
    const subjectInfo = document.getElementById('subject_info');
    if (!subjectSelect) return;

    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
    
    if (subjects && subjects.length > 0) {
        subjects.forEach(subject => {
            subjectSelect.innerHTML += `
                <option value="${subject.subject_id}" 
                        data-name="${subject.subject_name}"
                        data-semester="${subject.semester_id || ''}">
                    ${subject.subject_name}
                    ${subject.semester_id && subject.semester_id !== 'all_semesters' ? `(${subject.semester_id})` : ''}
                </option>`;
        });
        
        if (subjectInfo) {
            subjectInfo.style.display = 'flex';
        }
    }
}

document.getElementById('subject').addEventListener('change', function() {
    updateSyllabusTitle();
    loadSubjectCourseInfo();
});

function updateSyllabusTitle() {
    const subjectSelect = document.getElementById('subject');
    const subjectName = subjectSelect.options[subjectSelect.selectedIndex]?.dataset?.name;
    const semester = document.getElementById('semester_id').value;
    const mode = currentMode;

    let title = subjectName ? `${subjectName} Syllabus` : '';

    if (semester && semester !== 'all_semesters') {
        title = `${subjectName} - ${semester} Syllabus`;
    }

    if (mode === 'month_wise') {
        title = title + ' (Month Wise)';
    } else {
        title = title + ' (Full Semester)';
    }

    document.getElementById('syllabus_title').value = title;
}

document.getElementById('category').addEventListener('change', function() {
    const categoryId = this.value;
    const deptSelect = document.getElementById('department');
    
    if (categoryId) {
        fetch(`/ajax/departments-by-category?category_id=${categoryId}`)
            .then(res => res.json())
            .then(data => {
                deptSelect.innerHTML = '<option value="">Select Department</option>';
                if (data.success && data.departments) {
                    data.departments.forEach(dept => {
                        deptSelect.innerHTML += `<option value="${dept.department_id}">${dept.department}</option>`;
                    });
                } else {
                    deptSelect.innerHTML = '<option value="">No departments found</option>';
                }
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                deptSelect.innerHTML = '<option value="">Error loading departments</option>';
            });
    }
});

// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            const semesterId = document.getElementById('semester_id');
            const subjectId = document.getElementById('subject').value;
            
            if (currentMode === 'month_wise') {
                const selectedMonths = document.querySelectorAll('#month_selector_container input[type="checkbox"]:checked');
                if (selectedMonths.length === 0) {
                    alert('Please select at least one month for Month Wise mode.');
                    event.preventDefault();
                    event.stopPropagation();
                    return;
                }
                
                const topics = document.querySelectorAll('.topic-item');
                if (topics.length === 1) {
                    const firstTopicName = topics[0].querySelector('input[name="topics[0][topic_name]"]');
                    if (firstTopicName && firstTopicName.value === 'Select months and generate topics') {
                        alert('Please click "Generate Topics from Selected Months" to create your topics.');
                        event.preventDefault();
                        event.stopPropagation();
                        return;
                    }
                }
            }
            
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

document.addEventListener('DOMContentLoaded', function() {
    selectMode('whole_semester');
    setupAllPdfPreviews();
});
</script>
@endsection