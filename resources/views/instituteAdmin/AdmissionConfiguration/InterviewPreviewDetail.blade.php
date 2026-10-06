@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

@section('content')
    @php
        $applicationDocuments = is_array($configuration->application_documents)
            ? $configuration->application_documents
            : (json_decode($configuration->application_documents ?: '[]', true) ?: []);
        $skills = is_array($configuration->skills)
            ? $configuration->skills
            : (json_decode($configuration->skills ?: '[]', true) ?: []);
        $selectedRounds = is_array($configuration->selected_rounds)
            ? $configuration->selected_rounds
            : (json_decode($configuration->selected_rounds ?: '[]', true) ?: []);
        $roundWeightages = is_array($configuration->round_weightages)
            ? $configuration->round_weightages
            : (json_decode($configuration->round_weightages ?: '[]', true) ?: []);
        $onboardingDocuments = is_array($configuration->onboarding_documents)
            ? $configuration->onboarding_documents
            : (json_decode($configuration->onboarding_documents ?: '[]', true) ?: []);
        $formatList = function ($items) {
            return collect($items)->map(function ($item) {
                if (is_array($item)) {
                    return $item['name'] ?? $item['label'] ?? json_encode($item);
                }
                return (string) $item;
            })->implode(', ');
        };
        $steps = [
            ['label' => 'Application', 'enabled' => (bool) $configuration->step1_enabled],
            ['label' => 'Interview', 'enabled' => (bool) $configuration->step2_enabled],
            ['label' => 'Selection', 'enabled' => (bool) $configuration->step3_enabled],
            ['label' => 'Onboarding', 'enabled' => (bool) $configuration->step4_enabled],
        ];
    @endphp

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --primary-soft: #f5f3ff;
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
            --danger: #ef4444;
            --radius-sm: 10px;
            --radius-md: 16px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 20px 40px -12px rgba(0, 0, 0, 0.08);
        }

        .interview-detail {
            max-width: 1360px;
            margin: 0 auto;
        }

        /* ── BACK HEADER ── */
        .back-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .back-header .left-group {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .back-btn {
            padding: 12px 24px;
            border-radius: 40px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
            border: 1px solid var(--border);
            background: white;
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .back-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -6px rgba(79, 70, 229, 0.4);
        }

        .edit-btn {
            padding: 12px 24px;
            border-radius: 40px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: #78350f;
            box-shadow: 0 6px 14px -4px rgba(251, 191, 36, 0.4);
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -6px rgba(251, 191, 36, 0.5);
        }

        .page-title {
            margin: 0;
            color: var(--secondary);
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        /* ── MAIN CARD ── */
        .main-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        /* ── CONFIG HEADER (gradient) ── */
        .config-header {
            padding: 40px 44px;
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .config-header::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -5%;
            width: 380px;
            height: 380px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .config-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            pointer-events: none;
        }

        .config-header h1 {
            margin: 0 0 10px;
            font-size: 2.25rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            position: relative;
            z-index: 1;
        }

        .config-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 1rem;
            font-weight: 450;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .config-header p i {
            font-size: 0.9rem;
        }

        /* ── CONTENT ── */
        .content {
            padding: 36px 40px 40px;
        }

        .section {
            margin-bottom: 36px;
        }

        .section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            margin: 0 0 20px;
            padding-bottom: 14px;
            border-bottom: 2px solid var(--bg-subtle);
            color: var(--secondary);
            font-size: 1.15rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.01em;
        }

        .section-title i {
            color: var(--primary);
            background: var(--primary-light);
            padding: 10px;
            border-radius: 12px;
            font-size: 1rem;
        }

        /* ── DETAIL SECTIONS (TABS) ── */
        .detail-section {
            display: none;
            animation: fadeUp 0.3s ease;
        }

        .detail-section.active {
            display: block;
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(8px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── INFO GRID ── */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-box {
            padding: 20px 22px;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            background: var(--bg-subtle);
            transition: all 0.2s ease;
        }

        .info-box:hover {
            border-color: var(--primary);
            box-shadow: 0 6px 16px -6px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }

        .info-label {
            margin-bottom: 8px;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-label i {
            font-size: 0.8rem;
            color: var(--primary);
        }

        .info-value {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.5;
            word-break: break-word;
        }

        /* ── STEP TABS (TIMELINE) ── */
        .step-list {
            display: flex;
            justify-content: space-between;
            gap: 0;
            border-bottom: 2px solid var(--border);
            overflow-x: auto;
            background: var(--bg-subtle);
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            padding: 0 6px;
        }

        .step {
            position: relative;
            min-width: 150px;
            padding: 18px 20px 16px;
            border: 0;
            border-bottom: 3px solid transparent;
            background: transparent;
            color: var(--text-secondary);
            text-align: center;
            cursor: pointer;
            font: inherit;
            transition: all 0.25s ease;
            border-radius: 12px 12px 0 0;
            margin: 0 3px;
            font-weight: 500;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .step:hover {
            background: rgba(79, 70, 229, 0.05);
            color: var(--primary);
        }

        .step.active {
            border-bottom-color: var(--primary);
            background: white;
            color: var(--primary);
            font-weight: 700;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.02);
        }

        .step.enabled {
            color: var(--success);
        }

        .step.enabled.active {
            color: var(--primary);
        }

        .step.disabled {
            color: var(--text-muted);
            opacity: 0.6;
        }

        .step.disabled.active {
            color: var(--primary);
            opacity: 1;
        }

        .step i {
            font-size: 1.4rem;
            margin-bottom: 2px;
        }

        .step strong {
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .step small {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.8;
        }

        .step.enabled small {
            color: var(--success);
        }

        .step.disabled small {
            color: var(--text-muted);
        }

        .step.active small {
            color: var(--primary);
            opacity: 0.9;
        }

        /* ── TABLES ── */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .detail-table th,
        .detail-table td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            font-size: 0.9rem;
        }

        .detail-table th {
            color: var(--secondary);
            background: var(--bg-subtle);
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .detail-table tr:last-child td {
            border-bottom: 0;
        }

        .detail-table tbody tr {
            transition: background 0.15s ease;
        }

        .detail-table tbody tr:hover {
            background: rgba(79, 70, 229, 0.03);
        }

        /* ── EMPTY STATE ── */
        .empty {
            padding: 32px 24px;
            border: 2px dashed var(--border);
            border-radius: var(--radius-lg);
            color: var(--text-muted);
            text-align: center;
            font-size: 0.95rem;
            background: var(--bg-subtle);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
        }

        /* ── TAGS ── */
        .tag {
            display: inline-block;
            margin: 4px 6px 4px 0;
            padding: 6px 16px;
            border-radius: 40px;
            background: var(--primary-light);
            color: var(--primary-dark);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        /* ── SUB HEADING ── */
        .sub-heading {
            margin: 28px 0 16px;
            color: var(--secondary);
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sub-heading i {
            color: var(--primary);
            background: var(--primary-light);
            padding: 6px;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            body {
                padding: 20px;
            }

            .content {
                padding: 28px 24px 32px;
            }

            .config-header {
                padding: 32px 28px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 16px;
            }

            .back-header {
                flex-direction: column;
                align-items: stretch;
            }

            .back-header .left-group {
                flex-wrap: wrap;
                gap: 12px;
            }

            .page-title {
                order: -1;
                font-size: 1.35rem;
                width: 100%;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 20px 16px 28px;
            }

            .config-header {
                padding: 28px 20px;
            }

            .config-header h1 {
                font-size: 1.6rem;
            }

            .step {
                min-width: 110px;
                padding: 14px 10px;
            }

            .step strong {
                font-size: 0.75rem;
            }

            .step i {
                font-size: 1.2rem;
            }

            .detail-table th,
            .detail-table td {
                padding: 12px 14px;
                font-size: 0.82rem;
            }
        }

        @media (max-width: 480px) {

            .back-btn,
            .edit-btn {
                padding: 10px 18px;
                font-size: 0.8rem;
            }

            .step {
                min-width: 85px;
                padding: 12px 6px;
            }

            .step strong {
                font-size: 0.68rem;
            }

            .step small {
                font-size: 0.6rem;
            }

            .step i {
                font-size: 1rem;
            }

            .info-box {
                padding: 16px 18px;
            }

            .info-value {
                font-size: 0.9rem;
            }
        }
    </style>

    <div class="interview-detail">
        <!-- Header with back & edit -->
        <div class="back-header">
            <div class="left-group">
                <a href="{{ route('interview.preview') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <h1 class="page-title">Interview Configuration</h1>
            </div>
            <!-- <a href="{{ route('configuration.interview') }}" class="edit-btn">
                                <i class="fas fa-edit"></i> Edit Configuration
                            </a> -->
        </div>

        <!-- Main card -->
        <div class="main-card">
            <!-- Header with ID & dept -->
            <div class="config-header">
                <h1>{{ $configuration->interview_config_id }}</h1>
                <p>
                    <i class="fas fa-building"></i>
                    {{ optional($configuration->department)->department ?: 'Department not specified' }}
                    <span style="opacity:0.6;margin:0 8px;">•</span>
                    <i class="fas fa-calendar-alt"></i>
                    {{ $configuration->academic_year ?: 'Academic year not specified' }}
                </p>
            </div>

            <div class="content">
                <!-- Basic Info -->
                <section class="section">
                    <h2 class="section-title"><i class="fas fa-info-circle"></i> Basic Information</h2>
                    <div class="info-grid">
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-hashtag"></i> Configuration ID</div>
                            <div class="info-value">{{ $configuration->interview_config_id }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-building"></i> Department</div>
                            <div class="info-value">{{ optional($configuration->department)->department ?: 'N/A' }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-calendar"></i> Academic Year</div>
                            <div class="info-value">{{ $configuration->academic_year ?: 'N/A' }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-video"></i> Interview Mode</div>
                            <div class="info-value">
                                {{ $configuration->interview_mode ? ucfirst($configuration->interview_mode) : 'N/A' }}
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Steps / Tabs -->
                <section class="section">
                    <h2 class="section-title"><i class="fas fa-layer-group"></i> Admission Process Steps</h2>
                    <div class="step-list">
                        @foreach ($steps as $step)
                            <button type="button"
                                class="step {{ $step['enabled'] ? 'enabled' : 'disabled' }} {{ $loop->first ? 'active' : '' }}"
                                data-target="{{ strtolower($step['label']) }}-details">
                                <i class="fas {{ $step['enabled'] ? 'fa-check-circle' : 'fa-circle-minus' }}"></i>
                                <strong>{{ $step['label'] }}</strong>
                                <small>{{ $step['enabled'] ? 'Enabled' : 'Inactive' }}</small>
                            </button>
                        @endforeach
                    </div>
                </section>

                <!-- ====== DETAIL SECTIONS (tabs) ====== -->

                <!-- 1. Application -->
                <section class="section detail-section active" id="application-details">
                    <h2 class="section-title"><i class="fas fa-file-alt"></i> Application Configuration</h2>
                    <div class="info-grid">
                        <div class="info-box">
                            <div class="info-label">Form Title</div>
                            <div class="info-value">{{ $configuration->form_title ?: 'N/A' }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Maximum Applications</div>
                            <div class="info-value">{{ number_format($configuration->max_applications ?? 0) }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Application Dates</div>
                            <div class="info-value">
                                {{ $configuration->application_start_date ? \Carbon\Carbon::parse($configuration->application_start_date)->format('d M Y') : 'N/A' }}
                                –
                                {{ $configuration->application_end_date ? \Carbon\Carbon::parse($configuration->application_end_date)->format('d M Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Documents</div>
                            <div class="info-value">
                                {{ $applicationDocuments ? $formatList($applicationDocuments) : 'None' }}
                            </div>
                        </div>
                    </div>
                    @if ($skills)
                        <div class="info-box" style="margin-top:20px;">
                            <div class="info-label"><i class="fas fa-code"></i> Skills</div>
                            <div class="info-value">
                                @foreach ($skills as $skill)
                                    <span class="tag">{{ is_array($skill) ? ($skill['name'] ?? '') : $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </section>

                <!-- 2. Interview -->
                <section class="section detail-section" id="interview-details">
                    <h2 class="section-title"><i class="fas fa-comments"></i> Interview Configuration</h2>
                    <div class="info-grid">
                        <div class="info-box">
                            <div class="info-label">Dress Code</div>
                            <div class="info-value">
                                {{ $configuration->custom_dress_code ?: ($configuration->dress_code ?: 'N/A') }}
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Venue</div>
                            <div class="info-value">
                                {{ trim(($configuration->venue_building ?: '') . ' ' . ($configuration->venue_room ?: '')) ?: 'N/A' }}
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Online Meeting Link</div>
                            <div class="info-value">
                                <button class="btn btn-primary btn-sm"
                                    onclick="window.open('{{ $configuration->online_meet_link ?: '#' }}', '_blank')">
                                    <i class="fas fa-external-link-alt"></i> Join Online Meeting
                                </button>
                            </div>
                        </div>

                        <div class="info-box">
                            <div class="info-label"><i class="fas fa-map-pin"></i> Address</div>
                            <div class="info-value">{{ $configuration->venue_address ?: 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="sub-heading"><i class="fas fa-list-ol"></i> Interview Rounds</div>
                    @if ($configuration->interviewRounds->count())
                        <div style="overflow-x:auto;">
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th>Round</th>
                                        <th>Type</th>
                                        <th>Panel</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($configuration->interviewRounds as $round)
                                        <tr>
                                            <td><strong>{{ $round->name }}</strong></td>
                                            <td>{{ $round->type_label ?: ucfirst($round->type) }}</td>
                                            <td>{{ $round->panel ?: 'N/A' }}</td>
                                            <td>{{ $round->round_date ? \Carbon\Carbon::parse($round->round_date)->format('d M Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $round->start_time ? \Carbon\Carbon::parse($round->start_time)->format('h:i A') : 'N/A' }}
                                                –
                                                {{ $round->end_time ? \Carbon\Carbon::parse($round->end_time)->format('h:i A') : 'N/A' }}
                                            </td>
                                            <td>{{ $round->duration ? $round->duration . ' min' : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty"><i class="fas fa-info-circle"></i> No interview rounds configured.</div>
                    @endif
                </section>

                <!-- 3. Selection -->
                <section class="section detail-section" id="selection-details">
                    <h2 class="section-title"><i class="fas fa-check-double"></i> Selection Configuration</h2>
                    <div class="info-grid">
                        <div class="info-box">
                            <div class="info-label">Selection Method</div>
                            <div class="info-value">
                                {{ $configuration->selection_method ? ucfirst($configuration->selection_method) : 'N/A' }}
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Offer Letter</div>
                            <div class="info-value">
                                {{ $configuration->offer_letter_generation ? ucfirst($configuration->offer_letter_generation) : 'N/A' }}
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Acceptance Deadline</div>
                            <div class="info-value">{{ $configuration->acceptance_deadline_days ?? 0 }} days</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Rejection Emails</div>
                            <div class="info-value">{{ $configuration->send_rejection_emails ? 'Enabled' : 'Disabled' }}
                            </div>
                        </div>
                    </div>
                    @if ($selectedRounds)
                        <div class="info-box" style="margin-top:20px;">
                            <div class="info-label"><i class="fas fa-flag"></i> Selected Rounds</div>
                            <div class="info-value">
                                {{ $selectedRounds ? $formatList($selectedRounds) : 'None' }}
                            </div>
                        </div>
                    @endif
                </section>

                <!-- 4. Onboarding -->
                <section class="section detail-section" id="onboarding-details">
                    <h2 class="section-title"><i class="fas fa-user-plus"></i> Onboarding Configuration</h2>
                    <div class="info-grid">
                        <div class="info-box">
                            <div class="info-label">Joining Date</div>
                            <div class="info-value">
                                {{ $configuration->joining_date ? \Carbon\Carbon::parse($configuration->joining_date)->format('d M Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Reporting Time</div>
                            <div class="info-value">{{ $configuration->reporting_time ?: 'N/A' }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Reporting Venue</div>
                            <div class="info-value">{{ $configuration->reporting_venue ?: 'N/A' }}</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Induction</div>
                            <div class="info-value">{{ $configuration->induction_program ?: 'N/A' }}
                                ({{ $configuration->induction_days ?? 0 }} days)</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Training</div>
                            <div class="info-value">{{ $configuration->training_months ?? 0 }} months</div>
                        </div>
                        <div class="info-box">
                            <div class="info-label">Documents</div>
                            <div class="info-value">{{ $onboardingDocuments ? $formatList($onboardingDocuments) : 'None' }}
                            </div>
                        </div>
                    </div>
                </section>

            </div> <!-- end content -->
        </div> <!-- end main-card -->
    </div> <!-- end interview-detail -->

    <script>
        document.querySelectorAll('.step[data-target]').forEach(function (step) {
            step.addEventListener('click', function () {
                // Remove active class from all steps
                document.querySelectorAll('.step[data-target]').forEach(function (item) {
                    item.classList.remove('active');
                });
                // Hide all detail sections
                document.querySelectorAll('.detail-section').forEach(function (section) {
                    section.classList.remove('active');
                });
                // Activate clicked step
                step.classList.add('active');
                // Show target section
                var target = document.getElementById(step.dataset.target);
                if (target) {
                    target.classList.add('active');
                }
            });
        });
    </script>
@endsection