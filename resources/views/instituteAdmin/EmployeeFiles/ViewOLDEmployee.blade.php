@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --sp-primary: #4F46E5;
            --sp-primary-light: #818CF8;
            --sp-primary-dark: #3730A3;
            --sp-secondary: #7C3AED;
            --sp-accent: #0EA5E9;
            --sp-success: #10B981;
            --sp-danger: #EF4444;
            --sp-warning: #F59E0B;
            --sp-gray-50: #F8FAFC;
            --sp-gray-100: #F1F5F9;
            --sp-gray-200: #E2E8F0;
            --sp-gray-300: #CBD5E1;
            --sp-gray-400: #94A3B8;
            --sp-gray-500: #64748B;
            --sp-gray-600: #475569;
            --sp-gray-700: #334155;
            --sp-gray-800: #1E293B;
            --sp-gray-900: #0F172A;
            --sp-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            --sp-shadow: 0 20px 40px -12px rgba(79, 70, 229, 0.15);
            --sp-shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.05);
            --sp-radius: 16px;
            --sp-radius-sm: 10px;
        }

        .sp-wrapper {
            background: var(--sp-gray-50);
            padding: 20px 0 30px;
            min-height: 100vh;
        }

        .sp-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Top Header Card */
        .sp-page-header-card {
            background: #ffffff;
            border-radius: var(--sp-radius);
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: var(--sp-shadow-sm);
            border: 1px solid var(--sp-gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .sp-header-title h2 {
            font-size: 22px;
            font-weight: 800;
            color: var(--sp-gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sp-header-title h2 i {
            color: var(--sp-primary);
        }

        .sp-header-title p {
            margin: 4px 0 0;
            font-size: 13px;
            color: var(--sp-gray-500);
        }

        /* ----- left card ----- */
        .sp-left-card {
            border: none;
            border-radius: var(--sp-radius);
            box-shadow: var(--sp-shadow);
            overflow: hidden;
            background: #fff;
            height: 100%;
        }

        .sp-left-card .card-body {
            padding: 28px 20px 24px;
        }

        .sp-hero-wrap {
            text-align: center;
            position: relative;
            margin-bottom: 20px;
        }

        .sp-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.15);
            background: var(--sp-gray-100);
        }

        .sp-name {
            font-size: 22px;
            font-weight: 700;
            color: var(--sp-gray-800);
            margin-top: 12px;
            letter-spacing: -0.3px;
        }

        .sp-class-badge {
            display: inline-block;
            background: var(--sp-gradient);
            color: #fff;
            padding: 4px 18px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 4px;
            letter-spacing: 0.3px;
        }

        /* info list */
        .sp-info-list {
            list-style: none;
            padding: 0;
            margin: 16px 0 18px;
        }

        .sp-info-list li {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--sp-gray-100);
            font-size: 14px;
        }

        .sp-info-list li:last-child {
            border-bottom: none;
        }

        .il-label {
            color: var(--sp-gray-500);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .il-label i {
            color: var(--sp-primary);
            width: 18px;
            font-size: 15px;
        }

        .il-value {
            font-weight: 600;
            color: var(--sp-gray-800);
            text-align: right;
        }

        /* action buttons */
        .sp-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 6px 0 14px;
        }

        .sp-btn {
            flex: 1;
            min-width: 90px;
            padding: 10px 12px;
            border-radius: var(--sp-radius-sm);
            font-weight: 600;
            font-size: 13px;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            border: none;
            cursor: pointer;
        }

        .sp-btn-primary {
            background: var(--sp-gradient);
            color: #fff;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .sp-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
            color: #fff;
            text-decoration: none;
        }

        .sp-btn-outline {
            background: var(--sp-gray-100);
            color: var(--sp-gray-700);
            border: 1px solid var(--sp-gray-200);
        }

        .sp-btn-outline:hover {
            background: var(--sp-gray-200);
            color: var(--sp-gray-900);
            text-decoration: none;
        }

        /* ----- right tabs card ----- */
        .sp-tabs-card {
            border: none;
            border-radius: var(--sp-radius);
            box-shadow: var(--sp-shadow);
            overflow: hidden;
            background: #fff;
            height: 100%;
        }

        .sp-tabs-card .card-header {
            background: var(--sp-gray-50);
            border-bottom: 2px solid var(--sp-gray-200);
            padding: 0 16px;
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
        }

        .sp-tabs-card .nav-tabs {
            border-bottom: none;
            flex-wrap: wrap;
            gap: 2px;
        }

        .sp-tabs-card .nav-tabs .nav-link {
            border: none;
            border-radius: 8px 8px 0 0;
            padding: 12px 16px;
            font-weight: 600;
            font-size: 13px;
            color: var(--sp-gray-500);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            margin-bottom: -2px;
        }

        .sp-tabs-card .nav-tabs .nav-link i {
            font-size: 15px;
        }

        .sp-tabs-card .nav-tabs .nav-link:hover {
            color: var(--sp-primary);
            background: rgba(79, 70, 229, 0.04);
        }

        .sp-tabs-card .nav-tabs .nav-link.active {
            color: var(--sp-primary);
            background: #fff;
            border-bottom: 3px solid var(--sp-primary);
            font-weight: 700;
        }

        .sp-tabs-card .card-body {
            padding: 24px 28px;
        }

        /* ----- Enhanced profile sections ----- */
        .profile-section {
            margin-bottom: 28px;
            border-radius: var(--sp-radius-sm);
            overflow: hidden;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--sp-gray-200);
            transition: all 0.3s ease;
        }

        .profile-section:hover {
            border-color: var(--sp-primary-light);
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.08);
        }

        .profile-section-title {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 14px 20px;
            font-weight: 700;
            font-size: 15px;
            color: var(--sp-gray-700);
            border-bottom: 2px solid var(--sp-gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .profile-section-title-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile-section-title i {
            color: var(--sp-primary);
            font-size: 18px;
            width: 22px;
        }

        .profile-section .row {
            padding: 16px 20px 8px;
            margin: 0;
        }

        .info-row {
            display: flex;
            padding: 6px 0;
            font-size: 14px;
            border-bottom: 1px solid var(--sp-gray-100);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 160px;
            min-width: 160px;
            font-weight: 500;
            color: var(--sp-gray-500);
            flex-shrink: 0;
        }

        .info-value {
            font-weight: 500;
            color: var(--sp-gray-800);
            flex: 1;
            word-break: break-word;
        }

        /* ----- ENHANCED TABLE STYLES ----- */
        .sp-table-wrap {
            padding: 16px 20px;
            position: relative;
        }

        .sp-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .sp-table thead th {
            background: linear-gradient(135deg, #f1f5f9 0%, #e9edf4 100%);
            color: var(--sp-gray-700);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 14px 16px;
            border-bottom: 2px solid var(--sp-gray-300);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .sp-table thead th:first-child {
            border-top-left-radius: 10px;
        }

        .sp-table thead th:last-child {
            border-top-right-radius: 10px;
        }

        .sp-table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--sp-gray-100);
            color: var(--sp-gray-700);
            background: #fff;
            transition: background 0.15s ease;
        }

        .sp-table tbody tr:last-child td {
            border-bottom: none;
        }

        .sp-table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
        }

        .sp-table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
        }

        .sp-table tbody tr {
            transition: all 0.2s ease;
        }

        .sp-table tbody tr:hover td {
            background: #f8faff;
        }

        .sp-table tbody tr:nth-child(even) td {
            background: #fafcff;
        }

        .sp-table tbody tr:nth-child(even):hover td {
            background: #f5f8ff;
        }

        .sp-table .badge {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding: 5px 10px;
            border-radius: 6px;
        }

        .sp-table .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .sp-table .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .sp-table .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .sp-table .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .sp-table .badge-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .sp-table .badge-primary {
            background: #e0e7ff;
            color: #3730a3;
        }

        .sp-table .table-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .sp-table .btn-sm-action {
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .sp-table .btn-sm-action-primary {
            background: var(--sp-gradient);
            color: #fff;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
        }

        .sp-table .btn-sm-action-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            color: #fff;
        }

        .sp-table .btn-sm-action-outline {
            background: var(--sp-gray-100);
            color: var(--sp-gray-700);
            border: 1px solid var(--sp-gray-200);
        }

        .sp-table .btn-sm-action-outline:hover {
            background: var(--sp-gray-200);
            color: var(--sp-gray-900);
        }

        .sp-table .btn-sm-action-success {
            background: #10b981;
            color: #fff;
        }

        .sp-table .btn-sm-action-success:hover {
            background: #059669;
            color: #fff;
        }

        /* ----- ENHANCED DOCUMENTS SECTION ----- */
        .documents-grid {
            display: grid;
            /* grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); */
            gap: 16px;
            padding: 16px 20px;
        }

        .document-item {
            background: #ffffff;
            border: 1px solid var(--sp-gray-200);
            border-radius: 12px;
            padding: 16px 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            min-height: 64px;
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .document-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 4px 0 0 4px;
            background: var(--sp-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .document-item:hover {
            background: #ffffff;
            border-color: var(--sp-primary);
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.12);
            transform: translateY(-2px) scale(1.01);
        }

        .document-item:hover::before {
            opacity: 1;
        }

        .document-item .doc-info {
            min-width: 0;
            flex: 1;
        }

        .document-item .doc-info .doc-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #eef2ff;
            color: var(--sp-primary);
            font-size: 16px;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .document-item .doc-info .doc-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--sp-gray-800);
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .document-item .doc-info .doc-meta {
            color: var(--sp-gray-500);
            font-size: 11px;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .document-item .doc-info .doc-meta .meta-dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--sp-gray-300);
            display: inline-block;
        }

        .document-item .doc-info .doc-meta .status-available {
            color: var(--sp-success);
            font-weight: 600;
        }

        .document-item .doc-info .doc-meta .status-missing {
            color: var(--sp-gray-400);
        }

        .document-item .doc-actions {
            display: flex;
            gap: 6px;
            flex-shrink: 0;
        }

        .document-item .doc-actions .doc-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .document-item .doc-actions .doc-btn-view {
            background: var(--sp-gradient);
            color: #fff;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .document-item .doc-actions .doc-btn-view:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            color: #fff;
        }

        .document-item .doc-actions .doc-btn-download {
            background: var(--sp-gray-100);
            color: var(--sp-gray-700);
            border: 1px solid var(--sp-gray-200);
        }

        .document-item .doc-actions .doc-btn-download:hover {
            background: var(--sp-gray-200);
            color: var(--sp-gray-900);
        }

        .document-item .doc-status-badge {
            font-size: 10px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            background: var(--sp-gray-100);
            color: var(--sp-gray-500);
        }

        .document-item .doc-status-badge.available {
            background: #d1fae5;
            color: #065f46;
        }

        .document-item .doc-status-badge.missing {
            background: #fee2e2;
            color: #991b1b;
        }

        .document-item .doc-status-badge.uploaded {
            background: #dbeafe;
            color: #1e40af;
        }

        .doc-not-uploaded {
            font-size: 11px;
            color: var(--sp-gray-400);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ----- Empty state ----- */
        .beh-empty {
            padding: 40px 20px;
            text-align: center;
            color: var(--sp-gray-400);
            font-size: 15px;
            background: var(--sp-gray-50);
            border-radius: var(--sp-radius-sm);
            border: 2px dashed var(--sp-gray-300);
            transition: all 0.3s ease;
        }

        .beh-empty:hover {
            border-color: var(--sp-primary-light);
            background: #f8faff;
        }

        .beh-empty i {
            font-size: 36px;
            display: block;
            margin-bottom: 12px;
            color: var(--sp-gray-300);
            transition: all 0.3s ease;
        }

        .beh-empty:hover i {
            color: var(--sp-primary-light);
            transform: scale(1.05);
        }

        /* Calendar grid */
        .sp-cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            margin-bottom: 16px;
        }

        .sp-cal-cell {
            text-align: center;
            padding: 8px 4px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--sp-gray-600);
            background: var(--sp-gray-50);
            min-height: 52px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
        }

        .sp-cal-cell.cal-header {
            font-weight: 700;
            color: var(--sp-gray-500);
            background: transparent;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: none;
            min-height: auto;
            padding: 4px;
        }

        .sp-cal-cell.cal-present {
            background: #D1FAE5;
            border-color: #6EE7B7;
            color: #065F46;
        }

        .sp-cal-cell.cal-absent {
            background: #FEE2E2;
            border-color: #FCA5A5;
            color: #991B1B;
        }

        .sp-cal-cell.cal-late {
            background: #FEF3C7;
            border-color: #FCD34D;
            color: #92400E;
        }

        .sp-cred-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 6px;
        }

        .sp-employee-actions {
            display: grid;
            gap: 8px;
            margin-top: 12px;
            padding-top: 14px;
            border-top: 1px solid var(--sp-gray-200);
        }

        .sp-employee-action {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--sp-gray-200);
            border-radius: 9px;
            background: #fff;
            color: var(--sp-gray-700);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s ease;
        }

        .sp-employee-action i {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            background: #EEF2FF;
            color: var(--sp-primary);
        }

        .sp-employee-action:hover {
            color: var(--sp-primary-dark);
            border-color: var(--sp-primary-light);
            background: #F8FAFF;
            transform: translateX(2px);
            text-decoration: none;
        }

        .sp-employee-action.action-promote i {
            background: #ECFDF5;
            color: #059669;
        }

        .sp-employee-action.action-link i {
            background: #EFF6FF;
            color: #2563EB;
        }

        .btn-idcard {
            background: var(--sp-gradient);
            color: #fff !important;
            border: none;
            border-radius: var(--sp-radius-sm);
            font-weight: 600;
            font-size: 14px;
            padding: 10px 14px;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
        }

        .payroll-filter-bar {
            background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
            border: 1px solid #C7D2FE;
            border-radius: var(--sp-radius-sm);
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Timetable View Filter Buttons */
        .tt-filter-bar {
            background: var(--sp-gray-100);
            padding: 8px 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .btn-tt-view {
            border: none;
            background: transparent;
            color: var(--sp-gray-600);
            font-weight: 600;
            font-size: 13px;
            padding: 6px 16px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-tt-view.active {
            background: #ffffff;
            color: var(--sp-primary);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            font-weight: 700;
        }

        /* Official Payslip Modal Styling */
        .salary-slip-container-modal {
            max-width: 100%;
            background: white;
            border: 2px solid #333;
            padding: 0;
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .company-header-modal {
            background: linear-gradient(135deg, #1e3a8a, #3730a3);
            color: white;
            padding: 18px;
            text-align: center;
            border-bottom: 3px solid #fbbf24;
        }

        .company-name-modal {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }

        .slip-title-modal {
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0 0;
            padding: 6px 14px;
            background: #fbbf24;
            color: #1e3a8a;
            border-radius: 4px;
            display: inline-block;
        }

        .employee-section-modal {
            padding: 14px 18px;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .employee-grid-modal {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .employee-field-modal {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dotted #cbd5e1;
        }

        .salary-table-modal {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        .salary-table-modal th {
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            border: 1px solid #333;
        }

        .salary-table-modal td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .summary-section-modal {
            background: linear-gradient(135deg, #1e3a8a, #3730a3);
            color: white;
            padding: 16px;
            margin: 16px 0;
            border-radius: 8px;
        }

        .summary-grid-modal {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            text-align: center;
        }

        .final-amount-modal {
            background: #10b981;
            color: white;
            padding: 14px;
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            margin: 16px 0;
            border-radius: 8px;
        }

        /* Responsive table wrapper */
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 10px;
            border: 1px solid var(--sp-gray-200);
        }

        .table-responsive-custom .sp-table {
            min-width: 700px;
        }

        /* Document section header enhancement */
        .doc-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4px 20px 12px 20px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .doc-section-header .doc-count {
            font-size: 12px;
            color: var(--sp-gray-500);
            font-weight: 500;
        }

        .doc-section-header .doc-count strong {
            color: var(--sp-gray-800);
        }

        /* Stat cards in documents */
        .doc-stats {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            padding: 0 20px 12px 20px;
        }

        .doc-stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--sp-gray-600);
            background: var(--sp-gray-50);
            padding: 4px 12px;
            border-radius: 20px;
        }

        .doc-stat-item .stat-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .doc-stat-item .stat-dot.available {
            background: var(--sp-success);
        }

        .doc-stat-item .stat-dot.missing {
            background: var(--sp-danger);
        }

        .doc-stat-item .stat-dot.total {
            background: var(--sp-primary);
        }

        /* Enhanced card section for reimbursements */
        .reimb-policy-card {
            border-radius: 12px;
            border-left: 4px solid var(--sp-primary) !important;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid var(--sp-gray-200);
        }

        .reimb-policy-card:hover {
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.1);
            transform: translateY(-2px);
            border-color: var(--sp-primary-light);
        }

        /* ========== OFFICIAL DOCUMENTS STYLES FROM OLD FILE ========== */
        .view-toggle-group {
            display: flex;
            gap: 8px;
        }
        .view-toggle-btn {
            padding: 6px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
            font-size: 13px;
            color: #6b7280;
        }
        .view-toggle-btn:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }
        .view-toggle-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        .view-toggle-btn i {
            margin-right: 5px;
        }

        /* List view styles */
        .document-list-item {
            display: grid;
            grid-template-columns: 50px 1fr 140px 180px 1fr;
            align-items: center;
            padding: 12px 20px;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.2s;
            gap: 15px;
        }
        .document-list-item:hover {
            background: #f8fafc;
        }
        .document-list-item:last-child {
            border-bottom: none;
        }
        .document-list-item .doc-icon-small {
            font-size: 1.2rem;
            color: #3b82f6;
        }
        .document-list-item .doc-name {
            font-weight: 500;
            color: #1f2937;
        }
        .document-list-item .doc-status {
            text-align: center;
        }
        .document-list-item .doc-date {
            color: #6b7280;
            font-size: 13px;
        }
        .document-list-item .doc-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }
        .document-list-item .doc-actions .btn {
            padding: 4px 12px;
            font-size: 12px;
            white-space: nowrap;
        }
        .list-view-container {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }
        .list-header {
            display: grid;
            grid-template-columns: 50px 1fr 140px 180px 1fr;
            padding: 12px 20px;
            background: #f8fafc;
            border-bottom: 2px solid #e5e7eb;
            gap: 15px;
            font-weight: 600;
            font-size: 13px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Document category tabs */
        .doc-category-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px 0;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .doc-category-tab {
            padding: 8px 20px;
            border-radius: 20px;
            border: 2px solid #e5e7eb;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
        }
        .doc-category-tab:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }
        .doc-category-tab.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        .doc-category-tab .badge {
            background: rgba(255,255,255,0.2);
            color: white;
            margin-left: 5px;
        }
        .doc-category-tab:not(.active) .badge {
            background: #e5e7eb;
            color: #6b7280;
        }
        .doc-category-content {
            display: none;
        }
        .doc-category-content.active {
            display: block;
        }

        /* Document card styles for official documents */
        .document-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
            transition: all 0.3s;
            background: white;
            display: flex;
            flex-direction: column;
        }
        .document-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        .document-card .doc-icon {
            font-size: 2rem;
            color: #3b82f6;
        }
        .document-card .btn-group-custom {
            margin-top: auto;
        }
        .document-card .card-content {
            flex: 1;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-uploaded {
            background: #d1fae5;
            color: #065f46;
        }
        .status-not-generated {
            background: #f3f4f6;
            color: #6b7280;
        }

        .btn-group-custom {
            display: flex;
            gap: 5px;
            margin-top: 10px;
        }
        .btn-group-custom .btn {
            flex: 1;
            font-size: 13px;
            padding: 6px 12px;
        }
        .action-btn {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
            border: none;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            width: 100%;
            justify-content: center;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .action-btn-view {
            background: #3b82f6;
            color: white;
        }
        .action-btn-view:hover {
            background: #2563eb;
            color: white;
        }
        .action-btn-generate {
            background: #10b981;
            color: white;
        }
        .action-btn-generate:hover {
            background: #059669;
            color: white;
        }

        .letter-preview-body {
            min-height: 320px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            color: #1f2937;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.8;
            white-space: pre-wrap;
        }
        .letter-preview-body h1,
        .letter-preview-body h2,
        .letter-preview-body h3,
        .letter-preview-body h4,
        .letter-preview-body h5,
        .letter-preview-body h6 {
            color: #111827;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }
        .letter-preview-body p,
        .letter-preview-body li,
        .letter-preview-body div,
        .letter-preview-body span {
            color: #374151;
        }
        .letter-preview-body p {
            margin: 0 0 1rem;
        }
        .letter-preview-body ol,
        .letter-preview-body ul {
            padding-left: 1.35rem;
            margin: 0 0 1rem;
        }
        .letter-preview-body li {
            margin-bottom: 0.65rem;
        }
        .letter-preview-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        .letter-preview-body table th,
        .letter-preview-body table td {
            border: 1px solid #d1d5db;
            padding: 0.75rem 0.85rem;
            text-align: left;
        }
        .letter-preview-body table th {
            background: #f3f4f6;
        }
        .letter-preview-body blockquote {
            margin: 1rem 0;
            padding: 1rem 1.25rem;
            background: #f8fafc;
            border-left: 4px solid #3b82f6;
        }
        /* ========== END OFFICIAL DOCUMENTS STYLES ========== */
    </style>

    <div class="sp-wrapper">
        <div class="sp-container">

            {{-- ---------- PAGE HEADER CARD ---------- --}}
            <div class="sp-page-header-card">
                <div class="sp-header-title">
                    <h2>
                        <i class="bi bi-person-bounding-box"></i> Employee Profile
                    </h2>
                    <p>Comprehensive overview of staff profile, payroll, leave quotas, shift schedules, class timetable,
                        documents, and reimbursement policies.</p>
                </div>
                <div class="d-flex align-items-center gap-2 d-none">
                    <span class="badge bg-primary px-3 py-2 fs-6">Code: {{ $employee->employee_code ?? 'EMP' }}</span>
                    @if(Route::has('employees.index'))
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm fw-bold">
                            <i class="bi bi-arrow-left me-1"></i> Back to Employee Directory
                        </a>
                    @endif
                </div>
            </div>

            <div class="row g-4">

                {{-- ---------- LEFT COLUMN ---------- --}}
                <div class="col-md-4">
                    <div class="sp-left-card">
                        <div class="card-body">

                            {{-- Hero --}}
                            <div class="sp-hero-wrap">
                                @php
                                    $employeePhoto = $employee->profile_photo ?? $employee->passport_photo ?? $employee->photo ?? null;
                                    $fullName = $employee->name ?? 'Employee';
                                    $avatarUrl = !empty($employeePhoto)
                                        ? url('image/', $employeePhoto)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&size=120&background=4F46E5&color=fff&font-size=0.5';
                                @endphp
                                <img class="sp-avatar" src="{{ $avatarUrl }}" alt="{{ $fullName }}">

                                <div class="sp-name">{{ $fullName }}</div>
                                <span
                                    class="sp-class-badge">{{ $employee->designation_name ?? $employee->designation ?? 'Staff Member' }}</span>
                            </div>

                            {{-- Info list --}}
                            <ul class="sp-info-list">
                                <li>
                                    <span class="il-label"><i class="bi bi-person-badge-fill"></i> Employee Code</span>
                                    <span class="il-value">{{ $employee->employee_code ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-fingerprint"></i> Biometric ID</span>
                                    <span
                                        class="il-value">{{ $employee->biometric_id ?? $employee->employee_code ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-person-gear"></i> Role</span>
                                    <span class="il-value">{{ $employee->role ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-building"></i> Department</span>
                                    <span class="il-value">{{ $employee->department_name ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-gender-ambiguous"></i> Gender</span>
                                    <span class="il-value">{{ $employee->gender ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-droplet-fill"></i> Blood</span>
                                    <span class="il-value">
                                        @if($employee->blood_group)
                                            <span class="badge bg-danger">{{ $employee->blood_group }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </span>
                                </li>
                            </ul>

                            {{-- Actions --}}
                            <div class="sp-actions">
                                @if(Route::has('employees.edit'))
                                    <a href="{{ route('employees.edit', $employee->employee_id ?? '') }}"
                                        class="sp-btn sp-btn-primary">
                                        <i class="bi bi-pencil-fill"></i> Edit Profile
                                    </a>
                                @endif
                                <a href="#" class="sp-btn sp-btn-outline" onclick="window.print(); return false;">
                                    <i class="bi bi-printer-fill"></i> Print
                                </a>
                            </div>

                            {{-- Employee ID Card Action --}}
                            <div class="sp-cred-actions">
                                <button type="button" class="btn btn-idcard w-100 shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#employeeIdCardModal">
                                    <i class="bi bi-person-badge-fill me-1"></i> <b>View Employee ID Card</b>
                                </button>
                            </div>

                            <div class="sp-cred-actions">
                                <a href="{{ url('/employee-promotion/' . ($employee->id ?? '') . '/edit') }}"
                                    class="btn btn-idcard w-100 shadow-sm">
                                    <i class="bi bi-arrow-up-circle-fill"></i>
                                    <span>Promote / Change Role</span>
                                </a>
                            </div>

                            {{-- Barcode & QR --}}
                            <div class="sp-codes d-none">
                                <div class="sp-code-row">
                                    <span class="sp-code-label"><i class="bi bi-upc-scan"></i> Barcode</span>
                                    <img class="sp-code-img"
                                        src="https://barcode.tec-it.com/barcode.ashx?data={{ $employee->employee_code ?? 'N/A' }}&code=Code128&dpi=96&imagetype=Png"
                                        alt="Barcode">
                                </div>
                                <div class="sp-code-row">
                                    <span class="sp-code-label"><i class="bi bi-qr-code"></i> QR Code</span>
                                    <img class="sp-code-img sp-qr-img"
                                        src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $employee->employee_code ?? 'N/A' }}"
                                        alt="QR Code">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ---------- RIGHT COLUMN ---------- --}}
                <div class="col-md-8">

                    <div class="sp-tabs-card">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-profile" data-bs-toggle="pill" href="#pane-profile"
                                        role="tab">
                                        <i class="bi bi-person-fill"></i> Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-payroll" data-bs-toggle="pill" href="#pane-payroll"
                                        role="tab">
                                        <i class="bi bi-wallet-fill"></i> Payroll
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-leaves" data-bs-toggle="pill" href="#pane-leaves"
                                        role="tab">
                                        <i class="bi bi-calendar2-x"></i> Leaves
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-attendance" data-bs-toggle="pill" href="#pane-attendance"
                                        role="tab">
                                        <i class="bi bi-calendar-check-fill"></i> Attendance
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-shifts" data-bs-toggle="pill" href="#pane-shifts"
                                        role="tab">
                                        <i class="bi bi-clock-history"></i> Shifts
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-timetable" data-bs-toggle="pill" href="#pane-timetable"
                                        role="tab">
                                        <i class="bi bi-calendar3"></i> Timetable
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-documents" data-bs-toggle="pill" href="#pane-documents"
                                        role="tab">
                                        <i class="bi bi-folder-fill"></i> Documents
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active-official" id="tab-official-documents" data-bs-toggle="pill" href="#pane-official-documents"
                                        role="tab">
                                        <i class="bi bi-file-earmark-check"></i> Official Documents
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-reimbursements" data-bs-toggle="pill"
                                        href="#pane-reimbursements" role="tab">
                                        <i class="bi bi-cash-coin"></i> Reimbursements
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-career" data-bs-toggle="pill" href="#pane-career"
                                        role="tab">
                                        <i class="bi bi-graph-up-arrow"></i> Career
                                    </a>
                                </li>
                                <li class="nav-item d-none">
                                    <a class="nav-link" id="tab-appraisals" data-bs-toggle="pill" href="#pane-appraisals"
                                        role="tab">
                                        <i class="bi bi-star-fill"></i> Appraisals
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">

                            <div class="tab-content">

                                {{-- PANE: PROFILE --}}
                                <div class="tab-pane fade show active" id="pane-profile" role="tabpanel">

                                    {{-- Personal Information --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-person-badge-fill"></i> Personal Information
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Employee Code</span>
                                                    <span class="info-value"><span
                                                            class="badge bg-primary">{{ $employee->employee_code ?? 'N/A' }}</span></span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Full Name</span>
                                                    <span class="info-value">{{ $employee->name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Gender</span>
                                                    <span class="info-value">{{ $employee->gender ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Date of Birth</span>
                                                    <span
                                                        class="info-value">{{ $employee->dob ? date('d/m/Y', strtotime($employee->dob)) : 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Blood Group</span>
                                                    <span class="info-value">
                                                        @if($employee->blood_group)
                                                            <span class="badge bg-danger">{{ $employee->blood_group }}</span>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Email</span>
                                                    <span class="info-value">{{ $employee->email ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Mobile</span>
                                                    <span class="info-value">{{ $employee->mobile_number ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Alternate Phone</span>
                                                    <span
                                                        class="info-value">{{ $employee->alternate_phone ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Marital Status</span>
                                                    <span class="info-value">{{ $employee->marital_status ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Nationality</span>
                                                    <span class="info-value">{{ $employee->nationality ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Address --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-geo-alt-fill"></i> Address Details
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Address Line 1</span>
                                                    <span class="info-value">{{ $employee->addressline1 ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Address Line 2</span>
                                                    <span class="info-value">{{ $employee->addressline2 ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">City</span>
                                                    <span class="info-value">{{ $employee->city ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">State</span>
                                                    <span class="info-value">{{ $employee->state ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Pincode</span>
                                                    <span class="info-value">{{ $employee->pincode ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Emergency Contact --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-telephone-fill"></i> Emergency Contact
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Contact Person</span>
                                                    <span
                                                        class="info-value">{{ $employee->contact_person_name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Contact Number</span>
                                                    <span
                                                        class="info-value">{{ $employee->emergency_contact_number ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Relation</span>
                                                    <span
                                                        class="info-value">{{ $employee->relation_with_contact ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Health & Medical --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-heart-pulse-fill"></i> Health &amp; Medical
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Height</span>
                                                    <span class="info-value">N/A</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Weight</span>
                                                    <span class="info-value">N/A</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Medical History</span>
                                                    <span class="info-value">N/A</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Bank Details --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-bank2"></i> Bank Details
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Account Holder Name</span>
                                                    <span
                                                        class="info-value">{{ $employee->account_holder_name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Account Number</span>
                                                    <span class="info-value">{{ $employee->account_number ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Bank Name</span>
                                                    <span class="info-value">{{ $employee->bank_name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">IFSC Code</span>
                                                    <span class="info-value">{{ $employee->ifsc_code ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>{{-- /pane-profile --}}

                                {{-- PANE: PAYROLL --}}
                                <div class="tab-pane fade" id="pane-payroll" role="tabpanel">

                                    {{-- Month / Year Filter Bar --}}
                                    <div class="payroll-filter-bar">
                                        <div class="payroll-filter-title">
                                            <i class="bi bi-calendar-event-fill"></i> Select Month & Year for Salary Details
                                            & Payslip
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <select id="payrollMonthSelect" class="form-select form-select-sm fw-bold"
                                                style="width: 140px; border-color: #A5B4FC;"
                                                onchange="filterPayrollMonth()">
                                                @foreach(['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $mNum => $mName)
                                                    <option value="{{ $mNum }}" {{ date('m') == $mNum ? 'selected' : '' }}>
                                                        {{ $mName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <select id="payrollYearSelect" class="form-select form-select-sm fw-bold"
                                                style="width: 100px; border-color: #A5B4FC;"
                                                onchange="filterPayrollMonth()">
                                                @for($y = date('Y') - 3; $y <= date('Y') + 1; $y++)
                                                    <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    @php
                                        // Calculate accurate salary structure breakdown
                                        $salaryDetails = $salaryDetails ?? [];
                                        $basicSalMonthly = $salaryDetails['basic_salary'] ?? $employee->basic_salary ?? 0;
                                        $hraVal = $salaryDetails['hra'] ?? $employee->hra ?? 0;
                                        $daVal = $salaryDetails['da'] ?? $employee->da ?? 0;
                                        $specialVal = $salaryDetails['special_allowance'] ?? $employee->special_allowance ?? 0;
                                        $otherAllow = $salaryDetails['other_allowances'] ?? 0;
                                        $pfVal = $salaryDetails['pf_deduction'] ?? $employee->pf_deduction ?? 0;
                                        $ptVal = $salaryDetails['pt_deduction'] ?? $employee->pt_deduction ?? 0;
                                        $tdsVal = $salaryDetails['tds_deduction'] ?? 0;
                                        $calcFixedMonthly = $salaryDetails['monthly_fixed'] ?? 0;
                                        $calcTotalDeductions = $salaryDetails['total_deductions'] ?? ($pfVal + $ptVal + $tdsVal);
                                        $calcNetSal = $salaryDetails['net_salary'] ?? ($calcFixedMonthly - $calcTotalDeductions);
                                    @endphp

                                    {{-- Assigned Payroll Policy Banner --}}
                                    <div class="alert alert-indigo d-flex align-items-center justify-content-between mb-3 p-3"
                                        style="background: #EEF2FF; border: 1px solid #C7D2FE; border-radius: 12px; color: #3730A3;">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-shield-check fs-4"></i>
                                            <div>
                                                <strong>Assigned Payroll Policy:</strong>
                                                <span
                                                    class="fw-bold text-dark">{{ $payrollPolicy->policy_name ?? $salaryStructure->payroll_policy_id ?? 'Standard Company Payroll Policy' }}</span>
                                            </div>
                                        </div>
                                        <span class="badge bg-indigo text-white px-3 py-2" style="background:#4F46E5;">
                                            Employment: {{ $employee->employment_type ?? 'Full Time' }}
                                        </span>
                                    </div>

                                    {{-- Salary Structure Section --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-cash-stack"></i> Salary Structure (<span
                                                    id="structMonthYearText">{{ date('F Y') }}</span>)
                                            </div>
                                        </div>
                                        @php
                                            $slipEarnings = $salaryDetails['earnings_breakdown'] ?? [];
                                            $slipStandardDeductions = $salaryDetails['standard_deductions'] ?? [];
                                            $slipAttendanceDeductions = $salaryDetails['attendance_deductions'] ?? [];
                                            $slipOtherDeductions = $salaryDetails['other_deductions'] ?? [];
                                        @endphp
                                        <div class="row" id="salaryStructureDetailsContainer">
                                            <div class="col-md-6">
                                                <div class="info-row"><span class="info-label">Basic Salary
                                                        (Monthly)</span><span
                                                        class="info-value text-dark fw-bold">₹{{ number_format($basicSalMonthly, 2) }}</span>
                                                </div>
                                                @foreach($slipEarnings as $earningKey => $earningValue)
                                                    @if(is_numeric($earningValue) && (float) $earningValue > 0)
                                                        <div class="info-row"><span
                                                                class="info-label">{{ ucwords(str_replace(['_', '-'], ' ', $earningKey)) }}</span><span
                                                                class="info-value">₹{{ number_format($earningValue, 2) }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <div class="info-row"><span class="info-label">Gross Salary</span><span
                                                        class="info-value fw-bold text-primary">₹{{ number_format($calcFixedMonthly, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                @foreach($slipStandardDeductions as $deductionKey => $deductionValue)
                                                    @if(is_numeric($deductionValue) && (float) $deductionValue > 0)
                                                        <div class="info-row"><span
                                                                class="info-label">{{ ucwords(str_replace(['_', '-'], ' ', $deductionKey)) }}</span><span
                                                                class="info-value text-danger">₹{{ number_format($deductionValue, 2) }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @foreach($slipAttendanceDeductions as $deductionKey => $deductionValue)
                                                    @if(is_numeric($deductionValue) && (float) $deductionValue > 0)
                                                        <div class="info-row"><span
                                                                class="info-label">{{ ucwords(str_replace(['_', '-'], ' ', $deductionKey)) }}</span><span
                                                                class="info-value text-danger">₹{{ number_format($deductionValue, 2) }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @foreach($slipOtherDeductions as $otherDeduction)
                                                    @if(is_array($otherDeduction) && ($otherDeduction['amount'] ?? 0) > 0)
                                                        <div class="info-row"><span
                                                                class="info-label">{{ $otherDeduction['name'] ?? 'Other Deduction' }}</span><span
                                                                class="info-value text-danger">₹{{ number_format($otherDeduction['amount'], 2) }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <div class="info-row"><span class="info-label">Total Deductions</span><span
                                                        class="info-value fw-bold text-danger">₹{{ number_format($calcTotalDeductions, 2) }}</span>
                                                </div>
                                                <div class="info-row"><span class="info-label">Net Salary</span><span
                                                        class="info-value fw-bold text-success"
                                                        style="font-size:16px;">₹{{ number_format($calcNetSal, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Filtered Salary Slip Card --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-file-earmark-text-fill"></i> Salary Slip for Selected Period
                                                (<span id="slipMonthYearTitle">{{ date('F Y') }}</span>)
                                            </div>
                                            <span id="slipStatusBadge" class="badge bg-secondary">Not Generated</span>
                                        </div>
                                        <div id="slipDetailContainer" style="padding: 20px;">
                                            {{-- Rendered via JS dynamically --}}
                                        </div>
                                    </div>

                                    {{-- Payslip History Table --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-journal-text"></i> Salary Slips History
                                            </div>
                                        </div>
                                        <div class="sp-table-wrap">
                                            @if(isset($finalSalarySlips) && count($finalSalarySlips) > 0)
                                                <div class="table-responsive-custom">
                                                    <table class="sp-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Slip ID</th>
                                                                <th>Period</th>
                                                                <th class="text-end">Basic Salary</th>
                                                                <th class="text-end">Gross Salary</th>
                                                                <th class="text-end">Deductions</th>
                                                                <th class="text-end">Net Payable</th>
                                                                <th class="text-center">Status</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($finalSalarySlips as $slip)
                                                                @php
                                                                    $monthName = date("F", mktime(0, 0, 0, (int) $slip->month, 10));
                                                                    $paddedM = str_pad($slip->month, 2, '0', STR_PAD_LEFT);
                                                                    $key = $slip->year . '-' . $paddedM;
                                                                @endphp
                                                                <tr>
                                                                    <td><span
                                                                            class="badge bg-primary">{{ $slip->slip_id ?? 'SLIP-' . $slip->id }}</span>
                                                                    </td>
                                                                    <td><strong>{{ $monthName }} {{ $slip->year }}</strong></td>
                                                                    <td class="text-end fw-semibold">
                                                                        ₹{{ number_format($slip->basic_salary ?? 0, 2) }}</td>
                                                                    <td class="text-end text-primary fw-bold">
                                                                        ₹{{ number_format($slip->gross_salary ?? 0, 2) }}</td>
                                                                    <td class="text-end text-danger fw-semibold">
                                                                        ₹{{ number_format($slip->total_deductions ?? 0, 2) }}</td>
                                                                    <td class="text-end text-success fw-bold">
                                                                        ₹{{ number_format($slip->final_payable ?? $slip->net_salary ?? 0, 2) }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if(strtolower($slip->payment_status ?? '') == 'paid')
                                                                            <span class="badge badge-success">Paid</span>
                                                                        @else
                                                                            <span class="badge badge-primary">Generated</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div class="table-actions justify-content-center">
                                                                            <a href="{{ url('/final-salary-slips/' . ($slip->slip_id ?? 'SLIP-' . $slip->id)) }}"
                                                                                class="btn-sm-action btn-sm-action-primary">
                                                                                <i class="bi bi-eye-fill"></i> View
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="beh-empty">
                                                    <i class="bi bi-file-earmark-text-fill"></i>
                                                    No salary slips generated yet.
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>{{-- /pane-payroll --}}

                                {{-- PANE: LEAVES --}}
                                <div class="tab-pane fade" id="pane-leaves" role="tabpanel">
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-calendar2-x"></i> Leave Balance
                                            </div>
                                        </div>
                                        <div style="padding:16px 20px;">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <div class="card bg-light border-0 text-center p-3 shadow-sm">
                                                        <h4 class="fw-bold text-primary mb-1">
                                                            {{ $leaveBalance->casual_leave ?? $employee->casual_leave ?? 12 }}
                                                        </h4>
                                                        <span class="text-muted small font-weight-semibold">Casual
                                                            Leave</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-light border-0 text-center p-3 shadow-sm">
                                                        <h4 class="fw-bold text-success mb-1">
                                                            {{ $leaveBalance->sick_leave ?? $employee->sick_leave ?? 10 }}
                                                        </h4>
                                                        <span class="text-muted small font-weight-semibold">Sick
                                                            Leave</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-light border-0 text-center p-3 shadow-sm">
                                                        <h4 class="fw-bold text-warning mb-1">
                                                            {{ $leaveBalance->earned_leave ?? $employee->earned_leave ?? 15 }}
                                                        </h4>
                                                        <span class="text-muted small font-weight-semibold">Earned
                                                            Leave</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-light border-0 text-center p-3 shadow-sm">
                                                        <h4 class="fw-bold text-info mb-1">
                                                            {{ $leaveBalance->maternity_leave ?? 0 }}
                                                        </h4>
                                                        <span class="text-muted small font-weight-semibold">Other / 
                                                            Special</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Leave Applications / History --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-clock-history"></i> Leave Applications & History
                                            </div>
                                        </div>
                                        <div class="sp-table-wrap">
                                            @if(isset($leaveHistory) && count($leaveHistory) > 0)
                                                <div class="table-responsive-custom">
                                                    <table class="sp-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Date From</th>
                                                                <th>Date To</th>
                                                                <th>Leave Type</th>
                                                                <th>Total Days</th>
                                                                <th>Reason</th>
                                                                <th class="text-center">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($leaveHistory as $leave)
                                                                @php
                                                                    $startDateStr = $leave->start_date ?? $leave->from_date ?? null;
                                                                    $endDateStr = $leave->end_date ?? $leave->to_date ?? null;
                                                                    $totalDaysVal = $leave->total_days ?? $leave->days ?? 1;
                                                                    $statusStr = $leave->final_status ?? $leave->status ?? 'Pending';
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $startDateStr ? date('d M Y', strtotime($startDateStr)) : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $endDateStr ? date('d M Y', strtotime($endDateStr)) : 'N/A' }}
                                                                    </td>
                                                                    <td><span
                                                                            class="badge badge-primary">{{ $leave->leave_type ?? 'Casual' }}
                                                                            Leave</span></td>
                                                                    <td><strong>{{ $totalDaysVal }} day(s)</strong></td>
                                                                    <td>{{ $leave->reason ?? 'Urgent Work' }}</td>
                                                                    <td class="text-center">
                                                                        @php $st = strtolower($statusStr); @endphp
                                                                        @if($st == 'approved')
                                                                            <span class="badge badge-success">Approved</span>
                                                                        @elseif($st == 'rejected' || $st == 'cancelled')
                                                                            <span class="badge badge-danger">{{ ucfirst($st) }}</span>
                                                                        @else
                                                                            <span class="badge badge-warning">Pending</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="beh-empty">
                                                    <i class="bi bi-calendar2-x"></i>
                                                    No leave applications or history recorded.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- PANE: ATTENDANCE --}}
                                <div class="tab-pane fade" id="pane-attendance" role="tabpanel">
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-calendar-check-fill"></i> Attendance Summary
                                            </div>
                                        </div>
                                        <div style="padding:16px 20px;">
                                            <div class="row g-3 mb-4">
                                                <div class="col-md-3">
                                                    <div class="card bg-light border-0 text-center p-3 shadow-sm">
                                                        <h4 class="fw-bold text-success mb-1">
                                                            {{ $employee->present_days ?? 0 }}
                                                        </h4>
                                                        <span class="text-muted small">Present Days</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-light border-0 text-center p-3 shadow-sm">
                                                        <h4 class="fw-bold text-danger mb-1">
                                                            {{ $employee->absent_days ?? 0 }}
                                                        </h4>
                                                        <span class="text-muted small">Absent Days</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-light border-0 text-center p-3 shadow-sm">
                                                        <h4 class="fw-bold text-warning mb-1">
                                                            {{ $employee->late_days ?? 0 }}
                                                        </h4>
                                                        <span class="text-muted small">Late Days</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-light border-0 text-center p-3 shadow-sm">
                                                        <h4 class="fw-bold text-primary mb-1">
                                                            {{ $employee->attendance_percentage ?? 0 }}%
                                                        </h4>
                                                        <span class="text-muted small">Attendance Rate</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Attendance Calendar Nav --}}
                                            <div
                                                class="att-month-nav bg-light p-2 rounded border mb-3 d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        onclick="prevMonth()">
                                                        <i class="bi bi-chevron-left"></i> Prev
                                                    </button>
                                                    <span class="att-month-title mb-0" id="attMonthYearTitle">
                                                        <i class="bi bi-calendar3 me-1"
                                                            style="color:var(--sp-primary);"></i>
                                                        {{ date('F Y') }}
                                                    </span>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        onclick="nextMonth()">
                                                        Next <i class="bi bi-chevron-right"></i>
                                                    </button>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <select id="attMonthSelect" class="form-select form-select-sm"
                                                        onchange="renderAttendanceCalendar()" style="width:130px;">
                                                        @foreach(['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $mNum => $mName)
                                                            <option value="{{ $mNum }}" {{ date('m') == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select id="attYearSelect" class="form-select form-select-sm"
                                                        onchange="renderAttendanceCalendar()" style="width:100px;">
                                                        @for($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                                                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>
                                                                {{ $y }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="sp-cal-grid" id="attCalGrid">
                                                {{-- Populated via Javascript --}}
                                            </div>

                                            <div class="att-legend mt-3">
                                                <span class="leg-item"><span class="leg-dot dot-present"></span>
                                                    Present</span>
                                                <span class="leg-item"><span class="leg-dot dot-absent"></span>
                                                    Absent</span>
                                                <span class="leg-item"><span class="leg-dot dot-late"></span> Late</span>
                                                <span class="leg-item"><span class="leg-dot dot-unmarked"></span> Absent /
                                                    Unmarked</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- PANE: SHIFTS --}}
                                <div class="tab-pane fade" id="pane-shifts" role="tabpanel">
                                    {{-- Active Shift Banner --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-clock-history"></i> Current Active Shift
                                            </div>
                                            <span class="badge badge-success">Active</span>
                                        </div>
                                        <div style="padding:20px;">
                                            @php
                                                $shiftObj = $effectiveShift['shift'] ?? null;
                                            @endphp
                                            @if($shiftObj)
                                                <div class="card border-0 bg-light p-3 shadow-sm">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4 border-end">
                                                            <h5 class="fw-bold text-primary mb-1">
                                                                {{ $shiftObj->shift_name ?? 'General Shift' }}
                                                            </h5>
                                                            <span
                                                                class="badge bg-secondary mb-2">{{ ucfirst($effectiveShift['type'] ?? 'Assigned') }}
                                                                Shift</span>
                                                        </div>
                                                        <div class="col-md-8 ps-md-4">
                                                            <div class="row g-2">
                                                                <div class="col-6 col-md-3">
                                                                    <small class="text-muted d-block font-weight-bold">Start
                                                                        Time</small>
                                                                    <strong
                                                                        class="text-dark">{{ $shiftObj->formatted_start_time ?? $shiftObj->start_time ?? '09:00 AM' }}</strong>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small class="text-muted d-block font-weight-bold">End
                                                                        Time</small>
                                                                    <strong
                                                                        class="text-dark">{{ $shiftObj->formatted_end_time ?? $shiftObj->end_time ?? '05:00 PM' }}</strong>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small class="text-muted d-block font-weight-bold">Break
                                                                        Duration</small>
                                                                    <strong
                                                                        class="text-dark">{{ $shiftObj->break_minutes ?? 60 }}
                                                                        mins</strong>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <small class="text-muted d-block font-weight-bold">Working
                                                                        Hours</small>
                                                                    <strong
                                                                        class="text-success">{{ number_format($shiftObj->duration_in_hours ?? 8, 1) }}
                                                                        hrs</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="beh-empty">
                                                    <i class="bi bi-clock"></i>
                                                    No explicit shift assigned. Standard office hours apply.
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Shift History --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-journal-check"></i> Shift Assignment History
                                            </div>
                                        </div>
                                        <div class="sp-table-wrap">
                                            @if(isset($employeeShifts) && count($employeeShifts) > 0)
                                                <div class="table-responsive-custom">
                                                    <table class="sp-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Shift Name</th>
                                                                <th>Timings</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                                <th>Assignment Type</th>
                                                                <th class="text-center">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($employeeShifts as $empShift)
                                                                @php
                                                                    $sObj = $empShift->shift;
                                                                    $stDate = $sObj->start_date ?? $empShift->created_at ?? null;
                                                                    $endDate = $sObj->end_date ?? null;
                                                                    $isActive = $sObj->is_active ?? true;
                                                                @endphp
                                                                <tr>
                                                                    <td><strong>{{ $sObj->shift_name ?? 'Employee Shift' }}</strong>
                                                                    </td>
                                                                    <td>{{ $sObj->formatted_start_time ?? '09:00 AM' }} -
                                                                        {{ $sObj->formatted_end_time ?? '05:00 PM' }}
                                                                    </td>
                                                                    <td>{{ $stDate ? date('d M Y', strtotime($stDate)) : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $endDate ? date('d M Y', strtotime($endDate)) : 'Ongoing' }}
                                                                    </td>
                                                                    <td><span
                                                                            class="badge badge-primary">{{ $empShift->assignment_type == 'direct' ? 'Direct Assignment' : 'Department Based' }}</span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($isActive)
                                                                            <span class="badge badge-success">Active</span>
                                                                        @else
                                                                            <span class="badge badge-secondary">Inactive</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="beh-empty">
                                                    <i class="bi bi-clock-history"></i>
                                                    No prior shift assignment history.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- PANE: TIMETABLE --}}
                                <div class="tab-pane fade" id="pane-timetable" role="tabpanel">
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-calendar3"></i> Employee Class Schedule & Timetable
                                            </div>
                                        </div>
                                        <div style="padding:16px 20px;">

                                            {{-- Timetable View Filter Buttons --}}
                                            <div class="tt-filter-bar">
                                                <span class="fw-bold text-dark me-2"><i
                                                        class="bi bi-funnel-fill text-primary me-1"></i> Filter View:</span>
                                                <button type="button" class="btn-tt-view active" id="btnTtDay"
                                                    onclick="switchTimetableFilter('day')">
                                                    <i class="bi bi-calendar-day me-1"></i> Today / Day View
                                                </button>
                                                <button type="button" class="btn-tt-view" id="btnTtWeek"
                                                    onclick="switchTimetableFilter('week')">
                                                    <i class="bi bi-calendar-week me-1"></i> Weekly Grid
                                                </button>
                                                <button type="button" class="btn-tt-view" id="btnTtMonth"
                                                    onclick="switchTimetableFilter('month')">
                                                    <i class="bi bi-calendar-month me-1"></i> Monthly View
                                                </button>
                                                <button type="button" class="btn-tt-view" id="btnTtAll"
                                                    onclick="switchTimetableFilter('all')">
                                                    <i class="bi bi-table me-1"></i> All Assigned Schedule
                                                </button>
                                            </div>

                                            {{-- Container for Timetable Dynamic Rendering --}}
                                            <div id="timetableDynamicContainer">
                                                {{-- Populated by JavaScript --}}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                {{-- PANE: DOCUMENTS --}}
                                <div class="tab-pane fade" id="pane-documents" role="tabpanel">
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-folder-fill"></i> Core Employee Documents
                                            </div>
                                            <span class="badge bg-primary" id="docCountBadge">Loading...</span>
                                        </div>

                                        @php
                                            $docMap = [
                                                'Aadhaar Card' => $employee->aadhaar_card ?? null,
                                                'PAN Card' => $employee->pan_card ?? null,
                                                'Address Proof File' => $employee->address_proof_file ?? null,
                                                'Driving License' => $employee->driving_license ?? null,
                                                'Passport Photo' => $employee->passport_photo ?? null,
                                                'Resume' => $employee->resume ?? null,
                                                'Offer Letter' => $employee->offer_letter ?? null,
                                                'Experience Certificate' => $employee->experience_certificate ?? null,
                                                'Educational Certificates' => $employee->educational_certificates ?? null,
                                            ];

                                            function buildDocUrl($rawPath)
                                            {
                                                if (empty($rawPath))
                                                    return null;
                                                return url('image/' .$rawPath);
                                            }

                                            $totalDocs = count($docMap);
                                            $availableDocs = 0;
                                            foreach ($docMap as $rawPath) {
                                                if (!empty($rawPath))
                                                    $availableDocs++;
                                            }
                                        @endphp

                                        {{-- Document Statistics --}}
                                        <div class="doc-stats">
                                            <span class="doc-stat-item">
                                                <span class="stat-dot total"></span> Total:
                                                <strong>{{ $totalDocs }}</strong>
                                            </span>
                                            <span class="doc-stat-item">
                                                <span class="stat-dot available"></span> Uploaded:
                                                <strong>{{ $availableDocs }}</strong>
                                            </span>
                                            <span class="doc-stat-item">
                                                <span class="stat-dot missing"></span> Missing:
                                                <strong>{{ $totalDocs - $availableDocs }}</strong>
                                            </span>
                                        </div>

                                        <div class="documents-grid">
                                            @foreach($docMap as $label => $rawPath)
                                                @php
                                                    $docUrl = buildDocUrl($rawPath);
                                                    $isAvailable = !empty($docUrl);
                                                @endphp
                                                <div class="document-item {{ $isAvailable ? '' : 'opacity-75' }}">
                                                    <div class="doc-info d-flex align-items-center">
                                                        <span class="doc-icon">
                                                            <i
                                                                class="bi {{ $isAvailable ? 'bi-file-earmark-pdf-fill text-danger' : 'bi-file-earmark-x-fill text-muted' }}"></i>
                                                        </span>
                                                        <div>
                                                            <span class="doc-name" title="{{ $label }}">{{ $label }}</span>
                                                            <div class="doc-meta">
                                                                @if($isAvailable)
                                                                    <span class="status-available">
                                                                        <i class="bi bi-check-circle-fill"></i> Uploaded
                                                                    </span>
                                                                @else
                                                                    <span class="status-missing">
                                                                        <i class="bi bi-hourglass-split"></i> Not uploaded
                                                                    </span>
                                                                @endif
                                                                <!-- <span class="meta-dot"></span> -->
                                                                <span>{{ $isAvailable ? '' : 'Pending upload' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if($isAvailable)
                                                        <div class="doc-actions">
                                                            <a href="{{ $docUrl }}" class="doc-btn doc-btn-view" target="_blank">
                                                                <i class="bi bi-eye-fill"></i> View
                                                            </a>
                                                            <a href="{{ $docUrl }}" class="doc-btn doc-btn-download" download>
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <span class="doc-status-badge missing">
                                                            <i class="bi bi-exclamation-circle"></i> Missing
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Additional Uploaded Documents --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-files"></i> Additional Documents
                                            </div>
                                            @php
                                                $extraDocs = $employee->additional_documents ?? [];
                                                if (is_string($extraDocs)) {
                                                    $extraDocs = json_decode($extraDocs, true) ?? [];
                                                }
                                                $extraCount = is_array($extraDocs) ? count($extraDocs) : 0;
                                            @endphp
                                            <span class="badge bg-primary">{{ $extraCount }} file(s)</span>
                                        </div>
                                        <div style="padding: 16px 20px;">
                                            @if((is_array($extraDocs) && count($extraDocs) > 0) || (isset($employeeFiles) && count($employeeFiles) > 0))
                                                <div class="documents-grid p-0">
                                                    @if(is_array($extraDocs))
                                                        @foreach($extraDocs as $idx => $extra)
                                                            @php
                                                                $name = is_array($extra) ? ($extra['name'] ?? 'Document ' . ($idx + 1)) : (is_string($extra) ? basename($extra) : 'Document ' . ($idx + 1));
                                                                $path = is_array($extra) ? ($extra['file'] ?? $extra['path'] ?? '') : (is_string($extra) ? $extra : '');
                                                                $u = buildDocUrl($path);
                                                                $isAvailable = !empty($u);
                                                            @endphp
                                                            <div class="document-item {{ $isAvailable ? '' : 'opacity-75' }}">
                                                                <div class="doc-info d-flex align-items-center">
                                                                    <span class="doc-icon">
                                                                        <i
                                                                            class="bi {{ $isAvailable ? 'bi-file-earmark-text-fill text-primary' : 'bi-file-earmark-x-fill text-muted' }}"></i>
                                                                    </span>
                                                                    <div>
                                                                        <span class="doc-name" title="{{ $name }}">{{ $name }}</span>
                                                                        <div class="doc-meta">
                                                                            @if($isAvailable)
                                                                                <span class="status-available">
                                                                                    <i class="bi bi-check-circle-fill"></i> Uploaded
                                                                                </span>
                                                                            @else
                                                                                <span class="status-missing">
                                                                                    <i class="bi bi-hourglass-split"></i> No file
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @if($isAvailable)
                                                                    <div class="doc-actions">
                                                                        <a href="{{ $u }}" class="doc-btn doc-btn-view" target="_blank">
                                                                            <i class="bi bi-eye-fill"></i> View
                                                                        </a>
                                                                        <a href="{{ $u }}" class="doc-btn doc-btn-download" download>
                                                                            <i class="bi bi-download"></i>
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <span class="doc-status-badge missing">
                                                                        <i class="bi bi-exclamation-circle"></i> N/A
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @else
                                                <div class="beh-empty">
                                                    <i class="bi bi-folder-x"></i>
                                                    No additional documents uploaded.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- PANE: OFFICIAL DOCUMENTS (COPIED FROM OLD FILE) --}}
                                <div class="tab-pane fade" id="pane-official-documents" role="tabpanel">
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-file-earmark-check"></i> Official Documents
                                            </div>
                                            <div>
                                                <div class="view-toggle-group">
                                                    <button class="view-toggle-btn active" onclick="toggleView('card')" id="cardViewBtn">
                                                        <i class="bi bi-grid-3x3-gap-fill"></i> Cards
                                                    </button>
                                                    <button class="view-toggle-btn" onclick="toggleView('list')" id="listViewBtn">
                                                        <i class="bi bi-list-ul"></i> List
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="section-body" style="padding:20px;">

                                            @php
                                                use App\Models\LetterTemplate;
                                                use App\Models\Letter;
                                                use Illuminate\Support\Facades\DB;

                                                $templates = LetterTemplate::all();

                                                $officialDocumentTypes = DB::table('official_documents')
                                                    ->where('status', 'active')
                                                    ->select('official_documenttype_id', 'official_document_type')
                                                    ->get();

                                                $categoryOrder = ['onboarding', 'joining', 'promotion', 'salary', 'disciplinary', 'exit', 'other'];
                                                $categories = [];

                                                foreach ($categoryOrder as $categoryKey) {
                                                    $matchedType = null;

                                                    foreach ($officialDocumentTypes as $docType) {
                                                        $normalizedDocType = strtolower(trim((string) ($docType->official_document_type ?? '')));
                                                        if ($normalizedDocType === '' || strpos($normalizedDocType, 'experience') !== false || strpos($normalizedDocType, 'exp') !== false) {
                                                            continue;
                                                        }

                                                        $candidateCategoryKey = 'other';
                                                        if (strpos($normalizedDocType, 'onboard') !== false || strpos($normalizedDocType, 'induction') !== false) {
                                                            $candidateCategoryKey = 'onboarding';
                                                        } elseif (strpos($normalizedDocType, 'join') !== false) {
                                                            $candidateCategoryKey = 'joining';
                                                        } elseif (strpos($normalizedDocType, 'promotion') !== false) {
                                                            $candidateCategoryKey = 'promotion';
                                                        } elseif (strpos($normalizedDocType, 'salary') !== false || strpos($normalizedDocType, 'increment') !== false || strpos($normalizedDocType, 'slip') !== false) {
                                                            $candidateCategoryKey = 'salary';
                                                        } elseif (strpos($normalizedDocType, 'disciplin') !== false || strpos($normalizedDocType, 'warning') !== false || strpos($normalizedDocType, 'complaint') !== false || strpos($normalizedDocType, 'cause') !== false || strpos($normalizedDocType, 'notice') !== false) {
                                                            $candidateCategoryKey = 'disciplinary';
                                                        } elseif (strpos($normalizedDocType, 'exit') !== false || strpos($normalizedDocType, 'termination') !== false || strpos($normalizedDocType, 'noc') !== false || strpos($normalizedDocType, 'reliev') !== false || strpos($normalizedDocType, 'resign') !== false) {
                                                            $candidateCategoryKey = 'exit';
                                                        }

                                                        if ($candidateCategoryKey === $categoryKey) {
                                                            $matchedType = $docType;
                                                            break;
                                                        }
                                                    }

                                                    if ($matchedType) {
                                                        $categories[(string) $matchedType->official_documenttype_id] = [
                                                            'label' => $matchedType->official_document_type ?? ucfirst($categoryKey),
                                                            'icon' => 'bi-file-earmark-text',
                                                            'color' => '#3b82f6'
                                                        ];
                                                    }
                                                }

                                                foreach ($officialDocumentTypes as $docType) {
                                                    $categoryKey = (string) ($docType->official_documenttype_id ?? '');
                                                    if ($categoryKey === '' || array_key_exists($categoryKey, $categories)) {
                                                        continue;
                                                    }

                                                    $normalizedDocType = strtolower(trim((string) ($docType->official_document_type ?? '')));
                                                    if ($normalizedDocType === '' || strpos($normalizedDocType, 'experience') !== false || strpos($normalizedDocType, 'exp') !== false) {
                                                        continue;
                                                    }

                                                    $label = $docType->official_document_type ?? 'Other';

                                                    $categories[$categoryKey] = [
                                                        'label' => $label,
                                                        'icon' => 'bi-file-earmark-text',
                                                        'color' => '#3b82f6'
                                                    ];
                                                }

                                                if (empty($categories)) {
                                                    $categories['other'] = ['label' => 'Other', 'icon' => 'bi-file-earmark', 'color' => '#6b7280'];
                                                }

                                                $categoryTemplates = [];
                                                foreach ($categories as $ck => $cv) {
                                                    $categoryTemplates[$ck] = [];
                                                }

                                                foreach ($templates as $tpl) {
                                                    $categoryKey = $tpl->official_documenttype_id ? (string) $tpl->official_documenttype_id : 'other';
                                                    if (!isset($categoryTemplates[$categoryKey])) {
                                                        $categoryTemplates[$categoryKey] = [];
                                                    }
                                                    $categoryTemplates[$categoryKey][] = $tpl;
                                                }

                                                if (!isset($categoryTemplates['other'])) {
                                                    $categoryTemplates['other'] = [];
                                                }

                                                $employeeLetters = Letter::where('employee_id', $employee->id)->get()->keyBy('template_key');
                                            @endphp

                                            <!-- Category Tabs -->
                                            <div class="doc-category-tabs">
                                                @foreach($categories as $categoryKey => $category)
                                                    <button class="doc-category-tab {{ $loop->first ? 'active' : '' }}" 
                                                            data-category="{{ $categoryKey }}">
                                                        <i class="bi {{ $category['icon'] }}"></i> {{ $category['label'] }}
                                                        <span class="badge">
                                                            {{ count($categoryTemplates[$categoryKey] ?? []) }}
                                                        </span>
                                                    </button>
                                                @endforeach
                                            </div>

                                            <!-- Category Content -->
                                            @foreach($categories as $categoryKey => $category)
                                                <div class="doc-category-content {{ $loop->first ? 'active' : '' }}" id="category-{{ $categoryKey }}">
                                                    <!-- Card View -->
                                                    <div id="cardView" class="documents-view">
                                                        <div class="row">
                                                            @php
                                                                $catTemplates = $categoryTemplates[$categoryKey] ?? [];
                                                            @endphp
                                                            @if(count($catTemplates) > 0)
                                                                @foreach($catTemplates as $tpl)
                                                                    @php
                                                                        $templateKey = $tpl->key ?? ($tpl->id ?? '');
                                                                        $letter = $employeeLetters->get($templateKey) ?? null;
                                                                        $isGenerated = !empty($letter);
                                                                        $statusClass = $isGenerated ? 'status-uploaded' : 'status-not-generated';
                                                                        $statusText = $isGenerated ? 'Generated' : 'Not Generated';
                                                                        $subtitle = $isGenerated ? 'Generated on ' . ($letter->created_at ? $letter->created_at->format('d-m-Y') : '') : 'Generate document';
                                                                        
                                                                        // Set icon based on category
                                                                        $icon = 'bi-file-earmark-text';
                                                                        $color = '#6b7280';
                                                                        if ($categoryKey == 'onboarding') { $icon = 'bi-file-earmark-person'; $color = '#3b82f6'; }
                                                                        elseif ($categoryKey == 'joining') { $icon = 'bi-file-earmark-check'; $color = '#10b981'; }
                                                                        elseif ($categoryKey == 'promotion') { $icon = 'bi-file-earmark-arrow-up'; $color = '#8b5cf6'; }
                                                                        elseif ($categoryKey == 'salary') { $icon = 'bi-file-earmark-bar-graph'; $color = '#f59e0b'; }
                                                                        elseif ($categoryKey == 'disciplinary') { $icon = 'bi-file-earmark-exclamation'; $color = '#f97316'; }
                                                                        elseif ($categoryKey == 'exit') { $icon = 'bi-file-earmark-x'; $color = '#ef4444'; }
                                                                        elseif ($categoryKey == 'other') { $icon = 'bi-file-earmark'; $color = '#6b7280'; }
                                                                    @endphp
                                                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-4">
                                                                        <div class="document-card" data-template-key="{{ $templateKey }}">
                                                                            <div class="card-content">
                                                                                <div class="d-flex justify-content-between align-items-start">
                                                                                    <div>
                                                                                        <i class="bi {{ $icon }} doc-icon" style="color: {{ $color }}"></i>
                                                                                    </div> 
                                                                                    <div> 
                                                                                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <h6 class="mt-2 mb-1">{{ $tpl->title ?? $templateKey }}</h6>
                                                                                <small class="text-muted">{{ $subtitle }}</small>
                                                                                @if($isGenerated && !empty($letter->reference_id))
                                                                                    <div class="mt-2">
                                                                                        <small class="text-muted">
                                                                                            <strong>Ref ID:</strong> {{ $letter->reference_id }}
                                                                                        </small>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                            <div class="btn-group-custom">
                                                                                @if($isGenerated)
                                                                                    <button class="action-btn action-btn-view" type="button" onclick="viewLetter({{ $letter->id }})">
                                                                                        <i class="bi bi-eye"></i> View
                                                                                    </button>
                                                                                @else
                                                                                    <button class="action-btn action-btn-generate" type="button" onclick="generateDocument('{{ $tpl->title ?? $templateKey }}', '{{ $templateKey }}', '{{ $tpl->letter_id ?? $tpl->id }}')">
                                                                                        <i class="bi bi-file-earmark-pdf"></i> Generate
                                                                                    </button>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="col-12 text-center py-4">
                                                                    <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #d1d5db;"></i>
                                                                    <p class="text-muted mt-2">No documents in this category</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- List View -->
                                                    <div id="listView" class="documents-view" style="display:none;">
                                                        <div class="list-view-container">
                                                            <div class="list-header">
                                                                <span>#</span>
                                                                <span>Document Name</span>
                                                                <span class="text-center">Status</span>
                                                                <span>Generated Date</span>
                                                                <span class="text-end">Actions</span>
                                                            </div>
                                                            @php $index = 1; @endphp
                                                            @foreach($categoryTemplates[$categoryKey] ?? [] as $tpl)
                                                                @php
                                                                    $templateKey = $tpl->key ?? ($tpl->id ?? '');
                                                                    $letter = $employeeLetters->get($templateKey) ?? null;
                                                                    $isGenerated = !empty($letter);
                                                                    $statusClass = $isGenerated ? 'status-uploaded' : 'status-not-generated';
                                                                    $statusText = $isGenerated ? 'Generated' : 'Not Generated';
                                                                @endphp
                                                                <div class="document-list-item">
                                                                    <span class="doc-icon-small">
                                                                        <i class="bi bi-file-earmark-text"></i>
                                                                    </span>
                                                                    <span class="doc-name">{{ $tpl->title ?? $templateKey }}</span>
                                                                    <span class="doc-status">
                                                                        <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                                                                    </span>
                                                                    <span class="doc-date">
                                                                        {{ $isGenerated ? ($letter->created_at ? $letter->created_at->format('d-m-Y') : 'N/A') : '-' }}
                                                                    </span>
                                                                    <div class="doc-actions">
                                                                        @if($isGenerated)
                                                                            <button class="btn btn-primary btn-sm action-btn-view" type="button" onclick="viewLetter({{ $letter->id }})">
                                                                                <i class="bi bi-eye"></i> View
                                                                            </button>
                                                                        @else
                                                                            <button class="btn btn-success btn-sm action-btn-generate" type="button" onclick="generateDocument('{{ $tpl->title ?? $templateKey }}', '{{ $templateKey }}', '{{ $tpl->letter_id ?? $tpl->id }}')">
                                                                                <i class="bi bi-file-earmark-pdf"></i> Generate
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @php $index++; @endphp
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>

                                {{-- PANE: REIMBURSEMENTS --}}
                                <div class="tab-pane fade" id="pane-reimbursements" role="tabpanel">

                                    {{-- Assigned Reimbursement Policies Section --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-shield-lock-fill"></i> Assigned Reimbursement Policies
                                            </div>
                                            <span
                                                class="badge bg-primary">{{ isset($reimbursementPolicies) ? count($reimbursementPolicies) : 0 }}
                                                policy(s)</span>
                                        </div>
                                        <div style="padding:16px 20px;">
                                            @if(isset($reimbursementPolicies) && count($reimbursementPolicies) > 0)
                                                <div class="row g-3">
                                                    @foreach($reimbursementPolicies as $rPol)
                                                        <div class="col-md-6">
                                                            <div class="reimb-policy-card p-3">
                                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                                    <h6 class="fw-bold text-dark mb-0">
                                                                        {{ $rPol->policy_name ?? 'Reimbursement Policy' }}
                                                                    </h6>
                                                                    <span
                                                                        class="badge bg-primary">{{ ucfirst($rPol->policy_category ?? 'General') }}</span>
                                                                </div>
                                                                <div class="small mb-2">
                                                                    <strong>Status:</strong>
                                                                    @php $policyStatus = strtolower($rPol->status ?? 'active'); @endphp
                                                                    <span
                                                                        class="badge {{ $policyStatus === 'active' || $policyStatus === 'approved' ? 'badge-success' : 'badge-secondary' }}">
                                                                        {{ ucfirst($policyStatus) }}
                                                                    </span>
                                                                </div>
                                                                <div class="small text-muted mb-2">
                                                                    <strong>Assignment Type:</strong>
                                                                    {{ ucfirst($rPol->assignment_type ?? 'Individual') }}
                                                                    @if(!empty($rPol->department_name))
                                                                        <span class="text-primary">({{ $rPol->department_name }})</span>
                                                                    @elseif(!empty($rPol->designation_name))
                                                                        <span
                                                                            class="text-primary">({{ $rPol->designation_name }})</span>
                                                                    @endif
                                                                </div>
                                                                <div class="row g-2 text-xs" style="font-size:12px;">
                                                                    <div class="col-6">
                                                                        <span class="text-muted d-block">Effective From</span>
                                                                        <strong
                                                                            class="text-dark">{{ $rPol->effective_from ? date('d M Y', strtotime($rPol->effective_from)) : 'N/A' }}</strong>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <span class="text-muted d-block">Effective Until</span>
                                                                        <strong
                                                                            class="text-dark">{{ $rPol->effective_until ? date('d M Y', strtotime($rPol->effective_until)) : 'Ongoing' }}</strong>
                                                                    </div>
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-outline-primary mt-3"
                                                                    onclick="showReimbursementPolicyDetails({{ $loop->index }})">
                                                                    <i class="bi bi-eye me-1"></i> View Policy Details
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="beh-empty">
                                                    <i class="bi bi-shield-x"></i>
                                                    No reimbursement policy is assigned to this employee.
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Reimbursement Claims Table --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-cash-coin"></i> Reimbursement Claims & History
                                            </div>
                                            <span
                                                class="badge bg-primary">{{ isset($reimbursementClaimGroups) ? count($reimbursementClaimGroups) : 0 }}
                                                claim(s)</span>
                                        </div>
                                        <div class="sp-table-wrap">
                                            @if(isset($reimbursementClaimGroups) && count($reimbursementClaimGroups) > 0)
                                                <div class="table-responsive-custom">
                                                    <table class="sp-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Claim Ref ID</th>
                                                                <th>Policy / Category</th>
                                                                <th>Expense Date</th>
                                                                <th class="text-end">Claim Amount</th>
                                                                <th class="text-end">Approved Amount</th>
                                                                <th class="text-center">Status</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($reimbursementClaimGroups as $claimGroup)
                                                                @php $claim = $claimGroup['representative']; @endphp
                                                                <tr>
                                                                    <td><span
                                                                            class="badge bg-primary">{{ $claimGroup['master_request_id'] ?? $claim->reimbursement_request_id ?? 'CLM-' . $claim->id }}</span>
                                                                        @if($claimGroup['claim_count'] > 1)
                                                                            <span
                                                                                class="badge badge-info ms-1">{{ $claimGroup['claim_count'] }}
                                                                                claims</span>
                                                                        @endif
                                                                    </td>
                                                                    <td><strong>{{ $claim->policy_name ?? $claim->policy_category ?? 'Expense Claim' }}</strong>
                                                                    </td>
                                                                    <td>{{ $claim->expense_date ? date('d M Y', strtotime($claim->expense_date)) : ($claim->submission_date ? date('d M Y', strtotime($claim->submission_date)) : 'N/A') }}
                                                                    </td>
                                                                    <td class="text-end fw-bold text-dark">
                                                                        ₹{{ number_format($claimGroup['total_claim_amount'] ?? 0, 2) }}
                                                                    </td>
                                                                    <td class="text-end text-success fw-bold">
                                                                        ₹{{ number_format($claimGroup['total_approved_amount'] ?? 0, 2) }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @php $st = $claimGroup['status'] ?? 'pending'; @endphp
                                                                        @if($st == 'approved')
                                                                            <span class="badge badge-success">Approved</span>
                                                                        @elseif($st == 'rejected')
                                                                            <span class="badge badge-danger">Rejected</span>
                                                                        @else
                                                                            <span class="badge badge-warning">Pending</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button"
                                                                            class="btn-sm-action btn-sm-action-primary"
                                                                            onclick="showReimbursementClaimDetails({{ $loop->index }})">
                                                                            <i class="bi bi-eye me-1"></i> View
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="beh-empty">
                                                    <i class="bi bi-cash-coin"></i>
                                                    No reimbursement claims submitted by this employee.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- PANE: CAREER --}}
                                <div class="tab-pane fade" id="pane-career" role="tabpanel">
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-graph-up-arrow"></i> Career Progression
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Employment Type</span>
                                                    <span
                                                        class="info-value">{{ $employee->employment_type ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Department</span>
                                                    <span
                                                        class="info-value">{{ $employee->department_name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Designation</span>
                                                    <span
                                                        class="info-value">{{ $employee->designation_name ?? $employee->designation ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Date of Joining</span>
                                                    <span
                                                        class="info-value">{{ $employee->doj ? date('d/m/Y', strtotime($employee->doj)) : 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Date of Confirmation</span>
                                                    <span
                                                        class="info-value">{{ $employee->doc ? date('d/m/Y', strtotime($employee->doc)) : 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Years of Service</span>
                                                    <span class="info-value">{{ $employee->years_of_service ?? 0 }}
                                                        years</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- PANE: APPRAISALS --}}
                                <div class="tab-pane fade" id="pane-appraisals" role="tabpanel">
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <div class="profile-section-title-left">
                                                <i class="bi bi-star-fill"></i> Performance Appraisals
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Last Appraisal Date</span>
                                                    <span
                                                        class="info-value">{{ $employee->last_appraisal_date ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Performance Rating</span>
                                                    <span class="info-value">
                                                        @if(isset($employee->performance_rating))
                                                            <span
                                                                class="badge bg-warning text-dark">{{ $employee->performance_rating }}
                                                                / 5</span>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Next Appraisal Due</span>
                                                    <span
                                                        class="info-value">{{ $employee->next_appraisal_date ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Rating Scale</span>
                                                    <span class="info-value">1 - 5 Stars</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>{{-- /tab-content --}}

                        </div>{{-- /card-body --}}

                    </div>{{-- /sp-tabs-card --}}

                </div>{{-- /col-md-8 --}}

            </div>{{-- /row --}}

        </div>{{-- /sp-container --}}
    </div>{{-- /sp-wrapper --}}

    {{-- Official Salary Slip Modal --}}
    <div class="modal fade" id="viewSalarySlipModal" tabindex="-1" aria-labelledby="viewSalarySlipModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"
                style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #1E3A8A 0%, #3730A3 100%);">
                    <h5 class="modal-title fw-bold" id="viewSalarySlipModalLabel">
                        <i class="bi bi-file-earmark-text-fill me-2"></i> Official Salary Payslip - <span
                            id="modalSlipMonthYear"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light" id="printableSalarySlipContent">
                    {{-- Rendered dynamically via JavaScript --}}
                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printSalarySlip()">
                        <i class="bi bi-printer-fill me-1"></i> Print Payslip
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Employee ID Card Modal --}}
    <div class="modal fade" id="employeeIdCardModal" tabindex="-1" aria-labelledby="employeeIdCardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"
                style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #3730A3 0%, #4F46E5 100%);">
                    <h5 class="modal-title fw-bold" id="employeeIdCardModalLabel">
                        <i class="bi bi-person-badge-fill me-2"></i> Employee Identification Card
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light text-center">
                    @php
                        $cardImg = !empty($employee->profile_photo)
                            ? url('storage/' . ltrim(str_replace(['public/', 'storage/'], '', $employee->profile_photo), '/'))
                            : 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&size=150&background=4F46E5&color=fff';

                        $instLogo = !empty($idCardData['institute_logo'] ?? null)
                            ? url('storage/' . ltrim(str_replace(['public/', 'storage/'], '', $idCardData['institute_logo']), '/'))
                            : null;
                    @endphp
                    <div id="printableEmpIdCard" class="card shadow-lg mx-auto"
                        style="max-width: 420px; border-radius: 14px; overflow: hidden; border: 1px solid #CBD5E1; background: #ffffff;">
                        {{-- Card Header --}}
                        <div
                            style="background: {{ $idCardSettings['header_bg_color'] ?? '#3730A3' }}; color: {{ $idCardSettings['header_font_color'] ?? '#ffffff' }}; padding: 16px 14px; text-align: center;">
                            @if($instLogo)
                                <img src="{{ $instLogo }}" alt="Logo"
                                    style="max-height: 48px; object-fit: contain; margin-bottom: 6px; background: #fff; padding: 2px; border-radius: 4px;">
                            @endif
                            <h6 class="fw-bold mb-0 text-uppercase" style="font-size: 15px; letter-spacing: 0.5px;">
                                {{ $idCardData['institute_name'] ?? 'Institute Name' }}
                            </h6>
                            <p class="mb-0 small opacity-75" style="font-size: 11px;">
                                {{ $idCardData['institute_address'] ?? '' }}
                            </p>
                            <div class="mt-2"
                                style="background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px; display: inline-block; font-size: 11px; font-weight: 700; letter-spacing: 1px;">
                                {{ $idCardSettings['card_title'] ?? 'EMPLOYEE IDENTIFICATION CARD' }}
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-3 text-start" style="font-size: 13px;">
                            <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                                <img src="{{ $cardImg }}" alt="{{ $fullName }}"
                                    style="width: 90px; height: 90px; border-radius: 10px; object-fit: cover; border: 3px solid #E2E8F0; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 17px;">{{ $fullName }}</h5>
                                    <div class="badge bg-primary mb-1" style="font-size: 11px;">EMP:
                                        {{ $employee->employee_code ?? 'N/A' }}
                                    </div>
                                    <div class="text-muted small"><strong>Designation:</strong>
                                        {{ $employee->designation_name ?? $employee->designation ?? 'N/A' }}
                                    </div>
                                    <div class="text-muted small"><strong>Department:</strong>
                                        {{ $employee->department_name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <span class="text-muted d-block small">Employee Code</span>
                                    <strong class="text-dark">{{ $employee->employee_code ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Role</span>
                                    <strong class="text-dark">{{ $employee->role ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Date of Joining</span>
                                    <strong
                                        class="text-dark">{{ $employee->doj ? date('d/m/Y', strtotime($employee->doj)) : 'N/A' }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Blood Group</span>
                                    <strong class="text-danger">{{ $employee->blood_group ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Mobile</span>
                                    <strong class="text-dark">{{ $employee->mobile_number ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block small">Email</span>
                                    <strong class="text-dark">{{ $employee->email ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted d-block small">Address</span>
                                    <span
                                        class="text-dark small">{{ trim(($employee->addressline1 ?? '') . ' ' . ($employee->city ?? '') . ' ' . ($employee->state ?? '')) ?: 'N/A' }}</span>
                                </div>
                            </div>

                            {{-- Footer with Barcode & QR Code --}}
                            <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                                <div>
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $employee->employee_code ?? 'N/A' }}"
                                        alt="QR Code" style="height: 50px;">
                                </div>
                                <div class="text-center">
                                    <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $employee->employee_code ?? 'N/A' }}&code=Code128&dpi=96&imagetype=Png"
                                        alt="Barcode" style="height: 32px; width: auto; max-width: 140px;">
                                    <div style="font-size: 9px; color: #64748B;">{{ $employee->employee_code ?? '' }}</div>
                                </div>
                                <div class="text-end" style="font-size: 10px; color: #64748B;">
                                    <div style="height: 25px;"></div>
                                    <div class="border-top pt-1 fw-bold">
                                        {{ $idCardSettings['signature_text'] ?? "Authorized Signature" }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card Bottom Bar --}}
                        <div style="background: {{ $idCardSettings['footer_bg_color'] ?? '#3730A3' }}; color: {{ $idCardSettings['footer_font_color'] ?? '#ffffff' }}; padding: 6px 12px; font-size: 10px;"
                            class="d-flex justify-content-between">
                            <span>Valid: {{ date('Y') }}</span>
                            <span>Official Employee ID</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printEmpCard()">
                        <i class="bi bi-printer-fill me-1"></i> Print Card
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Letter Preview Modal --}}
    <div class="modal fade" id="letterPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="letterModalTitle">Letter Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="letterModalMeta" class="mb-3"></div>
                    <h6 id="letterModalHeading" class="fw-bold mb-3"></h6>
                    <div id="letterModalBody" class="letter-preview-body bg-white rounded shadow-sm p-4"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @php
        $empBasicDetails = [
            'name' => $employee->name ?? 'Employee',
            'code' => $employee->employee_code ?? 'EMP',
            'designation' => $employee->designation_name ?? $employee->designation ?? 'Staff',
            'department' => $employee->department_name ?? 'N/A',
            'bank_name' => $employee->account_holder_name ?? $employee->bank_name ?? 'N/A',
            'account_number' => $employee->account_number ?? 'N/A',
            'ifsc_code' => $employee->ifsc_code ?? 'N/A',
            'basic_salary' => $basicSalMonthly,
            'hra' => $hraVal,
            'da' => $daVal,
            'special_allowance' => $specialVal,
            'pf_deduction' => $pfVal,
            'pt_deduction' => $ptVal,
            'net_salary' => $calcNetSal,
        ];
    @endphp

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Document count badge update
        $(document).ready(function () {
            var totalDocs = {{ $totalDocs ?? 0 }};
            var availableDocs = {{ $availableDocs ?? 0 }};
            $('#docCountBadge').text(availableDocs + '/' + totalDocs + ' uploaded');
        });

        var attendanceDataMap = @json($attendanceByDate ?? []);
        var slipsByMonthYearMap = @json($slipsByMonthYear ?? []);
        var defaultSalaryStructure = @json($salaryStructure ?? null);
        var employeeBasicDetails = @json($empBasicDetails ?? []);
        var instituteNameStr = @json($idCardData['institute_name'] ?? 'Institute');
        var timetableEventsList = @json($timetableEvents ?? []);
        var salarySlipBaseUrl = @json(url('/final-salary-slips'));
        var structureSalaryDetails = @json($structureSalaryDetails ?? []);
        var reimbursementPoliciesList = @json($reimbursementPolicies ?? []);
        var reimbursementClaimGroupsList = @json($reimbursementClaimGroups ?? []);

        // ... rest of your JavaScript remains exactly the same ...
        function escapeReimbursementHtml(value) {
            return $('<div>').text(value == null ? 'N/A' : value).html();
        }

        function reimbursementLabel(value) {
            return String(value || '')
                .replace(/_/g, ' ')
                .replace(/\b\w/g, function (letter) { return letter.toUpperCase(); });
        }

        function reimbursementValue(value) {
            if (value === null || value === undefined || value === '') return 'N/A';
            if (typeof value === 'boolean') return value ? 'Yes' : 'No';
            if (typeof value === 'string' && /^\d{4}-\d{2}-\d{2}T/.test(value)) {
                return new Date(value).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' });
            }
            return String(value);
        }

        function renderPolicyDetailSection(title, values, nested) {
            if (!values || typeof values !== 'object') return '';
            var cardClass = nested ? 'border rounded-3 p-3 mb-3 bg-light' : 'border rounded-3 p-3 mb-3 bg-white';
            var html = '<div class="' + cardClass + '">';
            if (title) html += '<h6 class="fw-bold text-primary mb-3">' + escapeReimbursementHtml(title) + '</h6>';
            html += '<div class="row g-3">';
            Object.keys(values).forEach(function (key) {
                var value = values[key];
                if (value && typeof value === 'object' && !Array.isArray(value)) {
                    html += '<div class="col-12">' + renderPolicyDetailSection(reimbursementLabel(key), value, true) + '</div>';
                } else if (Array.isArray(value)) {
                    html += '<div class="col-12">' + renderPolicyDetailSection(reimbursementLabel(key), value, true) + '</div>';
                } else {
                    html += '<div class="col-md-6"><div class="text-muted small">' + escapeReimbursementHtml(reimbursementLabel(key)) + '</div><div class="fw-semibold text-dark">' + escapeReimbursementHtml(reimbursementValue(value)) + '</div></div>';
                }
            });
            return html + '</div></div>';
        }

        function renderPolicyDetails(details) {
            if (!Array.isArray(details)) details = details && typeof details === 'object' ? [details] : [];
            var html = '';
            details.forEach(function (section) {
                if (!section || typeof section !== 'object') return;
                Object.keys(section).forEach(function (sectionName) {
                    html += renderPolicyDetailSection(reimbursementLabel(sectionName), section[sectionName], false);
                });
            });
            return html || '<div class="text-muted">No additional policy rules configured.</div>';
        }

        function renderAllowedRanges(ranges) {
            if (ranges && !Array.isArray(ranges) && typeof ranges === 'object') {
                ranges = Object.keys(ranges).map(function (name) {
                    var range = ranges[name];
                    return Object.assign({ name: name }, range && typeof range === 'object' ? range : { value: range });
                });
            }

            if (!Array.isArray(ranges) || ranges.length === 0) {
                return '<div class="text-muted">No allowance ranges configured.</div>';
            }
            var html = '<div class="row g-3">';
            ranges.forEach(function (range) {
                html += '<div class="col-md-6"><div class="border rounded-3 p-3 h-100 bg-light">';
                html += '<div class="d-flex justify-content-between align-items-center mb-2"><strong>' + escapeReimbursementHtml(range.name || 'Allowance Range') + '</strong><span class="badge bg-primary">' + escapeReimbursementHtml(range.type || 'Rule') + '</span></div>';
                html += '<div class="row g-2 small">';
                Object.keys(range).forEach(function (key) {
                    if (key === 'name' || key === 'type') return;
                    var value = range[key];
                    var isAmount = key === 'min' || key === 'max';
                    html += '<div class="col-6"><span class="text-muted d-block">' + escapeReimbursementHtml(reimbursementLabel(key)) + '</span><strong>' + (isAmount ? '₹' + Number(value || 0).toFixed(2) : escapeReimbursementHtml(reimbursementValue(value))) + '</strong></div>';
                });
                html += '</div>';
                if (range.is_overridden) html += '<span class="badge bg-warning text-dark mt-3">Overridden</span>';
                html += '</div></div>';
            });
            return html + '</div>';
        }

        function showReimbursementPolicyDetails(index) {
            var policy = reimbursementPoliciesList[index];
            if (!policy) return;

            var details = policy.policy_details || [];
            var ranges = policy.allowed_ranges || [];
            var html = '<div class="mb-3"><h6 class="fw-bold">' + escapeReimbursementHtml(policy.policy_name || 'Reimbursement Policy') + '</h6>';
            html += '<span class="badge bg-primary me-1">' + escapeReimbursementHtml(policy.policy_category || 'General') + '</span>';
            html += '<span class="badge ' + ((policy.status || '').toLowerCase() === 'active' ? 'bg-success' : 'bg-secondary') + '">' + escapeReimbursementHtml(policy.status || 'Active') + '</span></div>';
            html += '<div class="row g-2 small mb-3">';
            html += '<div class="col-md-6"><strong>Assignment:</strong> ' + escapeReimbursementHtml(policy.assignment_type || 'Individual') + '</div>';
            html += '<div class="col-md-6"><strong>Assigned To:</strong> ' + escapeReimbursementHtml(policy.name || policy.department_name || policy.designation_name || 'Employee') + '</div>';
            html += '<div class="col-md-6"><strong>Approval:</strong> ' + escapeReimbursementHtml(policy.approval_type || 'N/A') + '</div>';
            html += '<div class="col-md-6"><strong>Effective From:</strong> ' + escapeReimbursementHtml(policy.effective_from || 'N/A') + '</div>';
            html += '<div class="col-md-6"><strong>Effective Until:</strong> ' + escapeReimbursementHtml(policy.effective_until || 'Ongoing') + '</div></div>';
            html += '<h6 class="fw-bold mb-3">Policy Rules</h6>' + renderPolicyDetails(details);
            html += '<h6 class="fw-bold mb-3">Allowed Ranges</h6>' + renderAllowedRanges(ranges);
            $('#reimbursementDetailTitle').text('Policy Details');
            $('#reimbursementDetailBody').html(html);
            $('#reimbursementDetailModal').modal('show');
        }

        function showReimbursementClaimDetails(index) {
            var group = reimbursementClaimGroupsList[index];
            if (!group) return;

            var html = '<div class="d-flex justify-content-between align-items-center mb-3">';
            html += '<strong>Request: ' + escapeReimbursementHtml(group.master_request_id || group.request_key) + '</strong>';
            html += '<span class="badge ' + (group.status === 'approved' ? 'bg-success' : (group.status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark')) + '">' + escapeReimbursementHtml(group.status || 'pending') + '</span></div>';
            html += '<div class="row g-2 mb-3"><div class="col-md-6"><strong>Total Claimed:</strong> ₹' + Number(group.total_claim_amount || 0).toFixed(2) + '</div>';
            html += '<div class="col-md-6"><strong>Total Approved:</strong> ₹' + Number(group.total_approved_amount || 0).toFixed(2) + '</div></div>';
            html += '<div class="table-responsive"><table class="table table-sm table-bordered"><thead><tr><th>Claim</th><th>Policy</th><th>Expense Date</th><th>Claimed</th><th>Approved</th><th>Status</th></tr></thead><tbody>';
            (group.claims || []).forEach(function (claim) {
                var status = String(claim.status || 'pending').toLowerCase();
                html += '<tr><td>' + escapeReimbursementHtml(claim.reimbursement_request_id || ('CLM-' + claim.id)) + '</td>';
                html += '<td>' + escapeReimbursementHtml(claim.policy_name || claim.policy_category || 'Expense Claim') + '</td>';
                html += '<td>' + escapeReimbursementHtml(claim.expense_date || claim.submission_date || 'N/A') + '</td>';
                html += '<td>₹' + Number(claim.claim_amount || 0).toFixed(2) + '</td><td>₹' + Number(claim.approved_amount || claim.calculated_amount || 0).toFixed(2) + '</td>';
                html += '<td>' + escapeReimbursementHtml(status) + '</td></tr>';
            });
            html += '</tbody></table></div>';
            $('#reimbursementDetailTitle').text(group.master_request_id ? 'Master Request Details' : 'Claim Details');
            $('#reimbursementDetailBody').html(html);
            $('#reimbursementDetailModal').modal('show');
        }

        // Ensure it's an array
        if (!Array.isArray(timetableEventsList)) {
            timetableEventsList = [];
        }

        // Diagnostic check
        $(document).ready(function () {
            console.log('Timetable data check:');
            console.log('Number of events:', timetableEventsList.length);
            if (timetableEventsList.length > 0) {
                console.log('First event:', timetableEventsList[0]);
                console.log('Event type:', timetableEventsList[0].event_type);
                console.log('Subject name:', timetableEventsList[0].main_subject_name || timetableEventsList[0].subject_display_name);
                console.log('Days of week:', timetableEventsList[0].days_of_week);
                console.log('Start time:', timetableEventsList[0].start_time);
                console.log('Frequency:', timetableEventsList[0].frequency);
            }
        });

        $(document).ready(function () {
            // Tab hash persistence
            var hash = window.location.hash;
            if (hash) {
                var target = $('#employeeTabs a[href="' + hash + '"]');
                if (target.length) {
                    target.tab('show');
                }
            }
            $('#employeeTabs a').on('shown.bs.tab', function (e) {
                history.replaceState(null, null, e.target.hash);
            });

            // Initial renders
            renderAttendanceCalendar();
            filterPayrollMonth();
            renderTimetable('day');

            // Category tab switching
            $('.doc-category-tab').click(function(){
                $('.doc-category-tab').removeClass('active');
                $(this).addClass('active');
                
                let category = $(this).data('category');
                $('.doc-category-content').removeClass('active');
                $('#category-' + category).addClass('active');
            });
        });


        function filterPayrollMonth() {
            var mVal = $('#payrollMonthSelect').val();
            var yVal = $('#payrollYearSelect').val();
            var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var monthName = monthNames[parseInt(mVal) - 1];

            $('#structMonthYearText').text(monthName + ' ' + yVal);
            $('#slipMonthYearTitle').text(monthName + ' ' + yVal);

            var key = yVal + '-' + mVal;
            var slip = slipsByMonthYearMap[key] || null;
            renderSalaryStructureDetails(slip ? {
                basic_salary: slip.basic_salary,
                monthly_fixed: slip.gross_salary,
                net_salary: slip.final_payable || slip.net_salary,
                total_deductions: slip.total_deductions,
                earnings_breakdown: slip.earnings_breakdown || {},
                standard_deductions: slip.standard_deductions || {},
                attendance_deductions: slip.attendance_deductions || {},
                other_deductions: slip.other_deductions || []
            } : structureSalaryDetails);

            var html = '';
            if (slip) {
                $('#slipStatusBadge').removeClass().addClass('badge bg-success').text(slip.payment_status ? slip.payment_status.toUpperCase() : 'GENERATED');

                html += '<div class="row align-items-center mb-3">';
                html += '  <div class="col-md-6">';
                html += '    <h5 class="fw-bold text-dark mb-1">Salary Slip Generated</h5>';
                html += '    <span class="text-muted small">Slip ID: <strong class="text-primary">' + (slip.slip_id || ('SLIP-' + slip.id)) + '</strong></span>';
                html += '  </div>';
                html += '  <div class="col-md-6 text-md-end mt-2 mt-md-0">';
                html += '    <a href="' + salarySlipBaseUrl + '/' + encodeURIComponent(slip.slip_id || ('SLIP-' + slip.id)) + '" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye-fill me-1"></i> View Salary Slip</a>';
                html += '    <button type="button" class="btn btn-outline-secondary" onclick="openPayslipModalByKey(\'' + key + '\')"><i class="bi bi-printer-fill me-1"></i> Print</button>';
                html += '  </div>';
                html += '</div>';

                html += '<div class="row g-3 bg-light p-3 rounded border">';
                html += '  <div class="col-md-3"><small class="text-muted d-block">Basic Salary</small><strong class="text-dark">₹' + numberFormat(slip.basic_salary || 0) + '</strong></div>';
                html += '  <div class="col-md-3"><small class="text-muted d-block">Gross Salary</small><strong class="text-primary">₹' + numberFormat(slip.gross_salary || 0) + '</strong></div>';
                html += '  <div class="col-md-3"><small class="text-muted d-block">Total Deductions</small><strong class="text-danger">₹' + numberFormat(slip.total_deductions || 0) + '</strong></div>';
                html += '  <div class="col-md-3"><small class="text-muted d-block">Net Payable</small><strong class="text-success" style="font-size:16px;">₹' + numberFormat(slip.final_payable || slip.net_salary || 0) + '</strong></div>';
                html += '</div>';
            } else {
                $('#slipStatusBadge').removeClass().addClass('badge bg-secondary').text('Not Generated');

                html += '<div class="text-center py-4 bg-light rounded border">';
                html += '  <i class="bi bi-file-earmark-text text-muted" style="font-size:32px;"></i>';
                html += '  <h6 class="fw-bold text-muted mt-2 mb-1">No Salary Slip generated for ' + monthName + ' ' + yVal + '</h6>';
                html += '  <p class="small text-muted mb-3">You can view the active salary structure above or inspect previous monthly slips in the history table below.</p>';
                html += '  <button type="button" class="btn btn-sm btn-outline-primary" onclick="openPayslipModalWithDefaults(\'' + monthName + '\', \'' + yVal + '\')"><i class="bi bi-file-earmark-pdf me-1"></i> Preview Draft Slip Structure</button>';
                html += '</div>';
            }

            $('#slipDetailContainer').html(html);
        }

        function openPayslipModalByKey(key) {
            var slip = slipsByMonthYearMap[key];
            if (!slip) return;

            var mVal = $('#payrollMonthSelect').val();
            var yVal = $('#payrollYearSelect').val();
            var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var monthName = slip.month ? monthNames[parseInt(slip.month) - 1] : monthNames[parseInt(mVal) - 1];
            var yearStr = slip.year || yVal;

            $('#modalSlipMonthYear').text(monthName + ' ' + yearStr);

            var basic = parseFloat(slip.basic_salary || 0);
            var gross = parseFloat(slip.gross_salary || 0);
            var totalDed = parseFloat(slip.total_deductions || 0);
            var netPayable = parseFloat(slip.final_payable || slip.net_salary || (gross - totalDed));

            var earningsArr = slip.earnings_breakdown || [];
            var stdDedArr = slip.standard_deductions || [];

            var html = '';
            html += '<div class="salary-slip-container-modal">';
            html += '  <div class="company-header-modal">';
            html += '    <h1 class="company-name-modal">' + instituteNameStr + '</h1>';
            html += '    <div class="small opacity-75 mt-1">Official Employee Salary Slip</div>';
            html += '    <div class="slip-title-modal"><i class="fas fa-file-invoice-dollar me-1"></i> SALARY SLIP - ' + monthName.toUpperCase() + ' ' + yearStr + '</div>';
            html += '  </div>';

            html += '  <div class="employee-section-modal">';
            html += '    <div class="employee-grid-modal">';
            html += '      <div>';
            html += '        <div class="employee-field-modal"><span>Employee Name:</span><strong>' + employeeBasicDetails.name + '</strong></div>';
            html += '        <div class="employee-field-modal"><span>Employee Code:</span><strong>' + employeeBasicDetails.code + '</strong></div>';
            html += '        <div class="employee-field-modal"><span>Department:</span><strong>' + employeeBasicDetails.department + '</strong></div>';
            html += '      </div>';
            html += '      <div>';
            html += '        <div class="employee-field-modal"><span>Designation:</span><strong>' + employeeBasicDetails.designation + '</strong></div>';
            html += '        <div class="employee-field-modal"><span>Slip ID:</span><strong class="text-primary">' + (slip.slip_id || ('SLIP-' + slip.id)) + '</strong></div>';
            html += '        <div class="employee-field-modal"><span>Status:</span><strong class="text-success">' + (slip.payment_status ? slip.payment_status.toUpperCase() : 'GENERATED') + '</strong></div>';
            html += '      </div>';
            html += '    </div>';
            html += '  </div>';

            html += '  <table class="salary-table-modal">';
            html += '    <thead>';
            html += '      <tr><th colspan="2" style="background:#059669; text-align:center;"><i class="fas fa-plus-circle me-1"></i> EARNINGS & ALLOWANCES</th></tr>';
            html += '      <tr style="background:#f1f5f9; color:#1e293b;"><th style="width:70%;">Particulars</th><th style="width:30%; text-align:right;">Amount (₹)</th></tr>';
            html += '    </thead>';
            html += '    <tbody>';
            html += '      <tr><td>Basic Salary</td><td style="text-align:right; font-weight:bold; color:#059669;">₹' + numberFormat(basic) + '</td></tr>';
            var hra = parseFloat(employeeBasicDetails.hra || 0);
            var da = parseFloat(employeeBasicDetails.da || 0);
            var sa = parseFloat(employeeBasicDetails.special_allowance || 0);
            if (earningsArr && typeof earningsArr === 'object' && Object.keys(earningsArr).length > 0) {
                $.each(earningsArr, function (k, v) {
                    html += '<tr><td>' + k.replace(/_/g, ' ').toUpperCase() + '</td><td style="text-align:right; color:#059669;">₹' + numberFormat(v) + '</td></tr>';
                });
            } else {
                if (hra > 0) html += '<tr><td>HRA</td><td style="text-align:right; color:#059669;">₹' + numberFormat(hra) + '</td></tr>';
                if (da > 0) html += '<tr><td>DA</td><td style="text-align:right; color:#059669;">₹' + numberFormat(da) + '</td></tr>';
                if (sa > 0) html += '<tr><td>Special Allowance</td><td style="text-align:right; color:#059669;">₹' + numberFormat(sa) + '</td></tr>';
            }
            html += '      <tr style="background:#e8f5e8; font-weight:bold;"><td>GROSS EARNINGS</td><td style="text-align:right; color:#059669;">₹' + numberFormat(gross || (basic + hra + da + sa)) + '</td></tr>';
            html += '    </tbody>';
            html += '  </table>';

            html += '  <table class="salary-table-modal">';
            html += '    <thead>';
            html += '      <tr><th colspan="2" style="background:#dc2626; text-align:center;"><i class="fas fa-minus-circle me-1"></i> DEDUCTIONS</th></tr>';
            html += '      <tr style="background:#f1f5f9; color:#1e293b;"><th style="width:70%;">Particulars</th><th style="width:30%; text-align:right;">Amount (₹)</th></tr>';
            html += '    </thead>';
            html += '    <tbody>';
            var pf = parseFloat(employeeBasicDetails.pf_deduction || 0);
            var pt = parseFloat(employeeBasicDetails.pt_deduction || 0);
            if (stdDedArr && typeof stdDedArr === 'object' && Object.keys(stdDedArr).length > 0) {
                $.each(stdDedArr, function (k, v) {
                    html += '<tr><td>' + k.replace(/_/g, ' ').toUpperCase() + '</td><td style="text-align:right; color:#dc2626;">- ₹' + numberFormat(v) + '</td></tr>';
                });
            } else {
                if (pf > 0) html += '<tr><td>PF DEDUCTION</td><td style="text-align:right; color:#dc2626;">- ₹' + numberFormat(pf) + '</td></tr>';
                if (pt > 0) html += '<tr><td>PROFESSIONAL TAX (PT)</td><td style="text-align:right; color:#dc2626;">- ₹' + numberFormat(pt) + '</td></tr>';
            }
            html += '      <tr style="background:#fee2e2; font-weight:bold;"><td>TOTAL DEDUCTIONS</td><td style="text-align:right; color:#dc2626;">- ₹' + numberFormat(totalDed || (pf + pt)) + '</td></tr>';
            html += '    </tbody>';
            html += '  </table>';

            html += '  <div class="summary-section-modal">';
            html += '    <div class="summary-grid-modal">';
            html += '      <div><small style="opacity:0.8; display:block;">Basic Salary</small><strong>₹' + numberFormat(basic) + '</strong></div>';
            html += '      <div><small style="opacity:0.8; display:block;">Gross Salary</small><strong>₹' + numberFormat(gross) + '</strong></div>';
            html += '      <div><small style="opacity:0.8; display:block;">Total Deductions</small><strong>₹' + numberFormat(totalDed) + '</strong></div>';
            html += '    </div>';
            html += '  </div>';

            html += '  <div class="final-amount-modal"><i class="fas fa-money-check-alt me-1"></i> NET PAYABLE AMOUNT: ₹' + numberFormat(netPayable) + '</div>';

            if (employeeBasicDetails.bank_name || employeeBasicDetails.account_number) {
                html += '  <div class="p-3 bg-light rounded border mb-3">';
                html += '    <h6 class="fw-bold text-primary mb-2"><i class="fas fa-university me-1"></i> BANK DETAILS FOR CREDIT</h6>';
                html += '    <div class="row g-2 text-sm">';
                html += '      <div class="col-6"><span>Bank:</span> <strong>' + employeeBasicDetails.bank_name + '</strong></div>';
                html += '      <div class="col-6"><span>Account No:</span> <strong>' + employeeBasicDetails.account_number + '</strong></div>';
                html += '      <div class="col-6"><span>IFSC Code:</span> <strong>' + employeeBasicDetails.ifsc_code + '</strong></div>';
                html += '    </div>';
                html += '  </div>';
            }

            html += '  <div class="row mt-4 pt-3 border-top text-center" style="font-size:11px; color:#64748B;">';
            html += '    <div class="col-6 text-start">Computer generated salary slip. No signature required.</div>';
            html += '    <div class="col-6 text-end border-top pt-1 fw-bold">Authorized Payroll Officer</div>';
            html += '  </div>';
            html += '</div>';

            $('#printableSalarySlipContent').html(html);
            $('#viewSalarySlipModal').modal('show');
        }

        function openPayslipModalWithDefaults(mName, yStr) {
            $('#modalSlipMonthYear').text(mName + ' ' + yStr + ' (Draft Structure)');
            var basic = parseFloat(employeeBasicDetails.basic_salary || 0);
            var hra = parseFloat(employeeBasicDetails.hra || 0);
            var da = parseFloat(employeeBasicDetails.da || 0);
            var sa = parseFloat(employeeBasicDetails.special_allowance || 0);
            var gross = basic + hra + da + sa;
            var pf = parseFloat(employeeBasicDetails.pf_deduction || 0);
            var pt = parseFloat(employeeBasicDetails.pt_deduction || 0);
            var totalDed = pf + pt;
            var netPayable = gross - totalDed;

            var html = '';
            html += '<div class="salary-slip-container-modal">';
            html += '  <div class="company-header-modal">';
            html += '    <h1 class="company-name-modal">' + instituteNameStr + '</h1>';
            html += '    <div class="small opacity-75 mt-1">Draft Salary Structure Payslip</div>';
            html += '    <div class="slip-title-modal"><i class="fas fa-file-invoice-dollar me-1"></i> DRAFT SALARY SLIP - ' + mName.toUpperCase() + ' ' + yStr + '</div>';
            html += '  </div>';

            html += '  <div class="employee-section-modal">';
            html += '    <div class="employee-grid-modal">';
            html += '      <div>';
            html += '        <div class="employee-field-modal"><span>Employee Name:</span><strong>' + employeeBasicDetails.name + '</strong></div>';
            html += '        <div class="employee-field-modal"><span>Employee Code:</span><strong>' + employeeBasicDetails.code + '</strong></div>';
            html += '        <div class="employee-field-modal"><span>Department:</span><strong>' + employeeBasicDetails.department + '</strong></div>';
            html += '      </div>';
            html += '      <div>';
            html += '        <div class="employee-field-modal"><span>Designation:</span><strong>' + employeeBasicDetails.designation + '</strong></div>';
            html += '        <div class="employee-field-modal"><span>Status:</span><strong class="text-warning">DRAFT STRUCTURE</strong></div>';
            html += '      </div>';
            html += '    </div>';
            html += '  </div>';

            html += '  <table class="salary-table-modal">';
            html += '    <thead>';
            html += '      <tr><th colspan="2" style="background:#059669; text-align:center;"><i class="fas fa-plus-circle me-1"></i> EARNINGS & ALLOWANCES</th></tr>';
            html += '      <tr style="background:#f1f5f9; color:#1e293b;"><th style="width:70%;">Particulars</th><th style="width:30%; text-align:right;">Amount (₹)</th></tr>';
            html += '    </thead>';
            html += '    <tbody>';
            html += '      <tr><td>Basic Salary</td><td style="text-align:right; font-weight:bold; color:#059669;">₹' + numberFormat(basic) + '</td></tr>';
            if (hra > 0) html += '<tr><td>HRA</td><td style="text-align:right; color:#059669;">₹' + numberFormat(hra) + '</td></tr>';
            if (da > 0) html += '<tr><td>DA</td><td style="text-align:right; color:#059669;">₹' + numberFormat(da) + '</td></tr>';
            if (sa > 0) html += '<tr><td>Special Allowance</td><td style="text-align:right; color:#059669;">₹' + numberFormat(sa) + '</td></tr>';
            html += '      <tr style="background:#e8f5e8; font-weight:bold;"><td>GROSS EARNINGS</td><td style="text-align:right; color:#059669;">₹' + numberFormat(gross) + '</td></tr>';
            html += '    </tbody>';
            html += '  </table>';

            html += '  <table class="salary-table-modal">';
            html += '    <thead>';
            html += '      <tr><th colspan="2" style="background:#dc2626; text-align:center;"><i class="fas fa-minus-circle me-1"></i> DEDUCTIONS</th></tr>';
            html += '      <tr style="background:#f1f5f9; color:#1e293b;"><th style="width:70%;">Particulars</th><th style="width:30%; text-align:right;">Amount (₹)</th></tr>';
            html += '    </thead>';
            html += '    <tbody>';
            if (pf > 0) html += '<tr><td>PF DEDUCTION</td><td style="text-align:right; color:#dc2626;">- ₹' + numberFormat(pf) + '</td></tr>';
            if (pt > 0) html += '<tr><td>PROFESSIONAL TAX (PT)</td><td style="text-align:right; color:#dc2626;">- ₹' + numberFormat(pt) + '</td></tr>';
            html += '      <tr style="background:#fee2e2; font-weight:bold;"><td>TOTAL DEDUCTIONS</td><td style="text-align:right; color:#dc2626;">- ₹' + numberFormat(totalDed) + '</td></tr>';
            html += '    </tbody>';
            html += '  </table>';

            html += '  <div class="summary-section-modal">';
            html += '    <div class="summary-grid-modal">';
            html += '      <div><small style="opacity:0.8; display:block;">Basic Salary</small><strong>₹' + numberFormat(basic) + '</strong></div>';
            html += '      <div><small style="opacity:0.8; display:block;">Gross Salary</small><strong>₹' + numberFormat(gross) + '</strong></div>';
            html += '      <div><small style="opacity:0.8; display:block;">Total Deductions</small><strong>₹' + numberFormat(totalDed) + '</strong></div>';
            html += '    </div>';
            html += '  </div>';

            html += '  <div class="final-amount-modal"><i class="fas fa-money-check-alt me-1"></i> ESTIMATED NET SALARY: ₹' + numberFormat(netPayable) + '</div>';
            html += '</div>';

            $('#printableSalarySlipContent').html(html);
            $('#viewSalarySlipModal').modal('show');
        }

        function printSalarySlip() {
            var printContents = document.getElementById('printableSalarySlipContent').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = '<div style="padding:20px;">' + printContents + '</div>';
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }

        function numberFormat(val) {
            var num = parseFloat(val || 0);
            return num.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function salaryLabel(value) {
            return String(value || '').replace(/[_-]/g, ' ').replace(/\b\w/g, function (letter) {
                return letter.toUpperCase();
            });
        }

        function renderSalaryStructureDetails(details) {
            details = details || {};
            var earnings = details.earnings_breakdown || {};
            var standardDeductions = details.standard_deductions || {};
            var attendanceDeductions = details.attendance_deductions || {};
            var otherDeductions = details.other_deductions || [];
            var html = '<div class="col-md-6">';
            html += '<div class="info-row"><span class="info-label">Basic Salary (Monthly)</span><span class="info-value text-dark fw-bold">₹' + numberFormat(details.basic_salary) + '</span></div>';

            if (Object.keys(earnings).length > 0) {
                Object.keys(earnings).forEach(function (key) {
                    var value = parseFloat(earnings[key] || 0);
                    if (value > 0) html += '<div class="info-row"><span class="info-label">' + salaryLabel(key) + '</span><span class="info-value">₹' + numberFormat(value) + '</span></div>';
                });
            } else {
                [['hra', 'HRA'], ['da', 'DA'], ['special_allowance', 'Special Allowance'], ['other_allowances', 'Other Allowances']].forEach(function (item) {
                    if (parseFloat(details[item[0]] || 0) > 0) html += '<div class="info-row"><span class="info-label">' + item[1] + '</span><span class="info-value">₹' + numberFormat(details[item[0]]) + '</span></div>';
                });
            }
            html += '<div class="info-row"><span class="info-label">Gross Salary</span><span class="info-value fw-bold text-primary">₹' + numberFormat(details.monthly_fixed) + '</span></div></div>';

            html += '<div class="col-md-6">';
            [standardDeductions, attendanceDeductions].forEach(function (deductions) {
                Object.keys(deductions).forEach(function (key) {
                    var value = parseFloat(deductions[key] || 0);
                    if (value > 0) html += '<div class="info-row"><span class="info-label">' + salaryLabel(key) + '</span><span class="info-value text-danger">₹' + numberFormat(value) + '</span></div>';
                });
            });
            if (Array.isArray(otherDeductions)) {
                otherDeductions.forEach(function (deduction) {
                    if (deduction && parseFloat(deduction.amount || 0) > 0) html += '<div class="info-row"><span class="info-label">' + (deduction.name || 'Other Deduction') + '</span><span class="info-value text-danger">₹' + numberFormat(deduction.amount) + '</span></div>';
                });
            }
            html += '<div class="info-row"><span class="info-label">Total Deductions</span><span class="info-value fw-bold text-danger">₹' + numberFormat(details.total_deductions) + '</span></div>';
            html += '<div class="info-row"><span class="info-label">Net Salary</span><span class="info-value fw-bold text-success" style="font-size:16px;">₹' + numberFormat(details.net_salary) + '</span></div></div>';
            $('#salaryStructureDetailsContainer').html(html);
        }

        function renderAttendanceCalendar() {
            var monthVal = $('#attMonthSelect').val();
            var yearVal = $('#attYearSelect').val();
            var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var monthName = monthNames[parseInt(monthVal) - 1];

            $('#attMonthYearTitle').html('<i class="bi bi-calendar3 me-1" style="color:var(--sp-primary);"></i> ' + monthName + ' ' + yearVal);

            var daysInMonth = new Date(yearVal, parseInt(monthVal), 0).getDate();
            var startDayOfWeek = new Date(yearVal, parseInt(monthVal) - 1, 1).getDay();

            var html = '';
            html += '<div class="sp-cal-cell cal-header">Sun</div>';
            html += '<div class="sp-cal-cell cal-header">Mon</div>';
            html += '<div class="sp-cal-cell cal-header">Tue</div>';
            html += '<div class="sp-cal-cell cal-header">Wed</div>';
            html += '<div class="sp-cal-cell cal-header">Thu</div>';
            html += '<div class="sp-cal-cell cal-header">Fri</div>';
            html += '<div class="sp-cal-cell cal-header">Sat</div>';

            for (var empty = 0; empty < startDayOfWeek; empty++) {
                html += '<div class="sp-cal-cell cal-empty"></div>';
            }

            var today = new Date();
            var isCurrentMonthYear = (today.getFullYear() == parseInt(yearVal) && (today.getMonth() + 1) == parseInt(monthVal));
            var todayDay = today.getDate();

            for (var d = 1; d <= daysInMonth; d++) {
                var dStr = (d < 10 ? '0' : '') + d;
                var key = yearVal + '-' + monthVal + '-' + dStr;
                var st = attendanceDataMap[key] || null;

                var cls = 'cal-unmarked';
                var statusText = 'Absent';

                if (st === 'present') {
                    cls = 'cal-present'; statusText = 'Present';
                } else if (st === 'absent') {
                    cls = 'cal-absent'; statusText = 'Absent';
                } else if (st === 'late') {
                    cls = 'cal-late'; statusText = 'Late';
                } else {
                    var curCellDate = new Date(yearVal, parseInt(monthVal) - 1, d);
                    if (curCellDate < today) {
                        cls = 'cal-absent';
                        statusText = 'Absent';
                    } else {
                        cls = 'cal-unmarked';
                        statusText = '';
                    }
                }

                if (isCurrentMonthYear && d == todayDay && cls === 'cal-unmarked') {
                    cls += ' cal-today';
                }

                html += '<div class="sp-cal-cell ' + cls + '">';
                html += '<span class="cal-day-num">' + d + '</span>';
                if (statusText) {
                    html += '<span class="cal-status-text">' + statusText + '</span>';
                }
                html += '</div>';
            }

            $('#attCalGrid').html(html);
        }

        function prevMonth() {
            var m = parseInt($('#attMonthSelect').val());
            var y = parseInt($('#attYearSelect').val());
            m--;
            if (m < 1) { m = 12; y--; }
            $('#attMonthSelect').val((m < 10 ? '0' : '') + m);
            $('#attYearSelect').val(y);
            renderAttendanceCalendar();
        }

        function nextMonth() {
            var m = parseInt($('#attMonthSelect').val());
            var y = parseInt($('#attYearSelect').val());
            m++;
            if (m > 12) { m = 1; y++; }
            $('#attMonthSelect').val((m < 10 ? '0' : '') + m);
            $('#attYearSelect').val(y);
            renderAttendanceCalendar();
        }

        /* ----- TIMETABLE FILTERING LOGIC (DAY, WEEK, MONTH, ALL) ----- */
        function switchTimetableFilter(filterType) {
            $('.btn-tt-view').removeClass('active');
            if (filterType === 'day') $('#btnTtDay').addClass('active');
            else if (filterType === 'week') $('#btnTtWeek').addClass('active');
            else if (filterType === 'month') $('#btnTtMonth').addClass('active');
            else $('#btnTtAll').addClass('active');

            renderTimetable(filterType);
        }

        function renderTimetable(filterType) {
            var container = $('#timetableDynamicContainer');
            var html = '';

            var daysOfWeekArr = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
            var daysOfWeekShort = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

            var events = timetableEventsList || [];

            // Helper function to get day name from day number
            function getDayName(dayNum) {
                var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                return days[dayNum] || "Monday";
            }

            function dateOnly(date) {
                return new Date(date.getFullYear(), date.getMonth(), date.getDate());
            }

            function parseEventDate(value) {
                if (!value) return null;
                var parts = String(value).slice(0, 10).split('-');
                if (parts.length !== 3) return null;
                return new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            }

            function normalizeWeekdays(value) {
                if (typeof value === 'string') {
                    try {
                        value = JSON.parse(value);
                    } catch (error) {
                        value = value.split(',');
                    }
                }

                if (!Array.isArray(value)) return [];

                var dayNames = {
                    sunday: 0, sun: 0,
                    monday: 1, mon: 1, tues: 2,
                    wednesday: 3, wed: 3,
                    thursday: 4, thu: 4, thurs: 4,
                    friday: 5, fri: 5,
                    saturday: 6, sat: 6
                };

                return value.map(function (day) {
                    if (typeof day === 'number' || /^\d+$/.test(String(day).trim())) {
                        return parseInt(day, 10);
                    }
                    return dayNames[String(day).trim().toLowerCase()];
                }).filter(function (day) {
                    return day >= 0 && day <= 6;
                });
            }

            // Match an event against the requested calendar date.
            function eventOccursOnDate(event, date) {
                var targetDate = dateOnly(date);
                var validFrom = parseEventDate(event.valid_from);
                var validTo = parseEventDate(event.valid_to);
                var frequency = event.frequency || 'daily';

                if (validFrom && targetDate < validFrom) return false;
                if (validTo && targetDate > validTo) return false;

                if (frequency === 'one_time' || frequency === 'once') {
                    return !!validFrom && targetDate.getTime() === validFrom.getTime();
                }

                if (frequency === 'monthly') {
                    return event.day_of_month != null && targetDate.getDate() === parseInt(event.day_of_month);
                }

                var weeklyDays = normalizeWeekdays(event.days_of_week);
                if (frequency === 'weekly' || weeklyDays.length > 0) {
                    return weeklyDays.length > 0 && weeklyDays.some(function (day) {
                        return parseInt(day) === targetDate.getDay();
                    });
                }

                return frequency === 'daily' || (!event.days_of_week || event.days_of_week.length === 0);
            }

            function eventOnDay(event, dayNum, date) {
                var targetDate = date || new Date();
                if (!date) {
                    var today = dateOnly(new Date());
                    targetDate = new Date(today);
                    targetDate.setDate(today.getDate() + ((dayNum - today.getDay() + 7) % 7));
                }
                return eventOccursOnDate(event, targetDate);
            }

            function isEventValidToday(event) {
                return eventOccursOnDate(event, new Date());
            }

            function dateForCurrentWeekDay(dayNum) {
                var today = dateOnly(new Date());
                var mondayOffset = today.getDay() === 0 ? -6 : 1 - today.getDay();
                var monday = new Date(today);
                monday.setDate(today.getDate() + mondayOffset);
                monday.setDate(monday.getDate() + (dayNum === 0 ? 6 : dayNum - 1));
                return monday;
            }

            if (filterType === 'day') {
                var today = new Date();
                var todayDayNum = today.getDay(); // 0 = Sunday, 1 = Monday, etc.
                var todayDayName = getDayName(todayDayNum);
                var todayDateStr = today.toISOString().split('T')[0];

                html += '<div class="alert alert-indigo d-flex align-items-center justify-content-between p-3 mb-3" style="background:#EEF2FF; border:1px solid #C7D2FE; border-radius:12px;">';
                html += '  <div><strong class="text-primary"><i class="bi bi-calendar-day-fill me-1"></i> Today\'s Schedule:</strong> <span class="fw-bold text-dark">' + todayDayName + ' (' + todayDateStr + ')</span></div>';
                html += '  <span class="badge bg-primary">Day View</span>';
                html += '</div>';

                html += '<div class="row g-3">';
                var dayCount = 0;

                // Sort events by start time
                var sortedEvents = [...events].sort((a, b) => {
                    return (a.start_time || '').localeCompare(b.start_time || '');
                });

                $.each(sortedEvents, function (idx, event) {
                    // Check if event occurs today
                    if (!eventOccursOnDate(event, today)) {
                        return;
                    }

                    // Skip cancelled lectures
                    if (event.is_cancelled) {
                        return;
                    }

                    var subjectName = event.main_subject_name || event.subject_display_name || 'Subject Lecture';
                    var courseName = event.course_type || event.branch_name || 'Course';
                    var deptName = event.department_name || 'Department';
                    var locationStr = event.location || event.venue || 'Classroom 101';

                    // Format time for display
                    var startTime = event.start_time || '09:00:00';
                    var endTime = event.end_time || '10:00:00';

                    // Format time to 12-hour format
                    startTime = formatTime(startTime);
                    endTime = formatTime(endTime);

                    // Check if it's a duty or lecture
                    var eventIcon = event.event_type === 'duty' ? 'bi-clipboard-check' : 'bi-book';
                    var eventBadge = event.event_type === 'duty' ? 'bg-warning text-dark' : 'bg-primary';
                    var eventLabel = event.event_type === 'duty' ? 'Duty' : 'Lecture';

                    html += '<div class="col-md-6">';
                    html += '  <div class="card border-0 bg-light p-3 shadow-sm" style="border-radius:12px; border-left:4px solid #4e73df !important;">';
                    html += '    <div class="d-flex align-items-center justify-content-between mb-2">';
                    html += '      <h6 class="fw-bold text-dark mb-0"><i class="bi ' + eventIcon + ' me-1"></i> ' + subjectName + '</h6>';
                    html += '      <span class="badge ' + eventBadge + '">' + eventLabel + '</span>';
                    html += '    </div>';

                    html += '    <div class="text-muted small mb-2"><i class="bi bi-clock-fill text-primary me-1"></i> <strong>' + startTime + ' - ' + endTime + '</strong></div>';
                    html += '    <div class="d-flex justify-content-between align-items-center text-xs" style="font-size:12px;">';
                    html += '      <span><i class="bi bi-building me-1"></i> ' + deptName + '</span>';
                    html += '      <span class="text-danger font-weight-bold"><i class="bi bi-geo-alt-fill me-1"></i> ' + locationStr + '</span>';
                    html += '    </div>';

                    if (event.lecture_mode === 'online' && event.meeting_link) {
                        html += '    <div class="mt-2">';
                        html += '      <a href="' + event.meeting_link + '" target="_blank" class="btn btn-sm btn-outline-primary">';
                        html += '        <i class="bi bi-camera-video me-1"></i> Join Online';
                        html += '      </a>';
                        html += '    </div>';
                    }

                    if (event.reassignment_info && event.reassignment_info.is_reassigned_to_me) {
                        html += '    <div class="mt-2 p-2 bg-warning bg-opacity-10 rounded">';
                        html += '      <small class="text-warning"><i class="bi bi-arrow-repeat me-1"></i> Reassigned from ' + event.reassignment_info.reassigned_from_employee_name + '</small>';
                        html += '    </div>';
                    }

                    html += '  </div>';
                    html += '</div>';
                    dayCount++;
                });

                if (dayCount === 0) {
                    html += '<div class="col-12"><div class="beh-empty"><i class="bi bi-calendar-check"></i> No lectures or duties scheduled for today.</div></div>';
                }
                html += '</div>';
            }
            else if (filterType === 'week') {
                html += '<div class="table-responsive">';
                html += '  <table class="table table-bordered table-sm align-middle text-center mb-0">';
                html += '    <thead class="table-light">';
                html += '      <tr>';
                // Show Monday to Saturday (1-6)
                for (var i = 1; i <= 6; i++) {
                    html += '<th style="width:14%;">' + daysOfWeekArr[i - 1] + '</th>';
                }
                html += '<th style="width:14%;">Sunday</th>';
                html += '      </tr>';
                html += '    </thead>';
                html += '    <tbody>';
                html += '      <tr>';
                for (var i = 1; i <= 6; i++) { // Monday(1) to Saturday(6)
                    html += '<td class="align-top p-2" style="background:#f8fafc; min-height:120px;">';
                    var matchCount = 0;

                    // Sort events for this day
                    var dayEvents = events.filter(function (event) {
                        return eventOccursOnDate(event, dateForCurrentWeekDay(i));
                    }).sort(function (a, b) {
                        return (a.start_time || '').localeCompare(b.start_time || '');
                    });

                    if (dayEvents.length > 0) {
                        $.each(dayEvents, function (idx, event) {
                            if (event.is_cancelled) return;

                            var subjectName = event.main_subject_name || event.subject_display_name || 'Subject';
                            var locationStr = event.location || event.venue || 'Room 101';
                            var startTime = formatTime(event.start_time || '09:00');
                            var endTime = formatTime(event.end_time || '10:00');

                            var borderColor = event.event_type === 'duty' ? '#f59e0b' : '#4e73df';
                            var bgColor = event.event_type === 'duty' ? '#fffbeb' : '#ffffff';

                            html += '<div class="card border-0 p-2 mb-2 shadow-sm text-start" style="border-radius:8px; border-left:3px solid ' + borderColor + ' !important; background:' + bgColor + '; font-size:11px;">';
                            html += '  <strong class="text-dark d-block">' + subjectName + '</strong>';
                            html += '  <span class="text-primary font-weight-semibold d-block"><i class="bi bi-clock me-1"></i>' + startTime + ' - ' + endTime + '</span>';
                            html += '  <span class="text-muted d-block opacity-75"><i class="bi bi-geo-alt me-1"></i>' + locationStr + '</span>';
                            if (event.event_type === 'duty') {
                                html += '  <span class="badge bg-warning text-dark mt-1" style="font-size:9px;">Duty</span>';
                            }
                            html += '</div>';
                            matchCount++;
                        });
                    }

                    if (matchCount === 0) {
                        html += '<span class="text-muted opacity-50 small d-block py-3">No Class</span>';
                    }
                    html += '</td>';
                }
                html += '<td class="align-top p-2" style="background:#f8fafc; min-height:120px;">';
                var sundayEvents = events.filter(function (event) {
                    return eventOccursOnDate(event, dateForCurrentWeekDay(0));
                }).sort(function (a, b) {
                    return (a.start_time || '').localeCompare(b.start_time || '');
                });
                if (sundayEvents.length > 0) {
                    $.each(sundayEvents, function (idx, event) {
                        if (event.is_cancelled) return;
                        var subjectName = event.main_subject_name || event.subject_display_name || 'Subject';
                        var locationStr = event.location || event.venue || 'Room 101';
                        var startTime = formatTime(event.start_time || '09:00');
                        var endTime = formatTime(event.end_time || '10:00');
                        var borderColor = event.event_type === 'duty' ? '#f59e0b' : '#4e73df';
                        var bgColor = event.event_type === 'duty' ? '#fffbeb' : '#ffffff';
                        html += '<div class="card border-0 p-2 mb-2 shadow-sm text-start" style="border-radius:8px; border-left:3px solid ' + borderColor + ' !important; background:' + bgColor + '; font-size:11px;">';
                        html += '  <strong class="text-dark d-block">' + subjectName + '</strong>';
                        html += '  <span class="text-primary font-weight-semibold d-block"><i class="bi bi-clock me-1"></i>' + startTime + ' - ' + endTime + '</span>';
                        html += '  <span class="text-muted d-block opacity-75"><i class="bi bi-geo-alt me-1"></i>' + locationStr + '</span>';
                        if (event.event_type === 'duty') html += '  <span class="badge bg-warning text-dark mt-1" style="font-size:9px;">Duty</span>';
                        html += '</div>';
                    });
                } else {
                    html += '<span class="text-muted opacity-50 small d-block py-3">No Class</span>';
                }
                html += '</td>';
                html += '      </tr>';
                html += '    </tbody>';
                html += '  </table>';
                html += '</div>';
            }
            else if (filterType === 'month') {
                html += '<div class="alert alert-light border p-3 rounded mb-3 text-center">';
                html += '  <h6 class="fw-bold text-dark mb-1"><i class="bi bi-calendar-month-fill text-primary me-2"></i> Monthly Schedule</h6>';
                html += '  <p class="small text-muted mb-0">Total assigned lectures & duties: <strong class="text-primary">' + events.length + ' active sessions</strong></p>';
                html += '</div>';

                html += '<div class="table-responsive">';
                html += '  <table class="table table-hover table-sm align-middle mb-0">';
                html += '    <thead class="table-light"><tr><th>Subject/Duty</th><th>Type</th><th>Days</th><th>Time</th><th>Location</th><th>Frequency</th></tr></thead>';
                html += '    <tbody>';

                // Sort events
                var sortedMonthly = [...events].sort((a, b) => {
                    return (a.start_time || '').localeCompare(b.start_time || '');
                });

                if (sortedMonthly.length > 0) {
                    $.each(sortedMonthly, function (idx, event) {
                        if (event.is_cancelled) return;

                        var subjectName = event.main_subject_name || event.subject_display_name || 'Subject';
                        var locationStr = event.location || event.venue || 'Classroom 101';
                        var eventType = event.event_type === 'duty' ? 'Duty' : 'Lecture';

                        // Format days
                        var daysDisplay = 'All Days';
                        if (event.days_of_week && event.days_of_week.length > 0) {
                            var dayNames = event.days_of_week.map(function (d) {
                                var dayInt = parseInt(d);
                                return daysOfWeekShort[dayInt] || d;
                            });
                            daysDisplay = dayNames.join(', ');
                        } else if (event.frequency === 'one_time' || event.frequency === 'once') {
                            daysDisplay = event.valid_from || 'One Time';
                        }

                        var startTime = formatTime(event.start_time || '09:00');
                        var endTime = formatTime(event.end_time || '10:00');
                        var eventTypeBadge = event.event_type === 'duty' ? 'badge bg-warning text-dark' : 'badge bg-info';
                        var frequencyLabel = event.frequency_label || event.frequency || '';

                        html += '<tr>';
                        html += '  <td><strong>' + subjectName + '</strong></td>';
                        html += '  <td><span class="' + eventTypeBadge + '">' + eventType + '</span></td>';
                        html += '  <td><span class="badge bg-light text-dark border">' + daysDisplay + '</span></td>';
                        html += '  <td class="text-primary font-weight-bold">' + startTime + ' - ' + endTime + '</td>';
                        html += '  <td><i class="bi bi-geo-alt-fill text-danger me-1"></i> ' + locationStr + '</td>';
                        html += '  <td><span class="badge bg-secondary">' + frequencyLabel + '</span></td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr><td colspan="6" class="text-center py-3 text-muted">No schedule records found.</td></tr>';
                }
                html += '    </tbody>';
                html += '  </table>';
                html += '</div>';
            }
            else { // 'all' table view
                html += '<div class="table-responsive">';
                html += '  <table class="table table-hover table-sm mb-0 align-middle">';
                html += '    <thead class="table-light">';
                html += '      <tr><th>Subject/Duty</th><th>Type</th><th>Department</th><th>Days</th><th>Timings</th><th>Location</th><th>Frequency</th></tr>';
                html += '    </thead>';
                html += '    <tbody>';

                if (events && events.length > 0) {
                    // Sort events
                    var sortedAll = [...events].sort((a, b) => {
                        return (a.start_time || '').localeCompare(b.start_time || '');
                    });

                    $.each(sortedAll, function (idx, event) {
                        if (event.is_cancelled) return;

                        var subjectName = event.main_subject_name || event.subject_display_name || 'Subject';
                        var deptName = event.department_name || 'N/A';
                        var locationStr = event.location || event.venue || 'Classroom 101';
                        var eventType = event.event_type === 'duty' ? 'Duty' : 'Lecture';

                        // Format days
                        var daysDisplay = 'All Days';
                        if (event.days_of_week && event.days_of_week.length > 0) {
                            var dayNames = event.days_of_week.map(function (d) {
                                var dayInt = parseInt(d);
                                return daysOfWeekShort[dayInt] || d;
                            });
                            daysDisplay = dayNames.join(', ');
                        } else if (event.frequency === 'one_time' || event.frequency === 'once') {
                            daysDisplay = event.valid_from || 'One Time';
                        }

                        var startTime = formatTime(event.start_time || '09:00');
                        var endTime = formatTime(event.end_time || '10:00');
                        var eventTypeBadge = event.event_type === 'duty' ? 'badge bg-warning text-dark' : 'badge bg-info';
                        var frequencyLabel = event.frequency_label || event.frequency || '';

                        html += '<tr>';
                        html += '  <td><strong>' + subjectName + '</strong></td>';
                        html += '  <td><span class="' + eventTypeBadge + '">' + eventType + '</span></td>';
                        html += '  <td>' + deptName + '</td>';
                        html += '  <td>' + daysDisplay + '</td>';
                        html += '  <td class="text-primary font-weight-semibold">' + startTime + ' - ' + endTime + '</td>';
                        html += '  <td><i class="bi bi-geo-alt-fill text-danger me-1"></i> ' + locationStr + '</td>';
                        html += '  <td><span class="badge bg-secondary">' + frequencyLabel + '</span></td>';
                        html += '</tr>';
                    });
                } else {
                    html += '<tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-calendar3 fs-3 d-block mb-1"></i> No timetable subjects assigned.</td></tr>';
                }
                html += '    </tbody>';
                html += '  </table>';
                html += '</div>';
            }

            container.html(html);
        }

        // Helper function to format time to 12-hour format
        function formatTime(timeStr) {
            if (!timeStr) return '09:00 AM';

            // Remove any date part if present
            if (timeStr.includes(' ')) {
                timeStr = timeStr.split(' ')[1];
            }
            if (timeStr.includes('T')) {
                timeStr = timeStr.split('T')[1];
            }

            // Parse time
            var parts = timeStr.split(':');
            var hours = parseInt(parts[0]);
            var minutes = parts[1] ? parts[1].substring(0, 2) : '00';

            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // Convert 0 to 12

            return hours + ':' + minutes + ' ' + ampm;
        }

        function printEmpCard() {
            var printContents = document.getElementById('printableEmpIdCard').outerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = '<div style="display:flex;justify-content:center;align-items:center;height:100vh;">' + printContents + '</div>';
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }

        // ===== OFFICIAL DOCUMENTS FUNCTIONS FROM OLD FILE =====
        const letterBuilderBase = "{{ url('/letter-builder') }}";

        function generateDocument(documentName, templateKey, letterId) {
            if (!templateKey) {
                const mapping = {
                    'Job Offer Letter': 'offer',
                    'Appointment Letter': 'appointment',
                    'Joining Report': 'joining_report',
                    'Induction Letter': 'induction',
                    'Promotion Letter': 'promotion',
                    'Appraisal Letter': 'appraisal',
                    'Salary Increment Letter': 'salary_increment',
                    'Salary Slip': 'salary_slip',
                    'Warning Letter': 'warning',
                    'Complaint Letter': 'complaint',
                    'Show Cause Notice': 'show_cause',
                    'Termination Letter': 'termination',
                    'No Objection Certificate (NOC)': 'noc'
                };
                templateKey = mapping[documentName] || 'appointment';
            }

            const presetData = {
                name: @json($employee->name ?? ''),
                job_title: @json($employee->designation ?? ''),
                department: @json($employee->department_name ?? ''),
                employee_id: @json($employee->id ?? ''),
                company: @json($fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? ''),
                title: documentName
            }; 

            const params = new URLSearchParams();   
            params.set('template_key', templateKey);   
            params.set('from_employee_view', '1');
            params.set('show_template_notice', '1');
            if (letterId) {
                params.set('letter_id', letterId);
            }
            Object.keys(presetData).forEach(key => {   
                if (presetData[key] !== undefined && presetData[key] !== null) {
                    params.set(key, presetData[key]); 
                } 
            }); 

            window.location.href = `${letterBuilderBase}?${params.toString()}`;
        }

        function viewLetter(letterId) {
            // Open PDF directly in new tab
            window.open(`/employee/letter/${letterId}/pdf`, '_blank');
        }

        function toggleView(view) {
            if (view === 'card') {
                document.querySelectorAll('#cardView').forEach(el => el.style.display = 'block');
                document.querySelectorAll('#listView').forEach(el => el.style.display = 'none');
                document.getElementById('cardViewBtn').classList.add('active');
                document.getElementById('listViewBtn').classList.remove('active');
            } else {
                document.querySelectorAll('#cardView').forEach(el => el.style.display = 'none');
                document.querySelectorAll('#listView').forEach(el => el.style.display = 'block');
                document.getElementById('listViewBtn').classList.add('active');
                document.getElementById('cardViewBtn').classList.remove('active');
            }
        }
    </script>

@endsection