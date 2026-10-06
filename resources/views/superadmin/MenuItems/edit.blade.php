{{-- resources/views/superadmin/menus/edit.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2-bootstrap4.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
            --warning: #f59e0b;
            --warning-dark: #d97706;
            --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
            --secondary: #0f172a;
            --success: #10b981;
            --danger: #ef4444;
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
            max-width: 820px;
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
            background: var(--warning-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.4rem;
            box-shadow: 0 4px 16px rgba(245, 158, 11, 0.35);
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.02em;
        }

        .page-header h1 span {
            color: var(--warning);
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
        .form-card {
            background: #fff;
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .form-card-header {
            padding: 20px 28px;
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border-bottom: 2px solid #fcd34d;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-card-header .header-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: var(--warning-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .form-card-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .form-card-header p {
            font-size: 0.8rem;
            color: #92400e;
            margin: 0;
        }

        .form-card-body {
            padding: 28px 32px 20px;
        }

        .form-card-footer {
            padding: 16px 32px 28px;
            border-top: 1px solid var(--gray-100);
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* ============================================================
           FORM ELEMENTS
           ============================================================ */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 6px;
        }

        .form-group label .required {
            color: var(--danger);
            margin-left: 2px;
        }

        .form-group .menu-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.1rem 0.6rem;
            border-radius: 20px;
            margin-left: 6px;
            vertical-align: middle;
        }

        .menu-badge-parent {
            background: #dbeafe;
            color: #1e40af;
        }

        .menu-badge-child {
            background: #fef3c7;
            color: #92400e;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            color: var(--gray-900);
            background: var(--gray-50);
            transition: var(--transition);
            outline: none;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: var(--warning);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.08);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
        }

        .form-control::placeholder {
            color: var(--gray-400);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            cursor: pointer;
        }

        .input-group {
            display: flex;
            align-items: stretch;
            gap: 0;
        }

        .input-group .input-group-prepend {
            display: flex;
            align-items: center;
        }

        .input-group .input-group-text {
            padding: 0.6rem 1rem;
            background: var(--gray-100);
            border: 2px solid var(--gray-200);
            border-right: none;
            border-radius: var(--radius-sm) 0 0 var(--radius-sm);
            font-size: 0.9rem;
            color: var(--gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 44px;
        }

        .input-group .input-group-text i {
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .input-group .form-control {
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
            border-left: none;
        }

        .input-group .form-control:focus + .input-group-text,
        .input-group .form-control:focus ~ .input-group-text {
            border-color: var(--warning);
        }

        .form-text {
            display: block;
            font-size: 0.75rem;
            color: var(--gray-400);
            margin-top: 4px;
        }

        .form-text i {
            margin-right: 4px;
        }

        .form-text a {
            color: var(--primary);
            text-decoration: none;
        }

        .form-text a:hover {
            text-decoration: underline;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.75rem;
            color: var(--danger);
            margin-top: 4px;
        }

        /* ============================================================
           SWITCH
           ============================================================ */
        .form-group-switch {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
        }

        .custom-switch {
            position: relative;
            width: 48px;
            height: 26px;
            flex-shrink: 0;
        }

        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .custom-switch .switch-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: var(--gray-300);
            border-radius: 40px;
            transition: var(--transition);
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.08);
        }

        .custom-switch .switch-slider::before {
            content: '';
            position: absolute;
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background: #fff;
            border-radius: 50%;
            transition: var(--transition);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .custom-switch input:checked + .switch-slider {
            background: var(--warning-gradient);
        }

        .custom-switch input:checked + .switch-slider::before {
            transform: translateX(22px);
        }

        .switch-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-700);
            cursor: pointer;
            user-select: none;
        }

        .switch-label .text-muted {
            font-weight: 400;
            color: var(--gray-400);
            font-size: 0.8rem;
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

        .btn-warning:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.35);
            color: #fff;
        }

        /* ============================================================
           SELECT2 OVERRIDES
           ============================================================ */
        .select2-container--bootstrap4 .select2-selection--multiple {
            min-height: 46px !important;
            border: 2px solid var(--gray-200) !important;
            border-radius: var(--radius-sm) !important;
            background: var(--gray-50) !important;
            padding: 4px 8px !important;
        }

        .select2-container--bootstrap4 .select2-selection--multiple:focus,
        .select2-container--bootstrap4 .select2-selection--multiple:focus-within {
            border-color: var(--warning) !important;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.08) !important;
            background: #fff !important;
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background: var(--warning-gradient) !important;
            border: none !important;
            border-radius: 20px !important;
            color: #fff !important;
            padding: 2px 12px !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255,255,255,0.7) !important;
            margin-right: 6px !important;
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff !important;
        }

        .select2-container--bootstrap4 .select2-dropdown {
            border: 2px solid var(--gray-200) !important;
            border-radius: var(--radius-sm) !important;
            margin-top: 2px !important;
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted[aria-selected] {
            background: var(--warning-gradient) !important;
        }

        .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field {
            border: 2px solid var(--gray-200) !important;
            border-radius: var(--radius-sm) !important;
            padding: 0.4rem 0.8rem !important;
            font-size: 0.85rem !important;
        }

        .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--warning) !important;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.08) !important;
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 768px) {
            body { padding: 16px; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .page-header .btn-back { align-self: stretch; justify-content: center; }
            .form-card-header { padding: 16px 20px; flex-wrap: wrap; }
            .form-card-body { padding: 20px; }
            .form-card-footer { padding: 16px 20px 24px; flex-direction: column; }
            .form-card-footer .btn { width: 100%; justify-content: center; }
            .form-group-switch { flex-wrap: wrap; }
        }

        @media (max-width: 480px) {
            body { padding: 12px; }
            .page-header h1 { font-size: 1.2rem; }
            .page-header .logo-icon { width: 40px; height: 40px; font-size: 1.1rem; }
            .form-card-header { padding: 12px 16px; }
            .form-card-header .header-icon { width: 34px; height: 34px; font-size: 0.9rem; }
            .form-card-header h2 { font-size: 0.95rem; }
            .form-card-body { padding: 16px; }
            .form-control { font-size: 0.85rem; padding: 0.5rem 0.8rem; }
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

        .form-card {
            animation: slideUp 0.5s ease;
        }

        /* ============================================================
           STATUS INDICATOR
           ============================================================ */
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-indicator.active {
            background: #ecfdf5;
            color: #065f46;
        }

        .status-indicator.inactive {
            background: #fef2f2;
            color: #991b1b;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-indicator.active .status-dot {
            background: #10b981;
        }

        .status-indicator.inactive .status-dot {
            background: #ef4444;
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
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <div>
                    <h1>Menu <span>Manager</span></h1>
                    <span class="subtitle">Edit existing menu item</span>
                </div>
            </div>
            <a href="{{ route('superadmin.menus.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Menu List
            </a>
        </div>

        <!-- ============================================================
        FORM CARD
        ============================================================ -->
        <div class="form-card">

            <!-- Header -->
            <div class="form-card-header">
                <div class="header-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h2>Edit Menu Item</h2>
                    <p>Update the details of "{{ $menu->name }}"</p>
                </div>
                <div style="margin-left: auto;">
                    <span class="status-indicator {{ $menu->is_active ? 'active' : 'inactive' }}">
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
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('superadmin.menus.update', $menu) }}" id="menuForm">
                @csrf
                @method('PUT')

                <div class="form-card-body">

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Menu Name <span class="required">*</span></label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="Enter menu name"
                               value="{{ old('name', $menu->name) }}"
                               required
                               autofocus>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Route -->
                    <div class="form-group">
                        <label for="route">Route / URL</label>
                        <input type="text" 
                               name="route" 
                               id="route" 
                               class="form-control @error('route') is-invalid @enderror" 
                               placeholder="e.g., /admin/dashboard or dashboard.index"
                               value="{{ old('route', $menu->route) }}">
                        <span class="form-text">
                            <i class="fas fa-info-circle"></i> 
                            Leave blank for parent menu items or use route names
                        </span>
                        @error('route')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Icon -->
                    <div class="form-group">
                        <label for="icon">Icon</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="iconPreview">
                                    <i class="{{ old('icon', $menu->icon ?? 'fas fa-circle') }}"></i>
                                </span>
                            </div>
                            <input type="text" 
                                   name="icon" 
                                   id="icon" 
                                   class="form-control @error('icon') is-invalid @enderror" 
                                   placeholder="e.g., fas fa-user"
                                   value="{{ old('icon', $menu->icon ?? 'fas fa-circle') }}">
                        </div>
                        <span class="form-text">
                            <i class="fas fa-info-circle"></i> 
                            <a href="https://fontawesome.com/icons" target="_blank">Browse FontAwesome Icons</a>
                        </span>
                        @error('icon')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Parent Menu -->
                    <div class="form-group">
                        <label for="parent_id">Parent Menu</label>
                        <select name="parent_id" 
                                id="parent_id" 
                                class="form-control @error('parent_id') is-invalid @enderror">
                            <option value="">— None (Top Level) —</option>
                            @foreach($parentMenus as $parent)
                                <option value="{{ $parent->id }}" 
                                    {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}
                                    {{ $parent->id == $menu->id ? 'disabled' : '' }}>
                                    {{ $parent->name }}
                                    @if($parent->id == $menu->id)
                                        (Current)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <span class="form-text">
                            <i class="fas fa-info-circle"></i> 
                            Select a parent to create a sub-menu item
                        </span>
                        @error('parent_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Display Order -->
                    <div class="form-group">
                        <label for="order">Display Order</label>
                        <input type="number" 
                               name="order" 
                               id="order" 
                               class="form-control @error('order') is-invalid @enderror" 
                               placeholder="0"
                               value="{{ old('order', $menu->order ?? 0) }}"
                               min="0">
                        <span class="form-text">
                            <i class="fas fa-info-circle"></i> 
                            Lower numbers appear first in the menu
                        </span>
                        @error('order')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Permission Name -->
                    <div class="form-group">
                        <label for="permission_name">Permission Name</label>
                        <input type="text" 
                               name="permission_name" 
                               id="permission_name" 
                               class="form-control @error('permission_name') is-invalid @enderror" 
                               placeholder="e.g., users.view or admin.access"
                               value="{{ old('permission_name', $menu->permission_name) }}">
                        <span class="form-text">
                            <i class="fas fa-info-circle"></i> 
                            Users must have this permission to see the menu
                        </span>
                        @error('permission_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Allowed Roles -->
                    <div class="form-group">
                        <label for="allowed_roles">Allowed Roles</label>
                        <select name="allowed_roles[]" 
                                id="allowed_roles" 
                                class="form-control select2 @error('allowed_roles') is-invalid @enderror" 
                                multiple>
                            @foreach($roles as $role)
                                @php
                                    $allowedRoles = old('allowed_roles', $menu->allowed_roles);
                                    if (is_string($allowedRoles)) {
                                        $allowedRoles = json_decode($allowedRoles, true);
                                    }
                                    $isSelected = $allowedRoles && in_array($role->name, $allowedRoles);
                                @endphp
                                <option value="{{ $role->name }}" 
                                    {{ $isSelected ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        <span class="form-text">
                            <i class="fas fa-info-circle"></i> 
                            Leave empty to allow all roles. Hold <kbd>Ctrl</kbd> (Windows) or <kbd>Cmd</kbd> (Mac) to select multiple.
                        </span>
                        @error('allowed_roles')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" 
                                  id="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Enter a brief description of this menu item">{{ old('description', $menu->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Is Active Switch -->
                    <div class="form-group-switch">
                        <div class="custom-switch">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1"
                                   {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="switch-slider"></label>
                        </div>
                        <label for="is_active" class="switch-label">
                            Active
                            <span class="text-muted">(Show in menu)</span>
                        </label>
                    </div>

                </div>

                <!-- Footer -->
                <div class="form-card-footer">
                    <button type="submit" class="btn btn-warning" id="submitBtn">
                        <i class="fas fa-save"></i> Update Menu Item
                    </button>
                    <a href="{{ route('superadmin.menus.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="button" class="btn btn-danger" style="margin-left: auto;" id="deleteBtn">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </div>

            </form>

        </div>

    </div>

    <script>
        $(document).ready(function() {

            // ============================================================
            // SELECT2
            // ============================================================
            $('#allowed_roles').select2({
                theme: 'bootstrap4',
                placeholder: 'Select roles...',
                allowClear: true,
                closeOnSelect: false,
                width: '100%'
            });

            // ============================================================
            // ICON PREVIEW
            // ============================================================
            $('#icon').on('input keyup change', function() {
                var iconClass = $(this).val().trim();
                if (iconClass === '') {
                    iconClass = 'fas fa-circle';
                }
                $('#iconPreview i').attr('class', iconClass);
            });

            // ============================================================
            // FORM VALIDATION
            // ============================================================
            $('#menuForm').on('submit', function(e) {
                var name = $('#name').val().trim();
                if (name === '') {
                    e.preventDefault();
                    $('#name').addClass('is-invalid');
                    toastr.error('Menu name is required');
                    $('#name').focus();
                    return false;
                }
                return true;
            });

            // ============================================================
            // REMOVE ERROR ON INPUT
            // ============================================================
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });

            // ============================================================
            // PREVENT DOUBLE SUBMIT
            // ============================================================
            $('#menuForm').on('submit', function() {
                $('#submitBtn').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Updating...');
            });

            // ============================================================
            // DELETE BUTTON
            // ============================================================
            $('#deleteBtn').on('click', function() {
                Swal.fire({
                    title: 'Delete Menu Item?',
                    html: `You are about to delete <strong>"{{ $menu->name }}"</strong>. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("superadmin.menus.destroy", $menu) }}',
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(r) {
                                if (r.success) {
                                    toastr.success(r.message);
                                    setTimeout(() => {
                                        window.location.href = '{{ route("superadmin.menus.index") }}';
                                    }, 800);
                                } else {
                                    toastr.error(r.message);
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

            // ============================================================
            // SHOW ERRORS FROM SESSION
            // ============================================================
            @if($errors->any())
                @foreach($errors->all() as $error)
                    toastr.error('{{ $error }}');
                @endforeach
            @endif

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>