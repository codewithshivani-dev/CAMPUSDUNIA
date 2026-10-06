{{-- employee_claims.blade.php --}}
@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --success: #10b981;
            --success-light: #f0fdf4;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
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
        }

        /* Same header styles as above - reuse with rc- prefix */
        .rc-page-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(15, 23, 42, 0.15);
        }

        .rc-page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .rc-page-header::after {
            content: '';
            position: absolute;
            bottom: -60%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .rc-header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .rc-header-left {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .rc-header-icon {
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

        .rc-header-title {
            color: white;
            margin: 0;
        }

        .rc-header-title h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .rc-header-title p {
            margin: 4px 0 0 0;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .rc-header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .rc-btn-header {
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

        .rc-btn-header:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .rc-stats-badge {
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

        .rc-stats-badge strong {
            color: white;
            font-weight: 700;
        }

        .rc-glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .rc-kpi-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .rc-kpi-val {
            font-size: 1.8rem;
            font-weight: 850;
            color: #0f172a;
            margin-top: 2px;
        }

        .rc-kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .rc-badge-pill {
            padding: 0.25rem 0.8rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .rc-badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .rc-badge-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .rc-badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .rc-badge-settled {
            background: #e0e7ff;
            color: #3730a3;
        }

        .rc-badge-step1 {
            background: #dbeafe;
            color: #1e40af;
        }

        .rc-table-wrapper {
            background: white;
            border-radius: 28px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.02);
        }

        .rc-modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .rc-modern-table th {
            background: #f8fafc;
            padding: 1rem 1.25rem;
            color: #475569;
            font-weight: 700;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1.5px solid #edf2f7;
            text-align: left;
        }

        .rc-modern-table td {
            padding: 1.1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .rc-modern-table tr:last-child td {
            border-bottom: none;
        }

        .rc-master-row {
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .rc-master-row:hover {
            background: #f8fafc;
        }

        .rc-master-row td {
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
        }

        .rc-master-row .rc-expand-icon {
            display: inline-block;
            transition: transform 0.3s ease;
            font-size: 0.9rem;
            color: #64748b;
            width: 24px;
            text-align: center;
        }

        .rc-master-row.expanded .rc-expand-icon {
            transform: rotate(90deg);
        }

        .rc-sub-row {
            background: #fafcfd;
            transition: all 0.3s ease;
        }

        .rc-sub-row.hidden {
            display: none;
        }

        .rc-sub-row td {
            border-bottom: 1px solid #f1f5f9;
            padding: 0.8rem 1.25rem;
            font-size: 0.82rem;
        }

        .rc-sub-row td:first-child {
            padding-left: 3.5rem;
        }

        .rc-filter-section {
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

        .rc-filter-section select,
        .rc-filter-section input {
            padding: 0.5rem 1.2rem;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
            outline: none;
            font-size: 0.85rem;
            background: white;
            cursor: pointer;
        }

        .rc-filter-section select:focus,
        .rc-filter-section input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .rc-filter-left {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .rc-filter-left label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rc-results-count {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }

        .rc-btn-details {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #2563eb;
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .rc-btn-details:hover {
            background: #dbeafe;
            transform: translateY(-1px);
        }

        .rc-empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #94a3b8;
        }

        .rc-empty-state i {
            font-size: 3rem;
            color: #e2e8f0;
            margin-bottom: 10px;
            display: block;
        }

        .rc-empty-state h3 {
            margin: 0;
            color: #475569;
            font-size: 1.1rem;
        }

        .rc-empty-state p {
            margin: 6px 0 0 0;
            font-size: 0.9rem;
        }

        .policy-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .policy-tag {
            background: #eff6ff;
            color: #2563eb;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Modal styles */
        .rc-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(8px);
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .rc-modal-overlay.rc-active {
            opacity: 1;
            pointer-events: auto;
        }

        .rc-modal-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -45%) scale(0.95);
            width: 95%;
            max-width: 900px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-height: 90vh;
            overflow-y: auto;
            padding: 0;
        }

        .rc-modal-container.rc-active {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
            pointer-events: auto;
        }

        .rc-modal-header {
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafcfd;
            border-radius: 24px 24px 0 0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .rc-modal-body {
            padding: 1.5rem 1.75rem;
        }

        .rc-modal-footer {
            padding: 1rem 1.75rem;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            position: sticky;
            bottom: 0;
            background: white;
            border-radius: 0 0 24px 24px;
        }

        .rc-modal-close-btn {
            border: none;
            background: #f1f5f9;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1rem;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .rc-modal-close-btn:hover {
            background: #e2e8f0;
        }

        .rc-info-card {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .rc-info-card h4 {
            margin: 0 0 0.75rem 0;
            font-size: 0.85rem;
            color: #0f172a;
        }

        .rc-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem 1.5rem;
        }

        .rc-info-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .rc-info-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .rc-info-value {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
        }

        .rc-sub-entry-detailed {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 10px;
        }

        .rc-sub-entry-detailed .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .rc-entry-amount-mini {
            flex: 1;
            text-align: center;
            padding: 0.5rem;
            border-radius: 10px;
        }

        .rc-entry-amount-mini.claimed {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .rc-entry-amount-mini.approved {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }

        .rc-entry-amount-mini .mini-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 600;
        }

        .rc-entry-amount-mini .mini-value {
            font-size: 1rem;
            font-weight: 800;
            color: #1e293b;
            margin-top: 2px;
        }

        .rc-entry-amount-mini.approved .mini-value {
            color: #10b981;
        }

        .rc-attachment-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s;
        }

        .rc-attachment-chip:hover {
            background: #eff6ff;
            border-color: #2563eb;
            color: #2563eb;
        }

        .rc-attachment-chip.bill {
            border-color: #bfdbfe;
            background: #eff6ff;
            color: #2563eb;
        }

        .rc-attachment-chip.photo {
            border-color: #fcd34d;
            background: #fffbeb;
            color: #d97706;
        }

        .rc-approval-timeline {
            display: flex;
            gap: 0;
            margin: 1rem 0;
        }

        .rc-approval-node {
            flex: 1;
            text-align: center;
            position: relative;
            padding: 0 8px;
        }

        .rc-approval-node::after {
            content: '';
            position: absolute;
            top: 20px;
            left: calc(50% + 16px);
            width: calc(100% - 32px);
            height: 2px;
            background: #e2e8f0;
            z-index: 0;
        }

        .rc-approval-node:last-child::after {
            display: none;
        }

        .rc-node-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            position: relative;
            z-index: 1;
            font-size: 0.9rem;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .rc-node-icon.completed {
            background: #10b981;
            color: white;
        }

        .rc-node-icon.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
        }

        .rc-node-icon.pending {
            background: #e2e8f0;
            color: #94a3b8;
        }

        .rc-node-icon.rejected {
            background: #ef4444;
            color: white;
        }

        .rc-node-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
        }

        .rc-node-detail {
            font-size: 0.6rem;
            color: #94a3b8;
            margin-top: 2px;
        }

        .rc-node-name {
            font-size: 0.7rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 1px;
        }

        .rc-settlement-card {
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
        }

        .rc-meta-chip {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 600;
            background: #f1f5f9;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        @media (max-width: 768px) {
            .rc-page-header {
                padding: 1.5rem;
            }

            .rc-header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .rc-modal-container {
                max-width: 100%;
                max-height: 100vh;
                border-radius: 24px 24px 0 0;
                bottom: 0;
                top: auto;
                transform: translate(-50%, 100%) scale(1);
            }

            .rc-modal-container.rc-active {
                transform: translate(-50%, 0) scale(1);
            }

            .rc-info-grid {
                grid-template-columns: 1fr;
            }

            .rc-filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .rc-filter-left {
                flex-direction: column;
                align-items: stretch;
            }

            .rc-filter-left input,
            .rc-filter-left select {
                width: 100%;
            }

            .rc-modern-table {
                font-size: 0.8rem;
            }

            .rc-modern-table th,
            .rc-modern-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>

    <div class="p-2">
        <!-- Header -->
        <div class="rc-page-header">
            <div class="rc-header-content">
                <div class="rc-header-left">
                    <div class="rc-header-icon"><i class="fas fa-history"></i></div>
                    <div class="rc-header-title">
                        <h1>My Reimbursement Claims</h1>
                        <p>Track submitted expenses, review approvals, and check settlement statuses</p>
                    </div>
                </div>
                <div class="rc-header-actions">
                    <a href="/institute-admin/create-reimbursement-claim" class="rc-btn-header"
                        style="background: rgba(37, 99, 235, 0.3); border-color: rgba(37, 99, 235, 0.3);">
                        <i class="fas fa-plus"></i> New Claim
                    </a>
                    <span class="rc-stats-badge"><i class="fas fa-file-invoice" style="color: #2563eb;"></i> <strong
                            id="my-kpi-total-header">0</strong> Batches</span>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
            <div class="rc-glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="rc-kpi-title">Total Batches</div>
                    <div class="rc-kpi-val" id="my-kpi-total">0</div>
                </div>
                <div class="rc-kpi-icon" style="background: #eff6ff; color: #2563eb;"><i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="rc-glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="rc-kpi-title">Total Entries</div>
                    <div class="rc-kpi-val" style="color: #7c3aed;" id="my-kpi-entries">0</div>
                </div>
                <div class="rc-kpi-icon" style="background: #f5f3ff; color: #7c3aed;"><i class="fas fa-list-ol"></i></div>
            </div>
            <div class="rc-glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="rc-kpi-title">Pending</div>
                    <div class="rc-kpi-val" style="color: #f59e0b;" id="my-kpi-pending">0</div>
                </div>
                <div class="rc-kpi-icon" style="background: #fffbeb; color: #f59e0b;"><i class="fas fa-clock"></i></div>
            </div>
            <div class="rc-glass-card" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div class="rc-kpi-title">Approved</div>
                    <div class="rc-kpi-val" style="color: #10b981;" id="my-kpi-approved">0</div>
                </div>
                <div class="rc-kpi-icon" style="background: #ecfdf5; color: #10b981;"><i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="rc-filter-section">
            <div class="rc-filter-left">
                <label><i class="fas fa-filter"></i> Filter</label>
                <select id="filter-status" onchange="applyFilter()">
                    <option value="all">All Batches</option>
                    <option value="pending">Has Pending</option>
                    <option value="approved">All Approved</option>
                    <option value="rejected">Has Rejected</option>
                    <option value="settled">Settled</option>
                </select>
                <input type="text" id="filter-search" placeholder="Search by Batch ID or Policy..." oninput="applyFilter()"
                    style="width: 220px;">
            </div>
            <div class="rc-results-count"><span id="my-claims-count">Loading...</span></div>
        </div>

        <!-- Table -->
        <div class="rc-table-wrapper">
            <table class="rc-modern-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th>
                        <th>Batch ID</th>
                        <th>Entries</th>
                        <th>Total Claimed</th>
                        <th>Total Approved</th>
                        <th>Submission Date</th>
                        <th>Overall Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="my-claims-table-body">
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #2563eb;"></i>
                            <div style="margin-top: 10px; color: #94a3b8;">Loading claims...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="my-empty-state" class="rc-empty-state" style="display: none;">
                <i class="fas fa-receipt"></i>
                <h3>No Claims Found</h3>
                <p>You haven't submitted any reimbursement claims yet.</p>
                <a href="/institute-admin/create-reimbursement-claim" class="rc-btn-header"
                    style="margin-top: 1rem; display: inline-flex; background: #2563eb; border: none;"> <i
                        class="fas fa-plus"></i> File New Claim</a>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="rc-modal-overlay" id="my-modal-overlay" onclick="closeClaimDetailModal()"></div>
    <div class="rc-modal-container" id="my-detail-modal">
        <div class="rc-modal-header">
            <h3
                style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-file-invoice-dollar" style="color: #2563eb;"></i>
                <span id="md-title">Claim Details</span>
            </h3>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span id="md-status-badge" class="rc-badge-pill rc-badge-pending">Pending</span>
                <button onclick="closeClaimDetailModal()" class="rc-modal-close-btn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="rc-modal-body" id="modal-detail-content"></div>
        <div class="rc-modal-footer">
            <button class="rc-btn-header"
                style="background: #64748b; box-shadow: none; padding: 0.5rem 1.5rem; font-size: 0.85rem; border: none;"
                onclick="closeClaimDetailModal()">Close</button>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;
        const CURRENT_EMPLOYEE_ID = "{{ $employeeId ?? '' }}";
        let allClaims = [];
        let batches = [];

        const categoryIcons = { 'travel': '🚗', 'accommodation': '🏨', 'food': '🍽️', 'mobile': '📱', 'internet': '🌐', 'entertainment': '🎉', 'miscellaneous': '📦', 'custom': '✨' };
        const categoryLabels = { 'travel': 'Travel', 'accommodation': 'Accommodation', 'food': 'Food', 'mobile': 'Mobile', 'internet': 'Internet', 'entertainment': 'Entertainment', 'miscellaneous': 'Miscellaneous', 'custom': 'Custom' };

        function showToast(message, type = 'success') {
            const colors = { success: '#22c55e', error: '#ef4444' };
            const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle' };
            const toast = document.createElement('div');
            toast.style.cssText = `position:fixed;top:20px;right:20px;z-index:9999;background:white;border-radius:12px;padding:16px 24px;box-shadow:0 10px 40px rgba(0,0,0,0.15);display:flex;align-items:center;gap:12px;min-width:300px;border-left:4px solid ${colors[type]};`;
            toast.innerHTML = `<i class="fas ${icons[type]}" style="color:${colors[type]};font-size:1.2rem;"></i><span>${message}</span>`;
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'all 0.3s ease'; setTimeout(() => toast.remove(), 300); }, 4000);
        }

        async function loadClaims() {
            try {
                const response = await fetch('/institute-admin/reimbursement-claims/my-claims', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    allClaims = result.data || [];
                    groupIntoBatches();
                    updateStats();
                    applyFilter();
                } else {
                    showToast(result.message || 'Failed to load claims', 'error');
                }

            } catch (err) {
                console.error('Error loading claims:', err);

                document.getElementById("my-claims-table-body").innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align:center;padding:2rem;color:#ef4444;">
                            Failed to load claims
                        </td>
                    </tr>
                `;
            }
        }

        function groupIntoBatches() {
            const batchMap = {};
            allClaims.forEach(claim => {
                const masterId = claim.master_request_id || 'UNKNOWN';
                if (!batchMap[masterId]) {
                    batchMap[masterId] = { master_request_id: masterId, entries: [], totalClaimed: 0, totalApproved: 0, submissionDate: null, categories: new Set() };
                }
                batchMap[masterId].entries.push(claim);
                batchMap[masterId].totalClaimed += parseFloat(claim.claim_amount || 0);
                batchMap[masterId].totalApproved += parseFloat(claim.approved_amount || 0);
                batchMap[masterId].categories.add(claim.policy_category || 'other');
                if (!batchMap[masterId].submissionDate) batchMap[masterId].submissionDate = claim.submission_date || claim.created_at;
            });
            batches = Object.values(batchMap).sort((a, b) => new Date(b.submissionDate) - new Date(a.submissionDate));
        }

        function getBatchOverallStatus(batch) {
            const statuses = batch.entries.map(e => e.status);
            if (statuses.every(s => s === 'settled')) return { label: 'Settled', cls: 'rc-badge-settled', icon: 'fa-wallet' };
            if (statuses.every(s => s === 'rejected')) return { label: 'Declined', cls: 'rc-badge-rejected', icon: 'fa-times-circle' };
            if (statuses.every(s => s === 'approved' || s === 'settled')) return { label: 'Approved', cls: 'rc-badge-approved', icon: 'fa-check-circle' };
            if (statuses.some(s => s === 'rejected') && statuses.some(s => s === 'pending' || s === 'step1_approved')) return { label: 'Partial', cls: 'rc-badge-pending', icon: 'fa-exclamation-circle' };
            if (statuses.some(s => s === 'pending' || s === 'step1_approved')) return { label: 'In Progress', cls: 'rc-badge-pending', icon: 'fa-clock' };
            return { label: 'Pending', cls: 'rc-badge-pending', icon: 'fa-clock' };
        }

        function getStatusBadge(status) {
            const map = {
                'approved': { cls: 'rc-badge-approved', icon: 'fa-check-circle', label: 'Approved' },
                'step1_approved': { cls: 'rc-badge-step1', icon: 'fa-user-check', label: 'Step 1 OK' },
                'rejected': { cls: 'rc-badge-rejected', icon: 'fa-times-circle', label: 'Declined' },
                'settled': { cls: 'rc-badge-settled', icon: 'fa-wallet', label: 'Settled' },
                'pending': { cls: 'rc-badge-pending', icon: 'fa-clock', label: 'Pending' }
            };
            const s = map[status] || map['pending'];
            return `<span class="rc-badge-pill ${s.cls}"><i class="fas ${s.icon}"></i> ${s.label}</span>`;
        }

        function updateStats() {
            const totalEntries = allClaims.length;
            const pendingEntries = allClaims.filter(c => c.status === 'pending' || c.status === 'step1_approved').length;
            const approvedEntries = allClaims.filter(c => c.status === 'approved' || c.status === 'settled').length;
            document.getElementById("my-kpi-total").textContent = batches.length;
            document.getElementById("my-kpi-total-header").textContent = batches.length;
            document.getElementById("my-kpi-entries").textContent = totalEntries;
            document.getElementById("my-kpi-pending").textContent = pendingEntries;
            document.getElementById("my-kpi-approved").textContent = approvedEntries;
        }

        function applyFilter() {
            const statusFilter = document.getElementById('filter-status').value;
            const searchQuery = document.getElementById('filter-search').value.toLowerCase();
            let filteredBatches = batches.filter(batch => {
                let matchesStatus = true;
                switch (statusFilter) {
                    case 'pending': matchesStatus = batch.entries.some(e => e.status === 'pending' || e.status === 'step1_approved'); break;
                    case 'approved': matchesStatus = batch.entries.every(e => e.status === 'approved' || e.status === 'settled'); break;
                    case 'rejected': matchesStatus = batch.entries.some(e => e.status === 'rejected'); break;
                    case 'settled': matchesStatus = batch.entries.every(e => e.status === 'settled'); break;
                }
                let matchesSearch = true;
                if (searchQuery) {
                    matchesSearch = batch.master_request_id.toLowerCase().includes(searchQuery) ||
                        batch.entries.some(e => (e.policy_name || '').toLowerCase().includes(searchQuery) || (e.policy_category || '').toLowerCase().includes(searchQuery) || (e.reimbursement_request_id || '').toLowerCase().includes(searchQuery));
                }
                return matchesStatus && matchesSearch;
            });
            renderTable(filteredBatches);
        }

        function renderTable(data) {
            const body = document.getElementById("my-claims-table-body");
            const empty = document.getElementById("my-empty-state");
            body.innerHTML = "";
            if (!data.length) { empty.style.display = "block"; document.getElementById("my-claims-count").textContent = "Showing 0 batches"; return; }
            empty.style.display = "none";
            document.getElementById("my-claims-count").textContent = `Showing ${data.length} batch(es) · ${allClaims.length} total entries`;

            data.forEach((batch, batchIndex) => {
                const overallStatus = getBatchOverallStatus(batch);
                const categorySummary = [...batch.categories].map(cat => `${categoryIcons[cat] || '📋'} ${cat}(${batch.entries.filter(e => e.policy_category === cat).length})`).join(', ');
                const batchId = `batch-${batchIndex}`;

                const masterRow = document.createElement('tr');
                masterRow.className = 'rc-master-row';
                masterRow.setAttribute('data-batch-id', batchId);
                masterRow.onclick = function (e) { if (e.target.closest('button') || e.target.closest('a')) return; toggleBatch(batchId); };
                masterRow.innerHTML = `
                                <td><span class="rc-expand-icon" id="icon-${batchId}"><i class="fas fa-chevron-right"></i></span></td>
                                <td><div style="font-weight: 700; color: #0f172a;">${batch.master_request_id}</div><div style="font-size: 0.7rem; color: #94a3b8;">${categorySummary}</div></td>
                                <td><span class="rc-badge-pill" style="background: #f1f5f9; color: #475569;"><i class="fas fa-list"></i> ${batch.entries.length} entries</span></td>
                                <td><strong>₹${batch.totalClaimed.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</strong></td>
                                <td><strong style="color: ${batch.totalApproved > 0 ? '#10b981' : '#94a3b8'};">₹${batch.totalApproved.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</strong></td>
                                <td style="font-size: 0.85rem; color: #64748b;">${batch.submissionDate ? new Date(batch.submissionDate).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : '-'}</td>
                                <td><span class="rc-badge-pill ${overallStatus.cls}"><i class="fas ${overallStatus.icon}"></i> ${overallStatus.label}</span></td>
                                <td style="text-align: right;"><span style="font-size: 0.7rem; color: #94a3b8;">Click to expand</span></td>
                            `;
                body.appendChild(masterRow);

                batch.entries.forEach((entry, entryIndex) => {
                    const subRow = document.createElement('tr');
                    subRow.className = 'rc-sub-row hidden';
                    subRow.setAttribute('data-batch-id', batchId);
                    subRow.innerHTML = `
                                    <td></td>
                                    <td><div style="display: flex; align-items: center; gap: 8px;"><span style="color: #94a3b8; font-size: 0.7rem;">#${entryIndex + 1}</span><div><div style="font-weight: 600; font-size: 0.85rem;">${entry.policy_name || 'N/A'}</div><span style="font-size: 0.7rem; color: #94a3b8;">${entry.reimbursement_request_id || ''}</span></div></div></td>
                                    <td><span class="rc-badge-pill" style="background: #f1f5f9; color: #64748b; font-size: 0.7rem;">${categoryIcons[entry.policy_category] || '📋'} ${entry.policy_category || 'other'}</span>${entry.selected_range ? `<div style="font-size: 0.7rem; color: #64748b; margin-top: 2px;">${entry.selected_range}</div>` : ''}</td>
                                    <td><strong>₹${parseFloat(entry.claim_amount || 0).toLocaleString('en-IN', { minimumFractionDigits: 2 })}</strong></td>
                                    <td><strong style="color: ${entry.approved_amount > 0 ? '#10b981' : '#94a3b8'};">${entry.approved_amount ? '₹' + parseFloat(entry.approved_amount).toLocaleString('en-IN', { minimumFractionDigits: 2 }) : '—'}</strong></td>
                                    <td style="font-size: 0.8rem; color: #64748b;">${entry.expense_date || '-'}</td>
                                    <td>${getStatusBadge(entry.status)}</td>
                                    <td style="text-align: right;"><button class="rc-btn-details" onclick="event.stopPropagation(); openClaimDetailModal('${entry.reimbursement_request_id || entry.id}')"><i class="fas fa-eye"></i> View</button></td>
                                `;
                    body.appendChild(subRow);
                });
            });
        }

        function toggleBatch(batchId) {
            const masterRow = document.querySelector(`tr.rc-master-row[data-batch-id="${batchId}"]`);
            const subRows = document.querySelectorAll(`tr.rc-sub-row[data-batch-id="${batchId}"]`);
            const icon = document.getElementById(`icon-${batchId}`);
            const isExpanded = masterRow.classList.contains('expanded');
            if (isExpanded) { masterRow.classList.remove('expanded'); subRows.forEach(r => r.classList.add('hidden')); if (icon) icon.style.transform = 'rotate(0deg)'; }
            else { masterRow.classList.add('expanded'); subRows.forEach(r => r.classList.remove('hidden')); if (icon) icon.style.transform = 'rotate(90deg)'; }
        }

        function openClaimDetailModal(claimId) {
            const claim = allClaims.find(c => (c.reimbursement_request_id == claimId || c.id == claimId));
            if (!claim) return;

            const category = claim.policy_category || 'other';
            const statusLabels = {
                'approved': 'Approved',
                'rejected': 'Declined',
                'step1_approved': 'Step 1 OK',
                'settled': 'Settled',
                'pending': 'Pending'
            };
            const statusClasses = {
                'approved': 'rc-badge-approved',
                'rejected': 'rc-badge-rejected',
                'step1_approved': 'rc-badge-step1',
                'settled': 'rc-badge-settled',
                'pending': 'rc-badge-pending'
            };

            // Parse sub-entries
            let subEntries = [];
            if (claim.sub_entries_data) {
                subEntries = typeof claim.sub_entries_data === 'string'
                    ? JSON.parse(claim.sub_entries_data)
                    : claim.sub_entries_data;
            }
            if (!Array.isArray(subEntries) || subEntries.length === 0) {
                subEntries = [{
                    range_name: claim.selected_range || 'Standard',
                    amount: parseFloat(claim.claim_amount || 0),
                    max_limit: parseFloat(claim.max_amount || 0)
                }];
            }

            // Attachments
            let attachmentsHtml = '';
            if (claim.bill_attachment_original) {
                let originals = typeof claim.bill_attachment_original === 'string'
                    ? JSON.parse(claim.bill_attachment_original)
                    : claim.bill_attachment_original;
                let paths = typeof claim.bill_attachment === 'string'
                    ? JSON.parse(claim.bill_attachment)
                    : claim.bill_attachment;
                if (!Array.isArray(originals)) originals = [originals];
                if (!Array.isArray(paths)) paths = [paths];
                originals.forEach((name, i) => {
                    const path = paths[i] || '';
                    const fullUrl = path.startsWith('http') ? path : `/storage/${path}`;
                    attachmentsHtml += `<a href="${fullUrl}" target="_blank" class="rc-attachment-chip bill" title="${name}"><i class="fas fa-file-invoice"></i> ${name.substring(0, 25)}</a>`;
                });
            }
            if (claim.photo_attachment_original) {
                let photoOriginals = typeof claim.photo_attachment_original === 'string'
                    ? JSON.parse(claim.photo_attachment_original)
                    : claim.photo_attachment_original;
                let photoPaths = typeof claim.photo_attachment === 'string'
                    ? JSON.parse(claim.photo_attachment)
                    : claim.photo_attachment;
                if (!Array.isArray(photoOriginals)) photoOriginals = [photoOriginals];
                if (!Array.isArray(photoPaths)) photoPaths = [photoPaths];
                photoOriginals.forEach((name, i) => {
                    const path = photoPaths[i] || '';
                    const fullUrl = path.startsWith('http') ? path : `/storage/${path}`;
                    attachmentsHtml += `<a href="${fullUrl}" target="_blank" class="rc-attachment-chip photo" title="${name}"><i class="fas fa-camera"></i> ${name.substring(0, 25)}</a>`;
                });
            }
            if (!attachmentsHtml) attachmentsHtml = '<span style="font-size:0.85rem;color:#94a3b8;">No attachments</span>';

            // Claim Info Grid
            const claimInfoItems = [
                { label: 'Employee', value: claim.name || 'N/A' },
                { label: 'Employee ID', value: claim.employee_id || 'N/A' },
                { label: 'Designation', value: claim.designation || 'N/A' },
                { label: 'Department', value: claim.department || 'N/A' },
                { label: 'Policy Name', value: claim.policy_name || 'N/A' },
                { label: 'Policy Category', value: `${categoryIcons[category] || '📋'} ${categoryLabels[category] || category}` },
                { label: 'Calculation Type', value: claim.calculation_type || 'N/A' },
                { label: 'Calculated Amount', value: claim.calculated_amount ? '₹' + parseFloat(claim.calculated_amount).toLocaleString('en-IN', { minimumFractionDigits: 2 }) : '—' },
                { label: 'Expense Date', value: claim.expense_date ? new Date(claim.expense_date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A' },
                { label: 'To Date', value: claim.to_date ? new Date(claim.to_date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A' },
                { label: 'Submission Date', value: claim.submission_date ? new Date(claim.submission_date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A' },
                { label: 'Selected Range', value: claim.selected_range || claim.range_name || 'N/A' },
                { label: 'Batch ID', value: claim.master_request_id || 'N/A' },
                { label: 'Claim ID', value: claim.reimbursement_request_id || claim.id || 'N/A' },
                { label: 'Remarks', value: claim.remarks || 'No remarks', fullWidth: true }
            ];

            let infoHtml = claimInfoItems.map(item => {
                const full = item.fullWidth ? 'grid-column: 1 / -1;' : '';
                return `<div class="rc-info-item" style="${full}">
                                    <span class="rc-info-label">${item.label}</span>
                                    <span class="rc-info-value">${item.value}</span>
                                </div>`;
            }).join('');

            // Sub-entries table
            let subEntriesHtml = '';
            if (subEntries.length) {
                subEntriesHtml = `
                            <div style="margin:1rem 0;">
                                <h4 style="margin:0 0 0.75rem 0;font-size:0.9rem;color:#0f172a;">
                                    <i class="fas fa-list-ul" style="color:#2563eb;"></i> Claim Entries
                                    <span class="rc-badge-pill" style="background:#f1f5f9;color:#64748b;font-size:0.7rem;">${subEntries.length}</span>
                                </h4>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered" style="font-size:0.8rem;border-color:#e2e8f0;">
                                        <thead style="background:#f8fafc;">
                                            <tr>
                                                <th>#</th>
                                                <th>Range</th>
                                                <th>Amount</th>
                                                <th>Max Limit</th>
                                                <th>Utilization</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${subEntries.map((entry, idx) => {
                    const amt = parseFloat(entry.amount || 0);
                    const max = parseFloat(entry.max_limit || 0);
                    const pct = max > 0 ? Math.round((amt / max) * 100) : 0;
                    const isOver = amt > max && max > 0;
                    const color = isOver ? '#ef4444' : pct > 80 ? '#f59e0b' : '#10b981';
                    const date = entry.meal_date || entry.travel_date || entry.checkin_date || entry.expense_date || entry.date || '';
                    return `<tr>
                                                    <td>${idx + 1}</td>
                                                    <td><strong>${entry.range_name || 'Standard'}</strong></td>
                                                    <td>₹${amt.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</td>
                                                    <td>${max > 0 ? '₹' + max.toLocaleString('en-IN', { minimumFractionDigits: 2 }) : '—'}</td>
                                                    <td><span style="color:${color};font-weight:600;">${pct}%</span></td>
                                                    <td>${date ? new Date(date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short' }) : '—'}</td>
                                                    <td>${entry.description || entry.remarks || '—'}</td>
                                                </tr>`;
                }).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
            }

            // Approval Details (simple list)
            let approvalHtml = '';
            if (claim.step1_approver_name || claim.step2_approver_name) {
                approvalHtml = `
                            <div style="margin:1rem 0;padding:1rem;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
                                <h4 style="margin:0 0 0.75rem 0;font-size:0.9rem;color:#0f172a;">
                                    <i class="fas fa-clipboard-check" style="color:#2563eb;"></i> Approval Details
                                </h4>
                                ${claim.step1_approver_name ? `
                                    <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #edf2f7;font-size:0.85rem;">
                                        <span style="font-weight:600;color:#64748b;">Step 1 Approver</span>
                                        <span><strong>${claim.step1_approver_name}</strong> 
                                            <span class="rc-badge-pill ${claim.step1_status === 'approved' ? 'rc-badge-approved' : claim.step1_status === 'rejected' ? 'rc-badge-rejected' : 'rc-badge-pending'}" style="font-size:0.65rem;margin-left:6px;">
                                                ${claim.step1_status || 'pending'}
                                            </span>
                                        </span>
                                    </div>
                                    ${claim.step1_remarks ? `
                                        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #edf2f7;font-size:0.8rem;">
                                            <span style="font-weight:600;color:#64748b;">Remarks</span>
                                            <span style="font-style:italic;color:#475569;">"${claim.step1_remarks}"</span>
                                        </div>
                                    ` : ''}
                                ` : ''}
                                ${claim.step2_approver_name ? `
                                    <div style="display:flex;justify-content:space-between;padding:6px 0;${claim.step1_approver_name ? 'border-top:2px solid #e2e8f0;' : ''}font-size:0.85rem;">
                                        <span style="font-weight:600;color:#64748b;">Step 2 Approver</span>
                                        <span><strong>${claim.step2_approver_name}</strong> 
                                            <span class="rc-badge-pill ${claim.step2_status === 'approved' ? 'rc-badge-approved' : claim.step2_status === 'rejected' ? 'rc-badge-rejected' : 'rc-badge-pending'}" style="font-size:0.65rem;margin-left:6px;">
                                                ${claim.step2_status || 'pending'}
                                            </span>
                                        </span>
                                    </div>
                                    ${claim.step2_remarks ? `
                                        <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:0.8rem;">
                                            <span style="font-weight:600;color:#64748b;">Remarks</span>
                                            <span style="font-style:italic;color:#475569;">"${claim.step2_remarks}"</span>
                                        </div>
                                    ` : ''}
                                ` : ''}
                                ${!claim.step1_approver_name && !claim.step2_approver_name ? '<span style="color:#94a3b8;">Awaiting approval</span>' : ''}
                            </div>
                        `;
            }

            // Settlement info (if settled)
            let settlementHtml = '';
            if (claim.status === 'settled' || claim.status === 'approved') {
                settlementHtml = `
                            <div class="rc-settlement-card" style="background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border:1px solid #bbf7d0;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1rem;">
                                <h4 style="margin:0 0 8px 0;font-size:0.85rem;color:#065f46;"><i class="fas fa-hand-holding-usd"></i> Settlement</h4>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 14px;font-size:0.8rem;">
                                    <div style="color:#64748b;">Timeline</div><div style="font-weight:700;">${claim.settlement_timeline || 7} Days</div>
                                    <div style="color:#64748b;">Mode</div><div style="font-weight:700;text-transform:capitalize;">${(claim.settlement_mode || 'bank_transfer').replace(/_/g, ' ')}</div>
                                    ${claim.payout_amount ? `<div style="color:#64748b;">Payout</div><div style="font-weight:700;">₹${parseFloat(claim.payout_amount).toLocaleString('en-IN', { minimumFractionDigits: 2 })}</div>` : ''}
                                </div>
                            </div>
                        `;
            }

            // Summary cards
            const claimedAmt = parseFloat(claim.claim_amount || 0);
            const approvedAmt = parseFloat(claim.approved_amount || 0);
            const pendingAmt = claimedAmt - approvedAmt;

            // Set modal header
            document.getElementById('md-title').innerHTML = `
                        <i class="fas fa-file-invoice-dollar" style="color:#2563eb;"></i>
                        ${claim.reimbursement_request_id || 'Claim #' + claim.id}
                        <span style="font-size:0.7rem;color:#94a3b8;font-weight:400;margin-left:8px;">Batch: ${claim.master_request_id || 'N/A'}</span>
                    `;
            document.getElementById('md-status-badge').className = `rc-badge-pill ${statusClasses[claim.status] || 'rc-badge-pending'}`;
            document.getElementById('md-status-badge').innerHTML = `
                        <i class="fas fa-${claim.status === 'approved' ? 'check-circle' : claim.status === 'rejected' ? 'times-circle' : claim.status === 'settled' ? 'wallet' : claim.status === 'step1_approved' ? 'user-check' : 'clock'}"></i>
                        ${statusLabels[claim.status] || 'Pending'}
                    `;

            // Build modal body
            document.getElementById('modal-detail-content').innerHTML = `
                        <!-- Summary Cards -->
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:1rem;margin-bottom:1rem;">
                            <div style="text-align:center;padding:1rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;">
                                <div style="font-size:0.65rem;text-transform:uppercase;color:#64748b;font-weight:600;">Claimed</div>
                                <div style="font-size:1.3rem;font-weight:800;color:#1e293b;margin-top:4px;">₹${claimedAmt.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</div>
                            </div>
                            <div style="text-align:center;padding:1rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;">
                                <div style="font-size:0.65rem;text-transform:uppercase;color:#64748b;font-weight:600;">Approved</div>
                                <div style="font-size:1.3rem;font-weight:800;color:#10b981;margin-top:4px;">
                                    ${approvedAmt > 0 ? '₹' + approvedAmt.toLocaleString('en-IN', { minimumFractionDigits: 2 }) : '—'}
                                </div>
                            </div>
                            ${pendingAmt > 0 ? `
                            <div style="text-align:center;padding:1rem;background:#fffbeb;border:1px solid #fde68a;border-radius:12px;">
                                <div style="font-size:0.65rem;text-transform:uppercase;color:#64748b;font-weight:600;">Pending</div>
                                <div style="font-size:1.3rem;font-weight:800;color:#f59e0b;margin-top:4px;">₹${pendingAmt.toLocaleString('en-IN', { minimumFractionDigits: 2 })}</div>
                            </div>
                            ` : ''}
                        </div>

                        ${settlementHtml}

                        <!-- Claim Information -->
                        <div class="rc-info-card" style="background:#f8fafc;border:1px solid #edf2f7;border-radius:16px;padding:1.25rem;margin-bottom:1rem;">
                            <h4 style="margin:0 0 0.75rem 0;font-size:0.85rem;color:#0f172a;">
                                <i class="fas fa-info-circle" style="color:#2563eb;"></i> Claim Information
                            </h4>
                            <div class="rc-info-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem 1.5rem;">
                                ${infoHtml}
                            </div>
                        </div>

                        <!-- Sub-entries -->
                        ${subEntriesHtml}

                        <!-- Attachments -->
                        <div class="rc-info-card" style="background:#f8fafc;border:1px solid #edf2f7;border-radius:16px;padding:1.25rem;margin-bottom:1rem;">
                            <h4 style="margin:0 0 0.75rem 0;font-size:0.85rem;color:#0f172a;">
                                <i class="fas fa-paperclip" style="color:#2563eb;"></i> Attachments
                            </h4>
                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                ${attachmentsHtml}
                            </div>
                        </div>

                        <!-- Approval Details -->
                        ${approvalHtml}
                    `;

            // Show modal
            document.getElementById("my-modal-overlay").classList.add("rc-active");
            document.getElementById("my-detail-modal").classList.add("rc-active");
        }

        function closeClaimDetailModal() {
            document.getElementById("my-modal-overlay").classList.remove("rc-active");
            document.getElementById("my-detail-modal").classList.remove("rc-active");
        }

        // Load on page load
        document.addEventListener('DOMContentLoaded', loadClaims);
        setInterval(loadClaims, 30000);
    </script>
@endsection