{{-- resources/views/instituteAdmin/Discounts/index.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<!-- Bootstrap Icons CDN -->
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
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
        --card-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
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
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .btn-create {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(5px);
        transition: all 0.3s;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-create:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        border: none;
        /*overflow: hidden;*/
        animation: fadeInUp 0.5s ease;
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

    .card-header-custom {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 20px 30px;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-header-custom h4 {
        margin: 0;
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
    }

    .card-header-custom h4 i {
        font-size: 22px;
    }

    /*.card-body-custom {*/
    /*    padding: 30px;*/
    /*}*/

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: var(--primary-gradient);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }

    /* Table Styles */
    /*.table-responsive {*/
    /*    overflow: scroll;*/
    /*}*/

    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead {
        background: var(--primary-gradient) !important;
        border-bottom: none !important;
    }
    
    .table thead th {
        padding: 15px 16px;
        color: white;
        font-weight: 600;
        text-align: left;
        font-size: 14px;
        border-bottom: none;
        cursor: pointer;
        user-select: none;
        transition: all 0.2s;
        position: relative;
        letter-spacing: 0.3px;
    }
    
    .sortable{
        min-width: 200px;
        font-weight: 700;
    }

    .table tbody td {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr {
        transition: all 0.3s;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    /* Badge Styles */
    .badge-custom {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-flat {
        background: var(--info-gradient);
        color: white;
    }

    .badge-percentage {
        background: var(--success-gradient);
        color: white;
    }

    .badge-course {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: white;
    }

    .badge-transportation {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .badge-hostel {
        background: linear-gradient(135deg, #ec4899, #be185d);
        color: white;
    }

    .badge-custom-fee {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
    }

    .badge-active {
        background: var(--success-gradient);
        color: white;
    }

    .badge-inactive {
        background: var(--danger-gradient);
        color: white;
    }

    .badge-valid {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .badge-expired {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .badge-assigned {
        background: var(--primary-gradient);
        color: white;
    }

    /* Discount ID */
    .discount-id {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--primary-color);
        background: #e0e7ff;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        font-size: 12px;
    }

    .coupon-code {
        font-size: 11px;
        color: #64748b;
        margin-top: 4px;
    }

    /* Action Buttons */
    .btn-group-sm {
        gap: 5px;
        display: flex;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
        color: white;
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
    }

    .btn-outline-info {
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
        border: 1px solid #7dd3fc;
    }

    .btn-outline-info:hover {
        background: linear-gradient(135deg, #bae6fd, #7dd3fc);
        transform: translateY(-2px);
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        color: #94a3b8;
        font-size: 40px;
    }

    .empty-state h5 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        font-size: 20px;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 20px;
    }

    /* Pagination */
    .pagination-info {
        font-size: 14px;
        color: #64748b;
    }

    .pagination {
        gap: 5px;
    }

    .page-link {
        border: 2px solid var(--border-color);
        border-radius: 8px !important;
        padding: 8px 12px;
        color: var(--primary-color);
        font-weight: 500;
        transition: all 0.3s;
    }

    .page-link:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }

    .page-item.active .page-link {
        background: var(--primary-gradient);
        border-color: transparent;
    }

    /* Modal */
    .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .modal-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 25px;
        border: none;
    }

    .modal-header .modal-title {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
        opacity: 0.8;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-body pre {
        background: #f1f5f9;
        padding: 15px;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        font-size: 12px;
        max-height: 400px;
        overflow-y: auto;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .btn-create {
            width: 100%;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .card-header-custom {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        /*.table-responsive {*/
        /*    overflow-x: auto;*/
        /*}*/

        .btn-group-sm {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .d-flex.justify-content-between.align-items-center {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
    }
    
    /* Filter container - Enhanced */
    .filter-container {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
    }

    .filter-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--primary-gradient);
    }

    .filter-form {
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin: 0;
    }

    .filter-grid {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-group {
        position: relative;
        /*flex: 1;*/
        min-width: 220px;
    }

    .filter-group .bi {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-color);
        z-index: 1;
        font-size: 16px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 14px;
        background: #fff;
        transition: all 0.3s;
        height: 45px;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .filter-group input:hover,
    .filter-group select:hover {
        border-color: var(--secondary-color);
    }

    .filter-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        /*margin-top:10px;*/
    }

    .btn-filter {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 14px;
        white-space: nowrap;
    }

    .btn-filter:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .btn-filter:active {
        transform: translateY(-1px);
    }

    .btn-filter-primary {
        background: var(--primary-gradient);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-filter-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-filter-primary:hover::before {
        left: 100%;
    }

    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid var(--border);
    }

    .filter-container h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    } 

    .date-group {
        position: relative;
    }

    .floating-label {
        position: absolute;
        top: -8px;
        left: 35px;
        background: white;
        padding: 0 6px;
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        z-index: 2;
    }

    /* Adjust icon spacing */
    .date-group i {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .date-group .filter-input {
        padding-left: 35px;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #ccc;
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    #pageLoader {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    
            /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }

        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {  
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table-responsive{
            overflow-x: hidden;
        }
        
        .erp-table thead .sticky-main,
        .erp-table tbody .sticky-main{
            left: 28px;
        }
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-tags"></i>
            Discount Management
        </h1>
        <a href="{{ route('discounts.create') }}" class="btn-create">
            <i class="fas fa-plus"></i> Create New Discount
        </a>
    </div>

    <!-- Stats Cards -->
    @if(!$discounts->isEmpty())
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tag"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Discounts</div>
                <div class="stat-value">{{ $discounts->total() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: var(--success-gradient);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Active Discounts</div>
                <div class="stat-value">{{ $discounts->where('is_active', true)->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Valid Discounts</div>
                <div class="stat-value">{{ $discounts->filter(function($d) { return $d->isValid(); })->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9);">
                <i class="fas fa-link"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Assigned</div>
                <div class="stat-value">{{ $discounts->sum('assignments_count') }}</div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="filter-container">
        <h6><i class="bi bi-funnel-fill"></i> *Select or Type to Search</h6>

        <form method="GET" id="filterForm" class="filter-form">
            <div class="filter-grid">

                <!-- Discount Name -->
                <div class="filter-group">
                    <i class="bi bi-tag"></i>
                    <input type="text" name="name" class="filter-input"
                        list="discountNames"
                        placeholder="Search Discount Name"
                        value="{{ request('name') }}">

                    <datalist id="discountNames">
                        @foreach($filterData['names'] as $name)
                            <option value="{{ $name }}">
                        @endforeach
                    </datalist>
                </div>

                <!-- Discount Type -->
                <div class="filter-group">
                    <i class="bi bi-percent"></i>
                    <select name="type" class="filter-input">
                        <option value="">All Types</option>
                        <option value="flat" {{ request('type') == 'flat' ? 'selected' : '' }}>Flat</option>
                        <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    </select>
                </div>

                <!-- Fee Type -->
                <div class="filter-group">
                    <i class="bi bi-cash-stack"></i>
                    <select name="fee_type" class="filter-input">
                        <option value="">All Fee Types</option>
                        @foreach($filterData['fee_types'] as $type)
                            <option value="{{ $type }}" {{ request('fee_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Valid From -->
                <div class="filter-group date-group">
                    <label class="floating-label">Valid From</label>
                    <i class="bi bi-calendar-event"></i>
                    <input type="date" name="valid_from" class="filter-input"
                        value="{{ request('valid_from') }}">
                </div>

                <div class="filter-group date-group">
                    <label class="floating-label">Valid To</label>
                    <i class="bi bi-calendar-check"></i>
                    <input type="date" name="valid_to" class="filter-input"
                        value="{{ request('valid_to') }}">
                </div>

                <!-- Status -->
                <div class="filter-group">
                    <i class="bi bi-toggle-on"></i>
                    <select name="status" class="filter-input">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter btn-filter-primary d-none">
                        <i class="bi bi-funnel"></i> Apply
                    </button>
    
                    <a href="{{ route('discounts.list') }}" class="btn-filter btn-filter-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </div>

        </form>
    </div>

    <!-- Main Card -->
    <div class="main-card">
        <div class="card-header-custom">
            <h4>
                <i class="fas fa-list"></i>
                Discounts List
            </h4>
            @if(!$discounts->isEmpty())
            <span class="badge-custom" style="background: var(--primary-gradient); color: white;">
                <i class="fas fa-layer-group me-1"></i>
                {{ $discounts->total() }} Total Entries
            </span>
            @endif
        </div>
        <div class="card-body-custom">
            @if($discounts->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h5>No Discounts Found</h5>
                    <p>Create your first discount to get started with managing student discounts.</p>
                    <a href="{{ route('discounts.create') }}" class="btn-action btn-primary" style="display: inline-flex;">
                        <i class="fas fa-plus me-1"></i> Create Discount
                    </a>
                </div>
            @else
                <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                    <table class="erp-table table">
                        <thead>
                            <tr>
                                <th class="sticky-checkbox">#</th>
                                <th class="sticky-main sortable">Discount ID</th>
                                <th class="sortable">Name</th>
                                <th class="sortable">Type</th>
                                <th class="sortable">Fee Type</th>
                                <th class="sortable">Value</th>
                                <th class="sortable">Valid Period</th>
                                <th class="sortable">Status</th>
                                <th class="sortable">Assignments</th>
                                <th class="sortable">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($discounts as $discount)
                                <tr>
                                    <td class="sticky-checkbox"><span class="fw-bold">{{ $loop->iteration + ($discounts->currentPage() - 1) * $discounts->perPage() }}</span></td>
                                    <td class="sticky-main">
                                        <span class="discount-id">{{ $discount->discount_hash_id }}</span>
                                        @if($discount->coupon_code)
                                            <div class="coupon-code">
                                                <i class="fas fa-ticket-alt me-1"></i>{{ $discount->coupon_code }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $discount->name }}</strong>
                                        @if($discount->description)
                                            <br><small class="text-muted" style="font-size: 11px;"><i class="fas fa-align-left me-1"></i>{{ Str::limit($discount->description, 40) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-custom {{ $discount->type == 'flat' ? 'badge-flat' : 'badge-percentage' }}">
                                            <i class="fas fa-{{ $discount->type == 'flat' ? 'rupee-sign' : 'percent' }} me-1"></i>
                                            {{ ucfirst($discount->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $feeTypeClass = 'badge-' . strtolower($discount->fee_type);
                                        @endphp
                                        <span class="badge-custom {{ $feeTypeClass }}">
                                            <i class="fas fa-{{ $discount->fee_type == 'course' ? 'book' : ($discount->fee_type == 'transportation' ? 'bus' : ($discount->fee_type == 'hostel' ? 'bed' : 'cog')) }} me-1"></i>
                                            {{ ucfirst($discount->fee_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold" style="color: var(--primary-color);">
                                            @if($discount->type == 'percentage')
                                                {{ number_format($discount->value, 0) }}%
                                            @else
                                                ₹{{ number_format($discount->value, 2) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-size: 12px;">
                                            <i class="fas fa-calendar-alt text-primary me-1"></i>
                                            {{ \Carbon\Carbon::parse($discount->valid_from)->format('d M Y') }}
                                            <br>
                                            <i class="fas fa-long-arrow-alt-down text-muted ms-3" style="font-size: 10px;"></i>
                                            <br>
                                            <i class="fas fa-calendar-alt text-danger me-1"></i>
                                            {{ \Carbon\Carbon::parse($discount->valid_to)->format('d M Y') }}
                                        </div>
                                        <span class="badge-custom {{ $discount->isValid() ? 'badge-valid' : 'badge-expired' }}" style="margin-top: 5px; display: inline-block;">
                                            <i class="fas fa-{{ $discount->isValid() ? 'check-circle' : 'exclamation-circle' }} me-1"></i>
                                            {{ $discount->isValid() ? 'Valid' : 'Expired' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($discount->is_active && $discount->isValid())
                                            <span class="badge-custom badge-active">
                                                <i class="fas fa-circle me-1"></i> Active
                                            </span>
                                        @else
                                            <span class="badge-custom badge-inactive">
                                                <i class="fas fa-circle me-1"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-custom badge-assigned">
                                            <i class="fas fa-users me-1"></i>
                                            {{ $discount->assignments_count ?? 0 }} assigned
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $assignRoute = null;   

                                            switch ($discount->fee_type) {
                                                case 'transportation':
                                                    $assignRoute = route('admin.transport.assign-fee.form', $discount->discount_hash_id);
                                                    break;

                                                case 'hostel':
                                                    $assignRoute = route('admin.hostel-fees.assign.form', $discount->discount_hash_id);
                                                    break;

                                                case 'custom':
                                                    $assignRoute = route('admin.custom-fees.assign.form', $discount->discount_hash_id);
                                                    break;

                                                case 'course':
                                                    $assignRoute = route('course.fee.discount.form', $discount->discount_hash_id);
                                                    break;
                                            }
                                        @endphp
                                        <div class="btn-group-sm">
                                            @if(($discount->assignments_count ?? 0) > 0)
                                                <button class="btn-action btn-success" disabled title="Already Assigned">
                                                    <i class="fas fa-check"></i> Assigned
                                                </button>
                                                <a href="#" class="btn-action btn-outline-info" title="View Assignments">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <a href="{{ $assignRoute }}" 
                                                    class="btn-action btn-primary" title="Assign Discount">
                                                        <i class="fas fa-link"></i> Assign
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Floating Horizontal Scrollbar -->
                <div class="table-scroll-top" id="tableScrollTop">
                    <div class="table-scroll-inner"></div>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-4">
                    <div class="pagination-info">
                        <i class="fas fa-info-circle me-1"></i>
                        Showing {{ $discounts->firstItem() }} to {{ $discounts->lastItem() }} of {{ $discounts->total() }} entries
                    </div>
                    <div class="pagination">
                        {{ $discounts->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Debug Modal -->
<div class="modal fade" id="debugModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-bug me-2"></i>
                    Discount Debug Info
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="debugContent"></pre>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Debug function to check discount data
    window.debugDiscount = function(discountId) {
        $.ajax({
            url: '/discounts/debug/' + discountId,
            method: 'GET',
            success: function(response) {
                $('#debugContent').text(JSON.stringify(response, null, 2));
                $('#debugModal').modal('show');
            }
        });
    };
});
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

document.querySelectorAll('.filter-input').forEach(input => {
    let timer;
    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            showLoader();
            document.getElementById('filterForm').submit();
        }, 500);
    });
});
</script>

@endsection