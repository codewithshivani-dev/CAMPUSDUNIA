@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
    @php
        $money = fn($amount) => 'INR ' . number_format((float) ($amount ?? 0), 2);
        $number = fn($value) => number_format((float) ($value ?? 0));
        $date = function ($value) {
            if (!$value) {
                return '-';
            }
            try {
                return \Carbon\Carbon::parse($value)->format('d M Y');
            } catch (\Exception $e) {
                return '-';
            }
        };
        $statusLabel = fn($status) => ucwords(str_replace('_', ' ', $status ?: 'pending'));
        $statusClass = function ($status) {
            return [
                'pending' => 'warning',
                'step1_approved' => 'info',
                'approved' => 'success',
                'settled' => 'primary',
                'completed' => 'success',
                'paid' => 'success',
                'rejected' => 'danger',
                'declined' => 'danger',
                'failed' => 'danger',
                'processing' => 'info',
                'active' => 'success',
                'inactive' => 'muted',
            ][strtolower($status ?: 'pending')] ?? 'muted';
        };
        $stageFor = function ($claim) {
            if (in_array($claim->status, ['rejected', 'declined'], true)) {
                return 'Rejected';
            }
            if (in_array($claim->status, ['settled', 'completed'], true)) {
                return 'Paid';
            }
            if ($claim->status === 'approved') {
                return 'Settlement Pending';
            }
            if ($claim->step1_status === 'approved' && $claim->step2_approver_id && $claim->step2_status !== 'approved') {
                return 'Step 2 Pending';
            }
            if (($claim->step1_status ?: $claim->status) === 'pending') {
                return 'Step 1 Pending';
            }
            return 'Submitted';
        };
        $percent = function ($value, $max) {
            $max = max((float) $max, 1);
            return min(100, round(((float) $value / $max) * 100, 1));
        };
        $statusMax = max($dashboard['statusCounts']->values()->all() ?: [1]);
        $monthMax = max($dashboard['monthlyTrend']->pluck('claimed')->all() ?: [1]);
        $categoryMax = max($dashboard['categoryAnalysis']->pluck('claimed')->all() ?: [1]);
        $query = request()->query();
        $lastMonthCount = (int) ($dashboard['kpis']['last_month_count'] ?? 0);
        $thisMonthCount = (int) ($dashboard['kpis']['this_month_count'] ?? 0);
        $monthDelta = $thisMonthCount - $lastMonthCount;
        $monthDeltaPct = $lastMonthCount > 0 ? round(($monthDelta / max(1, $lastMonthCount)) * 100, 1) : ($monthDelta !== 0 ? 100 : 0);
        $monthDeltaSign = $monthDelta >= 0 ? '+' : '';
        $monthDeltaClass = $monthDelta >= 0 ? 'rd-badge-success' : 'rd-badge-danger';

        $pageTitle = $dashboard['page_title'] ?? 'Reimbursement Dashboard';
        $pageDescription = $dashboard['description'] ?? 'Admin command center for policies, claims, approvals, settlements, payouts and exceptions.';

        $filterOptions = $dashboard['filterOptions'] ?? [];
        $timeFilterOptions = $filterOptions['ranges'] ?? [
            'this_month' => 'This Month',
            'last_3_months' => 'Last 3 Months',
            'last_6_months' => 'Last 6 Months',
            'this_financial_year' => 'This Financial Year',
            'previous_financial_year' => 'Previous Financial Year',
            'custom' => 'Custom Range',
        ];
        $approvalStageOptions = $filterOptions['approvalStages'] ?? [
            'all' => 'All Stages',
            'submitted' => 'Submitted',
            'step1_pending' => 'Step 1 Pending',
            'step2_pending' => 'Step 2 Pending',
            'settlement_pending' => 'Settlement Pending',
            'paid' => 'Paid',
            'rejected' => 'Rejected',
        ];

        $kpiCards = $dashboard['kpiCards'] ?? [
            [
                'label' => 'Total Claims',
                'value' => $dashboard['kpis']['total_claims'] ?? 0,
                'sub' => '<strong>' . $number($thisMonthCount) . '</strong> this month &nbsp;•&nbsp; <span title="Last month count">Last month: <strong>' . $number($lastMonthCount) . '</strong></span> &nbsp;•&nbsp; <span class="rd-kpi-delta ' . $monthDeltaClass . '" title="Change from last month">' . $monthDeltaSign . $monthDeltaPct . '%</span>',
                'icon' => 'fas fa-file-invoice',
                'iconStyle' => '',
            ],
            [
                'label' => 'Pending Approval',
                'value' => $dashboard['kpis']['pending_claims'] ?? 0,
                'sub' => $money($dashboard['kpis']['pending_amount'] ?? 0),
                'icon' => 'fas fa-hourglass-half',
                'iconStyle' => 'background:#fffbeb;color:var(--rd-amber);',
            ],
            [
                'label' => 'Approved Claims',
                'value' => $dashboard['kpis']['approved_claims'] ?? 0,
                'sub' => $money($dashboard['kpis']['approved_total'] ?? 0),
                'icon' => 'fas fa-circle-check',
                'iconStyle' => 'background:#ecfdf5;color:var(--rd-green);',
            ],
            [
                'label' => 'Rejected Claims',
                'value' => $dashboard['kpis']['rejected_claims'] ?? 0,
                'sub' => $money($dashboard['kpis']['rejected_amount'] ?? 0),
                'icon' => 'fas fa-circle-xmark',
                'iconStyle' => 'background:#fef2e2;color:var(--rd-red);',
            ],
            [
                'label' => 'Pending Settlement',
                'value' => $dashboard['kpis']['pending_settlement_count'] ?? 0,
                'sub' => $money($dashboard['kpis']['pending_settlement_amount'] ?? 0),
                'icon' => 'fas fa-wallet',
                'iconStyle' => 'background:#e0f2fe;color:var(--rd-cyan);',
            ],
            [
                'label' => 'Paid / Settled',
                'value' => $dashboard['kpis']['paid_count'] ?? 0,
                'sub' => $money($dashboard['kpis']['paid_total'] ?? 0),
                'icon' => 'fas fa-money-check-alt',
                'iconStyle' => 'background:#dcfce7;color:var(--rd-green);',
            ],
            [
                'label' => 'Total Claimed',
                'value' => $money($dashboard['kpis']['claimed_total'] ?? 0),
                'sub' => 'Approved ' . $money($dashboard['kpis']['approved_total'] ?? 0),
                'icon' => 'fas fa-indian-rupee-sign',
                'iconStyle' => 'background:#f5f3ff;color:var(--rd-indigo);',
            ],
            [
                'label' => 'Policies / Assignments',
                'value' => ($dashboard['kpis']['active_policies'] ?? 0) . ' / ' . ($dashboard['kpis']['total_assignments'] ?? 0),
                'sub' => ($number($dashboard['kpis']['inactive_policies'] ?? 0)) . ' inactive policies',
                'icon' => 'fas fa-file-contract',
                'iconStyle' => '',
            ],
        ];
    @endphp

    <style>
        :root {
            --rd-primary: #1f3b76;
            --rd-primary-light: #2563eb;
            --rd-secondary: #059669;
            --rd-accent: #4f46e5;
            --rd-surface: #ffffff;
            --rd-bg: #f1f5f9;
            --rd-ink: #0f172a;
            --rd-muted: #64748b;
            --rd-border: #e2e8f0;
            --rd-radius: 12px;
            --rd-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            --rd-shadow-hover: 0 8px 24px rgba(0, 0, 0, 0.09);
            --rd-transition: all 0.2s ease;
        }

        * {
            box-sizing: border-box;
        }

        .rd-dashboard {
            background: var(--rd-bg);
            color: var(--rd-ink);
            padding: 24px;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Header – more vibrant gradient, better spacing */
        .rd-header {
            background: linear-gradient(145deg, #1a2e5c, #2563eb 60%, #059669);
            border-radius: var(--rd-radius);
            padding: 28px 32px;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.2);
            color: #fff;
        }

        .rd-header h1 {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0;
        }

        .rd-header p {
            opacity: 0.85;
            font-size: 14px;
            margin: 4px 0 0;
        }

        .rd-header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Buttons – softer, with better hover */
        .rd-btn {
            align-items: center;
            background: #fff;
            border: 1px solid var(--rd-border);
            border-radius: 8px;
            color: var(--rd-ink);
            display: inline-flex;
            font-size: 13px;
            font-weight: 600;
            gap: 8px;
            padding: 8px 16px;
            min-height: 40px;
            transition: var(--rd-transition);
            text-decoration: none;
        }

        .rd-btn:hover {
            background: var(--rd-primary-light);
            border-color: var(--rd-primary-light);
            color: #fff;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
            transform: translateY(-1px);
        }

        .rd-btn-light {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            color: #fff;
        }

        .rd-btn-light:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff;
        }

        /* Filter panel – cleaner, bordered */
        .rd-filter-panel {
            background: var(--rd-surface);
            border-radius: var(--rd-radius);
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: var(--rd-shadow);
            border: 1px solid var(--rd-border);
        }

        .rd-filter-grid {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            align-items: end;
        }

        .rd-filter-grid label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--rd-muted);
            display: block;
            margin-bottom: 4px;
        }

        .rd-filter-grid select,
        .rd-filter-grid input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--rd-border);
            border-radius: 8px;
            background: #fafcff;
            font-size: 13px;
            transition: var(--rd-transition);
        }

        .rd-filter-grid select:focus,
        .rd-filter-grid input:focus {
            border-color: var(--rd-primary-light);
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        /* Section headers */
        .rd-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--rd-border);
        }

        .rd-section-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--rd-ink);
        }

        .rd-small-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--rd-muted);
            letter-spacing: 0.04em;
        }

        /* Cards – elevated, with hover */
        .rd-panel {
            background: var(--rd-surface);
            border-radius: var(--rd-radius);
            margin-bottom: 24px;
            box-shadow: var(--rd-shadow);
            border: 1px solid var(--rd-border);
            transition: var(--rd-transition);
            overflow: hidden;
        }

        .rd-panel:hover {
            box-shadow: var(--rd-shadow-hover);
        }

        .rd-panel-body {
            padding: 20px;
            gap: 15px;
            display: flex;
            flex-direction: column;
        }

        /* KPI Cards – more visual, with icon containers */
        .rd-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .rd-kpi-card {
            background: var(--rd-surface);
            border-radius: var(--rd-radius);
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--rd-shadow);
            border: 1px solid var(--rd-border);
            transition: var(--rd-transition);
        }

        .rd-kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--rd-shadow-hover);
        }

        .rd-kpi-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--rd-muted);
            letter-spacing: 0.04em;
        }

        .rd-kpi-value {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.2;
            margin-top: 4px;
        }

        .rd-kpi-sub {
            font-size: 12px;
            color: var(--rd-muted);
            margin-top: 4px;
        }

        .rd-kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff6ff;
            color: var(--rd-primary-light);
            font-size: 20px;
        }

        .rd-kpi-delta {
            font-size: 12px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .rd-badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .rd-badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Tables – cleaner, with stripes */
        .rd-table-wrap {
            overflow-x: auto;
        }

        .rd-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .rd-table th {
            background: #f8fafc;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 2px solid var(--rd-border);
        }

        .rd-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .rd-table tbody tr:hover {
            background: #f8fafc;
        }

        /* Badges – more refined */
        .rd-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .rd-badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .rd-badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .rd-badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .rd-badge-info {
            background: #cffafe;
            color: #155e75;
        }

        .rd-badge-primary {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .rd-badge-muted {
            background: #f1f5f9;
            color: #475569;
        }

        /* Progress bars – gradient, rounded */
        .rd-bar-track {
            background: #e9edf2;
            border-radius: 20px;
            height: 8px;
            overflow: hidden;
            flex: 1;
        }

        .rd-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--rd-primary-light), var(--rd-secondary));
            border-radius: 20px;
        }

        .rd-progress {
            background: #e9edf2;
            border-radius: 20px;
            height: 6px;
            overflow: hidden;
            flex: 1;
        }

        .rd-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--rd-primary-light), var(--rd-secondary));
        }

        /* Charts rows – better spacing */
        .rd-chart-row {
            display: grid;
            grid-template-columns: 120px 1fr 60px;
            align-items: center;
            gap: 12px;
            padding: 4px 0;
            text-decoration: none;
            color: inherit;
            transition: var(--rd-transition);
        }

        .rd-chart-row:hover {
            color: var(--rd-primary-light);
        }

        /* Pipeline items */
        .rd-pipeline-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            border: 1px solid var(--rd-border);
            border-radius: 8px;
            background: #fafcff;
            transition: var(--rd-transition);
        }

        .rd-pipeline-item:hover {
            background: #f0f4ff;
            border-color: var(--rd-primary-light);
        }

        .rd-pipeline-count {
            font-size: 22px;
            font-weight: 700;
            color: var(--rd-primary-light);
        }

        /* Alerts / exceptions */
        .rd-alert-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            border-left: 4px solid var(--rd-amber);
            background: #fffbeb;
            border-radius: 8px;
            text-decoration: none;
            color: inherit;
            transition: var(--rd-transition);
        }

        .rd-alert-item.danger {
            background: #fef2f2;
            border-left-color: #dc2626;
        }

        .rd-alert-item.info {
            background: #eff6ff;
            border-left-color: var(--rd-primary-light);
        }

        .rd-alert-item:hover {
            transform: translateX(4px);
        }

        /* Action cards */
        .rd-action-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border: 1px solid var(--rd-border);
            border-radius: 8px;
            background: var(--rd-surface);
            transition: var(--rd-transition);
            text-decoration: none;
            color: var(--rd-ink);
        }

        .rd-action-card:hover {
            border-color: var(--rd-primary-light);
            color: var(--rd-primary-light);
            box-shadow: var(--rd-shadow-hover);
        }

        .rd-action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--rd-primary-light);
        }

        /* Mini rows */
        .rd-mini-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .rd-mini-row:last-child {
            border-bottom: 0;
        }

        /* Empty state */
        .rd-empty {
            padding: 24px;
            text-align: center;
            color: var(--rd-muted);
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .rd-filter-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .rd-kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .rd-dashboard {
                padding: 16px;
            }

            .rd-header {
                padding: 20px;
                flex-direction: column;
                align-items: flex-start;
            }

            .rd-header h1 {
                font-size: 22px;
            }

            .rd-header-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .rd-filter-grid {
                grid-template-columns: 1fr 1fr;
            }

            .rd-kpi-grid {
                grid-template-columns: 1fr;
            }

            .rd-grid-2,
            .rd-grid-3 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .rd-filter-grid {
                grid-template-columns: 1fr;
            }

            .rd-chart-row {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    <div class="rd-dashboard">
        <div class="rd-header">
            <div class="rd-header-row">
                <div class="rd-header-left">
                    <div class="rd-header-icon"><i class="fas fa-file-invoice-dollar fa-lg"></i></div>
                    <div>
                        <h1>{{ $pageTitle }}</h1>
                        <p>{{ $pageDescription }}</p>
                    </div>
                </div>
                <div class="rd-header-actions">
                    <a class="rd-btn rd-btn-light" href="{{ route('reimbursement.policies.create') }}"><i
                            class="fas fa-plus"></i> Create Policy</a>
                    <a class="rd-btn rd-btn-light" href="{{ route('assign.reimbursement.policies') }}"><i
                            class="fas fa-user-tag"></i> Assign Policy</a>
                    <a class="rd-btn rd-btn-light" href="{{ route('reimbursement.claims.admin.view') }}"><i
                            class="fas fa-table-list"></i> Claims Register</a>
                </div>
            </div>
        </div>

        <form class="rd-filter-panel rd-auto-filter-form" method="GET" action="{{ route('reimbursement.dashboard') }}">
            <div class="rd-filter-grid">
                <div>
                    <label>Time Filter</label>
                    <select name="range">
                        @foreach($timeFilterOptions as $value => $label)
                            <option value="{{ $value }}" @selected(($dashboard['filters']['range'] ?? 'this_month') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="{{ $dashboard['filters']['start_date'] }}">
                </div>
                <div>
                    <label>End Date</label>
                    <input type="date" name="end_date" value="{{ $dashboard['filters']['end_date'] }}">
                </div>
                <div>
                    <label>Department</label>
                    <select name="department">
                        <option value="all" @selected(($dashboard['filters']['department'] ?? 'all') === 'all')>All
                            Departments</option>
                        @foreach($dashboard['filterOptions']['departments'] as $department)
                            <option value="{{ $department['id'] }}" @selected((string) ($dashboard['filters']['department'] ?? 'all') === (string) $department['id'])>{{ $department['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Policy</label>
                    <select name="policy">
                        <option value="all" @selected(($dashboard['filters']['policy'] ?? 'all') === 'all')>All Policies
                        </option>
                        @foreach($dashboard['filterOptions']['policies'] as $policy)
                            <option value="{{ $policy['id'] }}" @selected((string) ($dashboard['filters']['policy'] ?? 'all') === (string) $policy['id'])>{{ $policy['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Status</label>
                    <select name="status">
                        <option value="all" @selected(($dashboard['filters']['status'] ?? 'all') === 'all')>All Status
                        </option>
                        @foreach($dashboard['filterOptions']['statuses'] as $status)
                            <option value="{{ $status }}" @selected(($dashboard['filters']['status'] ?? 'all') === $status)>
                                {{ $statusLabel($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Approval Stage</label>
                    <select name="approval_stage">
                        @foreach($approvalStageOptions as $value => $label)
                            <option value="{{ $value }}" @selected(($dashboard['filters']['approval_stage'] ?? 'all') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Employee</label>
                    <input type="search" name="employee" value="{{ $dashboard['filters']['employee'] ?? '' }}"
                        placeholder="Search employee">
                </div>
                <div class="rd-action-row" style="align-items:end;justify-content:flex-start;">
                    <button class="rd-btn" type="submit"><i class="fas fa-filter"></i> Apply</button>
                    <a class="rd-btn" href="{{ route('reimbursement.dashboard') }}"><i class="fas fa-rotate-left"></i>
                        Reset</a>
                </div>
            </div>
        </form>

        <div class="rd-section-head" style="border:0;padding:4px 0 12px;">
            <h2 class="rd-section-title"><i class="fas fa-chart-pie"></i> KPI Summary</h2>
            <div class="rd-export-menu">
                <a class="rd-btn" href="{{ route('reimbursement.claims.admin.view') }}"><i class="fas fa-table-list"></i>
                    Claims Report</a>
                <a class="rd-btn" href="{{ route('reimbursement.dashboard', array_merge($query, ['export' => 'csv'])) }}"><i
                        class="fas fa-file-csv"></i> Export CSV</a>
                <a class="rd-btn" href="{{ route('reimbursement.dashboard', array_merge($query, ['export' => 'pdf'])) }}"><i
                        class="fas fa-file-pdf"></i> PDF</a>
            </div>
        </div>

        <div class="rd-kpi-grid">
            @foreach($kpiCards as $card)
                <div class="rd-kpi-card" role="region" aria-label="{{ $card['label'] }}">
                    <div>
                        <div class="rd-kpi-label">{{ $card['label'] }}</div>
                        <div class="rd-kpi-value">{!! is_string($card['value']) ? $card['value'] : $number($card['value']) !!}
                        </div>
                        @if(!empty($card['sub']))
                            <div class="rd-kpi-sub">{!! $card['sub'] !!}</div>
                        @endif
                    </div>
                    <span class="rd-kpi-icon" style="{{ $card['iconStyle'] ?? '' }}"><i class="{{ $card['icon'] }}"></i></span>
                </div>
            @endforeach
        </div>

        <div class="rd-grid-2">
            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-triangle-exclamation"></i> Action Required</h2>
                    <span class="rd-small-label">Operational queue</span>
                </div>
                <div class="rd-panel-body rd-alert-list">
                    @foreach($dashboard['pendingActions'] as $action)
                        <a class="rd-alert-item {{ $action['severity'] }}" href="{{ $action['url'] }}"
                            style="color:inherit;text-decoration:none;">
                            <span><i class="fas {{ $action['icon'] }} mr-2"></i>{{ $action['title'] }}<br><small
                                    class="text-muted">{{ $money($action['amount']) }}</small></span>
                            <span><strong>{{ $number($action['count']) }}</strong> <i
                                    class="fas fa-arrow-right ml-2"></i></span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-route"></i> Approval Pipeline</h2>
                </div>
                <div class="rd-panel-body rd-pipeline">
                    @foreach($dashboard['approvalPipeline'] as $stage)
                        <div class="rd-pipeline-item">
                            <div><strong>{{ $stage['label'] }}</strong>
                                <div class="rd-kpi-sub">{{ $money($stage['amount']) }}</div>
                            </div>
                            <div class="rd-pipeline-count">{{ $number($stage['count']) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="rd-grid-2">
            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-chart-simple"></i> Claims by Status</h2>
                    <span class="rd-small-label">{{ $number($dashboard['kpis']['total_claims']) }} total</span>
                </div>
                <div class="rd-panel-body">
                    @forelse($dashboard['statusCounts'] as $status => $count)
                        <a class="rd-chart-row"
                            href="{{ route('reimbursement.dashboard', array_merge($query, ['status' => $status])) }}"
                            style="color:inherit;text-decoration:none;">
                            <span>{{ $statusLabel($status) }}</span>
                            <div class="rd-bar-track">
                                <div class="rd-bar" style="width: {{ $percent($count, $statusMax) }}%;"></div>
                            </div>
                            <strong>{{ $count }}</strong>
                        </a>
                    @empty
                        <div class="rd-empty">No claims available.</div>
                    @endforelse
                </div>
            </div>

            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-clock"></i> Claim Aging</h2>
                    <span class="rd-small-label">Pending claims</span>
                </div>
                <div class="rd-panel-body rd-mini-list">
                    @foreach($dashboard['agingBuckets'] as $bucket => $count)
                        <div class="rd-mini-row"><span>{{ $bucket }}</span><strong>{{ $number($count) }}</strong></div>
                    @endforeach
                    <div
                        class="rd-alert-item {{ ($dashboard['agingBuckets']['11-30 days'] + $dashboard['agingBuckets']['30+ days']) > 0 ? 'danger' : 'info' }}">
                        <span>Claims pending more than 10 days</span>
                        <strong>{{ $number($dashboard['agingBuckets']['11-30 days'] + $dashboard['agingBuckets']['30+ days']) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="rd-panel">
            <div class="rd-section-head">
                <h2 class="rd-section-title"><i class="fas fa-chart-line"></i> Claim / Approval / Payment Trend</h2>
                <span class="rd-small-label">Claimed, approved and paid</span>
            </div>
            <div class="rd-table-wrap">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Claims</th>
                            <th>Claimed</th>
                            <th>Approved</th>
                            <th>Paid</th>
                            <th>Claimed Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dashboard['monthlyTrend'] as $month)
                            <tr>
                                <td><strong>{{ $month['label'] }}</strong></td>
                                <td>{{ $number($month['count']) }}</td>
                                <td>{{ $money($month['claimed']) }}</td>
                                <td>{{ $money($month['approved']) }}</td>
                                <td>{{ $money($month['paid']) }}</td>
                                <td>
                                    <div class="rd-bar-track">
                                        <div class="rd-bar" style="width: {{ $percent($month['claimed'], $monthMax) }}%;"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rd-grid-2">
            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-building-user"></i> Department-wise Reimbursement</h2>
                </div>
                <div class="rd-table-wrap">
                    <table class="rd-table">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Claims</th>
                                <th>Claimed</th>
                                <th>Approved</th>
                                <th>Paid</th>
                                <th>Approval Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dashboard['departmentAnalysis'] as $department)
                                <tr>
                                    <td><strong>{{ $department['department'] }}</strong><br><small
                                            class="text-muted">{{ $number($department['employees']) }} employees</small></td>
                                    <td>{{ $number($department['claims']) }}</td>
                                    <td>{{ $money($department['claimed']) }}</td>
                                    <td>{{ $money($department['approved']) }}</td>
                                    <td>{{ $money($department['paid']) }}</td>
                                    <td>{{ $department['approval_rate'] }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="rd-empty">No department data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-layer-group"></i> Expense Category Analysis</h2>
                </div>
                <div class="rd-panel-body">
                    @forelse($dashboard['categoryAnalysis'] as $category)
                        <div class="rd-chart-row">
                            <span>{{ $category['category'] }}</span>
                            <div class="rd-bar-track">
                                <div class="rd-bar" style="width: {{ $percent($category['claimed'], $categoryMax) }}%;"></div>
                            </div>
                            <strong>{{ $number($category['claims']) }}</strong>
                        </div>
                        <div class="rd-kpi-sub" style="margin:-6px 0 12px 132px;">{{ $money($category['claimed']) }} claimed,
                            {{ $money($category['approved']) }} approved
                        </div>
                    @empty
                        <div class="rd-empty">No category data available.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rd-panel">
            <div class="rd-section-head">
                <h2 class="rd-section-title"><i class="fas fa-gauge-high"></i> Policy-wise Utilization</h2>
                <a class="rd-btn" href="{{ route('assign.reimbursement.policies') }}">Manage Assignment</a>
            </div>
            <div class="rd-table-wrap">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th>Policy</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Assignments</th>
                            <th>Claims</th>
                            <th>Claimed</th>
                            <th>Approved</th>
                            <th>Limit Use</th>
                            <th>Violations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dashboard['policyUtilization'] as $policy)
                            <tr>
                                <td><strong>{{ $policy['name'] }}</strong><br><small
                                        class="text-muted">{{ $policy['policy_id'] }}</small></td>
                                <td>{{ $policy['category'] }}</td>
                                <td><span
                                        class="rd-badge rd-badge-{{ $statusClass($policy['status']) }}">{{ $statusLabel($policy['status']) }}</span>
                                </td>
                                <td>{{ $number($policy['assignments']) }}</td>
                                <td>{{ $number($policy['claims']) }}</td>
                                <td>{{ $money($policy['claimed']) }}</td>
                                <td>{{ $money($policy['approved']) }}</td>
                                <td>
                                    @if(!is_null($policy['utilization']))
                                        <div class="rd-action-row" style="justify-content:flex-start;">
                                            <div class="rd-progress">
                                                <div class="rd-progress-fill" style="width: {{ $policy['utilization'] }}%;"></div>
                                            </div><small>{{ $policy['utilization'] }}%</small>
                                        </div>
                                        <small class="text-muted">{{ $money($policy['approved']) }} /
                                            {{ $money($policy['limit_amount']) }}</small>
                                    @else
                                        <span class="text-muted">No limit</span>
                                    @endif
                                </td>
                                <td>{{ $number($policy['violations']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="rd-empty">No reimbursement policies found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rd-grid-2">
            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-scale-unbalanced"></i> Policy Limit Violations</h2>
                </div>
                <div class="rd-table-wrap">
                    <table class="rd-table">
                        <thead>
                            <tr>
                                <th>Request</th>
                                <th>Employee</th>
                                <th>Policy</th>
                                <th>Claimed</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dashboard['limitViolations'] as $claim)
                                <tr>
                                    <td><strong>{{ $claim->reimbursement_request_id }}</strong></td>
                                    <td>{{ $claim->name ?: $claim->employee_id }}</td>
                                    <td>{{ $claim->policy_name }}</td>
                                    <td>{{ $money($claim->claim_amount) }}</td>
                                    <td><span class="rd-badge rd-badge-danger">Exceeded</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="rd-empty">No policy limit violations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-users"></i> Top Reimbursement Employees</h2>
                </div>
                <div class="rd-table-wrap">
                    <table class="rd-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Claims</th>
                                <th>Claimed</th>
                                <th>Approved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dashboard['topEmployees'] as $employee)
                                <tr>
                                    <td><strong>{{ $employee['name'] }}</strong><br><small
                                            class="text-muted">{{ $employee['department'] }}</small></td>
                                    <td>{{ $number($employee['claims']) }}</td>
                                    <td>{{ $money($employee['claimed']) }}</td>
                                    <td>{{ $money($employee['approved']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="rd-empty">No employee reimbursement data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="rd-grid-3">
            <div class="rd-panel d-none">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-ban"></i> Rejection Analysis</h2>
                </div>
                <div class="rd-panel-body rd-mini-list">
                    @forelse($dashboard['rejectionReasons'] as $reason)
                        <div class="rd-mini-row">
                            <span>{{ $reason['reason'] }}</span><strong>{{ $number($reason['count']) }}</strong>
                        </div>
                    @empty
                        <div class="rd-empty">No rejection reasons found.</div>
                    @endforelse
                </div>
            </div>

            <div class="rd-panel d-none">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-stopwatch"></i> Average Approval Time</h2>
                </div>
                <div class="rd-panel-body rd-mini-list">
                    <div class="rd-mini-row"><span>Step 1</span><strong>{{ $dashboard['approvalTime']['step1'] }}
                            days</strong></div>
                    <div class="rd-mini-row"><span>Step 2</span><strong>{{ $dashboard['approvalTime']['step2'] }}
                            days</strong></div>
                    <div class="rd-mini-row"><span>Total</span><strong>{{ $dashboard['approvalTime']['total'] }}
                            days</strong></div>
                    <div class="rd-mini-row"><span>Fastest
                            department</span><strong>{{ $dashboard['approvalTime']['fastest_department'] }}</strong></div>
                    <div class="rd-mini-row"><span>Slowest
                            department</span><strong>{{ $dashboard['approvalTime']['slowest_department'] }}</strong></div>
                </div>
            </div>

            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-id-card"></i> Employee Policy Coverage</h2>
                </div>
                <div class="rd-panel-body rd-mini-list">
                    <div class="rd-mini-row"><span>Total
                            Employees</span><strong>{{ $number($dashboard['employeeCoverage']['total']) }}</strong></div>
                    <div class="rd-mini-row"><span>Reimbursement
                            Eligible</span><strong>{{ $number($dashboard['employeeCoverage']['eligible']) }}</strong></div>
                    <div class="rd-mini-row"><span>Policy
                            Assigned</span><strong>{{ $number($dashboard['employeeCoverage']['assigned']) }}</strong></div>
                    <div class="rd-alert-item {{ $dashboard['employeeCoverage']['unassigned'] > 0 ? 'warning' : 'info' }}">
                        <span>No Policy
                            Assigned</span><strong>{{ $number($dashboard['employeeCoverage']['unassigned']) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="rd-panel">
            <div class="rd-section-head">
                <h2 class="rd-section-title"><i class="fas fa-money-bill-transfer"></i> Settlement / Payout Tracking</h2>
            </div>
            <div class="rd-panel-body">
                <div class="rd-kpi-grid" style="margin-bottom:14px;">
                    @foreach($dashboard['settlements']['summary'] as $label => $item)
                        <div class="rd-kpi-card">
                            <div>
                                <div class="rd-kpi-label">{{ ucwords($label) }}</div>
                                <div class="rd-kpi-value">{{ $number($item['count']) }}</div>
                                <div class="rd-kpi-sub">{{ $money($item['amount']) }}</div>
                            </div>
                            <span class="rd-kpi-icon"><i class="fas fa-money-check-alt"></i></span>
                        </div>
                    @endforeach
                </div>
                <div class="rd-table-wrap">
                    <table class="rd-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Amount</th>
                                <th>Mode</th>
                                <th>Payout Date</th>
                                <th>Reference</th>
                                <th>Status</th>
                                <th>Remarks / Failed Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dashboard['settlements']['payouts'] as $payout)
                                <tr>
                                    <td><strong>{{ $payout->employee_name ?: $payout->employee_id }}</strong></td>
                                    <td>{{ $money($payout->payout_amount) }}</td>
                                    <td>{{ $payout->settlement_mode ?: '-' }}</td>
                                    <td>{{ $date($payout->payout_date) }}</td>
                                    <td>{{ $payout->payout_reference ?: $payout->payout_id }}</td>
                                    <td><span
                                            class="rd-badge rd-badge-{{ $statusClass($payout->status) }}">{{ $statusLabel($payout->status) }}</span>
                                    </td>
                                    <td>{{ $payout->processor_remarks ?: ($payout->deduction_reason ?: '-') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="rd-empty">No payout records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="rd-panel">
            <div class="rd-section-head">
                <h2 class="rd-section-title"><i class="fas fa-clock"></i> Recent Claims</h2>
                <a class="rd-btn" href="{{ route('reimbursement.claims.admin.view') }}">View All</a>
            </div>
            <div class="rd-table-wrap">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th>Request</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Policy</th>
                            <th>Claimed</th>
                            <th>Approved</th>
                            <th>Approval Stage</th>
                            <th>Settlement</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dashboard['recentClaims'] as $claim)
                            <tr>
                                <td><strong>{{ $claim->reimbursement_request_id ?: $claim->master_request_id }}</strong></td>
                                <td>{{ $claim->name ?: $claim->employee_id }}</td>
                                <td>{{ $claim->department_name ?: ($claim->department ?: '-') }}</td>
                                <td>{{ $claim->policy_name ?: '-' }}</td>
                                <td>{{ $money($claim->claim_amount) }}</td>
                                <td>{{ $money($claim->approved_amount) }}</td>
                                <td>{{ $stageFor($claim) }}</td>
                                <td>{{ $claim->settlement_mode ?: ($claim->settlement_reference ? 'Reference set' : '-') }}</td>
                                <td>{{ $date($claim->submission_date ?: $claim->created_at) }}</td>
                                <td><span
                                        class="rd-badge rd-badge-{{ $statusClass($claim->status) }}">{{ $statusLabel($claim->status) }}</span>
                                </td>
                                <td><a class="rd-btn" href="{{ route('reimbursement.claims.admin.view') }}">View</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="rd-empty">No recent claims found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rd-grid-2">
            <div class="rd-panel">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-heart-pulse"></i> Policy Health</h2>
                </div>
                <div class="rd-panel-body rd-mini-list">
                    <div class="rd-mini-row"><span>Active
                            Policies</span><strong>{{ $number($dashboard['policyHealth']['active']) }}</strong></div>
                    <div class="rd-mini-row"><span>Inactive
                            Policies</span><strong>{{ $number($dashboard['policyHealth']['inactive']) }}</strong></div>
                    <div class="rd-mini-row"><span>Expiring in 30
                            Days</span><strong>{{ $number($dashboard['policyHealth']['expiring']) }}</strong></div>
                    <div class="rd-mini-row"><span>No
                            Assignments</span><strong>{{ $number($dashboard['policyHealth']['no_assignments']) }}</strong>
                    </div>
                    <div class="rd-mini-row">
                        <span>Over-utilized</span><strong>{{ $number($dashboard['policyHealth']['over_utilized']) }}</strong>
                    </div>
                </div>
            </div>

            <div class="rd-panel d-none">
                <div class="rd-section-head">
                    <h2 class="rd-section-title"><i class="fas fa-triangle-exclamation"></i> Exceptions / Alerts</h2>
                </div>
                <div class="rd-panel-body rd-alert-list">
                    @forelse($dashboard['exceptions'] as $alert)
                        <div class="rd-alert-item {{ $alert['type'] }}">
                            <span>{{ $alert['title'] }}</span><strong>{{ $number($alert['count']) }}</strong>
                        </div>
                    @empty
                        <div class="rd-empty">No dashboard exceptions at this time.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rd-panel d-none">
            <div class="rd-section-head">
                <h2 class="rd-section-title"><i class="fas fa-bolt"></i> Quick Actions</h2>
                <span class="rd-small-label">Updated {{ $dashboard['generatedAt']->format('d M Y, h:i A') }}</span>
            </div>
            <div class="rd-panel-body">
                <div class="rd-action-row" style="justify-content:flex-start;flex-wrap:wrap;">
                    @php
                        $defaultQuickActions = [
                            ['label' => 'Create Policy', 'url' => route('reimbursement.policies.create'), 'icon' => 'fas fa-plus'],
                            ['label' => 'Assign Policy', 'url' => route('assign.reimbursement.policies'), 'icon' => 'fas fa-user-tag'],
                            ['label' => 'Claims Register', 'url' => route('reimbursement.claims.admin.view'), 'icon' => 'fas fa-table-list'],
                            ['label' => 'Approval Queue', 'url' => url('/institute-admin/reimbursement-claims/approval-list'), 'icon' => 'fas fa-check-double'],
                            ['label' => 'Settlement Queue', 'url' => route('reimbursement.claims.admin.view'), 'icon' => 'fas fa-wallet'],
                            ['label' => 'Failed Payouts', 'url' => route('reimbursement.dashboard', array_merge($query, ['status' => 'failed'])), 'icon' => 'fas fa-money-bill-wave'],
                            ['label' => 'Export', 'url' => route('reimbursement.dashboard', array_merge($query, ['export' => 'csv'])), 'icon' => 'fas fa-file-export'],
                        ];
                        $quickActions = $dashboard['quickActions'] ?? $defaultQuickActions;
                    @endphp
                    @foreach($quickActions as $action)
                        <a class="rd-btn" href="{{ $action['url'] }}"><i class="{{ $action['icon'] }}"></i>
                            {{ $action['label'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tooltips for KPI icons (if you add title attributes)
            const tooltips = document.querySelectorAll('[data-tooltip]');
            tooltips.forEach(el => {
                el.addEventListener('mouseenter', function (e) {
                    const tip = document.createElement('div');
                    tip.className = 'rd-tooltip';
                    tip.textContent = this.dataset.tooltip;
                    tip.style.cssText = `
                                    position: absolute; background: #0f172a; color: #fff;
                                    padding: 4px 10px; border-radius: 6px; font-size: 12px;
                                    white-space: nowrap; z-index: 1000;
                                  `;
                    document.body.appendChild(tip);
                    const rect = this.getBoundingClientRect();
                    tip.style.left = rect.left + rect.width / 2 - tip.offsetWidth / 2 + 'px';
                    tip.style.top = rect.bottom + 6 + 'px';
                    this._tip = tip;
                });
                el.addEventListener('mouseleave', function () {
                    if (this._tip) { this._tip.remove(); this._tip = null; }
                });
            });

            const autoFilterForm = document.querySelector('.rd-auto-filter-form');
            if (autoFilterForm) {
                const filterFields = autoFilterForm.querySelectorAll('select[name], input[name]');
                const debounce = (fn, delay) => {
                    let timer;
                    return (...args) => {
                        clearTimeout(timer);
                        timer = setTimeout(() => fn(...args), delay);
                    };
                };

                filterFields.forEach(field => {
                    if (field.tagName === 'SELECT') {
                        field.addEventListener('change', () => autoFilterForm.submit());
                    }
                    if (field.tagName === 'INPUT') {
                        if (field.type === 'search') {
                            field.addEventListener('keydown', function (event) {
                                if (event.key === 'Enter') {
                                    event.preventDefault();
                                    autoFilterForm.submit();
                                }
                            });
                            field.addEventListener('input', debounce(() => autoFilterForm.submit(), 400));
                        } else {
                            field.addEventListener('change', () => autoFilterForm.submit());
                        }
                    }
                });
            }

            // Auto-refresh every 5 minutes (optional)
            // setTimeout(() => location.reload(), 300000);
        });
    </script>


@endsection