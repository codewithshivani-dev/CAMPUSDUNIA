@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@section('content')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-glow: 0 0 20px rgba(67, 97, 238, 0.3);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        body {
            background: #f8fafc;
        }
        
        .page-header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: white;
            padding: 1.4rem 2rem;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid #e8ecf1;
        }
        
        .title-section h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }
        
        .title-section h1 i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 1.8rem;
        }

        .title-section p { 
            font-size: 0.85rem; 
            color: #64748b; 
            margin-top: 6px; 
            font-weight: 500; 
        }
        
        .stats-badge {
            background: linear-gradient(135deg, #f0f4ff, #e8edff);
            padding: 0.7rem 1.6rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--primary-color);
            box-shadow: 0 2px 8px rgba(67, 97, 238, 0.1);
            border: 1px solid rgba(67, 97, 238, 0.2);
            display: flex;
            gap: 24px;
        }
        
        .stats-badge span i { 
            margin-right: 8px; 
            color: var(--primary-color); 
        }

        /* Image Modal Styles */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            animation: fadeIn 0.3s ease;
        }

        .image-modal-content {
            position: relative;
            background: white;
            margin: 3% auto;
            padding: 0;
            width: 90%;
            max-width: 900px;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .image-modal-header {
            padding: 1.2rem 1.5rem;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .image-modal-header i {
            font-size: 1.5rem;
        }

        .image-modal-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }

        .image-modal-close {
            position: absolute;
            right: 20px;
            top: 15px;
            color: white;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            z-index: 10;
        }

        .image-modal-close:hover {
            color: #ffd700;
            transform: scale(1.1);
        }

        .image-modal-body {
            padding: 1.5rem;
            text-align: center;
            background: #f8fafc;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .certificate-image {
            max-width: 100%;
            max-height: 70vh;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            object-fit: contain;
        }

        .image-loading {
            text-align: center;
            padding: 3rem;
            color: var(--primary-color);
        }

        .image-loading i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .image-loading p {
            font-size: 0.9rem;
            color: #64748b;
        }

        .image-error {
            text-align: center;
            padding: 3rem;
            color: #dc2626;
            background: #fef2f2;
            border-radius: 12px;
        }

        .image-error i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .image-modal-content {
                width: 95%;
                margin: 10% auto;
            }
            .certificate-image {
                max-height: 60vh;
            }
        }

        .filter-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            border: 1px solid #e8ecf1;
            overflow: hidden;
        }
        
        .filter-header {
            padding: 1.2rem 1.8rem;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .filter-header i { 
            color: white; 
            font-size: 1.2rem; 
        }
        
        .filter-header h3 { 
            font-weight: 600; 
            font-size: 1rem; 
            color: white;
            margin: 0;
        }
        
        .filter-body { 
            padding: 1.5rem 1.8rem; 
            display: flex; 
            flex-wrap: wrap; 
            gap: 1rem; 
            align-items: flex-end; 
        }
        
        .filter-group { 
            flex: 1 1 200px; 
            min-width: 170px; 
        }
        
        .filter-group label { 
            font-size: 0.75rem; 
            font-weight: 600; 
            color: #475569; 
            display: block; 
            margin-bottom: 6px; 
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .filter-group input {
            width: 100%;
            padding: 0.75rem 1.2rem;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            font-size: 0.85rem;
            background: #f8fafc;
            transition: 0.25s;
            font-weight: 500;
            height: 48px;
            box-sizing: border-box;
        }
        
        .filter-group input:focus { 
            outline: none; 
            border-color: var(--primary-color); 
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            background: white;
        }
        
        .btn-clear {
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1.6rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            transition: 0.2s;
            height: 48px;
        }
        
        .btn-clear:hover { 
            background: #e2e8f0; 
            transform: translateY(-2px); 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .btn-apply {
            background: var(--primary-gradient);
            border: none;
            padding: 0.75rem 1.6rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            transition: 0.2s;
            white-space: nowrap;
            height: 48px;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-apply:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }
        
        .table-wrapper { 
            overflow-x: auto; 
            border-radius: 16px; 
            background: white; 
            border: 1px solid #e8ecf1; 
            box-shadow: var(--card-shadow); 
        }
        
        .cert-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 0.85rem; 
            min-width: 1400px; 
        }
        
        .cert-table th {
            text-align: left;
            padding: 1.1rem 1.2rem;
            background: var(--primary-gradient);
            font-weight: 600;
            color: white;
            border-bottom: 2px solid rgba(255,255,255,0.2);
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .cert-table th:last-child {
            text-align: center;
        }
        
        .cert-table td { 
            padding: 1rem 1.2rem; 
            border-bottom: 1px solid #f1f5f9; 
            vertical-align: top; 
            background: white;
            color: #334155;
        }
        
        .cert-table tr:hover td { 
            background: #f8f7ff; 
        }
        
        .cert-table td:first-child, .cert-table th:first-child {
            min-width: 180px;
            width: 180px;
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        
        .cert-table td:nth-child(2), .cert-table th:nth-child(2) {
            min-width: 240px;
        }
        
        .student-name { 
            font-weight: 600; 
            color: #1e293b; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
        }
        
        .student-name i { 
            color: var(--primary-color);
        }
        
        .class-text {
            background: linear-gradient(135deg, #e8edff, #dde4ff);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary-color);
        }
        
        .contact-line { 
            font-size: 0.8rem; 
            color: #64748b; 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            margin: 5px 0; 
            font-weight: 500; 
        }
        
        .contact-line i { 
            width: 20px; 
            color: #94a3b8; 
            font-size: 0.8rem; 
        }
        
        .cert-list { 
            display: flex; 
            flex-direction: column; 
            gap: 12px; 
        }
        
        .cert-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 8px 0;
            border-bottom: 1px dashed #e8ecf1;
            min-height: 54px;
            vertical-align: middle;
        }
        
        .cert-item:last-child { 
            border-bottom: none; 
        }
        
        .cert-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 0.55rem 1rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
            width: 200px;
            min-width: 175px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            height: 40px;
        }
        
        .cert-school { 
            background: linear-gradient(135deg, #e8edff, #d4dcff); 
            color: var(--primary-color); 
            border: 1px solid rgba(67, 97, 238, 0.2);
        }
        
        .cert-transfer { 
            background: linear-gradient(135deg, #fef3e2, #fde8ce); 
            color: #c97e0a; 
            border: 1px solid rgba(201, 126, 10, 0.2);
        }
        
        .cert-course { 
            background: linear-gradient(135deg, #e3f5ec, #d0ecdf); 
            color: #1e7a44; 
            border: 1px solid rgba(30, 122, 68, 0.2);
        }
        
        .cert-character { 
            background: linear-gradient(135deg, #f3e8ff, #e9d9ff); 
            color: #7c3aed; 
            border: 1px solid rgba(124, 58, 237, 0.2);
        }
        
        .cert-number {
            font-family: 'Inter', monospace;
            font-size: 0.75rem;
            background: linear-gradient(135deg, #f0f4ff, #e8edff);
            padding: 0.55rem 1rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: var(--primary-color);
            white-space: nowrap;
            min-width: 160px;
            justify-content: center;
            flex-shrink: 0;
            height: 40px;
            border: 1px solid rgba(67, 97, 238, 0.15);
        }
        
        .status-with-counter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
            background: linear-gradient(135deg, #dff0e6, #cce6d8);
            padding: 0.55rem 1rem;
            border-radius: 12px;
            white-space: nowrap;
            min-width: 167px;
            height: 40px;
            flex-shrink: 0;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #065f46;
            padding: 0.3rem 0;
        }
        
        .counter-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(16, 185, 129, 0.15);
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            color: #065f46;
            min-width: 28px;
            height: 20px;
            justify-content: center;
        }
        
        .counter-badge:empty {
            background: none;
            padding: 0;
            min-width: 28px;
        }
        
        .counter-badge i { 
            font-size: 0.65rem; 
        }
        
        .issue-date {
            font-family: 'Inter', monospace;
            font-size: 0.75rem;
            background: #f8fafc;
            padding: 0.55rem 1rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
            min-width: 135px;
            justify-content: center;
            flex-shrink: 0;
            height: 40px;
            color: #475569;
        }
        
        .action-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0.2rem 0.4rem;
            border-radius: 12px;
            flex-shrink: 0;
            height: 40px;
            vertical-align: middle;
        }
        
        .cert-table td:last-child {
            text-align: center;
            vertical-align: middle;
        }
        
        .cert-table td:last-child .cert-list {
            align-items: center;
        }
        
        .cert-table td:last-child .cert-item {
            justify-content: center;
        }
        
        .action-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: 0.2s;
            padding: 0.5rem 1.2rem;
            border-radius: 10px;
            font-weight: 600;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            height: 38px;
            min-height: 38px;
            white-space: nowrap;
            border: 1px solid #e2e8f0;
        }
        
        .action-btn i { 
            font-size: 0.9rem; 
            color: var(--primary-color); 
        }
        
        .action-btn span { 
            font-size: 0.75rem; 
            font-weight: 700; 
            color: #475569; 
            letter-spacing: 0.3px; 
        }
        
        .action-btn:hover { 
            background: linear-gradient(135deg, #f0f4ff, #e8edff); 
            transform: translateY(-2px); 
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
        }
        
        .action-btn:hover i { 
            color: var(--secondary-color); 
        }
        
        .action-btn:hover span { 
            color: var(--primary-color); 
        }
        
        .action-btn:disabled,
        .action-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }
        
        .empty-row td { 
            text-align: center; 
            padding: 3rem; 
            color: #94a3b8; 
            font-size: 0.9rem; 
        }
        
        .info-footer {
            margin-top: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #f0f4ff);
            border-radius: 12px;
            padding: 0.8rem 1.5rem;
            font-size: 0.8rem;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(67, 97, 238, 0.15);
            font-weight: 500;
        }

        /* Logs Modal Styles */
        .logs-modal {
            display: none;
            position: fixed;
            z-index: 10001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            animation: fadeIn 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .logs-modal-content {
            position: relative;
            background: white;
            padding: 0;
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .logs-modal-header {
            padding: 1.2rem 1.5rem;
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logs-modal-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logs-modal-close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            color: white;
        }

        .logs-modal-close:hover {
            color: #ffd700;
            transform: scale(1.1);
        }

        .logs-modal-body {
            padding: 1.5rem;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .log-detail-item {
            background: #f8fafc;
            margin-bottom: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            display: inline-block;
            min-width: 150px;
            text-align: center;
        }

        .log-detail-item strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        .log-detail-item p {
            margin: 3px 0;
            font-size: 0.75rem;
            color: #64748b;
        }

        .log-detail-item .log-time {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-top: 4px;
            font-weight: 500;
        }

        .logs-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .no-logs {
            text-align: center;
            padding: 2rem;
            color: #94a3b8;
        }

        .logs-stats {
            background: linear-gradient(135deg, #f0f4ff, #e8edff);
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid rgba(67, 97, 238, 0.15);
        }

        .logs-stats .total-downloads {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* Regenerate Modal */
        #regenerateConfirmModal .logs-modal-header {
            background: var(--primary-gradient);
        }

        @media (max-width: 780px) { 
            body { padding: 1rem; } 
            .filter-body { flex-direction: column; } 
            .cert-item { flex-wrap: wrap; } 
        }
        
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }

        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .table-responsive {
            overflow-x: hidden;
        }
    </style>

<div class="container-fluid">    
    <div class="app-container">
        <!-- <div class="page-header">
            <div class="title-section">
                <h1><i class="fas fa-certificate"></i> Certificate Management</h1>
                <p>View and manage all student certificates</p>
            </div>
            <div class="stats-badge">
                <span><i class="fas fa-file-alt"></i> Total: <strong>{{ count($studentsData) }}</strong></span>
            </div>
        </div> -->

        <div class="filter-card">
            <div class="filter-header">
                <i class="fas fa-sliders-h"></i>
                <h3>Filters</h3>
            </div>
            <div class="filter-body">
                <div class="filter-group">
                    <label><i class="fas fa-qrcode"></i> Registration number</label>
                    <input type="text" id="filterReg" placeholder="e.g. REG/2024" autocomplete="off">
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-user"></i> Student name</label>
                    <input type="text" id="filterName" placeholder="Search by name">
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-hashtag"></i> Certificate number</label>
                    <input type="text" id="filterCertNo" placeholder="e.g. SL-24001" autocomplete="off">
                </div>
                <div style="display: flex; gap: 12px;">
                    <button class="btn-apply" id="applyFiltersBtn"><i class="fas fa-filter"></i> Apply Filter</button>
                    <button class="btn-clear" id="clearFiltersBtn"><i class="fas fa-eraser"></i> Clear</button>
                </div>
            </div>
        </div>
        
        <div>
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table cert-table">
                    <thead>
                        <tr>
                            <th class="sticky-main-2">Student &nbsp;|</br> Reg. number</th>
                            <th>Class</th>
                            <th>Phone </br> Email</th>
                            <th>Certificate Type</th>
                            <th>Certificate No.</th>
                            <th>Status</th>
                            <th>Issue Date</th>
                            <th style="text-align: center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($studentsData as $student)
                        <tr>
                            <td class="sticky-main-2">
                                <div class="student-name"><i class="fas fa-user-circle"></i> {{ $student['name'] }}</div>
                                <span class="small">{{ $student['regNo'] }}</span>
                                </br>
                            </td>
                            <td>
                                <span class="class-text small"><i class="fas fa-graduation-cap"></i> {{ $student['className'] }}</span>
                            </td>
                            <td>
                                <div class="contact-line"><i class="fas fa-phone-alt"></i> {{ $student['contact'] }}</div>
                                <div class="contact-line"><i class="fas fa-envelope"></i> {{ $student['email'] }}</div>
                            </td>
                            <td>
                                <div class="cert-list">
                                    @foreach($student['certificates'] as $cert)
                                    <div class="cert-item">
                                        <span class="cert-badge 
                                            @if(str_contains($cert['type'], 'School')) cert-school
                                            @elseif(str_contains($cert['type'], 'Transfer')) cert-transfer
                                            @elseif(str_contains($cert['type'], 'Course')) cert-course
                                            @else cert-character @endif">
                                            {{ $cert['type'] }}
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="cert-list">
                                    @foreach($student['certificates'] as $cert)
                                    <div class="cert-item">
                                        <span class="cert-number"><i class="fas fa-hashtag"></i> {{ $cert['certificate_number'] }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="cert-list">
                                    @foreach($student['certificates'] as $cert)
                                    <div class="cert-item">
                                        <div class="status-with-counter">
                                            <span class="status-pill"><i class="fas fa-check-circle"></i> 
                                                @if($cert['generation_count'] > 0)
                                                    #{{ $cert['generation_count'] }} 
                                                @endif
                                                {{ $cert['status_label'] ?? 'Generated' }}
                                            </span>
                                            @if($cert['can_regenerate'] ?? true)
                                            <button class="cert-edit-btn" 
                                                data-cert-number="{{ $cert['certificate_number'] }}"
                                                data-cert-type="{{ $cert['type'] }}"
                                                data-student-id="{{ $student['id'] }}"
                                                data-student-name="{{ $student['name'] }}"
                                                data-reg-no="{{ $student['regNo'] }}"
                                                style="background: none; border: none; padding: 0; margin-left: 8px; cursor: pointer; color: var(--primary-color); font-size: 0.9rem; transition: 0.2s;"
                                                title="Edit & Regenerate"
                                                onclick="openRegenerateModal.call(this, event)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div class="cert-list">
                                    @foreach($student['certificates'] as $cert)
                                    <div class="cert-item">
                                        <span class="issue-date"><i class="far fa-calendar-alt"></i> {{ $cert['issue_date'] }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <div class="cert-list">
                                    @foreach($student['certificates'] as $certIdx => $cert)
                                    <div class="cert-item" style="justify-content: center;">
                                        <div class="action-group">
                                            <button class="action-btn download-cert {{ $cert['download_count'] >= 7 ? 'disabled' : '' }}" 
                                                data-student="{{ $student['id'] }}"
                                                data-cert-number="{{ $cert['certificate_number'] }}"
                                                data-cert-name="{{ $cert['type'] }}"
                                                data-cert-type="{{ $cert['certificate_type'] }}"
                                                data-name="{{ $student['name'] }}"
                                                data-reg="{{ $student['regNo'] }}"
                                                data-class="{{ $student['className'] }}"
                                                data-email="{{ $student['email'] }}"
                                                data-contact="{{ $student['contact'] }}"
                                                data-date="{{ $cert['issue_date'] }}"
                                                data-institute-id="{{ $cert['institute_id'] }}"
                                                {{ $cert['download_count'] >= 7 ? 'disabled' : '' }}>
                                                <i class="fas fa-download"></i><span>Download
                                                    <span class="counter-badge" data-cert-num="{{ $cert['certificate_number'] }}" id="counter-badge-{{ $loop->parent->index }}-{{ $loop->index }}">@if($cert['download_count'] > 0)#{{ $cert['download_count'] }}@endif</span>
                                                </span>
                                            </button>
                                            <button class="action-btn view-logs-btn" 
                                                data-cert-number="{{ $cert['certificate_number'] }}"
                                                data-cert-type="{{ $cert['type'] }}"
                                                data-student-name="{{ $student['name'] }}"
                                                onclick="logsHandler.call(this, event)">
                                                <i class="fas fa-history"></i><span>LOGS</span>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="8">No certificates found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
        </div>
        
        <div class="info-footer">
            <i class="fas fa-info-circle"></i> Download counter starts at 0 and increments per download. Maximum 7 downloads allowed per certificate.
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="certImageModal" class="image-modal">
    <div class="image-modal-content">
        <span class="image-modal-close">&times;</span>
        <div class="image-modal-header">
            <i class="fas fa-certificate"></i>
            <h3>Certificate Document</h3>
        </div>
        <div class="image-modal-body">
            <div class="image-loading" id="imageLoading">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading certificate image...</p>
            </div>
            <img id="certificateImageView" class="certificate-image" alt="Certificate View" style="display: none;">
            <div id="imageError" class="image-error" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <p>No certificate image available</p>
            </div>
        </div>
    </div>
</div>

<!-- Logs Modal -->
<div id="logsModal" class="logs-modal">
    <div class="logs-modal-content">
        <div class="logs-modal-header">
            <h3><i class="fas fa-history"></i> Certificate Activity Logs</h3>
            <span class="logs-modal-close">&times;</span>
        </div>
        <div class="logs-modal-body" id="logsModalBody">
            <div class="logs-stats" id="logsStats"></div>
            <div id="logsList"></div>
        </div>
    </div>
</div>

<!-- Regenerate Confirmation Modal -->
<div id="regenerateConfirmModal" class="logs-modal" style="display: none;">
    <div class="logs-modal-content" style="max-width: 450px;">
        <div class="logs-modal-header">
            <h3><i class="fas fa-sync-alt"></i> Regenerate Certificate</h3>
            <span class="regenerate-confirm-close" style="cursor: pointer; font-size: 28px; font-weight: bold; color: white;">&times;</span>
        </div>
        <div class="logs-modal-body" style="padding: 1.5rem; text-align: center;">
            <div style="background: #f0f4ff; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 4px solid var(--primary-color);">
                <p style="margin: 0; color: var(--primary-color); font-weight: 600; font-size: 0.9rem;">
                    <i class="fas fa-info-circle"></i> You are about to regenerate this certificate with a new certificate number.
                </p>
            </div>
            <div style="margin-bottom: 1.2rem;">
                <p style="color: #64748b; font-size: 0.95rem; line-height: 1.5;">
                    <strong>Student:</strong> <span id="modalStudentName"></span><br>
                    <strong>Current Certificate:</strong> <span id="modalCertNumber"></span><br>
                    <strong>Type:</strong> <span id="modalCertType"></span>
                </p>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button class="regenerate-confirm-btn" style="flex: 1; padding: 0.9rem; background: var(--primary-gradient); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 0.9rem; transition: 0.2s; box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);">
                    <i class="fas fa-check-circle"></i> Continue to Edit
                </button>
                <button class="regenerate-cancel-confirm-btn" style="flex: 1; padding: 0.9rem; background: #f1f5f9; color: #475569; border: 2px solid #e2e8f0; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 0.9rem; transition: 0.2s;">
                    <i class="fas fa-times-circle"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Download counters map - initialize with 1 for each certificate (as per requirement)
    let downloadCountMap = new Map();

    function initCountersFromDOM() {
        document.querySelectorAll('.counter-badge').forEach(badge => {
            const certNum = badge.getAttribute('data-cert-num');
            if (certNum) {
                const currentText = badge.innerText.trim();
                let count = 0; // Default to 0
                const match = currentText.match(/\d+/);
                if (match) count = parseInt(match[0], 10);
                // Ensure minimum 0
                if (count < 0) count = 0;
                downloadCountMap.set(certNum, count);
                badge.innerHTML = count > 0 ? `#${count}` : '';
                
                // Disable button if limit reached
                if (count >= 7) {
                    disableDownloadButton(certNum);
                }
            }
        });
        // For any certificate not yet in map, set to 0
        document.querySelectorAll('.counter-badge').forEach(badge => {
            const certNum = badge.getAttribute('data-cert-num');
            if (certNum && !downloadCountMap.has(certNum)) {
                downloadCountMap.set(certNum, 0);
                badge.innerHTML = '';
            }
        });
    }

    function updateCounterBadge(certNumber, newCount) {
        const badges = document.querySelectorAll('.counter-badge');
        for (let badge of badges) {
            if (badge.getAttribute('data-cert-num') === certNumber) {
                badge.innerHTML = `#${newCount}`;
                break;
            }
        }
    }

    function incrementDownloadCounter(certNumber) {
        let current = downloadCountMap.get(certNumber) || 0;
        current++;
        downloadCountMap.set(certNumber, current);
        updateCounterBadge(certNumber, current);
        return current;
    }

    function disableDownloadButton(certNumber) {
        const buttons = document.querySelectorAll(`.download-cert[data-cert-number="${certNumber}"]`);
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('disabled');
        });
    }

    // Filter functionality
    function filterTable() {
        const filterReg = document.getElementById("filterReg").value.trim();
        const filterName = document.getElementById("filterName").value.trim();
        const filterCertNo = document.getElementById("filterCertNo").value.trim();
        
        console.log('Sending filter values:', {filterReg, filterName, filterCertNo});
        
        fetch('{{ route("certificates.filter") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            
            body: JSON.stringify({
                reg_no: filterReg,
                student_name: filterName,
                certificate_no: filterCertNo
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Server error: ${response.status} - ${text.substring(0, 200)}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Filter response:', data);
            if (!data.success && data.message) {
                showNotification('Filter error: ' + data.message, 'error');
                console.error('Filter error:', data.message);
                return;
            }
            
            updateTable(data.students);
            setTimeout(() => {
                initCountersFromDOM();
                attachEventListeners();
            }, 10);
        })
        .catch(error => {
            console.error('Filter error:', error);
            showNotification('Error applying filter: ' + error.message, 'error');
        });
    }
    
    function updateTable(students) {
        const tbody = document.getElementById('tableBody');
        if (!students || students.length === 0) {
            tbody.innerHTML = '<tr class="empty-row"><td colspan="8">No certificates found</td></tr>';
            return;
        }
        
        let html = '';
        students.forEach((student, studentIdx) => {
            const certsHtml = student.certificates.map((cert, certIdx) => {
                let certClass = getCertClass(cert.type);
                let certIcon = getCertIcon(cert.type);
                let downloadCount = cert.download_count || 0;
                let generationCount = cert.generation_count || 0;
                let statusLabel = cert.status_label || 'Generated';
                let canRegenerate = cert.can_regenerate !== false;
                return {
                    typeHtml: `<div class="cert-item"><span class="cert-badge ${certClass}">${escapeHtml(cert.type)}</span></div>`,
                    numberHtml: `<div class="cert-item"><span class="cert-number"><i class="fas fa-hashtag"></i> ${escapeHtml(cert.certificate_number)}</span></div>`,
                    statusHtml: `<div class="cert-item"><div class="status-with-counter"><span class="status-pill"><i class="fas fa-check-circle"></i> ${generationCount > 0 ? `#${generationCount} ` : ''}${escapeHtml(statusLabel)}</span>${canRegenerate ? `<button class="cert-edit-btn" data-cert-number="${escapeHtml(cert.certificate_number)}" data-cert-type="${escapeHtml(cert.type)}" data-student-id="${student.id}" data-student-name="${escapeHtml(student.name)}" data-reg-no="${escapeHtml(student.regNo)}" style="background: none; border: none; padding: 0; margin-left: 8px; cursor: pointer; color: var(--primary-color); font-size: 0.9rem; transition: 0.2s;" title="Edit & Regenerate" onclick="openRegenerateModal.call(this, event)"><i class="fas fa-edit"></i></button>` : ''}</div></div>`,
                    dateHtml: `<div class="cert-item"><span class="issue-date"><i class="far fa-calendar-alt"></i> ${cert.issue_date}</span></div>`,
                    actionsHtml: `<div class="cert-item" style="justify-content: center;"><div class="action-group">
                        <button class="action-btn download-cert ${downloadCount >= 7 ? 'disabled' : ''}" 
                            data-student="${student.id}"
                            data-cert-number="${escapeHtml(cert.certificate_number)}"
                            data-cert-name="${escapeHtml(cert.type)}"
                            data-cert-type="${escapeHtml(cert.certificate_type)}"
                            data-name="${escapeHtml(student.name)}"
                            data-reg="${escapeHtml(student.regNo)}"
                            data-class="${escapeHtml(student.className)}"
                            data-email="${escapeHtml(student.email)}"
                            data-contact="${escapeHtml(student.contact)}"
                            data-date="${cert.issue_date}"
                            data-institute-id="${cert.institute_id}"
                            ${downloadCount >= 7 ? 'disabled' : ''}>
                            <i class="fas fa-download"></i><span>Download ${downloadCount > 0 ? `#${downloadCount}` : ''}</span>
                        </button>
                        <button class="action-btn view-logs-btn" 
                            data-cert-number="${escapeHtml(cert.certificate_number)}"
                            data-cert-type="${escapeHtml(cert.type)}"
                            data-student-name="${escapeHtml(student.name)}"
                            onclick="logsHandler.call(this, event)">
                            <i class="fas fa-history"></i><span>LOGS</span>
                        </button>
                    </div></div>`
                };
            });
            
            html += `<tr>
                <td>${escapeHtml(student.regNo)}</td>
                <td><div class="student-name"><i class="fas fa-user-circle"></i> ${escapeHtml(student.name)}</div><span class="class-text"><i class="fas fa-graduation-cap"></i> ${escapeHtml(student.className)}</span></td>
                <td><div class="contact-line"><i class="fas fa-envelope"></i> ${escapeHtml(student.email)}</div><div class="contact-line"><i class="fas fa-phone-alt"></i> ${escapeHtml(student.contact)}</div></td>
                <td><div class="cert-list">${certsHtml.map(c => c.typeHtml).join('')}</div></td>
                <td><div class="cert-list">${certsHtml.map(c => c.numberHtml).join('')}</div></td>
                <td><div class="cert-list">${certsHtml.map(c => c.statusHtml).join('')}</div></td>
                <td><div class="cert-list">${certsHtml.map(c => c.dateHtml).join('')}</div></td>
                <td style="text-align: center; vertical-align: middle;"><div class="cert-list">${certsHtml.map(c => c.actionsHtml).join('')}</div></td>
            </tr>`;
        });
        tbody.innerHTML = html;
    }
    
    function getCertClass(type) {
        if (type.includes('School')) return 'cert-school';
        if (type.includes('Transfer')) return 'cert-transfer';
        if (type.includes('Course')) return 'cert-course';
        return 'cert-character';
    }
    
    function getCertIcon(type) {
        if (type.includes('School')) return 'fa-graduation-cap';
        if (type.includes('Transfer')) return 'fa-exchange-alt';
        if (type.includes('Course')) return 'fa-check-double';
        return 'fa-star-of-life';
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        
        try {
            const date = new Date(dateString);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            let hours = date.getHours();
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12;
            
            return `${day} ${month} ${year} ${hours}:${minutes}${ampm}`;
        } catch (e) {
            return dateString;
        }
    }
    
    function attachEventListeners() {
        document.querySelectorAll('.download-cert').forEach(btn => {
            btn.removeEventListener('click', downloadHandler);
            btn.addEventListener('click', downloadHandler);
        });
        
        document.querySelectorAll('.view-logs-btn').forEach(btn => {
            btn.removeEventListener('click', logsHandler);
            btn.addEventListener('click', logsHandler);
        });
        
        document.querySelectorAll('.cert-edit-btn').forEach(btn => {
            btn.removeEventListener('click', openRegenerateModal);
            btn.addEventListener('click', openRegenerateModal);
        });
    }

    async function logDownloadToDatabase(certNumber, studentId, certType, instituteId, regNumber, issueDate) {
        try {
            const data = {
                certificate_number: certNumber,
                student_hash_id: studentId,
                certificate_type: certType,
                registration_number: regNumber,
                institute_id: instituteId,
                issue_date: issueDate,
                notes: 'Downloaded from certificates view'
            };
            
            console.log('Sending logging data:', data);
            
            const response = await fetch('{{ route("certificates.updateCount") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });
            
            console.log('Logging response status:', response.status);
            const result = await response.json();
            console.log('Logging response data:', result);
            
            if (!response.ok) {
                console.error('Failed to log download:', result);
                throw new Error('Failed to log download');
            }
            
            return result;
        } catch (error) {
            console.error('Error logging download:', error);
            throw error;
        }
    }

    function logsHandler(e) {
        const btn = this || (e && (e.currentTarget || e.target));
        
        if (!btn) {
            console.error('Could not determine button element');
            return;
        }
        
        const certNumber = btn.getAttribute('data-cert-number');
        const certType = btn.getAttribute('data-cert-type');
        const studentName = btn.getAttribute('data-student-name');
       
        if (!certNumber || !certType ||!studentName) {
            console.error('Missing required data on button:', btn);
            return;
        }
        
        showDownloadLogs(certNumber, certType, studentName);
    }
    
    function viewHandler(e) {
        const btn = e.currentTarget;
        const certId = btn.dataset.certId;
        
        console.log('Certificate ID from button:', certId);
        
        if (!certId || certId === '') {
            alert('Certificate ID not found. Please refresh the page and try again.');
            return;
        }
        
        const modal = document.getElementById('certImageModal');
        const imgElement = document.getElementById('certificateImageView');
        const loadingDiv = document.getElementById('imageLoading');
        const errorDiv = document.getElementById('imageError');
        
        const certType = btn.dataset.cert || 'Certificate';
        const certNumber = btn.dataset.number || '';
        
        imgElement.style.display = 'none';
        errorDiv.style.display = 'none';
        loadingDiv.style.display = 'block';
        
        const modalHeader = document.querySelector('.image-modal-header h3');
        if (modalHeader) {
            modalHeader.innerHTML = `<i class="fas fa-certificate" style="margin-right: 8px;"></i> ${certType} - ${certNumber}`;
        }
        
        modal.style.display = 'block';
        
        fetch('{{ route("certificates.view-image") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                certificate_id: certId
            })
        })
        .then(response => response.json())
        .then(data => {
            loadingDiv.style.display = 'none';
            
            if (data.success && data.image_data) {
                imgElement.src = data.image_data;
                imgElement.style.display = 'block';
            } else {
                errorDiv.style.display = 'block';
                errorDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${data.message || 'No certificate image available'}</p>
                    <small>Certificate: ${certType} | ${certNumber}</small>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loadingDiv.style.display = 'none';
            errorDiv.style.display = 'block';
            errorDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <p>Failed to load certificate image.</p>
                <small>${error.message}</small>
            `;
        });
    }

    function initModal() {
        const modal = document.getElementById('certImageModal');
        const closeBtn = document.querySelector('.image-modal-close');
        
        if (closeBtn) {
            closeBtn.onclick = function() {
                modal.style.display = 'none';
                const img = document.getElementById('certificateImageView');
                if (img) img.src = '';
            };
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                const img = document.getElementById('certificateImageView');
                if (img) img.src = '';
            }
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'block') {
                modal.style.display = 'none';
                const img = document.getElementById('certificateImageView');
                if (img) img.src = '';
            }
        });
    }

    initModal();
    initLogsModal();
    initRegenerateConfirmModal();

    function downloadHandler(e) {
        const btn = e.currentTarget; 
        const studentId = btn.dataset.student; 
        const certNumber = btn.dataset.certNumber;
        const certName = btn.dataset.certName;
        const certType = btn.dataset.certType;
        const instituteId = btn.dataset.instituteId;
        const regNumber = btn.dataset.reg;
        const issueDate = btn.dataset.date;
        
        const currentCount = downloadCountMap.get(certNumber) || 0;
        if (currentCount >= 7) {
            alert('Download limit reached. Maximum 7 downloads allowed per certificate.');
            return;
        }
        
        logDownloadToDatabase(certNumber, studentId, certType, instituteId, regNumber, issueDate)
            .then((result) => {
                if (result.download_sequence) {
                    updateCounterBadge(certNumber, result.download_sequence);
                    incrementDownloadCounter(certNumber);
                    
                    const newCount = downloadCountMap.get(certNumber) || 0;
                    if (newCount >= 7) {
                        disableDownloadButton(certNumber);
                    }
                }
                
                let route = '';
                if (certName.toLowerCase().includes('character')) {
                    route = '{{ route("certificates.character") }}'; 
                } else if (certName.toLowerCase().includes('course') && certName.toLowerCase().includes('completion')) {
                    route = '{{ route("certificates.course-completion") }}';
                } else if (certName.toLowerCase().includes('transfer')) {
                    route = '{{ route("certificates.transfer") }}';
                } else if (certName.toLowerCase().includes('school') && certName.toLowerCase().includes('leaving')) {
                    route = '{{ route("certificates.schoolLeaving") }}';
                } else if (certName.toLowerCase().includes('bonafide')) {
                    route = '{{ route("certificates.bonafide") }}';
                } else if (certName.toLowerCase().includes('no due') || certName.toLowerCase().includes('no-due')) {
                    route = '{{ route("certificates.noDue") }}';
                } else {
                    alert('Certificate type not recognized');
                    return;
                }
                
                const url = `${route}?cert_number=${encodeURIComponent(certNumber)}&student_id=${encodeURIComponent(studentId)}&auto_open=true&from_view=true`;
                window.location.href = url;
            })
            .catch(error => {
                console.error('Error logging download:', error);
                alert('Error logging download. Please try again.');
            });
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#4361ee'};
            color: white;
            border-radius: 10px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    document.getElementById("applyFiltersBtn").addEventListener("click", () => {
        filterTable();
    });
    
    document.getElementById("clearFiltersBtn").addEventListener("click", () => {
        document.getElementById("filterReg").value = "";
        document.getElementById("filterName").value = "";
        document.getElementById("filterCertNo").value = "";
        filterTable();
    });
    
    initCountersFromDOM();
    attachEventListeners();

    function showDownloadLogs(certNumber, certType, studentName) {
        const modal = document.getElementById('logsModal');
        const logsList = document.getElementById('logsList');
        const logsStats = document.getElementById('logsStats');
        
        if (!modal) {
            alert('Error: Modal not found. Please refresh the page.');
            return;
        }
        
        logsList.innerHTML = '<div class="no-logs"><i class="fas fa-spinner fa-spin"></i> Loading certificate history...</div>';
        logsStats.innerHTML = '';
        modal.style.display = 'flex';
        
        fetch('/certificates/download-logs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                certificate_number: certNumber
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.logs && data.logs.length > 0) {
                logsStats.innerHTML = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                        <div style="background: rgba(16, 185, 129, 0.1); padding: 10px; border-radius: 10px; text-align: center; border-left: 4px solid #10b981;">
                            <div style="font-size: 1.4rem; font-weight: 700; color: #10b981;">
                                <i class="fas fa-download"></i> ${data.total_downloads}
                            </div>
                            <div style="font-size: 0.75rem; color: #059669; font-weight: 600; margin-top: 4px;">Total Downloads</div>
                        </div>
                        <div style="background: rgba(67, 97, 238, 0.1); padding: 10px; border-radius: 10px; text-align: center; border-left: 4px solid #4361ee;">
                            <div style="font-size: 1.4rem; font-weight: 700; color: #4361ee;">
                                <i class="fas fa-sync-alt"></i> ${data.total_regenerations}
                            </div>
                            <div style="font-size: 0.75rem; color: #1d4ed8; font-weight: 600; margin-top: 4px;">Regenerations</div>
                        </div>
                    </div>
                    <div style="background: #f0f4ff; padding: 10px; border-radius: 10px; font-size: 0.85rem; color: #475569; line-height: 1.6;">
                        <div><i class="fas fa-certificate" style="color: var(--primary-color); margin-right: 6px;"></i> <strong>${escapeHtml(certType)}</strong></div>
                        <div><i class="fas fa-hashtag" style="color: var(--primary-color); margin-right: 6px;"></i> <strong>${escapeHtml(certNumber)}</strong></div>
                        <div><i class="fas fa-user" style="color: var(--primary-color); margin-right: 6px;"></i> <strong>${escapeHtml(studentName)}</strong></div>
                        ${data.issue_date ? `<div><i class="far fa-calendar-alt" style="color: var(--primary-color); margin-right: 6px;"></i> <strong>Issued: ${escapeHtml(data.issue_date)}</strong></div>` : ''}
                    </div>
                `;
                
                let logsHtml = '<div style="margin-bottom: 12px; padding-top: 8px;"><strong style="color: #1e293b; font-size: 0.9rem;"><i class="fas fa-history" style="margin-right: 6px; color: var(--primary-color);"></i>Certificate Activity History (${data.total_actions} events)</strong></div>';
                if (data.total_regenerations > 0) {
                    logsHtml += '<div style="background: #f0f4ff; border: 1px solid rgba(67, 97, 238, 0.2); border-radius: 8px; padding: 8px 12px; margin-bottom: 12px; font-size: 0.8rem; color: var(--primary-color);">';
                    logsHtml += '<i class="fas fa-info-circle" style="margin-right: 6px;"></i>';
                    logsHtml += '<strong>Note:</strong> Regeneration logs show actual certificate number changes from the database.';
                    logsHtml += '</div>';
                }
                logsHtml += '<div style="border-left: 3px solid #e2e8f0; padding-left: 16px;">';  
                
                data.logs.forEach((log, index) => {
                    let logIcon = log.type === 'download' ? 'fa-download' : 'fa-sync-alt';
                    let logColor = log.type === 'download' ? '#10b981' : '#4361ee';
                    let logBgColor = log.type === 'download' ? 'rgba(16, 185, 129, 0.05)' : 'rgba(67, 97, 238, 0.05)';
                    let timeString = log.type === 'download' ? formatDate(log.downloaded_at) : formatDate(log.regenerated_at);
                    
                    logsHtml += `
                        <div style="background: ${logBgColor}; margin-bottom: 12px; padding: 12px 12px; border-radius: 10px; border-left: 4px solid ${logColor}; position: relative;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                <i class="fas ${logIcon}" style="color: ${logColor}; font-size: 1.1rem;"></i>
                                <strong style="color: ${logColor}; font-size: 0.9rem;">
                                    ${log.type === 'download' ? `Download #${log.download_number || log.display_number}` : `Regeneration #${log.generation_sequence || log.display_number}`}
                                </strong>
                                <span style="background: ${logColor}; color: white; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: 700;">
                                    ${log.type.toUpperCase()}
                                </span>
                            </div>
                            <div style="font-size: 0.8rem; color: #475569; margin-bottom: 6px;">
                                <i class="far fa-clock" style="margin-right: 4px;"></i> ${timeString}
                            </div>
                    `;
                    
                    if (log.type === 'regeneration') {
                        logsHtml += `
                            <div style="font-size: 0.8rem; color: #64748b; background: white; padding: 6px 8px; border-radius: 8px; line-height: 1.5;">
                                <div><i class="fas fa-arrow-right" style="color: var(--primary-color); margin-right: 4px;"></i> <strong>Certificate Changed:</strong></div>
                                <div style="margin-left: 20px; color: #94a3b8;">
                                    From: <span style="background: #fef2f2; padding: 2px 6px; border-radius: 4px; font-family: monospace; font-weight: 600;">${escapeHtml(log.certificate_number_before)}</span>
                                </div>
                                <div style="margin-left: 20px; color: #94a3b8;">
                                    To: <span style="background: #f0fdf4; padding: 2px 6px; border-radius: 4px; font-family: monospace; font-weight: 600;">${escapeHtml(log.certificate_number_after)}</span>
                                </div>
                                ${log.generation_sequence ? `<div style="margin-top: 4px; color: #94a3b8;"><i class="fas fa-tag" style="margin-right: 4px;"></i> <strong>Seq:</strong> ${log.generation_sequence}</div>` : ''}
                            </div>
                        `;
                    } else {
                        logsHtml += `
                            <div style="font-size: 0.8rem; color: #64748b; background: white; padding: 6px 8px; border-radius: 8px;">
                                ${log.notes}
                            </div>
                        `;
                    }
                    
                    logsHtml += `</div>`;
                });
                
                logsHtml += '</div>';
                logsList.innerHTML = logsHtml;
            } else {
                logsStats.innerHTML = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                        <div style="background: rgba(16, 185, 129, 0.1); padding: 10px; border-radius: 10px; text-align: center; border-left: 4px solid #10b981;">
                            <div style="font-size: 1.4rem; font-weight: 700; color: #10b981;">0</div>
                            <div style="font-size: 0.75rem; color: #059669; font-weight: 600; margin-top: 4px;">Downloads</div>
                        </div>
                        <div style="background: rgba(67, 97, 238, 0.1); padding: 10px; border-radius: 10px; text-align: center; border-left: 4px solid #4361ee;">
                            <div style="font-size: 1.4rem; font-weight: 700; color: #4361ee;">0</div>
                            <div style="font-size: 0.75rem; color: #1d4ed8; font-weight: 600; margin-top: 4px;">Regenerations</div>
                        </div>
                    </div>
                    <div style="background: #f0f4ff; padding: 10px; border-radius: 10px; font-size: 0.85rem; color: #475569; line-height: 1.6;">
                        <div><i class="fas fa-certificate" style="color: var(--primary-color); margin-right: 6px;"></i> <strong>${escapeHtml(certType)}</strong></div>
                        <div><i class="fas fa-hashtag" style="color: var(--primary-color); margin-right: 6px;"></i> <strong>${escapeHtml(certNumber)}</strong></div>
                        <div><i class="fas fa-user" style="color: var(--primary-color); margin-right: 6px;"></i> <strong>${escapeHtml(studentName)}</strong></div>
                    </div>
                `;
                logsList.innerHTML = '<div class="no-logs"><i class="fas fa-file-alt" style="font-size: 2rem; margin-bottom: 10px; color: #a8bed0;"></i><br><p>No activity history yet</p><p style="font-size: 0.8rem; color: #94a3b8;">This certificate has not been downloaded or regenerated yet.</p></div>';
            }
        })
        .catch(error => {
            console.error('Error fetching logs:', error);
            logsList.innerHTML = '<div class="no-logs"><i class="fas fa-exclamation-triangle"></i> Failed to load certificate history. Please try again.</div>';
            logsStats.innerHTML = '<div class="total-downloads"><i class="fas fa-exclamation-triangle"></i> Error loading data</div>';
        });
    }

    function initLogsModal() {
        const modal = document.getElementById('logsModal');
        const closeBtn = document.querySelector('.logs-modal-close');
        
        if (closeBtn) {
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            };
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'block') {
                modal.style.display = 'none';
            }
        });
    }

    function openRegenerateModal(e) {
        const btn = this || (e && (e.currentTarget || e.target));
        if (!btn) return;
        
        const certNumber = btn.getAttribute('data-cert-number');
        const certType = btn.getAttribute('data-cert-type');
        const studentId = btn.getAttribute('data-student-id');
        const studentName = btn.getAttribute('data-student-name');
        const regNo = btn.getAttribute('data-reg-no');
        
        if (!certNumber || !certType) {
            alert('Error: Certificate information missing');
            return;
        }
        
        const modal = document.getElementById('regenerateConfirmModal');
        modal.setAttribute('data-cert-number', certNumber);
        modal.setAttribute('data-cert-type', certType);
        modal.setAttribute('data-student-id', studentId);
        modal.setAttribute('data-reg-no', regNo);
        
        document.getElementById('modalStudentName').textContent = studentName;
        document.getElementById('modalCertNumber').textContent = certNumber;
        document.getElementById('modalCertType').textContent = certType;
        
        modal.style.display = 'flex';
    }

    function initRegenerateConfirmModal() {
        const modal = document.getElementById('regenerateConfirmModal');
        const closeBtn = document.querySelector('.regenerate-confirm-close');
        const cancelBtn = document.querySelector('.regenerate-cancel-confirm-btn');
        const confirmBtn = document.querySelector('.regenerate-confirm-btn');
        
        if (!modal) return;
        
        if (closeBtn) {
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            };
        }
        
        if (cancelBtn) {
            cancelBtn.onclick = function() {
                modal.style.display = 'none';
            };
        }
        
        if (confirmBtn) {
            confirmBtn.onclick = function() {
                const certNumber = modal.getAttribute('data-cert-number');
                const certType = modal.getAttribute('data-cert-type');
                const studentId = modal.getAttribute('data-student-id');
                const regNo = modal.getAttribute('data-reg-no');
                
                modal.style.display = 'none';
                
                let route = '';
                if (certType.toLowerCase().includes('character')) {
                    route = '{{ route("certificates.character") }}';
                } else if (certType.toLowerCase().includes('course') && certType.toLowerCase().includes('completion')) {
                    route = '{{ route("certificates.course-completion") }}';
                } else if (certType.toLowerCase().includes('transfer')) {
                    route = '{{ route("certificates.transfer") }}';
                } else if (certType.toLowerCase().includes('school') && certType.toLowerCase().includes('leaving')) {
                    route = '{{ route("certificates.schoolLeaving") }}';
                } else if (certType.toLowerCase().includes('bonafide')) {
                    route = '{{ route("certificates.bonafide") }}';
                } else if (certType.toLowerCase().includes('no due') || certType.toLowerCase().includes('no-due')) {
                    route = '{{ route("certificates.noDue") }}';
                }
                
                const url = `${route}?cert_number=${encodeURIComponent(certNumber)}&student_id=${encodeURIComponent(studentId)}&registration_number=${encodeURIComponent(regNo)}&regenerate=true&from_view=true`;
                window.location.href = url;
            };
        }
        
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
        
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && modal.style.display === 'flex') {
                modal.style.display = 'none';
            }
        });
    }
</script>
@endsection