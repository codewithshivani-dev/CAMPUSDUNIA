@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

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

        * {
            box-sizing: border-box;
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

        /* Row & Column Fix */
        .sp-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
            gap: 0;
        }

        .sp-col-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            padding: 0 12px;
        }

        .sp-col-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
            padding: 0 12px;
        }

        @media (max-width: 992px) {
            .sp-col-4 {
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 24px;
            }

            .sp-col-8 {
                flex: 0 0 100%;
                max-width: 100%;
            }
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

        /* Donut */
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

        .leg-complete::before {
            background: var(--sp-success);
        }

        .leg-incomplete::before {
            background: #FEE2E2;
        }

        /* Info list - FIXED alignment */
        .sp-info-list {
            list-style: none;
            padding: 0;
            margin: 16px 0 18px;
        }

        .sp-info-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--sp-gray-100);
            font-size: 14px;
            flex-wrap: wrap;
            gap: 8px;
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
            flex-shrink: 0;
        }

        .il-label i {
            color: var(--sp-primary);
            width: 18px;
            font-size: 15px;
            flex-shrink: 0;
        }

        .il-value {
            font-weight: 600;
            color: var(--sp-gray-800);
            text-align: right;
            word-break: break-word;
            max-width: 60%;
        }

        .il-value .badge {
            font-weight: 600;
            font-size: 12px;
        }

        /* Action buttons */
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

        /* ID Card Button */
        .btn-idcard {
            background: var(--sp-gradient);
            color: #fff !important;
            border: none;
            border-radius: var(--sp-radius-sm);
            font-weight: 600;
            font-size: 14px;
            padding: 12px 14px;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-idcard:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
            color: #fff;
            text-decoration: none;
        }

        .sp-cred-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 6px;
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
            display: flex;
            flex-wrap: wrap;
            padding: 16px 20px 8px;
            margin: 0 -10px;
        }

        .profile-section .row>[class*="col-"] {
            padding: 0 10px;
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

        /* ----- Form Styles ----- */
        .form-group-modern {
            margin-bottom: 1.25rem;
        }

        .form-group-modern label {
            font-weight: 600;
            color: var(--sp-gray-700);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.875rem;
        }

        .form-group-modern label .required {
            color: var(--sp-danger);
            font-size: 1rem;
        }

        .form-control-modern {
            border: 2px solid var(--sp-gray-200);
            border-radius: 10px;
            padding: 10px 14px;
            transition: all 0.3s;
            background: #fff;
            font-size: 0.9rem;
            width: 100%;
            color: var(--sp-gray-800);
            display: block;
        }

        .form-control-modern:focus {
            border-color: var(--sp-primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .form-control-modern:hover {
            border-color: var(--sp-primary-light);
        }

        .form-control-modern:disabled,
        .form-control-modern[readonly] {
            background: var(--sp-gray-50);
            color: var(--sp-gray-600);
            cursor: not-allowed;
        }

        .form-control-modern.is-invalid {
            border-color: var(--sp-danger);
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .form-control-modern[type="file"] {
            padding: 8px 12px;
            cursor: pointer;
        }

        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 5px;
            color: var(--sp-danger);
        }

        /* Radio Buttons */
        .radio-group-modern {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 4px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-item input[type="radio"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--sp-primary);
        }

        .radio-item label {
            margin: 0;
            cursor: pointer;
            font-weight: 500;
            color: var(--sp-gray-700);
        }

        /* Action Buttons */
        .action-buttons-modern {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding: 20px 0 0;
            border-top: 1px solid var(--sp-gray-200);
            margin-top: 10px;
        }

        .btn-save-modern {
            background: var(--sp-gradient);
            border: none;
            padding: 12px 36px;
            font-weight: 600;
            transition: all 0.3s;
            color: #fff !important;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            cursor: pointer;
            font-size: 15px;
        }

        .btn-save-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
            color: #fff;
        }

        .btn-save-modern:active {
            transform: translateY(0);
        }

        .btn-reset-modern {
            background: var(--sp-gray-100);
            border: 1px solid var(--sp-gray-200);
            padding: 12px 28px;
            font-weight: 600;
            color: var(--sp-gray-700);
            border-radius: 12px;
            transition: all 0.3s;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-reset-modern:hover {
            background: var(--sp-gray-200);
            color: var(--sp-gray-900);
        }

        /* Alert Styles */
        .alert-custom {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.4s ease;
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

        .alert-success-custom {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border-left: 4px solid var(--sp-success);
            color: #065f46;
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border-left: 4px solid var(--sp-danger);
            color: #991b1b;
        }

        .alert-custom .btn-close {
            margin-left: auto;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0 8px;
            opacity: 0.7;
        }

        .alert-custom .btn-close:hover {
            opacity: 1;
        }

        /* Document items */
        .doc-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: var(--sp-gray-50);
            border-radius: 8px;
            border: 1px solid var(--sp-gray-200);
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .doc-item:hover {
            border-color: var(--sp-primary-light);
            background: #fff;
        }

        .doc-item .doc-name {
            font-weight: 500;
            color: var(--sp-gray-700);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .doc-item .doc-name i {
            color: var(--sp-primary);
            font-size: 16px;
        }

        .doc-item .doc-status {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge-modern {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-complete-modern {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .status-incomplete-modern {
            background: linear-gradient(135deg, #fed7aa, #ffedd5);
            color: #92400e;
        }

        .btn-outline-primary-modern {
            background: transparent;
            border: 1px solid var(--sp-primary);
            color: var(--sp-primary);
            padding: 4px 12px;
            border-radius: 6px;
            transition: all 0.3s;
            font-size: 0.75rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
        }

        .btn-outline-primary-modern:hover {
            background: var(--sp-gradient);
            border-color: transparent;
            color: white;
            transform: translateY(-1px);
            text-decoration: none;
        }

        /* Progress */
        .progress-container {
            position: relative;
            height: 10px;
            background: var(--sp-gray-200);
            border-radius: 10px;
            overflow: hidden;
            margin: 15px 0;
        }

        .progress-bar {
            height: 100%;
            background: var(--sp-gradient);
            border-radius: 10px;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow: 0 0 10px rgba(79, 70, 229, 0.3);
        }

        .percentage-display {
            font-size: 2rem;
            font-weight: 800;
            background: var(--sp-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .progress-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
        }

        .progress-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            padding: 8px 16px;
            border-radius: 50px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--sp-gray-200);
            flex: 1;
            min-width: 120px;
            transition: all 0.3s;
        }

        .progress-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.12);
            border-color: var(--sp-primary-light);
        }

        .progress-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .icon-complete {
            background: var(--sp-success);
            color: #fff;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        .icon-incomplete {
            background: var(--sp-gray-400);
            color: #fff;
        }

        .progress-label {
            font-weight: 600;
            color: var(--sp-gray-700);
            font-size: 13px;
        }

        .progress-label small {
            font-weight: 400;
            color: var(--sp-gray-500);
            font-size: 11px;
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

        .employee-id-badge {
            background: var(--sp-gradient);
            color: #fff;
            padding: 6px 18px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
        }

        .last-updated {
            background: var(--sp-gray-100);
            padding: 6px 16px;
            border-radius: 12px;
            font-size: 13px;
            color: var(--sp-gray-600);
        }

        /* Table styles */
        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 14px;
        }

        .table-modern thead th {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 10px 14px;
            font-weight: 700;
            color: var(--sp-gray-700);
            border-bottom: 2px solid var(--sp-gray-200);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            text-align: left;
        }

        .table-modern tbody td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--sp-gray-100);
            vertical-align: middle;
            color: var(--sp-gray-700);
        }

        .table-modern tbody tr:hover {
            background: #f8faff;
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        /* Document viewer modal */
        .modal-content-custom {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .modal-header-custom {
            background: var(--sp-gradient);
            color: #fff;
            padding: 16px 24px;
            border-bottom: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header-custom h5 {
            font-weight: 700;
            margin: 0;
            color: #fff;
        }

        .modal-header-custom .btn-close {
            filter: brightness(0) invert(1);
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0 8px;
            opacity: 0.8;
        }

        .modal-header-custom .btn-close:hover {
            opacity: 1;
        }

        /* Employee ID Card Modal Styles */
        .id-card-modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .id-card-modal-header {
            background: linear-gradient(135deg, #3730A3 0%, #4F46E5 100%);
            color: #fff;
            padding: 16px 24px;
            border-bottom: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .id-card-modal-header h5 {
            font-weight: 700;
            margin: 0;
            color: #fff;
        }

        .id-card-modal-header .btn-close {
            filter: brightness(0) invert(1);
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0 8px;
            opacity: 0.8;
        }

        .id-card-modal-header .btn-close:hover {
            opacity: 1;
        }

        /* ID Card Styles */
        .employee-id-card {
            max-width: 420px;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #CBD5E1;
            background: #ffffff;
            margin: 0 auto;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .id-card-header {
            padding: 16px 14px;
            text-align: center;
        }

        .id-card-header .id-logo {
            max-height: 48px;
            object-fit: contain;
            margin-bottom: 6px;
            background: #fff;
            padding: 2px;
            border-radius: 4px;
        }

        .id-card-header .id-institute-name {
            font-weight: 700;
            margin-bottom: 0;
            text-transform: uppercase;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        .id-card-header .id-institute-address {
            font-size: 11px;
            opacity: 0.75;
            margin-bottom: 0;
        }

        .id-card-header .id-title-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-top: 6px;
        }

        .id-card-body {
            padding: 16px 20px;
        }

        .id-card-body .id-profile-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #E2E8F0;
        }

        .id-card-body .id-profile-img {
            width: 90px;
            height: 90px;
            border-radius: 10px;
            object-fit: cover;
            border: 3px solid #E2E8F0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .id-card-body .id-profile-info .id-name {
            font-size: 17px;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 2px;
        }

        .id-card-body .id-profile-info .id-employee-badge {
            font-size: 11px;
            background: var(--sp-primary);
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 4px;
        }

        .id-card-body .id-profile-info .id-designation {
            font-size: 13px;
            color: #64748B;
        }

        .id-card-body .id-profile-info .id-department {
            font-size: 13px;
            color: #64748B;
        }

        .id-card-body .id-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 16px;
        }

        .id-card-body .id-detail-item .id-detail-label {
            display: block;
            font-size: 11px;
            color: #94A3B8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .id-card-body .id-detail-item .id-detail-value {
            font-weight: 600;
            color: #1E293B;
            font-size: 13px;
        }

        .id-card-body .id-detail-item .id-detail-value.text-danger {
            color: #EF4444;
        }

        .id-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-top: 1px solid #E2E8F0;
            margin: 0 16px 16px 16px;
            padding-top: 12px;
        }

        .id-card-footer .id-qr-code {
            height: 50px;
            width: auto;
        }

        .id-card-footer .id-barcode-wrap {
            text-align: center;
        }

        .id-card-footer .id-barcode-wrap .id-barcode-img {
            height: 32px;
            width: auto;
            max-width: 140px;
        }

        .id-card-footer .id-barcode-wrap .id-barcode-text {
            font-size: 9px;
            color: #64748B;
        }

        .id-card-footer .id-signature {
            text-align: right;
            font-size: 10px;
            color: #64748B;
        }

        .id-card-footer .id-signature .id-signature-line {
            border-top: 1px solid #CBD5E1;
            padding-top: 4px;
            font-weight: 700;
        }

        .id-card-bottom-bar {
            padding: 6px 14px;
            font-size: 10px;
            display: flex;
            justify-content: space-between;
            background: var(--sp-primary-dark);
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sp-page-header-card {
                flex-direction: column;
                text-align: center;
            }

            .progress-stats {
                flex-direction: column;
            }

            .progress-item {
                min-width: auto;
            }

            .sp-tabs-card .nav-tabs .nav-link {
                padding: 10px 14px;
                font-size: 12px;
            }

            .sp-tabs-card .card-body {
                padding: 16px;
            }

            .info-label {
                width: 100px;
                min-width: 100px;
                font-size: 12px;
            }

            .action-buttons-modern {
                flex-direction: column;
            }

            .btn-save-modern,
            .btn-reset-modern {
                width: 100%;
                justify-content: center;
            }

            .radio-group-modern {
                flex-direction: column;
                gap: 8px;
            }

            .sp-col-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .sp-col-8 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .il-value {
                max-width: 100%;
                text-align: left;
            }

            .id-card-body .id-profile-row {
                flex-direction: column;
                text-align: center;
            }

            .id-card-body .id-details-grid {
                grid-template-columns: 1fr;
            }

            .id-card-footer {
                flex-direction: column;
                gap: 12px;
                align-items: center;
            }

            .id-card-footer .id-signature {
                text-align: center;
            }
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

        .fade-in-up {
            animation: fadeInUp 0.5s ease;
        }

        /* Tab panes - ensure they display correctly */
        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .tab-pane.fade {
            transition: opacity 0.15s linear;
        }

        .tab-pane.fade.show {
            opacity: 1;
        }

        .tab-pane.fade:not(.show) {
            opacity: 0;
        }

        /* Fix for col-md-6 inside row */
        .row .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 10px;
        }

        .row .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
            padding: 0 10px;
        }

        .row .col-md-3 {
            flex: 0 0 25%;
            max-width: 25%;
            padding: 0 10px;
        }

        .row .col-md-12 {
            flex: 0 0 100%;
            max-width: 100%;
            padding: 0 10px;
        }

        @media (max-width: 768px) {

            .row .col-md-6,
            .row .col-md-4,
            .row .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Utility classes */
        .mt-4 {
            margin-top: 1.5rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .me-1 {
            margin-right: 0.25rem;
        }

        .me-2 {
            margin-right: 0.5rem;
        }

        .ms-1 {
            margin-left: 0.25rem;
        }

        .ms-2 {
            margin-left: 0.5rem;
        }

        .ms-auto {
            margin-left: auto;
        }

        .w-100 {
            width: 100%;
        }

        .d-flex {
            display: flex;
        }

        .d-block {
            display: block;
        }

        .d-none {
            display: none;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .justify-content-end {
            justify-content: flex-end;
        }

        .justify-content-center {
            justify-content: center;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-muted {
            color: var(--sp-gray-500);
        }

        .text-danger {
            color: var(--sp-danger);
        }

        .text-primary {
            color: var(--sp-primary);
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        .gap-4 {
            gap: 1.5rem;
        }

        .fw-bold {
            font-weight: 700;
        }

        .fw-semibold {
            font-weight: 600;
        }

        .fw-normal {
            font-weight: 400;
        }

        .fs-5 {
            font-size: 1.25rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .pt-2 {
            padding-top: 0.5rem;
        }

        .pt-3 {
            padding-top: 1rem;
        }

        .pb-3 {
            padding-bottom: 1rem;
        }

        .px-3 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .py-3 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
    </style>

    <div class="sp-wrapper fade-in-up">
        <div class="sp-container">

            {{-- ---------- PAGE HEADER CARD ---------- --}}
            <div class="sp-page-header-card">
                <div class="sp-header-title">
                    <h2>
                        <i class="bi bi-person-bounding-box"></i> My Profile
                    </h2>
                    <p>View and manage your personal and professional information.</p>
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="employee-id-badge">
                        <i class="bi bi-upc-scan me-1"></i>Employee ID: {{ $employee->employee_code ?? 'N/A' }}
                    </span>
                    <span class="last-updated">
                        <i class="bi bi-clock-history me-1"></i>
                        <strong>Last Updated:</strong>
                        {{ isset($employee->updated_at) ? $employee->updated_at->format('M d, Y h:i A') : 'N/A' }}
                    </span>
                </div>
            </div>

            <div class="sp-row">

                {{-- ---------- LEFT COLUMN ---------- --}}
                <div class="sp-col-4">
                    <div class="sp-left-card">
                        <div class="card-body">

                            {{-- Hero --}}
                            <div class="sp-hero-wrap">
                                @php
                                    $fullName = $employee->name ?? 'Employee';
                                    // Try multiple possible photo fields
                                    $photoPath = $employee->profile_photo ?? $employee->passport_photo ?? $employee->photo ?? null;

                                    if (!empty($photoPath)) {
                                        // Clean the path - handle both public/ and storage/ prefixes
                                        $cleanPath = ltrim(str_replace(['image/'], '', $photoPath), '/');
                                        $avatarUrl = url('image/' . $cleanPath);
                                    } else {
                                        // Use avatar API as fallback
                                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&size=120&background=4F46E5&color=fff&font-size=0.5&bold=true';
                                    }
                                @endphp
                                <img class="sp-avatar" src="{{ $avatarUrl }}" alt="{{ $fullName }}"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($fullName) }}&size=120&background=4F46E5&color=fff&font-size=0.5&bold=true'">
                                <!-- <div class="sp-status-dot"></div> -->

                                <div class="sp-name">{{ $fullName }}</div>
                                <span class="sp-class-badge">{{ $employee->designation ?? 'Staff Member' }}</span>
                            </div>

                            {{-- Donut (Profile Completion) --}}
                            <div class="sp-donut-wrap">
                                <div class="sp-donut"
                                    style="background: conic-gradient(#10B981 {{ $completionPercentage * 3.6 }}deg, #FEE2E2 {{ $completionPercentage * 3.6 }}deg);">
                                    <div class="donut-center">
                                        <span class="donut-pct">{{ $completionPercentage }}%</span>
                                        <span class="donut-lbl">Complete</span>
                                    </div>
                                </div>
                                <div class="sp-donut-title">Profile Completion</div>
                                <div class="sp-donut-legend">
                                    <span class="leg-complete">Complete</span>
                                    <span class="leg-incomplete">Incomplete</span>
                                </div>
                            </div>

                            {{-- Info list --}}
                            <ul class="sp-info-list">
                                <li>
                                    <span class="il-label"><i class="bi bi-envelope-fill"></i> Email</span>
                                    <span class="il-value">{{ $employee->email ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-phone-fill"></i> Mobile</span>
                                    <span class="il-value">{{ $employee->mobile_number ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-briefcase-fill"></i> Department</span>
                                    <span class="il-value">{{ $employeedepartment ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-person-vcard"></i> Designation</span>
                                    <span class="il-value">{{ $employee->designation ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-clock-fill"></i> Employment</span>
                                    <span class="il-value">{{ $employee->employment_type ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-calendar-plus-fill"></i> DOJ</span>
                                    <span
                                        class="il-value">{{ isset($employee->doj) ? date('d/m/Y', strtotime($employee->doj)) : 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-gender-ambiguous"></i> Gender</span>
                                    <span class="il-value">{{ ucfirst($employee->gender ?? 'N/A') }}</span>
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
                                <a href="#" class="sp-btn sp-btn-primary d-none" onclick="window.print(); return false;">
                                    <i class="bi bi-printer-fill"></i> Print Profile
                                </a>
                            </div>

                            {{-- Employee ID Card Action --}}
                            <div class="sp-cred-actions">
                                <button type="button" class="btn-idcard" data-bs-toggle="modal"
                                    data-bs-target="#employeeIdCardModal">
                                    <i class="bi bi-person-badge-fill me-1"></i> <b>View Employee ID Card</b>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ---------- RIGHT COLUMN ---------- --}}
                <div class="sp-col-8">

                    <div class="sp-tabs-card">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-basic" data-bs-toggle="pill" href="#pane-basic"
                                        role="tab">
                                        <i class="bi bi-person-fill"></i> Basic Details
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-professional" data-bs-toggle="pill"
                                        href="#pane-professional" role="tab">
                                        <i class="bi bi-briefcase-fill"></i> Professional
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-contact" data-bs-toggle="pill" href="#pane-contact"
                                        role="tab">
                                        <i class="bi bi-telephone-fill"></i> Contact
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-documents" data-bs-toggle="pill" href="#pane-documents"
                                        role="tab">
                                        <i class="bi bi-folder-fill"></i> Documents
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-bank" data-bs-toggle="pill" href="#pane-bank" role="tab">
                                        <i class="bi bi-bank2"></i> Bank
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">

                            {{-- ✅ Alerts --}}
                            @if(session('success'))
                                <div class="alert-custom alert-success-custom">
                                    <i class="bi bi-check-circle-fill fs-5"></i>
                                    <div>{{ session('success') }}</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close">&times;</button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert-custom alert-danger-custom">
                                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                    <div>{{ session('error') }}</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close">&times;</button>
                                </div>
                            @endif

                            @if(!$employee)
                                <div class="alert-custom alert-danger-custom">
                                    <i class="bi bi-emoji-frown-fill fs-5"></i>
                                    <div>Employee profile not found.</div>
                                </div>
                            @else

                                {{-- Profile Completion Progress --}}
                                <div class="profile-section mb-4">
                                    <div class="profile-section-title">
                                        <div class="profile-section-title-left">
                                            <i class="bi bi-graph-up-arrow"></i> Profile Completion Status
                                        </div>
                                        <span class="percentage-display"
                                            style="font-size:1.2rem; -webkit-text-fill-color: var(--sp-primary);">{{ $completionPercentage }}%</span>
                                    </div>
                                    <div style="padding:16px 20px;">
                                        <div class="progress-container">
                                            <div class="progress-bar" style="width: {{ $completionPercentage }}%"></div>
                                        </div>
                                        <div class="progress-stats">
                                            @foreach($sectionStatus as $section)
                                                <div class="progress-item">
                                                    <div
                                                        class="progress-icon {{ $section['complete'] ? 'icon-complete' : 'icon-incomplete' }}">
                                                        {{ $section['complete'] ? '✓' : '!' }}
                                                    </div>
                                                    <div>
                                                        <div class="progress-label">{{ $section['label'] }}</div>
                                                        <div class="progress-label"
                                                            style="font-weight:400; font-size:11px; color:var(--sp-gray-500);">
                                                            {{ $section['completed'] }}/{{ $section['total'] }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- ✅ Profile Update Form --}}
                                <form action="{{ route('employee.updateProfile') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="tab-content" id="employeeTabsContent">

                                        {{-- PANE: BASIC DETAILS --}}
                                        <div class="tab-pane fade show active" id="pane-basic" role="tabpanel">
                                            <div class="profile-section">
                                                <div class="profile-section-title">
                                                    <div class="profile-section-title-left">
                                                        <i class="bi bi-person-circle"></i> Personal Information
                                                    </div>
                                                    <span class="badge bg-primary">Required Fields Marked with *</span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Employee Code <span class="required">*</span></label>
                                                            <input type="text" class="form-control-modern"
                                                                value="{{ $employee->employee_code ?? 'N/A' }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Full Name <span class="required">*</span></label>
                                                            <input type="text" name="name"
                                                                value="{{ old('name', $employee->name) }}"
                                                                class="form-control-modern" required
                                                                placeholder="Enter full name">
                                                            @if($errors->has('name'))
                                                                <div class="invalid-feedback d-block">{{ $errors->first('name') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Email Address <span class="required">*</span></label>
                                                            <input type="email" name="email"
                                                                value="{{ old('email', $employee->email) }}"
                                                                class="form-control-modern" required
                                                                placeholder="example@company.com">
                                                            @if($errors->has('email'))
                                                                <div class="invalid-feedback d-block">{{ $errors->first('email') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Mobile Number <span class="required">*</span></label>
                                                            <input type="tel" name="mobile_number"
                                                                value="{{ old('mobile_number', $employee->mobile_number) }}"
                                                                class="form-control-modern" required
                                                                placeholder="10-digit number">
                                                            @if($errors->has('mobile_number'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('mobile_number') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Gender <span class="required">*</span></label>
                                                            <div class="radio-group-modern">
                                                                <div class="radio-item">
                                                                    <input type="radio" name="gender" value="male"
                                                                        id="gender_male" {{ strtolower(old('gender', $employee->gender)) == 'male' ? 'checked' : '' }}
                                                                        required>
                                                                    <label for="gender_male">Male</label>
                                                                </div>
                                                                <div class="radio-item">
                                                                    <input type="radio" name="gender" value="female"
                                                                        id="gender_female" {{ strtolower(old('gender', $employee->gender)) == 'female' ? 'checked' : '' }}>
                                                                    <label for="gender_female">Female</label>
                                                                </div>
                                                                <div class="radio-item">
                                                                    <input type="radio" name="gender" value="other"
                                                                        id="gender_other" {{ strtolower(old('gender', $employee->gender)) == 'other' ? 'checked' : '' }}>
                                                                    <label for="gender_other">Other</label>
                                                                </div>
                                                            </div>
                                                            @if($errors->has('gender'))
                                                                <div class="invalid-feedback d-block">{{ $errors->first('gender') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Date of Birth <span class="required">*</span></label>
                                                            <input type="date" name="dob"
                                                                value="{{ old('dob', $employee->dob) }}"
                                                                class="form-control-modern" required max="{{ date('Y-m-d') }}">
                                                            @if($errors->has('dob'))
                                                                <div class="invalid-feedback d-block">{{ $errors->first('dob') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Blood Group <span class="required">*</span></label>
                                                            <select name="blood_group" class="form-control-modern" required>
                                                                <option value="">Select Blood Group</option>
                                                                <option value="A+" {{ old('blood_group', $employee->blood_group) == 'A+' ? 'selected' : '' }}>A+
                                                                </option>
                                                                <option value="A-" {{ old('blood_group', $employee->blood_group) == 'A-' ? 'selected' : '' }}>A-
                                                                </option>
                                                                <option value="B+" {{ old('blood_group', $employee->blood_group) == 'B+' ? 'selected' : '' }}>B+
                                                                </option>
                                                                <option value="B-" {{ old('blood_group', $employee->blood_group) == 'B-' ? 'selected' : '' }}>B-
                                                                </option>
                                                                <option value="O+" {{ old('blood_group', $employee->blood_group) == 'O+' ? 'selected' : '' }}>O+
                                                                </option>
                                                                <option value="O-" {{ old('blood_group', $employee->blood_group) == 'O-' ? 'selected' : '' }}>O-
                                                                </option>
                                                                <option value="AB+" {{ old('blood_group', $employee->blood_group) == 'AB+' ? 'selected' : '' }}>AB+
                                                                </option>
                                                                <option value="AB-" {{ old('blood_group', $employee->blood_group) == 'AB-' ? 'selected' : '' }}>AB-
                                                                </option>
                                                            </select>
                                                            @if($errors->has('blood_group'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('blood_group') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Nationality <span class="required">*</span></label>
                                                            <select name="nationality" class="form-control-modern" required>
                                                                <option value="">Select</option>
                                                                <option value="Indian" {{ old('nationality', $employee->nationality) == 'Indian' ? 'selected' : '' }}>
                                                                    Indian</option>
                                                                <option value="American" {{ old('nationality', $employee->nationality) == 'American' ? 'selected' : '' }}>
                                                                    American</option>
                                                                <option value="British" {{ old('nationality', $employee->nationality) == 'British' ? 'selected' : '' }}>
                                                                    British</option>
                                                                <option value="Canadian" {{ old('nationality', $employee->nationality) == 'Canadian' ? 'selected' : '' }}>
                                                                    Canadian</option>
                                                                <option value="Australian" {{ old('nationality', $employee->nationality) == 'Australian' ? 'selected' : '' }}>
                                                                    Australian</option>
                                                                <option value="Others" {{ old('nationality', $employee->nationality) == 'Others' ? 'selected' : '' }}>
                                                                    Others</option>
                                                            </select>
                                                            @if($errors->has('nationality'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('nationality') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Religion <span class="required">*</span></label>
                                                            <select name="religion" class="form-control-modern" required>
                                                                <option value="">Select Religion</option>
                                                                <option value="Hindu" {{ old('religion', $employee->religion) == 'Hindu' ? 'selected' : '' }}>Hindu
                                                                </option>
                                                                <option value="Muslim" {{ old('religion', $employee->religion) == 'Muslim' ? 'selected' : '' }}>Muslim
                                                                </option>
                                                                <option value="Christian" {{ old('religion', $employee->religion) == 'Christian' ? 'selected' : '' }}>
                                                                    Christian</option>
                                                                <option value="Sikh" {{ old('religion', $employee->religion) == 'Sikh' ? 'selected' : '' }}>Sikh
                                                                </option>
                                                                <option value="Buddhist" {{ old('religion', $employee->religion) == 'Buddhist' ? 'selected' : '' }}>
                                                                    Buddhist</option>
                                                                <option value="Jain" {{ old('religion', $employee->religion) == 'Jain' ? 'selected' : '' }}>Jain
                                                                </option>
                                                                <option value="Jewish" {{ old('religion', $employee->religion) == 'Jewish' ? 'selected' : '' }}>Jewish
                                                                </option>
                                                                <option value="Others" {{ old('religion', $employee->religion) == 'Others' ? 'selected' : '' }}>Others
                                                                </option>
                                                            </select>
                                                            @if($errors->has('religion'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('religion') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Address Line 1 <span class="required">*</span></label>
                                                            <textarea name="addressline1" class="form-control-modern" required
                                                                placeholder="House no, Street, Area"
                                                                rows="2">{{ old('addressline1', $employee->addressline1) }}</textarea>
                                                            @if($errors->has('addressline1'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('addressline1') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Address Line 2 <span
                                                                    class="text-muted">(Optional)</span></label>
                                                            <textarea name="addressline2" class="form-control-modern"
                                                                placeholder="Landmark, Locality"
                                                                rows="2">{{ old('addressline2', $employee->addressline2) }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>State <span class="required">*</span></label>
                                                            <input type="text" name="state"
                                                                value="{{ old('state', $employee->state) }}"
                                                                class="form-control-modern" required placeholder="State name">
                                                            @if($errors->has('state'))
                                                                <div class="invalid-feedback d-block">{{ $errors->first('state') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>City <span class="required">*</span></label>
                                                            <input type="text" name="city"
                                                                value="{{ old('city', $employee->city) }}"
                                                                class="form-control-modern" required placeholder="City name">
                                                            @if($errors->has('city'))
                                                                <div class="invalid-feedback d-block">{{ $errors->first('city') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Pincode <span class="required">*</span></label>
                                                            <input type="text" name="pincode"
                                                                value="{{ old('pincode', $employee->pincode) }}"
                                                                class="form-control-modern" required maxlength="6"
                                                                placeholder="6-digit pincode">
                                                            @if($errors->has('pincode'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('pincode') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- PANE: PROFESSIONAL --}}
                                        <div class="tab-pane fade" id="pane-professional" role="tabpanel">
                                            <div class="profile-section">
                                                <div class="profile-section-title">
                                                    <div class="profile-section-title-left">
                                                        <i class="bi bi-briefcase"></i> Professional Details
                                                    </div>
                                                    <span class="badge bg-secondary">Read Only</span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Department Category</label>
                                                            <input type="text" class="form-control-modern"
                                                                value="{{ $employee->departmentCategory->category_name ?? 'N/A' }}"
                                                                readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Department</label>
                                                            <input type="text" class="form-control-modern"
                                                                value="{{ $employeedepartment ?? 'N/A' }}" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Designation</label>
                                                            <input type="text" name="designation"
                                                                value="{{ old('designation', $employee->designation) }}"
                                                                class="form-control-modern" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Employment Type <span class="required">*</span></label>
                                                            <input type="text" name="employment_type"
                                                                value="{{ old('employment_type', $employee->employment_type) }}"
                                                                class="form-control-modern" readonly>
                                                        </div>
                                                    </div>

                                                    @if(isset($employee->employment_type) && $employee->employment_type == 'Probation-Period')
                                                        <div class="col-md-6">
                                                            <div class="form-group-modern">
                                                                <label>Probation Period</label>
                                                                <input type="text" value="{{ $employee->probation_days ?? 0 }} days"
                                                                    class="form-control-modern" readonly>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Salary Type <span class="required">*</span></label>
                                                            <input type="text" name="salary_type"
                                                                value="{{ old('salary_type', $employee->salary_type) }}"
                                                                class="form-control-modern" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Date of Joining <span class="required">*</span></label>
                                                            <input type="date" name="doj"
                                                                value="{{ old('doj', $employee->doj) }}"
                                                                class="form-control-modern" required readonly>
                                                            @if($errors->has('doj'))
                                                                <div class="invalid-feedback d-block">{{ $errors->first('doj') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>PF Number (UAN) <span
                                                                    class="text-muted">(Optional)</span></label>
                                                            <input type="text" name="previous_pf_number"
                                                                value="{{ old('previous_pf_number', $employee->previous_pf_number) }}"
                                                                class="form-control-modern">
                                                            @if($errors->has('previous_pf_number'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('previous_pf_number') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>ESI Number <span class="text-muted">(Optional)</span></label>
                                                            <input type="text" name="esi_number"
                                                                value="{{ old('esi_number', $employee->esi_number) }}"
                                                                class="form-control-modern">
                                                            @if($errors->has('esi_number'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('esi_number') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- PANE: CONTACT --}}
                                        <div class="tab-pane fade" id="pane-contact" role="tabpanel">
                                            <div class="profile-section">
                                                <div class="profile-section-title">
                                                    <div class="profile-section-title-left">
                                                        <i class="bi bi-telephone"></i> Contact & Emergency Details
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group-modern">
                                                            <label>Emergency Contact <span class="required">*</span></label>
                                                            <input type="tel" name="emergency_contact_number"
                                                                value="{{ old('emergency_contact_number', $employee->emergency_contact_number) }}"
                                                                class="form-control-modern" required
                                                                placeholder="10-digit number">
                                                            @if($errors->has('emergency_contact_number'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('emergency_contact_number') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group-modern">
                                                            <label>Contact Person Name <span class="required">*</span></label>
                                                            <input type="text" name="contact_person_name"
                                                                value="{{ old('contact_person_name', $employee->contact_person_name) }}"
                                                                class="form-control-modern" required>
                                                            @if($errors->has('contact_person_name'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('contact_person_name') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group-modern">
                                                            <label>Relation <span class="required">*</span></label>
                                                            <input type="text" name="relation_with_contact"
                                                                value="{{ old('relation_with_contact', $employee->relation_with_contact) }}"
                                                                class="form-control-modern" required>
                                                            @if($errors->has('relation_with_contact'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('relation_with_contact') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Reference Name <span
                                                                    class="text-muted">(Optional)</span></label>
                                                            <input type="text" name="reference_name"
                                                                value="{{ old('reference_name', $employee->reference_name) }}"
                                                                class="form-control-modern">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Reference Contact <span
                                                                    class="text-muted">(Optional)</span></label>
                                                            <input type="tel" name="reference_contact_number"
                                                                value="{{ old('reference_contact_number', $employee->reference_contact_number) }}"
                                                                class="form-control-modern">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- PANE: DOCUMENTS --}}
                                        <div class="tab-pane fade" id="pane-documents" role="tabpanel">
                                            <div class="profile-section">
                                                <div class="profile-section-title">
                                                    <div class="profile-section-title-left">
                                                        <i class="bi bi-files"></i> Document Uploads
                                                    </div>
                                                    <span class="badge bg-primary">Upload Required Documents</span>
                                                </div>
                                                <div style="padding:16px 20px;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group-modern">
                                                                <label>Aadhaar Card <span class="required">*</span></label>
                                                                <input type="file" name="aadhaar_card"
                                                                    class="form-control-modern">
                                                                @if($employee->aadhaar_card)
                                                                    <div class="doc-item mt-2">
                                                                        <span class="doc-name">
                                                                            <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                                            Aadhaar Card
                                                                        </span>
                                                                        <span class="doc-status">
                                                                            <span
                                                                                class="status-badge-modern status-complete-modern">
                                                                                <i class="bi bi-check-circle"></i> Uploaded
                                                                            </span>
                                                                            <a href="{{ route('image', ['path' => $employee->aadhaar_card]) }}"
                                                                                target="_blank" class="btn-outline-primary-modern">
                                                                                <i class="bi bi-eye"></i> View
                                                                            </a>
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                @if($errors->has('aadhaar_card'))
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $errors->first('aadhaar_card') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group-modern">
                                                                <label>Aadhaar Number <span class="required">*</span></label>
                                                                <input type="text" name="aadhaar_number"
                                                                    value="{{ old('aadhaar_number', $employee->aadhaar_number) }}"
                                                                    class="form-control-modern" placeholder="12-digit number">
                                                                @if($errors->has('aadhaar_number'))
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $errors->first('aadhaar_number') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group-modern">
                                                                <label>PAN Card <span class="required">*</span></label>
                                                                <input type="file" name="pan_card" class="form-control-modern">
                                                                @if($employee->pan_card)
                                                                    <div class="doc-item mt-2">
                                                                        <span class="doc-name">
                                                                            <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                                                            PAN Card
                                                                        </span>
                                                                        <span class="doc-status">
                                                                            <span
                                                                                class="status-badge-modern status-complete-modern">
                                                                                <i class="bi bi-check-circle"></i> Uploaded
                                                                            </span>
                                                                            <a href="{{ route('image', ['path' => $employee->pan_card]) }}"
                                                                                target="_blank" class="btn-outline-primary-modern">
                                                                                <i class="bi bi-eye"></i> View
                                                                            </a>
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                @if($errors->has('pan_card'))
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $errors->first('pan_card') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group-modern">
                                                                <label>PAN Number <span class="required">*</span></label>
                                                                <input type="text" name="pan_number"
                                                                    value="{{ old('pan_number', $employee->pan_number) }}"
                                                                    class="form-control-modern" placeholder="ABCDE1234F">
                                                                @if($errors->has('pan_number'))
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $errors->first('pan_number') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Additional Documents --}}
                                                    @if($employee->additional_documents)
                                                        <div class="mt-4">
                                                            <h6 class="fw-bold mb-3" style="color: var(--sp-primary);">
                                                                <i class="bi bi-folder-plus me-2"></i>Additional Documents
                                                            </h6>
                                                            <div class="table-responsive">
                                                                <table class="table-modern">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Document Name</th>
                                                                            <th>Document Number</th>
                                                                            <th>Status</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach(json_decode($employee->additional_documents, true) as $doc)
                                                                            <tr>
                                                                                <td>{{ $doc['name'] ?? 'N/A' }}</td>
                                                                                <td>{{ $doc['number'] ?? 'N/A' }}</td>
                                                                                <td>
                                                                                    @if(!empty($doc['file']))
                                                                                        <span
                                                                                            class="status-badge-modern status-complete-modern">
                                                                                            <i class="bi bi-check-circle"></i> Uploaded
                                                                                        </span>
                                                                                    @else
                                                                                        <span
                                                                                            class="status-badge-modern status-incomplete-modern">
                                                                                            <i class="bi bi-hourglass"></i> Pending
                                                                                        </span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    @if(!empty($doc['file']))
                                                                                        <button type="button"
                                                                                            class="btn-outline-primary-modern view-doc"
                                                                                            data-file="{{ route('image', ['path' => $doc['file']]) }}"
                                                                                            data-title="{{ $doc['name'] ?? 'Document' }}">
                                                                                            <i class="bi bi-eye"></i> View
                                                                                        </button>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- PANE: BANK --}}
                                        <div class="tab-pane fade" id="pane-bank" role="tabpanel">
                                            <div class="profile-section">
                                                <div class="profile-section-title">
                                                    <div class="profile-section-title-left">
                                                        <i class="bi bi-bank2"></i> Bank Account Details
                                                    </div>
                                                    <span class="badge bg-secondary">Optional</span>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Bank Name</label>
                                                            <input type="text" name="bank_name"
                                                                value="{{ old('bank_name', $employee->bank_name) }}"
                                                                class="form-control-modern">
                                                            @if($errors->has('bank_name'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('bank_name') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Branch Name</label>
                                                            <input type="text" name="branch_name"
                                                                value="{{ old('branch_name', $employee->branch_name) }}"
                                                                class="form-control-modern">
                                                            @if($errors->has('branch_name'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('branch_name') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>Account Number</label>
                                                            <input type="text" name="account_number"
                                                                value="{{ old('account_number', $employee->account_number) }}"
                                                                class="form-control-modern">
                                                            @if($errors->has('account_number'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('account_number') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group-modern">
                                                            <label>IFSC Code</label>
                                                            <input type="text" name="ifsc_code"
                                                                value="{{ old('ifsc_code', $employee->ifsc_code) }}"
                                                                class="form-control-modern">
                                                            @if($errors->has('ifsc_code'))
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $errors->first('ifsc_code') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    {{-- 💾 Action Buttons --}}
                                    <div class="action-buttons-modern">
                                        <button type="button" class="btn-reset-modern" onclick="window.location.reload();">
                                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn-save-modern">
                                            <i class="bi bi-save me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </form>

                            @endif
                        </div>
                    </div>

                </div>{{-- /sp-col-8 --}}

            </div>{{-- /sp-row --}}

        </div>{{-- /sp-container --}}
    </div>{{-- /sp-wrapper --}}

    {{-- 📄 Employee ID Card Modal --}}
    <div class="modal fade" id="employeeIdCardModal" tabindex="-1" aria-labelledby="employeeIdCardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content id-card-modal-content">
                <div class="id-card-modal-header">
                    <h5 class="modal-title" id="employeeIdCardModalLabel">
                        <i class="bi bi-person-badge-fill me-2"></i> Employee Identification Card
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body p-4 bg-light text-center">
                    @php
                        // Get employee photo for ID card
                        $cardPhotoPath = $employee->profile_photo ?? $employee->passport_photo ?? $employee->photo ?? null;
                        if (!empty($cardPhotoPath)) {
                            $cleanCardPath = ltrim(str_replace(['public/', 'storage/'], '', $cardPhotoPath), '/');
                            $cardPhotoUrl = url('storage/' . $cleanCardPath);
                        } else {
                            $cardPhotoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&size=150&background=4F46E5&color=fff&bold=true';
                        }

                        // Institute logo
                        $instLogo = !empty($idCardData['institute_logo'] ?? null)
                            ? url('storage/' . ltrim(str_replace(['public/', 'storage/'], '', $idCardData['institute_logo']), '/'))
                            : null;
                    @endphp
                    <div id="printableEmpIdCard" class="employee-id-card">
                        {{-- Card Header --}}
                        <div class="id-card-header"
                            style="background: {{ $idCardSettings['header_bg_color'] ?? '#3730A3' }}; color: {{ $idCardSettings['header_font_color'] ?? '#ffffff' }};">
                            @if($instLogo)
                                <img src="{{ $instLogo }}" alt="Logo" class="id-logo">
                            @endif
                            <h6 class="id-institute-name">{{ $idCardData['institute_name'] ?? 'Institute Name' }}</h6>
                            <p class="id-institute-address">{{ $idCardData['institute_address'] ?? '' }}</p>
                            <div class="id-title-badge">
                                {{ $idCardSettings['card_title'] ?? 'EMPLOYEE IDENTIFICATION CARD' }}
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="id-card-body">
                            <div class="id-profile-row">
                                <img src="{{ $cardPhotoUrl }}" alt="{{ $fullName }}" class="id-profile-img"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($fullName) }}&size=150&background=4F46E5&color=fff&bold=true'">
                                <div class="id-profile-info">
                                    <div class="id-name">{{ $fullName }}</div>
                                    <div class="id-employee-badge">EMP: {{ $employee->employee_code ?? 'N/A' }}</div>
                                    <div class="id-designation"><strong>Designation:</strong>
                                        {{ $employee->designation_name ?? $employee->designation ?? 'N/A' }}
                                    </div>
                                    <div class="id-department"><strong>Department:</strong>
                                        {{ $employee->department_name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            <div class="id-details-grid">
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Employee Code</span>
                                    <span class="id-detail-value">{{ $employee->employee_code ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Role</span>
                                    <span class="id-detail-value">{{ $employee->role ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Date of Joining</span>
                                    <span
                                        class="id-detail-value">{{ isset($employee->doj) ? date('d/m/Y', strtotime($employee->doj)) : 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Blood Group</span>
                                    <span class="id-detail-value text-danger">{{ $employee->blood_group ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Mobile</span>
                                    <span class="id-detail-value">{{ $employee->mobile_number ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Email</span>
                                    <span class="id-detail-value">{{ $employee->email ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item" style="grid-column: 1 / -1;">
                                    <span class="id-detail-label">Address</span>
                                    <span class="id-detail-value"
                                        style="font-size:12px;">{{ trim(($employee->addressline1 ?? '') . ' ' . ($employee->city ?? '') . ' ' . ($employee->state ?? '')) ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer with Barcode & QR Code --}}
                        <div class="id-card-footer">
                            <div>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $employee->employee_code ?? 'N/A' }}"
                                    alt="QR Code" class="id-qr-code">
                            </div>
                            <div class="id-barcode-wrap">
                                <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $employee->employee_code ?? 'N/A' }}&code=Code128&dpi=96&imagetype=Png"
                                    alt="Barcode" class="id-barcode-img">
                                <div class="id-barcode-text">{{ $employee->employee_code ?? '' }}</div>
                            </div>
                            <div class="id-signature">
                                <div style="height: 25px;"></div>
                                <div class="id-signature-line">
                                    {{ $idCardSettings['signature_text'] ?? "Authorized Signature" }}
                                </div>
                            </div>
                        </div>

                        {{-- Card Bottom Bar --}}
                        <div class="id-card-bottom-bar"
                            style="background: {{ $idCardSettings['footer_bg_color'] ?? '#3730A3' }}; color: {{ $idCardSettings['footer_font_color'] ?? '#ffffff' }};">
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

    {{-- 📄 Reusable Modal for Document View --}}
    <div class="modal fade" id="viewDocumentModal" tabindex="-1" aria-labelledby="viewDocumentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title" id="viewDocumentModalLabel">
                        <i class="bi bi-file-earmark-text me-2"></i>View Document
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body text-center p-4 bg-light">
                    <div id="documentContainer"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize all tabs with Bootstrap's native method
            const triggerTabList = [].slice.call(document.querySelectorAll('#employeeTabs a'))
            triggerTabList.forEach(function (triggerEl) {
                const tabTrigger = new bootstrap.Tab(triggerEl)
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })

            // Handle hash in URL
            if (window.location.hash) {
                const hashTab = document.querySelector(`#employeeTabs a[href="${window.location.hash}"]`);
                if (hashTab) {
                    const hashTabTrigger = new bootstrap.Tab(hashTab);
                    hashTabTrigger.show();
                }
            }

            // Document Viewer Modal setup
            let viewDocumentModal = null;
            const modalElement = document.getElementById('viewDocumentModal');

            if (modalElement) {
                viewDocumentModal = new bootstrap.Modal(modalElement);
            }

            // Document Viewer
            document.addEventListener('click', function (event) {
                if (event.target.classList.contains('view-doc') ||
                    event.target.closest('.view-doc')) {
                    event.preventDefault();

                    const button = event.target.classList.contains('view-doc')
                        ? event.target
                        : event.target.closest('.view-doc');

                    const fileUrl = button.getAttribute('data-file');
                    const title = button.getAttribute('data-title') || 'Document';

                    if (!fileUrl) return;

                    const ext = fileUrl.split('.').pop().toLowerCase();

                    const modalTitle = document.getElementById('viewDocumentModalLabel');
                    if (modalTitle) {
                        modalTitle.innerHTML = `<i class="bi bi-file-earmark-text me-2"></i>${title}`;
                    }

                    let htmlContent = '';
                    if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(ext)) {
                        htmlContent = `
                                <div class="mb-3">
                                    <img src="${fileUrl}" class="img-fluid rounded shadow-sm" 
                                        style="max-height: 70vh; width: auto;" 
                                        alt="${title}">
                                </div>
                                <a href="${fileUrl}" download="${title}.${ext}" 
                                   class="btn btn-primary">
                                    <i class="bi bi-download me-2"></i>Download
                                </a>
                            `;
                    } else if (ext === 'pdf') {
                        htmlContent = `
                                <div class="mb-3">
                                    <iframe src="${fileUrl}" width="100%" height="500px" 
                                        class="border-0 rounded shadow-sm"></iframe>
                                </div>
                                <a href="${fileUrl}" download="${title}.pdf" 
                                   class="btn btn-primary">
                                    <i class="bi bi-download me-2"></i>Download
                                </a>
                            `;
                    } else {
                        htmlContent = `
                                <div class="alert alert-info">
                                    <p>This file type cannot be previewed.</p>
                                    <a href="${fileUrl}" download 
                                       class="btn btn-primary mt-2">
                                        <i class="bi bi-download me-2"></i>Download File
                                    </a>
                                </div>
                            `;
                    }

                    const documentContainer = document.getElementById('documentContainer');
                    if (documentContainer) {
                        documentContainer.innerHTML = htmlContent;
                    }

                    if (viewDocumentModal) {
                        viewDocumentModal.show();
                    }
                }
            });

            // Form validation feedback
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    let valid = true;
                    const requiredFields = form.querySelectorAll('[required]');

                    requiredFields.forEach(field => {
                        field.classList.remove('is-invalid');
                        if (!field.value || field.value.trim() === '') {
                            valid = false;
                            field.classList.add('is-invalid');
                        }
                    });

                    if (!valid) {
                        e.preventDefault();
                        const firstInvalid = form.querySelector('.is-invalid');
                        if (firstInvalid) {
                            // Find which tab contains the invalid field
                            const tabPane = firstInvalid.closest('.tab-pane');
                            if (tabPane) {
                                const tabId = '#' + tabPane.getAttribute('id');
                                const tabButton = document.querySelector(`#employeeTabs a[href="${tabId}"]`);
                                if (tabButton) {
                                    const tab = new bootstrap.Tab(tabButton);
                                    tab.show();
                                }
                            }
                            setTimeout(() => {
                                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                firstInvalid.focus();
                            }, 100);
                        }
                        alert('Please fill all required fields marked with *');
                    }
                });

                // Remove invalid class on input
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.addEventListener('input', function () {
                        this.classList.remove('is-invalid');
                    });
                    input.addEventListener('change', function () {
                        this.classList.remove('is-invalid');
                    });
                });
            }
        });

        // Print Employee ID Card
        function printEmpCard() {
            var printContents = document.getElementById('printableEmpIdCard').outerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = '<div style="display:flex;justify-content:center;align-items:center;height:100vh;background:#f1f5f9;padding:20px;">' + printContents + '</div>';
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>
@endsection