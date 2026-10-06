@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

    <style>
        /* Modern Header */
        .page-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.15);
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .header-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: white;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
        }

        .header-title {
            color: white;
            margin: 0;
        }

        .header-title h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .header-title p {
            /* color: #94a3b8; */
            margin: 4px 0 0 0;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: white;
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-header:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .btn-header i {
            font-size: 0.9rem;
        }

        .stats-badge {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            color: #94a3b8;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .stats-badge strong {
            color: white;
            font-weight: 700;
        }

        .glass-card {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
            padding: 2rem;
            max-width: 1024px;
            margin: 0 auto;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            border-color: rgba(37, 99, 235, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
        }

        /* Persona Bar */
        .persona-bar {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 12px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .persona-bar .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .persona-bar .user-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .persona-bar .user-details .name {
            font-weight: 700;
            font-size: 0.95rem;
            color: white;
        }

        .persona-bar .user-details .role {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .persona-bar .persona-selector {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.3rem 1rem 0.3rem 1.2rem;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .persona-bar .persona-selector label {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .persona-bar select {
            padding: 0.4rem 1rem;
            border-radius: 30px;
            border: 1px solid #475569;
            background: #334155;
            color: white;
            outline: none;
            font-size: 0.85rem;
            cursor: pointer;
            font-weight: 600;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.8rem center;
            padding-right: 2.5rem;
        }

        .persona-bar select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        /* Form Elements */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-group label .required {
            color: #ef4444;
            font-weight: 700;
        }

        .form-group label .field-icon {
            color: #94a3b8;
            font-size: 0.7rem;
        }

        .form-control {
            width: 100%;
            padding: 0.7rem 1.1rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.9rem;
            background: white;
            outline: none;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:read-only {
            background: #f8fafc;
            cursor: not-allowed;
        }

        textarea.form-control {
            border-radius: 12px;
            min-height: 80px;
            resize: vertical;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        .form-hint {
            color: #94a3b8;
            font-size: 0.7rem;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-hint i {
            font-size: 0.65rem;
        }

        .range-selector-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 10px;
            margin-bottom: 0.5rem;
        }

        .range-btn {
            padding: 0.8rem;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 700;
            color: #475569;
            font-size: 0.8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .range-btn:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .range-btn.active {
            background: #eff6ff;
            border-color: #2563eb;
            color: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
        }

        .range-btn i {
            font-size: 1.2rem;
        }

        .range-btn .range-label {
            font-size: 0.7rem;
            font-weight: 600;
        }

        .rule-pill-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .rule-pill {
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e2e8f0;
        }

        .rule-pill.accent {
            background: #fffbeb;
            color: #d97706;
            border-color: #fde68a;
        }

        .rule-pill.success {
            background: #f0fdf4;
            color: #059669;
            border-color: #bbf7d0;
        }

        .rule-pill.info {
            background: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white !important;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.25);
            font-size: 0.95rem;
            justify-content: center;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 6px 24px rgba(37, 99, 235, 0.35);
            transform: translateY(-2px);
        }

        .btn-outline-lg {
            background: white;
            border: 1.5px solid #e2e8f0;
            color: #475569;
            padding: 0.8rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline-lg:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        .error-box {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            color: #b91c1c;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            display: none;
            align-items: center;
            gap: 10px;
        }

        .error-box i {
            font-size: 1.2rem;
            color: #ef4444;
        }

        .file-upload-area {
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            background: #fafcfd;
            cursor: pointer;
            transition: all 0.2s;
        }

        .file-upload-area:hover {
            background: #eff6ff;
            border-color: #2563eb;
            border-style: solid;
        }

        .file-upload-area i {
            font-size: 1rem;
            color: #2563eb;
        }

        .file-upload-area .upload-text {
            font-weight: 700;
            font-size: 0.85rem;
            color: #1e293b;
        }

        .file-upload-area .upload-subtext {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 4px;
        }

        .file-upload-area .uploaded-status {
            color: #10b981;
            font-weight: 700;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 14px;
            border-top: 1.5px solid #edf2f7;
            padding-top: 1.8rem;
            margin-top: 1.5rem;
        }

        /* Custom Fields */
        .custom-field-group {
            background: #fafcfd;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            margin-bottom: 0.75rem;
        }

        .custom-field-group label {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #475569;
            display: block;
            margin-bottom: 4px;
        }

        .custom-field-group .form-control {
            border-radius: 10px;
            background: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-left {
                width: 100%;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .glass-card {
                padding: 1.2rem;
            }

            .persona-bar {
                flex-direction: column;
                align-items: flex-start;
                padding: 1rem;
            }

            .persona-bar .persona-selector {
                width: 100%;
                justify-content: space-between;
            }

            .range-selector-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions .btn-gradient,
            .form-actions .btn-outline-lg {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .header-title h1 {
                font-size: 1.3rem;
            }

            .header-icon {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
            }

            .range-selector-grid {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            }

            .range-btn {
                padding: 0.5rem;
                font-size: 0.7rem;
            }

            .range-btn i {
                font-size: 1rem;
            }

            .rule-pill {
                font-size: 0.65rem;
                padding: 0.3rem 0.7rem;
            }
        }

        /* ========== NEW STYLES FOR MULTI-CLAIM ========== */
        .claim-entry-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            position: relative;
            transition: all 0.2s;
        }

        .claim-entry-card:hover {
            border-color: #93c5fd;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.05);
        }

        .claim-entry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed #e2e8f0;
        }

        .claim-entry-number {
            background: #2563eb;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .btn-remove-entry {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid #fca5a5;
            background: white;
            color: #ef4444;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-size: 0.75rem;
        }

        .btn-remove-entry:hover {
            background: #fef2f2;
        }

        .btn-add-entry {
            width: 100%;
            padding: 0.8rem;
            border: 2px dashed #93c5fd;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-add-entry:hover {
            background: #dbeafe;
            border-color: #2563eb;
        }

        .summary-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            border-radius: 16px;
            padding: 1.25rem;
            margin-top: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 0.85rem;
        }

        .summary-total {
            font-size: 1.1rem;
            font-weight: 800;
            color: #065f46;
            border-top: 1.5px solid #bbf7d0;
            padding-top: 8px;
            margin-top: 4px;
        }

        .entry-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .entry-grid.full-width {
            grid-column: 1 / -1;
        }

        /* Travel destination rows */
        .destination-row {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }

        .destination-row input {
            flex: 1;
            min-width: 120px;
        }

        .destination-row .btn-remove-dest {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1px solid #fca5a5;
            background: white;
            color: #ef4444;
            cursor: pointer;
            font-size: 0.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .btn-add-dest {
            font-size: 0.7rem;
            color: #2563eb;
            cursor: pointer;
            font-weight: 600;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px 0;
        }

        .policy-detail-mini {
            background: #f0f7ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 10px 14px;
            margin: 10px 0px;
            font-size: 0.78rem;
            color: #1e293b;
        }

        .policy-detail-mini strong {
            color: #2563eb;
        }

        @media (max-width: 768px) {
            .entry-grid {
                grid-template-columns: 1fr;
            }

            .destination-row {
                flex-direction: column;
            }
        }

        .sub-range-btn {
            padding: 6px 12px !important;
            font-size: 0.75rem !important;
            border-radius: 8px !important;
            border: 1.5px solid #e2e8f0 !important;
            background: white !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            font-weight: 600 !important;
            color: #475569 !important;
            white-space: nowrap !important;
        }

        .sub-range-btn:hover {
            background: #f0f7ff !important;
            border-color: #2563eb !important;
            color: #2563eb !important;
        }

        .sub-range-btn.active {
            background: #eff6ff !important;
            border-color: #2563eb !important;
            color: #2563eb !important;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.15) !important;
        }

        .entry-period-days {
            transition: all 0.3s ease;
            cursor: default;
            user-select: none;
        }
    </style>

    <div class="p-2">
        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Modern Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="header-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="header-title">
                        <h1>Create Reimbursement Claims</h1>
                        <p>File multiple expense claims at once with different policies</p>
                    </div>
                </div>
                <div class="header-actions">
                    <span class="stats-badge">
                        <i class="fas fa-file-alt" style="color: #2563eb;"></i>
                        Bulk Claim Entry
                    </span>
                </div>
            </div>
        </div>

        <!-- Persona Bar -->
        <div class="persona-bar">
            <div class="user-info">
                <div class="user-avatar"><i class="fas fa-user"></i></div>
                <div class="user-details">
                    <div class="name" id="persona-name">Loading...</div>
                    <div class="role" id="persona-role">Loading...</div>
                </div>
            </div>
            <div style="display: flex; gap: 16px; align-items: center;">
                <span style="font-size: 0.75rem; color: #94a3b8;">Dept: <strong id="persona-dept"
                        style="color: white;">-</strong></span>
                <span style="font-size: 0.75rem; color: #94a3b8;">ID: <strong id="persona-id"
                        style="color: white;">-</strong></span>
            </div>
        </div>

        <div class="glass-card">
            <!-- Error Box -->
            <div class="error-box" id="validation-error-box">
                <i class="fas fa-exclamation-triangle"></i>
                <span id="validation-error-msg"></span>
            </div>

            <form id="claim-form" onsubmit="submitAllClaims(event)" enctype="multipart/form-data">
                <div id="claims-container"></div>

                <button type="button" class="btn-add-entry" onclick="addClaimEntry()">
                    <i class="fas fa-plus-circle"></i> Add Another Claim Entry
                </button>

                <!-- Summary Card -->
                <div class="summary-card" id="summary-card" style="display: none;">
                    <h4 style="margin: 0 0 0.75rem 0; font-size: 0.9rem; color: #065f46;">
                        <i class="fas fa-calculator"></i> Claims Summary
                    </h4>
                    <div class="summary-row">
                        <span>Total Claims:</span>
                        <strong id="summary-count">0</strong>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total Amount:</span>
                        <strong id="summary-total">₹0.00</strong>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions" style="margin-top: 1.5rem;">
                    <button type="reset" class="btn-outline-lg" onclick="resetAllClaims()">
                        <i class="fas fa-undo"></i> Reset All
                    </button>
                    <button type="submit" class="btn-gradient" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Submit All Claims
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value;

        // ❌ REMOVED: const STATIC_EMPLOYEE_ID = employeeData?.employee_id || '';
        const CALCULATED_TYPES = ['per_km', 'per_night', 'per_day', 'per_hour', 'per_unit', 'percentage'];

        let employeeData = null;
        let assignedPolicies = [];
        let claimEntries = [];
        let entryCounter = 0;
        let policyUsage = {};
        let usageFetchPromises = {};


        // ============ TOAST ============
        function showToast(message, type = 'success') {
            const container = document.createElement('div');
            container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;';
            const colors = { success: '#22c55e', error: '#ef4444', warning: '#f59e0b' };
            const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle' };
            const toast = document.createElement('div');
            toast.style.cssText = `background:white;border-radius:12px;padding:16px 24px;box-shadow:0 10px 40px rgba(0,0,0,0.15);margin-bottom:10px;display:flex;align-items:center;gap:12px;min-width:300px;border-left:4px solid ${colors[type] || '#2563eb'};`;
            toast.innerHTML = `<i class="fas ${icons[type] || 'fa-info-circle'}" style="color:${colors[type] || '#2563eb'};font-size:1.2rem;"></i><span>${message}</span>`;
            container.appendChild(toast);
            document.body.appendChild(container);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'all 0.3s ease'; setTimeout(() => container.remove(), 300); }, 4000);
        }


        // ============ EXTRACT RANGE DATA FROM POLICY ============
        function extractRangeFromPolicy(policy, rangeName, category) {
            const result = {
                min: 0,
                max: 0,
                bill: 'Yes',
                photo: 'No',
                ruleType: 'actual',
                rate: null,
                unit: null,
                includesFood: false
            };

            if (category === 'travel') {
                // Private vehicles
                const privateMap = { 'Two Wheeler': 'two_wheeler', 'Car': 'car', 'Auto': 'auto' };
                if (privateMap[rangeName]) {
                    const key = privateMap[rangeName];
                    result.min = parseFloat(policy[`${key}_min`]) || 0;
                    result.max = parseFloat(policy[`${key}_max`]) || 0;
                    result.bill = policy[`${key}_bill_required`] || 'Yes';
                    result.photo = policy[`${key}_photo_required`] || 'No';
                    result.ruleType = 'per_km';
                    result.rate = parseFloat(policy[`${key}_rate_km`]) || 0;
                    return result;
                }

                // Bus categories
                if (policy.bus_categories) {
                    let busCat = policy.bus_categories;
                    if (typeof busCat === 'string') {
                        try { busCat = JSON.parse(busCat); } catch (e) { busCat = null; }
                    }
                    if (busCat && typeof busCat === 'object') {
                        const busMap = {
                            'General Bus': 'general',
                            'Seater AC': 'seater_ac',
                            'Seater Non-AC': 'seater_nonac',
                            'Sleeper AC': 'sleeper_ac',
                            'Sleeper Non-AC': 'sleeper_nonac'
                        };
                        const sub = busMap[rangeName];
                        if (sub && busCat[sub]) {
                            result.min = parseFloat(busCat[sub].min) || 0;
                            result.max = parseFloat(busCat[sub].max) || 0;
                            result.bill = busCat[sub].bill_required || 'Yes';
                            result.photo = busCat[sub].photo_required || 'No';
                            result.ruleType = 'actual';
                            return result;
                        }
                    }
                }

                // Train categories
                if (policy.train_categories) {
                    let trainCat = policy.train_categories;
                    if (typeof trainCat === 'string') {
                        try { trainCat = JSON.parse(trainCat); } catch (e) { trainCat = null; }
                    }
                    if (trainCat && typeof trainCat === 'object') {
                        const trainMap = {
                            'General': 'general',
                            'Sleeper Class': 'sleeper',
                            '3AC': 'ac3',
                            '2AC': 'ac2',
                            '1AC': 'ac1'
                        };
                        const sub = trainMap[rangeName];
                        if (sub && trainCat[sub]) {
                            result.min = parseFloat(trainCat[sub].min) || 0;
                            result.max = parseFloat(trainCat[sub].max) || 0;
                            result.bill = trainCat[sub].bill_required || 'Yes';
                            result.photo = trainCat[sub].photo_required || 'No';
                            result.ruleType = 'actual';
                            return result;
                        }
                    }
                }

                // Flight categories
                if (policy.flight_categories) {
                    let flightCat = policy.flight_categories;
                    if (typeof flightCat === 'string') {
                        try { flightCat = JSON.parse(flightCat); } catch (e) { flightCat = null; }
                    }
                    if (flightCat && typeof flightCat === 'object') {
                        const flightMap = { 'Economy': 'economy', 'Business Class': 'business' };
                        const sub = flightMap[rangeName];
                        if (sub && flightCat[sub]) {
                            result.min = parseFloat(flightCat[sub].min) || 0;
                            result.max = parseFloat(flightCat[sub].max) || 0;
                            result.bill = flightCat[sub].bill_required || 'Yes';
                            result.photo = flightCat[sub].photo_required || 'No';
                            result.ruleType = 'actual';
                            return result;
                        }
                    }
                }
            }

            if (category === 'accommodation') {
                const map = {
                    'Budget': 'basic',
                    'Business': 'deluxe',
                    'Premium': 'premium',
                    '1-2 Star (Budget)': 'basic',
                    '3-4 Star (Business)': 'deluxe',
                    '5 Star (Premium)': 'premium'
                };
                if (map[rangeName]) {
                    const key = map[rangeName];
                    result.min = parseFloat(policy[`${key}_min`]) || 0;
                    result.max = parseFloat(policy[`${key}_max`]) || 0;
                    result.bill = policy[`${key}_bill_required`] || 'Yes';
                    result.photo = policy[`${key}_photo_required`] || 'No';
                    result.ruleType = 'per_night';
                    result.rate = parseFloat(policy[`${key}_max`]) || 0;
                    result.includesFood = policy[`${key}_includes_food`] === 'Yes' || policy[`${key}_includes_food`] === true || policy[`${key}_includes_food`] === 1;
                    return result;
                }
            }

            if (category === 'food') {
                const map = {
                    'Breakfast': 'breakfast',
                    'Lunch': 'lunch',
                    'Dinner': 'dinner',
                    'Two Meals': 'two_meals',
                    'Two Meals (Combined)': 'two_meals',
                    'Three Meals': 'three_meals',
                    'Three Meals (Full Day)': 'three_meals'
                };
                if (map[rangeName]) {
                    const key = map[rangeName];
                    result.min = parseFloat(policy[`${key}_min`]) || 0;
                    result.max = parseFloat(policy[`${key}_max`]) || 0;
                    result.bill = policy.individual_bill_required || policy[`${key}_bill_required`] || 'Yes';
                    result.photo = policy.individual_photo_required || policy[`${key}_photo_required`] || 'No';
                    result.ruleType = 'actual';
                    return result;
                }
            }

            if (category === 'custom') {
                let policyData = policy.policy_data;
                if (typeof policyData === 'string') {
                    try { policyData = JSON.parse(policyData); } catch (e) { policyData = []; }
                }
                if (Array.isArray(policyData)) {
                    for (const rule of policyData) {
                        if (rule.name === rangeName) {
                            const fields = rule.fields || {};
                            result.ruleType = rule.type || 'actual';
                            result.min = parseFloat(fields.min || fields.min_amount || 0);
                            result.max = parseFloat(fields.max || fields.max_amount || 0);
                            result.rate = parseFloat(fields.rate || fields.rate_km || fields.rate_night || fields.rate_day || 0);
                            result.unit = fields.unit || null;
                            result.bill = rule.bill_required || 'Yes';
                            result.photo = rule.photo_required || 'No';
                            return result;
                        }
                    }
                }
            }

            // Fixed policies (mobile, internet, entertainment, miscellaneous)
            result.min = parseFloat(policy.min_amount) || 0;
            result.max = parseFloat(policy.max_amount) || 0;
            result.bill = policy.bill_required || 'Yes';
            result.photo = policy.photo_required || 'No';
            result.ruleType = 'actual';

            return result;
        }

        // ============ LOAD EMPLOYEE ============
        async function loadEmployeeDetails() {
            try {
                // Get employee ID from a global variable or meta tag
                const response = await fetch(
                    `/institute-admin/reimbursement-claims/employee-details`,
                    {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );
                const result = await response.json();
                if (result.success) {
                    employeeData = result.data;
                    document.getElementById('persona-name').textContent = employeeData.name;
                    document.getElementById('persona-role').textContent = employeeData.designation;
                    document.getElementById('persona-dept').textContent = employeeData.department;
                    document.getElementById('persona-id').textContent = employeeData.employee_id;
                    loadAssignedPolicies();
                }

                console.log('Employee details loaded:', employeeData);
            } catch (err) {
                console.error('Error loading employee:', err);
                showToast('Failed to load employee details', 'error');
            }
        }


        // ============ LOAD ASSIGNED POLICIES ============
        async function loadAssignedPolicies() {
            try {
                const response = await fetch(`/institute-admin/reimbursement-claims/assigned-policies?employee_id=${employeeData.employee_id}&designation_id=${employeeData.designation_id}`, {
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                if (result.success) {
                    assignedPolicies = result.data;
                    console.log('Assigned Policies loaded:', assignedPolicies.length);
                    assignedPolicies.forEach((p, i) => {
                        console.log(`Policy ${i}: ${p.policy_name}`, {
                            two_wheeler_rate_km: p.two_wheeler_rate_km,
                            two_wheeler_min: p.two_wheeler_min,
                            two_wheeler_max: p.two_wheeler_max,
                            car_rate_km: p.car_rate_km,
                            auto_rate_km: p.auto_rate_km,
                        });
                    });
                    addClaimEntry();
                }
            } catch (err) {
                console.error('Error loading policies:', err);
                showToast('Failed to load assigned policies', 'error');
            }
        }

        // ============ REMOVE CLAIM ENTRY ============
        function removeClaimEntry(entryId) {
            const element = document.getElementById(entryId);
            if (element) element.remove();
            claimEntries = claimEntries.filter(e => e.id !== entryId);
            renumberEntries();
            updateSummary();
        }


        // ============ ADD CLAIM ENTRY (PARENT) ============
        function addClaimEntry() {
            entryCounter++;
            const entryId = `entry-${entryCounter}`;
            const entryCard = document.createElement('div');
            entryCard.className = 'claim-entry-card';
            entryCard.id = entryId;
            entryCard.innerHTML = `
                                                                                                                                            <div class="claim-entry-header">
                                                                                                                                            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                                                                                                                            <span class="claim-entry-number">${entryCounter}</span>
                                                                                                                                            <span style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">Claim Entry #${entryCounter}</span>
                                                                                                                                            <span class="entry-policy-name-badge" style="display:none; font-size:0.75rem; color:#2563eb; font-weight:600;"></span>
                                                                                                                                            </div>
                                                                                                                                            ${entryCounter > 1 ? `<button type="button" class="btn-remove-entry" onclick="removeClaimEntry('${entryId}')"><i class="fas fa-times"></i></button>` : ''}
                                                                                                                                            </div>

                                                                                                                                            <!-- Policy Selection -->
                                                                                                                                            <div class="form-group">
                                                                                                                                            <label style="font-size: 0.7rem;">Select Policy <span style="color: #ef4444;">*</span></label>
                                                                                                                                            <select class="form-control entry-policy" onchange="onEntryPolicyChange('${entryId}', this)" style="font-size: 0.85rem; padding: 0.5rem 0.8rem;">
                                                                                                                                            <option value="">-- Select Policy --</option>
                                                                                                                                            ${assignedPolicies.map(p => `<option value="${p.reimbursement_policy_id}" data-category="${p.policy_category}">${p.policy_name} (${p.policy_category.toUpperCase()})</option>`).join('')}
                                                                                                                                            </select>
                                                                                                                                            </div>

                                                                                                                                            <!-- Policy Details Mini Card -->
                                                                                                                                            <div class="policy-detail-mini entry-policy-detail" style="display: none;"></div>


                                                                                                                                            <!-- Expense Period -->
                                                                                                                                            <div class="entry-expense-date-range" style="display:none; margin-bottom: 12px; padding: 10px 14px; background: linear-gradient(135deg, #f0f7ff 0%, #e8f0fe 100%); border: 1px solid #bfdbfe; border-radius: 12px;">
                                                                                                                                            <div style="font-size: 0.7rem; font-weight: 700; color: #1e40af; text-transform: uppercase; margin-bottom: 8px;">
                                                                                                                                            <i class="fas fa-calendar-alt"></i> Expense Period
                                                                                                                                            </div>
                                                                                                                                            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: end;">
                                                                                                                                            <div class="form-group" style="margin-bottom:0;">
                                                                                                                                            <label style="font-size: 0.65rem;">Expense From <span style="color: #ef4444;">*</span></label>
                                                                                                                                            <input type="date" class="form-control entry-expense-from" style="font-size: 0.85rem; padding: 0.5rem 0.8rem;" value="{{ date('Y-m-d') }}" onchange="updateExpensePeriodDays('${entryId}')">
                                                                                                                                            </div>
                                                                                                                                            <div class="form-group" style="margin-bottom:0;">
                                                                                                                                            <label style="font-size: 0.65rem;">Expense To</label>
                                                                                                                                            <input type="date" class="form-control entry-expense-to" style="font-size: 0.85rem; padding: 0.5rem 0.8rem;" onchange="updateExpensePeriodDays('${entryId}')">
                                                                                                                                            </div>
                                                                                                                                            <div class="form-group" style="margin-bottom:0; min-width: 80px;">
                                                                                                                                            <label style="font-size: 0.65rem;">Days</label>
                                                                                                                                            <div class="entry-period-days" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.9rem; text-align: center; min-width: 50px;">0</div>
                                                                                                                                            </div>
                                                                                                                                            </div>
                                                                                                                                            </div>

                                                                                                                                            <!-- Sub-Entries Section -->
                                                                                                                                            <div class="entry-sub-entries-section" style="display: none;">
                                                                                                                                            <div style="font-size: 0.75rem; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1.5px solid #e2e8f0;" class="entry-sub-entries-label">
                                                                                                                                            📋 Details
                                                                                                                                            </div>
                                                                                                                                            <div class="entry-sub-entries"></div>
                                                                                                                                            <button type="button" class="btn-add-sub-entry" onclick="addSubEntry('${entryId}')" style="display:none; width:100%; padding:0.6rem; border:2px dashed #93c5fd; background:#eff6ff; color:#2563eb; border-radius:10px; font-weight:600; cursor:pointer; font-size:0.85rem; margin-top:8px; transition: all 0.2s;">
                                                                                                                                            <i class="fas fa-plus-circle"></i> Add Another
                                                                                                                                            </button>
                                                                                                                                            </div>

                                                                                                                                            <!-- Summary for this claim entry -->
                                                                                                                                            <div class="entry-summary-mini" style="display:none; margin-top:12px; padding:12px 16px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px;">
                                                                                                                                            <div class="entry-remaining-info" style="font-size:0.7rem; color:#475569; margin-top:4px;"></div>
                                                                                                                                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                                                                                                                            <div>
                                                                                                                                            <span style="font-weight:700; color:#065f46; font-size:0.85rem;"><i class="fas fa-calculator"></i> Claim #${entryCounter} Total</span>
                                                                                                                                            <div style="font-size:0.65rem; color:#059669; margin-top:2px;" class="entry-sub-count">0 items</div>
                                                                                                                                            </div>
                                                                                                                                            <span style="font-weight:800; color:#065f46; font-size:1.1rem;" class="entry-total-amount">₹0.00</span>
                                                                                                                                            </div>
                                                                                                                                            <div style="margin-top:6px; font-size:0.7rem; color:#047857;" class="entry-calculation-warning"></div>
                                                                                                                                            </div>

                                                                                                                                            <!-- Overall Remarks -->
                                                                                                                                            <div class="form-group" style="margin-top: 12px;">
                                                                                                                                            <label style="font-size: 0.7rem;">📝 Overall Remarks / Description</label>
                                                                                                                                            <textarea class="form-control entry-remarks" placeholder="Add any overall remarks for this claim..." rows="2" style="font-size: 0.85rem; padding: 0.5rem 0.8rem; border-radius: 10px;"></textarea>
                                                                                                                                            </div>
                                                                                                                                            `;

            document.getElementById('claims-container').appendChild(entryCard);

            // Store entry object
            const entry = {
                id: entryId,
                policy: null,
                subEntries: [],
                subEntryCounter: 0,
                bills: [],
                billCounter: 0,
                photos: [],
                photoCounter: 0,
                existingAmount: 0,
                existingCount: 0,
                maxAmount: 0,
                frequencyValue: 0,
                frequencyType: 'unlimited',
                calculationType: 'per_claim'
            };
            claimEntries.push(entry);

            // Event listener for expense date change
            const expenseFrom = entryCard.querySelector('.entry-expense-from');
            if (expenseFrom) {
                expenseFrom.addEventListener('change', function () {
                    const id = this.closest('.claim-entry-card').id;
                    updateExpensePeriodDays(id);
                    fetchPolicyUsageForEntry(id);
                });
            }

            updateSummary();
        }

        // ============ UPDATE EXPENSE PERIOD DAYS ============
        function updateExpensePeriodDays(entryId) {
            const card = document.getElementById(entryId);
            if (!card) return;

            const fromDate = card.querySelector('.entry-expense-from')?.value;
            const toDate = card.querySelector('.entry-expense-to')?.value;
            const daysDisplay = card.querySelector('.entry-period-days');

            if (fromDate && toDate && daysDisplay) {
                const from = new Date(fromDate);
                const to = new Date(toDate);
                const diffTime = to - from;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both start and end days

                if (diffDays > 0) {
                    daysDisplay.textContent = diffDays;
                    daysDisplay.style.background = '#2563eb';
                } else {
                    daysDisplay.textContent = '!';
                    daysDisplay.style.background = '#ef4444';
                }
            } else if (daysDisplay) {
                daysDisplay.textContent = '0';
                daysDisplay.style.background = '#94a3b8';
            }
        }

        // ============ SHOW POLICY DETAIL MINI ============
        function showPolicyDetailMini(entryId, policy, category) {
            const detail = document.querySelector(`#${entryId} .entry-policy-detail`);
            if (!detail) return;
            detail.style.display = 'block';

            let html = `<strong>📋 ${policy.policy_name}</strong> | Category: ${category}`;

            // Calculation type
            if (policy.calculation_type) {
                const calcLabels = {
                    'per_claim': 'Per Claim',
                    'per_day': 'Per Day',
                    'per_month': 'Per Month',
                    'per_quarter': 'Per Quarter',
                    'per_year': 'Per Year',
                    'lumpsum': 'One-Time (Lumpsum)'
                };
                html += ` | ⚙️ <strong>${calcLabels[policy.calculation_type] || policy.calculation_type}</strong>`;

                if (policy.max_amount && parseFloat(policy.max_amount) > 0) {
                    html += ` (Max: ₹${parseFloat(policy.max_amount).toFixed(2)})`;
                }
            }

            // Frequency info
            if (policy.frequency_type && policy.frequency_type !== 'unlimited') {
                html += ` | 🔢 Max: ${policy.frequency_value || 1}/${policy.frequency_type}`;

                if (policy.existing_claim_count !== undefined && policy.existing_claim_count > 0) {
                    html += ` <span style="color:#f59e0b;">(${policy.existing_claim_count} already submitted)</span>`;
                }
            }

            // Submission window
            html += ` | Submit within: ${policy.submission_within || 7}d`;

            // Actual amount
            if (policy.allow_actual_amount === 'Yes') {
                html += ` | <span style="color:#059669;">✅ Actual Amount Allowed</span>`;
            } else {
                html += ` | <span style="color:#dc2626;">🔒 Restricted Range</span>`;
            }

            // Settlement mode
            if (policy.settlement_mode) {
                html += `<br><span style="font-size:0.7rem;color:#64748b;">💳 Settlement: ${policy.settlement_mode.replace(/_/g, ' ')} | Timeline: ${policy.settlement_timeline || 7}d</span>`;
            }

            // Financial year
            if (policy.financial_year) {
                html += `<br><span style="font-size:0.7rem;color:#64748b;">📅 FY: ${policy.financial_year}</span>`;
            }

            detail.innerHTML = html;
        }

        // ============ POLICY CHANGE ============
        function onEntryPolicyChange(entryId, selectEl) {
            const entry = claimEntries.find(e => e.id === entryId);
            if (!entry) return;

            const policyId = selectEl.value;
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            const category = selectedOption?.dataset?.category || '';

            const card = document.getElementById(entryId);
            const policyDetail = card.querySelector('.entry-policy-detail');
            const expenseDateRange = card.querySelector('.entry-expense-date-range');
            const subSection = card.querySelector('.entry-sub-entries-section');
            const subLabel = card.querySelector('.entry-sub-entries-label');
            const subContainer = card.querySelector('.entry-sub-entries');
            const addSubBtn = card.querySelector('.btn-add-sub-entry');
            const badge = card.querySelector('.entry-policy-name-badge');
            const summaryMini = card.querySelector('.entry-summary-mini');

            // Reset
            policyDetail.style.display = 'none';
            expenseDateRange.style.display = 'none';
            subSection.style.display = 'none';
            subContainer.innerHTML = '';
            addSubBtn.style.display = 'none';
            badge.style.display = 'none';
            summaryMini.style.display = 'none';
            entry.policy = null;
            entry.subEntries = [];
            entry.subEntryCounter = 0;

            if (!policyId) { updateSummary(); return; }

            entry.policy = assignedPolicies.find(p => p.reimbursement_policy_id === policyId);
            if (!entry.policy) { updateSummary(); return; }

            // Show badge and sections
            badge.style.display = 'inline';
            badge.textContent = `| ${entry.policy.policy_name}`;
            expenseDateRange.style.display = 'block';
            subSection.style.display = 'block';
            addSubBtn.style.display = 'block';

            // Calculate initial days
            setTimeout(() => updateExpensePeriodDays(entryId), 100);
            fetchPolicyUsageForEntry(entryId)
            // Show policy detail
            showPolicyDetailMini(entryId, entry.policy, category);

            // Set labels based on category
            if (category === 'travel') {
                subLabel.innerHTML = '🚗 Trip Legs <span style="font-weight:400; font-size:0.65rem; color:#94a3b8;">(Add each leg of your journey)</span>';
                addSubBtn.innerHTML = '<i class="fas fa-plus-circle"></i> Add Another Leg';
            } else if (category === 'accommodation') {
                subLabel.innerHTML = '🏨 Stay Details <span style="font-weight:400; font-size:0.65rem; color:#94a3b8;">(Add each hotel/stay separately)</span>';
                addSubBtn.innerHTML = '<i class="fas fa-plus-circle"></i> Add Another Stay';
            } else if (category === 'food') {
                subLabel.innerHTML = '🍽️ Meal Details <span style="font-weight:400; font-size:0.65rem; color:#94a3b8;">(Add each meal/restaurant visit)</span>';
                addSubBtn.innerHTML = '<i class="fas fa-plus-circle"></i> Add Another Meal';
            } else if (category === 'custom') {
                subLabel.innerHTML = '✨ Custom Items <span style="font-weight:400; font-size:0.65rem; color:#94a3b8;">(Add each custom expense item)</span>';
                addSubBtn.innerHTML = '<i class="fas fa-plus-circle"></i> Add Another Item';
            } else {
                subLabel.innerHTML = '📋 Expense Items <span style="font-weight:400; font-size:0.65rem; color:#94a3b8;">(Add each expense item)</span>';
                addSubBtn.innerHTML = '<i class="fas fa-plus-circle"></i> Add Another Item';
            }

            // Add first sub-entry
            addSubEntry(entryId);
            updateSummary();
        }


        function renumberEntries() {
            document.querySelectorAll('.claim-entry-card').forEach((card, idx) => {
                const numSpan = card.querySelector('.claim-entry-number');
                if (numSpan) numSpan.textContent = idx + 1;
            });
        }

        // ============ HELPER FUNCTIONS FOR LABELS ============
        function getCategoryIcon(category) {
            const icons = {
                'travel': '🚗',
                'accommodation': '🏨',
                'food': '🍽️',
                'custom': '✨',
                'mobile': '📱',
                'internet': '🌐',
                'entertainment': '🎬',
                'miscellaneous': '📌'
            };
            return icons[category] || '📋';
        }


        function getCategoryLabel(category) {
            const labels = {
                'travel': 'Leg',
                'accommodation': 'Stay',
                'food': 'Meal',
                'custom': 'Item',
                'mobile': 'Item',
                'internet': 'Item',
                'entertainment': 'Item',
                'miscellaneous': 'Item'
            };
            return labels[category] || 'Item';
        }


        function getRangeLabel(category) {
            const labels = {
                'travel': '🚗 Mode of Travel',
                'accommodation': '🏨 Hotel Category',
                'food': '🍽️ Meal Type',
                'custom': '📌 Rule / Type',
                'mobile': '📱 Type',
                'internet': '🌐 Type',
                'entertainment': '🎬 Type',
                'miscellaneous': '📌 Type'
            };
            return labels[category] || '📋 Type';
        }

        // ============ ADD SUB-ENTRY WITH PER-ITEM BILL/PHOTO UPLOADS ============
        function addSubEntry(entryId) {
            const entry = claimEntries.find(e => e.id === entryId);
            if (!entry || !entry.policy) return;

            entry.subEntryCounter++;
            const subId = `sub-${entryId}-${entry.subEntryCounter}`;
            const subContainer = document.querySelector(`#${entryId} .entry-sub-entries`);
            const category = entry.policy.policy_category;
            const isFirst = entry.subEntries.length === 0;

            const subEntryData = {
                id: subId,
                rangeName: '',
                range: null,
                from: '',
                to: '',
                km: 0,
                unitsConsumed: 0,
                daysOrNights: 0,
                date: '',
                checkinDate: '',
                checkinTime: '12:00',
                checkoutDate: '',
                checkoutTime: '12:00',
                nights: 0,
                amount: 0,
                vendor: '',
                billNumber: '',
                description: '',
                bills: [],
                billCounter: 0,
                photos: [],
                photoCounter: 0
            };

            const subDiv = document.createElement('div');
            subDiv.className = 'sub-entry-card';
            subDiv.id = subId;
            subDiv.style.cssText = 'background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 1rem; margin-bottom: 10px; transition: all 0.2s;';

            let subHtml = `
                                                                                                                                                                                                                                                                                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; padding-bottom:8px; border-bottom:1.5px dashed #e2e8f0;">
                                                                                                                                                                                                                                                                                                <span style="font-weight:700; font-size:0.85rem; color:#1e293b;">
                                                                                                                                                                                                                                                                                                    ${getCategoryIcon(category)} ${getCategoryLabel(category)} #${entry.subEntryCounter}
                                                                                                                                                                                                                                                                                                </span>
                                                                                                                                                                                                                                                                                                ${!isFirst ? `<button type="button" class="btn-remove-dest" onclick="removeSubEntry('${entryId}', '${subId}')" style="width:26px;height:26px;border-radius:50%;border:1px solid #fca5a5;background:white;color:#ef4444;cursor:pointer;font-size:0.75rem;display:flex;align-items:center;justify-content:center;"><i class="fas fa-times"></i></button>` : ''}
                                                                                                                                                                                                                                                                                            </div>

                                                                                                                                                                                                                                                                                            <div class="form-group" style="margin-bottom:10px;">
                                                                                                                                                                                                                                                                                                <label style="font-size:0.65rem;">${getRangeLabel(category)}</label>
                                                                                                                                                                                                                                                                                                <div class="sub-range-grid" style="display:flex;flex-wrap:wrap;gap:5px;">
                                                                                                                                                                                                                                                                                                    ${buildRangeButtonsForSub(entryId, subId, entry.policy, category)}
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                            </div>`;

            // Travel Category - Show KM input for per_km vehicles, amount input for others
            if (category === 'travel') {
                subHtml += `
                                                                                                                                                                                                                                                                                                <div style="background:#f8fafc; border-radius:8px; padding:8px; margin-bottom:8px;">
                                                                                                                                                                                                                                                                                                    <label style="font-size:0.6rem; color:#64748b; font-weight:600; display:block; margin-bottom:6px;">📍 Route Details</label>
                                                                                                                                                                                                                                                                                                    <div style="display:grid; grid-template-columns:1fr auto 1fr; gap:8px; align-items:end;">
                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:0;">
                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">From</label>
                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-from" placeholder="City/Station" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                        <div style="color:#94a3b8; font-size:1rem; padding-bottom:6px;">→</div>
                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:0;">
                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">To</label>
                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-to" placeholder="City/Station" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                                                                                                                                                                                                                                                                                    <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                        <label style="font-size:0.6rem;">📅 Travel Date</label>
                                                                                                                                                                                                                                                                                                        <input type="date" class="form-control sub-date" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                    <div class="form-group sub-km-group" style="margin-bottom:6px; display:none;">
                                                                                                                                                                                                                                                                                                        <label style="font-size:0.6rem;">📏 Distance (KM)</label>
                                                                                                                                                                                                                                                                                                        <input type="number" class="form-control sub-km" placeholder="Enter KM" style="font-size:0.8rem; padding:0.4rem 0.6rem;" oninput="recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                    <div class="form-group sub-amount-group" style="margin-bottom:6px; display:none;">
                                                                                                                                                                                                                                                                                                        <label style="font-size:0.6rem;">💵 Amount (₹) <span style="color:#ef4444;">*</span></label>
                                                                                                                                                                                                                                                                                                        <input type="number" class="form-control sub-amount" placeholder="0.00" step="0.01" style="font-size:0.85rem; padding:0.5rem 0.8rem;" oninput="recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                    <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                        <label style="font-size:0.6rem;">🏢 Travel Provider</label>
                                                                                                                                                                                                                                                                                                        <input type="text" class="form-control sub-vendor" placeholder="e.g. Ola, Uber, IRCTC" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                    <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                        <label style="font-size:0.6rem;">🧾 Ticket / Bill #</label>
                                                                                                                                                                                                                                                                                                        <input type="text" class="form-control sub-bill" placeholder="e.g. TKT-12345" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                </div>`;
            } else if (category === 'accommodation') {
                subHtml += `
                                                                                                                                                                                                                                                                                                                                                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">📅 Check-in Date & Time</label>
                                                                                                                                                                                                                                                                                                                                                                            <div style="display:flex; gap:4px;">
                                                                                                                                                                                                                                                                                                                                                                                <input type="date" class="form-control sub-checkin" style="font-size:0.8rem; padding:0.4rem 0.6rem; flex:1.5;" onchange="calculateNights('${entryId}', '${subId}'); recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                                <input type="time" class="form-control sub-checkin-time" style="font-size:0.8rem; padding:0.4rem 0.6rem; flex:1;" value="12:00" onchange="calculateNights('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">📅 Check-out Date & Time</label>
                                                                                                                                                                                                                                                                                                                                                                            <div style="display:flex; gap:4px;">
                                                                                                                                                                                                                                                                                                                                                                                <input type="date" class="form-control sub-checkout" style="font-size:0.8rem; padding:0.4rem 0.6rem; flex:1.5;" onchange="calculateNights('${entryId}', '${subId}'); recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                                <input type="time" class="form-control sub-checkout-time" style="font-size:0.8rem; padding:0.4rem 0.6rem; flex:1;" value="12:00" onchange="calculateNights('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">🌙 Duration</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-nights" placeholder="Auto-calculated" readonly style="font-size:0.8rem; padding:0.4rem 0.6rem; background:#f8fafc; font-weight:600; color:#2563eb;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group sub-amount-group" style="margin-bottom:6px; display:none;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">💵 Amount (₹) <span style="color:#ef4444;">*</span></label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="number" class="form-control sub-amount" placeholder="0.00" step="0.01" style="font-size:0.85rem; padding:0.5rem 0.8rem;" oninput="recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group sub-units-group" style="margin-bottom:6px; display:none;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">🔢 Number of Nights</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="number" class="form-control sub-units" placeholder="Enter nights" style="font-size:0.8rem; padding:0.4rem 0.6rem;" oninput="recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">🏨 Hotel / Property Name</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-vendor" placeholder="e.g. Taj Palace" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">🧾 Booking / Invoice #</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-bill" placeholder="e.g. BKG-789" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                    </div>`;
            } else if (category === 'food') {
                subHtml += `
                                                                                                                                                                                                                                                                                                                                                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">📅 Meal Date</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="date" class="form-control sub-date" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">🍽️ Restaurant / Outlet</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-vendor" placeholder="e.g. Haldiram" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">📝 Description</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-desc" placeholder="e.g. Lunch with client" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">🧾 Bill Number</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-bill" placeholder="e.g. BILL-789" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group sub-amount-group" style="margin-bottom:6px; grid-column: 1 / -1; display:none;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">💵 Amount (₹) <span style="color:#ef4444;">*</span></label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="number" class="form-control sub-amount" placeholder="0.00" step="0.01" style="font-size:0.85rem; padding:0.5rem 0.8rem;" oninput="recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                    </div>`;
            } else {
                // Custom and other policies
                subHtml += `
                                                                                                                                                                                                                                                                                                                                                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">📅 Date</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="date" class="form-control sub-date" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">🏢 Vendor / Provider</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-vendor" placeholder="Vendor name" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">📝 Description</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-desc" placeholder="Item description" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group" style="margin-bottom:6px;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">🧾 Bill / Invoice #</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="text" class="form-control sub-bill" placeholder="Bill number" style="font-size:0.8rem; padding:0.4rem 0.6rem;">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group sub-units-group" style="margin-bottom:6px; display:none;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">🔢 Units Consumed</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="number" class="form-control sub-units" placeholder="Units" style="font-size:0.8rem; padding:0.4rem 0.6rem;" oninput="recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group sub-km-group" style="margin-bottom:6px; display:none;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">📏 Distance (KM)</label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="number" class="form-control sub-km" placeholder="KM" style="font-size:0.8rem; padding:0.4rem 0.6rem;" oninput="recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                        <div class="form-group sub-amount-group" style="margin-bottom:6px; grid-column: 1 / -1; display:none;">
                                                                                                                                                                                                                                                                                                                                                                            <label style="font-size:0.6rem;">💵 Amount (₹) <span style="color:#ef4444;">*</span></label>
                                                                                                                                                                                                                                                                                                                                                                            <input type="number" class="form-control sub-amount" placeholder="0.00" step="0.01" style="font-size:0.85rem; padding:0.5rem 0.8rem;" oninput="recalculateSubEntry('${entryId}', '${subId}')">
                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                    </div>`;
            }

            subHtml += `
                                                                                                                                                                                                                                                                                            <div style="display:flex; flex-direction:column; gap:4px; margin-top:6px; padding:8px 12px; background:#f0fdf4; border-radius:8px; border:1px solid #bbf7d0;">
                                                                                                                                                                                                                                                                                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                                                                                                                                                                                                                                                                                    <span style="font-size:0.75rem; color:#065f46; font-weight:600;">💰 Amount:</span>
                                                                                                                                                                                                                                                                                                    <span class="sub-leg-amount" style="font-weight:800; color:#065f46; font-size:1rem;">₹0.00</span>
                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                <div class="sub-range-info" style="font-size:0.7rem; color:#475569;"></div>
                                                                                                                                                                                                                                                                                                <div class="sub-range-warning" style="font-size:0.75rem; color:#d97706; font-weight:600; display:none;"></div>
                                                                                                                                                                                                                                                                                            </div>

                                                                                                                                                                                                                                                                                            <!-- Per-Item Bill Uploads -->
                                                                                                                                                                                                                                                                                            <div class="sub-bills-section" style="margin-top:8px; padding:8px; background:#fafcfd; border-radius:8px; border:1px solid #edf2f7;">
                                                                                                                                                                                                                                                                                                <label class="sub-bills-label" style="font-size:0.65rem; color:#64748b; font-weight:600;">📎 Bill Attachments <span class="sub-bills-required" style="color:#94a3b8; font-size:0.6rem;">(Optional)</span></label>
                                                                                                                                                                                                                                                                                                <div class="sub-bills-container" style="display:flex; flex-direction:column; gap:3px; margin-top:4px;"></div>
                                                                                                                                                                                                                                                                                                <button type="button" class="btn-outline-lg" onclick="addSubBill('${entryId}', '${subId}')" style="padding:0.3rem 0.6rem; font-size:0.7rem; margin-top:4px;">
                                                                                                                                                                                                                                                                                                    <i class="fas fa-plus"></i> Add Bill
                                                                                                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                                                                                            </div>

                                                                                                                                                                                                                                                                                            <!-- Per-Item Photo Uploads -->
                                                                                                                                                                                                                                                                                            <div class="sub-photos-section" style="margin-top:8px; padding:8px; background:#fafcfd; border-radius:8px; border:1px solid #edf2f7;">
                                                                                                                                                                                                                                                                                                <label class="sub-photos-label" style="font-size:0.65rem; color:#64748b; font-weight:600;">📸 Photo Attachments <span class="sub-photos-required" style="color:#94a3b8; font-size:0.6rem;">(Optional)</span></label>
                                                                                                                                                                                                                                                                                                <div class="sub-photos-container" style="display:flex; flex-direction:column; gap:3px; margin-top:4px;"></div>
                                                                                                                                                                                                                                                                                                <button type="button" class="btn-outline-lg" onclick="addSubPhoto('${entryId}', '${subId}')" style="padding:0.3rem 0.6rem; font-size:0.7rem; margin-top:4px;">
                                                                                                                                                                                                                                                                                                    <i class="fas fa-camera"></i> Add Photo
                                                                                                                                                                                                                                                                                                </button>
                                                                                                                                                                                                                                                                                            </div>`;

            subDiv.innerHTML = subHtml;
            subContainer.appendChild(subDiv);
            entry.subEntries.push(subEntryData);

            // Auto-select first range button
            setTimeout(() => {
                const rangeBtns = subDiv.querySelectorAll('.sub-range-btn');
                if (rangeBtns.length === 1) {
                    rangeBtns[0].click();
                }
            }, 100);
        }


        // Helper function to get the effective max amount for a claim entry
        function getEffectiveMaxForEntry(entry) {
            if (!entry || !entry.subEntries || entry.subEntries.length === 0) {
                return parseFloat(entry.policy?.max_amount) || 0;
            }

            // For per_claim: sum of all sub-entries, but each sub-entry has its own range max
            const calcType = entry.policy?.calculation_type || 'per_claim';

            if (calcType === 'per_claim') {
                // Per-claim: each sub-entry's max is its range max, but the total claim max is the sum?
                // Actually, for per_claim, the limit applies to the total claim amount
                // But each sub-entry also has its own individual range max
                let totalMax = 0;
                entry.subEntries.forEach(sub => {
                    const subMax = (sub.range && sub.range.max > 0) ? sub.range.max : 0;
                    // For per_claim, the limit is on the total, not per sub-entry
                    // So we don't sum the sub-entry maxes, we keep the policy max
                });
                return parseFloat(entry.policy?.max_amount) || 0;
            }

            if (calcType === 'per_day') {
                // For per_day: the limit applies PER DAY PER RANGE
                // So we need to track each range's max separately
                const rangeMaxes = {};
                entry.subEntries.forEach(sub => {
                    const rangeName = sub.rangeName || 'Standard';
                    const rangeMax = (sub.range && sub.range.max > 0) ? sub.range.max : 0;
                    if (rangeMax > 0 && !rangeMaxes[rangeName]) {
                        rangeMaxes[rangeName] = rangeMax;
                    }
                });
                // Return the minimum max across all ranges for this entry
                // Or return the policy max as a fallback
                const values = Object.values(rangeMaxes);
                return values.length > 0 ? Math.max(...values) : parseFloat(entry.policy?.max_amount) || 0;
            }

            // For other calculation types, use policy max
            return parseFloat(entry.policy?.max_amount) || 0;
        }

        // ============ BUILD RANGE BUTTONS FOR SUB-ENTRY ============
        function buildRangeButtonsForSub(entryId, subId, policy, category) {
            let ranges = [];

            if (policy.allowed_ranges && Array.isArray(policy.allowed_ranges) && policy.allowed_ranges.length > 0) {
                ranges = policy.allowed_ranges.map(range => {
                    if (typeof range === 'object') {
                        const rateVal = parseFloat(range.rate || range.rate_km || 0);
                        // FIX: Convert 'vehicle' type to 'per_km' when rate exists
                        let ruleTypeVal = range.type || range.ruleType || range.rule_type || 'actual';
                        if ((ruleTypeVal === 'vehicle' || ruleTypeVal === 'per_km') && rateVal > 0) {
                            ruleTypeVal = 'per_km';
                        }
                        return {
                            name: range.name || range.claim_name || range.range_name || 'Standard',
                            min: parseFloat(range.min || range.min_amount || 0),
                            max: parseFloat(range.max || range.max_amount || 0),
                            rate: rateVal,
                            ruleType: ruleTypeVal,
                            unit: range.unit || null,
                            bill: range.bill_required || range.bill || 'Yes',
                            photo: range.photo_required || range.photo || 'No',
                            includesFood: range.includes_food || false
                        };
                    }
                    return { name: String(range), min: 0, max: 0, rate: 0, ruleType: 'actual', unit: null, bill: 'Yes', photo: 'No', includesFood: false };
                });
            } else {
                ranges = extractRangesFromPolicy(policy, category);
            }

            console.log('Final ranges for buttons:', ranges);

            if (ranges.length === 0) {
                return '<span style="font-size:0.7rem; color:#94a3b8;">No range options available</span>';
            }

            const iconMap = {
                'Two Wheeler': '🏍️', 'Car': '🚗', 'Auto': '🛺',
                'General Bus': '🚌', 'Seater AC': '🚌', 'Seater Non-AC': '🚌',
                'Sleeper AC': '🚌', 'Sleeper Non-AC': '🚌',
                'General': '🚂', 'Sleeper Class': '🚂', '3AC': '🚂', '2AC': '🚂', '1AC': '🚂',
                'Economy': '✈️', 'Business Class': '✈️',
                'Budget': '🏨', '1-2 Star (Budget)': '🏨', 'Business': '🏨', '3-4 Star (Business)': '🏨',
                'Premium': '🏨', '5 Star (Premium)': '🏨',
                'Breakfast': '🌅', 'Lunch': '☀️', 'Dinner': '🌙', 'Two Meals': '🍽️',
                'Two Meals (Combined)': '🍽️', 'Three Meals': '🍽️', 'Three Meals (Full Day)': '🍽️',
                'Standard': '📋', 'Mobile': '📱', 'Internet': '🌐', 'Entertainment': '🎬', 'Miscellaneous': '📌'
            };

            return ranges.map((range, index) => {
                const rName = range.name || 'Standard';
                const icon = iconMap[rName] || '📌';
                const min = range.min || 0;
                const max = range.max || 0;
                const rate = range.rate || 0;
                const ruleType = range.ruleType || 'actual';
                const unit = range.unit || '';

                let tag = '';
                let displayRate = '';

                if (ruleType === 'per_km' && rate > 0) {
                    displayRate = `₹${rate}/KM`;
                    tag = max > 0 ? `${displayRate} | Max ₹${max}` : displayRate;
                } else if (ruleType === 'per_unit' && rate > 0) {
                    const u = unit || 'Unit';
                    displayRate = `₹${rate}/${u}`;
                    tag = max > 0 ? `${displayRate} | Max ₹${max}` : displayRate;
                } else if (ruleType === 'per_night' && rate > 0) {
                    displayRate = `₹${rate}/Night`;
                    tag = max > 0 ? `${displayRate} | Max ₹${max}` : displayRate;
                } else if (ruleType === 'per_day' && rate > 0) {
                    displayRate = `₹${rate}/Day`;
                    tag = max > 0 ? `${displayRate} | Max ₹${max}` : displayRate;
                } else if (ruleType === 'per_hour' && rate > 0) {
                    displayRate = `₹${rate}/Hour`;
                    tag = max > 0 ? `${displayRate} | Max ₹${max}` : displayRate;
                } else if (min > 0 && max > 0) {
                    tag = `₹${min} - ₹${max}`;
                } else if (max > 0) {
                    tag = `Max ₹${max}`;
                } else if (min > 0) {
                    tag = `Min ₹${min}`;
                } else {
                    tag = `Actual Amount`;
                }

                // Add includes food indicator for accommodation
                if (range.includesFood) {
                    tag += ' 🍽️';
                }

                const safeName = rName.replace(/'/g, "\\'").replace(/"/g, '&quot;');

                return `<button type="button" class="btn sub-range-btn" 
                                                                                                                                                                                                                                                                                                                                                                                        onclick="selectSubRange(event, '${entryId}', '${subId}', '${safeName}')" 
                                                                                                                                                                                                                                                                                                                                                                                        data-range-name="${safeName}"
                                                                                                                                                                                                                                                                                                                                                                                        data-min="${min}" 
                                                                                                                                                                                                                                                                                                                                                                                        data-max="${max}" 
                                                                                                                                                                                                                                                                                                                                                                                        data-rate="${rate}" 
                                                                                                                                                                                                                                                                                                                                                                                        data-rule-type="${ruleType}"
                                                                                                                                                                                                                                                                                                                                                                                        data-unit="${unit}"
                                                                                                                                                                                                                                                                                                                                                                                        data-bill="${range.bill || 'Yes'}"
                                                                                                                                                                                                                                                                                                                                                                                        data-photo="${range.photo || 'No'}"
                                                                                                                                                                                                                                                                                                                                                                                        data-includes-food="${range.includesFood || false}"
                                                                                                                                                                                                                                                                                                                                                                                        style="padding:4px 10px; font-size:0.75rem; border-radius:6px; border:1px solid #cbd5e1; background:#f8fafc; color:#334155; display:inline-flex; align-items:center; gap:6px; margin-right:4px; margin-bottom:4px; cursor:pointer; transition: all 0.2s;">
                                                                                                                                                                                                                                                                                                                                                                                        <span style="font-size:1rem;">${icon}</span>
                                                                                                                                                                                                                                                                                                                                                                                        <span style="font-weight:600;">${rName}</span>
                                                                                                                                                                                                                                                                                                                                                                                        <span style="font-size:0.65rem; background:#e2e8f0; color:#1e293b; padding:2px 6px; border-radius:4px; font-weight:700;">${tag}</span>
                                                                                                                                                                                                                                                                                                                                                                                    </button>`;
            }).join('');
        }

        // ============ EXTRACT RANGES FROM POLICY FIELDS ============
        function extractRangesFromPolicy(policy, category) {
            const ranges = [];

            console.log('Extracting ranges for category:', category, 'Policy:', policy.policy_name);

            if (category === 'travel') {
                // Two Wheeler
                if (policy.two_wheeler_max || policy.two_wheeler_rate_km || policy.two_wheeler_min) {
                    const rate = parseFloat(policy.two_wheeler_rate_km) || 0;
                    ranges.push({
                        name: 'Two Wheeler',
                        min: parseFloat(policy.two_wheeler_min) || 0,
                        max: parseFloat(policy.two_wheeler_max) || 0,
                        rate: rate,
                        ruleType: rate > 0 ? 'per_km' : 'actual',
                        unit: rate > 0 ? 'KM' : null,
                        bill: policy.two_wheeler_bill_required || 'Yes',
                        photo: policy.two_wheeler_photo_required || 'No',
                        includesFood: false
                    });
                }

                // Car
                if (policy.car_max || policy.car_rate_km || policy.car_min) {
                    const rate = parseFloat(policy.car_rate_km) || 0;
                    ranges.push({
                        name: 'Car',
                        min: parseFloat(policy.car_min) || 0,
                        max: parseFloat(policy.car_max) || 0,
                        rate: rate,
                        ruleType: rate > 0 ? 'per_km' : 'actual',
                        unit: rate > 0 ? 'KM' : null,
                        bill: policy.car_bill_required || 'Yes',
                        photo: policy.car_photo_required || 'No',
                        includesFood: false
                    });
                }

                // Auto
                if (policy.auto_max || policy.auto_rate_km || policy.auto_min) {
                    const rate = parseFloat(policy.auto_rate_km) || 0;
                    ranges.push({
                        name: 'Auto',
                        min: parseFloat(policy.auto_min) || 0,
                        max: parseFloat(policy.auto_max) || 0,
                        rate: rate,
                        ruleType: rate > 0 ? 'per_km' : 'actual',
                        unit: rate > 0 ? 'KM' : null,
                        bill: policy.auto_bill_required || 'Yes',
                        photo: policy.auto_photo_required || 'No',
                        includesFood: false
                    });
                }

                // Bus categories
                if (policy.bus_categories) {
                    let busCat = policy.bus_categories;
                    if (typeof busCat === 'string') {
                        try { busCat = JSON.parse(busCat); } catch (e) { busCat = null; }
                    }
                    if (busCat && typeof busCat === 'object') {
                        const busNames = {
                            'general': 'General Bus',
                            'seater_ac': 'Seater AC',
                            'seater_nonac': 'Seater Non-AC',
                            'sleeper_ac': 'Sleeper AC',
                            'sleeper_nonac': 'Sleeper Non-AC'
                        };
                        Object.keys(busCat).forEach(key => {
                            if (busCat[key] && typeof busCat[key] === 'object') {
                                ranges.push({
                                    name: busNames[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()),
                                    min: parseFloat(busCat[key].min) || 0,
                                    max: parseFloat(busCat[key].max) || 0,
                                    rate: 0,
                                    ruleType: 'actual',
                                    unit: null,
                                    bill: busCat[key].bill_required || 'Yes',
                                    photo: busCat[key].photo_required || 'No',
                                    includesFood: false
                                });
                            }
                        });
                    }
                }

                // Train categories
                if (policy.train_categories) {
                    let trainCat = policy.train_categories;
                    if (typeof trainCat === 'string') {
                        try { trainCat = JSON.parse(trainCat); } catch (e) { trainCat = null; }
                    }
                    if (trainCat && typeof trainCat === 'object') {
                        const trainNames = {
                            'general': 'General',
                            'sleeper': 'Sleeper Class',
                            'ac3': '3AC',
                            'ac2': '2AC',
                            'ac1': '1AC'
                        };
                        Object.keys(trainCat).forEach(key => {
                            if (trainCat[key] && typeof trainCat[key] === 'object') {
                                ranges.push({
                                    name: trainNames[key] || key.replace(/_/g, ' ').toUpperCase(),
                                    min: parseFloat(trainCat[key].min) || 0,
                                    max: parseFloat(trainCat[key].max) || 0,
                                    rate: 0,
                                    ruleType: 'actual',
                                    unit: null,
                                    bill: trainCat[key].bill_required || 'Yes',
                                    photo: trainCat[key].photo_required || 'No',
                                    includesFood: false
                                });
                            }
                        });
                    }
                }

                // Flight categories
                if (policy.flight_categories) {
                    let flightCat = policy.flight_categories;
                    if (typeof flightCat === 'string') {
                        try { flightCat = JSON.parse(flightCat); } catch (e) { flightCat = null; }
                    }
                    if (flightCat && typeof flightCat === 'object') {
                        const flightNames = {
                            'economy': 'Economy',
                            'business': 'Business Class'
                        };
                        Object.keys(flightCat).forEach(key => {
                            if (flightCat[key] && typeof flightCat[key] === 'object') {
                                ranges.push({
                                    name: flightNames[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()),
                                    min: parseFloat(flightCat[key].min) || 0,
                                    max: parseFloat(flightCat[key].max) || 0,
                                    rate: 0,
                                    ruleType: 'actual',
                                    unit: null,
                                    bill: flightCat[key].bill_required || 'Yes',
                                    photo: flightCat[key].photo_required || 'No',
                                    includesFood: false
                                });
                            }
                        });
                    }
                }
            }
            else if (category === 'accommodation') {
                // Basic/Budget
                if (policy.basic_max || policy.basic_min) {
                    ranges.push({
                        name: 'Budget',
                        min: parseFloat(policy.basic_min) || 0,
                        max: parseFloat(policy.basic_max) || 0,
                        rate: 0,
                        ruleType: 'actual',
                        unit: null,
                        bill: policy.basic_bill_required || 'Yes',
                        photo: policy.basic_photo_required || 'No',
                        includesFood: policy.basic_includes_food === 'Yes' || policy.basic_includes_food === true || policy.basic_includes_food === 1
                    });
                    ranges.push({
                        name: '1-2 Star (Budget)',
                        min: parseFloat(policy.basic_min) || 0,
                        max: parseFloat(policy.basic_max) || 0,
                        rate: 0,
                        ruleType: 'actual',
                        unit: null,
                        bill: policy.basic_bill_required || 'Yes',
                        photo: policy.basic_photo_required || 'No',
                        includesFood: policy.basic_includes_food === 'Yes' || policy.basic_includes_food === true || policy.basic_includes_food === 1
                    });
                }

                // Deluxe/Business
                if (policy.deluxe_max || policy.deluxe_min) {
                    ranges.push({
                        name: 'Business',
                        min: parseFloat(policy.deluxe_min) || 0,
                        max: parseFloat(policy.deluxe_max) || 0,
                        rate: 0,
                        ruleType: 'actual',
                        unit: null,
                        bill: policy.deluxe_bill_required || 'Yes',
                        photo: policy.deluxe_photo_required || 'No',
                        includesFood: policy.deluxe_includes_food === 'Yes' || policy.deluxe_includes_food === true || policy.deluxe_includes_food === 1
                    });
                    ranges.push({
                        name: '3-4 Star (Business)',
                        min: parseFloat(policy.deluxe_min) || 0,
                        max: parseFloat(policy.deluxe_max) || 0,
                        rate: 0,
                        ruleType: 'actual',
                        unit: null,
                        bill: policy.deluxe_bill_required || 'Yes',
                        photo: policy.deluxe_photo_required || 'No',
                        includesFood: policy.deluxe_includes_food === 'Yes' || policy.deluxe_includes_food === true || policy.deluxe_includes_food === 1
                    });
                }

                // Premium
                if (policy.premium_max || policy.premium_min) {
                    ranges.push({
                        name: 'Premium',
                        min: parseFloat(policy.premium_min) || 0,
                        max: parseFloat(policy.premium_max) || 0,
                        rate: 0,
                        ruleType: 'actual',
                        unit: null,
                        bill: policy.premium_bill_required || 'Yes',
                        photo: policy.premium_photo_required || 'No',
                        includesFood: policy.premium_includes_food === 'Yes' || policy.premium_includes_food === true || policy.premium_includes_food === 1
                    });
                    ranges.push({
                        name: '5 Star (Premium)',
                        min: parseFloat(policy.premium_min) || 0,
                        max: parseFloat(policy.premium_max) || 0,
                        rate: 0,
                        ruleType: 'actual',
                        unit: null,
                        bill: policy.premium_bill_required || 'Yes',
                        photo: policy.premium_photo_required || 'No',
                        includesFood: policy.premium_includes_food === 'Yes' || policy.premium_includes_food === true || policy.premium_includes_food === 1
                    });
                }
            }
            else if (category === 'food') {
                const mealTypes = [
                    { key: 'breakfast', name: 'Breakfast' },
                    { key: 'lunch', name: 'Lunch' },
                    { key: 'dinner', name: 'Dinner' },
                    { key: 'two_meals', name: 'Two Meals' },
                    { key: 'three_meals', name: 'Three Meals' }
                ];

                mealTypes.forEach(({ key, name }) => {
                    if (policy[`${key}_max`] || policy[`${key}_min`]) {
                        ranges.push({
                            name: name,
                            min: parseFloat(policy[`${key}_min`]) || 0,
                            max: parseFloat(policy[`${key}_max`]) || 0,
                            rate: 0,
                            ruleType: 'actual',
                            unit: null,
                            bill: policy[`${key}_bill_required`] || policy.individual_bill_required || 'Yes',
                            photo: policy[`${key}_photo_required`] || policy.individual_photo_required || 'No',
                            includesFood: false
                        });
                    }
                });

                // Add combined names if they exist
                if (policy.two_meals_max || policy.two_meals_min) {
                    ranges.push({
                        name: 'Two Meals (Combined)',
                        min: parseFloat(policy.two_meals_min) || 0,
                        max: parseFloat(policy.two_meals_max) || 0,
                        rate: 0,
                        ruleType: 'actual',
                        unit: null,
                        bill: policy.two_meals_bill_required || policy.individual_bill_required || 'Yes',
                        photo: policy.two_meals_photo_required || policy.individual_photo_required || 'No',
                        includesFood: false
                    });
                }

                if (policy.three_meals_max || policy.three_meals_min) {
                    ranges.push({
                        name: 'Three Meals (Full Day)',
                        min: parseFloat(policy.three_meals_min) || 0,
                        max: parseFloat(policy.three_meals_max) || 0,
                        rate: 0,
                        ruleType: 'actual',
                        unit: null,
                        bill: policy.three_meals_bill_required || policy.individual_bill_required || 'Yes',
                        photo: policy.three_meals_photo_required || policy.individual_photo_required || 'No',
                        includesFood: false
                    });
                }
            }
            else if (category === 'custom') {
                // Parse policy_data for custom policies
                let policyData = policy.policy_data;
                if (typeof policyData === 'string') {
                    try { policyData = JSON.parse(policyData); } catch (e) { policyData = []; }
                }

                if (Array.isArray(policyData) && policyData.length > 0) {
                    policyData.forEach(rule => {
                        const ruleType = rule.type || 'actual';
                        const fields = rule.fields || {};
                        let rate = 0;
                        let unit = null;

                        switch (ruleType) {
                            case 'per_km':
                                rate = parseFloat(fields.rate_km || fields.rate || 0);
                                unit = 'KM';
                                break;
                            case 'per_unit':
                                rate = parseFloat(fields.rate_unit || fields.rate || 0);
                                unit = fields.unit || 'Unit';
                                break;
                            case 'per_day':
                                rate = parseFloat(fields.rate_day || fields.rate || 0);
                                unit = 'Day';
                                break;
                            case 'per_night':
                                rate = parseFloat(fields.rate_night || fields.rate || 0);
                                unit = 'Night';
                                break;
                            case 'per_hour':
                                rate = parseFloat(fields.rate_hour || fields.rate || 0);
                                unit = 'Hour';
                                break;
                        }

                        ranges.push({
                            name: rule.name || 'Custom Item',
                            min: parseFloat(fields.min || fields.min_amount || 0),
                            max: parseFloat(fields.max || fields.max_amount || 0),
                            rate: rate,
                            ruleType: ruleType,
                            unit: unit,
                            bill: rule.bill_required || 'Yes',
                            photo: rule.photo_required || 'No',
                            includesFood: false
                        });
                    });
                }
            }
            else {
                // For fixed policies (mobile, internet, entertainment, miscellaneous)
                if (policy.max_amount || policy.min_amount) {
                    ranges.push({
                        name: policy.policy_name || 'Standard',
                        min: parseFloat(policy.min_amount) || 0,
                        max: parseFloat(policy.max_amount) || 0,
                        rate: 0,
                        ruleType: 'actual',
                        unit: null,
                        bill: policy.bill_required || 'Yes',
                        photo: policy.photo_required || 'No',
                        includesFood: false
                    });
                }
            }

            // If no ranges found, add a default one
            if (ranges.length === 0) {
                ranges.push({
                    name: 'Standard',
                    min: 0,
                    max: parseFloat(policy.max_amount) || 0,
                    rate: 0,
                    ruleType: 'actual',
                    unit: null,
                    bill: policy.bill_required || 'Yes',
                    photo: policy.photo_required || 'No',
                    includesFood: false
                });
            }

            console.log('Extracted ranges:', ranges);
            return ranges;
        }


        // ============ SELECT RANGE FOR SUB-ENTRY - COMPLETELY FIXED ============
        function selectSubRange(e, entryId, subId, rangeName) {
            const targetBtn = e.target.closest('.sub-range-btn');
            if (!targetBtn) return;

            const entry = claimEntries.find(ev => ev.id === entryId);
            if (!entry) return;

            const subEntry = entry.subEntries.find(s => s.id === subId);
            if (!subEntry) return;

            // Get ALL data from button attributes
            const min = parseFloat(targetBtn.dataset.min) || 0;
            const max = parseFloat(targetBtn.dataset.max) || 0;
            const rate = parseFloat(targetBtn.dataset.rate) || 0;
            const ruleType = targetBtn.dataset.ruleType || 'actual';
            const unit = targetBtn.dataset.unit || '';
            const billRequired = targetBtn.dataset.bill || 'Yes';
            const photoRequired = targetBtn.dataset.photo || 'No';
            const includesFood = targetBtn.dataset.includesFood === 'true';

            console.log('selectSubRange - Button data:', {
                rangeName, min, max, rate, ruleType, unit, billRequired, photoRequired
            });

            subEntry.rangeName = rangeName;
            subEntry.range = {
                name: rangeName, min, max, rate, ruleType, unit,
                unitLabel: ruleType === 'per_km' ? 'Kilometers (KM)' :
                    ruleType === 'per_unit' ? (unit || 'Units') :
                        ruleType === 'per_day' ? 'Days' :
                            ruleType === 'per_night' ? 'Nights' :
                                ruleType === 'per_hour' ? 'Hours' : null,
                bill: billRequired, photo: photoRequired, includesFood
            };

            const subDiv = document.getElementById(subId);
            if (!subDiv) return;

            // Update button styles
            subDiv.querySelectorAll('.sub-range-btn').forEach(btn => {
                if (btn.dataset.rangeName === rangeName) {
                    btn.style.backgroundColor = '#2563eb';
                    btn.style.color = '#ffffff';
                    btn.style.borderColor = '#2563eb';
                    btn.style.boxShadow = '0 2px 8px rgba(37, 99, 235, 0.3)';
                } else {
                    btn.style.backgroundColor = '#f8fafc';
                    btn.style.color = '#334155';
                    btn.style.borderColor = '#cbd5e1';
                    btn.style.boxShadow = 'none';
                }
            });

            // Update bill/photo styling
            const billsSection = subDiv.querySelector('.sub-bills-section');
            const photosSection = subDiv.querySelector('.sub-photos-section');
            const billsRequired = subDiv.querySelector('.sub-bills-required');
            const photosRequired = subDiv.querySelector('.sub-photos-required');

            if (billsSection) {
                if (billRequired === 'Yes' || billRequired === 'Mandatory') {
                    billsSection.style.borderColor = '#fca5a5';
                    billsSection.style.background = '#fef2f2';
                } else {
                    billsSection.style.borderColor = '#edf2f7';
                    billsSection.style.background = '#fafcfd';
                }
            }
            if (billsRequired) {
                billsRequired.textContent = (billRequired === 'Yes' || billRequired === 'Mandatory') ? '* Required' : '(Optional)';
                billsRequired.style.color = (billRequired === 'Yes' || billRequired === 'Mandatory') ? '#ef4444' : '#94a3b8';
            }

            if (photosSection) {
                if (photoRequired === 'Yes' || photoRequired === 'Mandatory') {
                    photosSection.style.borderColor = '#fca5a5';
                    photosSection.style.background = '#fef2f2';
                } else {
                    photosSection.style.borderColor = '#edf2f7';
                    photosSection.style.background = '#fafcfd';
                }
            }
            if (photosRequired) {
                photosRequired.textContent = (photoRequired === 'Yes' || photoRequired === 'Mandatory') ? '* Required' : '(Optional)';
                photosRequired.style.color = (photoRequired === 'Yes' || photoRequired === 'Mandatory') ? '#ef4444' : '#94a3b8';
            }

            // ============ SHOW/HIDE INPUTS BASED ON RULE TYPE ============
            const kmGroup = subDiv.querySelector('.sub-km-group');
            const amountGroup = subDiv.querySelector('.sub-amount-group');
            const unitsGroup = subDiv.querySelector('.sub-units-group');

            // Hide all first
            if (kmGroup) kmGroup.style.display = 'none';
            if (unitsGroup) unitsGroup.style.display = 'none';
            if (amountGroup) amountGroup.style.display = 'none';

            // IMPORTANT: Check ruleType from the button's data attribute
            if (ruleType === 'per_km') {
                // PRIVATE VEHICLE: Show KM input
                console.log('Showing KM input for per_km range:', rangeName, 'rate:', rate);
                if (kmGroup) {
                    kmGroup.style.display = 'block';
                    kmGroup.querySelector('label').textContent = `📏 Distance (KM) @ ₹${rate}/KM`;
                }
                // Show amount as read-only (auto-calculated)
                if (amountGroup) {
                    amountGroup.style.display = 'block';
                    amountGroup.querySelector('label').textContent = `💵 Amount (Auto-calculated from KM)`;
                    const amtInput = amountGroup.querySelector('input');
                    if (amtInput) {
                        amtInput.readOnly = true;
                        amtInput.style.background = '#f0fdf4';
                        amtInput.style.cursor = 'not-allowed';
                        amtInput.placeholder = 'Enter KM above';
                    }
                }
            } else if (['per_unit', 'per_day', 'per_night', 'per_hour'].includes(ruleType)) {
                // UNIT-BASED: Show units input
                console.log('Showing units input for', ruleType, 'range:', rangeName);
                if (unitsGroup) {
                    unitsGroup.style.display = 'block';
                    const unitName = ruleType === 'per_day' ? 'Days' : ruleType === 'per_night' ? 'Nights' :
                        ruleType === 'per_hour' ? 'Hours' : (unit || 'Units');
                    unitsGroup.querySelector('label').textContent = `🔢 ${unitName} @ ₹${rate}/${unitName}`;
                }
                // Show amount as read-only
                if (amountGroup) {
                    amountGroup.style.display = 'block';
                    amountGroup.querySelector('label').textContent = `💵 Amount (Auto-calculated)`;
                    const amtInput = amountGroup.querySelector('input');
                    if (amtInput) {
                        amtInput.readOnly = true;
                        amtInput.style.background = '#f0fdf4';
                        amtInput.style.cursor = 'not-allowed';
                    }
                }
            } else {
                // ACTUAL AMOUNT: Show editable amount input
                console.log('Showing amount input for actual range:', rangeName);
                if (amountGroup) {
                    amountGroup.style.display = 'block';
                    let labelText = `💵 Amount (₹) <span style="color:#ef4444;">*</span>`;
                    if (min > 0 && max > 0) {
                        labelText = `💵 Amount (₹) (Range: ₹${min} - ₹${max}) <span style="color:#ef4444;">*</span>`;
                    } else if (max > 0) {
                        labelText = `💵 Amount (₹) (Max: ₹${max}) <span style="color:#ef4444;">*</span>`;
                    } else if (min > 0) {
                        labelText = `💵 Amount (₹) (Min: ₹${min}) <span style="color:#ef4444;">*</span>`;
                    }
                    amountGroup.querySelector('label').innerHTML = labelText;
                    const amtInput = amountGroup.querySelector('input');
                    if (amtInput) {
                        amtInput.readOnly = false;
                        amtInput.style.background = 'white';
                        amtInput.style.cursor = 'text';
                        amtInput.placeholder = '0.00';
                    }
                }
            }

            // Clear previous values
            if (subDiv.querySelector('.sub-km')) subDiv.querySelector('.sub-km').value = '';
            if (subDiv.querySelector('.sub-units')) subDiv.querySelector('.sub-units').value = '';
            if (subDiv.querySelector('.sub-amount')) subDiv.querySelector('.sub-amount').value = '';
            if (subDiv.querySelector('.sub-leg-amount')) subDiv.querySelector('.sub-leg-amount').textContent = '₹0.00';

            // Update range info
            const rangeInfoDiv = subDiv.querySelector('.sub-range-info');
            if (rangeInfoDiv) {
                let infoText = '';
                if (ruleType === 'per_km' && rate > 0) {
                    infoText = `⚙️ Rate: ₹${rate}/KM`;
                    if (max > 0) infoText += ` | Max Limit: ₹${max}`;
                    if (min > 0) infoText += ` | Min: ₹${min}`;
                } else if (['per_unit', 'per_day', 'per_night', 'per_hour'].includes(ruleType) && rate > 0) {
                    const uLabel = subEntry.range.unitLabel || 'Unit';
                    infoText = `⚙️ Rate: ₹${rate}/${uLabel}`;
                    if (max > 0) infoText += ` | Max: ₹${max}`;
                } else {
                    if (min > 0 && max > 0) infoText = `📋 Range: ₹${min} - ₹${max}`;
                    else if (max > 0) infoText = `📋 Max: ₹${max}`;
                    else if (min > 0) infoText = `📋 Min: ₹${min}`;
                    else infoText = `📋 Actual Amount`;
                }
                if (includesFood) infoText += ' | 🍽️ Food Included';
                rangeInfoDiv.textContent = infoText;
            }

            // Clear warnings
            const rangeWarningDiv = subDiv.querySelector('.sub-range-warning');
            if (rangeWarningDiv) {
                rangeWarningDiv.style.display = 'none';
                rangeWarningDiv.innerHTML = '';
            }

            recalculateSubEntry(entryId, subId);
        }


        // ============ RECALCULATE SUB-ENTRY AMOUNT (FIXED - STORE DATE PROPERLY) ============
        function recalculateSubEntry(entryId, subId) {
            const entry = claimEntries.find(e => e.id === entryId);
            if (!entry) return;

            const subEntry = entry.subEntries.find(s => s.id === subId);
            if (!subEntry || !subEntry.range) return;

            const subDiv = document.getElementById(subId);
            if (!subDiv) return;

            let amount = 0;
            let warningText = '';
            let calculationDetails = '';
            let usedUnitCalculation = false;

            const ruleType = subEntry.range.ruleType || 'actual';
            const rate = subEntry.range.rate || 0;
            const maxLimit = subEntry.range.max || 0;
            const minLimit = subEntry.range.min || 0;

            // Check if user entered amount directly
            const directAmount = parseFloat(subDiv.querySelector('.sub-amount')?.value) || 0;
            const hasDirectAmount = directAmount > 0;

            // Check if user entered units
            const kmValue = parseFloat(subDiv.querySelector('.sub-km')?.value) || 0;
            const unitsValue = parseFloat(subDiv.querySelector('.sub-units')?.value) || 0;
            const hasUnits = kmValue > 0 || unitsValue > 0;

            // Priority: If both are entered, use direct amount. If only units, calculate from units.
            if (hasDirectAmount) {
                amount = directAmount;
                usedUnitCalculation = false;

                if (amount > 0) {
                    calculationDetails = `Amount entered: ₹${amount.toFixed(2)}`;
                }

                // Validate against min-max of the range
                if (maxLimit > 0 && amount > maxLimit) {
                    warningText = `⚠️ Amount ₹${amount.toFixed(2)} exceeds maximum limit of ₹${maxLimit.toFixed(2)} for this item`;
                }
                if (minLimit > 0 && amount < minLimit && amount > 0) {
                    warningText = `⚠️ Amount ₹${amount.toFixed(2)} is below minimum limit of ₹${minLimit.toFixed(2)}`;
                }

            } else if (hasUnits && rate > 0 && ['per_km', 'per_unit', 'per_day', 'per_night', 'per_hour'].includes(ruleType)) {
                usedUnitCalculation = true;
                const units = ruleType === 'per_km' ? kmValue : unitsValue;
                amount = units * rate;

                const unitLabel = subEntry.range.unitLabel || 'Units';

                if (ruleType === 'per_km') {
                    subEntry.km = kmValue;
                    subEntry.ratePerKm = rate;
                    calculationDetails = `${kmValue} KM × ₹${rate}/KM = ₹${amount.toFixed(2)}`;
                } else {
                    subEntry.unitsConsumed = units;
                    subEntry.ratePerUnit = rate;
                    subEntry.unit = unitLabel;
                    calculationDetails = `${units} ${unitLabel} × ₹${rate}/${unitLabel} = ₹${amount.toFixed(2)}`;
                }

                if (maxLimit > 0 && amount > maxLimit) {
                    warningText = `⚠️ Calculated amount ₹${amount.toFixed(2)} exceeds maximum limit of ₹${maxLimit.toFixed(2)}`;
                    amount = maxLimit;
                }

                if (minLimit > 0 && amount < minLimit) {
                    warningText = `⚠️ Calculated amount ₹${amount.toFixed(2)} is below minimum limit of ₹${minLimit.toFixed(2)}`;
                }

            } else {
                amount = 0;
            }

            // Update sub entry data - MAKE SURE DATE IS STORED
            subEntry.amount = amount;
            subEntry.date = subDiv.querySelector('.sub-date')?.value || '';
            subEntry.from = subDiv.querySelector('.sub-from')?.value || '';
            subEntry.to = subDiv.querySelector('.sub-to')?.value || '';
            subEntry.checkinDate = subDiv.querySelector('.sub-checkin')?.value || '';
            subEntry.checkinTime = subDiv.querySelector('.sub-checkin-time')?.value || '12:00';
            subEntry.checkoutDate = subDiv.querySelector('.sub-checkout')?.value || '';
            subEntry.checkoutTime = subDiv.querySelector('.sub-checkout-time')?.value || '12:00';
            subEntry.vendor = subDiv.querySelector('.sub-vendor')?.value || '';
            subEntry.billNumber = subDiv.querySelector('.sub-bill')?.value || '';
            subEntry.description = subDiv.querySelector('.sub-desc')?.value || '';

            console.log('Sub-entry date stored:', subEntry.date, 'for subId:', subId); // Debug log

            // Update amount display
            const legAmount = subDiv.querySelector('.sub-leg-amount');
            if (legAmount) {
                legAmount.textContent = `₹${amount.toFixed(2)}`;
                if (calculationDetails) {
                    legAmount.title = calculationDetails;
                }
            }

            // Update range info
            const rangeInfoDiv = subDiv.querySelector('.sub-range-info');
            if (rangeInfoDiv && calculationDetails) {
                const baseInfo = rangeInfoDiv.innerHTML.split('| <strong>')[0] || rangeInfoDiv.innerHTML;
                rangeInfoDiv.innerHTML = `${baseInfo} | <strong>${calculationDetails}</strong>`;
            }

            // Show warnings
            const rangeWarningDiv = subDiv.querySelector('.sub-range-warning');
            if (rangeWarningDiv) {
                if (warningText) {
                    rangeWarningDiv.style.display = 'block';
                    rangeWarningDiv.innerHTML = warningText;
                } else {
                    rangeWarningDiv.style.display = 'none';
                }
            }

            // Clear the other input if one is used
            if (hasDirectAmount && hasUnits) {
                if (subDiv.querySelector('.sub-km')) subDiv.querySelector('.sub-km').value = '';
                if (subDiv.querySelector('.sub-units')) subDiv.querySelector('.sub-units').value = '';
            }

            // Calculate nights for accommodation
            if (entry.policy?.policy_category === 'accommodation') {
                calculateNights(entryId, subId);
            }

            // IMPORTANT: Also store date when travel date changes
            const dateInput = subDiv.querySelector('.sub-date');
            if (dateInput) {
                dateInput.addEventListener('change', function () {
                    subEntry.date = this.value;
                    console.log('Date changed to:', this.value); // Debug log
                    updateClaimTotal(entryId);
                });
            }

            updateClaimTotal(entryId);
            refreshAllWarnings();
        }

        function buildEntryLevelPeriodStatus(entry, totalAmount, card, allEntries) {
            const policy = entry?.policy;
            if (!policy) return null;

            const calcType = policy.calculation_type || 'per_claim';
            const maxAmount = parseFloat(policy.max_amount) || 0;
            const existingAmount = parseFloat(entry.existingAmount) || 0;
            const samePolicyEntries = (allEntries || []).filter(otherEntry => otherEntry?.policy && otherEntry.policy.reimbursement_policy_id === policy.reimbursement_policy_id);
            const policyTotalAmount = samePolicyEntries.reduce((sum, otherEntry) => {
                const entryTotal = (otherEntry.subEntries || []).reduce((subSum, sub) => subSum + (sub.amount || 0), 0);
                return sum + entryTotal;
            }, 0);
            const totalWithExisting = existingAmount + policyTotalAmount;
            const firstSub = entry.subEntries?.find(sub => sub.date || sub.checkinDate || sub.travelDate || sub.mealDate) || null;
            const entryDate = firstSub?.date || firstSub?.checkinDate || firstSub?.travelDate || firstSub?.mealDate || card?.querySelector('.entry-expense-from')?.value || '';

            if (maxAmount <= 0) {
                return null;
            }

            let detailText = '';
            let warningText = '';
            let isExceeded = false;

            if (calcType === 'per_claim') {
                isExceeded = totalWithExisting > maxAmount;
                detailText = `Claim total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)}`;
                warningText = isExceeded
                    ? `⚠️ Claim total ₹${totalWithExisting.toFixed(2)} exceeds per-claim limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            } else if (calcType === 'per_day') {
                isExceeded = totalWithExisting > maxAmount;
                detailText = `Daily total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)}`;
                warningText = isExceeded
                    ? `⚠️ Daily total ₹${totalWithExisting.toFixed(2)} exceeds per-day limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            } else if (calcType === 'per_month') {
                isExceeded = totalWithExisting > maxAmount;
                const monthName = entryDate ? new Date(entryDate).toLocaleString('default', { month: 'long' }) : 'this month';
                detailText = `Monthly total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)} for ${monthName}`;
                warningText = isExceeded
                    ? `⚠️ Monthly total ₹${totalWithExisting.toFixed(2)} exceeds per-month limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            } else if (calcType === 'per_quarter') {
                isExceeded = totalWithExisting > maxAmount;
                detailText = `Quarterly total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)}`;
                warningText = isExceeded
                    ? `⚠️ Quarterly total ₹${totalWithExisting.toFixed(2)} exceeds per-quarter limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            } else if (calcType === 'per_year') {
                isExceeded = totalWithExisting > maxAmount;
                const fyStart = entryDate ? (new Date(entryDate).getMonth() >= 3 ? new Date(entryDate).getFullYear() : new Date(entryDate).getFullYear() - 1) : new Date().getFullYear();
                detailText = `Yearly total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)} for FY ${fyStart}-${(fyStart + 1).toString().slice(-2)}`;
                warningText = isExceeded
                    ? `⚠️ Yearly total ₹${totalWithExisting.toFixed(2)} exceeds per-year limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            }

            return {
                detailText,
                warningText,
                isExceeded,
                maxAmount
            };
        }

        // ============ UPDATE CLAIM TOTAL & DETAILED BREAKDOWN ============
        function updateClaimTotal(entryId) {
            const entry = claimEntries.find(e => e.id === entryId);
            if (!entry) return;

            let totalAmount = 0;
            entry.subEntries.forEach(sub => {
                totalAmount += sub.amount || 0;
            });

            const card = document.getElementById(entryId);
            const totalDisplay = card?.querySelector('.entry-total-amount');
            const summaryMini = card?.querySelector('.entry-summary-mini');
            const subCount = card?.querySelector('.entry-sub-count');
            const warningDiv = card?.querySelector('.entry-calculation-warning');

            if (totalDisplay) totalDisplay.textContent = `₹${totalAmount.toFixed(2)}`;
            if (summaryMini) summaryMini.style.display = totalAmount > 0 ? 'block' : 'none';
            if (subCount) {
                const count = entry.subEntries.length;
                subCount.textContent = `${count} item${count !== 1 ? 's' : ''}`;
            }

            if (warningDiv && entry.policy) {
                const calcType = entry.policy.calculation_type || 'per_claim';
                const policyMaxAmount = parseFloat(entry.policy.max_amount) || 0;
                const frequencyType = entry.policy.frequency_type || 'unlimited';
                const frequencyValue = parseInt(entry.policy.frequency_value) || 1;

                let allWarnings = [];
                let breakdownInfo = [];

                // 1. Group sub-entries by (Date + Range) for current card and across form cards
                const dateRangeGroups = {};
                const dateMealGroups = {};

                entry.subEntries.forEach(sub => {
                    const subDate = sub.date || sub.checkinDate || sub.travelDate || sub.mealDate || card.querySelector('.entry-expense-from')?.value || '';
                    const rangeName = sub.rangeName || 'Standard';
                    const rangeMax = (sub.range && sub.range.max > 0) ? sub.range.max : (entry.policyUsageData?.range_max_limits ? (entry.policyUsageData.range_max_limits[rangeName] || 0) : policyMaxAmount);

                    if (subDate) {
                        const key = `${subDate}|${rangeName}`;
                        if (!dateRangeGroups[key]) {
                            let dbClaimed = 0;
                            if (entry.policyUsageData?.daily_range_usage && entry.policyUsageData.daily_range_usage[subDate]) {
                                dbClaimed = entry.policyUsageData.daily_range_usage[subDate][rangeName] || 0;
                            }
                            dateRangeGroups[key] = {
                                total: 0,
                                dbClaimed: dbClaimed,
                                max: rangeMax,
                                date: subDate,
                                rangeName: rangeName
                            };
                        }
                        dateRangeGroups[key].total += (sub.amount || 0);

                        if (entry.policy.policy_category === 'food') {
                            if (!dateMealGroups[subDate]) {
                                let dbMealCount = entry.policyUsageData?.daily_range_usage && entry.policyUsageData.daily_range_usage[subDate] ? (entry.policyUsageData.daily_range_usage[subDate]._meal_count || 0) : 0;
                                let dbMealTotal = entry.policyUsageData?.daily_range_usage && entry.policyUsageData.daily_range_usage[subDate] ? (entry.policyUsageData.daily_range_usage[subDate]._total || 0) : 0;
                                dateMealGroups[subDate] = { count: dbMealCount, total: dbMealTotal, date: subDate };
                            }
                            dateMealGroups[subDate].count += 1;
                            dateMealGroups[subDate].total += (sub.amount || 0);
                        }
                    }
                });

                // Also combine sub-entries from other form cards on same policy
                claimEntries.forEach(otherEntry => {
                    if (otherEntry.id === entryId) return;
                    if (!otherEntry.policy || otherEntry.policy.reimbursement_policy_id !== entry.policy.reimbursement_policy_id) return;
                    const otherCard = document.getElementById(otherEntry.id);

                    otherEntry.subEntries.forEach(otherSub => {
                        const otherDate = otherSub.date || otherSub.checkinDate || otherSub.travelDate || otherSub.mealDate || otherCard?.querySelector('.entry-expense-from')?.value || '';
                        const otherRange = otherSub.rangeName || 'Standard';
                        const key = `${otherDate}|${otherRange}`;

                        if (dateRangeGroups[key]) {
                            dateRangeGroups[key].total += (otherSub.amount || 0);
                        }
                        if (entry.policy.policy_category === 'food' && dateMealGroups[otherDate]) {
                            dateMealGroups[otherDate].count += 1;
                            dateMealGroups[otherDate].total += (otherSub.amount || 0);
                        }
                    });
                });

                const entryPeriodStatus = buildEntryLevelPeriodStatus(entry, totalAmount, card, claimEntries);

                // Check Range Limits for (Date + Range) - ONLY warn when SAME RANGE exceeds its limit
                Object.values(dateRangeGroups).forEach(group => {
                    const combined = group.dbClaimed + group.total;
                    const remaining = group.max > 0 ? Math.max(0, group.max - combined) : null;
                    const previousUsage = entry.policyUsageData?.range_usage_breakdown?.[group.date]?.[group.rangeName] || null;
                    const previousClaimAmount = previousUsage?.claimed || group.dbClaimed || 0;
                    const previousClaimDetail = previousUsage?.previous_claims?.length ? previousUsage.previous_claims[0] : null;
                    const isExceeded = group.max > 0 && combined > group.max;

                    // Save onto sub-entries matching this date + range
                    entry.subEntries.forEach(sub => {
                        const subDate = sub.date || sub.checkinDate || sub.travelDate || sub.mealDate || card.querySelector('.entry-expense-from')?.value || '';
                        const rName = sub.rangeName || 'Standard';
                        if (subDate === group.date && rName === group.rangeName) {
                            sub.rangeMax = group.max;
                            sub.rangeExistingClaimed = group.dbClaimed;
                            sub.rangeRemainingLimit = remaining;
                            sub.isExceeded = isExceeded;
                            sub.exceededAmount = sub.isExceeded ? (combined - group.max) : 0;

                            // Update individual sub-entry DOM
                            const subDiv = document.getElementById(sub.id);
                            if (subDiv) {
                                const rangeInfoDiv = subDiv.querySelector('.sub-range-info');
                                if (rangeInfoDiv) {
                                    rangeInfoDiv.innerHTML = `
                                                                                                        <div style="display:flex; flex-direction:column; gap:4px; padding:8px 10px; border-radius:10px; background:${isExceeded ? '#fef2f2' : '#eff6ff'}; border:1px solid ${isExceeded ? '#fecaca' : '#bfdbfe'};">
                                                                                                            <div style="display:flex; justify-content:space-between; align-items:center; gap:8px; font-size:0.72rem; font-weight:700; color:${isExceeded ? '#b91c1c' : '#1d4ed8'};">
                                                                                                                <span>${rName || 'Range'}</span>
                                                                                                                <span>${isExceeded ? '⚠️ Exceeded' : '✅ Within limit'}</span>
                                                                                                            </div>
                                                                                                            <div style="display:flex; justify-content:space-between; gap:8px; font-size:0.68rem; color:#475569;">
                                                                                                                <span>Previous claim</span>
                                                                                                                <span>₹${previousClaimAmount.toFixed(2)}</span>
                                                                                                            </div>
                                                                                                            <div style="display:flex; justify-content:space-between; gap:8px; font-size:0.68rem; color:#475569;">
                                                                                                                <span>Current claim</span>
                                                                                                                <span>₹${group.total.toFixed(2)}</span>
                                                                                                            </div>
                                                                                                            <div style="display:flex; justify-content:space-between; gap:8px; font-size:0.68rem; color:#475569;">
                                                                                                                <span>Remaining</span>
                                                                                                                <span>₹${remaining !== null ? remaining.toFixed(2) : '—'}</span>
                                                                                                            </div>
                                                                                                            ${entryPeriodStatus ? `<div style="margin-top:6px; padding:7px 8px; border-radius:8px; background:${entryPeriodStatus.isExceeded ? '#fef2f2' : '#f8fafc'}; border:1px solid ${entryPeriodStatus.isExceeded ? '#fecaca' : '#e2e8f0'};">
                                                                                                                <div style="font-size:0.67rem; font-weight:700; color:${entryPeriodStatus.isExceeded ? '#b91c1c' : '#2563eb'};">${entryPeriodStatus.isExceeded ? '⚠️ Period warning' : '📊 Claim period'}</div>
                                                                                                                <div style="font-size:0.66rem; color:#475569; margin-top:2px;">${entryPeriodStatus.detailText}</div>
                                                                                                            </div>` : ''}
                                                                                                            ${previousClaimDetail ? `<div style="font-size:0.67rem; color:#64748b;">Last used on ${formatDate(previousClaimDetail.date)}: ₹${previousClaimDetail.amount.toFixed(2)}</div>` : ''}
                                                                                                            ${isExceeded ? `<div style="font-size:0.67rem; color:#b91c1c; font-weight:700;">Exceeded by ₹${(combined - group.max).toFixed(2)}</div>` : ''}
                                                                                                        </div>`;
                                }
                                const rangeWarningDiv = subDiv.querySelector('.sub-range-warning');
                                if (rangeWarningDiv) {
                                    if (entryPeriodStatus?.isExceeded) {
                                        rangeWarningDiv.style.display = 'block';
                                        rangeWarningDiv.innerHTML = entryPeriodStatus.warningText;
                                    } else if (isExceeded) {
                                        rangeWarningDiv.style.display = 'block';
                                        rangeWarningDiv.innerHTML = `⚠️ ${rName} on ${formatDate(subDate)} exceeds max of ₹${group.max.toFixed(2)} by ₹${(combined - group.max).toFixed(2)}`;
                                    } else {
                                        rangeWarningDiv.style.display = 'none';
                                    }
                                }
                            }
                        }
                    });

                    if (group.max > 0) {
                        if (combined > group.max) {
                            // Only add warning if this specific range exceeds its own max
                            allWarnings.push(`⚠️ ${group.rangeName} on ${formatDate(group.date)}: ₹${combined.toFixed(2)} exceeds max ₹${group.max.toFixed(2)} by ₹${(combined - group.max).toFixed(2)}`);
                        } else {
                            if (group.dbClaimed > 0) {
                                breakdownInfo.push(`🔹 ${group.rangeName} (${formatDate(group.date)}): Claimed ₹${combined.toFixed(2)} • Remaining ₹${remaining.toFixed(2)} • Max ₹${group.max.toFixed(2)}`);
                            } else {
                                breakdownInfo.push(`🔹 ${group.rangeName} (${formatDate(group.date)}): Remaining ₹${remaining.toFixed(2)} • Max ₹${group.max.toFixed(2)}`);
                            }
                        }
                    }
                });

                // Check Food Policy Meal Combination limits
                if (entry.policy.policy_category === 'food') {
                    const twoMax = parseFloat(entry.policyUsageData?.food_limits?.two_meals_max || entry.policy.two_meals_max) || 0;
                    const threeMax = parseFloat(entry.policyUsageData?.food_limits?.three_meals_max || entry.policy.three_meals_max) || 0;

                    Object.values(dateMealGroups).forEach(mg => {
                        if (mg.count === 2 && twoMax > 0) {
                            if (mg.total > twoMax) {
                                allWarnings.push(`⚠️ 2 Meals total ₹${mg.total.toFixed(2)} on ${formatDate(mg.date)} exceeds two-meals limit of ₹${twoMax.toFixed(2)} by ₹${(mg.total - twoMax).toFixed(2)}`);
                            } else {
                                breakdownInfo.push(`🍽️ 2 Meals Combined (${formatDate(mg.date)}): Remaining ₹${(twoMax - mg.total).toFixed(2)} (Max: ₹${twoMax.toFixed(2)})`);
                            }
                        } else if (mg.count >= 3 && threeMax > 0) {
                            if (mg.total > threeMax) {
                                allWarnings.push(`⚠️ 3 Meals total ₹${mg.total.toFixed(2)} on ${formatDate(mg.date)} exceeds full-day limit of ₹${threeMax.toFixed(2)} by ₹${(mg.total - threeMax).toFixed(2)}`);
                            } else {
                                breakdownInfo.push(`🍽️ 3 Meals Full Day (${formatDate(mg.date)}): Remaining ₹${(threeMax - mg.total).toFixed(2)} (Max: ₹${threeMax.toFixed(2)})`);
                            }
                        }
                    });
                }

                // Check Calculation Type Limits - FIXED: Don't show per_day warning across different ranges
                const effMax = getEffectiveMaxForEntry(entry) || policyMaxAmount;
                const existingAmt = parseFloat(entry.existingAmount) || 0;

                if (calcType === 'per_day') {
                    // For per_day, evaluate PER DATE AND PER RANGE (not total across ranges)
                    // The range-specific warnings are already handled in dateRangeGroups above
                    // We only need to show the per-day limit if there's a specific range that exceeds its max
                    // The warning is already added in dateRangeGroups loop above
                } else if (calcType !== 'per_claim') {
                    let activeCardsTotal = 0;
                    claimEntries.forEach(oe => {
                        if (oe.policy && oe.policy.reimbursement_policy_id === entry.policy.reimbursement_policy_id) {
                            const oCard = document.getElementById(oe.id);
                            activeCardsTotal += parseFloat(oCard?.querySelector('.entry-total-amount')?.textContent.replace('₹', '')) || 0;
                        }
                    });

                    const totalWithExisting = existingAmt + activeCardsTotal;
                    const remainingLimit = effMax > 0 ? Math.max(0, effMax - totalWithExisting) : null;

                    if (effMax > 0) {
                        if (totalWithExisting > effMax) {
                            // Only for non-per_day calculation types
                            allWarnings.push(`⚠️ Period Total (including ₹${existingAmt.toFixed(2)} existing) ₹${totalWithExisting.toFixed(2)} exceeds ${calcType.replace('_', ' ')} limit of ₹${effMax.toFixed(2)} by ₹${(totalWithExisting - effMax).toFixed(2)}`);
                        } else {
                            breakdownInfo.push(`📆 Policy ${calcType.replace('_', ' ')} Limit: Remaining ₹${remainingLimit.toFixed(2)} (Used: ₹${totalWithExisting.toFixed(2)} / Max: ₹${effMax.toFixed(2)})`);
                        }
                    }
                }

                // Check Frequency Limits
                if (frequencyType !== 'unlimited' && frequencyValue > 0) {
                    const existingCount = parseInt(entry.existingCount || entry.policy.existing_claim_count) || 0;
                    let currentClaimBatches = 0;
                    claimEntries.forEach(oe => {
                        if (oe.policy && oe.policy.reimbursement_policy_id === entry.policy.reimbursement_policy_id) {
                            currentClaimBatches += 1;
                        }
                    });

                    const totalClaimsCount = existingCount + currentClaimBatches;
                    const remainingClaims = Math.max(0, frequencyValue - totalClaimsCount);

                    if (totalClaimsCount > frequencyValue) {
                        allWarnings.push(`🚫 Frequency limit exceeded! You have submitted ${existingCount} claim(s) for this ${frequencyType}. Maximum allowed is ${frequencyValue} claim(s) per ${frequencyType}.`);
                    } else {
                        breakdownInfo.push(`🔢 Claim Frequency: ${totalClaimsCount} / ${frequencyValue} claims used (${remainingClaims} available for this ${frequencyType})`);
                    }
                }

                // Render warning div
                if (warningDiv) {
                    let html = '';
                    if (breakdownInfo.length > 0) {
                        html += `<div style="background:#eff6ff; color:#1e40af; border:1px solid #bfdbfe; border-radius:8px; padding:10px 14px; margin-top:8px; font-weight:600; font-size:0.83rem; line-height:1.5;">${breakdownInfo.join('<br>')}</div>`;
                    }
                    if (allWarnings.length > 0) {
                        html += allWarnings.map(w => `<div style="background:#fef2f2; color:#991b1b; border:1px solid #fecaca; border-radius:8px; padding:10px 14px; margin-top:8px; font-weight:600; font-size:0.83rem; line-height:1.5;">${w}</div>`).join('');
                    } else if (totalAmount > 0) {
                        html += `<div style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; border-radius:8px; padding:8px 12px; margin-top:8px; font-weight:600; font-size:0.82rem;">✅ All limits are within range.</div>`;
                    }
                    warningDiv.innerHTML = html;
                }
            }
            refreshAllWarnings();
            updateSummary();
        }

        // ============ SELECT RANGE FOR SUB-ENTRY - FIXED ============
        function selectSubRange(e, entryId, subId, rangeName) {
            const targetBtn = e.target.closest('.sub-range-btn');
            if (!targetBtn) return;

            const entry = claimEntries.find(ev => ev.id === entryId);
            if (!entry) return;

            const subEntry = entry.subEntries.find(s => s.id === subId);
            if (!subEntry) return;

            // Get ALL data from button attributes
            const min = parseFloat(targetBtn.dataset.min) || 0;
            const max = parseFloat(targetBtn.dataset.max) || 0;
            const rate = parseFloat(targetBtn.dataset.rate) || 0;
            const ruleType = targetBtn.dataset.ruleType || 'actual';
            const unit = targetBtn.dataset.unit || '';
            const billRequired = targetBtn.dataset.bill || 'Yes';
            const photoRequired = targetBtn.dataset.photo || 'No';
            const includesFood = targetBtn.dataset.includesFood === 'true';

            subEntry.rangeName = rangeName;
            subEntry.range = {
                name: rangeName, min, max, rate, ruleType, unit,
                unitLabel: ruleType === 'per_km' ? 'Kilometers (KM)' :
                    ruleType === 'per_unit' ? (unit || 'Units') :
                        ruleType === 'per_day' ? 'Days' :
                            ruleType === 'per_night' ? 'Nights' :
                                ruleType === 'per_hour' ? 'Hours' : null,
                bill: billRequired, photo: photoRequired, includesFood
            };

            const subDiv = document.getElementById(subId);
            if (!subDiv) return;

            // Update button styles
            subDiv.querySelectorAll('.sub-range-btn').forEach(btn => {
                if (btn.dataset.rangeName === rangeName) {
                    btn.style.backgroundColor = '#2563eb';
                    btn.style.color = '#ffffff';
                    btn.style.borderColor = '#2563eb';
                    btn.style.boxShadow = '0 2px 8px rgba(37, 99, 235, 0.3)';
                } else {
                    btn.style.backgroundColor = '#f8fafc';
                    btn.style.color = '#334155';
                    btn.style.borderColor = '#cbd5e1';
                    btn.style.boxShadow = 'none';
                }
            });

            // Update bill/photo styling
            const billsSection = subDiv.querySelector('.sub-bills-section');
            const photosSection = subDiv.querySelector('.sub-photos-section');
            const billsRequired = subDiv.querySelector('.sub-bills-required');
            const photosRequired = subDiv.querySelector('.sub-photos-required');

            if (billsSection) {
                if (billRequired === 'Yes' || billRequired === 'Mandatory') {
                    billsSection.style.borderColor = '#fca5a5';
                    billsSection.style.background = '#fef2f2';
                } else {
                    billsSection.style.borderColor = '#edf2f7';
                    billsSection.style.background = '#fafcfd';
                }
            }
            if (billsRequired) {
                billsRequired.textContent = (billRequired === 'Yes' || billRequired === 'Mandatory') ? '* Required' : '(Optional)';
                billsRequired.style.color = (billRequired === 'Yes' || billRequired === 'Mandatory') ? '#ef4444' : '#94a3b8';
            }

            if (photosSection) {
                if (photoRequired === 'Yes' || photoRequired === 'Mandatory') {
                    photosSection.style.borderColor = '#fca5a5';
                    photosSection.style.background = '#fef2f2';
                } else {
                    photosSection.style.borderColor = '#edf2f7';
                    photosSection.style.background = '#fafcfd';
                }
            }
            if (photosRequired) {
                photosRequired.textContent = (photoRequired === 'Yes' || photoRequired === 'Mandatory') ? '* Required' : '(Optional)';
                photosRequired.style.color = (photoRequired === 'Yes' || photoRequired === 'Mandatory') ? '#ef4444' : '#94a3b8';
            }

            // SHOW/HIDE INPUTS BASED ON RULE TYPE
            const kmGroup = subDiv.querySelector('.sub-km-group');
            const amountGroup = subDiv.querySelector('.sub-amount-group');
            const unitsGroup = subDiv.querySelector('.sub-units-group');

            // Hide all first
            if (kmGroup) kmGroup.style.display = 'none';
            if (unitsGroup) unitsGroup.style.display = 'none';
            if (amountGroup) amountGroup.style.display = 'none';

            if (ruleType === 'per_km') {
                if (kmGroup) {
                    kmGroup.style.display = 'block';
                    kmGroup.querySelector('label').textContent = `📏 Distance (KM) @ ₹${rate}/KM`;
                    // Add input listener to auto-calculate amount
                    const kmInput = kmGroup.querySelector('input');
                    if (kmInput) {
                        kmInput.addEventListener('input', function () {
                            const km = parseFloat(this.value) || 0;
                            const calculated = km * rate;
                            const amtInput = subDiv.querySelector('.sub-amount-group input');
                            if (amtInput) {
                                amtInput.value = calculated > 0 ? calculated.toFixed(2) : '';
                                // Trigger recalculation
                                recalculateSubEntry(entryId, subId);
                            }
                        });
                    }
                }
                if (amountGroup) {
                    amountGroup.style.display = 'block';
                    amountGroup.querySelector('label').textContent = `💵 Amount (Auto-calculated from KM)`;
                    const amtInput = amountGroup.querySelector('input');
                    if (amtInput) {
                        amtInput.readOnly = true;
                        amtInput.style.background = '#f0fdf4';
                        amtInput.style.cursor = 'not-allowed';
                        amtInput.placeholder = 'Enter KM above';
                        amtInput.value = '';
                    }
                }
            } else if (['per_unit', 'per_day', 'per_night', 'per_hour'].includes(ruleType)) {
                if (unitsGroup) {
                    unitsGroup.style.display = 'block';
                    const unitName = ruleType === 'per_day' ? 'Days' : ruleType === 'per_night' ? 'Nights' :
                        ruleType === 'per_hour' ? 'Hours' : (unit || 'Units');
                    unitsGroup.querySelector('label').textContent = `🔢 ${unitName} @ ₹${rate}/${unitName}`;
                    const unitsInput = unitsGroup.querySelector('input');
                    if (unitsInput) {
                        unitsInput.addEventListener('input', function () {
                            const val = parseFloat(this.value) || 0;
                            const calculated = val * rate;
                            const amtInput = subDiv.querySelector('.sub-amount-group input');
                            if (amtInput) {
                                amtInput.value = calculated > 0 ? calculated.toFixed(2) : '';
                                recalculateSubEntry(entryId, subId);
                            }
                        });
                    }
                }
                if (amountGroup) {
                    amountGroup.style.display = 'block';
                    amountGroup.querySelector('label').textContent = `💵 Amount (Auto-calculated)`;
                    const amtInput = amountGroup.querySelector('input');
                    if (amtInput) {
                        amtInput.readOnly = true;
                        amtInput.style.background = '#f0fdf4';
                        amtInput.style.cursor = 'not-allowed';
                        amtInput.value = '';
                    }
                }
            } else {
                if (amountGroup) {
                    amountGroup.style.display = 'block';
                    let labelText = `💵 Amount (₹) <span style="color:#ef4444;">*</span>`;
                    if (min > 0 && max > 0) {
                        labelText = `💵 Amount (₹) (Range: ₹${min} - ₹${max}) <span style="color:#ef4444;">*</span>`;
                    } else if (max > 0) {
                        labelText = `💵 Amount (₹) (Max: ₹${max}) <span style="color:#ef4444;">*</span>`;
                    } else if (min > 0) {
                        labelText = `💵 Amount (₹) (Min: ₹${min}) <span style="color:#ef4444;">*</span>`;
                    }
                    amountGroup.querySelector('label').innerHTML = labelText;
                    const amtInput = amountGroup.querySelector('input');
                    if (amtInput) {
                        amtInput.readOnly = false;
                        amtInput.style.background = 'white';
                        amtInput.style.cursor = 'text';
                        amtInput.placeholder = '0.00';
                        amtInput.value = '';
                    }
                }
            }

            // Clear previous values
            if (subDiv.querySelector('.sub-km')) subDiv.querySelector('.sub-km').value = '';
            if (subDiv.querySelector('.sub-units')) subDiv.querySelector('.sub-units').value = '';
            if (subDiv.querySelector('.sub-amount')) subDiv.querySelector('.sub-amount').value = '';
            if (subDiv.querySelector('.sub-leg-amount')) subDiv.querySelector('.sub-leg-amount').textContent = '₹0.00';

            // Update range info
            const rangeInfoDiv = subDiv.querySelector('.sub-range-info');
            if (rangeInfoDiv) {
                let infoText = '';
                if (ruleType === 'per_km' && rate > 0) {
                    infoText = `⚙️ Rate: ₹${rate}/KM`;
                    if (max > 0) infoText += ` | Max Limit: ₹${max}`;
                    if (min > 0) infoText += ` | Min: ₹${min}`;
                } else if (['per_unit', 'per_day', 'per_night', 'per_hour'].includes(ruleType) && rate > 0) {
                    const uLabel = subEntry.range.unitLabel || 'Unit';
                    infoText = `⚙️ Rate: ₹${rate}/${uLabel}`;
                    if (max > 0) infoText += ` | Max: ₹${max}`;
                } else {
                    if (min > 0 && max > 0) infoText = `📋 Range: ₹${min} - ₹${max}`;
                    else if (max > 0) infoText = `📋 Max: ₹${max}`;
                    else if (min > 0) infoText = `📋 Min: ₹${min}`;
                    else infoText = `📋 Actual Amount`;
                }
                if (includesFood) infoText += ' | 🍽️ Food Included';
                rangeInfoDiv.textContent = infoText;
            }

            const rangeWarningDiv = subDiv.querySelector('.sub-range-warning');
            if (rangeWarningDiv) {
                rangeWarningDiv.style.display = 'none';
                rangeWarningDiv.innerHTML = '';
            }

            recalculateSubEntry(entryId, subId);
        }

        // ============ RECALCULATE SUB-ENTRY AMOUNT - FIXED WITH AMOUNT INPUT UPDATE ============
        function recalculateSubEntry(entryId, subId) {
            const entry = claimEntries.find(e => e.id === entryId);
            if (!entry) return;

            const subEntry = entry.subEntries.find(s => s.id === subId);
            if (!subEntry || !subEntry.range) return;

            const subDiv = document.getElementById(subId);
            if (!subDiv) return;

            let amount = 0;
            let warningText = '';
            let calculationDetails = '';
            let usedUnitCalculation = false;

            const ruleType = subEntry.range.ruleType || 'actual';
            const rate = subEntry.range.rate || 0;
            const maxLimit = subEntry.range.max || 0;
            const minLimit = subEntry.range.min || 0;

            const amountInput = subDiv.querySelector('.sub-amount');
            const kmInput = subDiv.querySelector('.sub-km');
            const unitsInput = subDiv.querySelector('.sub-units');

            // Check if user entered units (KM or units)
            const kmValue = parseFloat(kmInput?.value) || 0;
            const unitsValue = parseFloat(unitsInput?.value) || 0;
            const directAmount = parseFloat(amountInput?.value) || 0;

            // Priority: If direct amount is entered and not read-only, use it
            // For per_km, amount input is read-only, so we use KM calculation
            const isReadOnlyAmount = amountInput?.readOnly || false;

            if (isReadOnlyAmount) {
                // Auto-calculated field
                if (ruleType === 'per_km' && kmValue > 0 && rate > 0) {
                    amount = kmValue * rate;
                    usedUnitCalculation = true;
                    subEntry.km = kmValue;
                    subEntry.ratePerKm = rate;
                    calculationDetails = `${kmValue} KM × ₹${rate}/KM = ₹${amount.toFixed(2)}`;
                    // Update the amount input
                    if (amountInput) {
                        amountInput.value = amount.toFixed(2);
                    }
                } else if (['per_unit', 'per_day', 'per_night', 'per_hour'].includes(ruleType) && unitsValue > 0 && rate > 0) {
                    amount = unitsValue * rate;
                    usedUnitCalculation = true;
                    const unitLabel = subEntry.range.unitLabel || 'Units';
                    subEntry.unitsConsumed = unitsValue;
                    subEntry.ratePerUnit = rate;
                    subEntry.unit = unitLabel;
                    calculationDetails = `${unitsValue} ${unitLabel} × ₹${rate}/${unitLabel} = ₹${amount.toFixed(2)}`;
                    if (amountInput) {
                        amountInput.value = amount.toFixed(2);
                    }
                } else {
                    // No units entered
                    amount = 0;
                    if (amountInput) {
                        amountInput.value = '';
                    }
                }
            } else if (directAmount > 0) {
                // User entered amount directly
                amount = directAmount;
                usedUnitCalculation = false;
                calculationDetails = `Amount entered: ₹${amount.toFixed(2)}`;
            }

            // Validate against min-max
            if (amount > 0) {
                if (maxLimit > 0 && amount > maxLimit) {
                    warningText = `⚠️ Amount ₹${amount.toFixed(2)} exceeds maximum limit of ₹${maxLimit.toFixed(2)} for this item`;
                }
                if (minLimit > 0 && amount < minLimit) {
                    warningText = `⚠️ Amount ₹${amount.toFixed(2)} is below minimum limit of ₹${minLimit.toFixed(2)}`;
                }
            }

            // Update sub entry data
            subEntry.amount = amount;
            subEntry.date = subDiv.querySelector('.sub-date')?.value || '';
            subEntry.from = subDiv.querySelector('.sub-from')?.value || '';
            subEntry.to = subDiv.querySelector('.sub-to')?.value || '';
            subEntry.checkinDate = subDiv.querySelector('.sub-checkin')?.value || '';
            subEntry.checkinTime = subDiv.querySelector('.sub-checkin-time')?.value || '12:00';
            subEntry.checkoutDate = subDiv.querySelector('.sub-checkout')?.value || '';
            subEntry.checkoutTime = subDiv.querySelector('.sub-checkout-time')?.value || '12:00';
            subEntry.vendor = subDiv.querySelector('.sub-vendor')?.value || '';
            subEntry.billNumber = subDiv.querySelector('.sub-bill')?.value || '';
            subEntry.description = subDiv.querySelector('.sub-desc')?.value || '';

            // Update amount display
            const legAmount = subDiv.querySelector('.sub-leg-amount');
            if (legAmount) {
                legAmount.textContent = `₹${amount.toFixed(2)}`;
                if (calculationDetails) {
                    legAmount.title = calculationDetails;
                }
            }

            // Update range info with calculation details
            const rangeInfoDiv = subDiv.querySelector('.sub-range-info');
            if (rangeInfoDiv && calculationDetails) {
                // Keep the base info and add calculation details
                const currentText = rangeInfoDiv.textContent || '';
                // Only update if we have a calculation
                if (amount > 0) {
                    rangeInfoDiv.innerHTML = `${currentText.split('|')[0] || currentText} | <strong>${calculationDetails}</strong>`;
                }
            }

            // Show warnings
            const rangeWarningDiv = subDiv.querySelector('.sub-range-warning');
            if (rangeWarningDiv) {
                if (warningText) {
                    rangeWarningDiv.style.display = 'block';
                    rangeWarningDiv.innerHTML = warningText;
                } else {
                    rangeWarningDiv.style.display = 'none';
                }
            }

            // Calculate nights for accommodation
            if (entry.policy?.policy_category === 'accommodation') {
                calculateNights(entryId, subId);
            }

            // Date change listener
            const dateInput = subDiv.querySelector('.sub-date');
            if (dateInput) {
                dateInput.addEventListener('change', function () {
                    subEntry.date = this.value;
                    updateClaimTotal(entryId);
                });
            }

            // Also listen for direct amount changes on editable fields
            if (amountInput && !amountInput.readOnly) {
                amountInput.addEventListener('input', function () {
                    recalculateSubEntry(entryId, subId);
                });
            }

            updateClaimTotal(entryId);
            refreshAllWarnings();
        }

        // ============ FIX: buildEntryLevelPeriodStatus - For Accommodation multi-day limit ============
        function buildEntryLevelPeriodStatus(entry, totalAmount, card, allEntries) {
            const policy = entry?.policy;
            if (!policy) return null;

            const calcType = policy.calculation_type || 'per_claim';
            let maxAmount = parseFloat(policy.max_amount) || 0;
            const existingAmount = parseFloat(entry.existingAmount) || 0;

            // For accommodation: multiply max by number of nights/days if it's per_day or per_night
            if (policy.policy_category === 'accommodation') {
                // Calculate total nights from all sub-entries
                let totalNights = 0;
                let totalDays = 0;
                entry.subEntries.forEach(sub => {
                    if (sub.nights > 0) {
                        totalNights += sub.nights;
                    }
                    // Also check if there are multiple days
                    if (sub.checkinDate && sub.checkoutDate) {
                        const diff = (new Date(sub.checkoutDate) - new Date(sub.checkinDate)) / (1000 * 60 * 60 * 24);
                        if (diff > 0) totalDays = Math.ceil(diff) + 1;
                    }
                });

                // If per_day or per_night, multiply max by number of nights/days
                if (calcType === 'per_day' || calcType === 'per_night') {
                    const multiplier = Math.max(totalNights, totalDays, 1);
                    maxAmount = maxAmount * multiplier;
                }
            }

            const samePolicyEntries = (allEntries || []).filter(otherEntry =>
                otherEntry?.policy &&
                otherEntry.policy.reimbursement_policy_id === policy.reimbursement_policy_id
            );

            const policyTotalAmount = samePolicyEntries.reduce((sum, otherEntry) => {
                const entryTotal = (otherEntry.subEntries || []).reduce((subSum, sub) => subSum + (sub.amount || 0), 0);
                return sum + entryTotal;
            }, 0);

            const totalWithExisting = existingAmount + policyTotalAmount;
            const firstSub = entry.subEntries?.find(sub => sub.date || sub.checkinDate || sub.travelDate || sub.mealDate) || null;
            const entryDate = firstSub?.date || firstSub?.checkinDate || firstSub?.travelDate || firstSub?.mealDate || card?.querySelector('.entry-expense-from')?.value || '';

            if (maxAmount <= 0) {
                return null;
            }

            let detailText = '';
            let warningText = '';
            let isExceeded = false;

            if (calcType === 'per_claim') {
                isExceeded = totalWithExisting > maxAmount;
                detailText = `Claim total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)}`;
                warningText = isExceeded
                    ? `⚠️ Claim total ₹${totalWithExisting.toFixed(2)} exceeds per-claim limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            } else if (calcType === 'per_day' || calcType === 'per_night') {
                isExceeded = totalWithExisting > maxAmount;
                const periodLabel = calcType === 'per_day' ? 'day' : 'night';
                detailText = `${calcType === 'per_day' ? 'Daily' : 'Nightly'} total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)}`;
                warningText = isExceeded
                    ? `⚠️ ${calcType === 'per_day' ? 'Daily' : 'Nightly'} total ₹${totalWithExisting.toFixed(2)} exceeds ${periodLabel} limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            } else if (calcType === 'per_month') {
                isExceeded = totalWithExisting > maxAmount;
                const monthName = entryDate ? new Date(entryDate).toLocaleString('default', { month: 'long' }) : 'this month';
                detailText = `Monthly total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)} for ${monthName}`;
                warningText = isExceeded
                    ? `⚠️ Monthly total ₹${totalWithExisting.toFixed(2)} exceeds per-month limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            } else if (calcType === 'per_quarter') {
                isExceeded = totalWithExisting > maxAmount;
                detailText = `Quarterly total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)}`;
                warningText = isExceeded
                    ? `⚠️ Quarterly total ₹${totalWithExisting.toFixed(2)} exceeds per-quarter limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            } else if (calcType === 'per_year') {
                isExceeded = totalWithExisting > maxAmount;
                const fyStart = entryDate ? (new Date(entryDate).getMonth() >= 3 ? new Date(entryDate).getFullYear() : new Date(entryDate).getFullYear() - 1) : new Date().getFullYear();
                detailText = `Yearly total ₹${totalWithExisting.toFixed(2)} / Max ₹${maxAmount.toFixed(2)} for FY ${fyStart}-${(fyStart + 1).toString().slice(-2)}`;
                warningText = isExceeded
                    ? `⚠️ Yearly total ₹${totalWithExisting.toFixed(2)} exceeds per-year limit of ₹${maxAmount.toFixed(2)} by ₹${(totalWithExisting - maxAmount).toFixed(2)}`
                    : '';
            }

            return {
                detailText,
                warningText,
                isExceeded,
                maxAmount
            };
        }

        // ============ FORMAT DATE HELPER ============
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-IN', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        }

        // ============ CALCULATE NIGHTS FOR ACCOMMODATION ============
        function calculateNights(entryId, subId) {
            const subDiv = document.getElementById(subId);
            if (!subDiv) return;

            const checkinDate = subDiv.querySelector('.sub-checkin')?.value;
            const checkinTime = subDiv.querySelector('.sub-checkin-time')?.value || '12:00';
            const checkoutDate = subDiv.querySelector('.sub-checkout')?.value;
            const checkoutTime = subDiv.querySelector('.sub-checkout-time')?.value || '12:00';

            if (checkinDate && checkoutDate) {
                const checkinDateTime = new Date(`${checkinDate}T${checkinTime}:00`);
                const checkoutDateTime = new Date(`${checkoutDate}T${checkoutTime}:00`);
                const diffMs = checkoutDateTime - checkinDateTime;

                if (diffMs > 0) {
                    const diffHours = diffMs / (1000 * 60 * 60);
                    const fullDays = Math.floor(diffHours / 24);
                    const remainingHours = diffHours % 24;

                    let nights = fullDays;
                    let displayText = '';

                    if (remainingHours > 0) {
                        nights = fullDays + 1;
                        displayText = `${nights} night${nights > 1 ? 's' : ''} (${fullDays}d ${Math.round(remainingHours)}h)`;
                    } else if (fullDays === 0) {
                        nights = 1;
                        displayText = `1 night (${Math.round(diffHours)} hrs)`;
                    } else {
                        displayText = `${nights} night${nights > 1 ? 's' : ''}`;
                    }

                    const nightsInput = subDiv.querySelector('.sub-nights');
                    if (nightsInput) {
                        nightsInput.value = displayText;
                    }

                    // Store nights in sub entry
                    const entry = claimEntries.find(e => e.id === entryId);
                    if (entry) {
                        const subEntry = entry.subEntries.find(s => s.id === subId);
                        if (subEntry) {
                            subEntry.nights = nights;
                            subEntry.checkinDate = checkinDate;
                            subEntry.checkinTime = checkinTime;
                            subEntry.checkoutDate = checkoutDate;
                            subEntry.checkoutTime = checkoutTime;
                        }
                    }
                }
            }
        }

        // ============ REMOVE SUB-ENTRY ============
        function removeSubEntry(entryId, subId) {
            const element = document.getElementById(subId);
            if (element) element.remove();
            const entry = claimEntries.find(e => e.id === entryId);
            if (entry) {
                entry.subEntries = entry.subEntries.filter(s => s.id !== subId);
                updateClaimTotal(entryId);
            }
        }

        // ============ SUB BILL ATTACHMENTS ============
        function addSubBill(entryId, subId) {
            const entry = claimEntries.find(e => e.id === entryId);
            if (!entry) return;

            const subEntry = entry.subEntries.find(s => s.id === subId);
            if (!subEntry) return;

            subEntry.billCounter++;
            const billId = `subbill-${subId}-${subEntry.billCounter}`;
            const container = document.querySelector(`#${subId} .sub-bills-container`);

            const billDiv = document.createElement('div');
            billDiv.style.cssText = 'display:flex; align-items:center; gap:6px; padding:3px 6px; background:#f8fafc; border-radius:6px; border:1px solid #e2e8f0;';
            billDiv.id = billId;
            billDiv.innerHTML = `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <input type="file" id="${billId}-input" style="display: none;" accept=".pdf,.jpg,.jpeg,.png" onchange="handleSubBillSelect(event, '${entryId}', '${subId}', '${billId}')">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <i class="fas fa-paperclip" style="color:#94a3b8; font-size:0.7rem;"></i>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span id="${billId}-status" style="font-size:0.7rem; color:#64748b; cursor:pointer; flex:1;" onclick="document.getElementById('${billId}-input').click()">Click to attach</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <button type="button" onclick="removeSubBill('${entryId}', '${subId}', '${billId}')" style="border:none; background:none; color:#ef4444; cursor:pointer; font-size:0.65rem;"><i class="fas fa-times"></i></button>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                `;
            container.appendChild(billDiv);
            subEntry.bills.push({ id: billId, file: null });
        }

        // ============ SUB PHOTO ATTACHMENTS ============
        function addSubPhoto(entryId, subId) {
            const entry = claimEntries.find(e => e.id === entryId);
            if (!entry) return;
            const subEntry = entry.subEntries.find(s => s.id === subId);
            if (!subEntry) return;

            subEntry.photoCounter++;
            const photoId = `subphoto-${subId}-${subEntry.photoCounter}`;
            const container = document.querySelector(`#${subId} .sub-photos-container`);

            const photoDiv = document.createElement('div');
            photoDiv.style.cssText = 'display:flex; align-items:center; gap:6px; padding:6px 8px; background:white; border-radius:8px; border:1px solid #e2e8f0; margin-bottom:3px;';
            photoDiv.id = photoId;
            photoDiv.innerHTML = `
                                                                                                                                                                                                                                                                                <input type="file" id="${photoId}-input" style="display: none;" accept=".jpg,.jpeg,.png" onchange="handleSubPhotoSelect(event, '${entryId}', '${subId}', '${photoId}')">
                                                                                                                                                                                                                                                                                <i class="fas fa-camera" style="color:#94a3b8; font-size:0.8rem;"></i>
                                                                                                                                                                                                                                                                                <span id="${photoId}-status" style="font-size:0.75rem; color:#64748b; cursor:pointer; flex:1;" onclick="document.getElementById('${photoId}-input').click()">Click to attach photo</span>
                                                                                                                                                                                                                                                                                <button type="button" onclick="removeSubPhoto('${entryId}', '${subId}', '${photoId}')" style="border:none; background:none; color:#ef4444; cursor:pointer; font-size:0.7rem;"><i class="fas fa-times"></i></button>
                                                                                                                                                                                                                                                                            `;
            container.appendChild(photoDiv);
            if (!subEntry.photos) subEntry.photos = [];
            subEntry.photos.push({ id: photoId, file: null });
        }


        function handleSubPhotoSelect(event, entryId, subId, photoId) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) { showToast('File size must be less than 5MB', 'error'); event.target.value = ''; return; }
            const statusEl = document.getElementById(`${photoId}-status`);
            if (statusEl) statusEl.innerHTML = `<span style="color:#10b981; font-weight:600;">📸 ${file.name.substring(0, 25)}</span>`;
            const entry = claimEntries.find(e => e.id === entryId);
            if (entry) {
                const subEntry = entry.subEntries.find(s => s.id === subId);
                if (subEntry && subEntry.photos) {
                    const photoEntry = subEntry.photos.find(p => p.id === photoId);
                    if (photoEntry) photoEntry.file = file;
                }
            }
        }


        function removeSubPhoto(entryId, subId, photoId) {
            const element = document.getElementById(photoId);
            if (element) element.remove();
            const entry = claimEntries.find(e => e.id === entryId);
            if (entry) {
                const subEntry = entry.subEntries.find(s => s.id === subId);
                if (subEntry && subEntry.photos) {
                    subEntry.photos = subEntry.photos.filter(p => p.id !== photoId);
                }
            }
        }


        function handleSubBillSelect(event, entryId, subId, billId) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) { showToast('File size must be less than 5MB', 'error'); event.target.value = ''; return; }
            const statusEl = document.getElementById(`${billId}-status`);
            if (statusEl) statusEl.innerHTML = `<span style="color:#10b981; font-weight:600;">✅ ${file.name.substring(0, 20)}</span>`;
            const entry = claimEntries.find(e => e.id === entryId);
            if (entry) {
                const subEntry = entry.subEntries.find(s => s.id === subId);
                if (subEntry) {
                    const billEntry = subEntry.bills.find(b => b.id === billId);
                    if (billEntry) billEntry.file = file;
                }
            }
        }


        function removeSubBill(entryId, subId, billId) {
            const element = document.getElementById(billId);
            if (element) element.remove();
            const entry = claimEntries.find(e => e.id === entryId);
            if (entry) {
                const subEntry = entry.subEntries.find(s => s.id === subId);
                if (subEntry) subEntry.bills = subEntry.bills.filter(b => b.id !== billId);
            }
        }


        function handleEntryBillSelect(event, entryId, billId) {
            const file = event.target.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) { showToast('File size must be less than 5MB', 'error'); event.target.value = ''; return; }
            const statusEl = document.getElementById(`${billId}-status`);
            if (statusEl) statusEl.innerHTML = `<span style="color:#10b981; font-weight:600;">✅ ${file.name.substring(0, 20)}</span>`;
            const entry = claimEntries.find(e => e.id === entryId);
            if (entry) {
                const billEntry = entry.bills.find(b => b.id === billId);
                if (billEntry) billEntry.file = file;
            }
        }


        // ============ CHECK CALCULATION TYPE LIMITS ============
        function checkCalculationTypeLimit(entryId, calcType, currentAmount, maxAmount, expenseDate, expenseToDate, policyId) {
            let warning = null;
            let totalForPeriod = currentAmount;

            if (!maxAmount || maxAmount <= 0) {
                return { warning: null, totalForPeriod };
            }

            // For per_claim: just check the current claim total
            if (calcType === 'per_claim') {
                if (currentAmount > maxAmount) {
                    warning = `⚠️ Total ₹${currentAmount.toFixed(2)} exceeds per-claim limit of ₹${maxAmount.toFixed(2)}`;
                }
                return { warning, totalForPeriod };
            }

            // For per_day: check all claims with the same expense date
            if (calcType === 'per_day') {
                if (!expenseDate) {
                    return { warning: null, totalForPeriod };
                }

                // Sum up all claims with the same expense date
                let dailyTotal = 0;
                claimEntries.forEach(otherEntry => {
                    if (!otherEntry.policy || otherEntry.policy.reimbursement_policy_id !== policyId) return;

                    const otherCard = document.getElementById(otherEntry.id);
                    if (!otherCard) return;

                    const otherExpenseDate = otherCard.querySelector('.entry-expense-from')?.value || '';

                    // Only count if same date
                    if (otherExpenseDate === expenseDate) {
                        const otherTotal = parseFloat(otherCard.querySelector('.entry-total-amount')?.textContent.replace('₹', '')) || 0;
                        dailyTotal += otherTotal;
                    }
                });

                totalForPeriod = dailyTotal;

                if (dailyTotal > maxAmount) {
                    warning = `⚠️ Daily total ₹${dailyTotal.toFixed(2)} exceeds per-day limit of ₹${maxAmount.toFixed(2)} for date ${formatDate(expenseDate)}`;
                }
                return { warning, totalForPeriod };
            }

            // For per_month: check claims within the same month based on start date
            if (calcType === 'per_month') {
                if (!expenseDate) {
                    return { warning: null, totalForPeriod };
                }

                const claimDate = new Date(expenseDate);
                const claimMonth = claimDate.getMonth();
                const claimYear = claimDate.getFullYear();

                // Sum up all claims in the same month
                let monthlyTotal = 0;
                claimEntries.forEach(otherEntry => {
                    if (!otherEntry.policy || otherEntry.policy.reimbursement_policy_id !== policyId) return;

                    const otherCard = document.getElementById(otherEntry.id);
                    if (!otherCard) return;

                    const otherExpenseDate = otherCard.querySelector('.entry-expense-from')?.value || '';

                    if (otherExpenseDate) {
                        const otherDate = new Date(otherExpenseDate);
                        // Check if same month and year
                        if (otherDate.getMonth() === claimMonth && otherDate.getFullYear() === claimYear) {
                            const otherTotal = parseFloat(otherCard.querySelector('.entry-total-amount')?.textContent.replace('₹', '')) || 0;
                            monthlyTotal += otherTotal;
                        }
                    }
                });

                totalForPeriod = monthlyTotal;

                const monthName = claimDate.toLocaleString('default', { month: 'long' });
                if (monthlyTotal > maxAmount) {
                    warning = `⚠️ Monthly total ₹${monthlyTotal.toFixed(2)} exceeds per-month limit of ₹${maxAmount.toFixed(2)} for ${monthName} ${claimYear}`;
                }
                return { warning, totalForPeriod };
            }

            // For per_quarter: check claims within the same quarter based on start date
            if (calcType === 'per_quarter') {
                if (!expenseDate) {
                    return { warning: null, totalForPeriod };
                }

                const claimDate = new Date(expenseDate);
                const claimMonth = claimDate.getMonth();
                const claimYear = claimDate.getFullYear();
                const claimQuarter = Math.floor(claimMonth / 3); // 0=Q1, 1=Q2, 2=Q3, 3=Q4

                // Sum up all claims in the same quarter
                let quarterlyTotal = 0;
                claimEntries.forEach(otherEntry => {
                    if (!otherEntry.policy || otherEntry.policy.reimbursement_policy_id !== policyId) return;

                    const otherCard = document.getElementById(otherEntry.id);
                    if (!otherCard) return;

                    const otherExpenseDate = otherCard.querySelector('.entry-expense-from')?.value || '';

                    if (otherExpenseDate) {
                        const otherDate = new Date(otherExpenseDate);
                        const otherQuarter = Math.floor(otherDate.getMonth() / 3);
                        // Check if same quarter and year
                        if (otherQuarter === claimQuarter && otherDate.getFullYear() === claimYear) {
                            const otherTotal = parseFloat(otherCard.querySelector('.entry-total-amount')?.textContent.replace('₹', '')) || 0;
                            quarterlyTotal += otherTotal;
                        }
                    }
                });

                totalForPeriod = quarterlyTotal;

                const quarterNames = ['Q1 (Jan-Mar)', 'Q2 (Apr-Jun)', 'Q3 (Jul-Sep)', 'Q4 (Oct-Dec)'];
                if (quarterlyTotal > maxAmount) {
                    warning = `⚠️ Quarterly total ₹${quarterlyTotal.toFixed(2)} exceeds per-quarter limit of ₹${maxAmount.toFixed(2)} for ${quarterNames[claimQuarter]} ${claimYear}`;
                }
                return { warning, totalForPeriod };
            }

            // For per_year: check claims within the same financial year based on start date
            if (calcType === 'per_year') {
                if (!expenseDate) {
                    return { warning: null, totalForPeriod };
                }

                const claimDate = new Date(expenseDate);
                const claimYear = claimDate.getFullYear();
                const claimMonth = claimDate.getMonth();

                // Determine financial year (assuming April to March)
                let financialYearStart;
                if (claimMonth >= 3) { // April (3) onwards
                    financialYearStart = claimYear;
                } else {
                    financialYearStart = claimYear - 1;
                }
                const financialYearEnd = financialYearStart + 1;

                // Sum up all claims in the same financial year
                let yearlyTotal = 0;
                claimEntries.forEach(otherEntry => {
                    if (!otherEntry.policy || otherEntry.policy.reimbursement_policy_id !== policyId) return;

                    const otherCard = document.getElementById(otherEntry.id);
                    if (!otherCard) return;

                    const otherExpenseDate = otherCard.querySelector('.entry-expense-from')?.value || '';

                    if (otherExpenseDate) {
                        const otherDate = new Date(otherExpenseDate);
                        const otherYear = otherDate.getFullYear();
                        const otherMonth = otherDate.getMonth();

                        let otherFinancialYearStart;
                        if (otherMonth >= 3) {
                            otherFinancialYearStart = otherYear;
                        } else {
                            otherFinancialYearStart = otherYear - 1;
                        }

                        // Check if same financial year
                        if (otherFinancialYearStart === financialYearStart) {
                            const otherTotal = parseFloat(otherCard.querySelector('.entry-total-amount')?.textContent.replace('₹', '')) || 0;
                            yearlyTotal += otherTotal;
                        }
                    }
                });

                totalForPeriod = yearlyTotal;

                if (yearlyTotal > maxAmount) {
                    warning = `⚠️ Yearly total ₹${yearlyTotal.toFixed(2)} exceeds per-year limit of ₹${maxAmount.toFixed(2)} for FY ${financialYearStart}-${financialYearEnd.toString().slice(-2)}`;
                }
                return { warning, totalForPeriod };
            }

            // For lumpsum: check if any claim already exists for this policy
            if (calcType === 'lumpsum') {
                let existingClaims = 0;
                claimEntries.forEach(otherEntry => {
                    if (otherEntry.id !== entryId &&
                        otherEntry.policy &&
                        otherEntry.policy.reimbursement_policy_id === policyId) {
                        existingClaims++;
                    }
                });

                // Also check for historically submitted claims
                const existingClaimCount = entry.policy?.existing_claim_count || 0;
                const totalClaims = existingClaims + existingClaimCount;

                if (totalClaims >= 1) {
                    warning = `⚠️ This is a lumpsum (one-time) policy. ${totalClaims} claim(s) already exist. You can only claim once under this policy.`;
                } else if (currentAmount > maxAmount) {
                    warning = `⚠️ Amount ₹${currentAmount.toFixed(2)} exceeds lumpsum limit of ₹${maxAmount.toFixed(2)}`;
                }

                totalForPeriod = currentAmount;
                return { warning, totalForPeriod };
            }

            return { warning, totalForPeriod };
        }


        async function fetchPolicyUsageForEntry(entryId) {
            const entry = claimEntries.find(e => e.id === entryId);
            if (!entry || !entry.policy) return;
            const card = document.getElementById(entryId);
            const expenseDate = card?.querySelector('.entry-expense-from')?.value;
            if (!expenseDate) return;

            const policyId = entry.policy.reimbursement_policy_id;
            const key = `${policyId}_${expenseDate}`;

            // Check cache
            if (policyUsage[key]) {
                const usage = policyUsage[key];
                entry.policyUsageData = usage;
                entry.existingAmount = usage.existing_amount;
                entry.existingCount = usage.existing_count;
                entry.maxAmount = usage.max_amount;
                entry.frequencyValue = usage.frequency_value;
                entry.frequencyType = usage.frequency_type;
                entry.calculationType = usage.calculation_type;
                refreshAllWarnings();
                return;
            }

            // Fetch if not cached
            if (usageFetchPromises[key]) {
                const usage = await usageFetchPromises[key];
                if (usage) {
                    entry.policyUsageData = usage;
                    entry.existingAmount = usage.existing_amount;
                    entry.existingCount = usage.existing_count;
                    entry.maxAmount = usage.max_amount;
                    entry.frequencyValue = usage.frequency_value;
                    entry.frequencyType = usage.frequency_type;
                    entry.calculationType = usage.calculation_type;
                    refreshAllWarnings();
                }
                return;
            }

            // Start fetch
            const promise = (async () => {
                try {
                    const resp = await fetch(`/institute-admin/reimbursement-claims/policy-usage?policy_id=${policyId}&employee_id=${employeeData.employee_id}&expense_date=${expenseDate}`, {
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    });
                    const result = await resp.json();
                    if (result.success) {
                        policyUsage[key] = result.data;
                        return result.data;
                    }
                    throw new Error(result.message || 'Failed to fetch usage');
                } catch (err) {
                    console.error('Fetch usage error:', err);
                    return null;
                } finally {
                    delete usageFetchPromises[key];
                }
            })();
            usageFetchPromises[key] = promise;
            const usage = await promise;
            if (usage) {
                entry.policyUsageData = usage;
                entry.existingAmount = usage.existing_amount;
                entry.existingCount = usage.existing_count;
                entry.maxAmount = usage.max_amount;
                entry.frequencyValue = usage.frequency_value;
                entry.frequencyType = usage.frequency_type;
                entry.calculationType = usage.calculation_type;
                refreshAllWarnings();
            }
        }


        async function refreshAllWarnings() {
            // refreshAllWarnings ensures card level total displays and breakdown info stay synchronized
            for (const entry of claimEntries) {
                if (!entry.policy) continue;
                const card = document.getElementById(entry.id);
                if (!card) continue;

                let totalAmount = 0;
                entry.subEntries.forEach(sub => {
                    totalAmount += sub.amount || 0;
                });

                const totalDisplay = card.querySelector('.entry-total-amount');
                const summaryMini = card.querySelector('.entry-summary-mini');
                const subCount = card.querySelector('.entry-sub-count');
                if (totalDisplay) totalDisplay.textContent = `₹${totalAmount.toFixed(2)}`;
                if (summaryMini) summaryMini.style.display = totalAmount > 0 ? 'block' : 'none';
                if (subCount) {
                    const count = entry.subEntries.length;
                    subCount.textContent = `${count} item${count !== 1 ? 's' : ''}`;
                }
            }
        }

        // ============ UPDATE SUMMARY WITH CALCULATION TYPE WARNINGS ============
        function updateSummary() {
            const summaryCard = document.getElementById('summary-card');
            let totalAmount = 0;
            let validCount = 0;
            let globalWarnings = [];

            claimEntries.forEach(entry => {
                const card = document.getElementById(entry.id);
                if (!card || !entry.policy) return;

                const totalDisplay = card.querySelector('.entry-total-amount');
                if (totalDisplay) {
                    const amount = parseFloat(totalDisplay.textContent.replace('₹', '')) || 0;
                    if (amount > 0) {
                        totalAmount += amount;
                        validCount++;
                    }
                }
            });

            // Check for cross-claim calculation type violations
            const policyGroups = {};
            claimEntries.forEach(entry => {
                if (!entry.policy) return;
                const policyId = entry.policy.reimbursement_policy_id;
                if (!policyGroups[policyId]) {
                    policyGroups[policyId] = {
                        policy: entry.policy,
                        entries: []
                    };
                }
                policyGroups[policyId].entries.push(entry);
            });

            // Check each policy group
            Object.values(policyGroups).forEach(group => {
                const calcType = group.policy.calculation_type || 'per_claim';
                const maxAmount = parseFloat(group.policy.max_amount) || 0;

                if (!maxAmount || maxAmount <= 0) return;

                if (calcType === 'per_claim') {
                    // Check each claim individually
                    group.entries.forEach(entry => {
                        const card = document.getElementById(entry.id);
                        const entryTotal = parseFloat(card?.querySelector('.entry-total-amount')?.textContent.replace('₹', '')) || 0;
                        if (entryTotal > maxAmount) {
                            globalWarnings.push(`⚠️ ${group.policy.policy_name}: Claim #${card?.querySelector('.claim-entry-number')?.textContent} exceeds per-claim limit of ₹${maxAmount.toFixed(2)}`);
                        }
                    });
                } else if (calcType === 'per_day') {
                    // Group by sub-entry date
                    const dateGroups = {};
                    group.entries.forEach(entry => {
                        const card = document.getElementById(entry.id);
                        const cardExpenseDate = card?.querySelector('.entry-expense-from')?.value || '';
                        entry.subEntries.forEach(sub => {
                            const subDate = sub.date || sub.checkinDate || sub.travelDate || sub.mealDate || cardExpenseDate;
                            if (subDate) {
                                if (!dateGroups[subDate]) {
                                    dateGroups[subDate] = { total: 0 };
                                }
                                dateGroups[subDate].total += (sub.amount || 0);
                            }
                        });
                    });

                    Object.entries(dateGroups).forEach(([date, data]) => {
                        const effMax = getEffectiveMaxForEntry(group.entries[0]) || maxAmount;
                        if (effMax > 0 && data.total > effMax) {
                            globalWarnings.push(`⚠️ ${group.policy.policy_name}: Daily total ₹${data.total.toFixed(2)} exceeds per-day limit of ₹${effMax.toFixed(2)} on ${formatDate(date)}`);
                        }
                    });
                } else if (calcType === 'per_month') {
                    // Group by month
                    const monthGroups = {};
                    group.entries.forEach(entry => {
                        const card = document.getElementById(entry.id);
                        const expenseDate = card?.querySelector('.entry-expense-from')?.value || '';
                        if (expenseDate) {
                            const date = new Date(expenseDate);
                            const monthKey = `${date.getFullYear()}-${date.getMonth()}`;
                            if (!monthGroups[monthKey]) {
                                monthGroups[monthKey] = { total: 0, month: date.getMonth(), year: date.getFullYear() };
                            }
                            const entryTotal = parseFloat(card?.querySelector('.entry-total-amount')?.textContent.replace('₹', '')) || 0;
                            monthGroups[monthKey].total += entryTotal;
                        }
                    });

                    Object.values(monthGroups).forEach(data => {
                        if (data.total > maxAmount) {
                            const monthName = new Date(data.year, data.month).toLocaleString('default', { month: 'long' });
                            globalWarnings.push(`⚠️ ${group.policy.policy_name}: Monthly total ₹${data.total.toFixed(2)} exceeds per-month limit of ₹${maxAmount.toFixed(2)} for ${monthName} ${data.year}`);
                        }
                    });
                } else if (calcType === 'per_quarter') {
                    // Group by quarter
                    const quarterGroups = {};
                    group.entries.forEach(entry => {
                        const card = document.getElementById(entry.id);
                        const expenseDate = card?.querySelector('.entry-expense-from')?.value || '';
                        if (expenseDate) {
                            const date = new Date(expenseDate);
                            const quarter = Math.floor(date.getMonth() / 3);
                            const quarterKey = `${date.getFullYear()}-Q${quarter + 1}`;
                            if (!quarterGroups[quarterKey]) {
                                quarterGroups[quarterKey] = { total: 0, quarter, year: date.getFullYear() };
                            }
                            const entryTotal = parseFloat(card?.querySelector('.entry-total-amount')?.textContent.replace('₹', '')) || 0;
                            quarterGroups[quarterKey].total += entryTotal;
                        }
                    });

                    const quarterNames = ['Q1 (Jan-Mar)', 'Q2 (Apr-Jun)', 'Q3 (Jul-Sep)', 'Q4 (Oct-Dec)'];
                    Object.values(quarterGroups).forEach(data => {
                        if (data.total > maxAmount) {
                            globalWarnings.push(`⚠️ ${group.policy.policy_name}: Quarterly total ₹${data.total.toFixed(2)} exceeds per-quarter limit of ₹${maxAmount.toFixed(2)} for ${quarterNames[data.quarter]} ${data.year}`);
                        }
                    });
                } else if (calcType === 'per_year') {
                    // Group by financial year
                    const yearGroups = {};
                    group.entries.forEach(entry => {
                        const card = document.getElementById(entry.id);
                        const expenseDate = card?.querySelector('.entry-expense-from')?.value || '';
                        if (expenseDate) {
                            const date = new Date(expenseDate);
                            let fyStart;
                            if (date.getMonth() >= 3) { // April onwards
                                fyStart = date.getFullYear();
                            } else {
                                fyStart = date.getFullYear() - 1;
                            }
                            const fyKey = `FY${fyStart}-${(fyStart + 1).toString().slice(-2)}`;
                            if (!yearGroups[fyKey]) {
                                yearGroups[fyKey] = { total: 0, fyStart };
                            }
                            const entryTotal = parseFloat(card?.querySelector('.entry-total-amount')?.textContent.replace('₹', '')) || 0;
                            yearGroups[fyKey].total += entryTotal;
                        }
                    });

                    Object.values(yearGroups).forEach(data => {
                        if (data.total > maxAmount) {
                            globalWarnings.push(`⚠️ ${group.policy.policy_name}: Yearly total ₹${data.total.toFixed(2)} exceeds per-year limit of ₹${maxAmount.toFixed(2)} for FY ${data.fyStart}-${(data.fyStart + 1).toString().slice(-2)}`);
                        }
                    });
                }
            });

            if (validCount > 0) {
                summaryCard.style.display = 'block';
                document.getElementById('summary-count').textContent = validCount;
                document.getElementById('summary-total').textContent = `₹${totalAmount.toFixed(2)}`;

                // Show global warnings in summary if any
                let summaryWarningDiv = document.getElementById('summary-warnings');
                if (!summaryWarningDiv) {
                    summaryWarningDiv = document.createElement('div');
                    summaryWarningDiv.id = 'summary-warnings';
                    summaryWarningDiv.style.cssText = 'margin-top:10px; padding:8px 12px; background:#fffbeb; border:1px solid #fcd34d; border-radius:8px;';
                    summaryCard.appendChild(summaryWarningDiv);
                }

                if (globalWarnings.length > 0) {
                    summaryWarningDiv.style.display = 'block';
                    summaryWarningDiv.innerHTML = globalWarnings.map(w =>
                        `<div style="color:#92400e; font-size:0.8rem; font-weight:500; margin-bottom:3px;">${w}</div>`
                    ).join('');
                } else {
                    summaryWarningDiv.style.display = 'none';
                }
            } else {
                summaryCard.style.display = 'none';
            }
        }


        // ============ SUBMIT ALL CLAIMS ============
        async function submitAllClaims(e) {
            e.preventDefault();
            const errorBox = document.getElementById("validation-error-box");
            const errorMsg = document.getElementById("validation-error-msg");
            errorBox.style.display = "none";

            const claimsPayload = [];
            const errors = [];

            for (const entry of claimEntries) {
                const card = document.getElementById(entry.id);
                if (!card || !entry.policy) continue;

                const policySelect = card.querySelector('.entry-policy');
                if (!policySelect?.value) {
                    errors.push(`Claim #${card.querySelector('.claim-entry-number').textContent}: Please select a policy`);
                    continue;
                }

                if (entry.subEntries.length === 0) {
                    errors.push(`Claim #${card.querySelector('.claim-entry-number').textContent}: Add at least one detail`);
                    continue;
                }

                const expenseFrom = card.querySelector('.entry-expense-from')?.value || '';
                const expenseTo = card.querySelector('.entry-expense-to')?.value || '';
                const remarks = card.querySelector('.entry-remarks')?.value || '';

                if (!expenseFrom) {
                    errors.push(`Claim #${card.querySelector('.claim-entry-number').textContent}: Expense date is required`);
                    continue;
                }

                // Validate required bill attachments
                if (entry.billRequired) {
                    const hasBillFiles = entry.bills && entry.bills.some(b => b.file);
                    const hasSubBillFiles = entry.subEntries && entry.subEntries.some(sub =>
                        sub.bills && sub.bills.some(b => b.file)
                    );
                    if (!hasBillFiles && !hasSubBillFiles) {
                        errors.push(`Claim #${card.querySelector('.claim-entry-number').textContent}: Bill attachment is required for this policy`);
                        continue;
                    }
                }

                // Validate required photo attachments
                if (entry.photoRequired) {
                    const hasPhotoFiles = entry.photos && entry.photos.some(p => p.file);
                    if (!hasPhotoFiles) {
                        errors.push(`Claim #${card.querySelector('.claim-entry-number').textContent}: Photo attachment is required for this policy`);
                        continue;
                    }
                }

                let claimTotal = 0;
                const subEntriesData = [];

                for (const sub of entry.subEntries) {
                    const subDiv = document.getElementById(sub.id);
                    if (!subDiv) continue;

                    const cat = entry.policy?.policy_category || 'other';
                    let subData = {
                        range_name: sub.rangeName || '',
                        range_type: sub.range?.ruleType || 'actual',
                        min_limit: sub.range?.min || 0,
                        max_limit: sub.rangeMax || sub.range?.max || 0,
                        rate: sub.range?.rate || 0,
                        unit_label: sub.range?.unitLabel || '',
                        vendor: subDiv.querySelector('.sub-vendor')?.value || '',
                        bill_number: subDiv.querySelector('.sub-bill')?.value || '',
                        description: subDiv.querySelector('.sub-desc')?.value || '',
                        amount: 0,
                        range_existing_claimed: sub.rangeExistingClaimed || 0,
                        range_remaining_limit: sub.rangeRemainingLimit !== undefined ? sub.rangeRemainingLimit : (sub.rangeMax || sub.range?.max || 0),
                        is_exceeded: sub.isExceeded || false,
                        exceeded_amount: sub.exceededAmount || 0
                    };

                    if (cat === 'travel') {
                        subData.from_location = subDiv.querySelector('.sub-from')?.value || '';
                        subData.to_location = subDiv.querySelector('.sub-to')?.value || '';
                        subData.travel_date = subDiv.querySelector('.sub-date')?.value || expenseFrom;
                        subData.travel_provider = subDiv.querySelector('.sub-vendor')?.value || '';

                        if (sub.range?.ruleType === 'per_km') {
                            subData.distance_km = parseFloat(subDiv.querySelector('.sub-km')?.value) || 0;
                            subData.rate_per_km = sub.range.rate || 0;
                            subData.amount = subData.distance_km * subData.rate_per_km;
                        } else {
                            subData.amount = parseFloat(subDiv.querySelector('.sub-amount')?.value) || 0;
                        }
                    } else if (cat === 'accommodation') {
                        subData.checkin_date = subDiv.querySelector('.sub-checkin')?.value || '';
                        subData.checkin_time = subDiv.querySelector('.sub-checkin-time')?.value || '12:00';
                        subData.checkout_date = subDiv.querySelector('.sub-checkout')?.value || '';
                        subData.checkout_time = subDiv.querySelector('.sub-checkout-time')?.value || '12:00';
                        subData.nights = sub.nights || 0;
                        subData.hotel_name = subDiv.querySelector('.sub-vendor')?.value || '';
                        subData.amount = parseFloat(subDiv.querySelector('.sub-amount')?.value) || 0;
                        subData.includes_food = sub.range?.includesFood || false;
                    } else if (cat === 'food') {
                        subData.meal_date = subDiv.querySelector('.sub-date')?.value || expenseFrom;
                        subData.restaurant_name = subDiv.querySelector('.sub-vendor')?.value || '';
                        subData.amount = parseFloat(subDiv.querySelector('.sub-amount')?.value) || 0;
                    } else {
                        subData.expense_date = subDiv.querySelector('.sub-date')?.value || expenseFrom;
                        subData.vendor = subDiv.querySelector('.sub-vendor')?.value || '';

                        if (sub.range?.ruleType === 'per_unit') {
                            subData.units_consumed = parseFloat(subDiv.querySelector('.sub-units')?.value) || 0;
                            subData.rate_per_unit = sub.range.rate || 0;
                            subData.unit_label = sub.range.unitLabel || 'Units';
                            subData.amount = subData.units_consumed * subData.rate_per_unit;
                        } else if (sub.range?.ruleType === 'per_km') {
                            subData.distance_km = parseFloat(subDiv.querySelector('.sub-km')?.value) || 0;
                            subData.rate_per_km = sub.range.rate || 0;
                            subData.amount = subData.distance_km * subData.rate_per_km;
                        } else if (sub.range?.ruleType === 'per_day' || sub.range?.ruleType === 'per_night') {
                            subData.units_consumed = parseFloat(subDiv.querySelector('.sub-units')?.value) || 0;
                            subData.rate_per_unit = sub.range.rate || 0;
                            subData.amount = subData.units_consumed * subData.rate_per_unit;
                        } else {
                            subData.amount = parseFloat(subDiv.querySelector('.sub-amount')?.value) || 0;
                        }
                    }

                    claimTotal += subData.amount;
                    subEntriesData.push(subData);
                }

                if (claimTotal <= 0) {
                    errors.push(`Claim #${card.querySelector('.claim-entry-number').textContent}: Total amount must be greater than 0`);
                    continue;
                }

                // Collect all bill files for this claim entry
                const allBillFiles = [];
                const allPhotoFiles = [];

                // Add sub-entry bills and photos
                if (entry.subEntries && entry.subEntries.length > 0) {
                    entry.subEntries.forEach(sub => {
                        if (sub.bills && sub.bills.length > 0) {
                            sub.bills.forEach(bill => {
                                if (bill.file) allBillFiles.push(bill.file);
                            });
                        }
                        if (sub.photos && sub.photos.length > 0) {
                            sub.photos.forEach(photo => {
                                if (photo.file) allPhotoFiles.push(photo.file);
                            });
                        }
                    });
                }

                const maxAmt = parseFloat(entry.maxAmount) || 0;
                const existAmt = parseFloat(entry.existingAmount) || 0;
                let remLimitAtSubmission = null;
                if (maxAmt > 0) {
                    remLimitAtSubmission = Math.max(0, maxAmt - (existAmt + claimTotal));
                }

                // Build clean payload
                const claimPayload = {
                    reimbursement_policy_id: policySelect.value,
                    policy_name: entry.policy.policy_name || '',
                    policy_category: entry.policy.policy_category || '',
                    claim_amount: claimTotal,
                    remaining_limit_at_submission: remLimitAtSubmission,
                    calculated_amount: claimTotal,
                    expense_date: expenseFrom,
                    to_date: expenseTo || expenseFrom,
                    remarks: remarks,
                    entry_order: claimEntries.indexOf(entry),
                    calculation_type: entry.policy?.calculation_type || 'per_claim',
                    sub_entries: subEntriesData,
                    _billFiles: allBillFiles,
                    _photoFiles: allPhotoFiles,
                    billRequired: entry.billRequired || false,
                    photoRequired: entry.photoRequired || false
                };

                claimsPayload.push(claimPayload);
            }

            if (errors.length > 0) {
                errorBox.style.display = "flex";
                errorMsg.innerHTML = errors.map(e => `• ${e}`).join('<br>');
                showToast(errors[0], 'error');
                return;
            }

            if (claimsPayload.length === 0) {
                showToast('Please add at least one valid claim', 'error');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

            try {
                const formData = new FormData();
                formData.append('employee_id', employeeData.employee_id);

                claimsPayload.forEach((claimPayload, index) => {
                    formData.append(`claims[${index}][reimbursement_policy_id]`, claimPayload.reimbursement_policy_id);
                    formData.append(`claims[${index}][policy_name]`, claimPayload.policy_name);
                    formData.append(`claims[${index}][policy_category]`, claimPayload.policy_category);
                    formData.append(`claims[${index}][claim_amount]`, claimPayload.claim_amount);
                    formData.append(`claims[${index}][remaining_limit_at_submission]`, claimPayload.remaining_limit_at_submission !== null ? claimPayload.remaining_limit_at_submission : '');
                    formData.append(`claims[${index}][calculated_amount]`, claimPayload.calculated_amount);
                    formData.append(`claims[${index}][expense_date]`, claimPayload.expense_date);
                    formData.append(`claims[${index}][to_date]`, claimPayload.to_date);
                    formData.append(`claims[${index}][remarks]`, claimPayload.remarks);
                    formData.append(`claims[${index}][entry_order]`, claimPayload.entry_order);
                    formData.append(`claims[${index}][calculation_type]`, claimPayload.calculation_type);
                    formData.append(`claims[${index}][billRequired]`, claimPayload.billRequired ? '1' : '0');
                    formData.append(`claims[${index}][photoRequired]`, claimPayload.photoRequired ? '1' : '0');
                    formData.append(`claims[${index}][sub_entries]`, JSON.stringify(claimPayload.sub_entries));

                    // Append bill files
                    if (claimPayload._billFiles && claimPayload._billFiles.length > 0) {
                        claimPayload._billFiles.forEach(file => {
                            formData.append(`claims[${index}][bill_attachments][]`, file);
                        });
                    }

                    // Append photo files
                    if (claimPayload._photoFiles && claimPayload._photoFiles.length > 0) {
                        claimPayload._photoFiles.forEach(file => {
                            formData.append(`claims[${index}][photo_attachments][]`, file);
                        });
                    }
                });

                console.log('Submitting claims:', claimsPayload);

                const response = await fetch('/institute-admin/reimbursement-claims/store-bulk', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    const masterId = result.data?.master_request_id || '';
                    if (result.warnings && result.warnings.length > 0) {
                        errorBox.style.display = 'flex';
                        errorBox.style.borderColor = '#f59e0b';
                        errorMsg.innerHTML = result.warnings.map(w => `• ${w}`).join('<br>');
                        showToast('Submitted with warnings. Please review the highlighted limits.', 'warning');
                    } else {
                        showToast(`🎉 ${result.message}`, 'success');
                    }
                    setTimeout(() => {
                        window.location.href = '/institute-admin/view-employee-claims';
                    }, 2000);
                } else {
                    let errorMessage = result.message || 'Failed to submit claims';
                    if (result.errors) {
                        const errorDetails = Object.values(result.errors).flat().join(', ');
                        errorMessage += ': ' + errorDetails;
                    }
                    showToast(errorMessage, 'error');

                    if (result.errors) {
                        errorBox.style.display = "flex";
                        errorMsg.innerHTML = Object.entries(result.errors).map(([key, messages]) => {
                            return `• ${key}: ${messages.join(', ')}`;
                        }).join('<br>');
                    }
                }
            } catch (err) {
                console.error('Error:', err);
                showToast('Error submitting claims: ' + err.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit All Claims';
            }
        }


        // ============ RESET ALL ============
        function resetAllClaims() {
            document.getElementById('claims-container').innerHTML = '';
            claimEntries = [];
            entryCounter = 0;
            document.getElementById('summary-card').style.display = 'none';
            addClaimEntry();
        }

        // ============ INITIALIZE ============
        document.addEventListener('DOMContentLoaded', loadEmployeeDetails);
    </script>

@endsection