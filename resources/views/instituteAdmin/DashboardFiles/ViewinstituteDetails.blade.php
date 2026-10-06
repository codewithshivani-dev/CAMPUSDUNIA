@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
@php
    // Small helpers (safe)
    function maskAadhaar($val){
        if(!$val) return '';
        $v = preg_replace('/\D/','',$val);
        if(strlen($v) < 4)
        return $val;
        return 'XXXX-XXXX-'.substr($v, -4);
    }
    
    function maskPan($val){
        if(!$val) return ''; $v = trim($val);
        if(strlen($v) <= 4) return $v;
        return
        substr($v,0,1).str_repeat('*', max(strlen($v)-5, 3)).substr($v,-4);
    }

    function maskAccount($val){
        if(!$val) return '';
        $v = preg_replace('/\s+/','',$val);
        if(strlen($v) <= 4)
            return $v;
        return 'XXXX'.substr($v, -4);
    }

    // banner path fallback from documents
    $bannerPath = null;
    if(isset($fincapMerchants->documents) && method_exists($fincapMerchants->documents, 'count') && $fincapMerchants->documents->count()) {
        $firstDoc = $fincapMerchants->documents->first();
        if(!empty($firstDoc->institute_image_path))
            $bannerPath = $firstDoc->institute_image_path;
        elseif(!empty($firstDoc->logo_path))
            $bannerPath = $firstDoc->logo_path;
        elseif(!empty($firstDoc->registration_document_path))
            $bannerPath = $firstDoc->registration_document_path;
    }

    // group stakeholder docs by stakeholder_id
    $stakeholderDocsGrouped = [];
    foreach($fincapMerchants->stakeholderDocuments ?? [] as $sd){
        $key = $sd->stakeholder_id ?? 'unknown';
        $stakeholderDocsGrouped[$key][] = $sd;
    }

    //group authorized docs by authorized_user_id
    $authDocsGrouped = [];
    foreach($fincapMerchants->authorizedUserDocuments ?? [] as $ad){
        $key = $ad->authorized_user_id ?? 'unknown';
        $authDocsGrouped[$key][] = $ad;
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
    }
    
    .main-tabs .nav-item{
        min-width: 140px;
        max-width: max-content;
    }

    .main-tabs .nav-link {
        font-weight: 600;
        color: #475569;
        border: none;
        margin-right: 5px;
        border-radius: 10px 10px 0 0;
        transition: all 0.3s;
    }
    
    a{
        text-decoration: none !important;
    }

    .main-tabs .nav-link:hover {
        color: var(--primary-color);
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    }

    .main-tabs .nav-link.active {
        background: var(--primary-gradient);
        color: white;
        position: relative;
    }

    .main-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--primary-gradient);
    }

    /* Sub Tabs - Enhanced */
    .subtabs {
        margin-bottom: 20px;
        gap: 5px;
    }

    .subtabs .nav-link {
        font-size: 0.9rem;
        color: #475569;
        border-radius: 30px;
        padding: 8px 16px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
        font-weight: 500;
    }

    .subtabs .nav-link:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
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
    }

    .no-data {
        color: #94a3b8;
        font-style: italic;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 12px 20px;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
    }

    .no-preview-img {
        width: 140px;
        height: 100px;
        object-fit: contain;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 10px;
        color: #64748b;
    }

    /* Buttons - Enhanced */
    .btn {
        border-radius: 10px;
        font-weight: 500;
        padding: 8px 16px;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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

    .btn-outline-primary {
        border: 2px solid #4361ee;
        color: #4361ee;
        background: transparent;
    }

    .btn-outline-primary:hover {
        background: #4361ee;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.2);
    }

    .btn-outline-danger {
        border: 2px solid #ef4444;
        color: #ef4444;
        background: transparent;
    }

    .btn-outline-danger:hover {
        background: #ef4444;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2);
    }

    /* Stamp & Signature Image Styles */
    .stamp-image, .signature-image {
        background: transparent !important;
    }

    .upload-preview {
        min-height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border-radius: 8px;
        border: 2px dashed #cbd5e1;
        padding: 10px;
        position: relative;
        transition: all 0.3s;
    }

    .upload-preview.has-image {
        background-image: 
            linear-gradient(45deg, #e5e7eb 25%, transparent 25%),
            linear-gradient(-45deg, #e5e7eb 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, #e5e7eb 75%),
            linear-gradient(-45deg, transparent 75%, #e5e7eb 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        border-color: #94a3b8;
    }

    .upload-preview img {
        max-width: 100%;
        max-height: 100px;
        object-fit: contain;
        background: transparent !important;
        display: block;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px;
    }

    .placeholder-preview {
        width: 100%;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 14px;
        text-align: center;
        padding: 10px;
    }

    .placeholder-preview i {
        font-size: 32px;
        margin-bottom: 5px;
    }

    /* Status Text */
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
        filter: invert(1);
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
            gap: 16px;
        }

        .banner .meta {
            left: 15px;
            right: 15px;
            padding: 15px;
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
    }
</style>

<div class="page-container container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h4 class="page-title">
            <i class="bi bi-building-fill"></i>
            Institute Details
        </h4>
    </div>

    <!-- Top banner -->
    <div class="banner mb-4">
        @if(!empty($bannerPath))
        <img
            src="{{ asset('/image/'.$bannerPath) }}"
            alt="Institute Image"
            class="bg"
        />
        @else
        <img
            src="https://via.placeholder.com/1600x500?text=Institute+Image"
            alt="Institute Image"
            class="bg"
        />
        @endif

        <div class="meta">
            <h3 style="margin: 0">
                <i class="bi bi-building-fill me-2" style="color: var(--primary-color);"></i>
                {{ $fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? 'Institute Name' }}
            </h3>
            <p style="margin: 0; color: #444">
                <i class="bi bi-geo-alt-fill me-1"></i>
                {{ $fincapMerchants->state ?? $fincapMerchants->fincap_merchant_state ?? '' }},
                {{ $fincapMerchants->city ?? $fincapMerchants->fincap_merchant_city ?? '' }}
            </p>
        </div>
    </div>

    <ul class="nav nav-tabs main-tabs" role="tablist">
        <li class="nav-item">
            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
            <a class="nav-link active" data-bs-toggle="tab" href="#pane-institute" role="tab">
                <i class="bi bi-building-fill me-2"></i>School Details
            </a>
            @else
            <a class="nav-link active" data-bs-toggle="tab" href="#pane-institute" role="tab">
                <i class="bi bi-building-fill me-2"></i>Institute Details
            </a>
            @endif
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
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#pane-sign" role="tab">
                <i class="bi bi-pen-fill me-2"></i>Stamp & E-Sign
            </a>
        </li>
    </ul>

    <div class="tab-content">
        {{-- INSTITUTE DETAILS TAB --}}
        <div class="tab-pane fade show active" id="pane-institute" role="tabpanel">
            <div class="info-block">
                <div class="row">
                    <div class="col-md-4">
                        <p class="info-label">
                            @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                            <i class="bi bi-building-fill me-1"></i> School Name
                            @else
                            <i class="bi bi-building-fill me-1"></i> Institute Name
                            @endif
                        </p>
                        <p class="info-value">{{ $fincapMerchants->name }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-tag-fill me-1"></i> Type</p>
                        <p class="info-value">{{ $fincapMerchants->type }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-card-heading me-1"></i> Registration Type</p>
                        <p class="info-value">{{ $fincapMerchants->registration_type }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-envelope-fill me-1"></i> Email</p>
                        <p class="info-value">{{ $fincapMerchants->email }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-telephone-fill me-1"></i> Contact Number</p>
                        <p class="info-value">{{ $fincapMerchants->contact_number }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-geo-alt-fill me-1"></i> State</p>
                        <p class="info-value">{{ $fincapMerchants->state }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-building me-1"></i> City</p>
                        <p class="info-value">{{ $fincapMerchants->city }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-mailbox me-1"></i> Pincode</p>
                        <p class="info-value">{{ $fincapMerchants->pincode }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-signpost-2-fill me-1"></i> Address Line 1</p>
                        <p class="info-value">{{ $fincapMerchants->address_line_1 }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-signpost-2 me-1"></i> Address Line 2</p>
                        <p class="info-value">{{ $fincapMerchants->address_line_2 ?? '—' }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-globe me-1"></i> Website</p>
                        <p class="info-value">
                            <a href="{{ $fincapMerchants->website }}" target="_blank">
                                {{ $fincapMerchants->website }}
                            </a>
                        </p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-calendar-fill me-1"></i> Establishment Date</p>
                        <p class="info-value">{{ $fincapMerchants->establishment_date }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-check-circle-fill me-1"></i> Status</p>
                        <p class="info-value">
                            @if($fincapMerchants->status == 'active')
                            <span class="text-success">{{ ucfirst($fincapMerchants->status) }}</span>
                            @else
                            <span class="text-danger">{{ ucfirst($fincapMerchants->status) }}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- STAKEHOLDERS (each has subtabs by name) --}}
        <div class="tab-pane fade" id="pane-stake" role="tabpanel">
            <ul class="nav nav-pills subtabs mb-3" role="tablist">
                @forelse($fincapMerchants->stakeholders ?? [] as $si => $stake)
                <li class="nav-item">
                    <a class="nav-link @if($si==0) active @endif" data-bs-toggle="pill" href="#stake-{{ $stake->id }}" role="tab">
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
                @forelse($fincapMerchants->stakeholders ?? [] as $si => $stake)
                <div class="tab-pane fade @if($si==0) show active @endif" id="stake-{{ $stake->id }}" role="tabpanel">
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
                                <p class="info-value">{{ $stake->designation }}</p>
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

        {{-- AUTHORIZED USERS (subtabs per authorized user) --}}
        <div class="tab-pane fade" id="pane-auth" role="tabpanel">
            <ul class="nav nav-pills subtabs mb-3" role="tablist">
                @forelse($fincapMerchants->authorizedUser ?? [] as $ai => $au)
                <li class="nav-item">
                    <a class="nav-link @if($ai==0) active @endif" data-bs-toggle="pill" href="#auth-{{ $au->id }}" role="tab">
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
                @forelse($fincapMerchants->authorizedUser ?? [] as $ai => $au)
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
                                <p class="info-value">{{ $au->designation }}</p>
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
            @php $stakeholderDocsGrouped =
            collect($fincapMerchants->stakeholderDocuments ??
            [])->groupBy('stakeholder_id'); $authDocsGrouped =
            collect($fincapMerchants->authorizedDocuments ??
            [])->groupBy('authorized_id'); $placeholder =
            "data:image/svg+xml;base64," . base64_encode('
            <svg width="200" height="150" xmlns="http://www.w3.org/2000/svg">
                <rect width="200" height="150" fill="#e3e3e3" />
                <text
                    x="50%"
                    y="50%"
                    font-size="14"
                    fill="#555"
                    dominant-baseline="middle"
                    text-anchor="middle"
                >
                    No Preview
                </text>
            </svg>
            '); @endphp

            {{-- TOP SUB-TABS --}}
            <ul class="nav nav-pills subtabs mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" href="#docs-institute">
                        <i class="bi bi-building-fill me-1"></i>Institute
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#docs-stakeholders">
                        <i class="bi bi-people-fill me-1"></i>Stakeholders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="pill" href="#docs-authorized">
                        <i class="bi bi-person-badge-fill me-1"></i>Authorized
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                {{-- =============================
                    INSTITUTE DOCUMENTS
                ============================== --}}
                <div class="tab-pane fade show active" id="docs-institute">
                    @forelse($fincapMerchants->documents ?? [] as $doc)
                    <div class="document-card">
                        <div style="flex: 1">
                            <div class="doc-label"><i class="bi bi-qr-code me-1"></i> Registration Number</div>
                            <div class="info-value">{{ $doc->registration_number ?? '—' }}</div>

                            <div class="doc-label mt-3"><i class="bi bi-credit-card-fill me-1"></i> PAN Number</div>
                            <div class="info-value">{{ $doc->pan_number ?? '—' }}</div>
                        </div>

                        @php
                            $filePath = $doc->registration_document_path ?? null;
                            $extension = $filePath ? strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) : null;
                        @endphp

                        @if($filePath && in_array($extension, ['jpg', 'jpeg', 'png']))
                            {{-- Show Image --}}
                            <div onclick="openZoomPreview('{{ asset('/image/'.$filePath) }}', 'institute-{{ $doc->id }}')" style="cursor: pointer;">
                                <img src="{{ asset('/image/'.$filePath) }}" onerror="this.src='{{ $placeholder }}'" class="doc-img" alt="Document Image">
                                </br>
                                <div id="verify-status-institute-{{ $doc->id }}" class="text-danger mt-2">
                                    <i class="bi bi-clock-fill me-1"></i>Vefify
                                </div>
                            </div>
                        @elseif($filePath && $extension === 'pdf')
                            {{-- Show PDF Button --}}
                            <a href="{{ asset('/image/'.$filePath) }}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-file-pdf-fill me-1"></i> View Document
                            </a>
                        @else
                            {{-- Placeholder --}}
                            <div onclick="openZoomPreview('{{ $placeholder }}', 'institute-{{ $doc->id }}')" style="cursor: pointer;">
                                <img src="{{ $placeholder }}" class="doc-img" alt="No Document">
                            </div>
                        @endif
                    </div>
                    @empty
                    @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                    <p class="no-data">No School documents uploaded.</p>
                    @else
                    <p class="no-data">No Institute documents uploaded.</p>
                    @endif
                    @endforelse
                </div>

                {{-- =============================
                    STAKEHOLDER DOCUMENTS
                ============================== --}}
                <div class="tab-pane fade" id="docs-stakeholders">
                    {{-- Sub-tabs --}}
                    <ul class="nav nav-tabs mb-3">
                        @forelse($fincapMerchants->stakeholders ?? [] as $si => $stk)
                        <li class="nav-item">
                            <a class="nav-link @if($si==0) active @endif" data-bs-toggle="tab" href="#docs-stake-{{ $stk->id }}">
                                <i class="bi bi-person-fill me-1"></i>{{ $stk->name }}
                            </a>
                        </li>
                        @empty
                        <p class="no-data">No stakeholders.</p>
                        @endforelse
                    </ul>

                    <div class="tab-content">
                        @forelse($fincapMerchants->stakeholders ?? [] as $si => $stk)
                        <div class="tab-pane fade @if($si==0) show active @endif" id="docs-stake-{{ $stk->id }}">
                            @php $sdocs = $stakeholderDocsGrouped[$stk->id] ?? []; @endphp 
                            @if(count($sdocs)) 
                            @foreach($sdocs as $s)
                            <div class="document-card">
                                {{-- Aadhaar Front (STATIC) --}}
                                <div onclick="openZoomPreview('{{ asset('/image/'.$s->aadhaar_front_path) }}', 'aadhaar-front-{{ $s->id }}')" style="cursor: pointer;">
                                    <div class="doc-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar Front</div>
                                    <img src="{{ asset('/image/'.$s->aadhaar_front_path) }}" class="doc-img" />
                                    </br>
                                    <div id="verify-status-aadhaar-front-{{ $s->id }}" class="text-danger mt-2">
                                        Vefify
                                    </div>
                                </div>

                                {{-- Aadhaar Back --}}
                                <div onclick="openZoomPreview('{{ asset('/image/'.$s->aadhaar_back_path) }}', 'aadhaar-back-{{ $s->id }}')" style="cursor: pointer;">
                                    <div class="doc-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar Back</div>
                                    <img src="{{ asset('/image/'.$s->aadhaar_back_path) }}" class="doc-img" />
                                    </br>
                                    <div id="verify-status-aadhaar-back-{{ $s->id }}" class="text-danger mt-2">
                                        Vefify
                                    </div>
                                </div>

                                {{-- PAN --}}
                                <div onclick="openZoomPreview('{{ asset('/image/'.$s->pan_document_path) }}', 'pan-{{ $s->id }}')" style="cursor: pointer;">
                                    <div class="doc-label"><i class="bi bi-credit-card-fill me-1"></i> PAN</div>
                                    <img src="{{ asset('/image/'.$s->pan_document_path) }}" class="doc-img" />
                                    </br>
                                    <div id="verify-status-pan-{{ $s->id }}" class="text-danger mt-2">
                                        Vefify
                                    </div>
                                </div>
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

                {{-- =============================
                    AUTHORIZED USER DOCUMENTS
                ============================== --}}
                <div class="tab-pane fade" id="docs-authorized">
                    {{-- Sub-tabs --}}
                    <ul class="nav nav-tabs mb-3">
                        @forelse($fincapMerchants->authorizedUser ?? [] as $ai => $au)
                        <li class="nav-item">
                            <a class="nav-link @if($ai==0) active @endif" data-bs-toggle="tab" href="#docs-auth-{{ $au->id }}">
                                <i class="bi bi-person-badge-fill me-1"></i>{{ $au->name }}
                            </a>
                        </li>
                        @empty
                        <p class="no-data">No authorized users.</p>
                        @endforelse
                    </ul>

                    <div class="tab-content">
                        @forelse($fincapMerchants->authorizedUser ?? [] as $ai => $au)
                        <div class="tab-pane fade @if($ai==0) show active @endif" id="docs-auth-{{ $au->id }}">
                            @php $ads = $authDocsGrouped[$au->id] ?? []; @endphp
                            @if(count($ads)) 
                            @foreach($ads as $a)
                            <div class="document-card">
                                {{-- Aadhaar Front --}}
                                <div onclick="openZoomPreview('{{ asset('/image/'.$a->aadhaar_front_path) }}', 'auth-front-{{ $a->id }}')" style="cursor: pointer;">
                                    <div class="doc-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar Front</div>
                                    <img src="{{ asset('/image/'.$a->aadhaar_front_path) }}" class="doc-img" />
                                    <div id="verify-status-auth-front-{{ $a->id }}" class="text-danger mt-2">
                                        <i class="bi bi-clock-fill me-1"></i>Vefify
                                    </div>
                                </div>

                                {{-- Aadhaar Back --}}
                                <div onclick="openZoomPreview('{{ asset('/image/'.$a->aadhaar_back_path) }}', 'auth-back-{{ $a->id }}')" style="cursor: pointer;">
                                    <div class="doc-label"><i class="bi bi-fingerprint me-1"></i> Aadhaar Back</div>
                                    <img src="{{ asset('/image/'.$a->aadhaar_back_path) }}" class="doc-img" />
                                    <div id="verify-status-auth-back-{{ $a->id }}" class="text-danger mt-2">
                                        <i class="bi bi-clock-fill me-1"></i>Vefify
                                    </div>
                                </div>

                                {{-- PAN --}}
                                <div onclick="openZoomPreview('{{ asset('/image/'.$a->pan_document_path) }}', 'auth-pan-{{ $a->id }}')" style="cursor: pointer;">
                                    <div class="doc-label"><i class="bi bi-credit-card-fill me-1"></i> PAN</div>
                                    <img src="{{ asset('/image/'.$a->pan_document_path) }}" class="doc-img" />
                                    <div id="verify-status-auth-pan-{{ $a->id }}" class="text-danger mt-2">
                                        <i class="bi bi-clock-fill me-1"></i>Vefify
                                    </div>
                                </div>
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
                        <img id="zoomPreviewImage" src="" style="max-width: 100%; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);" />
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
                            <input type="number" id="otpInput" class="form-control mb-3" placeholder="Enter 6-digit OTP" />

                            <label class="fw-bold"><i class="bi bi-person-fill me-1"></i> Verifier Name</label>
                            <input type="text" id="verifierName" class="form-control mb-3" placeholder="Enter name" />

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

        {{-- BANK DETAILS (in your data beneficiary used for bank) --}}
        <div class="tab-pane fade" id="pane-bank" role="tabpanel">
            @if($fincapMerchants->beneficiary)
            <div class="info-block">
                <div class="row">
                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-bank-fill me-1"></i> Bank Name</p>
                        <p class="info-value">{{ $fincapMerchants->beneficiary->bank_name }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-person-fill me-1"></i> Beneficiary Name</p>
                        <p class="info-value">{{ $fincapMerchants->beneficiary->beneficiary_name }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-credit-card-fill me-1"></i> Account Number</p>
                        <p class="info-value">{{ maskAccount($fincapMerchants->beneficiary->account_number ?? '') }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-qr-code me-1"></i> IFSC</p>
                        <p class="info-value">{{ $fincapMerchants->beneficiary->ifsc_code }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-tag-fill me-1"></i> Account Type</p>
                        <p class="info-value">{{ $fincapMerchants->beneficiary->account_type }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="info-label"><i class="bi bi-file-earmark-fill me-1"></i> Cancelled Cheque</p>
                        @if(!empty($fincapMerchants->beneficiary->cancelled_cheque_path))
                        <img src="{{ asset('/image/'.$fincapMerchants->beneficiary->cancelled_cheque_path) }}" style="max-width: 150px; border-radius: 12px; border: 2px solid #e2e8f0; padding: 4px;" />
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

        {{-- Stamp & E-Sign --}}
        <div class="tab-pane fade" id="pane-sign" role="tabpanel">
            @if($fincapMerchants->authorizedUser && $fincapMerchants->authorizedUser->count() > 0)
                @foreach($fincapMerchants->authorizedUser as $authorizedUser)
                @php
                    // Get the authorized user documents
                    $authorizedDoc = $authorizedUser->documents ?? null;
                    $stampPath = $authorizedDoc->stamp_path ?? null;
                    $signPath = $authorizedDoc->signature_path ?? null;
                    
                    // Handle stamp path - check if file exists in public folder
                    $stampUrl = null;
                    if($stampPath && file_exists(public_path($stampPath))) {
                        $stampUrl = asset($stampPath);
                    }
                    
                    // Handle signature path - check if file exists in public folder
                    $signUrl = null;
                    if($signPath && file_exists(public_path($signPath))) {
                        $signUrl = asset($signPath);
                    }
                @endphp

                <div class="info-block">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="d-flex flex-wrap">
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-person-badge"></i> Authorized Person</p>
                                    <p class="info-value">{{ $authorizedUser->name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="info-label"><i class="bi bi-briefcase-fill me-1"></i> Designation</p>
                                    <p class="info-value">{{ $authorizedUser->designation }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Stamp Upload Section --}}
                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-stamp"></i> Stamp</p>
                            <div class="upload-container" data-type="stamp" data-authorized-id="{{ $authorizedUser->id }}">
                                <div class="upload-preview mb-2 @if($stampUrl) has-image @endif">
                                    @if($stampUrl)
                                        <img src="{{ $stampUrl }}?t={{ time() }}" 
                                             class="stamp-image" 
                                             alt="Stamp"
                                             id="stamp-preview-{{ $authorizedUser->id }}"
                                             style="background: transparent; max-width: 140px; max-height: 120px; object-fit: contain; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px;">
                                    @else
                                        <div class="placeholder-preview" id="stamp-placeholder-{{ $authorizedUser->id }}">
                                            <i class="bi bi-image"></i>
                                            No Stamp Uploaded
                                        </div>
                                    @endif
                                </div>
                                <div class="upload-controls">
                                    <input type="file" class="stamp-file-input d-none" accept="image/*" id="stamp-input-{{ $authorizedUser->id }}">
                                    <button type="button" class="btn btn-sm btn-outline-primary upload-stamp-btn" data-target="stamp-input-{{ $authorizedUser->id }}">
                                        <i class="bi bi-cloud-upload"></i> Upload Stamp
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger clear-stamp-btn d-none" data-authorized-id="{{ $authorizedUser->id }}">
                                        <i class="bi bi-trash"></i> Clear
                                    </button>
                                </div>
                                <small class="text-muted">Recommended: Square image with transparent background</small>
                                <div class="upload-feedback text-danger mt-1" style="font-size: 0.8rem;"></div>
                            </div>
                        </div>

                        {{-- Signature Upload Section --}}
                        <div class="col-md-4">
                            <p class="info-label"><i class="bi bi-pen-fill me-1"></i> Authorized Signatory</p>
                            <div class="upload-container" data-type="signature" data-authorized-id="{{ $authorizedUser->id }}">
                                <div class="upload-preview mb-2 @if($signUrl) has-image @endif">
                                    @if($signUrl)
                                        <img src="{{ $signUrl }}?t={{ time() }}" 
                                             class="signature-image" 
                                             alt="Signature"
                                             id="signature-preview-{{ $authorizedUser->id }}"
                                             style="background: transparent; max-width: 160px; max-height: 80px; object-fit: contain; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px;">
                                    @else
                                        <div class="placeholder-preview" id="signature-placeholder-{{ $authorizedUser->id }}">
                                            <i class="bi bi-pen"></i>
                                            No Signature Uploaded
                                        </div>
                                    @endif
                                </div>
                                <div class="upload-controls">
                                    <input type="file" class="signature-file-input d-none" accept="image/*" id="signature-input-{{ $authorizedUser->id }}">
                                    <button type="button" class="btn btn-sm btn-outline-primary upload-signature-btn" data-target="signature-input-{{ $authorizedUser->id }}">
                                        <i class="bi bi-cloud-upload"></i> Upload Signature
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger clear-signature-btn d-none" data-authorized-id="{{ $authorizedUser->id }}">
                                        <i class="bi bi-trash"></i> Clear
                                    </button>
                                </div>
                                <small class="text-muted">Recommended: White background with dark signature</small>
                                <div class="upload-feedback text-danger mt-1" style="font-size: 0.8rem;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <p class="no-data">No authorized users found. Please add authorized user first.</p>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    /* ===== Helpers: show/hide modal compatible with BS5 & BS4 (jQuery) ===== */
    function showModalById(id) {
        const el = document.getElementById(id);
        if (!el) return;
        if (
            typeof bootstrap !== "undefined" &&
            typeof bootstrap.Modal === "function"
        ) {
            // Bootstrap 5 style
            const modal = new bootstrap.Modal(el);
            modal.show();
            return;
        }
        // Fallback to jQuery (Bootstrap 4)
        if (window.jQuery) {
            $("#" + id).modal("show");
        }
    }

    function hideModalById(id) {
        const el = document.getElementById(id);
        if (!el) return;
        // Prefer Bootstrap 5 getInstance if available
        if (
            typeof bootstrap !== "undefined" &&
            typeof bootstrap.Modal === "function" &&
            typeof bootstrap.Modal.getInstance === "function"
        ) {
            const inst = bootstrap.Modal.getInstance(el);
            if (inst) {
                inst.hide();
                return;
            }
        }
        // If Bootstrap 5 getInstance not available, try creating instance and hiding
        if (
            typeof bootstrap !== "undefined" &&
            typeof bootstrap.Modal === "function"
        ) {
            try {
                const tmp = new bootstrap.Modal(el);
                tmp.hide();
                return;
            } catch (err) {
                // ignore and fallback to jQuery
            }
        }
        // jQuery fallback (Bootstrap 4)
        if (window.jQuery) {
            $("#" + id).modal("hide");
        }
    }

    /* ===== Main flow variables ===== */
    let currentDocId = "";
    let currentDocUrl = "";

    /* OPEN ZOOM PREVIEW MODAL + PASS DOC ID */
    window.openZoomPreview = function(url, docId) {
        currentDocId = docId || "";
        currentDocUrl = url || "";
        const img = document.getElementById("zoomPreviewImage");
        if (img) {
            img.src = url || "";
            img.alt = "Document preview";
        }
        showModalById("zoomPreviewModal");
    };

    /* CLICK VERIFY BUTTON INSIDE ZOOM → OPEN OTP MODAL */
    window.openOtpFromZoom = function() {
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
        setTimeout(function () {
            showModalById("otpVerifyModal");
        }, 150);
    };

    /* OTP SUBMISSION */
    (function () {
        const otpForm = document.getElementById("otpVerificationForm");
        if (!otpForm) return;

        otpForm.addEventListener("submit", async function (e) {
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

            setTimeout(async () => {
                const statusBox = document.getElementById("verify-status-" + docId);
                if (statusBox) {
                    statusBox.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Status: Verified';
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

    /* ===== STAMP UPLOAD FUNCTIONALITY ===== */
    
    // Handle upload button click for stamp
    $('.upload-stamp-btn').on('click', function() {
        const targetInput = $(this).data('target');
        $('#' + targetInput).trigger('click');
    });
    
    // Handle file selection for stamp
    $('.stamp-file-input').on('change', function(e) {
        const file = e.target.files[0];
        const container = $(this).closest('.upload-container');
        const authorizedUserId = container.data('authorized-id');
        const feedbackDiv = container.find('.upload-feedback');
        
        if (!file) return;
        
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload JPG or PNG files only.',
                confirmButtonColor: '#4361ee'
            });
            feedbackDiv.text('Invalid file type. Please upload JPG or PNG.');
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'File size exceeds 2MB limit.',
                confirmButtonColor: '#4361ee'
            });
            feedbackDiv.text('File size exceeds 2MB limit.');
            return;
        }
        
        feedbackDiv.text('');
        
        Swal.fire({
            title: 'Uploading...',
            text: 'Processing stamp',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('authorized_user_id', authorizedUserId);
        formData.append('_token', '{{ csrf_token() }}');
        
        $.ajax({
            url: '{{ route("institute.upload.stamp") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    const previewContainer = container.find('.upload-preview');
                    previewContainer.addClass('has-image');
                    const previewHtml = `<img src="${response.file_url}?t=${Date.now()}" 
                        class="stamp-image" 
                        alt="Stamp"
                        style="background: transparent; max-width: 140px; max-height: 120px; object-fit: contain; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px;">`;
                    previewContainer.html(previewHtml);
                    
                    container.find('.clear-stamp-btn').removeClass('d-none');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Upload Successful!',
                        text: response.message,
                        confirmButtonColor: '#4361ee',
                        timer: 2000,
                        timerProgressBar: true
                    });
                    
                    feedbackDiv.removeClass('text-danger').addClass('text-success').text('Uploaded successfully!');
                    setTimeout(() => {
                        feedbackDiv.text('');
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: response.message || 'Upload failed. Please try again.',
                        confirmButtonColor: '#4361ee'
                    });
                    feedbackDiv.text(response.message || 'Upload failed. Please try again.');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Upload failed. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Error',
                    text: errorMsg,
                    confirmButtonColor: '#4361ee'
                });
                feedbackDiv.text(errorMsg);
            },
            complete: function() {
                Swal.close();
                container.find('.stamp-file-input').val('');
            }
        });
    });
    
    // Handle clear button for stamp
    $('.clear-stamp-btn').on('click', function() {
        const btn = $(this);
        const authorizedUserId = btn.data('authorized-id');
        const container = btn.closest('.upload-container');
        const feedbackDiv = container.find('.upload-feedback');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;
            
            Swal.fire({
                title: 'Deleting...',
                text: 'Removing stamp',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: '{{ route("institute.delete.stamp") }}',
                method: 'DELETE',
                data: {
                    authorized_user_id: authorizedUserId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const previewContainer = container.find('.upload-preview');
                        previewContainer.removeClass('has-image');
                        const placeholderHtml = `<div class="placeholder-preview">
                            <i class="bi bi-image"></i>
                            No Stamp Uploaded
                        </div>`;
                        previewContainer.html(placeholderHtml);
                        
                        btn.addClass('d-none');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            confirmButtonColor: '#4361ee',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        
                        feedbackDiv.removeClass('text-danger').addClass('text-success').text('Removed successfully!');
                        setTimeout(() => feedbackDiv.text(''), 3000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Deletion Failed',
                            text: response.message || 'Removal failed. Please try again.',
                            confirmButtonColor: '#4361ee'
                        });
                        feedbackDiv.text(response.message || 'Removal failed. Please try again.');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Removal failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg,
                        confirmButtonColor: '#4361ee'
                    });
                    feedbackDiv.text(errorMsg);
                },
                complete: function() {
                    Swal.close();
                }
            });
        });
    });

    /* ===== SIGNATURE UPLOAD FUNCTIONALITY ===== */
    
    // Handle upload button click for signature
    $('.upload-signature-btn').on('click', function() {
        const targetInput = $(this).data('target');
        $('#' + targetInput).trigger('click');
    });
    
    // Handle file selection for signature
    $('.signature-file-input').on('change', function(e) {
        const file = e.target.files[0];
        const container = $(this).closest('.upload-container');
        const authorizedUserId = container.data('authorized-id');
        const feedbackDiv = container.find('.upload-feedback');
        
        if (!file) return;
        
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!allowedTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload JPG or PNG files only.',
                confirmButtonColor: '#4361ee'
            });
            feedbackDiv.text('Invalid file type. Please upload JPG or PNG.');
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'File size exceeds 2MB limit.',
                confirmButtonColor: '#4361ee'
            });
            feedbackDiv.text('File size exceeds 2MB limit.');
            return;
        }
        
        feedbackDiv.text('');
        
        Swal.fire({
            title: 'Uploading...',
            text: 'Processing signature',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('authorized_user_id', authorizedUserId);
        formData.append('_token', '{{ csrf_token() }}');
        
        $.ajax({
            url: '{{ route("institute.upload.signature") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    const previewContainer = container.find('.upload-preview');
                    previewContainer.addClass('has-image');
                    const previewHtml = `<img src="${response.file_url}?t=${Date.now()}" 
                        class="signature-image" 
                        alt="Signature"
                        style="background: transparent; max-width: 160px; max-height: 80px; object-fit: contain; border: 2px solid #e2e8f0; border-radius: 8px; padding: 4px;">`;
                    previewContainer.html(previewHtml);
                    
                    container.find('.clear-signature-btn').removeClass('d-none');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Upload Successful!',
                        text: response.message,
                        confirmButtonColor: '#4361ee',
                        timer: 2000,
                        timerProgressBar: true
                    });
                    
                    feedbackDiv.removeClass('text-danger').addClass('text-success').text('Uploaded successfully!');
                    setTimeout(() => {
                        feedbackDiv.text('');
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: response.message || 'Upload failed. Please try again.',
                        confirmButtonColor: '#4361ee'
                    });
                    feedbackDiv.text(response.message || 'Upload failed. Please try again.');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Upload failed. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Error',
                    text: errorMsg,
                    confirmButtonColor: '#4361ee'
                });
                feedbackDiv.text(errorMsg);
            },
            complete: function() {
                Swal.close();
                container.find('.signature-file-input').val('');
            }
        });
    });
    
    // Handle clear button for signature
    $('.clear-signature-btn').on('click', function() {
        const btn = $(this);
        const authorizedUserId = btn.data('authorized-id');
        const container = btn.closest('.upload-container');
        const feedbackDiv = container.find('.upload-feedback');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) return;
            
            Swal.fire({
                title: 'Deleting...',
                text: 'Removing signature',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            $.ajax({
                url: '{{ route("institute.delete.signature") }}',
                method: 'DELETE',
                data: {
                    authorized_user_id: authorizedUserId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        const previewContainer = container.find('.upload-preview');
                        previewContainer.removeClass('has-image');
                        const placeholderHtml = `<div class="placeholder-preview">
                            <i class="bi bi-pen"></i>
                            No Signature Uploaded
                        </div>`;
                        previewContainer.html(placeholderHtml);
                        
                        btn.addClass('d-none');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            confirmButtonColor: '#4361ee',
                            timer: 2000,
                            timerProgressBar: true
                        });
                        
                        feedbackDiv.removeClass('text-danger').addClass('text-success').text('Removed successfully!');
                        setTimeout(() => feedbackDiv.text(''), 3000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Deletion Failed',
                            text: response.message || 'Removal failed. Please try again.',
                            confirmButtonColor: '#4361ee'
                        });
                        feedbackDiv.text(response.message || 'Removal failed. Please try again.');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Removal failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMsg,
                        confirmButtonColor: '#4361ee'
                    });
                    feedbackDiv.text(errorMsg);
                },
                complete: function() {
                    Swal.close();
                }
            });
        });
    });
    
    // Show clear button if preview exists
    $('.upload-container').each(function() {
        const hasPreview = $(this).find('.upload-preview img').length > 0;
        if (hasPreview) {
            $(this).find('.clear-stamp-btn, .clear-signature-btn').removeClass('d-none');
            $(this).find('.upload-preview').addClass('has-image');
        }
    });
});
</script>
@endsection