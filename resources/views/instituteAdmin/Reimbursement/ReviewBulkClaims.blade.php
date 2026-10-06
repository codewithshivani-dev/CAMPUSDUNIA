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

        .review-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .batch-header {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .batch-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.2) 0%, transparent 70%);
            border-radius: 50%;
        }

        .batch-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--gray-50);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .claim-number {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .claim-number .num {
            background: var(--primary);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }

        .claim-card-body {
            padding: 1.5rem 1.75rem;
        }

        .claim-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .claim-info-item .label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-400);
            font-weight: 700;
        }

        .claim-info-item .value {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-top: 2px;
        }

        .sub-entries-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }

        .sub-entries-table th {
            background: var(--gray-50);
            padding: 0.75rem 0.8rem;
            text-align: left;
            font-weight: 700;
            color: var(--gray-600);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--gray-200);
        }

        .sub-entries-table td {
            padding: 0.75rem 0.8rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: top;
        }

        .sub-entries-table tr.row-highlight {
            background: #fffbeb;
        }

        .sub-entries-table tr.row-other {
            opacity: 0.55;
        }

        .sub-entries-table tr:hover td {
            background: var(--gray-50);
        }

        .amount-cell {
            font-weight: 700;
            text-align: right;
        }

        .policy-range-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            background: var(--primary-light);
            color: var(--primary);
        }

        .approved-input {
            width: 110px;
            padding: 0.45rem 0.6rem;
            border: 2px solid var(--gray-200);
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            text-align: right;
            color: var(--gray-800);
        }

        .approved-input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .approved-input.step2-restricted {
            border-color: #7c3aed;
            background: #faf5ff;
        }

        .claim-total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.75rem;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-100);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .claim-total-amount {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--gray-900);
        }

        .status-badge {
            padding: 0.3rem 1rem;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-step1 {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-approve-all {
            background: var(--success);
            color: white;
        }

        .btn-reject-all {
            background: var(--danger);
            color: white;
        }

        .btn-back {
            background: white;
            border: 1.5px solid var(--gray-200);
            color: var(--gray-600);
            text-decoration: none;
        }

        .btn-payout {
            background: #7c3aed;
            color: white;
            text-decoration: none;
        }

        .remarks-textarea {
            width: 200px;
            padding: 0.5rem 0.75rem;
            border: 1.5px solid var(--gray-200);
            border-radius: 8px;
            font-size: 0.8rem;
            min-height: 38px;
            font-family: inherit;
        }

        .rc-info-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.25rem;
            margin-top: 1rem;
        }

        .rc-info-card h4 {
            margin: 0 0 0.75rem 0;
            font-size: 0.85rem;
            color: #0f172a;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 0.5rem;
            border-bottom: 1.5px solid #edf2f7;
        }

        .rc-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .rc-info-item {
            background: white;
            border: 1px solid #edf2f7;
            border-radius: 12px;
            padding: 0.85rem 1rem;
        }

        .approval-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .approval-status.approved {
            background: #d1fae5;
            color: #065f46;
        }

        .approval-status.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .approval-status.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .step-indicator {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: 700;
        }

        .step-indicator.step-1 {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .step-indicator.step-2 {
            background: #faf5ff;
            color: #7c3aed;
            border: 1px solid #ddd6fe;
        }

        .approver-summary {
            margin: 1rem 0;
            padding: 12px 16px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1.5px solid #93c5fd;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .detail-line {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 2px;
        }

        .detail-line strong {
            color: var(--gray-700);
        }

        @media (max-width: 768px) {
            .batch-header {
                padding: 1.5rem;
            }

            .claim-card-header {
                flex-direction: column;
            }

            .rc-info-grid {
                grid-template-columns: 1fr;
            }

            .claim-info-grid {
                grid-template-columns: 1fr 1fr;
            }

            .sub-entries-table {
                font-size: 0.75rem;
            }
        }
    </style>

    <div class="review-container p-2">
        <!-- Batch Header -->
        <div class="batch-header">
            <div>
                <h1 style="margin: 0; font-size: 1.8rem; font-weight: 800;">
                    <i class="fas fa-clipboard-check"></i> Batch Claims Review
                </h1>
                <p style="margin: 6px 0 0 0; font-size: 0.9rem; opacity: 0.8;">
                    Master Request ID: <strong>{{ $batchSummary['master_request_id'] }}</strong>
                </p>
            </div>
            <div class="batch-meta">
                <div class="batch-meta-item">
                    <div class="batch-meta-label">Your Items</div>
                    <div class="batch-meta-value">{{ $batchSummary['total_claims'] }}</div>
                </div>
                <div class="batch-meta-item">
                    <div class="batch-meta-label">Your Total</div>
                    <div class="batch-meta-value">₹{{ number_format($batchSummary['total_amount'], 2) }}</div>
                </div>
                <div class="batch-meta-item">
                    <div class="batch-meta-label">Employee</div>
                    <div class="batch-meta-value" style="font-size: 0.85rem;">{{ $batchSummary['employee_name'] }}</div>
                </div>
                <div class="batch-meta-item">
                    <div class="batch-meta-label">Submission Date</div>
                    <div class="batch-meta-value" style="font-size: 0.85rem;">{{ $batchSummary['submission_date'] }}</div>
                </div>
            </div>
        </div>

        <!-- Claims List -->
        @if(count($claims) > 0)
            @foreach($claims as $index => $claim)
                <div class="claim-card" id="claim-{{ $claim->id }}">
                    <div class="claim-card-header">
                        <div class="claim-number">
                            <span class="num">{{ $index + 1 }}</span>
                            <span>{{ $claim->reimbursement_request_id }}</span>
                            <span class="status-badge status-{{ $claim->status === 'step1_approved' ? 'step1' : $claim->status }}">
                                @if($claim->status === 'pending') <i class="fas fa-clock"></i> Pending
                                @elseif($claim->status === 'step1_approved') <i class="fas fa-user-check"></i> Step 1 OK
                                @elseif($claim->status === 'approved') <i class="fas fa-check-circle"></i> Approved
                                @elseif($claim->status === 'rejected') <i class="fas fa-times-circle"></i> Declined
                                @elseif($claim->status === 'settled') <i class="fas fa-wallet"></i> Settled
                                @endif
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <span style="font-size: 0.85rem; color: var(--gray-500);">
                                Policy: <strong>{{ $claim->policy_name }}</strong>
                            </span>
                            <span class="policy-range-badge">{{ $claim->policy_category }}</span>
                        </div>
                    </div>

                    <div class="claim-card-body">
                        <!-- Info Grid -->
                        <div class="claim-info-grid">
                            <div class="claim-info-item">
                                <div class="label">Employee</div>
                                <div class="value">{{ $claim->name ?? $claim->employee_name ?? 'N/A' }}</div>
                            </div>
                            <div class="claim-info-item">
                                <div class="label">Designation</div>
                                <div class="value">{{ $claim->designation }}</div>
                            </div>
                            <div class="claim-info-item">
                                <div class="label">Department</div>
                                <div class="value">{{ $claim->department }}</div>
                            </div>
                            <div class="claim-info-item">
                                <div class="label">Expense Period</div>
                                <div class="value">{{ $claim->expense_date }} @if($claim->to_date) → {{ $claim->to_date }} @endif
                                </div>
                            </div>
                            <div class="claim-info-item">
                                <div class="label">Calculation Type</div>
                                <div class="value">{{ str_replace('_', ' ', $claim->calculation_type ?? 'actual') }}</div>
                            </div>
                            <div class="claim-info-item">
                                <div class="label">Actual Amount</div>
                                <div class="value">
                                    @if(($claim->allow_actual_amount ?? 'No') === 'Yes')
                                        <span style="color: var(--success);">✅ Allowed</span>
                                    @else
                                        <span style="color: var(--danger);">🔒 Restricted</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Approval Details -->
                        <div class="rc-info-card">
                            <h4><i class="fas fa-clipboard-check"></i> Approval Workflow
                                <span style="margin-left: auto; font-size: 0.7rem; font-weight: 500; color: #94a3b8;">
                                    {{ $claim->has_step2 ? '2-Step Approval' : 'Single Step Approval' }}
                                </span>
                            </h4>
                            <div class="rc-info-grid">
                                <div class="rc-info-item">
                                    <div class="rc-info-label"><span class="step-indicator step-1">Step 1</span>
                                        {{ $claim->step1_approver_name ?? 'Not Assigned' }}
                                    </div>
                                    @if($claim->step1_status === 'approved')
                                        <div class="approval-status approved"><i class="fas fa-check-circle"></i> Approved
                                            @if($claim->step1_approved_at)
                                                <span
                                                    style="font-weight:400;font-size:0.65rem;">{{ \Carbon\Carbon::parse($claim->step1_approved_at)->format('d M, h:i A') }}</span>
                                            @endif
                                        </div>
                                    @elseif($claim->step1_status === 'rejected')
                                        <div class="approval-status rejected"><i class="fas fa-times-circle"></i> Rejected</div>
                                    @else
                                        <div class="approval-status pending"><i class="fas fa-clock"></i> Awaiting</div>
                                    @endif
                                    @if($claim->step1_remarks)
                                        <div style="font-size:0.7rem;color:#64748b;margin-top:4px;">💬 "{{ $claim->step1_remarks }}"
                                        </div>
                                    @endif
                                </div>
                                @if($claim->has_step2)
                                    <div class="rc-info-item">
                                        <div class="rc-info-label"><span class="step-indicator step-2">Step 2</span>
                                            {{ $claim->step2_approver_name ?? 'Not Assigned' }}
                                        </div>
                                        @if(!$claim->step1_status || $claim->step1_status === 'pending')
                                            <div class="approval-status pending" style="background:#f1f5f9;color:#64748b;"><i
                                                    class="fas fa-lock"></i> Waiting for Step 1</div>
                                        @elseif($claim->step2_status === 'approved')
                                            <div class="approval-status approved"><i class="fas fa-check-circle"></i> Approved
                                                @if($claim->step2_approved_at)
                                                    <span
                                                        style="font-weight:400;font-size:0.65rem;">{{ \Carbon\Carbon::parse($claim->step2_approved_at)->format('d M, h:i A') }}</span>
                                                @endif
                                            </div>
                                        @elseif($claim->step2_status === 'rejected')
                                            <div class="approval-status rejected"><i class="fas fa-times-circle"></i> Rejected</div>
                                        @else
                                            <div class="approval-status pending"><i class="fas fa-clock"></i> Awaiting</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Approver Items Summary -->
                        @php
                            $approverItemsTotal = $claim->approver_items_total ?? 0;
                            // Recalculate from sub-entries if needed
                            if ($approverItemsTotal == 0 && !empty($claim->sub_entries_data)) {
                                foreach ($claim->sub_entries_data as $se) {
                                    $approverItemsTotal += floatval($se['amount'] ?? 0);
                                }
                            }
                        @endphp
                        <div class="approver-summary">
                            <span style="font-weight: 700; color: #1e40af; font-size: 0.85rem;">
                                <i class="fas fa-tasks"></i> Items for Your Review
                            </span>
                            <span style="font-weight: 800; color: #1e40af; font-size: 1rem;">
                                Total: ₹{{ number_format($approverItemsTotal, 2) }}
                            </span>
                        </div>

                        <!-- Sub-Entries Table with FULL details -->
                        @php $subEntries = $claim->sub_entries_data; @endphp
                        @if(!empty($subEntries) && is_array($subEntries))
                            <div style="overflow-x: auto;">
                                <table class="sub-entries-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Range/Type</th>
                                            <th>Details</th>
                                            <th>Policy Limit</th>
                                            <th style="text-align:right;">Claimed</th>
                                            <th style="text-align:right;">Approved</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subEntries as $si => $subEntry)
                                            @php
                                                $subAmount = floatval($subEntry['amount'] ?? 0);
                                                $subMaxLimit = floatval($subEntry['max_limit'] ?? 0);
                                                $subMinLimit = floatval($subEntry['min_limit'] ?? 0);
                                                $subRuleType = $subEntry['range_type'] ?? 'actual';
                                                $subRangeName = $subEntry['range_name'] ?? 'N/A';
                                                $subRate = floatval($subEntry['rate'] ?? 0);
                                                $subUnit = $subEntry['unit_label'] ?? '';
                                                $actualAllowed = ($claim->allow_actual_amount ?? 'No') === 'Yes';
                                                $exceeds = $subMaxLimit > 0 && $subAmount > $subMaxLimit;

                                                $isApproverItem = !empty($claim->approver_items) && collect($claim->approver_items)->contains('sub_index', $si);
                                                $rowStyle = $isApproverItem ? 'background: #fffbeb;' : ($claim->status === 'approved' ? '' : 'opacity: 0.6;');
                                            @endphp
                                            <tr style="{{ $rowStyle }}">
                                                <td style="font-weight:700;">{{ $si + 1 }}</td>
                                                <td>
                                                    <span class="policy-range-badge">{{ $subRangeName }}</span>
                                                    @if($subRuleType !== 'actual')
                                                        <div style="font-size:0.65rem;color:var(--gray-400);">
                                                            {{ str_replace('_', ' ', $subRuleType) }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- Travel details --}}
                                                    @if(!empty($subEntry['from_location']))
                                                        <div>📍 <strong>{{ $subEntry['from_location'] }}</strong> →
                                                            <strong>{{ $subEntry['to_location'] }}</strong>
                                                        </div>
                                                    @endif
                                                    @if(!empty($subEntry['travel_date']) || !empty($subEntry['date']))
                                                        <div class="detail-line">📅 {{ $subEntry['travel_date'] ?? $subEntry['date'] }}</div>
                                                    @endif
                                                    @if(!empty($subEntry['distance_km']))
                                                        <div class="detail-line">📏 {{ $subEntry['distance_km'] }} KM</div>
                                                    @endif
                                                    @if(!empty($subEntry['rate_per_km']))
                                                        <div class="detail-line">💰 ₹{{ $subEntry['rate_per_km'] }}/KM</div>
                                                    @endif

                                                    {{-- Accommodation details --}}
                                                    @if(!empty($subEntry['checkin_date']))
                                                        <div class="detail-line">🏨 Check-in: {{ $subEntry['checkin_date'] }} @
                                                            {{ $subEntry['checkin_time'] ?? '12:00' }}
                                                        </div>
                                                        <div class="detail-line">🏨 Check-out: {{ $subEntry['checkout_date'] ?? '' }} @
                                                            {{ $subEntry['checkout_time'] ?? '12:00' }}
                                                        </div>
                                                    @endif
                                                    @if(!empty($subEntry['nights']))
                                                        <div class="detail-line">🌙 {{ $subEntry['nights'] }} Night(s)</div>
                                                    @endif
                                                    @if(!empty($subEntry['includes_food']))
                                                        <div class="detail-line" style="color:#059669;">🍽️ Food Included</div>
                                                    @endif

                                                    {{-- Food details --}}
                                                    @if(!empty($subEntry['meal_date']))
                                                        <div class="detail-line">📅 {{ $subEntry['meal_date'] }}</div>
                                                    @endif

                                                    {{-- Common details --}}
                                                    @if(!empty($subEntry['vendor']) || !empty($subEntry['hotel_name']) || !empty($subEntry['restaurant_name']) || !empty($subEntry['travel_provider']))
                                                        <div class="detail-line">🏢
                                                            {{ $subEntry['vendor'] ?? $subEntry['hotel_name'] ?? $subEntry['restaurant_name'] ?? $subEntry['travel_provider'] ?? '' }}
                                                        </div>
                                                    @endif
                                                    @if(!empty($subEntry['bill_number']))
                                                        <div class="detail-line">🧾 Bill #: {{ $subEntry['bill_number'] }}</div>
                                                    @endif
                                                    @if(!empty($subEntry['description']))
                                                        <div class="detail-line">📝 {{ Str::limit($subEntry['description'], 50) }}</div>
                                                    @endif

                                                    @php
                                                        $dateLabel = $subEntry['travel_date'] ?? $subEntry['meal_date'] ?? $subEntry['checkin_date'] ?? $subEntry['expense_date'] ?? $subEntry['date'] ?? '';
                                                        $claimedAmount = floatval($subEntry['amount'] ?? 0);
                                                        $existingClaimed = floatval($subEntry['range_existing_claimed'] ?? 0);
                                                        $remainingLimit = floatval($subEntry['range_remaining_limit'] ?? 0);
                                                        $exceededAmount = floatval($subEntry['exceeded_amount'] ?? 0);
                                                        $isLimitExceeded = !empty($subEntry['is_exceeded']) && $exceededAmount > 0;
                                                    @endphp
                                                    @if($existingClaimed > 0 || $remainingLimit > 0 || $isLimitExceeded)
                                                        <div
                                                            style="margin-top:8px; padding:8px 10px; border-radius:10px; background:{{ $isLimitExceeded ? '#fef2f2' : '#eff6ff' }}; border:1px solid {{ $isLimitExceeded ? '#fecaca' : '#bfdbfe' }};">
                                                            <div
                                                                style="display:flex; justify-content:space-between; gap:8px; font-size:0.71rem; font-weight:700; color:{{ $isLimitExceeded ? '#b91c1c' : '#1d4ed8' }};">
                                                                <span>Limit Breakdown</span>
                                                                <span>{{ $isLimitExceeded ? '⚠️ Exceeded' : '✅ Within range' }}</span>
                                                            </div>
                                                            <div
                                                                style="display:flex; justify-content:space-between; gap:8px; font-size:0.68rem; color:#475569; margin-top:4px;">
                                                                <span>Claimed</span>
                                                                <span>₹{{ number_format($claimedAmount, 2) }}</span>
                                                            </div>
                                                            <div
                                                                style="display:flex; justify-content:space-between; gap:8px; font-size:0.68rem; color:#475569;">
                                                                <span>Previous claim(s)</span>
                                                                <span>₹{{ number_format($existingClaimed, 2) }}</span>
                                                            </div>
                                                            <div
                                                                style="display:flex; justify-content:space-between; gap:8px; font-size:0.68rem; color:#475569;">
                                                                <span>Remaining</span>
                                                                <span>₹{{ number_format($remainingLimit, 2) }}</span>
                                                            </div>
                                                            @if($dateLabel)
                                                                <div style="font-size:0.67rem; color:#64748b; margin-top:4px;">Date used:
                                                                    {{ $dateLabel }}
                                                                </div>
                                                            @endif
                                                            @if($isLimitExceeded)
                                                                <div style="font-size:0.67rem; color:#b91c1c; font-weight:700; margin-top:3px;">
                                                                    Exceeded by ₹{{ number_format($exceededAmount, 2) }}</div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- Policy Limit with Rate, Min, Max --}}
                                                    @if(in_array($subRuleType, ['per_km', 'per_unit', 'per_day', 'per_night', 'per_hour']) && $subRate > 0)
                                                        <div style="font-weight:600;">Rate:
                                                            ₹{{ number_format($subRate, 2) }}/{{ $subUnit ?: 'Unit' }}</div>
                                                    @endif
                                                    @if($subMinLimit > 0 && $subMaxLimit > 0)
                                                        <div class="detail-line">Min: ₹{{ number_format($subMinLimit, 2) }}</div>
                                                        <div class="detail-line">Max: ₹{{ number_format($subMaxLimit, 2) }}</div>
                                                    @elseif($subMinLimit > 0)
                                                        <div class="detail-line">Min: ₹{{ number_format($subMinLimit, 2) }}</div>
                                                    @elseif($subMaxLimit > 0)
                                                        <div class="detail-line">Max: ₹{{ number_format($subMaxLimit, 2) }}</div>
                                                    @else
                                                        <span style="font-size:0.75rem;color:var(--gray-400);">No Limit</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="amount-cell {{ $exceeds && !$actualAllowed ? 'amount-exceeds' : 'amount-within' }}">
                                                    ₹{{ number_format($subAmount, 2) }}
                                                    @if($exceeds && !$actualAllowed)
                                                        <div style="font-size:0.6rem;color:var(--danger);">⚠️
                                                            +₹{{ number_format($subAmount - $subMaxLimit, 2) }}</div>
                                                    @endif
                                                </td>
                                                <td style="text-align:right;">
                                                    @if($claim->can_approve && ($isApproverItem || empty($claim->approver_items)))
                                                        @php
                                                            $defaultValue = $subAmount;
                                                            if (!$actualAllowed && $subMaxLimit > 0) {
                                                                $defaultValue = min($subAmount, $subMaxLimit);
                                                            }
                                                            if ($claim->current_approval_step === 'step2' && !empty($claim->approved_amounts)) {
                                                                foreach ($claim->approved_amounts as $ai) {
                                                                    if (($ai['sub_index'] ?? null) == $si) {
                                                                        $defaultValue = floatval($ai['approved'] ?? $subAmount);
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            $inputMax = ($claim->current_approval_step === 'step2')
                                                                ? ($actualAllowed ? 999999999 : min($defaultValue, $subMaxLimit > 0 ? $subMaxLimit : $defaultValue))
                                                                : (($actualAllowed || $subMaxLimit <= 0) ? 999999999 : $subMaxLimit);
                                                            $isStep2Restricted = ($claim->current_approval_step === 'step2' && !$actualAllowed);
                                                        @endphp
                                                        <input type="number"
                                                            class="approved-input {{ $isStep2Restricted ? 'step2-restricted' : '' }}"
                                                            value="{{ number_format($defaultValue, 2, '.', '') }}" data-sub-index="{{ $si }}"
                                                            data-claim-id="{{ $claim->id }}" data-max="{{ $inputMax }}"
                                                            data-step2-restricted="{{ $isStep2Restricted ? 'true' : 'false' }}" step="0.01"
                                                            onchange="updateSubApprovedAmount(this, {{ $inputMax }})">
                                                    @elseif(in_array($claim->status, ['approved', 'settled']))
                                                        @php
                                                            $displayAmt = $subAmount;
                                                            if (!empty($claim->approved_amounts)) {
                                                                foreach ($claim->approved_amounts as $ai) {
                                                                    if (($ai['sub_index'] ?? null) == $si) {
                                                                        $displayAmt = floatval($ai['approved'] ?? $subAmount);
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        <span style="color:#10b981;font-weight:700;">₹{{ number_format($displayAmt, 2) }}</span>
                                                    @else
                                                        <span style="color:#94a3b8;">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($claim->status === 'approved' || $claim->status === 'settled')
                                                        <span class="approval-status approved"><i class="fas fa-check"></i> Approved</span>
                                                    @elseif($claim->status === 'rejected')
                                                        <span class="approval-status rejected"><i class="fas fa-times"></i> Rejected</span>
                                                    @elseif($isApproverItem)
                                                        <span
                                                            style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:10px;font-size:0.65rem;font-weight:600;"><i
                                                                class="fas fa-clock"></i> Awaiting</span>
                                                    @else
                                                        <span style="font-size:0.65rem;color:#94a3b8;">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        {{-- Remarks --}}
                        @if($claim->remarks)
                            <div
                                style="margin-top:1rem;padding:0.75rem 1rem;background:var(--gray-50);border-radius:8px;font-size:0.85rem;">
                                <strong>📝 Remarks:</strong> {{ $claim->remarks }}
                            </div>
                        @endif

                        {{-- Attachments --}}
                        @if($claim->bill_attachment)
                            <div style="margin-top:1rem;display:flex;gap:8px;flex-wrap:wrap;">
                                @php
                                    $attachments = is_string($claim->bill_attachment) ? json_decode($claim->bill_attachment, true) ?? [$claim->bill_attachment] : $claim->bill_attachment;
                                    $originals = is_string($claim->bill_attachment_original ?? '[]') ? json_decode($claim->bill_attachment_original, true) ?? [] : ($claim->bill_attachment_original ?? []);
                                @endphp
                                @foreach($attachments as $ai => $attachment)
                                    <a href="/reimbursement-file/{{ $attachment }}" target="_blank"
                                        style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:var(--primary-light);border-radius:20px;text-decoration:none;color:var(--primary);font-size:0.8rem;font-weight:600;">
                                        <i class="fas fa-paperclip"></i> {{ $originals[$ai] ?? 'Attachment ' . ($ai + 1) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Total Section --}}
                    <div class="claim-total-section">
                        <div>
                            <span style="font-size:0.75rem;color:var(--gray-400);">Your Total</span>
                            <div class="claim-total-amount">₹{{ number_format($approverItemsTotal, 2) }}</div>
                        </div>
                        <div style="display:flex;gap:1rem;align-items:center;">
                            @if($claim->can_approve)
                                <input type="text" class="remarks-textarea" placeholder="Remarks (required for reject)"
                                    id="remarks-{{ $claim->id }}">
                                <button class="btn btn-approve-all" onclick="approveClaim({{ $claim->id }})"
                                    style="padding:0.5rem 1rem;font-size:0.8rem;"><i class="fas fa-check"></i> Approve</button>
                                <button class="btn btn-reject-all" onclick="rejectClaim({{ $claim->id }})"
                                    style="padding:0.5rem 1rem;font-size:0.8rem;"><i class="fas fa-times"></i> Reject</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div style="text-align:center;padding:4rem;color:var(--gray-400);">
                <i class="fas fa-inbox" style="font-size:4rem;display:block;margin-bottom:1rem;"></i>
                <h3>No Items Pending Your Approval</h3>
                <p>There are no claim items waiting for your review in this batch.</p>
            </div>
        @endif

        <div style="margin-top:2rem;display:flex;justify-content:space-between;align-items:center;">
            <a href="{{ route('view.all.claims') }}" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to
                Claims List</a>
            <!-- <a href="/institute-admin/reimbursement-claims/settlement/{{ $batchSummary['master_request_id'] }}"
                        class="btn btn-payout"><i class="fas fa-calculator"></i> View Settlement</a> -->
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

        function updateSubApprovedAmount(input, maxLimit) {
            const val = parseFloat(input.value) || 0;
            const isStep2Restricted = input.dataset.step2Restricted === 'true';
            if (isStep2Restricted && val > maxLimit) {
                input.value = maxLimit.toFixed(2);
                showToast('Cannot exceed Step 1 approval of ₹' + maxLimit.toFixed(2), 'error');
            } else if (!isStep2Restricted && maxLimit > 0 && maxLimit < 999999 && val > maxLimit) {
                input.value = maxLimit.toFixed(2);
                showToast('Amount cannot exceed policy maximum of ₹' + maxLimit.toFixed(2), 'error');
            }
        }

        async function approveClaim(claimId) {
            const remarks = document.getElementById('remarks-' + claimId)?.value || 'Approved';
            const approvedAmounts = {};
            document.querySelectorAll(`.approved-input[data-claim-id="${claimId}"]`).forEach(input => {
                approvedAmounts[input.dataset.subIndex] = parseFloat(input.value) || 0;
            });
            if (!confirm('Approve this claim with the entered amounts?')) return;
            try {
                const res = await fetch('/institute-admin/reimbursement-claims/bulk-action', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'approve', claim_ids: [claimId], approved_amounts: { [claimId]: approvedAmounts }, remarks: { [claimId]: remarks } })
                });
                const result = await res.json();
                if (result.success) { showToast('Claim approved!', 'success'); setTimeout(() => location.reload(), 1000); }
                else { showToast(result.message || 'Failed', 'error'); }
            } catch (err) { showToast('Error: ' + err.message, 'error'); }
        }

        async function rejectClaim(claimId) {
            const remarks = document.getElementById('remarks-' + claimId)?.value.trim();
            if (!remarks) { showToast('Please provide a reason for rejection', 'error'); return; }
            if (!confirm('Reject this claim?')) return;
            try {
                const res = await fetch('/institute-admin/reimbursement-claims/bulk-action', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'reject', claim_ids: [claimId], remarks: { [claimId]: remarks } })
                });
                const result = await res.json();
                if (result.success) { showToast('Claim rejected!', 'success'); setTimeout(() => location.reload(), 1000); }
                else { showToast(result.message || 'Failed', 'error'); }
            } catch (err) { showToast('Error: ' + err.message, 'error'); }
        }
    </script>
@endsection