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
        --badge-main: linear-gradient(135deg, #4361ee, #3a0ca3);
        --badge-sub: linear-gradient(135deg, #10b981, #059669);
        --badge-status-active: linear-gradient(135deg, #10b981, #059669);
        --badge-status-inactive: linear-gradient(135deg, #ef4444, #dc2626);
    }

    body {
        background-color: #f8fafc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .view-container {
        max-width: 1100px;
        margin: 30px auto;
        background: white;
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

    /* Badge Styles */
    .badge-custom {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-main {
        background: var(--badge-main);
        color: white;
    }

    .badge-sub {
        background: var(--badge-sub);
        color: white;
    }

    .badge-active {
        background: var(--badge-status-active);
        color: white;
    }

    .badge-inactive {
        background: var(--badge-status-inactive);
        color: white;
    }

    /* Days Display */
    .days-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 5px;
    }

    .day-badge {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #1e293b;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #cbd5e1;
    }

    /* Time Display */
    .time-display {
        background: var(--primary-gradient);
        color: white;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    /* Empty State */
    .text-muted-custom {
        color: #94a3b8 !important;
        font-style: italic;
        font-size: 14px;
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

        <!-- ================= EMPLOYEE & COURSE DETAILS ================= -->
        <div class="view-section">
            <div class="section-header">
                <i class="bi bi-person-badge-fill"></i>
                Employee & Course Details
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-person-fill"></i> Employee Name</div>
                        <div class="info-value">{{ $assignment->employee_name }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-building-fill"></i> Department</div>
                        <div class="info-value">{{ $assignment->department_name }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-tag-fill"></i> Course Type</div>
                        <div class="info-value">{{ $assignment->course_type }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-diagram-3-fill"></i> Branch</div>
                        <div class="info-value">{{ $assignment->branch_name }}</div>
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
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-calendar-range-fill"></i> Semester</div>
                        <div class="info-value">{{ $assignment->semester_id }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-columns"></i> Section</div>
                        <div class="info-value">{{ $assignment->section_name }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-diagram-2-fill"></i> Subject Type</div>
                        <div class="info-value">
                            @if($assignment->subject_type == 'main_subject')
                                <span class="badge-custom badge-main">Main Subject</span>
                            @elseif($assignment->subject_type == 'sub_subject')
                                <span class="badge-custom badge-sub">Sub Subject</span>
                            @else
                                {{ $assignment->subject_type }}
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-pencil-fill"></i> Subject Display Name</div>
                        <div class="info-value">{{ $assignment->subject_display_name }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-check-circle-fill"></i> Status</div>
                        <div class="info-value">
                            @if(strtolower($assignment->status) == 'active')
                                <span class="badge-custom badge-active">{{ ucfirst($assignment->status) }}</span>
                            @else
                                <span class="badge-custom badge-inactive">{{ ucfirst($assignment->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-chat-text-fill"></i> Remarks</div>
                        <div class="info-value">{{ $assignment->remarks ?? 'Not provided' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= LECTURE SCHEDULE ================= -->
        <div class="view-section">
            <div class="section-header">
                <i class="bi bi-clock-history"></i>
                Lecture Schedule
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-calendar-repeat-fill"></i> Frequency</div>
                        <div class="info-value">{{ $assignment->frequency ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-calendar-plus-fill"></i> Assigned Date</div>
                        <div class="info-value">{{ $assignment->assigned_date ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-clock-fill"></i> Time</div>
                        <div class="info-value">
                            <span class="time-display">{{ $assignment->start_time }} - {{ $assignment->end_time }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-calendar-check-fill"></i> Valid From</div>
                        <div class="info-value">{{ $assignment->valid_from }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-calendar-x-fill"></i> Valid To</div>
                        <div class="info-value">{{ $assignment->valid_to }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-calendar-week-fill"></i> Days</div>
                        <div class="info-value">
                            @if(!empty($assignment->days_of_week))
                                <div class="days-container">
                                    @foreach($assignment->days_of_week as $day)
                                        <span class="day-badge">{{ $day }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted-custom">N/A</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-geo-alt-fill"></i> Location</div>
                        <div class="info-value">{{ $assignment->location ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- No JavaScript changes needed - keeping original functionality -->
@endsection