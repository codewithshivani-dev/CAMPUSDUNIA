@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Edit Copy #{{ $copy->copy_id }} - {{ $book->title }}</title>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --danger-color: #ef4444;
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
        --card-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    /* Dashboard Header */
    .dashboard-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        animation: fadeInDown 0.5s ease;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
        pointer-events: none;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .header-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .header-title i {
        font-size: 2.2rem;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .header-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }

    /* Breadcrumb */
    .breadcrumb-wrapper {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 50px;
        padding: 10px 20px;
        margin-bottom: 20px;
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .breadcrumb {
        background: transparent;
        margin: 0;
        padding: 0;
    }

    .breadcrumb-item {
        font-size: 0.85rem;
    }

    .breadcrumb-item a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .breadcrumb-item a:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #64748b;
        font-weight: 500;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #94a3b8;
        font-size: 1.2rem;
    }

    /* Action Buttons */
    .action-buttons-top {
        display: flex;
        gap: 12px;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        text-decoration: none;
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    /* Info Cards Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .info-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        border: 2px solid var(--border-color);
        transition: all 0.3s;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--border-color);
    }

    .info-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .info-card-icon-primary {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        color: var(--primary-color);
    }

    .info-card-icon-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: var(--success-color);
    }

    .info-card-icon-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
    }

    .info-card-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .info-card-value {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        word-break: break-word;
    }

    .info-card-value-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
        background: var(--primary-gradient);
        color: white;
    }

    /* Condition Badge */
    .condition-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        background: var(--warning-gradient);
        color: white;
        box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
    }

    .condition-badge.new { background: var(--success-gradient); }
    .condition-badge.good { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .condition-badge.fair { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .condition-badge.poor { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .condition-badge.damage { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border: 2px solid var(--border-color);
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 20px 25px;
        border-bottom: 2px solid var(--border-color);
    }

    .form-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-header h3 i {
        color: var(--primary-color);
        font-size: 1.3rem;
    }

    .form-body {
        padding: 30px;
    }

    /* Copy ID Banner */
    .copy-id-banner {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 30px;
    }

    .copy-id-icon {
        width: 56px;
        height: 56px;
        background: white;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1.5rem;
        border: 2px solid var(--border-color);
    }

    .copy-id-info {
        flex: 1;
    }

    .copy-id-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--primary-color);
        margin-bottom: 4px;
    }

    .copy-id-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        font-family: monospace;
    }

    .copy-id-note {
        font-size: 0.75rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 4px;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-label i {
        font-size: 0.9rem;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.9rem;
        background: white;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .form-control[readonly] {
        background: #f8fafc;
        cursor: not-allowed;
        opacity: 0.7;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px;
        padding-right: 40px;
    }

    textarea.form-control {
        padding-top: 14px;
        min-height: 100px;
        resize: vertical;
    }

    textarea.form-control + .input-icon {
        top: 16px;
        transform: none;
    }

    /* Date Grid */
    .date-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    /* Helper Text */
    .helper-text {
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Quick Stats */
    .quick-stats {
        display: flex;
        align-items: center;
        gap: 24px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 15px 20px;
        border-radius: 12px;
        margin: 20px 0;
        border: 1px solid var(--border-color);
    }

    .stat-item {
        display: flex;
        flex-direction: column;
    }

    .stat-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--primary-color);
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
    }

    /* Form Footer */
    .form-footer {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }

    .btn-submit {
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
        background: var(--success-gradient);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }

    .btn-cancel {
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        border: 2px solid var(--border-color);
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        background: white;
        color: #475569;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #1e293b;
        transform: translateY(-2px);
        text-decoration: none;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }

        .dashboard-header {
            padding: 20px;
        }

        .header-title {
            font-size: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .action-buttons-top {
            margin-top: 15px;
            width: 100%;
        }

        .btn-action {
            flex: 1;
            justify-content: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .date-grid {
            grid-template-columns: 1fr;
        }

        .form-footer {
            flex-direction: column-reverse;
        }

        .btn-submit, .btn-cancel {
            width: 100%;
            justify-content: center;
        }

        .quick-stats {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="header-title">
                    <i class="bi bi-pencil-square"></i>Edit Book Copy
                </h1>
                <p class="header-subtitle">Update copy information for "{{ $book->title }}"</p>
            </div>
            <div class="action-buttons-top">
                <a href="{{ route('library.books.copies', $copy->librarybook_id) }}" class="btn-action btn-back">
                    <i class="bi bi-arrow-left"></i>Back to Copies
                </a>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="breadcrumb-wrapper">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('library.data') }}"><i class="bi bi-collection"></i> Books</a></li>
                <li class="breadcrumb-item"><a href="{{ route('library.books.copies', $copy->librarybook_id) }}"><i class="bi bi-files"></i> Copies</a></li>
                <li class="breadcrumb-item active">Edit Copy #{{ $copy->copy_id }}</li>
            </ol>
        </nav>
    </div>

    <!-- Info Cards -->
    <div class="info-grid">
        <div class="info-card">
            <div class="info-card-header">
                <div class="info-card-icon info-card-icon-primary">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <div class="info-card-title">Book Title</div>
                    <div class="info-card-value">{{ $book->title }}</div>
                </div>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-header">
                <div class="info-card-icon info-card-icon-success">
                    <i class="bi bi-upc-scan"></i>
                </div>
                <div>
                    <div class="info-card-title">Copy ID</div>
                    <div class="info-card-value">
                        <span class="info-card-value-badge">{{ $copy->copy_id }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="info-card">
            <div class="info-card-header">
                <div class="info-card-icon info-card-icon-warning">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div>
                    <div class="info-card-title">Current Condition</div>
                    <div class="info-card-value">
                        <span class="condition-badge {{ $copy->condition }}">
                            <i class="bi {{ 
                                $copy->condition == 'new' ? 'bi-star' : 
                                ($copy->condition == 'good' ? 'bi-check-circle' : 
                                ($copy->condition == 'fair' ? 'bi-exclamation-triangle' : 
                                ($copy->condition == 'poor' ? 'bi-arrow-down-circle' : 'bi-tools'))) 
                            }}"></i>
                            {{ ucfirst($copy->condition) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-header">
            <h3>
                <i class="bi bi-file-earmark-text"></i>
                Copy Information
            </h3>
        </div>

        <div class="form-body">
            <!-- Copy ID Banner -->
            <div class="copy-id-banner">
                <div class="copy-id-icon">
                    <i class="bi bi-upc-scan"></i>
                </div>
                <div class="copy-id-info">
                    <div class="copy-id-label">Copy ID (Cannot be changed)</div>
                    <div class="copy-id-value">{{ $copy->copy_id }}</div>
                    <div class="copy-id-note">
                        <i class="bi bi-info-circle"></i>
                        Copy ID is permanent and cannot be modified
                    </div>
                </div>
            </div>

            <form action="{{ route('library.copies.update', $copy->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Form Grid -->
                <div class="form-grid">
                    <!-- Author/Writer -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-pencil"></i>Author/Writer
                        </label>
                        <div class="input-wrapper">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" name="writer_name" class="form-control" 
                                   value="{{ old('writer_name', $copy->writer_name) }}"
                                   placeholder="Enter author name">
                        </div>
                    </div>

                    <!-- Pages -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-file-text"></i>Pages
                        </label>
                        <div class="input-wrapper">
                            <i class="bi bi-123 input-icon"></i>
                            <input type="number" name="pages" class="form-control" 
                                   value="{{ old('pages', $copy->pages) }}"
                                   min="1" placeholder="Number of pages">
                        </div>
                    </div>

                    <!-- Cost -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-currency-rupee"></i>Cost
                        </label>
                        <div class="input-wrapper">
                            <i class="bi bi-currency-rupee input-icon"></i>
                            <input type="number" name="cost" class="form-control" 
                                   value="{{ old('cost', $copy->cost) }}"
                                   min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>

                    <!-- Condition -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-clipboard-check"></i>Condition
                        </label>
                        <div class="input-wrapper">
                            <i class="bi bi-tag input-icon"></i>
                            <select name="condition" class="form-control">
                                <option value="">Select Condition</option>
                                <option value="new" {{ $copy->condition == 'new' ? 'selected' : '' }}>New</option>
                                <option value="good" {{ $copy->condition == 'good' ? 'selected' : '' }}>Good</option>
                                <option value="fair" {{ $copy->condition == 'fair' ? 'selected' : '' }}>Fair</option>
                                <option value="poor" {{ $copy->condition == 'poor' ? 'selected' : '' }}>Poor</option>
                                <option value="damage" {{ $copy->condition == 'damage' ? 'selected' : '' }}>Damaged</option>
                            </select>
                        </div>
                    </div>

                    <!-- Rack -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-grid-3x3"></i>Rack Number
                        </label>
                        <div class="input-wrapper">
                            <i class="bi bi-grid input-icon"></i>
                            <input type="text" name="rack" class="form-control" 
                                   value="{{ old('rack', $copy->rack) }}"
                                   placeholder="e.g., A-12">
                        </div>
                    </div>

                    <!-- Shelf -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-layers"></i>Shelf Number
                        </label>
                        <div class="input-wrapper">
                            <i class="bi bi-layers input-icon"></i>
                            <input type="text" name="shelf" class="form-control" 
                                   value="{{ old('shelf', $copy->shelf) }}"
                                   placeholder="e.g., S-3">
                        </div>
                    </div>
                </div>

                <!-- Publishing Date Section -->
                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label">
                        <i class="bi bi-calendar"></i>Publishing Date
                    </label>
                    
                    @php
                        $publishYear = null;
                        $publishMonth = null;
                        $publishDay = null;
                        
                        if ($copy->publishing_date) {
                            $date = new DateTime($copy->publishing_date);
                            $publishYear = $date->format('Y');
                            $publishMonth = $date->format('m');
                            $publishDay = $date->format('d');
                        }
                    @endphp
                    
                    <div class="date-grid">
                        <select name="publish_year" class="form-control">
                            <option value="">Year</option>
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 100;
                            @endphp
                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                <option value="{{ $year }}" {{ (old('publish_year', $publishYear) == $year) ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        
                        <select name="publish_month" class="form-control">
                            <option value="">Month</option>
                            @for ($month = 1; $month <= 12; $month++)
                                <option value="{{ $month }}" {{ (old('publish_month', $publishMonth) == $month) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                </option>
                            @endfor
                        </select>
                        
                        <select name="publish_day" class="form-control">
                            <option value="">Day</option>
                            @for ($day = 1; $day <= 31; $day++)
                                <option value="{{ $day }}" {{ (old('publish_day', $publishDay) == $day) ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="helper-text">
                        <i class="bi bi-info-circle"></i>Year required, Month/Day optional
                    </div>
                    @error('publish_year')
                        <div class="helper-text" style="color: var(--danger-color);">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remarks -->
                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label">
                        <i class="bi bi-chat-text"></i>Remarks
                    </label>
                    <div class="input-wrapper">
                        <i class="bi bi-chat-text input-icon" style="top: 16px;"></i>
                        <textarea name="remarks" class="form-control" 
                                  placeholder="Any additional notes about this copy...">{{ old('remarks', $copy->remarks) }}</textarea>
                    </div>
                    <div class="helper-text">
                        <i class="bi bi-info-circle"></i>Add any special notes or observations about this copy
                    </div>
                </div>

                <!-- Quick Stats -->
                @if($copy->created_at)
                <div class="quick-stats">
                    <div class="stat-item">
                        <span class="stat-label">Added On</span>
                        <span class="stat-value">{{ $copy->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($copy->updated_at && $copy->updated_at != $copy->created_at)
                    <div class="stat-item">
                        <span class="stat-label">Last Updated</span>
                        <span class="stat-value">{{ $copy->updated_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Form Footer -->
                <div class="form-footer">
                    <a href="{{ route('library.books.copies', $copy->librarybook_id) }}" class="btn-cancel">
                        <i class="bi bi-x-lg"></i>Cancel
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-circle"></i>Update Copy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.querySelector('select[name="publish_year"]');
    const monthSelect = document.querySelector('select[name="publish_month"]');
    const daySelect = document.querySelector('select[name="publish_day"]');
    
    function updateDays() {
        const year = yearSelect.value;
        const month = monthSelect.value;
        
        if (!year || !month) {
            return;
        }
        
        const daysInMonth = new Date(year, month, 0).getDate();
        const currentSelectedDay = daySelect.value;
        
        daySelect.innerHTML = '<option value="">Day</option>';
        
        for (let day = 1; day <= daysInMonth; day++) {
            const option = document.createElement('option');
            option.value = day;
            option.textContent = day;
            if (currentSelectedDay && currentSelectedDay == day) {
                option.selected = true;
            }
            daySelect.appendChild(option);
        }
    }
    
    yearSelect.addEventListener('change', updateDays);
    monthSelect.addEventListener('change', updateDays);
    
    // Initialize days on page load
    if (yearSelect.value && monthSelect.value) {
        updateDays();
    }
});
</script>
@endsection