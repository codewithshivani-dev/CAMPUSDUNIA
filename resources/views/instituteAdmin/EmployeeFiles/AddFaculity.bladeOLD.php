@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Employee Management</title>
    <style>
        .error {
            color: red;
            font-size: 12px;
            margin-top: 4px;
        }

        .mainDiv {
            position: relative;
        }

        .childContent {
            position: absolute;
            color: #000;
            top: 65%;
            left: 7%;
        }

        .child1 {
            background: linear-gradient(90deg, #4B3F72, #F6C667);
            padding: 40px 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
            color: white;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-top: -50px;
            border: 5px solid white;
        }

        .add-btn {
            padding: 10px 20px;
            background-color: #007BFF;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .add-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        /* Overlay */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
            z-index: 100;
        }

        .overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Side Panel */
        .side-panel {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 800px;
            height: 100vh;
            background-color: #F8FAFF;
            box-shadow: -2px 0 8px rgba(0, 0, 0, 0.2);
            transition: right 0.4s ease;
            z-index: 200;
            display: flex;
            flex-direction: column;
        }

        .side-panel.open {
            right: 0;
        }

        .panel-header {
            background-color: #DCEEFF;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        /* Panel Content */
        .panel-content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 100px;
            max-height: calc(100vh - 60px);
            box-sizing: border-box;
        }

        /* Accordion */
        #sidePanel .accordion {
            background-color: #fff;
            cursor: pointer;
            padding: 15px;
            width: 100%;
            border: none;
            border-bottom: 1px solid #ccc;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .accordion.active {
            background-color: #E8F4FF;
        }

        .accordion:after {
            content: '\25BC';
            transition: transform 0.3s ease;
        }

        .accordion.active:after {
            transform: rotate(180deg);
        }

        .panel {
            max-height: 0;
            overflow: hidden;
            background-color: #fff;
            transition: max-height 0.3s ease;
            padding: 0 15px;
        }

        .panel.open {
            max-height: none;
            padding: 15px;
        }

        /* Form Styling */
        form {
            margin: 10px 0;
        }

        label {
            display: block;
            margin-top: 8px;
            font-size: 14px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .gender-options {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }

        .gender-options label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .gender-options input[type="radio"] {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 2px solid #007BFF;
            border-radius: 50%;
            outline: none;
            cursor: pointer;
            position: relative;
        }

        .gender-options input[type="radio"]:checked::before {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 8px;
            height: 8px;
            background-color: #007BFF;
            border-radius: 50%;
        }

        .phone-input {
            display: flex;
            align-items: center;
        }

        .phone-input span {
            background: #eee;
            padding: 8px;
            border: 1px solid #ccc;
            border-right: none;
            border-radius: 4px 0 0 4px;
        }

        .phone-input input {
            border-radius: 0 4px 4px 0;
            border-left: none;
            flex: 1;
        }

        /* Footer buttons */
        .save-close {
            padding: 15px;
            background-color: #E8F4FF;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
            box-sizing: border-box;
            z-index: 11;
        }

        .save-close button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .save-btn {
            background-color: #007BFF;
            color: white;
        }

        .close-btn-footer {
            background-color: #ccc;
        }

        .form-group {
            margin-bottom: 15px;
        }

        /* Ensure main content scrollable */
        .container.mt-5 {
            overflow: visible;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* View Panel Styles */
        #viewEmployeePanel {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 800px;
            height: 100vh;
            background: #fff;
            box-shadow: -2px 0 8px rgba(0, 0, 0, .2);
            z-index: 300;
            overflow-y: auto;
            transition: right 0.4s ease;
        }

        #viewEmployeePanel.open {
            right: 0;
        }

        .view-panel-header {
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            background-color: #DCEEFF;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
        }

        .view-panel-content {
            padding: 15px;
        }

        .view-section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .view-section-header {
            background-color: #F5F5F5;
            padding: 10px 15px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }

        .view-section-body {
            padding: 15px;
        }

        .view-row {
            display: flex;
            margin-bottom: 10px;
        }

        .view-label {
            font-weight: bold;
            width: 40%;
            color: #555;
        }

        .view-value {
            width: 60%;
        }

        /* Document viewer */
        .doc-viewer {
            max-width: 100%;
            max-height: 400px;
            margin: 0 auto;
            display: block;
        }

        .doc-iframe {
            width: 100%;
            height: 500px;
            border: none;
        }

        /* Tabs styling */
        .view-tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .view-tab {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid transparent;
            border-bottom: none;
            margin-right: 5px;
            border-radius: 5px 5px 0 0;
            background-color: #F5F5F5;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .view-tab:hover {
            background-color: #e9e9e9;
        }

        .view-tab.active {
            background-color: #fff;
            border-color: #ddd;
            border-bottom: 1px solid #fff;
            margin-bottom: -1px;
            color: #007BFF;
            font-weight: 600;
        }

        .view-tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .view-tab-content.active {
            display: block;
        }

        /* Reference section styling */
        .reference-section {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
        }

        .reference-header {
            font-weight: bold;
            margin-bottom: 10px;
            color: #555;
        }

        #signupFormContainer label {
            margin-top: 0;
            margin-bottom: 0;
        }

        #signupFormContainer input {
            width: 100%;
            margin-top: 0;
        }

        #signupFormContainer #terms {
            width: auto;
            margin-top: 0;
            margin-right: 8px;
        }
    </style>
    <style>
        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .erp-table thead {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .erp-table th {
            padding: 12px 8px;
            font-weight: 600;
            color: #475569;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #e2e8f0;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th:hover {
            background-color: #f1f5f9;
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: #cbd5e1;
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: #3b82f6;
        }

        .erp-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            transition: transform 0.2s;
        }

        .status-badge:hover {
            transform: scale(1.05);
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* Bulk Actions */
        .bulk-actions-container {
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bulk-actions-container.active {
            display: flex;
        }

        .selected-count {
            font-weight: 500;
            color: #475569;
            margin-right: auto;
            font-size: 14px;
        }

        .bulk-action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            color: #475569;
            transition: all 0.2s;
            font-size: 14px;
            display: flex;
            align-items: center;
            border: 1px solid transparent;
            margin-right: 5px;
        }

        .bulk-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .bulk-action-btn:active {
            transform: translateY(0);
        }

        .bulk-action-btn.download {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .bulk-action-btn.download:hover {
            background: #bbf7d0;
        }

        .bulk-action-btn.notice {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .bulk-action-btn.notice:hover {
            background: #fde68a;
        }

        .bulk-action-btn.exit {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .bulk-action-btn.exit:hover {
            background: #fecaca;
        }

        .bulk-action-btn.delete {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .bulk-action-btn.delete:hover {
            background: #fecaca;
        }

        .bulk-action-btn.clear {
            background: transparent;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }

        .bulk-action-btn.clear:hover {
            background: #f1f5f9;
        }

        .bulk-action-btn i {
            margin-right: 5px;
        }

        /* Checkbox styling */
        .select-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            border-radius: 4px;
            border: 2px solid #cbd5e1;
            transition: all 0.2s;
        }

        .select-checkbox:hover {
            border-color: #3b82f6;
        }

        .select-checkbox:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
        }

        /* Filter container */
        .filter-container {
            background: #fff;
            border-radius: 8px;
            padding: 6px 14px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            animation: slideUp 0.3s ease;
        }

        .filter-form {
            display: flex;
            justify-content: space-between;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-grid {
            display: flex;
            gap: 16px;
        }

        .filter-input {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
            transition: all 0.2s;
        }

        .filter-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        .filter-input:hover {
            border-color: #cbd5e1;
        }

        .filter-actions {
            display: flex;
            gap: 12px;
        }

        .btn-filter {
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-filter-secondary:hover {
            text-decoration: none;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-filter:active {
            transform: translateY(0);
        }

        .btn-filter-primary {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .btn-filter-primary:hover {
            background: #2563eb;
        }

        .btn-filter-secondary {
            background: #f1f5f9;
            color: #475569;
            border-color: #e2e8f0;
        }

        .btn-filter-secondary:hover {
            background: #e2e8f0;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
            animation: fadeIn 0.5s ease;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        /* Banner */
        .banner {
            height: 260px;
            border-radius: 18px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #2154BE, #3E70B3);
            display: flex;
            align-items: center;
            animation: fadeIn 0.8s ease;
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
            padding: 14px 20px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            animation: slideInLeft 0.5s ease;
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

        /* Action Buttons */
        .table-actions {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .action-btn {
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 5px;
            justify-content: center;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .action-btn:active {
            transform: translateY(0);
        }

        .action-btn-view {
            background: #e0f2fe;
            color: #0369a1;
            border-color: #bae6fd;
        }

        .action-btn-assign {
            background: #ffa0a0;
            color: #291515;
            border-color: #ffa0a0;
        }

        .action-btn-edit {
            background: #fef2c8;
            color: #92400e;
            border-color: #fef2c8;
        }

        .action-btn-delete {
            background: #fee2e2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .action-btn-assign:hover {
            color: #291515;
            text-decoration: none;
        }

        .action-btn-view:hover {
            background: #bae6fd;
            text-decoration: none;
        }

        .action-btn-edit:hover {
            background: #fef2c8;
            text-decoration: none;
            color: #92400e;
        }

        .action-btn-delete:hover {
            background: #fecaca;
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .empty-state-icon {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        /* Tooltips */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 120px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
        }

        .tooltip .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #333 transparent transparent transparent;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .bulk-actions-container {
                flex-direction: column;
                align-items: stretch;
            }

            .bulk-actions-container .d-flex {
                flex-wrap: wrap;
                gap: 8px;
            }

            .table-actions {
                flex-direction: column;
                gap: 4px;
            }

            .action-btn {
                width: 100%;
            }
        }
        
        .action-btn-card {
            background: #dcfce7;
            color: #166534;
            border-color: #bbf7d0;
        }

        .action-btn-card:hover {
            background: #bbf7d0;
            text-decoration: none;
            color: #166534;
        }
        
        /*.bulk-actions-container .dropdown-menu {*/
        /*    border-radius: 8px;*/
        /*    border: 1px solid #e2e8f0;*/
        /*    box-shadow: 0 8px 20px rgba(0,0,0,0.08);*/
        /*}*/
    
        /*.bulk-actions-container .dropdown-item {*/
        /*    font-size: 14px;*/
        /*    padding: 8px 14px;*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*}*/
    
        /*.bulk-actions-container .dropdown-item:hover {*/
        /*    background-color: #f1f5f9;*/
        /*}*/
    </style>

    <!-- Header with merchant name -->
    <div class="mainDiv1 d-none">
        <div class="mainDiv" style="height: 270px;">
            <div class="banner mb-4">
                @if(!empty($bannerPath))
                    <img src="{{ asset('/image/' . $fincapMerchants->documents->first()->institute_image_path) }}"
                        alt="institute image" class="bg">
                @else
                    <img src="{{ asset('/image/' . $fincapMerchants->documents->first()->institute_image_path) }}"
                        alt="institute image" class="bg">
                @endif
                <div class="meta">
                    <h3 style="margin:0;">
                        {{ $fincapMerchants->name ?? $fincapMerchants->fincap_merchant_name ?? 'Institute Name' }}
                    </h3>
                    <p style="margin:0;color:#444;">
                        {{ $fincapMerchants->state ?? $fincapMerchants->fincap_merchant_state ?? '' }},
                        {{ $fincapMerchants->city ?? $fincapMerchants->fincap_merchant_city ?? '' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Employee Management</h1>
            <button class="add-btn" onclick="window.location.href='/institute/admin/addemployeesdetails'">
                <i class="bi bi-plus-circle"></i>
                Add Employee
            </button>
        </div>

        {{-- Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Filters --}}
        <div class="filter-container">
            <form method="GET" class="filter-form" id="filterForm">
                <div class="filter-grid">
                    <div class="filter-group">
                        <input list="employeeNamesList" name="name" class="filter-input" value="{{ request(key: 'name') }}"
                            placeholder="Employee Name">
                        <datalist id="employeeNamesList">
                            @foreach($employeeNames as $emp)
                                <option value="{{ $emp->name }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="filter-group">
                        <input list="employeeCodeList" name="employee_code" class="filter-input"
                            value="{{ request('employee_code') }}" placeholder="Employee Code">
                        <datalist id="employeeCodeList">
                            @foreach($employeeCodes as $code)
                                <option value="{{ $code->employee_code }}">
                            @endforeach
                        </datalist>
                    </div>

                    <div class="filter-group d-none">
                        <input list="employeeDepartmentList"
                               name="department"
                               class="filter-input"
                               value="{{ request('department') }}"
                               placeholder="All Departments">
                    
                        <datalist id="employeeDepartmentList">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->department }}">
                                    <span class="small text-primary">({{ $dept->category->category_name }})</span>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="filter-group">
                        <input list="employeeDesignationList" name="designation" class="filter-input"
                            value="{{ request('designation') }}" placeholder="All Designations">
                        <datalist id="employeeDesignationList">
                            @foreach($designations as $ds)
                                <option value="{{ $ds->designations }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <div class="filter-actions">
                    <!-- <button type="submit" class="btn-filter btn-filter-primary">
                        <i class="bi bi-funnel"></i>
                        Apply Filters
                    </button> -->
                    <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                        <i class="bi bi-x-circle"></i>
                        Reset Filters
                    </a>
                </div>

                <!-- Hidden sort inputs -->
                <input type="hidden" name="sort_by" id="sortBy" value="{{ request('sort_by', 'employee_code') }}">
                <input type="hidden" name="sort_order" id="sortOrder" value="{{ request('sort_order', 'asc') }}">
            </form>
        </div>

        {{-- Bulk Actions Container --}}
        <div class="bulk-actions-container" id="bulkActionsContainer">
            <div class="selected-count" id="selectedCount">0 employees selected</div>
            <div class="d-flex flex-wrap">
                <span style="margin-right:5px;">
                    <select class="bulk-action-btn download" onchange="bulkAction('download', this.value)"
                        style="margin-right: 0px;margin-top:0px;">
                        <option value="">Download</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </span>
                
                <!--<span style="margin-right:5px;">-->
                <!--    <div class="dropdown me-2">-->
                <!--        <button class="bulk-action-btn download dropdown-toggle"-->
                <!--                type="button"-->
                <!--                data-bs-toggle="dropdown"-->
                <!--                aria-expanded="false">-->
                <!--            <i class="bi bi-download"></i>-->
                <!--            Download-->
                <!--        </button>-->

                <!--        <ul class="dropdown-menu">-->
                <!--            <li>-->
                <!--                <a class="dropdown-item" href="javascript:void(0)"-->
                <!--                onclick="bulkDownload('excel')">-->
                <!--                    <i class="bi bi-file-earmark-excel text-success me-2"></i>-->
                <!--                    Excel(.xlsx)-->
                <!--                </a>-->
                <!--            </li>-->
                <!--            <li>-->
                <!--                <a class="dropdown-item" href="javascript:void(0)"-->
                <!--                onclick="bulkDownload('csv')">-->
                <!--                    <i class="bi bi-file-earmark-text text-primary me-2"></i>-->
                <!--                    CSV(.csv)-->
                <!--                </a>-->
                <!--            </li>-->
                <!--        </ul>-->
                <!--    </div>-->
                <!--</span>-->

                <button class="bulk-action-btn notice d-none" onclick="bulkAction('send_notice')">
                    <i class="bi bi-envelope"></i>
                    Send Notice
                </button>
                <button class="bulk-action-btn exit d-none" onclick="bulkAction('bulk_exit')">
                    <i class="bi bi-door-open"></i>
                    Bulk Exit
                </button>
                <button class="bulk-action-btn delete" onclick="bulkAction('bulk_delete')">
                    <i class="bi bi-trash"></i>
                    Bulk Delete
                </button>
                <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                    <i class="bi bi-x-lg"></i>
                    Clear
                </button>
            </div>
        </div>

        {{-- Employee Table --}}
        <div class="table-responsive">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="selectAll" class="select-checkbox">
                        </th>
                        <th class="sortable d-none" onclick="sortTable('employee_code')">
                            Employee Code
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'employee_code' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'employee_code' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('name')">
                            Name
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('email')">
                            Email
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'email' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'email' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('mobile_number')">
                            Phone
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'mobile_number' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'mobile_number' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('department_name')">
                            Department
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'department_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'department_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('designation')">
                            Designation
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'designation' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'designation' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('status')">
                            Status
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'status' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                        <tr>
                            <td>
                                <input type="checkbox" class="employee-checkbox select-checkbox" value="{{ $emp->id }}">
                            </td>
                            <td class="d-none">{{ $emp->employee_code }}</td>
                            <td>{{ $emp->name }} <br> <span class="small text-primary">{{ $emp->employee_code }}</span></td>
                            <td>{{ $emp->email }}</td>
                            <td>{{ $emp->mobile_number ?? 'N/A' }}</td>
                            <td>{{ $emp->department_name ?? 'N/A' }}</td>
                            <td>{{ $emp->designation }}</td>
                            <td>
                                @php
                                    $statusClass = 'status-active';
                                    $statusText = 'Active';

                                    if (isset($emp->status)) {
                                        switch (strtolower($emp->status)) {
                                            case 'inactive':
                                                $statusClass = 'status-inactive';
                                                $statusText = 'Inactive';
                                                break;
                                            case 'pending':
                                                $statusClass = 'status-pending';
                                                $statusText = 'Pending';
                                                break;
                                            default:
                                                $statusClass = 'status-active';
                                                $statusText = 'Active';
                                        }
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td class="text-center">
                                <div class="table-actions">
                                    <a href="{{ route('employees.assign-responsibilities.form', $emp->employee_id) }}" class="action-btn action-btn-assign d-block"
                                        title="Assign Duties">
                                       <div>
                                            <i class="fas fa-tasks"></i>
                                       </div>
                                       <div>
                                         <span class="small">Assign Duties</span>
                                       </div>
                                    </a>

                                    <a href="{{ url('/employee-details/' . $emp->id) }}" class="action-btn action-btn-view d-block"
                                        title="View Details">
                                       <div>
                                          <i class="fa-regular fa-eye"></i>
                                       </div>
                                       <div>
                                         <span class="small">View</span>
                                       </div>
                                    </a>

                                    <a href="{{ route('employees.edit', $emp->employee_id) }}"
                                        class="action-btn action-btn-edit d-block" title="Edit Employee">
                                        <div>
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </div>

                                         <div>
                                         <span class="small">Edit</span>
                                       </div>
                                    </a>
                                    <a href="/employee/{{ $emp->employee_id }}/card"
                                        class="action-btn action-btn-card d-block" title="Employee Card">
                                        <div>
                                            <i class="fa-solid fa-id-card"></i>
                                        </div>
                                        <div>
                                            <span class="small">ID Card</span>
                                        </div>
                                    </a>
                                    <button class="action-btn action-btn-delete d-block" onclick="confirmDelete('{{ $emp->id }}')"
                                        title="Delete Employee">
                                        <div>
                                            <i class="fa-regular fa-trash-can"></i>
                                        </div>
                                        <div>
                                         <span class="small">Delete</span>
                                       </div>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($employees->count() == 0)
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <h4>No employees found</h4>
                                    <p>Try adjusting your filters or add a new employee</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($employees->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-sm text-gray-600">
                    Showing {{ $employees->firstItem() ?? 0 }} to {{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }}
                    results
                </div>
                <div>
                    {{ $employees->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>

    <div class="overlay" id="overlay" onclick="closePanels()"></div>
    <div class="overlay" id="overlayView" onclick="closePanels()"></div>

    <!-- View Employee Panel -->
    <div id="viewEmployeePanel">
        <div class="view-panel-header">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle"></i>
                Employee Details
            </div>
            <button class="close-btn" onclick="closePanels()">×</button>
        </div>

        <div class="view-tabs">
            <div class="view-tab active" data-tab="basic">
                <i class="bi bi-person me-1"></i> Basic
            </div>
            <div class="view-tab" data-tab="professional">
                <i class="bi bi-briefcase me-1"></i> Professional
            </div>
            <div class="view-tab" data-tab="contact">
                <i class="bi bi-telephone me-1"></i> Contact
            </div>
            <div class="view-tab" data-tab="documents">
                <i class="bi bi-file-text me-1"></i> Legal
            </div>
            <div class="view-tab" data-tab="bank">
                <i class="bi bi-bank me-1"></i> Bank
            </div>
        </div>

        <div class="view-panel-content">
            <div class="view-tab-content active" id="basic-tab">
                <div class="view-section">
                    <div class="view-section-header">Employee Information</div>
                    <div class="view-section-body" id="employee-info">
                        <!-- Employee info will be populated here -->
                    </div>
                </div>

                <div class="view-section">
                    <div class="view-section-header">Address Information</div>
                    <div class="view-section-body" id="address-info">
                        <!-- Address info will be populated here -->
                    </div>
                </div>
            </div>

            <div class="view-tab-content" id="professional-tab">
                <div class="view-section">
                    <div class="view-section-header">Professional Details</div>
                    <div class="view-section-body" id="professional-info">
                        <!-- Professional info will be populated here -->
                    </div>
                </div>
            </div>

            <div class="view-tab-content" id="contact-tab">
                <div class="view-section">
                    <div class="view-section-header">Emergency Contact</div>
                    <div class="view-section-body" id="contact-info">
                        <!-- Contact info will be populated here -->
                    </div>
                </div>

                <div class="view-section">
                    <div class="view-section-header">Reference Details</div>
                    <div class="view-section-body" id="reference-info">
                        <!-- Reference info will be populated here -->
                    </div>
                </div>
            </div>

            <div class="view-tab-content" id="documents-tab">
                <div class="view-section">
                    <div class="view-section-header">Legal Documents</div>
                    <div class="view-section-body" id="documents-info">
                        <!-- Documents info will be populated here -->
                    </div>
                </div>
            </div>

            <div class="view-tab-content" id="bank-tab">
                <div class="view-section">
                    <div class="view-section-header">Bank Information</div>
                    <div class="view-section-body" id="bank-info">
                        <!-- Bank info will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document View Modal -->
    <div class="modal fade" id="docModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Viewer</h5>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">X</button>
                </div>
                <div class="modal-body text-center" id="docPreview">
                    <!-- Document will be shown here -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this employee? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bulk Selection Management
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('selectAll');
            const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
            const bulkActionsContainer = document.getElementById('bulkActionsContainer');
            const selectedCountElement = document.getElementById('selectedCount');

            // Select All functionality
            selectAllCheckbox.addEventListener('change', function () {
                employeeCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectionUI();
            });

            // Individual checkbox change
            employeeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectionUI);
            });

            function updateSelectionUI() {
                const selectedCount = document.querySelectorAll('.employee-checkbox:checked').length;

                if (selectedCount > 0) {
                    bulkActionsContainer.classList.add('active');
                    selectedCountElement.textContent = selectedCount + ' employee(s) selected';

                    // Update select all checkbox state
                    selectAllCheckbox.checked = selectedCount === employeeCheckboxes.length;
                    selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < employeeCheckboxes.length;
                } else {
                    bulkActionsContainer.classList.remove('active');
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                }
            }
        });

        // Bulk Action Functions
        function clearSelection() {
            document.querySelectorAll('.employee-checkbox:checked').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAll').checked = false;
            document.getElementById('bulkActionsContainer').classList.remove('active');
        }

        function bulkAction(action, format = null) {
            const selectedEmployees = Array.from(document.querySelectorAll('.employee-checkbox:checked'))
                .map(checkbox => checkbox.value);

            if (selectedEmployees.length === 0) {
                alert('Please select at least one employee.');
                return;
            }

            switch (action) {
                case 'download':

                    if (!format) {
                        alert("Please select a format");
                        return;
                    }

                    const ids = selectedEmployees.join(',');

                    const url = `/employees/bulk-download?ids=${ids}&type=${format}`;

                    window.location.href = url;

                    break;
                case 'bulk_delete':
                    if (confirm(`Are you sure you want to delete ${selectedEmployees.length} employee(s)? This action cannot be undone.`)) {
                        // Show loading state
                        const btn = event.target.closest('button');
                        const originalHTML = btn.innerHTML;
                        btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                        btn.disabled = true;

                        // Simulate delete
                        setTimeout(() => {
                            btn.innerHTML = originalHTML;
                            btn.disabled = false;
                            clearSelection();
                            alert(`${selectedEmployees.length} employees deleted successfully`);
                        }, 1500);
                    }
                    break;

                default:
                    alert(`${action} action triggered for ${selectedEmployees.length} employees`);
            }
        }

        // Sorting Functionality
        function sortTable(column) {
            const currentSortBy = document.getElementById('sortBy').value;
            const currentSortOrder = document.getElementById('sortOrder').value;

            let newSortOrder = 'asc';

            if (currentSortBy === column) {
                newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
            }

            document.getElementById('sortBy').value = column;
            document.getElementById('sortOrder').value = newSortOrder;

            // Submit the form
            document.getElementById('filterForm').submit();
        }

        // Panel Functions
        function openPanel() {
            document.getElementById("sidePanel").classList.add("open");
            document.getElementById("overlay").classList.add("show");
            document.body.style.overflow = 'hidden';
        }

        function openViewPanel() {
            document.getElementById("viewEmployeePanel").classList.add("open");
            document.getElementById("overlayView").classList.add("show");
            document.body.style.overflow = "hidden";
        }

        function closePanels() {
            const sidePanel = document.getElementById("sidePanel");
            const overlay = document.getElementById("overlay");
            const viewPanel = document.getElementById("viewEmployeePanel");
            const overlayView = document.getElementById("overlayView");

            if (sidePanel) {
                sidePanel.classList.remove("open");
                sidePanel.style.right = "";
            }

            if (overlay) overlay.classList.remove("show");

            if (viewPanel) {
                viewPanel.classList.remove("open");
                viewPanel.style.right = "";
            }

            if (overlayView) overlayView.classList.remove("show");

            document.body.style.overflow = "";
        }

        // Tab switching for view panel
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('.view-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    document.querySelectorAll('.view-tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.view-tab-content').forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
        });

        // Document Viewer
        $(document).on('click', '.view-doc', function () {
            let filePath = $(this).data('file');
            let previewHtml = '';
            let cacheBuster = '?t=' + new Date().getTime();

            if (filePath.match(/\.(jpg|jpeg|png|gif|webp|bmp)$/i)) {
                previewHtml = `<img src="${filePath}${cacheBuster}" class="doc-viewer" alt="Document">`;
            } else if (filePath.match(/\.(pdf)$/i)) {
                previewHtml = `<iframe src="${filePath}${cacheBuster}#view=fitH" class="doc-iframe" frameborder="0"></iframe>`;
            } else {
                previewHtml = `<div class="p-3"><a href="${filePath}" target="_blank" class="btn btn-primary">Open Document</a></div>`;
            }

            $('#docPreview').html(previewHtml);
            $('#docModal').modal('show');
        });

        // Delete Confirmation
        let employeeToDelete = null;

        function confirmDelete(employeeId) {
            employeeToDelete = employeeId;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            if (employeeToDelete) {
                // Show loading
                const btn = this;
                const originalText = btn.textContent;
                btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                btn.disabled = true;

                // Simulate delete API call
                setTimeout(() => {
                    alert('Employee deleted successfully');
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    btn.textContent = originalText;
                    btn.disabled = false;
                    employeeToDelete = null;

                    // Reload page or remove row
                    location.reload();
                }, 1500);
            }
        });

        // View Employee Function
        function viewEmployee(id) {
            openViewPanel();

            // Reset content
            $("#employee-info, #address-info, #professional-info, #contact-info, #documents-info, #bank-info, #reference-info")
                .html('<div class="text-center py-4"><div class="loading-spinner mb-2"></div><p>Loading...</p></div>');

            $.ajax({
                url: '/employee-details/' + id,
                method: 'GET',
                success: function (e) {
                    // Employee Info
                    $('#employee-info').html(`
                        <div class="view-row">
                            <div class="view-label">Employee Code</div>
                            <div class="view-value">${e.employee_code || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Name</div>
                            <div class="view-value">${e.name || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Gender</div>
                            <div class="view-value">${e.gender || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Email</div>
                            <div class="view-value">${e.email || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Phone</div>
                            <div class="view-value">${e.mobile_number || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Date of Birth</div>
                            <div class="view-value">${e.dob || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Department</div>
                            <div class="view-value">${e.department_name || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Designation</div>
                            <div class="view-value">${e.designation || '-'}</div>
                        </div>
                    `);

                    // Address Info
                    $('#address-info').html(`
                        <div class="view-row">
                            <div class="view-label">Address Line 1</div>
                            <div class="view-value">${e.addressline1 || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Address Line 2</div>
                            <div class="view-value">${e.addressline2 || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">City</div>
                            <div class="view-value">${e.city || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">State</div>
                            <div class="view-value">${e.state || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Pincode</div>
                            <div class="view-value">${e.pincode || '-'}</div>
                        </div>
                    `);

                    // Professional Info
                    $('#professional-info').html(`
                        <div class="view-row">
                            <div class="view-label">Employment Type</div>
                            <div class="view-value">${e.employment_type || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Salary Type</div>
                            <div class="view-value">${e.salary_type || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Date of Joining</div>
                            <div class="view-value">${e.doj || '-'}</div>
                        </div>
                    `);

                    // Contact Info
                    $('#contact-info').html(`
                        <div class="view-row">
                            <div class="view-label">Emergency Contact</div>
                            <div class="view-value">${e.emergency_contact_number || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Contact Person</div>
                            <div class="view-value">${e.contact_person_name || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Relation</div>
                            <div class="view-value">${e.relation_with_contact || '-'}</div>
                        </div>
                    `);

                    // Reference Info
                    $('#reference-info').html(`
                        <div class="view-row">
                            <div class="view-label">Reference Name</div>
                            <div class="view-value">${e.reference_name || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Reference Contact</div>
                            <div class="view-value">${e.reference_contact_number || '-'}</div>
                        </div>
                    `);

                    // Documents Info
                    $('#documents-info').html(`
                        <div class="view-row">
                            <div class="view-label">Aadhaar Card</div>
                            <div class="view-value">
                                ${e.aadhaar_card
                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.aadhaar_card}">
                                        <i class="bi bi-eye me-1"></i>View
                                       </button>`
                            : '<span class="text-muted">Not Uploaded</span>'}
                            </div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">PAN Card</div>
                            <div class="view-value">
                                ${e.pan_card
                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.pan_card}">
                                        <i class="bi bi-eye me-1"></i>View
                                       </button>`
                            : '<span class="text-muted">Not Uploaded</span>'}
                            </div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Driving License</div>
                            <div class="view-value">
                                ${e.driving_license
                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.driving_license}">
                                        <i class="bi bi-eye me-1"></i>View
                                       </button>`
                            : '<span class="text-muted">Not Uploaded</span>'}
                            </div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Passport Photo</div>
                            <div class="view-value">
                                ${e.passport_photo
                            ? `<button class="btn btn-primary btn-sm view-doc" data-file="/image/${e.passport_photo}">
                                        <i class="bi bi-eye me-1"></i>View
                                       </button>`
                            : '<span class="text-muted">Not Uploaded</span>'}
                            </div>
                        </div>
                    `);

                    // Bank Info
                    $('#bank-info').html(`
                        <div class="view-row">
                            <div class="view-label">Bank Name</div>
                            <div class="view-value">${e.bank_name || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Branch Name</div>
                            <div class="view-value">${e.branch_name || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">Account Number</div>
                            <div class="view-value">${e.account_number || '-'}</div>
                        </div>
                        <div class="view-row">
                            <div class="view-label">IFSC Code</div>
                            <div class="view-value">${e.ifsc_code || '-'}</div>
                        </div>
                    `);
                },
                error: function () {
                    alert("Failed to load employee details.");
                    closePanels();
                }
            });
        }

        // Filter form submission with loading state
        document.getElementById('filterForm').addEventListener('submit', function (e) {
            const submitBtn = this.querySelector('.btn-filter-primary');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading-spinner"></span> Applying...';
            submitBtn.disabled = true;

            // Re-enable button after 2 seconds in case of error
            setTimeout(() => {
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }, 2000);
        });


        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('filterForm');
            let typingTimer;

            const delay = 400;

            const autoInputs = [
                'name',
                'employee_code',
                'department_id',
                'designation'
            ];

            autoInputs.forEach(name => {

                const input = document.querySelector(`input[name="${name}"]`);

                if (!input) return;

                input.addEventListener('input', function () {

                    clearTimeout(typingTimer);

                    typingTimer = setTimeout(() => {
                        form.submit();
                    }, delay);

                });
            });

        });

    </script>
@endsection