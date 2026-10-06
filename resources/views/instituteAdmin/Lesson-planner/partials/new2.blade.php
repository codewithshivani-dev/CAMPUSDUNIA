@extends('instituteAdmin.Lesson-planner.index')

@section('styles')
<style>
    .create-page {
        padding: 28px 32px;
        background: #f0f4f9;
    }

    .create-card {
        background: white;
        border-radius: 28px;
        padding: 32px 36px;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.06);
        max-width: 1200px;
        margin: 0 auto;
        border: 1px solid rgba(37, 99, 235, 0.06);
    }

    .page-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 6px;
        color: #0a1e3c;
    }
    .page-title i { color: #2563eb; margin-right: 12px; }

    .page-subtitle {
        color: #4b6a8b;
        font-size: 15px;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .page-subtitle i { color: #2563eb; }

    .form-section {
        margin-bottom: 28px;
        padding-bottom: 28px;
        border-bottom: 1px solid #eef2f6;
    }
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #0a1e3c;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title i { color: #2563eb; }
    .section-title .count {
        font-size: 13px;
        color: #4b6a8b;
        font-weight: 400;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px 24px;
    }
    .form-group { margin-bottom: 4px; }
    .form-group-full { grid-column: 1 / -1; }
    .form-label {
        display: block;
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 6px;
        color: #0a1e3c;
    }
    .form-label i { color: #2563eb; margin-right: 6px; width: 18px; }
    .form-required { color: #ef4444; }
    .form-hint {
        font-size: 12px;
        color: #8a9bb5;
        margin-top: 4px;
    }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 20px;
        margin-top: 4px;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
    }
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #2563eb;
        cursor: pointer;
    }
    textarea {
        min-height: 80px;
        resize: vertical;
        width: 100%;
    }

    select, input[type="text"], input[type="url"], input[type="date"], input[type="number"], textarea {
        padding: 10px 16px;
        border: 1.5px solid #dce4ed;
        border-radius: 40px;
        font-size: 14px;
        background: #fafcff;
        font-family: inherit;
        outline: none;
        transition: 0.2s;
        width: 100%;
    }
    select:focus, input:focus, textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        background: white;
    }
    input[type="date"] { cursor: pointer; }
    input[type="number"] {
        -moz-appearance: textfield;
        width: 120px;
        text-align: center;
        font-weight: 700;
        font-size: 20px;
        padding: 8px 12px;
    }
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .success-message {
        background: #d1fae5;
        color: #0b6e4f;
        padding: 14px 22px;
        border-radius: 60px;
        display: none;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .success-message i { margin-right: 8px; }
    .success-message.show { display: block; }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #eef2f6;
    }

    .btn {
        border: none;
        padding: 10px 24px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        background: #eef2f6;
        color: #1f334f;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn i { font-size: 14px; }
    .btn-primary {
        background: #2563eb;
        color: white;
    }
    .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
    .btn-success {
        background: #10b981;
        color: white;
    }
    .btn-success:hover { background: #059669; transform: translateY(-1px); }
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    .btn-danger:hover { background: #dc2626; transform: translateY(-1px); }
    .btn-secondary {
        background: #e6ecf3;
        color: #1f334f;
    }
    .btn-secondary:hover { background: #d1d9e6; transform: translateY(-1px); }
    .btn-small { padding: 6px 14px; font-size: 12px; }

    .plan-level-selector {
        display: flex;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .plan-level-btn {
        padding: 12px 28px;
        border-radius: 60px;
        border: 2px solid #dce4ed;
        background: white;
        cursor: pointer;
        font-weight: 600;
        font-size: 15px;
        color: #4b6a8b;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        justify-content: center;
        min-width: 150px;
    }
    .plan-level-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f0f4ff;
        transform: translateY(-2px);
    }
    .plan-level-btn.active {
        border-color: #2563eb;
        background: #2563eb;
        color: white;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        transform: translateY(-2px);
    }
    .plan-level-btn .icon { font-size: 20px; }

    .duration-section {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        border: 1.5px solid #dce4ed;
    }

    .duration-input-group {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .duration-input-group .unit-label {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 16px;
        min-width: 60px;
    }
    .duration-input-group .total-days-info {
        background: #2563eb;
        color: white;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-left: 8px;
    }

    .preset-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #e6ecf3;
    }
    .preset-btn {
        padding: 6px 16px;
        border-radius: 20px;
        border: 1.5px solid #dce4ed;
        background: white;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        color: #4b6a8b;
        transition: 0.2s;
    }
    .preset-btn:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f0f4ff;
        transform: translateY(-2px);
    }
    .preset-btn.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    .preset-btn.popular {
        background: #fef3c7;
        border-color: #f59e0b;
        color: #92400e;
    }
    .preset-btn.popular:hover {
        background: #f59e0b;
        color: white;
    }
    .preset-btn.popular.active {
        background: #f59e0b;
        color: white;
        border-color: #f59e0b;
    }

    .date-range-display {
        background: linear-gradient(135deg, #f0f4ff 0%, #e8edf5 100%);
        padding: 16px 24px;
        border-radius: 16px;
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        border: 1.5px solid #dbeafe;
    }
    .date-range-display .range-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .date-range-display .range-item .label {
        font-size: 13px;
        color: #4b6a8b;
    }
    .date-range-display .range-item .value {
        font-weight: 700;
        color: #1d4ed8;
        font-size: 15px;
    }
    .date-range-display .range-arrow {
        color: #8a9bb5;
        font-size: 20px;
    }
    .date-range-display .duration-badge {
        background: #2563eb;
        color: white;
        padding: 4px 20px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    .date-range-display .days-count {
        color: #4b6a8b;
        font-size: 14px;
        font-weight: 500;
    }
    .date-range-display .weekday-info {
        font-size: 12px;
        color: #8a9bb5;
    }

    .level-badge {
        display: inline-block;
        padding: 2px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 8px;
    }
    .level-badge.day { background: #dbeafe; color: #1d4ed8; }
    .level-badge.week { background: #d1fae5; color: #0b6e4f; }

    .topic-row {
        border: 1.5px solid #dce4ed;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 14px;
        background: #fafcff;
        transition: all 0.2s;
    }
    .topic-row:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.06);
    }
    .topic-row .topic-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }
    .topic-row .topic-number {
        font-weight: 700;
        font-size: 16px;
        color: #2563eb;
    }
    .topic-row .topic-number i { margin-right: 8px; }
    .topic-row .topic-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .topic-row .topic-fields-full { grid-column: 1 / -1; }
    .topic-row .topic-field-label {
        font-size: 13px;
        font-weight: 500;
        color: #4b6a8b;
        display: block;
        margin-bottom: 4px;
    }
    .topic-row .topic-field-label i { color: #2563eb; margin-right: 4px; }

    .video-preview {
        margin-top: 10px;
        padding: 8px 12px;
        background: #eef2f6;
        border-radius: 12px;
        display: none;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #4b6a8b;
    }
    .video-preview.show { display: flex; }
    .video-preview .video-thumb {
        width: 120px;
        height: 68px;
        border-radius: 6px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .video-preview .video-thumb iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .video-preview .video-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .video-preview .video-info .label {
        font-size: 11px;
        color: #8a9bb5;
    }
    .video-preview .video-info .link {
        color: #2563eb;
        word-break: break-all;
    }

    .file-list { margin-top: 8px; }
    .file-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: white;
        border-radius: 8px;
        margin-bottom: 4px;
        font-size: 13px;
        border: 1px solid #e6ecf3;
    }
    .file-item .file-name { flex: 1; }
    .file-item .file-size {
        color: #8a9bb5;
        font-size: 11px;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px;
        margin-top: 12px;
        padding: 12px 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid #dce4ed;
    }
    .quick-stats .stat-item { text-align: center; }
    .quick-stats .stat-item .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: #2563eb;
    }
    .quick-stats .stat-item .stat-label {
        font-size: 11px;
        color: #8a9bb5;
        margin-top: 2px;
    }

    .plan-level-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1.5px solid #eef2f6;
        margin-top: 4px;
    }

    .syllabus-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px 24px;
        border: 2px solid #2563eb;
        margin-top: 4px;
        position: relative;
    }

    .syllabus-container::before {
        content: "📚 SYLLABUS PLANNER";
        position: absolute;
        top: -12px;
        left: 20px;
        background: #2563eb;
        color: white;
        padding: 2px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .syllabus-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        background: white;
        border-radius: 12px;
        margin-bottom: 8px;
        border: 1px solid #e6ecf3;
        cursor: pointer;
        transition: all 0.2s;
    }
    .syllabus-item:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.08);
        transform: translateX(4px);
    }
    .syllabus-item.selected {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.15);
    }
    .syllabus-item .syllabus-check {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid #dce4ed;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: 0.2s;
        background: white;
    }
    .syllabus-item.selected .syllabus-check {
        background: #2563eb;
        border-color: #2563eb;
        color: white;
    }
    .syllabus-item .syllabus-info {
        flex: 1;
    }
    .syllabus-item .syllabus-info .title {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 14px;
    }
    .syllabus-item .syllabus-info .meta {
        font-size: 12px;
        color: #8a9bb5;
        margin-top: 2px;
    }
    .syllabus-item .syllabus-badge {
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 20px;
        background: #dbeafe;
        color: #1d4ed8;
        font-weight: 600;
    }
    .syllabus-item .syllabus-chapters {
        font-size: 11px;
        color: #4b6a8b;
        padding: 2px 10px;
        background: #fef3c7;
        border-radius: 20px;
    }

    .distribute-btn {
        margin-top: 12px;
        padding: 10px 24px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 40px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    .distribute-btn:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .distribute-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .topic-distribution {
        margin-top: 16px;
        padding: 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid #dce4ed;
        max-height: 300px;
        overflow-y: auto;
    }
    .topic-distribution .dist-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 6px 12px;
        border-bottom: 1px solid #f0f4f9;
    }
    .topic-distribution .dist-item:last-child {
        border-bottom: none;
    }
    .topic-distribution .dist-day {
        font-weight: 600;
        color: #2563eb;
        min-width: 100px;
        font-size: 13px;
    }
    .topic-distribution .dist-topic {
        flex: 1;
        font-size: 13px;
        color: #0a1e3c;
    }
    .topic-distribution .dist-count {
        font-size: 11px;
        color: #8a9bb5;
        background: #eef2f6;
        padding: 2px 10px;
        border-radius: 20px;
    }

    .syllabus-empty {
        text-align: center;
        padding: 30px;
        color: #8a9bb5;
    }
    .syllabus-empty i {
        font-size: 48px;
        color: #2563eb;
        margin-bottom: 12px;
        display: block;
    }

    .syllabus-detail {
        font-size: 12px;
        color: #4b6a8b;
        padding: 8px 12px;
        background: #f8fafc;
        border-radius: 8px;
        margin-top: 4px;
        border-left: 3px solid #2563eb;
    }

    @media (max-width: 820px) {
        .create-page { padding: 16px; }
        .create-card { padding: 20px; }
        .form-grid { grid-template-columns: 1fr; }
        .topic-row .topic-fields { grid-template-columns: 1fr; }
        .action-buttons { flex-direction: column; }
        .action-buttons .btn { justify-content: center; }
        .plan-level-selector { flex-direction: column; }
        .plan-level-btn { min-width: auto; }
        .date-range-display {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
        .date-range-display .range-arrow { transform: rotate(90deg); }
        .duration-input-group {
            flex-direction: column;
            align-items: stretch;
        }
        .duration-input-group input[type="number"] { width: 100%; }
        .preset-buttons { justify-content: center; }
        .quick-stats { grid-template-columns: 1fr 1fr; }
        .plan-level-container { padding: 16px; }
        .syllabus-container { padding: 16px; }
        .syllabus-container::before { left: 10px; font-size: 10px; }
    }
</style>
@endsection

@section('content')
<div class="create-page">
    <div class="create-card">
        <h2 class="page-title"><i class="fas fa-plus-circle"></i> Create Lesson Plan</h2>
        <p class="page-subtitle">
            <i class="fas fa-info-circle"></i> Select a syllabus to automatically distribute chapter topics across days or weeks.
        </p>

        <div class="success-message" id="successMessage"><i class="fas fa-check-circle"></i> Lesson plan saved successfully!</div>

        <form id="lessonForm" onsubmit="saveLessonPlan(event)">
            <!-- ===== COURSE INFORMATION ===== -->
            <div class="form-section">
                <div class="section-title"><i class="fas fa-info-circle"></i> Course Information</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-graduation-cap"></i> Course Type <span class="form-required">*</span></label>
                        <select id="formCourseType" required>
                            <option value="">Select Course Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Advanced">Advanced</option>
                            <option value="Remedial">Remedial</option>
                            <option value="Enrichment">Enrichment</option>
                            <option value="Summer Course">Summer Course</option>
                            <option value="Winter Course">Winter Course</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-layer-group"></i> Course Sub Type <span class="form-required">*</span></label>
                        <select id="formCourseSubType" required>
                            <option value="">Select Course Sub Type</option>
                            <option value="Theory">Theory</option>
                            <option value="Practical">Practical</option>
                            <option value="Lab">Lab</option>
                            <option value="Workshop">Workshop</option>
                            <option value="Seminar">Seminar</option>
                            <option value="Tutorial">Tutorial</option>
                            <option value="Project">Project</option>
                            <option value="Internship">Internship</option>
                            <option value="Field Work">Field Work</option>
                            <option value="Research">Research</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-calendar-alt"></i> Academic Year <span class="form-required">*</span></label>
                        <select id="formAcademicYear" required>
                            <option value="">Select Academic Year</option>
                            <option value="2025-2026">2025-2026</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2022-2023">2022-2023</option>
                            <option value="2021-2022">2021-2022</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-book"></i> Subject <span class="form-required">*</span></label>
                        <select id="formSubject" required onchange="loadSyllabi()">
                            <option value="">Select Subject</option>
                            <option value="Physics">Physics</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Biology">Biology</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="English">English</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="History">History</option>
                            <option value="Geography">Geography</option>
                            <option value="Economics">Economics</option>
                            <option value="Business Studies">Business Studies</option>
                            <option value="Physical Education">Physical Education</option>
                            <option value="Art">Art</option>
                            <option value="Music">Music</option>
                            <option value="Foreign Language">Foreign Language</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-chalkboard-teacher"></i> Teacher <span class="form-required">*</span></label>
                        <select id="formTeacher" required>
                            <option value="">Select Teacher</option>
                            <option value="Mr. John Smith">Mr. John Smith</option>
                            <option value="Ms. Sarah Johnson">Ms. Sarah Johnson</option>
                            <option value="Dr. Robert Brown">Dr. Robert Brown</option>
                            <option value="Mrs. Emily Davis">Mrs. Emily Davis</option>
                            <option value="Mr. James Wilson">Mr. James Wilson</option>
                            <option value="Ms. Maria Garcia">Ms. Maria Garcia</option>
                            <option value="Dr. David Lee">Dr. David Lee</option>
                            <option value="Mrs. Linda Martinez">Mrs. Linda Martinez</option>
                        </select>
                    </div>

                    <div class="form-group form-group-full">
                        <div class="plan-level-container">
                            <label class="form-label"><i class="fas fa-tag"></i> Plan Type <span class="form-required">*</span></label>
                            <div class="plan-level-selector">
                                <button type="button" class="plan-level-btn active" data-level="day" onclick="selectPlanLevel('day')">
                                    <span class="icon">📅</span>
                                    Day Plan
                                </button>
                                <button type="button" class="plan-level-btn" data-level="week" onclick="selectPlanLevel('week')">
                                    <span class="icon">📋</span>
                                    Week Plan
                                </button>
                            </div>
                            <div style="font-size:13px; color:#4b6a8b; padding:6px 4px 0 4px;">
                                <i class="fas fa-lightbulb" style="color:#2563eb;"></i>
                                <span id="level-description">📌 Plan for 1 day with detailed topics and activities</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <div class="duration-section">
                            <label class="form-label"><i class="fas fa-hourglass-half"></i> Duration <span class="form-required">*</span></label>
                            <div class="duration-input-group">
                                <input type="number" id="formDuration" value="1" min="1" max="365" required />
                                <span class="unit-label" id="durationUnitLabel">Day(s)</span>
                                <span class="total-days-info" id="totalDaysDisplay"><i class="fas fa-calendar-day"></i> = 1 day</span>
                            </div>
                            <div class="preset-buttons" id="presetButtons"></div>
                            <div class="form-hint" id="durationHint"><i class="fas fa-info-circle"></i> Enter the number of days for your lesson plan</div>
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label"><i class="fas fa-calendar-alt"></i> Start Date <span class="form-required">*</span></label>
                        <input type="date" id="formStartDate" required />
                        <div class="form-hint"><i class="fas fa-info-circle"></i> Select the start date for your lesson plan</div>
                    </div>

                    <div class="form-group form-group-full">
                        <div class="date-range-display" id="dateRangeDisplay">
                            <div class="range-item">
                                <span class="label"><i class="fas fa-play"></i> Start:</span>
                                <span class="value" id="startDateDisplay">-</span>
                            </div>
                            <span class="range-arrow">➜</span>
                            <div class="range-item">
                                <span class="label"><i class="fas fa-stop"></i> End:</span>
                                <span class="value" id="endDateDisplay">-</span>
                            </div>
                            <span class="range-arrow">|</span>
                            <div class="range-item">
                                <span class="duration-badge" id="durationDisplay"><i class="fas fa-tag"></i> Day Plan</span>
                            </div>
                            <span class="days-count" id="daysCountDisplay"></span>
                        </div>
                        <div class="weekday-info" id="weekdayInfo" style="margin-top:6px; font-size:12px; color:#8a9bb5;">
                            <i class="fas fa-info-circle"></i> Select a start date to see the range
                        </div>
                    </div>

                    <div class="form-group form-group-full">
                        <label class="form-label"><i class="fas fa-heading"></i> Plan Title (optional)</label>
                        <input type="text" id="formTitle" placeholder="e.g. Physics - Semester 1" />
                        <div class="form-hint"><i class="fas fa-info-circle"></i> If left blank, title will be auto-generated</div>
                    </div>
                </div>
            </div>

            <!-- ===== SYLLABUS SELECTION ===== -->
            <div class="form-section">
                <div class="section-title"><i class="fas fa-book-open"></i> Syllabus Selection & Distribution</div>
                <div class="syllabus-container">
                    <p style="color: #4b6a8b; font-size: 14px; margin-bottom: 16px; margin-top: 8px;">
                        <i class="fas fa-info-circle" style="color: #2563eb;"></i>
                        Select a syllabus. Topics from each chapter will be automatically distributed across your plan.
                    </p>
                    
                    <div id="subjectInfo" style="display:none; background: #dbeafe; padding: 8px 16px; border-radius: 12px; margin-bottom: 12px;">
                        <span id="subjectDisplay" style="font-weight: 600; color: #1d4ed8;"></span>
                    </div>

                    <div id="syllabusList">
                        <div class="syllabus-empty">
                            <i class="fas fa-book"></i>
                            <div>Please select a subject first to view available syllabi</div>
                        </div>
                    </div>

                    <div style="margin-top: 16px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                        <button type="button" class="btn btn-success btn-small" id="distributeBtn" onclick="distributeSyllabus()" disabled>
                            <i class="fas fa-arrow-right"></i> Distribute Topics
                        </button>
                        <button type="button" class="btn btn-secondary btn-small" onclick="clearSyllabusSelection()" id="clearBtn" style="display:none;">
                            <i class="fas fa-times"></i> Clear Selection
                        </button>
                        <span id="selectedInfo" style="font-size:13px; color:#4b6a8b;"></span>
                    </div>

                    <div id="distributionResult" class="topic-distribution" style="display:none; margin-top:16px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap;">
                            <div style="font-weight:600; color:#0a1e3c;">
                                <i class="fas fa-list-check" style="color:#2563eb;"></i> Topic Distribution Preview
                            </div>
                            <div>
                                <span id="distTotalTopics" style="font-size:12px; color:#4b6a8b;"></span>
                            </div>
                        </div>
                        <div id="distributedTopics"></div>
                        <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
                            <button type="button" class="btn btn-success btn-small" onclick="addDistributedTopics()">
                                <i class="fas fa-plus"></i> Add All to Plan
                            </button>
                            <button type="button" class="btn btn-secondary btn-small" onclick="hideDistribution()">
                                <i class="fas fa-times"></i> Close Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== QUICK STATS ===== -->
            <div class="form-section">
                <div class="section-title"><i class="fas fa-chart-bar"></i> Plan Summary</div>
                <div class="quick-stats">
                    <div class="stat-item">
                        <div class="stat-value" id="statDays">1</div>
                        <div class="stat-label"><i class="fas fa-calendar-day"></i> Total Days</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="statTopics">0</div>
                        <div class="stat-label"><i class="fas fa-list"></i> Topics in Plan</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="statWeeks">1</div>
                        <div class="stat-label"><i class="fas fa-calendar-week"></i> Weeks</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="statSyllabusTopics">0</div>
                        <div class="stat-label"><i class="fas fa-book"></i> Chapters</div>
                    </div>
                </div>
            </div>

            <!-- ===== LEARNING OBJECTIVES ===== -->
            <div class="form-section">
                <div class="section-title"><i class="fas fa-bullseye"></i> Learning Objectives</div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-list-check"></i> Learning Objectives <span class="form-required">*</span></label>
                    <textarea id="formObjectives" required placeholder="What should students be able to do by the end of this lesson?
Example:
- Understand the concepts of units and measurements
- Apply dimensional analysis to physical quantities
- Solve problems using SI units"></textarea>
                </div>
            </div>

            <!-- ===== TOPICS SECTION ===== -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-book"></i> Lesson Topics
                    <span class="count" id="topic-count">(0 topics)</span>
                    <span class="level-badge day" id="levelBadge">Day</span>
                </div>
                <div style="display:flex; gap:10px; margin-bottom:16px; flex-wrap:wrap;">
                    <button type="button" class="btn btn-primary btn-small" onclick="addTopic()"><i class="fas fa-plus"></i> Add Topic</button>
                    <button type="button" class="btn btn-secondary btn-small" onclick="addMultipleTopics()"><i class="fas fa-layer-group"></i> Add 3 Topics</button>
                    <button type="button" class="btn btn-secondary btn-small" onclick="generateTopicsFromDuration()"><i class="fas fa-magic"></i> Auto-generate</button>
                    <button type="button" class="btn btn-danger btn-small" onclick="clearAllTopics()"><i class="fas fa-trash"></i> Clear All</button>
                </div>
                <div id="topicContainer">
                    <!-- Topics will be added here -->
                </div>
                <div id="emptyTopicsMessage" style="text-align:center; padding:30px; color:#8a9bb5; border:2px dashed #dce4ed; border-radius:16px;">
                    <div style="font-size:40px; margin-bottom:8px;"><i class="fas fa-book-open" style="color:#2563eb;"></i></div>
                    <div>No topics added yet</div>
                    <div style="font-size:13px; margin-top:4px; color:#4b6a8b;">Add topics manually or use the syllabus distribution above</div>
                </div>
            </div>

            <!-- ===== TEACHING METHODS ===== -->
            <div class="form-section">
                <div class="section-title"><i class="fas fa-chalkboard-teacher"></i> Teaching Methods</div>
                <div class="form-group">
                    <div class="checkbox-group">
                        <div class="checkbox-item"><input type="checkbox" id="lecture" name="methods" value="Lecture" /><label for="lecture"><i class="fas fa-microphone"></i> Lecture</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="activity" name="methods" value="Activity" /><label for="activity"><i class="fas fa-puzzle-piece"></i> Activity</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="demonstration" name="methods" value="Demonstration" /><label for="demonstration"><i class="fas fa-flask"></i> Demonstration</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="discussion" name="methods" value="Discussion" /><label for="discussion"><i class="fas fa-comments"></i> Discussion</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="groupwork" name="methods" value="Group work" /><label for="groupwork"><i class="fas fa-users"></i> Group work</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="audiovisual" name="methods" value="Audio-visual" /><label for="audiovisual"><i class="fas fa-film"></i> Audio-visual</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="projectbased" name="methods" value="Project based" /><label for="projectbased"><i class="fas fa-project-diagram"></i> Project based</label></div>
                    </div>
                </div>
            </div>

            <!-- ===== TEACHING AIDS ===== -->
            <div class="form-section">
                <div class="section-title"><i class="fas fa-tools"></i> Teaching Aids</div>
                <div class="form-group">
                    <div class="checkbox-group">
                        <div class="checkbox-item"><input type="checkbox" id="textbook" name="aids" value="Textbook" /><label for="textbook"><i class="fas fa-book"></i> Textbook</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="projector" name="aids" value="Projector" /><label for="projector"><i class="fas fa-video"></i> Projector</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="smartboard" name="aids" value="Smart board" /><label for="smartboard"><i class="fas fa-tv"></i> Smart board</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="charts" name="aids" value="Charts / Models" /><label for="charts"><i class="fas fa-chart-pie"></i> Charts / Models</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="worksheets" name="aids" value="Worksheets" /><label for="worksheets"><i class="fas fa-file-alt"></i> Worksheets</label></div>
                        <div class="checkbox-item"><input type="checkbox" id="online" name="aids" value="Online resources" /><label for="online"><i class="fas fa-globe"></i> Online resources</label></div>
                    </div>
                </div>
            </div>

            <!-- ===== RESOURCES ===== -->
            <div class="form-section">
                <div class="section-title"><i class="fas fa-box"></i> Resources Required</div>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-list"></i> Resources</label>
                    <textarea id="formResources" placeholder="List any additional resources needed:
- Textbook
- Lab equipment
- Worksheets
- Multimedia resources"></textarea>
                </div>
            </div>

            <!-- ===== ACTION BUTTONS ===== -->
            <div class="action-buttons">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-save"></i> Save as Draft</button>
                <button type="button" class="btn btn-success" onclick="submitLessonPlan()"><i class="fas fa-paper-plane"></i> Save & Activate</button>
                <a href="{{ route('lesson-planner.plans') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    // ===== STATIC SYLLABUS DATA WITH CHAPTERS =====
    const allSyllabi = [
        // Physics
        {
            id: 1,
            subject: 'Physics',
            title: 'Physics - Class 11',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Units and Measurements - Need for measurement, SI units, fundamental and derived units, significant figures, dimensional analysis',
                'Chapter 2: Motion in a Straight Line - Frame of reference, uniform and non-uniform motion, velocity-time and position-time graphs, uniformly accelerated motion',
                'Chapter 3: Motion in a Plane - Scalar and vector quantities, position and displacement vectors, addition and subtraction of vectors, projectile motion, uniform circular motion',
                'Chapter 4: Laws of Motion - Newton\'s laws of motion, momentum, impulse, conservation of linear momentum, equilibrium of forces, friction, centripetal force',
                'Chapter 5: Work, Energy and Power - Work done, kinetic energy, potential energy, conservation of mechanical energy, power, collision'
            ],
            topics: [] // Will be populated from chapters
        },
        {
            id: 2,
            subject: 'Physics',
            title: 'Physics - Class 12',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Electric Charges and Fields - Electric charge, Coulomb\'s law, electric field, electric flux, Gauss\'s law',
                'Chapter 2: Electrostatic Potential and Capacitance - Electric potential, potential due to a point charge, capacitance, parallel plate capacitor',
                'Chapter 3: Current Electricity - Electric current, Ohm\'s law, resistance, resistivity, Kirchhoff\'s laws, Wheatstone bridge',
                'Chapter 4: Moving Charges and Magnetism - Magnetic field, Biot-Savart law, Ampere\'s law, force on a moving charge, torque on current loop',
                'Chapter 5: Magnetism and Matter - Magnetic dipole, magnetic field lines, earth\'s magnetism, magnetic properties'
            ],
            topics: []
        },
        // Chemistry
        {
            id: 3,
            subject: 'Chemistry',
            title: 'Chemistry - Class 11',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Some Basic Concepts of Chemistry - Matter, laws of chemical combination, mole concept, molar mass, percentage composition',
                'Chapter 2: Structure of Atom - Atomic models, wave nature of matter, Heisenberg uncertainty principle, quantum numbers, electronic configuration',
                'Chapter 3: Classification of Elements - Periodic table, periodic trends, atomic radius, ionization enthalpy, electron gain enthalpy, electronegativity',
                'Chapter 4: Chemical Bonding - Ionic bond, covalent bond, VSEPR theory, hybridization, molecular orbital theory, hydrogen bonding'
            ],
            topics: []
        },
        {
            id: 4,
            subject: 'Chemistry',
            title: 'Chemistry - Class 12',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Solutions - Types of solutions, concentration terms, Raoult\'s law, colligative properties, abnormal molecular mass',
                'Chapter 2: Electrochemistry - Electrochemical cells, Nernst equation, conductance, Kohlrausch law, batteries, fuel cells',
                'Chapter 3: Chemical Kinetics - Rate of reaction, order and molecularity, integrated rate equations, Arrhenius equation, catalysis',
                'Chapter 4: Surface Chemistry - Adsorption, catalysis, colloids, emulsions, applications of colloids'
            ],
            topics: []
        },
        // Biology
        {
            id: 5,
            subject: 'Biology',
            title: 'Biology - Class 11',
            month: 'Full Year',
            chapters: [
                'Chapter 1: The Living World - Characteristics of living organisms, biodiversity, taxonomy, binomial nomenclature',
                'Chapter 2: Biological Classification - Five kingdom classification, monera, protista, fungi, plantae, animalia',
                'Chapter 3: Plant Kingdom - Algae, bryophytes, pteridophytes, gymnosperms, angiosperms, plant life cycles',
                'Chapter 4: Animal Kingdom - Classification of animals, non-chordates, chordates, vertebrate classes'
            ],
            topics: []
        },
        {
            id: 6,
            subject: 'Biology',
            title: 'Biology - Class 12',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Reproduction - Reproduction in organisms, sexual reproduction, asexual reproduction, pollination, fertilization',
                'Chapter 2: Genetics - Mendelian laws, inheritance patterns, sex determination, genetic disorders, linkage and crossing over',
                'Chapter 3: Evolution - Origin of life, evolutionary theories, evidence of evolution, human evolution',
                'Chapter 4: Human Health and Disease - Types of diseases, immunity, AIDS, cancer, drug abuse'
            ],
            topics: []
        },
        // Mathematics
        {
            id: 7,
            subject: 'Mathematics',
            title: 'Mathematics - Class 11',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Sets - Sets and their representations, subsets, Venn diagrams, operations on sets, laws of algebra of sets',
                'Chapter 2: Relations and Functions - Cartesian product, relations, functions, domain, range, types of functions',
                'Chapter 3: Trigonometric Functions - Angles, trigonometric ratios, identities, transformations, equations',
                'Chapter 4: Principle of Mathematical Induction - Process of induction, applications in different areas',
                'Chapter 5: Complex Numbers - Complex numbers, algebra of complex numbers, argand plane, modulus and conjugate'
            ],
            topics: []
        },
        {
            id: 8,
            subject: 'Mathematics',
            title: 'Mathematics - Class 12',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Relations and Functions - Types of relations, equivalence relations, types of functions, composition of functions, invertible functions',
                'Chapter 2: Inverse Trigonometric Functions - Inverse trigonometric functions, principal values, properties',
                'Chapter 3: Matrices - Matrix operations, types of matrices, transpose, symmetric/skew-symmetric matrices, invertible matrices',
                'Chapter 4: Determinants - Determinants, properties, minors, cofactors, adjoint, inverse of a matrix, applications'
            ],
            topics: []
        },
        // English
        {
            id: 9,
            subject: 'English',
            title: 'English - Literature & Grammar',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Reading Comprehension - Unseen passages, poetic passages, factual passages, inferential questions',
                'Chapter 2: Writing Skills - Essay writing, letter writing, article writing, report writing, speech writing',
                'Chapter 3: Grammar - Tenses, modals, voice, narration, clauses, transformation of sentences',
                'Chapter 4: Literature - Poetry analysis, prose analysis, drama analysis, literary devices, character analysis'
            ],
            topics: []
        },
        // Computer Science
        {
            id: 10,
            subject: 'Computer Science',
            title: 'Computer Science - Fundamentals',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Computer Basics - History of computers, generations of computers, types of computers, hardware and software',
                'Chapter 2: Operating Systems - Functions of OS, types of OS, file management, process management, memory management',
                'Chapter 3: Programming Concepts - Algorithms, flowcharts, programming paradigms, compilation and interpretation',
                'Chapter 4: Data Structures - Arrays, linked lists, stacks, queues, trees, graphs, searching and sorting algorithms'
            ],
            topics: []
        },
        // History
        {
            id: 11,
            subject: 'History',
            title: 'World History',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Ancient Civilizations - Mesopotamia, Egypt, Indus Valley, China, Greece, Rome',
                'Chapter 2: Medieval History - Rise of Islam, Crusades, Mongol Empire, Renaissance, Reformation',
                'Chapter 3: Modern History - Age of Exploration, Industrial Revolution, World Wars, Cold War',
                'Chapter 4: Contemporary History - UN and global organizations, post-colonialism, globalization, current affairs'
            ],
            topics: []
        },
        // Geography
        {
            id: 12,
            subject: 'Geography',
            title: 'Physical Geography',
            month: 'Full Year',
            chapters: [
                'Chapter 1: The Earth - Origin and structure, plate tectonics, continents and oceans, internal processes',
                'Chapter 2: Landforms - Mountains, plateaus, plains, river systems, coastlines, glaciers, deserts',
                'Chapter 3: Climate - Weather and climate, climatic zones, monsoons, cyclones, climate change',
                'Chapter 4: Natural Resources - Types of resources, distribution, conservation, sustainable development'
            ],
            topics: []
        },
        // Economics
        {
            id: 13,
            subject: 'Economics',
            title: 'Economics - Micro & Macro',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Introduction to Economics - Scarcity, choice, opportunity cost, economic systems',
                'Chapter 2: Microeconomics - Demand and supply, elasticity, market structures, consumer behavior',
                'Chapter 3: Macroeconomics - GDP, inflation, unemployment, fiscal policy, monetary policy',
                'Chapter 4: International Economics - Trade, exchange rates, balance of payments, globalization'
            ],
            topics: []
        },
        // Physical Education
        {
            id: 14,
            subject: 'Physical Education',
            title: 'Physical Education - Theory & Practice',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Health and Fitness - Components of fitness, importance of physical activity, health-related fitness',
                'Chapter 2: Sports and Games - Team sports, individual sports, rules and regulations, skills and techniques',
                'Chapter 3: Anatomy and Physiology - Skeletal system, muscular system, cardiovascular system, respiratory system',
                'Chapter 4: Nutrition and Wellness - Balanced diet, nutritional requirements, hydration, weight management'
            ],
            topics: []
        },
        // Art
        {
            id: 15,
            subject: 'Art',
            title: 'Art & Design',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Color Theory - Color wheel, color schemes, color psychology, color mixing',
                'Chapter 2: Drawing Techniques - Line drawing, shading, perspective, composition',
                'Chapter 3: Painting Fundamentals - Acrylic, watercolor, oil painting, techniques, styles',
                'Chapter 4: Art History - Major movements, artists, art appreciation, cultural significance'
            ],
            topics: []
        },
        // Foreign Language
        {
            id: 16,
            subject: 'Foreign Language',
            title: 'Spanish - Level 1',
            month: 'Full Year',
            chapters: [
                'Chapter 1: Basic Communication - Greetings, introductions, numbers, days, months, colors',
                'Chapter 2: Grammar Fundamentals - Nouns, articles, adjectives, verb conjugation in present tense',
                'Chapter 3: Vocabulary Building - Family, food, clothing, animals, places, transportation',
                'Chapter 4: Culture and Conversation - Spanish culture, idiomatic expressions, practical dialogues'
            ],
            topics: []
        }
    ];

    // Populate topics from chapters for each syllabus
    allSyllabi.forEach(s => {
        s.topics = s.chapters.map(chapter => {
            // Extract chapter number and title
            const parts = chapter.split(' - ');
            const chapterTitle = parts[0] || chapter;
            const description = parts[1] || '';
            return {
                title: chapterTitle,
                description: description,
                full: chapter
            };
        });
    });

    let syllabiData = allSyllabi;
    let selectedSyllabusId = null;
    let distributedTopics = [];
    let currentPlanLevel = 'day';

    // ===== SYLLABUS FUNCTIONS =====
    function loadSyllabi() {
        const subject = $('#formSubject').val();
        if (!subject) {
            $('#syllabusList').html(`
                <div class="syllabus-empty">
                    <i class="fas fa-book"></i>
                    <div>Please select a subject first to view available syllabi</div>
                </div>
            `);
            $('#subjectInfo').hide();
            $('#distributeBtn').prop('disabled', true);
            $('#clearBtn').hide();
            return;
        }

        $('#subjectDisplay').text(`📚 ${subject}`);
        $('#subjectInfo').show();

        const filtered = syllabiData.filter(s => s.subject === subject);
        
        if (filtered.length === 0) {
            $('#syllabusList').html(`
                <div class="syllabus-empty">
                    <i class="fas fa-book"></i>
                    <div>No syllabi found for ${subject}</div>
                </div>
            `);
            $('#distributeBtn').prop('disabled', true);
            $('#clearBtn').hide();
            return;
        }

        let html = '';
        filtered.forEach(s => {
            const isSelected = selectedSyllabusId === s.id;
            html += `
                <div class="syllabus-item ${isSelected ? 'selected' : ''}" onclick="selectSyllabus(${s.id})" id="syllabus-${s.id}">
                    <div class="syllabus-check">
                        <i class="fas fa-check" style="${isSelected ? '' : 'display:none;'} font-size:12px; color:white;"></i>
                    </div>
                    <div class="syllabus-info">
                        <div class="title">${s.title}</div>
                        <div class="meta">${s.month} • ${s.chapters ? s.chapters.length : 0} chapters</div>
                        <div class="syllabus-detail">${s.chapters ? s.chapters[0] : ''}</div>
                    </div>
                    <span class="syllabus-chapters">${s.chapters ? s.chapters.length : 0} chapters</span>
                </div>
            `;
        });
        
        $('#syllabusList').html(html);
        
        if (selectedSyllabusId && filtered.some(s => s.id === selectedSyllabusId)) {
            $('#distributeBtn').prop('disabled', false);
            $('#clearBtn').show();
            updateQuickStats();
        } else {
            selectedSyllabusId = null;
            $('#distributeBtn').prop('disabled', true);
            $('#clearBtn').hide();
            $('#selectedInfo').text('');
        }
    }

    function selectSyllabus(id) {
        selectedSyllabusId = id;
        $('.syllabus-item').removeClass('selected');
        $(`#syllabus-${id}`).addClass('selected');
        $(`#syllabus-${id} .syllabus-check i`).show();
        $('#distributeBtn').prop('disabled', false);
        $('#clearBtn').show();
        const syllabus = syllabiData.find(s => s.id === id);
        $('#selectedInfo').text(`✅ Selected: ${syllabus.title} (${syllabus.chapters ? syllabus.chapters.length : 0} chapters)`);
        
        if (syllabus) {
            $('#statSyllabusTopics').text(syllabus.chapters ? syllabus.chapters.length : 0);
        }
        updateQuickStats();
    }

    function clearSyllabusSelection() {
        selectedSyllabusId = null;
        $('.syllabus-item').removeClass('selected');
        $('.syllabus-check i').hide();
        $('#distributeBtn').prop('disabled', true);
        $('#clearBtn').hide();
        $('#selectedInfo').text('');
        $('#distributionResult').hide();
        $('#statSyllabusTopics').text(0);
        updateQuickStats();
    }

    function distributeSyllabus() {
        if (!selectedSyllabusId) {
            alert('Please select a syllabus first.');
            return;
        }

        const syllabus = syllabiData.find(s => s.id === selectedSyllabusId);
        if (!syllabus) {
            alert('Syllabus not found.');
            return;
        }

        const duration = parseInt($('#formDuration').val()) || 1;
        const level = currentPlanLevel;
        
        let totalDays = duration;
        if (level === 'week') totalDays = duration * 7;

        const chapters = syllabus.chapters || [];
        const chapterCount = chapters.length;
        
        if (chapterCount === 0) {
            alert('This syllabus has no chapters to distribute.');
            return;
        }

        distributedTopics = [];
        const days = Math.min(totalDays, chapterCount);
        const chaptersPerDay = Math.ceil(chapterCount / days);

        for (let i = 0; i < days; i++) {
            const start = i * chaptersPerDay;
            const end = Math.min(start + chaptersPerDay, chapterCount);
            const dayChapters = chapters.slice(start, end);
            
            let label = '';
            if (level === 'day') {
                label = `Day ${i + 1}`;
            } else {
                const weekNum = Math.floor(i / 7) + 1;
                const dayInWeek = (i % 7) + 1;
                label = `Week ${weekNum} - Day ${dayInWeek}`;
            }
            
            distributedTopics.push({
                day: i + 1,
                label: label,
                topics: dayChapters
            });
        }

        let html = '';
        let totalTopics = 0;
        distributedTopics.forEach(item => {
            totalTopics += item.topics.length;
            html += `
                <div class="dist-item">
                    <span class="dist-day">${item.label}</span>
                    <span class="dist-topic">${item.topics.join('; ')}</span>
                    <span class="dist-count">${item.topics.length} chapters</span>
                </div>
            `;
        });

        $('#distributedTopics').html(html);
        $('#distTotalTopics').text(`📊 ${totalTopics} chapters across ${distributedTopics.length} days`);
        $('#distributionResult').show();
        
        document.getElementById('distributionResult').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function addDistributedTopics() {
        if (distributedTopics.length === 0) {
            alert('No chapters to add. Please distribute a syllabus first.');
            return;
        }

        $('#topicContainer').empty();
        $('#emptyTopicsMessage').hide();
        topicCounter = 0;

        let totalAdded = 0;
        distributedTopics.forEach(item => {
            item.topics.forEach((chapter) => {
                addTopicRow();
                const row = $('#topicContainer .topic-row').last();
                const fullTitle = `${item.label}: ${chapter}`;
                row.find('.topic-title-input').val(fullTitle);
                totalAdded++;
            });
        });

        updateTopicCount();
        updateQuickStats();
        
        alert(`✅ Added ${totalAdded} chapters to your lesson plan!`);
        hideDistribution();
    }

    function hideDistribution() {
        $('#distributionResult').hide();
    }

    // ===== PLAN LEVEL SELECTION =====
    const presets = {
        day: [1, 3, 5, 7, 10, 14, 18, 21, 30, 45, 60, 90],
        week: [1, 2, 3, 4, 6, 8, 12, 16, 20]
    };

    const popularValues = [7, 14, 18, 30];

    const unitLabels = {
        day: { singular: 'Day', plural: 'Days', hint: 'Enter the number of days for your lesson plan' },
        week: { singular: 'Week', plural: 'Weeks', hint: 'Enter the number of weeks for your lesson plan' }
    };

    function selectPlanLevel(level) {
        currentPlanLevel = level;
        
        $('.plan-level-btn').removeClass('active');
        $(`.plan-level-btn[data-level="${level}"]`).addClass('active');
        
        const descriptions = {
            day: '📌 Plan for specific days with detailed topics and activities',
            week: '📌 Plan for multiple weeks with topics distributed across weeks'
        };
        $('#level-description').text(descriptions[level] || '');
        
        const badgeLabels = { day: 'Day', week: 'Week' };
        $('#levelBadge').text(badgeLabels[level] || 'Day');
        $('#levelBadge').attr('class', 'level-badge ' + level);
        
        const unit = unitLabels[level];
        const currentValue = parseInt($('#formDuration').val()) || 1;
        const labelText = currentValue === 1 ? unit.singular : unit.plural;
        $('#durationUnitLabel').text(labelText);
        $('#durationHint').text(unit.hint);
        
        const maxValues = { day: 365, week: 52 };
        $('#formDuration').attr('max', maxValues[level]);
        
        updateDurationDisplay();
        updatePresetButtons(level);
        updateDateRange();
    }

    function updatePresetButtons(level) {
        const container = $('#presetButtons');
        container.empty();
        
        const presetValues = presets[level] || [1, 3, 5, 7, 10, 14, 18, 30];
        const currentValue = parseInt($('#formDuration').val()) || 1;
        
        presetValues.forEach(val => {
            const unit = unitLabels[level];
            const label = val === 1 ? `${val} ${unit.singular}` : `${val} ${unit.plural}`;
            const isPopular = popularValues.includes(val);
            const isActive = val === currentValue;
            
            container.append(`
                <button type="button" class="preset-btn ${isPopular ? 'popular' : ''} ${isActive ? 'active' : ''}" 
                        onclick="setDuration(${val})">
                    ${label}
                    ${isPopular ? '<i class="fas fa-star"></i>' : ''}
                </button>
            `);
        });
    }

    function setDuration(value) {
        $('#formDuration').val(value);
        updateDurationDisplay();
        updateDateRange();
        updatePresetButtons(currentPlanLevel);
        updateQuickStats();
    }

    function updateDurationDisplay() {
        const level = currentPlanLevel;
        const value = parseInt($('#formDuration').val()) || 1;
        const unit = unitLabels[level];
        const labelText = value === 1 ? unit.singular : unit.plural;
        $('#durationUnitLabel').text(labelText);
        
        let totalDays = value;
        if (level === 'week') totalDays = value * 7;
        
        const daysText = totalDays === 1 ? '1 day' : totalDays + ' days';
        $('#totalDaysDisplay').html(`<i class="fas fa-calendar-day"></i> = ${daysText}`);
        
        updateQuickStats();
    }

    function updateQuickStats() {
        const level = currentPlanLevel;
        const value = parseInt($('#formDuration').val()) || 1;
        const topics = $('#topicContainer .topic-row').length;
        
        let totalDays = value;
        let weeks = 1;
        
        if (level === 'week') {
            totalDays = value * 7;
            weeks = value;
        } else {
            weeks = Math.ceil(totalDays / 7);
        }
        
        $('#statDays').text(totalDays);
        $('#statTopics').text(topics);
        $('#statWeeks').text(weeks);
        
        if (selectedSyllabusId) {
            const syllabus = syllabiData.find(s => s.id === selectedSyllabusId);
            if (syllabus) {
                $('#statSyllabusTopics').text(syllabus.chapters ? syllabus.chapters.length : 0);
            }
        } else {
            $('#statSyllabusTopics').text(0);
        }
    }

    function updateDateRange() {
        const startDate = $('#formStartDate').val();
        const value = parseInt($('#formDuration').val()) || 1;
        const level = currentPlanLevel;
        
        if (!startDate) return;
        
        let totalDays = value;
        let planTypeLabel = '';
        
        switch(level) {
            case 'day':
                totalDays = value;
                planTypeLabel = value === 1 ? 'Day Plan' : `${value} Day Plan`;
                break;
            case 'week':
                totalDays = value * 7;
                planTypeLabel = value === 1 ? 'Weekly Plan' : `${value} Week Plan`;
                break;
        }
        
        const start = new Date(startDate + 'T00:00:00');
        const end = new Date(start);
        end.setDate(end.getDate() + totalDays - 1);
        
        const startStr = start.toLocaleDateString('en-US', { 
            weekday: 'short', 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric' 
        });
        const endStr = end.toLocaleDateString('en-US', { 
            weekday: 'short', 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric' 
        });
        
        const daysText = totalDays === 1 ? '1 day' : totalDays + ' days';
        
        $('#startDateDisplay').text(startStr);
        $('#endDateDisplay').text(endStr);
        $('#durationDisplay').html(`<i class="fas fa-tag"></i> ${planTypeLabel}`);
        $('#daysCountDisplay').text(`📊 ${daysText}`);
        
        const startWeekday = start.toLocaleDateString('en-US', { weekday: 'long' });
        const endWeekday = end.toLocaleDateString('en-US', { weekday: 'long' });
        $('#weekdayInfo').html(`<i class="fas fa-calendar-alt"></i> Starts on ${startWeekday} • Ends on ${endWeekday}`);
        
        $('#formEndDate').val(end.toISOString().split('T')[0]);
    }

    // ===== TOPIC MANAGEMENT =====
    let topicCounter = 0;

    function addTopicRow() {
        const container = $('#topicContainer');
        const emptyMsg = $('#emptyTopicsMessage');
        const num = ++topicCounter;
        
        emptyMsg.hide();
        
        const row = `
            <div class="topic-row" data-topic="${num}">  
                <div class="topic-header"> 
                    <span class="topic-number"><i class="fas fa-book"></i> Topic #${num}</span>
                    <button type="button" class="btn btn-danger btn-small" onclick="removeTopicRow(this)"><i class="fas fa-trash-alt"></i> Remove</button> 
                </div>
                <div class="topic-fields">
                    <div class="topic-fields-full">
                        <label class="topic-field-label"><i class="fas fa-heading"></i> Topic Title <span class="form-required">*</span></label>
                        <input type="text" class="topic-title-input" placeholder="Enter topic title" onchange="updateTopicCount()" />
                    </div>
                    <div> 
                        <label class="topic-field-label"><i class="fab fa-youtube" style="color:#ff0000;"></i> YouTube Video URL</label>
                        <input type="url" class="topic-video-input" placeholder="https://youtube.com/watch?v=..." onchange="previewVideo(this)" />
                        <div class="form-hint"><i class="fas fa-info-circle"></i> Paste a YouTube link to embed the video</div> 
                    </div>
                    <div>
                        <label class="topic-field-label"><i class="fas fa-upload"></i> Upload PDF/File</label> 
                        <input type="file" class="topic-file-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" multiple style="margin-top:4px;" />
                        <div class="file-list" style="margin-top:6px;"></div>
                        <div class="form-hint"><i class="fas fa-info-circle"></i> Upload PDF, Word, PowerPoint, or Excel files</div>
                    </div>
                    <div>
                        <label class="topic-field-label"><i class="fas fa-cubes"></i> Resources (comma separated)</label> 
                        <input type="text" class="topic-resources-input" placeholder="e.g. Textbook, Worksheet" />
                        <div class="form-hint"><i class="fas fa-info-circle"></i> Separate multiple resources with commas</div>
                    </div>
                </div>
                <div class="video-preview" id="video-preview-${num}">
                    <div class="video-thumb">
                        <iframe id="video-frame-${num}" src="" allowfullscreen></iframe>
                    </div>
                    <div class="video-info">
                        <span class="label"><i class="fab fa-youtube" style="color:#ff0000;"></i> Video Preview</span>
                        <span class="link" id="video-link-${num}"></span>
                    </div>
                </div>
            </div>
        `;
        
        container.append(row); 
        
        const fileInput = container.find('.topic-file-input').last();
        fileInput.on('change', function() {
            const fileList = $(this).siblings('.file-list'); 
            fileList.empty();
            
            const files = this.files; 
            for (let i = 0; i < files.length; i++) { 
                const file = files[i];
                const fileSize = (file.size / 1024).toFixed(1);
                const icon = file.type.includes('pdf') ? 'fa-file-pdf' : 
                             file.type.includes('word') ? 'fa-file-word' :
                             file.type.includes('powerpoint') ? 'fa-file-powerpoint' :
                             file.type.includes('sheet') ? 'fa-file-excel' : 'fa-file';
                const color = file.type.includes('pdf') ? '#dc2626' : 
                             file.type.includes('word') ? '#2563eb' :
                             file.type.includes('powerpoint') ? '#f59e0b' :
                             file.type.includes('sheet') ? '#10b981' : '#6b7280'; 
                
                fileList.append(` 
                    <div class="file-item">
                        <i class="fas ${icon}" style="color:${color};"></i>
                        <span class="file-name">${file.name}</span>
                        <span class="file-size">(${fileSize} KB)</span>
                        <button type="button" class="btn btn-danger btn-small" onclick="removeFile(this)" style="padding:2px 8px; font-size:10px;"><i class="fas fa-times"></i></button>
                    </div>
                `);
            }
        });
        
        updateTopicCount();
        updateQuickStats();
    }

    function removeFile(btn) {
        $(btn).closest('.file-item').remove();
    }

    function removeTopicRow(btn) {
        $(btn).closest('.topic-row').remove(); 
        updateTopicCount();
        updateQuickStats();
        
        if ($('#topicContainer .topic-row').length === 0) { 
            $('#emptyTopicsMessage').show();
        }
    }

    function addTopic() {
        addTopicRow();
    }
     
    function addMultipleTopics() {
        for (let i = 0; i < 3; i++) { 
            addTopicRow();
        }
    }

    function generateTopicsFromDuration() {
        const level = currentPlanLevel;
        const duration = parseInt($('#formDuration').val()) || 1;
        const currentTopics = $('#topicContainer .topic-row').length;
        const subject = $('#formSubject').val() || 'Lesson';
        
        let targetTopics = 0;
        let timeUnit = '';
        
        switch(level) {
            case 'day':
                targetTopics = duration;
                timeUnit = 'day';
                break;
            case 'week':
                targetTopics = duration;
                timeUnit = 'week';
                break;
            default:
                targetTopics = 1;
        }
        
        targetTopics = Math.min(targetTopics, 365);
        
        if (currentTopics >= targetTopics) {
            alert(`You already have ${currentTopics} topics. The recommended number for ${duration} ${timeUnit}${duration > 1 ? 's' : ''} is ${targetTopics} topics.`);
            return;
        }
        
        const toAdd = Math.min(targetTopics - currentTopics, 30);
        
        const topicSuggestions = {
            'Physics': [
                'Units and Measurements', 'Motion in a Straight Line', 'Motion in a Plane', 
                'Laws of Motion', 'Work, Energy and Power', 'System of Particles', 
                'Rotational Motion', 'Gravitation', 'Mechanical Properties of Solids',
                'Mechanical Properties of Fluids', 'Thermal Properties of Matter', 'Thermodynamics',
                'Kinetic Theory', 'Oscillations', 'Waves', 'Electric Charges and Fields',
                'Electrostatic Potential and Capacitance', 'Current Electricity', 'Moving Charges and Magnetism',
                'Magnetism and Matter', 'Electromagnetic Induction', 'Alternating Current',
                'Electromagnetic Waves', 'Ray Optics', 'Wave Optics', 'Dual Nature of Radiation',
                'Atoms', 'Nuclei', 'Semiconductor Electronics'
            ],
            'Chemistry': [
                'Some Basic Concepts of Chemistry', 'Structure of Atom', 'Classification of Elements',
                'Chemical Bonding', 'States of Matter', 'Thermodynamics', 'Equilibrium',
                'Redox Reactions', 'Hydrogen', 's-Block Elements', 'p-Block Elements',
                'Organic Chemistry', 'Hydrocarbons', 'Environmental Chemistry', 'Solutions',
                'Electrochemistry', 'Chemical Kinetics', 'Surface Chemistry', 'Solid State',
                'Coordination Compounds', 'Aldehydes and Ketones', 'Carboxylic Acids', 'Amines'
            ],
            'Biology': [
                'The Living World', 'Biological Classification', 'Plant Kingdom', 'Animal Kingdom',
                'Morphology of Flowering Plants', 'Anatomy of Flowering Plants', 'Structural Organisation in Animals',
                'Cell Structure', 'Cell Division', 'Transport in Plants', 'Mineral Nutrition',
                'Photosynthesis', 'Respiration in Plants', 'Plant Growth', 'Digestion and Absorption',
                'Breathing and Exchange', 'Body Fluids', 'Excretory Products', 'Locomotion',
                'Neural Control', 'Chemical Coordination', 'Reproduction', 'Genetics', 'Evolution',
                'Human Health', 'Biotechnology', 'Ecology', 'Environmental Issues'
            ],
            'Mathematics': [
                'Sets', 'Relations and Functions', 'Trigonometric Functions', 'Principle of Mathematical Induction',
                'Complex Numbers', 'Quadratic Equations', 'Linear Inequalities', 'Permutations and Combinations',
                'Binomial Theorem', 'Sequences and Series', 'Straight Lines', 'Conic Sections',
                'Three Dimensional Geometry', 'Limits and Derivatives', 'Statistics', 'Probability',
                'Relations and Functions (Class 12)', 'Inverse Trigonometric Functions', 'Matrices', 'Determinants',
                'Continuity and Differentiability', 'Application of Derivatives', 'Integrals', 'Application of Integrals',
                'Differential Equations', 'Vector Algebra', 'Linear Programming', 'Probability'
            ],
            'English': [
                'Reading Comprehension', 'Writing Skills', 'Grammar', 'Literature', 'Poetry Analysis',
                'Prose Analysis', 'Drama Analysis', 'Essay Writing', 'Letter Writing', 'Article Writing',
                'Report Writing', 'Speech Writing', 'Tenses', 'Modals', 'Voice', 'Narration',
                'Clauses', 'Transformation of Sentences', 'Parts of Speech', 'Sentence Structure'
            ]
        };
        
        let suggestions = topicSuggestions[subject] || [];
        
        if (suggestions.length === 0) {
            suggestions = [];
            for (let i = 1; i <= 100; i++) {
                suggestions.push(`Chapter ${i}: ${subject} Topic ${i}`);
            }
        }
        
        while (suggestions.length < targetTopics) {
            suggestions = suggestions.concat(suggestions);           
        }
        
        let addedCount = 0;
        const startIndex = currentTopics;
        
        for (let i = 0; i < toAdd; i++) {
            addTopicRow();
            const row = $('#topicContainer .topic-row').last();
            const suggestionIndex = (startIndex + i) % suggestions.length;
            const topicTitle = suggestions[suggestionIndex];
            
            if (level === 'day') {
                const dayNum = startIndex + i + 1;
                row.find('.topic-title-input').val(`Day ${dayNum}: ${topicTitle}`);
            } else if (level === 'week') {
                const weekNum = Math.floor((startIndex + i) / 7) + 1;
                const dayInWeek = (startIndex + i) % 7 + 1;
                row.find('.topic-title-input').val(`Week ${weekNum} - Day ${dayInWeek}: ${topicTitle}`);
            } else {
                row.find('.topic-title-input').val(topicTitle);
            }
            
            addedCount++;
        }
        
        const unitLabel = timeUnit + (duration > 1 ? 's' : '');
        const totalTopics = $('#topicContainer .topic-row').length;
        
        alert(`✅ Generated ${addedCount} topic${addedCount > 1 ? 's' : ''} for your ${duration} ${unitLabel} plan.\nTotal topics: ${totalTopics}\nRecommended: ${targetTopics} topics for ${duration} ${unitLabel}.`);
        
        updateTopicCount();
        updateQuickStats();
    }

    function updateTopicCount() {
        const count = $('#topicContainer .topic-row').length;
        $('#topic-count').text(`(${count} topics)`);
        updateQuickStats();
    }

    function getTopicsFromForm() {
        const topics = [];
        $('#topicContainer .topic-row').each(function() {
            const title = $(this).find('.topic-title-input').val().trim();
            const video = $(this).find('.topic-video-input').val().trim();
            const resources = $(this).find('.topic-resources-input').val().trim();
            const files = [];
            
            $(this).find('.file-item').each(function() {
                const name = $(this).find('.file-name').text().trim();
                if (name) {
                    files.push({
                        name: name,
                        url: '#',
                        type: 'file'
                    });
                }
            });
            
            if (title) {
                topics.push({
                    number: topics.length + 1,
                    title: title,
                    video: video,
                    files: files,
                    resources: resources ? resources.split(',').map(r => r.trim()) : [],
                    covered: false
                });
            }
        });
        return topics;
    }

    function previewVideo(input) {
        const row = $(input).closest('.topic-row');
        const videoUrl = $(input).val().trim();
        const preview = row.find('.video-preview');
        const frame = row.find('.video-frame');
        const link = row.find('.video-link');
        
        const videoId = getYoutubeId(videoUrl);
        
        if (videoId) {
            preview.addClass('show');
            frame.attr('src', `https://www.youtube.com/embed/${videoId}`);
            link.text(videoUrl);
        } else {
            preview.removeClass('show');
            frame.attr('src', '');
            link.text('');
        }
    }

    function getYoutubeId(url) {
        if (!url) return null;
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);
        if (match && match[2].length === 11) { 
            return match[2];
        }
        return null;
    }

    function getDateRange(startDate, totalDays) {
        const dates = [];
        const start = new Date(startDate + 'T00:00:00');
        
        for (let i = 0; i < totalDays; i++) {
            const d = new Date(start);
            d.setDate(d.getDate() + i);
            dates.push(d.toISOString().split('T')[0]);
        }
        
        return dates;
    }

    function clearAllTopics() {
        if ($('#topicContainer .topic-row').length === 0) return;
        if (confirm('Are you sure you want to clear all topics?')) {
            $('#topicContainer').empty();
            $('#emptyTopicsMessage').show();
            topicCounter = 0;
            updateTopicCount();
            updateQuickStats();
        }
    }

    // ===== SAVE FUNCTIONS =====
    function saveLessonPlan(e) {
        e.preventDefault();

        const topics = getTopicsFromForm();
        const startDate = $('#formStartDate').val();
        const value = parseInt($('#formDuration').val()) || 1;
        const level = currentPlanLevel;
        const endDate = $('#formEndDate').val(); 
        
        if (topics.length === 0) {
            alert('⚠️ Please add at least one topic before saving.');
            return;
        }
        
        if (!startDate) {
            alert('⚠️ Please select a start date.');
            return;
        }
        
        let totalDays = value;
        let planTypeLabel = '';
        let planType = '';
        
        switch(level) {
            case 'day':
                totalDays = value;
                planTypeLabel = value === 1 ? 'Day Plan' : `${value} Day Plan`;
                planType = 'Daily Plan';
                break;
            case 'week':
                totalDays = value * 7;
                planTypeLabel = value === 1 ? 'Weekly Plan' : `${value} Week Plan`;
                planType = 'Weekly Plan';
                break;
        }
        
        if (totalDays < 1 || totalDays > 365) {
            alert('⚠️ Total duration must be between 1 and 365 days.');
            return;
        }
        
        const dateObj = new Date(startDate + 'T00:00:00');
        const monthName = dateObj.toLocaleString('default', { month: 'long' });
        const year = dateObj.getFullYear();
        const weekNum = Math.ceil((dateObj.getDate() + 1) / 7);
        
        const dateRange = getDateRange(startDate, totalDays);
        
        const dailyTopics = {};
        dateRange.forEach(date => {
            dailyTopics[date] = topics.map((t, index) => ({
                ...t,
                number: index + 1,
                covered: false
            }));
        });
        
        let syllabusInfo = null;
        if (selectedSyllabusId) {
            const syllabus = syllabiData.find(s => s.id === selectedSyllabusId);
            if (syllabus) {
                syllabusInfo = {
                    id: syllabus.id,
                    title: syllabus.title,
                    month: syllabus.month,
                    chapters: syllabus.chapters
                };
            }
        }
        
        const planData = {
            id: Date.now(),
            title: $('#formTitle').val().trim() || ($('#formSubject').val() + ' - ' + $('#formCourseType').val()),
            courseType: $('#formCourseType').val(),
            courseSubType: $('#formCourseSubType').val(),
            academicYear: $('#formAcademicYear').val(),
            subject: $('#formSubject').val(),
            teacher: $('#formTeacher').val(),
            status: window.pendingSubmit ? 'active' : 'draft',
            objectives: $('#formObjectives').val(),
            methods: $('input[name="methods"]:checked').map(function() { return this.value; }).get(),
            aids: $('input[name="aids"]:checked').map(function() { return this.value; }).get(),
            resources: $('#formResources').val(),
            coverage: Math.floor(Math.random() * 30) + 10,
            planLevel: level,
            planType: planType,
            planTypeLabel: planTypeLabel,
            duration: value,
            totalDays: totalDays,
            month: monthName,
            year: year,
            week: 'Week ' + Math.min(weekNum, 5),
            startDate: startDate,
            endDate: endDate,
            dateRange: dateRange,
            topics: topics,
            dailyTopics: dailyTopics,
            syllabus: syllabusInfo,
            distributedTopics: distributedTopics,
            fromStatic: true
        };

        const updatedPlans = [...window.plansData, planData];
        savePlans(updatedPlans);
        window.pendingSubmit = false;
        
        $('#successMessage').addClass('show');
        setTimeout(() => {
            if (window.refreshLessonPlannerViews) {
                window.refreshLessonPlannerViews();
            }
            window.location.href = "{{ route('lesson-planner.plans') }}";
        }, 1000);
    }

    function submitLessonPlan() {
        const topics = getTopicsFromForm();
        if (topics.length === 0) {
            alert('⚠️ Please add at least one topic before submitting.');
            return;
        }
        if (!$('#formCourseType').val() || !$('#formSubject').val() || !$('#formObjectives').val() || !$('#formTeacher').val()) {
            alert('⚠️ Please fill in all required fields (Course Type, Subject, Teacher, and Learning Objectives).');
            return;
        }
        if (!$('#formStartDate').val()) {
            alert('⚠️ Please select a start date.'); 
            return;
        }
        
        if (confirm('Activate this lesson plan?')) {
            window.pendingSubmit = true;
            const form = document.getElementById('lessonForm');
            form.querySelector('button[type="submit"]').click();
        }
    }

    // ===== INIT =====
    $(document).ready(function() {
        const now = new Date();
        const today = now.toISOString().split('T')[0];
        $('#formStartDate').val(today);
        
        $('<input>').attr({
            type: 'hidden',
            id: 'formEndDate',
            name: 'formEndDate'
        }).appendTo('#lessonForm');
        
        addTopicRow();
        selectPlanLevel('day');
        loadSyllabi();
        
        $('#formStartDate').on('change', function() {
            updateDateRange();
            updateQuickStats();
        });
        
        $('#formDuration').on('input', function() {
            updateDurationDisplay();
            updateDateRange();
            updatePresetButtons(currentPlanLevel);
            updateQuickStats();
        });
        
        updateDateRange();
        updateQuickStats();
    });
</script>
@endsection