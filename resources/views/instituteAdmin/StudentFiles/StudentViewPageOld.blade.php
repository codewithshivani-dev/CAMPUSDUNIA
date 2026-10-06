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
        --student-color: #4361ee;
        --parent-color: #10b981;
        --guardian-color: #f59e0b;
    }

    .view-container {
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
        font-size: 26px;
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        z-index: 1;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .view-header h4 i {
        font-size: 32px;
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
        text-decoration: none;
    }

    .view-header .btn-light:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    /* Tabs */
    .view-tabs {
        display: flex;
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 0 15px;
        gap: 5px;
    }

    .view-tab {
        padding: 15px 25px;
        cursor: pointer;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #64748b;
        position: relative;
        transition: all 0.3s;
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
    }

    .view-tab:hover {
        color: var(--primary-color);
        background: rgba(67, 97, 238, 0.05);
    }

    .view-tab.active {
        color: var(--primary-color);
        background: white;
        box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.02);
    }

    .view-tab.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--primary-gradient);
        border-radius: 3px 3px 0 0;
    }

    .view-tab i {
        font-size: 18px;
    }

    /* Content Area */
    .view-content {
        padding: 30px;
    }

    /* Card Sections */
    .view-section {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        margin-bottom: 20px;
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

    .section-header.student-header {
        color: var(--student-color);
        border-left: 4px solid var(--student-color);
    }

    .section-header.parent-header {
        color: var(--student-color);
        border-left: 4px solid var(--student-color);
    }

    .section-header.guardian-header {
        color: var(--student-color);
        border-left: 4px solid var(--student-color);
    }

    .section-header i {
        font-size: 20px;
        background: white;
        padding: 8px;
        border-radius: 10px;
        color: inherit;
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
    }

    .info-value:hover {
        transform: translateX(5px);
        border-left-color: var(--secondary-color);
    }

    /* Documents Grid - Three Columns with Compact Design */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .document-item {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 12px;
        transition: all 0.3s;
        height: auto;
        min-height: 60px;
    }

    .document-item.student-doc {
        border-left-color: var(--student-color);
    }

    .document-item.parent-doc {
        border-left-color: var(--student-color);
    }

    .document-item.guardian-doc {
        border-left-color: var(--student-color);
    }

    .document-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 3px 10px rgba(67, 97, 238, 0.1);
    }

    .document-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        height: 100%;
    }

    .document-content .document-info {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-width: 0; /* Allows text to truncate */
    }

    .document-content .document-info i {
        font-size: 16px;
        background: white;
        padding: 6px;
        border-radius: 6px;
        flex-shrink: 0;
    }

    .document-item.student-doc .document-info i {
        color: var(--student-color);
    }

    .document-item.parent-doc .document-info i {
        color: var(--student-color);
    }

    .document-item.guardian-doc .document-info i {
        color: var(--student-color);
    }

    .document-content .document-info span {
        font-weight: 500;
        color: #1e293b;
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .document-value {
        font-size: 12px;
        color: #64748b;
        background: white;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .document-not-uploaded {
        color: #94a3b8;
        font-style: italic;
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        background: white;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px dashed #e2e8f0;
        white-space: nowrap;
    }

    .document-not-uploaded i {
        color: #94a3b8;
        font-size: 12px;
    }

    .btn-view-compact {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        transition: all 0.3s;
        text-decoration: none;
        cursor: pointer;
        flex-shrink: 0;
    }

    .btn-view-compact:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(67, 97, 238, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-view-compact i {
        font-size: 12px;
    }

    /* Category Headers */
    .category-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0 15px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e2e8f0;
    }

    .category-header.student {
        color: var(--student-color);
    }

    .category-header.parent {
        color: var(--parent-color);
    }

    .category-header.guardian {
        color: var(--guardian-color);
    }

    .category-header h5 {
        font-weight: 700;
        margin: 0;
        font-size: 18px;
    }

    .category-header i {
        font-size: 22px;
    }

    /* Responsive for mobile */
    @media (max-width: 992px) {
        .documents-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .view-header {
            gap: 15px;
            padding: 20px;
        }

        .view-header h4 {
            font-size: 22px;
        }

        .view-tabs {
            flex-wrap: wrap;
            padding: 0;
        }

        .view-tab {
            flex: 1 1 auto;
            padding: 12px 15px;
            font-size: 13px;
            justify-content: center;
        }

        .view-tab i {
            font-size: 16px;
        }

        .view-content {
            padding: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .documents-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        
        .document-content {
            flex-wrap: wrap;
        }
        
        .btn-view-compact {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .view-tab {
            font-size: 12px;
            padding: 10px;
        }

        .document-content {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .btn-view-compact {
            width: 100%;
        }
    }
</style>
<div class="container-fluid">
    <div class="view-container">

    <!-- HEADER -->
    <div class="view-header">
        <h4>
            <i class="bi bi-mortarboard-fill"></i>
            <span id="studentName">Student Details</span>
        </h4>

        <a href="{{ url('/institute/admin/students') }}" class="btn btn-light btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- TABS -->
    <div class="view-tabs">
        <button class="view-tab active" data-tab="basic">
            <i class="bi bi-person-fill"></i> Basic
        </button>
        <button class="view-tab" data-tab="address">
            <i class="bi bi-geo-alt-fill"></i> Address
        </button>
        <button class="view-tab" data-tab="academic">
            <i class="bi bi-book-fill"></i> Academic
        </button>
        <button class="view-tab" data-tab="documents">
            <i class="bi bi-file-earmark-fill"></i> Documents
        </button>
        <button class="view-tab" data-tab="bank">
            <i class="bi bi-bank2"></i> Bank
        </button>
    </div>

    <div class="view-content">

        {{-- BASIC TAB --}}
        <div id="basic-tab">

            {{-- ================= STUDENT DETAILS ================= --}}
            <div class="view-section">
                <div class="section-header">
                    <i class="bi bi-person-badge-fill"></i>
                    Student Details
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-upc-scan"></i> Registration Number</div>
                            <div class="info-value">{{ $student->registration_number ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-person-fill"></i> Name</div>
                            <div class="info-value">
                                {{ trim(($student->first_name ?? '').' '.($student->middle_name ?? '').' '.($student->last_name ?? '')) ?: '-' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-calendar-fill"></i> DOB</div>
                            <div class="info-value">{{ $student->dob ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-phone-fill"></i> Mobile</div>
                            <div class="info-value">{{ $student->mobile ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-droplet-fill"></i> Blood Group</div>
                            <div class="info-value">{{ $student->blood_group ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-envelope-fill"></i> Email</div>
                            <div class="info-value">{{ $student->email ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-gender-ambiguous"></i> Gender</div>
                            <div class="info-value">{{ $student->gender ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Nationality</div>
                            <div class="info-value">{{ $student->nationality ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Religion</div>
                            <div class="info-value">{{ $student->religion ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-people-fill"></i> Category</div>
                            <div class="info-value">{{ $student->category ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-check-circle-fill"></i> Status</div>
                            <div class="info-value">{{ $student->status ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= FATHER DETAILS ================= --}}
            <div class="view-section">
                <div class="section-header">
                    <i class="bi bi-person-standing"></i>
                    Father Details
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-person-fill"></i> Father Name</div>
                            <div class="info-value">
                                {{ trim(($student->father_first_name ?? '').' '.($student->father_middle_name ?? '').' '.($student->father_last_name ?? '')) ?: '-' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-calendar-fill"></i> DOB</div>
                            <div class="info-value">{{ $student->father_dob ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-envelope-fill"></i> Email</div>
                            <div class="info-value">{{ $student->father_email ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-phone-fill"></i> Phone</div>
                            <div class="info-value">{{ $student->father_phone ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-briefcase-fill"></i> Occupation</div>
                            <div class="info-value">{{ $student->father_occupation ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-cash-stack"></i> Income</div>
                            <div class="info-value">{{ $student->father_income ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-droplet-fill"></i> Blood Group</div>
                            <div class="info-value">{{ $student->father_blood_group ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Nationality</div>
                            <div class="info-value">{{ $student->father_nationality ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Religion</div>
                            <div class="info-value">{{ $student->father_religion ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-people-fill"></i> Category</div>
                            <div class="info-value">{{ $student->father_category ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= MOTHER DETAILS ================= --}}
            <div class="view-section">
                <div class="section-header">
                    <i class="bi bi-person-standing-dress"></i>
                    Mother Details
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-person-fill"></i> Mother Name</div>
                            <div class="info-value">
                                {{ trim(($student->mother_first_name ?? '').' '.($student->mother_middle_name ?? '').' '.($student->mother_last_name ?? '')) ?: '-' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-calendar-fill"></i> DOB</div>
                            <div class="info-value">{{ $student->mother_dob ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-envelope-fill"></i> Email</div>
                            <div class="info-value">{{ $student->mother_email ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-phone-fill"></i> Phone</div>
                            <div class="info-value">{{ $student->mother_phone ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-briefcase-fill"></i> Occupation</div>
                            <div class="info-value">{{ $student->mother_occupation ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-cash-stack"></i> Income</div>
                            <div class="info-value">{{ $student->mother_income ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-droplet-fill"></i> Blood Group</div>
                            <div class="info-value">{{ $student->mother_blood_group ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Nationality</div>
                            <div class="info-value">{{ $student->mother_nationality ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Religion</div>
                            <div class="info-value">{{ $student->mother_religion ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-people-fill"></i> Category</div>
                            <div class="info-value">{{ $student->mother_category ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= GUARDIAN DETAILS ================= --}}
            <div class="view-section">
                <div class="section-header">
                    <i class="bi bi-shield-fill-check"></i>
                    Guardian Details
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-people-fill"></i> Relation</div>
                            <div class="info-value">{{ $student->guardian_relation ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-person-fill"></i> Guardian Name</div>
                            <div class="info-value">
                                {{ trim(($student->guardian_first_name ?? '').' '.($student->guardian_middle_name ?? '').' '.($student->guardian_last_name ?? '')) ?: '-' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-gender-ambiguous"></i> Gender</div>
                            <div class="info-value">{{ $student->guardian_gender ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-calendar-fill"></i> DOB</div>
                            <div class="info-value">{{ $student->guardian_dob ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-envelope-fill"></i> Email</div>
                            <div class="info-value">{{ $student->guardian_email ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-phone-fill"></i> Phone</div>
                            <div class="info-value">{{ $student->guardian_phone ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-briefcase-fill"></i> Occupation</div>
                            <div class="info-value">{{ $student->guardian_occupation ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-cash-stack"></i> Income</div>
                            <div class="info-value">{{ $student->guardian_income ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Nationality</div>
                            <div class="info-value">{{ $student->guardian_nationality ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Religion</div>
                            <div class="info-value">{{ $student->guardian_religion ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-people-fill"></i> Category</div>
                            <div class="info-value">{{ $student->guardian_category ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ADDRESS TAB --}}
        <div id="address-tab" style="display:none">
            {{-- STUDENT ADDRESS --}}
            <div class="view-section">
                <div class="section-header student-header">
                    <i class="bi bi-person-fill"></i>
                    Student Address
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-door-fill"></i> Permanent Address Line1</div>
                            <div class="info-value">{{ $address->student_perm_address_line1 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-door-fill"></i> Permanent Address Line2</div>
                            <div class="info-value">{{ $address->student_perm_address_line2 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Permanent City</div>
                            <div class="info-value">{{ $address->student_perm_city ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Permanent State</div>
                            <div class="info-value">{{ $address->student_perm_state ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-upc-scan"></i> Permanent Pincode</div>
                            <div class="info-value">{{ $address->student_perm_pincode ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-heart-fill"></i> Communication Address Line1</div>
                            <div class="info-value">{{ $address->student_comm_address_line1 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-heart-fill"></i> Communication Address Line2</div>
                            <div class="info-value">{{ $address->student_comm_address_line2 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Communication City</div>
                            <div class="info-value">{{ $address->student_comm_city ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Communication State</div>
                            <div class="info-value">{{ $address->student_comm_state ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-upc-scan"></i> Communication Pincode</div>
                            <div class="info-value">{{ $address->student_comm_pincode ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PARENT ADDRESS --}}
            <div class="view-section">
                <div class="section-header parent-header">
                    <i class="bi bi-people-fill"></i>
                    Parent Address
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-door-fill"></i> Permanent Address Line1</div>
                            <div class="info-value">{{ $address->parent_perm_address_line1 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-door-fill"></i> Permanent Address Line2</div>
                            <div class="info-value">{{ $address->parent_perm_address_line2 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Permanent City</div>
                            <div class="info-value">{{ $address->parent_perm_city ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Permanent State</div>
                            <div class="info-value">{{ $address->parent_perm_state ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-upc-scan"></i> Permanent Pincode</div>
                            <div class="info-value">{{ $address->parent_perm_pincode ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-heart-fill"></i> Communication Address Line1</div>
                            <div class="info-value">{{ $address->parent_comm_address_line1 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-heart-fill"></i> Communication Address Line2</div>
                            <div class="info-value">{{ $address->parent_comm_address_line2 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Communication City</div>
                            <div class="info-value">{{ $address->parent_comm_city ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Communication State</div>
                            <div class="info-value">{{ $address->parent_comm_state ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-upc-scan"></i> Communication Pincode</div>
                            <div class="info-value">{{ $address->parent_comm_pincode ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GUARDIAN ADDRESS --}}
            <div class="view-section">
                <div class="section-header guardian-header">
                    <i class="bi bi-shield-fill-check"></i>
                    Guardian Address
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-door-fill"></i> Permanent Address Line1</div>
                            <div class="info-value">{{ $address->guardian_perm_address_line1 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-door-fill"></i> Permanent Address Line2</div>
                            <div class="info-value">{{ $address->guardian_perm_address_line2 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Permanent City</div>
                            <div class="info-value">{{ $address->guardian_perm_city ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Permanent State</div>
                            <div class="info-value">{{ $address->guardian_perm_state ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-upc-scan"></i> Permanent Pincode</div>
                            <div class="info-value">{{ $address->guardian_perm_pincode ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-heart-fill"></i> Communication Address Line1</div>
                            <div class="info-value">{{ $address->guardian_comm_address_line1 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-house-heart-fill"></i> Communication Address Line2</div>
                            <div class="info-value">{{ $address->guardian_comm_address_line2 ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Communication City</div>
                            <div class="info-value">{{ $address->guardian_comm_city ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-flag-fill"></i> Communication State</div>
                            <div class="info-value">{{ $address->guardian_comm_state ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-upc-scan"></i> Communication Pincode</div>
                            <div class="info-value">{{ $address->guardian_comm_pincode ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACADEMIC TAB --}}
        <div id="academic-tab" style="display:none">
            <div class="view-section">
                <div class="section-header">
                    <i class="bi bi-book-fill"></i>
                    Academic & Transport
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Department</div>
                            <div class="info-value">{{ $extra->department ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-tag-fill"></i> Course Type</div>
                            <div class="info-value">{{ $extra->course_type ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-diagram-3-fill"></i> Course Subtype</div>
                            <div class="info-value">{{ $extra->course_subtype ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-people-fill"></i> Batch</div>
                            <div class="info-value">{{ $extra->batch ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-calendar-fill"></i> Academic Year</div>
                            <div class="info-value">{{ $extra->academic_year ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-clock-fill"></i> Mode Type</div>
                            <div class="info-value">{{ $extra->mode_type ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-laptop-fill"></i> Mode Of Course</div>
                            <div class="info-value">{{ $extra->mode_of_course ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-bus-front-fill"></i> Uses Transport</div>
                            <div class="info-value">
                                {{ ($extra->uses_transport ?? 0) == 1 ? 'Yes' : 'No' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-sun-fill"></i> Morning Route Name</div>
                            <div class="info-value">{{ $extra->morning_route_name ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-moon-fill"></i> Evening Route Name</div>
                            <div class="info-value">{{ $extra->evening_route_name ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-sign-stop-fill"></i> Morning Stop</div>
                            <div class="info-value">{{ $extra->morning_stop ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-sign-stop-fill"></i> Evening Stop</div>
                            <div class="info-value">{{ $extra->evening_stop ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DOCUMENT TAB --}}
        <div id="documents-tab" style="display:none">
            {{-- STUDENT DOCUMENTS --}}
            <div class="view-section">
                <div class="section-header student-header">
                    <i class="bi bi-person-fill"></i>
                    Student Documents
                </div>
                <div class="section-body">
                    @php
                    $studentDocs = [
                        'Aadhaar Number' => ['value' => $documents->student_aadhaar_number ?? null, 'icon' => 'bi bi-qr-code'],
                        'Aadhaar File' => ['value' => $documents->student_aadhaar_file ?? null, 'icon' => 'bi bi-file-earmark-fill', 'is_file' => true],
                        'PAN Number' => ['value' => $documents->student_pan_number ?? null, 'icon' => 'bi bi-credit-card-fill'],
                        'PAN File' => ['value' => $documents->student_pan_file ?? null, 'icon' => 'bi bi-file-earmark-fill', 'is_file' => true],
                        'Photo' => ['value' => $documents->student_photo ?? null, 'icon' => 'bi bi-camera-fill', 'is_file' => true],
                        'ID Card' => ['value' => $documents->student_id_card ?? null, 'icon' => 'bi bi-person-badge-fill', 'is_file' => true],
                        'Address Proof' => ['value' => $documents->student_address_proof ?? null, 'icon' => 'bi bi-house-door-fill', 'is_file' => true],
                        'Birth Certificate' => ['value' => $documents->student_bonafide ?? null, 'icon' => 'bi bi-file-earmark-check-fill', 'is_file' => true],
                        'Academic Documents' => ['value' => $documents->academic_documents ?? null, 'icon' => 'bi bi-journal-bookmark-fill', 'is_file' => true],
                        'Prev Class Certificate' => ['value' => $documents->prev_class_certificate ?? null, 'icon' => 'bi bi-award-fill', 'is_file' => true],
                        '10th Marksheet' => ['value' => $documents->marksheet_10 ?? null, 'icon' => 'bi bi-file-text-fill', 'is_file' => true],
                        '12th Marksheet' => ['value' => $documents->marksheet_12 ?? null, 'icon' => 'bi bi-file-text-fill', 'is_file' => true],
                        'Bachelor Marksheet' => ['value' => $documents->bachelor_marksheet ?? null, 'icon' => 'bi bi-file-text-fill', 'is_file' => true],
                        'Other Documents' => ['value' => $documents->other_documents ?? null, 'icon' => 'bi bi-files-fill', 'is_file' => true],
                    ];
                    @endphp

                    <div class="documents-grid">
                        @foreach($studentDocs as $title => $data)
                            <div class="document-item student-doc">
                                <div class="document-content">
                                    <div class="document-info">
                                        <i class="{{ $data['icon'] }}"></i>
                                        <span title="{{ $title }}">{{ $title }}</span>
                                    </div>
                                    
                                    @if(!empty($data['value']))
                                        @if(isset($data['is_file']) && $data['is_file'])
                                            <a href="{{ asset('storage/'.$data['value']) }}" class="btn-view-compact" target="_blank">
                                                <i class="bi bi-eye-fill"></i> View
                                            </a>
                                        @else
                                            <span class="document-value" title="{{ $data['value'] }}">{{ $data['value'] }}</span>
                                        @endif
                                    @else
                                        <span class="document-not-uploaded" title="Not Uploaded">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            Not Uploaded
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- PARENT DOCUMENTS --}}
            <div class="view-section">
                <div class="section-header parent-header">
                    <i class="bi bi-people-fill"></i>
                    Parent Documents
                </div>
                <div class="section-body">
                    @php
                    $parentDocs = [
                        'Aadhaar Number' => ['value' => $documents->parent_aadhaar_number ?? null, 'icon' => 'bi bi-qr-code'],
                        'Aadhaar File' => ['value' => $documents->parent_aadhaar_file ?? null, 'icon' => 'bi bi-file-earmark-fill', 'is_file' => true],
                        'PAN Number' => ['value' => $documents->parent_pan_number ?? null, 'icon' => 'bi bi-credit-card-fill'],
                        'PAN File' => ['value' => $documents->parent_pan_file ?? null, 'icon' => 'bi bi-file-earmark-fill', 'is_file' => true],
                        'Income Proof' => ['value' => $documents->parent_income_proof ?? null, 'icon' => 'bi bi-cash-stack', 'is_file' => true],
                        'Photo' => ['value' => $documents->parent_photo ?? null, 'icon' => 'bi bi-camera-fill', 'is_file' => true],
                    ];
                    @endphp

                    <div class="documents-grid">
                        @foreach($parentDocs as $title => $data)
                            <div class="document-item parent-doc">
                                <div class="document-content">
                                    <div class="document-info">
                                        <i class="{{ $data['icon'] }}"></i>
                                        <span title="{{ $title }}">{{ $title }}</span>
                                    </div>
                                    
                                    @if(!empty($data['value']))
                                        @if(isset($data['is_file']) && $data['is_file'])
                                            <a href="{{ asset('storage/'.$data['value']) }}" class="btn-view-compact" target="_blank">
                                                <i class="bi bi-eye-fill"></i> View
                                            </a>
                                        @else
                                            <span class="document-value" title="{{ $data['value'] }}">{{ $data['value'] }}</span>
                                        @endif
                                    @else
                                        <span class="document-not-uploaded" title="Not Uploaded">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            Not Uploaded
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- GUARDIAN DOCUMENTS --}}
            <div class="view-section">
                <div class="section-header guardian-header">
                    <i class="bi bi-shield-fill-check"></i>
                    Guardian Documents
                </div>
                <div class="section-body">
                    @php
                    $guardianDocs = [
                        'Aadhaar Number' => ['value' => $documents->guardian_aadhaar_number ?? null, 'icon' => 'bi bi-qr-code'],
                        'Aadhaar File' => ['value' => $documents->guardian_aadhaar_file ?? null, 'icon' => 'bi bi-file-earmark-fill', 'is_file' => true],
                        'PAN Number' => ['value' => $documents->guardian_pan_number ?? null, 'icon' => 'bi bi-credit-card-fill'],
                        'PAN File' => ['value' => $documents->guardian_pan_file ?? null, 'icon' => 'bi bi-file-earmark-fill', 'is_file' => true],
                        'Income Proof' => ['value' => $documents->guardian_income_proof ?? null, 'icon' => 'bi bi-cash-stack', 'is_file' => true],
                        'Photo' => ['value' => $documents->guardian_photo ?? null, 'icon' => 'bi bi-camera-fill', 'is_file' => true],
                    ];
                    @endphp

                    <div class="documents-grid">
                        @foreach($guardianDocs as $title => $data)
                            <div class="document-item guardian-doc">
                                <div class="document-content">
                                    <div class="document-info">
                                        <i class="{{ $data['icon'] }}"></i>
                                        <span title="{{ $title }}">{{ $title }}</span>
                                    </div>
                                    
                                    @if(!empty($data['value']))
                                        @if(isset($data['is_file']) && $data['is_file'])
                                            <a href="{{ asset('storage/'.$data['value']) }}" class="btn-view-compact" target="_blank">
                                                <i class="bi bi-eye-fill"></i> View
                                            </a>
                                        @else
                                            <span class="document-value" title="{{ $data['value'] }}">{{ $data['value'] }}</span>
                                        @endif
                                    @else
                                        <span class="document-not-uploaded" title="Not Uploaded">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            Not Uploaded
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- BANK TAB --}}
        <div id="bank-tab" style="display:none">
            {{-- PARENT BANK DETAILS --}}
            <div class="view-section">
                <div class="section-header parent-header">
                    <i class="bi bi-people-fill"></i>
                    Parent Bank Details
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-person-fill"></i> Beneficiary Name</div>
                            <div class="info-value">{{ $bank->benificiary_name ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-credit-card-fill"></i> Account Number</div>
                            <div class="info-value">{{ $bank->bank_account_number ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Bank Name</div>
                            <div class="info-value">{{ $bank->bank_name ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-upc-scan"></i> IFSC Code</div>
                            <div class="info-value">{{ $bank->ifsc_code ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-list-ul"></i> Account Type</div>
                            <div class="info-value">{{ $bank->account_type ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-file-earmark-fill"></i> Cancelled Cheque</div>
                            <div class="info-value">
                                @if(!empty($bank->upload_cancelled_cheque))
                                    <a href="{{ asset('image/'.$bank->upload_cancelled_cheque) }}" class="btn-view-compact" target="_blank">
                                        <i class="bi bi-eye-fill"></i> View
                                    </a>
                                @else
                                    <span class="document-not-uploaded">Not Uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STUDENT BANK DETAILS --}}
            <div class="view-section">
                <div class="section-header student-header">
                    <i class="bi bi-person-fill"></i>
                    Student Bank Details
                </div>
                <div class="section-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-person-fill"></i> Beneficiary Name</div>
                            <div class="info-value">{{ $bank->student_benificiary_name ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-credit-card-fill"></i> Account Number</div>
                            <div class="info-value">{{ $bank->student_bank_account_number ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-building-fill"></i> Bank Name</div>
                            <div class="info-value">{{ $bank->student_bank_name ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-upc-scan"></i> IFSC Code</div>
                            <div class="info-value">{{ $bank->student_ifsc_code ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-list-ul"></i> Account Type</div>
                            <div class="info-value">{{ $bank->student_account_type ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="bi bi-file-earmark-fill"></i> Cancelled Cheque</div>
                            <div class="info-value">
                                @if(!empty($bank->student_upload_cancelled_cheque))
                                    <a href="{{ asset('image/'.$bank->student_upload_cancelled_cheque) }}" class="btn-view-compact" target="_blank">
                                        <i class="bi bi-eye-fill"></i> View
                                    </a>
                                @else
                                    <span class="document-not-uploaded">Not Uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
/* TAB SWITCH */
$('.view-tab').click(function(){
    $('.view-tab').removeClass('active');
    $(this).addClass('active');
    
    let tab = $(this).data('tab');
    
    $('[id$="-tab"]').hide();
    $('#' + tab + '-tab').show();
});

/* LOAD STUDENT DATA */
$(document).ready(function(){
    let hash_id = "{{ request()->route('hash_id') }}";
    
    $.get('/student-details/' + hash_id, function(res){
        $('#studentName').text(res.student.first_name + ' ' + res.student.last_name);
        $('#reg_no').text(res.student.registration);
        $('#full_name').text(res.student.first_name + ' ' + res.student.last_name);
        $('#dob').text(res.student.dob);
        $('#age').text(res.student.age);
        $('#mobile').text(res.student.mobile);
        $('#blood_group').text(res.student.blood_group);
        $('#email').text(res.student.email);
        $('#gender').text(res.student.gender);
        
        $('#parent_relation').text(res.student.parent_relation);
        $('#parent_name').text(res.student.parent_first_name + ' ' + res.student.parent_last_name);
        $('#parent_gender').text(res.student.parent_gender);
        $('#parent_dob').text(res.student.parent_dob);
        $('#parent_email').text(res.student.parent_email);
        $('#parent_phone').text(res.student.parent_phone);
        $('#parent_occupation').text(res.student.parent_occupation);
        $('#parent_income').text(res.student.parent_income);
        $('#alternate_phone').text(res.student.alternate_phone_number);
        
        /* ADDRESS */
        $('#perm_line1').text(res.address.student_perm_address_line1);
        $('#perm_line2').text(res.address.student_perm_address_line2);
        $('#perm_city').text(res.address.student_perm_city);
        $('#perm_state').text(res.address.student_perm_state);
        $('#perm_pin').text(res.address.student_perm_pincode);
        
        /* ACADEMIC */
        $('#institute_id').text(res.extra.institute_id);
        $('#course_type').text(res.extra.course_type);
        $('#course_subtype').text(res.extra.course_subtype);
        $('#session_id').text(res.extra.session_id);
        $('#semester_id').text(res.extra.semester_id);
        $('#section_id').text(res.extra.section_id);
        $('#uses_transport').text(res.extra.uses_transport);
        
        /* BANK */
        $('#beneficiary').text(res.bank.benificiary_name);
        $('#account_no').text(res.bank.bank_account_number);
        $('#bank_name').text(res.bank.bank_name);
        $('#ifsc').text(res.bank.ifsc_code);
        
        /* DOCUMENTS */
        let docsHtml = '';
        
        if(res.documents.student_photo){
            docsHtml += `
                <div class="document-item">
                    <div class="document-content">
                        <div class="document-info">
                            <i class="bi bi-camera-fill"></i>
                            <span>Student Photo</span>
                        </div>
                        <a href="/storage/${res.documents.student_photo}" target="_blank" class="btn-view-compact">
                            <i class="bi bi-eye-fill"></i> View
                        </a>
                    </div>
                </div>`;
        }
        
        if(res.documents.student_aadhaar_file){
            docsHtml += `
                <div class="document-item">
                    <div class="document-content">
                        <div class="document-info">
                            <i class="bi bi-qr-code"></i>
                            <span>Aadhaar</span>
                        </div>
                        <a href="/storage/${res.documents.student_aadhaar_file}" target="_blank" class="btn-view-compact">
                            <i class="bi bi-eye-fill"></i> View
                        </a>
                    </div>
                </div>`;
        }
        
        $('#documentsArea').html(docsHtml);
    });
});
</script>

@endsection