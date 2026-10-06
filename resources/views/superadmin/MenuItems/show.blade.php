{{-- resources/views/superadmin/menus/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Menu Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        /* ============================================================
           ROOT VARIABLES
           ============================================================ */
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #818cf8;
            --primary-gradient: linear-gradient(135deg, #4f46e5, #7c3aed);
            --info: #3b82f6;
            --info-gradient: linear-gradient(135deg, #3b82f6, #6366f1);
            --warning: #f59e0b;
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --success: #10b981;
            --danger: #ef4444;
            --secondary: #0f172a;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.12);
            --shadow-xl: 0 20px 48px rgba(0,0,0,0.15);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-2xl: 24px;
            --transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* ============================================================
           RESET & BASE
           ============================================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f0f4f9;
            color: var(--gray-900);
            line-height: 1.6;
            padding: 24px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .app-container {
            max-width: 880px;
            width: 100%;
            margin: 0 auto;
        }

        /* ============================================================
           HEADER
           ============================================================ */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 0 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .page-header .logo-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            background: var(--info-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.4rem;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.35);
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.02em;
        }

        .page-header h1 span {
            color: var(--info);
        }

        .page-header .subtitle {
            font-size: 0.8rem;
            color: var(--gray-400);
            font-weight: 500;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 1.2rem;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid var(--gray-200);
            background: #fff;
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            box-shadow: var(--shadow-sm);
        }

        .btn-back:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ============================================================
           MAIN CARD
           ============================================================ */
        .detail-card {
            background: #fff;
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .detail-card-header {
            padding: 20px 28px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-bottom: 2px solid #93c5fd;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .detail-card-header .header-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: var(--info-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .detail-card-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .detail-card-header .menu-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            margin-left: auto;
        }

        .menu-badge-parent {
            background: #dbeafe;
            color: #1e40af;
        }

        .menu-badge-child {
            background: #fef3c7;
            color: #92400e;
        }

        .detail-card-body {
            padding: 28px 32px;
        }

        .detail-card-footer {
            padding: 16px 32px 28px;
            border-top: 1px solid var(--gray-100);
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* ============================================================
           STATUS BADGE
           ============================================================ */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .status-badge.active {
            background: #ecfdf5;
            color: #065f46;
        }

        .status-badge.inactive {
            background: #fef2f2;
            color: #991b1b;
        }

        .status-badge .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-badge.active .status-dot {
            background: #10b981;
        }

        .status-badge.inactive .status-dot {
            background: #ef4444;
        }

        /* ============================================================
           INFO GRID
           ============================================================ */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .info-section {
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            border: 1px solid var(--gray-100);
        }

        .info-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: var(--gray-400);
            margin-bottom: 12px;
            border-bottom: 1px solid var(--gray-200);
            padding-bottom: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid rgba(0,0,0,0.03);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gray-500);
        }

        .info-row .value {
            font-size: 0.85rem;
            color: var(--gray-900);
            text-align: right;
            word-break: break-word;
            max-width: 60%;
        }

        .info-row .value .icon-display {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            background: var(--gray-200);
            color: var(--gray-600);
            font-size: 0.9rem;
            margin-right: 6px;
        }

        .info-row .value .icon-display.has-icon {
            background: #eef2ff;
            color: var(--primary);
        }

        /* ============================================================
           BADGES
           ============================================================ */
        .badge {
            display: inline-block;
            padding: 0.2rem 0.7rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .badge-primary {
            background: #eef2ff;
            color: var(--primary);
        }

        .badge-success {
            background: #ecfdf5;
            color: #065f46;
        }

        .badge-danger {
            background: #fef2f2;
            color: #991b1b;
        }

        .badge-warning {
            background: #fffbeb;
            color: #92400e;
        }

        .badge-secondary {
            background: var(--gray-100);
            color: var(--gray-500);
        }

        .badge-info {
            background: #eff6ff;
            color: #1e40af;
        }

        .badge-sm {
            padding: 0.1rem 0.5rem;
            font-size: 0.6rem;
        }

        .role-pill {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            font-weight: 500;
            font-size: 0.65rem;
            padding: 0.15rem 0.6rem;
            border-radius: 20px;
            margin: 2px 2px 0 0;
        }

        .role-pill-all {
            background: var(--gray-200);
            color: var(--gray-600);
            font-weight: 600;
        }

        /* ============================================================
           CHILDREN LIST
           ============================================================ */
        .children-section {
            margin-top: 24px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .children-section-header {
            padding: 12px 20px;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .children-section-header h3 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--gray-700);
            margin: 0;
        }

        .children-section-header .count {
            font-size: 0.75rem;
            color: var(--gray-400);
            background: var(--gray-200);
            padding: 0.1rem 0.6rem;
            border-radius: 20px;
        }

        .children-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .children-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            border-bottom: 1px solid var(--gray-100);
            transition: var(--transition);
        }

        .children-list li:last-child {
            border-bottom: none;
        }

        .children-list li:hover {
            background: var(--gray-50);
        }

        .children-list li .child-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .children-list li .child-info .child-icon {
            width: 28px;
            height: 28px;
            border-radius: var(--radius-sm);
            background: var(--gray-100);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            font-size: 0.8rem;
        }

        .children-list li .child-info .child-icon.has-icon {
            background: #eef2ff;
            color: var(--primary);
        }

        .children-list li .child-info a {
            color: var(--gray-800);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.88rem;
            transition: var(--transition);
        }

        .children-list li .child-info a:hover {
            color: var(--primary);
        }

        .children-list li .child-meta {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ============================================================
           DESCRIPTION
           ============================================================ */
        .description-box {
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            padding: 16px 20px;
            border: 1px solid var(--gray-100);
            margin-top: 20px;
        }

        .description-box .desc-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            color: var(--gray-400);
            margin-bottom: 6px;
        }

        .description-box .desc-text {
            font-size: 0.9rem;
            color: var(--gray-700);
            line-height: 1.7;
        }

        /* ============================================================
           BUTTONS
           ============================================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.6rem 1.5rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            background: transparent;
            color: var(--gray-600);
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: #fff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
            color: #fff;
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: #fff;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
            color: #fff;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.35);
            color: #fff;
        }

        .btn-secondary {
            border: 1px solid var(--gray-200);
            color: var(--gray-600);
            background: #fff;
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            transform: translateY(-2px);
        }

        .btn-sm {
            padding: 0.4rem 1rem;
            font-size: 0.75rem;
        }

        /* ============================================================
           META INFO
           ============================================================ */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
            padding: 16px 20px;
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-100);
        }

        .meta-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .meta-item .meta-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .meta-item .meta-value {
            font-size: 0.8rem;
            color: var(--gray-700);
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 768px) {
            body { padding: 16px; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .page-header .btn-back { align-self: stretch; justify-content: center; }
            .detail-card-header { padding: 16px 20px; }
            .detail-card-header .menu-badge { margin-left: 0; }
            .detail-card-body { padding: 20px; }
            .detail-card-footer { padding: 16px 20px 24px; flex-direction: column; }
            .detail-card-footer .btn { width: 100%; justify-content: center; }
            .info-grid { grid-template-columns: 1fr; }
            .meta-grid { grid-template-columns: 1fr; }
            .children-list li { flex-direction: column; align-items: flex-start; gap: 6px; }
            .children-list li .child-meta { align-self: flex-start; }
        }

        @media (max-width: 480px) {
            body { padding: 12px; }
            .page-header h1 { font-size: 1.2rem; }
            .page-header .logo-icon { width: 40px; height: 40px; font-size: 1.1rem; }
            .detail-card-header { padding: 12px 16px; }
            .detail-card-header .header-icon { width: 34px; height: 34px; font-size: 0.9rem; }
            .detail-card-header h2 { font-size: 0.95rem; }
            .detail-card-body { padding: 16px; }
            .info-section { padding: 14px 16px; }
            .info-row { flex-direction: column; align-items: flex-start; gap: 2px; }
            .info-row .value { text-align: left; max-width: 100%; }
            .btn { padding: 0.5rem 1rem; font-size: 0.8rem; }
        }

        /* ============================================================
           TOASTR
           ============================================================ */
        .toast-success { background: linear-gradient(135deg, #10b981, #059669) !important; }
        .toast-error { background: linear-gradient(135deg, #ef4444, #dc2626) !important; }
        .toast-warning { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
        .toast-info { background: linear-gradient(135deg, #4f46e5, #7c3aed) !important; }

        /* ============================================================
           ANIMATIONS
           ============================================================ */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .detail-card {
            animation: slideUp 0.5s ease;
        }
    </style>
</head>
<body>
    <div class="app-container">

        <!-- ============================================================
        PAGE HEADER
        ============================================================ -->
        <div class="page-header">
            <div class="page-header-left">
                <div class="logo-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div>
                    <h1>Menu <span>Manager</span></h1>
                    <span class="subtitle">Viewing menu item details</span>
                </div>
            </div>
            <a href="{{ route('superadmin.menus.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Menu List
            </a>
        </div>

        <!-- ============================================================
        DETAIL CARD
        ============================================================ -->
        <div class="detail-card">

            <!-- Header -->
            <div class="detail-card-header">
                <div class="header-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h2>{{ $menu->name }}</h2>
                </div>
                <span class="status-badge {{ $menu->is_active ? 'active' : 'inactive' }}">
                    <span class="status-dot"></span>
                    {{ $menu->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($menu->parent_id)
                    <span class="menu-badge menu-badge-child">
                        <i class="fas fa-child"></i> Sub-menu
                    </span>
                @else
                    <span class="menu-badge menu-badge-parent">
                        <i class="fas fa-home"></i> Parent
                    </span>
                @endif
            </div>

            <!-- Body -->
            <div class="detail-card-body">

                <!-- Info Grid -->
                <div class="info-grid">
                    <!-- Left Column -->
                    <div class="info-section">
                        <div class="info-section-title"><i class="fas fa-tag"></i> General Information</div>
                        <div class="info-row">
                            <span class="label">ID</span>
                            <span class="value">#{{ $menu->id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Name</span>
                            <span class="value">
                                @if($menu->parent_id)
                                    <span style="color:var(--gray-400);">↳</span>
                                @endif
                                {{ $menu->name }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Icon</span>
                            <span class="value">
                                @if($menu->icon)
                                    <span class="icon-display has-icon">
                                        <i class="{{ $menu->icon }}"></i>
                                    </span>
                                    <code style="font-size:0.7rem;background:var(--gray-100);padding:0.1rem 0.4rem;border-radius:4px;">{{ $menu->icon }}</code>
                                @else
                                    <span style="color:var(--gray-400);">—</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Route / URL</span>
                            <span class="value">
                                @if($menu->route)
                                    <a href="{{ url($menu->route) }}" target="_blank" style="color:var(--primary);text-decoration:none;">
                                        {{ $menu->route }}
                                        <i class="fas fa-external-link-alt" style="font-size:0.6rem;margin-left:4px;opacity:0.6;"></i>
                                    </a>
                                @else
                                    <span style="color:var(--gray-400);">—</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Parent</span>
                            <span class="value">
                                @if($menu->parent)
                                    <a href="{{ route('superadmin.menus.show', $menu->parent) }}" style="color:var(--primary);text-decoration:none;">
                                        {{ $menu->parent->name }}
                                    </a>
                                @else
                                    <span class="badge badge-secondary">Root</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="info-section">
                        <div class="info-section-title"><i class="fas fa-cog"></i> Permissions & Settings</div>
                        <div class="info-row">
                            <span class="label">Display Order</span>
                            <span class="value">{{ $menu->display_order ?? 0 }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Permission</span>
                            <span class="value">
                                @if($menu->permission_name)
                                    <span class="badge badge-primary">{{ $menu->permission_name }}</span>
                                @else
                                    <span style="color:var(--gray-400);">—</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Allowed Roles</span>
                            <span class="value" style="text-align:right;">
                                @php
                                    $roles = is_string($menu->allowed_roles) 
                                        ? json_decode($menu->allowed_roles, true) 
                                        : $menu->allowed_roles;
                                @endphp
                                @if($roles && count($roles) > 0)
                                    <div style="display:flex;flex-wrap:wrap;gap:2px;justify-content:flex-end;">
                                        @foreach($roles as $role)
                                            <span class="role-pill">{{ $role }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="role-pill role-pill-all">All</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Children</span>
                            <span class="value">
                                @if($menu->children->count() > 0)
                                    <span class="badge badge-info">{{ $menu->children->count() }} sub-items</span>
                                @else
                                    <span style="color:var(--gray-400);">No children</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($menu->description)
                    <div class="description-box">
                        <div class="desc-label"><i class="fas fa-align-left"></i> Description</div>
                        <div class="desc-text">{{ $menu->description }}</div>
                    </div>
                @endif

                <!-- Children List -->
                @if($menu->children->count() > 0)
                    <div class="children-section">
                        <div class="children-section-header">
                            <h3><i class="fas fa-sitemap"></i> Child Menu Items</h3>
                            <span class="count">{{ $menu->children->count() }} items</span>
                        </div>
                        <ul class="children-list">
                            @foreach($menu->children as $child)
                                <li>
                                    <div class="child-info">
                                        <span class="child-icon {{ $child->icon ? 'has-icon' : '' }}">
                                            @if($child->icon)
                                                <i class="{{ $child->icon }}"></i>
                                            @else
                                                <i class="fas fa-file"></i>
                                            @endif
                                        </span>
                                        <a href="{{ route('superadmin.menus.show', $child) }}">
                                            {{ $child->name }}
                                        </a>
                                    </div>
                                    <div class="child-meta">
                                        <span class="badge badge-{{ $child->is_active ? 'success' : 'danger' }} badge-sm">
                                            {{ $child->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="badge badge-secondary badge-sm">
                                            Order: {{ $child->display_order ?? 0 }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Meta Information -->
                <div class="meta-grid">
                    <div class="meta-item">
                        <span class="meta-label"><i class="far fa-calendar-plus"></i> Created At</span>
                        <span class="meta-value">{{ $menu->created_at ? $menu->created_at->format('d M Y, h:i A') : '-' }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label"><i class="far fa-calendar-edit"></i> Updated At</span>
                        <span class="meta-value">{{ $menu->updated_at ? $menu->updated_at->format('d M Y, h:i A') : '-' }}</span>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="detail-card-footer">
                <a href="{{ route('superadmin.menus.edit', $menu) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button type="button" class="btn btn-danger delete-btn" 
                        data-id="{{ $menu->id }}" 
                        data-name="{{ $menu->name }}">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
                <a href="{{ route('superadmin.menus.index') }}" class="btn btn-secondary" style="margin-left:auto;">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>

        </div>

    </div>

    <script>
        $(document).ready(function() {

            // ============================================================
            // DELETE BUTTON
            // ============================================================
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                Swal.fire({
                    title: 'Delete Menu Item?',
                    html: `You are about to delete <strong>"${name}"</strong>. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal-modern-premium',
                        title: 'swal-title-premium'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("superadmin.menus.destroy", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    setTimeout(function() {
                                        window.location.href = '{{ route("superadmin.menus.index") }}';
                                    }, 800);
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function() {
                                toastr.error('Failed to delete menu item');
                            }
                        });
                    }
                });
            });

            // ============================================================
            // TOASTR CONFIG
            // ============================================================
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 4000,
                extendedTimeOut: 1000,
                showEasing: 'swing',
                hideEasing: 'linear',
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            };

            @if(session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if(session('error'))
                toastr.error('{{ session('error') }}');
            @endif

        });
    </script>

</body>
</html>