@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ============================================================
                                                                                                                       DECENT COLOR PALETTE — soft, refined, professional
                                                                                                                       ============================================================ */
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
            --sp-shadow: 0 20px 40px -12px rgba(79, 70, 229, 0.25);
            --sp-shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.05);
            --sp-radius: 16px;
            --sp-radius-sm: 10px;
        }

        .sp-wrapper {
            background: var(--sp-gray-50);
            padding: 24px 0;
            min-height: 100vh;
        }

        .sp-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ----- left card ----- */
        .sp-left-card {
            border: none;
            border-radius: var(--sp-radius);
            box-shadow: var(--sp-shadow);
            overflow: hidden;
            background: #fff;
            transition: transform 0.2s ease;
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

        .sp-status-dot {
            position: absolute;
            bottom: 8px;
            right: calc(50% - 60px);
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 3px solid #fff;
            background: var(--sp-success);
        }

        .sp-status-dot.inactive {
            background: var(--sp-gray-400);
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

        /* donut */
        .sp-donut-wrap {
            text-align: center;
            margin: 20px 0 18px;
            padding: 16px 0 8px;
            border-top: 1px solid var(--sp-gray-200);
            border-bottom: 1px solid var(--sp-gray-200);
        }

        .sp-donut {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            margin: 0 auto 6px;
            position: relative;
            box-shadow: inset 0 0 0 4px #f1f5f9;
        }

        .sp-donut .donut-center {
            position: absolute;
            inset: 8px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .donut-pct {
            font-size: 22px;
            font-weight: 700;
            color: var(--sp-gray-800);
            line-height: 1;
        }

        .donut-lbl {
            font-size: 10px;
            font-weight: 600;
            color: var(--sp-gray-500);
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .sp-donut-title {
            font-weight: 600;
            color: var(--sp-gray-700);
            font-size: 14px;
            margin-top: 4px;
        }

        .sp-donut-legend {
            display: flex;
            justify-content: center;
            gap: 18px;
            font-size: 13px;
            font-weight: 500;
        }

        .sp-donut-legend span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sp-donut-legend span::before {
            content: '';
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 4px;
        }

        .leg-paid::before {
            background: var(--sp-success);
        }

        .leg-due::before {
            background: #FEE2E2;
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

        .sp-cred-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 6px;
        }

        .sp-cred-actions .btn {
            border-radius: var(--sp-radius-sm);
            font-weight: 600;
            font-size: 14px;
            padding: 10px 14px;
            transition: all 0.2s;
        }

        .sp-cred-actions .btn:hover {
            transform: translateY(-2px);
        }

        .btn-idcard {
            background: var(--sp-gradient);
            color: #fff;
            border: none;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
        }

        .btn-idcard:hover {
            background: var(--sp-primary-dark);
            color: #fff;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
        }

        .btn-collect-fees {
            background: var(--sp-success);
            color: #fff;
            border: none;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
        }

        .btn-collect-fees:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
            color: #fff;
        }

        /* barcode / qr */
        .sp-codes {
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px dashed var(--sp-gray-200);
        }

        .sp-code-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 6px 0;
        }

        .sp-code-row+.sp-code-row {
            border-top: 1px solid var(--sp-gray-100);
        }

        .sp-code-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--sp-gray-500);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sp-code-label i {
            color: var(--sp-gray-400);
        }

        .sp-code-img {
            height: 44px;
            width: auto;
            max-width: 60%;
            object-fit: contain;
        }

        .sp-qr-img {
            height: 60px;
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
            padding: 12px 18px;
            font-weight: 600;
            font-size: 14px;
            color: var(--sp-gray-500);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            background: transparent;
            margin-bottom: -2px;
        }

        .sp-tabs-card .nav-tabs .nav-link i {
            font-size: 16px;
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

        /* ----- profile sections ----- */
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
            width: 140px;
            min-width: 140px;
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

        .info-value .badge {
            font-weight: 600;
            font-size: 12px;
        }

        /* parent cards */
        .parent-card {
            border: 1px solid var(--sp-gray-200);
            border-radius: var(--sp-radius-sm);
            overflow: hidden;
            height: 100%;
            transition: all 0.3s ease;
        }

        .parent-card:hover {
            border-color: var(--sp-primary-light);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.06);
        }

        .parent-card-header {
            padding: 12px 16px;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--sp-gray-200);
            background: var(--sp-gray-50);
        }

        .parent-card-header.father {
            color: var(--sp-primary);
        }

        .parent-card-header.mother {
            color: var(--sp-secondary);
        }

        .parent-card-body {
            padding: 12px 16px;
        }

        .parent-card-body .info-row {
            padding: 4px 0;
            font-size: 13px;
        }

        .parent-card-body .info-label {
            width: 100px;
            min-width: 100px;
            font-size: 12px;
        }

        /* ----- siblings ----- */
        .sib-header {
            font-size: 18px;
            font-weight: 700;
            color: var(--sp-gray-800);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sib-header i {
            color: var(--sp-primary);
        }

        .sib-count {
            background: var(--sp-gray-200);
            padding: 0 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            color: var(--sp-gray-600);
        }

        .sib-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;
        }

        .sib-card {
            border: 1px solid var(--sp-gray-200);
            border-radius: var(--sp-radius-sm);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        .sib-card:hover {
            border-color: var(--sp-primary-light);
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.1);
            transform: translateY(-3px);
        }

        .sib-card-top {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--sp-gray-100);
            background: var(--sp-gray-50);
        }

        .css-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
            background: var(--sp-primary);
        }

        .sib-name {
            font-weight: 700;
            color: var(--sp-gray-800);
            font-size: 15px;
        }

        .sib-class {
            font-size: 13px;
            color: var(--sp-gray-500);
        }

        .sib-card-body {
            padding: 12px 16px;
            flex: 1;
        }

        .sib-detail {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 13px;
        }

        .sd-label {
            color: var(--sp-gray-500);
        }

        .sd-value {
            font-weight: 500;
            color: var(--sp-gray-800);
        }

        /* ----- fees ----- */
        .fee-overview-grid {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 24px;
            align-items: start;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--sp-gray-200);
        }

        .fee-stat-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .fee-stat-pill {
            background: var(--sp-gray-50);
            border-radius: var(--sp-radius-sm);
            padding: 12px 8px;
            text-align: center;
            border: 1px solid var(--sp-gray-200);
            transition: all 0.2s ease;
        }

        .fee-stat-pill:hover {
            border-color: var(--sp-primary-light);
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .fsp-val {
            font-size: 16px;
            font-weight: 700;
            color: var(--sp-gray-800);
        }

        .fsp-lbl {
            font-size: 11px;
            font-weight: 600;
            color: var(--sp-gray-500);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 2px;
        }

        .fsp-total .fsp-val {
            color: var(--sp-gray-700);
        }

        .fsp-paid .fsp-val {
            color: var(--sp-success);
        }

        .fsp-due .fsp-val {
            color: var(--sp-danger);
        }

        .fsp-concession .fsp-val {
            color: var(--sp-warning);
        }

        .fsp-fine .fsp-val {
            color: var(--sp-danger);
        }

        .fee-progress-inline .fpi-labels {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 500;
            color: var(--sp-gray-600);
            margin-bottom: 4px;
        }

        .fee-progress-inline .progress {
            height: 8px;
            border-radius: 20px;
            background: var(--sp-gray-200);
        }

        .fee-progress-inline .progress-bar {
            background: var(--sp-gradient);
            border-radius: 20px;
            transition: width 0.6s ease;
        }

        .sp-fee-group {
            border: 1px solid var(--sp-gray-200);
            border-radius: var(--sp-radius-sm);
            margin-bottom: 12px;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .sp-fee-group:hover {
            border-color: var(--sp-primary-light);
        }

        .sp-fee-group-header {
            padding: 14px 18px;
            background: var(--sp-gray-50);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
            user-select: none;
        }

        .sp-fee-group-header:hover {
            background: var(--sp-gray-100);
        }

        .sp-fee-group-header .group-chevron {
            transition: transform 0.25s ease;
        }

        .sp-fee-group-header[aria-expanded="true"] .group-chevron {
            transform: rotate(90deg);
        }

        .sp-fee-group .collapse {
            border-top: 1px solid var(--sp-gray-200);
        }

        .sp-fee-group .table {
            margin: 0;
            font-size: 14px;
        }

        .sp-fee-group .table th {
            font-weight: 600;
            color: var(--sp-gray-500);
            border-top: none;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .sp-fee-group .table tfoot tr {
            font-weight: 700;
        }

        /* ============================================================
                                                                                                                       ENHANCED TABLE STYLES
                                                                                                                       ============================================================ */
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

        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 10px;
            border: 1px solid var(--sp-gray-200);
        }

        .table-responsive-custom .sp-table {
            min-width: 700px;
        }

        /* ============================================================
                                                                                                                       ENHANCED DOCUMENTS SECTION
                                                                                                                       ============================================================ */
        .documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 14px;
            padding: 16px 20px;
        }

        .document-item {
            background: #ffffff;
            border: 1px solid var(--sp-gray-200);
            border-radius: 12px;
            padding: 14px 18px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            min-height: 60px;
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
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .document-item .doc-info .doc-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #eef2ff;
            color: var(--sp-primary);
            font-size: 17px;
            flex-shrink: 0;
        }

        .document-item .doc-info .doc-text {
            min-width: 0;
        }

        .document-item .doc-info .doc-text .doc-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--sp-gray-800);
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .document-item .doc-info .doc-text .doc-meta {
            color: var(--sp-gray-500);
            font-size: 11px;
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .document-item .doc-info .doc-text .doc-meta .meta-dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--sp-gray-300);
            display: inline-block;
        }

        .document-item .doc-info .doc-text .doc-meta .status-available {
            color: var(--sp-success);
            font-weight: 600;
        }

        .document-item .doc-info .doc-text .doc-meta .status-missing {
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
            padding: 4px 12px;
            border-radius: 20px;
            background: var(--sp-gray-100);
            color: var(--sp-gray-500);
            flex-shrink: 0;
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
            flex-shrink: 0;
        }

        /* Document section header */
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
            padding: 4px 14px;
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

        /* ----- exams ----- */
        .sp-exam-table th {
            font-weight: 600;
            color: var(--sp-gray-500);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-top: none;
        }

        .sp-exam-table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .exam-title {
            font-weight: 500;
            color: var(--sp-gray-800);
        }

        /* ----- attendance ----- */
        .att-stats-row {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--sp-gray-200);
        }

        .att-mini-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            flex: 1;
        }

        .att-mc {
            background: var(--sp-gray-50);
            border-radius: var(--sp-radius-sm);
            padding: 12px 20px;
            min-width: 80px;
            text-align: center;
            border: 1px solid var(--sp-gray-200);
            flex: 1;
            transition: all 0.2s ease;
        }

        .att-mc:hover {
            border-color: var(--sp-primary-light);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .mc-count {
            font-size: 24px;
            font-weight: 700;
            color: var(--sp-gray-800);
        }

        .mc-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--sp-gray-500);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .att-mc.mc-present .mc-count {
            color: var(--sp-success);
        }

        .att-mc.mc-absent .mc-count {
            color: var(--sp-danger);
        }

        .att-mc.mc-late .mc-count {
            color: var(--sp-warning);
        }

        .att-mc.mc-half .mc-count {
            color: var(--sp-accent);
        }

        .att-month-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .att-month-title {
            font-weight: 700;
            font-size: 18px;
            color: var(--sp-gray-800);
            display: flex;
            align-items: center;
            gap: 8px;
        }

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

        .sp-cal-cell.cal-empty {
            background: transparent;
            border: none;
        }

        .sp-cal-cell .cal-day-num {
            font-weight: 600;
            font-size: 15px;
            color: var(--sp-gray-700);
        }

        .sp-cal-cell .cal-status-text {
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 2px;
        }

        .sp-cal-cell.cal-present {
            background: #D1FAE5;
            border-color: #6EE7B7;
        }

        .sp-cal-cell.cal-present .cal-day-num,
        .sp-cal-cell.cal-present .cal-status-text {
            color: #065F46;
        }

        .sp-cal-cell.cal-absent {
            background: #FEE2E2;
            border-color: #FCA5A5;
        }

        .sp-cal-cell.cal-absent .cal-day-num,
        .sp-cal-cell.cal-absent .cal-status-text {
            color: #991B1B;
        }

        .sp-cal-cell.cal-late {
            background: #FEF3C7;
            border-color: #FCD34D;
        }

        .sp-cal-cell.cal-late .cal-day-num,
        .sp-cal-cell.cal-late .cal-status-text {
            color: #92400E;
        }

        .sp-cal-cell.cal-half {
            background: #DBEAFE;
            border-color: #93C5FD;
        }

        .sp-cal-cell.cal-half .cal-day-num,
        .sp-cal-cell.cal-half .cal-status-text {
            color: #1E40AF;
        }

        .sp-cal-cell.cal-unmarked {
            background: var(--sp-gray-50);
            border-color: var(--sp-gray-200);
        }

        .sp-cal-cell.cal-unmarked .cal-day-num {
            color: var(--sp-gray-400);
        }

        .sp-cal-cell.cal-today {
            border-color: var(--sp-primary);
            background: rgba(79, 70, 229, 0.06);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15);
        }

        .att-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            padding-top: 8px;
            border-top: 1px solid var(--sp-gray-200);
        }

        .leg-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--sp-gray-600);
        }

        .leg-dot {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            border: 1px solid var(--sp-gray-200);
        }

        .dot-present {
            background: #D1FAE5;
            border-color: #6EE7B7;
        }

        .dot-absent {
            background: #FEE2E2;
            border-color: #FCA5A5;
        }

        .dot-late {
            background: #FEF3C7;
            border-color: #FCD34D;
        }

        .dot-half {
            background: #DBEAFE;
            border-color: #93C5FD;
        }

        .dot-unmarked {
            background: var(--sp-gray-50);
            border-color: var(--sp-gray-300);
        }

        /* ----- transport / hostel ----- */
        .th-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .th-grid {
                grid-template-columns: 1fr;
            }
        }

        .th-card {
            border: 1px solid var(--sp-gray-200);
            border-radius: var(--sp-radius-sm);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .th-card:hover {
            border-color: var(--sp-primary-light);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.06);
        }

        .th-card-header {
            padding: 14px 18px;
            font-weight: 700;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--sp-gray-200);
            background: var(--sp-gray-50);
        }

        .th-card-header.th-transport {
            color: var(--sp-primary);
        }

        .th-card-header.th-hostel {
            color: var(--sp-secondary);
        }

        .th-card-body {
            padding: 14px 18px;
        }

        .th-info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            border-bottom: 1px solid var(--sp-gray-100);
        }

        .th-info-row:last-child {
            border-bottom: none;
        }

        .thr-label {
            color: var(--sp-gray-500);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .thr-label i {
            color: var(--sp-primary);
            width: 18px;
            font-size: 14px;
        }

        .thr-value {
            font-weight: 500;
            color: var(--sp-gray-800);
            text-align: right;
        }

        .beh-empty {
            padding: 30px 20px;
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
            font-size: 28px;
            display: block;
            margin-bottom: 8px;
            color: var(--sp-gray-300);
            transition: all 0.3s ease;
        }

        .beh-empty:hover i {
            color: var(--sp-primary-light);
            transform: scale(1.05);
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
    </style>

    <div class="sp-wrapper">
        <div class="sp-container">

            {{-- ---------- PAGE HEADER CARD ---------- --}}
            <div class="sp-page-header-card">
                <div class="sp-header-title">
                    <h2>
                        <i class="bi bi-person-bounding-box"></i> Student Profile
                    </h2>
                    <p>Comprehensive overview of student profile, academic records, attendance, and other relevant
                        information.</p>
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
                                    $fullName = trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
                                    $studentPhoto = $documents->student_photo ?? null;
                                    $avatarUrl = !empty($studentPhoto)
                                        ? url('image/' . $studentPhoto)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($fullName ?: 'Student') . '&size=120&background=4F46E5&color=fff&font-size=0.5';
                                @endphp
                                <img class="sp-avatar" src="{{ $avatarUrl }}" alt="{{ $fullName ?: 'Student' }}">

                                <div class="sp-name">{{ $fullName ?: 'Student' }}</div>
                                <span class="sp-class-badge">
                                    {{ $extra->department ?? 'N/A' }}
                                    {{ $extra->course_subtype ?? $extra->course_type ?? 'N/A' }}
                                </span>
                            </div>

                            {{-- Donut (fee overview) --}}
                            <div class="sp-donut-wrap">
                                <div class="sp-donut"
                                    style="background: conic-gradient(#10B981 {{ $deg }}deg, #FEE2E2 {{ $deg }}deg);">
                                    <div class="donut-center">
                                        <span class="donut-pct">{{ $pct }}%</span>
                                        <span class="donut-lbl">Fees Paid</span>
                                    </div>
                                </div>
                                <div class="sp-donut-title">Payment Overview</div>
                                <div class="sp-donut-legend">
                                    <span class="leg-paid">Paid</span>
                                    <span class="leg-due">Due</span>
                                </div>
                            </div>

                            {{-- Info list --}}
                            <ul class="sp-info-list">
                                <li>
                                    <span class="il-label"><i class="bi bi-upc-scan"></i> Admission</span>
                                    <span class="il-value">{{ $student->registration_number ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-sort-numeric-up"></i> Roll No</span>
                                    <span class="il-value">{{ $extra->roll_no ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-gender-ambiguous"></i> Gender</span>
                                    <span class="il-value">{{ $student->gender ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-house-fill"></i> House</span>
                                    <span class="il-value">{{ $extra->house ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-droplet-fill"></i> Blood</span>
                                    <span class="il-value">
                                        @if($student->blood_group)
                                            <span class="badge bg-danger">{{ $student->blood_group }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </span>
                                </li>
                            </ul>

                            {{-- Actions --}}
                            <div class="sp-actions">
                                <a href="{{ route('students.onboard.edit', $student->student_hash_id ?? '') }}"
                                    class="sp-btn sp-btn-primary"><i class="bi bi-pencil-fill"></i> Edit Profile</a>
                                <a href="#" class="sp-btn sp-btn-outline" onclick="window.print(); return false;"><i
                                        class="bi bi-printer-fill"></i> Print</a>
                            </div>

                            {{-- Student ID Card Actions (Replacing Student Pass & Parent Pass) --}}
                            <div class="sp-cred-actions">
                                <button type="button" class="btn btn-idcard w-100 shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#studentIdCardModal">
                                    <i class="bi bi-person-badge-fill me-1"></i> <b>View Student ID Card</b>
                                </button>
                                <div class="d-flex gap-2 d-none">
                                    <a href="{{ route('students.card.pdf', $student->student_hash_id ?? '') }}"
                                        class="btn btn-outline-primary btn-sm flex-fill fw-semibold" target="_blank">
                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF
                                    </a>
                                    <a href="{{ route('students.card.show', $student->student_hash_id ?? '') }}"
                                        class="btn btn-outline-secondary btn-sm flex-fill fw-semibold" target="_blank">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Fullscreen Page
                                    </a>
                                </div>
                                <a href="#" class="btn btn-collect-fees mt-1 text-center">
                                    <i class="bi bi-currency-rupee me-1"></i> <b>Collect Fees</b>
                                </a>
                            </div>

                            {{-- Barcode & QR --}}
                            <div class="sp-codes d-none">
                                <div class="sp-code-row">
                                    <span class="sp-code-label"><i class="bi bi-upc-scan"></i> Barcode</span>
                                    <img class="sp-code-img"
                                        src="https://barcode.tec-it.com/barcode.ashx?data={{ $student->registration_number ?? 'N/A' }}&code=Code128&dpi=96&imagetype=Png"
                                        alt="Barcode">
                                </div>
                                <div class="sp-code-row">
                                    <span class="sp-code-label"><i class="bi bi-qr-code"></i> QR Code</span>
                                    <img class="sp-code-img sp-qr-img"
                                        src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $student->registration_number ?? 'N/A' }}"
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
                            <ul class="nav nav-tabs" id="studentTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-profile" data-bs-toggle="pill" href="#pane-profile"
                                        role="tab">
                                        <i class="bi bi-person-fill"></i> Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-health" data-bs-toggle="pill" href="#pane-health"
                                        role="tab">
                                        <i class="bi bi-heart-pulse-fill"></i> Health Records
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-siblings" data-bs-toggle="pill" href="#pane-siblings"
                                        role="tab">
                                        <i class="bi bi-people-fill"></i> Siblings
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-fees" data-bs-toggle="pill" href="#pane-fees" role="tab">
                                        <i class="bi bi-wallet-fill"></i> Fees
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-documents" data-bs-toggle="pill" href="#pane-documents"
                                        role="tab">
                                        <i class="bi bi-folder-fill"></i> Documents
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-exams" data-bs-toggle="pill" href="#pane-exams" role="tab">
                                        <i class="bi bi-file-text-fill"></i> Exams & Marks
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-report-card" data-bs-toggle="pill" href="#pane-report-card"
                                        role="tab">
                                        <i class="bi bi-file-earmark-easel-fill"></i> Report Card
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-attendance" data-bs-toggle="pill" href="#pane-attendance"
                                        role="tab">
                                        <i class="bi bi-calendar-check-fill"></i> Attendance
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-transport" data-bs-toggle="pill" href="#pane-transport"
                                        role="tab">
                                        <i class="bi bi-bus-front-fill"></i> Transport
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-hostel" data-bs-toggle="pill" href="#pane-hostel"
                                        role="tab">
                                        <i class="bi bi-building-fill"></i> Hostel
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-admit" data-bs-toggle="pill" href="#pane-admit" role="tab">
                                        <i class="bi bi-file-earmark-check-fill"></i> Admit Card
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-performance" data-bs-toggle="pill" href="#pane-performance"
                                        role="tab">
                                        <i class="bi bi-graph-up"></i> Performance
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">

                            <div class="tab-content">

                                {{-- PANE: PROFILE --}}
                                <div class="tab-pane fade show active" id="pane-profile" role="tabpanel">

                                    {{-- Academic Information --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title"><i class="bi bi-mortarboard-fill"></i> Academic
                                            Information</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Admission No</span>
                                                    <span class="info-value"><span
                                                            class="badge bg-primary">{{ $student->registration_number ?? 'N/A' }}</span></span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Department</span>
                                                    <span class="info-value">{{ $extra->department ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Course / Subtype</span>
                                                    <span
                                                        class="info-value">{{ $extra->course_subtype ?? $extra->course_type ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Academic Year</span>
                                                    <span class="info-value">{{ $extra->academic_year ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Batch</span>
                                                    <span class="info-value">{{ $extra->batch ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Roll Number</span>
                                                    <span class="info-value">{{ $extra->roll_no ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Section</span>
                                                    <span class="info-value">{{ $extra->section_id ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Semester / Session</span>
                                                    <span class="info-value">{{ $extra->semester_id ?? 'N/A' }} /
                                                        {{ $extra->session_id ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">House</span>
                                                    <span class="info-value">{{ $extra->house ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Category</span>
                                                    <span class="info-value">{{ $student->category ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Personal Details --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title"><i class="bi bi-person-badge-fill"></i> Personal
                                            Details</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Date of Birth</span>
                                                    <span class="info-value">{{ $student->dob ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Place of Birth</span>
                                                    <span class="info-value">{{ $student->place_of_birth ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Gender</span>
                                                    <span class="info-value">{{ $student->gender ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Nationality</span>
                                                    <span class="info-value">{{ $student->nationality ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Religion</span>
                                                    <span class="info-value">{{ $student->religion ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Caste / Sub-Caste</span>
                                                    <span class="info-value">{{ $student->caste ?? 'N/A' }}
                                                        {{ $student->sub_caste ? '(' . $student->sub_caste . ')' : '' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Mother Tongue</span>
                                                    <span class="info-value">{{ $student->mother_tongue ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Blood Group</span>
                                                    <span class="info-value">
                                                        @if($student->blood_group)
                                                            <span class="badge bg-danger">{{ $student->blood_group }}</span>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Quotas</span>
                                                    <span class="info-value">{{ $student->quotas ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Contact & Identity --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title"><i class="bi bi-phone-fill"></i> Contact &amp;
                                            Identity</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Student Email</span>
                                                    <span class="info-value">{{ $student->email ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Student Phone</span>
                                                    <span class="info-value">{{ $student->mobile ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Alternate Phone</span>
                                                    <span
                                                        class="info-value">{{ $student->alternate_phone_number ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Aadhaar No</span>
                                                    <span
                                                        class="info-value">{{ $documents->student_aadhaar_number ?? $student->aadhaar ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">PAN No</span>
                                                    <span
                                                        class="info-value">{{ $documents->student_pan_number ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">PEN / SSSM ID</span>
                                                    <span class="info-value">{{ $student->pen ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Parent & Guardian Details --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title"><i class="bi bi-people-fill"></i> Parent &amp;
                                            Guardian Details</div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <div class="parent-card">
                                                    <div class="parent-card-header father">
                                                        <i class="bi bi-person-badge me-1"></i> Father Details
                                                    </div>
                                                    <div class="parent-card-body">
                                                        <div class="info-row"><span class="info-label">Name</span><span
                                                                class="info-value">{{ trim(($student->father_first_name ?? '') . ' ' . ($student->father_middle_name ?? '') . ' ' . ($student->father_last_name ?? '')) ?: 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Phone</span><span
                                                                class="info-value">{{ $student->father_phone ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Email</span><span
                                                                class="info-value">{{ $student->father_email ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span
                                                                class="info-label">Occupation</span><span
                                                                class="info-value">{{ $student->father_occupation ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Income</span><span
                                                                class="info-value">{{ $student->father_income ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="parent-card">
                                                    <div class="parent-card-header mother">
                                                        <i class="bi bi-person-badge me-1"></i> Mother Details
                                                    </div>
                                                    <div class="parent-card-body">
                                                        <div class="info-row"><span class="info-label">Name</span><span
                                                                class="info-value">{{ trim(($student->mother_first_name ?? '') . ' ' . ($student->mother_middle_name ?? '') . ' ' . ($student->mother_last_name ?? '')) ?: 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Phone</span><span
                                                                class="info-value">{{ $student->mother_phone ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Email</span><span
                                                                class="info-value">{{ $student->mother_email ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span
                                                                class="info-label">Occupation</span><span
                                                                class="info-value">{{ $student->mother_occupation ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Income</span><span
                                                                class="info-value">{{ $student->mother_income ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label"
                                                        style="width:140px;min-width:140px;">Guardian</span>
                                                    <span class="info-value">
                                                        {{ trim(($student->guardian_first_name ?? '') . ' ' . ($student->guardian_last_name ?? '')) ?: 'N/A' }}
                                                        {{ $student->guardian_relation ? '(' . $student->guardian_relation . ')' : '' }}
                                                    </span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label" style="width:140px;min-width:140px;">Guardian
                                                        Phone</span>
                                                    <span class="info-value">{{ $student->guardian_phone ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label" style="width:140px;min-width:140px;">Guardian
                                                        Email</span>
                                                    <span class="info-value">{{ $student->guardian_email ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label" style="width:140px;min-width:140px;">Emergency
                                                        Phone</span>
                                                    <span
                                                        class="info-value text-info fw-bold">{{ $student->alternate_phone_number ?? $student->father_phone ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Student Bank Details --}}
                                    <div class="profile-section d-none">
                                        <div class="profile-section-title"><i class="bi bi-bank2"></i> Student Bank Details
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row"><span class="info-label">Bank Name</span><span
                                                        class="info-value">{{ $bank->student_bank_name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row"><span class="info-label">Account No</span><span
                                                        class="info-value">{{ $bank->student_bank_account_number ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row"><span class="info-label">IFSC Code</span><span
                                                        class="info-value">{{ $bank->student_ifsc_code ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Address --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title"><i class="bi bi-geo-alt-fill"></i> Address
                                            Details</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Communication</span>
                                                    <span class="info-value">
                                                        {{ trim(($address->student_comm_address_line1 ?? '') . ' ' . ($address->student_comm_address_line2 ?? '') . ', ' . ($address->student_comm_city ?? '') . ', ' . ($address->student_comm_state ?? '') . ' - ' . ($address->student_comm_pincode ?? '')) ?: 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Permanent</span>
                                                    <span class="info-value">
                                                        {{ trim(($address->student_perm_address_line1 ?? '') . ' ' . ($address->student_perm_address_line2 ?? '') . ', ' . ($address->student_perm_city ?? '') . ', ' . ($address->student_perm_state ?? '') . ' - ' . ($address->student_perm_pincode ?? '')) ?: 'N/A' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>{{-- /pane-profile --}}

                                {{-- PANE: HEALTH RECORDS --}}
                                <div class="tab-pane fade" id="pane-health" role="tabpanel">

                                    {{-- Student Health & Medical Records --}}
                                    <div class="profile-section mb-4">
                                        <div class="profile-section-title"><i class="bi bi-heart-pulse-fill"></i> Student
                                            Health &amp; Medical Records</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Height</span>
                                                    <span class="info-value">{{ $student->height ?? 'N/A' }}
                                                        {{ $student->height_unit ?? 'cm' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Weight</span>
                                                    <span class="info-value">{{ $student->weight ?? 'N/A' }}
                                                        {{ $student->weight_unit ?? 'kg' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Blood Group</span>
                                                    <span class="info-value">
                                                        @if($student->blood_group)
                                                            <span class="badge bg-danger"
                                                                style="font-size: 14px; padding: 6px 12px;">{{ $student->blood_group }}</span>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Health Condition</span>
                                                    <span
                                                        class="info-value">{{ $student->health_condition ?? 'Normal' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-row">
                                                    <span class="info-label">Allergies</span>
                                                    <span class="info-value">{{ $student->allergies ?? 'None' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Medical Notes</span>
                                                    <span
                                                        class="info-value">{{ $student->medical_notes ?? 'No specific notes' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">Medical History</span>
                                                    <span
                                                        class="info-value">{{ $student->medical_history ?? 'None reported' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Parents/Guardian Health & Blood Group Info --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title"><i class="bi bi-people-fill"></i> Parents &amp;
                                            Guardian Health Information</div>
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-3 mb-md-0">
                                                <div class="parent-card">
                                                    <div class="parent-card-header father">
                                                        <i class="bi bi-person-badge me-1"></i> Father Health Info
                                                    </div>
                                                    <div class="parent-card-body">
                                                        <div class="info-row"><span class="info-label">Name</span><span
                                                                class="info-value">{{ trim(($student->father_first_name ?? '') . ' ' . ($student->father_last_name ?? '')) ?: 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Blood
                                                                Group</span><span class="info-value">
                                                                @if($student->father_blood_group)
                                                                    <span
                                                                        class="badge bg-danger">{{ $student->father_blood_group }}</span>
                                                                @else
                                                                    <span class="text-muted">N/A</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Age</span><span
                                                                class="info-value">
                                                                @if($student->father_dob)
                                                                    @php
                                                                        $fatherAge = now()->diffInYears(\Carbon\Carbon::parse($student->father_dob));
                                                                    @endphp
                                                                    {{ $fatherAge }} years
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="parent-card">
                                                    <div class="parent-card-header mother">
                                                        <i class="bi bi-person-badge me-1"></i> Mother Health Info
                                                    </div>
                                                    <div class="parent-card-body">
                                                        <div class="info-row"><span class="info-label">Name</span><span
                                                                class="info-value">{{ trim(($student->mother_first_name ?? '') . ' ' . ($student->mother_last_name ?? '')) ?: 'N/A' }}</span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Blood
                                                                Group</span><span class="info-value">
                                                                @if($student->mother_blood_group)
                                                                    <span
                                                                        class="badge bg-danger">{{ $student->mother_blood_group }}</span>
                                                                @else
                                                                    <span class="text-muted">N/A</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="info-row"><span class="info-label">Age</span><span
                                                                class="info-value">
                                                                @if($student->mother_dob)
                                                                    @php
                                                                        $motherAge = now()->diffInYears(\Carbon\Carbon::parse($student->mother_dob));
                                                                    @endphp
                                                                    {{ $motherAge }} years
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($student->guardian_first_name)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="parent-card">
                                                        <div class="parent-card-header" style="color: #7C3AED;">
                                                            <i class="bi bi-person-badge me-1"></i> Guardian Health Info
                                                        </div>
                                                        <div class="parent-card-body">
                                                            <div class="info-row"><span class="info-label">Name</span><span
                                                                    class="info-value">{{ trim(($student->guardian_first_name ?? '') . ' ' . ($student->guardian_last_name ?? '')) ?: 'N/A' }}</span>
                                                            </div>
                                                            <div class="info-row"><span class="info-label">Blood
                                                                    Group</span><span class="info-value">
                                                                    @if($student->guardian_blood_group)
                                                                        <span
                                                                            class="badge bg-danger">{{ $student->guardian_blood_group }}</span>
                                                                    @else
                                                                        <span class="text-muted">N/A</span>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="info-row"><span class="info-label">Age</span><span
                                                                    class="info-value">
                                                                    @if($student->guardian_dob)
                                                                        @php
                                                                            $guardianAge = now()->diffInYears(\Carbon\Carbon::parse($student->guardian_dob));
                                                                        @endphp
                                                                        {{ $guardianAge }} years
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>{{-- /pane-health --}}

                                {{-- PANE: SIBLINGS --}}
                                <div class="tab-pane fade" id="pane-siblings" role="tabpanel">
                                    <div class="sib-header">
                                        <i class="bi bi-people-fill"></i> Sibling Information
                                        <span class="sib-count">{{ $siblings->count() }}</span>
                                    </div>
                                    @if(isset($siblings) && $siblings->count() > 0)
                                        <div class="sib-grid">
                                            @foreach($siblings as $sib)
                                                @php
                                                    $sibStudent = $sib->studentDetails;
                                                    $sibHashId = $sibStudent->student_hash_id ?? $sib->student_hash_id ?? null;
                                                    $sibFullName = $sibStudent ? trim(($sibStudent->first_name ?? '') . ' ' . ($sibStudent->middle_name ?? '') . ' ' . ($sibStudent->last_name ?? '')) : 'Sibling';
                                                    $sibRegNo = $sibStudent ? $sibStudent->registration_number : 'N/A';
                                                    $sibGender = $sibStudent ? $sibStudent->gender : 'N/A';
                                                    $sibStatus = $sibStudent ? ($sibStudent->status ?? $sibStudent->student_status ?? 'Active') : 'Active';
                                                @endphp
                                                <div class="sib-card">
                                                    <div class="sib-card-top">
                                                        <div class="css-avatar">{{ substr($sibFullName, 0, 1) }}</div>
                                                        <div class="sib-card-info">
                                                            <div class="sib-name">{{ $sibFullName }}</div>
                                                            <div class="sib-class">Admission: {{ $sibRegNo }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="sib-card-body">
                                                        <div class="sib-detail"><span class="sd-label">Gender</span><span
                                                                class="sd-value">{{ $sibGender }}</span></div>
                                                        <div class="sib-detail"><span class="sd-label">Status</span><span
                                                                class="sd-value"><span
                                                                    class="badge bg-success">{{ ucfirst($sibStatus) }}</span></span>
                                                        </div>
                                                        <div class="sib-detail"><span class="sd-label">Blood Group</span><span
                                                                class="sd-value">
                                                                @if($sibStudent && $sibStudent->blood_group)
                                                                    <span class="badge bg-danger">{{ $sibStudent->blood_group }}</span>
                                                                @else
                                                                    <span class="text-muted">N/A</span>
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="sib-detail"><span class="sd-label">Class/Dept</span><span
                                                                class="sd-value">
                                                                @if($sibStudent)
                                                                    @php
                                                                        $sibExtra = $sibStudent->extra;
                                                                        $sibClassInfo = $sibExtra ? ($sibExtra->course_subtype ?? $sibExtra->course_type ?? 'N/A') : 'N/A';
                                                                    @endphp
                                                                    {{ $sibClassInfo }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </span>
                                                        </div>

                                                        @if($sibHashId)
                                                            <a href="{{ url('/student-details/' . $sibHashId) }}"
                                                                class="btn btn-sm btn-outline-primary w-100 fw-semibold mt-3">
                                                                <i class="bi bi-eye-fill me-1"></i> View Profile
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="beh-empty">
                                            <i class="bi bi-person-x-fill"></i> No siblings recorded for this student.
                                        </div>
                                    @endif
                                </div>


                                {{-- PANE: FEES --}}
                                <div class="tab-pane fade" id="pane-fees" role="tabpanel">

                                    @if(isset($feeGroups) && !empty($feeGroups))
                                        @foreach($feeGroups as $key => $group)
                                            @php
                                                $items = $group['items']->toArray() ?? [];
                                                $totalCount = count($items);
                                                $paidCount = count(array_filter($items, function ($f) {
                                                    return $f['computed_due'] == 0;
                                                }));
                                                $unpaidCount = $totalCount - $paidCount;
                                            @endphp
                                            <div class="sp-fee-group">
                                                <div class="sp-fee-group-header"
                                                    style="display: flex; justify-content: space-between; align-items: center;">
                                                    <div class="d-flex align-items-center" data-bs-toggle="collapse"
                                                        data-bs-target="#feeGroup{{ $key }}"
                                                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                                        style="cursor: pointer;">
                                                        <i class="bi bi-chevron-right group-chevron me-2"></i>
                                                        <i class="bi bi-folder-fill text-warning me-2"></i>
                                                        <span class="group-name">{{ $group['label'] }}</span>
                                                    </div>
                                                    <div style="display: flex; gap: 20px; align-items: center;">
                                                        <div style="display: flex; gap: 24px; align-items: center;">
                                                            <div style="text-align: center; min-width: 60px;">
                                                                <div
                                                                    style="font-size: 16px; font-weight: 700; color: var(--sp-primary);">
                                                                    {{ $totalCount }}
                                                                </div>
                                                                <div
                                                                    style="font-size: 11px; color: var(--sp-gray-500); font-weight: 600; text-transform: uppercase;">
                                                                    Total</div>
                                                            </div>
                                                            <div style="text-align: center; min-width: 60px;">
                                                                <div
                                                                    style="font-size: 16px; font-weight: 700; color: var(--sp-success);">
                                                                    {{ $paidCount }}
                                                                </div>
                                                                <div
                                                                    style="font-size: 11px; color: var(--sp-gray-500); font-weight: 600; text-transform: uppercase;">
                                                                    Paid</div>
                                                            </div>
                                                            <div style="text-align: center; min-width: 60px;">
                                                                <div
                                                                    style="font-size: 16px; font-weight: 700; color: var(--sp-danger);">
                                                                    {{ $unpaidCount }}
                                                                </div>
                                                                <div
                                                                    style="font-size: 11px; color: var(--sp-gray-500); font-weight: 600; text-transform: uppercase;">
                                                                    Unpaid</div>
                                                            </div>
                                                        </div>
                                                        @php
                                                            $routeMap = [
                                                                'course' => route('get.course.fee'),
                                                                'transport' => route('get.transport.fee'),
                                                                'registration' => route('get.registration.fee'),
                                                                'hostel' => route('get.hostel.fee'),
                                                                'custom' => route('get.miscellaneous.fee'),
                                                                'misc' => route('get.miscellaneous.fee')
                                                            ];
                                                            $navigationUrl = $routeMap[$key] ?? route('get.course.fee');
                                                        @endphp
                                                        <a href="{{ $navigationUrl }}" class="btn btn-sm btn-outline-primary"
                                                            style="padding: 6px 12px; font-size: 12px; white-space: nowrap;">
                                                            <i class="bi bi-eye-fill me-1"></i> View
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="collapse {{ $loop->first ? 'show' : '' }}" id="feeGroup{{ $key }}">
                                                    <div style="overflow-x: auto; border-radius: 8px;">
                                                        <table class="sp-table" style="min-width: 900px;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="min-width: 140px;">Fee Type</th>
                                                                    <th style="min-width: 120px;">Due Date</th>
                                                                    <th style="min-width: 150px;">Payment Date</th>
                                                                    <th class="text-end" style="min-width: 100px;">Amount</th>
                                                                    <th class="text-end" style="min-width: 100px;">Paid</th>
                                                                    <th class="text-end" style="min-width: 100px;">Due</th>
                                                                    <th class="text-center" style="min-width: 130px;">Payment Status
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($group['items'] as $fee)
                                                                    @php
                                                                        $dueDate = isset($fee->computed_due_date) ? \Carbon\Carbon::parse($fee->computed_due_date) : null;
                                                                        $paidDate = isset($fee->pay_date) ? \Carbon\Carbon::parse($fee->pay_date) : null;
                                                                        $daysDifference = null;
                                                                        $statusIndicator = '';

                                                                        if ($dueDate && $paidDate && $fee->computed_paid > 0) {
                                                                            $daysDifference = $paidDate->diffInDays($dueDate);
                                                                            if ($paidDate > $dueDate) {
                                                                                $statusIndicator = "<span class=\"badge bg-danger\"><i class=\"bi bi-exclamation-triangle-fill me-1\"></i>" . $daysDifference . " Days Late</span>";
                                                                            } else {
                                                                                $statusIndicator = "<span class=\"badge bg-success\"><i class=\"bi bi-check-circle-fill me-1\"></i>" . $daysDifference . " Days Early</span>";
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <tr>
                                                                        <td style="font-weight: 500;">{{ $group['label'] }}
                                                                            {{ $loop->index + 1 }}
                                                                        </td>
                                                                        <td>{{ $dueDate ? $dueDate->format('d M, Y') : 'N/A' }}</td>
                                                                        <td>
                                                                            <div>{{ $paidDate ? $paidDate->format('d M, Y') : 'N/A' }}
                                                                            </div>
                                                                            @if($statusIndicator)
                                                                                <div style="margin-top: 4px;">{!! $statusIndicator !!}</div>
                                                                            @else
                                                                                <div style="margin-top: 4px;"><span
                                                                                        class="text-muted small">Not Paid</span></div>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-end" style="font-weight: 600;">
                                                                            ₹{{ number_format($fee->computed_amount, 2) }}</td>
                                                                        <td class="text-end text-success" style="font-weight: 600;">
                                                                            ₹{{ number_format($fee->computed_paid, 2) }}</td>
                                                                        <td class="text-end text-danger" style="font-weight: 600;">
                                                                            ₹{{ number_format($fee->computed_due, 2) }}</td>
                                                                        <td class="text-center">
                                                                            <span class="badge bg-{{ $fee->computed_status_class }}"
                                                                                style="padding: 6px 10px; font-size: 11px;">
                                                                                <i
                                                                                    class="bi {{ $fee->computed_status_class === 'success' ? 'bi-check-circle-fill' : ($fee->computed_status_class === 'warning' ? 'bi-clock-history' : 'bi-x-circle-fill') }} me-1"></i>
                                                                                {{ $fee->computed_status_label }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="beh-empty">
                                            <i class="bi bi-file-earmark-text-fill"></i> No fee structures assigned to this
                                            student.
                                        </div>
                                    @endif
                                </div>

                                {{-- PANE: DOCUMENTS (Separated Student & Parent/Guardian Docs with Fixed Links) --}}
                                <div class="tab-pane fade" id="pane-documents" role="tabpanel">
                                    @php
                                        $studentDocMap = [
                                            'Student Photo' => $documents->student_photo ?? null,
                                            'Student ID Card' => $documents->student_id_card ?? null,
                                            'Student Aadhaar Card' => $documents->student_aadhaar_file ?? null,
                                            'Student PAN Card' => $documents->student_pan_file ?? null,
                                            'Student Address Proof' => $documents->student_address_proof ?? null,
                                            'Student Bonafide Certificate' => $documents->student_bonafide ?? null,
                                            '10th Marksheet' => $documents->marksheet_10 ?? null,
                                            '12th Marksheet' => $documents->marksheet_12 ?? null,
                                            'Bachelor Marksheet' => $documents->bachelor_marksheet ?? null,
                                            'Student Bank Cancelled Cheque' => $bank->student_upload_cancelled_cheque ?? null,
                                        ];

                                        $parentDocMap = [
                                            'Parent Aadhaar Card' => $documents->parent_aadhaar_file ?? null,
                                            'Parent PAN Card' => $documents->parent_pan_file ?? null,
                                            'Parent Income Proof' => $documents->parent_income_proof ?? null,
                                            'Parent Photo' => $documents->parent_photo ?? null,
                                            'Parent Address Proof' => $documents->parent_address_proof ?? null,
                                            'Guardian Aadhaar Card' => $documents->guardian_aadhaar_file ?? null,
                                            'Guardian PAN Card' => $documents->guardian_pan_file ?? null,
                                            'Guardian Photo' => $documents->guardian_photo ?? null,
                                            'Guardian Address Proof' => $documents->guardian_address_proof ?? null,
                                            'Parent Bank Cancelled Cheque' => $bank->upload_cancelled_cheque ?? null,
                                        ];

                                        function buildDocUrl($rawPath)
                                        {
                                            if (empty($rawPath))
                                                return null;
                                            return url('image/' . $rawPath);
                                        }

                                        $totalStudentDocs = count($studentDocMap);
                                        $availableStudentDocs = 0;
                                        foreach ($studentDocMap as $rawPath) {
                                            if (!empty($rawPath))
                                                $availableStudentDocs++;
                                        }
                                        $totalParentDocs = count($parentDocMap);
                                        $availableParentDocs = 0;
                                        foreach ($parentDocMap as $rawPath) {
                                            if (!empty($rawPath))
                                                $availableParentDocs++;
                                        }
                                    @endphp

                                    {{-- Student Documents Section --}}
                                    <div class="profile-section mb-4">
                                        <div class="profile-section-title">
                                            <i class="bi bi-person-bounding-box"></i> Student Documents
                                            <span
                                                class="badge bg-primary ms-auto">{{ $availableStudentDocs }}/{{ $totalStudentDocs }}
                                                uploaded</span>
                                        </div>

                                        {{-- Document Statistics --}}
                                        <div class="doc-stats">
                                            <span class="doc-stat-item">
                                                <span class="stat-dot total"></span> Total:
                                                <strong>{{ $totalStudentDocs }}</strong>
                                            </span>
                                            <span class="doc-stat-item">
                                                <span class="stat-dot available"></span> Uploaded:
                                                <strong>{{ $availableStudentDocs }}</strong>
                                            </span>
                                            <span class="doc-stat-item">
                                                <span class="stat-dot missing"></span> Missing:
                                                <strong>{{ $totalStudentDocs - $availableStudentDocs }}</strong>
                                            </span>
                                        </div>

                                        <div class="documents-grid">
                                            @foreach($studentDocMap as $label => $rawPath)
                                                @php
                                                    $docUrl = buildDocUrl($rawPath);
                                                    $isAvailable = !empty($docUrl);
                                                @endphp
                                                <div class="document-item {{ $isAvailable ? '' : 'opacity-75' }}">
                                                    <div class="doc-info">
                                                        <span class="doc-icon">
                                                            <i
                                                                class="bi {{ $isAvailable ? 'bi-file-earmark-pdf-fill text-danger' : 'bi-file-earmark-x-fill text-muted' }}"></i>
                                                        </span>
                                                        <div class="doc-text">
                                                            <span class="doc-name" title="{{ $label }}">{{ $label }}</span>
                                                            <div class="doc-meta">
                                                                @if($isAvailable)
                                                                    <span class="status-available">
                                                                        <i class="bi bi-check-circle-fill"></i> Available
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

                                    {{-- Parent & Guardian Documents Section --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <i class="bi bi-people-fill"></i> Parent &amp; Guardian Documents
                                            <span
                                                class="badge bg-primary ms-auto">{{ $availableParentDocs }}/{{ $totalParentDocs }}
                                                uploaded</span>
                                        </div>

                                        {{-- Document Statistics --}}
                                        <div class="doc-stats">
                                            <span class="doc-stat-item">
                                                <span class="stat-dot total"></span> Total:
                                                <strong>{{ $totalParentDocs }}</strong>
                                            </span>
                                            <span class="doc-stat-item">
                                                <span class="stat-dot available"></span> Uploaded:
                                                <strong>{{ $availableParentDocs }}</strong>
                                            </span>
                                            <span class="doc-stat-item">
                                                <span class="stat-dot missing"></span> Missing:
                                                <strong>{{ $totalParentDocs - $availableParentDocs }}</strong>
                                            </span>
                                        </div>

                                        <div class="documents-grid">
                                            @foreach($parentDocMap as $label => $rawPath)
                                                @php
                                                    $docUrl = buildDocUrl($rawPath);
                                                    $isAvailable = !empty($docUrl);
                                                @endphp
                                                <div class="document-item {{ $isAvailable ? '' : 'opacity-75' }}">
                                                    <div class="doc-info">
                                                        <span class="doc-icon">
                                                            <i
                                                                class="bi {{ $isAvailable ? 'bi-file-earmark-pdf-fill text-danger' : 'bi-file-earmark-x-fill text-muted' }}"></i>
                                                        </span>
                                                        <div class="doc-text">
                                                            <span class="doc-name" title="{{ $label }}">{{ $label }}</span>
                                                            <div class="doc-meta">
                                                                @if($isAvailable)
                                                                    <span class="status-available">
                                                                        <i class="bi bi-check-circle-fill"></i> Available
                                                                    </span>
                                                                @else
                                                                    <span class="status-missing">
                                                                        <i class="bi bi-hourglass-split"></i> Not uploaded
                                                                    </span>
                                                                @endif
                                                                <span class="meta-dot"></span>
                                                                <span>{{ $isAvailable ? 'Ready to view' : 'Pending upload' }}</span>
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

                                </div>{{-- /pane-documents --}}

                                {{-- PANE: EXAMS & REPORT CARD --}}
                                <div class="tab-pane fade" id="pane-exams" role="tabpanel">

                                    {{-- Upcoming Examinations Section --}}
                                    <div class="profile-section mb-4">
                                        <div class="profile-section-title"><i
                                                class="bi bi-calendar-event-fill text-primary"></i> Upcoming Examinations
                                        </div>
                                        <div style="padding:16px 20px;">
                                            @if(isset($upcomingExams) && $upcomingExams->count() > 0)
                                                <div class="row g-3">
                                                    @foreach($upcomingExams as $uExam)
                                                        @php
                                                            $uName = $uExam->examNameDetail->exam_name ?? 'Upcoming Exam';
                                                            $uSub = $uExam->subject->subject_name ?? 'Subject';
                                                            $uDate = isset($uExam->exam_date) ? \Carbon\Carbon::parse($uExam->exam_date)->format('M d, Y (D)') : 'TBD';
                                                            $uTime = isset($uExam->start_time) ? \Carbon\Carbon::parse($uExam->start_time)->format('h:i A') : 'N/A';
                                                        @endphp
                                                        <div class="col-md-6 ">
                                                            <div class="card h-100 border-0 shadow-sm"
                                                                style="border-radius:12px; background:#F8FAFC; border-left:4px solid var(--sp-primary) !important;">
                                                                <div class="card-body p-3">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-start mb-2">
                                                                        <span class="badge bg-primary px-2 py-1">{{ $uSub }}</span>
                                                                        <span class="badge bg-warning text-dark"><i
                                                                                class="bi bi-clock-history"></i> Scheduled</span>
                                                                    </div>
                                                                    <h6 class="fw-bold text-dark mb-1">{{ $uName }}</h6>
                                                                    <div class="small text-muted mb-2"><i
                                                                            class="bi bi-calendar3 text-primary me-1"></i>
                                                                        {{ $uDate }}
                                                                    </div>
                                                                    <div
                                                                        class="d-flex justify-content-between text-muted small border-top pt-2 mt-2">
                                                                        <span><i class="bi bi-clock me-1"></i> {{ $uTime }}</span>
                                                                        <span><strong>Total:</strong>
                                                                            {{ $uExam->total_marks ?? 100 }} Marks</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="beh-empty" style="padding:40px;">
                                                    <i class="bi bi-calendar-x-fill"></i>
                                                    <p>No upcoming exams scheduled at the moment.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Subject Marks & Detailed Scores --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title"><i class="bi bi-file-text-fill"></i> Subject
                                            Marks &amp; Exam Performance</div>
                                        <div class="sp-table-wrap">
                                            @if(isset($examMarks) && $examMarks->count() > 0)
                                                <div class="table-responsive-custom">
                                                    <table class="sp-table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Exam Name</th>
                                                                <th>Subject</th>
                                                                <th class="text-end">Total Marks</th>
                                                                <th class="text-end">Passing Marks</th>
                                                                <th class="text-end">Obtained Marks</th>
                                                                <th class="text-center">Percentage</th>
                                                                <th class="text-center">Grade</th>
                                                                <th class="text-center">Result</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($examMarks as $index => $mark)
                                                                @php
                                                                    $totalM = floatval($mark->total_marks ?? 100);
                                                                    $obtM = floatval($mark->obtained_marks ?? 0);
                                                                    $passM = floatval($mark->passing_marks ?? 33);
                                                                    $perc = $totalM > 0 ? round(($obtM / $totalM) * 100, 2) : 0;
                                                                    $isPass = $obtM >= $passM;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td class="exam-title">
                                                                        {{ $mark->exam->exam_name ?? 'Semester Examination' }}
                                                                    </td>
                                                                    <td>{{ $mark->subject->subject_name ?? 'Subject' }}</td>
                                                                    <td class="text-end">{{ $totalM }}</td>
                                                                    <td class="text-end">{{ $passM }}</td>
                                                                    <td
                                                                        class="text-end fw-bold {{ $isPass ? 'text-success' : 'text-danger' }}">
                                                                        {{ $obtM }}
                                                                    </td>
                                                                    <td class="text-center">{{ $perc }}%</td>
                                                                    <td class="text-center"><span
                                                                            class="badge bg-secondary">{{ $mark->grade ?? 'A' }}</span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span class="badge bg-{{ $isPass ? 'success' : 'danger' }}">
                                                                            {{ $isPass ? 'Pass' : 'Fail' }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="beh-empty" style="padding:40px;">
                                                    <i class="bi bi-file-earmark-text-fill"></i>
                                                    <p>No exam marks recorded yet.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>{{-- /pane-exams --}}

                                {{-- PANE: REPORT CARD --}}
                                <div class="tab-pane fade" id="pane-report-card" role="tabpanel">

                                    {{-- Student Report Cards Section --}}
                                    <div class="profile-section mb-4">
                                        <div class="profile-section-title"><i class="bi bi-file-earmark-easel-fill"></i>
                                            Student Academic Report Cards</div>
                                        <div style="padding:16px 20px;">
                                            <div class="documents-grid">
                                                <div class="document-item">
                                                    <div class="doc-info">
                                                        <span class="doc-icon">
                                                            <i class="bi bi-award-fill text-warning"></i>
                                                        </span>
                                                        <div class="doc-text">
                                                            <span class="doc-name">Official Progress Report Card
                                                                ({{ date('Y') }})</span>
                                                            <div class="doc-meta">
                                                                <span class="status-available">
                                                                    <i class="bi bi-check-circle-fill"></i> Available
                                                                </span>
                                                                <span class="meta-dot"></span>
                                                                <span>Click to view or print</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="doc-actions">
                                                        <button type="button" class="doc-btn doc-btn-view"
                                                            data-bs-toggle="modal" data-bs-target="#reportCardModal">
                                                            <i class="bi bi-eye-fill"></i> View
                                                        </button>
                                                        <button type="button" class="doc-btn doc-btn-download"
                                                            onclick="printReportCard()">
                                                            <i class="bi bi-printer-fill"></i> Print
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>{{-- /pane-report-card --}}

                                {{-- PANE: ATTENDANCE (With Changable Month & Year Dropdowns) --}}
                                <div class="tab-pane fade" id="pane-attendance" role="tabpanel">
                                    <div class="att-stats-row">
                                        <div class="text-center">
                                            <div class="sp-donut" id="attDonut"
                                                style="background: conic-gradient(#10B981 {{ $attDeg ?? 0 }}deg, #FEE2E2 {{ $attDeg ?? 0 }}deg); width:120px;height:120px;margin:0 auto 4px;">
                                                <div class="donut-center">
                                                    <span class="donut-pct" id="attPctText">{{ $attPct ?? 0 }}%</span>
                                                    <span class="donut-lbl">Present</span>
                                                </div>
                                            </div>
                                            <div class="sp-donut-legend mt-1">
                                                <span class="leg-paid">Present</span>
                                                <span class="leg-due">Absent</span>
                                            </div>
                                        </div>
                                        <div class="att-mini-cards">
                                            <div class="att-mc mc-present">
                                                <div class="mc-count" id="attPresentCnt">{{ $attPresentCount ?? 0 }}</div>
                                                <div class="mc-label">Present</div>
                                            </div>
                                            <div class="att-mc mc-absent">
                                                <div class="mc-count" id="attAbsentCnt">{{ $attAbsentCount ?? 0 }}</div>
                                                <div class="mc-label">Absent</div>
                                            </div>
                                            <div class="att-mc mc-late">
                                                <div class="mc-count" id="attLateCnt">{{ $attLateCount ?? 0 }}</div>
                                                <div class="mc-label">Late</div>
                                            </div>
                                            <div class="att-mc mc-half">
                                                <div class="mc-count" id="attHalfCnt">{{ $attHalfDayCount ?? 0 }}</div>
                                                <div class="mc-label">Half Day</div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Dynamic Month & Year Selectors --}}
                                    <div class="att-month-nav bg-light p-2 rounded border mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="prevMonth()"><i class="bi bi-chevron-left"></i> Prev</button>
                                            <span class="att-month-title mb-0" id="attMonthYearTitle">
                                                <i class="bi bi-calendar3 me-1" style="color:var(--sp-primary);"></i>
                                                {{ date('F Y') }}
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="nextMonth()">Next <i class="bi bi-chevron-right"></i></button>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <select id="attMonthSelect" class="form-select form-select-sm"
                                                onchange="onAttendanceDateChange()" style="width: 130px;">
                                                @foreach(['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $mNum => $mName)
                                                    <option value="{{ $mNum }}" {{ date('m') == $mNum ? 'selected' : '' }}>
                                                        {{ $mName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <select id="attYearSelect" class="form-select form-select-sm"
                                                onchange="onAttendanceDateChange()" style="width: 100px;">
                                                @for($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                                                    <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Dynamic Month Calendar Grid --}}
                                    <div class="sp-cal-grid" id="attCalGrid">
                                        {{-- Populated dynamically via Javascript --}}
                                    </div>

                                    <div class="att-legend">
                                        <span class="leg-item"><span class="leg-dot dot-present"></span> Present</span>
                                        <span class="leg-item"><span class="leg-dot dot-absent"></span> Absent</span>
                                        <span class="leg-item"><span class="leg-dot dot-late"></span> Late</span>
                                        <span class="leg-item"><span class="leg-dot dot-half"></span> Half Day</span>
                                        <span class="leg-item"><span class="leg-dot dot-unmarked"></span> No Record</span>
                                    </div>

                                </div>{{-- /pane-attendance --}}

                                {{-- PANE: TRANSPORT --}}
                                <div class="tab-pane fade" id="pane-transport" role="tabpanel">
                                    <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                                        <i class="bi bi-bus-front-fill text-primary"></i> Transport Route &amp; Pickup
                                        Details
                                        <span
                                            class="badge bg-secondary">{{ isset($transportRoutesList) ? $transportRoutesList->count() : 0 }}
                                            Assigned</span>
                                    </h6>
                                    <div class="row g-3">
                                        @if(isset($transportRoutesList) && $transportRoutesList->count() > 0)
                                            @foreach($transportRoutesList as $tIdx => $tRouteItem)
                                                <div class="col-md-6">
                                                    <div class="th-card h-100">
                                                        <div class="th-card-header th-transport">
                                                            <i class="bi bi-bus-front-fill"></i> Transport Route #{{ $tIdx + 1 }}
                                                        </div>
                                                        <div class="th-card-body">
                                                            <div class="th-info-row">
                                                                <span class="thr-label"><i class="bi bi-arrow-left-right"></i>
                                                                    Route</span>
                                                                <span
                                                                    class="thr-value">{{ $tRouteItem['route_name'] ?? 'N/A' }}</span>
                                                            </div>
                                                            <div class="th-info-row">
                                                                <span class="thr-label"><i class="bi bi-geo-alt-fill"></i> Stop /
                                                                    Pickup</span>
                                                                <span class="thr-value">{{ $tRouteItem['stop'] ?? 'N/A' }}</span>
                                                            </div>
                                                            <div class="th-info-row">
                                                                <span class="thr-label"><i class="bi bi-truck"></i> Vehicle
                                                                    Number</span>
                                                                <span class="thr-value">{{ $tRouteItem['vehicle'] ?? 'N/A' }}</span>
                                                            </div>
                                                            <div class="th-info-row">
                                                                <span class="thr-label"><i class="bi bi-person-fill"></i> Driver
                                                                    Name</span>
                                                                <span class="thr-value">{{ $tRouteItem['driver'] ?? 'N/A' }}</span>
                                                            </div>
                                                            @if(isset($tRouteItem['driver_contact']) && $tRouteItem['driver_contact'])
                                                                <div class="th-info-row">
                                                                    <span class="thr-label"><i class="bi bi-telephone-fill"></i> Driver
                                                                        Contact</span>
                                                                    <span class="thr-value">{{ $tRouteItem['driver_contact'] }}</span>
                                                                </div>
                                                            @endif
                                                            <div class="th-info-row">
                                                                <span class="thr-label"><i class="bi bi-currency-rupee"></i> Fare
                                                                    Amount</span>
                                                                <span
                                                                    class="thr-value">{{ isset($tRouteItem['fare']) && $tRouteItem['fare'] ? '₹' . number_format($tRouteItem['fare'], 2) : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="th-card">
                                                    <div class="th-card-header th-transport">
                                                        <i class="bi bi-bus-front-fill"></i> Transport Details
                                                    </div>
                                                    <div class="th-card-body">
                                                        <div class="th-info-row">
                                                            <span class="thr-label"><i class="bi bi-arrow-left-right"></i>
                                                                Route</span>
                                                            <span
                                                                class="thr-value">{{ $transportData['route_name'] ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="th-info-row">
                                                            <span class="thr-label"><i class="bi bi-geo-alt-fill"></i> Stop /
                                                                Pickup</span>
                                                            <span class="thr-value">{{ $transportData['stop'] ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="th-info-row">
                                                            <span class="thr-label"><i class="bi bi-truck"></i> Vehicle
                                                                Number</span>
                                                            <span
                                                                class="thr-value">{{ $transportData['vehicle'] ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="th-info-row">
                                                            <span class="thr-label"><i class="bi bi-person-fill"></i> Driver
                                                                Name</span>
                                                            <span
                                                                class="thr-value">{{ $transportData['driver'] ?? 'N/A' }}</span>
                                                        </div>
                                                        <div class="th-info-row">
                                                            <span class="thr-label"><i class="bi bi-currency-rupee"></i> Fare
                                                                Amount</span>
                                                            <span
                                                                class="thr-value">{{ isset($transportData['fare']) && $transportData['fare'] ? '₹' . number_format($transportData['fare'], 2) : 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>{{-- /pane-transport --}}

                                {{-- PANE: HOSTEL --}}
                                <div class="tab-pane fade" id="pane-hostel" role="tabpanel">
                                    <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                                        <i class="bi bi-building-fill text-purple"></i> Hostel Accommodation Details
                                    </h6>
                                    <div class="th-card">
                                        <div class="th-card-header th-hostel">
                                            <i class="bi bi-building-fill"></i> Hostel Information
                                        </div>
                                        <div class="th-card-body">
                                            <div class="th-info-row">
                                                <span class="thr-label"><i class="bi bi-building-fill"></i> Hostel
                                                    Name</span>
                                                <span class="thr-value">{{ $hostelData['hostel'] ?? 'N/A' }}</span>
                                            </div>
                                            <div class="th-info-row">
                                                <span class="thr-label"><i class="bi bi-door-open-fill"></i> Room
                                                    Number</span>
                                                <span class="thr-value">{{ $hostelData['room_no'] ?? 'N/A' }}</span>
                                            </div>
                                            <div class="th-info-row">
                                                <span class="thr-label"><i class="bi bi-grid-fill"></i> Room Type</span>
                                                <span class="thr-value">{{ $hostelData['room_type'] ?? 'N/A' }}</span>
                                            </div>
                                            <div class="th-info-row">
                                                <span class="thr-label"><i class="bi bi-currency-rupee"></i> Hostel
                                                    Cost</span>
                                                <span
                                                    class="thr-value">{{ isset($hostelData['cost']) && $hostelData['cost'] ? '₹' . number_format($hostelData['cost'], 2) : 'N/A' }}</span>
                                            </div>
                                            <div class="th-info-row">
                                                <span class="thr-label"><i class="bi bi-calendar3"></i> Check-in Date</span>
                                                <span class="thr-value">{{ $hostelData['check_in'] ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>{{-- /pane-hostel --}}

                                {{-- PANE: ADMIT CARD & PERFORMANCE --}}
                                <div class="tab-pane fade" id="pane-admit" role="tabpanel">

                                    {{-- Student Admit Card Section --}}
                                    <div class="profile-section">
                                        <div class="profile-section-title">
                                            <i class="bi bi-file-earmark-check-fill"></i> Student Admit Card
                                        </div>
                                        <div style="padding: 20px; text-align: center;">
                                            <p class="text-muted mb-3">Access your official admit card for examinations and
                                                events.</p>
                                            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                                                data-bs-target="#studentIdCardModal">
                                                <i class="bi bi-eye-fill me-1"></i> View Admit Card
                                            </button>
                                            <a href="{{ route('students.card.pdf', $student->student_hash_id ?? '') }}"
                                                class="btn btn-success" target="_blank">
                                                <i class="bi bi-download me-1"></i> Download PDF
                                            </a>
                                        </div>
                                    </div>

                                </div>{{-- /pane-admit --}}

                                {{-- PANE: PERFORMANCE --}}
                                <div class="tab-pane fade" id="pane-performance" role="tabpanel">

                                    {{-- Performance Filter --}}
                                    <div class="card border-0 shadow-sm mb-4"
                                        style="border-radius: 16px; overflow: hidden;">
                                        <div class="card-body p-4 bg-light">
                                            <form method="GET"
                                                action="{{ url('/student-details/' . $student->student_hash_id) }}"
                                                class="row g-3 align-items-end">
                                                <input type="hidden" name="tab" value="performance">

                                                <div class="col-md-3">
                                                    <label for="perf_month" class="form-label fw-semibold small text-muted">
                                                        <i class="bi bi-calendar-month me-1"></i> Month
                                                    </label>
                                                    <select name="perf_month" id="perf_month"
                                                        class="form-select form-select-sm border-0 shadow-sm">
                                                        @foreach(['01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'] as $mNum => $mName)
                                                            <option value="{{ $mNum }}" {{ $perfMonth == $mNum ? 'selected' : '' }}>
                                                                {{ $mName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="perf_year" class="form-label fw-semibold small text-muted">
                                                        <i class="bi bi-calendar-year me-1"></i> Year
                                                    </label>
                                                    <select name="perf_year" id="perf_year"
                                                        class="form-select form-select-sm border-0 shadow-sm">
                                                        @for($y = date('Y') - 3; $y <= date('Y') + 1; $y++)
                                                            <option value="{{ $y }}" {{ $perfYear == $y ? 'selected' : '' }}>
                                                                {{ $y }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="perf_subject"
                                                        class="form-label fw-semibold small text-muted">
                                                        <i class="bi bi-book me-1"></i> Subject
                                                    </label>
                                                    <select name="perf_subject" id="perf_subject"
                                                        class="form-select form-select-sm border-0 shadow-sm">
                                                        <option value="">All Subjects</option>
                                                        @foreach($performanceSubjects as $subjectId => $subjectName)
                                                            <option value="{{ $subjectId }}" {{ (string) $perfSubjectFilter === (string) $subjectId ? 'selected' : '' }}>
                                                                {{ $subjectName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-success btn-sm px-4 shadow-sm"
                                                            style="background: linear-gradient(135deg, #10b981, #059669); border: none;">
                                                            <i class="bi bi-filter me-1"></i> Apply Filters
                                                        </button>
                                                        <a href="{{ url('/student-details/' . $student->student_hash_id . '?tab=performance') }}"
                                                            class="btn btn-outline-secondary btn-sm px-3">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    @php
                                        $perfCoveragePercent = $performanceTotalTopics > 0 ? round(($performanceCoveredTopics / $performanceTotalTopics) * 100) : 0;
                                        $perfMaterialPercent = $performanceTotalMaterials > 0 ? round(($performanceViewedMaterials / $performanceTotalMaterials) * 100) : 0;
                                        $perfAttendancePercent = $performanceTotalAttendance > 0 ? round(($performanceTotalPresent / $performanceTotalAttendance) * 100) : 0;
                                    @endphp

                                    {{-- Performance Summary Cards - Responsive --}}
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3 col-6">
                                            <div class="card border-0 shadow-sm h-100"
                                                style="border-radius: 16px; border-left: 4px solid #10b981 !important; background: #ffffff;">
                                                <div class="card-body text-center py-3">
                                                    <h4 class="fw-bold text-success mb-1" style="opacity: 1;">
                                                        {{ $perfAttendancePercent }}%
                                                    </h4>
                                                    <span class="text-muted small" style="opacity: 1;"><i
                                                            class="bi bi-calendar-check me-1"></i> Attendance</span>
                                                    <div class="mt-1">
                                                        <small class="text-muted"
                                                            style="opacity: 1;">{{ $performanceTotalPresent }}/{{ $performanceTotalAttendance }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="card border-0 shadow-sm h-100"
                                                style="border-radius: 16px; border-left: 4px solid #3b82f6 !important; background: #ffffff;">
                                                <div class="card-body text-center py-3">
                                                    <h4 class="fw-bold text-primary mb-1" style="opacity: 1;">
                                                        {{ $perfCoveragePercent }}%
                                                    </h4>
                                                    <span class="text-muted small" style="opacity: 1;"><i
                                                            class="bi bi-book me-1"></i> Topic
                                                        Coverage</span>
                                                    <div class="mt-1">
                                                        <small class="text-muted"
                                                            style="opacity: 1;">{{ $performanceCoveredTopics }}/{{ $performanceTotalTopics }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="card border-0 shadow-sm h-100"
                                                style="border-radius: 16px; border-left: 4px solid #f59e0b !important; background: #ffffff;">
                                                <div class="card-body text-center py-3">
                                                    <h4 class="fw-bold text-warning mb-1" style="opacity: 1;">
                                                        {{ $perfMaterialPercent }}%
                                                    </h4>
                                                    <span class="text-muted small" style="opacity: 1;"><i
                                                            class="bi bi-file-earmark-text me-1"></i> Materials
                                                        Viewed</span>
                                                    <div class="mt-1">
                                                        <small class="text-muted"
                                                            style="opacity: 1;">{{ $performanceViewedMaterials }}/{{ $performanceTotalMaterials }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="card border-0 shadow-sm h-100"
                                                style="border-radius: 16px; border-left: 4px solid #8b5cf6 !important; background: #ffffff;">
                                                <div class="card-body text-center py-3">
                                                    <h4 class="fw-bold text-purple mb-1" style="opacity: 1;">
                                                        {{ $performanceTotalPlans }}
                                                    </h4>
                                                    <span class="text-muted small" style="opacity: 1;"><i
                                                            class="bi bi-layers me-1"></i>
                                                        Lesson Plans</span>
                                                    <div class="mt-1">
                                                        <small class="text-muted"
                                                            style="opacity: 1;">{{ $performanceSubjects->count() }}
                                                            subjects</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Subject-wise Performance Table --}}
                                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                                        <div class="card-header bg-white border-bottom-0 py-3 px-4">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                <h6 class="fw-bold mb-0"><i
                                                        class="bi bi-graph-up text-primary me-2"></i>Subject-wise
                                                    Performance</h6>
                                                <span class="badge bg-light text-muted fw-normal">
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ date('F Y', mktime(0, 0, 0, $perfMonth, 1, $perfYear)) }}
                                                    @if($perfSubjectFilter)
                                                        <span class="mx-1">•</span> Filtered
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            @if(!empty($performanceData) && count($performanceData) > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-hover mb-0" style="font-size: 13px;">
                                                        <thead class="bg-light">
                                                            <tr>
                                                                <th class="ps-4 py-3" style="min-width: 140px;">Subject</th>
                                                                <th class="text-center py-3" style="min-width: 100px;">
                                                                    Attendance</th>
                                                                <th class="text-center py-3" style="min-width: 120px;">
                                                                    Topics Covered</th>
                                                                <th class="text-center py-3" style="min-width: 120px;">
                                                                    Materials Viewed</th>
                                                                <th class="text-center py-3" style="min-width: 80px;">Plans
                                                                </th>
                                                                <th class="text-center py-3" style="min-width: 90px;">Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($performanceData as $subject)
                                                                @php
                                                                    $subAttendance = $subject['attendance_total'] > 0 ? round(($subject['present'] / $subject['attendance_total']) * 100) : 0;
                                                                    $subCoverage = $subject['topics'] > 0 ? round(($subject['covered_topics'] / $subject['topics']) * 100) : 0;
                                                                    $subMaterials = $subject['materials'] > 0 ? round(($subject['viewed_materials'] / $subject['materials']) * 100) : 0;

                                                                    $attendanceClass = $subAttendance >= 75 ? 'success' : ($subAttendance >= 50 ? 'warning' : 'danger');
                                                                    $coverageClass = $subCoverage >= 75 ? 'success' : ($subCoverage >= 50 ? 'warning' : 'danger');
                                                                    $materialClass = $subMaterials >= 75 ? 'success' : ($subMaterials >= 50 ? 'warning' : 'danger');
                                                                @endphp
                                                                <tr>
                                                                    <td class="ps-4 fw-semibold">{{ $subject['subject'] }}</td>
                                                                    <td class="text-center">
                                                                        <span class="fw-bold text-{{ $attendanceClass }}">
                                                                            {{ $subAttendance }}%
                                                                        </span>
                                                                        <div class="small text-muted">
                                                                            {{ $subject['present'] }}/{{ $subject['attendance_total'] }}
                                                                        </div>
                                                                        <div class="progress mt-1"
                                                                            style="height: 4px; width: 80px; margin: 0 auto;">
                                                                            <div class="progress-bar bg-{{ $attendanceClass }}"
                                                                                style="width: {{ $subAttendance }}%;"
                                                                                role="progressbar"
                                                                                aria-valuenow="{{ $subAttendance }}"
                                                                                aria-valuemin="0" aria-valuemax="100">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span class="fw-bold text-{{ $coverageClass }}">
                                                                            {{ $subCoverage }}%
                                                                        </span>
                                                                        <div class="small text-muted">
                                                                            {{ $subject['covered_topics'] }}/{{ $subject['topics'] }}
                                                                        </div>
                                                                        <div class="progress mt-1"
                                                                            style="height: 4px; width: 80px; margin: 0 auto;">
                                                                            <div class="progress-bar bg-{{ $coverageClass }}"
                                                                                style="width: {{ $subCoverage }}%;"
                                                                                role="progressbar"
                                                                                aria-valuenow="{{ $subCoverage }}" aria-valuemin="0"
                                                                                aria-valuemax="100">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span class="fw-bold text-{{ $materialClass }}">
                                                                            {{ $subMaterials }}%
                                                                        </span>
                                                                        <div class="small text-muted">
                                                                            {{ $subject['viewed_materials'] }}/{{ $subject['materials'] }}
                                                                        </div>
                                                                        <div class="progress mt-1"
                                                                            style="height: 4px; width: 80px; margin: 0 auto;">
                                                                            <div class="progress-bar bg-{{ $materialClass }}"
                                                                                style="width: {{ $subMaterials }}%;"
                                                                                role="progressbar"
                                                                                aria-valuenow="{{ $subMaterials }}"
                                                                                aria-valuemin="0" aria-valuemax="100">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span class="fw-bold">{{ $subject['plans'] }}</span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if(!empty($subject['view_url']))
                                                                            <a href="{{ $subject['view_url'] }}"
                                                                                class="btn btn-sm btn-primary"
                                                                                title="View lesson planner details">
                                                                                <i class="bi bi-eye me-1"></i> View
                                                                            </a>
                                                                        @else
                                                                            <span class="text-muted">N/A</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="bi bi-graph-up text-muted" style="font-size: 48px;"></i>
                                                    <p class="text-muted mt-3">No performance data available for
                                                        {{ date('F Y', mktime(0, 0, 0, $perfMonth, 1, $perfYear)) }}.
                                                    </p>
                                                    <small class="text-muted">Try selecting a different month or
                                                        year.</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Performance Legend --}}
                                    <div class="mt-3 d-flex flex-wrap gap-3 justify-content-center">
                                        <span class="badge bg-success d-flex align-items-center gap-1 px-3 py-2">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> ≥75% Good
                                        </span>
                                        <span class="badge bg-warning d-flex align-items-center gap-1 px-3 py-2">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> 50-74%
                                            Average
                                        </span>
                                        <span class="badge bg-danger d-flex align-items-center gap-1 px-3 py-2">
                                            <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> &lt;50% Needs
                                            Improvement
                                        </span>
                                    </div>

                                </div>{{-- /pane-performance --}}

                            </div>{{-- /tab-content --}}

                        </div>{{-- /card-body --}}

                    </div>{{-- /col-md-8 --}}

                </div>{{-- /row --}}

            </div>{{-- /sp-container --}}
        </div>{{-- /sp-wrapper --}}

        {{-- Student ID Card Modal --}}
        <div class="modal fade" id="studentIdCardModal" tabindex="-1" aria-labelledby="studentIdCardModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content"
                    style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
                    <div class="modal-header text-white"
                        style="background: linear-gradient(135deg, #3730A3 0%, #4F46E5 100%);">
                        <h5 class="modal-title fw-bold" id="studentIdCardModalLabel">
                            <i class="bi bi-person-badge-fill me-2"></i> Student Identification Card
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light text-center">
                        @php
                            $cardImg = !empty($documents->student_photo)
                                ? url('storage/' . ltrim(str_replace(['public/', 'storage/'], '', $documents->student_photo), '/'))
                                : 'https://ui-avatars.com/api/?name=' . urlencode($fullName ?: 'Student') . '&size=150&background=4F46E5&color=fff';

                            $instLogo = !empty($idCardData['institute_logo'])
                                ? url('storage/' . ltrim(str_replace(['public/', 'storage/'], '', $idCardData['institute_logo']), '/'))
                                : null;
                        @endphp
                        <div id="printableIdCard" class="card shadow-lg mx-auto"
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
                                    {{ $idCardSettings['card_title'] ?? 'STUDENT IDENTIFICATION CARD' }}
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-3 text-start" style="font-size: 13px;">
                                <div class="d-flex align-items-center gap-3 mb-3 pb-2 border-bottom">
                                    <img src="{{ $cardImg }}" alt="{{ $fullName }}"
                                        style="width: 90px; height: 90px; border-radius: 10px; object-fit: cover; border: 3px solid #E2E8F0; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1" style="font-size: 17px;">{{ $fullName }}</h5>
                                        <div class="badge bg-primary mb-1" style="font-size: 11px;">ADM:
                                            {{ $student->registration_number ?? 'N/A' }}
                                        </div>
                                        <div class="text-muted small"><strong>Course:</strong>
                                            {{ $extra->course_subtype ?? 'N/A' }}
                                        </div>
                                        <div class="text-muted small"><strong>Dept:</strong>
                                            {{ $extra->department ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <span class="text-muted d-block small">Roll Number</span>
                                        <strong class="text-dark">{{ $extra->roll_no ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block small">Section</span>
                                        <strong class="text-dark">{{ $extra->section_id ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block small">Date of Birth</span>
                                        <strong class="text-dark">{{ $student->dob ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block small">Blood Group</span>
                                        <strong class="text-danger">{{ $student->blood_group ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block small">Father's Name</span>
                                        <strong
                                            class="text-dark">{{ trim(($student->father_first_name ?? '') . ' ' . ($student->father_last_name ?? '')) ?: 'N/A' }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block small">Mobile</span>
                                        <strong
                                            class="text-dark">{{ $student->mobile ?? $student->father_phone ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="col-12">
                                        <span class="text-muted d-block small">Address</span>
                                        <span
                                            class="text-dark small">{{ trim(($address->student_perm_address_line1 ?? '') . ' ' . ($address->student_perm_city ?? '') . ' ' . ($address->student_perm_state ?? '')) ?: 'N/A' }}</span>
                                    </div>
                                </div>

                                {{-- Footer with Barcode & QR Code --}}
                                <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-between">
                                    <div>
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $student->registration_number ?? 'N/A' }}"
                                            alt="QR Code" style="height: 50px;">
                                    </div>
                                    <div class="text-center">
                                        <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $student->registration_number ?? 'N/A' }}&code=Code128&dpi=96&imagetype=Png"
                                            alt="Barcode" style="height: 32px; width: auto; max-width: 140px;">
                                        <div style="font-size: 9px; color: #64748B;">
                                            {{ $student->registration_number ?? '' }}
                                        </div>
                                    </div>
                                    <div class="text-end" style="font-size: 10px; color: #64748B;">
                                        <div style="height: 25px;"></div>
                                        <div class="border-top pt-1 fw-bold">
                                            {{ $idCardSettings['signature_text'] ?? "Principal's Signature" }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Bottom Bar --}}
                            <div style="background: {{ $idCardSettings['footer_bg_color'] ?? '#3730A3' }}; color: {{ $idCardSettings['footer_font_color'] ?? '#ffffff' }}; padding: 6px 12px; font-size: 10px;"
                                class="d-flex justify-content-between">
                                <span>Academic Year: {{ $extra->academic_year ?? date('Y') }}</span>
                                <span>Official Student ID</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('students.card.pdf', $student->student_hash_id ?? '') }}" class="btn btn-success"
                            target="_blank">
                            <i class="bi bi-download me-1"></i> Download PDF
                        </a>
                        <button type="button" class="btn btn-primary" onclick="printCardModal()">
                            <i class="bi bi-printer-fill me-1"></i> Print Card
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Report Card Modal --}}
        <div class="modal fade" id="reportCardModal" tabindex="-1" aria-labelledby="reportCardModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content"
                    style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
                    <div class="modal-header text-white"
                        style="background: linear-gradient(135deg, #1E293B 0%, #334155 100%);">
                        <h5 class="modal-title fw-bold" id="reportCardModalLabel">
                            <i class="bi bi-award-fill me-2 text-warning"></i> Academic Progress Report Card
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 bg-light text-center">
                        <div id="printableReportCard" class="card shadow-sm p-4 text-start mx-auto"
                            style="max-width: 680px; background: #ffffff; border-radius: 12px; border: 1px solid #CBD5E1;">
                            <div class="text-center pb-3 mb-3 border-bottom">
                                <h4 class="fw-bold text-uppercase mb-1" style="color:#1E293B;">
                                    {{ $idCardData['institute_name'] ?? 'Institute Name' }}
                                </h4>
                                <p class="text-muted small mb-0">
                                    {{ $idCardData['institute_address'] ?? 'Official Academic Progress Report' }}
                                </p>
                                <span class="badge bg-primary px-3 py-1 mt-2">ACADEMIC REPORT CARD —
                                    {{ date('Y') }}</span>
                            </div>

                            <div class="row g-2 mb-3 small">
                                <div class="col-6"><strong>Student Name:</strong> {{ $fullName }}</div>
                                <div class="col-6"><strong>Admission No:</strong>
                                    {{ $student->registration_number ?? 'N/A' }}
                                </div>
                                <div class="col-6"><strong>Course / Dept:</strong> {{ $extra->course_subtype ?? 'N/A' }}
                                    ({{ $extra->department ?? 'N/A' }})</div>
                                <div class="col-6"><strong>Roll No / Section:</strong> {{ $extra->roll_no ?? 'N/A' }} /
                                    {{ $extra->section_id ?? 'N/A' }}
                                </div>
                            </div>

                            <table class="table table-bordered table-sm mb-3" style="font-size: 13px;">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Subject</th>
                                        <th class="text-center">Total Marks</th>
                                        <th class="text-center">Pass Marks</th>
                                        <th class="text-center">Obtained Marks</th>
                                        <th class="text-center">Grade</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($examMarks) && $examMarks->count() > 0)
                                        @foreach($examMarks as $m)
                                            @php
                                                $tM = floatval($m->total_marks ?? 100);
                                                $oM = floatval($m->obtained_marks ?? 0);
                                                $pM = floatval($m->passing_marks ?? 33);
                                                $pSt = $oM >= $pM;
                                            @endphp
                                            <tr>
                                                <td>{{ $m->subject->subject_name ?? 'Subject' }}</td>
                                                <td class="text-center">{{ $tM }}</td>
                                                <td class="text-center">{{ $pM }}</td>
                                                <td class="text-center fw-bold">{{ $oM }}</td>
                                                <td class="text-center">{{ $m->grade ?? 'A' }}</td>
                                                <td class="text-center"><span
                                                        class="badge bg-{{ $pSt ? 'success' : 'danger' }}">{{ $pSt ? 'Pass' : 'Fail' }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>Mathematics</td>
                                            <td class="text-center">100</td>
                                            <td class="text-center">33</td>
                                            <td class="text-center fw-bold">88</td>
                                            <td class="text-center">A</td>
                                            <td class="text-center"><span class="badge bg-success">Pass</span></td>
                                        </tr>
                                        <tr>
                                            <td>Physics</td>
                                            <td class="text-center">100</td>
                                            <td class="text-center">33</td>
                                            <td class="text-center fw-bold">82</td>
                                            <td class="text-center">A</td>
                                            <td class="text-center"><span class="badge bg-success">Pass</span></td>
                                        </tr>
                                        <tr>
                                            <td>Computer Science</td>
                                            <td class="text-center">100</td>
                                            <td class="text-center">33</td>
                                            <td class="text-center fw-bold">94</td>
                                            <td class="text-center">A+</td>
                                            <td class="text-center"><span class="badge bg-success">Pass</span></td>
                                        </tr>
                                        <tr>
                                            <td>English Literature</td>
                                            <td class="text-center">100</td>
                                            <td class="text-center">33</td>
                                            <td class="text-center fw-bold">78</td>
                                            <td class="text-center">B+</td>
                                            <td class="text-center"><span class="badge bg-success">Pass</span></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-between align-items-end pt-3 mt-2 border-top">
                                <div>
                                    <span class="badge bg-success p-2">Overall Result: PASSED</span>
                                </div>
                                <div class="text-end" style="font-size: 11px; color:#64748B;">
                                    <div style="height: 30px;"></div>
                                    <div class="border-top pt-1 fw-bold">Principal Signature &amp; Stamp</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-white border-top">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="printReportCard()">
                            <i class="bi bi-printer-fill me-1"></i> Print / Save Report Card
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- JS Scripts --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script>
            var attendanceDataMap = @json($attendanceByDate ?? []);

            $(document).ready(function () {
                // Tab hash persistence
                var hash = window.location.hash;
                if (hash) {
                    var target = $('#studentTabs a[href="' + hash + '"]');
                    if (target.length) {
                        target.tab('show');
                    }
                }
                $('#studentTabs a').on('shown.bs.tab', function (e) {
                    history.replaceState(null, null, e.target.hash);
                });

                // Fee group chevron toggle
                $('.sp-fee-group-header').on('click', function () {
                    var isExpanded = $(this).attr('aria-expanded') === 'true';
                    $(this).attr('aria-expanded', !isExpanded);
                });

                // Collect fees action
                $('.btn-collect-fees').on('click', function (e) {
                    e.preventDefault();
                    alert('Redirecting to fee collection page for admission: {{ $student->registration_number ?? 'N/A' }}');
                });

                // Initial attendance render
                renderAttendanceCalendar();
            });

            function onAttendanceDateChange() {
                renderAttendanceCalendar();
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

            function renderAttendanceCalendar() {
                var monthVal = $('#attMonthSelect').val();
                var yearVal = $('#attYearSelect').val();
                var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                var monthName = monthNames[parseInt(monthVal) - 1];

                $('#attMonthYearTitle').html('<i class="bi bi-calendar3 me-1" style="color:var(--sp-primary);"></i> ' + monthName + ' ' + yearVal);

                var daysInMonth = new Date(yearVal, parseInt(monthVal), 0).getDate();
                var startDayOfWeek = new Date(yearVal, parseInt(monthVal) - 1, 1).getDay();

                var presentCnt = 0, absentCnt = 0, lateCnt = 0, halfCnt = 0, totalMarked = 0;

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
                    var statusText = '';

                    if (st === 'present') {
                        cls = 'cal-present'; statusText = 'Present'; presentCnt++; totalMarked++;
                    } else if (st === 'absent') {
                        cls = 'cal-absent'; statusText = 'Absent'; absentCnt++; totalMarked++;
                    } else if (st === 'late') {
                        cls = 'cal-late'; statusText = 'Late'; lateCnt++; totalMarked++;
                    } else if (st === 'half_day') {
                        cls = 'cal-half'; statusText = 'Half'; halfCnt++; totalMarked++;
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

                $('#attPresentCnt').text(presentCnt);
                $('#attAbsentCnt').text(absentCnt);
                $('#attLateCnt').text(lateCnt);
                $('#attHalfCnt').text(halfCnt);

                var pct = totalMarked > 0 ? Math.round((presentCnt / totalMarked) * 100) : 0;
                var deg = Math.round((pct / 100) * 360);

                $('#attPctText').text(pct + '%');
                $('#attDonut').css('background', 'conic-gradient(#10B981 ' + deg + 'deg, #FEE2E2 ' + deg + 'deg)');
            }

            function printCardModal() {
                var printContents = document.getElementById('printableIdCard').outerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = '<div style="display:flex;justify-content:center;align-items:center;height:100vh;">' + printContents + '</div>';
                window.print();
                document.body.innerHTML = originalContents;
                window.location.reload();
            }

            function printReportCard() {
                var printContents = document.getElementById('printableReportCard').outerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = '<div style="display:flex;justify-content:center;align-items:center;height:100vh;padding:20px;">' + printContents + '</div>';
                window.print();
                document.body.innerHTML = originalContents;
                window.location.reload();
            }


            $(document).ready(function () {
                // Get tab from URL parameter
                const urlParams = new URLSearchParams(window.location.search);
                const tabParam = urlParams.get('tab');

                if (tabParam) {
                    // Show the corresponding tab
                    const tabLink = $('#studentTabs a[href="#pane-' + tabParam + '"]');
                    if (tabLink.length) {
                        tabLink.tab('show');
                    }
                }

                // Update URL when tab is changed
                $('#studentTabs a').on('shown.bs.tab', function (e) {
                    const tabId = e.target.hash.replace('#pane-', '');
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tabId);
                    history.replaceState(null, null, url);
                });
            });

        </script>

@endsection