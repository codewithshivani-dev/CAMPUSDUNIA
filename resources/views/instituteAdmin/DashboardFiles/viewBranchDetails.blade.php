@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
@php
// Small helpers (safe)
function maskAadhaar($val){
if(!$val) return '';
$v = preg_replace('/\D/','',$val);
if(strlen($v) < 4) return $val; return 'XXXX-XXXX-' .substr($v, -4); } function maskPan($val){ if(!$val) return '' ;
    $v=trim($val); if(strlen($v) <=4) return $v; return substr($v,0,1).str_repeat('*', max(strlen($v)-5,
    3)).substr($v,-4); } function maskAccount($val){ if(!$val) return '' ; $v=preg_replace('/\s+/','',$val);
    if(strlen($v) <=4) return $v; return 'XXXX' .substr($v, -4); } // banner path fallback from documents
    $bannerPath=null; if(isset($branch->documents) && $branch->documents) {
    $doc = $branch->documents->first();
    if(!empty($doc->institute_image_path))
    $bannerPath = $doc->institute_image_path;
    elseif(!empty($doc->logo_path))
    $bannerPath = $doc->logo_path;
    elseif(!empty($doc->registration_document_path))
    $bannerPath = $doc->registration_document_path;
    }

    // group stakeholder docs by stakeholder_id
    $stakeholderDocsGrouped = [];
    foreach($branch->stakeholders ?? [] as $stakeholder){
    foreach($stakeholder->documents ?? [] as $doc){
    $key = $doc->stakeholder_id ?? 'unknown';
    $stakeholderDocsGrouped[$key][] = $doc;
    }
    }

    // group authorized docs by authorized_user_id
    $authDocsGrouped = [];
    foreach($branch->authorizedUser ?? [] as $authUser){
    foreach($authUser->documents ?? [] as $doc){
    $key = $doc->authorized_user_id ?? 'unknown';
    $authDocsGrouped[$key][] = $doc;
    }
    }
    @endphp

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

    /* Page Header - Enhanced */
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

    .add-btn {
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white !important;
        cursor: pointer;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        text-decoration: none;
    }

    .add-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        color: white !important;
    }

    /* Banner - Enhanced */
    .banner {
        height: 260px;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    }

    .banner img.bg {
        width: 100%;
        height: 260px;
        object-fit: cover;
        filter: brightness(0.8);
    }

    .banner .meta {
        position: absolute;
        left: 28px;
        bottom: 22px;
        background: rgba(255, 255, 255, 0.95);
        padding: 20px 30px;
        border-radius: 16px;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--primary-color);
        animation: slideInLeft 0.5s ease;
    }

    .banner .meta h3 {
        margin: 0;
        color: var(--primary-color);
        font-weight: 700;
    }

    .banner .meta p {
        margin: 0;
        color: #64748b;
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Main Tabs - Enhanced */
    .main-tabs {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .main-tabs .nav-item {
        min-width: 140px;
        max-width: max-content;
    }

    .main-tabs .nav-link {
        font-weight: 600;
        color: #475569;
        border: none;
        padding: 12px 20px;
        margin-right: 5px;
        border-radius: 10px 10px 0 0;
        transition: all 0.3s;
        cursor: pointer;
    }

    .main-tabs .nav-link:hover {
        color: var(--primary-color);
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }

    .main-tabs .nav-link.active {
        background: var(--primary-gradient);
        color: white;
        position: relative;
        box-shadow: 0 -5px 15px rgba(67, 97, 238, 0.15);
    }

    .main-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--primary-gradient);
        border-radius: 3px;
    }

    /* Sub Tabs - Enhanced */
    .subtabs {
        margin-bottom: 20px;
        gap: 5px;
        display: flex;
        flex-wrap: wrap;
    }

    .subtabs .nav-link {
        font-size: 0.9rem;
        color: #475569;
        border-radius: 30px;
        padding: 8px 16px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
        font-weight: 500;
        cursor: pointer;
        background: white;
    }

    .subtabs .nav-link:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
    }

    .subtabs .nav-link.active {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
    }

    /* Info Blocks - Enhanced */
    .info-block {
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .info-block:hover {
        border-color: var(--primary-color);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1);
    }

    .info-label {
        font-size: 0.82rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.3px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 1rem;
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 15px;
    }

    /* Document Cards - Enhanced */
    .document-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        gap: 20px;
        align-items: flex-start;
        flex-wrap: wrap;
        background: linear-gradient(135deg, #ffffff, #f8fafc);
        transition: all 0.3s;
    }

    .document-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1);
    }

    .doc-img {
        width: 160px;
        height: 120px;
        object-fit: cover;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 4px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        cursor: pointer;
        transition: all 0.3s;
    }

    .doc-img:hover {
        transform: scale(1.05);
        border-color: var(--primary-color);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.2);
    }

    .doc-label {
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #334155;
    }

    .no-data {
        color: #94a3b8;
        font-style: italic;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 12px 20px;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
        text-align: center;
    }

    /* Status Badge */
    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-suspended {
        background: #fff3cd;
        color: #856404;
    }

    .text-danger {
        color: #ef4444 !important;
        font-weight: 600;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        padding: 4px 12px;
        border-radius: 30px;
        display: inline-block;
        font-size: 12px;
    }

    .text-success {
        color: #10b981 !important;
        font-weight: 600;
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        padding: 4px 12px;
        border-radius: 30px;
        display: inline-block;
        font-size: 12px;
    }

    /* Modal Enhancements */
    .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-header {
        background: var(--primary-gradient);
        border-bottom: none;
        padding: 20px 25px;
    }

    .modal-title {
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
        transition: all 0.3s;
    }

    .modal-header .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 20px 25px;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        transition: all 0.3s;
        width: 100%;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .form-check-input:hover {
        border-color: var(--primary-color);
        transform: scale(1.1);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-label {
        color: #475569;
        font-weight: 500;
        cursor: pointer;
    }

    /* Alert Messages */
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
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        font-weight: 500;
        padding: 8px 16px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
    }

    .btn-success:hover {
        background: var(--success-gradient);
        color: white;
    }

    .btn-danger {
        background: var(--danger-gradient);
        color: white;
    }

    .btn-danger:hover {
        background: var(--danger-gradient);
        color: white;
    }

    .btn-outline-primary {
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-outline-primary:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }

    /* Links */
    a {
        color: var(--primary-color);
        text-decoration: none;
        transition: all 0.3s;
    }

    a:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }

        .banner {
            height: 200px;
        }

        .banner .meta {
            left: 15px;
            right: 15px;
            padding: 15px;
        }

        .banner .meta h3 {
            font-size: 18px;
        }

        .document-card {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .main-tabs .nav-link {
            padding: 10px 12px;
            font-size: 13px;
        }

        .subtabs {
            flex-wrap: wrap;
        }

        .subtabs .nav-link {
            font-size: 12px;
            padding: 6px 12px;
        }

        .doc-img {
            width: 120px;
            height: 100px;
        }
    }
    </style>

    <div class="page-container container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h4 class="page-title">
                <i class="bi bi-building-fill"></i>
                Branch Details: {{ $branch->name ?? 'N/A' }}
            </h4>
            <a href="{{ route('admin.branch.index') }}" class="add-btn">
                <i class="bi bi-arrow-left"></i> Back to Branches
            </a>
        </div>

        <!-- Top banner -->
        <div class="banner mb-4">
            @if(!empty($bannerPath))
            <img src="{{ asset('/image/'.$bannerPath) }}" alt="Institute Image" class="bg" />
            @else
            <img src="https://via.placeholder.com/1600x500?text=Branch+Image" alt="Branch Image" class="bg" />
            @endif

            <div class="meta">
                <h3 style="margin: 0">
                    <i class="bi bi-building-fill me-2" style="color: var(--primary-color);"></i>
                    {{ $branch->name ?? 'Branch Name' }}
                </h3>
                <p style="margin: 0; color: #444">
                    <i class="bi bi-geo-alt-fill me-1"></i>
                    {{ $branch->city ?? '' }}, {{ $branch->state ?? '' }}
                    <span class="badge-status badge-{{ $branch->status ?? 'active' }} ms-2">
                        {{ ucfirst($branch->status ?? 'Active') }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Main Tabs -->
        <ul class="nav nav-tabs main-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#pane-institute" role="tab">
                    <i class="bi bi-building-fill me-2"></i>Branch Details
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#pane-stake" role="tab">
                    <i class="bi bi-people-fill me-2"></i>Stakeholder Details
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#pane-auth" role="tab">
                    <i class="bi bi-person-badge-fill me-2"></i>Authorized Person Details
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#pane-docs" role="tab">
                    <i class="bi bi-file-earmark-text-fill me-2"></i>Documents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#pane-bank" role="tab">
                    <i class="bi bi-bank me-2"></i>Beneficiary Details
                </a>
            </li>
        </ul>

        <div class="tab-content">
            {{-- INSTITUTE DETAILS TAB --}}
            <div class="tab-pane fade show active" id="pane-institute" role="tabpanel">
                <div class="info-block">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-tag-fill me-1"></i> Branch Code</p>
                            <p class="info-value">{{ $branch->fincap_merchant_id ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-building-fill me-1"></i> Branch Name</p>
                            <p class="info-value">{{ $branch->name ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-tag-fill me-1"></i> Type</p>
                            <p class="info-value">{{ $branch->type ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-card-heading me-1"></i> Registration Type</p>
                            <p class="info-value">{{ $branch->registration_type ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-envelope-fill me-1"></i> Email</p>
                            <p class="info-value">{{ $branch->email ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-telephone-fill me-1"></i> Contact Number</p>
                            <p class="info-value">{{ $branch->contact_number ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-geo-alt-fill me-1"></i> State</p>
                            <p class="info-value">{{ $branch->state ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-building me-1"></i> City</p>
                            <p class="info-value">{{ $branch->city ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-mailbox me-1"></i> Pincode</p>
                            <p class="info-value">{{ $branch->pincode ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-signpost-2-fill me-1"></i> Address Line 1</p>
                            <p class="info-value">{{ $branch->address_line_1 ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-signpost-2 me-1"></i> Address Line 2</p>
                            <p class="info-value">{{ $branch->address_line_2 ?? '—' }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-globe me-1"></i> Website</p>
                            <p class="info-value">
                                @if($branch->website)
                                <a href="{{ $branch->website }}" target="_blank">
                                    {{ $branch->website }}
                                </a>
                                @else
                                N/A
                                @endif
                            </p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-calendar-fill me-1"></i> Establishment Date</p>
                            <p class="info-value">
                                {{ $branch->establishment_date ? date('d M Y', strtotime($branch->establishment_date)) : 'N/A' }}
                            </p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-check-circle-fill me-1"></i> Status</p>
                            <p class="info-value">
                                <span class="badge-status badge-{{ $branch->status ?? 'active' }}">
                                    {{ ucfirst($branch->status ?? 'Active') }}
                                </span>
                            </p>
                        </div>

                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-clock-fill me-1"></i> Created At</p>
                            <p class="info-value">
                                {{ $branch->created_at ? $branch->created_at->format('d M Y h:i A') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STAKEHOLDERS --}}
            <div class="tab-pane fade" id="pane-stake" role="tabpanel">
                <ul class="nav nav-pills subtabs mb-3" role="tablist">
                    @forelse($branch->stakeholders ?? [] as $si => $stake)
                    <li class="nav-item">
                        <a class="nav-link @if($si==0) active @endif" data-bs-toggle="pill"
                            href="#stake-{{ $stake->id }}" role="tab">
                            <i class="bi bi-person-fill me-1"></i>{{ $stake->name }}
                        </a>
                    </li>
                    @empty
                    <li class="nav-item">
                        <span class="no-data">No stakeholders</span>
                    </li>
                    @endforelse
                </ul>

                <div class="tab-content">
                    @forelse($branch->stakeholders ?? [] as $si => $stake)
                    <div class="tab-pane fade @if($si==0) show active @endif" id="stake-{{ $stake->id }}"
                        role="tabpanel">
                        <div class="info-block">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-person-fill me-1"></i> Name</p>
                                    <p class="info-value">{{ $stake->name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-envelope-fill me-1"></i> Email</p>
                                    <p class="info-value">{{ $stake->email }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-telephone-fill me-1"></i> Phone</p>
                                    <p class="info-value">{{ $stake->phone_number }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-briefcase-fill me-1"></i> Designation</p>
                                    <p class="info-value">{{ ucfirst($stake->designation) }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-credit-card-fill me-1"></i> PAN</p>
                                    <p class="info-value">{{ maskPan($stake->pan_number ?? '') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar</p>
                                    <p class="info-value">{{ maskAadhaar($stake->aadhaar_number ?? '') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>No stakeholder data available.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- AUTHORIZED USERS --}}
            <div class="tab-pane fade" id="pane-auth" role="tabpanel">
                <ul class="nav nav-pills subtabs mb-3" role="tablist">
                    @forelse($branch->authorizedUser ?? [] as $ai => $au)
                    <li class="nav-item">
                        <a class="nav-link @if($ai==0) active @endif" data-bs-toggle="pill" href="#auth-{{ $au->id }}"
                            role="tab">
                            <i class="bi bi-person-badge-fill me-1"></i>{{ $au->name }}
                        </a>
                    </li>
                    @empty
                    <li class="nav-item">
                        <span class="no-data">No authorized users</span>
                    </li>
                    @endforelse
                </ul>

                <div class="tab-content">
                    @forelse($branch->authorizedUser ?? [] as $ai => $au)
                    <div class="tab-pane fade @if($ai==0) show active @endif" id="auth-{{ $au->id }}" role="tabpanel">
                        <div class="info-block">
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-person-fill me-1"></i> Name</p>
                                    <p class="info-value">{{ $au->name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-envelope-fill me-1"></i> Email</p>
                                    <p class="info-value">{{ $au->email }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-telephone-fill me-1"></i> Phone</p>
                                    <p class="info-value">{{ $au->phone_number }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-briefcase-fill me-1"></i> Designation</p>
                                    <p class="info-value">{{ ucfirst($au->designation) }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-credit-card-fill me-1"></i> PAN</p>
                                    <p class="info-value">{{ maskPan($au->pan_number ?? '') }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar</p>
                                    <p class="info-value">{{ maskAadhaar($au->aadhaar_number ?? '') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle-fill me-2"></i>No authorized user data available.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- DOCUMENTS TAB --}}
            <div class="tab-pane fade" id="pane-docs" role="tabpanel">
                @php
                $placeholder = "data:image/svg+xml;base64," . base64_encode('
                <svg width="200" height="150" xmlns="http://www.w3.org/2000/svg">
                    <rect width="200" height="150" fill="#e3e3e3" />
                    <text x="50%" y="50%" font-size="14" fill="#555" dominant-baseline="middle" text-anchor="middle">
                        No Preview
                    </text>
                </svg>
                ');
                @endphp

                {{-- TOP SUB-TABS --}}
                <ul class="nav nav-pills subtabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="pill" href="#docs-institute">
                            <i class="bi bi-building-fill me-1"></i>Institute Documents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#docs-stakeholders">
                            <i class="bi bi-people-fill me-1"></i>Stakeholder Documents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#docs-authorized">
                            <i class="bi bi-person-badge-fill me-1"></i>Authorized Documents
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    {{-- Institute Documents --}}
                    <div class="tab-pane fade show active" id="docs-institute">
                        @if($branch->documents->count())
                        @php
                        $doc = $branch->documents->first();
                        @endphp
                        {{-- Registration Document --}}
                        @if($doc->registration_document_path)
                        <div class="document-card">
                            <div style="flex: 1;">
                                <div class="doc-label"><i class="bi bi-qr-code me-1"></i> Registration Document</div>
                                <div class="info-value">Registration Number: {{ $doc->registration_number ?? 'N/A' }}
                                </div>
                            </div>
                            @php
                            $filePath = $doc->registration_document_path;
                            $extension = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : null;
                            @endphp
                            @if($filePath && in_array($extension, ['jpg', 'jpeg', 'png']))
                            <div onclick="openZoomPreview('{{ asset('/image/'.$filePath) }}', 'reg-{{ $doc->id }}')"
                                style="cursor: pointer;">
                                <img src="{{ asset('/image/'.$filePath) }}" onerror="this.src='{{ $placeholder }}'"
                                    class="doc-img" alt="Registration Document">
                                <div id="verify-status-reg-{{ $doc->id }}" class="text-danger mt-2">
                                    <i class="bi bi-clock-fill me-1"></i>Verify
                                </div>
                            </div>
                            @elseif($filePath && $extension === 'pdf')
                            <a href="{{ asset('/image/'.$filePath) }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-file-pdf-fill me-1"></i> View PDF
                            </a>
                            @else
                            <div onclick="openZoomPreview('{{ $placeholder }}', 'reg-{{ $doc->id }}')"
                                style="cursor: pointer;">
                                <img src="{{ $placeholder }}" class="doc-img" alt="No Document">
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- PAN Document --}}
                        @if($doc->pan_document_path)
                        <div class="document-card">
                            <div style="flex: 1;">
                                <div class="doc-label"><i class="bi bi-credit-card-fill me-1"></i> PAN Document</div>
                                <div class="info-value">PAN Number: {{ $doc->pan_number ?? 'N/A' }}</div>
                            </div>
                            @php
                            $filePath = $doc->pan_document_path;
                            $extension = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : null;
                            @endphp
                            @if($filePath && in_array($extension, ['jpg', 'jpeg', 'png']))
                            <div onclick="openZoomPreview('{{ asset('/image/'.$filePath) }}', 'pan-{{ $doc->id }}')"
                                style="cursor: pointer;">
                                <img src="{{ asset('/image/'.$filePath) }}" onerror="this.src='{{ $placeholder }}'"
                                    class="doc-img" alt="PAN Document">
                                <div id="verify-status-pan-{{ $doc->id }}" class="text-danger mt-2">
                                    <i class="bi bi-clock-fill me-1"></i>Verify
                                </div>
                            </div>
                            @elseif($filePath && $extension === 'pdf')
                            <a href="{{ asset('/image/'.$filePath) }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-file-pdf-fill me-1"></i> View PDF
                            </a>
                            @else
                            <div onclick="openZoomPreview('{{ $placeholder }}', 'pan-{{ $doc->id }}')"
                                style="cursor: pointer;">
                                <img src="{{ $placeholder }}" class="doc-img" alt="No Document">
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- GST Document --}}
                        @if($doc->gst_document_path)
                        <div class="document-card">
                            <div style="flex: 1;">
                                <div class="doc-label"><i class="bi bi-file-earmark-text me-1"></i> GST Document</div>
                                <div class="info-value">GST Number: {{ $doc->gst_number ?? 'N/A' }}</div>
                            </div>
                            @php
                            $filePath = $doc->gst_document_path;
                            $extension = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : null;
                            @endphp
                            @if($filePath && in_array($extension, ['jpg', 'jpeg', 'png']))
                            <div onclick="openZoomPreview('{{ asset('/image/'.$filePath) }}', 'gst-{{ $doc->id }}')"
                                style="cursor: pointer;">
                                <img src="{{ asset('/image/'.$filePath) }}" onerror="this.src='{{ $placeholder }}'"
                                    class="doc-img" alt="GST Document">
                                <div id="verify-status-gst-{{ $doc->id }}" class="text-danger mt-2">
                                    <i class="bi bi-clock-fill me-1"></i>Verify
                                </div>
                            </div>
                            @elseif($filePath && $extension === 'pdf')
                            <a href="{{ asset('/image/'.$filePath) }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-file-pdf-fill me-1"></i> View PDF
                            </a>
                            @else
                            <div onclick="openZoomPreview('{{ $placeholder }}', 'gst-{{ $doc->id }}')"
                                style="cursor: pointer;">
                                <img src="{{ $placeholder }}" class="doc-img" alt="No Document">
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Logo --}}
                        @if($doc->logo_path)
                        <div class="document-card">
                            <div style="flex: 1;">
                                <div class="doc-label"><i class="bi bi-image-fill me-1"></i> Institute Logo</div>
                            </div>
                            @php
                            $filePath = $doc->logo_path;
                            $extension = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : null;
                            @endphp
                            @if($filePath && in_array($extension, ['jpg', 'jpeg', 'png']))
                            <div onclick="openZoomPreview('{{ asset('/image/'.$filePath) }}', 'logo-{{ $doc->id }}')"
                                style="cursor: pointer;">
                                <img src="{{ asset('/image/'.$filePath) }}" onerror="this.src='{{ $placeholder }}'"
                                    class="doc-img" alt="Logo">
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Institute Image --}}
                        @if($doc->institute_image_path)
                        <div class="document-card">
                            <div style="flex: 1;">
                                <div class="doc-label"><i class="bi bi-building-fill me-1"></i> Institute Image</div>
                            </div>
                            @php
                            $filePath = $doc->institute_image_path;
                            $extension = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : null;
                            @endphp
                            @if($filePath && in_array($extension, ['jpg', 'jpeg', 'png']))
                            <div onclick="openZoomPreview('{{ asset('/image/'.$filePath) }}', 'image-{{ $doc->id }}')"
                                style="cursor: pointer;">
                                <img src="{{ asset('/image/'.$filePath) }}" onerror="this.src='{{ $placeholder }}'"
                                    class="doc-img" alt="Institute Image">
                            </div>
                            @endif
                        </div>
                        @endif

                        @else
                        <p class="no-data">No documents uploaded for this branch.</p>
                        @endif
                    </div>

                    {{-- Stakeholder Documents --}}
                    {{-- Stakeholder Documents Section --}}
                    <div class="tab-pane fade" id="docs-stakeholders">
                        <ul class="nav nav-tabs mb-3">
                            @forelse($branch->stakeholders ?? [] as $si => $stk)
                            <li class="nav-item">
                                <a class="nav-link @if($si==0) active @endif" data-bs-toggle="tab"
                                    href="#docs-stake-{{ $stk->id }}">
                                    <i class="bi bi-person-fill me-1"></i>{{ $stk->name }}
                                </a>
                            </li>
                            @empty
                            <p class="no-data">No stakeholders.</p>
                            @endforelse
                        </ul>

                        <div class="tab-content">
                            @forelse($branch->stakeholders ?? [] as $si => $stk)
                            <div class="tab-pane fade @if($si==0) show active @endif" id="docs-stake-{{ $stk->id }}">
                                @php
                                // ✅ FIX: Handle both hasOne and hasMany
                                $sdocs = [];
                                if (method_exists($stk, 'documents')) {
                                $relation = $stk->documents();
                                if ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                                $sdocs = $stk->documents ?? [];
                                } elseif ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne) {
                                $sdocs = $stk->documents ? [$stk->documents] : [];
                                }
                                }

                                // ✅ Alternative: Use a helper function
                                // $sdocs = is_iterable($stk->documents) ? $stk->documents :
                                // ($stk->documents ? [$stk->documents] : []);
                                @endphp

                                @if(count($sdocs) > 0)
                                @foreach($sdocs as $s)
                                <div class="document-card">
                                    @if($s->aadhaar_front_path)
                                    <div onclick="openZoomPreview('{{ asset('/image/'.$s->aadhaar_front_path) }}', 'stake-front-{{ $s->id }}')"
                                        style="cursor: pointer;">
                                        <div class="doc-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar Front
                                        </div>
                                        <img src="{{ asset('/image/'.$s->aadhaar_front_path) }}" class="doc-img" />
                                        <div id="verify-status-stake-front-{{ $s->id }}" class="text-danger mt-2">
                                            <i class="bi bi-clock-fill me-1"></i>Verify
                                        </div>
                                    </div>
                                    @endif

                                    @if($s->aadhaar_back_path)
                                    <div onclick="openZoomPreview('{{ asset('/image/'.$s->aadhaar_back_path) }}', 'stake-back-{{ $s->id }}')"
                                        style="cursor: pointer;">
                                        <div class="doc-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar Back</div>
                                        <img src="{{ asset('/image/'.$s->aadhaar_back_path) }}" class="doc-img" />
                                        <div id="verify-status-stake-back-{{ $s->id }}" class="text-danger mt-2">
                                            <i class="bi bi-clock-fill me-1"></i>Verify
                                        </div>
                                    </div>
                                    @endif

                                    @if($s->pan_document_path)
                                    <div onclick="openZoomPreview('{{ asset('/image/'.$s->pan_document_path) }}', 'stake-pan-{{ $s->id }}')"
                                        style="cursor: pointer;">
                                        <div class="doc-label"><i class="bi bi-credit-card-fill me-1"></i> PAN</div>
                                        <img src="{{ asset('/image/'.$s->pan_document_path) }}" class="doc-img" />
                                        <div id="verify-status-stake-pan-{{ $s->id }}" class="text-danger mt-2">
                                            <i class="bi bi-clock-fill me-1"></i>Verify
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                                @else
                                <p class="no-data">No documents for this stakeholder.</p>
                                @endif
                            </div>
                            @empty
                            <p class="no-data">No stakeholders available.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Authorized User Documents Section --}}
                    <div class="tab-pane fade" id="docs-authorized">
                        <ul class="nav nav-tabs mb-3">
                            @forelse($branch->authorizedUser ?? [] as $ai => $au)
                            <li class="nav-item">
                                <a class="nav-link @if($ai==0) active @endif" data-bs-toggle="tab"
                                    href="#docs-auth-{{ $au->id }}">
                                    <i class="bi bi-person-badge-fill me-1"></i>{{ $au->name }}
                                </a>
                            </li>
                            @empty
                            <p class="no-data">No authorized users.</p>
                            @endforelse
                        </ul>

                        <div class="tab-content">
                            @forelse($branch->authorizedUser ?? [] as $ai => $au)
                            <div class="tab-pane fade @if($ai==0) show active @endif" id="docs-auth-{{ $au->id }}">
                                @php
                                // ✅ FIX: Handle both hasOne and hasMany
                                $ads = [];
                                if (method_exists($au, 'documents')) {
                                $relation = $au->documents();
                                if ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                                $ads = $au->documents ?? [];
                                } elseif ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOne) {
                                $ads = $au->documents ? [$au->documents] : [];
                                }
                                }
                                @endphp

                                @if(count($ads) > 0)
                                @foreach($ads as $a)
                                <div class="document-card">
                                    @if($a->aadhaar_front_path)
                                    <div onclick="openZoomPreview('{{ asset('/image/'.$a->aadhaar_front_path) }}', 'auth-front-{{ $a->id }}')"
                                        style="cursor: pointer;">
                                        <div class="doc-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar Front
                                        </div>
                                        <img src="{{ asset('/image/'.$a->aadhaar_front_path) }}" class="doc-img" />
                                        <div id="verify-status-auth-front-{{ $a->id }}" class="text-danger mt-2">
                                            <i class="bi bi-clock-fill me-1"></i>Verify
                                        </div>
                                    </div>
                                    @endif

                                    @if($a->aadhaar_back_path)
                                    <div onclick="openZoomPreview('{{ asset('/image/'.$a->aadhaar_back_path) }}', 'auth-back-{{ $a->id }}')"
                                        style="cursor: pointer;">
                                        <div class="doc-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar Back</div>
                                        <img src="{{ asset('/image/'.$a->aadhaar_back_path) }}" class="doc-img" />
                                        <div id="verify-status-auth-back-{{ $a->id }}" class="text-danger mt-2">
                                            <i class="bi bi-clock-fill me-1"></i>Verify
                                        </div>
                                    </div>
                                    @endif

                                    @if($a->pan_document_path)
                                    <div onclick="openZoomPreview('{{ asset('/image/'.$a->pan_document_path) }}', 'auth-pan-{{ $a->id }}')"
                                        style="cursor: pointer;">
                                        <div class="doc-label"><i class="bi bi-credit-card-fill me-1"></i> PAN</div>
                                        <img src="{{ asset('/image/'.$a->pan_document_path) }}" class="doc-img" />
                                        <div id="verify-status-auth-pan-{{ $a->id }}" class="text-danger mt-2">
                                            <i class="bi bi-clock-fill me-1"></i>Verify
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                                @else
                                <p class="no-data">No documents for this authorized user.</p>
                                @endif
                            </div>
                            @empty
                            <p class="no-data">No authorized users available.</p>
                            @endforelse
                        </div>
                    </div>


                </div>
            </div>
            {{-- BANK DETAILS --}}
            <div class="tab-pane fade" id="pane-bank" role="tabpanel">
                @if($branch->beneficiary)
                <div class="info-block">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-bank-fill me-1"></i> Bank Name</p>
                            <p class="info-value">{{ $branch->beneficiary->bank_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-person-fill me-1"></i> Beneficiary Name</p>
                            <p class="info-value">{{ $branch->beneficiary->beneficiary_name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-credit-card-fill me-1"></i> Account Number</p>
                            <p class="info-value">
                                {{ maskAccount($branch->beneficiary->account_number ?? '') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-qr-code me-1"></i> IFSC Code</p>
                            <p class="info-value">{{ $branch->beneficiary->ifsc_code ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-tag-fill me-1"></i> Account Type</p>
                            <p class="info-value">{{ $branch->beneficiary->account_type ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-check-circle-fill me-1"></i> Terms Agreed</p>
                            <p class="info-value">
                                <span
                                    class="badge-status {{ ($branch->beneficiary->terms_agreed ?? 0) ? 'badge-active' : 'badge-inactive' }}">
                                    {{ ($branch->beneficiary->terms_agreed ?? 0) ? 'Yes' : 'No' }}
                                </span>
                            </p>
                        </div>

                        <div class="col-md-12">
                            <p class="info-label"><i class="bi bi-file-earmark-fill me-1"></i> Cancelled Cheque
                            </p>
                            @if(!empty($branch->beneficiary->cancelled_cheque_path))
                            <div onclick="openZoomPreview('{{ asset('/image/'.$branch->beneficiary->cancelled_cheque_path) }}', 'cheque-{{ $branch->beneficiary->id }}')"
                                style="cursor: pointer; display: inline-block;">
                                <img src="{{ asset('/image/'.$branch->beneficiary->cancelled_cheque_path) }}"
                                    style="max-width: 200px; border-radius: 12px; border: 2px solid #e2e8f0; padding: 4px;" />
                            </div>
                            @else
                            <p class="no-data">No cheque uploaded</p>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <p class="no-data">No bank/beneficiary details available.</p>
                @endif
            </div>
            <!-- ========================
                ZOOM PREVIEW MODAL
            ========================= -->
            <div class="modal fade" id="zoomPreviewModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-image-fill me-2"></i>Document Preview
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body text-center">
                            <img id="zoomPreviewImage" src=""
                                style="max-width: 100%; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);" />
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-success" onclick="openOtpFromZoom()" id="verifyFromZoomBtn">
                                <i class="bi bi-check-circle-fill me-1"></i> Verify This Document
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================
                OTP VERIFICATION MODAL
            ========================= -->
            <div class="modal fade" id="otpVerifyModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-shield-lock-fill me-2"></i>Document Verification
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="otpVerificationForm">
                            <div class="modal-body">
                                <label class="fw-bold"><i class="bi bi-key-fill me-1"></i> OTP</label>
                                <input type="number" id="otpInput" class="form-control mb-3"
                                    placeholder="Enter 6-digit OTP" />

                                <label class="fw-bold"><i class="bi bi-person-fill me-1"></i> Verifier Name</label>
                                <input type="text" id="verifierName" class="form-control mb-3"
                                    placeholder="Enter name" />

                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="agreeTerms" />
                                    <label class="form-check-label">I agree to the Terms & Conditions</label>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-check-circle-fill me-1"></i> Verify Document
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
            /* ===== Helpers: show/hide modal compatible with BS5 & BS4 (jQuery) ===== */
            function showModalById(id) {
                const el = document.getElementById(id);
                if (!el) return;
                if (typeof bootstrap !== "undefined" && typeof bootstrap.Modal === "function") {
                    const modal = new bootstrap.Modal(el);
                    modal.show();
                    return;
                }
                if (window.jQuery) {
                    $("#" + id).modal("show");
                }
            }

            function hideModalById(id) {
                const el = document.getElementById(id);
                if (!el) return;
                if (typeof bootstrap !== "undefined" && typeof bootstrap.Modal === "function") {
                    const inst = bootstrap.Modal.getInstance(el);
                    if (inst) {
                        inst.hide();
                        return;
                    }
                    try {
                        const tmp = new bootstrap.Modal(el);
                        tmp.hide();
                        return;
                    } catch (err) {}
                }
                if (window.jQuery) {
                    $("#" + id).modal("hide");
                }
            }

            /* ===== Main flow variables ===== */
            let currentDocId = "";
            let currentDocUrl = "";

            /* OPEN ZOOM PREVIEW MODAL + PASS DOC ID */
            function openZoomPreview(url, docId) {
                currentDocId = docId || "";
                currentDocUrl = url || "";
                const img = document.getElementById("zoomPreviewImage");
                if (img) {
                    img.src = url || "";
                    img.alt = "Document preview";
                }
                showModalById("zoomPreviewModal");
            }

            /* CLICK VERIFY BUTTON INSIDE ZOOM → OPEN OTP MODAL */
            function openOtpFromZoom() {
                if (!currentDocId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Document Selected',
                        text: 'Please select a document to verify.',
                        confirmButtonColor: '#4361ee',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const form = document.getElementById("otpVerificationForm");
                if (form) form.setAttribute("data-doc-id", currentDocId);

                hideModalById("zoomPreviewModal");
                setTimeout(function() {
                    showModalById("otpVerifyModal");
                }, 150);
            }

            /* OTP SUBMISSION */
            (function() {
                const otpForm = document.getElementById("otpVerificationForm");
                if (!otpForm) return;

                otpForm.addEventListener("submit", async function(e) {
                    e.preventDefault();

                    const otpInput = document.getElementById("otpInput");
                    const verifierInput = document.getElementById("verifierName");
                    const termsInput = document.getElementById("agreeTerms");

                    const otp = otpInput ? String(otpInput.value).trim() : "";
                    const verifierName = verifierInput ? String(verifierInput.value).trim() : "";
                    const terms = termsInput ? termsInput.checked : false;
                    const docId = this.getAttribute("data-doc-id") || "";

                    if (!verifierName) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Missing Information',
                            text: 'Please enter the verifier name.',
                            confirmButtonColor: '#4361ee'
                        });
                        verifierInput.focus();
                        return;
                    }

                    if (!terms) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Terms & Conditions',
                            text: 'You must agree to the Terms & Conditions.',
                            confirmButtonColor: '#4361ee'
                        });
                        return;
                    }

                    if (otp.length !== 6) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Invalid OTP',
                            text: 'OTP must be exactly 6 digits.',
                            confirmButtonColor: '#4361ee'
                        });
                        otpInput.focus();
                        return;
                    }

                    Swal.fire({
                        title: 'Verifying Document...',
                        text: 'Please wait while we verify the document',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Simulate API call
                    setTimeout(async () => {
                        const statusBox = document.getElementById("verify-status-" + docId);
                        if (statusBox) {
                            statusBox.innerHTML =
                                '<i class="bi bi-check-circle-fill me-1"></i> Verified';
                            statusBox.classList.remove("text-danger");
                            statusBox.classList.add("text-success");
                        }

                        hideModalById("otpVerifyModal");
                        otpForm.reset();
                        currentDocId = "";
                        currentDocUrl = "";

                        await Swal.fire({
                            icon: 'success',
                            title: 'Document Verified!',
                            text: 'The document has been successfully verified.',
                            confirmButtonColor: '#4361ee',
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }, 1500);
                });
            })();
    </script>
@endsection