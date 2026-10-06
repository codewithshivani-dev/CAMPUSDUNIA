@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<!-- Bootstrap Icons CDN - Latest version -->
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
        gap: 10px;
        position: relative;
        z-index: 1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .view-header h4 i {
        font-size: 28px;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px;
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
        background: white;
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
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
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

    .section-header b {
        color: var(--primary-color);
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
    }

    .info-value:hover {
        transform: translateX(5px);
        border-left-color: var(--secondary-color);
    }

    /* Badge Styles */
    .badge-custom {
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-custom.bg-success {
        background: var(--success-gradient);
        color: white;
        box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
    }

    .badge-custom.bg-secondary {
        background: linear-gradient(135deg, #94a3b8, #64748b);
        color: white;
    }

    /* Document Items - For Sub Subjects */
    .document-item {
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px 20px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }

    .document-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .document-item div:first-child {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .document-item strong {
        color: var(--primary-color);
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .document-item strong i {
        font-size: 18px;
        background: white;
        padding: 6px;
        border-radius: 8px;
        color: var(--primary-color);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .document-item small {
        color: #64748b;
        font-size: 13px;
        margin-left: 32px;
    }

    .document-item .badge-custom {
        margin-left: 10px;
    }

    /* Count Badge */
    .count-badge {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        margin-left: 10px;
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

        .document-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .document-item div:last-child {
            align-self: flex-end;
        }

        .document-item small {
            margin-left: 0;
        }
    }
</style>

<div class="view-container mt-4">

    <!-- HEADER -->
    <div class="view-header">
        <h4>
            <i class="bi bi-book-fill"></i>
            Subject Details
        </h4>

        <button onclick="history.back()" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </button>
    </div>

    <div class="view-content">

        <!-- ================= SUBJECT INFORMATION ================= -->
        <div class="view-section">
            <div class="section-header">
                <i class="bi bi-info-circle-fill"></i>
                <b>Subject Information</b>
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-upc-scan"></i> Subject ID</div>
                        <div class="info-value">{{ $subject->subject_id }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-book-fill"></i> Subject Name</div>
                        <div class="info-value">{{ $subject->subject_name }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-tag-fill"></i> Course Type</div>
                        <div class="info-value">{{ $subject->course_type ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-diagram-3-fill"></i> Course Sub Type</div>
                        <div class="info-value">{{ $subject->sub_type ?? 'N/A' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-layers-fill"></i> Semester / Term</div>
                        <div class="info-value">
                            {{ $subject->semester_id ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-calendar-plus-fill"></i> Assigned Date</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($subject->created_at)->format('d-m-Y') }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-check-circle-fill"></i> Status</div>
                        <div class="info-value">
                            @if($subject->status == 'Active')
                                <span class="badge-custom bg-success">
                                    <i class="bi bi-check-circle-fill"></i> Active
                                </span>
                            @else
                                <span class="badge-custom bg-secondary">
                                    <i class="bi bi-x-circle-fill"></i> Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= SUB SUBJECTS ================= -->
        @if($subSubjects->count() > 0)
        <div class="view-section">
            <div class="section-header">
                <i class="bi bi-files-fill"></i>
                <b>Sub Subjects</b>
                <span class="count-badge">{{ $subSubjects->count() }}</span>
            </div>
            <div class="section-body">
                @foreach($subSubjects as $index => $sub)
                    <div class="document-item">
                        <div>
                            <strong>
                                <i class="bi bi-file-text-fill"></i>
                                {{ $index + 1 }}. {{ $sub->sub_subject_name }}
                            </strong>
                            <small>
                                <i class="bi bi-upc-scan me-1"></i> ID: {{ $sub->sub_subject_id }}
                            </small>
                        </div>

                        <div>
                            @if($sub->status == 'active')
                                <span class="badge-custom bg-success">
                                    <i class="bi bi-check-circle-fill"></i> Active
                                </span>
                            @else
                                <span class="badge-custom bg-secondary">
                                    <i class="bi bi-x-circle-fill"></i> Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ================= ADDITIONAL INFO ================= -->
        <div class="view-section">
            <div class="section-header">
                <i class="bi bi-clock-fill"></i>
                <b>Additional Information</b>
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-chat-fill"></i> Remarks</div>
                        <div class="info-value">{{ $subject->remarks ?? '-' }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-calendar-fill"></i> Created At</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($subject->created_at)->format('d-m-Y H:i:s') }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="bi bi-arrow-repeat"></i> Updated At</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($subject->updated_at)->format('d-m-Y H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection