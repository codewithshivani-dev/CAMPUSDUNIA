@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Requests Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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


        .header-section {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title i {
            font-size: 2rem;
            background: #fff;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .breadcrumb {
            color: #64748b;
            font-size: 0.9rem;
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .journey-btn a:hover, .journey-btn a {
            color: #fff !important;
            text-decoration: auto;
        }

        /* Top Section */
        .top-section {
            display: flex;
            align-items: stretch;
            gap: 20px;
            margin-bottom: 30px;
        }

        .apply-loan-wrapper {
            flex: 0 0 280px;
            display: flex;
            align-items: stretch;
        }

        .apply-loan-btn {
            width: 100%;
            padding: 0 20px;
            background: white;
            color: var(--primary-color);
            border: 2px solid rgba(67, 97, 238, 0.2);
            border-radius: 15px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            white-space: nowrap;
        }

        .apply-loan-btn i {
            font-size: 22px;
        }

        .apply-loan-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        .stats-wrapper {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 2px solid rgba(67, 97, 238, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(67, 97, 238, 0.15);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 22px;
            color: white;
        }

        .stat-icon.total {
            background: var(--primary-gradient);
        }

        .stat-icon.pending {
            background: var(--warning-gradient);
        }

        .stat-icon.approved {
            background: var(--success-gradient);
        }

        .stat-icon.rejected {
            background: var(--danger-gradient);
        }

        .stat-label {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-top: auto;
        }

        .table-container {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            border: 2px solid rgba(67, 97, 238, 0.1);
            /*overflow-x: auto;*/
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border: 2px solid rgba(67, 97, 238, 0.2);
            border-radius: 30px;
            font-size: 0.9rem;
            width: 250px;
            transition: border-color 0.3s ease;
            background: #fafbfc;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
        }

        .filter-btn {
            padding: 8px 18px;
            border: 2px solid rgba(67, 97, 238, 0.15);
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            color: #64748b;
        }

        .filter-btn:hover {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.05), rgba(58, 12, 163, 0.05));
            border-color: var(--primary-color);
        }

        .filter-btn.active {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        thead
        {
            background:var(--primary-gradient);
        }
        th {
            color: white;
            padding: 15px;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }

        th:first-child {
            border-top-left-radius: 10px;
        }

        th:last-child {
            border-top-right-radius: 10px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid rgba(67, 97, 238, 0.08);
            color: #475569;
            font-size: 0.9rem;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.04), rgba(58, 12, 163, 0.04));
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
            text-align: center;
            min-width: 90px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
        }

        .status-pending {
            background: var(--warning-gradient);
            box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
        }

        .status-approved {
            background: var(--success-gradient);
            box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
        }

        .status-rejected {
            background: var(--danger-gradient);
            box-shadow: 0 3px 10px rgba(239, 68, 68, 0.3);
        }

        .status-disbursed {
            background: var(--info-gradient);
            box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .journey-btn {
            padding: 8px 16px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            box-shadow: 0 3px 10px rgba(67, 97, 238, 0.3);
        }

        .journey-btn i {
            font-size: 0.8rem;
        }

        .journey-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }

        .amount-cell {
            font-weight: 700;
            color: #1e293b;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
        }

        .no-data i {
            font-size: 4rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-data p {
            color: #64748b;
            font-size: 1rem;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            margin: 50px auto;
            padding: 0;
            width: 90%;
            max-width: 600px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s ease;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            padding: 20px 25px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            font-size: 1.2rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .modal-body {
            padding: 25px;
        }

        .journey-timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 30px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -22px;
            top: 0;
            width: 2px;
            height: 100%;
            background: rgba(67, 97, 238, 0.15);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: -30px;
            top: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: white;
            border: 3px solid rgba(67, 97, 238, 0.3);
        }

        .timeline-dot.completed {
            background: var(--primary-gradient);
            border-color: var(--primary-color);
        }

        .timeline-dot.current {
            background: var(--warning-gradient);
            border-color: #f59e0b;
            animation: pulse 2s infinite;
        }

        .timeline-content {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(67, 97, 238, 0.1);
        }

        .timeline-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .timeline-date {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-bottom: 5px;
        }

        .timeline-desc {
            font-size: 0.85rem;
            color: #475569;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.85rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(67, 97, 238, 0.2);
            border-radius: 12px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
            background: #fafbfc;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 8px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .top-section {
                flex-direction: column;
            }
            .apply-loan-wrapper {
                flex: 0 0 auto;
                width: 100%;
            }
            .apply-loan-btn {
                padding: 18px 20px;
            }
        }

        @media (max-width: 768px) {
            .stats-wrapper {
                grid-template-columns: repeat(2, 1fr);
            }
            .form-row {
                grid-template-columns: 1fr;
            }
            .table-header {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box input {
                width: 100%;
            }
            .filter-buttons {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stats-wrapper {
                grid-template-columns: 1fr;
            }
        }
        
                /* ERP Table Styles */
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

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
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
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
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
        
        .table-responsive{
            overflow-x: hidden;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header-section">
            <h1 class="page-title">
                <i class="fas fa-file-invoice"></i>
                Education Loan
            </h1>
            <!--<div class="breadcrumb">-->
            <!--    <a href="#"><i class="fas fa-home"></i> Dashboard</a> / Loan Requests-->
            <!--</div>-->
        </div>

        <!-- Top Section with Apply Button and Stats -->
        <div class="top-section">
            <div class="stats-wrapper">
                @php
                    $totalLoans = $loanRequests->count();
                    $pendingLoans = $loanRequests->where('loan_status', 'pending')->count();
                    $approvedLoans = $loanRequests->where('loan_status', 'approved')->count();
                    $rejectedLoans = $loanRequests->where('loan_status', 'rejected')->count();
                @endphp
                
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="stat-label">Total Applications</div>
                    <div class="stat-value">{{ $totalLoans }}</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ $pendingLoans }}</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon approved">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-label">Approved</div>
                    <div class="stat-value">{{ $approvedLoans }}</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon rejected">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-label">Rejected</div>
                    <div class="stat-value">{{ $rejectedLoans }}</div>
                </div>
            </div>
        </div>
        
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">
                    <i class="fas fa-list-ul"></i>
                    All Loan Applications
                </h2>
                <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Search applications..." onkeyup="filterTable()">
                    </div>
                    <div class="filter-buttons">
                        <button class="filter-btn active" onclick="filterByStatus('all')">All</button>
                        <button class="filter-btn" onclick="filterByStatus('pending')">Pending</button>
                        <button class="filter-btn" onclick="filterByStatus('approved')">Approved</button>
                        <button class="filter-btn" onclick="filterByStatus('rejected')">Rejected</button>
                    </div>
                </div>
            </div>
            
            @if($loanRequests->isEmpty())
                <div class="no-data">
                    <i class="fas fa-inbox"></i>
                    <p>No loan requests found.</p>
                    <button class="apply-loan-btn" style="margin-top: 20px; width: auto; padding: 14px 28px;" onclick="openApplyModal()">
                        <i class="fas fa-plus-circle"></i>
                        Apply for Your First Loan
                    </button>
                </div>
            @else
            <div>
                <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                    <table class="erp-table" id="loanTable">
                        <thead>
                            <tr>
                                <th class="sticky-main-2 sortable">Loan ID</th>
                                <th class="sortable">Applicant Name</th>
                                <th class="sortable">Phone</th>
                                <th class="sortable">Email</th>
                                <th class="sortable">Apply For</th>
                                <th class="sortable">Loan Amount (₹)</th>
                                <th class="sortable">Tenure</th>
                                <th class="sortable">Status</th>
                                <th class="sortable">Applied On</th>
                                <th class="sortable">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loanRequests as $loan)
                                <tr data-status="{{ strtolower($loan->loan_status) }}">
                                    <td class="sticky-main-2"><strong>#{{ $loan->loan_request_id }}</strong></td>
                                    <td>{{ $loan->student_name ?? 'N/A' }}</td>
                                    <td>{{ $loan->mobile_number }}</td>
                                    <td>{{ $loan->email_id }}</td>
                                    <td>{{ $loan->institute_course ?? 'N/A' }}</td>
                                    <td class="amount-cell">₹{{ number_format($loan->loan_amount, 2) }}</td>
                                    <td>{{ $loan->scheme_tenure }} Months</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($loan->loan_status) }}">
                                            {{ ucfirst($loan->loan_status) }}
                                        </span> 
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('d M Y') }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="journey-btn">
                                                <i class="fas fa-road"></i>
                                                <a href="/loan/journey/journey/show/{{ $loan->loan_request_id }}">Check Journey</a>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Floating Horizontal Scrollbar -->
                <div class="table-scroll-top" id="tableScrollTop">
                    <div class="table-scroll-inner"></div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Check Journey Modal -->
    <div id="journeyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-road" style="margin-right: 10px;"></i>Loan Journey - <span id="journeyLoanId"></span></h3>
                <button class="close-btn" onclick="closeJourneyModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="journey-timeline" id="journeyTimeline">
                    <!-- Timeline will be populated dynamically -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter table by status
        function filterByStatus(status) {
            const rows = document.querySelectorAll('#loanTable tbody tr');
            const buttons = document.querySelectorAll('.filter-btn');
            
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.textContent.toLowerCase().includes(status)) {
                    btn.classList.add('active');
                }
                if (status === 'all' && btn.textContent === 'All') {
                    btn.classList.add('active');
                }
            });
            
            rows.forEach(row => {
                if (status === 'all') {
                    row.style.display = '';
                } else {
                    if (row.dataset.status === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }
        
        // Search functionality
        function filtertable() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('loanTable');
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                let textContent = '';
                const cells = row.getElementsByTagName('td');
                
                for (let j = 0; j < cells.length - 1; j++) {
                    textContent += cells[j].textContent + ' ';
                }
                
                if (textContent.toUpperCase().indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
        
        // Check Journey Function
        function checkJourney(loanId, studentName, currentStatus) {
            document.getElementById('journeyLoanId').textContent = `#${loanId} - ${studentName}`;
            
            const timeline = document.getElementById('journeyTimeline');
            const statuses = ['pending', 'verified', 'approved', 'disbursed'];
            const currentIndex = statuses.indexOf(currentStatus.toLowerCase());
            
            let timelineHTML = '';
            
            const steps = [
                {
                    status: 'pending',
                    title: 'Application Submitted',
                    icon: 'fa-file-alt',
                    description: 'Your loan application has been submitted successfully.'
                },
                {
                    status: 'verified',
                    title: 'Document Verification',
                    icon: 'fa-check-double',
                    description: 'Your documents are being verified by our team.'
                },
                {
                    status: 'approved',
                    title: 'Loan Approved',
                    icon: 'fa-check-circle',
                    description: 'Congratulations! Your loan has been approved.'
                },
                {
                    status: 'disbursed',
                    title: 'Amount Disbursed',
                    icon: 'fa-money-bill-wave',
                    description: 'The loan amount has been disbursed to your account.'
                }
            ];
            
            steps.forEach((step, index) => {
                const stepStatus = step.status;
                let statusClass = '';
                let statusText = '';
                
                if (statuses.indexOf(stepStatus) < currentIndex) {
                    statusClass = 'completed';
                    statusText = 'Completed';
                } else if (statuses.indexOf(stepStatus) === currentIndex) {
                    statusClass = 'current';
                    statusText = 'Current';
                } else {
                    statusText = 'Pending';
                }
                
                timelineHTML += `
                    <div class="timeline-item">
                        <div class="timeline-dot ${statusClass}"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">
                                <i class="fas ${step.icon}" style="margin-right: 8px;"></i>
                                ${step.title}
                                ${statusText ? `<span style="font-size: 11px; margin-left: 10px; color: #999;">(${statusText})</span>` : ''}
                            </div>
                            <div class="timeline-date">
                                ${index < currentIndex ? new Date().toLocaleDateString('en-IN') : 'Pending'}
                            </div>
                            <div class="timeline-desc">${step.description}</div>
                        </div>
                    </div>
                `;
            });
            
            timeline.innerHTML = timelineHTML;
            document.getElementById('journeyModal').style.display = 'block';
        }
        
        function closeJourneyModal() {
            document.getElementById('journeyModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const journeyModal = document.getElementById('journeyModal');
            if (event.target == journeyModal) {
                journeyModal.style.display = 'none';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function(event) {
                    if (event.key === 'Enter') {
                        filterTable();
                    }
                });
            }
        });
    </script>
</body>
@endsection