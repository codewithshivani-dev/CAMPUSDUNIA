@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Return Books Management</title>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* ERP Table Styles */
    .erp-table {
        width: 100%;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }
    
    .erp-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .erp-table th {
         width: auto;
        padding: 12px 16px;
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
        padding: 12px 16px;
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
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .erp-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .erp-table tbody tr.selected-row {
        background-color: #e0f2fe !important;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: transform 0.2s;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
    
    .status-issued {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    
    .status-overdue {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .status-returned {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }
    
    .status-reissued {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
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
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .page-subtitle {
        color: #64748b;
        font-size: 14px;
        margin-top: 4px;
    }
    
    /* Card Styling */
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        overflow: hidden;
    }
    
    .card-header {
        padding: 16px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-body {
        padding: 24px;
    }
    
    /* Book Count Badge */
    .book-count {
        background: #3b82f6;
        color: white;
        font-size: 12px;
        padding: 2px 10px;
        border-radius: 12px;
        font-weight: 500;
    }
    
    /* Back Button */
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
    
    /* Radio Select */
    .radio-select {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        transition: all 0.2s;
    }
    
    .radio-select:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    /* Book Info */
    .book-info {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .book-id {
        color: #3b82f6;
        font-family: monospace;
        background: #eff6ff;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
    }
    
    /* Person Type */
    .person-type {
        display: inline-block;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
        /* background: #f1f5f9;
        color: #64748b; */
        margin-left: 4px;
    }
    
    /* Amount Cells */
    .amount-cell {
        font-family: 'SF Mono', 'Monaco', 'Inconsolata', monospace;
        font-weight: 500;
    }
    
    .text-danger {
        color: #dc2626 !important;
    }
    
    .text-success {
        color: #16a34a !important;
    }
    
    .text-warning {
        color: #d97706 !important;
    }
    
    /* Filter container */
    .filter-container {
        background: #fff;
        border-radius: 8px;
        padding: 8px 16px;
        border: 1px solid #e2e8f0;
    }
    
    .search-box {
        position: relative;
        width: 300px;
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }
    
    .search-input {
        width: 100%;
        height: 36px;
        padding: 0 12px 0 36px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        background: white;
        color: #334155;
        transition: all 0.2s;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Fine Summary Card */
    .fine-card {
        border-left: 4px solid #f59e0b;
        margin-top: 24px;
    }
    
    .fine-card .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .fine-card .form-label {
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 8px;
    }
    
    .fine-card .form-select {
        height: 44px;
        padding: 0 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        background: white;
        transition: all 0.2s;
    }
    
    .fine-card .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Payment Link Container */
    .payment-link-container {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px;
        margin-top: 16px;
        border: 1px solid #e2e8f0;
    }
    
    /* Submit Button */
    .submit-btn {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 32px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .submit-btn:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    .btn-outline-primary {
        background: white;
        color: #3b82f6;
        border: 1px solid #3b82f6;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-outline-primary:hover {
        background: #eff6ff;
        color: #3b82f6;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }
    
    .empty-state-icon {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
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
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .card-header {
            flex-direction: column;
            gap: 12px;
            align-items: stretch;
        }
        
        .search-box {
            width: 100%;
        }
        
        .filter-container {
            width: 100%;
        }
        
        .erp-table {
            font-size: 12px;
        }
        
        .erp-table th,
        .erp-table td {
            padding: 8px 12px;
        }
    }
</style>

<div class="container">
    <!-- Header -->
    <div class="page-header">
            <h1 class="page-title">Return Books</h1>
            <!-- <p class="page-subtitle">Manage book returns and fine payments</p> -->
 
        <a href="{{ route('library.issue.create') }}" class="add-btn">
            <i class="bi bi-arrow-left"></i>
            Back to Issues
        </a>
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

    <!-- Main Card -->
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="bi bi-list-check text-primary"></i> 
                Issued Books 
                <span class="book-count">{{ $issues->count() }}</span>
            </h2>
            <div class="filter-container">
                <div class="search-box">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" 
                           id="bookSearch" 
                           class="search-input" 
                           placeholder="Search issued books...">
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($issues->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4>No Books Found</h4>
                    <p>No books are currently issued for return</p>
                </div>
            @else
                <form method="POST" action="{{ route('library.return.store') }}" id="returnForm">
                    @csrf
                    
                    <!-- Books Table -->
                    <div class="table-responsive">
                        <table class="erp-table">
                            <thead>
                                <tr>
                                    <th width="60" class="text-center">Select</th>
                                    <th class="sortable " onclick="sortTable('title')">
                                        Book Title
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <th class="sortable " onclick="sortTable('issued_to')">
                                        Issued To
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <!-- <th class="sortable " onclick="sortTable('code')">
                                        Code
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th> -->
                                    <th class="sortable " onclick="sortTable('issue_date')">
                                        Issue Date
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <th class="sortable " onclick="sortTable('due_date')">
                                        Due Date
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <th class="sortable " onclick="sortTable('days_overdue')" class="text-center">
                                        Overdue Days
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <th class="sortable " onclick="sortTable('fine_amount')" class="text-end">
                                        Fine Amount
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <th class="sortable " onclick="sortTable('paid_amount')" class="text-end">
                                        Paid
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <th class="sortable " onclick="sortTable('due_amount')" class="text-end">
                                        Due
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <th class="sortable " onclick="sortTable('payment_status')" class="text-center">
                                        Payment Status
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <th class="sortable " onclick="sortTable('payment_method')" class="text-center">
                                        Payment Method
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                    <th class="sortable " onclick="sortTable('status')" class="text-center">
                                        Status
                                        <div class="sort-icons">
                                            <i class="sort-icon bi bi-caret-up-fill"></i>
                                            <i class="sort-icon bi bi-caret-down-fill"></i>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="issuedBooksContainer">
                                @foreach($issues as $issue)
                                   @php
                                        $due = \Carbon\Carbon::parse($issue->due_date);
                                        $today = \Carbon\Carbon::today();
                                        $daysOverdue = $today->gt($due) ? $due->diffInDays($today) : 0;
                                        $finePerDay = 5;
                                        $fineAmount = $daysOverdue * $finePerDay;
                                        
                                        if ($issue->status == "returned") {
                                            $daysOverdue = $issue->fine->days_overdue ?? 0;
                                            $fineAmount = $issue->fine->amount ?? 0;
                                        }
                                        
                                        $paidAmount = $issue->paymentLinks->amount ?? '0.00';
                                        $remainingAmount = $fineAmount - $paidAmount;
                                        
                                        if($issue->issueable_type == "App\Models\StudentParentDetails"){
                                            $name = isset($issue->issueable->first_name) ? $issue->issueable->first_name : 'N/A' ;
                                            $code = isset($issue->issueable->registration_number) ? $issue->issueable->registration_number : 'N/A' ;
                                        }elseif($issue->issueable_type == "App\Models\EmployeeDetails"){
                                            $name = isset($issue->issueable->name) ? $issue->issueable->name : 'N/A';
                                            $code = isset($issue->issueable->employee_code) ? $issue->issueable->employee_code : 'N/A' ;
                                        }
                                    @endphp
                                    <tr class="book-row" id="row-{{ $issue->id }}">
                                        <td class="text-center">
                                            <input type="radio" 
                                                   name="book_issue_id" 
                                                   value="{{ $issue->id }}" 
                                                   data-fine="{{ $fineAmount }}" 
                                                   data-title="{{ $issue->libraryBook->title }}" 
                                                   data-id="{{ $issue->id }}"
                                                   class="radio-select"
                                                   required>
                                        </td>
                                        <td>
                                            {{ $issue->libraryBook->title ?? 'N/A' }}
                                            <div class="book-info">
                                                <!-- <span class="small text-primary">ID: {{ $issue->libraryBook->librarybook_id ?? 'N/A' }}</span> -->
                                            </div>
                                        </td>
                                        <td>
                                            {{ $name ?? 'N/A' }}
                                            <div class="book-info">
                                                @if($issue->issueable_type == 'App\Models\StudentParentDetails')
                                                    <span class="small text-primary">Student</span>
                                                @elseif($issue->issueable_type == 'App\Models\EmployeeDetails')
                                                    <span class="small text-primary">Employee</span>
                                                @endif
                                            </div>
                                        </td>
                                        <!-- <td>
                                            <span class="person-type">{{ $code ?? 'N/A' }}</span>
                                        </td> -->
                                        <td>{{ \Carbon\Carbon::parse($issue->issue_date)->format('d M Y') }}</td>
                                        <td>
                                            <span class="{{ $today->gt($due) ? 'text-danger fw-medium' : '' }}">
                                                {{ $due->format('d M Y') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($daysOverdue > 0)
                                                <span class="">
                                                   
                                                    {{ $daysOverdue }} days
                                                </span>
                                            @else
                                                <span class="status-badge status-returned">
                                                    
                                                    On time
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end amount-cell">
                                            ₹{{ number_format($fineAmount, 2) }}
                                        </td>
                                        <td class="text-end amount-cell text-success">
                                            ₹{{ number_format($paidAmount, 2) }}
                                        </td>
                                        <td class="text-end amount-cell {{ $remainingAmount > 0 ? 'text-danger' : 'text-success' }}">
                                            ₹{{ number_format($remainingAmount, 2) }}
                                        </td>
                                        <td class="text-center">
                                            @php
                                                if(isset($issue->paymentLinks->status)) {
                                                    $badgepgClass = match($issue->paymentLinks->status) {
                                                        'created' => 'status-issued',
                                                        'failed' => 'status-overdue',
                                                        'expired' => 'status-overdue',
                                                        'paid' => 'status-returned',
                                                        default => ''
                                                    };
                                                    $paymentStatus = ucfirst($issue->paymentLinks->status);
                                                } else {
                                                    if(isset($issue->fine->payment_method) && $issue->fine->payment_method == 'cash') {
                                                        $badgepgClass = 'status-returned';
                                                        $paymentStatus = 'Paid';
                                                    } elseif (!isset($issue->fine->payment_method)){
                                                        $badgepgClass = '';
                                                        $paymentStatus = 'Not Required'; 
                                                    } else {
                                                        $badgepgClass = 'status-overdue';
                                                        $paymentStatus = 'Pending'; 
                                                    }
                                                }
                                            @endphp
                                            <span class="status-badge {{ $badgepgClass }}">
                                                @if($paymentStatus == 'Paid')
                                                    <i class="bi bi-check-circle"></i>
                                                @elseif($paymentStatus == 'Pending')
                                                    <i class="bi bi-clock"></i>
                                                @endif
                                                {{ $paymentStatus }}
                                            </span>
                                            <input type="hidden" name="payment_status" value="{{ $paymentStatus }}">
                                        </td>
                                        <td class="text-center">
                                            @if($issue->fine->payment_method ?? false)
                                                <span class="person-type">
                                                    {{ ucfirst($issue->fine->payment_method) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $badgeClass = match($issue->status) {
                                                    'issued' => 'status-issued',
                                                    'overdue' => 'status-overdue',
                                                    'reissued' => 'status-reissued',
                                                    'returned' => 'status-returned',
                                                    default => ''
                                                };
                                                $statusIcon = match($issue->status) {
                                                    'issued' => 'bi-arrow-up-circle',
                                                    'overdue' => 'bi-exclamation-triangle',
                                                    'reissued' => 'bi-arrow-repeat',
                                                    'returned' => 'bi-arrow-down-circle',
                                                    default => 'bi-question-circle'
                                                };
                                            @endphp
                                            <span class="status-badge {{ $badgeClass }}">
                                                <i class="bi {{ $statusIcon }}"></i>
                                                {{ ucfirst($issue->status) }}
                                            </span>
                                            <input type="hidden" name="return_status" value="{{ $issue->status }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Fine & Payment Section -->
                    <div id="fineSummary" class="d-none">
                        <div class="card fine-card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-cash-coin text-warning"></i>
                                    Fine & Payment Details
                                </h5>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3 ">
                                        <label class="form-label">Selected Book</label>
                                        <div class="h5 fw-bold text-dark" id="selectedBook">-</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Total Fine Amount</label>
                                        <div class="h5 text-warning fw-bold" id="selectedFine">₹0.00</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3  ">
                                        <label class="form-label ">Fine Status</label>
                                        <div id="divfine_paid_status">
                                            <select name="fine_paid_status" id="fine_paid_status" class="form-select w-100">
                                                <option value="unpaid">Select Fine Status</option>
                                                <option value="paid">Paid</option>
                                                <option value="waive-off">Waive-off</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3  ">
                                        <label class="form-label">Payment Method</label>
                                        <select name="payment_method" id="payment_method" class="form-select w-100">
                                            <option value="">Select Payment Method</option>
                                            <option value="netbanking">Netbanking</option>
                                            <option value="dc">Debit Card</option>
                                            <option value="upi">UPI</option>
                                            <option value="cash">Cash</option>
                                        </select>
                                    </div>
                                </div>

                                <div class=" mt-4 mb-4 align-items-start">
                                    <button type="button" id="generateLinkBtn" class="btn-outline-primary">
                                        <i class="bi bi-link-45deg me-2"></i>Generate Payment Link
                                    </button>
                                    <div class="flex-grow-1" id="linkResult"></div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="submit-btn " id="returned_btn">
                                        <i class="bi bi-check-circle me-2"></i>Mark as Returned
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="book_issue_id"]');
    const fineSummary = document.getElementById('fineSummary');
    const returnbtn = document.getElementById('returned_btn');
    const fineAmount = document.getElementById('selectedFine');
    const fineBook = document.getElementById('selectedBook');
    const finePaidStatus = document.getElementById('fine_paid_status');
    const divFinePaidStatus = document.getElementById('divfine_paid_status');
    const generateLinkBtn = document.getElementById('generateLinkBtn');
    const linkResult = document.getElementById('linkResult');
    const paymentMethod = document.getElementById('payment_method');
    
    returnbtn.classList.add('d-none');
    let selectedFine = 0;
    let selectedBookId = null;
    let selectedBookTitle = '';

    // Handle book selection
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove selected style from all rows
            document.querySelectorAll('.book-row').forEach(row => {
                row.classList.remove('selected-row');
            });
            
            // Add selected style to current row
            const row = this.closest('tr');
            row.classList.add('selected-row');
            
            returnbtn.classList.remove('d-none');

            // Get return status
            const returnStatusInput = row.querySelector('input[name="return_status"]');
            const returnStatus = returnStatusInput ? returnStatusInput.value.trim().toLowerCase() : 'issued';

            // If already returned, don't open fine box
            if (returnStatus === 'returned') {
                fineSummary.classList.add('d-none');
                returnbtn.classList.add('d-none');
                alert('This book is already returned.');
                return;
            }
            
            selectedFine = parseFloat(this.dataset.fine);
            selectedBookId = this.value;
            selectedBookTitle = this.dataset.title;

            fineBook.textContent = selectedBookTitle;
            fineAmount.textContent = '₹' + selectedFine.toFixed(2);
            fineSummary.classList.remove('d-none');
            linkResult.innerHTML = "";

            // Get payment status
            const paymentStatusInput = row.querySelector('input[name="payment_status"]');
            const paymentStatus = paymentStatusInput ? paymentStatusInput.value.trim().toLowerCase() : 'unpaid';
            
            // Set fine status dropdown
            finePaidStatus.value = paymentStatus;

            // Show/hide generate link based on payment status
            if (paymentStatus === 'unpaid' && selectedFine > 0) {
                divFinePaidStatus.classList.add('d-none');
                generateLinkBtn.classList.remove('d-none');
                paymentMethod.classList.remove('d-none');
            } else {
                divFinePaidStatus.classList.remove('d-none');
            }
            
            // Show/hide generate link based on payment status
            if (paymentStatus === 'paid' || selectedFine <= 0) {
                generateLinkBtn.classList.add('d-none');
                paymentMethod.classList.add('d-none');
            } else {
                generateLinkBtn.classList.remove('d-none');
                paymentMethod.classList.remove('d-none');
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById('bookSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const value = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.book-row');
            
            rows.forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(value)
                    ? ''
                    : 'none';
            });
        });
    }

    // Handle fine status change
    finePaidStatus.addEventListener('change', function() {
        if (finePaidStatus.value === 'paid') {
            generateLinkBtn.classList.add('d-none');
            paymentMethod.classList.add('d-none');
        } else if (finePaidStatus.value === 'unpaid' && selectedFine > 0) {
            generateLinkBtn.classList.remove('d-none');
            paymentMethod.classList.remove('d-none');
        } else {
            generateLinkBtn.classList.add('d-none');
            paymentMethod.classList.add('d-none');
        }

        toggleReturnButton();
    });

    function toggleReturnButton() {
        // If no fine at all → allow return
        if (selectedFine <= 0) {
            returnbtn.classList.remove('d-none');
            return;
        }

        const fineStatus = finePaidStatus.value;
        const method = paymentMethod.value;

        // Allowed cases
        if (fineStatus === 'paid' || fineStatus === 'waive-off' || method === 'cash') {
            returnbtn.classList.remove('d-none');
        } else {
            returnbtn.classList.add('d-none');
        }
    }

    // Handle payment method change
    paymentMethod.addEventListener('change', function() {
        if (paymentMethod.value === 'cash') {
            generateLinkBtn.classList.add('d-none');
        } else {
            generateLinkBtn.classList.remove('d-none');
        }

        toggleReturnButton();
    });

    // Generate Payment Link
    generateLinkBtn.addEventListener('click', function() {
        if (!selectedBookId || selectedFine <= 0) {
            alert("Please select a valid book with fine amount first.");
            return;
        }
        
        generateLinkBtn.disabled = true;
        generateLinkBtn.innerHTML = '<span class="loading-spinner"></span> Generating...';
        
        fetch("{{ route('payment.link.create') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                amount: selectedFine,
                user_transaction_refered_id: selectedBookId,
                payment_type: "library-book-fine",
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                linkResult.innerHTML = `
                    <div class="payment-link-container">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="badge bg-success me-2">✓</span>
                                <strong>Payment Link Generated</strong>
                            </div>
                            <a href="${data.payment_link}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Open Link
                            </a>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted d-block">Share this link with the borrower:</small>
                            <input type="text" class="form-control form-control-sm mt-1" value="${data.payment_link}" readonly onclick="this.select()">
                        </div>
                    </div>
                `;
            } else {
                linkResult.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            }
        })
        .catch(err => {
            linkResult.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle me-2"></i>
                    Error generating payment link
                </div>
            `;
        })
        .finally(() => {
            generateLinkBtn.disabled = false;
            generateLinkBtn.innerHTML = '<i class="bi bi-link-45deg me-2"></i>Generate Payment Link';
        });
    });

    // Sorting Functionality
    function sortTable(column) {
        // Remove active class from all sort icons
        document.querySelectorAll('.sort-icon').forEach(icon => {
            icon.classList.remove('active');
        });
        
        // Get current th element
        const th = event.currentTarget;
        const sortIcons = th.querySelectorAll('.sort-icon');
        
        // Determine sort order
        let sortOrder = 'asc';
        if (sortIcons[0].classList.contains('active')) {
            sortOrder = 'desc';
            sortIcons[0].classList.remove('active');
            sortIcons[1].classList.add('active');
        } else if (sortIcons[1].classList.contains('active')) {
            sortOrder = 'asc';
            sortIcons[1].classList.remove('active');
            sortIcons[0].classList.add('active');
        } else {
            sortIcons[0].classList.add('active');
        }
        
        // Get table and rows
        const tbody = document.querySelector('#issuedBooksContainer');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        // Sort rows
        rows.sort((a, b) => {
            let aValue = getCellValue(a, column);
            let bValue = getCellValue(b, column);
            
            // Convert to appropriate type for comparison
            if (column === 'fine_amount' || column === 'paid_amount' || column === 'due_amount') {
                aValue = parseFloat(aValue.replace(/[^\d.-]/g, '')) || 0;
                bValue = parseFloat(bValue.replace(/[^\d.-]/g, '')) || 0;
                return sortOrder === 'asc' ? aValue - bValue : bValue - aValue;
            } else if (column === 'issue_date' || column === 'due_date') {
                aValue = new Date(aValue);
                bValue = new Date(bValue);
                return sortOrder === 'asc' ? aValue - bValue : bValue - aValue;
            } else if (column === 'days_overdue') {
                aValue = parseInt(aValue) || 0;
                bValue = parseInt(bValue) || 0;
                return sortOrder === 'asc' ? aValue - bValue : bValue - aValue;
            } else {
                // String comparison
                aValue = aValue.toLowerCase();
                bValue = bValue.toLowerCase();
                return sortOrder === 'asc' ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
            }
        });
        
        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
    }

    function getCellValue(row, column) {
        const cells = row.cells;
        
        switch(column) {
            case 'title':
                return cells[1].querySelector('strong').textContent;
            case 'issued_to':
                return cells[2].querySelector('strong').textContent;
            case 'code':
                return cells[3].querySelector('.person-type').textContent;
            case 'issue_date':
                return cells[4].textContent;
            case 'due_date':
                return cells[5].querySelector('span').textContent;
            case 'days_overdue':
                const badge = cells[6].querySelector('.status-badge');
                return badge.textContent.replace(/\D/g, '');
            case 'fine_amount':
                return cells[7].textContent;
            case 'paid_amount':
                return cells[8].textContent;
            case 'due_amount':
                return cells[9].textContent;
            case 'payment_status':
                return cells[10].querySelector('.status-badge').textContent;
            case 'payment_method':
                return cells[11].textContent;
            case 'status':
                return cells[12].querySelector('.status-badge').textContent;
            default:
                return '';
        }
    }
});
</script>
@endsection