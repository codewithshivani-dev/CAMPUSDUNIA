@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
    <style>
        .preview-shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 24px;
            color: #1e293b;
        }

        .preview-hero {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
            padding: 28px;
            border-radius: 18px;
            background: linear-gradient(135deg, #1d4ed8, #2563eb 60%, #38bdf8);
            color: #fff;
            box-shadow: 0 12px 30px rgba(37, 99, 235, .22);
        }

        .preview-hero h1 {
            margin: 0 0 8px;
            font-size: clamp(1.5rem, 3vw, 2.2rem);
        }

        .preview-hero p {
            margin: 0;
            opacity: .86;
        }

        .preview-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 18px;
        }

        .preview-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .16);
            font-size: .8rem;
        }

        .preview-back {
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .55);
            background: transparent;
            text-decoration: none;
            padding: 9px 14px;
            border-radius: 8px;
            white-space: nowrap;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-top: 18px;
        }

        .preview-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 5px 18px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .preview-card.full {
            grid-column: 1/-1;
        }

        .preview-card-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 18px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .preview-card-head i {
            color: #2563eb;
            width: 20px;
            text-align: center;
        }

        .preview-card-head h2 {
            margin: 0;
            font-size: 1rem;
        }

        .preview-card-body {
            padding: 18px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .detail-label {
            color: #64748b;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .detail-value {
            color: #1e293b;
            font-weight: 600;
            line-height: 1.45;
            word-break: break-word;
        }

        .round-list {
            display: grid;
            gap: 12px;
        }

        .round-item {
            display: grid;
            grid-template-columns: 42px minmax(0, 1fr) auto;
            gap: 12px;
            align-items: start;
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .round-number {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 800;
        }

        .round-item h3 {
            margin: 0 0 6px;
            font-size: .95rem;
        }

        .round-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 14px;
            color: #64748b;
            font-size: .82rem;
        }

        .round-type {
            padding: 5px 9px;
            border-radius: 999px;
            background: #ecfeff;
            color: #0e7490;
            font-size: .72rem;
            font-weight: 700;
        }

        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            padding: 6px 9px;
            border-radius: 7px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: .8rem;
            font-weight: 600;
        }

        .empty {
            color: #94a3b8;
            font-style: italic;
        }

        @media(max-width:760px) {
            .preview-shell {
                padding: 14px
            }

            .preview-hero {
                padding: 20px;
                flex-direction: column
            }

            .preview-grid {
                grid-template-columns: 1fr
            }

            .detail-grid {
                grid-template-columns: 1fr
            }

            .round-item {
                grid-template-columns: 36px 1fr
            }

            .round-type {
                grid-column: 2
            }
        }
    </style>

    @php
        $documents = is_array($configuration->application_documents) ? $configuration->application_documents : [];
        $skills = is_array($configuration->skills) ? $configuration->skills : [];
        $selectionRounds = is_array($configuration->selected_rounds) ? $configuration->selected_rounds : [];
        $weightages = is_array($configuration->round_weightages) ? $configuration->round_weightages : [];
        $onboardingDocs = is_array($configuration->onboarding_documents) ? $configuration->onboarding_documents : [];
        $locationParts = collect([
            $configuration->venueBuilding?->name,
            $configuration->venueBlock?->name,
            $configuration->venueFloor?->floor_name,
            $configuration->venueRoom?->room_name,
        ])->filter()->implode(', ');
    @endphp

    <div class="preview-shell">
        <section class="preview-hero">
            <div>
                <div style="opacity:.8;font-size:.75rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">
                    Interview Configuration</div>
                <h1>{{ $configuration->form_title ?: 'Untitled Interview Configuration' }}</h1>
                <p>{{ $configuration->department?->department ?? 'Department not assigned' }} ·
                    {{ $configuration->academic_year ?: 'Academic year not assigned' }}</p>
                <div class="preview-meta">
                    <span class="preview-pill"><i
                            class="fas fa-hashtag"></i>{{ $configuration->interview_config_id }}</span>
                    <span class="preview-pill"><i
                            class="fas fa-video"></i>{{ ucfirst($configuration->interview_mode ?: 'Not set') }}</span>
                    <span class="preview-pill"><i
                            class="fas fa-calendar-check"></i>{{ optional($configuration->created_at)->format('d M Y') }}</span>
                </div>
            </div>
            <a class="preview-back" href="{{ route('interview.preview') }}"><i class="fas fa-arrow-left"></i> Back</a>
        </section>

        <div class="preview-grid">
            <section class="preview-card">
                <header class="preview-card-head"><i class="fas fa-file-alt"></i>
                    <h2>Application</h2>
                </header>
                <div class="preview-card-body">
                    <div class="detail-grid">
                        <div>
                            <div class="detail-label">Status</div>
                            <div class="detail-value">{{ $configuration->step1_enabled ? 'Enabled' : 'Disabled' }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Maximum Applications</div>
                            <div class="detail-value">{{ $configuration->max_applications ?: 'Not set' }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Opening Date</div>
                            <div class="detail-value">
                                {{ $configuration->application_start_date ? \Carbon\Carbon::parse($configuration->application_start_date)->format('d M Y') : 'Not set' }}
                            </div>
                        </div>
                        <div>
                            <div class="detail-label">Closing Date</div>
                            <div class="detail-value">
                                {{ $configuration->application_end_date ? \Carbon\Carbon::parse($configuration->application_end_date)->format('d M Y') : 'Not set' }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="preview-card">
                <header class="preview-card-head"><i class="fas fa-map-marker-alt"></i>
                    <h2>Interview Location</h2>
                </header>
                <div class="preview-card-body">
                    <div class="detail-grid">
                        <div>
                            <div class="detail-label">Mode</div>
                            <div class="detail-value">{{ ucfirst($configuration->interview_mode ?: 'Not set') }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Online Meeting</div>
                            <div class="detail-value">@if($configuration->online_meet_link)<a
                                href="{{ $configuration->online_meet_link }}" target="_blank" rel="noopener">Join
                            meeting</a>@else<span class="empty">Not configured</span>@endif</div>
                        </div>
                        <div style="grid-column:1/-1">
                            <div class="detail-label">Offline Location</div>
                            <div class="detail-value">
                                {{ $locationParts ?: ($configuration->venue_address ?: 'Not configured') }}</div>
                        </div>
                        <div style="grid-column:1/-1">
                            <div class="detail-label">Address</div>
                            <div class="detail-value">{{ $configuration->venue_address ?: 'Not configured' }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="preview-card full">
                <header class="preview-card-head"><i class="fas fa-layer-group"></i>
                    <h2>Interview Rounds</h2><span
                        style="margin-left:auto;color:#64748b;font-size:.8rem;">{{ $rounds->count() }} configured</span>
                </header>
                <div class="preview-card-body">
                    @if($rounds->isEmpty())
                    <div class="empty">No interview rounds configured.</div> @else
                        <div class="round-list">@foreach($rounds as $index => $round)
                            <article class="round-item">
                                <div class="round-number">{{ $index + 1 }}</div>
                                <div>
                                    <h3>{{ $round->name ?: 'Round ' . ($index + 1) }}</h3>
                                    <div class="round-meta"><span><i class="far fa-calendar"></i>
                                            {{ $round->round_date ? \Carbon\Carbon::parse($round->round_date)->format('d M Y') : 'Date not set' }}</span><span><i
                                                class="far fa-clock"></i>
                                            {{ $round->start_time ?: 'Time not set' }}{{ $round->end_time ? ' - ' . $round->end_time : '' }}</span><span><i
                                                class="fas fa-users"></i> Panel {{ $round->panel ?: 'Not set' }}</span></div>
                                </div>
                                <span class="round-type">{{ $round->type_label ?: ucfirst($round->type ?: 'Interview') }}</span>
                            </article>
                        @endforeach
                        </div>
                    @endif
                </div>
            </section>

            <section class="preview-card">
                <header class="preview-card-head"><i class="fas fa-check-circle"></i>
                    <h2>Selection</h2>
                </header>
                <div class="preview-card-body">
                    <div class="detail-grid">
                        <div>
                            <div class="detail-label">Status</div>
                            <div class="detail-value">{{ $configuration->step3_enabled ? 'Enabled' : 'Disabled' }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Method</div>
                            <div class="detail-value">{{ ucfirst($configuration->selection_method ?: 'Not set') }}</div>
                        </div>
                    </div>
                    <div style="margin-top:16px">
                        <div class="detail-label">Selected Rounds</div>
                        <div class="tag-list">@forelse($selectionRounds as $round)<span
                        class="tag">{{ is_array($round) ? ($round['name'] ?? 'Round') : $round }}</span>@empty<span
                                class="empty">All configured rounds</span>@endforelse</div>
                    </div>
                </div>
            </section>

            <section class="preview-card">
                <header class="preview-card-head"><i class="fas fa-user-plus"></i>
                    <h2>Onboarding</h2>
                </header>
                <div class="preview-card-body">
                    <div class="detail-grid">
                        <div>
                            <div class="detail-label">Status</div>
                            <div class="detail-value">{{ $configuration->step4_enabled ? 'Enabled' : 'Disabled' }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Joining Date</div>
                            <div class="detail-value">
                                {{ $configuration->joining_date ? \Carbon\Carbon::parse($configuration->joining_date)->format('d M Y') : 'Not set' }}
                            </div>
                        </div>
                        <div>
                            <div class="detail-label">Reporting Time</div>
                            <div class="detail-value">{{ $configuration->reporting_time ?: 'Not set' }}</div>
                        </div>
                        <div>
                            <div class="detail-label">Reporting Venue</div>
                            <div class="detail-value">{{ $configuration->reporting_venue ?: 'Not set' }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="preview-card full">
                <header class="preview-card-head"><i class="fas fa-list-check"></i>
                    <h2>Documents & Skills</h2>
                </header>
                <div class="preview-card-body">
                    <div class="detail-grid">
                        <div>
                            <div class="detail-label">Application Documents</div>
                            <div class="tag-list">@forelse($documents as $document)<span
                            class="tag">{{ is_array($document) ? ($document['name'] ?? 'Document') : $document }}</span>@empty<span
                                    class="empty">None configured</span>@endforelse</div>
                        </div>
                        <div>
                            <div class="detail-label">Required Skills</div>
                            <div class="tag-list">@forelse($skills as $skill)<span
                            class="tag">{{ is_array($skill) ? ($skill['name'] ?? 'Skill') : $skill }}</span>@empty<span
                                    class="empty">None configured</span>@endforelse</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection