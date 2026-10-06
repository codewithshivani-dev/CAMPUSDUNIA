@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<!-- Bootstrap Icons CDN -->
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
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
        --subject-main-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --subject-sub-gradient: linear-gradient(135deg, #10b981, #059669);
    }

    body {
        background-color: #f8fafc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .view-container {
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    /* Page Header */
    .view-header {
        background: var(--primary-gradient);
        color: white;
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .view-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .view-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .view-header h4 i {
        font-size: 28px;
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 12px;
    }

    .view-header .btn-light {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(5px);
        transition: all 0.3s;
        position: relative;
        z-index: 1;
        border: none;
    }

    .view-header .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Content Area */
    .view-content {
        padding: 30px;
    }

    /* Card Sections */
    .view-section {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        margin-bottom: 25px;
        overflow: hidden;
        transition: all 0.3s;
        background: white;
    }

    .view-section:hover {
        border-color: var(--primary-color);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1);
    }

    .section-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 16px 20px;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-header i {
        font-size: 20px;
        background: white;
        padding: 8px;
        border-radius: 10px;
        color: var(--primary-color);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .section-body {
        padding: 25px;
        background: white;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .info-label i {
        color: var(--primary-color);
        font-size: 14px;
    }

    .info-value {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 12px 16px;
        border-left: 4px solid var(--primary-color);
        border-radius: 10px;
        font-weight: 500;
        color: #1e293b;
        transition: all 0.3s;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        font-size: 15px;
        word-break: break-word;
    }

    .info-value:hover {
        transform: translateX(5px);
        border-left-color: var(--secondary-color);
    }

    /* Subject Cards Grid */
    .subjects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 10px;
    }

    .subject-card {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .subject-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
    }

    .subject-card.main-subject {
        border-left: 4px solid var(--primary-color);
    }

    .subject-card.sub-subject {
        border-left: 4px solid var(--success-color);
    }

    .subject-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, transparent, rgba(67, 97, 238, 0.05));
        border-radius: 0 16px 0 50%;
    }

    .subject-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .subject-icon.main-icon {
        background: var(--subject-main-gradient);
        color: white;
    }

    .subject-icon.sub-icon {
        background: var(--subject-sub-gradient);
        color: white;
    }

    .subject-icon i {
        font-size: 20px;
    }

    .subject-title {
        font-weight: 700;
        font-size: 18px;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .subject-subtitle {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .subject-detail {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        font-size: 13px;
        color: #475569;
    }

    .subject-detail i {
        color: var(--primary-color);
        font-size: 14px;
        width: 20px;
    }

    .subject-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-main {
        background: var(--subject-main-gradient);
        color: white;
    }

    .badge-sub {
        background: var(--subject-sub-gradient);
        color: white;
    }

    .badge-semester {
        background: linear-gradient(135deg, #f3e8ff, #e9d5ff);
        color: #6b21a5;
        border: 1px solid #d8b4fe;
    }

    /* Stats Card */
    .stats-card {
        background: var(--primary-gradient);
        color: white;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .stats-icon {
        background: rgba(255, 255, 255, 0.2);
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stats-icon i {
        font-size: 30px;
        color: white;
    }

    .stats-content {
        text-align: right;
    }

    .stats-label {
        font-size: 14px;
        opacity: 0.8;
        margin-bottom: 5px;
    }

    .stats-number {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state-icon {
        font-size: 64px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #64748b;
    }

    /* No Data Message */
    .no-data-message {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 30px;
        text-align: center;
    }

    .no-data-message i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .no-data-message p {
        color: #64748b;
        font-size: 16px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .view-container {
            margin: 15px;
        }

        .view-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
        }

        .view-header h4 {
            font-size: 20px;
        }

        .view-content {
            padding: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .subjects-grid {
            grid-template-columns: 1fr;
        }

        .stats-card {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .stats-content {
            text-align: center;
        }

        .section-body {
            padding: 20px;
        }
    }

    @media (max-width: 480px) {
        .view-header h4 {
            font-size: 18px;
        }
    }
</style>

<div class="view-container">

    <!-- HEADER -->
    <div class="view-header">
        <h4>
            <i class="bi bi-journal-text"></i>
            <span>Assignment Details</span>
        </h4>

        <button onclick="history.back()" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left"></i> Back to List
        </button>
    </div>

    <div class="view-content">

        <!-- ================= STUDENT & COURSE DETAILS ================= -->
        <div class="view-section">
            <div class="section-header">
                <i class="bi bi-person-badge-fill"></i>
                Student & Course Details
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-person-fill"></i> Student Name</div>
                        <div class="info-value">{{ trim(($student->first_name ?? '').' '.($student->middle_name ?? '').' '.($student->last_name ?? '')) ?: '-' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-tag-fill"></i> Course Type</div>
                        <div class="info-value">{{ $course->course_type ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-diagram-3-fill"></i> Sub Type</div>
                        <div class="info-value">{{ $course->sub_type ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-journal-check"></i> Total Subjects</div>
                        <div class="info-value">
                            <div class="stats-number" style="font-size: 24px; background: none; padding: 0;">
                                {{ count($mainSubjects) + count($subSubjects) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SUBJECT DETAILS ================= -->
        <div class="view-section">
            <div class="section-header">
                <i class="bi bi-journal-bookmark-fill"></i>
                Subject Details
            </div>
            <div class="section-body">
                @if(count($mainSubjects) > 0 || count($subSubjects) > 0)
                    <div class="subjects-grid">
                        @if(count($mainSubjects) > 0)
                            @foreach($mainSubjects as $subject)
                                <div class="subject-card main-subject">
                                    <div class="subject-icon main-icon">
                                        <i class="bi bi-book-fill"></i>
                                    </div>
                                    <div class="subject-title">{{ $subject['subject_name'] }}</div>
                                    <div class="subject-subtitle">
                                        <span class="subject-badge badge-main">Main Subject</span>
                                    </div>
                                    
                                    <div class="subject-detail">
                                        <i class="bi bi-calendar-range-fill"></i>
                                        <span>
                                            @if($subject['semester_id'] === 'all_semesters')
                                                <span class="subject-badge badge-semester">All Semesters</span>
                                            @else
                                                Semester: {{ $subject['semester_id'] }}
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="subject-detail">
                                        <i class="bi bi-calendar-plus-fill"></i>
                                        <span>Assigned: {{ \Carbon\Carbon::parse($subject['assigned_date'])->format('d M Y') }}</span>
                                    </div>
                                    
                                    @if(isset($subject['subject_id']))
                                    <div class="subject-detail">
                                        <i class="bi bi-upc-scan"></i>
                                        <span class="text-muted" style="font-size: 11px;">ID: {{ $subject['subject_id'] }}</span>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        @if(count($subSubjects) > 0)
                            @foreach($subSubjects as $subject)
                                <div class="subject-card sub-subject">
                                    <div class="subject-icon sub-icon">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <div class="subject-title">{{ $subject['sub_subject_name'] }}</div>
                                    <div class="subject-subtitle">
                                        <span class="subject-badge badge-sub">Sub Subject</span>
                                    </div>
                                    
                                    <div class="subject-detail">
                                        <i class="bi bi-book-fill"></i>
                                        <span>Parent: {{ $subject['subject_name'] }}</span>
                                    </div>
                                    
                                    <div class="subject-detail">
                                        <i class="bi bi-calendar-plus-fill"></i>
                                        <span>Assigned: {{ \Carbon\Carbon::parse($subject['assigned_date'])->format('d M Y') }}</span>
                                    </div>
                                    
                                    @if(isset($subject['sub_subject_id']))
                                    <div class="subject-detail">
                                        <i class="bi bi-upc-scan"></i>
                                        <span class="text-muted" style="font-size: 11px;">ID: {{ $subject['sub_subject_id'] }}</span>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="no-data-message">
                        <i class="bi bi-journal-x"></i>
                        <p>No subjects have been assigned to this student yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ================= NO DATA MESSAGE (if no subjects) ================= -->
        @if(count($mainSubjects) == 0 && count($subSubjects) == 0)
        <div class="view-section">
            <div class="section-header">
                <i class="bi bi-info-circle-fill"></i>
                No Data Available
            </div>
            <div class="section-body">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-journal-x"></i>
                    </div>
                    <h5>No Subjects Assigned</h5>
                    <p>This student hasn't been assigned any subjects yet.</p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection