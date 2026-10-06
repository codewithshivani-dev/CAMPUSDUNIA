@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Edit Syllabus - Mathematics</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px 30px;
        background: var(--primary-gradient);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .page-title i {
        font-size: 32px;
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
    }

    .page-title small {
        font-size: 16px;
        font-weight: 400;
        opacity: 0.9;
    }

    .btn-back {
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #fff !important;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-3px);
        text-decoration: none;
        color: white !important;
    }

    /* Alert Styles */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .alert-success {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 1px solid #86efac;
        color: #166534;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 1px solid #fcd34d;
        color: #92400e;
    }

    /* Month Tabs - Small Box Style */
    .month-tabs-wrapper {
        background: #ffffff;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        margin-bottom: 25px;
    }

    .month-tabs {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
        gap: 8px;
        margin-bottom: 20px;
    }

    .month-tab {
        padding: 10px 8px;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        color: #64748b;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        position: relative;
        min-height: 60px;
        justify-content: center;
    }

    .month-tab:hover {
        border-color: var(--primary-color);
        background: #f0f4ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
    }

    .month-tab.active {
        border-color: var(--primary-color);
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        transform: translateY(-2px);
    }

    .month-tab .month-short { font-size: 14px; font-weight: 700; line-height: 1.2; }
    .month-tab .month-year { font-size: 9px; font-weight: 400; opacity: 0.7; }
    .month-tab.active .month-year { opacity: 0.9; }

    .month-tab .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        position: absolute;
        top: 6px;
        right: 6px;
    }

    .status-dot.uploaded { background: #10b981; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4); }
    .status-dot.missing { background: #e2e8f0; }

    .month-tab .tab-icon { font-size: 16px; }
    .month-tab.active .tab-icon { filter: brightness(10); }

    .month-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 2px;
    }

    .month-badge.uploaded { background: #dcfce7; color: #166534; }
    .month-badge.missing { background: #f1f5f9; color: #64748b; }
    .month-tab.active .month-badge.uploaded { background: rgba(255,255,255,0.25); color: white; }
    .month-tab.active .month-badge.missing { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.8); }

    /* Tab Content */
    .tab-content-wrapper {
        padding: 20px 0 0 0;
        background: #fafbfc;
        border-radius: 12px;
        min-height: 200px;
    }

    .tab-pane { display: none; animation: fadeIn 0.4s ease; }
    .tab-pane.active { display: block; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Month Content Card */
    .month-content-card {
        background: white;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }

    .month-content-card .topic-item {
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 0;
    }

    .month-content-card .topic-item:last-child { border-bottom: none; padding-bottom: 0; }

    .month-content-card .topic-title { font-weight: 700; color: #0f172a; font-size: 16px; margin-bottom: 6px; }
    .month-content-card .topic-description { color: #475569; font-size: 14px; margin-bottom: 12px; line-height: 1.6; }

    .month-content-card .file-list {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 10px;
    }

    .month-content-card .file-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s;
        flex: 1 1 200px;
    }

    .month-content-card .file-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    .month-content-card .file-item .file-icon { font-size: 24px; color: #ef4444; }
    .month-content-card .file-item .file-info { flex: 1; }
    .month-content-card .file-item .file-name { font-weight: 600; font-size: 14px; color: #0f172a; }
    .month-content-card .file-item .file-meta { font-size: 12px; color: #94a3b8; }
    .month-content-card .file-actions { display: flex; gap: 6px; flex-wrap: wrap; }
    .month-content-card .file-actions .btn { padding: 4px 12px; font-size: 12px; }

    .empty-month-state { text-align: center; padding: 40px 20px; color: #94a3b8; }
    .empty-month-state i { font-size: 48px; color: #cbd5e1; display: block; margin-bottom: 16px; }
    .empty-month-state h6 { color: #475569; font-weight: 600; }

    /* Buttons */
    .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 10px 22px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); }
    .btn-primary { background: var(--primary-gradient); color: white; }
    .btn-success { background: var(--success-gradient); color: white; }
    .btn-danger { background: var(--danger-gradient); color: white; }
    .btn-warning { background: var(--warning-gradient); color: white; }
    .btn-outline-primary { background: transparent; border: 2px solid var(--primary-color); color: var(--primary-color); }
    .btn-outline-primary:hover { background: var(--primary-gradient); border-color: transparent; color: white; }
    .btn-sm { padding: 4px 12px; font-size: 12px; }

    /* Edit Section */
    .month-edit-section {
        margin-top: 25px;
        background: white;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }

    .month-edit-section .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .month-edit-section .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .month-edit-section .form-label { font-weight: 600; color: #1e293b; margin-bottom: 6px; font-size: 13px; }
    .month-edit-section .required { color: #ef4444; }

    .info-card {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #1e293b;
        border-left: 4px solid var(--primary-color);
    }

    .info-card i { color: var(--primary-color); font-size: 18px; }

    /* Upload Area Style */
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 30px 20px;
        text-align: center;
        background: #fafbfc;
        transition: all 0.3s;
        cursor: pointer;
        margin-top: 10px;
    }

    .upload-area:hover { border-color: var(--primary-color); background: #f0f4ff; }
    .upload-area .upload-icon { font-size: 40px; color: #9ca3af; display: block; margin-bottom: 10px; }
    .upload-area .upload-text { color: #6b7280; font-size: 14px; }
    .upload-area .upload-text strong { color: var(--primary-color); cursor: pointer; }

    .upload-formats { display: flex; gap: 8px; justify-content: center; margin-top: 10px; flex-wrap: wrap; }
    .upload-formats span { background: #e5e7eb; padding: 2px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; color: #4b5563; }

    /* Inline Edit Styles */
    .edit-inputs { display: block; margin-top: 8px; }
    .edit-inputs .form-control { font-size: 14px; }

    .file-item.editing {
        border-color: var(--primary-color);
        background: #f0f4ff;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .inline-edit-actions {
        display: flex;
        gap: 6px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .inline-edit-actions .btn {
        padding: 4px 14px;
        font-size: 12px;
    }

    .semester-edit-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 22px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        margin-bottom: 24px;
    }

    .semester-edit-card .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 20px;
    }

    .semester-edit-card .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }

    .semester-edit-card .section-subtitle {
        color: #64748b;
        font-size: 14px;
    }

    .semester-edit-card .summary-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 18px;
    }

    .semester-edit-card .summary-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
    }

    .semester-edit-card .summary-item strong {
        display: block;
        color: #475569;
        margin-bottom: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .semester-edit-card .summary-item span {
        color: #0f172a;
        display: block;
        font-size: 14px;
    }

    .semester-edit-card .form-label {
        font-size: 13px;
        color: #334155;
        margin-bottom: 6px;
    }

    .semester-edit-card .form-control,
    .semester-edit-card .form-control-sm {
        border: 1px solid #cbd5e1;
        background: #f8fafc;
    }

    .semester-edit-card .form-control:focus,
    .semester-edit-card .form-control-sm:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    .semester-edit-card .notes {
        color: #475569;
        font-size: 13px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header { flex-direction: column; align-items: flex-start; gap: 16px; padding: 20px; }
        .page-title { font-size: 22px; flex-wrap: wrap; }
        .btn-back { width: 100%; justify-content: center; }
        .month-tabs { grid-template-columns: repeat(auto-fill, minmax(55px, 1fr)); gap: 6px; }
        .month-tab { padding: 8px 4px; font-size: 11px; min-height: 50px; }
        .month-tab .month-short { font-size: 12px; }
        .month-content-card .file-item { flex-direction: column; align-items: flex-start; }
        .month-content-card .file-actions { width: 100%; justify-content: flex-start; }
    }
</style>

@php
    $defaultMonth = collect($months)->first() ? \Carbon\Carbon::parse(collect($months)->first() . '-01') : \Carbon\Carbon::now();
    $defaultMonthLabel = $defaultMonth->format('F Y');
    $defaultStartDate = $defaultMonth->copy()->startOfMonth()->format('Y-m-d');
    $defaultEndDate = $defaultMonth->copy()->endOfMonth()->format('Y-m-d');
    $selectedStartDate = $courseStartDate ? \Carbon\Carbon::parse($courseStartDate)->format('Y-m-d') : $defaultStartDate;
    $selectedEndDate = $courseEndDate ? \Carbon\Carbon::parse($courseEndDate)->format('Y-m-d') : $defaultEndDate;
    $selectedMonthKey = collect($months)->first();
    $selectedFile = $selectedMonthKey && isset($filesByMonth[$selectedMonthKey]) ? ($filesByMonth[$selectedMonthKey][0] ?? null) : null;
@endphp

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-pencil-square"></i>
            Edit Syllabus
            <small>{{ $subjectName }}</small>
        </h1>
        <a href="{{ route('instituteAdmin.syllabus.viewAll') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <!-- Subject Info -->
    <div class="info-card mb-3">
        <i class="bi bi-info-circle-fill"></i>
        <div>
            <strong>Subject:</strong> {{ $subjectName }}
            <span class="mx-2">|</span>
            <strong>Start:</strong> {{ $courseStartDate ? \Carbon\Carbon::parse($courseStartDate)->format('Y-m-d') : 'N/A' }}
            <span class="mx-2">|</span>
            <strong>End:</strong> {{ $courseEndDate ? \Carbon\Carbon::parse($courseEndDate)->format('Y-m-d') : 'N/A' }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($displayMode === 'monthly')
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label class="form-label">Start Date</label>
            <input type="date" id="course_start_date" class="form-control" value="{{ $selectedStartDate }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">End Date</label>
            <input type="date" id="course_end_date" class="form-control" value="{{ $selectedEndDate }}">
        </div>
    </div>

    <!-- Month Tabs - Small Box Style -->
    <div class="month-tabs-wrapper">
        <div class="month-tabs">
            @foreach($months as $index => $month)
                @php
                    $monthCarbon = \Carbon\Carbon::parse("{$month}-01");
                    $isActive = $index === 0;
                    $isCompleted = in_array($month, $completedMonths, true);
                @endphp
                <button class="month-tab {{ $isActive ? 'active' : '' }}" data-month="{{ $month }}">
                    <span class="status-dot {{ $isCompleted ? 'uploaded' : 'missing' }}"></span>
                    <span class="month-short">{{ $monthCarbon->format('M') }}</span>
                    <span class="month-year">{{ $monthCarbon->format('Y') }}</span>
                    <span class="month-badge {{ $isCompleted ? 'uploaded' : 'missing' }}">{{ $isCompleted ? '✓' : '+' }}</span>
                </button>
            @endforeach
        </div>

        <!-- Tab Content -->
        <div class="tab-content-wrapper">
            @foreach($months as $index => $month)
                @php
                    $monthCarbon = \Carbon\Carbon::parse("{$month}-01");
                    $monthLabel = $monthCarbon->format('F Y');
                    $topicsForMonth = $topicsByMonth[$month] ?? collect();
                    $filesForMonth = $filesByMonth->get($month) ?? collect();
                    $hasFiles = $filesForMonth->isNotEmpty();
                    $showCompleted = $topicsForMonth->isNotEmpty() || $hasFiles;
                    $isActive = $index === 0;
                @endphp
                <div class="tab-pane {{ $isActive ? 'active' : '' }}" data-month="{{ $month }}">
                    <div class="month-content-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">📚 {{ $monthLabel }}</h6>
                            <span class="badge bg-{{ $showCompleted ? 'success' : 'secondary' }}">
                                {{ $showCompleted ? '✓ Complete' : '+ Missing' }}
                            </span>
                        </div>

                        @if($filesForMonth->isEmpty())
                            <div class="empty-month-state">
                                <i class="bi bi-calendar-plus"></i>
                                <h6>No syllabus uploaded yet</h6>
                                <p class="text-muted small">Use the form below to upload syllabus for {{ $monthLabel }}</p>
                            </div>
                        @else
                           <div class="file-list mb-3">
    @foreach($filesForMonth as $file)
        @php
            $firstTopic = $topicsForMonth->firstWhere('syllabus_id', $file['syllabus_id'])
                ?? $topics->firstWhere('syllabus_id', $file['syllabus_id']);
        @endphp
        <div class="file-item" data-file-id="{{ $file['id'] }}" data-month="{{ $month }}">
            <!-- VIEW MODE - Shows existing data -->
            <div class="display-only" style="flex:1;">
                <div style="display: flex; justify-content: space-between; align-items: start; gap: 15px;">
                    <div style="flex: 1;">
                        <div class="fw-bold mb-2 topic-name">{{ data_get($firstTopic, 'topic_name', $file['title'] ?? 'Syllabus') }}</div>
                        <div class="small text-muted mb-2 topic-description">
                            <strong>Description:</strong> {{ data_get($firstTopic, 'description', $file['description'] ?? 'No description') }}
                        </div>
                        <div class="small text-muted file-meta">
                            <strong>File:</strong> <span class="file-name">{{ $file['file_name'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary edit-toggle-btn">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                </div>
            </div>

            <!-- EDIT MODE - Hidden by default -->
            <div class="edit-inputs" style="flex:1; display:none;">
                <div class="semester-edit-card">
                    <div class="section-heading">
                        <div>
                            <div class="section-title">Edit {{ $monthLabel }} Syllabus</div>
                            <div class="section-subtitle">Update the topic, description, and file for this month.</div>
                        </div>
                        <div class="notes">Current file: <strong>{{ $file['file_name'] ?? 'No file uploaded' }}</strong></div>
                    </div>

                    <form method="POST" action="{{ route('instituteAdmin.syllabus.updateAjax', ['subjectId' => $subjectId]) }}" enctype="multipart/form-data" class="inline-edit-form">
                        @csrf
                        <input type="hidden" name="editing_file_id" value="{{ $file['id'] }}">
                        <input type="hidden" name="selected_month_value" value="{{ $month }}">
                        <input type="hidden" name="topic_id" value="{{ data_get($firstTopic, 'topic_id', data_get($firstTopic, 'id')) }}">
                        <input type="hidden" name="parsed_preview_text" class="parsed-preview-text-inline" value="">

                        <div class="row gy-3">
                            <div class="col-md-6">
                                <label class="form-label">Topic Name</label>
                                <input type="text" name="topic_name" class="form-control form-control-lg" value="{{ data_get($firstTopic, 'topic_name', $file['title'] ?? '') }}" placeholder="Enter topic name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Upload New File</label>
                                <input type="file" name="file" class="form-control form-control-sm" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Leave empty to keep the current file.</small>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Topic Description</label>
                                <textarea name="description" class="form-control form-control-lg" rows="3" placeholder="Short description (optional)">{{ data_get($firstTopic, 'description', $file['description'] ?? '') }}</textarea>
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-lg"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @else
    <div class="month-tabs-wrapper">
        <div class="alert alert-info mb-3">
            <i class="bi bi-journal-bookmark-fill"></i>
            <div>
                <strong>Whole Semester Syllabus</strong>
                <p class="mb-0 mt-1">This syllabus is uploaded at the semester level, so it is shown as one full-semester entry.</p>
            </div>
        </div>

        @php
            $semesterFiles = $files->filter(function ($file) {
                $termType = trim(strtolower((string) ($file->term_type ?? '')));
                return in_array($termType, ['', 'semester', 'yearly'], true);
            })->values();
        @endphp

        @if($semesterFiles->isEmpty())
            <div class="empty-month-state">
                <i class="bi bi-journal-bookmark-fill"></i>
                <h6>No whole-semester syllabus uploaded yet</h6>
                <p class="text-muted small">Upload the syllabus for the entire semester from the main syllabus upload screen.</p>
            </div> 
        @else
            <div class="file-list mb-3">
                @foreach($semesterFiles as $file)
                    @php
                        $topicForFile = $topics->firstWhere('syllabus_id', $file->syllabus_id);
                    @endphp
                    <div class="file-item semester-edit-card" data-file-id="{{ $file->id }}">
                        <div class="section-heading">
                            <div>
                                <div class="section-title">Full Semester Syllabus</div>
                                <div class="section-subtitle">Update the main syllabus topic and file for the whole semester.</div>
                            </div>
                        </div>

                        <div class="edit-inputs" style="flex:1; display:block;">
                            <form method="POST" action="{{ route('instituteAdmin.syllabus.updateAjax', ['subjectId' => $subjectId]) }}" enctype="multipart/form-data" class="inline-edit-form">
                                @csrf
                                <input type="hidden" name="editing_file_id" value="{{ $file->id }}">
                                <input type="hidden" name="selected_month_value" value="{{ $file->term_value ?? 'semester' }}">
                                <input type="hidden" name="topic_id" value="{{ data_get($topicForFile, 'topic_id', data_get($topicForFile, 'id')) }}">
                                <input type="hidden" name="parsed_preview_text" class="parsed-preview-text-inline" value="">

                                <div class="row gy-3">
                                    <div class="col-12">
                                        <label class="form-label">Topic Name</label>
                                        <input type="text" name="topic_name" class="form-control form-control-lg" value="{{ data_get($topicForFile, 'topic_name', 'Full Semester Syllabus') }}" placeholder="Enter topic name">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Topic Description</label>
                                        <textarea name="description" class="form-control form-control-lg" rows="4" placeholder="Short description (optional)">{{ $file->description ?? data_get($topicForFile, 'description', '') }}</textarea>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Upload Syllabus File</label>
                                        <div class="upload-area" style="padding: 28px 20px; border-radius: 18px;">
                                            <span class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></span>
                                            <div class="upload-text">Drop your syllabus file here or <strong>click to browse</strong></div>
                                            <div class="upload-formats">
                                                <span>PDF</span>
                                                <span>DOC</span>
                                                <span>DOCX</span>
                                                <span>JPG</span>
                                                <span>PNG</span>
                                            </div>
                                        </div>
                                        <input type="file" name="file" class="form-control d-none semester-file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <small class="text-muted d-block mt-2">Leave empty to keep the existing file.</small>
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg"></i> Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

    <!-- Monthly/Semester Syllabus Edit Section -->
   <!-- Monthly/Semester Syllabus Edit Section -->
<div id="monthEditContainer" style="display: none;">
    <div id="noDataMessage" class="month-edit-section" style="display: none;">
            <div class="alert alert-warning">
                <i class="bi bi-info-circle"></i>
                <div>
                    <strong>No Data Available</strong>
                    <p class="mb-0 mt-2">This month has no syllabus data. Please upload syllabus data to continue.</p>
                </div>
            </div>
        </div>

        <div id="uploadFormSection" class="month-edit-section" style="display: none;">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-cloud-arrow-up-fill text-primary"></i>
                <span id="uploadFormTitle">Upload Selected Month Syllabus</span>
            </h6>
            <form method="POST" action="{{ route('instituteAdmin.syllabus.update', ['subjectId' => $subjectId]) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" id="selected_month_value_upload" name="selected_month_value" value="{{ collect($months)->first() ?? $defaultMonth->format('Y-m') }}">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Month</label>
                        <input type="text" id="selected_month_display_upload" class="form-control" readonly value="{{ $defaultMonthLabel }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Syllabus Title</label>
                        <input type="text" name="title" id="syllabus_title_upload" class="form-control" value="{{ $defaultMonthLabel . ' Syllabus' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Topic Name</label>
                        <input type="text" name="topic_name" id="upload_topic_name" class="form-control" placeholder="Enter topic name">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="syllabus_description_upload" class="form-control" rows="2" placeholder="Optional description">{{ 'Syllabus for '.$defaultMonthLabel }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Upload Syllabus File</label>
                        <div id="uploadArea" class="upload-area">
                            <span class="upload-icon"><i class="bi bi-cloud-arrow-up"></i></span>
                            <div class="upload-text">
                                Drop your syllabus file here or <strong>click to browse</strong>
                            </div>
                            <div class="upload-formats">
                                <span>PDF</span>
                                <span>DOC</span>
                                <span>DOCX</span>
                                <span>JPG</span>
                                <span>PNG</span>
                            </div>
                        </div>
                        <input type="file" name="file" id="uploadFileInput" class="form-control d-none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <input type="hidden" name="parsed_preview_text" id="parsed_preview_text_upload" value="">
                        <div class="form-text">Upload a syllabus file for the selected month.</div>
                        <div id="pdfPreviewSectionUpload" class="bg-white border rounded p-3 mt-3" style="display:none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>PDF Text Preview</strong>
                                <span id="wordCountUpload" class="badge bg-info">0 words</span>
                            </div>
                            <div id="pdfExtractedTextUpload" class="small text-muted" style="white-space: pre-wrap; max-height: 220px; overflow-y: auto;">No text extracted yet</div>
                            <div class="d-flex flex-wrap gap-3 mt-2 text-muted small">
                                <span><i class="bi bi-file-earmark-pdf"></i> PDF detected</span>
                                <span><i class="bi bi-book"></i> <span id="pageCountUpload">0</span> pages</span>
                                <span><i class="bi bi-arrow-left-right"></i> <span id="charCountUpload">0</span> chars</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Upload Month Syllabus
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div id="editFormSection" class="month-edit-section" style="display: none;">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-pencil-square text-primary"></i>
                <span id="formTitle">Edit Selected Month Syllabus</span>
            </h6>

            <div id="existingTopicsSection" class="mb-3" style="display: none;">
                <h6 class="fw-bold">Topics for this Month</h6>
                <div id="existingTopicsList" class="list-group"></div>
            </div>

            <div id="topicFormSection" class="mb-3">
                <h6 class="fw-bold">Add / Edit Topic</h6>
                <form id="topicForm" method="POST" action="{{ route('instituteAdmin.syllabus.update', ['subjectId' => $subjectId]) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="topic_action" name="topic_action" value="add">
                    <input type="hidden" id="topic_id" name="topic_id" value="">
                    <input type="hidden" id="topic_month" name="topic_month" value="{{ collect($months)->first() ?? $defaultMonth->format('Y-m') }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Topic Name</label>
                            <input type="text" id="new_topic_name" name="topic_name" class="form-control" placeholder="Topic name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" id="new_topic_start_date" name="topic_start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="date" id="new_topic_end_date" name="topic_end_date" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea id="new_topic_description" name="topic_description" class="form-control" rows="2" placeholder="Optional description"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Attach File (optional)</label>
                            <input type="file" id="new_topic_file" name="topic_file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-success" id="topicSubmitBtn">Add Topic</button>
                            <button type="button" class="btn btn-outline-secondary" id="topicCancelBtn" style="display:none;">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Removed the duplicate form section since we now have inline editing -->
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const displayMode = @json($displayMode ?? 'monthly');
    const monthTabsContainer = document.querySelector('.month-tabs');
    const tabContentWrapper = document.querySelector('.tab-content-wrapper');
    const startDateInput = document.getElementById('course_start_date');
    const endDateInput = document.getElementById('course_end_date');
    const selectedMonthValue = document.getElementById('selected_month_value');
    const selectedMonthValueUpload = document.getElementById('selected_month_value_upload');
    const selectedMonthDisplay = document.getElementById('selected_month_display');
    const noDataMessage = document.getElementById('noDataMessage');

    const initialMonths = @json($months ?? []);
    const completedMonths = @json($completedMonths ?? []);
    const topicsByMonth = @json($topicsByMonthArray ?? []);
    const filesByMonth = @json($filesByMonthArray ?? []);
    const csrfToken = '{{ csrf_token() }}';
    const pdfPreviewSectionUpload = document.getElementById('pdfPreviewSectionUpload');
    const pdfExtractedTextUpload = document.getElementById('pdfExtractedTextUpload');
    const wordCountUpload = document.getElementById('wordCountUpload');
    const pageCountUpload = document.getElementById('pageCountUpload');
    const charCountUpload = document.getElementById('charCountUpload');
    const parsedPreviewTextUpload = document.getElementById('parsed_preview_text_upload');

    // ============================================
    // INLINE EDIT TOGGLE FUNCTIONALITY
    // ============================================

    function toggleInlineEdit(fileItem, showEdit) {
        const viewMode = fileItem.querySelector('.display-only');
        const editMode = fileItem.querySelector('.edit-inputs');

        if (showEdit) {
            fileItem.classList.add('editing');
            if (viewMode) viewMode.style.display = 'none';
            if (editMode) editMode.style.display = 'block';
        } else {
            fileItem.classList.remove('editing');
            if (viewMode) viewMode.style.display = 'block';
            if (editMode) editMode.style.display = 'none';
        }
    }
function setupMonthUploadForm() {
    document.querySelectorAll('.tab-pane').forEach(pane => {
        const month = pane.dataset.month;
        const emptyState = pane.querySelector('.empty-month-state');
        
        if (emptyState) {
            // Create upload form container if it doesn't exist
            let uploadContainer = pane.querySelector('.month-upload-form-container');
            if (!uploadContainer) {
                uploadContainer = document.createElement('div');
                uploadContainer.className = 'month-upload-form-container mt-4';
                uploadContainer.style.background = 'white';
                uploadContainer.style.borderRadius = '14px';
                uploadContainer.style.padding = '20px';
                uploadContainer.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.04)';
                
                uploadContainer.innerHTML = `
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-cloud-arrow-up-fill text-primary"></i>
                        Upload Syllabus for ${month}
                    </h6>
                    <form method="POST" action="{{ route('instituteAdmin.syllabus.update', ['subjectId' => $subjectId]) }}" enctype="multipart/form-data" class="month-upload-form">
                        @csrf
                        <input type="hidden" name="selected_month_value" value="${month}">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Topic Name</label>
                                <input type="text" name="topic_name" class="form-control" placeholder="Enter topic name">
                            </div>
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Syllabus description"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Upload File</label>
                                <div class="upload-area" style="border: 2px dashed #d1d5db; border-radius: 12px; padding: 30px 20px; text-align: center; background: #fafbfc; cursor: pointer;">
                                    <span style="font-size: 40px; color: #9ca3af; display: block; margin-bottom: 10px;"><i class="bi bi-cloud-arrow-up"></i></span>
                                    <div style="color: #6b7280; font-size: 14px;">
                                        Drop your syllabus file here or <strong style="color: #4361ee; cursor: pointer;">click to browse</strong>
                                    </div>
                                    <div style="display: flex; gap: 8px; justify-content: center; margin-top: 10px; flex-wrap: wrap;">
                                        <span style="background: #e5e7eb; padding: 2px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; color: #4b5563;">PDF</span>
                                        <span style="background: #e5e7eb; padding: 2px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; color: #4b5563;">DOC</span>
                                        <span style="background: #e5e7eb; padding: 2px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; color: #4b5563;">DOCX</span>
                                    </div>
                                </div>
                                <input type="file" name="file" class="form-control d-none month-file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                <input type="hidden" name="parsed_preview_text" class="parsed-preview-text-month" value="">
                                <div class="pdf-preview-section month-pdf-preview mt-3" style="display:none; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="text-secondary">PDF Text Preview</strong>
                                        <span class="badge bg-info pdf-word-count">0 words</span>
                                    </div>
                                    <div class="pdf-extracted-text small text-muted" style="white-space: pre-wrap; max-height: 180px; overflow-y:auto;">No text extracted yet</div>
                                    <div class="d-flex flex-wrap gap-3 mt-2 text-muted small">
                                        <span><i class="bi bi-book"></i> <span class="pdf-page-count">0</span> pages</span>
                                        <span><i class="bi bi-arrow-left-right"></i> <span class="pdf-char-count">0</span> chars</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-upload"></i> Upload Syllabus
                                </button>
                            </div>
                        </div>
                    </form>
                `;
                
                pane.querySelector('.month-content-card').appendChild(uploadContainer);
                
                // Add drag and drop and click handlers
                const uploadArea = uploadContainer.querySelector('.upload-area');
                const fileInput = uploadContainer.querySelector('.month-file-input');
                
                uploadArea.addEventListener('click', () => fileInput.click());
                uploadArea.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    uploadArea.style.borderColor = '#4361ee';
                    uploadArea.style.background = '#f0f4ff';
                });
                uploadArea.addEventListener('dragleave', () => {
                    uploadArea.style.borderColor = '#d1d5db';
                    uploadArea.style.background = '#fafbfc';
                });
                uploadArea.addEventListener('drop', (e) => {
                    e.preventDefault();
                    if (e.dataTransfer.files.length > 0) {
                        fileInput.files = e.dataTransfer.files;
                        fileInput.dispatchEvent(new Event('change'));
                    }
                });
            }
        }
    });
    setupMonthPdfPreviews();
}
    // Attach inline edit handlers to all file items
    function attachInlineEditHandlers() {
        document.querySelectorAll('.file-item').forEach(item => {
            // Remove existing listeners to avoid duplicates
            const editBtn = item.querySelector('.edit-toggle-btn');
            const cancelBtn = item.querySelector('.cancel-edit-btn');

            if (editBtn) {
                editBtn.replaceWith(editBtn.cloneNode(true));
                const newEditBtn = item.querySelector('.edit-toggle-btn');
                newEditBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Close any other open edit forms
                    document.querySelectorAll('.file-item.editing').forEach(el => {
                        if (el !== item) {
                            toggleInlineEdit(el, false);
                        }
                    });
                    toggleInlineEdit(item, true);
                });
            }

            if (cancelBtn) {
                cancelBtn.replaceWith(cancelBtn.cloneNode(true));
                const newCancelBtn = item.querySelector('.cancel-edit-btn');
                newCancelBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleInlineEdit(item, false);
                });
            }

            // Handle form submission via AJAX for inline editing
            const form = item.querySelector('.inline-edit-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const actionUrl = this.action;

                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
                    submitBtn.disabled = true;

                    fetch(actionUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || csrfToken,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Show success message
                            showToast('success', data.message || 'Syllabus updated successfully!');

                            // Update the display with new values
                            const titleInput = form.querySelector('input[name="title"]');
                            const descInput = form.querySelector('textarea[name="description"]');

                            const viewTitle = item.querySelector('.file-name');
                            const viewMeta = item.querySelector('.file-meta');

                            if (titleInput && viewTitle) {
                                viewTitle.textContent = titleInput.value || 'No title';
                            }

                            if (descInput && viewMeta) {
                                const descDiv = viewMeta.querySelector('div:nth-child(2)');
                                if (descDiv) {
                                    descDiv.innerHTML = '<strong>Description:</strong> ' + (descInput.value || 'No description');
                                }
                            }

                            // If a new file was uploaded, update the file name
                            if (data.file_name) {
                                const fileNameDiv = viewMeta?.querySelector('div:first-child');
                                if (fileNameDiv) {
                                    fileNameDiv.innerHTML = '<strong>File:</strong> ' + data.file_name;
                                }
                            }

                            // Switch back to view mode
                            toggleInlineEdit(item, false);
                        } else {
                            showToast('error', data.message || 'Failed to update syllabus.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'An error occurred while saving.');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
                });
            }
        });
    }

    // Toast notification function
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.style.maxWidth = '400px';
        toast.style.animation = 'slideIn 0.3s ease';
        toast.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill'}"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    function setMonthEditFormForMonth(month) {
        // This page renders panes server-side, so no additional month-specific client-side form setup is required here.
        return;
    }

    // ============================================
    // MONTH TABS AND PANES
    // ============================================

    function getMonthsBetween(start, end) {
        const months = [];
        const cursor = new Date(start.getFullYear(), start.getMonth(), 1);
        const finish = new Date(end.getFullYear(), end.getMonth(), 1);

        while (cursor <= finish) {
            months.push(`${cursor.getFullYear()}-${String(cursor.getMonth() + 1).padStart(2, '0')}`);
            cursor.setMonth(cursor.getMonth() + 1);
        }

        return months;
    }


    // ============================================
    // PDF UPLOAD HANDLING
    // ============================================

    document.getElementById('uploadFileInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) {
            if (pdfPreviewSectionUpload) pdfPreviewSectionUpload.style.display = 'none';
            if (parsedPreviewTextUpload) parsedPreviewTextUpload.value = '';
            return;
        }

        if (file.type === 'application/pdf') {
            if (pdfPreviewSectionUpload) pdfPreviewSectionUpload.style.display = 'block';
            if (pdfExtractedTextUpload) pdfExtractedTextUpload.innerHTML = '<span class="text-muted">Extracting text from PDF...</span>';
            if (wordCountUpload) wordCountUpload.textContent = 'Loading...';
            if (parsedPreviewTextUpload) parsedPreviewTextUpload.value = '';

            const reader = new FileReader();
            reader.onload = function(event) {
                const typedarray = new Uint8Array(event.target.result);
                pdfjsLib.getDocument(typedarray).promise
                    .then(function(pdf) {
                        const numPages = pdf.numPages;
                        if (pageCountUpload) pageCountUpload.textContent = numPages;
                        let fullText = '';
                        const promises = [];

                        for (let i = 1; i <= numPages; i++) {
                            promises.push(pdf.getPage(i).then(function(page) {
                                return page.getTextContent().then(function(textContent) {
                                    const pageText = textContent.items.map(item => item.str).join(' ');
                                    fullText += pageText + '\n';
                                });
                            }));
                        }

                        Promise.all(promises).then(function() {
                            const trimmedText = fullText.trim();
                            const wordCount = trimmedText.length > 0 ? trimmedText.split(/\s+/).length : 0;
                            const charCount = trimmedText.length;
                            if (wordCountUpload) wordCountUpload.textContent = wordCount + ' words';
                            if (charCountUpload) charCountUpload.textContent = charCount;
                            if (pdfExtractedTextUpload) {
                                if (trimmedText.length > 0) {
                                    pdfExtractedTextUpload.textContent = trimmedText.substring(0, 1000) + (trimmedText.length > 1000 ? '...' : '');
                                } else {
                                    pdfExtractedTextUpload.innerHTML = '<span class="text-muted">No text could be extracted from this PDF. It may be a scanned image or have no selectable text.</span>';
                                }
                            }
                            if (parsedPreviewTextUpload) parsedPreviewTextUpload.value = trimmedText;
                        });
                    })
                    .catch(function(error) {
                        if (pdfExtractedTextUpload) pdfExtractedTextUpload.innerHTML = '<span class="text-danger">Error reading PDF: ' + error.message + '</span>';
                        if (wordCountUpload) wordCountUpload.textContent = 'Error';
                        if (parsedPreviewTextUpload) parsedPreviewTextUpload.value = '';
                    });
            };
            reader.readAsArrayBuffer(file);
        } else {
            if (pdfPreviewSectionUpload) pdfPreviewSectionUpload.style.display = 'block';
            if (pdfExtractedTextUpload) pdfExtractedTextUpload.innerHTML = '<span class="text-warning">Text extraction only available for PDF files.</span>';
            if (wordCountUpload) wordCountUpload.textContent = 'N/A';
            if (pageCountUpload) pageCountUpload.textContent = 'N/A';
            if (charCountUpload) charCountUpload.textContent = 'N/A';
            if (parsedPreviewTextUpload) parsedPreviewTextUpload.value = '';
        }
    });

    function setupInlinePdfPreview(fileInput) {
        if (!fileInput) return;
        const form = fileInput.closest('.inline-edit-form');
        if (!form) return;
        const previewField = form.querySelector('.parsed-preview-text-inline');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!previewField) return;
            previewField.value = '';
            if (!file || (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf'))) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                const typedarray = new Uint8Array(event.target.result);
                pdfjsLib.getDocument(typedarray).promise
                    .then(function(pdf) {
                        const numPages = pdf.numPages;
                        let fullText = '';
                        const promises = [];

                        for (let i = 1; i <= numPages; i++) {
                            promises.push(pdf.getPage(i).then(function(page) {
                                return page.getTextContent().then(function(textContent) {
                                    const pageText = textContent.items.map(item => item.str).join(' ');
                                    fullText += pageText + '\n';
                                });
                            }));
                        }

                        return Promise.all(promises).then(function() {
                            return fullText.trim();
                        });
                    })
                    .then(function(trimmedText) {
                        previewField.value = trimmedText;
                    })
                    .catch(function() {
                        previewField.value = '';
                    });
            };
            reader.readAsArrayBuffer(file);
        });
    }

    function setupAllInlinePdfPreviews() {
        document.querySelectorAll('.inline-edit-form input[type="file"]').forEach(setupInlinePdfPreview);
    }

    function setupMonthPdfPreviews() {
        document.querySelectorAll('.month-upload-form .month-file-input').forEach(function(fileInput) {
            if (fileInput.dataset.pdfPreviewSetup === 'true') {
                return;
            }
            fileInput.dataset.pdfPreviewSetup = 'true';

            const form = fileInput.closest('.month-upload-form');
            const previewSection = form.querySelector('.month-pdf-preview');
            const extractedTextDiv = form.querySelector('.pdf-extracted-text');
            const pageCountSpan = form.querySelector('.pdf-page-count');
            const charCountSpan = form.querySelector('.pdf-char-count');
            const wordCountSpan = form.querySelector('.pdf-word-count');
            const previewField = form.querySelector('.parsed-preview-text-month');

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    if (previewSection) previewSection.style.display = 'none';
                    if (previewField) previewField.value = '';
                    return;
                }

                if (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf')) {
                    if (previewSection) previewSection.style.display = 'block';
                    if (extractedTextDiv) extractedTextDiv.innerHTML = '<span class="text-muted">Extracting text from PDF...</span>';
                    if (wordCountSpan) wordCountSpan.textContent = 'Loading...';
                    if (previewField) previewField.value = '';

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const typedarray = new Uint8Array(event.target.result);
                        pdfjsLib.getDocument(typedarray).promise
                            .then(function(pdf) {
                                const numPages = pdf.numPages;
                                if (pageCountSpan) pageCountSpan.textContent = numPages;
                                let fullText = '';
                                const promises = [];

                                for (let i = 1; i <= numPages; i++) {
                                    promises.push(pdf.getPage(i).then(function(page) {
                                        return page.getTextContent().then(function(textContent) {
                                            const pageText = textContent.items.map(item => item.str).join(' ');
                                            fullText += pageText + '\n';
                                        });
                                    }));
                                }

                                return Promise.all(promises).then(function() {
                                    return fullText.trim();
                                });
                            })
                            .then(function(trimmedText) {
                                const wordCount = trimmedText.length > 0 ? trimmedText.split(/\s+/).length : 0;
                                const charCount = trimmedText.length;
                                if (wordCountSpan) wordCountSpan.textContent = wordCount + ' words';
                                if (charCountSpan) charCountSpan.textContent = charCount;
                                if (extractedTextDiv) {
                                    if (trimmedText.length > 0) {
                                        extractedTextDiv.textContent = trimmedText.substring(0, 1000) + (trimmedText.length > 1000 ? '...' : '');
                                    } else {
                                        extractedTextDiv.innerHTML = '<span class="text-muted">No text could be extracted from this PDF. It may be a scanned image or have no selectable text.</span>';
                                    }
                                }
                                if (previewField) previewField.value = trimmedText;
                            })
                            .catch(function(error) {
                                if (extractedTextDiv) extractedTextDiv.innerHTML = '<span class="text-danger">Error reading PDF: ' + error.message + '</span>';
                                if (wordCountSpan) wordCountSpan.textContent = 'Error';
                                if (previewField) previewField.value = '';
                            });
                    };
                    reader.readAsArrayBuffer(file);
                } else {
                    if (previewSection) previewSection.style.display = 'block';
                    if (extractedTextDiv) extractedTextDiv.innerHTML = '<span class="text-warning">Text extraction only available for PDF files.</span>';
                    if (wordCountSpan) wordCountSpan.textContent = 'N/A';
                    if (pageCountSpan) pageCountSpan.textContent = 'N/A';
                    if (charCountSpan) charCountSpan.textContent = 'N/A';
                    if (previewField) previewField.value = '';
                }
            });
        });
    }

    setupAllInlinePdfPreviews();
    setupMonthPdfPreviews();

    // ============================================
    // DRAG AND DROP FOR UPLOAD AREA
    // ============================================

    const uploadArea = document.getElementById('uploadArea');
    const uploadFileInput = document.getElementById('uploadFileInput');

    if (uploadArea) {
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#4361ee';
            uploadArea.style.background = '#f0f4ff';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#d1d5db';
            uploadArea.style.background = '#fafbfc';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            if (e.dataTransfer.files.length > 0) {
                uploadFileInput.files = e.dataTransfer.files;
                uploadFileInput.dispatchEvent(new Event('change'));
            }
        });

        uploadArea.addEventListener('click', () => {
            uploadFileInput.click();
        });
    }

    // ============================================
    // MONTH TAB RENDERING
    // ============================================

    function renderTabs(months) {
        if (!monthTabsContainer) return;
        monthTabsContainer.innerHTML = '';

        months.forEach((month, index) => { 
            const monthCarbon = new Date(`${month}-01`); 
            const monthLabel = monthCarbon.toLocaleString('default', { month: 'short' });  
            const yearLabel = monthCarbon.getFullYear(); 
            const isActive = index === 0; 
            const isCompleted = completedMonths.includes(month);    

            const button = document.createElement('button');
            button.className = `month-tab ${isActive ? 'active' : ''}`;
            button.dataset.month = month;
            button.innerHTML = `
                <span class="status-dot ${isCompleted ? 'uploaded' : 'missing'}"></span>
                <span class="month-short">${monthLabel}</span>
                <span class="month-year">${yearLabel}</span>
                <span class="month-badge ${isCompleted ? 'uploaded' : 'missing'}">${isCompleted ? '✓' : '+'}</span>
            `;

            button.addEventListener('click', function() {
                document.querySelectorAll('.month-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                const pane = document.querySelector(`.tab-pane[data-month="${month}"]`);
                if (pane) pane.classList.add('active');
                setMonthEditFormForMonth(month);
            });

            monthTabsContainer.appendChild(button);
        });
    }

 function renderPanes(months) {
    // Disabled - using Blade template rendering instead to avoid duplicates
}
    // ============================================
    // REBUILD MONTHS
    // ============================================

    function rebuildMonths() {
        if (!monthTabsContainer || !tabContentWrapper) return;
        const startDateValue = startDateInput?.value;
        const endDateValue = endDateInput?.value;
        const start = new Date(startDateValue);
        const end = new Date(endDateValue);

        if (isNaN(start.getTime()) || isNaN(end.getTime()) || start > end) {
            monthTabsContainer.innerHTML = '<div class="text-muted small">Please select a valid start and end date range.</div>';
            tabContentWrapper.innerHTML = '';
            return;
        }

        const months = getMonthsBetween(start, end);
        renderTabs(months);
        // renderPanes(months);
        setMonthEditFormForMonth(months[0]);

        // Attach inline edit handlers after rendering
        setTimeout(attachInlineEditHandlers, 100);
    }

    // ============================================
    // INITIALIZATION
    // ============================================

if (displayMode === 'monthly') {
    startDateInput?.addEventListener('change', rebuildMonths);
    endDateInput?.addEventListener('change', rebuildMonths);
    rebuildMonths();
    
    // Setup upload forms for empty months
    setTimeout(setupMonthUploadForm, 200);
} else {
    // For semester mode, just attach edit handlers
    setTimeout(attachInlineEditHandlers, 100);
}
    // Attach inline edit handlers when tabs change
    document.addEventListener('click', function(e) {
        if (e.target.closest('.month-tab')) {
            setTimeout(attachInlineEditHandlers, 200);
        }
    });
});
</script>

@endsection