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

        .btn-header-primary {
            background: rgba(37, 99, 235, 0.3);
            border-color: rgba(37, 99, 235, 0.3);
        }

        .btn-header-primary:hover {
            background: rgba(37, 99, 235, 0.4);
            border-color: rgba(37, 99, 235, 0.5);
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

        /* Premium Design variables */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(37, 99, 235, 0.08);
            border-color: rgba(37, 99, 235, 0.25);
        }

        .kpi-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .kpi-val {
            font-size: 2rem;
            font-weight: 850;
            color: #0f172a;
            line-height: 1.1;
            margin-top: 4px;
        }

        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: transform 0.3s;
        }

        .glass-card:hover .kpi-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* Buttons */
        .btn-gradient {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white !important;
            border: none;
            padding: 0.7rem 1.6rem;
            border-radius: 50px;
            font-weight: 650;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            font-size: 0.9rem;
            text-decoration: none !important;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }

        .btn-gradient-secondary {
            background: #0f172a;
            box-shadow: none;
        }

        .btn-gradient-secondary:hover {
            background: #1e293b;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.3);
        }

        .btn-action {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid #e2e8f0;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none !important;
        }

        .btn-action:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
            transform: scale(1.05);
        }

        .btn-action.view:hover {
            color: #2563eb;
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .btn-action.edit:hover {
            color: #7c3aed;
            background: #f5f3ff;
            border-color: #ddd6fe;
        }

        .btn-action.assign:hover {
            color: #10b981;
            background: #ecfdf5;
            border-color: #a7f3d0;
        }

        .btn-action.delete:hover {
            color: #ef4444;
            background: #fef2f2;
            border-color: #fca5a5;
        }

        /* Table */
        .table-wrapper {
            background: white;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.02);
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table th {
            background: #f8fafc;
            padding: 0.9rem 1.25rem;
            color: #475569;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1.5px solid #edf2f7;
            text-align: left;
        }

        .modern-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.88rem;
        }

        .modern-table tr:last-child td {
            border-bottom: none;
        }

        .modern-table tr {
            transition: background 0.15s;
        }

        .modern-table tr:hover {
            background: #f8fafc;
        }

        /* Badges */
        .badge-pill {
            padding: 0.25rem 0.8rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-category {
            background: #e0e7ff;
            color: #3730a3;
            text-transform: capitalize;
            font-size: 0.7rem;
            padding: 0.2rem 0.7rem;
        }

        .badge-upcoming {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-expired {
            background: #fef3c7;
            color: #92400e;
        }

        /* Filters */
        .filter-section {
            padding: 0.75rem 1.25rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .filter-section .filter-left {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
            flex: 1;
        }

        .filter-section input,
        .filter-section select {
            padding: 0.5rem 1rem;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
            outline: none;
            font-size: 0.85rem;
            font-family: inherit;
            background: white;
            transition: all 0.2s;
        }

        .filter-section input:focus,
        .filter-section select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .filter-section input {
            width: 220px;
        }

        .filter-section select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        .filter-section .results-count {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            white-space: nowrap;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -45%) scale(0.95);
            width: 90%;
            max-width: 750px;
            background: white;
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            z-index: 1001;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-height: 85vh;
            overflow-y: auto;
            padding: 2rem;
        }

        .modal-container.active {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
            pointer-events: auto;
        }

        .modal-container::-webkit-scrollbar {
            width: 4px;
        }

        .modal-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .modal-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .nav-tabs {
            display: flex;
            gap: 6px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 50px;
            width: fit-content;
            margin-bottom: 1.5rem;
        }

        .tab-btn {
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .tab-btn.active {
            background: white;
            color: #2563eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .tab-btn:hover:not(.active) {
            color: #1e293b;
        }

        .modal-detail-row {
            display: flex;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
        }

        .modal-detail-row .label {
            font-weight: 600;
            color: #64748b;
            width: 35%;
            flex-shrink: 0;
        }

        .modal-detail-row .value {
            color: #1e293b;
            font-weight: 500;
            flex: 1;
        }

        .modal-detail-row:last-child {
            border-bottom: none;
        }

        .range-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            margin-bottom: 0.75rem;
        }

        .range-card .range-title {
            font-weight: 700;
            color: #0f172a;
            display: block;
            margin-bottom: 6px;
            font-size: 0.85rem;
        }

        .range-card .range-values {
            display: grid;
            /* grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); */
            gap: 6px;
            font-size: 0.8rem;
            color: #475569;
        }

        .range-card .range-values .highlight {
            font-weight: 600;
            color: #1e293b;
        }

        .custom-field-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 12px;
            background: white;
            border: 1px solid #edf2f7;
            border-radius: 8px;
            font-size: 0.8rem;
            margin-bottom: 4px;
        }

        .custom-field-item .field-name {
            font-weight: 600;
            color: #0f172a;
        }

        .custom-field-item .field-meta {
            color: #64748b;
            font-size: 0.7rem;
        }

        .custom-field-item .field-badge {
            background: #eff6ff;
            color: #2563eb;
            padding: 0.1rem 0.6rem;
            border-radius: 20px;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .custom-field-item .field-badge.calc {
            background: #fef3c7;
            color: #d97706;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 1.5rem;
            border-top: 1.5px solid #edf2f7;
            padding-top: 1.5rem;
        }

        /* Empty State */
        .empty-state {
            padding: 3rem 2rem;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            color: #e2e8f0;
            margin-bottom: 0.75rem;
            display: block;
        }

        .empty-state h3 {
            margin: 0;
            color: #475569;
            font-size: 1.1rem;
        }

        .empty-state p {
            margin: 4px 0 0 0;
            font-size: 0.9rem;
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: white;
            border-radius: 12px;
            padding: 16px 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid #2563eb;
        }

        .toast-success {
            border-left-color: #22c55e;
        }

        .toast-error {
            border-left-color: #ef4444;
        }

        .toast i {
            font-size: 1.2rem;
        }

        .toast-success i {
            color: #22c55e;
        }

        .toast-error i {
            color: #ef4444;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
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

            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-section .filter-left {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-section input,
            .filter-section select {
                width: 100%;
            }

            .filter-section .results-count {
                text-align: center;
            }

            .modal-container {
                padding: 1.5rem;
                max-width: 100%;
                max-height: 100vh;
                border-radius: 24px 24px 0 0;
                bottom: 0;
                top: auto;
                transform: translate(-50%, 100%) scale(1);
            }

            .modal-container.active {
                transform: translate(-50%, 0) scale(1);
            }

            .nav-tabs {
                width: 100%;
                overflow-x: auto;
                border-radius: 12px;
            }

            .tab-btn {
                flex: 1;
                text-align: center;
                font-size: 0.7rem;
                padding: 0.3rem 0.8rem;
                white-space: nowrap;
            }

            .range-card .range-values {
                grid-template-columns: 1fr;
            }

            .modern-table {
                font-size: 0.8rem;
            }

            .modern-table th,
            .modern-table td {
                padding: 0.6rem 0.8rem;
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

            .kpi-val {
                font-size: 1.5rem;
            }

            .glass-card {
                padding: 1rem;
            }

            .modal-detail-row {
                flex-direction: column;
                padding: 0.4rem 0;
            }

            .modal-detail-row .label {
                width: 100%;
                font-size: 0.7rem;
            }

            .modal-detail-row .value {
                font-size: 0.85rem;
            }

            .custom-field-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }

        /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #e2e8f0;
            border-radius: 50%;
            border-top-color: #2563eb;
            animation: spin 0.6s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
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
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="header-title">
                        <h1>Reimbursement Policy Master</h1>
                        <p>Create, customize, and manage reimbursement value structures for your organization</p>
                    </div>
                </div>
                <div class="header-actions">

                    <a href="{{ route('reimbursement.policies.create') }}" class="btn-header btn-header-primary">
                        <i class="fas fa-plus"></i> Create Policy
                    </a>
                    <span class="stats-badge">
                        <i class="fas fa-file-signature" style="color: #2563eb;"></i>
                        <strong id="header-total">0</strong> Policies
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
            <div class="glass-card"
                style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="kpi-title">Total Policies</div>
                    <div class="kpi-val" id="kpi-total">0</div>
                </div>
                <div class="kpi-icon" style="background: #eff6ff; color: #2563eb;"><i class="fas fa-file-signature"></i>
                </div>
            </div>

            <div class="glass-card"
                style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="kpi-title">Active Rules</div>
                    <div class="kpi-val" style="color: #10b981;" id="kpi-active">0</div>
                </div>
                <div class="kpi-icon" style="background: #ecfdf5; color: #10b981;"><i class="fas fa-check-circle"></i></div>
            </div>

            <div class="glass-card"
                style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="kpi-title">Categories Used</div>
                    <div class="kpi-val" style="color: #7c3aed;" id="kpi-categories">0</div>
                </div>
                <div class="kpi-icon" style="background: #f5f3ff; color: #7c3aed;"><i class="fas fa-layer-group"></i></div>
            </div>

            <div class="glass-card"
                style="padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="kpi-title">Avg Settlement</div>
                    <div class="kpi-val" style="color: #f59e0b;" id="kpi-settlement">7 Days</div>
                </div>
                <div class="kpi-icon" style="background: #fffbeb; color: #f59e0b;"><i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="filter-left">
                <input type="text" id="search-input" placeholder="Search policy by name or code...">
                <select id="filter-category">
                    <option value="all">All Categories</option>
                    <option value="travel">Travel</option>
                    <option value="accommodation">Accommodation</option>
                    <option value="food">Food</option>
                    <option value="mobile">Mobile</option>
                    <option value="internet">Internet</option>
                    <option value="entertainment">Entertainment</option>
                    <option value="miscellaneous">Miscellaneous</option>
                    <option value="custom">Custom Policy</option>
                </select>
                <select id="filter-status">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="expired">Expired</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button onclick="loadPolicies()" class="btn-gradient" style="padding: 0.5rem 1.2rem; font-size: 0.8rem;">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
            <div class="results-count" id="results-count">Loading policies...</div>
        </div>

        <!-- Table -->
        <div class="table-wrapper">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Policy Details</th>
                        <th>Category</th>
                        <th>Reimbursement Values</th>
                        <th>Claim Rules</th>
                        <th>Effective Period</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="policies-table-body">
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem;">
                            <div class="spinner" style="margin: 0 auto;"></div>
                            <div style="margin-top: 10px; color: #94a3b8;">Loading policies...</div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div id="table-empty-state" class="empty-state" style="display: none;">
                <i class="fas fa-folder-open"></i>
                <h3>No Policies Found</h3>
                <p>Create a new policy using the button above to begin configuring claim rules.</p>
                <a href="{{ route('reimbursement.policies.create') }}" class="btn-gradient"
                    style="margin-top: 1rem; display: inline-flex;">
                    <i class="fas fa-plus"></i> Create First Policy
                </a>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="detail-overlay" onclick="closePolicyModal()"></div>
    <div class="modal-container" id="detail-modal">
        <div
            style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1.5px solid #edf2f7; padding-bottom: 1rem; margin-bottom: 1.25rem;">
            <h3
                style="font-size: 1.2rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; margin: 0;">
                <i class="fas fa-file-contract" style="color: #2563eb;"></i>
                Policy Details
            </h3>
            <button onclick="closePolicyModal()"
                style="border: none; background: transparent; font-size: 1.2rem; color: #94a3b8; cursor: pointer; transition: 0.2s;"><i
                    class="fas fa-times"></i></button>
        </div>

        <div class="nav-tabs">
            <button class="tab-btn active" onclick="switchModalTab('basic')">Basic Info</button>
            <button class="tab-btn" onclick="switchModalTab('ranges')">Reimbursement Values</button>
            <button class="tab-btn" onclick="switchModalTab('rules')">Claim & Settlement</button>
        </div>

        <!-- Tab: Basic Info -->
        <div id="modal-tab-basic" class="tab-content">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 10px;">
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #eff6ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #2563eb; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-size: 0.65rem; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">
                            Policy Name</div>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #1e293b; word-break: break-word;"
                            id="m-name"></div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-size: 0.65rem; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">
                            Policy Code</div>
                        <div style="font-size: 0.85rem; font-weight: 600; color: #64748b; font-family: monospace;"
                            id="m-code">-</div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #e0e7ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #3730a3; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-size: 0.65rem; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">
                            Category</div>
                        <div><span class="badge-pill badge-category" id="m-category"></span></div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px; grid-column: 1 / -1;">
                    <div
                        style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-align-left"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-size: 0.65rem; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">
                            Description</div>
                        <div style="font-size: 0.85rem; color: #475569;" id="m-desc"></div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #fef3c7; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #d97706; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-size: 0.65rem; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">
                            Financial Year</div>
                        <div style="font-size: 0.9rem; font-weight: 600; color: #1e293b;" id="m-fy">-</div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #faf5ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #7c3aed; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-size: 0.65rem; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">
                            Calculation Type</div>
                        <div style="font-size: 0.9rem; font-weight: 600; color: #1e293b; text-transform: capitalize;"
                            id="m-calctype">-</div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #d1fae5; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #065f46; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-size: 0.65rem; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">
                            Effective From</div>
                        <div style="font-size: 0.85rem; font-weight: 600; color: #1e293b;" id="m-efffrom">-</div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #fee2e2; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #991b1b; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-stop-circle"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-size: 0.65rem; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">
                            Effective To</div>
                        <div style="font-size: 0.85rem; font-weight: 600; color: #1e293b;" id="m-effto">-</div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px; grid-column: 1 / -1;">
                    <div
                        style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-size: 0.65rem; text-transform: uppercase; color: #94a3b8; font-weight: 600; letter-spacing: 0.5px;">
                            Status & Dates</div>
                        <div style="display: flex; gap: 16px; flex-wrap: wrap; align-items: center; margin-top: 4px;">
                            <span id="m-status"></span>
                            <span style="font-size: 0.75rem; color: #94a3b8;">Created: <strong style="color: #64748b;"
                                    id="m-created"></strong></span>
                            <span style="font-size: 0.75rem; color: #94a3b8;">Updated: <strong style="color: #64748b;"
                                    id="m-updated"></strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Reimbursement Values -->
        <div id="modal-tab-ranges" class="tab-content" style="display: none;">
            <div id="m-ranges-container"></div>
        </div>

        <!-- Tab: Claim & Settlement -->
        <div id="modal-tab-rules" class="tab-content" style="display: none;">
            <!-- Claim Rules -->
            <h4
                style="margin: 0 0 12px 0; color: #0f172a; font-size: 0.9rem; font-weight: 700; display: flex; align-items: center; gap: 8px; padding-bottom: 8px; border-bottom: 1.5px solid #edf2f7;">
                <i class="fas fa-gavel" style="color: #2563eb; font-size: 0.85rem;"></i> Claim Rules
            </h4>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 10px; margin-bottom: 1.5rem;">
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #eff6ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #2563eb; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-repeat"></i>
                    </div>
                    <div style="flex:1;">
                        <div
                            style="font-size:0.65rem;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;">
                            Claim Frequency</div>
                        <div style="font-size:0.9rem;font-weight:700;color:#1e293b;" id="m-rule-freq"></div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div style="flex:1;">
                        <div
                            style="font-size:0.65rem;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;">
                            Submission Window</div>
                        <div style="font-size:0.9rem;font-weight:700;color:#1e293b;"><span id="m-rule-submission"></span> ·
                            <span id="m-rule-submission-type" style="font-weight:500;color:#64748b;"></span>
                        </div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #faf5ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #7c3aed; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-check-shield"></i>
                    </div>
                    <div style="flex:1;">
                        <div
                            style="font-size:0.65rem;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;">
                            Actual Amount</div>
                        <div style="font-size:0.9rem;font-weight:600;color:#1e293b;" id="m-allow-actual-amount"></div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #fff7ed; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #f97316; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div style="flex:1;">
                        <div
                            style="font-size:0.65rem;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;">
                            Bill Rules</div>
                        <div style="font-size:0.85rem;font-weight:600;color:#1e293b;">Multi: <span id="m-rule-multi-bills"
                                style="font-weight:700;"></span> · Max: <span id="m-rule-max-bills"
                                style="font-weight:700;"></span> · Dup: <span id="m-rule-duplicate"
                                style="font-weight:500;color:#64748b;"></span></div>
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; grid-column: 1 / -1; display: flex; align-items: center; gap: 12px;">
                    <div
                        style="width: 40px; height: 40px; background: #fef2f2; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 0.9rem; flex-shrink: 0;">
                        <i class="fas fa-asterisk"></i>
                    </div>
                    <div style="flex:1;">
                        <div
                            style="font-size:0.65rem;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;margin-bottom:6px;">
                            Required Fields</div>
                        <div style="display:flex;gap:16px;flex-wrap:wrap;"><span style="font-size:0.8rem;color:#475569;">📝
                                Remarks: <strong id="m-rule-remarks" style="color:#1e293b;"></strong></span><span
                                style="font-size:0.8rem;color:#475569;">📎 Bill: <strong id="m-rule-bill-mandatory"
                                    style="color:#1e293b;"></strong></span><span style="font-size:0.8rem;color:#475569;">🏢
                                Vendor: <strong id="m-rule-vendor" style="color:#1e293b;"></strong></span></div>
                    </div>
                </div>
            </div>

            <!-- Settlement Rules -->
            <h4
                style="margin: 0 0 12px 0; color: #0f172a; font-size: 0.9rem; font-weight: 700; display: flex; align-items: center; gap: 8px; padding-bottom: 8px; border-bottom: 1.5px solid #edf2f7;">
                <i class="fas fa-check-double" style="color: #7c3aed; font-size: 0.85rem;"></i> Settlement Rules
            </h4>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin-bottom: 1rem;">
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; text-align: center;">
                    <div
                        style="width: 36px; height: 36px; background: #f0fdf4; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 0.85rem; margin: 0 auto 8px;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div
                        style="font-size:0.65rem;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;">
                        Timeline</div>
                    <div style="font-size:1.2rem;font-weight:800;color:#1e293b;margin-top:4px;" id="m-settle-timeline">
                    </div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; text-align: center;">
                    <div
                        style="width: 36px; height: 36px; background: #eff6ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2563eb; font-size: 0.85rem; margin: 0 auto 8px;">
                        <i class="fas fa-university"></i>
                    </div>
                    <div
                        style="font-size:0.65rem;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;">
                        Payment Mode</div>
                    <div style="font-size:0.9rem;font-weight:700;color:#1e293b;margin-top:4px;text-transform:capitalize;"
                        id="m-settle-modes"></div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; text-align: center;">
                    <div
                        style="width: 36px; height: 36px; background: #faf5ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #7c3aed; font-size: 0.85rem; margin: 0 auto 8px;">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div
                        style="font-size:0.65rem;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;">
                        Auto Settlement</div>
                    <div style="font-size:0.85rem;font-weight:600;color:#1e293b;margin-top:4px;" id="m-settle-auto"></div>
                </div>
                <div
                    style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; text-align: center;">
                    <div
                        style="width: 36px; height: 36px; background: #fff7ed; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #f97316; font-size: 0.85rem; margin: 0 auto 8px;">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div
                        style="font-size:0.65rem;text-transform:uppercase;color:#94a3b8;font-weight:600;letter-spacing:0.5px;">
                        Partial Settlement</div>
                    <div style="font-size:0.85rem;font-weight:600;color:#1e293b;margin-top:4px;" id="m-settle-partial">
                    </div>
                </div>
            </div>
            <div
                style="padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.78rem; color: #64748b; line-height: 1.5;">
                <i class="fas fa-info-circle" style="color: #2563eb; margin-right: 4px;"></i>
                Claims settled within <strong style="color:#1e293b;" id="m-settle-summary-timeline"></strong> via <strong
                    style="color:#1e293b;" id="m-settle-summary-mode"></strong>. <span id="m-settle-summary-auto"></span>
                <span id="m-settle-summary-partial"></span>
            </div>
        </div>

        <div class="modal-footer">
            <button onclick="closePolicyModal()" class="btn-gradient"
                style="background: #64748b; box-shadow: none;">Close</button>
        </div>
    </div>

    <script>
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value;

        let policies = [];
        let isLoading = false;

        // Toast notification helper
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                                                                                                                                                                                                                            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                                                                                                                                                                                                                            <span>${message}</span>
                                                                                                                                                                                                                        `;
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        function loadPolicies() {
            if (isLoading) return;
            isLoading = true;

            const url = '/institute-admin/reimbursement-policies';

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(async response => {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        throw new Error('Server returned HTML instead of JSON. Please check the route.');
                    }

                    const result = await response.json();
                    if (!response.ok) {
                        throw new Error(result.message || 'Failed to load policies');
                    }
                    return result;
                })
                .then(data => {
                    // Handle both array and object responses
                    if (Array.isArray(data)) {
                        policies = data;
                    } else if (data.data && Array.isArray(data.data)) {
                        policies = data.data;
                    } else if (data.success && Array.isArray(data.data)) {
                        policies = data.data;
                    } else {
                        policies = [];
                    }

                    // Add logging to debug policy data
                    console.log('Loaded policies:', policies.length);
                    policies.forEach((p, i) => {
                        console.log(`Policy ${i}: ID=${p.id}, Name=${p.policy_name}, Category=${p.policy_category}`);
                        if (p.policy_category === 'custom') {
                            console.log(`  -> policy_data:`, p.policy_data);
                        }
                    });

                    updateStats();
                    applyFilters();
                })
                .catch(err => {
                    console.error('Error loading policies:', err);
                    showToast('Error loading policies: ' + err.message, 'error');
                    document.getElementById("policies-table-body").innerHTML = `
                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                        <td colspan="6" style="text-align: center; padding: 2rem;">
                                                                                                                                                                                                                                            <i class="fas fa-exclamation-triangle" style="color: #ef4444; font-size: 2rem; display: block; margin-bottom: 10px;"></i>
                                                                                                                                                                                                                                            <div style="color: #ef4444; font-weight: 600;">Failed to load policies</div>
                                                                                                                                                                                                                                            <div style="color: #64748b; font-size: 0.85rem; margin-top: 5px;">${err.message}</div>
                                                                                                                                                                                                                                            <button onclick="loadPolicies()" class="btn-gradient" style="margin-top: 1rem; padding: 0.5rem 1.2rem; font-size: 0.8rem;">
                                                                                                                                                                                                                                                <i class="fas fa-sync-alt"></i> Try Again
                                                                                                                                                                                                                                            </button>
                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                    </tr>
                                                                                                                                                                                                                                `;
                    document.getElementById("results-count").textContent = "Error loading policies";
                })
                .finally(() => {
                    isLoading = false;
                });
        }

        // Update Stats
        function updateStats() {
            const total = policies.length;
            const active = policies.filter(p => p.status === 'active').length;

            // Count unique categories
            const categories = new Set(policies.map(p => p.policy_category));
            const categoryCount = categories.size;

            // Calculate average settlement
            let totalSettlement = 0;
            let settlementCount = 0;
            policies.forEach(p => {
                if (p.settlement_timeline) {
                    totalSettlement += parseInt(p.settlement_timeline) || 0;
                    settlementCount++;
                }
            });
            const avgSettlement = settlementCount > 0 ? Math.round(totalSettlement / settlementCount) : 7;

            document.getElementById('kpi-total').textContent = total;
            document.getElementById('kpi-active').textContent = active;
            document.getElementById('kpi-categories').textContent = categoryCount;
            document.getElementById('kpi-settlement').textContent = avgSettlement + ' Days';
            document.getElementById('header-total').textContent = total;
        }

        // Get values summary for table display
        function getValuesSummary(p) {
            const category = p.policy_category;

            if (category === 'travel') {
                let parts = [];
                if (p.two_wheeler_max) parts.push(`2W: ₹${p.two_wheeler_max}`);
                if (p.car_max) parts.push(`Car: ₹${p.car_max}`);
                if (p.flight_max) parts.push(`Flight: ₹${p.flight_max}`);
                return parts.length > 0 ? parts.join(', ') : 'No limits set';
            }

            if (category === 'accommodation') {
                let parts = [];
                if (p.basic_max) parts.push(`Basic: ₹${p.basic_max}`);
                if (p.deluxe_max) parts.push(`Deluxe: ₹${p.deluxe_max}`);
                if (p.premium_max) parts.push(`Premium: ₹${p.premium_max}`);
                return parts.length > 0 ? parts.join(', ') : 'No limits set';
            }

            if (category === 'food') {
                let parts = [];
                if (p.breakfast_max) parts.push(`Bf: ₹${p.breakfast_max}`);
                if (p.lunch_max) parts.push(`Lunch: ₹${p.lunch_max}`);
                if (p.dinner_max) parts.push(`Dinner: ₹${p.dinner_max}`);
                if (p.two_meals_max) parts.push(`2Meals: ₹${p.two_meals_max}`);
                if (p.three_meals_max) parts.push(`3Meals: ₹${p.three_meals_max}`);
                return parts.length > 0 ? parts.join(', ') : 'No limits set';
            }
            if (category === 'custom') {
                let rulesCount = 0;
                let rulesSummary = [];
                let calcType = p.calculation_type ? p.calculation_type.replace('_', ' ') : '';
                try {
                    if (p.policy_data) {
                        const policyData = typeof p.policy_data === 'string' ? JSON.parse(p.policy_data) : p.policy_data;
                        if (Array.isArray(policyData)) {
                            rulesCount = policyData.length;
                            // Show first 2 rule names
                            policyData.slice(0, 2).forEach(rule => {
                                if (rule.name) {
                                    let summary = rule.name;
                                    if (rule.fields && rule.fields.rate) {
                                        summary += ` (₹${rule.fields.rate}`;
                                        if (rule.fields.unit) summary += `/${rule.fields.unit}`;
                                        summary += ')';
                                    }
                                    rulesSummary.push(summary);
                                }
                            });
                        }
                    }
                } catch (e) {
                    rulesCount = 0;
                }

                if (rulesSummary.length > 0) {
                    let text = rulesSummary.join(', ');
                    if (rulesCount > 2) text += ` +${rulesCount - 2} more`;
                    return text;
                }
                return rulesCount > 0 ? `${rulesCount} custom rule(s)` : 'No rules';
            }

            // For mobile, internet, entertainment, miscellaneous
            if (p.min_amount || p.max_amount) {
                return `₹${p.min_amount || 0} - ₹${p.max_amount || 0}`;
            }

            return 'No limits set';
        }

        // Get claim rules summary for table display
        function getClaimRulesSummary(p) {
            const freqType = p.frequency_type || 'unlimited';
            const freqValue = p.frequency_value;
            const submissionWithin = p.submission_within || 7;

            let freqText = freqType === 'unlimited' ? 'Unlimited' : `${freqValue || 1}/${freqType}`;
            return `${freqText} · ${submissionWithin}d`;
        }

        // Render Table
        function renderTable(data) {
            const body = document.getElementById("policies-table-body");
            const emptyState = document.getElementById("table-empty-state");
            body.innerHTML = "";

            if (!data || data.length === 0) {
                emptyState.style.display = "block";
                document.getElementById("results-count").textContent = "Showing 0 policies";
                return;
            }
            emptyState.style.display = "none";
            document.getElementById("results-count").textContent = `Showing ${data.length} policy(ies)`;

            data.forEach((p, index) => {
                const status = p.status || 'active';
                let statusBadge = '';
                switch (status) {
                    case 'active':
                        statusBadge = `<span class="badge-pill badge-active"><i class="fas fa-check-circle"></i> Active</span>`;
                        break;
                    case 'inactive':
                        statusBadge = `<span class="badge-pill badge-inactive"><i class="fas fa-ban"></i> Inactive</span>`;
                        break;
                    case 'upcoming':
                        statusBadge = `<span class="badge-pill" style="background:#e0e7ff;color:#3730a3;"><i class="fas fa-clock"></i> Upcoming</span>`;
                        break;
                    case 'expired':
                        statusBadge = `<span class="badge-pill" style="background:#fef3c7;color:#92400e;"><i class="fas fa-calendar-times"></i> Expired</span>`;
                        break;
                    default:
                        statusBadge = `<span class="badge-pill badge-active"><i class="fas fa-check-circle"></i> ${status}</span>`;
                }

                const category = p.policy_category || 'miscellaneous';
                let iconClass = "fa-file-signature";
                if (category === 'travel') iconClass = "fa-car";
                else if (category === 'accommodation') iconClass = "fa-hotel";
                else if (category === 'food') iconClass = "fa-utensils";
                else if (category === 'mobile') iconClass = "fa-mobile-alt";
                else if (category === 'internet') iconClass = "fa-wifi";
                else if (category === 'entertainment') iconClass = "fa-champagne-glasses";
                else if (category === 'miscellaneous') iconClass = "fa-box";
                else if (category === 'custom') iconClass = "fa-wand-magic-sparkles";

                const valuesSummary = getValuesSummary(p);
                const claimRulesSummary = getClaimRulesSummary(p);
                const policyName = p.policy_name || 'Unnamed Policy';
                const policyCode = p.reimbursement_policy_id || 'N/A';

                // Create unique identifier combining id and category
                const uniqueId = `${p.id}_${category}`;

                const tr = document.createElement("tr");
                tr.innerHTML = `
                                                                                            <td>
                                                                                                <div style="font-weight: 700; color: #0f172a;">${policyName}</div>
                                                                                                <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px;">${policyCode}</div>
                                                                                            </td>
                                                                                            <td>
                                                                                                <span class="badge-pill badge-category">
                                                                                                    <i class="fas ${iconClass}" style="margin-right: 4px;"></i>
                                                                                                    ${category}
                                                                                                </span>
                                                                                            </td>
                                                                                            <td style="font-size: 0.82rem; color: #475569;">${valuesSummary}</td>
                                                                                            <td style="font-size: 0.82rem; color: #475569;">${claimRulesSummary}</td>
                                                                                            <td style="font-size: 0.78rem; color: #475569;">
                                                                                                <div>${p.effective_from ? new Date(p.effective_from).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A'}</div>
                                                                                                <div style="color: #94a3b8;">to</div>
                                                                                                <div>${p.effective_to ? new Date(p.effective_to).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : 'Ongoing'}</div>
                                                                                            </td>
                                                                                            <td>${statusBadge}</td>
                                                                                            <td style="text-align: right;">
                                                                                                <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                                                                    <button class="btn-action view" onclick="viewPolicyDetail('${uniqueId}')" title="View Details"><i class="fas fa-eye"></i></button>
                                                                                                    <a href="/institute-admin/edit-reimbursement-policy/${p.id}" class="btn-action edit" title="Edit Policy"><i class="fas fa-edit"></i></a>
                                                                                                    <a href="/institute-admin/assign-reimbursement-policy" class="btn-action assign" title="Assign Policy"><i class="fas fa-user-tag"></i></a>
                                                                                                    <button class="btn-action delete" onclick="deletePolicy('${p.id}')" title="Delete Policy"><i class="fas fa-trash-alt"></i></button>
                                                                                                </div>
                                                                                            </td>
                                                                                        `;
                body.appendChild(tr);
            });
        }


        // Filters
        function applyFilters() {
            const query = document.getElementById("search-input").value.toLowerCase();
            const category = document.getElementById("filter-category").value;
            const status = document.getElementById("filter-status").value;

            const filtered = policies.filter(p => {
                const policyName = (p.policy_name || '').toLowerCase();
                const policyCategory = (p.policy_category || '');
                const policyCode = (p.reimbursement_policy_id || '').toLowerCase();

                const matchesQuery = policyName.includes(query) ||
                    policyCategory.toLowerCase().includes(query) ||
                    policyCode.includes(query);
                const matchesCategory = category === 'all' || policyCategory === category;
                const matchesStatus = status === 'all' || (p.status || 'active') === status;
                return matchesQuery && matchesCategory && matchesStatus;
            });

            renderTable(filtered);
        }

        document.getElementById("search-input").addEventListener("input", applyFilters);
        document.getElementById("filter-category").addEventListener("change", applyFilters);
        document.getElementById("filter-status").addEventListener("change", applyFilters);

        // Delete Policy
        function deletePolicy(id) {
            if (!confirm("Are you sure you want to delete this policy? This action cannot be undone.")) {
                return;
            }

            const url = `/institute-admin/reimbursement-policies/${id}`;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(async response => {
                    const result = await response.json();
                    if (!response.ok) {
                        throw new Error(result.message || 'Delete failed');
                    }
                    return result;
                })
                .then(data => {
                    showToast('Policy deleted successfully!', 'success');
                    loadPolicies();
                })
                .catch(err => {
                    showToast('Error deleting policy: ' + err.message, 'error');
                });
        }


        // View Policy Detail - Fixed to find correct policy
        function viewPolicyDetail(uniqueId) {
            // Parse the unique identifier
            const parts = uniqueId.split('_');
            const id = parts[0];
            const category = parts.slice(1).join('_');

            // Find the correct policy by matching both id and category
            let p = policies.find(item => {
                return String(item.id) === String(id) && item.policy_category === category;
            });

            if (!p) {
                // Fallback: try finding by just id
                p = policies.find(item => String(item.id) === String(id));
            }

            if (!p) {
                showToast('Policy not found', 'error');
                console.error('Could not find policy with id:', id, 'category:', category);
                return;
            }

            console.log('Viewing policy:', p.policy_name, 'ID:', p.id, 'Category:', p.policy_category);

            // Basic Info
            document.getElementById("m-name").textContent = p.policy_name || 'Unnamed Policy';
            document.getElementById("m-code").textContent = p.reimbursement_policy_id || 'N/A';
            document.getElementById("m-category").textContent = p.policy_category || 'miscellaneous';
            document.getElementById("m-desc").textContent = p.description || 'No description provided.';

            const status = p.status || 'active';
            let statusHtml = '';
            switch (status) {
                case 'active':
                    statusHtml = `<span class="badge-pill badge-active"><i class="fas fa-check-circle"></i> Active</span>`;
                    break;
                case 'inactive':
                    statusHtml = `<span class="badge-pill badge-inactive"><i class="fas fa-ban"></i> Inactive</span>`;
                    break;
                case 'upcoming':
                    statusHtml = `<span class="badge-pill" style="background:#e0e7ff;color:#3730a3;"><i class="fas fa-clock"></i> Upcoming</span>`;
                    break;
                case 'expired':
                    statusHtml = `<span class="badge-pill" style="background:#fef3c7;color:#92400e;"><i class="fas fa-calendar-times"></i> Expired</span>`;
                    break;
                default:
                    statusHtml = `<span class="badge-pill badge-active"><i class="fas fa-check-circle"></i> ${status}</span>`;
            }

            // Financial & Effective Dates
            document.getElementById("m-fy").textContent = p.financial_year || 'N/A';

            let calcType = p.calculation_type || 'N/A';
            calcType = calcType.replace(/_/g, ' ');
            calcType = calcType.replace(/\b\w/g, c => c.toUpperCase()); // Capitalize
            document.getElementById("m-calctype").textContent = calcType;

            document.getElementById("m-efffrom").textContent = p.effective_from
                ? new Date(p.effective_from).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
                : 'N/A';

            document.getElementById("m-effto").textContent = p.effective_to
                ? new Date(p.effective_to).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
                : 'Ongoing (No End Date)';

            document.getElementById("m-status").innerHTML = statusHtml;
            document.getElementById("m-created").textContent = p.created_at ? new Date(p.created_at).toLocaleString() : 'N/A';
            document.getElementById("m-updated").textContent = p.updated_at ? new Date(p.updated_at).toLocaleString() : 'N/A';

            // Ranges
            const rangesContainer = document.getElementById("m-ranges-container");
            rangesContainer.innerHTML = "";
            const policyCategory = p.policy_category || 'miscellaneous';

            if (policyCategory === 'travel') {
                let html = '';

                // Private vehicles
                html += `
                                                                            <div class="range-card">
                                                                                <span class="range-title"><i class="fas fa-motorcycle"></i> Two Wheeler</span>
                                                                                <div class="range-values">
                                                                                    <div>Min: <span class="highlight">₹${p.two_wheeler_min || 0}</span>, Max: <span class="highlight">₹${p.two_wheeler_max || 0}</span>${p.two_wheeler_rate_km ? ', Rate: <span class="highlight">₹' + p.two_wheeler_rate_km + '/KM</span>' : ''}</div>
                                                                                    <div style="font-size: 0.75rem; color: #64748b;">Bill: ${p.two_wheeler_bill_required || 'Yes'} · Photo: ${p.two_wheeler_photo_required || 'No'}</div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="range-card">
                                                                                <span class="range-title"><i class="fas fa-car"></i> Car</span>
                                                                                <div class="range-values">
                                                                                    <div>Min: <span class="highlight">₹${p.car_min || 0}</span>, Max: <span class="highlight">₹${p.car_max || 0}</span>${p.car_rate_km ? ', Rate: <span class="highlight">₹' + p.car_rate_km + '/KM</span>' : ''}</div>
                                                                                    <div style="font-size: 0.75rem; color: #64748b;">Bill: ${p.car_bill_required || 'Yes'} · Photo: ${p.car_photo_required || 'No'}</div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="range-card">
                                                                                <span class="range-title"><i class="fas fa-truck"></i> Auto</span>
                                                                                <div class="range-values">
                                                                                    <div>Min: <span class="highlight">₹${p.auto_min || 0}</span>, Max: <span class="highlight">₹${p.auto_max || 0}</span>${p.auto_rate_km ? ', Rate: <span class="highlight">₹' + p.auto_rate_km + '/KM</span>' : ''}</div>
                                                                                    <div style="font-size: 0.75rem; color: #64748b;">Bill: ${p.auto_bill_required || 'Yes'} · Photo: ${p.auto_photo_required || 'No'}</div>
                                                                                </div>
                                                                            </div>`;

                // Bus sub-categories
                if (p.bus_categories) {
                    var busCat = typeof p.bus_categories === 'string' ? JSON.parse(p.bus_categories) : p.bus_categories;
                    var busNames = {
                        'general': 'General Bus', 'seater_ac': 'Seater (AC)', 'seater_nonac': 'Seater (Non-AC)',
                        'sleeper_ac': 'Sleeper (AC)', 'sleeper_nonac': 'Sleeper (Non-AC)'
                    };
                    html += '<div class="range-card"><span class="range-title">🚌 Bus Categories</span><div class="range-values">';
                    Object.keys(busCat).forEach(function (key) {
                        if (busCat[key] && (busCat[key].min || busCat[key].max)) {
                            html += '<div style="margin-bottom:8px;padding-bottom:8px;border-bottom:1px dashed #e2e8f0;">';
                            html += '<div><strong>' + (busNames[key] || key) + ':</strong> <span class="highlight">₹' + (busCat[key].min || 0) + ' - ₹' + (busCat[key].max || 0) + '</span></div>';
                            html += '<div style="font-size:0.7rem;color:#64748b;">Bill: ' + (busCat[key].bill_required || 'Yes') + ' · Photo: ' + (busCat[key].photo_required || 'No') + '</div>';
                            html += '</div>';
                        }
                    });
                    html += '</div></div>';
                }

                // Train sub-categories
                if (p.train_categories) {
                    var trainCat = typeof p.train_categories === 'string' ? JSON.parse(p.train_categories) : p.train_categories;
                    var trainNames = { 'general': 'General', 'sleeper': 'Sleeper', 'ac3': '3AC', 'ac2': '2AC', 'ac1': '1AC' };
                    html += '<div class="range-card"><span class="range-title">🚂 Train Categories</span><div class="range-values">';
                    Object.keys(trainCat).forEach(function (key) {
                        if (trainCat[key] && (trainCat[key].min || trainCat[key].max)) {
                            html += '<div style="margin-bottom:8px;padding-bottom:8px;border-bottom:1px dashed #e2e8f0;">';
                            html += '<div><strong>' + (trainNames[key] || key) + ':</strong> <span class="highlight">₹' + (trainCat[key].min || 0) + ' - ₹' + (trainCat[key].max || 0) + '</span></div>';
                            html += '<div style="font-size:0.7rem;color:#64748b;">Bill: ' + (trainCat[key].bill_required || 'Yes') + ' · Photo: ' + (trainCat[key].photo_required || 'No') + '</div>';
                            html += '</div>';
                        }
                    });
                    html += '</div></div>';
                }

                // Flight sub-categories
                if (p.flight_categories) {
                    var flightCat = typeof p.flight_categories === 'string' ? JSON.parse(p.flight_categories) : p.flight_categories;
                    var flightNames = { 'economy': 'Economy', 'business': 'Business Class' };
                    html += '<div class="range-card"><span class="range-title">✈️ Flight Categories</span><div class="range-values">';
                    Object.keys(flightCat).forEach(function (key) {
                        if (flightCat[key] && (flightCat[key].min || flightCat[key].max)) {
                            html += '<div style="margin-bottom:8px;padding-bottom:8px;border-bottom:1px dashed #e2e8f0;">';
                            html += '<div><strong>' + (flightNames[key] || key) + ':</strong> <span class="highlight">₹' + (flightCat[key].min || 0) + ' - ₹' + (flightCat[key].max || 0) + '</span></div>';
                            html += '<div style="font-size:0.7rem;color:#64748b;">Bill: ' + (flightCat[key].bill_required || 'Yes') + ' · Photo: ' + (flightCat[key].photo_required || 'No') + '</div>';
                            html += '</div>';
                        }
                    });
                    html += '</div></div>';
                }

                rangesContainer.innerHTML = html;

            } else if (policyCategory === 'accommodation') {
                rangesContainer.innerHTML = `
                                                                                                                                                                <div div class="range-card">
                                                                                                                                                                    <span class="range-title"><i class="fas fa-star"></i> 1-2 Star (Budget)</span>
                                                                                                                                                                    <div class="range-values">
                                                                                                                                                                        <div>₹${p.basic_min || 0} - ₹${p.basic_max || 0} ${p.basic_includes_food ? '🍽️ Includes Food' : ''}</div>
                                                                                                                                                                        <div style="font-size: 0.75rem; color: #64748b;">Bill: ${p.basic_bill_required || 'Yes'} · Photo: ${p.basic_photo_required || 'No'}</div>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="range-card">
                                                                                                                                                                    <span class="range-title"><i class="fas fa-star"></i><i class="fas fa-star"></i> 3-4 Star (Business)</span>
                                                                                                                                                                    <div class="range-values">
                                                                                                                                                                        <div>₹${p.deluxe_min || 0} - ₹${p.deluxe_max || 0} ${p.deluxe_includes_food ? '🍽️ Includes Food' : ''}</div>
                                                                                                                                                                        <div style="font-size: 0.75rem; color: #64748b;">Bill: ${p.deluxe_bill_required || 'Yes'} · Photo: ${p.deluxe_photo_required || 'No'}</div>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="range-card">
                                                                                                                                                                    <span class="range-title"><i class="fas fa-crown"></i> 5 Star (Premium)</span>
                                                                                                                                                                    <div class="range-values">
                                                                                                                                                                        <div>₹${p.premium_min || 0} - ₹${p.premium_max || 0} ${p.premium_includes_food ? '🍽️ Includes Food' : ''}</div>
                                                                                                                                                                        <div style="font-size: 0.75rem; color: #64748b;">Bill: ${p.premium_bill_required || 'Yes'} · Photo: ${p.premium_photo_required || 'No'}</div>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            `;
            } else if (policyCategory === 'food') {
                rangesContainer.innerHTML = `
                                                                                                                                                        <div class="range-card">
                                                                                                                                                                <span class="range-title"><i class="fas fa-utensils"></i> Individual Meals</span>
                                                                                                                                                                <div class="range-values">
                                                                                                                                                                    <div>Breakfast: <span class="highlight">₹${p.breakfast_min || 0} - ₹${p.breakfast_max || 0}</span></div>
                                                                                                                                                                    <div>Lunch: <span class="highlight">₹${p.lunch_min || 0} - ₹${p.lunch_max || 0}</span></div>
                                                                                                                                                                    <div>Dinner: <span class="highlight">₹${p.dinner_min || 0} - ₹${p.dinner_max || 0}</span></div>
                                                                                                                                                                    <div style="font-size: 0.75rem; color: #64748b;">Bill: ${p.individual_bill_required || 'Yes'} · Photo: ${p.individual_photo_required || 'No'}</div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            <div class="range-card">
                                                                                                                                                                <span class="range-title"><i class="fas fa-utensil-spoon"></i> Two Meals Combined</span>
                                                                                                                                                                <div class="range-values">
                                                                                                                                                                    <div>₹${p.two_meals_min || 0} - ₹${p.two_meals_max || 0}</div>
                                                                                                                                                                    <div style="font-size: 0.75rem; color: #64748b;">Bill: ${p.two_meals_bill_required || 'Yes'} · Photo: ${p.two_meals_photo_required || 'No'}</div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            <div class="range-card">
                                                                                                                                                                <span class="range-title"><i class="fas fa-concierge-bell"></i> Three Meals (Full Day)</span>
                                                                                                                                                                <div class="range-values">
                                                                                                                                                                    <div>₹${p.three_meals_min || 0} - ₹${p.three_meals_max || 0}</div>
                                                                                                                                                                    <div style="font-size: 0.75rem; color: #64748b;">Bill: ${p.three_meals_bill_required || 'Yes'} · Photo: ${p.three_meals_photo_required || 'No'}</div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        `;
            } else if (policyCategory === 'custom') {
                let policyData = p.policy_data;
                if (typeof policyData === 'string') {
                    try {
                        policyData = JSON.parse(policyData);
                    } catch (e) {
                        console.error('Error parsing policy_data:', e);
                        policyData = [];
                    }
                }

                if (Array.isArray(policyData) && policyData.length > 0) {
                    let rulesHtml = policyData.map((rule, idx) => {
                        let fieldDetails = '';
                        if (rule.fields) {
                            const fields = rule.fields;

                            // Build detailed field display based on type
                            if (rule.type === 'actual') {
                                if (fields.min) fieldDetails += `Min: ₹${fields.min} | `;
                                if (fields.max) fieldDetails += `Max: ₹${fields.max} | `;
                            } else if (rule.type === 'fixed') {
                                if (fields.amount) fieldDetails += `Fixed Amount: ₹${fields.amount} | `;
                            } else if (rule.type === 'per_km') {
                                if (fields.rate_km) fieldDetails += `Rate: ₹${fields.rate_km}/KM | `;
                                if (fields.min_km) fieldDetails += `Min KM: ${fields.min_km} | `;
                                if (fields.max_km) fieldDetails += `Max KM: ${fields.max_km} | `;
                            } else if (rule.type === 'per_day') {
                                if (fields.rate_day) fieldDetails += `Rate: ₹${fields.rate_day}/Day | `;
                                if (fields.max_days) fieldDetails += `Max Days: ${fields.max_days} | `;
                            } else if (rule.type === 'per_night') {
                                if (fields.rate_night) fieldDetails += `Rate: ₹${fields.rate_night}/Night | `;
                                if (fields.max_nights) fieldDetails += `Max Nights: ${fields.max_nights} | `;
                            } else if (rule.type === 'per_hour') {
                                if (fields.rate_hour) fieldDetails += `Rate: ₹${fields.rate_hour}/Hour | `;
                                if (fields.max_hours) fieldDetails += `Max Hours: ${fields.max_hours} | `;
                            } else if (rule.type === 'per_unit') {
                                if (fields.rate_unit) fieldDetails += `Rate: ₹${fields.rate_unit}/${fields.unit || 'Unit'} | `;
                                if (fields.unit) fieldDetails += `Unit Type: ${fields.unit} | `;
                                if (fields.max_units) fieldDetails += `Max Units: ${fields.max_units} | `;
                            } else if (rule.type === 'percentage') {
                                if (fields.rate_percentage) fieldDetails += `Percentage: ${fields.rate_percentage}% | `;
                                if (fields.max_amount) fieldDetails += `Max Amount: ₹${fields.max_amount} | `;
                            }

                            // Remove trailing separator
                            fieldDetails = fieldDetails.replace(/ \| $/, '');
                        }

                        return `
                                                                                                                                                        <div class="custom-field-item" style="flex-direction: column; align-items: flex-start; gap: 8px; padding: 10px 14px;">
                                                                                                                                                            <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                                                                                                                                                                <div>
                                                                                                                                                                    <span class="field-name" style="font-size: 0.85rem;">${rule.name || 'Rule ' + (idx + 1)}</span>
                                                                                                                                                                    <span class="field-meta" style="margin-left: 6px;">(${rule.type || 'actual'})</span>
                                                                                                                                                                </div>
                                                                                                                                                                <div>
                                                                                                                                                                    <span class="field-badge" style="background: #f1f5f9; color: #475569; margin-right: 4px;">Bill: ${rule.bill_required || 'Yes'}</span>
                                                                                                                                                                    <span class="field-badge" style="background: #f1f5f9; color: #475569;">Photo: ${rule.photo_required || 'No'}</span>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                            ${fieldDetails ? `
                                                                                                                                                                <div style="background: #f0f7ff; padding: 10px 14px; border-radius: 8px; width: 100%; font-size: 0.8rem; color: #1e293b; border-left: 3px solid #2563eb;">
                                                                                                                                                                    <strong style="color: #2563eb; display: block; margin-bottom: 4px;">📋 Configured Values:</strong>
                                                                                                                                                                    ${fieldDetails.split('|').map(item => `<div style="padding: 2px 0;">• ${item.trim()}</div>`).join('')}
                                                                                                                                                                </div>
                                                                                                                                                            ` : `
                                                                                                                                                                <div style="background: #fef3c7; padding: 8px 12px; border-radius: 8px; width: 100%; font-size: 0.8rem; color: #92400e;">
                                                                                                                                                                    <i class="fas fa-exclamation-triangle"></i> No values configured for this rule
                                                                                                                                                                </div>
                                                                                                                                                            `}
                                                                                                                                                        </div>
                                                                                                                                                    `;
                    }).join('');

                    rangesContainer.innerHTML = `
                                                                                                                                                    <div class="range-card">
                                                                                                                                                        <span class="range-title"><i class="fas fa-list-check"></i> Custom Claim Rules (${policyData.length} rule${policyData.length > 1 ? 's' : ''})</span>
                                                                                                                                                        <div style="display: flex; flex-direction: column; gap: 8px;">${rulesHtml}</div>
                                                                                                                                                    </div>
                                                                                                                                                `;
                } else {
                    rangesContainer.innerHTML = `
                                                                                                                                                    <div style="padding: 2rem; text-align: center; color: #94a3b8; background: #f8fafc; border-radius: 12px;">
                                                                                                                                                        <i class="fas fa-info-circle"></i> No custom claim rules defined.
                                                                                                                                                    </div>
                                                                                                                                                `;
                }
            } else {
                // Mobile, internet, entertainment, miscellaneous
                rangesContainer.innerHTML = `
                                                                                                                                            <div class="range-card">
                                                                                                                                                <span class="range-title"><i class="fas fa-tag"></i> General Limits</span>
                                                                                                                                                <div class="range-values">
                                                                                                                                                    <div>Amount Range: <span class="highlight">₹${p.min_amount || 0} - ₹${p.max_amount || 0}</span></div>
                                                                                                                                                    <div>Remarks: ${p.remarks || 'N/A'}</div>
                                                                                                                                                    <div style="font-size: 0.75rem; color: #64748b;">Bill Required: ${p.bill_required || 'Yes'} · Photo Required: ${p.photo_required || 'No'}</div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        `;
            }

            // Claim & Settlement Rules
            const freqType = p.frequency_type || 'unlimited';
            const freqValue = p.frequency_value;
            document.getElementById("m-rule-freq").textContent = freqType === 'unlimited' ? 'Unlimited' : `${freqValue || 1} per ${freqType}`;
            document.getElementById("m-rule-submission").textContent = `${p.submission_within || 7} Days`;
            document.getElementById("m-rule-submission-type").textContent = p.submission_type === 'bill_date' ? 'Of Bill Date' : 'After Expense Date';
            document.getElementById("m-allow-actual-amount").textContent = (p.allow_actual_amount === 'Yes' ? '✅ Allowed' : '🔒 Restricted');
            document.getElementById("m-rule-multi-bills").textContent = p.allow_multi_bills || 'Yes';
            document.getElementById("m-rule-max-bills").textContent = p.allow_multi_bills === 'Yes' ? (p.max_bills || 5) : 'N/A';
            document.getElementById("m-rule-duplicate").textContent = p.allow_same_bill === 'Yes' ? 'Allowed' : 'Unique Only';
            document.getElementById("m-rule-remarks").textContent = p.remarks_mandatory ? 'Required' : 'Optional';
            document.getElementById("m-rule-bill-mandatory").textContent = p.bill_mandatory ? 'Required' : 'Optional';
            document.getElementById("m-rule-vendor").textContent = p.vendor_mandatory ? 'Required' : 'Optional';

            // In viewPolicyDetail function, update the settlement section:
            document.getElementById("m-settle-timeline").textContent = `${p.settlement_timeline || 7} Days`;
            const settleMode = (p.settlement_mode || 'payroll').replace(/_/g, ' ');
            const settleModeCapitalized = settleMode.charAt(0).toUpperCase() + settleMode.slice(1);
            document.getElementById("m-settle-modes").textContent = settleModeCapitalized;
            document.getElementById("m-settle-auto").textContent = (p.auto_settlement === 'Yes' ? '✅ Enabled' : '❌ Disabled');
            document.getElementById("m-settle-partial").textContent = (p.allow_partial_settlement === 'Yes' ? '✅ Allowed' : '❌ Not Allowed');

            // Settlement Summary Banner
            document.getElementById("m-settle-summary-timeline").textContent = `${p.settlement_timeline || 7} days`;
            document.getElementById("m-settle-summary-mode").textContent = settleModeCapitalized;
            document.getElementById("m-settle-summary-auto").textContent = p.auto_settlement === 'Yes'
                ? 'Settlement will be processed automatically after approval.'
                : 'Settlement requires manual processing.';
            document.getElementById("m-settle-summary-partial").textContent = p.allow_partial_settlement === 'Yes'
                ? 'Partial settlement is allowed.'
                : 'Only full settlement is permitted.';

            // Show modal
            document.getElementById("detail-overlay").classList.add("active");
            document.getElementById("detail-modal").classList.add("active");
            switchModalTab('basic');
        }

        function closePolicyModal() {
            document.getElementById("detail-overlay").classList.remove("active");
            document.getElementById("detail-modal").classList.remove("active");
        }

        function switchModalTab(tabName) {
            document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));
            document.querySelectorAll(".tab-content").forEach(c => c.style.display = "none");

            const tabs = ['basic', 'ranges', 'rules'];
            const index = tabs.indexOf(tabName);
            if (index !== -1) {
                document.querySelectorAll(".tab-btn")[index].classList.add("active");
                document.getElementById(`modal-tab-${tabName}`).style.display = "block";
            }
        }

        // Initialize - load policies on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadPolicies();
        });

        // Auto-refresh every 30 seconds
        setInterval(loadPolicies, 30000);
    </script>

@endsection