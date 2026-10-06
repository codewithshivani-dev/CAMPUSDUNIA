{{-- resources/views/superadmin/menus/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management</title>
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
            --secondary: #0f172a;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
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
        }

        /* ============================================================
           LAYOUT
           ============================================================ */
        .app-container {
            max-width: 1480px;
            margin: 0 auto;
        }

        /* ============================================================
           HEADER
           ============================================================ */
        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .app-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .app-logo {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.4rem;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.35);
        }

        .app-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.02em;
        }

        .app-title span {
            color: var(--primary);
        }

        .app-subtitle {
            font-size: 0.8rem;
            color: var(--gray-400);
            font-weight: 500;
        }

        .app-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-time {
            font-size: 0.8rem;
            color: var(--gray-500);
            background: #fff;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
        }

        /* ============================================================
           STATISTICS
           ============================================================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            position: relative;
            border-radius: var(--radius-lg);
            padding: 20px 24px;
            overflow: hidden;
            transition: var(--transition);
            cursor: default;
            background: #fff;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0,0,0,0.03);
            min-height: 100px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card:nth-child(1)::before { background: linear-gradient(90deg, #4f46e5, #818cf8); }
        .stat-card:nth-child(2)::before { background: linear-gradient(90deg, #10b981, #34d399); }
        .stat-card:nth-child(3)::before { background: linear-gradient(90deg, #ef4444, #f87171); }
        .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

        .stat-card .stat-icon-box {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            color: #fff;
        }

        .stat-card:nth-child(1) .stat-icon-box { background: var(--primary-gradient); }
        .stat-card:nth-child(2) .stat-icon-box { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-card:nth-child(3) .stat-icon-box { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .stat-card:nth-child(4) .stat-icon-box { background: linear-gradient(135deg, #f59e0b, #d97706); }

        .stat-card .stat-info {
            flex: 1;
            min-width: 0;
        }

        .stat-card .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
            color: var(--gray-400);
            display: block;
        }

        .stat-card .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .stat-card .stat-change {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.15rem 0.6rem;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }

        .stat-change.up { color: #10b981; background: #ecfdf5; }
        .stat-change.down { color: #ef4444; background: #fef2f2; }
        .stat-change.neutral { color: var(--gray-500); background: var(--gray-100); }

        .stat-card .stat-bg-pattern {
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            opacity: 0.04;
            pointer-events: none;
        }

        .stat-card:nth-child(1) .stat-bg-pattern { background: #4f46e5; }
        .stat-card:nth-child(2) .stat-bg-pattern { background: #10b981; }
        .stat-card:nth-child(3) .stat-bg-pattern { background: #ef4444; }
        .stat-card:nth-child(4) .stat-bg-pattern { background: #f59e0b; }

        /* ============================================================
           MAIN CARD
           ============================================================ */
        .main-card {
            background: #fff;
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .main-card-header {
            padding: 20px 28px;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .main-card-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .main-card-header .header-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .main-card-header h2 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .main-card-header p {
            font-size: 0.8rem;
            color: var(--gray-400);
            margin: 0;
        }

        .main-card-header .header-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* ============================================================
           BUTTONS
           ============================================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 1.2rem;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
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

        .btn-outline {
            border: 1px solid var(--gray-200);
            color: var(--gray-600);
        }

        .btn-outline:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
        }

        .btn-danger:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.35);
        }

        .btn-danger:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .btn-sm {
            padding: 0.35rem 0.9rem;
            font-size: 0.75rem;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
        }

        /* ============================================================
           TOOLBAR
           ============================================================ */
        .toolbar {
            padding: 20px 28px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .toolbar-search {
            position: relative;
            flex: 1;
            min-width: 200px;
            max-width: 400px;
        }

        .toolbar-search i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.85rem;
            pointer-events: none;
        }

        .toolbar-search input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.8rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            background: var(--gray-50);
            transition: var(--transition);
            color: var(--gray-900);
            outline: none;
        }

        .toolbar-search input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
        }

        .toolbar-search .clear-search {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            display: none;
            font-size: 0.8rem;
            padding: 4px;
        }

        .toolbar-search .clear-search.visible {
            display: block;
        }

        .toolbar-info {
            font-size: 0.8rem;
            color: var(--gray-400);
            font-weight: 500;
            white-space: nowrap;
        }

        .toolbar-info i {
            margin-right: 6px;
        }

        /* ============================================================
           TABLE
           ============================================================ */
        .table-wrap {
            padding: 16px 28px 0;
            overflow-x: auto;
        }

        .table-premium {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
            min-width: 1100px;
        }

        .table-premium thead th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            border-bottom: 2px solid var(--gray-200);
            background: var(--gray-50);
            position: sticky;
            top: 0;
            z-index: 10;
            white-space: nowrap;
        }

        .table-premium tbody tr {
            transition: var(--transition);
        }

        .table-premium tbody tr:hover {
            background: var(--gray-50);
        }

        .table-premium tbody td {
            padding: 0.7rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .table-premium tbody tr:last-child td {
            border-bottom: none;
        }

        .table-premium .row-child.collapsed {
            display: none;
        }

        .table-premium .row-child.expanded {
            display: table-row;
            animation: fadeSlide 0.3s ease;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateX(-8px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Column widths */
        .col-select { width: 40px; }
        .col-index { width: 40px; }
        .col-order { width: 80px; }
        .col-icon { width: 70px; }
        .col-status { width: 140px; }
        .col-actions { width: 140px; }

        /* ============================================================
           CUSTOM CHECKBOX
           ============================================================ */
        .checkbox-custom {
            position: relative;
            display: inline-block;
        }

        .checkbox-custom input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .checkbox-custom label {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid var(--gray-300);
            background: #fff;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .checkbox-custom label::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 10px;
            height: 10px;
            background: #fff;
            border-radius: 3px;
            transition: var(--transition);
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }

        .checkbox-custom input:checked + label {
            border-color: var(--primary);
            background: var(--primary);
        }

        .checkbox-custom input:checked + label::after {
            transform: translate(-50%, -50%) scale(1);
        }

        .checkbox-custom label:hover {
            border-color: var(--primary);
        }

        /* ============================================================
           MENU NAME
           ============================================================ */
        .menu-name-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .menu-name-parent {
            font-weight: 700;
            color: var(--gray-900);
            font-size: 0.95rem;
        }

        .menu-name-child {
            font-weight: 400;
            color: var(--gray-600);
            font-size: 0.88rem;
        }

        .indent-line {
            display: inline-block;
            width: 28px;
            flex-shrink: 0;
        }

        .connector-line {
            display: inline-block;
            width: 18px;
            height: 2px;
            background: linear-gradient(90deg, var(--gray-300), transparent);
            flex-shrink: 0;
            margin-right: 4px;
            position: relative;
        }

        .connector-line::after {
            content: '';
            position: absolute;
            right: -4px;
            top: -3px;
            width: 6px;
            height: 6px;
            border-right: 2px solid var(--gray-300);
            border-bottom: 2px solid var(--gray-300);
            transform: rotate(-45deg);
        }

        .child-count {
            background: var(--gray-100);
            color: var(--gray-600);
            font-weight: 700;
            font-size: 0.6rem;
            padding: 0.1rem 0.6rem;
            border-radius: 20px;
            letter-spacing: 0.02em;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--gray-400);
            padding: 0.1rem 0.3rem;
            font-size: 0.7rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .toggle-btn:hover {
            color: var(--primary);
            transform: scale(1.1);
        }

        .toggle-btn.expanded i {
            transform: rotate(180deg);
        }

        .toggle-btn i {
            transition: transform 0.3s ease;
        }

        /* ============================================================
           ORDER BADGE
           ============================================================ */
        .order-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            padding: 0.15rem 0.5rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--gray-600);
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            font-variant-numeric: tabular-nums;
        }

        .order-badge.child-order {
            background: var(--gray-50);
            color: var(--gray-400);
            border-color: var(--gray-200);
        }

        /* ============================================================
           ICON
           ============================================================ */
        .icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            background: var(--gray-100);
            color: var(--gray-600);
            font-size: 1rem;
            transition: var(--transition);
        }

        .icon-wrap:hover {
            background: #eef2ff;
            color: var(--primary);
            transform: scale(1.05);
        }

        .empty-icon {
            color: var(--gray-400);
            font-size: 0.8rem;
        }

        /* ============================================================
           ROUTE
           ============================================================ */
        .route-link {
            text-decoration: none;
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
        }

        .route-link:hover {
            color: var(--primary-dark);
        }

        .route-code {
            background: var(--gray-100);
            padding: 0.1rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            color: var(--gray-700);
            border: 1px solid var(--gray-200);
            font-family: 'SF Mono', 'Fira Code', monospace;
        }

        .route-external {
            font-size: 0.6rem;
            opacity: 0.5;
        }

        .empty-route {
            color: var(--gray-400);
            font-size: 0.8rem;
        }

        /* ============================================================
           PERMISSION
           ============================================================ */
        .permission-badge {
            background: #fef3c7;
            color: #92400e;
            font-weight: 600;
            font-size: 0.65rem;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            display: inline-block;
        }

        .empty-permission {
            color: var(--gray-400);
            font-size: 0.8rem;
        }

        /* ============================================================
           ROLES
           ============================================================ */
        .role-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .role-pill {
            background: #dbeafe;
            color: #1e40af;
            font-weight: 500;
            font-size: 0.6rem;
            padding: 0.15rem 0.6rem;
            border-radius: 20px;
        }

        .role-pill-all {
            background: var(--gray-200);
            color: var(--gray-600);
            font-weight: 600;
        }

        /* ============================================================
           STATUS TOGGLE
           ============================================================ */
        .status-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .toggle-switch .toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: var(--gray-300);
            border-radius: 40px;
            transition: var(--transition);
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.08);
        }

        .toggle-switch .toggle-slider::before {
            content: '';
            position: absolute;
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background: #fff;
            border-radius: 50%;
            transition: var(--transition);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .toggle-switch input:checked + .toggle-slider {
            background: var(--primary-gradient);
        }

        .toggle-switch input:checked + .toggle-slider::before {
            transform: translateX(20px);
        }

        .toggle-switch input:disabled + .toggle-slider {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .status-label {
            font-size: 0.7rem;
            font-weight: 700;
            min-width: 48px;
            letter-spacing: 0.03em;
        }

        .status-label.active { color: var(--success); }
        .status-label.inactive { color: var(--danger); }

        /* ============================================================
           ACTIONS
           ============================================================ */
        .action-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
        }

        .action-view {
            background: #eef2ff;
            color: var(--primary);
        }

        .action-view:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
        }

        .action-edit {
            background: #fef3c7;
            color: #d97706;
        }

        .action-edit:hover {
            background: #d97706;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(217, 119, 6, 0.3);
        }

        .action-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .action-delete:hover {
            background: #dc2626;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(220, 38, 38, 0.3);
        }

        /* ============================================================
           BULK ACTIONS
           ============================================================ */
        .bulk-actions {
            padding: 16px 28px 24px;
            border-top: 2px solid var(--gray-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 4px;
        }

        .bulk-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .bulk-count {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--gray-600);
        }

        .bulk-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--gray-300);
            transition: var(--transition);
        }

        .bulk-indicator.active {
            background: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        }

        .bulk-right {
            font-size: 0.8rem;
            color: var(--gray-400);
            font-weight: 500;
        }

        .bulk-right i {
            margin-right: 6px;
        }

        /* ============================================================
           ALERTS
           ============================================================ */
        .alert {
            padding: 14px 20px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: #ecfdf5;
            border-left: 4px solid var(--success);
            color: #065f46;
        }

        .alert-danger {
            background: #fef2f2;
            border-left: 4px solid var(--danger);
            color: #991b1b;
        }

        .alert i {
            font-size: 1.2rem;
        }

        .alert-success i { color: var(--success); }
        .alert-danger i { color: var(--danger); }

        .alert .alert-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--gray-400);
            cursor: pointer;
            padding: 0 4px;
            transition: var(--transition);
        }

        .alert .alert-close:hover {
            color: var(--gray-600);
        }

        /* ============================================================
           INDEX BADGE
           ============================================================ */
        .index-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: var(--radius-sm);
            background: var(--gray-100);
            color: var(--gray-600);
            font-weight: 600;
            font-size: 0.75rem;
        }

        .index-badge-child {
            background: var(--gray-50);
            color: var(--gray-400);
            font-weight: 400;
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 1024px) {
            body { padding: 16px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            body { padding: 12px; }
            .app-header { flex-direction: column; align-items: flex-start; }
            .app-header-right { width: 100%; }
            .header-time { width: 100%; text-align: center; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
            .stat-card { padding: 16px; min-height: 80px; }
            .stat-card .stat-number { font-size: 1.4rem; }
            .stat-card .stat-icon-box { width: 44px; height: 44px; font-size: 1.1rem; }
            .main-card-header { padding: 16px 20px; flex-direction: column; align-items: flex-start; }
            .main-card-header .header-actions { width: 100%; }
            .main-card-header .header-actions .btn { flex: 1; justify-content: center; }
            .toolbar { padding: 16px 20px 0; flex-direction: column; align-items: stretch; }
            .toolbar-search { max-width: 100%; }
            .table-wrap { padding: 12px 16px 0; }
            .bulk-actions { padding: 16px 20px 20px; flex-direction: column; align-items: stretch; }
            .bulk-left { flex-wrap: wrap; }
            .bulk-left .btn { flex: 1; justify-content: center; }
            .table-premium thead { display: none; }
            .table-premium tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0.75rem;
                border-bottom: 1px solid var(--gray-100);
            }
            .table-premium tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                font-size: 0.65rem;
                color: var(--gray-400);
                text-transform: uppercase;
                letter-spacing: 0.04em;
            }
            .table-premium tbody td:last-child { border-bottom: 2px solid var(--gray-200); }
            .table-premium tbody td:nth-child(1)::before { content: ''; }
            .table-premium tbody td:nth-child(2)::before { content: '#'; }
            .table-premium tbody td:nth-child(3)::before { content: 'Order'; }
            .table-premium tbody td:nth-child(4)::before { content: 'Menu'; }
            .table-premium tbody td:nth-child(5)::before { content: 'Icon'; }
            .table-premium tbody td:nth-child(6)::before { content: 'Route'; }
            .table-premium tbody td:nth-child(7)::before { content: 'Permission'; }
            .table-premium tbody td:nth-child(8)::before { content: 'Roles'; }
            .table-premium tbody td:nth-child(9)::before { content: 'Status'; }
            .table-premium tbody td:nth-child(10)::before { content: 'Actions'; }
            .indent-line { width: 14px; }
            .connector-line { width: 10px; }
            .action-group { gap: 4px; }
            .action-btn { width: 30px; height: 30px; font-size: 0.7rem; }
            .toggle-switch { width: 38px; height: 20px; }
            .toggle-switch .toggle-slider::before { height: 14px; width: 14px; left: 3px; bottom: 3px; }
            .toggle-switch input:checked + .toggle-slider::before { transform: translateX(18px); }
            .status-label { font-size: 0.6rem; min-width: 40px; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
            .stat-card { padding: 12px; min-height: 70px; }
            .stat-card .stat-number { font-size: 1.1rem; }
            .stat-card .stat-icon-box { width: 36px; height: 36px; font-size: 0.9rem; }
            .stat-card .stat-label { font-size: 0.6rem; }
            .stat-card .stat-change { font-size: 0.6rem; padding: 0.1rem 0.4rem; }
            .main-card-header { padding: 12px 16px; }
            .main-card-header h2 { font-size: 1rem; }
            .btn { padding: 0.35rem 0.8rem; font-size: 0.7rem; }
            .btn span { display: none; }
            .btn i { margin: 0; }
            .table-wrap { padding: 8px 12px 0; }
            .table-premium tbody td { padding: 0.4rem 0.5rem; font-size: 0.75rem; }
            .bulk-actions { padding: 12px 16px 16px; }
            .bulk-count { font-size: 0.75rem; }
        }

        /* ============================================================
           TOASTR CUSTOM
           ============================================================ */
        .toast-success { background: linear-gradient(135deg, #10b981, #059669) !important; }
        .toast-error { background: linear-gradient(135deg, #ef4444, #dc2626) !important; }
        .toast-warning { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
        .toast-info { background: linear-gradient(135deg, #4f46e5, #7c3aed) !important; }

        /* ============================================================
           SWEETALERT CUSTOM
           ============================================================ */
        .swal2-popup {
            border-radius: var(--radius-lg) !important;
            padding: 2rem !important;
        }
        .swal2-title {
            font-size: 1.3rem !important;
            font-weight: 700 !important;
            color: var(--gray-900) !important;
        }
        .swal2-html-container {
            color: var(--gray-600) !important;
            font-size: 0.95rem !important;
        }
        .swal2-confirm {
            border-radius: var(--radius-sm) !important;
            font-weight: 600 !important;
            padding: 0.5rem 1.5rem !important;
        }
        .swal2-cancel {
            border-radius: var(--radius-sm) !important;
            font-weight: 600 !important;
            padding: 0.5rem 1.5rem !important;
        }
    </style>
</head>
<body>
    <div class="app-container">

        <!-- ============================================================
        HEADER
        ============================================================ -->
        <header class="app-header">
            <div class="app-header-left">
                <div class="app-logo">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h1 class="app-title">Menu <span>Manager</span></h1>
                    <span class="app-subtitle">Navigation structure control</span>
                </div>
            </div>
            <div class="app-header-right">
                <span class="header-time">
                    <i class="far fa-calendar-alt"></i> {{ date('M d, Y') }}
                </span>
            </div>
        </header>

        <!-- ============================================================
        STATISTICS
        ============================================================ -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-bg-pattern"></div>
                <div class="stat-icon-box"><i class="fas fa-list-ul"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Total Menus</span>
                    <span class="stat-number">{{ $statistics['total'] }}</span>
                    <span class="stat-change up"><i class="fas fa-arrow-up"></i> 12%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-bg-pattern"></div>
                <div class="stat-icon-box"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Active</span>
                    <span class="stat-number">{{ $statistics['active'] }}</span>
                    <span class="stat-change up"><i class="fas fa-arrow-up"></i> 8%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-bg-pattern"></div>
                <div class="stat-icon-box"><i class="fas fa-times-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Inactive</span>
                    <span class="stat-number">{{ $statistics['inactive'] }}</span>
                    <span class="stat-change down"><i class="fas fa-arrow-down"></i> 3%</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-bg-pattern"></div>
                <div class="stat-icon-box"><i class="fas fa-sitemap"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Parent / Child</span>
                    <span class="stat-number">{{ $statistics['parents'] }} / {{ $statistics['children'] }}</span>
                    <span class="stat-change neutral"><i class="fas fa-minus"></i> 0%</span>
                </div>
            </div>
        </div>

        <!-- ============================================================
        MAIN CARD
        ============================================================ -->
        <div class="main-card">

            <!-- Header -->
            <div class="main-card-header">
                <div class="main-card-header-left">
                    <div class="header-icon-wrap">
                        <i class="fas fa-bars"></i>
                    </div>
                    <div>
                        <h2>Menu Structure</h2>
                        <p>Manage your application navigation hierarchy</p>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline btn-sm" id="expandAllBtn">
                        <i class="fas fa-expand-alt"></i> Expand
                    </button>
                    <button class="btn btn-outline btn-sm" id="collapseAllBtn">
                        <i class="fas fa-compress-alt"></i> Collapse
                    </button>
                    <a href="{{ route('superadmin.menus.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i> Add New
                    </a>
                </div>
            </div>

            <!-- Body -->
            <div style="padding: 0 28px 20px;">

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                        <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                <!-- Toolbar -->
                <div class="toolbar">
                    <div class="toolbar-search">
                        <i class="fas fa-search"></i>
                        <input type="text" id="menuSearch" placeholder="Search menus by name, route, permission...">
                        <button class="clear-search" id="clearSearch"><i class="fas fa-times"></i></button>
                    </div>
                    <span class="toolbar-info">
                        <i class="fas fa-database"></i> {{ count($menuItems) }} items
                    </span>
                </div>

                <!-- Table -->
                <div class="table-wrap">
                    <table class="table-premium" id="menuTable">
                        <thead>
                            <tr>
                                <th class="col-select">
                                    <div class="checkbox-custom">
                                        <input type="checkbox" id="selectAll">
                                        <label for="selectAll"></label>
                                    </div>
                                </th>
                                <th class="col-index">#</th>
                                <th class="col-order">
                                    <i class="fas fa-sort-numeric-down"></i> Order
                                </th>
                                <th>Menu Name</th>
                                <th class="col-icon">Icon</th>
                                <th>Route / URL</th>
                                <th>Permission</th>
                                <th>Roles</th>
                                <th class="col-status">Status</th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($menuItems as $item)
                                @if(!$item->parent_id)
                                    <tr class="row-parent" data-id="{{ $item->id }}" data-parent="0">
                                        <td>
                                            <div class="checkbox-custom">
                                                <input type="checkbox" class="menu-checkbox" id="menu_{{ $item->id }}" value="{{ $item->id }}">
                                                <label for="menu_{{ $item->id }}"></label>
                                            </div>
                                        </td>
                                        <td><span class="index-badge">{{ $counter++ }}</span></td>
                                        <td>
                                            <span class="order-badge">
                                                <i class="fas fa-hashtag" style="font-size:0.5rem;margin-right:2px;opacity:0.5;"></i>
                                                {{ $item->order ?? 0 }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="menu-name-wrap">
                                                <span class="menu-name-parent">{{ $item->name }}</span>
                                                @if($item->children->count() > 0)
                                                    <span class="child-count">{{ $item->children->count() }}</span>
                                                    <button class="toggle-btn" data-id="{{ $item->id }}">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->icon)
                                                <span class="icon-wrap"><i class="{{ $item->icon }}"></i></span>
                                            @else
                                                <span class="empty-icon">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->route)
                                                <a href="{{ url($item->route) }}" target="_blank" class="route-link">
                                                    <code class="route-code">{{ $item->route }}</code>
                                                    <i class="fas fa-external-link-alt route-external"></i>
                                                </a>
                                            @else
                                                <span class="empty-route">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->permission_name)
                                                <span class="permission-badge">{{ $item->permission_name }}</span>
                                            @else
                                                <span class="empty-permission">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $roles = is_string($item->allowed_roles) 
                                                    ? json_decode($item->allowed_roles, true) 
                                                    : $item->allowed_roles;
                                            @endphp
                                            @if($roles && count($roles) > 0)
                                                <div class="role-pills">
                                                    @foreach($roles as $role)
                                                        <span class="role-pill">{{ $role }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="role-pill role-pill-all">All</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="status-toggle">
                                                <div class="toggle-switch">
                                                    <input type="checkbox" class="toggle-status" 
                                                           id="status_{{ $item->id }}" 
                                                           data-id="{{ $item->id }}"
                                                           {{ $item->is_active ? 'checked' : '' }}>
                                                    <label for="status_{{ $item->id }}" class="toggle-slider"></label>
                                                </div>
                                                <span class="status-label {{ $item->is_active ? 'active' : 'inactive' }}">
                                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('superadmin.menus.show', $item) }}" 
                                                   class="action-btn action-view" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('superadmin.menus.edit', $item) }}" 
                                                   class="action-btn action-edit" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <button type="button" 
                                                        class="action-btn action-delete delete-btn" 
                                                        data-id="{{ $item->id }}" 
                                                        data-name="{{ $item->name }}"
                                                        title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    @foreach($item->children as $child)
                                        <tr class="row-child child-of-{{ $item->id }} collapsed" 
                                            data-id="{{ $child->id }}" 
                                            data-parent="{{ $item->id }}">
                                            <td>
                                                <div class="checkbox-custom">
                                                    <input type="checkbox" class="menu-checkbox" id="menu_{{ $child->id }}" value="{{ $child->id }}">
                                                    <label for="menu_{{ $child->id }}"></label>
                                                </div>
                                            </td>
                                            <td><span class="index-badge index-badge-child">{{ $counter++ }}</span></td>
                                            <td>
                                                <span class="order-badge child-order">
                                                    <i class="fas fa-hashtag" style="font-size:0.5rem;margin-right:2px;opacity:0.4;"></i>
                                                    {{ $child->order ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="menu-name-wrap">
                                                    <span class="indent-line"></span>
                                                    <span class="connector-line"></span>
                                                    <span class="menu-name-child">{{ $child->name }}</span>
                                                    @if($child->children->count() > 0)
                                                        <span class="child-count">{{ $child->children->count() }}</span>
                                                        <button class="toggle-btn" data-id="{{ $child->id }}">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($child->icon)
                                                    <span class="icon-wrap"><i class="{{ $child->icon }}"></i></span>
                                                @else
                                                    <span class="empty-icon">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($child->route)
                                                    <a href="{{ url($child->route) }}" target="_blank" class="route-link">
                                                        <code class="route-code">{{ $child->route }}</code>
                                                        <i class="fas fa-external-link-alt route-external"></i>
                                                    </a>
                                                @else
                                                    <span class="empty-route">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($child->permission_name)
                                                    <span class="permission-badge">{{ $child->permission_name }}</span>
                                                @else
                                                    <span class="empty-permission">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $childRoles = is_string($child->allowed_roles) 
                                                        ? json_decode($child->allowed_roles, true) 
                                                        : $child->allowed_roles;
                                                @endphp
                                                @if($childRoles && count($childRoles) > 0)
                                                    <div class="role-pills">
                                                        @foreach($childRoles as $role)
                                                            <span class="role-pill">{{ $role }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="role-pill role-pill-all">All</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="status-toggle">
                                                    <div class="toggle-switch">
                                                        <input type="checkbox" class="toggle-status" 
                                                               id="status_{{ $child->id }}" 
                                                               data-id="{{ $child->id }}"
                                                               {{ $child->is_active ? 'checked' : '' }}>
                                                        <label for="status_{{ $child->id }}" class="toggle-slider"></label>
                                                    </div>
                                                    <span class="status-label {{ $child->is_active ? 'active' : 'inactive' }}">
                                                        {{ $child->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-group">
                                                    <a href="{{ route('superadmin.menus.show', $child) }}" 
                                                       class="action-btn action-view" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('superadmin.menus.edit', $child) }}" 
                                                       class="action-btn action-edit" title="Edit">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="action-btn action-delete delete-btn" 
                                                            data-id="{{ $child->id }}" 
                                                            data-name="{{ $child->name }}"
                                                            title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        @foreach($child->children as $grandchild)
                                            <tr class="row-child child-of-{{ $child->id }} collapsed" 
                                                data-id="{{ $grandchild->id }}" 
                                                data-parent="{{ $child->id }}">
                                                <td>
                                                    <div class="checkbox-custom">
                                                        <input type="checkbox" class="menu-checkbox" id="menu_{{ $grandchild->id }}" value="{{ $grandchild->id }}">
                                                        <label for="menu_{{ $grandchild->id }}"></label>
                                                    </div>
                                                </td>
                                                <td><span class="index-badge index-badge-child">{{ $counter++ }}</span></td>
                                                <td>
                                                    <span class="order-badge child-order">
                                                        <i class="fas fa-hashtag" style="font-size:0.5rem;margin-right:2px;opacity:0.4;"></i>
                                                        {{ $grandchild->display_order ?? 0 }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="menu-name-wrap">
                                                        <span class="indent-line"></span>
                                                        <span class="indent-line"></span>
                                                        <span class="connector-line"></span>
                                                        <span class="menu-name-child">{{ $grandchild->name }}</span>
                                                        @if($grandchild->children->count() > 0)
                                                            <span class="child-count">{{ $grandchild->children->count() }}</span>
                                                            <button class="toggle-btn" data-id="{{ $grandchild->id }}">
                                                                <i class="fas fa-chevron-down"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($grandchild->icon)
                                                        <span class="icon-wrap"><i class="{{ $grandchild->icon }}"></i></span>
                                                    @else
                                                        <span class="empty-icon">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($grandchild->route)
                                                        <a href="{{ url($grandchild->route) }}" target="_blank" class="route-link">
                                                            <code class="route-code">{{ $grandchild->route }}</code>
                                                            <i class="fas fa-external-link-alt route-external"></i>
                                                        </a>
                                                    @else
                                                        <span class="empty-route">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($grandchild->permission_name)
                                                        <span class="permission-badge">{{ $grandchild->permission_name }}</span>
                                                    @else
                                                        <span class="empty-permission">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $grandRoles = is_string($grandchild->allowed_roles) 
                                                            ? json_decode($grandchild->allowed_roles, true) 
                                                            : $grandchild->allowed_roles;
                                                    @endphp
                                                    @if($grandRoles && count($grandRoles) > 0)
                                                        <div class="role-pills">
                                                            @foreach($grandRoles as $role)
                                                                <span class="role-pill">{{ $role }}</span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="role-pill role-pill-all">All</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="status-toggle">
                                                        <div class="toggle-switch">
                                                            <input type="checkbox" class="toggle-status" 
                                                                   id="status_{{ $grandchild->id }}" 
                                                                   data-id="{{ $grandchild->id }}"
                                                                   {{ $grandchild->is_active ? 'checked' : '' }}>
                                                            <label for="status_{{ $grandchild->id }}" class="toggle-slider"></label>
                                                        </div>
                                                        <span class="status-label {{ $grandchild->is_active ? 'active' : 'inactive' }}">
                                                            {{ $grandchild->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="action-group">
                                                        <a href="{{ route('superadmin.menus.show', $grandchild) }}" 
                                                           class="action-btn action-view" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('superadmin.menus.edit', $grandchild) }}" 
                                                           class="action-btn action-edit" title="Edit">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="action-btn action-delete delete-btn" 
                                                                data-id="{{ $grandchild->id }}" 
                                                                data-name="{{ $grandchild->name }}"
                                                                title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions -->
                <div class="bulk-actions">
                    <div class="bulk-left">
                        <div class="bulk-indicator" id="bulkIndicator"></div>
                        <span class="bulk-count" id="selectedCount">0 items selected</span>
                        <button class="btn btn-danger btn-sm" id="bulkDeleteBtn" disabled>
                            <i class="fas fa-trash-alt"></i> Delete Selected
                        </button>
                        <button class="btn btn-outline btn-sm" id="bulkCancelBtn" style="display:none;">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                    <div class="bulk-right">
                        <i class="fas fa-list-ul"></i> {{ count($menuItems) }} total items
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {

            // ============================================================
            // SEARCH
            // ============================================================
            const $search = $('#menuSearch');
            const $clear = $('#clearSearch');
            const $table = $('#menuTable');

            $search.on('keyup', function() {
                const q = $(this).val().toLowerCase();
                $table.find('tbody tr').each(function() {
                    const $row = $(this);
                    const match = $row.text().toLowerCase().indexOf(q) > -1;
                    $row.toggle(match);
                    if (match && $row.hasClass('row-child')) {
                        const pid = $row.data('parent');
                        if (pid) $(`.row-parent[data-id="${pid}"]`).show();
                    }
                });
                $clear.toggleClass('visible', q.length > 0);
            });

            $clear.on('click', function() {
                $search.val('').trigger('keyup');
                $(this).removeClass('visible');
            });

            // ============================================================
            // EXPAND / COLLAPSE
            // ============================================================
            $(document).on('click', '.toggle-btn', function() {
                const $btn = $(this);
                const id = $btn.data('id');
                const $children = $(`.child-of-${id}`);
                const expanded = $children.hasClass('expanded');

                if (expanded) {
                    $children.removeClass('expanded').addClass('collapsed');
                    $children.each(function() {
                        const cid = $(this).data('id');
                        $(`.child-of-${cid}`).removeClass('expanded').addClass('collapsed');
                        $(`.toggle-btn[data-id="${cid}"]`).removeClass('expanded');
                    });
                    $btn.removeClass('expanded');
                } else {
                    $children.removeClass('collapsed').addClass('expanded');
                    $btn.addClass('expanded');
                }
            });

            $('#expandAllBtn').on('click', function() {
                $('.row-child').removeClass('collapsed').addClass('expanded');
                $('.toggle-btn').addClass('expanded');
            });

            $('#collapseAllBtn').on('click', function() {
                $('.row-child').removeClass('expanded').addClass('collapsed');
                $('.toggle-btn').removeClass('expanded');
            });

            // ============================================================
            // SELECT ALL
            // ============================================================
            $('#selectAll').on('change', function() {
                $('.menu-checkbox').prop('checked', $(this).prop('checked'));
                updateBulk();
            });

            $(document).on('change', '.menu-checkbox', function() {
                const all = $('.menu-checkbox:checked').length === $('.menu-checkbox').length;
                $('#selectAll').prop('checked', all);
                updateBulk();
            });

            function updateBulk() {
                const count = $('.menu-checkbox:checked').length;
                const $btn = $('#bulkDeleteBtn');
                const $count = $('#selectedCount');
                const $cancel = $('#bulkCancelBtn');
                const $ind = $('#bulkIndicator');

                if (count > 0) {
                    $btn.prop('disabled', false);
                    $btn.html(`<i class="fas fa-trash-alt"></i> Delete Selected (${count})`);
                    $count.text(`${count} items selected`);
                    $cancel.show();
                    $ind.addClass('active');
                } else {
                    $btn.prop('disabled', true);
                    $btn.html(`<i class="fas fa-trash-alt"></i> Delete Selected`);
                    $count.text('0 items selected');
                    $cancel.hide();
                    $ind.removeClass('active');
                }
            }

            $('#bulkCancelBtn').on('click', function() {
                $('.menu-checkbox').prop('checked', false);
                $('#selectAll').prop('checked', false);
                updateBulk();
            });

            // ============================================================
            // TOGGLE STATUS
            // ============================================================
            $(document).on('change', '.toggle-status', function() {
                const $this = $(this);
                const id = $this.data('id');
                const $label = $this.closest('.status-toggle').find('.status-label');
                const checked = $this.prop('checked');

                $.ajax({
                    url: '{{ route("superadmin.menus.toggle-status", ":id") }}'.replace(':id', id),
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', _method: 'PATCH' },
                    beforeSend: function() { $this.prop('disabled', true); },
                    success: function(r) {
                        if (r.success) {
                            if (checked) {
                                $label.removeClass('inactive').addClass('active').text('Active');
                            } else {
                                $label.removeClass('active').addClass('inactive').text('Inactive');
                            }
                            toastr.success(r.message);
                        } else {
                            $this.prop('checked', !checked);
                            toastr.error(r.message);
                        }
                    },
                    error: function() {
                        $this.prop('checked', !checked);
                        toastr.error('Failed to update status');
                    },
                    complete: function() { $this.prop('disabled', false); }
                });
            });

            // ============================================================
            // SINGLE DELETE
            // ============================================================
            $(document).on('click', '.delete-btn', function() {
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
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("superadmin.menus.destroy", ":id") }}'.replace(':id', id),
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(r) {
                                if (r.success) {
                                    toastr.success(r.message);
                                    setTimeout(() => window.location.reload(), 800);
                                } else {
                                    toastr.error(r.message);
                                }
                            },
                            error: function() { toastr.error('Failed to delete'); }
                        });
                    }
                });
            });

            // ============================================================
            // BULK DELETE
            // ============================================================
            $('#bulkDeleteBtn').on('click', function() {
                const ids = [];
                $('.menu-checkbox:checked').each(function() { ids.push($(this).val()); });

                if (ids.length === 0) {
                    toastr.warning('Please select at least one item.');
                    return;
                }

                Swal.fire({
                    title: 'Delete Selected Items?',
                    html: `You are about to delete <strong>${ids.length}</strong> menu item${ids.length > 1 ? 's' : ''}. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, delete all',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const $btn = $('#bulkDeleteBtn');
                        $.ajax({
                            url: '{{ route("superadmin.menus.bulk-delete") }}',
                            type: 'POST',
                            data: { _token: '{{ csrf_token() }}', ids: ids },
                            beforeSend: function() {
                                $btn.prop('disabled', true).html(
                                    '<i class="fas fa-spinner fa-spin"></i> Deleting...'
                                );
                            },
                            success: function(r) {
                                if (r.success) {
                                    toastr.success(r.message);
                                    setTimeout(() => window.location.reload(), 800);
                                } else {
                                    toastr.error(r.message);
                                    $btn.prop('disabled', false).html(
                                        '<i class="fas fa-trash-alt"></i> Delete Selected'
                                    );
                                }
                            },
                            error: function() {
                                toastr.error('Failed to delete');
                                $btn.prop('disabled', false).html(
                                    '<i class="fas fa-trash-alt"></i> Delete Selected'
                                );
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
        });
    </script>

</body>
</html>