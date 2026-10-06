@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* ── RESET & GLOBAL ── */

        .interview-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ── TYPOGRAPHY & ICONS ── */
        .header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header h1 i {
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            color: white;
            padding: 12px;
            border-radius: 16px;
            font-size: 1.25rem;
            box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.2);
        }

        .header p {
            color: #64748b;
            font-size: 0.95rem;
            margin-top: 6px;
            font-weight: 400;
        }

        /* ── HEADER CARD ── */
        .header {
            background: white;
            border-radius: 28px;
            padding: 24px 32px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(226, 232, 240, 0.6);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .filters {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 12px 20px;
            border-radius: 40px;
            border: 1px solid #e2e8f0;
            background: white;
            font-size: 0.9rem;
            color: #1e293b;
            font-weight: 500;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
            min-width: 150px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .filter-select:hover {
            border-color: #4f46e5;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
        }

        /* ── PROGRESS STEPS (TIMELINE) ── */
        .steps-progress {
            background: white;
            border-radius: 28px;
            padding: 28px 36px;
            margin-bottom: 24px;
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .progress-step {
            flex: 1;
            text-align: center;
            position: relative;
            cursor: pointer;
            padding: 6px 0;
            transition: all 0.2s ease;
        }

        .progress-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 30px;
            left: calc(50% + 30px);
            width: calc(100% - 60px);
            height: 3px;
            background: #e9eef4;
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .progress-step.active:not(:last-child)::after {
            background: linear-gradient(90deg, #4f46e5, #a5b4fc);
        }

        .step-marker {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 600;
            font-size: 1.1rem;
            color: #94a3b8;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .progress-step.active .step-marker {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border-color: #4f46e5;
            color: white;
            box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.3);
            transform: scale(1.02);
        }

        .step-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
            margin-bottom: 3px;
            transition: color 0.2s;
        }

        .progress-step.active .step-title {
            color: #4f46e5;
        }

        .step-subtitle {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 400;
        }

        /* ── MAIN CARD ── */
        .main-card {
            background: white;
            border-radius: 28px;
            border: 1px solid rgba(226, 232, 240, 0.6);
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.02);
        }

        .card-header {
            padding: 22px 32px;
            background: white;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        .card-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header h2 i {
            color: #4f46e5;
            background: #eef2ff;
            padding: 10px;
            border-radius: 14px;
            font-size: 1.1rem;
        }

        .step-content {
            padding: 32px;
        }

        .step-pane {
            display: none;
        }

        .step-pane.active {
            display: block;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── GRID & LAYOUT ── */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        /* ── SECTION CARDS ── */
        .section-card {
            background: white;
            border: 1px solid #edf2f7;
            border-radius: 20px;
            margin-bottom: 24px;
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }

        .section-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.02);
        }

        .section-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafcff;
        }

        .section-header h3 {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header h3 i {
            /* color: #fff; */
            font-size: 1rem;
            /* background: #eef2ff; */
            padding: 6px;
            border-radius: 10px;
        }

        .section-body {
            padding: 24px;
        }

        /* ── FORM ELEMENTS (elevated) ── */
        .form-group {
            margin-bottom: 18px;
        }

        .form-row {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            display: block;
            letter-spacing: 0.01em;
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.9rem;
            background: white;
            transition: all 0.15s ease;
            color: #0f172a;
            font-weight: 450;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.01);
        }

        .form-control:focus {
            border-color: #4f46e5;
            outline: none;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        textarea.form-control {
            min-height: 70px;
            resize: vertical;
            line-height: 1.5;
        }

        /* ── TOGGLE (modern) ── */
        .toggle-switch {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            color: #475569;
            font-weight: 500;
        }

        .toggle {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #e2e8f0;
            transition: 0.25s;
            border-radius: 30px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 2px;
            bottom: 2px;
            background: white;
            transition: 0.25s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input:checked+.slider {
            background: #4f46e5;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
        }

        /* ── RADIO / DRESS / CARD SELECTORS ── */
        .radio-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .radio-card {
            flex: 1;
            min-width: 100px;
            padding: 14px 10px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            text-align: center;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            background: white;
            transition: all 0.2s ease;
            color: #1e293b;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.01);
        }

        .radio-card i {
            display: block;
            margin-bottom: 8px;
            font-size: 1.2rem;
            color: #4f46e5;
        }

        .radio-card.selected {
            background: #4f46e5;
            border-color: #4f46e5;
            color: white;
            box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.3);
            transform: translateY(-2px);
        }

        .radio-card.selected i {
            color: white;
        }

        .dress-codes {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .dress-card {
            flex: 1;
            min-width: 130px;
            padding: 16px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            text-align: center;
            cursor: pointer;
            background: white;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.01);
        }

        .dress-card.selected {
            background: #eef2ff;
            border-color: #4f46e5;
            box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }

        .dress-icon {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #4f46e5;
        }

        .dress-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: #0f172a;
        }

        .dress-desc {
            font-size: 0.7rem;
            color: #64748b;
            margin-top: 4px;
        }

        /* ── DOC LIST & ROUND ITEMS ── */
        .doc-list,
        .rounds-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .doc-item,
        .round-item {
            background: #fafcff;
            border: 1px solid #edf2f7;
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .doc-item:hover,
        .round-item:hover {
            background: white;
            border-color: #cbd5e1;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.02);
        }

        .round-item {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .round-info h4 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
        }

        .round-info h4 span {
            background: #eef2ff;
            color: #4f46e5;
            padding: 3px 12px;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .round-meta {
            display: flex;
            gap: 18px;
            font-size: 0.8rem;
            color: #64748b;
            margin: 8px 0;
            flex-wrap: wrap;
        }

        .test-details {
            background: #f0f9ff;
            padding: 12px 16px;
            border-radius: 14px;
            font-size: 0.8rem;
            color: #0369a1;
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-top: 6px;
            font-weight: 500;
        }

        .round-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-icon.edit {
            background: #3b82f6;
        }

        .btn-icon.delete {
            background: #ef4444;
        }

        .btn-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        /* ── BUTTONS ── */
        .btn {
            padding: 10px 22px;
            border-radius: 40px;
            border: none;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background: #4338ca;
            box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.4);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: white;
            border: 1px solid #e2e8f0;
            color: #1e293b;
        }

        .btn-outline:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            box-shadow: 0 8px 16px -4px rgba(16, 185, 129, 0.4);
        }

        /* ── NAVIGATION ── */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 36px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
        }

        /* ── MODAL ── */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 28px;
            max-width: 560px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-content::-webkit-scrollbar {
            width: 6px;
        }

        .modal-content::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 10px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-close {
            background: #f1f5f9;
            border: none;
            font-size: 1.4rem;
            cursor: pointer;
            color: #64748b;
            width: 40px;
            height: 40px;
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-close:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        /* ── TOAST ── */
        .toast-box {
            position: fixed;
            top: 24px;
            right: 24px;
            background: #10b981;
            color: white;
            padding: 16px 24px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
            z-index: 2000;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .toast-box.show {
            opacity: 1;
            transform: translateX(0);
        }

        /* ── SKILL SECTION (re-styled) ── */
        .skill-tab {
            padding: 8px 22px;
            border-radius: 40px;
            font-size: 0.85rem;
            position: relative;
            border: none;
            background: transparent;
            color: #64748b;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .skill-tab.active {
            background: white !important;
            color: #4f46e5 !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
        }

        .skill-tab.active span {
            background: #e0e7ff !important;
            color: #4f46e5 !important;
        }

        .skill-tab span {
            background: #e2e8f0;
            color: #475569;
            padding: 2px 10px;
            border-radius: 100px;
            font-size: 0.7rem;
            transition: all 0.2s;
        }

        .skills-list::-webkit-scrollbar {
            width: 5px;
        }

        .skills-list::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 10px;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            body {
                padding: 20px;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .progress-steps {
                flex-direction: column;
                gap: 16px;
            }

            .progress-step:not(:last-child)::after {
                display: none;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .step-content {
                padding: 20px;
            }

            .card-header {
                padding: 18px 20px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 12px;
            }

            .header {
                padding: 18px;
            }

            .steps-progress {
                padding: 20px;
            }

            .radio-group,
            .dress-codes {
                flex-direction: column;
            }
        }

        /* ── MISC UTILS ── */
        .duration-display {
            background: #eef2ff;
            padding: 10px 18px;
            border-radius: 40px;
            color: #4f46e5;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 12px 0;
            font-weight: 500;
        }

        .info-message {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 16px;
            padding: 14px 20px;
            color: #0369a1;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .test-fields {
            display: none;
            padding: 20px;
            background: #f8fafc;
            border-radius: 18px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }

        .test-fields.show {
            display: block;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .checkbox-item {
            display: flex;
            align-items: flex-start;
            padding: 14px 18px;
            background: #fafcff;
            border: 1px solid #edf2f7;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .checkbox-item:hover {
            background: white;
            border-color: #cbd5e1;
        }

        .checkbox-item input[type="checkbox"] {
            margin-right: 16px;
            margin-top: 3px;
            width: 18px;
            height: 18px;
            accent-color: #4f46e5;
        }

        #timeError {
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 14px;
            padding: 12px 18px;
            margin-bottom: 18px;
            color: #b91c1c;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ── SKILL LIST GRID ── */
        #skillsList .skill-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.01);
        }

        #skillsList .skill-card:hover {
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        /* ── SKILL QUICK ADD ── */
        .quick-skill-chip {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 100px;
            padding: 6px 6px 6px 16px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
            cursor: pointer;
        }

        .quick-skill-chip:hover {
            border-color: #4f46e5;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.1);
            transform: translateY(-1px);
        }

        /* ensure proper icon alignment everywhere */
        .fas,
        .far,
        .fab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* scrollbar for modal */
        .modal-content::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
    </style>

    <div class="interview-container">
        <!-- Header (unchanged content, refined layout) -->
        <div class="header">
            <div>
                <h1>
                    <i class="fas fa-calendar-check"></i>
                    Applicant Journey Configuration
                </h1>
                <p>Configure complete interview process for students</p>
            </div>
            <div class="filters">
                <select class="filter-select" id="academicYearSelect">
                    <option value="2026-2027">2026-2027</option>
                    <option value="2027-2028">2027-2028</option>
                    <option value="2025-2026">2025-2026</option>
                </select>
                <select class="filter-select" id="departmentSelect">
                    <option value="">Select Department</option>
                    @foreach($selectDepartments as $selectDepartment)
                        <option value="{{ $selectDepartment->department_id }}">
                            {{ $selectDepartment->department }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Progress Steps / Timeline -->
        <div class="steps-progress">
            <div class="progress-steps">
                <div class="progress-step active" onclick="showStep(1)">
                    <div class="step-marker">1</div>
                    <div class="step-title">Application</div>
                    <div class="step-subtitle">Student applies</div>
                </div>
                <div class="progress-step" onclick="showStep(2)">
                    <div class="step-marker">2</div>
                    <div class="step-title">Interview</div>
                    <div class="step-subtitle">Rounds & Details</div>
                </div>
                <div class="progress-step" onclick="showStep(3)">
                    <div class="step-marker">3</div>
                    <div class="step-title">Selection</div>
                    <div class="step-subtitle">Result</div>
                </div>
                <div class="progress-step" onclick="showStep(4)">
                    <div class="step-marker">4</div>
                    <div class="step-title">Onboarding</div>
                    <div class="step-subtitle">Joining</div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <div class="card-header">
                <h2 id="stepTitle">
                    <i class="fas fa-file-alt"></i>
                    Step 1: Application Form
                </h2>
            </div>

            <div class="step-content">
                <!-- STEP 1: APPLICATION (unchanged content, re-styled) -->
                <div class="step-pane active" id="step1">
                    <div class="grid-2">
                        <!-- Application Settings -->
                        <div class="section-card">
                            <div class="section-header">
                                <h3><i class="fas fa-cog"></i> Application Settings</h3>
                                <div class="toggle-switch">
                                    <span>Enable</span>
                                    <label class="toggle">
                                        <input type="checkbox" id="enableStep1" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="form-group">
                                    <label class="form-label">Add profile</label>
                                    <input type="text" class="form-control" id="appFormTitle">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Opening Start Date</label>
                                        <input type="date" class="form-control" id="appStartDate">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Opening End Date</label>
                                        <input type="date" class="form-control" id="appEndDate">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Max Applications</label>
                                        <input type="number" class="form-control" value="500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Required Documents -->
                        <div class="section-card">
                            <div class="section-header">
                                <h3><i class="fas fa-file-alt"></i> Required Documents</h3>
                            </div>
                            <div class="section-body">
                                <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px;">
                                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                        <input type="text" class="form-control" id="docName"
                                            placeholder="Document name (e.g., Resume)" style="flex:2; min-width: 140px;">
                                        <select id="docType" class="form-control" style="width:130px;">
                                            <option value="required">Required</option>
                                            <option value="optional">Optional</option>
                                        </select>
                                        <button class="btn btn-primary" onclick="addDocument()"><i class="fas fa-plus"></i>
                                            Add</button>
                                    </div>
                                    <input type="text" class="form-control" id="docSpec"
                                        placeholder="Specification (e.g., PDF, DOC, DOCX, JPG, PNG, 5MB max)"
                                        value="PDF, DOC, DOCX, JPG, PNG, 5MB max">
                                </div>
                                <div class="doc-list" id="documentsList"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Skills Section - Modern Redesign (unchanged functionality) -->
                    <div class="section-card" style="margin-top: 24px;">
                        <div class="section-header" style="background: linear-gradient(to right, #f8fafc, white);">
                            <h3 style="display: flex; align-items: center; gap: 12px;">
                                <span
                                    style="background: linear-gradient(135deg, #4f46e5, #818cf8); width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-code" style="color: white; font-size: 1rem;"></i>
                                </span>
                                <span style="font-size: 1rem; font-weight: 600; color: #1e293b;">Required Skills</span>
                            </h3>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <span style="color: #64748b; font-size: 0.85rem; font-weight: 500;" id="totalSkillsCount">0
                                    skills</span>
                                <label class="toggle">
                                    <input type="checkbox" id="enableSkills" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="section-body" style="padding: 28px;">
                            <!-- Modern Input Card -->
                            <div
                                style="background: #f8fafc; border-radius: 24px; padding: 24px; margin-bottom: 28px; border: 1px solid #e2e8f0;">
                                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                                    <div
                                        style="width: 40px; height: 40px; background: #e0e7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-plus" style="color: #4f46e5; font-size: 1rem;"></i>
                                    </div>
                                    <span style="font-weight: 600; color: #1e293b;">Add New Skill</span>
                                    <span style="color: #94a3b8; font-size: 0.8rem; margin-left: auto;">Fill in the details
                                        below</span>
                                </div>

                                <div style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
                                    <div style="flex: 2; min-width: 200px; position: relative;">
                                        <i class="fas fa-tag"
                                            style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.95rem;"></i>
                                        <input type="text" class="form-control" id="skillName"
                                            placeholder="e.g., React.js, Leadership, Python"
                                            style="padding-left: 44px; height: 48px; border-radius: 14px; border: 1px solid #e2e8f0; background: white;">
                                    </div>

                                    <div style="position: relative; width: 150px;">
                                        <i class="fas fa-chart-line"
                                            style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 1; font-size: 0.85rem;"></i>
                                        <select id="skillLevel" class="form-control"
                                            style="padding-left: 44px; height: 48px; border-radius: 14px; appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%2364748b\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><polyline points=\'6 9 12 15 18 9\'/></svg>'); background-repeat: no-repeat; background-position: right 16px center;">
                                            <option value="beginner">Beginner</option>
                                            <option value="intermediate">Intermediate</option>
                                            <option value="advanced">Advanced</option>
                                            <option value="expert">Expert</option>
                                            <option value="mandatory">Mandatory</option>
                                        </select>
                                    </div>

                                    <div style="position: relative; width: 150px;">
                                        <i class="fas fa-folder"
                                            style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 1; font-size: 0.85rem;"></i>
                                        <select id="skillType" class="form-control"
                                            style="padding-left: 44px; height: 48px; border-radius: 14px; appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%2364748b\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><polyline points=\'6 9 12 15 18 9\'/></svg>'); background-repeat: no-repeat; background-position: right 16px center;">
                                            <option value="technical">Technical</option>
                                            <option value="soft">Soft Skill</option>
                                            <option value="language">Language</option>
                                            <option value="certification">Certification</option>
                                        </select>
                                    </div>

                                    <button class="btn" onclick="addSkill()"
                                        style="background: linear-gradient(135deg, #4f46e5, #6366f1); color: white; border: none; padding: 0 28px; height: 48px; border-radius: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.3);">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Add Skill</span>
                                    </button>
                                </div>

                                <div style="margin-top: 14px; display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-lightbulb" style="color: #fbbf24; font-size: 0.9rem;"></i>
                                    <span style="color: #64748b; font-size: 0.8rem;">Add skills one at a time with their
                                        proficiency level</span>
                                </div>
                            </div>

                            <!-- Quick Add Templates (unchanged content, improved chips) -->
                            <div style="margin-bottom: 28px;">
                                <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 18px;">
                                    <div
                                        style="width: 6px; height: 22px; background: linear-gradient(to bottom, #4f46e5, #818cf8); border-radius: 6px;">
                                    </div>
                                    <span style="font-weight: 600; color: #334155;">Quick Add Templates</span>
                                    <span style="color: #94a3b8; font-size: 0.75rem;">Click to add common teaching
                                        skills</span>
                                </div>

                                <!-- Teaching Skills -->
                                <div style="margin-bottom: 22px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <i class="fas fa-chalkboard-teacher" style="color: #4f46e5; font-size: 0.9rem;"></i>
                                        <span
                                            style="font-size: 0.75rem; font-weight: 700; color: #4f46e5; letter-spacing: 0.5px;">TEACHING
                                            SKILLS</span>
                                        <div
                                            style="flex: 1; height: 1px; background: linear-gradient(to right, #e0e7ff, transparent);">
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Lesson Planning', 'advanced', 'soft')">
                                            <i class="fas fa-calendar-alt" style="color: #4f46e5;"></i>
                                            <span style="color: #334155;">Lesson Planning</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Classroom Management', 'advanced', 'soft')">
                                            <i class="fas fa-users-class" style="color: #3b82f6;"></i>
                                            <span style="color: #334155;">Classroom Mgmt</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Curriculum Development', 'intermediate', 'soft')">
                                            <i class="fas fa-book" style="color: #8b5cf6;"></i>
                                            <span style="color: #334155;">Curriculum Dev</span>
                                            <span
                                                style="background: #3b82f6; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Intermediate</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Student Assessment', 'advanced', 'soft')">
                                            <i class="fas fa-check-circle" style="color: #10b981;"></i>
                                            <span style="color: #334155;">Student Assessment</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Communication Skills -->
                                <div style="margin-bottom: 22px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <i class="fas fa-comments" style="color: #3b82f6; font-size: 0.9rem;"></i>
                                        <span
                                            style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; letter-spacing: 0.5px;">COMMUNICATION</span>
                                        <div
                                            style="flex: 1; height: 1px; background: linear-gradient(to right, #bfdbfe, transparent);">
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Parent-Teacher Communication', 'advanced', 'soft')">
                                            <i class="fas fa-handshake" style="color: #3b82f6;"></i>
                                            <span style="color: #334155;">Parent Communication</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Student Counseling', 'intermediate', 'soft')">
                                            <i class="fas fa-heart" style="color: #ec4899;"></i>
                                            <span style="color: #334155;">Student Counseling</span>
                                            <span
                                                style="background: #3b82f6; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Intermediate</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Public Speaking', 'advanced', 'soft')">
                                            <i class="fas fa-microphone-alt" style="color: #8b5cf6;"></i>
                                            <span style="color: #334155;">Public Speaking</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Interpersonal Skills -->
                                <div style="margin-bottom: 22px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <i class="fas fa-users" style="color: #f59e0b; font-size: 0.9rem;"></i>
                                        <span
                                            style="font-size: 0.75rem; font-weight: 700; color: #f59e0b; letter-spacing: 0.5px;">INTERPERSONAL</span>
                                        <div
                                            style="flex: 1; height: 1px; background: linear-gradient(to right, #fde68a, transparent);">
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                        <div class="quick-skill-chip" onclick="quickAddSkill('Patience', 'expert', 'soft')">
                                            <i class="fas fa-clock" style="color: #f59e0b;"></i>
                                            <span style="color: #334155;">Patience</span>
                                            <span
                                                style="background: #10b981; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Expert</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Empathy', 'advanced', 'soft')">
                                            <i class="fas fa-heart" style="color: #ec4899;"></i>
                                            <span style="color: #334155;">Empathy</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Conflict Resolution', 'intermediate', 'soft')">
                                            <i class="fas fa-handshake" style="color: #3b82f6;"></i>
                                            <span style="color: #334155;">Conflict Resolution</span>
                                            <span
                                                style="background: #3b82f6; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Intermediate</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Mentoring', 'advanced', 'soft')">
                                            <i class="fas fa-chalkboard" style="color: #8b5cf6;"></i>
                                            <span style="color: #334155;">Mentoring</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Digital Skills -->
                                <div style="margin-bottom: 22px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <i class="fas fa-laptop" style="color: #64748b; font-size: 0.9rem;"></i>
                                        <span
                                            style="font-size: 0.75rem; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">DIGITAL
                                            SKILLS</span>
                                        <div
                                            style="flex: 1; height: 1px; background: linear-gradient(to right, #cbd5e1, transparent);">
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Online Teaching', 'advanced', 'technical')">
                                            <i class="fas fa-video" style="color: #4f46e5;"></i>
                                            <span style="color: #334155;">Online Teaching</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('MS Office', 'expert', 'technical')">
                                            <i class="fab fa-microsoft" style="color: #3b82f6;"></i>
                                            <span style="color: #334155;">MS Office</span>
                                            <span
                                                style="background: #10b981; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Expert</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Google Classroom', 'intermediate', 'technical')">
                                            <i class="fab fa-google" style="color: #f97316;"></i>
                                            <span style="color: #334155;">Google Classroom</span>
                                            <span
                                                style="background: #3b82f6; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Intermediate</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subject Expertise -->
                                <div style="margin-bottom: 22px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <i class="fas fa-book-open" style="color: #8b5cf6; font-size: 0.9rem;"></i>
                                        <span
                                            style="font-size: 0.75rem; font-weight: 700; color: #8b5cf6; letter-spacing: 0.5px;">SUBJECT
                                            EXPERTISE</span>
                                        <div
                                            style="flex: 1; height: 1px; background: linear-gradient(to right, #ddd6fe, transparent);">
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Mathematics', 'advanced', 'technical')">
                                            <i class="fas fa-calculator" style="color: #4f46e5;"></i>
                                            <span style="color: #334155;">Mathematics</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Science', 'advanced', 'technical')">
                                            <i class="fas fa-flask" style="color: #10b981;"></i>
                                            <span style="color: #334155;">Science</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('English', 'expert', 'language')">
                                            <i class="fas fa-language" style="color: #3b82f6;"></i>
                                            <span style="color: #334155;">English</span>
                                            <span
                                                style="background: #10b981; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Expert</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Computer Science', 'intermediate', 'technical')">
                                            <i class="fas fa-desktop" style="color: #8b5cf6;"></i>
                                            <span style="color: #334155;">Computer Science</span>
                                            <span
                                                style="background: #3b82f6; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Intermediate</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Classroom Support -->
                                <div style="margin-bottom: 22px;">
                                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                        <i class="fas fa-hands-helping" style="color: #14b8a6; font-size: 0.9rem;"></i>
                                        <span
                                            style="font-size: 0.75rem; font-weight: 700; color: #14b8a6; letter-spacing: 0.5px;">CLASSROOM
                                            SUPPORT</span>
                                        <div
                                            style="flex: 1; height: 1px; background: linear-gradient(to right, #a5f3fc, transparent);">
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Special Needs Education', 'intermediate', 'soft')">
                                            <i class="fas fa-hands" style="color: #14b8a6;"></i>
                                            <span style="color: #334155;">Special Needs</span>
                                            <span
                                                style="background: #3b82f6; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Intermediate</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Activity Planning', 'advanced', 'soft')">
                                            <i class="fas fa-gamepad" style="color: #f97316;"></i>
                                            <span style="color: #334155;">Activity Planning</span>
                                            <span
                                                style="background: #4f46e5; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Advanced</span>
                                        </div>
                                        <div class="quick-skill-chip"
                                            onclick="quickAddSkill('Exam Invigilation', 'intermediate', 'soft')">
                                            <i class="fas fa-pencil-alt" style="color: #64748b;"></i>
                                            <span style="color: #334155;">Exam Invigilation</span>
                                            <span
                                                style="background: #3b82f6; color: white; padding: 2px 10px; border-radius: 100px; font-size: 0.65rem; font-weight: 600;">Intermediate</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Tabs -->
                            <div style="margin-bottom: 24px;">
                                <div
                                    style="display: flex; gap: 8px; background: #f1f5f9; padding: 6px; border-radius: 20px; display: inline-flex;">
                                    <button class="skill-tab active" onclick="filterSkills('all')" id="tab-all">
                                        All Skills <span id="count-all">0</span>
                                    </button>
                                    <button class="skill-tab" onclick="filterSkills('technical')" id="tab-technical">
                                        <i class="fas fa-code" style="font-size: 0.8rem;"></i> Technical <span
                                            id="count-technical">0</span>
                                    </button>
                                    <button class="skill-tab" onclick="filterSkills('soft')" id="tab-soft">
                                        <i class="fas fa-heart" style="font-size: 0.8rem;"></i> Soft Skills <span
                                            id="count-soft">0</span>
                                    </button>
                                    <button class="skill-tab" onclick="filterSkills('language')" id="tab-language">
                                        <i class="fas fa-language" style="font-size: 0.8rem;"></i> Languages <span
                                            id="count-language">0</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Skills List -->
                            <div id="skillsList" style="min-height: 150px;"></div>

                            <!-- Footer with stats -->
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-top: 28px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                                <div style="display: flex; align-items: center; gap: 14px;">
                                    <div style="width: 8px; height: 8px; background: #4f46e5; border-radius: 50%;"></div>
                                    <span style="color: #1e293b; font-weight: 600;" id="totalSkillsCount-footer">0 skills
                                        added</span>
                                    <span style="color: #94a3b8;">•</span>
                                    <span style="color: #64748b; font-size: 0.85rem;">Last updated just now</span>
                                </div>
                                <button class="btn btn-outline" onclick="clearAllSkills()"
                                    style="border-color: #fee2e2; color: #ef4444; border-radius: 14px; padding: 10px 20px; display: flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-trash-alt" style="font-size: 0.8rem;"></i>
                                    <span>Clear All</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: INTERVIEW (unchanged content, redesigned cards) -->
                <div class="step-pane" id="step2">
                    <!-- Interview Mode -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3><i class="fas fa-laptop"></i> Interview Mode</h3>
                            <div class="toggle-switch">
                                <span>Enable</span>
                                <label class="toggle">
                                    <input type="checkbox" id="enableStep2" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="radio-group">
                                <label class="radio-card" onclick="selectRadio(this, 'online', event)">
                                    <i class="fas fa-video"></i>
                                    <div>Online</div>
                                </label>
                                <label class="radio-card" onclick="selectRadio(this, 'offline', event)">
                                    <i class="fas fa-building"></i>
                                    <div>Offline</div>
                                </label>
                                <label class="radio-card" onclick="selectRadio(this, 'hybrid', event)">
                                    <i class="fas fa-sync"></i>
                                    <div>Hybrid</div>
                                </label>
                            </div>
                            <input type="hidden" id="interviewMode" value="online">
                            <div id="onlineMeetingFields" class="form-group" style="margin-top: 20px;">
                                <label class="form-label">Google Meet Link</label>
                                <input type="url" class="form-control" id="onlineMeetLink"
                                    placeholder="https://meet.google.com/...">
                            </div>
                        </div>
                    </div>

                    <!-- Dress Code -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3><i class="fas fa-tshirt"></i> Dress Code</h3>
                        </div>
                        <div class="section-body">
                            <div class="dress-codes">
                                <label class="dress-card" onclick="selectDress(this, 'formal')">
                                    <div class="dress-icon"><i class="fas fa-user-tie"></i></div>
                                    <div class="dress-name">Formal</div>
                                    <div class="dress-desc">Suit, Tie, Formal Shoes</div>
                                </label>
                                <label class="dress-card" onclick="selectDress(this, 'business')">
                                    <div class="dress-icon"><i class="fas fa-briefcase"></i></div>
                                    <div class="dress-name">Business Casual</div>
                                    <div class="dress-desc">Shirt, Trousers, Blazer</div>
                                </label>
                                <label class="dress-card" onclick="selectDress(this, 'smart')">
                                    <div class="dress-icon"><i class="fas fa-shirt"></i></div>
                                    <div class="dress-name">Semi-formal</div>
                                    <div class="dress-desc">Neat & Professional</div>
                                </label>
                                <label class="dress-card" onclick="selectDress(this, 'traditional')">
                                    <div class="dress-icon"><i class="fas fa-vest"></i></div>
                                    <div class="dress-name">Traditional</div>
                                    <div class="dress-desc">Indian Formal Wear</div>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Custom Instructions</label>
                                <input type="text" class="form-control" id="customDressCode"
                                    placeholder="e.g., White shirt, Black trousers">
                            </div>
                            <input type="hidden" id="dressCode" value="formal">
                        </div>
                    </div>

                    <!-- Interview Rounds -->
                    <div class="section-card">
                        <div class="section-header">
                            <h3><i class="fas fa-layer-group"></i> Interview Rounds</h3>
                            <button class="btn btn-primary" onclick="openRoundModal()">
                                <i class="fas fa-plus"></i> Add Round/Test
                            </button>
                        </div>
                        <div class="section-body">
                            <div class="rounds-list" id="roundsList"></div>
                        </div>
                    </div>

                    <!-- Offline Location -->
                    <div class="grid-1" id="offlineLocationFields">
                        <div class="section-card">
                            <div class="section-header">
                                <h3><i class="fas fa-map-marker-alt"></i> Offline Location</h3>
                            </div>
                            <div class="section-body">
                                <div class="form-group">
                                    <label class="form-label">Offline Location Type</label>
                                    <select class="form-control" id="offlineLocationType">
                                        <option value="existing">Select Existing Address</option>
                                        <option value="manual">Manual Address</option>
                                    </select>
                                </div>
                                <div id="existingLocationFields">
                                    <div class="form-group"><label class="form-label">Building</label><select
                                            class="form-control" id="venueBuildingId">
                                            <option value="">Select Building</option>
                                        </select></div>
                                    <div class="form-group"><label class="form-label">Block</label><select
                                            class="form-control" id="venueBlockId" disabled>
                                            <option value="">Select Block</option>
                                        </select></div>
                                    <div class="form-group"><label class="form-label">Floor</label><select
                                            class="form-control" id="venueFloorId" disabled>
                                            <option value="">Select Floor</option>
                                        </select></div>
                                    <div class="form-group"><label class="form-label">Room</label><select
                                            class="form-control" id="venueRoomId" disabled>
                                            <option value="">Select Room</option>
                                        </select></div>
                                </div>
                                <div id="manualLocationFields" style="display:none;">
                                    <div class="form-group">
                                        <label class="form-label">Venue/Building</label>
                                        <input type="text" class="form-control" id="manualVenueBuilding"
                                            placeholder="e.g., Academic Block A">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Room/Floor</label>
                                        <input type="text" class="form-control" id="manualVenueRoom"
                                            placeholder="e.g., Room 101, 2nd Floor">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" id="venueAddress" rows="2"
                                        placeholder="Full address"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: SELECTION -->
                <div class="step-pane" id="step3">
                    <div class="grid-2">
                        <div class="section-card">
                            <div class="section-header">
                                <h3><i class="fas fa-check-circle"></i> Selection Criteria</h3>
                                <div class="toggle-switch">
                                    <span>Enable</span>
                                    <label class="toggle">
                                        <input type="checkbox" id="enableStep3" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="info-message">
                                    <i class="fas fa-info-circle"></i>
                                    Select which interview rounds/tests will be considered for final selection
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Select Rounds for Selection</label>
                                    <div class="checkbox-group" id="roundsCheckboxList">
                                        <p style="color: #94a3b8; text-align:center; padding:20px;">No rounds added yet.
                                            Please add rounds in Interview step first.</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Selection Method</label>
                                    <select class="form-control" id="selectionMethod">
                                        <option value="all">All selected rounds must be cleared</option>
                                        <option value="any">Any one round cleared</option>
                                        <option value="average">Average of all rounds</option>
                                        <option value="best">Best round score</option>
                                        <option value="weighted">Weighted average (configure below)</option>
                                    </select>
                                </div>
                                <div class="form-group" id="weightageContainer" style="display:none;">
                                    <label class="form-label">Round Weightages (%)</label>
                                    <div id="weightageFields"></div>
                                    <small style="color:#94a3b8;">Total should be 100%</small>
                                </div>
                            </div>
                        </div>
                        <div class="section-card">
                            <div class="section-header">
                                <h3><i class="fas fa-envelope"></i> Offer Letter</h3>
                            </div>
                            <div class="section-body">
                                <div class="form-group">
                                    <label class="form-label">Generate Offer Letter</label>
                                    <select class="form-control" id="offerLetterGen">
                                        <option value="auto">Automatically</option>
                                        <option value="manual">Manually</option>
                                        <option value="no">Do not generate</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Acceptance Deadline</label>
                                    <input type="number" class="form-control" id="acceptanceDays" value="7"
                                        placeholder="Days">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">
                                        <input type="checkbox" id="sendRejectionEmail" checked> Send rejection emails
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: ONBOARDING -->
                <div class="step-pane" id="step4">
                    <div class="grid-2">
                        <div class="section-card">
                            <div class="section-header">
                                <h3><i class="fas fa-calendar-alt"></i> Joining Details</h3>
                                <div class="toggle-switch">
                                    <span>Enable</span>
                                    <label class="toggle">
                                        <input type="checkbox" id="enableStep4" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="section-body">
                                <div class="form-group">
                                    <label class="form-label">Joining Date</label>
                                    <input type="date" class="form-control" id="joiningDate">
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Reporting Time</label>
                                        <input type="time" class="form-control" value="09:00">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Venue</label>
                                        <input type="text" class="form-control" value="HR Office">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section-card">
                            <div class="section-header">
                                <h3><i class="fas fa-chalkboard-teacher"></i> Induction Program</h3>
                            </div>
                            <div class="section-body">
                                <div class="form-group">
                                    <label class="form-label">Induction</label>
                                    <select class="form-control" id="inductionProgram">
                                        <option>Mandatory</option>
                                        <option>Optional</option>
                                        <option>None</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label">Duration (days)</label>
                                        <input type="number" class="form-control" id="inductionDays" value="3">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Training (months)</label>
                                        <input type="number" class="form-control" id="trainingMonths" value="6">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section-card" style="margin-top:24px;">
                        <div class="section-header">
                            <h3><i class="fas fa-file-signature"></i> Documents to Submit</h3>
                        </div>
                        <div class="section-body">
                            <div class="form-row" style="margin-bottom:15px;">
                                <input type="text" class="form-control" id="onboardDoc" placeholder="Document name">
                                <button class="btn btn-outline" onclick="addOnboardDoc()">Add</button>
                            </div>
                            <div class="doc-list" id="onboardDocsList"></div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="nav-buttons">
                    <button class="btn btn-outline" onclick="previousStep()" id="prevBtn" disabled>
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button class="btn btn-primary" onclick="nextStep()" id="nextBtn">
                        Next Step <i class="fas fa-arrow-right"></i>
                    </button>
                    <button class="btn btn-success" onclick="saveConfig()" id="saveBtn" style="display:none;">
                        <i class="fas fa-save"></i> Save Configuration
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Round Modal -->
    <div class="modal" id="roundModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> Add Interview Round/Test</h3>
                <button class="btn-close" onclick="closeRoundModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Round/Test Name</label>
                    <input type="text" class="form-control" id="roundName"
                        placeholder="e.g., Technical Round 1 or Aptitude Test">
                </div>
                <div class="form-group">
                    <label class="form-label">Round Type</label>
                    <select class="form-control" id="roundType" onchange="toggleTestFields()">
                        <option value="technical">Technical Interview</option>
                        <option value="hr">HR Interview</option>
                        <option value="managerial">Managerial Interview</option>
                        <option value="group">Group Discussion</option>
                        <option value="presentation">Presentation</option>
                        <option value="test">📝 Test/Assessment</option>
                    </select>
                </div>

                <div class="test-fields" id="testFields">
                    <h4 style="margin-bottom:15px; color:#4f46e5;">Test Configuration</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Total Marks</label>
                            <input type="number" class="form-control" id="totalMarks" value="100" min="1">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Minimum Passing Marks</label>
                            <input type="number" class="form-control" id="passingMarks" value="40" min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Test Format</label>
                        <select class="form-control" id="testFormat">
                            <option value="online">Online MCQ</option>
                            <option value="offline">Offline Written</option>
                            <option value="coding">Coding Test</option>
                            <option value="essay">Essay/Descriptive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Topics/Syllabus</label>
                        <textarea class="form-control" id="testTopics" rows="2"
                            placeholder="e.g., Aptitude, Core Subjects, Programming"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Round Date</label>
                    <input type="date" class="form-control" id="roundDate">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="roundStartTime" value="09:00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Time</label>
                        <input type="time" class="form-control" id="roundEndTime" value="10:00">
                    </div>
                </div>

                <div id="timeError" style="display: none;">
                    <i class="fas fa-exclamation-circle"></i> <span id="timeErrorMessage">End time must be after start
                        time</span>
                </div>

                <div class="duration-display" id="durationDisplay" style="display: none;">
                    <i class="fas fa-hourglass-half"></i> Duration: <span id="durationMinutes">60</span> minutes
                </div>

                <div class="form-group">
                    <label class="form-label">Panel Members / Invigilators</label>
                    <input type="number" class="form-control" id="panelCount" value="2" min="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Description / Instructions</label>
                    <textarea class="form-control" id="roundDesc" rows="2"
                        placeholder="Additional details about the round/test"></textarea>
                </div>
                <input type="hidden" id="roundDuration" value="60">
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" onclick="closeRoundModal()">Cancel</button>
                <button class="btn btn-primary" onclick="addRound()">Add Round/Test</button>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="toast-box">
        <p id="notifMessage"></p>
    </div>

    <script>
        // ── ALL ORIGINAL JAVASCRIPT (unchanged functionality) ──
        let currentStep = 1;
        let rounds = [];
        let roundId = 1;
        let appDocs = [];
        let onboardDocs = [];
        let selectedRoundsForSelection = [];
        let roundWeightages = {};

        let skills = [];
        let currentSkillFilter = 'all';

        document.addEventListener('DOMContentLoaded', function () {
            let today = new Date();
            let nextWeek = new Date(today);
            nextWeek.setDate(today.getDate() + 7);
            let nextMonth = new Date(today);
            nextMonth.setDate(today.getDate() + 30);

            document.getElementById('appStartDate').valueAsDate = today;
            document.getElementById('appFormTitle').placeholder = "e.g., Teacher";
            document.getElementById('appEndDate').valueAsDate = nextWeek;
            document.getElementById('joiningDate').valueAsDate = nextMonth;

            document.getElementById('roundDate').valueAsDate = nextWeek;

            setTimeout(() => {
                const onlineRadio = document.querySelector('.radio-card');
                if (onlineRadio) {
                    document.querySelectorAll('.radio-card').forEach(el => {
                        el.classList.remove('selected');
                    });
                    onlineRadio.classList.add('selected');
                    document.getElementById('interviewMode').value = 'online';
                    updateInterviewModeFields();
                    setupLocationSelectors();
                }

                const formalDress = document.querySelector('.dress-card');
                if (formalDress) {
                    formalDress.classList.add('selected');
                    document.getElementById('dressCode').value = 'formal';
                }
            }, 100);

            autoCalculateDuration();

            document.getElementById('roundStartTime')?.addEventListener('change', autoCalculateDuration);
            document.getElementById('roundEndTime')?.addEventListener('change', autoCalculateDuration);
            document.getElementById('selectionMethod')?.addEventListener('change', toggleWeightageFields);

            document.getElementById('roundStartTime')?.addEventListener('change', function () {
                document.getElementById('timeError').style.display = 'none';
                autoCalculateDuration();
            });

            document.getElementById('roundEndTime')?.addEventListener('change', function () {
                document.getElementById('timeError').style.display = 'none';
                autoCalculateDuration();
            });

            document.getElementById('totalMarks')?.addEventListener('change', validateMarks);
            document.getElementById('passingMarks')?.addEventListener('change', validateMarks);

            loadConfig();
            setCurrentAcademicYear();
        });

        function toggleTestFields() {
            let roundType = document.getElementById('roundType').value;
            let testFields = document.getElementById('testFields');
            if (roundType === 'test') {
                testFields.classList.add('show');
            } else {
                testFields.classList.remove('show');
            }
        }

        function validateMarks() {
            let total = parseInt(document.getElementById('totalMarks').value) || 0;
            let passing = parseInt(document.getElementById('passingMarks').value) || 0;
            if (passing > total) {
                alert('Passing marks cannot exceed total marks');
                document.getElementById('passingMarks').value = total;
            }
        }

        function showStep(step) {
            document.querySelectorAll('.progress-step').forEach(el => el.classList.remove('active'));
            document.querySelector(`.progress-step:nth-child(${step})`).classList.add('active');
            document.querySelectorAll('.step-pane').forEach(el => el.classList.remove('active'));
            document.getElementById(`step${step}`).classList.add('active');

            const titles = {
                1: '<i class="fas fa-file-alt"></i> Step 1: Application Form',
                2: '<i class="fas fa-users"></i> Step 2: Interview & Tests Setup',
                3: '<i class="fas fa-check-circle"></i> Step 3: Selection Criteria',
                4: '<i class="fas fa-user-graduate"></i> Step 4: Onboarding'
            };
            document.getElementById('stepTitle').innerHTML = titles[step];

            currentStep = step;
            updateNavButtons();

            if (step === 3) {
                renderRoundCheckboxes();
            }
        }

        function nextStep() {
            if (currentStep < 4) showStep(currentStep + 1);
        }

        function previousStep() {
            if (currentStep > 1) showStep(currentStep - 1);
        }

        function updateNavButtons() {
            document.getElementById('prevBtn').disabled = currentStep === 1;
            if (currentStep === 4) {
                document.getElementById('nextBtn').style.display = 'none';
                document.getElementById('saveBtn').style.display = 'inline-flex';
            } else {
                document.getElementById('nextBtn').style.display = 'inline-flex';
                document.getElementById('saveBtn').style.display = 'none';
            }
        }

        function selectRadio(element, value, event) {
            if (event) { event.preventDefault(); event.stopPropagation(); }
            document.querySelectorAll('.radio-card').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('interviewMode').value = value;
            updateInterviewModeFields();
        }

        function updateInterviewModeFields() {
            const mode = document.getElementById('interviewMode')?.value || 'online';
            const offline = document.getElementById('offlineLocationFields');
            const online = document.getElementById('onlineMeetingFields');
            if (offline) offline.style.display = mode === 'online' ? 'none' : 'block';
            if (online) online.style.display = mode === 'offline' ? 'none' : 'block';
        }

        function resetSelect(id, label) {
            const select = document.getElementById(id);
            if (!select) return;
            select.innerHTML = `<option value="">${label}</option>`;
            select.value = '';
            select.disabled = true;
        }

        async function loadLocationOptions(url, payload, selectId, label, valueKey, textKey) {
            const select = document.getElementById(selectId);
            if (!select) return;
            select.innerHTML = '<option value="">Loading...</option>';
            select.disabled = true;
            const response = await fetch(url, {
                method: payload ? 'POST' : 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: payload ? JSON.stringify(payload) : undefined
            });
            const data = await response.json();
            if (!data.success) throw new Error(data.message || 'Unable to load locations');
            const rows = data[valueKey] || [];
            select.innerHTML = `<option value="">${label}</option>` + rows.map(row =>
                `<option value="${row.id}">${row[textKey] || row.name || row.room_name || row.floor_name || row.block_name}</option>`
            ).join('');
            select.disabled = false;
        }

        function setupLocationSelectors() {
            const type = document.getElementById('offlineLocationType');
            const existing = document.getElementById('existingLocationFields');
            const manual = document.getElementById('manualLocationFields');
            const toggle = () => {
                const isManual = type?.value === 'manual';
                if (existing) existing.style.display = isManual ? 'none' : 'block';
                if (manual) manual.style.display = isManual ? 'block' : 'none';
            };
            type?.addEventListener('change', toggle);
            toggle();

            document.getElementById('venueBuildingId')?.addEventListener('change', async event => {
                resetSelect('venueBlockId', 'Select Block');
                resetSelect('venueFloorId', 'Select Floor');
                resetSelect('venueRoomId', 'Select Room');
                if (!event.target.value) return;
                try { await loadLocationOptions('/ajax/get-blocks-by-building', { building_id: event.target.value }, 'venueBlockId', 'Select Block', 'blocks', 'block_name'); }
                catch (error) { showToastNotification(error.message); }
            });
            document.getElementById('venueBlockId')?.addEventListener('change', async event => {
                resetSelect('venueFloorId', 'Select Floor');
                resetSelect('venueRoomId', 'Select Room');
                if (!event.target.value) return;
                try { await loadLocationOptions('/ajax/get-floors-by-block', { block_id: event.target.value }, 'venueFloorId', 'Select Floor', 'floors', 'floor_name'); }
                catch (error) { showToastNotification(error.message); }
            });
            document.getElementById('venueFloorId')?.addEventListener('change', async event => {
                resetSelect('venueRoomId', 'Select Room');
                if (!event.target.value) return;
                try {
                    await loadLocationOptions('/ajax/get-rooms-by-floor', { floor_id: event.target.value, block_id: document.getElementById('venueBlockId').value }, 'venueRoomId', 'Select Room', 'rooms', 'room_name');
                } catch (error) { showToastNotification(error.message); }
            });
            loadLocationOptions('/ajax/get-buildings', null, 'venueBuildingId', 'Select Building', 'buildings', 'building_name')
                .catch(error => showToastNotification(error.message));
        }

        function openRoundModal() {
            document.getElementById('roundModal').classList.add('show');
            clearRoundForm();
            let nextWeek = new Date();
            nextWeek.setDate(nextWeek.getDate() + 7);
            document.getElementById('roundDate').valueAsDate = nextWeek;
        }

        function selectDress(element, value, event) {
            if (event) { event.preventDefault(); event.stopPropagation(); }
            document.querySelectorAll('.dress-card').forEach(el => el.classList.remove('selected'));
            element.classList.add('selected');
            document.getElementById('dressCode').value = value;
        }

        function addDocument() {
            let name = document.getElementById('docName').value;
            let type = document.getElementById('docType').value;
            let spec = document.getElementById('docSpec').value;
            if (!name) { alert('Please enter document name'); return; }
            appDocs.push({ id: Date.now(), name: name, type: type, specification: spec || 'No specification' });
            document.getElementById('docName').value = '';
            document.getElementById('docSpec').value = 'PDF, DOC, DOCX, JPG, PNG, 5MB max';
            renderDocs();
        }

        function renderDocs() {
            let html = '';
            appDocs.forEach(doc => {
                html += `<div class="doc-item" style="flex-direction: column; align-items: stretch; margin-bottom: 12px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div class="doc-info">
                                            <i class="fas fa-file-alt" style="color:#4f46e5;"></i>
                                            <span style="font-weight: 600;">${doc.name}</span>
                                            <span class="doc-tag tag-${doc.type}">${doc.type}</span>
                                        </div>
                                        <button class="btn-icon delete" onclick="removeDoc(${doc.id})"><i class="fas fa-trash"></i></button>
                                    </div>
                                    <div style="font-size:0.85rem; margin-top:8px; padding:8px 12px; background:#f0f9ff; border-radius:8px; color:#0369a1;">
                                        <i class="fas fa-info-circle" style="margin-right:5px;"></i> ${doc.specification}
                                    </div>
                                </div>`;
            });
            document.getElementById('documentsList').innerHTML = html || '<p style="color:#94a3b8; text-align:center; padding:20px;">No documents added yet</p>';
        }

        function removeDoc(id) {
            appDocs = appDocs.filter(doc => doc.id !== id);
            renderDocs();
        }

        function addOnboardDoc() {
            let name = document.getElementById('onboardDoc').value;
            if (!name) return;
            onboardDocs.push({ id: Date.now(), name });
            document.getElementById('onboardDoc').value = '';
            renderOnboardDocs();
        }

        function renderOnboardDocs() {
            let html = '';
            onboardDocs.forEach(doc => {
                html += `<div class="doc-item">
                                    <div><i class="fas fa-file-signature" style="color:#4f46e5;"></i> ${doc.name}</div>
                                    <button class="btn-icon delete" onclick="removeOnboardDoc(${doc.id})"><i class="fas fa-trash"></i></button>
                                </div>`;
            });
            document.getElementById('onboardDocsList').innerHTML = html || '<p style="color:#94a3b8; text-align:center;">No documents added</p>';
        }

        function removeOnboardDoc(id) {
            onboardDocs = onboardDocs.filter(d => d.id !== id);
            renderOnboardDocs();
        }

        function addRound() {
            document.getElementById('timeError').style.display = 'none';
            let name = document.getElementById('roundName').value;
            let type = document.getElementById('roundType').value;
            let roundDate = document.getElementById('roundDate').value;
            let startTime = document.getElementById('roundStartTime').value;
            let endTime = document.getElementById('roundEndTime').value;
            let panel = document.getElementById('panelCount').value;
            let desc = document.getElementById('roundDesc').value;

            if (!name) { alert('Please enter round/test name'); return; }
            if (!roundDate) { alert('Please select round date'); return; }
            if (!validateTime()) { return; }

            let duration = parseInt(document.getElementById('roundDuration').value);
            let today = new Date(); today.setHours(0, 0, 0, 0);
            let selectedDate = new Date(roundDate);
            if (selectedDate < today) {
                if (!confirm('Selected date is in the past. Are you sure you want to continue?')) return;
            }

            let typeLabels = {
                technical: 'Technical Interview', hr: 'HR Interview', managerial: 'Managerial Interview',
                group: 'Group Discussion', presentation: 'Presentation', test: '📝 Test/Assessment'
            };

            let round = { id: roundId++, name, type, typeLabel: typeLabels[type], roundDate, startTime, endTime, duration, panel, desc };

            if (type === 'test') {
                let totalMarks = parseInt(document.getElementById('totalMarks').value) || 100;
                let passingMarks = parseInt(document.getElementById('passingMarks').value) || 40;
                if (passingMarks > totalMarks) { alert('Passing marks cannot exceed total marks'); return; }
                round.totalMarks = totalMarks;
                round.passingMarks = passingMarks;
                round.testFormat = document.getElementById('testFormat').value;
                round.testTopics = document.getElementById('testTopics').value;
            }

            rounds.push(round);
            rounds.sort((a, b) => {
                let dateA = new Date(`${a.roundDate}T${a.startTime}`);
                let dateB = new Date(`${b.roundDate}T${b.startTime}`);
                return dateA - dateB;
            });

            clearRoundForm();
            closeRoundModal();
            renderRounds();
            if (currentStep === 3) renderRoundCheckboxes();
            showToastNotification('Round/Test added successfully!');
        }

        function closeRoundModal() {
            document.getElementById('roundModal').classList.remove('show');
            clearRoundForm();
        }

        document.getElementById('roundModal').addEventListener('click', function (e) {
            if (e.target === this) closeRoundModal();
        });

        function renderRounds() {
            let html = '';
            rounds.forEach(r => {
                let testDetails = '';
                if (r.type === 'test') {
                    testDetails = `<div class="test-details">
                                            <span><i class="fas fa-star"></i> Marks: ${r.totalMarks} (Pass: ${r.passingMarks})</span>
                                            <span><i class="fas fa-laptop"></i> Format: ${r.testFormat}</span>
                                            <span><i class="fas fa-hourglass-half"></i> Duration: ${r.duration} min</span>
                                            ${r.testTopics ? `<span><i class="fas fa-book"></i> ${r.testTopics}</span>` : ''}
                                        </div>`;
                } else {
                    testDetails = `<div class="test-details" style="background:#f1f5f9; color:#475569;">
                                            <span><i class="fas fa-hourglass-half"></i> Duration: ${r.duration} min</span>
                                        </div>`;
                }

                html += `<div class="round-item">
                                    <div class="round-info">
                                        <h4>${r.name} <span>${r.typeLabel}</span></h4>
                                        <div class="round-meta">
                                            <span><i class="far fa-calendar"></i> ${r.roundDate}</span>
                                            <span><i class="far fa-clock"></i> ${r.startTime || '09:00'} - ${r.endTime || '10:00'}</span>
                                            <span><i class="fas fa-users"></i> ${r.panel} ${r.type === 'test' ? 'invigilators' : 'panel members'}</span>
                                        </div>
                                        ${testDetails}
                                        ${r.desc ? `<p style="font-size:0.85rem; margin-top:6px;">${r.desc}</p>` : ''}
                                    </div>
                                    <div class="round-actions">
                                        <button class="btn-icon edit" onclick="editRound(${r.id})"><i class="fas fa-edit"></i></button>
                                        <button class="btn-icon delete" onclick="deleteRound(${r.id})"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>`;
            });
            document.getElementById('roundsList').innerHTML = html || '<p style="color:#94a3b8; text-align:center; padding:20px;">No rounds/tests added yet</p>';
        }

        function renderRoundCheckboxes() {
            let html = '';
            if (rounds.length === 0) {
                html = '<p style="color:#94a3b8; text-align:center; padding:20px;">No rounds/tests added yet. Please add rounds in Interview step first.</p>';
            } else {
                rounds.forEach(r => {
                    let isChecked = selectedRoundsForSelection.includes(r.id) ? 'checked' : '';
                    let testInfo = r.type === 'test' ? ` (${r.totalMarks} marks, Pass: ${r.passingMarks})` : '';
                    html += `<label class="checkbox-item">
                                        <input type="checkbox" value="${r.id}" ${isChecked} onchange="toggleRoundSelection(${r.id}, this.checked)">
                                        <div class="round-details">
                                            <span class="round-name">${r.name}${testInfo}</span>
                                            <span class="round-type">${r.typeLabel}</span>
                                            <div style="font-size:0.8rem; color:#94a3b8; margin-top:4px;">
                                                <span><i class="far fa-calendar"></i> ${r.roundDate}</span>
                                                <span><i class="far fa-clock"></i> ${r.startTime || '09:00'} - ${r.endTime || '10:00'} (${r.duration} min)</span>
                                            </div>
                                        </div>
                                    </label>`;
                });
            }
            document.getElementById('roundsCheckboxList').innerHTML = html;
        }

        function quickAddSkill(name, level, type) {
            if (skills.some(s => s.name.toLowerCase() === name.toLowerCase())) {
                showToastNotification(`"${name}" already exists`);
                return;
            }
            skills.push({ id: Date.now() + Math.random(), name, level, type, required: level === 'mandatory' });
            renderSkills();
            updateSkillCounts();
            showToastNotification(`Added "${name}"`);
        }

        function autoCalculateDuration() {
            let startTime = document.getElementById('roundStartTime')?.value;
            let endTime = document.getElementById('roundEndTime')?.value;
            if (startTime && endTime) {
                let today = new Date().toISOString().split('T')[0];
                let startDateTime = new Date(`${today}T${startTime}`);
                let endDateTime = new Date(`${today}T${endTime}`);
                let durationMs = endDateTime - startDateTime;
                let durationMinutes = Math.round(durationMs / (1000 * 60));
                if (durationMinutes > 0) {
                    document.getElementById('roundDuration').value = durationMinutes;
                    document.getElementById('durationMinutes').innerText = durationMinutes;
                    document.getElementById('durationDisplay').style.display = 'block';
                    document.getElementById('timeError').style.display = 'none';
                } else {
                    document.getElementById('roundDuration').value = 0;
                    document.getElementById('durationMinutes').innerText = '0';
                    document.getElementById('durationDisplay').style.display = 'none';
                }
            }
        }

        function toggleRoundSelection(roundId, isChecked) {
            if (isChecked) {
                if (!selectedRoundsForSelection.includes(roundId)) selectedRoundsForSelection.push(roundId);
            } else {
                selectedRoundsForSelection = selectedRoundsForSelection.filter(id => id !== roundId);
            }
            if (document.getElementById('selectionMethod').value === 'weighted') renderWeightageFields();
        }

        function toggleWeightageFields() {
            let method = document.getElementById('selectionMethod').value;
            let container = document.getElementById('weightageContainer');
            if (method === 'weighted') {
                container.style.display = 'block';
                renderWeightageFields();
            } else {
                container.style.display = 'none';
            }
        }

        function renderWeightageFields() {
            let html = '';
            let selectedRounds = rounds.filter(r => selectedRoundsForSelection.includes(r.id));
            selectedRounds.forEach((round) => {
                let weightage = roundWeightages[round.id] || Math.floor(100 / selectedRounds.length);
                html += `<div style="display:flex; gap:10px; margin-bottom:10px; align-items:center;">
                                    <span style="flex:1;">${round.name}</span>
                                    <input type="number" class="form-control" style="width:80px;" value="${weightage}" min="0" max="100" onchange="updateWeightage(${round.id}, this.value)">
                                    <span>%</span>
                                </div>`;
            });
            document.getElementById('weightageFields').innerHTML = html;
        }

        function updateWeightage(roundId, value) {
            roundWeightages[roundId] = parseInt(value) || 0;
        }

        function deleteRound(id) {
            if (confirm('Delete this round/test?')) {
                rounds = rounds.filter(r => r.id !== id);
                selectedRoundsForSelection = selectedRoundsForSelection.filter(rId => rId !== id);
                delete roundWeightages[id];
                renderRounds();
                if (currentStep === 3) {
                    renderRoundCheckboxes();
                    if (document.getElementById('selectionMethod').value === 'weighted') renderWeightageFields();
                }
            }
        }

        function validateTime() {
            let startTime = document.getElementById('roundStartTime').value;
            let endTime = document.getElementById('roundEndTime').value;
            let timeError = document.getElementById('timeError');
            let errorMessage = document.getElementById('timeErrorMessage');
            if (!startTime || !endTime) {
                errorMessage.innerText = 'Please select both start and end time';
                timeError.style.display = 'block';
                document.getElementById('durationDisplay').style.display = 'none';
                return false;
            }
            let today = new Date().toISOString().split('T')[0];
            let startDateTime = new Date(`${today}T${startTime}`);
            let endDateTime = new Date(`${today}T${endTime}`);
            let durationMs = endDateTime - startDateTime;
            let durationMinutes = Math.round(durationMs / (1000 * 60));
            if (durationMinutes <= 0) {
                errorMessage.innerText = 'Invalid time';
                timeError.style.display = 'block';
                document.getElementById('durationDisplay').style.display = 'none';
                return false;
            }
            if (durationMinutes > 480) {
                if (!confirm('Duration is more than 8 hours. Are you sure you want to continue?')) return false;
            }
            timeError.style.display = 'none';
            document.getElementById('roundDuration').value = durationMinutes;
            document.getElementById('durationMinutes').innerText = durationMinutes;
            document.getElementById('durationDisplay').style.display = 'block';
            return true;
        }

        function clearRoundForm() {
            document.getElementById('roundName').value = '';
            document.getElementById('roundDesc').value = '';
            document.getElementById('roundDate').value = '';
            document.getElementById('roundStartTime').value = '09:00';
            document.getElementById('roundEndTime').value = '10:00';
            document.getElementById('panelCount').value = '2';
            document.getElementById('totalMarks').value = '100';
            document.getElementById('passingMarks').value = '40';
            document.getElementById('testTopics').value = '';
            document.getElementById('testFields').classList.remove('show');
            document.getElementById('timeError').style.display = 'none';
            document.getElementById('durationDisplay').style.display = 'none';
            document.getElementById('roundType').value = 'technical';
        }

        function editRound(id) {
            let round = rounds.find(r => r.id === id);
            if (round) {
                document.getElementById('roundName').value = round.name;
                document.getElementById('roundType').value = round.type;
                if (round.type === 'test') {
                    document.getElementById('testFields').classList.add('show');
                    document.getElementById('totalMarks').value = round.totalMarks || 100;
                    document.getElementById('passingMarks').value = round.passingMarks || 40;
                    document.getElementById('testFormat').value = round.testFormat || 'online';
                    document.getElementById('testTopics').value = round.testTopics || '';
                } else {
                    document.getElementById('testFields').classList.remove('show');
                }
                document.getElementById('roundStartTime').value = round.startTime || '09:00';
                document.getElementById('roundEndTime').value = round.endTime || '10:00';
                document.getElementById('roundDuration').value = round.duration || 60;
                document.getElementById('durationMinutes').innerText = round.duration || 60;
                document.getElementById('panelCount').value = round.panel;
                document.getElementById('roundDesc').value = round.desc || '';
                document.getElementById('roundDate').value = round.roundDate || '';
                document.getElementById('durationDisplay').style.display = 'block';
                document.getElementById('timeError').style.display = 'none';
                rounds = rounds.filter(r => r.id !== id);
                selectedRoundsForSelection = selectedRoundsForSelection.filter(rId => rId !== id);
                delete roundWeightages[id];
                renderRounds();
                if (currentStep === 3) renderRoundCheckboxes();
                document.getElementById('roundModal').classList.add('show');
            }
        }

        function getDressCode() {
            return document.getElementById('dressCode')?.value || 'formal';
        }

        function addSkill() {
            let name = document.getElementById('skillName').value.trim();
            let level = document.getElementById('skillLevel').value;
            let type = document.getElementById('skillType').value;
            if (!name) { alert('Please enter a skill name'); return; }
            if (skills.some(s => s.name.toLowerCase() === name.toLowerCase())) {
                alert(`"${name}" already exists in the skills list`);
                return;
            }
            skills.push({ id: Date.now() + Math.random(), name, level, type, required: level === 'mandatory' });
            document.getElementById('skillName').value = '';
            renderSkills();
            updateSkillCounts();
            showToastNotification(`Added "${name}"`);
        }

        function clearAllSkills() {
            if (skills.length > 0 && confirm('Remove all skills?')) {
                skills = [];
                renderSkills();
                updateSkillCounts();
                showToastNotification('All skills cleared');
            }
        }

        function renderSkills() {
            let filteredSkills = skills;
            if (currentSkillFilter !== 'all') {
                filteredSkills = skills.filter(s => s.type === currentSkillFilter);
            }
            if (filteredSkills.length === 0) {
                document.getElementById('skillsList').innerHTML = `
                            <div style="text-align: center; padding: 40px; color: #6b7280; background: #f9fafb; border-radius: 16px;">
                                <i class="fas fa-tasks" style="font-size: 2.5rem; margin-bottom: 15px; opacity: 0.3;"></i>
                                <p style="font-size: 1rem; margin-bottom: 5px;">No ${currentSkillFilter !== 'all' ? currentSkillFilter : ''} skills added yet</p>
                                <p style="font-size: 0.85rem;">Add skills using the input above</p>
                            </div>`;
                return;
            }

            let html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 12px;">';
            filteredSkills.forEach(skill => {
                let levelColor = { 'beginner': '#6b7280', 'intermediate': '#3b82f6', 'advanced': '#8b5cf6', 'expert': '#10b981', 'mandatory': '#ef4444' }[skill.level] || '#6b7280';
                let levelLabel = { 'beginner': 'Beginner', 'intermediate': 'Intermediate', 'advanced': 'Advanced', 'expert': 'Expert', 'mandatory': 'Mandatory' }[skill.level] || skill.level;
                let typeIcon = { 'technical': 'fa-code', 'soft': 'fa-comments', 'language': 'fa-language', 'certification': 'fa-certificate' }[skill.type] || 'fa-tag';
                let iconBg = { 'technical': '#e0e7ff', 'soft': '#dbeafe', 'language': '#fef3c7', 'certification': '#dcfce7' }[skill.type] || '#e0e7ff';
                let iconColor = { 'technical': '#4f46e5', 'soft': '#3b82f6', 'language': '#f59e0b', 'certification': '#10b981' }[skill.type] || '#4f46e5';

                html += `
                            <div class="skill-card">
                                <div style="display: flex; align-items: center; gap: 14px;">
                                    <div style="width: 48px; height: 48px; background: ${iconBg}; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas ${typeIcon}" style="color: ${iconColor}; font-size: 1.3rem;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 6px; font-size: 1rem;">${skill.name}</div>
                                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                            <span style="background: ${levelColor}15; color: ${levelColor}; padding: 4px 14px; border-radius: 100px; font-size: 0.7rem; font-weight: 600;">${levelLabel}</span>
                                            <span style="background: #f1f5f9; color: #475569; padding: 4px 14px; border-radius: 100px; font-size: 0.7rem; font-weight: 500; text-transform: capitalize;">${skill.type}</span>
                                        </div>
                                    </div>
                                </div>
                                <button onclick="deleteSkill(${skill.id})" style="width: 38px; height: 38px; border-radius: 12px; border: none; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                    <i class="fas fa-trash" style="font-size: 0.9rem;"></i>
                                </button>
                            </div>`;
            });
            html += '</div>';
            document.getElementById('skillsList').innerHTML = html;
        }

        function updateSkillCounts() {
            document.getElementById('count-all').innerText = skills.length;
            document.getElementById('count-technical').innerText = skills.filter(s => s.type === 'technical').length;
            document.getElementById('count-soft').innerText = skills.filter(s => s.type === 'soft').length;
            document.getElementById('count-language').innerText = skills.filter(s => s.type === 'language').length;
            document.getElementById('totalSkillsCount').innerText = `${skills.length} skills`;
            document.getElementById('totalSkillsCount-footer').innerText = `${skills.length} skills added`;
        }

        function filterSkills(type) {
            currentSkillFilter = type;
            document.querySelectorAll('.skill-tab').forEach(tab => {
                tab.classList.remove('active');
                tab.style.background = 'transparent';
                tab.style.color = '#64748b';
                tab.style.fontWeight = 'normal';
                tab.style.boxShadow = 'none';
            });
            const activeTab = document.getElementById(`tab-${type}`);
            activeTab.classList.add('active');
            activeTab.style.background = 'white';
            activeTab.style.color = '#4f46e5';
            activeTab.style.fontWeight = '500';
            activeTab.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
            renderSkills();
        }

        function deleteSkill(id) {
            if (confirm('Remove this skill?')) {
                skills = skills.filter(s => s.id !== id);
                renderSkills();
                updateSkillCounts();
                showToastNotification('Skill removed');
            }
        }

        function getSkills() {
            return skills.map(skill => ({
                name: skill.name,
                level: skill.level,
                type: skill.type,
                required: skill.level === 'mandatory'
            }));
        }

        function saveConfig() {
            setCurrentAcademicYear();
            let academicYear = document.getElementById('academicYearSelect')?.value || '';
            let departmentId = document.getElementById('departmentSelect')?.value || '';
            if (!academicYear) { showToastNotification('Please select an academic year'); return; }
            if (!departmentId) { showToastNotification('Please select a department'); return; }

            const locationType = document.getElementById('offlineLocationType')?.value || 'existing';
            const buildingSelect = document.getElementById('venueBuildingId');
            const blockSelect = document.getElementById('venueBlockId');
            const floorSelect = document.getElementById('venueFloorId');
            const roomSelect = document.getElementById('venueRoomId');
            const venueInput = document.getElementById('manualVenueBuilding');
            const roomInput = document.getElementById('manualVenueRoom');
            const addressInput = document.getElementById('venueAddress');
            let reportingTimeInput = document.querySelector('#joiningDate')?.closest('.section-card')?.querySelector('input[type="time"]');
            let reportingVenueInput = document.querySelector('input[value="HR Office"]');

            let roundNamesMap = {};
            rounds.forEach(round => { roundNamesMap[round.id] = round.name; });
            let selectedRoundNames = selectedRoundsForSelection.map(id => roundNamesMap[id]).filter(name => name);

            let config = {
                academic_year: academicYear,
                department_id: departmentId,
                steps: {
                    application: {
                        enabled: document.getElementById('enableStep1')?.checked || false,
                        form_title: document.getElementById('appFormTitle')?.value || '',
                        start_date: document.getElementById('appStartDate')?.value || '',
                        end_date: document.getElementById('appEndDate')?.value || '',
                        max_applications: parseInt(document.querySelector('input[value="500"]')?.value) || 500,
                        docs: appDocs.map(doc => ({ id: doc.id, name: String(doc.name), type: String(doc.type), specification: String(doc.specification || '') })),
                        skills: { enabled: document.getElementById('enableSkills')?.checked || true, list: getSkills() }
                    },
                    interview: {
                        enabled: document.getElementById('enableStep2')?.checked || false,
                        mode: document.getElementById('interviewMode')?.value || 'online',
                        dressCode: getDressCode(),
                        customDressCode: document.getElementById('customDressCode')?.value || '',
                        rounds: rounds.map(round => ({ id: round.id, name: String(round.name), type: String(round.type), typeLabel: String(round.typeLabel), roundDate: String(round.roundDate), startTime: String(round.startTime), endTime: String(round.endTime), duration: parseInt(round.duration) || 0, panel: parseInt(round.panel) || 0, desc: String(round.desc || ''), ...(round.type === 'test' ? { totalMarks: parseInt(round.totalMarks) || 0, passingMarks: parseInt(round.passingMarks) || 0, testFormat: String(round.testFormat || ''), testTopics: String(round.testTopics || '') } : {}) })),
                        location: { type: locationType, venue: locationType === 'manual' ? (venueInput?.value || '') : (buildingSelect?.selectedOptions[0]?.text || ''), room: locationType === 'manual' ? (roomInput?.value || '') : (roomSelect?.selectedOptions[0]?.text || ''), address: addressInput?.value || '', building_id: locationType === 'existing' ? (buildingSelect?.value || null) : null, block_id: locationType === 'existing' ? (blockSelect?.value || null) : null, floor_id: locationType === 'existing' ? (floorSelect?.value || null) : null, room_id: locationType === 'existing' ? (roomSelect?.value || null) : null },
                        meeting_link: document.getElementById('onlineMeetLink')?.value.trim() || ''
                    },
                    selection: {
                        enabled: document.getElementById('enableStep3')?.checked || false,
                        selectedRounds: selectedRoundNames,
                        method: document.getElementById('selectionMethod')?.value || 'all',
                        weightages: Object.fromEntries(Object.entries(roundWeightages).map(([key, value]) => [parseInt(key), parseInt(value) || 0])),
                        offerLetter: document.getElementById('offerLetterGen')?.value || 'auto',
                        acceptanceDays: parseInt(document.getElementById('acceptanceDays')?.value) || 7,
                        sendRejection: document.getElementById('sendRejectionEmail')?.checked || false
                    },
                    onboarding: {
                        enabled: document.getElementById('enableStep4')?.checked || false,
                        joiningDate: document.getElementById('joiningDate')?.value || '',
                        reportingTime: reportingTimeInput?.value || '09:00',
                        reportingVenue: reportingVenueInput?.value || 'HR Office',
                        induction: document.getElementById('inductionProgram')?.value || 'Mandatory',
                        inductionDays: parseInt(document.getElementById('inductionDays')?.value) || 3,
                        trainingMonths: parseInt(document.getElementById('trainingMonths')?.value) || 6,
                        docs: onboardDocs.map(doc => ({ id: doc.id, name: String(doc.name) }))
                    }
                }
            };

            // localStorage.setItem('interviewConfig', JSON.stringify(config));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) { console.error('CSRF token not found'); showToastNotification('Error: CSRF token not found'); return; }

            fetch('/interview-configuration/save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify(config)
            })
                .then(response => { if (!response.ok) { return response.json().then(err => { throw err; }); } return response.json(); })
                .then(data => {
                    if (data.success) { showToastNotification(data.message || 'Configuration saved successfully!'); }
                    else { showToastNotification('Error: ' + (data.message || 'Unknown error')); }
                })
                .catch(error => {
                    console.error('Error details:', error);
                    if (error.message === 'Failed to fetch' || error.name === 'TypeError') {
                        showToastNotification('Network error: Please check your internet connection and try again');
                    } else {
                        showToastNotification('Error saving configuration: ' + (error.message || 'Unknown error'));
                    }
                });
        }

        function showToastNotification(msg) {
            let toast = document.getElementById('notification');
            let message = document.getElementById('notifMessage');
            message.innerText = msg;
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 3000);
        }

        function setCurrentAcademicYear() {
            const academicYearSelect = document.getElementById('academicYearSelect');
            if (academicYearSelect && !academicYearSelect.value) {
                const currentDate = new Date();
                const currentYear = currentDate.getFullYear();
                const nextYear = currentYear + 1;
                const currentYearString = `${currentYear}-${nextYear}`;
                const options = Array.from(academicYearSelect.options);
                const matchingOption = options.find(opt => opt.value === currentYearString);
                if (matchingOption) { academicYearSelect.value = currentYearString; }
                else if (options.length > 0) {
                    const firstValidOption = options.find(opt => opt.value !== '');
                    if (firstValidOption) { academicYearSelect.value = firstValidOption.value; }
                }
            }
        }

        function loadConfig() {
            let saved = localStorage.getItem('interviewConfig');
            if (saved) {
                let config = JSON.parse(saved);
                if (config.steps?.interview?.rounds) {
                    rounds = config.steps.interview.rounds;
                    roundId = Math.max(...rounds.map(r => r.id), 0) + 1;
                    renderRounds();
                }
                if (config.steps?.selection?.selectedRounds) {
                    let savedSelectedRounds = config.steps.selection.selectedRounds;
                    if (savedSelectedRounds.length > 0) {
                        if (typeof savedSelectedRounds[0] === 'string') {
                            let roundNameToId = {};
                            rounds.forEach(round => { roundNameToId[round.name] = round.id; });
                            selectedRoundsForSelection = savedSelectedRounds.map(name => roundNameToId[name]).filter(id => id !== undefined);
                        } else {
                            selectedRoundsForSelection = savedSelectedRounds;
                        }
                    }
                }
                if (config.steps?.selection?.weightages) { roundWeightages = config.steps.selection.weightages; }
                if (config.steps?.interview?.mode) {
                    let mode = config.steps.interview.mode;
                    document.getElementById('interviewMode').value = mode;
                    document.querySelectorAll('.radio-card').forEach(el => {
                        el.classList.remove('selected');
                        if (el.innerText.toLowerCase().includes(mode)) { el.classList.add('selected'); }
                    });
                    updateInterviewModeFields();
                }
                if (config.steps?.interview?.meeting_link) { document.getElementById('onlineMeetLink').value = config.steps.interview.meeting_link; }
                if (config.steps?.interview?.location) {
                    const location = config.steps.interview.location;
                    const locationType = document.getElementById('offlineLocationType');
                    if (locationType && location.type) locationType.value = location.type;
                    if (document.getElementById('manualVenueBuilding')) document.getElementById('manualVenueBuilding').value = location.venue || '';
                    if (document.getElementById('manualVenueRoom')) document.getElementById('manualVenueRoom').value = location.room || '';
                    if (document.getElementById('venueAddress')) document.getElementById('venueAddress').value = location.address || '';
                }
                if (config.steps?.interview?.dressCode) {
                    let dress = config.steps.interview.dressCode;
                    document.querySelectorAll('.dress-card').forEach(el => {
                        if (el.querySelector('.dress-name').innerText === dress) { el.classList.add('selected'); }
                        else if (dress.length > 20) { document.getElementById('customDressCode').value = dress; }
                    });
                }
                if (config.steps?.application?.docs) { appDocs = config.steps.application.docs; renderDocs(); }
                if (config.steps?.onboarding?.docs) { onboardDocs = config.steps.onboarding.docs; renderOnboardDocs(); }
                if (config.steps?.selection?.method) {
                    document.getElementById('selectionMethod').value = config.steps.selection.method;
                    if (config.steps.selection.method === 'weighted') { toggleWeightageFields(); }
                }
                skills = [];
                renderSkills();
                updateSkillCounts();
                if (config.steps?.application?.skills?.enabled !== undefined) {
                    document.getElementById('enableSkills').checked = config.steps.application.skills.enabled;
                }
            }
        }
    </script>

@endsection