@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
    <style>
        :root {
            --primary: #2563eb;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --purple: #7c3aed;
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

        .settlement-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .page-header p {
            position: relative;
            z-index: 1;
        }

        .batch-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .batch-meta-item {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 0.75rem 1rem;
        }

        .batch-meta-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            opacity: 0.8;
        }

        .batch-meta-value {
            font-size: 1rem;
            font-weight: 700;
            margin-top: 2px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border: 1.5px solid var(--gray-200);
            border-radius: 16px;
            padding: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--gray-500); font-weight: 700; }
        .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--gray-900); margin-top: 4px; }
        .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }

        /* Claim Card */
        .claim-card {
            background: white;
            border: 1.5px solid var(--gray-200);
            border-radius: 20px;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .claim-card-header {
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid var(--gray-100);
            background: var(--gray-50);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .claim-card-body { padding: 1.5rem 1.75rem; }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: 12px;
            border: 1px solid var(--gray-100);
        }
        .info-item .info-label { font-size: 0.6rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--gray-400); font-weight: 700; }
        .info-item .info-value { font-size: 0.85rem; font-weight: 600; color: var(--gray-800); margin-top: 2px; }

        /* Policy Settlement Card */
        .policy-settlement-card {
            background: linear-gradient(135deg, #faf5ff, #ede9fe);
            border: 1.5px solid #c4b5fd;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
        }
        .policy-settlement-card .ps-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #5b21b6;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .policy-settlement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.5rem;
        }
        .ps-item {
            background: white;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
        }
        .ps-item .ps-label { font-size: 0.6rem; text-transform: uppercase; color: var(--gray-400); font-weight: 600; }
        .ps-item .ps-value { font-size: 0.8rem; font-weight: 700; color: var(--gray-800); margin-top: 2px; }

        /* Approver Cards */
        .approver-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .approver-card {
            border: 1.5px solid var(--gray-200);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            background: white;
        }
        .approver-card.step1 { border-left: 4px solid #2563eb; }
        .approver-card.step2 { border-left: 4px solid #7c3aed; }
        .approver-card .approver-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed var(--gray-200);
        }
        .approver-card .approver-title {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .approver-card.step1 .approver-title { color: #2563eb; }
        .approver-card.step2 .approver-title { color: #7c3aed; }
        .approver-card .approver-name { font-size: 0.9rem; font-weight: 600; color: var(--gray-800); }
        .approver-card .approver-detail {
            font-size: 0.7rem;
            color: var(--gray-500);
            margin-top: 2px;
        }
        .approver-card .approver-items {
            font-size: 0.75rem;
        }
        .approver-card .approver-item-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }
        .approver-card .approver-item-row .item-name { color: var(--gray-600); }
        .approver-card .approver-item-row .item-amount { font-weight: 600; }
        .approver-card .approver-total {
            font-weight: 700;
            border-top: 1px solid var(--gray-200);
            padding-top: 6px;
            margin-top: 4px;
            font-size: 0.8rem;
        }

        /* Settlement Table */
        .settlement-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        .settlement-table th {
            background: var(--gray-50);
            padding: 0.7rem 0.8rem;
            text-align: left;
            font-weight: 700;
            color: var(--gray-600);
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--gray-200);
            white-space: nowrap;
        }
        .settlement-table td {
            padding: 0.7rem 0.8rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: top;
        }
        .settlement-table tr:hover td { background: #fafcfd; }
        .settlement-table tr.row-approved { background: #f0fdf4; }
        .settlement-table tr.row-rejected { background: #fef2f2; }
        .settlement-table tr.row-pending { background: #fffbeb; }

        .amount-cell { font-weight: 700; text-align: right; }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }
        .text-warning { color: var(--warning); }
        .text-purple { color: var(--purple); }

        .status-badge {
            padding: 0.2rem 0.7rem;
            border-radius: 20px;
            font-size: 0.65rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-partial { background: #dbeafe; color: #1e40af; }
        .status-settled { background: #ede9fe; color: #5b21b6; }

        .policy-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: 600;
            background: #eff6ff;
            color: #2563eb;
        }
        .detail-line { font-size: 0.72rem; color: var(--gray-500); margin-top: 2px; }

        .approver-tag {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 1px 6px;
            border-radius: 3px;
            font-size: 0.6rem;
            font-weight: 600;
        }
        .approver-tag.s1 { background: #dbeafe; color: #1e40af; }
        .approver-tag.s2 { background: #ede9fe; color: #5b21b6; }

        /* Settlement Summary */
        .settlement-summary {
            background: white;
            border: 1.5px solid var(--gray-200);
            border-radius: 20px;
            margin-top: 2rem;
            overflow: hidden;
        }
        .settlement-summary-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: white;
        }
        .settlement-summary-body { padding: 1.5rem 2rem; }

        .grand-total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.9rem;
        }
        .grand-total-row:last-child { border-bottom: none; }
        .grand-total-row .label { color: var(--gray-500); font-weight: 500; }
        .grand-total-row .value { font-weight: 700; color: var(--gray-800); }
        .grand-total-row.section-header {
            font-weight: 700; font-size: 0.75rem; text-transform: uppercase;
            letter-spacing: 0.5px; color: var(--gray-600);
            background: var(--gray-50); padding: 8px 12px;
            border-radius: 6px; margin: 8px 0; border: none;
        }
        .grand-total-row.grand-total {
            font-size: 1.2rem; font-weight: 800;
            border-top: 2px solid var(--gray-800);
            padding-top: 16px; margin-top: 8px;
            background: linear-gradient(135deg, #faf5ff, #ede9fe);
            padding: 16px; border-radius: 8px;
        }

        .btn {
            padding: 0.75rem 1.5rem; border-radius: 12px;
            font-weight: 700; font-size: 0.9rem; cursor: pointer;
            border: none; display: inline-flex; align-items: center; gap: 8px;
            transition: all 0.2s;
        }
        .btn-back { background: white; border: 1.5px solid var(--gray-200); color: var(--gray-600); text-decoration: none; }
        .btn-back:hover { background: var(--gray-50); }
        .btn-settle { background: var(--purple); color: white; }
        .btn-settle:hover { background: #6d28d9; transform: translateY(-2px); }
        .btn-settle:disabled { opacity: 0.5; cursor: not-allowed; }

        .remarks-textarea {
            width: 100%; padding: 0.5rem 0.75rem;
            border: 1.5px solid var(--gray-200); border-radius: 8px;
            font-size: 0.8rem; resize: vertical; min-height: 50px; font-family: inherit;
        }
        .note-box {
            margin-top: 1rem; padding: 10px 14px;
            background: #fffbeb; border: 1px solid #fcd34d;
            border-radius: 10px; font-size: 0.8rem; color: #92400e;
        }

        @media (max-width: 768px) {
            .page-header { padding: 1.5rem; }
            .settlement-table { font-size: 0.72rem; }
            .settlement-table th, .settlement-table td { padding: 0.4rem; }
            .approver-cards { grid-template-columns: 1fr; }
        }
    </style>

    <div class="settlement-container p-2">
        <!-- Header -->
        <div class="page-header">
            <h1><i class="fas fa-calculator"></i> Settlement View</h1>
            <p style="margin: 6px 0 0 0; opacity: 0.8; font-size: 0.9rem;">
                Master Request ID: <strong>{{ $batchSummary['master_request_id'] }}</strong>
            </p>
            <div class="batch-meta">
                <div class="batch-meta-item">
                    <div class="batch-meta-label">Employee</div>
                    <div class="batch-meta-value">{{ $batchSummary['employee_name'] }}</div>
                </div>
                <div class="batch-meta-item">
                    <div class="batch-meta-label">Designation</div>
                    <div class="batch-meta-value" style="font-size:0.85rem;">{{ $batchSummary['designation'] }}</div>
                </div>
                <div class="batch-meta-item">
                    <div class="batch-meta-label">Department</div>
                    <div class="batch-meta-value" style="font-size:0.85rem;">{{ $batchSummary['department'] }}</div>
                </div>
                <div class="batch-meta-item">
                    <div class="batch-meta-label">Submission Date</div>
                    <div class="batch-meta-value" style="font-size:0.85rem;">{{ $batchSummary['submission_date'] }}</div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div><div class="stat-label">Total Claimed</div><div class="stat-value">₹{{ number_format($batchSummary['total_claimed'], 2) }}</div></div>
                <div class="stat-icon" style="background:#eff6ff;color:#2563eb;"><i class="fas fa-file-invoice"></i></div>
            </div>
            <div class="stat-card">
                <div><div class="stat-label">Total Approved</div><div class="stat-value text-success">₹{{ number_format($batchSummary['total_approved'], 2) }}</div></div>
                <div class="stat-icon" style="background:#f0fdf4;color:#10b981;"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-card">
                <div><div class="stat-label">Total Rejected</div><div class="stat-value text-danger">₹{{ number_format($batchSummary['total_rejected'], 2) }}</div></div>
                <div class="stat-icon" style="background:#fef2f2;color:#ef4444;"><i class="fas fa-times-circle"></i></div>
            </div>
            <div class="stat-card">
                <div><div class="stat-label">Pending</div><div class="stat-value text-warning">₹{{ number_format($batchSummary['total_pending'], 2) }}</div></div>
                <div class="stat-icon" style="background:#fffbeb;color:#f59e0b;"><i class="fas fa-clock"></i></div>
            </div>
            <div class="stat-card">
                <div><div class="stat-label">Settlement</div><div class="stat-value text-purple">₹{{ number_format($batchSummary['settlement_amount'], 2) }}</div></div>
                <div class="stat-icon" style="background:#faf5ff;color:#7c3aed;"><i class="fas fa-wallet"></i></div>
            </div>
        </div>

        <!-- Claims Detail -->
        @foreach($claims as $index => $claim)
            @php
                $claimClaimed = floatval($claim->claim_amount ?? 0);
                $claimApproved = floatval($claim->approved_amount ?? 0);
                $approval = $claim->approval_detail;
                $step1Name = $approval->step1_approver_name ?? 'N/A';
                $step2Name = $approval->step2_approver_name ?? null;
                $hasStep2 = !empty($step2Name);

                // Get per-item approvals
                $approvedAmounts = $approval->approved_amounts ?? [];
                $step1ApprovedMap = [];
                $step2ApprovedMap = [];

                // Build maps from approved_amounts
                if (is_array($approvedAmounts)) {
                    foreach ($approvedAmounts as $aa) {
                        $si = $aa['sub_index'] ?? 0;
                        $step1ApprovedMap[$si] = floatval($aa['approved'] ?? 0);
                    }
                }

                // Get policy settlement details
                $policySettlementMode = $claim->settlement_mode ?? 'N/A';
                $policySettlementTimeline = $claim->settlement_timeline ?? $claim->submission_within ?? 'N/A';

                // Calculate approver totals
                $step1Total = array_sum($step1ApprovedMap);
                $step2Total = $step1Total; // Step 2 may reduce but uses same array

                $subEntries = $claim->sub_entries_data;
            @endphp
            <div class="claim-card">
                <div class="claim-card-header">
                    <div>
                        <strong style="font-size:1rem;">#{{ $index + 1 }} {{ $claim->reimbursement_request_id }}</strong>
                        <span class="policy-badge" style="margin-left:8px;">{{ $claim->policy_name }} ({{ $claim->policy_category }})</span>
                    </div>
                    <span class="status-badge status-{{ $claim->status === 'approved' ? 'approved' : ($claim->status === 'rejected' ? 'rejected' : ($claim->status === 'step1_approved' ? 'partial' : ($claim->status === 'settled' ? 'settled' : 'pending'))) }}">
                        @if($claim->status === 'approved') <i class="fas fa-check-circle"></i> Approved
                        @elseif($claim->status === 'rejected') <i class="fas fa-times-circle"></i> Rejected
                        @elseif($claim->status === 'step1_approved') <i class="fas fa-clock"></i> Partial
                        @elseif($claim->status === 'settled') <i class="fas fa-check-double"></i> Settled
                        @else <i class="fas fa-clock"></i> Pending
                        @endif
                    </span>
                </div>
                <div class="claim-card-body">
                    <!-- Claim Info -->
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Expense Period</div>
                            <div class="info-value">{{ $claim->expense_date }} @if($claim->to_date) → {{ $claim->to_date }} @endif</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Calculation Type</div>
                            <div class="info-value">{{ str_replace('_', ' ', $claim->calculation_type ?? 'actual') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Actual Amount</div>
                            <div class="info-value">@if(($claim->allow_actual_amount ?? 'No') === 'Yes') <span class="text-success">✅ Allowed</span> @else <span class="text-danger">🔒 Restricted</span> @endif</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Policy Max</div>
                            <div class="info-value">₹{{ number_format($claim->max_amount ?? 0, 2) }}</div>
                        </div>
                    </div>

                    <!-- Policy Settlement Details -->
                    <div class="policy-settlement-card">
                        <div class="ps-title"><i class="fas fa-file-contract"></i> Policy Settlement Terms</div>
                        <div class="policy-settlement-grid">
                            <div class="ps-item">
                                <div class="ps-label">Settlement Mode</div>
                                <div class="ps-value">{{ ucwords(str_replace('_', ' ', $policySettlementMode)) }}</div>
                            </div>
                            <div class="ps-item">
                                <div class="ps-label">Settlement Timeline</div>
                                <div class="ps-value">{{ $policySettlementTimeline }} Days</div>
                            </div>
                            <div class="ps-item">
                                <div class="ps-label">Auto Settlement</div>
                                <div class="ps-value">{{ $claim->auto_settlement ?? 'No' }}</div>
                            </div>
                            <div class="ps-item">
                                <div class="ps-label">Partial Settlement</div>
                                <div class="ps-value">{{ $claim->allow_partial_settlement ?? 'Yes' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Approver Details with Amounts -->
                    <div class="approver-cards">
                        <!-- Step 1 Approver Card -->
                        <div class="approver-card step1">
                            <div class="approver-header">
                                <div>
                                    <div class="approver-title"><i class="fas fa-user-check"></i> Step 1 Approver</div>
                                    <div class="approver-name">{{ $step1Name }}</div>
                                </div>
                                <span class="status-badge status-{{ $approval->step1_status === 'approved' ? 'approved' : ($approval->step1_status === 'rejected' ? 'rejected' : 'pending') }}">
                                    @if($approval->step1_status === 'approved') <i class="fas fa-check"></i> Approved
                                    @elseif($approval->step1_status === 'rejected') <i class="fas fa-times"></i> Rejected
                                    @else <i class="fas fa-clock"></i> Pending
                                    @endif
                                </span>
                            </div>
                            @if($approval->step1_approved_at)
                                <div class="approver-detail">📅 {{ \Carbon\Carbon::parse($approval->step1_approved_at)->format('d M Y, h:i A') }}</div>
                            @endif
                            @if($approval->step1_remarks)
                                <div class="approver-detail">💬 "{{ $approval->step1_remarks }}"</div>
                            @endif
                            <div class="approver-items" style="margin-top: 8px;">
                                <div style="font-size:0.65rem;text-transform:uppercase;color:var(--gray-400);font-weight:600;margin-bottom:4px;">Approved Items</div>
                                @if(!empty($subEntries) && is_array($subEntries))
                                    @foreach($subEntries as $si => $se)
                                        @php $itemAmt = floatval($se['amount'] ?? 0);
                                        $appAmt = $step1ApprovedMap[$si] ?? 0; @endphp
                                        <div class="approver-item-row">
                                            <span class="item-name">{{ $se['range_name'] ?? 'Item #' . ($si + 1) }}</span>
                                            <span class="item-amount {{ $appAmt > 0 ? 'text-success' : 'text-warning' }}">
                                                @if($approval->step1_status === 'approved') ₹{{ number_format($appAmt, 2) }} @else Pending @endif
                                            </span>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="approver-total">
                                    <span>Step 1 Total:</span>
                                    <span style="float:right;">₹{{ number_format($step1Total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 Approver Card -->
                        <div class="approver-card step2" style="{{ !$hasStep2 ? 'opacity:0.5;' : '' }}">
                            <div class="approver-header">
                                <div>
                                    <div class="approver-title"><i class="fas fa-user-shield"></i> Step 2 Approver</div>
                                    <div class="approver-name">{{ $step2Name ?? 'Not Required' }}</div>
                                </div>
                                @if($hasStep2)
                                    @if(!$approval->step1_status || $approval->step1_status === 'pending')
                                        <span class="status-badge status-pending" style="background:#f1f5f9;color:#64748b;"><i class="fas fa-lock"></i> Waiting</span>
                                    @else
                                        <span class="status-badge status-{{ $approval->step2_status === 'approved' ? 'approved' : ($approval->step2_status === 'rejected' ? 'rejected' : 'pending') }}">
                                            @if($approval->step2_status === 'approved') <i class="fas fa-check"></i> Approved
                                            @elseif($approval->step2_status === 'rejected') <i class="fas fa-times"></i> Rejected
                                            @else <i class="fas fa-clock"></i> Pending
                                            @endif
                                        </span>
                                    @endif
                                @endif
                            </div>
                            @if($hasStep2 && $approval->step2_approved_at)
                                <div class="approver-detail">📅 {{ \Carbon\Carbon::parse($approval->step2_approved_at)->format('d M Y, h:i A') }}</div>
                            @endif
                            @if($hasStep2 && $approval->step2_remarks)
                                <div class="approver-detail">💬 "{{ $approval->step2_remarks }}"</div>
                            @endif
                            @if($hasStep2)
                                <div class="approver-items" style="margin-top: 8px;">
                                    <div style="font-size:0.65rem;text-transform:uppercase;color:var(--gray-400);font-weight:600;margin-bottom:4px;">Final Approved Items</div>
                                    @if(!empty($subEntries) && is_array($subEntries))
                                        @foreach($subEntries as $si => $se)
                                            @php $itemAmt = floatval($se['amount'] ?? 0);
                                            $appAmt = $step1ApprovedMap[$si] ?? 0; @endphp
                                            <div class="approver-item-row">
                                                <span class="item-name">{{ $se['range_name'] ?? 'Item #' . ($si + 1) }}</span>
                                                <span class="item-amount {{ $appAmt > 0 ? 'text-success' : 'text-danger' }}">
                                                    @if(in_array($claim->status, ['approved', 'settled'])) ₹{{ number_format($appAmt, 2) }} @elseif($claim->status === 'rejected') Rejected @else Pending @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="approver-total">
                                        <span>Final Total:</span>
                                        <span style="float:right;">₹{{ number_format($step2Total, 2) }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Items Table -->
                    @if(!empty($subEntries) && is_array($subEntries))
                        <table class="settlement-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item / Range</th>
                                    <th>Details</th>
                                    <th style="text-align:right;">Policy Limit</th>
                                    <th style="text-align:right;">Claimed</th>
                                    <th style="text-align:right;">Step 1</th>
                                    <th style="text-align:right;">Final</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subEntries as $si => $subEntry)
                                    @php
                                        $subAmount = floatval($subEntry['amount'] ?? 0);
                                        $subMaxLimit = floatval($subEntry['max_limit'] ?? 0);
                                        $subMinLimit = floatval($subEntry['min_limit'] ?? 0);
                                        $subRangeName = $subEntry['range_name'] ?? 'N/A';
                                        $subRuleType = $subEntry['range_type'] ?? 'actual';
                                        $subRate = floatval($subEntry['rate'] ?? 0);
                                        $subUnit = $subEntry['unit_label'] ?? '';
                                        $step1App = $step1ApprovedMap[$si] ?? 0;
                                        $finalApp = $step1App;

                                        if ($claim->status === 'approved' || $claim->status === 'settled') {
                                            $rowClass = 'row-approved';
                                        } elseif ($claim->status === 'rejected') {
                                            $rowClass = 'row-rejected';
                                        } else {
                                            $rowClass = 'row-pending';
                                        }
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td style="font-weight:700;">{{ $si + 1 }}</td>
                                        <td>
                                            <strong>{{ $subRangeName }}</strong>
                                            @if($subRuleType !== 'actual')<div style="font-size:0.6rem;color:var(--gray-400);">{{ str_replace('_', ' ', $subRuleType) }}</div>@endif
                                        </td>
                                        <td>
                                            @if(!empty($subEntry['from_location']))<div>📍 {{ $subEntry['from_location'] }} → {{ $subEntry['to_location'] }}</div>@endif
                                            @if(!empty($subEntry['travel_date']) || !empty($subEntry['date']))<div class="detail-line">📅 {{ $subEntry['travel_date'] ?? $subEntry['date'] }}</div>@endif
                                            @if(!empty($subEntry['checkin_date']))<div class="detail-line">🏨 {{ $subEntry['checkin_date'] }} {{ $subEntry['checkin_time'] ?? '12:00' }} → {{ $subEntry['checkout_date'] ?? '' }} {{ $subEntry['checkout_time'] ?? '12:00' }}</div>@endif
                                            @if(!empty($subEntry['nights']))<div class="detail-line">🌙 {{ $subEntry['nights'] }} Night(s)</div>@endif
                                            @if(!empty($subEntry['distance_km']))<div class="detail-line">📏 {{ $subEntry['distance_km'] }} KM @if(!empty($subEntry['rate_per_km'])) @ ₹{{ $subEntry['rate_per_km'] }}/KM @endif</div>@endif
                                            @if(!empty($subEntry['vendor']) || !empty($subEntry['hotel_name']))<div class="detail-line">🏢 {{ $subEntry['vendor'] ?? $subEntry['hotel_name'] ?? '' }}</div>@endif
                                            @if(!empty($subEntry['bill_number']))<div class="detail-line">🧾 {{ $subEntry['bill_number'] }}</div>@endif
                                            @if(!empty($subEntry['includes_food']))<div class="detail-line text-success">🍽️ Food Included</div>@endif
                                        </td>
                                        <td style="text-align:right;">
                                            @if($subRate > 0 && in_array($subRuleType, ['per_km', 'per_unit', 'per_day', 'per_night']))<div style="font-weight:600;">₹{{ number_format($subRate, 2) }}/{{ $subUnit ?: 'Unit' }}</div>@endif
                                            @if($subMinLimit > 0)<div class="detail-line">Min: ₹{{ number_format($subMinLimit, 2) }}</div>@endif
                                            @if($subMaxLimit > 0)<div class="detail-line">Max: ₹{{ number_format($subMaxLimit, 2) }}</div>@endif
                                        </td>
                                        <td class="amount-cell">₹{{ number_format($subAmount, 2) }}</td>
                                        <td class="amount-cell {{ $step1App > 0 ? 'text-success' : 'text-warning' }}">
                                            @if($step1App > 0) ₹{{ number_format($step1App, 2) }} @else - @endif
                                        </td>
                                        <td class="amount-cell {{ $finalApp > 0 ? 'text-success' : ($claim->status === 'rejected' ? 'text-danger' : 'text-warning') }}">
                                            @if($claim->status === 'rejected') <span class="text-danger">₹0.00</span>
                                            @elseif(in_array($claim->status, ['approved', 'settled'])) ₹{{ number_format($finalApp, 2) }}
                                            @else Pending @endif
                                        </td>
                                        <td>
                                            @if(in_array($claim->status, ['approved', 'settled']))
                                                <span class="status-badge status-approved"><i class="fas fa-check"></i> Approved</span>
                                            @elseif($claim->status === 'rejected')
                                                <span class="status-badge status-rejected"><i class="fas fa-times"></i> Rejected</span>
                                            @else
                                                <span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:var(--gray-50);font-weight:700;">
                                    <td colspan="4" style="text-align:right;">Claim Totals →</td>
                                    <td class="amount-cell">₹{{ number_format($claimClaimed, 2) }}</td>
                                    <td class="amount-cell text-success">₹{{ number_format($step1Total, 2) }}</td>
                                    <td class="amount-cell text-success">₹{{ number_format($claimApproved, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Settlement Summary -->
        <div class="settlement-summary">
            <div class="settlement-summary-header">
                <h2 style="margin:0;font-size:1.2rem;font-weight:700;"><i class="fas fa-file-invoice-dollar"></i> Final Settlement Summary</h2>
            </div>
            <div class="settlement-summary-body">
                <div class="grand-total-row section-header">Batch Financial Summary</div>
                <div class="grand-total-row">
                    <span class="label">Total Claimed (All Items)</span>
                    <span class="value">₹{{ number_format($batchSummary['total_claimed'], 2) }}</span>
                </div>
                <div class="grand-total-row">
                    <span class="label">Total Approved</span>
                    <span class="value text-success">₹{{ number_format($batchSummary['total_approved'], 2) }}</span>
                </div>
                <div class="grand-total-row">
                    <span class="label">Total Rejected</span>
                    <span class="value text-danger">₹{{ number_format($batchSummary['total_rejected'], 2) }}</span>
                </div>
                <div class="grand-total-row">
                    <span class="label">Still Pending</span>
                    <span class="value text-warning">₹{{ number_format($batchSummary['total_pending'], 2) }}</span>
                </div>
                <div class="grand-total-row grand-total">
                    <span class="label"><i class="fas fa-check-double"></i> Net Settlement Amount</span>
                    <span class="value text-purple">₹{{ number_format($batchSummary['settlement_amount'], 2) }}</span>
                </div>

                @if($batchSummary['total_pending'] > 0)
                    <div class="note-box">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Note:</strong> ₹{{ number_format($batchSummary['total_pending'], 2) }} is still pending approval.
                    </div>
                @endif

                <div style="margin-top:1.5rem;display:flex;gap:1rem;align-items:center;">
                    <input type="text" class="remarks-textarea" id="settlement-remarks" placeholder="Settlement reference / remarks...">
                    <button class="btn btn-settle" onclick="processSettlement()" {{ $batchSummary['total_pending'] > 0 ? 'disabled' : '' }}>
                        <i class="fas fa-check-double"></i> Process Settlement (₹{{ number_format($batchSummary['settlement_amount'], 2) }})
                    </button>
                </div>
            </div>
        </div>

        <div style="margin-top:2rem;">
            <a href="/institute-admin/reimbursement-claims/review/{{ $batchSummary['master_request_id'] }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back to Review
            </a>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        function showToast(message, type = 'success') {
            const colors = { success: '#22c55e', error: '#ef4444' };
            const toast = document.createElement('div');
            toast.style.cssText = `position:fixed;top:20px;right:20px;z-index:9999;background:white;border-radius:12px;padding:16px 24px;box-shadow:0 10px 40px rgba(0,0,0,0.15);display:flex;align-items:center;gap:12px;min-width:300px;border-left:4px solid ${colors[type]};`;
            toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}" style="color:${colors[type]};font-size:1.2rem;"></i><span>${message}</span>`;
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'all 0.3s ease'; setTimeout(() => toast.remove(), 300); }, 4000);
        }

        async function processSettlement() {
            const remarks = document.getElementById('settlement-remarks')?.value || '';
            const amount = {{ $batchSummary['settlement_amount'] }};
            if (!confirm('Confirm settlement of ₹' + amount.toLocaleString('en-IN', {minimumFractionDigits: 2}) + '?')) return;
            try {
                const res = await fetch('/institute-admin/reimbursement-claims/bulk-settlement', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ master_request_id: '{{ $batchSummary['master_request_id'] }}', settlement_amount: amount, remarks: remarks })
                });
                const result = await res.json();
                if (result.success) { showToast('✅ Settlement processed!', 'success'); setTimeout(() => location.reload(), 1500); }
                else { showToast(result.message || 'Failed', 'error'); }
            } catch (err) { showToast('Error: ' + err.message, 'error'); }
        }
    </script>
@endsection