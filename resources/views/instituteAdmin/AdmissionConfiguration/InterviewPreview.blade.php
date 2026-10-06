@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@section('content')
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #0f172a;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border: #e9eef4;
            --border-dark: #cbd5e1;
            --bg-subtle: #f8fafc;
            --bg-card: #ffffff;
            --success: #10b981;
            --success-light: #d1fae5;
            --success-bg: #f0fdf4;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --warning-bg: #fffbeb;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --radius-sm: 10px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 20px 40px -12px rgba(0, 0, 0, 0.08);
        }


        .container {
            max-width: 1440px;
            margin: 0 auto;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding: 0 0 20px 0;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            color: var(--secondary);
            margin: 0;
            font-size: 1.85rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .page-title i {
            background: linear-gradient(135deg, var(--primary), #818cf8);
            color: white;
            padding: 12px 14px;
            border-radius: 16px;
            font-size: 1.3rem;
            box-shadow: 0 8px 20px -6px rgba(79, 70, 229, 0.35);
        }

        /* ── BUTTONS ── */
        .btn {
            padding: 12px 28px;
            border-radius: 40px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.25s ease;
            border: none;
            text-decoration: none;
            line-height: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: white;
            box-shadow: 0 8px 16px -6px rgba(79, 70, 229, 0.4);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(79, 70, 229, 0.5);
        }

        /* ── LEGEND ── */
        .legend-bar {
            background: var(--success-bg);
            border-left: 6px solid var(--success);
            border-radius: var(--radius-lg);
            padding: 18px 24px;
            margin-bottom: 28px;
            display: flex;
            gap: 28px;
            flex-wrap: wrap;
            align-items: center;
            box-shadow: var(--shadow-sm);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .legend-badge {
            padding: 8px 18px;
            border-radius: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .legend-badge.published {
            background: var(--success-light);
            color: var(--success);
        }

        .legend-badge.draft {
            background: var(--warning-light);
            color: var(--warning);
        }

        .legend-text {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 450;
        }

        /* ── CONFIG TABLE CARD ── */
        .config-table {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow-x: auto;
            overflow-y: hidden;
            border: 1px solid var(--border);
        }

        .config-table table {
            width: max-content;
            min-width: 100%;
            border-collapse: collapse;
        }

        .config-table th {
            padding: 20px 24px;
            text-align: left;
            font-weight: 700;
            color: var(--secondary);
            border-bottom: 2px solid var(--border);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            background: var(--bg-subtle);
            white-space: nowrap;
        }

        .config-table td {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            font-size: 0.925rem;
            color: var(--text-primary);
        }

        .config-table tr:last-child td {
            border-bottom: none;
        }

        .config-table tr:hover {
            background: #fafcff;
        }

        .config-table tr {
            transition: background 0.15s ease;
        }

        .config-id {
            font-weight: 700;
            color: var(--primary);
            font-size: 0.95rem;
            letter-spacing: -0.01em;
        }

        .session-text {
            font-weight: 600;
            color: var(--secondary);
        }

        .dept-text {
            font-weight: 500;
            color: var(--text-primary);
        }

        .mode-text {
            font-weight: 500;
            color: var(--text-primary);
            text-transform: capitalize;
        }

        /* ── STEP PREVIEW ── */
        .step-preview {
            display: flex;
            align-items: center;
            gap: 0;
            min-width: 520px;
            background: var(--bg-subtle);
            border-radius: var(--radius-lg);
            padding: 12px 8px;
            border: 1px solid var(--border);
        }

        .step-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 4px 8px;
            position: relative;
            min-width: 0;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 32px;
            right: -6px;
            width: 14px;
            height: 2.5px;
            background: var(--border-dark);
            z-index: 0;
            border-radius: 4px;
            transition: background 0.2s ease;
        }

        .step-item.completed:not(:last-child)::after {
            background: var(--success);
        }

        .step-item.inactive:not(:last-child)::after {
            background: var(--border);
        }

        .step-circle {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: white;
            position: relative;
            z-index: 1;
            flex-shrink: 0;
            transition: all 0.25s ease;
            border: none;
        }

        .step-circle.completed {
            background: linear-gradient(135deg, var(--success), #34d399);
            box-shadow: 0 6px 14px -4px rgba(16, 185, 129, 0.4);
        }

        .step-circle.inactive {
            background: #e9eef4;
            color: #94a3b8;
            box-shadow: none;
        }

        .step-circle i {
            font-size: 0.95rem;
        }

        .step-circle .step-number {
            font-size: 0.85rem;
            font-weight: 700;
            color: #94a3b8;
        }

        .step-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-top: 8px;
            text-align: center;
            line-height: 1.2;
            word-break: break-word;
        }

        .step-item.completed .step-label {
            color: var(--success);
        }

        .step-item.inactive .step-label {
            color: var(--text-muted);
        }

        .step-status-text {
            font-size: 0.55rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-top: 3px;
        }

        .step-item.completed .step-status-text {
            color: var(--success);
        }

        .step-item.inactive .step-status-text {
            color: var(--text-muted);
        }

        .step-item:hover .step-circle {
            transform: scale(1.08);
        }

        /* ── STATUS BADGES ── */
        .status-badge {
            padding: 8px 18px;
            border-radius: 40px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .badge-published {
            background: var(--success-light);
            color: var(--success);
            border: 1.5px solid var(--success);
        }

        .badge-draft {
            background: var(--warning-light);
            color: var(--warning);
            border: 1.5px solid var(--warning);
        }

        .badge-published:hover,
        .badge-draft:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        /* ── ACTION BUTTONS ── */
        .action-btns {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            padding: 8px 18px;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            line-height: 1;
        }

        .btn-view {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 10px -4px rgba(79, 70, 229, 0.4);
        }

        .btn-view:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 8px 16px -6px rgba(79, 70, 229, 0.5);
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 64px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 56px;
            margin-bottom: 20px;
            color: #cbd5e1;
            display: block;
        }

        .empty-state p {
            font-size: 1rem;
            font-weight: 500;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            body {
                padding: 20px;
            }

            .step-preview {
                min-width: 440px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 16px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-title {
                font-size: 1.4rem;
            }

            .legend-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .config-table th,
            .config-table td {
                padding: 14px 16px;
            }

            .step-preview {
                min-width: unset;
                flex-wrap: wrap;
                justify-content: center;
                padding: 16px 8px;
                gap: 8px;
            }

            .step-item:not(:last-child)::after {
                display: none;
            }

            .action-btns {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.2rem;
            }

            .btn {
                padding: 10px 20px;
                font-size: 0.85rem;
            }

            .status-badge {
                padding: 6px 14px;
                font-size: 0.7rem;
            }

            .action-btn {
                padding: 6px 14px;
                font-size: 0.75rem;
            }
        }
    </style>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="page-title">
                <i class="fas fa-sliders-h"></i>
                Interview Configurations
            </h1>
            <a href="{{ route('configuration.interview') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                New Configuration
            </a>
        </div>

        <!-- Status Legend -->
        <div class="legend-bar">
            <div class="legend-item">
                <div class="legend-badge published">
                    <i class="fas fa-check-circle" style="font-size: 1.1rem;"></i>
                    <span>Published</span>
                </div>
                <span class="legend-text">Configuration is active and live</span>
            </div>
            <div class="legend-item">
                <div class="legend-badge draft">
                    <i class="fas fa-edit" style="font-size: 1.1rem;"></i>
                    <span>Draft</span>
                </div>
                <span class="legend-text">Work in progress — not yet published</span>
            </div>
        </div>

        <!-- Config Table -->
        <div class="config-table">
            <table>
                <thead>
                    <tr>
                        <th>Config ID</th>
                        <th>Session</th>
                        <th>Department</th>
                        <th>Interview Mode</th>
                        <th>Steps</th>
                        <th>Status</th>
                        <th>Add On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($configurations as $configuration)
                        @php
                            $steps = [
                                ['label' => 'Application', 'enabled' => (bool) $configuration->step1_enabled],
                                ['label' => 'Interview', 'enabled' => (bool) $configuration->step2_enabled],
                                ['label' => 'Selection', 'enabled' => (bool) $configuration->step3_enabled],
                                ['label' => 'Onboarding', 'enabled' => (bool) $configuration->step4_enabled],
                            ];
                            $enabledSteps = collect($steps)->where('enabled', true)->count();
                        @endphp
                        <tr>
                            <td>
                                <span class="config-id">
                                    {{ $configuration->interview_config_id ?: '#' . $configuration->id }}
                                </span>
                            </td>
                            <td>
                                <span class="session-text">{{ $configuration->academic_year ?: 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="dept-text">{{ optional($configuration->department)->department ?: 'N/A' }}</span>
                            </td>
                            <td>
                                <span
                                    class="mode-text">{{ $configuration->interview_mode ? ucfirst($configuration->interview_mode) : 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="step-preview">
                                    @foreach ($steps as $step)
                                        @php $stepClass = $step['enabled'] ? 'completed' : 'inactive'; @endphp
                                        <div class="step-item {{ $stepClass }}"
                                            title="{{ $step['label'] }}: {{ $step['enabled'] ? 'Enabled' : 'Inactive' }}">
                                            <div class="step-circle {{ $stepClass }}">
                                                @if ($step['enabled'])
                                                    <i class="fas fa-check"></i>
                                                @else
                                                    <span class="step-number">–</span>
                                                @endif
                                            </div>
                                            <span class="step-label">{{ $step['label'] }}</span>
                                            <span class="step-status-text">{{ $step['enabled'] ? 'Enabled' : 'Inactive' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <span
                                    class="status-badge {{ $enabledSteps === count($steps) ? 'badge-published' : 'badge-draft' }}">
                                    <i class="fas {{ $enabledSteps === count($steps) ? 'fa-star' : 'fa-pen-to-square' }}"></i>
                                    {{ $enabledSteps === count($steps) ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 500; color: var(--text-secondary);">
                                    {{ optional($configuration->created_at)->format('Y-m-d') ?: 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('interview.preview.details', $configuration->id) }}"
                                        class="action-btn btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-sliders-h"></i>
                                    <p>No interview configurations found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function deleteConfig(id) {
            if (confirm('Are you sure you want to delete this configuration?')) {
                alert('Delete functionality would be implemented here for config: ' + id);
            }
        }
    </script>
@endsection