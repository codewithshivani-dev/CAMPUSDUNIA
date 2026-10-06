@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
        <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons (keeping for compatibility) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
        }

        /* Overlay */
        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: none;
            z-index: 9998;
        }

        /* Slide panel */
        #sidePanel {
            position: fixed;
            top: 0;
            right: -100%;
            width: 900px;
            height: 100%;
            background: #fff;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.3);
            overflow-y: auto;
            transition: right 0.4s ease;
            z-index: 9999;
            padding: 20px;
            display: none;
        }

        /* Table spacing for better readability */
        .student-details-table th,
        .student-details-table td {
            vertical-align: middle;
        }

        /* Add subtle row separation */
        .student-details-table tr {
            border-bottom: 1px solid #e9ecef;
        }

        /* Section headers (like Parent Details) */
        .student-details-table .section-header {
            background-color: #f1f1f1;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Label column styling */
        .student-details-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        /* ERP Table Styles - Enhanced */
        /* ERP Updated CSS for table head sticky */
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
        /* ------------------------------------- */

        .erp-table th {
            padding: 15px 10px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: none;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s;
            position: relative;
            letter-spacing: 0.3px;
        }

        .erp-table th:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
            /* min-width: 150px; */
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
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
        }

        .erp-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: all 0.3s;
        }

        .erp-table tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(67, 97, 238, 0.1);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges - Enhanced */
        .status-badge {
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .status-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .status-active {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
        }

        .status-inactive {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
        }

        .status-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border: none;
        }

        /* Bulk Actions - Enhanced */
        .bulk-actions-container {
            gap: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            animation: slideDown 0.4s ease;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 15px 20px;
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bulk-actions-container.active {
            display: flex;
        }

        .selected-count {
            font-weight: 600;
            color: var(--primary-color);
            margin-right: auto;
            font-size: 14px;
            background: white;
            padding: 6px 12px;
            border-radius: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        /* Disabled certificate button styling */
        .certificates .action-btn:disabled,
        .certificates .action-btn.disabled {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            color: #ffff !important;
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .certificates .action-btn:disabled:hover,
        .certificates .action-btn.disabled:hover {
            transform: none;
            box-shadow: none;
        }
        
        .bulk-action-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            color: white;
            transition: all 0.3s;
            font-size: 14px;
            display: flex;
            align-items: center;
            border: 1px solid transparent;
            margin-right: 10px;
        }

        .bulk-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .bulk-action-btn:active {
            transform: translateY(-1px);
        }

        .bulk-action-btn.download {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .bulk-action-btn select {
            background: transparent;
            border: none;
            color: white;
            font-weight: 500;
            cursor: pointer;
            outline: none;
        }

        .bulk-action-btn select option {
            color: #166534;
        }

        .bulk-action-btn.notice {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .bulk-action-btn.delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .bulk-action-btn.exit {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        .bulk-action-btn.clear {
            background: linear-gradient(135deg, #94a3b8, #64748b);
        }

        .bulk-action-btn i {
            margin-right: 5px;
        }

        /* Checkbox styling */
        .select-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            border-radius: 4px;
            border: 2px solid #cbd5e1;
            transition: all 0.2s;
        }

        .select-checkbox:hover {
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .select-checkbox:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
        }

        /* Filter container - Enhanced */
        .filter-container {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
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

        .filter-container h6 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
        }

        .filter-form {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 16px;
            width: 100%;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 12px;
            width: 100%;
            align-items: end;
        }

        .filter-group {
            position: relative;
            min-width: 0;
        }

        .filter-group .filter-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 15px;
            z-index: 2;
            pointer-events: none;
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 11px 12px 11px 38px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 14px;
            background: #fff;
            transition: all 0.3s;
            min-height: 44px;
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

        .filter-group input:disabled {
            background-color: #f8fafc;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-end;
            min-width: 180px;
            width: 100%;
            margin-top: 4px;
        }

        .btn-filter {
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-size: 14px;
            white-space: nowrap;
            min-height: 44px;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.12);
            text-decoration: none;
            color: white !important;
        }

        .btn-filter:active {
            transform: translateY(-1px);
        }

        .btn-filter-primary {
            background: var(--primary-gradient);
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 14px rgba(67, 97, 238, 0.18);
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
            border: 1px solid #e2e8f0;
        }

        .btn-filter-secondary:hover {
            background: #e2e8f0;
            color: #475569;
            transform: translateY(-2px);
        }

        /* Page Header - Enhanced (matching department management) */
        .page-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px 30px;
            background: var(--primary-gradient);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
            position: relative;
            overflow: hidden;
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
            position: relative;
            z-index: 1;
        }

        .page-title i {
            font-size: 32px;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
        }

        /* Banner - Enhanced */
        .banner {
            height: 260px;
            border-radius: 18px;
            position: relative;
            overflow: hidden;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        }

        .banner img.bg {
            width: 100%;
            height: 260px;
            object-fit: cover;
            filter: brightness(0.8);
        }

        .banner .meta {
            position: absolute;
            left: 28px;
            bottom: 22px;
            background: rgba(255, 255, 255, 0.95);
            padding: 20px 30px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary-color);
        }

        .banner .meta h3 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
        }

        .banner .meta p {
            margin: 0;
            color: #64748b;
        }

        /* Action Buttons - Enhanced */
        .table-actions {
            display: inline-grid;
            gap: 8px;
            justify-content: center;
        }

        .action-btn {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .action-btn:hover::before {
            width: 200px;
            height: 200px;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .action-btn:active {
            transform: translateY(-1px);
        }

        .action-btn-view {
            background: linear-gradient(135deg, #e0f2fe, #bae6fd);
            color: #0369a1;
        }

        .action-btn-edit {
            background: linear-gradient(135deg, #fef2c8, #fde68a);
            color: #92400e;
        }

        .action-btn-delete {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .action-btn-card {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
        }
        
        .action-btn-exit {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
        }
    
        .action-btn-exit:hover {
            background: linear-gradient(135deg, #ea580c, #c2410c);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(234, 88, 12, 0.3);
        }

        /* Add Button - Enhanced */
        .add-btn {
            padding: 12px 24px;
            background: var(--primary-gradient);
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .add-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .table-responsive {
           overflow-x: hidden;
        }

        .add-btn i {
            font-size: 18px;
        }

        /* Alert Messages - Enhanced */
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

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #e2e8f0;
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 0.9s linear infinite;
        }

        /* Empty State - Enhanced */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .empty-state-icon {
            font-size: 64px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 8px 0;
            overflow: hidden;
        }

        .dropdown-item {
            font-size: 14px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            padding-left: 25px;
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            background: var(--primary-gradient);
            border-bottom: none;
            padding: 20px 24px;
        }

        .modal-title {
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
        }

        .modal-header .btn-close {
            background: rgba(255, 255, 255, 0.2);
            opacity: 1;
            border-radius: 50%;
            padding: 8px;
            transition: all 0.3s;
        }

        .modal-header .btn-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 16px 24px;
        }

        /* Tabs Styling */
        .nav-tabs {
            border-bottom: 2px solid #e2e8f0;
            margin-top: 20px;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 12px 20px;
            transition: all 0.3s;
            position: relative;
        }

        .nav-tabs .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-gradient);
            transition: width 0.3s;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            background: transparent;
            border: none;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background: transparent;
            border: none;
        }

        .nav-tabs .nav-link.active::after {
            width: 100%;
        }

        /* Page Loader */
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

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                gap: 16px;
                padding: 20px;
            }

            .page-title {
                font-size: 24px;
            }

            .filter-form {
                align-items: stretch;
            }

            .filter-grid {
                display: flex;
                flex-wrap: wrap;
            }

            .filter-group {
                max-width: 200px;
            }

            .btn-filter {
                flex: 1;
                justify-content: center;
            }

            .bulk-actions-container .d-flex {
                flex-wrap: wrap;
                gap: 8px;
            }

            .table-actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
            }

            .banner .meta {
                left: 15px;
                right: 15px;
                padding: 15px;
            }
        }

        /* Utility Classes */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background: var(--primary-gradient) !important;
        }

        .small {
            font-size: 12px;
        }

        .filter-group.disabled-hint::after {
            content: "ⓘ Select Class first";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #94a3b8;
            pointer-events: none;
            background: white;
            padding-left: 5px;
        }
        
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 10px 0;
            flex-wrap: wrap;
            gap: 10px;
        }

        .per-page-selector {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .per-page-selector select {
            margin: 0 5px;
        }

        .pagination-buttons {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .pagination-btn {
            padding: 5px 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .pagination-btn:hover:not(:disabled) {
            background-color: #e9ecef;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-info {
            font-size: 14px;
            color: #6c757d;
        }

        .pagination-numbers {
            text-align: center;
            margin-top: 15px;
        }

        .page-number-btn {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .page-number-btn:hover {
            background-color: #e9ecef;
        }

        @media (max-width: 768px) {
            .table-footer {
                flex-direction: column;
                text-align: center;
            }
        }

        /* ============================================ */
        /* CERTIFICATE GRID LAYOUT - 2-2-1 PATTERN */
        /* ============================================ */
        .certificates {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 10px;
            min-width: 240px;
            min-height: 190px;
        }

        /* Make certificate buttons full width with proper text display */
        .certificates .action-btn,
        .certificates button.action-btn,
        .certificates a.action-btn {
            width: 100%;
            align-items: center;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 10px;
            border-radius: 10px;
            white-space: normal;
            line-height: 1.3;
            display: flex;
            gap: 6px;
            transition: all 0.3s ease;
        }

        /* Ensure icons are visible and properly sized */
        .certificates .action-btn i,
        .certificates button.action-btn i,
        .certificates a.action-btn i {
            font-size: 12px;
            margin-right: 4px;
        }

        /* Last certificate stays in first column */
        .certificates a.action-btn:last-child,
        .certificates button.action-btn:last-child {
            grid-column: 1 / 2;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .certificates .action-btn,
            .certificates button.action-btn,
            .certificates a.action-btn {
                font-size: 9px;
                padding: 5px 3px;
                white-space: normal;
                word-break: keep-all;
                line-height: 1.3;
            }
            
            .certificates .action-btn i,
            .certificates button.action-btn i,
            .certificates a.action-btn i {
                font-size: 9px;
            }
        }
        /* Suspend button - Temporary block */
       .action-btn-suspend {
            background: linear-gradient(135deg, #fa8993, #fa1b5d);
            color: #ffffff;
        }
    
        .action-btn-suspend:hover {
            background: linear-gradient(135deg, #fa8993, #fa1b5d);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }
    
        /* Unsuspend button - Restore access */
        .action-btn-unsuspend {
            background: linear-gradient(135deg, #002998, #42a6e2);
            color: #fcfdfc;
        }
    
        .action-btn-unsuspend:hover {
            background: linear-gradient(135deg, #002998, #42a6e2);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
        }
    
    
        .suspend-badge {
            background: linear-gradient(135deg, #f5690b, #d95306);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            margin-left: 8px;
        }
    
        /* Visual indicator for suspended rows */
        tr.suspended-row {
            background-color: rgba(254, 242, 200, 0.1);
            border-left: 3px solid #f59e0b;
        }
    </style>

    <!-- Header with merchant name (enhanced) -->
    <div class="mainDiv1 d-none">
        <div class="mainDiv" style="height: 270px;">
            <div class="banner mb-4">
                @if(!empty($fincapMerchants->documents->first()->institute_image_path))
                    <img src="{{ asset('/image/' . $fincapMerchants->documents->first()->institute_image_path) }}"
                        alt="institute image" class="bg">
                @else
                    <img src="{{ asset('/image/default-institute.jpg') }}"
                        alt="institute image" class="bg">
                @endif
                <div class="meta">
                    <h3 style="margin:0;">
                        <i class="bi bi-building me-2"></i>{{ $fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? 'Institute Name' }}
                    </h3>
                    <p style="margin:0;color:#444;">
                        <i class="bi bi-geo-alt me-1"></i>{{ $fincapMerchants->state ?? $fincapMerchants->fincap_merchant_state ?? '' }},
                        {{ $fincapMerchants->city ?? $fincapMerchants->fincap_merchant_city ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="container-fluid">
        <!-- Page Header - Exactly matching department management -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-people-fill"></i>
                View Student Details
            </h1>
            <button class="add-btn" onclick="window.location.href='/institute/admin/add-students1'">
                <i class="bi bi-plus-circle"></i>
                Add Student
            </button>
        </div>

        {{-- Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Filters --}}
        <div class="filter-container">
            <h6><i class="bi bi-funnel-fill"></i> *Select or Type to Search</h6>
            <form method="GET" action="{{ url()->current() }}" class="filter-form" id="filterForm">
                <div class="filter-grid">
                    <!-- Registration -->
                    <div class="filter-group">
                        <i class="bi bi-qr-code-scan filter-icon"></i>
                        <input list="regList" name="registration_number" class="filter-input auto-search"
                            value="{{ request('registration_number') }}" placeholder="Registration Number">
                        <datalist id="regList">
                            @foreach($regNumbers as $r)
                                <option value="{{ $r->registration_number }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Name -->
                    <div class="filter-group">
                        <i class="bi bi-person filter-icon"></i>
                        <input list="nameList" name="name" class="filter-input auto-search"
                            value="{{ request('name') }}" placeholder="Student Name">
                        <datalist id="nameList">
                            @foreach($names as $n)
                                <option value="{{ $n->first_name }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Class -->
                    <div class="filter-group">
                        <i class="bi bi-book filter-icon"></i>
                        <input list="courseList"
                            id="course_filter"
                            name="course_type"
                            class="filter-input auto-search"
                            value="{{ request('course_type') }}"
                            placeholder="Class">
                        <datalist id="courseList">
                            @foreach($courses as $c)
                                <option value="{{ $c }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Section (depends on Class selection) -->
                    <div class="filter-group">
                        <i class="bi bi-grid-3x3 filter-icon"></i>
                        <input list="sectionList"
                            name="section_name"
                            id="section_input"
                            class="filter-input auto-search"
                            value="{{ request('section_name') ?? '' }}"
                            placeholder="Select Class first"
                            disabled>
                        <datalist id="sectionList">
                            <!-- Options will be populated dynamically via JavaScript -->
                        </datalist>
                        <!-- Hidden field to send actual section ID to backend -->
                        @if(request('section_id'))
                            <input type="hidden" name="section_id" id="section_id_hidden" value="{{ request('section_id') }}">
                        @else
                            <input type="hidden" name="section_id" id="section_id_hidden" value="">
                        @endif
                    </div>

                             <!-- Batch -->
                    <div class="filter-group">
                        <i class="bi bi-collection filter-icon"></i>
                        <select id="batch_filter" name="batch" class="filter-input auto-search">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch }}" {{ request('batch') == $batch ? 'selected' : '' }}>{{ $batch }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Academic Year -->
                    <div class="filter-group">
                        <i class="bi bi-calendar2-week filter-icon"></i>
                        <select id="academic_year_filter" name="academic_year" class="filter-input auto-search">
                            <option value="">All Academic Years</option>
                            @foreach($academicYears as $academicYear)
                                <option value="{{ $academicYear }}" {{ request('academic_year') == $academicYear ? 'selected' : '' }}>{{ $academicYear }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender -->
                    <div class="filter-group">
                        <i class="bi bi-gender-ambiguous filter-icon"></i>
                        <input list="genderList" id="gender_filter" name="gender" class="filter-input auto-search"
                            value="{{ request('gender') }}" placeholder="Gender">
                        <datalist id="genderList">
                            @foreach($genders as $g)
                                <option value="{{ $g->gender }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Status -->
                    <div class="filter-group">
                        <i class="bi bi-toggle-on filter-icon"></i>
                        <select id="status_filter" name="status" class="filter-input auto-search">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            

                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn-filter btn-filter-primary d-none">
                            <i class="bi bi-funnel-fill"></i> Apply
                        </button>
                        <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary" style="color:#475569 !important;">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>

                    <!-- DOB (hidden) -->
                    <div class="filter-group d-none">
                        <i class="bi bi-calendar filter-icon"></i>
                        <input type="date" name="dob" class="filter-input auto-search" value="{{ request('dob') }}">
                    </div>
                </div>

                

                <!-- Sorting -->
                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'registration_number') }}">
                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">
            </form>
        </div>

        {{-- Bulk Actions Container --}}
        <div class="bulk-actions-container d-flex" id="bulkActionsContainer">
            <div class="selected-count" id="selectedCount">0 students selected</div>
            <div class="d-flex flex-wrap">
                <div class="dropdown">
                    <button class="bulk-action-btn download dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <i class="bi bi-download"></i>
                        Download
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)"
                            onclick="bulkAction('download','excel')">
                                <i class="bi bi-file-earmark-excel text-success me-2"></i>
                                Excel (.xlsx)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)"
                            onclick="bulkAction('download','csv')">
                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                CSV (.csv)
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="bulk-action-btn notice d-none" onclick="bulkAction('send_notice')">
                    <i class="bi bi-envelope"></i>
                    Send Notice
                </button>
                <button class="bulk-action-btn delete" onclick="bulkAction('bulk_delete')">
                    <i class="bi bi-trash"></i>
                    Bulk Delete
                </button>
                <button class="bulk-action-btn exit d-none" onclick="bulkAction('bulk_exit')">
                    <i class="bi bi-door-open"></i>
                    Bulk Exit
                </button>
                <button class="bulk-action-btn clear" onclick="clearSelection()">
                    <i class="bi bi-x-lg"></i>
                    Clear
                </button>
            </div>
        </div>

        <div>
            {{-- Student Table --}}
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <input type="hidden" id="filteredTotal" value="{{ $students->total() }}">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th class="sticky-checkbox" width="40">
                                <input type="checkbox" id="selectAll" class="select-checkbox">
                            </th>
                            <th class="sortable d-none" onclick="sortTable('student_hash_id')">
                                Hash ID
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'student_hash_id' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'student_hash_id' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable d-none" onclick="sortTable('registration_number')">
                                Registration No
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'registration_number' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'registration_number' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sticky-main sortable" onclick="sortTable('first_name')">
                                Name
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'first_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'first_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('dob')">
                                DOB
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'dob' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'dob' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('mobile')">
                                Mobile
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'mobile' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'mobile' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('gender')">
                                Gender
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'gender' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'gender' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('class')">
                                Class
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'class' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'class' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('section')">
                                Section
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'section' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'section' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('status')">
                                Academic Year
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'status' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable" onclick="sortTable('status')">
                                Status
                                <div class="sort-icons">
                                    <i class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                    <i class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'status' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                                </div>
                            </th>
                            <th class="sortable">Fee Structure</th>
                            <th class="sortable">Id Card</th>
                            <th class="sortable text-center">Certificate</th>
                            <th class="sortable text-center">Actions</th>
                            <th class="sortable text-center">History</th>
                        </tr>
                    </thead>
                    <tbody id="CourseDisplayTable">
                        @foreach ($students as $student)
                            <tr>
                                <td class="sticky-checkbox">
                                    <input type="checkbox" class="student-checkbox select-checkbox"
                                        value="{{ $student->student_hash_id }}">
                                </td>
                                <td class="d-none">{{ $student->student_hash_id }}</td>
                                <td class="d-none">{{ $student->registration_number }}</td>
                                <td class="sticky-main">
                                    <div style="font-weight: 500;">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</div>
                                    <span class="small text-primary">{{ $student->registration_number }}</span>
                                </td>
                                <td>
                                    {{ $student->dob 
                                        ? \Carbon\Carbon::parse($student->dob)->format('d-m-Y') 
                                        : 'N/A' 
                                    }}
                                </td>
                                <td>{{ $student->mobile }}</td>
                                <td>{{ $student->gender }}</td>
                                <td>{{ $student->academicTransportDetails->course_type ?? 'N/A' }}</td>
                                <td>{{ optional($student->academicTransportDetails)->section_name ?? 'N/A' }}</td>
                                <td>{{ $student->academicTransportDetails->academic_year ?? 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusClass = 'status-active';
                                        $statusText = 'Active';
    
                                        if (isset($student->status)) {
                                            switch (strtolower($student->status)) {
                                                case 'inactive':
                                                    $statusClass = 'status-inactive';
                                                    $statusText = 'Inactive';
                                                    break;
                                                case 'pending':
                                                    $statusClass = 'status-pending';
                                                    $statusText = 'Pending';
                                                    break;
                                                default:
                                                    $statusClass = 'status-active';
                                                    $statusText = 'Active';
                                            }
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.student.fee', $student->student_hash_id) }}" class="action-btn action-btn-view" style="font-size: 12px;">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                                <td>
                                    <a href="/students/{{ $student->student_hash_id }}/card"
                                        class="action-btn action-btn-card" title="Student Card">
                                        <i class="fa-solid fa-id-card"></i>
                                        <span class="small">view</span>
                                    </a>
                                </td>
                                <td class="certificates">
                                    {{-- Course Completion Certificate --}}
                                    @php
                                        $courseCompletionKey = $student->registration_number . '_course_completion';
                                        $isCourseCompletionGenerated = isset($generatedCertificates[$courseCompletionKey]);
                                    @endphp
                                    @if($isCourseCompletionGenerated)
                                        <button class="action-btn action-btn-view" style="font-size: 12px; background: #dcfce7; color: #166534; cursor: default; opacity: 0.8;" disabled title="Certificate Generated">
                                            <i class="fas fa-check-circle"></i> Course Completion
                                        </button>
                                    @else
                                        <a href="{{ url('/institute-admin/certificates/course-completion?reg=' . $student->registration_number) }}" class="action-btn action-btn-view" style="font-size: 12px;" title="Generate Certificate">
                                            <i class="fas fa-plus-circle"></i> Course Completion
                                        </a>
                                    @endif
                                    
                                    {{-- Character Certificate --}}
                                    @php
                                        $characterKey = $student->registration_number . '_character';
                                        $isCharacterGenerated = isset($generatedCertificates[$characterKey]);
                                    @endphp
                                    @if($isCharacterGenerated)
                                        <button class="action-btn action-btn-view" style="font-size: 12px; background: #dcfce7; color: #166534; cursor: default; opacity: 0.8;" disabled title="Certificate Generated">
                                            <i class="fas fa-check-circle"></i> Character
                                        </button>
                                    @else
                                        <a href="{{ url('/institute-admin/certificates/character?reg=' . $student->registration_number) }}" class="action-btn action-btn-view" style="font-size: 12px;" title="Generate Certificate">
                                            <i class="fas fa-plus-circle"></i> Character
                                        </a>
                                    @endif
                                    
                                    {{-- Bonafide Certificate --}}
                                    @php
                                        $bonafideKey = $student->registration_number . '_bonafide';
                                        $isBonafideGenerated = isset($generatedCertificates[$bonafideKey]);
                                    @endphp
                                    @if($isBonafideGenerated)
                                        <button class="action-btn action-btn-view" style="font-size: 12px; background: #dcfce7; color: #166534; cursor: default; opacity: 0.8;" disabled title="Certificate Generated">
                                            <i class="fas fa-check-circle"></i> Bonafide
                                        </button>
                                    @else
                                        <a href="{{ url('/institute-admin/bonafide?reg=' . $student->registration_number) }}" class="action-btn action-btn-view" style="font-size: 12px;" title="Generate Certificate">
                                            <i class="fas fa-plus-circle"></i> Bonafide
                                        </a>
                                    @endif
                                    
                                    {{-- Transfer Certificate (TC) --}}
                                    @php
                                        $transferKey = $student->registration_number . '_tc';
                                        $isTransferGenerated = isset($generatedCertificates[$transferKey]);
                                    @endphp
                                    @if($isTransferGenerated)
                                        <button class="action-btn action-btn-view" style="font-size: 12px; background: #dcfce7; color: #166534; cursor: default; opacity: 0.8;" disabled title="Certificate Generated">
                                            <i class="fas fa-check-circle"></i> Transfer
                                        </button>
                                    @else
                                        <a href="{{ url('/institute-admin/certificates/transfer?reg=' . $student->registration_number) }}" class="action-btn action-btn-view" style="font-size: 12px;" title="Generate Certificate">
                                            <i class="fas fa-plus-circle"></i> Transfer
                                        </a>
                                    @endif
                                    
                                    {{-- School Leaving Certificate --}}
                                    @php
                                        $schoolLeavingKey = $student->registration_number . '_school_leaving';
                                        $isSchoolLeavingGenerated = isset($generatedCertificates[$schoolLeavingKey]);
                                    @endphp
                                    @if($isSchoolLeavingGenerated)
                                        <button class="action-btn action-btn-view" style="font-size: 12px; background: #dcfce7; color: #166534; cursor: default; opacity: 0.8;" disabled title="Certificate Generated">
                                            <i class="fas fa-check-circle"></i> School Leaving
                                        </button>
                                    @else
                                        <a href="{{ url('/institute-admin/certificates/school-leaving?reg=' . $student->registration_number) }}" class="action-btn action-btn-view" style="font-size: 12px;" title="Generate Certificate">
                                            <i class="fas fa-plus-circle"></i> School Leaving
                                        </a>
                                    @endif
                                    
                                    {{-- No Due Certificate --}}
                                    @php
                                        $noDueKey = $student->registration_number . '_no_due';
                                        $isNoDueGenerated = isset($generatedCertificates[$noDueKey]);
                                    @endphp
                                    @if($isNoDueGenerated)
                                        <button class="action-btn action-btn-view" style="font-size: 12px; background: #dcfce7; color: #166534; cursor: default; opacity: 0.8;" disabled title="Certificate Generated">
                                            <i class="fas fa-check-circle"></i> No Due
                                        </button>
                                    @else
                                        <a href="{{ url('/institute-admin/certificates/no-due?reg=' . $student->registration_number) }}" class="action-btn action-btn-view" style="font-size: 12px;" title="Generate Certificate">
                                            <i class="fas fa-plus-circle"></i> No Due
                                        </a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="table-actions">
                                        <a href="{{ url('/student-details/' . $student->student_hash_id) }}"
                                            class="action-btn action-btn-view" title="View Details">
                                            <i class="fa-regular fa-eye"></i>
                                            <span class="small">View</span>
                                        </a>
                                        <a href="{{ route('students.onboard.edit', $student->student_hash_id) }}"
                                                class="action-btn action-btn-edit" title="Edit Student">
                                                <div>
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </div>
                                                <div>
                                                    <span class="small">Edit</span>
                                                </div>
                                            </a>
                                            {{-- SUSPEND/UNSUSPEND BUTTON (Temporary) --}}
                                        @if($student->suspend_status === 'suspended')
                                            <button class="action-btn action-btn-unsuspend"
                                                    onclick="unsuspendStudent('{{ $student->student_hash_id }}', '{{ $student->first_name }} {{ $student->last_name }}')"
                                                    title="Unsuspend Student - Restore Credits">
                                                <i class="bi bi-unlock-fill"></i>
                                                <span class="small">Unsuspend</span>
                                            </button>
                                        @else
                                            <button class="action-btn action-btn-suspend"
                                                    onclick="suspendStudent('{{ $student->student_hash_id }}', '{{ $student->first_name }} {{ $student->last_name }}')"
                                                    title="Suspend Student - Block Credits Temporarily">
                                                <i class="bi bi-lock-fill"></i>
                                                <span class="small">Suspend</span>
                                            </button>
                                        @endif
                                      
                                         @if($student->student_status != 'exit')
                                      
                                        <button class="action-btn action-btn-exit" 
                                                onclick="window.location.href='{{ route('institute.admin.student.exit.form', $student->student_hash_id) }}'" 
                                                title="Exit Student">
                                            <i class="bi bi-door-open"></i>
                                            <span class="small">Exit</span>
                                        </button>
                                          @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="action-btn action-btn-info"
                                        onclick="viewSuspensionHistory('{{ $student->student_hash_id }}', '{{ $student->first_name }} {{ $student->last_name }}')"
                                        title="View Suspension History">
                                        <i class="bi bi-clock-history"></i>
                                        <span class="small">view</span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @if($students->count() == 0)
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <h4 style="color: #1e293b; margin-bottom: 10px;">No students found</h4>
                                        <p class="text-muted">Try adjusting your filters or add a new student</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Floating Horizontal Scrollbar -->
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
    
            {{-- Pagination --}}
            @if($students->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of
                        {{ $students->total() }} results
                    </div>
                    <div>
                        {{ $students->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Overlay -->
    <div id="overlay"></div>

    <!-- Slide Panel -->
    <div id="sidePanel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4><i class="bi bi-person-badge me-2" style="color: var(--primary-color);"></i>Student Details</h4>
            <button id="closePanel" class="btn-close"></button>
        </div>

        <!-- Bootstrap Tabs -->
        <ul class="nav nav-tabs" id="studentTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="basic-tab" data-toggle="tab" href="#tabBasic" role="tab">Basic</a></li>
            <li class="nav-item"><a class="nav-link" id="address-tab" data-toggle="tab" href="#tabAddress" role="tab">Address</a></li>
            <li class="nav-item"><a class="nav-link" id="extra-tab" data-toggle="tab" href="#tabExtra" role="tab">Academic</a></li>
            <li class="nav-item"><a class="nav-link" id="docs-tab" data-toggle="tab" href="#tabDocs" role="tab">Documents</a></li>
            <li class="nav-item"><a class="nav-link" id="bank-tab" data-toggle="tab" href="#tabBank" role="tab">Bank</a></li>
        </ul>

        <!-- Document View Modal -->
        <div class="modal fade" id="docModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-file-earmark me-2"></i>Document Viewer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center" id="docPreview">
                        <!-- Document will be shown here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Contents -->
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="tabBasic" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabAddress" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabBank" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabDocs" role="tabpanel"></div>
            <div class="tab-pane fade" id="tabExtra" role="tabpanel"></div>
        </div>
    </div>
    
    <!-- Suspension History Modal -->
    <div class="modal fade" id="suspensionHistoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <h5 class="modal-title" style="color: white;">
                        <i class="bi bi-clock-history me-2"></i>
                         History
                    </h5>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body" id="suspensionHistoryContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-warning" role="status"></div>
                        <p class="mt-2">Loading history...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="pageLoader">
        <div class="spinner"></div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ============================================
    // GLOBAL VARIABLES
    // ============================================
    let isSelectAllActive = false;
    let selectedStudentIds = new Set();
    let typingTimer;
    let currentFilters = {};

    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function () {
        initializeSelectionManagement();
        setupFilterAutoSubmit();
        setupViewPanel();
        setupEventListeners();
        loadCurrentFilters();

        // --- Auto-select if filters are present ---
        if (hasActiveFilters()) {
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox && !selectAllCheckbox.checked) {
                // Slight delay to ensure UI is ready
                setTimeout(() => selectAllCheckbox.click(), 100);
            }
        }

        // --- Restore global selection state from previous page/sort ---
        restoreSelectionState();
    });

    // ============================================
    // FILTER DETECTION
    // ============================================
    function hasActiveFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        const filterFields = ['registration_number', 'name', 'gender', 'course_type', 'section_id', 'dob', 'batch', 'academic_year', 'status'];
        return filterFields.some(field => urlParams.has(field) && urlParams.get(field).trim() !== '');
    }

    // Dynamic Section Loading based on Class selection
    document.addEventListener('DOMContentLoaded', function() {
        const courseInput = document.querySelector('input[name="course_type"]');
        const sectionInput = document.getElementById('section_input');
        const sectionList = document.getElementById('sectionList');
        const sectionIdHidden = document.getElementById('section_id_hidden');
        const filterForm = document.getElementById('filterForm');
        
        // Course sections data from PHP
        const courseSections = @json($courseSections);
        
        // If the page was loaded with a section filter, store it
        const preselectedSectionId = '{{ request("section_id") }}';
        const preselectedSectionName = '{{ request("section_name") }}';
        
        function updateSections() {
            const selectedCourse = courseInput ? courseInput.value.trim() : '';
            
            if (selectedCourse && courseSections[selectedCourse] && courseSections[selectedCourse].length > 0) {
                // Enable section input
                sectionInput.disabled = false;
                sectionInput.placeholder = "Select Section";
                sectionInput.classList.remove('disabled-hint');
                
                // Clear existing options
                sectionList.innerHTML = '';
                
                // Add new options
                courseSections[selectedCourse].forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.name;
                    option.setAttribute('data-id', section.id);
                    sectionList.appendChild(option);
                });
                
                // If there's a preselected section that matches this course, restore it
                if (preselectedSectionId && preselectedSectionName) {
                    const sectionExists = courseSections[selectedCourse].some(s => 
                        String(s.id) === String(preselectedSectionId) || s.name === preselectedSectionName
                    );
                    if (sectionExists) {
                        sectionInput.value = preselectedSectionName;
                        sectionIdHidden.value = preselectedSectionId;
                    } else {
                        sectionInput.value = '';
                        sectionIdHidden.value = '';
                    }
                }
                // If there's a previously selected section that doesn't match current course, clear it
                else {
                    const currentSection = sectionInput.value;
                    if (currentSection) {
                        const sectionExists = courseSections[selectedCourse].some(s => s.name === currentSection);
                        if (!sectionExists) {
                            sectionInput.value = '';
                            sectionIdHidden.value = '';
                        }
                    }
                }
            } else if (selectedCourse && (!courseSections[selectedCourse] || courseSections[selectedCourse].length === 0)) {
                // Course exists but has no sections
                sectionInput.disabled = true;
                sectionInput.placeholder = "No sections available";
                sectionInput.classList.add('disabled-hint');
                sectionInput.value = '';
                sectionList.innerHTML = '';
                sectionIdHidden.value = '';
            } else {
                // No course selected
                sectionInput.disabled = true;
                sectionInput.placeholder = "Select Class first";
                sectionInput.classList.add('disabled-hint');
                sectionInput.value = '';
                sectionList.innerHTML = '';
                sectionIdHidden.value = '';
            }
        }
        
        // When section is selected, store the ID in the hidden field
        function handleSectionChange() {
            const selectedValue = sectionInput.value;
            const options = Array.from(sectionList.options);
            const selectedOption = options.find(opt => opt.value === selectedValue);
            
            if (selectedOption && selectedOption.getAttribute('data-id')) {
                const sectionId = selectedOption.getAttribute('data-id');
                sectionIdHidden.value = sectionId;
            } else if (selectedValue === '') {
                // If user clears the section, clear hidden value
                sectionIdHidden.value = '';
            } else if (options.length > 0 && selectedValue) {
                // If user typed a value that doesn't exactly match an option, try fuzzy match
                const fuzzyMatch = options.find(opt => opt.value.toLowerCase().includes(selectedValue.toLowerCase()));
                if (fuzzyMatch) {
                    sectionInput.value = fuzzyMatch.value;
                    sectionIdHidden.value = fuzzyMatch.getAttribute('data-id');
                } else {
                    sectionIdHidden.value = '';
                }
            }
        }
        
        // Initial check
        updateSections();
        
        // Set initial section value if it exists in the URL
        if (preselectedSectionName && sectionInput.disabled === false) {
            const matchingOption = Array.from(sectionList.options).find(opt => opt.value === preselectedSectionName);
            if (matchingOption) {
                sectionInput.value = preselectedSectionName;
                sectionIdHidden.value = preselectedSectionId;
            }
        }
        
        // Listen for changes in course input
        if (courseInput) {
            courseInput.addEventListener('change', function() {
                updateSections();
                handleSectionChange();
            });
            
            courseInput.addEventListener('input', function() {
                clearTimeout(window.sectionTimer);
                window.sectionTimer = setTimeout(updateSections, 500);
            });
        }
        
        // Listen for section changes
        sectionInput.addEventListener('change', handleSectionChange);
        sectionInput.addEventListener('input', function() {
            clearTimeout(window.sectionInputTimer);
            window.sectionInputTimer = setTimeout(handleSectionChange, 300);
        });
        
        // For the auto-submit functionality, ensure section filter works properly
        if (filterForm) {
            const originalSubmit = filterForm.submit;
            
            // Override submit to ensure section_id is set before form submission
            filterForm.submit = function() {
                handleSectionChange();
                return originalSubmit.apply(this, arguments);
            };
        }
    });

    // ============================================
    // BULK SELECTION MANAGEMENT
    // ============================================
    function initializeSelectionManagement() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');

        loadCurrentFilters();

        // ---------- Select All ----------
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {

                if (this.checked) {
                    isSelectAllActive = true;
                    selectAllCheckbox.checked = true;
                    studentCheckboxes.forEach(cb => {
                        cb.checked = true;
                        cb.closest('tr')?.classList.add('select-all-highlight');
                    });

                    bulkActionsContainer?.classList.add('active', 'select-all-active');
                    selectedStudentIds.clear();

                    updateSelectionUI();
                } else {

                    isSelectAllActive = false;
                    bulkActionsContainer?.classList.remove('select-all-active');

                    studentCheckboxes.forEach(cb => {
                        cb.checked = false;
                        cb.closest('tr')?.classList.remove('select-all-highlight', 'selected-highlight');
                    });

                    updateSelectionUI();
                }
            });
        }
        // ---------- Individual Checkboxes ----------
        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {

                const id = this.value;

                if (this.checked) {
                    selectedStudentIds.add(id);
                    this.closest('tr')?.classList.add('selected-highlight');
                } else {
                    selectedStudentIds.delete(id);
                    this.closest('tr')?.classList.remove('selected-highlight');
                }

                if (isSelectAllActive && !this.checked) {
                    document.getElementById('selectAll').checked = false;
                    isSelectAllActive = false;
                    bulkActionsContainer?.classList.remove('select-all-active');
                    document.querySelectorAll('.student-checkbox')
                        .forEach(cb => cb.closest('tr')?.classList.remove('select-all-highlight'));
                }

                updateSelectionUI();
            });
        });

        updateSelectionUI();
    }

    // ============================================
    // UI UPDATE – Selected Count & Filter Display
    // ============================================
    function updateSelectionUI() {
        const selectedCount = selectedStudentIds.size;
        const selectAllCheckbox = document.getElementById('selectAll');
        const selectedCountElement = document.getElementById('selectedCount');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');

        if (isSelectAllActive || (selectAllCheckbox && selectAllCheckbox.checked)) {
            const filteredTotalInput = document.getElementById('filteredTotal');
            const filteredTotal = filteredTotalInput ? parseInt(filteredTotalInput.value, 10) : 0;

            selectedCountElement.textContent = `${filteredTotal} student(s) selected (all)`;
            bulkActionsContainer?.classList.add('active', 'select-all-active');
        } else if (selectedCount > 0) {
            selectedCountElement.textContent = `${selectedCount} student(s) selected`;
            bulkActionsContainer?.classList.add('active');
            bulkActionsContainer?.classList.remove('select-all-active');

            if (selectAllCheckbox) {
                const totalCheckboxes = document.querySelectorAll('.student-checkbox').length;
                selectAllCheckbox.checked = selectedCount === totalCheckboxes;
                selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCheckboxes;
            }
        } else {
            selectedCountElement.textContent = '0 students selected';
            bulkActionsContainer?.classList.remove('active', 'select-all-active');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }
    }

    // ============================================
    // CLEAR SELECTION
    // ============================================
    function clearSelection() {
        document.querySelectorAll('.student-checkbox:checked').forEach(checkbox => {
            checkbox.checked = false;
            checkbox.closest('tr')?.classList.remove('selected-highlight', 'select-all-highlight');
        });

        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }

        isSelectAllActive = false;

        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        bulkActionsContainer?.classList.remove('active', 'select-all-active');
        updateSelectionUI();

        // Also clear stored state
        sessionStorage.removeItem('studentSelectAllActive');
        sessionStorage.removeItem('studentSelectAllFilters');
    }

    // ============================================
    // STATE PERSISTENCE (for pagination / sorting)
    // ============================================
    function storeSelectionState() {
        if (isSelectAllActive) {
            sessionStorage.setItem('studentSelectAllActive', 'true');
            sessionStorage.setItem('studentSelectAllFilters', JSON.stringify(getCurrentFilters()));
        } else {
            sessionStorage.removeItem('studentSelectAllActive');
            sessionStorage.removeItem('studentSelectAllFilters');
        }
    }

    function restoreSelectionState() {
        const storedActive = sessionStorage.getItem('studentSelectAllActive');
        const storedFilters = sessionStorage.getItem('studentSelectAllFilters');
        if (storedActive === 'true' && storedFilters) {
            const current = getCurrentFilters();
            const stored = JSON.parse(storedFilters);
            // Simple equality check – you may enhance this if needed
            if (JSON.stringify(current) === JSON.stringify(stored)) {
                const selectAllCheckbox = document.getElementById('selectAll');
                if (selectAllCheckbox && !selectAllCheckbox.checked) {
                    selectAllCheckbox.click();
                }
            }
            // Clean up after restoring
            sessionStorage.removeItem('studentSelectAllActive');
            sessionStorage.removeItem('studentSelectAllFilters');
        }
    }

    // ============================================
    // FILTER HELPERS
    // ============================================
    function loadCurrentFilters() {
        const form = document.getElementById('filterForm');
        if (!form) return;
        const formData = new FormData(form);
        currentFilters = {};
        for (let [key, value] of formData) {
            if (value && !key.includes('_token') && !key.includes('sort')) {
                currentFilters[key] = value;
            }
        }
    }

    function getCurrentFilters() {
        const form = document.getElementById('filterForm');
        if (!form) return currentFilters;
        const formData = new FormData(form);
        const filters = {};
        for (let [key, value] of formData) {
            if (value && !key.includes('_token') && !key.includes('sort')) {
                filters[key] = value;
            }
        }
        currentFilters = filters;
        return filters;
    }
    
    function showLoader() {
        const loader = document.getElementById('pageLoader');
        if (loader) loader.style.display = 'flex';
    }

    // ============================================
    // AUTO-SUBMIT FILTERS (with debounce)
    // ============================================
    function setupFilterAutoSubmit() {
        const form = document.getElementById('filterForm');
        if (!form) return;
    
        const delay = 1000;
        const autoInputs = ['registration_number', 'name', 'gender', 'course_type', 'section_name', 'batch', 'academic_year', 'status'];
    
        autoInputs.forEach(name => {
            const input = form.querySelector(`[name="${name}"]`);
            if (!input) return;

            const submitFilter = () => {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    storeSelectionState();
                    clearSelection();
                    showLoader();
                    form.submit();
                }, delay);
            };
    
            // For section_name input, we need to update the hidden section_id before submit
            if (name === 'section_name') {
                input.addEventListener('input', function() {
                    // Update section_id_hidden based on selected/typed value
                    const sectionList = document.getElementById('sectionList');
                    const hiddenField = document.getElementById('section_id_hidden');
                    const selectedValue = this.value;
    
                    if (sectionList && hiddenField) {
                        const match = Array.from(sectionList.options).find(o => o.value === selectedValue);
                        if (match && match.getAttribute('data-id')) {
                            hiddenField.value = match.getAttribute('data-id');
                        } else if (selectedValue === '') {
                            hiddenField.value = '';
                        }
                    }

                    submitFilter();
                });
            } else if (input.tagName === 'SELECT') {
                input.addEventListener('change', submitFilter);
            } else {
                input.addEventListener('input', submitFilter);
            }
        });
    }

    // ============================================
    // EVENT LISTENERS (Submit, Reset, Keyboard)
    // ============================================
    function setupEventListeners() {
        // Filter form manual submit (if any)
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function (e) {
                // Store state before actual submit
                storeSelectionState();

                const submitBtn = this.querySelector('.btn-filter-primary');
                if (submitBtn) {
                    const originalHTML = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
                    submitBtn.disabled = true;
                    setTimeout(() => {
                        submitBtn.innerHTML = originalHTML;
                        submitBtn.disabled = false;
                    }, 3000);
                }
                // Do not prevent default – let the form submit naturally
            });
        }

        // Reset filters button
        const resetBtn = document.querySelector('.btn-filter-secondary');
        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                clearSelection();          // clear checkboxes and flags
                sessionStorage.removeItem('studentSelectAllActive'); // clear stored state
                sessionStorage.removeItem('studentSelectAllFilters');
                // Form will reset and reload page
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                e.preventDefault();
                document.getElementById('selectAll')?.click();
            }
            if (e.key === 'Escape') {
                clearSelection();
            }
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'D') {
                e.preventDefault();
                document.querySelector('.bulk-action-btn.download')?.click();
            }
        });
    }

    // ============================================
    // BULK ACTIONS (Download, Delete, Exit, Notice)
    // ============================================
    function bulkAction(action, format = null) {
        const selectAllCheckbox = document.getElementById('selectAll');
        const selectedStudents = Array.from(document.querySelectorAll('.student-checkbox:checked'))
            .map(cb => cb.value);

            if (selectedStudents.length === 0 && !(selectAllCheckbox && selectAllCheckbox.checked)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one student.',
                    confirmButtonColor: '#4361ee',
                    confirmButtonText: 'OK'
                });
                return;
            }

        switch (action) {
            case 'download':
                if (!format) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Format Required',
                        text: 'Please select a format.',
                        confirmButtonColor: '#4361ee',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                let url = `/students/bulk-download?type=${format}`;
                if (selectAllCheckbox && selectAllCheckbox.checked) {

                    const filters = getCurrentFilters();
                    url += `&select_all=1&filters=${encodeURIComponent(JSON.stringify(filters))}`;

                    showLoadingForAction('Preparing download for all students...');

                } else {

                    url += `&ids=${selectedStudents.join(',')}`;
                    showLoadingForAction(`Preparing download for ${selectedStudents.length} student(s)...`);
                }
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = url;
                document.body.appendChild(iframe);
                setTimeout(() => {
                    hideLoadingForAction();
                    iframe.parentNode?.removeChild(iframe);
                }, 3000);
                break;

            case 'send_notice':
                handleBulkNotice(selectAllCheckbox, selectedStudents);
                break;
            case 'bulk_exit':
                handleBulkExit(selectAllCheckbox, selectedStudents);
                break;
            case 'bulk_delete':
                handleBulkDelete(selectAllCheckbox, selectedStudents);
                break;
            default:
            Swal.fire({
                icon: 'success',
                title: `${action} Triggered`,
                text: `Triggered for ${(selectAllCheckbox?.checked) ? 'ALL' : selectedStudents.length} students`,
                confirmButtonColor: '#4361ee',
                confirmButtonText: 'OK'
            });
        }
    }

    function handleBulkDelete(selectAllCheckbox, selectedStudents) {
        const isAll = selectAllCheckbox && selectAllCheckbox.checked;
        const count = isAll ? 'ALL' : selectedStudents.length;
        
        Swal.fire({
            title: 'Are you sure?',
            text: isAll
                ? `You are about to delete ALL students matching your current filters. This action cannot be undone.`
                : `You are about to delete ${selectedStudents.length} student(s). This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoadingForAction('Processing deletion...');
                const data = new FormData();
                if (isAll) {
                    data.append('select_all', 1);
                    data.append('filters', JSON.stringify(getCurrentFilters()));
                } else {
                    data.append('ids', selectedStudents.join(','));
                }
                
                // Simulate delete - replace with actual API call
                setTimeout(() => {
                    hideLoadingForAction();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: `${isAll ? 'All students' : selectedStudents.length + ' student(s)'} have been deleted.`,
                        confirmButtonColor: '#4361ee'
                    }).then(() => {
                        location.reload();
                    });
                }, 1500);
            }
        });
    }

    function handleBulkNotice(selectAllCheckbox, selectedStudents) {
        const isAll = selectAllCheckbox && selectAllCheckbox.checked;
        const count = isAll ? 'ALL' : selectedStudents.length;
        
        Swal.fire({
            title: 'Send Notice',
            input: 'textarea',
            inputLabel: `Enter notice message for ${count} student(s):`,
            inputPlaceholder: 'Type your message here...',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Send',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to write a message!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoadingForAction('Sending notice...');
                setTimeout(() => {
                    hideLoadingForAction();
                    Swal.fire({
                        icon: 'success',
                        title: 'Sent!',
                        text: `Notice sent to ${count} student(s)`,
                        confirmButtonColor: '#4361ee'
                    }).then(() => {
                        clearSelection();
                    });
                }, 1500);
            }
        });
    }

    function handleBulkExit(selectAllCheckbox, selectedStudents) {
        const isAll = selectAllCheckbox && selectAllCheckbox.checked;
        const count = isAll ? 'ALL' : selectedStudents.length;
        
        Swal.fire({
            title: 'Enter Exit Date',
            input: 'date',
            inputLabel: `Select exit date for ${count} student(s):`,
            inputValue: new Date().toISOString().split('T')[0],
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Process Exit'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoadingForAction('Processing exits...');
                setTimeout(() => {
                    hideLoadingForAction();
                    Swal.fire({
                        icon: 'success',
                        title: 'Processed!',
                        text: `${count} student(s) marked as exited`,
                        confirmButtonColor: '#4361ee'
                    }).then(() => {
                        location.reload();
                    });
                }, 1500);
            }
        });
    }

    function showLoadingForAction(message) {
        const container = document.getElementById('bulkActionsContainer');
        if (!container) return;
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'bulk-action-loading';
        loadingDiv.innerHTML = `<div class="loading-spinner"></div><span class="loading-message ms-2">${message}</span>`;
        container.appendChild(loadingDiv);
        container.classList.add('processing');
    }

    function hideLoadingForAction() {
        const container = document.getElementById('bulkActionsContainer');
        if (!container) return;
        container.querySelector('.bulk-action-loading')?.remove();
        container.classList.remove('processing');
    }

    // ============================================
    // SORTING
    // ============================================
    function sortTable(column) {
        const sortBy = document.getElementById('sortBy');
        const sortOrder = document.getElementById('sortOrder');
        if (!sortBy || !sortOrder) return;

        const currentSortBy = sortBy.value;
        const currentSortOrder = sortOrder.value;

        let newSortOrder = 'asc';
        if (currentSortBy === column) {
            newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
        }

        sortBy.value = column;
        sortOrder.value = newSortOrder;

        storeSelectionState(); // persist selection before sort
        document.getElementById('filterForm').submit();
    }

    // ============================================
    // VIEW PANEL (jQuery dependent – keep as is)
    // ============================================
    function setupViewPanel() {
        $(document).on('click', '.view-button', function () {
            let hashId = $(this).data('id');
            $('#overlay').fadeIn(200);
            $.ajax({
                url: '/student-details/' + hashId,
                method: 'GET',
                success: function (response) {
                    $('#sidePanel').css('right', '0');
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Load Failed',
                        text: 'Failed to load student details',
                        confirmButtonColor: '#4361ee',
                        confirmButtonText: 'OK'
                    });
                    $('#overlay').fadeOut(200);
                }
            });
        });

        $('#closePanel, #overlay').click(function () {
            $('#sidePanel').css('right', '-100%');
            $('#overlay').fadeOut(200);
        });

        $(document).on('click', '.view-doc', function () {
            let filePath = $(this).data('file');
            let cacheBuster = '?t=' + new Date().getTime();
            let previewHtml = '';
            if (filePath.match(/\.(jpg|jpeg|png|gif|webp|bmp)$/i)) {
                previewHtml = `<img src="${filePath}${cacheBuster}" class="img-fluid" alt="Document" style="max-height: 80vh;">`;
            } else if (filePath.match(/\.(pdf)$/i)) {
                previewHtml = `<iframe src="${filePath}${cacheBuster}#view=fitH" width="100%" height="600px" frameborder="0"></iframe>`;
            } else {
                previewHtml = `<div class="p-3"><a href="${filePath}" target="_blank" class="btn btn-primary">Open Document</a></div>`;
            }
            $('#docPreview').html(previewHtml);
            $('#docModal').modal('show');
        });
    }

    // ============================================
    // MUTATION OBSERVER (for dynamic rows)
    // ============================================
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(mutation => {
            if (mutation.type === 'childList') {
                const newCheckboxes = document.querySelectorAll('.student-checkbox:not(.initialized)');
                if (newCheckboxes.length > 0) {
                    newCheckboxes.forEach(cb => {
                        cb.classList.add('initialized');
                        cb.addEventListener('change', function () {
                            if (isSelectAllActive && !this.checked) {
                                document.getElementById('selectAll').checked = false;
                                isSelectAllActive = false;
                                document.getElementById('bulkActionsContainer')?.classList.remove('select-all-active');
                            }
                            updateSelectionUI();
                        });
                    });
                    updateSelectionUI();
                }
            }
        });
    });

    const tableContainer = document.querySelector('.table-responsive');
    if (tableContainer) observer.observe(tableContainer, { childList: true, subtree: true });

    // ============================================
    // UTILITY
    // ============================================
    function formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }
    
    function viewSuspensionHistory(studentHashId, studentName) {
        $('#suspensionHistoryModal').modal('show');
        $('#suspensionHistoryContent').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-warning" role="status"></div>
                <p class="mt-2">Loading suspension history for ${studentName}...</p>
            </div>
        `);

        $.ajax({
            url: `/student/${studentHashId}/suspension-history`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    let html = `
                        <div class="mb-4">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="text-warning mb-0">${response.statistics.total_suspensions}</h5>
                                            <small class="text-muted">Total Suspensions</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="text-success mb-0">${response.statistics.total_unsuspensions}</h5>
                                            <small class="text-muted">Total Unsuspensions</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="${response.statistics.currently_suspended ? 'text-danger' : 'text-success'} mb-0">
                                                ${response.statistics.currently_suspended ? 'Suspended' : 'Active'}
                                            </h5>
                                            <small class="text-muted">Current Status</small>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <h6><i class="bi bi-list-ul me-2"></i>Suspension Logs</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Action</th>
                                        <th>Reason</th>
                                        <th>Performed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    if (response.logs.length === 0) {
                        html += `<tr><td colspan="4" class="text-center text-muted">No suspension records found</td></tr>`;
                    } else {
                        response.logs.forEach(log => {
                            const actionClass = log.action === 'suspend' ? 'text-warning' : 'text-success';
                            const actionIcon = log.action === 'suspend' ? '🔒' : '🔓';
                            html += `
                                <tr>
                                    <td><small>${new Date(log.created_at).toLocaleString()}</small></td>
                                    <td><span class="${actionClass}">${actionIcon} ${log.action.toUpperCase()}</span></td>
                                    <td><small>${log.reason || 'N/A'}</small></td>
                                    <td><small>${log.performed_by ? log.performed_by.name : 'System'}</small></td>
                                </tr>
                            `;
                        });
                    }

                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;

                    $('#suspensionHistoryContent').html(html);
                } else {
                    $('#suspensionHistoryContent').html(`
                        <div class="alert alert-danger">${response.message}</div>
                    `);
                }
            },
            error: function() {
                $('#suspensionHistoryContent').html(`
                    <div class="alert alert-danger">Error loading suspension history</div>
                `);
            }
        });
    }
    
    // SUSPEND STUDENT FUNCTION (Temporary block - different from exit)
    function suspendStudent(studentHashId, studentName) {
        Swal.fire({
            title: 'Suspend Student (Temporary)',
            html: `
                <p>Are you sure you want to <strong class="text-warning">TEMPORARILY SUSPEND</strong> <strong>${studentName}</strong>?</p>
                <p>This will:</p>
                <ul class="text-left">
                    <li><i class="bi bi-lock-fill text-warning"></i> <strong>BLOCK ALL CREDITS</strong> temporarily</li>
                    <li><i class="bi bi-clock-history"></i> Student can be unsuspended later</li>
                    <li><i class="bi bi-person-check"></i> Student remains enrolled in the system</li>
                </ul>
                <p class="text-muted small">Note: This is different from Exit (which is permanent).</p>
                <div class="mt-3">
                    <label for="suspensionReason" class="form-label">Suspension Reason <span class="text-danger">*</span>:</label>
                    <textarea id="suspensionReason" class="form-control" rows="3"
                        placeholder="Please provide a reason for temporary suspension..."></textarea>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Suspend Temporarily',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const suspensionReason = document.getElementById('suspensionReason').value;
                if (!suspensionReason || suspensionReason.trim().length < 3) {
                    Swal.showValidationMessage('Please provide a valid suspension reason (minimum 3 characters)');
                    return false;
                }
                return { suspensionReason: suspensionReason };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Suspending student and blocking credits',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: `/student/${studentHashId}/suspend`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        suspension_reason: result.value.suspensionReason
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Student Suspended',
                                text: response.message,
                                confirmButtonColor: '#f59e0b'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonColor: '#f59e0b'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while suspending the student.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#f59e0b'
                        });
                    }
                });
            }
        });
    }
    
    // UNSUSPEND STUDENT FUNCTION (Restore credits)
    function unsuspendStudent(studentHashId, studentName) {
        Swal.fire({
            title: 'Unsuspend Student',
            html: `
                <p>Are you sure you want to <strong class="text-success">UNSUSPEND</strong> <strong>${studentName}</strong>?</p>
                <p>This will:</p>
                <ul class="text-left">
                    <li><i class="bi bi-unlock-fill text-success"></i> <strong>RESTORE ALL CREDITS</strong> access</li>
                    <li><i class="bi bi-check-circle"></i> Student can use all services normally</li>
                    <li><i class="bi bi-arrow-repeat"></i> Student remains enrolled in the system</li>
                </ul>
                <div class="mt-3">
                    <label for="unsuspensionReason" class="form-label">Unsuspension Reason (Optional):</label>
                    <textarea id="unsuspensionReason" class="form-control" rows="3"
                        placeholder="Please provide a reason for unsuspending the student..."></textarea>
                    <small class="text-muted">This will be logged for audit purposes</small>
                </div>
                <div class="mt-3 alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    The student will be able to access credits immediately after unsuspension.
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Unsuspend Student',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const unsuspensionReason = document.getElementById('unsuspensionReason').value;
                return { unsuspensionReason: unsuspensionReason || null };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Restoring student credits access',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: `/student/${studentHashId}/unsuspend`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        unsuspension_reason: result.value.unsuspensionReason
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Student Unsuspended',
                                text: response.message,
                                confirmButtonColor: '#10b981'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonColor: '#10b981'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while unsuspending the student.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#10b981'
                        });
                    }
                });
            }
        });
    }
    
   
    window.exportSelection = function () {
        return {
            isSelectAllActive,
            selectedCount: document.querySelectorAll('.student-checkbox:checked').length,
            currentFilters: getCurrentFilters(),
            selectedIds: Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value)
        };
    };
</script>
@endsection