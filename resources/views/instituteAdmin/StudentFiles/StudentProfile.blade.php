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

        /* Row & Column */
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

        /* Info list */
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

        /* ----- ORIGINAL FORM STYLES KEPT INTACT ----- */
        .PersonalForm h3 {
            background: var(--primary-gradient);
            color: white;
            padding: 18px 25px;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 1.3rem;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.25);
        }

        .AddHeading {
            background: var(--sp-gradient);
            color: white;
            width: max-content;
            margin: 30px 0 20px 0;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .SubHeading {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1rem;
            padding: 10px 0;
            border-bottom: 2px solid rgba(67, 97, 238, 0.2);
            position: relative;
        }

        .SubHeading::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-gradient);
            border-radius: 3px;
        }

        .FormContainer {
            max-width: 100%;
        }

        .FormWrap {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .FormRow {
            flex: 0 0 calc(33.333% - 14px);
            min-width: 250px;
        }

        .FormField {
            margin-bottom: 15px;
        }

        .FormField label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .readonlyField {
            border: 2px solid rgba(67, 97, 238, 0.15);
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.9rem;
            color: #475569;
            cursor: not-allowed;
            transition: all 0.2s ease;
            width: 100%;
        }

        .readonlyField:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            outline: none;
        }

        select.readonlyField {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234361ee' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 20px;
            padding-right: 40px;
        }

        .phone-input {
            display: flex;
            gap: 10px;
        }

        .phone-input .country-code {
            width: 80px;
            flex-shrink: 0;
        }

        .phone-input .phone-number {
            flex: 1;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-left: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-indicator.text-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .status-indicator.text-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #ff5200 !important;
        }

        .img-thumbnail {
            border-radius: 12px;
            border: 2px solid rgba(67, 97, 238, 0.2);
            padding: 5px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.8rem;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .text-muted {
            color: #94a3b8 !important;
            font-size: 0.85rem;
        }

        .tab-pane {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Student ID Card Modal */
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

        /* Student ID Card */
        .student-id-card {
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

        .id-card-body .id-profile-info .id-student-badge {
            font-size: 11px;
            background: var(--sp-primary);
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 4px;
        }

        .id-card-body .id-profile-info .id-course {
            font-size: 13px;
            color: #64748B;
        }

        .id-card-body .id-profile-info .id-dept {
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

        /* Page Header */
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

        /* Responsive */
        @media (max-width: 1199px) {
            .FormRow {
                flex: 0 0 calc(50% - 10px);
            }
        }

        @media (max-width: 767px) {
            .sp-col-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .sp-col-8 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .FormWrap {
                gap: 0;
            }

            .FormField {
                margin-bottom: 15px;
            }

            .FormField label {
                font-size: 0.75rem;
            }

            .readonlyField {
                font-size: 0.85rem;
                padding: 10px 14px;
            }

            .PersonalForm h3 {
                font-size: 1.1rem;
                padding: 14px 18px;
            }

            .FormWrap {
                flex-direction: column;
                gap: 0;
            }

            .FormRow {
                flex: 0 0 100%;
                min-width: 100%;
            }

            .phone-input {
                flex-direction: column;
                gap: 10px;
            }

            .phone-input .country-code {
                width: 100%;
            }

            .AddHeading {
                font-size: 0.85rem;
                margin: 20px 0 15px 0;
                padding: 10px 18px;
            }

            .sp-tabs-card .nav-tabs .nav-link {
                padding: 10px 14px;
                font-size: 12px;
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
    </style>

    <div class="sp-wrapper fade-in-up">
        <div class="sp-container">

            {{-- ---------- PAGE HEADER CARD ---------- --}}
            <div class="sp-page-header-card">
                <div class="sp-header-title">
                    <h2>
                        <i class="bi bi-person-bounding-box"></i> Student Profile
                    </h2>
                    <p>Comprehensive overview of student details, academic information, and documents.</p>
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="employee-id-badge">
                        <i class="bi bi-upc-scan me-1"></i>Admission: {{ $student->registration_number ?? 'N/A' }}
                    </span>
                    <span class="last-updated">
                        <i class="bi bi-clock-history me-1"></i>
                        <strong>Last Updated:</strong>
                        {{ isset($student->updated_at) ? $student->updated_at->format('M d, Y h:i A') : 'N/A' }}
                    </span>
                </div>
            </div>

            <div class="sp-row">

                {{-- ---------- LEFT COLUMN — PROFILE CARD ---------- --}}
                <div class="sp-col-4">
                    <div class="sp-left-card">
                        <div class="card-body">

                            {{-- Hero --}}
                            <div class="sp-hero-wrap">
                                @php
                                    $fullName = trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
                                    $studentPhoto = $student->documents->student_photo ?? null;
                                    if (!empty($studentPhoto)) {
                                        $avatarUrl = asset('/image/' . $studentPhoto);
                                    } else {
                                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($fullName ?: 'Student') . '&size=120&background=4F46E5&color=fff&font-size=0.5&bold=true';
                                    }
                                @endphp
                                <img class="sp-avatar" src="{{ $avatarUrl }}" alt="{{ $fullName ?: 'Student' }}"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($fullName ?: 'Student') }}&size=120&background=4F46E5&color=fff&font-size=0.5&bold=true'">
                                <!-- <div class="sp-status-dot"></div> -->

                                <div class="sp-name">{{ $fullName ?: 'Student' }}</div>
                                <span class="sp-class-badge">
                                    {{ $student->academicTransportDetails->department ?? 'N/A' }}
                                    {{ isset($student->academicTransportDetails->course_subtype) ? '— ' . $student->academicTransportDetails->course_subtype : (isset($student->academicTransportDetails->course_type) ? '— ' . $student->academicTransportDetails->course_type : '') }}
                                </span>
                            </div>

                            {{-- Info list --}}
                            <ul class="sp-info-list">
                                <li>
                                    <span class="il-label"><i class="bi bi-upc-scan"></i> Admission</span>
                                    <span class="il-value">{{ $student->registration_number ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-sort-numeric-up"></i> Roll No</span>
                                    <span class="il-value">{{ $student->academicTransportDetails->roll_no ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-gender-ambiguous"></i> Gender</span>
                                    <span class="il-value">{{ $student->gender ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-envelope-fill"></i> Email</span>
                                    <span class="il-value">{{ $student->email ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-phone-fill"></i> Mobile</span>
                                    <span class="il-value">{{ $student->mobile ?? 'N/A' }}</span>
                                </li>
                                <li>
                                    <span class="il-label"><i class="bi bi-calendar-plus-fill"></i> DOB</span>
                                    <span class="il-value">{{ $student->dob ?? 'N/A' }}</span>
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
                                <a href="#" class="sp-btn sp-btn-primary d-none" onclick="window.print(); return false;">
                                    <i class="bi bi-printer-fill"></i> Print Profile
                                </a>
                            </div>

                            {{-- Student ID Card Action --}}
                            <div class="sp-cred-actions">
                                <button type="button" class="btn-idcard" data-bs-toggle="modal"
                                    data-bs-target="#studentIdCardModal">
                                    <i class="bi bi-person-badge-fill me-1"></i> <b>View Student ID Card</b>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ---------- RIGHT COLUMN — TABS (ORIGINAL CONTENT KEPT INTACT) ---------- --}}
                <div class="sp-col-8">

                    <div class="sp-tabs-card">
                        <div class="card-header p-0 pt-1 border-bottom-0">
                            <ul class="nav nav-tabs" id="desktopTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="personal-tab" data-bs-toggle="tab" href="#personal"
                                        role="tab" aria-controls="personal" aria-selected="true">
                                        <i class="bi bi-person-fill me-1"></i> Personal Details
                                        @php $personalComplete = !empty($student->first_name) && !empty($student->dob) && !empty($student->gender); @endphp
                                        @if($personalComplete)
                                            <span class="status-indicator text-success">✔</span>
                                        @else
                                            <span class="status-indicator text-warning">Pending</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="address-tab" data-bs-toggle="tab" href="#address" role="tab"
                                        aria-controls="address" aria-selected="false">
                                        <i class="bi bi-geo-alt-fill me-1"></i> Address
                                        @php $addressComplete = $student->address && !empty($student->address->student_perm_state) && !empty($student->address->student_perm_city) && !empty($student->address->student_perm_pincode); @endphp
                                        @if($addressComplete)
                                            <span class="status-indicator text-success">✔</span>
                                        @else
                                            <span class="status-indicator text-warning">Pending</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="academic-tab" data-bs-toggle="tab" href="#academic" role="tab"
                                        aria-controls="academic" aria-selected="false">
                                        <i class="bi bi-mortarboard-fill me-1"></i> Academic
                                        @php $academicComplete = $student->academicTransportDetails && !empty($student->academicTransportDetails->department_id) && !empty($student->academicTransportDetails->course_id); @endphp
                                        @if($academicComplete)
                                            <span class="status-indicator text-success">✔</span>
                                        @else
                                            <span class="status-indicator text-warning">Pending</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="documents-tab" data-bs-toggle="tab" href="#documents" role="tab"
                                        aria-controls="documents" aria-selected="false">
                                        <i class="bi bi-file-earmark-text-fill me-1"></i> Documents
                                        @php $documentsComplete = $student->documents && !empty($student->documents->student_aadhaar_number) && !empty($student->documents->student_aadhaar_file) && !empty($student->documents->student_photo) && !empty($student->documents->parent_aadhaar_number); @endphp
                                        @if($documentsComplete)
                                            <span class="status-indicator text-success">✔</span>
                                        @else
                                            <span class="status-indicator text-warning">Pending</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bank-tab" data-bs-toggle="tab" href="#bank" role="tab"
                                        aria-controls="bank" aria-selected="false">
                                        <i class="bi bi-bank me-1"></i> Bank Details
                                        @php $bankComplete = $student->bankAccount && !empty($student->bankAccount->account_number) && !empty($student->bankAccount->ifsc_code) && !empty($student->bankAccount->upload_cancelled_cheque); @endphp
                                        @if($bankComplete)
                                            <span class="status-indicator text-success">✔</span>
                                        @else
                                            <span class="status-indicator text-warning">Pending</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">

                            <div class="tab-content" id="desktopTabContent">

                                <!-- Personal Details Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                    aria-labelledby="personal-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="PersonalForm">
                                                <h3><i class="bi bi-person-badge me-2"></i>Student Details</h3>
                                                <form class="FormContainer">
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Registration Number</label>
                                                                <input type="text" value="{{$student->registration_number}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>First Name</label>
                                                                <input type="text" value="{{$student->first_name}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Middle Name</label>
                                                                <input type="text" value="{{$student->middle_name}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Last Name</label>
                                                                <input type="text" value="{{$student->last_name}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Email ID</label>
                                                                <input type="email" value="{{$student->email}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Mobile Number</label>
                                                                <div class="phone-input">
                                                                    <input type="text" value="+91" readonly
                                                                        class="form-control readonlyField country-code" />
                                                                    <input type="text" value="{{$student->mobile}}" readonly
                                                                        class="form-control readonlyField phone-number" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Date of Birth</label>
                                                                <input type="date" value="{{$student->dob}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Gender</label>
                                                                <select name="gender" readonly
                                                                    class="form-control readonlyField">
                                                                    <option value="Male" {{ ($student->gender == 'Male') ? 'selected' : '' }}>Male</option>
                                                                    <option value="Female" {{($student->gender == 'Female') ? 'selected' : ''}}>Female</option>
                                                                    <option value="Other" {{($student->gender == 'Other') ? 'selected' : ''}}>Other</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Blood Group</label>
                                                                <input type="text" value="{{$student->blood_group}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h5 class="AddHeading">Parent's Details</h5>
                                                    <p class="SubHeading">Father's Details</p>
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>First Name</label>
                                                                <input type="text" value="{{$student->father_first_name}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Middle Name</label>
                                                                <input type="text" value="{{$student->father_middle_name}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Last Name</label>
                                                                <input type="text" value="{{$student->father_last_name}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Father's Email</label>
                                                                <input type="email" value="{{$student->father_email}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Father's Phone</label>
                                                                <div class="phone-input">
                                                                    <input type="text" value="+91" readonly
                                                                        class="form-control readonlyField country-code" />
                                                                    <input type="text" value="{{$student->father_phone}}"
                                                                        readonly
                                                                        class="form-control readonlyField phone-number" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Father's Date of Birth</label>
                                                                <input type="date" value="{{$student->father_dob}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Father's Occupation</label>
                                                                <select readonly class="form-control readonlyField">
                                                                    <option>-- Select --</option>
                                                                    <option>Engineer</option>
                                                                    <option>Doctor</option>
                                                                    <option>Teacher</option>
                                                                    <option selected>Business</option>
                                                                    <option>Other</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Father's Annual Income</label>
                                                                <input type="text" value="{{$student->father_income}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Father's Blood Group</label>
                                                                <input type="text" value="{{$student->father_blood_group}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <p class="SubHeading">Mother's Details</p>
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>First Name</label>
                                                                <input type="text" value="{{$student->mother_first_name}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Middle Name</label>
                                                                <input type="text" value="{{$student->mother_last_name}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Last Name</label>
                                                                <input type="text" value="{{$student->mother_last_name}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Mother's Email</label>
                                                                <input type="email" value="{{$student->mother_email}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Mother's Phone</label>
                                                                <div class="phone-input">
                                                                    <input type="text" value="+91" readonly
                                                                        class="form-control readonlyField country-code" />
                                                                    <input type="text" value="{{$student->mother_phone}}"
                                                                        readonly
                                                                        class="form-control readonlyField phone-number" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Mother's Date of Birth</label>
                                                                <input type="date" value="{{$student->mother_dob}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Mother's Occupation</label>
                                                                <select readonly class="form-control readonlyField">
                                                                    <option>-- Select --</option>
                                                                    <option>Engineer</option>
                                                                    <option>Doctor</option>
                                                                    <option selected>Teacher</option>
                                                                    <option>Business</option>
                                                                    <option>Other</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Mother's Annual Income</label>
                                                                <input type="text" value="{{$student->mother_income}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Mother's Blood Group</label>
                                                                <input type="text" value="{{$student->mother_blood_group}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @php
                                                        $guardianFields = [
                                                            $student->guardian_first_name,
                                                            $student->guardian_middle_name,
                                                            $student->guardian_last_name,
                                                            $student->guardian_phone,
                                                            $student->alternate_phone_number,
                                                            $student->guardian_dob,
                                                            $student->relation_with_guardian ?? null,
                                                            $student->guardian_occupation ?? null
                                                        ];
                                                        $hasGuardian = count(array_filter($guardianFields)) > 0;
                                                    @endphp

                                                    @if($hasGuardian)
                                                        <h5 class="AddHeading">Guardian Details</h5>
                                                        <div class="FormWrap">
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>First Name</label>
                                                                    <input type="text" value="{{$student->guardian_first_name}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Middle Name</label>
                                                                    <input type="text"
                                                                        value="{{$student->guardian_middle_name}}" readonly
                                                                        class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Last Name</label>
                                                                    <input type="text" value="{{$student->guardian_last_name}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Phone</label>
                                                                    <div class="phone-input">
                                                                        <input type="text" value="+91" readonly
                                                                            class="form-control readonlyField country-code" />
                                                                        <input type="text" value="{{$student->guardian_phone}}"
                                                                            readonly
                                                                            class="form-control readonlyField phone-number" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Alternate Phone</label>
                                                                    <div class="phone-input">
                                                                        <input type="text" value="+91" readonly
                                                                            class="form-control readonlyField country-code" />
                                                                        <input type="text"
                                                                            value="{{$student->alternate_phone_number}}"
                                                                            readonly
                                                                            class="form-control readonlyField phone-number" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Relation with Student</label>
                                                                    <select readonly class="form-control readonlyField">
                                                                        <option>-- Select --</option>
                                                                        <option>Grandparent</option>
                                                                        <option>Uncle/Aunt</option>
                                                                        <option selected>Legal Guardian</option>
                                                                        <option>Other</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Gender</label>
                                                                    <select name="gender" readonly
                                                                        class="form-control readonlyField">
                                                                        <option value="Male" {{($student->gender == "Male") ? 'selected' : ''}}>Male</option>
                                                                        <option value="Female" {{($student->gender == "Female") ? 'selected' : ''}}>Female</option>
                                                                        <option value="Other" {{($student->gender == "Other") ? 'selected' : ''}}>Other</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Date of Birth</label>
                                                                    <input type="date" value="{{$student->guardian_dob}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Occupation</label>
                                                                    <select readonly class="form-control readonlyField">
                                                                        <option>-- Select --</option>
                                                                        <option selected>Engineer</option>
                                                                        <option>Doctor</option>
                                                                        <option>Teacher</option>
                                                                        <option>Business</option>
                                                                        <option>Other</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Annual Income</label>
                                                                    <input type="text" value="₹15,00,000" readonly
                                                                        class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Tab -->
                                <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="PersonalForm">
                                                <h3><i class="bi bi-geo-alt me-2"></i>Address Details</h3>
                                                <form class="FormContainer">
                                                    <h5 class="AddHeading">Student</h5>
                                                    <p class="SubHeading">Permanent Address</p>
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Line 1</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_perm_address_line1}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Line 2</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_perm_address_line2}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>City</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_perm_city}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>State</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_perm_state}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Pincode</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_perm_pincode}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <p class="SubHeading">Communication Address</p>
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Line 1</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_comm_address_line1}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Line 2</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_comm_address_line2}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>City</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_comm_city}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>State</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_comm_state}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Pincode</label>
                                                                <input type="text"
                                                                    value="{{$student->address->student_comm_pincode}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h5 class="AddHeading">Parent</h5>
                                                    <p class="SubHeading">Permanent Address</p>
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Line 1</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_perm_address_line1}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Line 2</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_perm_address_line2}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>City</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_perm_city}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>State</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_perm_state}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Pincode</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_perm_pincode}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <p class="SubHeading">Communication Address</p>
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Line 1</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_comm_address_line1}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Line 2</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_comm_address_line2}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>City</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_comm_city}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>State</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_comm_state}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Pincode</label>
                                                                <input type="text"
                                                                    value="{{$student->address->parent_comm_pincode}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Academic Tab -->
                                <div class="tab-pane fade" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="PersonalForm">
                                                <h3><i class="bi bi-mortarboard me-2"></i>Academic Details</h3>
                                                <form class="FormContainer">
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Department</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->department}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Course Type</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->course_type}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Sub Type</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->course_subtype}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Session</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->course_type}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Batch</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->batch}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Academic Year</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->academic_year}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Course Mode</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->mode_type}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Mode Type</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->mode_of_course}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Semester</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->semester_id}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Section</label>
                                                                <input type="text"
                                                                    value="{{$student->academicTransportDetails->section_id}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if(
                                                            $student->academicTransportDetails &&
                                                            (
                                                                $student->academicTransportDetails->morning_route_name ||
                                                                $student->academicTransportDetails->morning_stop ||
                                                                $student->academicTransportDetails->evening_route_name ||
                                                                $student->academicTransportDetails->evening_stop
                                                            )
                                                        )
                                                        <h5 class="AddHeading">Transport Details</h5>
                                                        <div class="FormWrap">
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Morning Pickup</label>
                                                                    <input type="text"
                                                                        value="{{$student->academicTransportDetails->morning_route_name}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Nearest Stop</label>
                                                                    <input type="text"
                                                                        value="{{$student->academicTransportDetails->morning_stop}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="FormWrap">
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Evening Drop</label>
                                                                    <input type="text"
                                                                        value="{{$student->academicTransportDetails->morning_route_name}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Nearest Stop</label>
                                                                    <input type="text"
                                                                        value="{{$student->academicTransportDetails->evening_stop}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Documents Tab -->
                                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="PersonalForm">
                                                <h3><i class="bi bi-file-earmark-text me-2"></i>Student Documents</h3>
                                                <form class="FormContainer">
                                                    <h5 class="AddHeading">Student Documents</h5>
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Aadhaar Number</label>
                                                                <input type="text"
                                                                    value="{{ $student->documents->student_aadhaar_number ?? '' }}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>PAN Number</label>
                                                                <input type="text"
                                                                    value="{{ $student->documents->student_pan_number ?? '' }}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Student Photo</label>
                                                                @if(!empty($student->documents->student_photo))
                                                                    <img src="{{ asset('/image/' . $student->documents->student_photo) }}"
                                                                        class="img-thumbnail" style="width:120px;height:auto;">
                                                                @else
                                                                    <p class="text-muted">No photo uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Aadhaar Card</label>
                                                                @if(!empty($student->documents->student_aadhaar_file))
                                                                    <a href="{{ asset('/image/' . $student->documents->student_aadhaar_file) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View Aadhaar
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>PAN Card</label>
                                                                @if(!empty($student->documents->student_pan_file))
                                                                    <a href="{{ asset('/image/' . $student->documents->student_pan_file) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View PAN
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Proof</label>
                                                                @if(!empty($student->documents->student_address_proof))
                                                                    <a href="{{ asset('/image/' . $student->documents->student_address_proof) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View File
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Student ID Card</label>
                                                                @if(!empty($student->documents->student_id_card))
                                                                    <a href="{{ asset('/image/' . $student->documents->student_id_card) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View ID Card
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Birth Certificate</label>
                                                                @if(!empty($student->documents->birth_certificate))
                                                                    <a href="{{ asset('/image/' . $student->documents->birth_certificate) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View File
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>10th Marksheet</label>
                                                                @if(!empty($student->documents->marksheet_10))
                                                                    <a href="{{ asset('/image/' . $student->documents->marksheet_10) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View File
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>12th Marksheet</label>
                                                                @if(!empty($student->documents->marksheet_12))
                                                                    <a href="{{ asset('/image/' . $student->documents->marksheet_12) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View File
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Bachelor's Marksheet</label>
                                                                @if(!empty($student->documents->bachelor_marksheet))
                                                                    <a href="{{ asset('/image/' . $student->documents->bachelor_marksheet) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View File
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h5 class="AddHeading">Parent / Guardian Documents</h5>
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Aadhaar Number</label>
                                                                <input type="text"
                                                                    value="{{ $student->documents->parent_aadhaar_number ?? '' }}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>PAN Number</label>
                                                                <input type="text"
                                                                    value="{{ $student->documents->parent_pan_number ?? '' }}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Aadhaar Card</label>
                                                                @if(!empty($student->documents->parent_aadhaar_file))
                                                                    <a href="{{ asset('/image/' . $student->documents->parent_aadhaar_file) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View Aadhaar
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>PAN Card</label>
                                                                @if(!empty($student->documents->parent_pan_file))
                                                                    <a href="{{ asset('/image/' . $student->documents->parent_pan_file) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View PAN
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Income Proof</label>
                                                                @if(!empty($student->documents->parent_income_proof))
                                                                    <a href="{{ asset('/image/' . $student->documents->parent_income_proof) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View File
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Parent Photo</label>
                                                                @if(!empty($student->documents->parent_photo))
                                                                    <a href="{{ asset('/image/' . $student->documents->parent_photo) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View File
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Address Proof</label>
                                                                @if(!empty($student->documents->parent_address_proof))
                                                                    <a href="{{ asset('/image/' . $student->documents->parent_address_proof) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary">
                                                                        <i class="bi bi-eye me-1"></i>View File
                                                                    </a>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bank Tab -->
                                <div class="tab-pane fade" id="bank" role="tabpanel" aria-labelledby="bank-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="PersonalForm">
                                                <h3><i class="bi bi-bank me-2"></i>Bank Details</h3>
                                                <form class="FormContainer">
                                                    <h5 class="AddHeading">Parent</h5>
                                                    <div class="FormWrap">
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Beneficiary Name</label>
                                                                <input type="text"
                                                                    value="{{$student->bankAccount->benificiary_name}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Bank Account Number</label>
                                                                <input type="text"
                                                                    value="{{$student->bankAccount->bank_account_number}}"
                                                                    readonly class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Bank Name</label>
                                                                <input type="text"
                                                                    value="{{$student->bankAccount->bank_name}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>IFSC Code</label>
                                                                <input type="text"
                                                                    value="{{$student->bankAccount->ifsc_code}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Account Type</label>
                                                                <input type="text"
                                                                    value="{{$student->bankAccount->account_type}}" readonly
                                                                    class="form-control readonlyField" />
                                                            </div>
                                                        </div>
                                                        <div class="FormRow">
                                                            <div class="FormField">
                                                                <label>Cancelled Cheque</label>
                                                                @if(!empty($student->bankAccount->upload_cancelled_cheque))
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('storage/bank_documents/' . $student->bankAccount->upload_cancelled_cheque) }}"
                                                                            alt="Cancelled Cheque"
                                                                            style="max-width: 180px; border: 2px solid rgba(67, 97, 238, 0.2); border-radius: 12px;" />
                                                                    </div>
                                                                @else
                                                                    <p class="text-muted">No file uploaded</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if(
                                                            $student->bankAccount &&
                                                            (
                                                                $student->bankAccount->student_benificiary_name ||
                                                                $student->bankAccount->student_bank_account_number ||
                                                                $student->bankAccount->student_bank_name ||
                                                                $student->bankAccount->student_ifsc_code ||
                                                                $student->bankAccount->student_account_type ||
                                                                $student->bankAccount->student_upload_cancelled_cheque
                                                            )
                                                        )
                                                        <h5 class="AddHeading">Student</h5>
                                                        <div class="FormWrap">
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Beneficiary Name</label>
                                                                    <input type="text"
                                                                        value="{{$student->bankAccount->student_benificiary_name}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Bank Account Number</label>
                                                                    <input type="text"
                                                                        value="{{$student->bankAccount->student_bank_account_number}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Bank Name</label>
                                                                    <input type="text"
                                                                        value="{{$student->bankAccount->student_bank_name}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>IFSC Code</label>
                                                                    <input type="text"
                                                                        value="{{$student->bankAccount->student_ifsc_code}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Account Type</label>
                                                                    <input type="text"
                                                                        value="{{$student->bankAccount->student_account_type}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                            <div class="FormRow">
                                                                <div class="FormField">
                                                                    <label>Cancelled Cheque</label>
                                                                    <input type="text"
                                                                        value="{{$student->bankAccount->student_upload_cancelled_cheque}}"
                                                                        readonly class="form-control readonlyField" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>{{-- /sp-col-8 --}}

            </div>{{-- /sp-row --}}

        </div>{{-- /sp-container --}}
    </div>{{-- /sp-wrapper --}}

    {{-- 📄 Student ID Card Modal --}}
    <div class="modal fade" id="studentIdCardModal" tabindex="-1" aria-labelledby="studentIdCardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content id-card-modal-content">
                <div class="id-card-modal-header">
                    <h5 class="modal-title" id="studentIdCardModalLabel">
                        <i class="bi bi-person-badge-fill me-2"></i> Student Identification Card
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body p-4 bg-light text-center">
                    @php
                        $fullName = trim(($student->first_name ?? '') . ' ' . ($student->middle_name ?? '') . ' ' . ($student->last_name ?? ''));
                        $studentPhoto = $student->documents->student_photo ?? null;
                        if (!empty($studentPhoto)) {
                            $cardPhotoUrl = asset('/image/' . $studentPhoto);
                        } else {
                            $cardPhotoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($fullName ?: 'Student') . '&size=150&background=4F46E5&color=fff&bold=true';
                        }

                        $instLogo = !empty($idCardData['institute_logo'] ?? null)
                            ? asset('/image/' . $idCardData['institute_logo'])
                            : null;
                    @endphp
                    <div id="printableStudentIdCard" class="student-id-card">
                        {{-- Card Header --}}
                        <div class="id-card-header"
                            style="background: {{ $idCardSettings['header_bg_color'] ?? '#3730A3' }}; color: {{ $idCardSettings['header_font_color'] ?? '#ffffff' }};">
                            @if($instLogo)
                                <img src="{{ $instLogo }}" alt="Logo" class="id-logo">
                            @endif
                            <h6 class="id-institute-name">{{ $idCardData['institute_name'] ?? 'Institute Name' }}</h6>
                            <p class="id-institute-address">{{ $idCardData['institute_address'] ?? '' }}</p>
                            <div class="id-title-badge">{{ $idCardSettings['card_title'] ?? 'STUDENT IDENTIFICATION CARD' }}
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="id-card-body">
                            <div class="id-profile-row">
                                <img src="{{ $cardPhotoUrl }}" alt="{{ $fullName ?: 'Student' }}" class="id-profile-img"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($fullName ?: 'Student') }}&size=150&background=4F46E5&color=fff&bold=true'">
                                <div class="id-profile-info">
                                    <div class="id-name">{{ $fullName ?: 'Student' }}</div>
                                    <div class="id-student-badge">ADM: {{ $student->registration_number ?? 'N/A' }}</div>
                                    <div class="id-course"><strong>Course:</strong>
                                        {{ $student->academicTransportDetails->course_subtype ?? 'N/A' }}
                                    </div>
                                    <div class="id-dept"><strong>Dept:</strong>
                                        {{ $student->academicTransportDetails->department ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            <div class="id-details-grid">
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Roll Number</span>
                                    <span
                                        class="id-detail-value">{{ $student->academicTransportDetails->roll_no ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Section</span>
                                    <span
                                        class="id-detail-value">{{ $student->academicTransportDetails->section_id ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Date of Birth</span>
                                    <span class="id-detail-value">{{ $student->dob ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Blood Group</span>
                                    <span class="id-detail-value text-danger">{{ $student->blood_group ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Father's Name</span>
                                    <span
                                        class="id-detail-value">{{ trim(($student->father_first_name ?? '') . ' ' . ($student->father_last_name ?? '')) ?: 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item">
                                    <span class="id-detail-label">Mobile</span>
                                    <span
                                        class="id-detail-value">{{ $student->mobile ?? $student->father_phone ?? 'N/A' }}</span>
                                </div>
                                <div class="id-detail-item" style="grid-column: 1 / -1;">
                                    <span class="id-detail-label">Address</span>
                                    <span class="id-detail-value"
                                        style="font-size:12px;">{{ trim(($student->address->student_perm_address_line1 ?? '') . ' ' . ($student->address->student_perm_city ?? '') . ' ' . ($student->address->student_perm_state ?? '')) ?: 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer with Barcode & QR Code --}}
                        <div class="id-card-footer">
                            <div>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ $student->registration_number ?? 'N/A' }}"
                                    alt="QR Code" class="id-qr-code">
                            </div>
                            <div class="id-barcode-wrap">
                                <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $student->registration_number ?? 'N/A' }}&code=Code128&dpi=96&imagetype=Png"
                                    alt="Barcode" class="id-barcode-img">
                                <div class="id-barcode-text">{{ $student->registration_number ?? '' }}</div>
                            </div>
                            <div class="id-signature">
                                <div style="height: 25px;"></div>
                                <div class="id-signature-line">
                                    {{ $idCardSettings['signature_text'] ?? "Principal's Signature" }}
                                </div>
                            </div>
                        </div>

                        {{-- Card Bottom Bar --}}
                        <div class="id-card-bottom-bar"
                            style="background: {{ $idCardSettings['footer_bg_color'] ?? '#3730A3' }}; color: {{ $idCardSettings['footer_font_color'] ?? '#ffffff' }};">
                            <span>Academic Year: {{ $student->academicTransportDetails->academic_year ?? date('Y') }}</span>
                            <span>Official Student ID</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printStudentCard()">
                        <i class="bi bi-printer-fill me-1"></i> Print Card
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ JS --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tabs
            const triggerTabList = [].slice.call(document.querySelectorAll('#desktopTab a'))
            triggerTabList.forEach(function (triggerEl) {
                const tabTrigger = new bootstrap.Tab(triggerEl)
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault()
                    tabTrigger.show()
                })
            })

            // Handle hash in URL
            if (window.location.hash) {
                const hashTab = document.querySelector(`#desktopTab a[href="${window.location.hash}"]`);
                if (hashTab) {
                    const hashTabTrigger = new bootstrap.Tab(hashTab);
                    hashTabTrigger.show();
                }
            }
        });

        // Print Student ID Card
        function printStudentCard() {
            var printContents = document.getElementById('printableStudentIdCard').outerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = '<div style="display:flex;justify-content:center;align-items:center;height:100vh;background:#f1f5f9;padding:20px;">' + printContents + '</div>';
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>
@endsection